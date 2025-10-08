@extends('layouts.vertical-guru', ['subtitle' => 'Scan QR Absensi'])

@section('content')
    {{-- ... (kode HTML tidak berubah) ... --}}
    @include('layouts.partials.page-title', ['title' => 'Scan QR', 'subtitle' => 'Absensi'])

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Pindai QR Code Siswa</h4>
                    <div class="mb-3">
                        <label for="timetable_id" class="form-label">Pilih Jadwal Mata Pelajaran</label>
                        <select class="form-select" id="timetable_id" name="timetable_id">
                            <option value="" selected disabled>-- Pilih Mapel --</option>
                            @forelse ($jadwalHariIni as $jadwal)
                                <option value="{{ $jadwal->id }}">{{ $jadwal->subject->name }} - {{ $jadwal->classroom->name }} ({{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }})</option>
                            @empty
                                <option disabled>Tidak ada jadwal mengajar hari ini.</option>
                            @endforelse
                        </select>
                        <div class="invalid-feedback">Silakan pilih jadwal terlebih dahulu.</div>
                    </div>
                    <div id="reader" width="100%"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Hasil Pindaian Hari Ini</h4>
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-striped table-hover" id="scan-results-table">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="initial-message-row">
                                    <td colspan="5" class="text-center">Silakan pilih mata pelajaran untuk melihat data.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resultTableBody = document.querySelector("#scan-results-table tbody");
            const timetableSelect = document.getElementById('timetable_id');
            let rowCounter = 1;
            let lastScannedData = null;
            let lastScanTime = null;

            // ... (fungsi populateTable dan fetchScanResults tetap sama) ...
            function populateTable(attendances) {
                resultTableBody.innerHTML = '';
                rowCounter = 1;
                if (attendances.length === 0) {
                    resultTableBody.innerHTML = `<tr id="initial-message-row"><td colspan="5" class="text-center">Belum ada pindaian untuk mapel ini.</td></tr>`;
                    return;
                }
                attendances.forEach(absen => {
                    const student = absen.student;
                    const user = student ? student.user : null;
                    if(!user) return;
                    let statusBadge = '';
                    if (absen.check_out_time) {
                        statusBadge = `<span class="badge bg-soft-success text-success">Selesai</span>`;
                    } else if (absen.status === 'T' || absen.notes === 'Terlambat') {
                        statusBadge = `<span class="badge bg-soft-warning text-warning">Terlambat</span>`;
                    } else {
                        statusBadge = `<span class="badge bg-soft-info text-info">Sudah Masuk</span>`;
                    }
                    const newRow = `
                        <tr id="nisn-${student.nis}">
                            <td>${rowCounter++}</td>
                            <td>${user.full_name}</td>
                            <td class="jam-masuk">${absen.check_in_time}</td>
                            <td class="jam-keluar">${absen.check_out_time ?? '-'}</td>
                            <td class="status">${statusBadge}</td>
                        </tr>
                    `;
                    resultTableBody.insertAdjacentHTML('beforeend', newRow);
                });
            }
            function fetchScanResults() {
                const timetableId = timetableSelect.value;
                if (!timetableId) return;
                let url = `{{ route('guru.absensi.results', ['timetable_id' => ':id']) }}`.replace(':id', timetableId);
                fetch(url)
                    .then(response => response.json())
                    .then(data => populateTable(data))
                    .catch(error => console.error('Error fetching scan results:', error));
            }

            // --- FUNGSI BARU UNTUK MEMBERSIHKAN TEKS DARI KODE HTML ---
            function decodeHtmlEntities(text) {
                const textarea = document.createElement('textarea');
                textarea.innerHTML = text;
                return textarea.value;
            }

            // --- FUNGSI onScanSuccessHandler YANG SUDAH DIPERBARUI ---
            function onScanSuccessHandler(decodedText, decodedResult) {
                let nisnToSubmit;

                try {
                    // 1. Bersihkan teks dari &quot; menjadi "
                    const cleanText = decodeHtmlEntities(decodedText);
                    
                    // 2. Parse teks yang sudah bersih sebagai JSON
                    const qrData = JSON.parse(cleanText);
                    
                    nisnToSubmit = qrData.nisn;
                    
                    if (!nisnToSubmit) {
                        alert('Format QR Code tidak valid: Properti "nisn" tidak ditemukan.');
                        return;
                    }
                } catch (e) {
                    nisnToSubmit = decodedText;
                }

                const now = new Date().getTime();
                if (nisnToSubmit === lastScannedData && (now - lastScanTime) < 3000) {
                    return; 
                }
                lastScannedData = nisnToSubmit;
                lastScanTime = now;

                const selectedTimetableId = timetableSelect.value;
                if (!selectedTimetableId || selectedTimetableId === '') {
                    timetableSelect.classList.add('is-invalid');
                    alert('Silakan pilih jadwal mata pelajaran terlebih dahulu.');
                    return;
                }
                timetableSelect.classList.remove('is-invalid');

                fetch("{{ route('guru.absensi.process') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ nisn: nisnToSubmit, timetable_id: selectedTimetableId }) 
                })
                .then(response => response.json().then(data => ({ ok: response.ok, data })))
                .then(({ ok, data }) => {
                    if (!ok) { throw new Error(data.error || 'Terjadi kesalahan.'); }
                    fetchScanResults(); 
                })
                .catch(error => {
                    alert(error.message);
                });
            }
            
            timetableSelect.addEventListener('change', fetchScanResults);

            const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
            html5QrcodeScanner.render(onScanSuccessHandler);
        });
    </script>
@endpush
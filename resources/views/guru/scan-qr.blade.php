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

                    <div id="qrcode" class="d-flex justify-content-center" style="display:none; width:180px; height:180px; margin:0 auto;"></div>
                    <p class="mt-2 text-muted" id="qrInfoText" style="font-size:0.85rem; margin-top:6px;"></p>
                    <button id="stopSession" class="btn btn-danger mt-2" style="display: none;">Hentikan Sesi Absensi</button>
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
    <!-- Use kjua for cleaner SVG QR (fallback to qrcodejs if needed) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/kjua/0.1.1/kjua.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timetableSelect = document.getElementById('timetable_id');
            const qrcodeContainer = document.getElementById('qrcode');
            const qrInfoText = document.getElementById('qrInfoText');
            const scanResultsCard = document.getElementById('scan-results-table');
            const scanCountEl = document.getElementById('scanCount');
            const scanResultsBody = document.getElementById('scan-results-table') ? document.querySelector('#scan-results-table tbody') : null;
            const stopSessionBtn = document.getElementById('stopSession');

            let qrcode = null;
            let scanInterval = null;

            function fetchScanResults(timetableId) {
                fetch(`/scan-qr/results/${timetableId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!scanResultsBody) return;
                        scanResultsBody.innerHTML = '';
                        scanCountEl && (scanCountEl.innerText = data.length);
                        if (data.length === 0) {
                            scanResultsBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada siswa yang melakukan absensi.</td></tr>';
                        } else {
                            data.forEach(r => {
                                const row = `<tr>
                                    <td>${r.no}</td>
                                    <td>${r.student_name}</td>
                                    <td>${r.student_nisn}</td>
                                    <td>${r.check_in_time}</td>
                                    <td>${r.status === 'T' ? '<span class="badge bg-warning">Terlambat</span>' : '<span class="badge bg-success">Hadir</span>'}</td>
                                </tr>`;
                                scanResultsBody.innerHTML += row;
                            });
                        }
                    }).catch(err => console.error(err));
            }

            timetableSelect.addEventListener('change', function () {
                const timetableId = this.value;
                if (!timetableId) return;

                if (scanInterval) clearInterval(scanInterval);

                qrcodeContainer.style.display = 'block';
                stopSessionBtn.style.display = 'inline-block';
                qrcodeContainer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Membuat QR...';

                fetch(`{{ route('guru.absensi.generate-qr') }}?timetable_id=${timetableId}`)
                    .then(r => r.json())
                    .then(data => {
                        // clear previous
                        qrcodeContainer.innerHTML = '';
                        try {
                            // prefer kjua (SVG) for cleaner output
                            if (typeof kjua !== 'undefined') {
                                const svg = kjua({
                                    render: 'svg',
                                    crisp: true,
                                    size: 180,
                                    text: JSON.stringify(data),
                                    fill: '#000000',
                                    back: '#ffffff',
                                    rounded: 0
                                });
                                qrcodeContainer.appendChild(svg);
                            } else if (typeof QRCode !== 'undefined') {
                                // fallback to QRCode.js
                                new QRCode(qrcodeContainer, {
                                    text: JSON.stringify(data),
                                    width: 180,
                                    height: 180,
                                    colorDark: '#000000',
                                    colorLight: '#ffffff',
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            } else {
                                throw new Error('No QR generator available');
                            }
                            const selectedOptionText = timetableSelect.options[timetableSelect.selectedIndex].text;
                            qrInfoText.innerText = `Sesi untuk: ${selectedOptionText}`;
                        } catch (err) {
                            console.error('QR generation error:', err);
                            qrcodeContainer.innerHTML = '<div class="text-danger">Gagal membuat QR Code.</div>';
                            qrInfoText.innerText = '';
                        }
                    }).catch(err => {
                        console.error('Fetch QR data error:', err);
                        qrcodeContainer.innerHTML = '<div class="text-danger">Gagal mengambil data QR.</div>';
                    });

                fetchScanResults(timetableId);
                scanInterval = setInterval(() => fetchScanResults(timetableId), 5000);
            });

            stopSessionBtn && stopSessionBtn.addEventListener('click', function () {
                if (scanInterval) clearInterval(scanInterval);
                this.style.display = 'none';
                qrcodeContainer.style.display = 'none';
                qrInfoText.innerText = '';
                timetableSelect.selectedIndex = 0;
            });
        });
    </script>
@endpush
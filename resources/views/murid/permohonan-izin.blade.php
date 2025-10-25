@extends('layouts.vertical-murid')

@section('title', 'Permohonan Izin')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Permohonan Izin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Permohonan Izin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Permohonan Izin --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-file-plus me-2"></i>
                        Ajukan Permohonan Izin
                    </h4>
                </div>
                <div class="card-body">
                    <form id="permohonanForm" action="{{ route('murid.permohonan-izin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenisIzin" class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jenisIzin" name="jenisIzin" required>
                                        <option value="">Pilih Jenis Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="izin">Izin</option>
                                        <option value="keperluan-keluarga">Keperluan Keluarga</option>
                                        <option value="acara-keluarga">Acara Keluarga</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalMulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" required>
                                </div>
                            </div>
                        </div>

                        {{-- Field untuk jenis izin lainnya dan tanggal selesai --}}
                        <div class="row" id="jenisIzinLainnya" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenisIzinCustom" class="form-label">Jenis Izin Lainnya <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenisIzinCustom" name="jenisIzinCustom" placeholder="Tuliskan jenis izin lainnya...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalSelesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai">
                                </div>
                            </div>
                        </div>

                        {{-- Field tanggal selesai untuk jenis izin selain lainnya --}}
                        <div class="row" id="tanggalSelesaiNormal">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalSelesaiNormal" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalSelesaiNormal" name="tanggalSelesaiNormal" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" placeholder="Jelaskan alasan mengajukan izin..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="dokumenPendukung" class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control" id="dokumenPendukung" name="dokumenPendukung" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG (Maksimal 2MB)</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-send me-1"></i>
                                Ajukan Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Informasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Ketentuan Izin:</h6>
                        <ul class="mb-0 small">
                            <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                            <li>Untuk izin sakit, lampirkan surat dokter</li>
                            <li>Izin akan diproses dalam 1-2 hari kerja</li>
                            <li>Status dapat dicek di riwayat permohonan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-history me-2"></i>
                        Riwayat Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentRequests ?? collect() as $request)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $request->leave_type_display }}</h6>
                                    <small class="text-muted">{{ $request->created_at->format('d M Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $request->status_badge }}">
                                    @switch($request->status)
                                        @case('pending')
                                            Menunggu
                                            @break
                                        @case('approved')
                                            Disetujui
                                            @break
                                        @case('rejected')
                                            Ditolak
                                            @break
                                        @default
                                            {{ ucfirst($request->status) }}
                                    @endswitch
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                <small>Belum ada permohonan izin</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Permohonan --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-list-ul me-2"></i>
                        Riwayat Permohonan Izin
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Izin</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <span class="badge bg-danger">Sakit</span>
                                    </td>
                                    <td>15 Okt 2024</td>
                                    <td>1 hari</td>
                                    <td>
                                        <span class="badge bg-success">Disetujui</span>
                                    </td>
                                    <td>14 Okt 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <span class="badge bg-info">Keperluan Keluarga</span>
                                    </td>
                                    <td>10-12 Okt 2024</td>
                                    <td>3 hari</td>
                                    <td>
                                        <span class="badge bg-warning">Menunggu</span>
                                    </td>
                                    <td>9 Okt 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <span class="badge bg-primary">Acara Keluarga</span>
                                    </td>
                                    <td>5 Okt 2024</td>
                                    <td>1 hari</td>
                                    <td>
                                        <span class="badge bg-success">Disetujui</span>
                                    </td>
                                    <td>4 Okt 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <span class="badge bg-secondary">Izin</span>
                                    </td>
                                    <td>1 Okt 2024</td>
                                    <td>1 hari</td>
                                    <td>
                                        <span class="badge bg-danger">Ditolak</span>
                                    </td>
                                    <td>30 Sep 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('permohonanForm');
    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesai = document.getElementById('tanggalSelesai');
    const tanggalSelesaiNormal = document.getElementById('tanggalSelesaiNormal');
    const jenisIzin = document.getElementById('jenisIzin');
    const jenisIzinLainnya = document.getElementById('jenisIzinLainnya');
    const jenisIzinCustom = document.getElementById('jenisIzinCustom');
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalMulai.value = today;
    tanggalMulai.min = today;
    tanggalSelesai.min = today;
    tanggalSelesaiNormal.min = today;
    
    // Set default end date to today as well
    tanggalSelesaiNormal.value = today;

    // Function to show notification modal
    function showNotification(message, isSuccess = true) {
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        notificationModal.show();
    }

    // Show/hide custom jenis izin field
    jenisIzin.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            jenisIzinLainnya.style.display = 'block';
            document.getElementById('tanggalSelesaiNormal').style.display = 'none';
            jenisIzinCustom.required = true;
            tanggalSelesai.required = true;
            tanggalSelesaiNormal.required = false;
        } else {
            jenisIzinLainnya.style.display = 'none';
            document.getElementById('tanggalSelesaiNormal').style.display = 'block';
            jenisIzinCustom.required = false;
            jenisIzinCustom.value = '';
            tanggalSelesai.required = false;
            tanggalSelesaiNormal.required = true;
        }
    });

    // Update end date when start date changes
    function updateEndDate() {
        const startDate = tanggalMulai.value;
        tanggalSelesai.min = startDate;
        tanggalSelesaiNormal.min = startDate;
        
        if (tanggalSelesai.value && tanggalSelesai.value < startDate) {
            tanggalSelesai.value = startDate;
        }
        if (tanggalSelesaiNormal.value && tanggalSelesaiNormal.value < startDate) {
            tanggalSelesaiNormal.value = startDate;
        }
    }

    tanggalMulai.addEventListener('change', updateEndDate);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submitted'); // Debug log
        
        // Validate custom jenis izin if "lainnya" is selected
        if (jenisIzin.value === 'lainnya' && !jenisIzinCustom.value.trim()) {
            showNotification('Harap isi jenis izin lainnya.', false);
            return;
        }
        
        // Validate end date based on leave type
        let endDateValue = '';
        if (jenisIzin.value === 'lainnya') {
            endDateValue = tanggalSelesai.value;
            if (!endDateValue) {
                showNotification('Harap isi tanggal selesai.', false);
                return;
            }
        } else {
            endDateValue = tanggalSelesaiNormal.value;
            if (!endDateValue) {
                showNotification('Harap isi tanggal selesai.', false);
                return;
            }
        }
        
        console.log('Selected end date value:', endDateValue);
        
        // Show loading
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengirim...';
        submitButton.disabled = true;
        
        // Prepare form data
        const formData = new FormData(form);
        
        // Debug: Log form data
        console.log('Form action:', form.action);
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        // Debug: Log specific date values
        console.log('Jenis izin:', jenisIzin.value);
        console.log('Tanggal mulai:', tanggalMulai.value);
        console.log('Tanggal selesai (lainnya):', tanggalSelesai.value);
        console.log('Tanggal selesai (normal):', tanggalSelesaiNormal.value);
        console.log('Jenis izin custom:', jenisIzinCustom.value);
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            
            if (data.success) {
                showNotification(data.message, true);
                form.reset();
                // Reset default date to today
                tanggalMulai.value = today;
                tanggalSelesaiNormal.value = today;
                // Hide custom field and show normal field
                jenisIzinLainnya.style.display = 'none';
                document.getElementById('tanggalSelesaiNormal').style.display = 'block';
                jenisIzinCustom.required = false;
                // Reload page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat mengajukan permohonan.', false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            showNotification('Terjadi kesalahan saat mengirim permohonan.', false);
        });
    });
});

function resetForm() {
    const form = document.getElementById('permohonanForm');
    const jenisIzinLainnya = document.getElementById('jenisIzinLainnya');
    const jenisIzinCustom = document.getElementById('jenisIzinCustom');
    const tanggalMulai = document.getElementById('tanggalMulai');
    
    form.reset();
    
    // Reset default date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalMulai.value = today;
    tanggalSelesaiNormal.value = today;
    
    // Hide custom field and show normal field
    jenisIzinLainnya.style.display = 'none';
    document.getElementById('tanggalSelesaiNormal').style.display = 'block';
    jenisIzinCustom.required = false;
}
</script>
@endpush

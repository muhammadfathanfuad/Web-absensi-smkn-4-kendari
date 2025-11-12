@extends('layouts.vertical-guru')

@section('title', 'Permohonan Izin')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Permohonan Izin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Guru</li>
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
                    <form id="permohonanForm" action="{{ route('guru.permohonan-izin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_date" class="form-label">Tanggal Mulai Izin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="leave_date" name="leave_date" min="{{ date('Y-m-d') }}" required>
                                    <small class="form-text text-muted">Pilih tanggal mulai izin</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Akhir Izin <span class="text-muted">(Opsional)</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" min="{{ date('Y-m-d') }}">
                                    <small class="form-text text-muted">Kosongkan jika izin hanya satu hari</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div id="date_range_info" class="alert alert-info" style="display: none;">
                                        <i class="bx bx-info-circle"></i> <span id="date_range_text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Jadwal Mengajar <span class="text-danger">*</span></label>
                                    <div id="timetables_loading" class="text-center py-3" style="display: none;">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2">Mencari jadwal mengajar...</span>
                                    </div>
                                    <div id="timetables_list" class="border rounded p-3" style="display: none;">
                                        <p class="text-muted mb-2">Pilih jadwal yang akan diizinkan:</p>
                                        <div id="timetables_checkboxes"></div>
                                        <div id="no_timetables" class="alert alert-warning" style="display: none;">
                                            <i class="bx bx-info-circle"></i> Tidak ada jadwal mengajar dalam periode yang dipilih.
                                        </div>
                                    </div>
                                    <div id="timetables_placeholder" class="alert alert-info">
                                        <i class="bx bx-info-circle"></i> Pilih tanggal mulai dan akhir izin terlebih dahulu untuk melihat jadwal mengajar.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="leave_type" name="leave_type" required>
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
                                <div class="mb-3" id="custom_leave_type_wrapper" style="display: none;">
                                    <label for="custom_leave_type" class="form-label">Jenis Izin Lainnya <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="custom_leave_type" name="custom_leave_type" placeholder="Tuliskan jenis izin lainnya...">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Jelaskan alasan mengajukan izin..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="dokumenPendukung" class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control" id="dokumenPendukung" name="dokumenPendukung" accept=".pdf,.jpg,.jpeg,.png" data-max-size="512000">
                            <small class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG (Maksimal 500KB)</small>
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
                            <li>Admin akan menugaskan pengganti</li>
                            <li>Status dapat dicek di riwayat permohonan</li>
                        </ul>
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
                                    <th>Kelas</th>
                                    <th>Tanggal Izin</th>
                                    <th>Jenis Izin</th>
                                    <th>Status</th>
                                    <th>Pengganti</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveRequests as $i => $request)
                                    @php
                                        // Get all timetables for this request
                                        $allTimetables = collect();
                                        if ($request->timetables && $request->timetables->count() > 0) {
                                            // Use timetables from pivot table (new multi-timetable requests)
                                            foreach ($request->timetables as $timetablePivot) {
                                                if ($timetablePivot->timetable) {
                                                    $allTimetables->push($timetablePivot->timetable);
                                                }
                                            }
                                        } else {
                                            // Fallback to single timetable (backward compatibility)
                                            if ($request->timetable) {
                                                $allTimetables->push($request->timetable);
                                            }
                                        }
                                        
                                        // Get unique classes
                                        $uniqueClasses = $allTimetables->map(function($timetable) {
                                            return $timetable->classSubject->class->name;
                                        })->unique()->values();
                                        $totalClasses = $uniqueClasses->count();
                                    @endphp
                                    @if($totalClasses > 0)
                                        @foreach($uniqueClasses as $idx => $className)
                                    <tr>
                                                @if($idx === 0)
                                                    <td rowspan="{{ $totalClasses }}">{{ $i + 1 }}</td>
                                                @endif
                                        <td>
                                                    <strong>{{ $className }}</strong>
                                                </td>
                                                @if($idx === 0)
                                                    <td rowspan="{{ $totalClasses }}">
                                                        @if($request->end_date && $request->end_date != $request->leave_date)
                                                            {{ $request->leave_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}
                                                            <br><small class="text-muted">({{ $request->leave_date->diffInDays($request->end_date) + 1 }} hari)</small>
                                                        @else
                                                            {{ $request->leave_date->format('d M Y') }}
                                                        @endif
                                        </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                            <span class="badge bg-{{ $request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary')) }}">
                                                {{ $request->leave_type_display }}
                                            </span>
                                        </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                @if($request->status === 'pending')
                                                    Menunggu
                                                @elseif($request->status === 'approved')
                                                    Disetujui
                                                @elseif($request->status === 'rejected')
                                                    Ditolak
                                                @else
                                                    {{ ucfirst($request->status) }}
                                                @endif
                                            </span>
                                        </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                            @if($request->substitute)
                                                {{ $request->substitute->full_name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                                    <td rowspan="{{ $totalClasses }}">{{ $request->created_at->format('d M Y') }}</td>
                                                    <td rowspan="{{ $totalClasses }}">
                                            <button class="btn btn-sm btn-outline-primary" onclick="showDetailModal({{ $request->id }})">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                        </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td colspan="7" class="text-center text-muted">Data jadwal tidak ditemukan</td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Permohonan -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="bx bx-detail me-2"></i>Detail Permohonan Izin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Notifikasi --}}
    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification modal
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

    // Ensure close buttons work
    document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
        notificationModal.hide();
    });
    document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
        notificationModal.hide();
    });

    // Make showNotification available globally
    let modalHiddenHandler = null;
    let shouldReload = false; // Flag to control reload
    let isNotificationShowing = false; // Flag to prevent multiple notifications
    
    window.showNotification = function(message, isSuccess = true, reloadAfter = false) {
        // Prevent multiple notifications from showing at the same time
        if (isNotificationShowing) {
            return;
        }
        
        isNotificationShowing = true;
        
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        
        // Set reload flag
        shouldReload = reloadAfter;
        
        // For error cases, reset button immediately when modal is shown
        if (!isSuccess) {
            const form = document.getElementById('permohonanForm');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    const originalText = submitBtn.getAttribute('data-original-text');
                    if (originalText) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    } else {
                        // Fallback if data-original-text is not set
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Ajukan Permohonan';
                    }
                }
            }
        }
        
        // Remove previous handler if exists
        const modalElement = document.getElementById('notificationModal');
        if (modalHiddenHandler) {
            modalElement.removeEventListener('hidden.bs.modal', modalHiddenHandler);
        }
        
        // Add new handler to ensure button is reset when modal is closed (for error cases that weren't reset)
        modalHiddenHandler = function() {
            // Reset notification flag
            isNotificationShowing = false;
            
            // Find submit button and reset it if still in loading state (fallback for error cases)
            const form = document.getElementById('permohonanForm');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    // Only reset if button is still disabled (error case fallback)
                    const originalText = submitBtn.getAttribute('data-original-text');
                    if (originalText) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    } else {
                        // Fallback if data-original-text is not set
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Ajukan Permohonan';
                    }
                }
            }
            
            // Reload only if success and reloadAfter is true
            if (shouldReload && isSuccess) {
                // Reset submitting flag before reload
                if (typeof isSubmitting !== 'undefined') {
                    isSubmitting = false;
                }
                location.reload();
            }
        };
        modalElement.addEventListener('hidden.bs.modal', modalHiddenHandler, { once: true });
        
        notificationModal.show();
    };

    const form = document.getElementById('permohonanForm');
    const leaveType = document.getElementById('leave_type');
    const customLeaveTypeWrapper = document.getElementById('custom_leave_type_wrapper');
    const customLeaveType = document.getElementById('custom_leave_type');
    const leaveDate = document.getElementById('leave_date');
    const endDate = document.getElementById('end_date');
    const dateRangeInfo = document.getElementById('date_range_info');
    const dateRangeText = document.getElementById('date_range_text');
    
    // Flag to prevent double submission
    let isSubmitting = false;
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    leaveDate.min = today;
    endDate.min = today;
    
    // Update end_date min when leave_date changes
    leaveDate.addEventListener('change', function() {
        if (this.value) {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) {
                endDate.value = '';
            }
            updateDateRangeInfo();
            fetchTimetables();
        }
    });
    
    // Update date range info when end_date changes
    endDate.addEventListener('change', function() {
        updateDateRangeInfo();
        fetchTimetables();
    });
    
    // Function to fetch timetables based on date range
    function fetchTimetables() {
        const startDate = leaveDate.value;
        const endDateValue = endDate.value || startDate;
        
        if (!startDate) {
            document.getElementById('timetables_placeholder').style.display = 'block';
            document.getElementById('timetables_list').style.display = 'none';
            document.getElementById('timetables_loading').style.display = 'none';
            return;
        }
        
        if (endDateValue && endDateValue < startDate) {
            return;
        }
        
        // Show loading
        document.getElementById('timetables_placeholder').style.display = 'none';
        document.getElementById('timetables_list').style.display = 'none';
        document.getElementById('timetables_loading').style.display = 'block';
        
        // Fetch timetables
        fetch('{{ route("guru.permohonan-izin.get-timetables") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDateValue || null
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('timetables_loading').style.display = 'none';
            
            if (data.success && data.timetables && data.timetables.length > 0) {
                displayTimetables(data.timetables);
            } else {
                document.getElementById('timetables_list').style.display = 'block';
                document.getElementById('timetables_checkboxes').innerHTML = '';
                document.getElementById('no_timetables').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('timetables_loading').style.display = 'none';
            document.getElementById('timetables_placeholder').style.display = 'block';
            document.getElementById('timetables_placeholder').className = 'alert alert-danger';
            document.getElementById('timetables_placeholder').innerHTML = '<i class="bx bx-error-circle"></i> Terjadi kesalahan saat memuat jadwal mengajar.';
        });
    }
    
    // Function to display timetables
    function displayTimetables(timetables) {
        const checkboxesContainer = document.getElementById('timetables_checkboxes');
        checkboxesContainer.innerHTML = '';
        
        // Group by date first
        const groupedByDate = {};
        timetables.forEach(timetable => {
            if (!groupedByDate[timetable.date]) {
                groupedByDate[timetable.date] = [];
            }
            groupedByDate[timetable.date].push(timetable);
        });
        
        // Display grouped by date
        Object.keys(groupedByDate).sort().forEach(date => {
            const dateTimetables = groupedByDate[date];
            const dateFormatted = formatDate(date);
            
            // Group by class within the same date
            const groupedByClass = {};
            dateTimetables.forEach(timetable => {
                const classKey = timetable.class;
                if (!groupedByClass[classKey]) {
                    groupedByClass[classKey] = [];
                }
                groupedByClass[classKey].push(timetable);
            });
            
            const dateGroup = document.createElement('div');
            dateGroup.className = 'mb-3';
            dateGroup.innerHTML = `<h6 class="mb-2"><strong>${dateFormatted} (${dateTimetables[0].day_name})</strong></h6>`;
            
            // Display grouped by class
            Object.keys(groupedByClass).forEach(classKey => {
                const classTimetables = groupedByClass[classKey];
                
                // Find earliest start time and latest end time
                let earliestStart = classTimetables[0].start_time;
                let latestEnd = classTimetables[0].end_time;
                
                classTimetables.forEach(timetable => {
                    if (timetable.start_time < earliestStart) {
                        earliestStart = timetable.start_time;
                    }
                    if (timetable.end_time > latestEnd) {
                        latestEnd = timetable.end_time;
                    }
                });
                
                // Get unique subjects
                const subjects = [...new Set(classTimetables.map(t => t.subject))];
                const subjectText = subjects.length > 1 ? subjects.join(', ') : subjects[0];
                
                // Build label text
                let labelText = `${subjectText} - ${classKey} (${earliestStart} - ${latestEnd})`;
                
                // Add group type if all have the same group type
                const groupTypes = [...new Set(classTimetables.map(t => t.group_type).filter(Boolean))];
                if (groupTypes.length === 1) {
                    labelText += ` - Kelompok ${groupTypes[0]}`;
                } else if (groupTypes.length > 1) {
                    labelText += ` - Kelompok ${groupTypes.join(', ')}`;
                }
                
                // Add location type if all have the same location type
                const locationTypes = [...new Set(classTimetables.map(t => t.location_type).filter(Boolean))];
                if (locationTypes.length === 1) {
                    labelText += ` - ${locationTypes[0] === 'lab' ? 'Lab' : 'Teori'}`;
                } else if (locationTypes.length > 1) {
                    const locationText = locationTypes.map(lt => lt === 'lab' ? 'Lab' : 'Teori').join(', ');
                    labelText += ` - ${locationText}`;
                }
                
                // Add week alternation if all have the same
                const weekAlternations = [...new Set(classTimetables.map(t => t.week_alternation).filter(Boolean))];
                if (weekAlternations.length === 1) {
                    labelText += ` - Minggu ${weekAlternations[0] === 'ganjil' ? 'Ganjil' : 'Genap'}`;
                }
                
                // Create checkbox with all timetable_ids
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'form-check mb-2';
                
                // Create value with all timetable_ids (comma separated)
                const valueIds = classTimetables.map(t => `${t.id}_${t.date}`).join(',');
                const safeId = `class_${classKey.replace(/[^a-zA-Z0-9]/g, '_')}_${date.replace(/[^a-zA-Z0-9]/g, '_')}`;
                
                checkboxDiv.innerHTML = `
                    <input class="form-check-input" type="checkbox" 
                           name="timetable_ids[]" 
                           id="timetable_${safeId}" 
                           value="${valueIds}" 
                           data-timetable-ids="${valueIds}"
                           required>
                    <label class="form-check-label" for="timetable_${safeId}">
                        ${labelText}
                    </label>
                `;
                
                dateGroup.appendChild(checkboxDiv);
            });
            
            checkboxesContainer.appendChild(dateGroup);
        });
        
        document.getElementById('timetables_list').style.display = 'block';
        document.getElementById('no_timetables').style.display = 'none';
    }
    
    // Function to update date range info
    function updateDateRangeInfo() {
        const startDate = leaveDate.value;
        const endDateValue = endDate.value;
        
        if (startDate && endDateValue) {
            if (endDateValue < startDate) {
                dateRangeInfo.className = 'alert alert-danger';
                dateRangeText.textContent = 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai';
                dateRangeInfo.style.display = 'block';
            } else {
                const start = new Date(startDate);
                const end = new Date(endDateValue);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                dateRangeInfo.className = 'alert alert-info';
                dateRangeText.textContent = `Izin selama ${diffDays} hari (${formatDate(startDate)} - ${formatDate(endDateValue)})`;
                dateRangeInfo.style.display = 'block';
            }
        } else if (startDate) {
            dateRangeInfo.className = 'alert alert-info';
            dateRangeText.textContent = `Izin 1 hari pada ${formatDate(startDate)}`;
            dateRangeInfo.style.display = 'block';
        } else {
            dateRangeInfo.style.display = 'none';
        }
    }
    
    // Function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.toLocaleString('id-ID', { month: 'long' });
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }
    
    // Show/hide custom leave type field
    leaveType.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            customLeaveTypeWrapper.style.display = 'block';
            customLeaveType.required = true;
        } else {
            customLeaveTypeWrapper.style.display = 'none';
            customLeaveType.required = false;
            customLeaveType.value = '';
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        if (isSubmitting) {
            return;
        }
        
        // Validate custom leave type if "lainnya" is selected
        if (leaveType.value === 'lainnya' && !customLeaveType.value.trim()) {
            alert('Harap isi jenis izin lainnya.');
            return;
        }
        
        // Validate end_date if provided
        if (endDate.value && endDate.value < leaveDate.value) {
            alert('Tanggal akhir izin harus lebih besar atau sama dengan tanggal mulai izin.');
            return;
        }
        
        // Validate at least one timetable is selected
        const selectedTimetables = form.querySelectorAll('input[name="timetable_ids[]"]:checked');
        if (selectedTimetables.length === 0) {
            alert('Harap pilih minimal satu jadwal mengajar.');
            return;
        }
        
        // Validate file size (500KB = 512000 bytes)
        const fileInput = document.getElementById('dokumenPendukung');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 512000; // 500KB in bytes
            if (file.size > maxSize) {
                alert('Ukuran file dokumen pendukung maksimal 500KB.');
                return;
            }
        }
        
        // Set submitting flag
        isSubmitting = true;
        
        // Process timetable_ids - split comma-separated values
        const allTimetableIds = [];
        selectedTimetables.forEach(checkbox => {
            const value = checkbox.value;
            // Split by comma if multiple
            if (value.includes(',')) {
                const ids = value.split(',');
                ids.forEach(id => {
                    if (id.trim()) {
                        allTimetableIds.push(id.trim());
                    }
                });
            } else {
                allTimetableIds.push(value);
            }
        });
        
        const formData = new FormData(this);
        
        // Remove old timetable_ids and add processed ones
        formData.delete('timetable_ids[]');
        allTimetableIds.forEach(id => {
            formData.append('timetable_ids[]', id);
        });
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Store original text as data attribute for recovery
        submitBtn.setAttribute('data-original-text', originalText);
        
        // Disable button to prevent double submission (no loading spinner)
        submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            let data;
            try {
                data = await response.json();
            } catch (e) {
                // If response is not JSON, treat as error
                throw { status: response.status, message: 'Invalid response format' };
            }
            
            // Check if data has success property first (regardless of response status)
            // This handles cases where data is saved but response status is not 200
            const isSuccess = data.success === true || data.success === 'true';
            
            if (isSuccess) {
                // Data successfully saved - reset button immediately
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                const successMessage = data.message || 'Permohonan izin berhasil diajukan!';
                showNotification(successMessage, true, true); // Pass true to reload after modal closes
                // Don't reset isSubmitting here - let reload handle it
                return; // Exit early to prevent further processing
            }
            
            // If not success, check response status
            if (response.ok) {
                // Response OK but success is false or not present
                let errorMessage = data.error || data.message || 'Terjadi kesalahan saat mengajukan permohonan izin.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }
                // Reset submitting flag for error case
                isSubmitting = false;
                // Reset button immediately for error case
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                // Show notification
                showNotification(errorMessage, false);
            } else {
                // Response not OK (status 400+)
                let errorMessage = data.error || data.message || 'Terjadi kesalahan saat mengajukan permohonan izin.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }
                // Reset submitting flag for error case
                isSubmitting = false;
                // Reset button immediately for error case
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                // Show notification
                showNotification(errorMessage, false);
            }
        })
        .catch(error => {
            // Reset submitting flag on error
            isSubmitting = false;
            
            // Reset button immediately for error case
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            console.error('Error:', error);
            let errorMessage = 'Terjadi kesalahan saat mengajukan permohonan izin.';
            if (error.data) {
                if (error.data.error) {
                errorMessage = error.data.error;
                } else if (error.data.errors) {
                const errors = Object.values(error.data.errors).flat();
                errorMessage = errors.join('\n');
                } else if (error.data.message) {
                    errorMessage = error.data.message;
                }
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification(errorMessage, false);
        })
        .finally(() => {
            // Button is already reset in error handlers above
            // This block is kept for any additional cleanup if needed
        });
    });
});

// Reset form
function resetForm() {
    document.getElementById('permohonanForm').reset();
    document.getElementById('custom_leave_type_wrapper').style.display = 'none';
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('leave_date').min = today;
}


// Show detail modal
function showDetailModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const modalBody = document.getElementById('detailModalBody');
    
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    fetch('{{ route("guru.permohonan-izin.show", ":id") }}'.replace(':id', id))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.data;
                const days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                const leaveDate = new Date(request.leave_date);
                let formattedDate = leaveDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                
                if (request.end_date && request.end_date !== request.leave_date) {
                    const endDate = new Date(request.end_date);
                    const endDateFormatted = endDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                    const diffDays = Math.ceil((endDate - leaveDate) / (1000 * 60 * 60 * 24)) + 1;
                    formattedDate = `${formattedDate} - ${endDateFormatted} (${diffDays} hari)`;
                }
                
                // Build jadwal HTML
                let jadwalHTML = '';
                if (request.timetables && request.timetables.length > 0) {
                    // Use grouped timetables - display in 4 columns
                    jadwalHTML = '<div class="row">';
                    request.timetables.forEach(timetable => {
                        const dayName = days[timetable.day_of_week] || `Hari ${timetable.day_of_week}`;
                        const subjects = timetable.subjects.join(', ');
                        const startTime = new Date('2000-01-01T' + timetable.start_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                        const endTime = new Date('2000-01-01T' + timetable.end_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                        
                        jadwalHTML += `
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="p-3 border rounded h-100">
                                    <strong>${dayName}</strong><br>
                                    <strong>${timetable.class_name}</strong><br>
                                    <small class="text-muted">${subjects}</small><br>
                                    <small class="text-muted">${startTime} - ${endTime}</small>
                                </div>
                            </div>
                        `;
                    });
                    jadwalHTML += '</div>';
                } else if (request.timetable) {
                    // Fallback to single timetable (backward compatibility)
                const dayName = days[request.timetable.day_of_week] || request.timetable.day_of_week;
                    const startTime = new Date('2000-01-01T' + request.timetable.start_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                    const endTime = new Date('2000-01-01T' + request.timetable.end_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                    
                    jadwalHTML = `
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="p-3 border rounded h-100">
                                    <strong>${dayName}</strong><br>
                                    <strong>${request.timetable.class_subject.class.name}</strong><br>
                                    <small class="text-muted">${request.timetable.class_subject.subject.name}</small><br>
                                    <small class="text-muted">${startTime} - ${endTime}</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Tanggal Izin:</strong><br>${formattedDate}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>Jadwal:</strong></p>
                            ${jadwalHTML || '<p class="text-muted">Tidak ada jadwal</p>'}
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jenis Izin:</strong><br>
                            <span class="badge bg-${request.leave_type === 'sakit' ? 'danger' : (request.leave_type === 'izin' ? 'secondary' : (request.leave_type === 'keperluan-keluarga' ? 'info' : 'primary'))}">
                                ${request.leave_type_display || request.leave_type}
                            </span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong><br>
                            <span class="badge bg-${request.status === 'approved' ? 'success' : (request.status === 'rejected' ? 'danger' : 'warning')}">
                                ${request.status === 'pending' ? 'Menunggu' : (request.status === 'approved' ? 'Disetujui' : 'Ditolak')}
                            </span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Alasan:</strong><br>${request.reason || '-'}</p>
                        </div>
                        ${request.substitute ? `
                        <div class="col-12">
                            <p><strong>Pengganti:</strong><br>${request.substitute.full_name}</p>
                        </div>
                        ` : ''}
                        ${request.admin_notes ? `
                        <div class="col-12">
                            <p><strong>Catatan Admin:</strong><br>${request.admin_notes}</p>
                        </div>
                        ` : ''}
                        ${request.supporting_document ? `
                        <div class="col-12">
                            <p><strong>Dokumen Pendukung:</strong><br>
                            <a href="${request.document_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-file"></i> Lihat Dokumen
                            </a></p>
                        </div>
                        ` : ''}
                        <div class="col-12">
                            <p><strong>Tanggal Ajukan:</strong><br>${new Date(request.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat data.</p>';
        });
}
</script>
@endpush
@endsection


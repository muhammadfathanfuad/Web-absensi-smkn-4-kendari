@extends('layouts.vertical-admin', ['subtitle' => 'Manajemen Delegasi'])

@section('css')
    @vite(['resources/css/admin/delegasi.css'])
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Manajemen Tugas Pengganti'])

{{-- Tab Navigation --}}
<ul class="nav nav-tabs mb-3" id="delegasiTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="delegasi-tab" data-bs-toggle="tab" data-bs-target="#delegasi" type="button" role="tab" aria-controls="delegasi" aria-selected="true">
            <i class="bx bx-transfer"></i> Delegasi Absensi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="permohonan-izin-tab" data-bs-toggle="tab" data-bs-target="#permohonan-izin" type="button" role="tab" aria-controls="permohonan-izin" aria-selected="false">
            <i class="bx bx-file"></i> Permohonan Izin Guru
        </button>
    </li>
</ul>

{{-- Tabs --}}
<div class="tab-content">
    {{-- Tab Delegasi --}}
    <div class="tab-pane fade show active" id="delegasi" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="delegasi-header-controls">
                            <h5 class="card-title mb-0 delegasi-title">📋 Manajemen Delegasi Absensi</h5>
                            <div class="delegasi-actions-wrapper">
                                <button type="button" class="btn btn-primary" id="tambahDelegasiBtn" onclick="bukaModalTambahDelegasi()">
                                    <i class="bx bx-plus"></i> Tambah Pengganti Absensi
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jadwal</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Guru Asli</th>
                                <th>Delegasi Kepada</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="delegasiTableBody">
                            @php
                                // Group delegations by class_subject_id and day_of_week (like attendance system)
                                $groupedDelegations = [];
                                $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                
                                foreach ($delegations as $delegasi) {
                                    if (!is_object($delegasi) || !isset($delegasi->id) || !$delegasi->timetable) {
                                        continue;
                                    }
                                    
                                    $timetable = $delegasi->timetable;
                                    $dayOfWeek = $timetable->day_of_week ?? null;
                                    $classSubjectId = ($timetable->classSubject && is_object($timetable->classSubject)) 
                                        ? $timetable->classSubject->id 
                                        : null;
                                    
                                    // Create unique key based on class_subject_id and day_of_week (like attendance system)
                                    $key = $classSubjectId . '_' . $dayOfWeek;
                                    
                                    if (!isset($groupedDelegations[$key])) {
                                        $classSubject = $timetable->classSubject;
                                        $groupedDelegations[$key] = [
                                            'day_of_week' => $dayOfWeek,
                                            'class_subject_id' => $classSubjectId,
                                            'class_name' => ($classSubject && is_object($classSubject) && $classSubject->class) 
                                                ? $classSubject->class->name 
                                                : 'N/A',
                                            'subject_name' => ($classSubject && is_object($classSubject) && $classSubject->subject) 
                                                ? $classSubject->subject->name 
                                                : 'N/A',
                                            'original_teacher' => ($delegasi->originalTeacher && is_object($delegasi->originalTeacher) && $delegasi->originalTeacher->user && is_object($delegasi->originalTeacher->user)) 
                                                ? $delegasi->originalTeacher->user->full_name 
                                                : 'N/A',
                                            'start_times' => [],
                                            'end_times' => [],
                                            'delegations' => []
                                        ];
                                    }
                                    
                                    // Add start and end times
                                    if ($timetable->start_time) {
                                        $groupedDelegations[$key]['start_times'][] = $timetable->start_time;
                                    }
                                    if ($timetable->end_time) {
                                        $groupedDelegations[$key]['end_times'][] = $timetable->end_time;
                                    }
                                    
                                    // Add delegation to group
                                    $groupedDelegations[$key]['delegations'][] = $delegasi;
                                }
                                
                                // Process grouped delegations to find earliest start and latest end
                                foreach ($groupedDelegations as $key => &$group) {
                                    if (!empty($group['start_times'])) {
                                        // Convert times to comparable format and find earliest
                                        $earliestStart = null;
                                        foreach ($group['start_times'] as $time) {
                                            if ($time) {
                                                $timeObj = \Carbon\Carbon::parse($time);
                                                if ($earliestStart === null || $timeObj->lt(\Carbon\Carbon::parse($earliestStart))) {
                                                    $earliestStart = $time;
                                                }
                                            }
                                        }
                                        $group['earliest_start'] = $earliestStart;
                                    } else {
                                        $group['earliest_start'] = null;
                                    }
                                    
                                    if (!empty($group['end_times'])) {
                                        // Convert times to comparable format and find latest
                                        $latestEnd = null;
                                        foreach ($group['end_times'] as $time) {
                                            if ($time) {
                                                $timeObj = \Carbon\Carbon::parse($time);
                                                if ($latestEnd === null || $timeObj->gt(\Carbon\Carbon::parse($latestEnd))) {
                                                    $latestEnd = $time;
                                                }
                                            }
                                        }
                                        $group['latest_end'] = $latestEnd;
                                    } else {
                                        $group['latest_end'] = null;
                                    }
                                }
                                unset($group);
                                
                                $rowIndex = 0;
                            @endphp
                            
                            @forelse($groupedDelegations as $key => $group)
                                @php
                                    $dayName = isset($dayNames[$group['day_of_week']]) ? $dayNames[$group['day_of_week']] : 'N/A';
                                    $timeDisplay = 'N/A';
                                    if ($group['earliest_start']) {
                                        $timeDisplay = \Carbon\Carbon::parse($group['earliest_start'])->format('H:i');
                                    }
                                    
                                    // Group delegations by delegated_to_user_id, type, and status within this class_subject group
                                    $delegationsByUserTypeStatus = [];
                                    foreach ($group['delegations'] as $delegasi) {
                                        $delegatedUserId = ($delegasi->delegatedTo && is_object($delegasi->delegatedTo)) 
                                            ? $delegasi->delegatedTo->id 
                                            : 'N/A';
                                        $type = $delegasi->type ?? 'N/A';
                                        $status = $delegasi->status ?? 'N/A';
                                        
                                        // Create unique key based on user, type, and status
                                        $userTypeStatusKey = $delegatedUserId . '_' . $type . '_' . $status;
                                        
                                        if (!isset($delegationsByUserTypeStatus[$userTypeStatusKey])) {
                                            $delegationsByUserTypeStatus[$userTypeStatusKey] = [
                                                'user_id' => $delegatedUserId,
                                                'user_name' => ($delegasi->delegatedTo && is_object($delegasi->delegatedTo)) 
                                                    ? $delegasi->delegatedTo->full_name 
                                                    : 'N/A',
                                                'type' => $type,
                                                'status' => $status,
                                                'delegations' => []
                                            ];
                                        }
                                        $delegationsByUserTypeStatus[$userTypeStatusKey]['delegations'][] = $delegasi;
                                    }
                                    
                                    $totalRowCount = count($group['delegations']);
                                    $isFirstRow = true;
                                    
                                    // Check if all delegations are in one group (all have same user, type, status)
                                    $isAllMerged = (count($delegationsByUserTypeStatus) == 1);
                                @endphp
                                
                                @foreach($delegationsByUserTypeStatus as $userTypeStatusGroup)
                                    @php
                                        $groupRowCount = count($userTypeStatusGroup['delegations']);
                                        $isFirstGroupRow = true;
                                    @endphp
                                    
                                    @foreach($userTypeStatusGroup['delegations'] as $index => $delegasi)
                                        <tr>
                                            @if($isFirstRow)
                                                <td rowspan="{{ $totalRowCount }}">{{ ++$rowIndex }}</td>
                                                <td rowspan="{{ $totalRowCount }}">
                                                    <strong>{{ $dayName }}</strong><br>
                                                    <small class="text-muted">{{ $timeDisplay }}</small>
                                                </td>
                                                <td rowspan="{{ $totalRowCount }}">{{ $group['subject_name'] }}</td>
                                                <td rowspan="{{ $totalRowCount }}">{{ $group['class_name'] }}</td>
                                                <td rowspan="{{ $totalRowCount }}">{{ $group['original_teacher'] }}</td>
                                                @php $isFirstRow = false; @endphp
                                            @endif
                                            
                                            @if($isFirstGroupRow)
                                                <td rowspan="{{ $groupRowCount }}">{{ $userTypeStatusGroup['user_name'] }}</td>
                                                <td rowspan="{{ $groupRowCount }}">
                                                    @if($userTypeStatusGroup['type'] == 'permanent')
                                        <span class="badge bg-info">Permanent</span>
                                    @else
                                        <span class="badge bg-warning">Temporary</span>
                                    @endif
                                </td>
                                                <td rowspan="{{ $groupRowCount }}">
                                                    @if($userTypeStatusGroup['status'] == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                                    @elseif($userTypeStatusGroup['status'] == 'revoked')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary">Kedaluwarsa</span>
                                    @endif
                                </td>
                                                <td rowspan="{{ $groupRowCount }}">
                                                    @php
                                                        // Collect all delegation IDs in this group
                                                        $delegationIds = array_map(function($del) {
                                                            return $del->id;
                                                        }, $userTypeStatusGroup['delegations']);
                                                        $delegationIdsJson = json_encode($delegationIds);
                                                    @endphp
                                                    {{-- Show single delete button that deletes all delegations in this group --}}
                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusSemuaDelegasi({{ $delegationIdsJson }})">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                                                @php $isFirstGroupRow = false; @endphp
                                            @endif
                            </tr>
                                    @endforeach
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada delegasi</p>
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
    </div>
    
    {{-- Tab Permohonan Izin Guru --}}
    <div class="tab-pane fade" id="permohonan-izin" role="tabpanel" aria-labelledby="permohonan-izin-tab" tabindex="0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📝 Permohonan Izin Guru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Guru</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Izin</th>
                                        <th>Jenis Izin</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="permohonanIzinTableBody">
                                    @forelse(isset($teacherLeaveRequests) ? $teacherLeaveRequests : [] as $index => $request)
                                        @if(is_object($request) && isset($request->id))
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ($request->teacher && is_object($request->teacher)) ? ($request->teacher->full_name ?? 'N/A') : 'N/A' }}</td>
                                            <td>
                                                @if($request->timetables && $request->timetables->count() > 0)
                                                    @php
                                                        $subjects = $request->timetables->map(function($t) {
                                                            if ($t && is_object($t) && $t->timetable && is_object($t->timetable) && $t->timetable->classSubject && is_object($t->timetable->classSubject)) {
                                                                return $t->timetable->classSubject->subject->name ?? 'N/A';
                                                            }
                                                            return 'N/A';
                                                        })->unique()->values();
                                                    @endphp
                                                    {{ $subjects->implode(', ') }}
                                                    @if($request->timetables->count() > 1)
                                                        <br><small class="text-muted">({{ $request->timetables->count() }} jadwal)</small>
                                                    @endif
                                                @elseif($request->timetable && is_object($request->timetable) && $request->timetable->classSubject && is_object($request->timetable->classSubject))
                                                    {{ $request->timetable->classSubject->subject->name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->timetables && $request->timetables->count() > 0)
                                                    @php
                                                        $classes = $request->timetables->map(function($t) {
                                                            if ($t && is_object($t) && $t->timetable && is_object($t->timetable) && $t->timetable->classSubject && is_object($t->timetable->classSubject)) {
                                                                return $t->timetable->classSubject->class->name ?? 'N/A';
                                                            }
                                                            return 'N/A';
                                                        })->unique()->values();
                                                    @endphp
                                                    {{ $classes->implode(', ') }}
                                                @elseif($request->timetable && is_object($request->timetable) && $request->timetable->classSubject && is_object($request->timetable->classSubject))
                                                    {{ $request->timetable->classSubject->class->name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->leave_date)
                                                    @if($request->end_date && $request->end_date != $request->leave_date)
                                                        {{ $request->leave_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                                        <br><small class="text-muted">({{ \Carbon\Carbon::parse($request->leave_date)->diffInDays($request->end_date) + 1 }} hari)</small>
                                                    @else
                                                        {{ $request->leave_date->format('d/m/Y') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $request->leave_type_display ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if($request->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($request->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="lihatDetailIzin({{ $request->id }})">
                                                        <i class="bx bx-show"></i> Detail
                                                    </button>
                                                    @if($request->status == 'pending')
                                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="setujuiIzin({{ $request->id }})">
                                                            <i class="bx bx-check"></i> Setujui
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="tolakIzin({{ $request->id }})">
                                                            <i class="bx bx-x"></i> Tolak
                                                        </button>
                                                    @elseif($request->status == 'approved')
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahDelegasiDariPermohonan({{ $request->id }})" title="Tambah delegasi untuk jadwal yang belum didelegasikan">
                                                            <i class="bx bx-plus"></i> Tambah Delegasi
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <p class="text-muted mb-0">Belum ada permohonan izin</p>
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
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalTitle">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmDeleteModalMessage">Apakah Anda yakin ingin menghapus delegasi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
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

<!-- Modal Tambah/Edit Delegasi -->
<div class="modal fade" id="delegasiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delegasiModalTitle">Tambah Delegasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="delegasiForm">
                    <input type="hidden" id="delegasi_id" name="id">
                    <input type="hidden" id="selected_timetable_id" name="timetable_id" value="">
                    <input type="hidden" id="leave_request_id" name="leave_request_id" value="">
                    
                    <!-- Section: Pilih Jadwal yang Akan Didelegasikan (untuk permohonan izin) -->
                    <div class="mb-3" id="multiple_schedule_wrapper" style="display: none;">
                        <label class="form-label fw-bold">Pilih Jadwal yang Akan Didelegasikan <span class="text-danger">*</span></label>
                        <div id="schedule_list_container" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted text-center">Memuat jadwal...</p>
                        </div>
                        <small class="text-muted">Centang jadwal yang akan didelegasikan dan masukkan email pengganti untuk masing-masing jadwal</small>
                    </div>
                    
                    <!-- Section: Form Delegasi Standar (untuk tambah delegasi manual) -->
                    <div id="single_delegation_wrapper">
                    <!-- Step 1: Pilih Mata Pelajaran -->
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects ?? [] as $subject)
                                @if(is_object($subject) && isset($subject->id))
                                    <option value="{{ $subject->id }}">{{ $subject->name ?? 'N/A' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 2: Pilih Kelas -->
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes ?? [] as $class)
                                @if(is_object($class) && isset($class->id))
                                    <option value="{{ $class->id }}">
                                        {{ $class->name ?? 'N/A' }} 
                                        @if(isset($class->grade) && $class->grade) - Kelas {{ $class->grade }} @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 3: Pilih Guru yang digantikan -->
                    <div class="mb-3">
                        <label class="form-label">Email Guru yang Digantikan <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="teacher_email" name="teacher_email" placeholder="contoh@email.com" required>
                        <small class="text-muted">Masukkan email guru yang akan digantikan</small>
                        <div id="teacher_email_validation_message" class="mt-2"></div>
                        <input type="hidden" id="teacher_id" name="teacher_id" value="">
                    </div>

                    <!-- Step 4: Pilih Jadwal (filtered by subject, class, teacher) -->
                    <div class="mb-3" id="schedule_wrapper" style="display: none;">
                        <label class="form-label">Jadwal <span class="text-danger">*</span></label>
                        <select class="form-select" id="schedule_id" name="schedule_id" required>
                            <option value="">Pilih Jadwal</option>
                        </select>
                        <div id="schedule_info" class="mt-2 text-muted"></div>
                        </div>
                    </div>

                    <!-- Step 5: Tanggal Sesuai Jadwal (only for single delegation mode) -->
                    <div class="mb-3" id="delegation_date_wrapper">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="delegation_date" name="delegation_date" required>
                        <small class="text-muted">Pilih tanggal sesuai jadwal hari yang dipilih</small>
                    </div>

                    <!-- Step 6: Pilih Delegasi Kepada (only for single delegation mode) -->
                    <div class="mb-3" id="delegated_to_email_wrapper">
                        <label class="form-label">Email Delegasi Kepada <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="delegated_to_email" name="delegated_to_email" placeholder="contoh@email.com" required>
                        <small class="text-muted">Masukkan email guru atau murid yang akan menerima delegasi</small>
                        <div id="email_validation_message" class="mt-2"></div>
                        <input type="hidden" id="delegated_to_user_id" name="delegated_to_user_id" value="">
                    </div>

                    <!-- Common fields (visible in both modes) -->
                    <!-- Step 7: Tipe Delegasi -->
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="permanent">Permanent</option>
                            <option value="temporary">Temporary</option>
                        </select>
                    </div>

                    <div class="mb-3" id="validUntilWrapper" style="display: none;">
                        <label class="form-label">Berlaku Sampai</label>
                        <input type="date" class="form-control" id="valid_until" name="valid_until">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Alasan delegasi (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanDelegasi()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Permohonan Izin -->
<div class="modal fade" id="detailIzinModal" tabindex="-1" aria-labelledby="detailIzinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailIzinModalLabel">Detail Permohonan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailIzinModalBody">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setujui Izin -->
<div class="modal fade" id="setujuiIzinModal" tabindex="-1" aria-labelledby="setujuiIzinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setujuiIzinModalLabel">Setujui Permohonan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="setujuiIzinForm">
                    <input type="hidden" id="setujui_izin_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label">Email Pengganti <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="substitute_email" name="substitute_email" placeholder="contoh@email.com" required>
                        <small class="text-muted">Masukkan email guru atau murid yang akan menggantikan</small>
                        <div id="substitute_email_validation_message" class="mt-2"></div>
                        <input type="hidden" id="substitute_user_id" name="substitute_user_id" value="">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="approve_admin_notes" name="admin_notes" rows="3" placeholder="Catatan untuk permohonan izin (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="prosesSetujuiIzin()">Setujui</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Izin -->
<div class="modal fade" id="tolakIzinModal" tabindex="-1" aria-labelledby="tolakIzinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tolakIzinModalLabel">Tolak Permohonan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tolakIzinForm">
                    <input type="hidden" id="tolak_izin_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="reject_admin_notes" name="admin_notes" rows="3" placeholder="Masukkan alasan penolakan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="prosesTolakIzin()">Tolak</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        // Pass data from Laravel to JavaScript
        window.allTimetables = @json($timetables ?? []);
    </script>
    @vite(['resources/js/admin/delegasi.js'])
@endsection


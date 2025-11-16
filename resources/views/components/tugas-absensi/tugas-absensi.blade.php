@props([
    'mode' => 'murid', // 'guru' or 'murid'
    'myDelegations' => collect(),
    'today' => null,
    'showInfoAlert' => false, // Show info alert (for murid)
    'qrRouteName' => 'guru.absensi.scan', // Route name for QR scan (for guru)
    'qrModalFunction' => 'openQRModal', // Function name for opening QR modal (for murid)
    'cardTitle' => 'Tugas Absensi',
    'emptyMessage' => 'Anda belum memiliki delegasi',
    'emptySubMessage' => null
])

@php
    // Group delegations by subject, day, and class
    $groupedDelegations = [];
    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
    
    if (!$today) {
        $today = \Carbon\Carbon::now();
    }
    
    foreach ($myDelegations as $delegasi) {
        if (!is_object($delegasi) || !isset($delegasi->id) || !$delegasi->timetable) {
            continue;
        }
        
        $timetable = $delegasi->timetable;
        $dayOfWeek = $timetable->day_of_week ?? null;
        $subjectId = ($timetable->classSubject && is_object($timetable->classSubject) && $timetable->classSubject->subject) 
            ? $timetable->classSubject->subject->id 
            : null;
        $classId = ($timetable->classSubject && is_object($timetable->classSubject) && $timetable->classSubject->class) 
            ? $timetable->classSubject->class->id 
            : null;
        
        // Create unique key based on subject, day, and class
        $key = $subjectId . '_' . $dayOfWeek . '_' . $classId;
        
        if (!isset($groupedDelegations[$key])) {
            $classSubject = $timetable->classSubject;
            $groupedDelegations[$key] = [
                'day_of_week' => $dayOfWeek,
                'subject_name' => ($classSubject && is_object($classSubject) && $classSubject->subject) 
                    ? $classSubject->subject->name 
                    : 'N/A',
                'class_name' => ($classSubject && is_object($classSubject) && $classSubject->class) 
                    ? $classSubject->class->name 
                    : 'N/A',
                'original_teacher' => ($delegasi->originalTeacher && is_object($delegasi->originalTeacher) && $delegasi->originalTeacher->user && is_object($delegasi->originalTeacher->user)) 
                    ? $delegasi->originalTeacher->user->full_name 
                    : 'N/A',
                'type' => $delegasi->type ?? 'N/A',
                'start_times' => [],
                'delegations' => []
            ];
        }
        
        // Add start time
        if ($timetable->start_time) {
            $groupedDelegations[$key]['start_times'][] = $timetable->start_time;
        }
        
        // Add delegation to group
        $groupedDelegations[$key]['delegations'][] = $delegasi;
    }
    
    // Find earliest start time for each group
    foreach ($groupedDelegations as $key => &$group) {
        if (!empty($group['start_times'])) {
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
    }
    unset($group);
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    @if($mode === 'murid')
                        📋 Tugas Absensi dari Guru
                    @else
                        📋 {{ $cardTitle }}
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($showInfoAlert && $mode === 'murid')
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i>
                    <strong>Info:</strong> Sebagai Pengganti, Anda dapat membuka QR Code untuk absensi kelas yang ditunjuk oleh Guru Anda.
                </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Guru Asli</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupedDelegations as $key => $group)
                            @php
                                $dayName = isset($dayNames[$group['day_of_week']]) ? $dayNames[$group['day_of_week']] : 'N/A';
                                $timeDisplay = 'N/A';
                                if ($group['earliest_start']) {
                                    $timeDisplay = \Carbon\Carbon::parse($group['earliest_start'])->format('H:i');
                                }
                                
                                // Get first delegation for action check
                                $firstDelegation = $group['delegations'][0];
                                $delegationDayNumber = $firstDelegation->timetable->day_of_week;
                                $todayDayNumber = $today->dayOfWeekIso;
                                $isToday = ($todayDayNumber === $delegationDayNumber);
                                
                                $isWithinTemporaryPeriod = true;
                                if ($firstDelegation->type === 'temporary') {
                                    $validFrom = \Carbon\Carbon::parse($firstDelegation->valid_from)->startOfDay();
                                    $validUntil = \Carbon\Carbon::parse($firstDelegation->valid_until)->endOfDay();
                                    $todayDate = $today->startOfDay();
                                    $isWithinTemporaryPeriod = $todayDate->isBetween($validFrom, $validUntil, true);
                                }
                                
                                // Get first timetable ID for QR action
                                $firstTimetableId = $firstDelegation->timetable->id;
                                
                                // For murid mode, get additional info for modal
                                if ($mode === 'murid') {
                                    $firstTimetable = $firstDelegation->timetable;
                                    $firstSubjectName = $group['subject_name'];
                                    $firstClassName = $group['class_name'];
                                    $firstTimeRange = $timeDisplay;
                                }
                            @endphp
                            <tr>
                                <td>{{ $dayName }}</td>
                                <td>{{ $group['subject_name'] }}</td>
                                <td>{{ $group['class_name'] }}</td>
                                <td>{{ $timeDisplay }}</td>
                                <td>{{ $group['original_teacher'] }}</td>
                                <td>
                                    @if($group['type'] == 'permanent')
                                        <span class="badge bg-info">Permanent</span>
                                    @else
                                        <span class="badge bg-warning">Temporary</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isToday && $isWithinTemporaryPeriod)
                                        @if($mode === 'guru')
                                            <a href="{{ route($qrRouteName, ['timetable_id' => $firstTimetableId]) }}" class="btn btn-sm btn-primary">
                                                <i class="bx bx-qr-scan"></i> Buka QR
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary" onclick="{{ $qrModalFunction }}({{ $firstTimetableId }}, {{ json_encode($firstSubjectName) }}, {{ json_encode($firstClassName) }}, {{ json_encode($firstTimeRange) }})">
                                                <i class="bx bx-qr-scan"></i> Buka QR
                                            </button>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Belum waktunya</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center {{ $mode === 'murid' ? 'py-5' : 'py-4' }}">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">
                                            @if($mode === 'murid')
                                                Anda belum memiliki Tugas menggantikan guru untuk mengabsen
                                            @else
                                                {{ $emptyMessage }}
                                            @endif
                                        </p>
                                        @if($mode === 'murid' || $emptySubMessage)
                                            <small>
                                                @if($mode === 'murid')
                                                    Guru akan menunjuk Anda sebagai Pengganti jika diperlukan
                                                @else
                                                    {{ $emptySubMessage }}
                                                @endif
                                            </small>
                                        @endif
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


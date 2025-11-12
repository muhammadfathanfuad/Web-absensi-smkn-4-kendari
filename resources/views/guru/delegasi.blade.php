@extends('layouts.vertical-guru', ['subtitle' => 'Delegasi Saya'])

@section('css')
<style>
    /* Footer sticky di mobile */
    @media (max-width: 575.98px) {
        .page-content {
            padding-bottom: 60px;
        }
        
        .footer {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000;
            width: 100% !important;
        }
    }
</style>
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengganti Absensi'])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📋 Tugas Pengganti Absensi </h5>
            </div>
            <div class="card-body">
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
                            @php
                                // Group delegations by subject, day, and class
                                $groupedDelegations = [];
                                $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                
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
                                    <a href="{{ route('guru.absensi.scan', ['timetable_id' => $firstTimetableId]) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-qr-scan"></i> Buka QR
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">Belum waktunya</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">Anda belum memiliki delegasi</p>
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

@endsection


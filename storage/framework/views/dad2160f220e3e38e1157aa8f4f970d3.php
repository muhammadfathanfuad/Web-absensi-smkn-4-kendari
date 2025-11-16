<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'mode' => 'murid', // 'guru' or 'murid'
    'myDelegations' => collect(),
    'today' => null,
    'showInfoAlert' => false, // Show info alert (for murid)
    'qrRouteName' => 'guru.absensi.scan', // Route name for QR scan (for guru)
    'qrModalFunction' => 'openQRModal', // Function name for opening QR modal (for murid)
    'cardTitle' => 'Tugas Absensi',
    'emptyMessage' => 'Anda belum memiliki delegasi',
    'emptySubMessage' => null
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'mode' => 'murid', // 'guru' or 'murid'
    'myDelegations' => collect(),
    'today' => null,
    'showInfoAlert' => false, // Show info alert (for murid)
    'qrRouteName' => 'guru.absensi.scan', // Route name for QR scan (for guru)
    'qrModalFunction' => 'openQRModal', // Function name for opening QR modal (for murid)
    'cardTitle' => 'Tugas Absensi',
    'emptyMessage' => 'Anda belum memiliki delegasi',
    'emptySubMessage' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <?php if($mode === 'murid'): ?>
                        📋 Tugas Absensi dari Guru
                    <?php else: ?>
                        📋 <?php echo e($cardTitle); ?>

                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if($showInfoAlert && $mode === 'murid'): ?>
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i>
                    <strong>Info:</strong> Sebagai Pengganti, Anda dapat membuka QR Code untuk absensi kelas yang ditunjuk oleh Guru Anda.
                </div>
                <?php endif; ?>
                
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
                            <?php $__empty_1 = true; $__currentLoopData = $groupedDelegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
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
                            ?>
                            <tr>
                                <td><?php echo e($dayName); ?></td>
                                <td><?php echo e($group['subject_name']); ?></td>
                                <td><?php echo e($group['class_name']); ?></td>
                                <td><?php echo e($timeDisplay); ?></td>
                                <td><?php echo e($group['original_teacher']); ?></td>
                                <td>
                                    <?php if($group['type'] == 'permanent'): ?>
                                        <span class="badge bg-info">Permanent</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Temporary</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($isToday && $isWithinTemporaryPeriod): ?>
                                        <?php if($mode === 'guru'): ?>
                                            <a href="<?php echo e(route($qrRouteName, ['timetable_id' => $firstTimetableId])); ?>" class="btn btn-sm btn-primary">
                                                <i class="bx bx-qr-scan"></i> Buka QR
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="<?php echo e($qrModalFunction); ?>(<?php echo e($firstTimetableId); ?>, <?php echo e(json_encode($firstSubjectName)); ?>, <?php echo e(json_encode($firstClassName)); ?>, <?php echo e(json_encode($firstTimeRange)); ?>)">
                                                <i class="bx bx-qr-scan"></i> Buka QR
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum waktunya</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center <?php echo e($mode === 'murid' ? 'py-5' : 'py-4'); ?>">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">
                                            <?php if($mode === 'murid'): ?>
                                                Anda belum memiliki Tugas menggantikan guru untuk mengabsen
                                            <?php else: ?>
                                                <?php echo e($emptyMessage); ?>

                                            <?php endif; ?>
                                        </p>
                                        <?php if($mode === 'murid' || $emptySubMessage): ?>
                                            <small>
                                                <?php if($mode === 'murid'): ?>
                                                    Guru akan menunjuk Anda sebagai Pengganti jika diperlukan
                                                <?php else: ?>
                                                    <?php echo e($emptySubMessage); ?>

                                                <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/tugas-absensi/tugas-absensi.blade.php ENDPATH**/ ?>
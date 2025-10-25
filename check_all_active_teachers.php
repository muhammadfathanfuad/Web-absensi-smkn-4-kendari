<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Mencari semua guru aktif dan mata pelajaran yang mereka ajarkan:\n\n";

// Cari semua guru aktif
$activeTeachers = \App\Models\Teacher::with(['user', 'classSubjects.subject'])
    ->whereHas('user', function($query) {
        $query->where('status', 'active');
    })
    ->get();

echo "Total guru aktif: " . $activeTeachers->count() . "\n\n";

foreach($activeTeachers as $teacher) {
    echo "Guru: " . $teacher->user->full_name . " (ID: " . $teacher->user_id . ", Kode: " . $teacher->kode_guru . ")\n";
    
    // Ambil mata pelajaran yang diajarkan
    $subjects = \App\Models\ClassSubject::where('teacher_id', $teacher->user_id)
        ->with('subject')
        ->get()
        ->pluck('subject.name')
        ->unique()
        ->toArray();
    
    echo "Mata pelajaran: " . implode(', ', $subjects) . "\n";
    
    // Cek apakah mengajar Bahasa Inggris
    $teachesBahasaInggris = in_array('BAHASA INGGRIS', $subjects);
    echo "Mengajar Bahasa Inggris: " . ($teachesBahasaInggris ? "YA" : "TIDAK") . "\n";
    
    if($teachesBahasaInggris) {
        // Cek apakah sibuk pada Senin 08:00-10:40
        $isBusy = \App\Models\Timetable::where('day_of_week', 1)
            ->where('start_time', '<', '10:40')
            ->where('end_time', '>', '08:00')
            ->whereHas('classSubject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->user_id);
            })
            ->exists();
        
        echo "Sibuk Senin 08:00-10:40: " . ($isBusy ? "YA" : "TIDAK") . "\n";
        
        if(!$isBusy) {
            echo "✅ GURU INI TERSEDIA!\n";
        }
    }
    echo str_repeat("-", 50) . "\n";
}

// Cek apakah ada guru Bahasa Inggris yang suspended tapi bisa diaktifkan
echo "\n" . str_repeat("=", 60) . "\n";
echo "Guru Bahasa Inggris yang suspended:\n\n";

$suspendedBahasaInggrisTeachers = \App\Models\Teacher::with('user')
    ->whereHas('user', function($query) {
        $query->where('status', 'suspended');
    })
    ->whereHas('classSubjects', function($query) {
        $query->where('subject_id', 103); // BAHASA INGGRIS
    })
    ->get();

foreach($suspendedBahasaInggrisTeachers as $teacher) {
    echo "- " . $teacher->user->full_name . " (ID: " . $teacher->user_id . ", Kode: " . $teacher->kode_guru . ")\n";
}

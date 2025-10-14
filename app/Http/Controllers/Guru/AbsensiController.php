<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Services\TimeOverrideService;

class AbsensiController extends Controller
{
    public function showScanner()
    {
        $teacherId = Auth::user()->teacher->user_id;
        $dayOfWeek = TimeOverrideService::dayOfWeek();

        $jadwalHariIni = Timetable::with(['classSubject.subject', 'classSubject.class'])
            ->whereHas('classSubject', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('guru.scan-qr', compact('jadwalHariIni'));
    }

    public function getScanResults($timetable_id)
    {
        $classSession = ClassSession::where('timetable_id', $timetable_id)
                                      ->where('date', TimeOverrideService::today())
                                      ->first();

        if (!$classSession) {
            return response()->json([]);
        }

        // Relasi student() di model Attendance akan mengambil data siswa
        $attendances = Attendance::with(['student.user'])
            ->where('class_session_id', $classSession->id)
            ->get();

        return response()->json($attendances);
    }

    public function processScan(Request $request)
    {
        // Validasi 'nisn' sesuai dengan data yang dikirim dari QR (JavaScript)
        $request->validate([
            'nisn' => 'required|string',
            'timetable_id' => 'required|integer|exists:timetables,id',
        ]);
        

        // Mencari siswa berdasarkan kolom 'nis' menggunakan data dari 'nisn'
        $student = Student::where('nis', $request->nisn)->with('user')->first();
        if (!$student) {
            return response()->json(['error' => 'Siswa dengan NIS ' . $request->nisn . ' tidak ditemukan.'], 404);
        }

        $timetable = Timetable::find($request->timetable_id);
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $request->timetable_id, 'date' => today()->toDateString()],
            ['status' => 'ongoing', 'opened_by' => 2] // Ganti dengan auth()->id()
        );
        
        // --- PERBAIKAN KUNCI #1 ---
        // Mencari absensi menggunakan PRIMARY KEY yang benar yaitu 'user_id'
        $attendance = Attendance::where('class_session_id', $classSession->id)
                                ->where('student_id', $student->user_id) // DIUBAH DARI $student->id
                                ->first();

        $action = '';
        $note = null;

        if ($attendance) {
            // Logika untuk scan keluar (check-out)
            if ($attendance->check_out_time !== null) {
                return response()->json(['error' => $student->user->full_name . ' sudah melakukan scan masuk dan keluar.'], 409);
            }
            $attendance->check_out_time = now()->format('H:i:s');
            $attendance->save();
            $action = 'scan_out';
        } else {
            // Logika untuk scan masuk (check-in)
            $timezone = 'Asia/Makassar'; 
            $jamMasukJadwal = Carbon::parse($timetable->start_time, $timezone);
            $jamScan = Carbon::now($timezone);
            
            $selisihMenit = abs($jamScan->diffInMinutes($jamMasukJadwal));
            $isAfter = $jamScan->isAfter($jamMasukJadwal);

            $status = 'H';
            $note = null;

            if ($isAfter && $selisihMenit > 15) {
                $note = 'Terlambat';
                $status = 'T';
            }

            // --- PERBAIKAN KUNCI #2 ---
            // Membuat absensi dengan PRIMARY KEY siswa yang benar yaitu 'user_id'
            $attendance = Attendance::create([
                'class_session_id' => $classSession->id,
                'student_id' => $student->user_id, // DIUBAH DARI $student->id
                'status' => $status,
                'check_in_time' => $jamScan->format('H:i:s'),
                'notes' => $note,
            ]);
            $action = 'scan_in';
        }

        $dataUntukTabel = [
            'action' => $action,
            'nisn' => $student->nis,
            'nama' => optional($student->user)->full_name ?? 'Siswa Tidak Ditemukan',
            'jam_masuk' => $attendance->check_in_time,
            'jam_keluar' => $attendance->check_out_time,
            'note' => $attendance->notes,
            'status' => $attendance->status,
        ];

        return response()->json($dataUntukTabel);
    }

    public function showStatus(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = $request->input('subject_id');
        $selectedDate = $request->input('date', today()->toDateString());

        $query = Attendance::with(['student.user', 'classSession.timetable.classSubject.subject'])
            ->whereHas('classSession', function ($q) use ($selectedDate) {
                $q->where('date', $selectedDate);
            });

        if ($selectedSubjectId) {
            $query->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            });
        }
        
        $attendances = $query->latest('id')->get();

        return view('guru.status-absensi', compact('subjects', 'attendances', 'selectedSubjectId', 'selectedDate'));
    }
}
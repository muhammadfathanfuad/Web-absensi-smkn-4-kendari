<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Subject;

class AbsensiController extends Controller
{
    // Menampilkan halaman scanner/QR generator
    public function showScanner()
    {
        $teacherId = Auth::id();
        $dayOfWeek = today()->dayOfWeekIso;

        $jadwalHariIni = Timetable::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('guru.scan-qr', compact('jadwalHariIni'));
    }

    // Menghasilkan payload QR (JSON) dan memastikan ClassSession ada
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
        ]);

        $timetable = Timetable::with('subject')->findOrFail($request->timetable_id);
        $user = Auth::user()->load('teacher');

        if (!$user->teacher) {
            return response()->json(['error' => 'Data guru (NIP) tidak ditemukan.'], 404);
        }

        // Pastikan ada ClassSession untuk hari ini
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => today()->toDateString()],
            ['status' => 'ongoing', 'opened_by' => $user->id]
        );

        $qrData = [
            'teacher_user_id' => $user->id,
            'teacher_name' => $user->name,
            'teacher_nip' => optional($user->teacher)->nip,
            'subject_id' => optional($timetable->subject)->id,
            'subject_name' => optional($timetable->subject)->name,
            'timetable_id' => $timetable->id,
            'class_id' => $timetable->class_id,
            'class_session_id' => $classSession->id,
        ];

        return response()->json($qrData);
    }

    // Memproses scan (dipanggil oleh teacher JS saat menerima scan dari siswa)
    public function processScan(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'timetable_id' => 'required|exists:timetables,id',
        ]);

        $student = Student::where('nis', $request->nisn)->with('user')->first();
        if (!$student) {
            return response()->json(['error' => 'Siswa dengan NIS ' . $request->nisn . ' tidak ditemukan.'], 404);
        }

        $timetable = Timetable::findOrFail($request->timetable_id);

        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => today()->toDateString()],
            ['status' => 'ongoing', 'opened_by' => Auth::id()]
        );

        // Cari attendance berdasarkan class_session dan student.user_id
        $attendance = Attendance::where('class_session_id', $classSession->id)
                                ->where('student_id', $student->user_id)
                                ->first();

        $action = '';

        if ($attendance) {
            // Check-out
            if ($attendance->check_out_time !== null) {
                return response()->json(['error' => optional($student->user)->full_name . ' sudah melakukan scan masuk dan keluar.'], 409);
            }
            $attendance->check_out_time = now()->format('H:i:s');
            $attendance->save();
            $action = 'scan_out';
        } else {
            // Check-in
            $timezone = 'Asia/Makassar';
            $jamMasukJadwal = Carbon::parse($timetable->start_time, $timezone);
            $jamScan = Carbon::now($timezone);

            $selisihMenit = $jamScan->diffInMinutes($jamMasukJadwal);
            $isAfter = $jamScan->isAfter($jamMasukJadwal);

            $status = 'H';
            $note = null;
            if ($isAfter && $selisihMenit > 15) {
                $status = 'T';
                $note = 'Terlambat';
            }

            $attendance = Attendance::create([
                'class_session_id' => $classSession->id,
                'student_id' => $student->user_id,
                'status' => $status,
                'check_in_time' => $jamScan->format('H:i:s'),
                'notes' => $note,
            ]);
            $action = 'scan_in';
        }

        $response = [
            'action' => $action,
            'nisn' => $student->nis,
            'nama' => optional($student->user)->full_name ?? 'Siswa Tidak Ditemukan',
            'jam_masuk' => $attendance->check_in_time,
            'jam_keluar' => $attendance->check_out_time,
            'note' => $attendance->notes,
            'status' => $attendance->status,
        ];

        return response()->json($response);
    }

    // Mengembalikan hasil pindaian untuk ditampilkan guru (format yang mudah dirender)
    public function getScanResults($timetable_id)
    {
        $classSession = ClassSession::where('timetable_id', $timetable_id)
                                    ->where('date', today()->toDateString())
                                    ->first();

        if (!$classSession) {
            return response()->json([]);
        }

        $attendances = Attendance::with(['student.user'])
                        ->where('class_session_id', $classSession->id)
                        ->orderBy('id')
                        ->get();

        $rows = $attendances->map(function ($a, $i) {
            return [
                'no' => $i + 1,
                'student_name' => optional($a->student->user)->full_name ?? '-',
                'student_nisn' => optional($a->student)->nis ?? '-',
                'check_in_time' => $a->check_in_time,
                'check_out_time' => $a->check_out_time,
                'note' => $a->notes,
                'status' => $a->status,
            ];
        })->values();

        return response()->json($rows);
    }

    // Menampilkan halaman status absensi (existing)
    public function showStatus(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = $request->input('subject_id');
        $selectedDate = $request->input('date', today()->toDateString());

        $query = Attendance::with(['student.user', 'classSession.timetable.subject'])
            ->whereHas('classSession', function ($q) use ($selectedDate) {
                $q->where('date', $selectedDate);
            });

        if ($selectedSubjectId) {
            $query->whereHas('classSession.timetable', function ($q) use ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            });
        }

        $attendances = $query->latest('id')->get();

        return view('guru.status-absensi', compact('subjects', 'attendances', 'selectedSubjectId', 'selectedDate'));
    }
}
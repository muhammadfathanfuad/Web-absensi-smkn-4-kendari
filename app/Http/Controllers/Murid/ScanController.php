<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Student;

class ScanController extends Controller
{
    // Murid mengirimkan scan ke endpoint ini (POST)
    public function submit(Request $request)
    {
        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json(['error' => 'Data murid tidak ditemukan.'], 404);
        }

        $timetable = Timetable::findOrFail($request->timetable_id);

        // Validasi: pastikan murid terdaftar di kelas yang sesuai
        if (isset($student->class_id) && $timetable->class_id && $student->class_id != $timetable->class_id) {
            return response()->json(['error' => 'Anda tidak terdaftar pada kelas ini.'], 403);
        }

        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => today()->toDateString()],
            ['status' => 'ongoing', 'opened_by' => $timetable->teacher_id ?? null]
        );

        $attendance = Attendance::where('class_session_id', $classSession->id)
                                ->where('student_id', $user->id)
                                ->first();

        if ($attendance) {
            // already checked in? do check-out
            if ($attendance->check_out_time !== null) {
                return response()->json(['error' => 'Anda sudah melakukan check-out.'], 409);
            }
            $attendance->check_out_time = now()->format('H:i:s');
            $attendance->save();
            return response()->json(['message' => 'Check-out berhasil', 'status' => $attendance->status]);
        }

        // check-in logic with lateness
        $timezone = 'Asia/Makassar';
        $jamMasuk = Carbon::parse($timetable->start_time, $timezone);
        $jamScan = Carbon::now($timezone);

        $isAfter = $jamScan->isAfter($jamMasuk);
        $diffMins = $jamScan->diffInMinutes($jamMasuk);

        $status = 'H';
        $note = null;
        if ($isAfter && $diffMins > 15) {
            $status = 'T';
            $note = 'Terlambat';
        }

        $attendance = Attendance::create([
            'class_session_id' => $classSession->id,
            'student_id' => $user->id,
            'status' => $status,
            'check_in_time' => $jamScan->format('H:i:s'),
            'notes' => $note,
        ]);

        return response()->json(['message' => 'Check-in berhasil', 'status' => $attendance->status]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user', 'classroom')->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'nis' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:255',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User dengan email tersebut tidak ditemukan!']);
        }

        // Check if already a student
        if ($user->student) {
            return response()->json(['success' => false, 'message' => 'User tersebut sudah menjadi murid!']);
        }

        // Check if already a teacher
        if ($user->teacher) {
            return response()->json(['success' => false, 'message' => 'User tersebut sudah menjadi guru!']);
        }

        $user->roles()->syncWithoutDetaching([3]); // student role

        $student = Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'class_id' => $request->class_id,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
        ]);

        return response()->json(['success' => true, 'message' => 'Murid berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:255',
        ]);

        $student->user->update([
            'full_name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'nis' => $request->nis,
            'class_id' => $request->class_id,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
        ]);

        return response()->json(['success' => true, 'message' => 'Murid berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->user->delete();
        $student->delete();
        return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new StudentsImport, $request->file('file'));

            return response()->json(['success' => true, 'message' => 'Murid berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengimpor murid: ' . $e->getMessage()], 500);
        }
    }

    // Bulk delete students
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:students,user_id',
            ]);

            Student::whereIn('user_id', $request->ids)->delete();

            return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Bulk activate students
    public function bulkStatusActive(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:students,user_id',
            ]);

            $students = Student::whereIn('user_id', $request->ids)->with('user')->get();
            foreach ($students as $student) {
                $student->user->update(['status' => 'active']);
            }

            return response()->json(['success' => true, 'message' => 'Status murid berhasil diubah ke aktif!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Bulk suspend students
    public function bulkStatusSuspended(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:students,user_id',
            ]);

            $students = Student::whereIn('user_id', $request->ids)->with('user')->get();
            foreach ($students as $student) {
                $student->user->update(['status' => 'suspended']);
            }

            return response()->json(['success' => true, 'message' => 'Status murid berhasil diubah ke suspended!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

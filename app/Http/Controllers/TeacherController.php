<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with('user')->get();
        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'nip' => 'required|string|max:255',
            'kode_guru' => 'required|string|max:255',
            'department' => 'required|string|max:255|exists:subjects,name',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User dengan email tersebut tidak ditemukan!']);
        }

        // Check if already a teacher
        if ($user->teacher) {
            return response()->json(['success' => false, 'message' => 'User tersebut sudah menjadi guru!']);
        }

        $user->roles()->syncWithoutDetaching([2]); // teacher role

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'kode_guru' => $request->kode_guru,
            'department' => $request->department,
        ]);

        // Otomatis hubungkan guru dengan mata pelajaran yang dipilih
        $this->connectTeacherToSelectedSubject($teacher, $request->department);

        return response()->json(['success' => true, 'message' => 'Guru berhasil ditambahkan dan terhubung dengan mata pelajaran!']);
    }

    /**
     * Menghubungkan guru dengan mata pelajaran yang dipilih
     * ClassSubject akan dibuat tanpa class_id (akan diisi nanti saat mata pelajaran ditambahkan ke kelas)
     */
    private function connectTeacherToSelectedSubject($teacher, $subjectName)
    {
        // Cari mata pelajaran berdasarkan nama yang dipilih
        $subject = \App\Models\Subject::where('name', $subjectName)->first();
        
        if ($subject) {
            // Buat ClassSubject tanpa class_id (akan diisi nanti)
            \App\Models\ClassSubject::create([
                'teacher_id' => $teacher->user_id,
                'subject_id' => $subject->id,
                'class_id' => null, // Akan diisi nanti saat mata pelajaran ditambahkan ke kelas
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|max:255',
            'kode_guru' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $teacher->user->update([
            'full_name' => $request->name,
            'email' => $request->email,
        ]);

        $teacher->update([
            'nip' => $request->nip,
            'kode_guru' => $request->kode_guru,
            'department' => $request->department,
        ]);

        return response()->json(['success' => true, 'message' => 'Guru berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->user->delete();
        $teacher->delete();
        return response()->json(['success' => true, 'message' => 'Guru berhasil dihapus!']);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $import = new TeachersImport;
            Excel::import($import, $request->file('file'));

            $errors = $import->getErrors();
            $successCount = $import->getSuccessCount();
            $skipCount = $import->getSkipCount();

            // Build response message
            $message = '';
            $hasErrors = !empty($errors);
            $hasSuccess = $successCount > 0;

            if ($hasSuccess) {
                $message .= "Berhasil mengimpor {$successCount} data guru.\n";
            }

            if ($skipCount > 0) {
                $message .= "Melewati {$skipCount} baris kosong.\n";
            }

            if ($hasErrors) {
                $message .= "\nTerdapat " . count($errors) . " baris yang gagal diimpor:\n\n";
                
                foreach ($errors as $error) {
                    $message .= "Baris {$error['row']} - {$error['nama_guru']} (Kode: {$error['kode_guru']}):\n";
                    foreach ($error['errors'] as $err) {
                        $message .= "  • {$err}\n";
                    }
                    $message .= "\n";
                }
            }

            if (!$hasSuccess && !$hasErrors) {
                $message = "Tidak ada data yang berhasil diimpor. Pastikan file berisi data yang valid.";
            } elseif (!$hasSuccess && $hasErrors) {
                $message = "Gagal mengimpor semua data. " . trim($message);
            } else {
                $message = trim($message);
            }

            return response()->json([
                'success' => !$hasErrors || $hasSuccess,
                'message' => $message,
                'errors' => $errors,
                'success_count' => $successCount,
                'error_count' => count($errors),
                'skip_count' => $skipCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengimpor guru: ' . $e->getMessage(),
                'errors' => [],
                'success_count' => 0,
                'error_count' => 0,
                'skip_count' => 0
            ], 500);
        }
    }

    // Bulk delete teachers
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:teachers,user_id',
            ]);

            Teacher::whereIn('user_id', $request->ids)->delete();

            return response()->json(['success' => true, 'message' => 'Guru berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


}

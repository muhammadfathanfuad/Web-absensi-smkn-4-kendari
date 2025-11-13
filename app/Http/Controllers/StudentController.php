<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Validators\ValidationException;

class StudentController extends Controller
{
    public function index(Request $request)
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
        try {
        $student = Student::findOrFail($id);
            
            // Delete user first, which will cascade delete student if foreign key is set up
            // But we'll delete student explicitly to be safe
            if ($student->user) {
        $student->user->delete();
            }
            // Student will be deleted automatically if cascade is set, but delete explicitly to be safe
        $student->delete();
            
        return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus murid: ' . $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            // Increase execution time limit for large imports
            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', '300');
            ini_set('memory_limit', '512M');
            
            // Disable output buffering to prevent timeout
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Send headers early to prevent web server timeout
            if (!headers_sent()) {
                header('Content-Type: application/json');
                header('X-Accel-Buffering: no'); // Disable nginx buffering
            }
            
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $import = new StudentsImport;
            Excel::import($import, $request->file('file'));

            $errors = $import->getErrors();
            $successCount = $import->getSuccessCount();
            $skipCount = $import->getSkipCount();

            // Build response message
            $message = '';
            $hasErrors = !empty($errors);
            $hasSuccess = $successCount > 0;

            if ($hasSuccess) {
                $message .= "Berhasil mengimpor {$successCount} data siswa.\n";
            }

            if ($skipCount > 0) {
                $message .= "Melewati {$skipCount} baris kosong.\n";
            }

            if ($hasErrors) {
                $message .= "\nTerdapat " . count($errors) . " baris yang gagal diimpor:\n\n";
                
                foreach ($errors as $error) {
                    $message .= "Baris {$error['row']} - {$error['nama']} (NIS: {$error['nis']}):\n";
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => [],
                'success_count' => 0,
                'error_count' => 0,
                'skip_count' => 0
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error importing students: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if it's a timeout error
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Maximum execution time') !== false || 
                strpos($errorMessage, 'timeout') !== false) {
                $errorMessage = 'Import memakan waktu terlalu lama. File terlalu besar atau server lambat. Silakan coba dengan file yang lebih kecil atau hubungi administrator.';
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengimpor murid: ' . $errorMessage,
                'errors' => [],
                'success_count' => 0,
                'error_count' => 0,
                'skip_count' => 0
            ], 500);
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

            // Get students with their users
            $students = Student::whereIn('user_id', $request->ids)->with('user')->get();
            
            // Delete users (this will cascade delete students if foreign key is set up correctly)
            // But to be safe, we'll delete both explicitly
            foreach ($students as $student) {
                if ($student->user) {
                    $student->user->delete();
                }
                // Student will be deleted automatically if cascade is set, but delete explicitly to be safe
                $student->delete();
            }

            return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


}

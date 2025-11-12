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
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $row = $failure->row(); // Nomor baris di Excel (dimulai dari 2 karena baris 1 adalah header)
                $attribute = $failure->attribute(); // Field yang error (misalnya 'nis')
                $errors = $failure->errors(); // Array error messages
                
                // Terjemahkan nama field ke bahasa Indonesia
                $fieldNames = [
                    'nama' => 'Nama',
                    'nis' => 'NIS',
                    'kelas' => 'Kelas',
                    'nama_wali' => 'Nama Wali',
                    'telepon_wali' => 'Telepon Wali',
                ];
                
                $fieldName = $fieldNames[$attribute] ?? ucfirst($attribute);
                
                // Proses setiap error message
                $processedErrors = [];
                foreach ($errors as $error) {
                    // Hapus prefix "The {row}.{field}" dan format ulang
                    $errorText = preg_replace('/^' . preg_quote($attribute, '/') . ' /', '', $error);
                    
                    // Terjemahkan error messages ke bahasa Indonesia
                    $translations = [
                        'has already been taken' => 'sudah pernah digunakan (duplikat)',
                        'is required' => 'harus diisi',
                        'must be a string' => 'harus berupa teks',
                        'must be unique' => 'harus unik (sudah ada di database)',
                        'must be an integer' => 'harus berupa angka',
                        'must be a valid email' => 'harus berupa email yang valid',
                        'must be a valid date' => 'harus berupa tanggal yang valid',
                    ];
                    
                    foreach ($translations as $en => $id) {
                        if (stripos($errorText, $en) !== false) {
                            $errorText = str_ireplace($en, $id, $errorText);
                            break;
                        }
                    }
                    
                    $processedErrors[] = $errorText;
                }
                
                $errorMessages[] = "Baris {$row} (kolom {$fieldName}): " . implode(', ', $processedErrors);
            }
            
            $message = 'Gagal mengimpor murid. Terdapat kesalahan pada data berikut:' . "\n\n" . implode("\n", $errorMessages);
            
            return response()->json([
                'success' => false, 
                'message' => $message
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengimpor murid: ' . $e->getMessage()
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

            Student::whereIn('user_id', $request->ids)->delete();

            return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


}

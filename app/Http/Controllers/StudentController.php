<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Teacher;

class StudentController extends Controller
{
    // Menampilkan data murid
    public function index()
    {
        // Mengambil data murid dengan relasi ke data user dan classroom
        $students = Student::with('user', 'classroom')->get();

        // Kembalikan data dalam bentuk JSON untuk Grid.js
        return response()->json($students->map(function ($student) {
            return [
                'id' => $student->user_id,
                'user' => $student->user,
                'nis' => $student->nis,
                'classroom' => $student->classroom,
                'guardian_name' => $student->guardian_name,
                'guardian_phone' => $student->guardian_phone,
            ];
        })->map(function ($murid) {
            return [
                $murid['user']?->full_name ?? "-",
                $murid['nis'] ?? "-",
                $murid['classroom']?->name ?? "-",
                $murid['guardian_name'] ?? "-",
                $murid['guardian_phone'] ?? "-",
                [
                    'id' => $murid['id'],
                    'user_name' => $murid['user']?->full_name ?? "-",
                    'nis' => $murid['nis'],
                    'class_id' => $murid['classroom']?->id ?? "",
                    'guardian_name' => $murid['guardian_name'],
                    'guardian_phone' => $murid['guardian_phone'],
                ],
            ];
        }));
    }

    // Menambahkan murid baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'user_username' => 'required|exists:users,username',
                'nis' => 'required|unique:students,nis',
                'class_id' => 'required|exists:classes,id',
                'guardian_name' => 'nullable|string|max:255',
                'guardian_phone' => 'nullable|string|max:255',
            ]);

            // Cari user berdasarkan username
            $user = User::where('username', $validated['user_username'])->first();

            // Cek apakah user sudah terdaftar sebagai guru
            if (Teacher::where('user_id', $user->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar sebagai guru, tidak bisa didaftarkan sebagai murid.'], 422);
            }

            // Cek apakah user sudah terdaftar sebagai murid
            if (Student::where('user_id', $user->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar sebagai murid.'], 422);
            }

            // Menambahkan data murid baru
            Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_phone' => $validated['guardian_phone'],
            ]);

            // Tetapkan role student ke user
            $role = Role::where('name', 'student')->first();
            $user->roles()->attach($role);

            return response()->json(['success' => true, 'message' => 'Murid berhasil ditambahkan!']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', ')], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Update data murid
    public function update(Request $request, $id)
    {
        try {
            // Cari data murid berdasarkan ID
            $student = Student::findOrFail($id);

            // Validasi input
            $validated = $request->validate([
                'nis' => 'required|unique:students,nis,' . $id . ',user_id',
                'class_id' => 'required|exists:classes,id',
                'guardian_name' => 'nullable|string|max:255',
                'guardian_phone' => 'nullable|string|max:255',
            ]);

            // Update data murid
            $student->update([
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_phone' => $validated['guardian_phone'],
            ]);

            return response()->json(['success' => true, 'message' => 'Murid berhasil diperbarui!']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', ')], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Hapus data murid
    public function destroy($id)
    {
        try {
            // Cari data murid berdasarkan ID
            $student = Student::findOrFail($id);

            // Hapus data murid
            $student->delete();

            return response()->json(['success' => true, 'message' => 'Murid berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

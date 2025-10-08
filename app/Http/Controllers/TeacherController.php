<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Student;

class TeacherController extends Controller
{
    // Menampilkan data guru
    // 

    public function index()
    {
        // Mengambil data guru dengan relasi ke data user
        $teachers = Teacher::with('user')->get();

        // Kembalikan data dalam bentuk JSON untuk Grid.js
        return response()->json($teachers->map(function ($teacher) {
            return [
                'id' => $teacher->user_id,
                'user' => $teacher->user, // Relasi dengan user
                'nip' => $teacher->nip,
                'department' => $teacher->department,
                'title' => $teacher->title,
            ];
        })->map(function ($guru) {
            return [
                $guru['user']?->full_name ?? "-",
                $guru['nip'] ?? "-",
                $guru['department'] ?? "-",
                $guru['title'] ?? "-",
                [
                    'id' => $guru['id'],
                    'user_name' => $guru['user']?->full_name ?? "-",
                    'nip' => $guru['nip'],
                    'department' => $guru['department'],
                    'title' => $guru['title'],
                ],
            ];
        }));
    }


    // Menambahkan guru baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'user_username' => 'required|exists:users,username',
                'nip' => 'nullable|unique:teachers,nip',
                'department' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
            ]);

            // Cari user berdasarkan username
            $user = User::where('username', $validated['user_username'])->first();

            // Cek apakah user sudah terdaftar sebagai murid
            if (Student::where('user_id', $user->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar sebagai murid, tidak bisa didaftarkan sebagai guru.'], 422);
            }

            // Cek apakah user sudah terdaftar sebagai guru
            if (Teacher::where('user_id', $user->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar sebagai guru.'], 422);
            }

            // Menambahkan data guru baru
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'department' => $validated['department'],
                'title' => $validated['title'],
            ]);

            return response()->json(['success' => true, 'message' => 'Guru berhasil ditambahkan!']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', ')], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Update data guru
    public function update(Request $request, $id)
    {
        try {
            // Cari data guru berdasarkan ID
            $teacher = Teacher::findOrFail($id);

            // Validasi input
            $validated = $request->validate([
                'nip' => 'nullable|unique:teachers,nip,' . $id . ',user_id',
                'department' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
            ]);

            // Update data guru
            $teacher->update([
                'nip' => $validated['nip'],
                'department' => $validated['department'],
                'title' => $validated['title'],
            ]);

            return response()->json(['success' => true, 'message' => 'Guru berhasil diperbarui!']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', ')], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Hapus data guru
    public function destroy($id)
    {
        try {
            // Cari data guru berdasarkan ID
            $teacher = Teacher::findOrFail($id);

            // Hapus data guru
            $teacher->delete();

            return response()->json(['success' => true, 'message' => 'Guru berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function table()
    {
        // Ambil kolom yang dibutuhkan saja
        $users = User::select('id', 'full_name', 'email', 'phone', 'username', 'status')->get();

        // Kembalikan JSON (array of objects)
        return response()->json($users);
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:15|unique:users,phone',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8', // password minimal 8 karakter
            ]);

            // Membuat user baru
            $user = User::create([
                'full_name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'username' => $validated['username'],
                'password_hash' => Hash::make($validated['password']),
                'status' => 'active', // default status bisa diubah sesuai kebutuhan
            ]);

            // Mengembalikan response sukses
            return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan!']);
        } catch (ValidationException $e) {
            // Mengembalikan response gagal validasi
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Mengembalikan response gagal karena error lain
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Cari user berdasarkan ID sebelum validasi
            $user = User::findOrFail($id);

            // Validasi input dengan pengecualian pada user yang sedang diupdate
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id, // Pengecualian untuk email yang sedang diupdate
                'phone' => 'required|string|max:15|unique:users,phone,' . $user->id, // Pengecualian untuk phone yang sedang diupdate
                'username' => 'required|string|max:255|unique:users,username,' . $user->id, // Pengecualian untuk username yang sedang diupdate
                'password' => 'nullable|string|min:8', // password bisa diabaikan
                'status' => 'required|in:active,suspended',
            ]);

            // Update data user
            $user->full_name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->username = $validated['username'];
            $user->status = $validated['status'];

            // Update password hanya jika diisi
            if (!empty($validated['password'])) {
                $user->password_hash = Hash::make($validated['password']);
            }

            $user->save();

            // Mengembalikan response sukses yang konsisten
            return response()->json(['success' => true, 'message' => 'User berhasil diperbarui!']);
        } catch (ValidationException $e) {
            // Mengembalikan response gagal validasi
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Mengembalikan response gagal karena error lain
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Hapus user
            $user->delete();

            // Mengembalikan response sukses yang konsisten
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika user tidak ditemukan
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            // Jika user tidak ditemukan atau error lainnya
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
    }
}

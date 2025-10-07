<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class UserController extends Controller
{
    public function table()
    {
        // Ambil kolom yang dibutuhkan saja
        $users = User::select('full_name', 'email', 'phone', 'username', 'status')->get();

        // Kembalikan JSON (array of objects)
        return response()->json($users);
    }

    public function store(Request $request)
    {
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
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
    // app/Http/Controllers/UserController.php

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8', // password bisa diabaikan jika tidak diubah
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user
        $user->full_name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->username = $validated['username'];
        if ($validated['password']) {
            $user->password_hash = Hash::make($validated['password']); // Update password jika ada perubahan
        }
        $user->save();

        // Mengembalikan response sukses
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // app/Http/Controllers/UserController.php

    public function destroy($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Mengembalikan response sukses
        return response()->json(['message' => 'User deleted successfully']);
    }
}



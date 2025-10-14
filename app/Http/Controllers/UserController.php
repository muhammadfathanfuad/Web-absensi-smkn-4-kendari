<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.manage-user', compact('users'));
    }

    public function table(Request $request)
    {
        $users = User::with('roles', 'teacher', 'student')->get()->map(function ($user) {
            $user->role = $user->roles->first()?->name ?? null;
            return $user;
        });

        return response()->json($users);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:255|unique:users',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password_hash' => $request->password ? bcrypt($request->password) : bcrypt('password'),
            'status' => 'suspended',
        ]);

        // No role attached

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:255|unique:users,phone,' . $id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,suspended',
        ]);

        $updateData = [
            'full_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password_hash'] = bcrypt($request->password);
        }

        $user->update($updateData);

        return response()->json(['success' => true, 'message' => 'User berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete related teacher or student records
        if ($user->teacher) {
            $user->teacher->delete();
        }
        if ($user->student) {
            $user->student->delete();
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'User berhasil dihapus!']);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->with('roles')
            ->get();
        return response()->json($users);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);

            Excel::import(new UsersImport, $request->file('file'));

            return response()->json(['success' => true, 'message' => 'Users berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengimpor users: ' . $e->getMessage()], 500);
        }
    }

    // Bulk delete users
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:users,id',
            ]);

            User::whereIn('id', $request->ids)->delete();

            return response()->json(['success' => true, 'message' => 'User berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Bulk activate users
    public function bulkStatusActive(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:users,id',
            ]);

            User::whereIn('id', $request->ids)->update(['status' => 'active']);

            return response()->json(['success' => true, 'message' => 'Status user berhasil diubah ke aktif!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Bulk suspend users
    public function bulkStatusSuspended(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:users,id',
            ]);

            User::whereIn('id', $request->ids)->update(['status' => 'suspended']);

            return response()->json(['success' => true, 'message' => 'Status user berhasil diubah ke suspended!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('guru.pengaturan-guru');
    }

    public function updateProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ], [
            'full_name.required' => 'Nama lengkap harus diisi.',
            'full_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor handphone maksimal 20 karakter.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            // Update NIP if teacher exists
            if ($user->teacher) {
                $user->teacher->nip = $request->nip;
                $user->teacher->save();
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.',
                    'reset_form' => false
                ]);
            }
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password_hash)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password lama tidak sesuai.'
                    ], 422);
                }
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
            }

            // Update password
            $user->password_hash = Hash::make($request->password);
            $user->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diubah.',
                    'reset_form' => true
                ]);
            }
            return back()->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah password: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:200',
        ], [
            'photo.required' => 'Foto harus dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran foto maksimal 200KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists('users/' . $user->photo)) {
                Storage::disk('public')->delete('users/' . $user->photo);
            }
            
            // Store new photo
            $fileName = time() . '_' . $user->id . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('users', $fileName, 'public');
            
            // Update user photo
            $user->photo = $fileName;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'photo_url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah foto: ' . $e->getMessage()
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $classrooms = \App\Models\Classroom::orderBy('grade')->orderBy('name')->get();
        return view('admin.manage-user', compact('users', 'classrooms'));
    }

    public function table(Request $request)
    {
        $query = User::with('roles', 'teacher', 'student.classroom');
        
        // Filter berdasarkan role (guru atau siswa)
        $roleFilter = $request->input('role_filter');
        if ($roleFilter === 'teacher') {
            // Hanya ambil user yang memiliki role teacher atau memiliki data di tabel teachers
            $query->whereHas('teacher');
        } elseif ($roleFilter === 'student') {
            // Hanya ambil user yang memiliki role student atau memiliki data di tabel students
            $query->whereHas('student');
        }
        
        // Filter siswa berdasarkan kelas
        $classFilter = $request->input('class_filter');
        if ($classFilter && $roleFilter === 'student') {
            $query->whereHas('student', function($q) use ($classFilter) {
                $q->where('class_id', $classFilter);
            });
        }
        
        $users = $query
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderBy('full_name', 'asc')
            ->get()
            ->map(function ($user) {
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

    // Export users to PDF
    public function export(Request $request)
    {
        try {
            // Get all users with their roles and classroom info
            $allUsers = User::with('roles', 'teacher', 'student.classroom')
                ->get()
                ->map(function ($user) {
                    $role = $user->roles->first();
                    $user->role_name = $role ? $role->name : '-';
                    
                    // Determine user type and prepare data
                    if ($user->teacher) {
                        $user->user_type = 'Guru';
                        $user->classroom_info = '-';
                    } elseif ($user->student) {
                        $user->user_type = 'Siswa';
                        // Get classroom info
                        $classroom = $user->student->classroom;
                        if ($classroom) {
                            $user->sort_grade = $classroom->grade ?? 0;
                            $user->sort_class_name = $classroom->name ?? '';
                            $user->classroom_info = ($classroom->grade ?? '') . ' - ' . ($classroom->name ?? '');
                        } else {
                            $user->sort_grade = 0;
                            $user->sort_class_name = '';
                            $user->classroom_info = '-';
                        }
                    } else {
                        $user->user_type = 'Admin';
                        $user->classroom_info = '-';
                    }
                    
                    // Default password is "password"
                    $user->default_password = 'password';
                    
                    return $user;
                });

            // Separate teachers and students (exclude admins)
            $teachers = $allUsers->filter(function ($user) {
                return $user->teacher; // Only teachers, exclude admins
            })->sortBy('full_name')->values();

            // Group students by classroom
            $studentsByClass = $allUsers->filter(function ($user) {
                return $user->student;
            })->groupBy(function ($user) {
                // Group by classroom_info, or 'Tidak Ada Kelas' if no classroom
                return $user->classroom_info ?? 'Tidak Ada Kelas';
            })->map(function ($classStudents, $className) {
                // Sort students within each class by name
                return $classStudents->sortBy('full_name')->values();
            })->sortBy(function ($students, $className) {
                // Sort classes: first by grade (10, 11, 12), then by class name
                $firstStudent = $students->first();
                if ($firstStudent && isset($firstStudent->sort_grade) && isset($firstStudent->sort_class_name)) {
                    // Create sort key: grade * 10000 + first char of class name
                    $gradeSort = ($firstStudent->sort_grade ?? 0) * 10000;
                    $classSort = ord(substr($firstStudent->sort_class_name ?? '', 0, 1)) ?? 0;
                    return $gradeSort + $classSort;
                }
                // For "Tidak Ada Kelas", put it last
                return 999999;
            });

            // Generate filename
            $filename = 'data_user_' . date('YmdHis') . '.pdf';

            // Generate PDF
            $pdf = Pdf::loadView('admin.users-pdf', [
                'teachers' => $teachers,
                'studentsByClass' => $studentsByClass,
                'exportDate' => now()->format('d F Y H:i:s'),
            ]);

            $totalStudents = $studentsByClass->sum(function ($students) {
                return $students->count();
            });
            
            Log::info('Exporting users to PDF', [
                'teachers_count' => $teachers->count(),
                'students_count' => $totalStudents,
                'classes_count' => $studentsByClass->count()
            ]);

            // Return PDF download (download() method already sets Content-Disposition: attachment header)
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Export users error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Return JSON error for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport data: ' . $e->getMessage()
                ], 500);
            }

            // Redirect back with error message for regular requests
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}

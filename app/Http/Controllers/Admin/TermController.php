<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.terms.index');
    }

    /**
     * Get terms data for GridJS
     */
    public function data()
    {
        $terms = Term::select(['id', 'name', 'start_date', 'end_date', 'is_active', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($terms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama semester harus diisi',
            'name.string' => 'Nama semester harus berupa teks',
            'name.max' => 'Nama semester maksimal 255 karakter',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid',
            'end_date.required' => 'Tanggal berakhir harus diisi',
            'end_date.date' => 'Tanggal berakhir harus berupa tanggal yang valid',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai',
            'is_active.boolean' => 'Status aktif harus berupa boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // If setting as active, deactivate other terms
            if ($request->is_active) {
                Term::where('is_active', true)->update(['is_active' => false]);
            }

            $term = Term::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil ditambahkan',
                'data' => $term
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $term = Term::find($id);
        
        if (!$term) {
            return response()->json([
                'success' => false,
                'message' => 'Semester tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $term
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama semester harus diisi',
            'name.string' => 'Nama semester harus berupa teks',
            'name.max' => 'Nama semester maksimal 255 karakter',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid',
            'end_date.required' => 'Tanggal berakhir harus diisi',
            'end_date.date' => 'Tanggal berakhir harus berupa tanggal yang valid',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai',
            'is_active.boolean' => 'Status aktif harus berupa boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // If setting as active, deactivate other terms
            if ($request->is_active && !$term->is_active) {
                Term::where('is_active', true)->update(['is_active' => false]);
            }

            $term->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil diperbarui',
                'data' => $term
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $term = Term::find($id);
            
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();

            // Check if term has timetables
            if ($term->timetables()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus semester yang memiliki jadwal pelajaran'
                ], 422);
            }

            $term->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil dihapus',
                'data' => $term
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all terms
     */
    public function deleteAll()
    {
        try {
            DB::beginTransaction();

            // Check if any term has timetables
            $termsWithTimetables = Term::whereHas('timetables')->count();
            if ($termsWithTimetables > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus semua semester karena ada yang memiliki jadwal pelajaran'
                ], 422);
            }

            Term::truncate();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Semua semester berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active term
     */
    public function getActive()
    {
        $activeTerm = Term::where('is_active', true)->first();
        
        return response()->json([
            'success' => true,
            'data' => $activeTerm
        ]);
    }
}

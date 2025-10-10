<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalPelajaranImport;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        try {
            Excel::import(new JadwalPelajaranImport, $request->file('file'));

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diimpor.']);
        } catch (\Exception $e) {
            Log::error('Import jadwal gagal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengimpor jadwal: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class LeaveRequestController extends Controller
{
    /**
     * Display the leave request form
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent leave requests for the student with pagination
        $recentRequests = LeaveRequest::where('student_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('murid.permohonan-izin', compact('recentRequests'));
    }

    /**
     * Store a new leave request
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        Log::info('Leave Request Data:', $request->all());
        
        // Get the correct end date field based on leave type
        $endDateField = $request->jenisIzin === 'lainnya' ? 'tanggalSelesai' : 'tanggalSelesaiNormal';
        $endDate = $request->input($endDateField);
        
        // Fallback: if the primary field is empty, try the other one
        if (!$endDate) {
            $endDate = $request->jenisIzin === 'lainnya' ? $request->tanggalSelesaiNormal : $request->tanggalSelesai;
        }
        
        Log::info('End date field: ' . $endDateField);
        Log::info('End date value: ' . $endDate);

        $validator = Validator::make($request->all(), [
            'jenisIzin' => 'required|string|in:sakit,izin,keperluan-keluarga,acara-keluarga,lainnya',
            'jenisIzinCustom' => 'nullable|string|max:255',
            'tanggalMulai' => 'required|date|after_or_equal:today',
            'tanggalSelesai' => 'nullable|date|after_or_equal:tanggalMulai',
            'tanggalSelesaiNormal' => 'nullable|date|after_or_equal:tanggalMulai',
            'alasan' => 'required|string|max:1000',
            'dokumenPendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512'
        ], [
            'jenisIzin.required' => 'Jenis izin harus dipilih.',
            'tanggalMulai.required' => 'Tanggal mulai harus diisi.',
            'tanggalMulai.after_or_equal' => 'Tanggal mulai tidak boleh lebih dari hari ini.',
            'tanggalSelesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'tanggalSelesaiNormal.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'alasan.required' => 'Alasan izin harus diisi.',
            'alasan.max' => 'Alasan izin maksimal 1000 karakter.',
            'dokumenPendukung.mimes' => 'Dokumen pendukung harus berupa PDF, JPG, atau PNG.',
            'dokumenPendukung.max' => 'Dokumen pendukung maksimal 500KB.'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Manual validation for end date
        if (!$endDate) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal selesai harus diisi.',
                'errors' => ['tanggalSelesai' => ['Tanggal selesai harus diisi.']]
            ], 422);
        }
        
        // Manual validation for custom leave type
        if ($request->jenisIzin === 'lainnya' && empty($request->jenisIzinCustom)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis izin lainnya harus diisi.',
                'errors' => ['jenisIzinCustom' => ['Jenis izin lainnya harus diisi.']]
            ], 422);
        }

        try {
            $user = Auth::user();
            Log::info('User ID: ' . $user->id);
            
            // Check for existing duplicate request within the last 5 seconds
            $existingRequest = LeaveRequest::where('student_id', $user->id)
                ->where('leave_type', $request->jenisIzin)
                ->where('start_date', $request->tanggalMulai)
                ->where('end_date', $endDate)
                ->where('reason', $request->alasan)
                ->where('created_at', '>=', now()->subSeconds(5))
                ->first();
            
            if ($existingRequest) {
                Log::warning('Duplicate request detected within 5 seconds, ignoring.');
                return response()->json([
                    'success' => true,
                    'message' => 'Permohonan izin berhasil diajukan dan akan diproses dalam 1-2 hari kerja.',
                    'data' => $existingRequest
                ]);
            }
            
            // Handle file upload
            $supportingDocument = null;
            if ($request->hasFile('dokumenPendukung')) {
                $file = $request->file('dokumenPendukung');
                $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
                $supportingDocument = $file->storeAs('leave_requests', $filename, 'public');
                Log::info('File uploaded: ' . $supportingDocument);
            }

            // Prepare data for insertion
            $data = [
                'student_id' => $user->id,
                'leave_type' => $request->jenisIzin,
                'custom_leave_type' => $request->jenisIzin === 'lainnya' ? $request->jenisIzinCustom : null,
                'start_date' => $request->tanggalMulai,
                'end_date' => $endDate,
                'reason' => $request->alasan,
                'supporting_document' => $supportingDocument,
                'status' => 'pending'
            ];
            
            Log::info('Data to insert:', $data);

            // Create leave request
            $leaveRequest = LeaveRequest::create($data);
            
            Log::info('Leave request created with ID: ' . $leaveRequest->id);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin berhasil diajukan dan akan diproses dalam 1-2 hari kerja.',
                'data' => $leaveRequest
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in leave request store:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan permohonan izin.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave request history
     */
    public function history()
    {
        $user = Auth::user();
        
        $leaveRequests = LeaveRequest::where('student_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('murid.riwayat-permohonan-izin', compact('leaveRequests'));
    }

    /**
     * Show specific leave request
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $leaveRequest = LeaveRequest::with(['student', 'processedBy', 'teacherNotes.teacher', 'teacherNotes.subject'])
            ->where('student_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // If AJAX request, return JSON for modal
        if (request()->expectsJson() || request()->wantsJson()) {
            // Add full URL for supporting document if it exists
            if ($leaveRequest->supporting_document) {
                // Use asset() helper to get the proper URL
                $documentPath = str_replace('storage/', '', $leaveRequest->supporting_document);
                $leaveRequest->document_url = asset('storage/' . $leaveRequest->supporting_document);
                Log::info('Document URL: ' . $leaveRequest->document_url);
            }
            
            return response()->json([
                'success' => true,
                'leaveRequest' => $leaveRequest
            ]);
        }

        return view('murid.detail-permohonan-izin', compact('leaveRequest'));
    }
}
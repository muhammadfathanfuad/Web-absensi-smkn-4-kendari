<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionDelegation;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class DelegationController extends Controller
{
    // Menampilkan halaman delegasi saya (untuk murid)
    public function index()
    {
        $userId = Auth::id();
        $today = TimeOverrideService::now();
        $todayName = strtolower($today->dayName);
        
        // Ambil delegasi aktif untuk murid ini
        $myDelegations = SessionDelegation::with([
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetable.classSubject.teacher.user',
            'originalTeacher.user'
        ])
        ->where('delegated_to_user_id', $userId)
        ->where('status', 'active')
        ->where(function($query) {
            $query->where('type', 'permanent')
                  ->orWhere(function($q) {
                      $q->where('type', 'temporary')
                        ->where('valid_until', '>=', TimeOverrideService::now()->toDateString());
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('murid.delegasi', compact('myDelegations', 'today', 'todayName'));
    }
}

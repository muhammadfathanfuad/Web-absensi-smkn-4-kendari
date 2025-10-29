<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionDelegation;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class DelegationController extends Controller
{
    // Menampilkan halaman delegasi saya
    public function index()
    {
        $userId = Auth::id();
        $today = TimeOverrideService::now();
        $todayName = strtolower($today->dayName);
        
        // Ambil delegasi aktif untuk user ini
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
        
        return view('guru.delegasi', compact('myDelegations', 'today', 'todayName'));
    }

    // Get delegasi hari ini untuk count badge
    public function getTodayCount()
    {
        $userId = Auth::id();
        $today = now()->format('l'); // Monday, Tuesday, etc.
        
        $count = SessionDelegation::where('delegated_to_user_id', $userId)
            ->where('status', 'active')
            ->whereHas('timetable', function($query) use ($today) {
                $query->where('day_of_week', $today);
            })
            ->where(function($q) {
                $q->where('type', 'permanent')
                  ->orWhere(function($query) {
                      $query->where('type', 'temporary')
                            ->where('valid_until', '>=', now()->toDateString());
                  });
            })
            ->count();
        
        return response()->json(['count' => $count]);
    }
}

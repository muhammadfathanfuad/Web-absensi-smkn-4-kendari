<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TimeOverrideService;

class TimeOverrideController extends Controller
{
    public function index()
    {
        $timeInfo = TimeOverrideService::getInfo();
        $scenarios = TimeOverrideService::getPresetScenarios();
        
        return view('time-override.index', compact('timeInfo', 'scenarios'));
    }

    public function setTime(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i'
        ]);

        // Convert HH:MM to HH:MM:SS format
        $time = $request->time . ':00';

        TimeOverrideService::setOverride($request->date, $time);

        return response()->json([
            'success' => true,
            'message' => 'Time override berhasil diset!',
            'time_info' => TimeOverrideService::getInfo()
        ]);
    }

    public function clearTime()
    {
        TimeOverrideService::clearOverride();

        return response()->json([
            'success' => true,
            'message' => 'Time override berhasil dihapus!',
            'time_info' => TimeOverrideService::getInfo()
        ]);
    }

    public function getStatus()
    {
        return response()->json(TimeOverrideService::getInfo());
    }

    public function getJSData()
    {
        $timeInfo = TimeOverrideService::getInfo();
        
        return response()->json([
            'is_active' => $timeInfo['is_active'],
            'current_time' => $timeInfo['current_time'],
            'real_time' => $timeInfo['real_time'],
            'override_datetime' => $timeInfo['override_datetime'],
            'js_time' => TimeOverrideService::toISOString()
        ]);
    }
}

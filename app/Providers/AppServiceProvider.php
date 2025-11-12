<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\TeacherLeaveRequest;
use App\Models\AttendanceSession;
use App\Observers\AttendanceObserver;
use App\Observers\LeaveRequestObserver;
use App\Observers\TeacherLeaveRequestObserver;
use App\Observers\AttendanceSessionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Attendance::observe(AttendanceObserver::class);
        LeaveRequest::observe(LeaveRequestObserver::class);
        TeacherLeaveRequest::observe(TeacherLeaveRequestObserver::class);
        AttendanceSession::observe(AttendanceSessionObserver::class);
    }
}

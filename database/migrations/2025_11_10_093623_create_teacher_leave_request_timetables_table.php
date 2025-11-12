<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_leave_request_timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_leave_request_id');
            $table->unsignedBigInteger('timetable_id');
            $table->date('leave_date'); // Tanggal spesifik untuk jadwal ini
            $table->timestamps();
            
            $table->foreign('teacher_leave_request_id', 'tlr_timetables_request_fk')
                  ->references('id')->on('teacher_leave_requests')->onDelete('cascade');
            $table->foreign('timetable_id', 'tlr_timetables_timetable_fk')
                  ->references('id')->on('timetables')->onDelete('cascade');
            
            $table->unique(['teacher_leave_request_id', 'timetable_id', 'leave_date'], 'unique_request_timetable_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_leave_request_timetables');
    }
};

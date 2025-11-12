<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated migration for attendances table.
     * Combines:
     * - create_attendances_table (original structure)
     * - add_checkout_time_to_attendances_table (check_out_time)
     * - add_session_fields_to_attendances_table (session_id, session_number, is_on_time, late_minutes)
     * 
     * Final structure includes all session-related fields
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_session_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->enum('status', ['H', 'S', 'I', 'A', 'T']); // H=Hadir, S=Sakit, I=Izin, A=Alpha, T=Terlambat
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->integer('session_number')->default(1);
            $table->boolean('is_on_time')->default(true);
            $table->integer('late_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('class_session_id')->references('id')->on('class_sessions')->cascadeOnDelete();
            // Note: session_id foreign key will be added in separate migration after attendance_sessions table is created
            $table->foreign('student_id')->references('user_id')->on('students')->cascadeOnDelete();

            // Unique constraint: prevent duplicate attendance for same session and student
            $table->unique(['class_session_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};


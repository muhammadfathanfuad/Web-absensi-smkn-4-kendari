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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id');
            $table->unsignedBigInteger('teacher_id');
            $table->integer('session_number')->default(1); // 1, 2, 3, dst
            $table->string('session_token')->unique();
            $table->json('qr_data');
            $table->enum('session_type', ['on_time', 'late_tolerance', 'late'])->default('on_time');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('timetable_id')->references('id')->on('timetables')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();

            // Indexes
            $table->index(['timetable_id', 'teacher_id']);
            $table->index(['timetable_id', 'teacher_id', 'session_number']);
            $table->index('expires_at');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};

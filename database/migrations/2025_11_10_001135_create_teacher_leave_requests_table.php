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
        Schema::create('teacher_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('timetable_id');
            $table->date('leave_date');
            $table->string('leave_type'); // sakit, izin, keperluan-keluarga, acara-keluarga, lainnya
            $table->string('custom_leave_type')->nullable(); // untuk jenis izin lainnya
            $table->text('reason');
            $table->string('supporting_document')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('substitute_user_id')->nullable(); // guru atau siswa pengganti
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('timetable_id')->references('id')->on('timetables')->cascadeOnDelete();
            $table->foreign('substitute_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_leave_requests');
    }
};

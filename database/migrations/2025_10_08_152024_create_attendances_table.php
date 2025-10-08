<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_session_id'); // Menghubungkan ke sesi kelas mana
            $table->unsignedBigInteger('student_id');       // Menunjukkan siapa siswanya
            $table->enum('status', ['H', 'S', 'I', 'A', 'T']);    // H=Hadir, S=Sakit, I=Izin, A=Alpha
            $table->time('check_in_time')->nullable();      // Jam masuk siswa saat scan
            $table->text('notes')->nullable();              // Catatan tambahan (opsional)
            $table->timestamps();

            // Foreign Keys (Kunci Relasi)
            $table->foreign('class_session_id')->references('id')->on('class_sessions')->cascadeOnDelete();
            $table->foreign('student_id')->references('user_id')->on('students')->cascadeOnDelete();

            // Mencegah satu siswa diabsen dua kali di sesi yang sama
            $table->unique(['class_session_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
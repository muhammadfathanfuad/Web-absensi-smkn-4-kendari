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
        Schema::create('student_presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // user_id dari students
            $table->date('date'); // Tanggal kehadiran
            $table->enum('status', ['H', 'A', 'I', 'S'])->default('H'); // H=Hadir, A=Alfa, I=Izin, S=Sakit
            $table->time('check_in_time')->nullable(); // Waktu scan QR pertama kali
            $table->text('notes')->nullable(); // Catatan opsional
            $table->timestamps();

            // Foreign Keys
            $table->foreign('student_id')->references('user_id')->on('students')->cascadeOnDelete();

            // Indexes
            $table->unique(['student_id', 'date']); // Satu siswa hanya bisa satu record per hari
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_presences');
    }
};

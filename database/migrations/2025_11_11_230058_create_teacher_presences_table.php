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
        Schema::create('teacher_presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // user_id dari teachers
            $table->date('date'); // Tanggal kehadiran
            $table->enum('status', ['H', 'A', 'I', 'S'])->default('H'); // H=Hadir, A=Alfa, I=Izin, S=Sakit
            $table->time('check_in_time')->nullable(); // Waktu tombol ditekan
            $table->text('notes')->nullable(); // Catatan opsional
            $table->timestamps();

            // Foreign Keys
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();

            // Indexes
            $table->unique(['teacher_id', 'date']); // Satu guru hanya bisa satu record per hari
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_presences');
    }
};

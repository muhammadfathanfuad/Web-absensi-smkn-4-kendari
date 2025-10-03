<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();

            // relasi akademik
            $table->unsignedBigInteger('timetable_id');     // FK -> timetables.id
            $table->date('date');
            $table->time('start_time_actual')->nullable();
            $table->time('end_time_actual')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'canceled'])->default('scheduled');

            // QR
            $table->string('qr_token')->nullable();
            $table->dateTime('qr_expires_at')->nullable();

            // auditor/penanggung jawab
            $table->unsignedBigInteger('opened_by')->nullable(); // FK -> users.id
            $table->unsignedBigInteger('closed_by')->nullable(); // FK -> users.id

            // // opsional: telemetri ringan versi absensi (boleh dihapus jika tak perlu)
            // $table->integer('last_activity')->nullable();
            // $table->ipAddress('ip_address')->nullable();
            // $table->text('user_agent')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('timetable_id')->references('id')->on('timetables')->cascadeOnDelete();
            $table->foreign('opened_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('closed_by')->references('id')->on('users')->nullOnDelete();

            // Unik per jadwal per tanggal (seperti requirement awal)
            $table->unique(['timetable_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};

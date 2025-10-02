<?php

// database/migrations/..._create_sessions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id');  // Pastikan menggunakan unsignedBigInteger untuk mengarah ke timetables.id
            $table->date('date');
            $table->time('start_time_actual')->nullable();
            $table->time('end_time_actual')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'canceled']);
            $table->string('qr_token')->nullable();
            $table->dateTime('qr_expires_at')->nullable();
            $table->unsignedBigInteger('opened_by')->nullable(); // Mengarah ke users.id, menggunakan UUID
            $table->unsignedBigInteger('closed_by')->nullable(); // Mengarah ke users.id, menggunakan UUID
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('timetable_id')->references('id')->on('timetables')->cascadeOnDelete();  // Mengarah ke timetables.id
            $table->foreign('opened_by')->references('id')->on('users')->cascadeOnDelete();  // Mengarah ke users.id
            $table->foreign('closed_by')->references('id')->on('users')->cascadeOnDelete();  // Mengarah ke users.id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
}

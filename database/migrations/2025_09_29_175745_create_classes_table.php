<?php

// database/migrations/..._create_classes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();  // Kolom id sebagai primary key bertipe BIGINT
            $table->string('name')->unique();  // Nama kelas
            $table->integer('grade');          // Grade (misal: X, XI, XII)
            $table->unsignedBigInteger('homeroom_teacher_id');  // FK ke teachers.user_id (UUID)
            $table->unsignedBigInteger('room_id')->nullable();  // Gunakan unsignedBigInteger untuk room_id (FK ke rooms.id)
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('homeroom_teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();  // Mengarah ke teachers.user_id
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();  // Mengarah ke rooms.id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
}

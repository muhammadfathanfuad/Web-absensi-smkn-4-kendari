<?php

// database/migrations/2025_10_01_000004_create_class_subjects_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassSubjectsTable extends Migration
{
    public function up(): void
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');  // Gunakan unsignedBigInteger untuk class_id
            $table->unsignedBigInteger('subject_id');  // Gunakan unsignedBigInteger untuk subject_id
            $table->unsignedBigInteger('teacher_id');  // Gunakan UUID untuk teacher_id agar kompatibel dengan teachers.user_id
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();  // Menambahkan FK ke classes.id
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();  // Menambahkan FK ke subjects.id
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();  // Menambahkan FK ke teachers.user_id

            // Mencegah duplikasi
            $table->unique(['class_id', 'subject_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
}

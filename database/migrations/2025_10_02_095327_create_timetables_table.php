<?php

// database/migrations/..._create_timetables_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();  // Tipe data default BIGINT
            $table->unsignedBigInteger('term_id');   // FK ke terms.id
            $table->unsignedBigInteger('class_id');  // FK ke classes.id
            $table->unsignedBigInteger('subject_id');  // FK ke subjects.id
            
            // Mengubah teacher_id dari unsignedBigInteger menjadi uuid
            $table->unsignedBigInteger('teacher_id');  // FK ke teachers.user_id (UUID)

            $table->tinyInteger('day_of_week'); // 1=Senin ... 7=Minggu
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('room_id')->nullable();  // FK ke rooms.id
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnDelete();  // Mengarah ke terms.id
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();  // Mengarah ke classes.id
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();  // Mengarah ke subjects.id
            
            // Mengubah FK teacher_id yang sebelumnya unsignedBigInteger menjadi uuid, dan mengarah ke teachers.user_id
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();  // Mengarah ke teachers.user_id
            
            // Mengarah ke rooms.id
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
}


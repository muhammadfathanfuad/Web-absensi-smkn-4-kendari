<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated migration for classes table.
     * Combines:
     * - create_classes_table (original structure)
     * - make_homeroom_teacher_id_nullable_in_classes_table (homeroom_teacher_id nullable)
     * - change_grade_column_to_string_in_classes_table (grade: integer -> string)
     * - add_xi_class_system_to_classes_table (group_type, location_preference)
     * - remove_unique_constraint_from_classes_name_column (name: unique -> not unique)
     * 
     * Final structure: name is NOT unique, grade is string, homeroom_teacher_id is nullable
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // NOT unique (constraint was removed)
            $table->string('grade'); // Changed from integer to string
            $table->unsignedBigInteger('homeroom_teacher_id')->nullable(); // Changed to nullable
            $table->unsignedBigInteger('room_id')->nullable();
            $table->enum('group_type', ['A', 'B'])->nullable()->comment('Group type for XI classes: A or B');
            $table->enum('location_preference', ['lab', 'theory'])->nullable()->comment('Location preference for XI classes');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('homeroom_teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};


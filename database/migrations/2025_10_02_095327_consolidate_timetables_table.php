<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated migration for timetables table.
     * Combines:
     * - create_timetables_table (original structure)
     * - add_type_and_week_to_timetables_table (type, week_type)
     * - add_class_subject_id_to_timetables_table (class_subject_id + FK)
     * - add_week_id_and_date_to_timetables_table (week_id, date + FK)
     * - drop_old_columns_from_timetables_table (dropped: class_id, subject_id, teacher_id, room_id, is_active)
     * - add_xi_class_system_to_timetables_table (group_type, location_type, week_alternation)
     * 
     * Final structure uses class_subject_id instead of separate class_id, subject_id, teacher_id
     */
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('class_subject_id')->nullable();
            $table->unsignedBigInteger('week_id')->nullable();
            $table->tinyInteger('day_of_week'); // 1=Senin ... 7=Minggu
            $table->date('date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['teori', 'praktik'])->default('teori');
            $table->enum('week_type', ['ganjil', 'genap'])->nullable();
            $table->enum('group_type', ['A', 'B'])->nullable()->comment('Group type for XI classes: A or B');
            $table->enum('location_type', ['lab', 'theory'])->nullable()->comment('Location type: lab or theory');
            $table->enum('week_alternation', ['ganjil', 'genap'])->nullable()->comment('Week alternation for XI classes');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnDelete();
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->nullOnDelete();
            // Note: week_id foreign key will be added in separate migration after weeks table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};


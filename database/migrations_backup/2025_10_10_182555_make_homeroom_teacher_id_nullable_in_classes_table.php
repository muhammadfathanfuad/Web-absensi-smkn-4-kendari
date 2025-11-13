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
        Schema::table('classes', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['homeroom_teacher_id']);
            // Make homeroom_teacher_id nullable
            $table->unsignedBigInteger('homeroom_teacher_id')->nullable()->change();
            // Add back foreign key constraint
            $table->foreign('homeroom_teacher_id')->references('user_id')->on('teachers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['homeroom_teacher_id']);
            // Make homeroom_teacher_id not nullable
            $table->unsignedBigInteger('homeroom_teacher_id')->nullable(false)->change();
            // Add back foreign key constraint
            $table->foreign('homeroom_teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();
        });
    }
};

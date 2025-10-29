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
        Schema::create('session_delegations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id');
            $table->unsignedBigInteger('original_teacher_id');
            $table->unsignedBigInteger('delegated_to_user_id');
            $table->enum('type', ['permanent', 'temporary'])->default('temporary');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('status', ['active', 'expired', 'revoked'])->default('active');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('timetable_id')->references('id')->on('timetables')->cascadeOnDelete();
            $table->foreign('original_teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();
            $table->foreign('delegated_to_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_delegations');
    }
};

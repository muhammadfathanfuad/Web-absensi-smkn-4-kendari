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
        Schema::table('leave_requests', function (Blueprint $table) {
            // Field untuk melacak guru yang sudah menyetujui
            $table->json('approved_by_teachers')->nullable()->comment('Array of teacher user_ids who approved this request');
            
            // Field untuk melacak guru yang menolak
            $table->json('rejected_by_teachers')->nullable()->comment('Array of teacher user_ids who rejected this request');
            
            // Field untuk melacak status keseluruhan (approved, rejected, partially_approved)
            $table->enum('overall_status', ['pending', 'approved', 'rejected', 'partially_approved'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['approved_by_teachers', 'rejected_by_teachers', 'overall_status']);
        });
    }
};

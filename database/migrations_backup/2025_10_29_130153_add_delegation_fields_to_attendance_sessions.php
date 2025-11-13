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
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('opened_by_user_id')->nullable()->after('teacher_id');
            $table->foreign('opened_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->boolean('is_delegated')->default(false)->after('opened_by_user_id');
            $table->text('delegation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['opened_by_user_id']);
            $table->dropColumn(['opened_by_user_id', 'is_delegated', 'delegation_reason']);
        });
    }
};

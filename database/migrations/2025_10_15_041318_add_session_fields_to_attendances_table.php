<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->nullable()->after('class_session_id');
            $table->integer('session_number')->default(1)->after('session_id');
            $table->boolean('is_on_time')->default(true)->after('session_number');
            $table->integer('late_minutes')->default(0)->after('is_on_time');

            // Foreign key
            $table->foreign('session_id')->references('id')->on('attendance_sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn(['session_id', 'session_number', 'is_on_time', 'late_minutes']);
        });
    }
};
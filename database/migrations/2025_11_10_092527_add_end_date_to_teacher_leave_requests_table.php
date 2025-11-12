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
        Schema::table('teacher_leave_requests', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('leave_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_leave_requests', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
};

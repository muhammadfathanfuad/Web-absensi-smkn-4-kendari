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
        Schema::table('student_presences', function (Blueprint $table) {
            $table->unsignedInteger('approval_count')->default(0)->after('status')->comment('Jumlah guru yang menyetujui izin');
            $table->unsignedInteger('rejection_count')->default(0)->after('approval_count')->comment('Jumlah guru yang menolak izin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_presences', function (Blueprint $table) {
            $table->dropColumn(['approval_count', 'rejection_count']);
        });
    }
};

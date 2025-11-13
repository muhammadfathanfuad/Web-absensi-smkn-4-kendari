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
        Schema::table('timetables', function (Blueprint $table) {
            $table->unsignedBigInteger('week_id')->nullable()->after('term_id');
            $table->date('date')->nullable()->after('day_of_week');
            $table->foreign('week_id')->references('id')->on('weeks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['week_id']);
            $table->dropColumn(['week_id', 'date']);
        });
    }
};

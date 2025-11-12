<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign key for week_id to timetables table.
     * This is separated because weeks table is created after timetables table.
     */
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
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
        });
    }
};

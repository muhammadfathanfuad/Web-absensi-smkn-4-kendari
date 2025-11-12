<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drop the unused attendance_records table.
     * This table was created but never used - it has no columns except id and timestamps,
     * no data, and no foreign key references from other tables.
     */
    public function up(): void
    {
        Schema::dropIfExists('attendance_records');
    }

    /**
     * Reverse the migrations.
     * Recreate the table if needed (though it was never used).
     */
    public function down(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};

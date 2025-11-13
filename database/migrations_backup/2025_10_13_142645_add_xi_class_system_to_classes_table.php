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
            // Add XI class system columns
            $table->enum('group_type', ['A', 'B'])->nullable()->after('grade')->comment('Group type for XI classes: A or B');
            $table->enum('location_preference', ['lab', 'theory'])->nullable()->after('group_type')->comment('Location preference for XI classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Drop XI class system columns
            $table->dropColumn(['group_type', 'location_preference']);
        });
    }
};

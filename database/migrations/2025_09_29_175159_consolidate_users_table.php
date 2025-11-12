<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated migration for users table.
     * Combines:
     * - create_users_table
     * - make_username_nullable (username was later dropped)
     * - drop_username_from_users_table
     * - add_photo_column_to_users_table
     * 
     * Note: username column is NOT included (was dropped in later migration)
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password_hash');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_ip')->nullable();
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


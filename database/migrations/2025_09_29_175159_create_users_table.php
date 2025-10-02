<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Menggunakan auto-increment untuk ID (1, 2, 3, dst)
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_ip')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

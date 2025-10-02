<?php

// database/migrations/2025_10_01_000007_create_rooms_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key, bertipe BIGINT
            $table->string('name')->unique(); // Nama ruang, misalnya 'Ruang 101'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
}

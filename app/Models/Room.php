<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Tentukan kolom yang bisa diisi secara mass-assignment
    protected $fillable = ['name'];

    // Relasi dengan kelas, jika ada
    public function classes()
    {
        return $this->hasMany(Classroom::class, 'room_id');
    }
}


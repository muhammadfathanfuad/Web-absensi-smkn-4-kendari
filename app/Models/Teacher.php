<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Teacher extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'user_id'; // Menggunakan 'user_id' sebagai primary key
    public $incrementing = false; // Non-incrementing karena menggunakan UUID
    protected $keyType = 'string'; // Gunakan tipe string (UUID)

    // Relasi ke kelas
    public function classes()
    {
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }
}


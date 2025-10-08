<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id'; // Menggunakan 'user_id' sebagai primary key
    public $incrementing = false; // Non-incrementing karena foreign key
    protected $keyType = 'int'; // Gunakan tipe int untuk user_id

    protected $fillable = ['user_id', 'nip', 'department', 'title'];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke kelas
    public function classes()
    {
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }
}


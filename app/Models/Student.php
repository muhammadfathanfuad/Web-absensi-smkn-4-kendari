<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'user_id'; // Menggunakan 'user_id' sebagai primary key
    public $incrementing = false; // Non-incrementing karena foreign key
    protected $keyType = 'int'; // Gunakan tipe int untuk user_id

    protected $fillable = [
        'user_id',
        'nis',
        'class_id',
        'guardian_name',
        'guardian_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}


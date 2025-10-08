<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    // Menentukan kolom room_id menggunakan unsignedBigInteger
    protected $casts = [
        'room_id' => 'integer',  // Pastikan room_id bertipe integer atau unsignedBigInteger
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes'; // <-- TAMBAHKAN BARIS INI

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
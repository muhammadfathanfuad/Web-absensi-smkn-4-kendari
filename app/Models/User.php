<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tidak perlu mendefinisikan $keyType dan $incrementing jika menggunakan auto-increment
    // Laravel secara otomatis mengatur ini jika menggunakan $table->id() di migrasi.

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}

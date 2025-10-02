<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'qr_token', 'qr_expires_at', 'opened_by', 'closed_by'];

    // Jika menggunakan UUID, set model dengan tipe UUID
    public $incrementing = false; // Non-incrementing ID karena menggunakan UUID
    protected $keyType = 'string'; // Menentukan tipe key sebagai string (UUID)

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}

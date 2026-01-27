<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed(); // Menggunakan withTrashed untuk mengambil data user yang sudah dihapus
    }

    // Relasi dengan Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

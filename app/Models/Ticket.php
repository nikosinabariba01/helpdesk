<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'Jenis_Pengaduan',
        'Lokasi',
        'Tanggal_Pengaduan',
        'Tanggal_Selesai',
        'status',
        'Detail',
        'gambar',
        'user_id',
        'asignee_id'
    ];

    protected $dates = ['Tanggal_Pengaduan'];

    public function getFormattedTanggalPengaduanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function asignee()
{
    return $this->belongsTo(User::class, 'asignee_id');
}


    
}

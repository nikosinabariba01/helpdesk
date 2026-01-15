<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'creator_id'
    ];

    // Relasi dengan pengguna yang membuat pengumuman (One-to-Many)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Relasi dengan penerima pengumuman (Many-to-Many via pivot table `target_pengumuman`)
    public function penerima()
    {
        return $this->belongsToMany(User::class, 'target_pengumuman', 'pengumuman_id', 'user_id');
    }
}

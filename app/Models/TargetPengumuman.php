<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPengumuman extends Model
{
    use HasFactory;

    protected $table = 'target_pengumuman';

    // Relasi dengan pengumuman (Many-to-One)
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class);
    }

    // Relasi dengan pengguna yang menerima pengumuman (Many-to-One)
    public function pengguna()
    {
        return $this->belongsTo(User::class);
    }
}

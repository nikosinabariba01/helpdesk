<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Guid\Guid as RamseyUuid;

class Ticket extends Model {
    use HasFactory;

    protected $keyType   = 'string'; // Tambahkan ini: Primary key adalah string (UUID)
    public $incrementing = false;    // Tambahkan ini: Tidak auto-increment

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
        'asignee_id',
    ];

    protected $dates = ['Tanggal_Pengaduan'];

    public function getFormattedTanggalPengaduanAttribute() {
        return $this->created_at->diffForHumans();
    }

    // Menambahkan event untuk auto-generate UUID v7 saat model dibuat
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                // Generate UUID v7 using RamseyUuid
                $model->id = RamseyUuid::uuid7()->toString();
            }
        });
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // Relasi dengan TicketAssignee
    public function asignees()
    {
        // Gunakan TicketAssignee sebagai pivot model
        return $this->belongsToMany(User::class, 'ticket_assignees', 'ticket_id', 'user_id');
    }
}

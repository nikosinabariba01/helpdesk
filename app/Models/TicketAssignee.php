<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAssignee extends Model {
    use HasFactory;

    // Menentukan kolom yang digunakan oleh model ini
    protected $table = 'ticket_assignees';

    // Relasi dengan Ticket
    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    // Relasi dengan User
    public function user() {
        return $this->belongsTo(User::class);
    }
}

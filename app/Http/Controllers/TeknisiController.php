<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller {
    public function index() {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang belum memiliki asignees (belum ada yang meng-assign) dan status 'open'
        $teknisi_data_ticket = Ticket::with('user')
            ->whereDoesntHave('asignees') // Mengambil tiket yang belum memiliki asignees
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total tiket yang sedang dalam proses (on process) berdasarkan asignee yang memiliki user_id yang sedang login
        $totalOnProcessTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'on process')
            ->count();

        // Hitung total tiket yang berstatus 'close' berdasarkan asignee yang memiliki user_id yang sedang login
        $totalClosedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'close')
            ->count();

        $totalAllTickets = Ticket::count(); // Total tiket keseluruhan

        $totalTickets = $teknisi_data_ticket->count(); // Total tiket yang belum memiliki asignees

        return view('teknisi', compact('teknisi_data_ticket', 'totalTickets', 'totalOnProcessTickets', 'totalClosedTickets', 'totalAllTickets'));
    }

    public function viewasigne() {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang sudah memiliki asignees yang sesuai dengan user_id yang sedang login dan status 'open' atau 'on process'
        $teknisi_data_ticket = Ticket::with('user')
            ->whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where(function ($query) {
                $query->where('status', 'open')
                    ->orWhere('status', 'on process');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $teknisi_data_ticket->count();

        return view('asigne', compact('teknisi_data_ticket', 'totalTickets'));
    }

    public function closeticket() {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang memiliki asignees yang sesuai dengan user_id yang sedang login dan status 'close'
        $teknisi_data_ticket = Ticket::with('user')
            ->whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'close')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $teknisi_data_ticket->count();

        return view('closed', compact('teknisi_data_ticket', 'totalTickets'));
    }

    public function ListTicket() {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang memiliki asignees dan diurutkan berdasarkan 'created_at'
        $teknisi_data_ticket = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ListTicket', compact('teknisi_data_ticket'));
    }
}

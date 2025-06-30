<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    public function index()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki asignee_id null dan status open
        $teknisi_data_ticket = Ticket::with('user')
            ->whereNull('asignee_id')
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total tiket yang sedang dalam proses (on process) berdasarkan assignee_id yang sama dengan user_id yang sedang login
        $totalOnProcessTickets = Ticket::where('asignee_id', $userId)
            ->where('status', 'on process')
            ->count();

        $totalClosedTickets = Ticket::where('asignee_id', $userId)
            ->where('status', 'close')
            ->count();

        $totalAllTickets = Ticket::whereNotNull('id')->count();

        $totalTickets = $teknisi_data_ticket->count();

        return view('teknisi', compact('teknisi_data_ticket', 'totalTickets', 'totalOnProcessTickets', 'totalClosedTickets', 'totalAllTickets'));
    }

    public function viewasigne()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki assignee_id berdasarkan user_id yang sedang login dan status open atau on process
        $teknisi_data_ticket = Ticket::with('user')
            ->where('asignee_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'open')
                    ->orWhere('status', 'on process');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $teknisi_data_ticket->count();

        return view('asigne', compact('teknisi_data_ticket', 'totalTickets'));
    }

    public function closeticket()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki assignee_id berdasarkan user_id yang sedang login dan status close
        $teknisi_data_ticket = Ticket::with('user')
            ->where('asignee_id', $userId)
            ->where('status', 'close')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $teknisi_data_ticket->count();

        return view('closed', compact('teknisi_data_ticket', 'totalTickets'));
    }


    public function ListTicket()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki assignee_id berdasarkan user_id yang sedang login dan status close, diurutkan berdasarkan created_at secara ascending
        $teknisi_data_ticket = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ListTicket', compact('teknisi_data_ticket'));
    }
}

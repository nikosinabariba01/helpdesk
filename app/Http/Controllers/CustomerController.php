<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    private function getLatestComments()
    {
        // Dapatkan ID pengguna yang sedang login (penyewa)
        $userId = Auth::id();

        // Ambil tiket yang dibuat oleh penyewa
        $ticketsByUser = Ticket::where('user_id', $userId)
            ->pluck('id'); // Mendapatkan ID tiket yang dibuat oleh penyewa

        // Ambil komentar terbaru yang diberikan oleh teknisi pada tiket yang dibuat oleh penyewa
        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)  // Hanya tiket yang dibuat oleh penyewa
            ->whereHas('user', function ($query) {
                // Pastikan komentar berasal dari teknisi (role pemilik atau pengurus)
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->with('ticket.user')  // Memuat relasi untuk menampilkan informasi tiket
            ->latest()
            ->limit(3)  // Batasi 3 komentar terbaru
            ->get();

        return $latestComments;
    }

    public function index()
    {
        $userId = Auth::id();
        $data_ticket = Ticket::with('user')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $totalTickets = $data_ticket->count();


        $OnProcessTickets = Ticket::where('user_id', $userId)
            ->where('status', 'on process')
            ->count();

        $OpenTic = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        $closedtic = Ticket::where('user_id', $userId)
            ->where('status', 'close')
            ->count();

        $latestComments = $this->getLatestComments();

        return view('customer', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic', 'latestComments'));
    }

    public function viewprocess()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki assignee_id berdasarkan user_id yang sedang login dan status open atau on process
        $data_ticket = Ticket::with('user')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'open')
                    ->orWhere('status', 'on process');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $data_ticket->count();

        $OnProcessTickets = Ticket::where('user_id', $userId)
            ->where('status', 'on process')
            ->count();

        $OpenTic = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        $closedtic = Ticket::where('user_id', $userId)
            ->where('status', 'close')
            ->count();

        $latestComments = $this->getLatestComments();

        return view('process', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic', 'latestComments'));
    }
}

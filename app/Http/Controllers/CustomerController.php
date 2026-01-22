<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\User;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    private function getLatestCommentsfromteknisi()
    {
        // Dapatkan ID pengguna yang sedang login (penyewa)
        $userId = Auth::id();

        // Ambil tiket yang dibuat oleh penyewa
        $ticketsByUser = Ticket::where('user_id', $userId)
            ->pluck('id'); // Mendapatkan ID tiket yang dibuat oleh penyewa

        // Ambil tiket yang dibuat oleh penyewa dan waktu pembuatan tiketnya
        $ticketTimestamps = Ticket::whereIn('id', $ticketsByUser)
            ->pluck('created_at', 'id'); // Mendapatkan waktu created_at tiap tiket

        // Ambil komentar terbaru yang diberikan oleh teknisi pada tiket yang dibuat oleh penyewa
        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)  // Hanya tiket yang dibuat oleh penyewa
            ->whereHas('user', function ($query) {
                // Pastikan komentar berasal dari teknisi (role pemilik atau pengurus)
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->where(function ($query) use ($ticketTimestamps) {
                foreach ($ticketTimestamps as $ticketId => $ticketCreatedAt) {
                    $query->orWhere(function ($q) use ($ticketId, $ticketCreatedAt) {
                        $q->where('ticket_id', $ticketId)
                            ->where('created_at', '>', $ticketCreatedAt); // Hanya ambil komentar setelah tiket dibuat
                    });
                }
            })
            ->with('ticket.user')  // Memuat relasi untuk menampilkan informasi tiket
            ->latest()  // Mengurutkan berdasarkan waktu terbaru
            ->limit(3)  // Batasi 3 komentar terbaru
            ->get();

        return $latestComments;
    }


    public function index()
    {
        $userId = Auth::id();
        $totalTickets = Ticket::where('user_id', $userId)->count();

        $OnProcessTickets = Ticket::where('user_id', $userId)
            ->where('status', 'on process')
            ->count();

        $OpenTic = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        $closedtic = Ticket::where('user_id', $userId)
            ->where('status', 'close')
            ->count();

        $latestComments = $this->getLatestCommentsfromteknisi();

        // Ambil pengumuman untuk user yang login
        $pengumuman = Pengumuman::with('creator')
            ->whereHas('penerima', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data tiket halaman pertama (10 data)
        $data_ticket = Ticket::with('user')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('customer', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic', 'latestComments', 'pengumuman'));
    }

    // API untuk infinite scroll
    public function getTicketsInfiniteScroll(Request $request)
    {
        $userId = Auth::id();
        $page = $request->query('page', 1);
        $perPage = 10;
        $skip = ($page - 1) * $perPage;

        $tickets = Ticket::with('user')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($perPage)
            ->get();

        return response()->json([
            'tickets' => $tickets,
            'hasMore' => $tickets->count() === $perPage,
        ]);
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

        $latestComments = $this->getLatestCommentsfromteknisi();

        // Ambil pengumuman untuk user yang login
        $pengumuman = Pengumuman::with('creator')
            ->whereHas('penerima', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('process', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic', 'latestComments', 'pengumuman'));
    }
}

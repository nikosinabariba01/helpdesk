<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{

    private function getLatestComments()
    {
        // Dapatkan ID pengguna yang sedang login (penyewa)
        $userId = Auth::id();

        // Ambil tiket yang dibuat oleh penyewa
        $ticketsByUser = Ticket::where('user_id', $userId)
            ->pluck('id'); // Mendapatkan ID tiket yang dibuat oleh penyewa

        // Ambil komentar terbaru setelah tiket di-assign berdasarkan tiket_id
        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)  // Hanya tiket yang dibuat oleh penyewa
            ->whereHas('user', function ($query) {
                // Pastikan komentar berasal dari teknisi (role pemilik atau pengurus)
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->where(function ($query) {
                // Pastikan komentar hanya diambil setelah tiket di-assign
                // Ambil waktu peng-assign-an berdasarkan ticket_id dan user_id yang sedang login
                $query->whereIn('ticket_id', Ticket::whereHas('asignees', function ($query) {
                    $query->where('user_id', Auth::id()); // Teknisi yang sedang login
                })
                    ->pluck('id')); // Mengambil tiket_id yang relevan
            })
            ->where('user_id', '!=', $userId) // Menambahkan kondisi untuk menghindari komentar dari teknisi yang sedang login
            ->with('ticket.user')  // Mengambil informasi tiket dan pengguna yang mengomentari
            ->latest()  // Mengurutkan berdasarkan waktu terbaru
            ->limit(3)  // Batasi 3 komentar terbaru
            ->get();

        return $latestComments;
    }



    // Fungsi untuk menampilkan data teknisi (tiket yang belum ditugaskan)
    public function index()
    {
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

        $totalAllTickets = Ticket::count();               // Total tiket keseluruhan
        $totalTickets    = $teknisi_data_ticket->count(); // Total tiket yang belum memiliki asignees

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments(); // Panggil fungsi untuk mendapatkan komentar terbaru

        return view('teknisi', compact(
            'teknisi_data_ticket',
            'totalTickets',
            'totalOnProcessTickets',
            'totalClosedTickets',
            'totalAllTickets',
            'latestComments' // Mengirimkan komentar terbaru ke view
        ));
    }

    // Fungsi untuk menampilkan tiket yang sudah ditugaskan
    public function viewasigne()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang sudah memiliki asignees yang sesuai dengan user_id yang sedang login dan status 'open' atau 'on process'
        $teknisi_data_ticket = Ticket::with('user')
            ->whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where(function ($query) {
                $query->where('status', 'on process')
                    ->orWhere('status', 'escalated');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $teknisi_data_ticket->count();

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments();

        return view('asigne', compact('teknisi_data_ticket', 'totalTickets', 'latestComments'));
    }

    // Fungsi untuk menampilkan tiket dengan status escalated
    public function viewEscalation()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang sudah memiliki assignees yang sesuai dengan user_id yang sedang login dan status 'escalated'
        $teknisi_data_ticket = Ticket::with('user')
            ->where('status', 'escalated') // Hanya menampilkan tiket dengan status escalated
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments();

        return view('escalation', compact('teknisi_data_ticket', 'latestComments'));
    }

    // Fungsi untuk menampilkan tiket yang telah ditutup
    public function closeticket()
    {
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

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments();

        return view('closed', compact('teknisi_data_ticket', 'totalTickets', 'latestComments'));
    }

    // Fungsi untuk menampilkan semua tiket
    public function ListTicket()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang memiliki asignees dan diurutkan berdasarkan 'created_at'
        $teknisi_data_ticket = Ticket::with('user', 'asignees')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments();

        return view('ListTicket', compact('teknisi_data_ticket', 'latestComments'));
    }
}

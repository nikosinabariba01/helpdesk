<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeknisiController extends Controller
{

    private function getLatestComments()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang ditugaskan ke teknisi yang sedang login
        $assignedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId); // Menyaring tiket yang ditugaskan ke teknisi
        })->pluck('id'); // Mengambil ID tiket yang ditugaskan ke teknisi

        // Mengambil tiket yang ditugaskan dan waktu penugasannya
        $assignedTicketTimestamps = Ticket::whereIn('id', $assignedTickets)
            ->whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('created_at', 'id'); // Mengambil created_at tiap tiket yang ditugaskan ke teknisi

        // Ambil komentar-komentar setelah tiket ditugaskan (created_at tiket < created_at komentar)
        $latestComments = Comment::whereIn('ticket_id', $assignedTickets)
            ->where('user_id', '!=', $userId) // Menambahkan kondisi untuk menghindari komentar dari teknisi yang sedang login
            ->where(function ($query) use ($assignedTicketTimestamps) {
                foreach ($assignedTicketTimestamps as $ticketId => $assignedAt) {
                    $query->orWhere(function ($q) use ($ticketId, $assignedAt) {
                        $q->where('ticket_id', $ticketId)
                            ->where('created_at', '>', $assignedAt); // Hanya ambil komentar setelah tiket ditugaskan
                    });
                }
            })
            ->with('ticket.user') // Mengambil informasi tiket dan pengguna yang mengomentari
            ->latest() // Mengurutkan berdasarkan waktu terbaru
            ->limit(3) // Batasi hanya 3 komentar terbaru
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

        // Mengambil jumlah tiket per bulan (per tahun dan bulan) untuk Line Chart
        $ticketsPerMonth = Ticket::selectRaw('YEAR(Tanggal_Pengaduan) as year, MONTH(Tanggal_Pengaduan) as month,
                                        COUNT(*) as count, Jenis_Pengaduan')
            ->whereNotNull('Tanggal_Pengaduan') // Pastikan Tanggal_Pengaduan tidak null
            ->groupBy('year', 'month', 'Jenis_Pengaduan')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Menggunakan Carbon untuk mengubah angka bulan menjadi nama bulan (January, February, dll.)
        $monthLabels = $ticketsPerMonth->groupBy('month')->keys()->map(function ($month) {
            return Carbon::create()->month($month)->format('F'); // Mengubah angka bulan menjadi nama bulan
        });

        // Mengambil status tiket dan menghitung jumlah per status untuk Pie Chart
        $statusData = Ticket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('teknisi', compact(
            'teknisi_data_ticket',
            'totalTickets',
            'totalOnProcessTickets',
            'totalClosedTickets',
            'totalAllTickets',
            'latestComments', // Mengirimkan komentar terbaru ke view
            'ticketsPerMonth',
            'statusData'
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

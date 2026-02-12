<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        $userId = Auth::id();

        $teknisi_data_ticket = Ticket::with('user')
            ->whereDoesntHave('asignees')
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOnProcessTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'on process')->count();

        $totalClosedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'close')->count();

        $totalAllTickets = Ticket::count();
        $totalTickets    = $teknisi_data_ticket->count();

        $latestComments = $this->getLatestComments();

        // =========================
        // CHART FILTER (Line)
        // =========================
        $selectedYear = (int) request('year', now()->year);
        $scope        = request('scope', 'all'); // open | close | all
        if (! in_array($scope, ['open', 'close', 'all'], true)) {
            $scope = 'all';
        }

        // daftar tahun dari DB untuk dropdown
        $years = Ticket::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isNotEmpty() && ! $years->contains($selectedYear)) {
            $selectedYear = (int) $years->first();
        }

        // =========================
        // LINE CHART: Permintaan vs Perbaikan per bulan (berdasarkan scope)
        // =========================
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $types  = ['perbaikan', 'permintaan'];

        $monthlyByType = [
            'perbaikan'  => array_fill(0, 12, 0),
            'permintaan' => array_fill(0, 12, 0),
        ];

        $q = Ticket::selectRaw('MONTH(created_at) AS bulan, Jenis_Pengaduan AS jenis, COUNT(*) AS total')
            ->whereYear('created_at', $selectedYear)
            ->whereIn('Jenis_Pengaduan', $types);

        if ($scope === 'open') {
            $q->where('status', 'open');
        } elseif ($scope === 'close') {
            $q->where('status', 'close');
        } else {
            $q->whereIn('status', ['open', 'close']);
        }

        $rows = $q->groupBy('bulan', 'jenis')->get();

        foreach ($rows as $r) {
            $idx = (int) $r->bulan - 1;
            if ($idx >= 0 && $idx < 12 && isset($monthlyByType[$r->jenis])) {
                $monthlyByType[$r->jenis][$idx] = (int) $r->total;
            }
        }

        $chartLine = [
            'year'          => $selectedYear,
            'scope'         => $scope,
            'labels'        => $labels,
            'types'         => $types,
            'monthlyByType' => $monthlyByType,
        ];

        // =========================
        // ✅ DOUGHNUT DATA: Status per bulan (untuk doughnut chart)
        // pakai tahun yang sama biar konsisten dengan dropdown year
        // =========================
        $statuses = ['open', 'on process', 'close', 'escalated'];

        $monthlyByStatus = [];
        foreach ($statuses as $s) {
            $monthlyByStatus[$s] = array_fill(0, 12, 0);
        }

        $statusRows = Ticket::selectRaw('MONTH(created_at) AS bulan, status, COUNT(*) AS total')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('bulan', 'status')
            ->get();

        foreach ($statusRows as $r) {
            $idx = (int) $r->bulan - 1;
            if ($idx >= 0 && $idx < 12 && isset($monthlyByStatus[$r->status])) {
                $monthlyByStatus[$r->status][$idx] = (int) $r->total;
            }
        }

        $chartData = [
            'year'            => $selectedYear,
            'labels'          => $labels,
            'statuses'        => $statuses,
            'monthlyByStatus' => $monthlyByStatus,
        ];

        return view('teknisi', compact(
            'teknisi_data_ticket',
            'totalTickets',
            'totalOnProcessTickets',
            'totalClosedTickets',
            'totalAllTickets',
            'latestComments',
            'years',
            'selectedYear',
            'scope',
            'chartLine',
            'chartData'
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

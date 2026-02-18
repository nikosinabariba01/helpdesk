<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf; // dompdf
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function downloadMonthlyReport(Request $request)
    {
        $user = Auth::user();

        $year  = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        if ($month < 1 || $month > 12) $month = now()->month;

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = (clone $start)->endOfMonth()->endOfDay();

        // base query
        $q = Ticket::with(['user', 'asignees'])
            ->whereBetween('created_at', [$start, $end]);

        // role scope sederhana
        if ($user->role === 'penyewa') {
            $q->where('user_id', $user->id);
        } elseif ($user->role === 'pengurus') {
            $q->whereHas('asignees', fn($x) => $x->where('user_id', $user->id));
        } elseif ($user->role === 'pemilik') {
            $q->where(function ($w) use ($user) {
                $w->whereHas('asignees', fn($x) => $x->where('user_id', $user->id))
                    ->orWhere('status', 'escalated');
            });
        }

        // optional filter dari view
        $status = $request->get('status', 'all');
        if ($status !== 'all') $q->where('status', $status);

        $jenis = $request->get('jenis', 'all');
        if ($jenis !== 'all') $q->where('Jenis_Pengaduan', $jenis);

        $tickets = $q->orderBy('created_at', 'asc')->get();

        // ===== summary simple
        $total = $tickets->count();
        $byStatusCount = $tickets->groupBy('status')->map->count();
        $close = (int) ($byStatusCount['close'] ?? 0);
        $unfinished = (int) (($byStatusCount['open'] ?? 0) + ($byStatusCount['on process'] ?? 0) + ($byStatusCount['escalated'] ?? 0));
        $closeRate = $total ? ($close / $total) * 100 : 0;

        $byTypeCount = $tickets->groupBy('Jenis_Pengaduan')->map->count();

        $dominantType = ((int)($byTypeCount['perbaikan'] ?? 0) >= (int)($byTypeCount['permintaan'] ?? 0))
            ? 'PERBAIKAN' : 'PERMINTAAN';

        // dummy angka resolusi (kalau kamu punya kolom Tanggal_Selesai, ini akan kepakai)
        $resolutionDays = [];
        foreach ($tickets as $t) {
            if (!empty($t->Tanggal_Selesai)) {
                $a = Carbon::parse($t->created_at)->startOfDay();
                $b = Carbon::parse($t->Tanggal_Selesai)->startOfDay();
                $resolutionDays[] = max(0, $a->diffInDays($b));
            }
        }
        sort($resolutionDays);
        $avgRes = count($resolutionDays) ? array_sum($resolutionDays) / count($resolutionDays) : 0;
        $medianRes = 0;
        if (count($resolutionDays)) {
            $mid = intdiv(count($resolutionDays), 2);
            $medianRes = (count($resolutionDays) % 2)
                ? $resolutionDays[$mid]
                : ($resolutionDays[$mid - 1] + $resolutionDays[$mid]) / 2;
        }

        // format untuk blade pdf
        $statuses = ['open', 'on process', 'close', 'escalated'];
        $byStatus = [];
        foreach ($statuses as $st) {
            $c = (int)($byStatusCount[$st] ?? 0);
            $byStatus[$st] = ['count' => $c, 'pct' => $total ? ($c / $total) * 100 : 0];
        }

        $types = ['perbaikan', 'permintaan'];
        $byType = [];
        foreach ($types as $tp) {
            $c = (int)($byTypeCount[$tp] ?? 0);
            $byType[$tp] = ['count' => $c, 'pct' => $total ? ($c / $total) * 100 : 0];
        }

        $topLocations = Ticket::selectRaw('Lokasi, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('Lokasi')->orderByDesc('total')->limit(8)->get()->toArray();

        $topSubjects = Ticket::selectRaw('subject, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('subject')->orderByDesc('total')->limit(8)->get()->toArray();

        $performance = []; // opsional kalau mau nanti

        $meta = [
            'periode_label' => $start->translatedFormat('F Y'),
            'generated_at'  => now()->translatedFormat('d F Y H:i'),
            'role'          => $user->role,
            'user_name'     => $user->name,
        ];

        $summary = [
            'total' => $total,
            'close' => $close,
            'unfinished' => $unfinished,
            'escalated' => (int)($byStatusCount['escalated'] ?? 0),
            'close_rate' => $closeRate,
            'avg_resolution_days' => $avgRes,
            'median_resolution_days' => $medianRes,
            'dominant_type' => $dominantType,
        ];
        $pdf = Pdf::loadView('ticketsmonthly', compact(
            'meta',
            'summary',
            'byStatus',
            'byType',
            'topLocations',
            'topSubjects',
            'performance',
            'tickets'
        ))->setPaper('a4', 'portrait');
        return $pdf->download('laporan-keluhan-' . $start->format('Y-m') . '.pdf');
    }

    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;
        $role   = $user->role;

        // =========================
        // LIST TABEL: tiket open yg belum di-assign (global)
        // =========================
        $teknisi_data_ticket = Ticket::with('user')
            ->whereDoesntHave('asignees')
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();

        // =========================
        // CARD: Assigned & Closed (khusus user login)
        // =========================
        $totalOnProcessTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'on process')->count();

        $totalClosedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'close')->count();

        // =========================
        // CARD: Total global
        // =========================
        $totalAllTickets = Ticket::count();
        $totalTickets    = $teknisi_data_ticket->count();

        $latestComments = $this->getLatestComments();

        // =========================
        // ✅ CARD BARU 1: Unfinished (GLOBAL)
        // =========================
        $totalUnfinishedTickets = Ticket::whereIn('status', ['open', 'on process', 'escalated'])->count();

        // =========================
        // ✅ CARD BARU 2: Escalated (conditional)
        // pemilik => global
        // admin/pengurus => escalated yg assigned ke dia
        // =========================
        if ($role === 'pemilik') {
            $totalEscalatedTickets = Ticket::where('status', 'escalated')->count();
        } elseif (in_array($role, ['admin', 'pengurus'], true)) {
            $totalEscalatedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('status', 'escalated')->count();
        } else {
            $totalEscalatedTickets = Ticket::where('status', 'escalated')->count();
        }

        // =========================
        // CHART FILTER (Line & Doughnut) - tahun sama
        // =========================
        $selectedYear = (int) request('year', now()->year);
        $scope        = request('scope', 'all'); // open | close | all
        if (!in_array($scope, ['open', 'close', 'all'], true)) {
            $scope = 'all';
        }

        // daftar tahun dari DB untuk dropdown
        $years = Ticket::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isNotEmpty() && !$years->contains($selectedYear)) {
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
            // all = open + close saja
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
        // DOUGHNUT DATA: Status per bulan (tahun sama)
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
            'totalEscalatedTickets',
            'totalUnfinishedTickets',
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

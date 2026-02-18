<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // dompdf
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller {

    private function getLatestComments() {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang ditugaskan ke teknisi yang sedang login
        $assignedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId); // Menyaring tiket yang ditugaskan ke teknisi
        })->pluck('id');                   // Mengambil ID tiket yang ditugaskan ke teknisi

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
            ->latest()            // Mengurutkan berdasarkan waktu terbaru
            ->limit(3)            // Batasi hanya 3 komentar terbaru
            ->get();

        return $latestComments;
    }

    public function downloadMonthlyReport(Request $request) {
        $period = $request->get('period', 'monthly'); // monthly | yearly | all

        $year  = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        // =========================
        // RANGE TANGGAL
        // =========================
        if ($period === 'all') {
            $minDate = Ticket::min('created_at');
            $maxDate = Ticket::max('created_at');

            $start = $minDate ? Carbon::parse($minDate)->startOfDay() : now()->startOfYear();
            $end   = $maxDate ? Carbon::parse($maxDate)->endOfDay() : now()->endOfDay();

            $periodeLabel = 'Semua Tahun';
        } elseif ($period === 'yearly') {
            $start        = Carbon::create($year, 1, 1)->startOfDay();
            $end          = Carbon::create($year, 12, 31)->endOfDay();
            $periodeLabel = "Tahun {$year} (Jan–Des)";
        } else {
            $start        = Carbon::create($year, $month, 1)->startOfDay();
            $end          = (clone $start)->endOfMonth()->endOfDay();
            $periodeLabel = $start->translatedFormat('F Y');
        }

        // =========================
        // BASE QUERY (GLOBAL)
        // =========================
        $q = Ticket::query()
            ->with(['user', 'asignees'])
            ->whereBetween('created_at', [$start, $end]);

        // filter opsional
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $q->where('status', $status);
        }

        $jenis = $request->get('jenis', 'all');
        if ($jenis !== 'all') {
            $q->where('Jenis_Pengaduan', $jenis);
        }

        $tickets = $q->orderBy('created_at', 'asc')->get();

        // =========================
        // SUMMARY & KPI
        // =========================
        $total = $tickets->count();

        $open      = $tickets->where('status', 'open')->count();
        $onProcess = $tickets->where('status', 'on process')->count();
        $escalated = $tickets->where('status', 'escalated')->count();
        $close     = $tickets->where('status', 'close')->count();

        $closeRate = $total ? round(($close / $total) * 100, 2) : 0;

        // by type
        $perbaikan  = $tickets->where('Jenis_Pengaduan', 'perbaikan')->count();
        $permintaan = $tickets->where('Jenis_Pengaduan', 'permintaan')->count();

        $summary = [
            'total'      => $total,
            'open'       => $open,
            'on_process' => $onProcess,
            'escalated'  => $escalated,
            'close'      => $close,
            'close_rate' => $closeRate,
            'perbaikan'  => $perbaikan,
            'permintaan' => $permintaan,
        ];

        // =========================
        // BY STATUS (untuk tabel persentase)
        // =========================
        $byStatus = [
            'open'       => ['count' => $open, 'pct' => $total ? round(($open / $total) * 100, 2) : 0],
            'on process' => ['count' => $onProcess, 'pct' => $total ? round(($onProcess / $total) * 100, 2) : 0],
            'escalated'  => ['count' => $escalated, 'pct' => $total ? round(($escalated / $total) * 100, 2) : 0],
            'close'      => ['count' => $close, 'pct' => $total ? round(($close / $total) * 100, 2) : 0],
        ];

        // =========================
        // BY TYPE (untuk tabel persentase)
        // =========================
        $byType = [
            'perbaikan'  => ['count' => $perbaikan, 'pct' => $total ? round(($perbaikan / $total) * 100, 2) : 0],
            'permintaan' => ['count' => $permintaan, 'pct' => $total ? round(($permintaan / $total) * 100, 2) : 0],
        ];

        // =========================
        // TOP LOCATIONS & SUBJECTS (ikut filter)
        // =========================
        $qAgg = Ticket::query()->whereBetween('created_at', [$start, $end]);
        if ($status !== 'all') {
            $qAgg->where('status', $status);
        }

        if ($jenis !== 'all') {
            $qAgg->where('Jenis_Pengaduan', $jenis);
        }

        $topLocations = (clone $qAgg)
            ->selectRaw('Lokasi, COUNT(*) as total')
            ->groupBy('Lokasi')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->toArray();

        $topSubjects = (clone $qAgg)
            ->selectRaw('subject, COUNT(*) as total')
            ->groupBy('subject')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->toArray();

        // =========================
        // FILTER TEXT (buat header PDF)
        // =========================
        $filters   = [];
        $filters[] = "Periode: {$periodeLabel}";
        if ($status !== 'all') {
            $filters[] = "Status: {$status}";
        }

        if ($jenis !== 'all') {
            $filters[] = "Jenis: {$jenis}";
        }

        $filtersText = implode(' • ', $filters);

        // =========================
        // META
        // =========================
        $meta = [
            'periode_label' => $periodeLabel,
            'generated_at'  => now()->translatedFormat('d F Y H:i'),
            'filters_text'  => $filtersText,
            'generated_by'  => Auth::user()->name ?? 'System',
            'period'        => $period,
            'year'          => $year,
            'month'         => $month,
        ];

        $pdf = Pdf::loadView('ticketsmonthly', compact(
            'meta', 'summary', 'byStatus', 'byType', 'topLocations', 'topSubjects', 'tickets'
        ))->setPaper('a4', 'portrait');

        $fileKey = ($period === 'all') ? 'all-time' : (($period === 'yearly') ? $year : $start->format('Y-m'));
        return $pdf->download("laporan-keluhan-{$fileKey}.pdf");
    }

    public function index() {
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
    public function viewasigne() {
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
    public function viewEscalation() {
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

        // Mengambil komentar terbaru
        $latestComments = $this->getLatestComments();

        return view('closed', compact('teknisi_data_ticket', 'totalTickets', 'latestComments'));
    }

    // Fungsi untuk menampilkan semua tiket
    public function ListTicket() {
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

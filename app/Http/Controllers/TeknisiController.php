<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\TicketAssignee;
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
        // FILTER OPSIONAL (GLOBAL)
        // =========================
        $status = $request->get('status', 'all');
        $jenis  = $request->get('jenis', 'all');

        // =========================
        // BASE QUERY (GLOBAL)
        // =========================
        $q = Ticket::query()
            ->with(['user', 'asignees'])
            ->whereBetween('created_at', [$start, $end]);

        if ($status !== 'all') {
            $q->where('status', $status);
        }
        if ($jenis !== 'all') {
            $q->where('Jenis_Pengaduan', $jenis);
        }

        $tickets = $q->orderBy('created_at', 'asc')->get();

        // =========================
        // SUMMARY & KPI
        // =========================
        $total     = $tickets->count();
        $open      = $tickets->where('status', 'open')->count();
        $onProcess = $tickets->where('status', 'on process')->count();
        $escalated = $tickets->where('status', 'escalated')->count();
        $close     = $tickets->where('status', 'close')->count();

        $closeRate = $total ? round(($close / $total) * 100, 2) : 0;

        $perbaikan  = $tickets->where('Jenis_Pengaduan', 'perbaikan')->count();
        $permintaan = $tickets->where('Jenis_Pengaduan', 'permintaan')->count();

        $dominantType = 'perbaikan';
        if ($permintaan > $perbaikan) {
            $dominantType = 'permintaan';
        }

        if ($perbaikan === 0 && $permintaan === 0) {
            $dominantType = '-';
        }

        // ===== waktu penyelesaian (created_at -> Tanggal_Selesai) =====
        $resolvedDays = $tickets
            ->filter(fn($t) => $t->status === 'close' && ! empty($t->Tanggal_Selesai) && ! empty($t->created_at))
            ->map(function ($t) {
                $a = Carbon::parse($t->created_at)->startOfDay();
                $b = Carbon::parse($t->Tanggal_Selesai)->startOfDay();
                return max(0, $a->diffInDays($b));
            })
            ->values();

        $avgDays    = $resolvedDays->count() ? round($resolvedDays->avg(), 2) : 0;
        $medianDays = $resolvedDays->count() ? round($this->median($resolvedDays->all()), 2) : 0;
        $p90Days    = $resolvedDays->count() ? round($this->percentile($resolvedDays->all(), 90), 2) : 0;

        $summary = [
            'total'                  => $total,
            'open'                   => $open,
            'on_process'             => $onProcess,
            'escalated'              => $escalated,
            'close'                  => $close,
            'close_rate'             => $closeRate,
            'perbaikan'              => $perbaikan,
            'permintaan'             => $permintaan,
            'dominant_type'          => $dominantType,

            // dipakai view sesuai period
            'avg_resolution_days'    => $avgDays,
            'median_resolution_days' => $medianDays,
            'p90_resolution_days'    => $p90Days,
        ];

        // =========================
        // BY STATUS / BY TYPE
        // =========================
        $byStatus = [
            'open'       => ['count' => $open, 'pct' => $total ? round(($open / $total) * 100, 2) : 0],
            'on process' => ['count' => $onProcess, 'pct' => $total ? round(($onProcess / $total) * 100, 2) : 0],
            'escalated'  => ['count' => $escalated, 'pct' => $total ? round(($escalated / $total) * 100, 2) : 0],
            'close'      => ['count' => $close, 'pct' => $total ? round(($close / $total) * 100, 2) : 0],
        ];

        $byType = [
            'perbaikan'  => ['count' => $perbaikan, 'pct' => $total ? round(($perbaikan / $total) * 100, 2) : 0],
            'permintaan' => ['count' => $permintaan, 'pct' => $total ? round(($permintaan / $total) * 100, 2) : 0],
        ];

        // =========================
        // TOP LOCATIONS & SUBJECTS (tanpa raw)
        // =========================
        $topLocations = $tickets
            ->groupBy('Lokasi')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(8)
            ->map(fn($count, $lokasi) => ['Lokasi' => $lokasi, 'total' => $count])
            ->values()
            ->all();

        $topSubjects = $tickets
            ->groupBy('subject')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(8)
            ->map(fn($count, $subject) => ['subject' => $subject, 'total' => $count])
            ->values()
            ->all();

        // =========================
        // TREN BULANAN (Jan–Des) - untuk yearly
        // =========================
        $trendMonthly = [];
        if ($period === 'yearly') {
            for ($m = 1; $m <= 12; $m++) {
                $monthTickets = $tickets->filter(function ($t) use ($m) {
                    return Carbon::parse($t->created_at)->month === $m;
                });

                $tTotal = $monthTickets->count();
                $tClose = $monthTickets->where('status', 'close')->count();
                $tRate  = $tTotal ? round(($tClose / $tTotal) * 100, 2) : 0;

                $trendMonthly[$m] = [
                    'total'      => $tTotal,
                    'close'      => $tClose,
                    'close_rate' => $tRate,
                ];
            }
        }

        // =========================
        // KPI PER TAHUN - untuk all (lebih masuk akal dari avg global)
        // =========================
        $kpiByYear = [];
        if ($period === 'all') {
            $ticketsByYear = $tickets->groupBy(function ($t) {
                return Carbon::parse($t->created_at)->year;
            })->sortKeys();

            $kpiByYear = $ticketsByYear->map(function ($group, $y) {
                $tTotal = $group->count();
                $tClose = $group->where('status', 'close')->count();
                $rate   = $tTotal ? round(($tClose / $tTotal) * 100, 2) : 0;

                $days = $group->filter(fn($t) => $t->status === 'close' && ! empty($t->Tanggal_Selesai) && ! empty($t->created_at))
                    ->map(function ($t) {
                        $a = Carbon::parse($t->created_at)->startOfDay();
                        $b = Carbon::parse($t->Tanggal_Selesai)->startOfDay();
                        return max(0, $a->diffInDays($b));
                    })->values()->all();

                $median = count($days) ? round($this->median($days), 2) : 0;
                $p90    = count($days) ? round($this->percentile($days, 90), 2) : 0;

                return [
                    'year'        => (int) $y,
                    'total'       => $tTotal,
                    'close'       => $tClose,
                    'close_rate'  => $rate,
                    'median_days' => $median,
                    'p90_days'    => $p90,
                ];
            })->values()->all();
        }

        // =========================
        // KINERJA PER PENGURUS (pemilik + semua pengurus, pemilik paling atas)
        // HANYA UNTUK ROLE PEMILIK
        // =========================
        $performance = [];
        if ((Auth::user()->role ?? '') === 'pemilik') {

            // ambil semua user role pemilik + pengurus (biar handled 0 tetap muncul)
            $staff = User::query()
                ->whereIn('role', ['pemilik', 'pengurus'])
                ->orderByRaw("FIELD(role, 'pemilik', 'pengurus')") // ini bukan DB::raw, ini orderByRaw bawaan eloquent (kalau kamu tetap gak mau raw sama sekali, bilang ya nanti saya ganti manual sort collection)
                ->orderBy('name')
                ->get();

            // ambil pivot assignees yang ticketnya masuk range + filter
            $assignees = TicketAssignee::query()
                ->with(['user', 'ticket'])
                ->whereHas('ticket', function ($qq) use ($start, $end, $status, $jenis) {
                    $qq->whereBetween('created_at', [$start, $end]);
                    if ($status !== 'all') {
                        $qq->where('status', $status);
                    }

                    if ($jenis !== 'all') {
                        $qq->where('Jenis_Pengaduan', $jenis);
                    }

                })
                ->get();

            $grouped = $assignees->groupBy('user_id');

            $performance = $staff->map(function ($u) use ($grouped, $period) {
                $rows = $grouped->get($u->id, collect());

                $handledTickets = $rows->pluck('ticket')->filter()->unique('id')->values();
                $handled        = $handledTickets->count();

                $closedTickets = $handledTickets->where('status', 'close');
                $closed        = $closedTickets->count();

                $closeRate = $handled ? round(($closed / $handled) * 100, 2) : 0;

                // waktu selesai per user
                $days = $closedTickets
                    ->filter(fn($t) => ! empty($t->Tanggal_Selesai) && ! empty($t->created_at))
                    ->map(function ($t) {
                        $a = Carbon::parse($t->created_at)->startOfDay();
                        $b = Carbon::parse($t->Tanggal_Selesai)->startOfDay();
                        return max(0, $a->diffInDays($b));
                    })
                    ->values();

                $avg    = $days->count() ? round($days->avg(), 2) : 0;
                $median = $days->count() ? round($this->median($days->all()), 2) : 0;
                $p90    = $days->count() ? round($this->percentile($days->all(), 90), 2) : 0;

                return [
                    'user_id'                => $u->id,
                    'name'                   => $u->name,
                    'role'                   => $u->role,
                    'handled'                => $handled,
                    'closed'                 => $closed,
                    'close_rate'             => $closeRate,
                    'avg_resolution_days'    => $avg,
                    'median_resolution_days' => $median,
                    'p90_resolution_days'    => $p90,
                ];
            });

            // pastikan pemilik benar-benar di atas (tanpa DB raw)
            $performance = $performance->sort(function ($a, $b) {
                // pemilik dulu
                $ra = ($a['role'] === 'pemilik') ? 0 : 1;
                $rb = ($b['role'] === 'pemilik') ? 0 : 1;
                if ($ra !== $rb) {
                    return $ra <=> $rb;
                }

                // lalu handled desc biar kelihatan yang aktif
                if (($a['handled'] ?? 0) !== ($b['handled'] ?? 0)) {
                    return ($b['handled'] ?? 0) <=> ($a['handled'] ?? 0);
                }

                // lalu nama
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            })->values()->all();
        }

        // =========================
        // REKAP ESKALASI
        // tampil hanya jika role pemilik & period monthly/yearly
        // =========================
        $escalationSummary = null;
        if ((Auth::user()->role ?? '') === 'pemilik' && in_array($period, ['monthly', 'yearly'])) {

            $escalatedTickets = $tickets->where('status', 'escalated')->values();

            $topEscLoc = $escalatedTickets
                ->groupBy('Lokasi')
                ->map(fn($g) => $g->count())
                ->sortDesc()
                ->take(8)
                ->map(fn($count, $lok) => ['Lokasi' => $lok, 'total' => $count])
                ->values()
                ->all();

            $topEscSub = $escalatedTickets
                ->groupBy('subject')
                ->map(fn($g) => $g->count())
                ->sortDesc()
                ->take(8)
                ->map(fn($count, $sub) => ['subject' => $sub, 'total' => $count])
                ->values()
                ->all();

            $escalationSummary = [
                'total'         => $escalatedTickets->count(),
                'top_locations' => $topEscLoc,
                'top_subjects'  => $topEscSub,
            ];
        }

        // =========================
        // FILTER TEXT (header)
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
            'user_name'     => Auth::user()->name ?? 'System',
            'role'          => Auth::user()->role ?? '-',
            'period'        => $period,
            'year'          => $year,
            'month'         => $month,
        ];

        $pdf = Pdf::loadView('ticketsmonthly', compact(
            'meta',
            'summary',
            'byStatus',
            'byType',
            'topLocations',
            'topSubjects',
            'tickets',
            'performance',
            'escalationSummary',
            'kpiByYear',
            'trendMonthly'
        ))->setPaper('a4', 'portrait');

        $fileKey = ($period === 'all') ? 'all-time' : (($period === 'yearly') ? $year : $start->format('Y-m'));
        return $pdf->download("laporan-keluhan-{$fileKey}.pdf");
    }

/**
 * Median helper (tanpa raw)
 */
    private function median(array $values): float {
        if (empty($values)) {
            return 0;
        }

        sort($values);
        $count = count($values);
        $mid   = intdiv($count, 2);

        if ($count % 2) {
            return (float) $values[$mid];
        }
        return ((float) $values[$mid - 1] + (float) $values[$mid]) / 2;
    }

/**
 * Percentile helper (P90, dll) sederhana
 */
    private function percentile(array $values, int $percent): float {
        if (empty($values)) {
            return 0;
        }

        sort($values);
        $count = count($values);

        // nearest-rank method
        $rank = (int) ceil(($percent / 100) * $count);
        $idx  = max(0, min($count - 1, $rank - 1));

        return (float) $values[$idx];
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
        // Hitung total perbaikan dan permintaan berdasarkan tahun yang dipilih
        // =========================
        $perbaikanTotal = Ticket::whereYear('created_at', $selectedYear)
            ->where('Jenis_Pengaduan', 'perbaikan')
            ->count();

        $permintaanTotal = Ticket::whereYear('created_at', $selectedYear)
            ->where('Jenis_Pengaduan', 'permintaan')
            ->count();

        // Kirimkan total perbaikan dan permintaan ke view dengan nama variabel yang berbeda
        $jenisTicketTotal = [
            'perbaikan_total'  => $perbaikanTotal,
            'permintaan_total' => $permintaanTotal,
        ];

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

        // Kirimkan chartData yang sudah diproses ke view
        $chartData = [
            'year'            => $selectedYear,
            'labels'          => $labels,
            'statuses'        => $statuses,
            'monthlyByStatus' => $monthlyByStatus,
        ];

        // Kirimkan semua data yang diproses ke view
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
            'jenisTicketTotal', // Mengirimkan total perbaikan dan permintaan
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

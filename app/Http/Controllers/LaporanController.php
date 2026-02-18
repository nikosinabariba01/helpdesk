<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->year ?? now()->year;

        $years = Ticket::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isNotEmpty() && !$years->contains($selectedYear)) {
            $selectedYear = $years->first();
        }

        $tickets = Ticket::with('asignees')
            ->whereYear('created_at', $selectedYear)
            ->get();

        $totalTickets = $tickets->count();
        $totalClose = $tickets->where('status', 'close')->count();
        $totalOpen = $tickets->where('status', 'open')->count();
        $totalOnProcess = $tickets->where('status', 'on process')->count();
        $totalEscalated = $tickets->where('status', 'escalated')->count();

        $totalPermintaan = $tickets->where('Jenis_Pengaduan', 'permintaan')->count();
        $totalPerbaikan = $tickets->where('Jenis_Pengaduan', 'perbaikan')->count();

        $closeRate = $totalTickets > 0
            ? round(($totalClose / $totalTickets) * 100, 2)
            : 0;

        // =============================
        // Kinerja Pengurus + Ranking
        // =============================
        $pengurusList = User::where('role', 'pengurus')->get();
        $kinerja = [];

        foreach ($pengurusList as $pengurus) {

            $assignedTickets = $pengurus->assigneeTickets()
                ->whereYear('created_at', $selectedYear)
                ->get();

            $totalHandled = $assignedTickets->count();
            $totalClosed = $assignedTickets->where('status', 'close')->count();
            $totalEscalation = $assignedTickets->where('status', 'escalated')->count();

            $avgDays = 0;
            $closedTickets = $assignedTickets->where('status', 'close');

            if ($closedTickets->count() > 0) {
                $totalDays = 0;

                foreach ($closedTickets as $t) {
                    if ($t->Tanggal_Selesai) {
                        $totalDays += Carbon::parse($t->Tanggal_Selesai)
                            ->diffInDays($t->created_at);
                    }
                }

                $avgDays = round($totalDays / $closedTickets->count(), 2);
            }

            $closeRatePengurus = $totalHandled > 0
                ? round(($totalClosed / $totalHandled) * 100, 2)
                : 0;

            $kinerja[] = [
                'nama' => $pengurus->name,
                'total' => $totalHandled,
                'close' => $totalClosed,
                'escalated' => $totalEscalation,
                'close_rate' => $closeRatePengurus,
                'avg_days' => $avgDays
            ];
        }

        // Urutkan berdasarkan close rate tertinggi
        usort($kinerja, fn($a, $b) => $b['close_rate'] <=> $a['close_rate']);

        return view('laporan.index', compact(
            'years',
            'selectedYear',
            'totalTickets',
            'totalClose',
            'totalOpen',
            'totalOnProcess',
            'totalEscalated',
            'totalPermintaan',
            'totalPerbaikan',
            'closeRate',
            'kinerja'
        ));
    }

    public function download(Request $request)
    {
        $year = $request->year ?? now()->year;

        return Pdf::loadView(
            'laporan.pdf',
            $this->index($request)->getData()
        )->download('laporan_kinerja_' . $year . '.pdf');
    }
}

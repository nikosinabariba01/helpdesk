<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function download(Request $request)
    {
        $year = $request->year ?? now()->year;

        // ========================
        // AMBIL DATA TIKET TAHUN TERPILIH
        // ========================
        $tickets = Ticket::with('asignees')
            ->whereYear('tickets.created_at', $year) // FIX ambiguous
            ->get();

        // ========================
        // GLOBAL SUMMARY
        // ========================
        $totalTickets     = $tickets->count();
        $totalClose       = $tickets->where('status', 'close')->count();
        $totalOpen        = $tickets->where('status', 'open')->count();
        $totalOnProcess   = $tickets->where('status', 'on process')->count();
        $totalEscalated   = $tickets->where('status', 'escalated')->count();

        $totalPermintaan  = $tickets->where('Jenis_Pengaduan', 'permintaan')->count();
        $totalPerbaikan   = $tickets->where('Jenis_Pengaduan', 'perbaikan')->count();

        $closeRate = $totalTickets > 0
            ? round(($totalClose / $totalTickets) * 100, 2)
            : 0;

        // ========================
        // KINERJA PENGURUS
        // ========================
        $pengurusList = User::where('role', 'pengurus')->get();

        $kinerja = [];

        foreach ($pengurusList as $pengurus) {

            // FIX ambiguous column
            $assignedTickets = $pengurus->assigneeTickets()
                ->whereYear('tickets.created_at', $year)
                ->get();

            $totalHandled     = $assignedTickets->count();
            $totalClosed      = $assignedTickets->where('status', 'close')->count();
            $totalEscalation  = $assignedTickets->where('status', 'escalated')->count();

            // ========================
            // HITUNG RATA-RATA HARI SELESAI
            // ========================
            $closedTickets = $assignedTickets
                ->where('status', 'close')
                ->whereNotNull('Tanggal_Selesai');

            $avgDays = 0;

            if ($closedTickets->count() > 0) {

                $totalDays = 0;

                foreach ($closedTickets as $t) {
                    $totalDays += Carbon::parse($t->Tanggal_Selesai)
                        ->diffInDays($t->created_at);
                }

                $avgDays = round($totalDays / $closedTickets->count(), 2);
            }

            $kinerja[] = [
                'nama'        => $pengurus->name,
                'total'       => $totalHandled,
                'close'       => $totalClosed,
                'escalated'   => $totalEscalation,
                'close_rate'  => $totalHandled > 0
                    ? round(($totalClosed / $totalHandled) * 100, 2)
                    : 0,
                'avg_days'    => $avgDays
            ];
        }

        // ========================
        // GENERATE PDF
        // ========================
        $pdf = Pdf::loadView('laporan.pdf', compact(
            'year',
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

        return $pdf->download('laporan_kinerja_' . $year . '.pdf');
    }
}

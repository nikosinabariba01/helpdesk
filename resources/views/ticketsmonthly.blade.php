{{-- resources/views/ticketsmonthly.blade.php --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keluhan Bulanan</title>
    <style>
        @page { margin: 70px 26px 55px 26px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .muted { color:#6b7280; }
        .small { font-size: 10px; }
        .h1 { font-size: 18px; font-weight: 700; margin: 0; }
        .h2 { font-size: 14px; font-weight: 700; margin: 12px 0 6px; }
        .h3 { font-size: 12px; font-weight: 700; margin: 10px 0 6px; }

        .card { border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px; margin:0 0 10px 0; }
        .divider { height:1px; background:#e5e7eb; margin:10px 0; }

        .kpi { width:100%; }
        .kpi .box { width:19%; display:inline-block; vertical-align:top; border-right:1px dashed #e5e7eb; padding:6px 8px; }
        .kpi .box:last-child { border-right:none; }
        .kpi .label { font-size:10px; color:#6b7280; text-transform:uppercase; letter-spacing:.3px; }
        .kpi .value { font-size:16px; font-weight:800; margin-top:2px; }
        .kpi .hint  { font-size:10px; color:#6b7280; margin-top:2px; }

        table { width:100%; border-collapse:collapse; margin-top:6px; }
        th, td { border:1px solid #e5e7eb; padding:6px 7px; vertical-align:top; }
        th { background:#f3f4f6; font-size:10px; text-transform:uppercase; letter-spacing:.3px; color:#374151; text-align:left; }
        td { font-size:11px; }

        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .nowrap { white-space:nowrap; }
        .wrap { word-break:break-word; }

        tr { page-break-inside:avoid; }
        .no-break { page-break-inside:avoid; }

        .st { font-weight:700; }
        .st-open { color:#2563eb; }
        .st-process { color:#b45309; }
        .st-close { color:#047857; }
        .st-escalated { color:#b91c1c; }
    </style>
</head>
<body>

<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 9;

        $pdf->text(26, 22, "Laporan Keluhan Bulanan • Sistem Kos", $font, $size);

        $periode = "{{ $meta['periode_label'] ?? '-' }}";
        $role    = "{{ strtoupper($meta['role'] ?? '-') }}";
        $pdf->text(360, 22, "Periode: " . $periode . " • Role: " . $role, $font, $size);

        $filters = "{{ $meta['filters_text'] ?? '' }}";
        if ($filters !== "") {
            $pdf->text(26, 36, "Filter: " . $filters, $font, $size);
        }

        $pdf->text(26, 820, "Dicetak: {{ $meta['generated_at'] ?? '-' }} • Oleh: {{ $meta['user_name'] ?? '-' }}", $font, $size);
        $pdf->text(520, 820, "Halaman {PAGE_NUM} / {PAGE_COUNT}", $font, $size);
    }
</script>

<div class="card no-break">
    <div class="h1">Laporan Keluhan Bulanan</div>
    <div class="muted" style="margin-top:4px;">Sistem Manajemen Keluhan & Monitoring Kos</div>
    <div class="small muted" style="margin-top:4px;">
        Periode: <b>{{ $meta['periode_label'] ?? '-' }}</b>
        • Role: <b>{{ strtoupper($meta['role'] ?? '-') }}</b>
        @if(!empty($meta['filters_text']))
            • Filter: <b>{{ $meta['filters_text'] }}</b>
        @endif
    </div>
</div>

<div class="card no-break">
    <div class="h2" style="margin-top:0;">Ringkasan</div>

    <div class="kpi">
        <div class="box">
            <div class="label">Total</div>
            <div class="value">{{ $summary['total'] ?? 0 }}</div>
            <div class="hint">Tiket periode</div>
        </div>
        <div class="box">
            <div class="label">Open</div>
            <div class="value">{{ $summary['open'] ?? 0 }}</div>
            <div class="hint">Belum diproses</div>
        </div>
        <div class="box">
            <div class="label">On process</div>
            <div class="value">{{ $summary['on_process'] ?? 0 }}</div>
            <div class="hint">Ditangani</div>
        </div>
        <div class="box">
            <div class="label">Escalated</div>
            <div class="value">{{ $summary['escalated'] ?? 0 }}</div>
            <div class="hint">Eskalasi</div>
        </div>
        <div class="box">
            <div class="label">Close</div>
            <div class="value">{{ $summary['close'] ?? 0 }}</div>
            <div class="hint">Selesai</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="small muted">
        Close rate: <b>{{ number_format((float)($summary['close_rate'] ?? 0), 1) }}%</b>
        • Rata-rata selesai: <b>{{ number_format((float)($summary['avg_resolution_days'] ?? 0), 1) }}</b> hari
        • Median: <b>{{ number_format((float)($summary['median_resolution_days'] ?? 0), 1) }}</b> hari
        • Dominan: <b>{{ $summary['dominant_type'] ?? '-' }}</b>
    </div>

    <div class="small muted" style="margin-top:6px;">
        Perbaikan: <b>{{ $byType['perbaikan']['count'] ?? 0 }}</b>
        • Permintaan: <b>{{ $byType['permintaan']['count'] ?? 0 }}</b>
    </div>
</div>

@if(!empty($topLocations) && count($topLocations))
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Top Lokasi Keluhan</div>
        <table>
            <thead>
                <tr>
                    <th>Lokasi</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topLocations as $loc)
                    <tr>
                        <td class="wrap">{{ $loc['Lokasi'] ?? '-' }}</td>
                        <td class="text-right">{{ $loc['total'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@if(!empty($topSubjects) && count($topSubjects))
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Top Subject Keluhan</div>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topSubjects as $sb)
                    <tr>
                        <td class="wrap">{{ $sb['subject'] ?? '-' }}</td>
                        <td class="text-right">{{ $sb['total'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<div class="card">
    <div class="h2" style="margin-top:0;">Daftar Tiket (Periode)</div>
    <table>
        <thead>
            <tr>
                <th style="width:12%;">ID</th>
                <th style="width:28%;">Subject</th>
                <th style="width:12%;">Jenis</th>
                <th style="width:12%;">Status</th>
                <th style="width:18%;">Lokasi</th>
                <th style="width:9%;">Tanggal</th>
                <th style="width:9%;">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $t)
                @php
                    $st = $t->status;
                    $cls = $st==='open' ? 'st-open' : ($st==='on process' ? 'st-process' : ($st==='close' ? 'st-close' : 'st-escalated'));
                    $ticketCode = $t->ticket_code ?? ('sp-' . substr(preg_replace('/[^0-9]/','',$t->id), -3) . \Carbon\Carbon::parse($t->created_at)->format('dmy'));
                @endphp
                <tr>
                    <td class="nowrap">{{ $ticketCode }}</td>
                    <td class="wrap"><b>{{ $t->subject ?? '-' }}</b></td>
                    <td class="nowrap">{{ $t->Jenis_Pengaduan ?? '-' }}</td>
                    <td class="nowrap"><span class="st {{ $cls }}">{{ $st ?? '-' }}</span></td>
                    <td class="wrap">{{ $t->Lokasi ?? '-' }}</td>
                    <td class="nowrap">{{ $t->created_at ? \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') : '-' }}</td>
                    <td class="nowrap">{{ !empty($t->Tanggal_Selesai) ? \Carbon\Carbon::parse($t->Tanggal_Selesai)->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center muted">Tidak ada tiket pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="small muted" style="margin-top:8px;">
        Total baris: {{ method_exists($tickets,'count') ? $tickets->count() : 0 }}
    </div>
</div>

</body>
</html>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keluhan Bulanan</title>
    <style>
        @page { margin: 70px 26px 55px 26px; } /* ruang untuk header/footer */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }

        .muted { color:#6b7280; }
        .small { font-size: 10px; }
        .h1 { font-size: 18px; font-weight: 700; margin: 0; }
        .h2 { font-size: 14px; font-weight: 700; margin: 14px 0 8px; }
        .h3 { font-size: 12px; font-weight: 700; margin: 12px 0 6px; }

        /* Hindari display:table kompleks (DomPDF kadang bikin page break aneh) */
        .row { width: 100%; }
        .col-6 { width: 49%; display: inline-block; vertical-align: top; }
        .col-12 { width: 100%; }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 12px;
            margin: 0 0 10px 0;
            background: #fff;
        }

        .kpi-wrap { width: 100%; }
        .kpi-box { width: 19%; display:inline-block; vertical-align: top; border-right: 1px dashed #e5e7eb; padding: 6px 8px; }
        .kpi-box:last-child { border-right: none; }
        .kpi-label { font-size: 10px; color:#6b7280; text-transform: uppercase; letter-spacing: .3px; }
        .kpi-value { font-size: 16px; font-weight: 800; margin-top: 2px; }
        .kpi-hint { font-size: 10px; color:#6b7280; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 7px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .3px; color:#374151; }
        td { font-size: 11px; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }
        .wrap { word-break: break-word; }

        .divider { height: 1px; background: #e5e7eb; margin: 10px 0; }

        /* DomPDF: cegah pecah aneh */
        .no-break { page-break-inside: avoid; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }

        .st { font-weight: 700; }
        .st-open { color:#2563eb; }
        .st-process { color:#b45309; }
        .st-close { color:#047857; }
        .st-escalated { color:#b91c1c; }
    </style>
</head>
<body>

@php
    $openCount      = $byStatus['open']['count'] ?? 0;
    $processCount   = $byStatus['on process']['count'] ?? 0;
    $closeCount     = $byStatus['close']['count'] ?? 0;
    $escalatedCount = $byStatus['escalated']['count'] ?? 0;

    $perbaikanCount  = $byType['perbaikan']['count'] ?? 0;
    $permintaanCount = $byType['permintaan']['count'] ?? 0;

    $closeRate = $summary['close_rate'] ?? 0;
@endphp

{{-- HEADER/FOOTER via script (lebih stabil, tidak bikin banyak halaman kosong) --}}
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 9;

        // Header kiri
        $pdf->text(26, 22, "Laporan Keluhan Bulanan • Sistem Kos", $font, $size);

        // Header kanan
        $periode = "{{ $meta['periode_label'] ?? '-' }}";
        $role    = "{{ strtoupper($meta['role'] ?? '-') }}";
        $pdf->text(360, 22, "Periode: " . $periode . " • Role: " . $role, $font, $size);

        // Footer
        $pdf->text(26, 820, "Dicetak: {{ $meta['generated_at'] ?? '-' }} • Oleh: {{ $meta['user_name'] ?? '-' }}", $font, $size);

        // Nomor halaman kanan bawah
        $pdf->text(520, 820, "Halaman {PAGE_NUM} / {PAGE_COUNT}", $font, $size);
    }
</script>

{{-- BODY CONTENT --}}
<div class="card no-break">
    <div class="h1">Laporan Keluhan Bulanan</div>
    <div class="muted" style="margin-top:4px;">Sistem Manajemen Keluhan & Monitoring Kos</div>
    <div class="small muted" style="margin-top:4px;">
        Periode: <b>{{ $meta['periode_label'] ?? '-' }}</b> • Role: <b>{{ strtoupper($meta['role'] ?? '-') }}</b>
    </div>
</div>

<div class="card no-break">
    <div class="h2" style="margin-top:0;">Ringkasan</div>

    <div class="kpi-wrap">
        <div class="kpi-box">
            <div class="kpi-label">Total</div>
            <div class="kpi-value">{{ $summary['total'] ?? 0 }}</div>
            <div class="kpi-hint">Tiket periode</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Open</div>
            <div class="kpi-value">{{ $openCount }}</div>
            <div class="kpi-hint">Belum diproses</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">On process</div>
            <div class="kpi-value">{{ $processCount }}</div>
            <div class="kpi-hint">Ditangani</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Escalated</div>
            <div class="kpi-value">{{ $escalatedCount }}</div>
            <div class="kpi-hint">Eskalasi</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Close</div>
            <div class="kpi-value">{{ $closeCount }}</div>
            <div class="kpi-hint">Selesai</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col-6">
            <div class="h3" style="margin-top:0;">Kinerja</div>
            <div class="muted">
                Close rate: <b>{{ number_format((float)$closeRate, 1) }}%</b>
            </div>
            <div class="small muted" style="margin-top:6px;">
                Rata-rata selesai: <b>{{ number_format((float)($summary['avg_resolution_days'] ?? 0), 1) }}</b> hari
                • Median: <b>{{ number_format((float)($summary['median_resolution_days'] ?? 0), 1) }}</b> hari
            </div>
            <div class="small muted" style="margin-top:6px;">
                Dominan: <b>{{ $summary['dominant_type'] ?? '-' }}</b>
            </div>
        </div>

        <div class="col-6" style="margin-left:2%;">
            <div class="h3" style="margin-top:0;">Jenis Keluhan</div>
            <div class="small muted">
                Perbaikan: <b>{{ $perbaikanCount }}</b> • Permintaan: <b>{{ $permintaanCount }}</b>
            </div>

            @if(!empty($topLocations) && count($topLocations))
                <div class="small muted" style="margin-top:6px;">
                    Lokasi teratas: <b>{{ $topLocations[0]['Lokasi'] ?? '-' }}</b> ({{ $topLocations[0]['total'] ?? 0 }})
                </div>
            @endif

            @if(!empty($topSubjects) && count($topSubjects))
                <div class="small muted" style="margin-top:6px;">
                    Subject teratas: <b>{{ $topSubjects[0]['subject'] ?? '-' }}</b> ({{ $topSubjects[0]['total'] ?? 0 }})
                </div>
            @endif
        </div>
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
                <th style="width: 12%;">ID</th>
                <th style="width: 28%;">Subject</th>
                <th style="width: 12%;">Jenis</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 18%;">Lokasi</th>
                <th style="width: 9%;">Tanggal</th>
                <th style="width: 9%;">Selesai</th>
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

{{-- resources/views/ticketsmonthly.blade.php --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keluhan</title>
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

        .pill {
            display:inline-block;
            padding:2px 8px;
            border-radius:999px;
            font-size:10px;
            border:1px solid #e5e7eb;
            background:#f9fafb;
            color:#374151;
        }

        .note {
            background:#f9fafb;
            border:1px dashed #e5e7eb;
            border-radius:8px;
            padding:8px 10px;
        }
    </style>
</head>
<body>

{{-- HEADER/FOOTER DomPDF --}}
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 9;

        $pdf->text(26, 22, "Laporan Keluhan • Sistem Kos", $font, $size);

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

{{-- COVER INFO --}}
<div class="card no-break" style="text-align:center;">
    {{-- Header Paling Atas --}}
    <div class="h0" style="font-size:22px; font-weight:900; color:#1f2937; margin-bottom:6px;">
        KOST 74 SEMARANG
    </div>

    {{-- Judul Laporan --}}
    <div class="h1" style="margin-top:4px;">Laporan Keluhan dan KPI</div>
    <div class="muted" style="margin-top:2px;">Sistem Manajemen Keluhan & Monitoring Kos</div>

    {{-- Info Periode, Role, Filter --}}
    <div class="small muted" style="margin-top:4px;">
        Periode: <b>{{ $meta['periode_label'] ?? '-' }}</b>
        • Role: <b>{{ strtoupper($meta['role'] ?? '-') }}</b>
        @if(!empty($meta['filters_text']))
            • Filter: <b>{{ $meta['filters_text'] }}</b>
        @endif
    </div>
</div>

{{-- =========================
     RINGKASAN GLOBAL
========================= --}}
<div class="card no-break">
    <div class="h2" style="margin-top:0;">Ringkasan Global</div>

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

    {{-- Close Rate + metrik waktu (conditional per period) --}}
    <div class="small muted">
        Close rate: <b>{{ number_format((float)($summary['close_rate'] ?? 0), 1) }}%</b>

        @if(($meta['period'] ?? '') === 'monthly')
            • Avg selesai: <b>{{ number_format((float)($summary['avg_resolution_days'] ?? 0), 1) }}</b> hari
            • Median: <b>{{ number_format((float)($summary['median_resolution_days'] ?? 0), 1) }}</b> hari
        @elseif(($meta['period'] ?? '') === 'yearly')
            • Median selesai: <b>{{ number_format((float)($summary['median_resolution_days'] ?? 0), 1) }}</b> hari
            • P90 selesai: <b>{{ number_format((float)($summary['p90_resolution_days'] ?? 0), 1) }}</b> hari
        @endif

        • Dominan: <b>{{ $summary['dominant_type'] ?? '-' }}</b>
    </div>

    <div class="small muted" style="margin-top:6px;">
        Jenis — Perbaikan: <b>{{ $byType['perbaikan']['count'] ?? 0 }}</b>
        • Permintaan: <b>{{ $byType['permintaan']['count'] ?? 0 }}</b>
    </div>

    @if(($meta['period'] ?? '') === 'all')
        <div class="note small muted" style="margin-top:8px;">
            Catatan: Untuk <b>Semua Tahun</b>, metrik waktu (avg/median) global biasanya bias.
            Laporan menampilkan <b>KPI(Key Performance Indicator) Per Tahun</b> agar lebih representatif.
        </div>
    @endif
</div>

{{-- =========================
     BREAKDOWN STATUS
========================= --}}
<div class="card no-break">
    <div class="h2" style="margin-top:0;">Breakdown Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach(($byStatus ?? []) as $k => $v)
                <tr>
                    <td class="wrap"><span class="pill">{{ strtoupper($k) }}</span></td>
                    <td class="text-right">{{ $v['count'] ?? 0 }}</td>
                    <td class="text-right">{{ number_format((float)($v['pct'] ?? 0), 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- =========================
     BREAKDOWN JENIS
========================= --}}
<div class="card no-break">
    <div class="h2" style="margin-top:0;">Breakdown Jenis Pengaduan</div>
    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach(($byType ?? []) as $k => $v)
                <tr>
                    <td class="wrap"><span class="pill">{{ strtoupper($k) }}</span></td>
                    <td class="text-right">{{ $v['count'] ?? 0 }}</td>
                    <td class="text-right">{{ number_format((float)($v['pct'] ?? 0), 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- =========================
     TREN BULANAN (khusus yearly)
========================= --}}
@if(($meta['period'] ?? '') === 'yearly')
    @php
        $trend = $trendMonthly ?? [];
        $monthNames = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
            7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
        ];
    @endphp
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Tren Bulanan (Jan–Des)</div>
        <div class="small muted">Jumlah tiket dibuat per bulan pada tahun terpilih.</div>

        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Close</th>
                    <th class="text-right">Close Rate</th>
                </tr>
            </thead>
            <tbody>
                @for($m=1; $m<=12; $m++)
                    @php
                        $row = $trend[$m] ?? ['total'=>0,'close'=>0,'close_rate'=>0];
                    @endphp
                    <tr>
                        <td><span class="pill">{{ $monthNames[$m] }}</span></td>
                        <td class="text-right">{{ $row['total'] ?? 0 }}</td>
                        <td class="text-right">{{ $row['close'] ?? 0 }}</td>
                        <td class="text-right">{{ number_format((float)($row['close_rate'] ?? 0), 1) }}%</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endif

{{-- =========================
     TOP LOCATIONS
========================= --}}
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

{{-- =========================
     TOP SUBJECTS
========================= --}}
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

{{-- =========================
     KPI PER TAHUN (khusus all)
========================= --}}
@if(($meta['period'] ?? '') === 'all' && !empty($kpiByYear))
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Ringkasan KPI Per Tahun (All Time)</div>
        <div class="small muted">Lebih representatif daripada avg global sepanjang sejarah.</div>

        <table>
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Close</th>
                    <th class="text-right">Close Rate</th>
                    <th class="text-right">Median Selesai</th>
                    <th class="text-right">P90 Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kpiByYear as $y)
                    <tr>
                        <td class="nowrap"><span class="pill">{{ $y['year'] ?? '-' }}</span></td>
                        <td class="text-right">{{ $y['total'] ?? 0 }}</td>
                        <td class="text-right">{{ $y['close'] ?? 0 }}</td>
                        <td class="text-right">{{ number_format((float)($y['close_rate'] ?? 0), 1) }}%</td>
                        <td class="text-right">{{ number_format((float)($y['median_days'] ?? 0), 1) }} hari</td>
                        <td class="text-right">{{ number_format((float)($y['p90_days'] ?? 0), 1) }} hari</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- =========================
     PEMILIK: KINERJA PER PENGURUS (pemilik + pengurus)
========================= --}}
@if(($meta['role'] ?? '') === 'pemilik' && !empty($performance))
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Kinerja Per Pengurus (Termasuk Pemilik)</div>
        <div class="small muted">
            Handled = tiket yang ditugaskan ke penanggung jawab pada periode.
            Jika handled=0 artinya tidak menangani tiket.
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th class="text-right">Handled</th>
                    <th class="text-right">Closed</th>
                    <th class="text-right">Close Rate</th>

                    @if(($meta['period'] ?? '') === 'monthly')
                        <th class="text-right">Avg Selesai (hari)</th>
                    @elseif(($meta['period'] ?? '') === 'yearly')
                        <th class="text-right">Median (hari)</th>
                        <th class="text-right">P90 (hari)</th>
                    @else
                        <th class="text-right">Catatan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($performance as $p)
                    <tr>
                        <td class="wrap"><b>{{ $p['name'] ?? '-' }}</b></td>
                        <td class="nowrap"><span class="pill">{{ strtoupper($p['role'] ?? '-') }}</span></td>
                        <td class="text-right">{{ $p['handled'] ?? 0 }}</td>
                        <td class="text-right">{{ $p['closed'] ?? 0 }}</td>
                        <td class="text-right">{{ number_format((float)($p['close_rate'] ?? 0), 1) }}%</td>

                        @if(($meta['period'] ?? '') === 'monthly')
                            <td class="text-right">{{ number_format((float)($p['avg_resolution_days'] ?? 0), 1) }}</td>
                        @elseif(($meta['period'] ?? '') === 'yearly')
                            <td class="text-right">{{ number_format((float)($p['median_resolution_days'] ?? 0), 1) }}</td>
                            <td class="text-right">{{ number_format((float)($p['p90_resolution_days'] ?? 0), 1) }}</td>
                        @else
                            <td class="text-right muted">All-time: lihat KPI per tahun</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- =========================
     PEMILIK: REKAP ESKALASI (hanya monthly/yearly)
========================= --}}
@if(($meta['role'] ?? '') === 'pemilik' && in_array(($meta['period'] ?? ''), ['monthly','yearly']) && !empty($escalationSummary))
    <div class="card no-break">
        <div class="h2" style="margin-top:0;">Rekap Eskalasi</div>
        <div class="small muted">
            Total escalated pada periode: <b>{{ $escalationSummary['total'] ?? 0 }}</b>
        </div>

        @if(!empty($escalationSummary['top_locations']) && count($escalationSummary['top_locations']))
            <div class="h3">Top Lokasi Eskalasi</div>
            <table>
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($escalationSummary['top_locations'] as $x)
                        <tr>
                            <td class="wrap">{{ $x['Lokasi'] ?? '-' }}</td>
                            <td class="text-right">{{ $x['total'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(!empty($escalationSummary['top_subjects']) && count($escalationSummary['top_subjects']))
            <div class="h3">Top Subject Eskalasi</div>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($escalationSummary['top_subjects'] as $x)
                        <tr>
                            <td class="wrap">{{ $x['subject'] ?? '-' }}</td>
                            <td class="text-right">{{ $x['total'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endif

{{-- =========================
     DAFTAR TIKET
========================= --}}
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

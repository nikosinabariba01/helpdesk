{{-- resources/views/reports/tickets-monthly.blade.php --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keluhan Bulanan</title>

    <style>
        /* DomPDF friendly */
        @page { margin: 22px 26px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .muted { color:#6b7280; }
        .small { font-size: 10px; }
        .h1 { font-size: 18px; font-weight: 700; margin: 0; }
        .h2 { font-size: 14px; font-weight: 700; margin: 18px 0 8px; }
        .h3 { font-size: 12px; font-weight: 700; margin: 14px 0 8px; }

        .header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .brand {
            display: table; width: 100%;
        }
        .brand-left, .brand-right {
            display: table-cell; vertical-align: top;
        }
        .brand-right { text-align: right; }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
        }
        .b-open { background:#3b82f6; }
        .b-process { background:#f59e0b; }
        .b-close { background:#10b981; }
        .b-escalated { background:#ef4444; }
        .b-info { background:#111827; }

        .grid { display: table; width: 100%; table-layout: fixed; }
        .col { display: table-cell; vertical-align: top; }
        .gap { width: 12px; display: table-cell; }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background: #fff;
        }

        .kpi {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .kpi .item {
            display: table-cell;
            border-right: 1px dashed #e5e7eb;
            padding: 8px 10px;
            vertical-align: top;
        }
        .kpi .item:last-child { border-right: none; }
        .kpi .label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: .4px; }
        .kpi .value { font-size: 16px; font-weight: 800; margin-top: 2px; }
        .kpi .hint { font-size: 10px; color: #6b7280; margin-top: 2px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .35px;
            color: #374151;
        }
        td { font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }
        .wrap { word-break: break-word; }

        .divider { height: 1px; background: #e5e7eb; margin: 14px 0; }

        .footer {
            position: fixed;
            bottom: -6px;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #6b7280;
        }
        .footer .left { float: left; }
        .footer .right { float: right; }

        .note {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 12px;
        }

        /* Status text fallback */
        .st { font-weight: 700; }
        .st-open { color:#2563eb; }
        .st-process { color:#b45309; }
        .st-close { color:#047857; }
        .st-escalated { color:#b91c1c; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="brand">
            <div class="brand-left">
                <div class="h1">Laporan Keluhan Bulanan</div>
                <div class="muted" style="margin-top:4px;">
                    Sistem Manajemen Keluhan & Monitoring Kos
                </div>
                <div class="small muted" style="margin-top:4px;">
                    Periode: <b>{{ $monthName ?? '-' }} {{ $year ?? '-' }}</b>
                    @if(!empty($filtersText))
                        • Filter: {{ $filtersText }}
                    @endif
                </div>
            </div>
            <div class="brand-right">
                <div class="badge b-info">PDF</div>
                <div class="small muted" style="margin-top:6px;">
                    Dicetak: {{ $generatedAt ?? '' }}
                </div>
                <div class="small muted">
                    Dibuat oleh: {{ $generatedBy ?? '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- KPI SUMMARY --}}
    <div class="card">
        <div class="h2" style="margin:0 0 8px;">Ringkasan</div>
        <div class="kpi">
            <div class="item">
                <div class="label">Total tiket</div>
                <div class="value">{{ $summary['total'] ?? 0 }}</div>
                <div class="hint">Keluhan masuk pada periode</div>
            </div>
            <div class="item">
                <div class="label">Open</div>
                <div class="value">{{ $summary['open'] ?? 0 }}</div>
                <div class="hint">Belum diproses</div>
            </div>
            <div class="item">
                <div class="label">On process</div>
                <div class="value">{{ $summary['on_process'] ?? 0 }}</div>
                <div class="hint">Sedang ditangani</div>
            </div>
            <div class="item">
                <div class="label">Escalated</div>
                <div class="value">{{ $summary['escalated'] ?? 0 }}</div>
                <div class="hint">Perlu bantuan pemilik</div>
            </div>
            <div class="item">
                <div class="label">Close</div>
                <div class="value">{{ $summary['close'] ?? 0 }}</div>
                <div class="hint">Selesai pada periode</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="grid">
            <div class="col">
                <div class="h3" style="margin-top:0;">Close Rate</div>
                <div class="muted">
                    <b>{{ $summary['close_rate'] ?? 0 }}%</b>
                    <span class="small muted">(Close / Total periode)</span>
                </div>
                @if(isset($summary['avg_close_days']))
                    <div class="small muted" style="margin-top:6px;">
                        Rata-rata waktu selesai: <b>{{ $summary['avg_close_days'] }}</b> hari
                    </div>
                @endif
            </div>
            <div class="gap"></div>
            <div class="col">
                <div class="h3" style="margin-top:0;">Rekap Jenis Keluhan</div>
                <div class="small muted" style="margin-top:6px;">
                    Perbaikan: <b>{{ $byType['perbaikan'] ?? 0 }}</b> •
                    Permintaan: <b>{{ $byType['permintaan'] ?? 0 }}</b>
                </div>
                @if(!empty($topLocations) && count($topLocations))
                    <div class="small muted" style="margin-top:6px;">
                        Lokasi paling sering:
                        <b>{{ $topLocations[0]['Lokasi'] ?? '-' }}</b>
                        ({{ $topLocations[0]['total'] ?? 0 }})
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TREND / INSIGHT --}}
    <div class="note">
        <div class="h2" style="margin-top:0;">Tren & Insight</div>
        <div class="small muted">
            Di bawah ini ringkasan tren berdasarkan data pada periode yang dipilih.
        </div>

        <ul style="margin:10px 0 0 16px; padding:0;">
            @if(!empty($trendNotes) && is_array($trendNotes))
                @foreach($trendNotes as $n)
                    <li style="margin-bottom:6px;">{{ $n }}</li>
                @endforeach
            @else
                <li style="margin-bottom:6px;">Tren utama belum tersedia (isi dari controller).</li>
                <li style="margin-bottom:6px;">Saran: hitung top issue (Jenis_Pengaduan), top lokasi, serta perubahan dibanding bulan sebelumnya.</li>
            @endif
        </ul>
    </div>

    {{-- TOP LOCATIONS --}}
    @if(!empty($topLocations) && count($topLocations))
        <div class="card" style="margin-top:12px;">
            <div class="h2" style="margin-top:0;">Top Lokasi Keluhan</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:70%;">Lokasi</th>
                        <th class="text-right" style="width:30%;">Jumlah</th>
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

    {{-- OPTIONAL: PERFORMANCE PENGURUS --}}
    @if(!empty($performance) && is_array($performance) && count($performance))
        <div class="card" style="margin-top:12px;">
            <div class="h2" style="margin-top:0;">Bukti Kinerja Pengurus</div>
            <div class="small muted">
                Perhitungan berdasarkan relasi <b>ticket_assignees</b> (1 tiket bisa ditangani beberapa pengurus).
                Angka menunjukkan total tiket yang pernah ditangani pada periode.
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nama Pengurus</th>
                        <th class="text-right">Tiket Ditangani</th>
                        <th class="text-right">Tiket Close</th>
                        <th class="text-right">Close Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performance as $p)
                        <tr>
                            <td class="wrap">{{ $p['name'] ?? '-' }}</td>
                            <td class="text-right">{{ $p['handled'] ?? 0 }}</td>
                            <td class="text-right">{{ $p['closed'] ?? 0 }}</td>
                            <td class="text-right">{{ $p['close_rate'] ?? 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="small muted" style="margin-top:8px;">
                Catatan: “Tiket Close” dapat dihitung dari tiket yang status akhirnya <b>close</b> pada periode, dan pengurus tercatat sebagai assignee.
            </div>
        </div>
    @endif

    {{-- TICKET LIST --}}
    <div class="card" style="margin-top:12px;">
        <div class="h2" style="margin-top:0;">Daftar Tiket (Periode)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">ID</th>
                    <th style="width: 22%;">Subject</th>
                    <th style="width: 12%;">Jenis</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 18%;">Lokasi</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 12%;">Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                    <tr>
                        <td class="nowrap">
                            {{-- Format tiket ala kamu (sp-xxxddmmyy...) bisa kamu generate di controller & kirim jadi ticket_code --}}
                            {{ $t->ticket_code ?? ('sp-' . substr(preg_replace('/[^0-9]/','',$t->id), -3) . \Carbon\Carbon::parse($t->created_at)->format('dmy')) }}
                        </td>
                        <td class="wrap"><b>{{ $t->subject }}</b></td>
                        <td class="nowrap">{{ $t->Jenis_Pengaduan }}</td>
                        <td class="nowrap">
                            @php
                                $st = $t->status;
                                $cls = $st==='open' ? 'st-open' : ($st==='on process' ? 'st-process' : ($st==='close' ? 'st-close' : 'st-escalated'));
                            @endphp
                            <span class="st {{ $cls }}">{{ $st }}</span>
                        </td>
                        <td class="wrap">{{ $t->Lokasi }}</td>
                        <td class="nowrap">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                        <td class="nowrap">
                            {{ $t->Tanggal_Selesai ? \Carbon\Carbon::parse($t->Tanggal_Selesai)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center muted">Tidak ada tiket pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(!empty($tickets) && method_exists($tickets, 'count'))
            <div class="small muted" style="margin-top:8px;">
                Total baris: {{ $tickets->count() }}
            </div>
        @endif
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="left">
            Laporan Keluhan Bulanan • Sistem Kos
        </div>
        <div class="right">
            Halaman <script type="text/php">echo $PAGE_NUM . " / " . $PAGE_COUNT;</script>
        </div>
    </div>

</body>
</html>

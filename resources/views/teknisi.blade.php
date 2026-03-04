@extends('mainlayout.layout')
@section('navbar')
    @include('mainlayout.navbar.nav')
@endsection
@section('pages')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
    </nav>
@endsection
@section('upnav')
    @include('mainlayout.navbar.upnavtek')
@endsection

@section('container')

    <div class="row g-1">
        <!-- Current Ticket -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Current Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle icon-48">
                            <i class="fa fa-copy text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Ticket (On Process) -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Proceeding Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalOnProcessTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle icon-48">
                            <i class="fa fa-clipboard text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Closed Ticket -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Closed Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalClosedTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle icon-48">
                            <i class="fa fa-check text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escalated Ticket -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Escalated Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalEscalatedTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle icon-48">
                            <i class="fa fa-exclamation-triangle text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unfinished Ticket (Not Close) -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Unfinished Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalUnfinishedTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle icon-48">
                            <i class="fa fa-hourglass-half text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Ticket -->
        <div class="col-6 col-sm-6 col-xl-2">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">All Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalAllTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle icon-48">
                            <i class="fa fa-folder text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="row mt-4 g-3">
        <!-- LINE CHART (KIRI) -->
        <div class="col-12 col-lg-8">
            <div class="card z-index-2 h-100 shadow-lg" style="border: 1px solid #e4e4e4;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="me-2">
                        <h6 class="mb-0">Tickets per Bulan (Permintaan vs Perbaikan)</h6>
                        <small class="text-secondary">
                            Tahun: {{ $selectedYear }} • Scope:
                            <b>{{ $scope === 'all' ? 'All (Resolved+Unresolved)' : strtoupper($scope) }}</b>
                        </small>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap chart-controls">
                        <!-- Dropdown scope -->
                        <select id="scopeSelect" class="form-select form-select-sm">
                            <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>All (Resolved+Unresolved)
                            </option>
                            <option value="resolved" {{ $scope === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="unresolved" {{ $scope === 'unresolved' ? 'selected' : '' }}>Unresolved</option>
                        </select>

                        <!-- Dropdown tahun -->
                        <select id="yearSelect" class="form-select form-select-sm">
                            @foreach ($years as $y)
                                <option value="{{ $y }}"
                                    {{ (int) $selectedYear === (int) $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-body">
                    <div class="chart-pop chart-wrap">
                        <canvas id="ticketsLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- PIE / DOUGHNUT (KANAN) -->
        <div class="col-12 col-lg-4">
            <div class="card z-index-2 h-100 shadow-lg" style="border: 1px solid #e4e4e4;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0">Komposisi Status</h6>

                    <select select id="pieMonthSelect" class="form-select form-select-sm" style="width: 140px;">
                        <option value="0">This year</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="card-body">
                    <div class="chart-pop chart-wrap chart-wrap--pie">
                        <canvas id="ticketsPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-card shadow-sm">
        {{-- HEADER (bersih, tanpa badge & quick actions) --}}
        <div class="card-header px-3 py-3">
            <h6 class="report-title">Download Laporan PDF</h6>
            <p class="report-subtitle mb-0">Rekap keluhan kos: ringkasan, close rate, top lokasi/subject, dan daftar tiket.
            </p>
        </div>

        <div class="card-body px-3 py-3">
            {{-- FORM FILTER --}}
            <form action="{{ route('teknisi.report.monthly') }}" method="GET">
                {{-- ambil period SEKALI di awal --}}
                @php $period = request('period','monthly'); @endphp

                {{-- ROW 1 --}}
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label mb-1">Periode</label>
                        <select name="period" id="periodSelect" class="form-select form-select-sm">
                            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan (Jan–Des)
                            </option>
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                        </select>
                        <div class="form-text">Pilih cakupan laporan.</div>
                    </div>

                    {{-- ✅ YEAR: hide saat period = all --}}
                    <div class="col-12 col-md-4 col-lg-3 {{ $period === 'all' ? 'd-none' : '' }}" id="yearWrap">
                        <label class="form-label mb-1">Tahun</label>
                        @php
                            $yNow = now()->year;
                            $yearsList = $years ?? collect(range($yNow, $yNow - 10));
                            $selectedYear = (int) request('year', $yNow);
                        @endphp
                        <select name="year" id="yearSelectReport" class="form-select form-select-sm"
                            {{ $period === 'all' ? 'disabled' : '' }}>
                            @foreach ($yearsList as $y)
                                <option value="{{ $y }}"
                                    {{ (int) $selectedYear === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <div class="form-text" id="yearHelp">Dipakai untuk Bulanan/Tahunan.</div>
                    </div>

                    {{-- ✅ MONTH: hide saat period = yearly ATAU all --}}
                    <div class="col-12 col-md-4 col-lg-3 {{ in_array($period, ['yearly', 'all']) ? 'd-none' : '' }}"
                        id="monthWrap">
                        <label class="form-label mb-1">Bulan</label>
                        @php
                            $months = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                            $selectedMonth = (int) request('month', now()->month);
                        @endphp
                        <select name="month" id="monthSelectReport" class="form-select form-select-sm"
                            {{ in_array($period, ['yearly', 'all']) ? 'disabled' : '' }}>
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}"
                                    {{ $selectedMonth === (int) $num ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text" id="monthHelp">Muncul hanya untuk Bulanan.</div>
                    </div>
                </div>

                {{-- ROW 2 --}}
                <div class="row g-3 align-items-end mt-1">
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Status (opsional)</label>
                        @php $status = request('status','all'); @endphp
                        <select name="status" class="form-select form-select-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="on process" {{ $status === 'on process' ? 'selected' : '' }}>On Process
                            </option>
                            <option value="close" {{ $status === 'close' ? 'selected' : '' }}>Close</option>
                            <option value="escalated" {{ $status === 'escalated' ? 'selected' : '' }}>Escalated</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Jenis (opsional)</label>
                        @php $jenis = request('jenis','all'); @endphp
                        <select name="jenis" class="form-select form-select-sm">
                            <option value="all" {{ $jenis === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="perbaikan" {{ $jenis === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            <option value="permintaan" {{ $jenis === 'permintaan' ? 'selected' : '' }}>Permintaan</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <div class="d-flex gap-2 justify-content-md-start flex-column flex-md-row">
                            <a href="{{ url()->current() }}" class="btn btn-soft btn-sm">
                                <i class="fa fa-rotate-left me-1"></i> Reset
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-download me-1"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="download" value="1">
            </form>




            <div class="report-divider"></div>

            {{-- HINT --}}
            <div class="report-hint d-flex align-items-start gap-2">
                <i class="fa fa-circle-info mt-1"></i>
                <div class="small text-muted">
                    <b>Bulanan:</b> hitung data dalam 1 bulan terpilih. &nbsp;|&nbsp;
                    <b>Tahunan:</b> akumulasi Jan–Des untuk tahun tertentu. &nbsp;|&nbsp;
                    <b>Semua Tahun:</b> akumulasi keseluruhan data.
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4 ">
            <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Current Ticket</h6>
                    <div class="d-flex">
                        <!-- Kolom Pencarian dengan input-group -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text text-body"><i class="fas fa-search"
                                    aria-hidden="true"></i></span>
                            <input type="text" id="search" class="form-control" placeholder="Search"
                                onfocus="focused(this)" onfocusout="defocused(this)">
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 h-500">
                    @if ($teknisi_data_ticket->isEmpty())
                        <div class="table-responsive margin-right: 15px; position: relative;"
                            style="height: 400px; max-height: 400px; overflow-y: auto;">
                            <!-- Add your button here -->
                            <a href="{{ route('customer.tickets') }}"
                                class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
                        </div>
                    @else
                        <div class="table-responsive margin-right: 15px;"
                            style="height: 400px; max-height: 400px; overflow-y: auto;">
                            <table class="table align-items-center mb-0" id="TicketTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            subject</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            User</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Deskripsi</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi Status</th>
                                        <th
                                            class="text-secondary text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teknisi_data_ticket as $teknisidataticket)
                                        <tr class="align-middle text-sm border border-light"
                                            data-created-at="{{ $teknisidataticket->created_at->timestamp }}"
                                            data-jenis-pengaduan="{{ $teknisidataticket->Jenis_Pengaduan }}"
                                            data-status="{{ $teknisidataticket->status }}">
                                            <td class="align-middle text-sm border border-light"
                                                data-subject="{{ $teknisidataticket->subject }}">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                                            <a
                                                                href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                                {{ $teknisidataticket->subject }}
                                                            </a>
                                                        </h6>

                                                        <div class="d-flex list-inline">
                                                            <li class="text-xs list-inline-item text-secondary"><i
                                                                    class="fa fa-circle fa-xs text-danger"></i>{{ 'sp-' . substr(preg_replace('/[^0-9]/', '', $teknisidataticket->id), -3) . \Carbon\Carbon::parse($teknisidataticket->created_at)->format('dmy') . ($teknisidataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}
                                                            </li>
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="type"><i
                                                                    class="fa fa-circle fa-xs text-primary"></i>{{ $teknisidataticket->Jenis_Pengaduan }}
                                                            </li>
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Created Date"><i
                                                                    class="fa fa-circle fa-xs text-secondary"></i></i>
                                                                {{ $teknisidataticket->formattedTanggalPengaduan }}</li>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm text-limit-20 border border-light"
                                                data-user="{{ $teknisidataticket->user->name }}">
                                                {{ $teknisidataticket->user->name }}
                                            </td>
                                            <td class="align-middle text-center text-sm border border-light"
                                                data-status="{{ $teknisidataticket->status }}">
                                                <x-status-badge :status="$teknisidataticket->status" />
                                            </td>
                                            <td class="align-middle text-center text-limit-30 border border-light">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold ">{{ $teknisidataticket->Detail }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm border border-light">
                                                <form action="{{ route('tickets.assign', $teknisidataticket->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-transparent text-primary">Proceed</button>
                                                </form>
                                            </td>
                                            <!-- "Edit" button within a dropdown -->
                                            <td class="align-middle text-center border border-light">
                                                <a class="dropdown-item"
                                                    href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                    <i class="fa fa-eye pe-2 text-dark"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div
                            style="padding: 15px 16px; border-top: 1px solid #e4e4e4; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <!-- Pagination Info as Dropdown -->
                                <div class="dropdown" style="position: relative;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="paginationDisplay">1-10 dari {{ $teknisi_data_ticket->count() }}</span>
                                        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                                        <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc"
                                                style="padding: 8px 16px;">
                                                <i class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru
                                            </a></li>
                                        <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc"
                                                style="padding: 8px 16px;">
                                                <i class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama
                                            </a></li>
                                    </ul>
                                </div>
                                <!-- Filter Jenis Pengaduan Dropdown -->
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        type="button" id="filterJenisPengaduanBtn" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="filterJenisPengaduanDisplay">Jenis Pengaduan</span>
                                        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterJenisPengaduanBtn"
                                        style="font-size: 13px; min-width: 150px;">
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="jenis_pengaduan" data-filter-value=""
                                                style="padding: 8px 16px;">Semua</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="jenis_pengaduan" data-filter-value="perbaikan"
                                                style="padding: 8px 16px;">Perbaikan</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="jenis_pengaduan" data-filter-value="permintaan"
                                                style="padding: 8px 16px;">Permintaan</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div style="display: flex; gap: 12px; align-items: center;">
                                <!-- Pagination Navigation -->
                                <div style="display: flex; gap: 6px;">
                                    <button id="prevPage" class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;"
                                        title="Halaman Sebelumnya">
                                        <i class="fa fa-chevron-left" style="font-size: 11px;"></i>
                                    </button>
                                    <button id="nextPage" class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;"
                                        title="Halaman Berikutnya">
                                        <i class="fa fa-chevron-right" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($teknisi_data_ticket->isEmpty())
                            <!-- Tampilkan tombol untuk membuat tiket baru -->
                            <a href="{{ route('customer.index') }}" class="btn btn-primary">Buat Tiket Baru</a>
                        @else
                            <!-- Iterasi melalui data tiket jika tidak kosong -->
                            @forelse ($teknisi_data_ticket as $teknisidataticket)
                                <form method="POST"
                                    action="{{ route('ticketsteknisi.update', $teknisidataticket->id) }}"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="subject">Subject</label>
                                                <input type="text" id="subject" name="subject"
                                                    class="form-control border-input" value="">
                                                @error('subject')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="Jenis_Pengaduan">Jenis Pengaduan</label>
                                                <select id="Jenis_Pengaduan" name="Jenis_Pengaduan"
                                                    class="form-control border-input">
                                                    <option value="" selected>--Pilih Jenis Pengaduan--</option>
                                                    <option value="perbaikan">Perbaikan</option>
                                                    <option value="permintaan">Permintaan</option>
                                                </select>
                                                @error('Jenis_Pengaduan')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="Lokasi">Alamat</label>
                                                <input type="text" id="Lokasi" name="Lokasi"
                                                    class="form-control border-input">
                                                @error('Lokasi')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="Detail">Deskripsi</label>
                                                <textarea id="Detail" name="Detail" rows="5" class="form-control border-input"
                                                    placeholder="Here can be your description" value=""></textarea>
                                                @error('Detail')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="gambar">Gambar Pendukung</label>
                                            <input class="form-control form-control-sm" id="gambar" name="gambar"
                                                type="file">
                                            @error('gambar')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-left mt-4">
                                            <button type="submit" class="btn btn-info btn-fill btn-wd">Submit
                                                ticket</button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            @empty
                                <p>Tidak ada data tiket yang tersedia.</p>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-4">

    </div>

    </div>

    <script>
        $(document).ready(function() {
            let currentSort = 'desc'; // Set default sorting to 'desc'
            let currentPage = 1;
            const itemsPerPage = 10;
            let filteredData = []; // To store filtered data
            let originalData = []; // To store original data (before filtering or searching)
            let hasActiveFilter = false; // FIX: Flag baru untuk track jika filter spesifik applied (bukan Semua)

            var table = $('#TicketTable').DataTable({
                searching: true,
                ordering: true,
                paging: false, // We will handle pagination manually
                lengthChange: false,
                info: false,
                columnDefs: [{
                    targets: [0, 1, 2],
                    orderable: true
                }, {
                    targets: [3, 4, 5],
                    orderable: false
                }]
            });

            $('#TicketTable_filter').hide();

            // Store original data (before filter or search)
            $('#TicketTable tbody tr').each(function() {
                originalData.push(this); // Store all rows as original data
            });

            // Handle column header sorting
            $('#TicketTable thead th').slice(0, 3).on('click', function() {
                var columnIndex = $(this).index();
                var isAsc = $(this).hasClass('sorting_asc');

                // Remove all sorting classes
                $('#TicketTable thead th').removeClass('sorting_asc sorting_desc').addClass('sorting');

                // Add sorting class to current column
                if (isAsc) {
                    $(this).removeClass('sorting').addClass('sorting_desc');
                } else {
                    $(this).removeClass('sorting').addClass('sorting_asc');
                }

                sortAllDataByColumn(columnIndex, !isAsc);
                currentPage = 1;
                updatePagination();
            });

            function sortAllDataByColumn(columnIndex, isAsc) {
                var rows = $('#TicketTable tbody tr').get();
                rows.sort(function(a, b) {
                    var aVal, bVal;
                    if (columnIndex === 0) {
                        aVal = $(a).data('subject') || '';
                        bVal = $(b).data('subject') || '';
                    } else if (columnIndex === 1) {
                        aVal = $(a).data('user') || '';
                        bVal = $(b).data('user') || '';
                    } else if (columnIndex === 2) {
                        aVal = $(a).data('status') || '';
                        bVal = $(b).data('status') || '';
                    }
                    // Case-insensitive string comparison
                    aVal = String(aVal).toLowerCase();
                    bVal = String(bVal).toLowerCase();
                    if (isAsc) {
                        return aVal.localeCompare(bVal);
                    } else {
                        return bVal.localeCompare(aVal);
                    }
                });
                $.each(rows, function(index, row) {
                    $('#TicketTable tbody').append(row);
                });
            }

            // Search filter (TIDAK DISENTUH, tetap seperti asli)
            $('#search').on('keyup', function() {
                var searchTerm = this.value.toLowerCase();
                // If search term is empty, restore the original data
                if (searchTerm === '') {
                    filteredData = []; // Clear filtered data
                    $('#TicketTable tbody').empty().append(originalData); // Restore original data
                    sortTableByDate(currentSort); // Reapply last sort
                    updatePagination(); // Reapply pagination
                    return;
                }
                $.fn.dataTable.ext.search = [];
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    return data[0].toLowerCase().includes(searchTerm) || data[1].toLowerCase()
                        .includes(searchTerm);
                });
                table.draw();
                currentPage = 1;
                updatePagination();
                // After searching, re-sort the table by date
                sortTableByDate(currentSort); // Ensure latest items are first
            });

            // Sorting by date (newest to oldest)
            $(document).on('click', '.page-sort-option', function(e) {
                e.preventDefault();
                currentSort = $(this).data('sort');
                sortTableByDate(currentSort);
                currentPage = 1;
                updatePagination();
            });

            function sortTableByDate(direction) {
                var rows = $('#TicketTable tbody tr').get();
                rows.sort(function(a, b) {
                    var aTimestamp = parseInt($(a).data('created-at')) || 0;
                    var bTimestamp = parseInt($(b).data('created-at')) || 0;
                    return direction === 'desc' ? bTimestamp - aTimestamp : aTimestamp - bTimestamp;
                });
                $.each(rows, function(index, row) {
                    $('#TicketTable tbody').append(row);
                });
            }

            // Update Pagination (dengan fix untuk no match)
            function updatePagination() {
                let totalRows;
                let rowsToDisplay;

                if (filteredData.length > 0) {
                    totalRows = filteredData.length;
                    rowsToDisplay = filteredData;
                } else {
                    // Jika filteredData kosong → check jika filter active
                    totalRows = $('#TicketTable tbody tr').length;
                    rowsToDisplay = $('#TicketTable tbody tr').get();
                }

                const totalPages = Math.ceil(totalRows / itemsPerPage);
                if (currentPage > totalPages) {
                    currentPage = totalPages || 1;
                }

                $('#TicketTable tbody tr').hide(); // Hide all rows first

                // FIX: Jika hasActiveFilter true dan filteredData.length === 0, treat sebagai no match
                if (hasActiveFilter && filteredData.length === 0) {
                    totalRows = 0;
                }

                if (totalRows === 0) {
                    // Tampilkan pesan jika nol
                    // Hapus pesan lama jika ada
                    $('.no-data-row').remove();
                    $('#TicketTable tbody').append(
                        '<tr class="no-data-row"><td colspan="6" class="text-center text-secondary py-4">Tidak ada data ditemukan</td></tr>'
                    );
                    $('#paginationDisplay').text('0-0 dari 0');
                } else {
                    // Hapus pesan no-data jika ada
                    $('.no-data-row').remove();

                    var startIndex = (currentPage - 1) * itemsPerPage;
                    var endIndex = startIndex + itemsPerPage;

                    for (let i = startIndex; i < endIndex && i < totalRows; i++) {
                        $(rowsToDisplay[i]).show();
                    }

                    var displayStart = startIndex + 1;
                    var displayEnd = Math.min(endIndex, totalRows);

                    if (currentSort === 'desc') {
                        $('#paginationDisplay').text(displayStart + '-' + displayEnd + ' dari ' + totalRows);
                    } else {
                        const reversedStart = totalRows - startIndex;
                        const reversedEnd = Math.max(reversedStart - (itemsPerPage - 1), 1);
                        $('#paginationDisplay').text(reversedStart + '-' + reversedEnd + ' dari ' + totalRows);
                    }
                }

                $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1')
                    .css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
                $('#nextPage').prop('disabled', currentPage === totalPages || totalRows === 0).css('opacity',
                    currentPage === totalPages || totalRows === 0 ? '0.5' : '1').css('cursor', currentPage ===
                    totalPages || totalRows === 0 ? 'not-allowed' : 'pointer');
            }

            $('#prevPage').on('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });

            $('#nextPage').on('click', function() {
                const totalRows = filteredData.length || $('#TicketTable tbody tr').length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });

            // Filter Dropdown: Jenis Pengaduan
            $(document).on('click', '.filter-option[data-filter-type="jenis_pengaduan"]', function(e) {
                e.preventDefault();
                var filterValue = $(this).data('filter-value');
                $('#filterJenisPengaduanDisplay').text($(this).text()); // Update button text
                filterTable();
            });

            function filterTable() {
                const selectedJenis = $('#filterJenisPengaduanDisplay').text().trim();

                const isAllJenis = selectedJenis === 'Jenis Pengaduan' || selectedJenis === 'Semua';

                hasActiveFilter = !isAllJenis; // FIX: Set flag true jika ada filter spesifik

                filteredData = [];
                originalData.forEach(function(row) {
                    var $row = $(row);
                    var jenis = $row.data('jenis-pengaduan') || '';

                    var matchJ = isAllJenis || jenis.toLowerCase().includes(selectedJenis.toLowerCase());

                    if (matchJ) {
                        filteredData.push(row);
                    }
                });

                currentPage = 1;
                updatePagination();

                // FIX: Jika reset ke Semua, pastikan fallback ke semua data dan show
                if (!hasActiveFilter) {
                    $('#TicketTable tbody tr').show();
                }
            }

            // Initial load: trigger pagination on page load (using default "Jenis Pengaduan" and "Status")
            updatePagination();
            // Sort table by date when the page loads
            sortTableByDate('desc'); // Default sort by 'created_at' desc (newest first)
            updatePagination(); // Update pagination after sorting
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodEl = document.getElementById('periodSelect');
            const yearWrap = document.getElementById('yearWrap');
            const monthWrap = document.getElementById('monthWrap');
            const yearSelect = document.getElementById('yearSelectReport');
            const monthSelect = document.getElementById('monthSelectReport');

            if (!periodEl || !yearWrap || !monthWrap || !yearSelect || !monthSelect) return;

            const show = (el) => el.classList.remove('d-none');
            const hide = (el) => el.classList.add('d-none');

            function applyPeriod() {
                const p = periodEl.value;

                // default: tampil semua
                show(yearWrap);
                show(monthWrap);
                yearSelect.disabled = false;
                monthSelect.disabled = false;

                if (p === 'all') {
                    hide(yearWrap);
                    hide(monthWrap);
                    yearSelect.disabled = true;
                    monthSelect.disabled = true;
                    return;
                }

                if (p === 'yearly') {
                    hide(monthWrap);
                    monthSelect.disabled = true;
                    return;
                }
                // monthly: tetap tampil semua
            }

            periodEl.addEventListener('change', applyPeriod);
            applyPeriod();
        });
    </script>


@endsection

<!-- Modal -->


<script>
    // Menangani peristiwa klik pada tombol edit
    document.getElementById('editButton').addEventListener('click', function() {
        // Memanggil modal dengan menggunakan modal('show')
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalMessage'));
        myModal.show();
    });
</script>

<!-- Add this script at the end of your HTML file or in a separate script file -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal element
        const modal = document.getElementById('exampleModalMessage');

        // Attach an event listener to the modal when it is shown
        modal.addEventListener('show.bs.modal', function(event) {
            // Extract the button that triggered the modal
            const button = event.relatedTarget;

            // Extract ticket details from the button's data attributes
            const ticketId = button.getAttribute('data-ticket-id');
            const ticketSubject = button.getAttribute('data-ticket-subject');
            const ticketJenis = button.getAttribute('data-ticket-jenis');
            const ticketLokasi = button.getAttribute('data-ticket-lokasi');
            const ticketDetail = button.getAttribute('data-ticket-detail');

            // Set the form values based on the ticket details
            modal.querySelector('#subject').value = ticketSubject;
            modal.querySelector('#Jenis_Pengaduan').value = ticketJenis;
            modal.querySelector('#Lokasi').value = ticketLokasi;
            modal.querySelector('#Detail').value = ticketDetail;
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartLine = @json($chartLine);
        const chartData = @json($chartData);

        const fmt = (n) => new Intl.NumberFormat('id-ID').format(n);

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const ANIM_MS = prefersReducedMotion ? 0 : 900;
        const EASING = 'easeOutQuart';

        // ✅ CSS appear
        document.querySelectorAll('.chart-pop').forEach(el => {
            requestAnimationFrame(() => el.classList.add('is-ready'));
        });

        // =========================
        // YEAR + SCOPE SELECT (reload)
        // =========================
        const chartYearEl = document.getElementById('chartYear');
        if (chartYearEl) chartYearEl.textContent = chartLine?.year ?? '';

        const yearSelect = document.getElementById('yearSelect');
        const scopeSelect = document.getElementById('scopeSelect');

        function reloadWithParams() {
            const url = new URL(window.location.href);
            if (yearSelect) url.searchParams.set('year', yearSelect.value);
            if (scopeSelect) url.searchParams.set('scope', scopeSelect.value);
            window.location.href = url.toString();
        }
        if (yearSelect) yearSelect.addEventListener('change', reloadWithParams);
        if (scopeSelect) scopeSelect.addEventListener('change', reloadWithParams);

        // =========================
        // PLUGIN: hover crosshair (line)
        // =========================
        const hoverLinePlugin = {
            id: 'hoverLine',
            afterDatasetsDraw(chart) {
                const active = chart.tooltip?.getActiveElements?.() || [];
                if (!active.length) return;

                const {
                    ctx,
                    chartArea: {
                        top,
                        bottom
                    }
                } = chart;
                const x = active[0].element.x;

                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x, top);
                ctx.lineTo(x, bottom);
                ctx.setLineDash([4, 4]);
                ctx.lineWidth = 1;
                ctx.strokeStyle = 'rgba(0,0,0,.12)';
                ctx.stroke();
                ctx.restore();
            }
        };

        // =========================
        // PLUGIN: center text (doughnut)
        // =========================
        const centerTextPlugin = {
            id: 'centerText',
            afterDraw(chart, args, opts) {
                if (chart.config.type !== 'doughnut') return;

                const {
                    ctx
                } = chart;
                const meta = chart.getDatasetMeta(0);
                if (!meta?.data?.length) return;

                const x = meta.data[0].x;
                const y = meta.data[0].y;

                const data = chart.data.datasets[0].data || [];
                const total = data.reduce((a, b) => a + b, 0);

                const closeIndex = chart.data.labels.indexOf('close');
                const closed = closeIndex >= 0 ? (data[closeIndex] || 0) : 0;
                const closeRate = total ? (closed / total * 100) : 0;

                const topText = opts?.topText ?? 'Total Ticket';
                const midText = opts?.midText ?? fmt(total);
                const botText = opts?.botText ?? `Close rate ${closeRate.toFixed(1)}%`;

                const isPhone = window.matchMedia('(max-width: 576px)').matches;

                // ✅ lebih kecil lagi di HP
                const topSize = opts?.topSize ?? (isPhone ? 8 : 12);
                const midSize = opts?.midSize ?? (isPhone ? 14 : 22);
                const botSize = opts?.botSize ?? (isPhone ? 8 : 12);

                // jarak antar teks juga diperkecil di HP
                const topOffset = isPhone ? 12 : 18;
                const botOffset = isPhone ? 14 : 22;

                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                ctx.fillStyle = 'rgba(52,71,103,.70)';
                ctx.font =
                    `600 ${topSize}px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial`;
                ctx.fillText(topText, x, y - topOffset);

                ctx.fillStyle = 'rgba(52,71,103,.95)';
                ctx.font =
                    `800 ${midSize}px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial`;
                ctx.fillText(midText, x, y + 1);

                ctx.fillStyle = 'rgba(52,71,103,.70)';
                ctx.font =
                    `600 ${botSize}px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial`;
                ctx.fillText(botText, x, y + botOffset);

                ctx.restore();
            }
        };

        Chart.register(hoverLinePlugin, centerTextPlugin);

        // ==========================================================
        // ✅ LINE CHART
        // ==========================================================
        const lineEl = document.getElementById('ticketsLineChart');
        if (lineEl) {
            const lineCtx = lineEl.getContext('2d');

            const lineLabels = chartLine.labels;
            const types = chartLine.types;

            const typeColors = {
                perbaikan: {
                    stroke: "rgba(34,193,195,1)", // Modify color for 'perbaikan'
                    fillTop: "rgba(34,193,195,.20)"
                },
                permintaan: {
                    stroke: "rgba(253,38,138,1)", // Modify color for 'permintaan'
                    fillTop: "rgba(253,38,138,.18)"
                },
            };

            const niceTypeLabel = (t) => (t === 'permintaan' ? 'Permintaan' : 'Perbaikan');

            const lineDatasets = types.map((t) => ({
                label: t,
                data: chartLine.monthlyByType[t] || Array(12).fill(0),
                borderColor: typeColors[t].stroke,
                backgroundColor: (context) => {
                    const chart = context.chart;
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    if (!chartArea) return typeColors[t].fillTop;

                    const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    g.addColorStop(0, typeColors[t].fillTop);
                    g.addColorStop(1, 'rgba(255,255,255,0)');
                    return g;
                },
                fill: true,
                tension: 0.38,
                borderWidth: 2,
                pointRadius: 2.8,
                pointHoverRadius: 6,
                pointHitRadius: 12,
                pointBorderWidth: 0,
            }));

            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: lineLabels,
                    datasets: lineDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: false,
                                boxWidth: 14,
                                boxHeight: 10,
                                padding: 16,
                                color: 'rgba(52,71,103,.85)',
                                font: {
                                    size: 12,
                                    weight: '600'
                                },
                                generateLabels: (chart) => {
                                    const original = Chart.defaults.plugins.legend.labels
                                        .generateLabels(chart);

                                    // Mengambil total perbaikan dan permintaan dari controller
                                    const perbaikanTotal = @json($jenisTicketTotal['perbaikan_total']);
                                    const permintaanTotal = @json($jenisTicketTotal['permintaan_total']);

                                    return original.map((item) => {
                                        const text = (item.text || '').toLowerCase();

                                        // Menambahkan total perbaikan dan permintaan ke label chart
                                        if (text === 'permintaan') {
                                            item.text = `Permintaan: ${permintaanTotal}`;
                                        }

                                        if (text === 'perbaikan') {
                                            item.text = `Perbaikan: ${perbaikanTotal}`;
                                        }

                                        item.fillStyle = item.strokeStyle;
                                        item.lineWidth = 0;
                                        return item;
                                    });
                                }
                            }
                        },
                        tooltip: {
                            padding: 12,
                            callbacks: {
                                label: (ctx) =>
                                    ` ${niceTypeLabel(ctx.dataset.label)}: ${fmt(ctx.parsed.y)}`,
                                footer: (items) => {
                                    const total = items.reduce((sum, it) => sum + (it.parsed.y ||
                                        0), 0);
                                    return `Total bulan ini: ${fmt(total)}`;
                                }
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 6,
                            right: 10,
                            bottom: 0,
                            left: 6
                        }
                    },

                    // ✅ muncul dari BAWAH (baseline y=0)
                    animation: {
                        duration: ANIM_MS,
                        easing: EASING
                    },
                    animations: {
                        y: {
                            from: (ctx) => {
                                const yScale = ctx.chart?.scales?.y;
                                return yScale ? yScale.getPixelForValue(0) : 0; // baseline pixel
                            }
                        },
                        radius: {
                            from: 0,
                            duration: prefersReducedMotion ? 0 : Math.round(ANIM_MS * 0.85),
                            easing: EASING
                        },
                        tension: {
                            duration: prefersReducedMotion ? 0 : 550,
                            easing: EASING,
                            from: 0.2,
                            to: 0.38
                        }
                    },

                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: 'rgba(52,71,103,.65)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: 'rgba(52,71,103,.65)',
                                callback: (v) => fmt(v)
                            },
                            grid: {
                                color: 'rgba(0,0,0,.06)',
                                borderDash: [6, 4]
                            }
                        }
                    }
                }
            });
        }

        // ==========================================================
        // ✅ DOUGHNUT CHART (animasi dipaksa tampil)
        // ==========================================================
        const pieEl = document.getElementById('ticketsPieChart');
        if (pieEl) {
            const pieCtx = pieEl.getContext('2d');
            const pieMonthSelect = document.getElementById('pieMonthSelect');

            const statuses = ['open', 'on process', 'escalated', 'close']; // Corrected order

            const statusColors = {
                "open": {
                    stroke: "rgba(94,114,228,1)" // Success color
                },
                "on process": {
                    stroke: "rgba(245,158,11,1)" // Warning color
                },
                "close": {
                    stroke: "rgba(255,0,0,1)" // Danger color
                },
                "escalated": {
                    stroke: "rgba(46,204,113,1)" // Info color
                }
            };

            const labelMap = {
                "open": "Open",
                "on process": "On Process",
                "close": "Close",
                "escalated": "Escalated"
            };

            const MONTHS_FULL = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                "September", "Oktober", "November", "Desember"
            ];
            const currentMonth = new Date().getMonth() + 1;

            function getPieData(monthValue) {
                if (monthValue === 0) {
                    return statuses.map(s => (chartData.monthlyByStatus[s] || []).reduce((a, b) => a + b, 0));
                }
                const idx = Math.max(0, Math.min(11, monthValue - 1));
                return statuses.map(s => (chartData.monthlyByStatus[s] || [])[idx] || 0);
            }

            function pieTopText(m) {
                return m === 0 ?
                    `Komposisi Status (${chartData.year})` :
                    `Komposisi Status (${MONTHS_FULL[m - 1]} ${chartData.year})`;
            }

            function closeRateText(dataArr) {
                const total = dataArr.reduce((a, b) => a + b, 0);
                const closeIndex = statuses.indexOf('close');
                const closed = closeIndex >= 0 ? (dataArr[closeIndex] || 0) : 0;
                const rate = total ? (closed / total * 100) : 0;
                return `Close rate ${rate.toFixed(1)}%`;
            }

            if (pieMonthSelect) pieMonthSelect.value = String(currentMonth);

            const initialMonth = pieMonthSelect ? Number(pieMonthSelect.value) : currentMonth;
            const initialData = getPieData(initialMonth);

            // ✅ render setelah 1 frame biar animasi “keliatan”
            requestAnimationFrame(() => {
                const doughnutChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statuses,
                        datasets: [{
                            data: initialData,
                            backgroundColor: statuses.map(s => statusColors[s]
                                ?.stroke || 'rgba(0,0,0,.4)'),
                            borderColor: 'rgba(255,255,255,.85)',
                            borderWidth: 2,
                            hoverOffset: 10,
                            borderRadius: 10,
                            spacing: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    padding: 14
                                }
                            },
                            tooltip: {
                                padding: 12,
                                callbacks: {
                                    label: (ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a +
                                            b, 0);
                                        const val = ctx.parsed || 0;
                                        const pct = total ? (val / total * 100) : 0;
                                        const nice = labelMap[ctx.label] || ctx.label;
                                        return ` ${nice}: ${fmt(val)} (${pct.toFixed(1)}%)`;
                                    }
                                }
                            },
                            centerText: {
                                topText: pieTopText(initialMonth),
                                midText: fmt(initialData.reduce((a, b) => a + b, 0)),
                                botText: closeRateText(initialData)
                            }
                        },

                        // ✅ animasi “muncul” yang kerasa
                        animation: {
                            duration: ANIM_MS,
                            easing: EASING,
                            animateRotate: true,
                            animateScale: true
                        },
                        animations: {
                            radius: {
                                from: 0,
                                duration: prefersReducedMotion ? 0 : ANIM_MS,
                                easing: EASING
                            },
                            circumference: {
                                from: 0,
                                duration: prefersReducedMotion ? 0 : ANIM_MS,
                                easing: EASING
                            }
                        }
                    }
                });

                // ✅ update bulan dengan animasi yang kerasa
                if (pieMonthSelect) {
                    pieMonthSelect.addEventListener('change', () => {
                        const m = Number(pieMonthSelect.value);
                        const data = getPieData(m);

                        doughnutChart.data.datasets[0].data = data;
                        doughnutChart.options.plugins.centerText.topText = pieTopText(m);
                        doughnutChart.options.plugins.centerText.midText = fmt(data.reduce((a,
                            b) => a + b, 0));
                        doughnutChart.options.plugins.centerText.botText = closeRateText(data);

                        doughnutChart.update('active'); // 🔥 anim lebih kerasa
                    });
                }
            });
        }
    });
</script>


<style>
    .chart-pop {
        opacity: 0;
        transform: translateY(16px) scaleY(.92);
        transform-origin: bottom;
        transition: opacity .35s ease, transform .35s ease;
    }

    .chart-pop.is-ready {
        opacity: 1;
        transform: translateY(0) scaleY(1);
    }
</style>

<style>
    /* default desktop */
    .chart-wrap {
        height: 330px;
    }

    .chart-wrap--pie {
        height: 330px;
    }

    /* controls width desktop */
    .chart-controls #scopeSelect {
        width: 195px;
    }

    .chart-controls #yearSelect {
        width: 110px;
    }

    .pie-month {
        width: 140px;
    }

    /* anim wrapper (muncul dari bawah) */
    .chart-pop {
        opacity: 0;
        transform: translateY(16px) scaleY(.92);
        transform-origin: bottom;
        transition: opacity .35s ease, transform .35s ease;
    }

    .chart-pop.is-ready {
        opacity: 1;
        transform: translateY(0) scaleY(1);
    }

    /* ===== MOBILE ===== */
    @media (max-width: 576px) {
        .chart-wrap {
            height: 260px;
        }

        .chart-wrap--pie {
            height: 280px;
        }

        /* biar header ga kepotong */
        .card-header {
            flex-wrap: wrap !important;
            gap: 10px !important;
            padding-bottom: .75rem;
        }

        /* dropdown jadi full width dan turun rapi */
        .chart-controls,
        .chart-controls #scopeSelect,
        .chart-controls #yearSelect,
        .pie-month {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* judul lebih rapih di hp */
        .card-header h6 {
            font-size: 14px;
        }

        .card-header small {
            display: block;
            margin-top: 2px;
            line-height: 1.2;
        }
    }
</style>

<style>
    .card-stat {
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .card-stat-title {
        font-size: 11px;
        letter-spacing: .5px;
        line-height: 1.15;
    }

    .card-stat-value {
        font-size: 28px;
        line-height: 1.2;
    }

    .card-stat-icon {
        flex: 0 0 auto;
    }

    .icon-48 {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* HP: kecilin sedikit biar muat */
    @media (max-width: 576px) {
        .card-stat {
            min-height: 108px;
        }

        .card-stat-title {
            font-size: 10px;
        }

        .card-stat-value {
            font-size: 24px;
        }

        .icon-48 {
            width: 42px;
            height: 42px;
        }
    }
</style>

<style>
    /* Grid form yang auto-rapi walau field disembunyikan */
    .report-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .report-grid .field-wrap {
        display: block;
    }

    .report-grid .hidden {
        display: none !important;
    }

    /* ✅ FIX: kolom ke-3 jadi max-content biar tombol gak kejauhan */
    .report-grid-2 {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) max-content;
        gap: 12px;
        align-items: end;
        margin-top: 12px;
    }

    .report-grid-2 .actions-wrap {
        grid-column: 1 / -1;
        /* actions turun ke baris bawah, full width */
    }


    /* ✅ biar kolom actions benar2 nempel kanan */
    .report-grid-2 .field-wrap:last-child {
        justify-self: end;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .report-actions .btn {
        white-space: nowrap;
        /* tombol gak pecah kata */
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .report-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        /* di tablet: jadi 2 kolom (Status | Jenis) lalu Actions full di baris bawah */
        .report-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .report-grid-2 .field-wrap:last-child {
            justify-self: stretch;
            /* actions ikut lebar */
            grid-column: 1 / -1;
            /* pindah full row biar rapi */
        }

        .report-actions {
            justify-content: stretch;
        }

        .report-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .report-grid {
            grid-template-columns: 1fr;
        }

        .report-grid-2 {
            grid-template-columns: 1fr;
        }

        .report-grid-2 .field-wrap:last-child {
            grid-column: auto;
        }
    }
</style>

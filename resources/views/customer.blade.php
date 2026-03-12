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
    @include('mainlayout.navbar.upnav')
@endsection

@section('container')

    <div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3"
                style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
                <div class="numbers" style="flex: 1;">
                    <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                        Open</p>
                    <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
                        {{ $OpenTic }}
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle"
                        style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-copy text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3"
                style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
                <div class="numbers" style="flex: 1;">
                    <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                        On Process</p>
                    <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
                        {{ $OnProcessTickets }}
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle"
                        style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-clipboard text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3"
                style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
                <div class="numbers" style="flex: 1;">
                    <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                        Close</p>
                    <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
                        {{ $closedtic }}
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
                    <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle"
                        style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-minus text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-6">
        <div class="card">
            <div class="card-body p-3"
                style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
                <div class="numbers" style="flex: 1;">
                    <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                        Escalation</p>
                    <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
                        {{ $totalEscalation }}
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle"
                        style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-folder text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0">Ticket list</h6>

                    <div class="d-flex align-items-center gap-2" style="min-width: 280px;">
                        <a href="{{ route('customer.tickets') }}" class="btn btn-primary btn-sm mb-0">Buat Tiket</a>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text text-body">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input
                                type="text"
                                id="customSearch"
                                class="form-control"
                                placeholder="Search subject"
                                onfocus="focused(this)"
                                onfocusout="defocused(this)">
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2 h-500">
                    <style>
                        #TicketTable {
                            border-collapse: collapse !important;
                            width: 100% !important;
                        }

                        #TicketTable thead th,
                        #TicketTable tbody td {
                            text-align: center !important;
                            vertical-align: middle !important;
                        }

                        #TicketTable thead th {
                            border-top: 1px solid #e9ecef !important;
                            border-bottom: none !important;
                            border-left: none !important;
                            border-right: none !important;
                            background: #fff !important;
                            white-space: nowrap;
                            position: sticky;
                            top: 0;
                            z-index: 2;
                            padding: 12px 10px !important;
                            text-align: center !important;
                        }

                        #TicketTable tbody td {
                            border-bottom: 1px solid #e9ecef !important;
                            border-right: 1px solid #e9ecef !important;
                            padding: 12px 10px !important;
                            background: #fff;
                        }

                        #TicketTable thead th span.dt-column-order {
                            transform: scale(1.50) !important;
                            font-weight: 700;
                            color: #5e72e4 !important;
                            opacity: 1 !important;
                        }

                        #TicketTable tbody td:first-child {
                            border-left: 1px solid #e9ecef !important;
                            text-align: left !important;
                        }

                        #TicketTable tbody td:first-child > div {
                            justify-content: flex-start !important;
                            text-align: left !important;
                        }

                        #TicketTable tbody tr:hover td {
                            background: #fafafa !important;
                        }

                        #TicketTable tbody td > div {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 44px;
                            text-align: center;
                        }

                        .ticket-subject-wrap {
                            display: flex;
                            flex-direction: column;
                            align-items: flex-start !important;
                            justify-content: center;
                            gap: 4px;
                            text-align: left !important;
                            width: 100%;
                        }

                        .ticket-subject-wrap a {
                            text-align: left !important;
                            display: inline-block;
                            width: 100%;
                        }

                        .ticket-subject-wrap .ticket-meta {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: flex-start !important;
                            gap: 6px 12px;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                        }

                        .ticket-subject-wrap .ticket-meta li {
                            margin: 0;
                            padding: 0;
                        }

                        .ticket-table-shell {
                            display: flex;
                            flex-direction: column;
                            min-height: 520px;
                        }

                        .ticket-table-scroller {
                            flex: 1 1 auto;
                            overflow-y: auto;
                            overflow-x: auto;
                            margin-right: 15px;
                            height: 400px;
                            max-height: 400px;
                        }

                        #TicketTable thead th.sorting,
                        #TicketTable thead th.sorting_asc,
                        #TicketTable thead th.sorting_desc {
                            position: relative;
                            cursor: pointer;
                            user-select: none;
                            white-space: nowrap;
                            color: #6c757d;
                        }

                        #TicketTable thead th.sorting .sort-icons,
                        #TicketTable thead th.sorting_asc .sort-icons,
                        #TicketTable thead th.sorting_desc .sort-icons {
                            display: inline-block;
                            position: relative;
                            width: 15px;
                            height: 18px;
                            margin-left: 7px;
                            vertical-align: middle;
                            top: -1px;
                        }

                        #TicketTable thead th.sorting .sort-icons::before,
                        #TicketTable thead th.sorting .sort-icons::after,
                        #TicketTable thead th.sorting_asc .sort-icons::before,
                        #TicketTable thead th.sorting_asc .sort-icons::after,
                        #TicketTable thead th.sorting_desc .sort-icons::before,
                        #TicketTable thead th.sorting_desc .sort-icons::after {
                            content: '';
                            position: absolute;
                            left: 50%;
                            transform: translateX(-50%);
                            border-left: 4px solid transparent;
                            border-right: 4px solid transparent;
                            transition: opacity 0.2s ease;
                        }

                        #TicketTable thead th.sorting .sort-icons::before,
                        #TicketTable thead th.sorting_asc .sort-icons::before,
                        #TicketTable thead th.sorting_desc .sort-icons::before {
                            top: 1px;
                            border-bottom: 6px solid #6c757d;
                        }

                        #TicketTable thead th.sorting .sort-icons::after,
                        #TicketTable thead th.sorting_asc .sort-icons::after,
                        #TicketTable thead th.sorting_desc .sort-icons::after {
                            bottom: 1px;
                            border-top: 6px solid #6c757d;
                        }

                        #TicketTable thead th.sorting .sort-icons::before,
                        #TicketTable thead th.sorting .sort-icons::after {
                            opacity: 0.65;
                        }

                        #TicketTable thead th.sorting:hover .sort-icons::before,
                        #TicketTable thead th.sorting:hover .sort-icons::after {
                            opacity: 0.9;
                        }

                        #TicketTable thead th.sorting_asc {
                            color: #495057;
                        }

                        #TicketTable thead th.sorting_asc .sort-icons::before {
                            opacity: 1;
                        }

                        #TicketTable thead th.sorting_asc .sort-icons::after {
                            opacity: 0.22;
                        }

                        #TicketTable thead th.sorting_desc {
                            color: #495057;
                        }

                        #TicketTable thead th.sorting_desc .sort-icons::before {
                            opacity: 0.22;
                        }

                        #TicketTable thead th.sorting_desc .sort-icons::after {
                            opacity: 1;
                        }

                        #TicketTable thead th.sorting::before,
                        #TicketTable thead th.sorting::after,
                        #TicketTable thead th.sorting_asc::before,
                        #TicketTable thead th.sorting_asc::after,
                        #TicketTable thead th.sorting_desc::before,
                        #TicketTable thead th.sorting_desc::after {
                            display: none !important;
                            content: none !important;
                        }

                        #TicketTable_wrapper {
                            padding: 0;
                        }

                        #TicketTable_wrapper .dt-layout-row:first-child,
                        #TicketTable_wrapper .dt-layout-row:last-child {
                            display: none !important;
                        }

                        .ticket-table-footer {
                            flex: 0 0 auto;
                            border-top: 1px solid #ececec;
                            background: #fff;
                            padding: 14px 16px;
                        }

                        .ticket-table-footer-top,
                        .ticket-table-footer-bottom {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 12px;
                            flex-wrap: wrap;
                        }

                        .ticket-table-footer-top {
                            margin-bottom: 12px;
                        }

                        .ticket-table-footer-group {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            flex-wrap: wrap;
                        }

                        .ticket-table-footer .form-select,
                        .ticket-table-footer .dt-length select {
                            border: 1px solid #ffffff !important;
                            border-radius: 8px;
                            padding: 6px 12px;
                            font-size: 13px;
                            background-color: #fff;
                            color: #344767;
                            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                        }

                        .ticket-table-footer .form-select:focus,
                        .ticket-table-footer .dt-length select:focus {
                            border-color: #ffffff !important;
                            box-shadow: 0 0 0 0.1rem rgba(94, 114, 228, 0.08) !important;
                        }

                        .ticket-table-footer .dt-length {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin: 0;
                        }

                        .ticket-table-footer .dt-length label {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin: 0;
                            font-size: 12px;
                            color: #67748e;
                            font-weight: 600;
                        }

                        .ticket-table-footer .dt-info {
                            margin: 0;
                            font-size: 13px;
                            color: #67748e;
                        }

                        .ticket-table-footer .dt-paging {
                            margin: 0;
                        }

                        .ticket-table-footer .dt-paging .dt-paging-button {
                            border-radius: 8px !important;
                            min-width: 34px;
                            height: 34px;
                            margin: 0 2px;
                            border: 1px solid #d2d6da !important;
                            background: #ffffff !important;
                            color: #344767 !important;
                        }

                        .ticket-table-footer .dt-paging .dt-paging-button.current {
                            background: #5e72e4 !important;
                            border-color: #5e72e4 !important;
                            color: #fff !important;
                            box-shadow: none !important;
                        }

                        .ticket-table-footer .dt-paging .dt-paging-button:hover {
                            background: #f8f9fa !important;
                            color: #344767 !important;
                            border-color: #cfd4da !important;
                        }

                        .ticket-table-footer .dt-paging .dt-paging-button.current:hover {
                            background: #5e72e4 !important;
                            color: #fff !important;
                            border-color: #5e72e4 !important;
                        }

                        .ticket-table-footer .dt-paging .disabled {
                            opacity: 0.5 !important;
                            cursor: not-allowed !important;
                        }

                        @media (max-width: 768px) {
                            .ticket-table-footer {
                                padding: 12px;
                            }

                            .ticket-table-footer-top,
                            .ticket-table-footer-bottom {
                                flex-direction: column;
                                align-items: stretch;
                            }

                            .ticket-table-footer-group {
                                width: 100%;
                            }

                            .ticket-table-footer .form-select,
                            .ticket-table-footer .dt-length select {
                                width: 100%;
                                min-width: 100%;
                            }

                            .ticket-table-footer .dt-paging {
                                overflow-x: auto;
                                width: 100%;
                            }
                        }
                    </style>

                    <div class="ticket-table-shell">
                        <div class="table-responsive ticket-table-scroller">
                            <table class="table align-items-center mb-0" id="TicketTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">subject</th>
                                        <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Deskripsi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="ticket-table-footer">
                            <div class="ticket-table-footer-top">
                                <div class="ticket-table-footer-group">
                                    <div id="ticket-length-slot"></div>
                                </div>

                                <div class="ticket-table-footer-group">
                                    <div>
                                        <select id="filterJenisPengaduan" class="form-select form-select-sm">
                                            <option value="">Jenis Pengaduan</option>
                                            <option value="perbaikan">Jenis Pengaduan: Perbaikan</option>
                                            <option value="permintaan">Jenis Pengaduan: Permintaan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <select id="filterStatus" class="form-select form-select-sm">
                                            <option value="">Status</option>
                                            <option value="open">Status: Open</option>
                                            <option value="on process">Status: On Process</option>
                                            <option value="escalated">Status: Escalated</option>
                                            <option value="close">Status: Close</option>
                                        </select>
                                    </div>

                                    <div>
                                        <select id="sortCreatedAt" class="form-select form-select-sm" style="min-width: 150px;">
                                            <option value="desc">Tanggal: Terbaru</option>
                                            <option value="asc">Tanggal: Terlama</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-table-footer-bottom">
                                <div class="ticket-table-footer-group">
                                    <div id="ticket-info-slot"></div>
                                </div>

                                <div class="ticket-table-footer-group">
                                    <div id="ticket-paging-slot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcement -->
        <div class="col-lg-4 ms-auto">
            <div class="card shadow-lg overflow-hidden h-100 p-0">
                <div class="card-header bg-gradient-success border-0 p-3">
                    <h5 class="mb-0 text-white">Announcement</h5>
                </div>
                <div class="card-body p-4" style="height: 500px; overflow-y: auto;">
                    <div class="list-group">
                        @if ($pengumuman->isEmpty())
                            <div class="text-center text-muted py-5">
                                <i class="fa fa-inbox fa-3x mb-3 opacity-5"></i>
                                <p>Tidak ada pengumuman</p>
                            </div>
                        @else
                            @foreach ($pengumuman as $item)
                                <div class="list-group-item shadow-sm mb-3"
                                    style="padding: 12px 16px; border: 1px solid #e4e4e4; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;"
                                    data-bs-toggle="modal" data-bs-target="#announcementModal"
                                    data-pengumuman-id="{{ $item->id }}" data-pengumuman-judul="{{ $item->judul }}"
                                    data-pengumuman-deskripsi="{{ $item->deskripsi }}"
                                    data-creator-name="{{ $item->creator->name }}"
                                    data-creator-role="{{ $item->creator->role }}"
                                    data-creator-photo="{{ $item->creator && $item->creator->profile_photo ? route('profile.photo', ['filename' => basename($item->creator->profile_photo)]) : asset('default-profile.png') }}"
                                    data-created-at="{{ $item->created_at }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $item->creator && $item->creator->profile_photo ? route('profile.photo', ['filename' => basename($item->creator->profile_photo)]) : asset('default-profile.png') }}"
                                            alt="Profile" class="rounded-circle"
                                            style="width: 32px; height: 32px; object-fit: cover; margin-right: 10px;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark" style="font-size: 14px; font-weight: 600;">
                                                {{ $item->creator->name }}</h6>
                                            <small class="text-muted"
                                                style="font-size: 11px;">{{ $item->creator->role }}</small>
                                        </div>
                                        <small class="text-muted"
                                            style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                    </div>

                                    <h5 class="mb-2 text-dark" style="font-size: 15px; font-weight: 600;">
                                        {{ $item->judul }}</h5>

                                    <p class="mb-0 text-muted" style="font-size: 13px; line-height: 1.4;">
                                        {{ Str::limit($item->deskripsi, 100) }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcement Modal -->
        <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog"
            aria-labelledby="announcementModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center w-100">
                            <img id="modalCreatorPhoto" src="" alt="Profile" class="rounded-circle"
                                style="width: 40px; height: 40px; object-fit: cover; margin-right: 12px;">
                            <div class="flex-grow-1">
                                <h6 id="modalCreatorName" class="mb-0 text-dark"
                                    style="font-size: 14px; font-weight: 600;"></h6>
                                <small id="modalCreatorRole" class="text-muted" style="font-size: 11px;"></small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body pt-2">
                        <h5 id="modalJudul" class="text-dark mb-3" style="font-size: 18px; font-weight: 600;"></h5>
                        <small id="modalCreatedAt" class="text-muted d-block mb-3" style="font-size: 12px;"></small>
                        <p id="modalDeskripsi" class="text-muted" style="font-size: 14px; line-height: 1.6;"></p>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <small id="modalTimeAgo" class="text-muted me-auto" style="font-size: 12px;"></small>
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Ticket -->
        <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="editTicketForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" id="ticketId" name="ticketId" value="">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" id="subject" name="subject" class="form-control border-input">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Jenis_Pengaduan">Jenis Pengaduan</label>
                                        <select id="Jenis_Pengaduan" name="Jenis_Pengaduan" class="form-control border-input">
                                            <option value="" selected>--Pilih Jenis Pengaduan--</option>
                                            <option value="perbaikan">Perbaikan</option>
                                            <option value="permintaan">Permintaan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Lokasi">Alamat</label>
                                        <input type="text" id="Lokasi" name="Lokasi" class="form-control border-input">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Detail">Deskripsi</label>
                                        <textarea id="Detail" name="Detail" rows="5" class="form-control border-input"
                                            placeholder="Here can be your description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="gambar">Gambar Pendukung</label>
                                    <input class="form-control form-control-sm" id="gambar" name="gambar" type="file" accept="image/*">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-left mt-4">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Submit ticket</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="//cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

<script>
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escapeAttr(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function debounce(fn, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function renderStatusBadge(status) {
        if (status === 'open') {
            return `<span class="badge badge-sm bg-gradient-success">${escapeHtml(status)}</span>`;
        } else if (status === 'on process') {
            return `<span class="badge badge-sm bg-gradient-warning">${escapeHtml(status)}</span>`;
        } else if (status === 'close') {
            return `<span class="badge badge-sm bg-gradient-danger">${escapeHtml(status)}</span>`;
        } else if (status === 'escalated') {
            return `<span class="badge badge-sm bg-gradient-info">${escapeHtml(status)}</span>`;
        } else {
            return `<span class="badge badge-sm bg-gradient-secondary">Unknown Status</span>`;
        }
    }

    function renderSubjectColumn(row) {
        return `
            <div class="ticket-subject-wrap">
                <h6 class="mb-0 text-s text-limit-35" title="Subject">
                    <a href="${row.view_url}">
                        ${escapeHtml(row.subject)}
                    </a>
                </h6>

                <ul class="ticket-meta">
                    <li class="text-xs text-secondary">
                        <i class="fa fa-circle fa-xs text-danger"></i>${escapeHtml(row.ticket_code)}
                    </li>
                    <li class="text-xs text-secondary" title="type">
                        <i class="fa fa-circle fa-xs text-primary"></i>${escapeHtml(row.Jenis_Pengaduan)}
                    </li>
                    <li class="text-xs text-secondary" title="Created Date">
                        <i class="fa fa-circle fa-xs text-secondary"></i> ${escapeHtml(row.created_at_formatted)}
                    </li>
                </ul>
            </div>
        `;
    }

    function renderAksi(row) {
        const deleteAction = row.can_delete ? `
            <li>
                <form method="POST" action="${row.delete_url}" onsubmit="return confirm('are you sure?')">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fa fa-trash pe-2 text-danger"></i>delete
                    </button>
                </form>
            </li>
        ` : '';

        return `
            <div class="dropdown">
                <a class="btn text-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class=""></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item text-info" href="${row.view_url}">
                            <i class="fa fa-eye pe-2 text-info"></i>Detail
                        </a>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item text-success btn-edit-ticket"
                            data-ticket-id="${row.id}"
                            data-ticket-subject="${escapeAttr(row.subject)}"
                            data-ticket-jenis="${escapeAttr(row.Jenis_Pengaduan)}"
                            data-ticket-lokasi="${escapeAttr(row.Lokasi)}"
                            data-ticket-detail="${escapeAttr(row.Detail)}"
                            data-ticket-update-url="${row.update_url}">
                            <i class="fa fa-pencil pe-2 text-success"></i>edit
                        </button>
                    </li>
                    ${deleteAction}
                </ul>
            </div>
        `;
    }

    function moveDataTableControls() {
        const wrapper = document.getElementById('TicketTable_wrapper');
        if (!wrapper) return;

        const length = wrapper.querySelector('.dt-length');
        const info = wrapper.querySelector('.dt-info');
        const paging = wrapper.querySelector('.dt-paging');

        const lengthSlot = document.getElementById('ticket-length-slot');
        const infoSlot = document.getElementById('ticket-info-slot');
        const pagingSlot = document.getElementById('ticket-paging-slot');

        if (length && lengthSlot && !lengthSlot.contains(length)) {
            lengthSlot.innerHTML = '';
            lengthSlot.appendChild(length);
        }

        if (info && infoSlot && !infoSlot.contains(info)) {
            infoSlot.innerHTML = '';
            infoSlot.appendChild(info);
        }

        if (paging && pagingSlot && !pagingSlot.contains(paging)) {
            pagingSlot.innerHTML = '';
            pagingSlot.appendChild(paging);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const table = new DataTable('#TicketTable', {
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            searchDelay: 350,
            layout: {
                topStart: 'pageLength',
                topEnd: null,
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            ajax: {
                url: "{{ route('customer.tickets.datatable', ['mode' => $tableMode]) }}",
                type: "GET",
                data: function(d) {
                    d.filter_status = document.getElementById('filterStatus').value;
                    d.filter_jenis_pengaduan = document.getElementById('filterJenisPengaduan').value;
                    d.sort_created_at = document.getElementById('sortCreatedAt').value;
                }
            },
            columns: [
                {
                    data: null,
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return renderSubjectColumn(row);
                    }
                },
                {
                    data: 'status',
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return `<div>${renderStatusBadge(data)}</div>`;
                    }
                },
                {
                    data: 'detail_short',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `<div><span class="text-secondary text-xs font-weight-bold">${escapeHtml(data ?? '')}</span></div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div>${renderAksi(row)}</div>`;
                    }
                }
            ],
            order: [],
            language: {
                processing: 'Memuat data...',
                search: 'Cari Subject:',
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data tiket',
                paginate: {
                    first: '‹‹',
                    last: '››',
                    next: '›',
                    previous: '‹'
                }
            },
            initComplete: function() {
                moveDataTableControls();
            },
            drawCallback: function() {
                moveDataTableControls();
            }
        });

        const debouncedSearch = debounce(function(value) {
            table.search(value).draw();
        }, 350);

        document.getElementById('customSearch').addEventListener('input', function() {
            debouncedSearch(this.value);
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            table.ajax.reload();
        });

        document.getElementById('filterJenisPengaduan').addEventListener('change', function() {
            table.ajax.reload();
        });

        document.getElementById('sortCreatedAt').addEventListener('change', function() {
            table.ajax.reload();
        });

        document.addEventListener('click', function(e) {
            const editButton = e.target.closest('.btn-edit-ticket');
            if (!editButton) return;

            const modalEl = document.getElementById('exampleModalMessage');
            const form = modalEl.querySelector('#editTicketForm');

            form.action = editButton.dataset.ticketUpdateUrl;
            form.querySelector('#ticketId').value = editButton.dataset.ticketId;
            form.querySelector('#subject').value = editButton.dataset.ticketSubject;
            form.querySelector('#Jenis_Pengaduan').value = editButton.dataset.ticketJenis;
            form.querySelector('#Lokasi').value = editButton.dataset.ticketLokasi;
            form.querySelector('#Detail').value = editButton.dataset.ticketDetail;

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        const announcementModal = document.getElementById('announcementModal');
        if (announcementModal) {
            announcementModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const judul = button.getAttribute('data-pengumuman-judul');
                const deskripsi = button.getAttribute('data-pengumuman-deskripsi');
                const creatorName = button.getAttribute('data-creator-name');
                const creatorRole = button.getAttribute('data-creator-role');
                const creatorPhoto = button.getAttribute('data-creator-photo');
                const createdAt = button.getAttribute('data-created-at');

                document.getElementById('modalJudul').textContent = judul;
                document.getElementById('modalDeskripsi').textContent = deskripsi;
                document.getElementById('modalCreatorName').textContent = creatorName;
                document.getElementById('modalCreatorRole').textContent = creatorRole;
                document.getElementById('modalCreatorPhoto').src = creatorPhoto;

                document.getElementById('modalCreatedAt').textContent = new Date(createdAt).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                if (window.moment) {
                    document.getElementById('modalTimeAgo').textContent = moment(createdAt).fromNow();
                } else {
                    document.getElementById('modalTimeAgo').textContent = '';
                }
            });
        }
    });
</script>
@endsection
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

    <div class="row g-1">
        <!-- Open Ticket -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Open Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $OpenTic }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape shadow-success text-center rounded-circle icon-48"
                            style="background: rgba(46,204,113,1);">
                            <i class="fa fa-ticket text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Process Ticket -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">On Process Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $OnProcessTickets }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape shadow-warning text-center rounded-circle icon-48"
                            style="background: rgba(245,158,11,1);">
                            <i class="fa fa-spinner text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escalation Ticket -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Escalation Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $totalEscalation }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape shadow-primary text-center rounded-circle icon-48"
                            style="background: rgba(94,114,228,1);">
                            <i class="fa fa-flag text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Closed Ticket -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-3 card-stat">
                    <div class="numbers">
                        <p class="text-sm mb-2 text-uppercase font-weight-bold card-stat-title">Closed Ticket</p>
                        <h5 class="font-weight-bolder mb-0 card-stat-value">{{ $closedtic }}</h5>
                    </div>
                    <div class="card-stat-icon">
                        <div class="icon icon-shape shadow-danger text-center rounded-circle icon-48"
                            style="background: rgba(255,0,0,1);">
                            <i class="fa fa-archive text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4 ">
            <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Ticket list</h6>
                    <div class="d-flex">
                        <!-- Kolom Pencarian dengan input-group -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" id="search" class="form-control" placeholder="Search"
                                onfocus="focused(this)" onfocusout="defocused(this)">
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 h-500">
                    @if ($data_ticket->isEmpty())
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
                                        <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">subject</th>
                                        <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Description</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_ticket as $dataticket)
                                        <tr class="align-middle text-sm border border-light"
                                            data-created-at="{{ $dataticket->created_at->timestamp }}"
                                            data-jenis-pengaduan="{{ $dataticket->Jenis_Pengaduan }}"
                                            data-status="{{ $dataticket->status }}"
                                            data-subject="{{ $dataticket->subject }}"
                                            data-user="{{ $dataticket->user->name }}">
                                            <td class="align-middle text-sm border border-light">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                                            <a
                                                                href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
                                                                {{ $dataticket->subject }}
                                                            </a>
                                                        </h6>
                                                        <div class="d-flex list-inline">
                                                            <li class="text-xs list-inline-item text-secondary"><i
                                                                    class="fa fa-circle fa-xs text-danger"></i>{{ 'sp-' . substr(preg_replace('/[^0-9]/', '', $dataticket->id), -3) . \Carbon\Carbon::parse($dataticket->created_at)->format('dmy') . ($dataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}
                                                            </li>
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="type"><i
                                                                    class="fa fa-circle fa-xs text-primary"></i>{{ $dataticket->Jenis_Pengaduan }}
                                                            </li>
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Created Date"><i
                                                                    class="fa fa-circle fa-xs text-secondary"></i></i>
                                                                {{ $dataticket->formattedTanggalPengaduan }}</li>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm border border-light">
                                                <x-status-badge :status="$dataticket->status" />
                                            </td>
                                            <td class="align-middle text-center text-limit-30 border border-light">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold ">{{ Str::limit($dataticket->Detail, 40, '...') }}</span>
                                            </td>
                                            <!-- "Edit" button within a dropdown -->
                                            <td class="align-middle text-center border border-light">
                                                <div class="dropdown">
                                                    <a class="btn text-primary dropdown-toggle" href="#"
                                                        role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class=""></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuLink">
                                                        <li>
                                                        <li>
                                                            <a class="dropdown-item text-info"
                                                                href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
                                                                <i class="fa fa-eye pe-2 text-info"></i>Detail
                                                            </a>
                                                        </li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalMessage"
                                                            data-ticket-id="{{ $dataticket->id }}"
                                                            data-ticket-subject="{{ $dataticket->subject }}"
                                                            data-ticket-jenis="{{ $dataticket->Jenis_Pengaduan }}"
                                                            data-ticket-lokasi="{{ $dataticket->Lokasi }}"
                                                            data-ticket-detail="{{ $dataticket->Detail }}">
                                                            <i class="fa fa-pencil pe-2 text-success"></i>edit
                                                        </a>

                                                        </li>
                                                        @if ($dataticket->status === 'open')
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('tickets.destroy', $dataticket->id) }}">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger" href="#"
                                                                        onclick="return confirm ('are you sure?')"><i
                                                                            class="fa fa-trash pe-2 text-danger"></i>delete</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                        <!-- Pagination and Sorting Controls -->
                        <div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; background-color: #ffffff;"
                            class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap">

                            <!-- Left side: Filters / Dropdowns -->
                            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                                <!-- Sort Dropdown -->
                                <div class="dropdown" style="position: relative;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="paginationDisplay">1-10 dari {{ $data_ticket->count() }}</span>
                                        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                                        <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc"><i
                                                    class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru</a>
                                        </li>
                                        <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc"><i
                                                    class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama</a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Filter Jenis Pengaduan -->
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
                                                data-filter-type="jenis_pengaduan" data-filter-value="">Semua</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="jenis_pengaduan"
                                                data-filter-value="perbaikan">Perbaikan</a>
                                        </li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="jenis_pengaduan"
                                                data-filter-value="permintaan">Permintaan</a></li>
                                    </ul>
                                </div>

                                <!-- Filter Status Dropdown -->
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        type="button" id="filterStatusBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="filterStatusDisplay">Status</span>
                                        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterStatusBtn"
                                        style="font-size: 13px; min-width: 150px;">
                                        <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                                data-filter-value="" style="padding: 8px 16px;">Semua</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                                data-filter-value="open" style="padding: 8px 16px;">Open</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                                data-filter-value="on process" style="padding: 8px 16px;">On Process</a></li>
                                        <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                                data-filter-value="escalated" style="padding: 8px 16px;">Escalated</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Right side: Pagination -->
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
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
                    @endif
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
                                    <!-- Pengirim Info -->
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

                                    <!-- Judul Pengumuman -->
                                    <h5 class="mb-2 text-dark" style="font-size: 15px; font-weight: 600;">
                                        {{ $item->judul }}</h5>

                                    <!-- Deskripsi Pengumuman -->
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
                        <form method="POST" id="editTicketForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="ticketId" name="ticketId" value="">
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
                                        <label for="Jenis_Pengaduan">Complaint Type</label>
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
                                        <label for="Lokasi">Location / Room Number</label>
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
                                        <label for="Detail">Description</label>
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
                                    <label for="gambar">Image</label>
                                    <input class="form-control form-control-sm @error('gambar') is-invalid @enderror"
                                        id="gambar" name="gambar" type="file" accept="image/*">
                                    @error('gambar')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
    <div class="row mt-4">

    </div>

    </div>
    <script>
        $(document).ready(function() {
            // ========================
            // 0. Inisialisasi Variabel
            // ========================
            const rowsPerPage = 10;
            let ticketsData = [];
            let currentPage = 1;
            let totalPages = 1;
            let currentSort = {
                column: 'createdAt',
                order: 'desc'
            };
            let currentFilters = {
                status: '',
                jenis_pengaduan: ''
            };
            let currentSearch = '';

            // Ambil semua row ke array
            $('#TicketTable tbody tr').each(function() {
                const $tr = $(this);
                ticketsData.push({
                    trElement: $tr,
                    subject: $tr.data('subject').toString().toLowerCase(),
                    status: $tr.data('status').toString().toLowerCase(),
                    user: $tr.data('user').toString().toLowerCase(),
                    jenis_pengaduan: $tr.data('jenis-pengaduan').toString().toLowerCase(),
                    createdAt: parseInt($tr.data('created-at'))
                });
            });

            // ========================
            // 1. Filter
            // ========================
            function applyFilters(data) {
                return data.filter(item => {
                    const matchStatus = currentFilters.status ? item.status === currentFilters.status :
                        true;
                    const matchJenis = currentFilters.jenis_pengaduan ? item.jenis_pengaduan ===
                        currentFilters.jenis_pengaduan : true;
                    return matchStatus && matchJenis;
                });
            }

            // ========================
            // 2. Search
            // ========================
            function applySearch(data) {
                if (!currentSearch) return data;
                const keyword = currentSearch.toLowerCase();
                return data.filter(item =>
                    item.subject.includes(keyword) || item.user.includes(keyword)
                );
            }

            // ========================
            // 3. Sort
            // ========================
            function applySort(data) {
                const sorted = [...data];
                const {
                    column,
                    order
                } = currentSort;
                sorted.sort((a, b) => {
                    let valA = a[column];
                    let valB = b[column];

                    // String (subject, user, status) -> alfabetis
                    if (typeof valA === 'string') {
                        valA = valA.toLowerCase();
                        valB = valB.toLowerCase();
                        if (valA < valB) return order === 'asc' ? -1 : 1;
                        if (valA > valB) return order === 'asc' ? 1 : -1;
                        // tie-break dengan createdAt
                        return order === 'asc' ? a.createdAt - b.createdAt : b.createdAt - a.createdAt;
                    }

                    // Number (createdAt)
                    if (valA < valB) return order === 'asc' ? -1 : 1;
                    if (valA > valB) return order === 'asc' ? 1 : -1;
                    return 0;
                });
                return sorted;
            }

            // ========================
            // 4. Render Table & Pagination
            // ========================
            function renderTable() {
                let filteredData = applyFilters(ticketsData);
                filteredData = applySearch(filteredData);
                filteredData = applySort(filteredData);


                totalPages = Math.ceil(filteredData.length / rowsPerPage);
                if (currentPage > totalPages) currentPage = totalPages || 1;

                // Hide all rows first
                $('#TicketTable tbody tr').hide();

                // Hitung start & end index
                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;

                // Ambil subset data untuk halaman ini
                const pageData = filteredData.slice(startIndex, endIndex);
                const $tbody = $('#TicketTable tbody');
                const rowsToShow = pageData.map(item => item.trElement.detach()); // lepaskan row dari DOM
                $tbody.append(rowsToShow); // append kembali
                rowsToShow.forEach(r => r.show()); // tampilkan


                // ========================
                // Update pagination display (asc/desc)
                // ========================
                const totalRows = filteredData.length;

                if (totalRows === 0) {
                    $('#paginationDisplay').text('0-0 dari 0');
                } else {
                    const displayStart = startIndex + 1;
                    const displayEnd = Math.min(endIndex, totalRows);

                    if (currentSort.column === 'createdAt' && currentSort.order === 'desc') {
                        // Descending → terbaru di atas
                        $('#paginationDisplay').text(`${displayStart}-${displayEnd} dari ${totalRows}`);
                    } else if (currentSort.column === 'createdAt' && currentSort.order === 'asc') {
                        // Ascending → terlama di atas → nomor tampilan dibalik
                        const reversedStart = totalRows - startIndex;
                        const reversedEnd = Math.max(reversedStart - (rowsPerPage - 1), 1);
                        $('#paginationDisplay').text(`${reversedStart}-${reversedEnd} dari ${totalRows}`);
                    } else {
                        // Kolom selain createdAt → numbering normal
                        $('#paginationDisplay').text(`${displayStart}-${displayEnd} dari ${totalRows}`);
                    }
                }

                // Enable/disable Prev/Next
                $('#prevPage').prop('disabled', currentPage <= 1).css('opacity', currentPage <= 1 ? 0.5 : 1).css(
                    'cursor', currentPage <= 1 ? 'not-allowed' : 'pointer');
                $('#nextPage').prop('disabled', currentPage >= totalPages).css('opacity', currentPage >=
                    totalPages ? 0.5 : 1).css('cursor', currentPage >= totalPages ? 'not-allowed' : 'pointer');

                // Hapus ikon lama
                $('#TicketTable thead th.sorting').removeClass('sorting_asc sorting_desc');
                $('#TicketTable thead th.sorting .sort-icons').remove();

                // Tambahkan ikon segitiga untuk kolom sortable (subject, user, status)
                $('#TicketTable thead th.sorting').each(function() {
                    const colText = $(this).text().trim().toLowerCase();
                    if (currentSort.column === colText) {
                        $(this).addClass(currentSort.order === 'asc' ? 'sorting_asc' : 'sorting_desc');
                    }
                    if (!$(this).find('.sort-icons').length) {
                        $(this).append('<span class="sort-icons"></span>');
                    }
                });
            }
            // ========================
            // 5. Event Handlers
            // ========================

            // Search
            $('#search').on('input', function() {
                currentSearch = $(this).val().toLowerCase();
                currentPage = 1;
                renderTable();
            });

            // Filter dropdown
            $('.filter-option').click(function(e) {
                e.preventDefault();

                const filterType = $(this).data('filter-type'); // "status" atau "jenis_pengaduan"
                const filterValue = $(this).data('filter-value') || '';

                // Simpan filter
                currentFilters[filterType] = filterValue.toLowerCase();

                // Update teks dropdown
                if (filterType === 'status') {
                    const displayText = filterValue ? `Status: ${$(this).text()}` : 'Status';
                    $('#filterStatusDisplay').text(displayText);
                } else if (filterType === 'jenis_pengaduan') {
                    const displayText = filterValue ? `Jenis Pengaduan: ${$(this).text()}` :
                        'Jenis Pengaduan';
                    $('#filterJenisPengaduanDisplay').text(displayText);
                }

                // Reset page ke 1
                currentPage = 1;
                renderTable();
            });

            // Dropdown sort terbaru/terlama
            $('.page-sort-option').click(function(e) {
                e.preventDefault();
                const sortOrder = $(this).data('sort');
                currentSort.column = 'createdAt';
                currentSort.order = sortOrder;
                currentPage = 1;
                renderTable();
            });

            // Sorting klik th
            $('#TicketTable thead th.sorting').click(function() {
                const colText = $(this).text().trim().toLowerCase();
                if (colText === 'subject') currentSort.column = 'subject';
                else if (colText === 'user') currentSort.column = 'user';
                else if (colText === 'status') currentSort.column = 'status';
                else return;

                currentSort.order = (currentSort.order === 'asc') ? 'desc' : 'asc';
                currentPage = 1;
                renderTable();
            });

            // Pagination Prev/Next
            $('#prevPage').click(function() {
                if (currentPage > 1) currentPage--;
                renderTable();
            });
            $('#nextPage').click(function() {
                if (currentPage < totalPages) currentPage++;
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>

    <style>
        /* Card Stat - Layout untuk dashboard cards */
        .card-body.card-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 100px;
        }

        .card-stat .numbers {
            flex: 1;
        }

        .card-stat-title {
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card-stat-value {
            font-size: 28px;
            line-height: 1.2;
        }

        .card-stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 12px;
            flex-shrink: 0;
        }

        /* Tetapkan space untuk ikon supaya kolom tidak bergeser */
        th.sorting {
            position: relative;
            cursor: pointer;
            user-select: none;
            padding-right: 30px;
            /* space tetap untuk ikon */
            width: 150px;
            /* opsional, bisa disesuaikan lebar kolom */
        }

        /* container ikon */
        th.sorting .sort-icons {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            font-size: 1em;
            line-height: 0.7em;
            width: 16px;
            /* fix width ikon supaya tidak memengaruhi layout */
            height: 16px;
            /* fix height juga */
        }

        /* default abu-abu */
        th.sorting .sort-icons::before,
        th.sorting .sort-icons::after {
            color: #ccc;
        }

        /* ascending active */
        th.sorting.sorting_asc .sort-icons::before {
            color: #000;
        }

        th.sorting.sorting_asc .sort-icons::after {
            color: #ccc;
        }

        /* descending active */
        th.sorting.sorting_desc .sort-icons::before {
            color: #ccc;
        }

        th.sorting.sorting_desc .sort-icons::after {
            color: #000;
        }

        /* isi segitiga */
        th.sorting .sort-icons::before {
            content: "▲";
        }

        th.sorting .sort-icons::after {
            content: "▼";
        }
    </style>

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
        const modal = document.getElementById('exampleModalMessage');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const ticketId = button.getAttribute('data-ticket-id');
            const ticketSubject = button.getAttribute('data-ticket-subject');
            const ticketJenis = button.getAttribute('data-ticket-jenis');
            const ticketLokasi = button.getAttribute('data-ticket-lokasi');
            const ticketDetail = button.getAttribute('data-ticket-detail');

            const form = modal.querySelector('#editTicketForm');
            form.action = `/tickets/${ticketId}`;
            form.querySelector('#ticketId').value = ticketId;
            form.querySelector('#subject').value = ticketSubject;
            form.querySelector('#Jenis_Pengaduan').value = ticketJenis;
            form.querySelector('#Lokasi').value = ticketLokasi;
            form.querySelector('#Detail').value = ticketDetail;
        });

        // Handle announcement modal
        const announcementModal = document.getElementById('announcementModal');
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
            document.getElementById('modalCreatedAt').textContent = new Date(createdAt)
                .toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            document.getElementById('modalTimeAgo').textContent = moment(createdAt).fromNow();
        });
    });
</script>

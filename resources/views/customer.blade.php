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
                            <table class="table align-items-center mb-0 " id="TicketTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">subject</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">Deskripsi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="padding: 10px;">aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_ticket as $dataticket)
                                        <tr class="align-middle text-sm border border-light"
                                            data-created-at="{{ $dataticket->created_at->timestamp }}"
                                            data-jenis-pengaduan="{{ $dataticket->Jenis_Pengaduan }}"
                                            data-status="{{ $dataticket->status }}">
                                            <td class="align-middle text-sm border border-light"
                                                data-subject="{{ $dataticket->subject }}">
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
                                            <td class="align-middle text-center text-sm border border-light"
                                                data-status="{{ $dataticket->status }}">
                                                <x-status-badge :status="$dataticket->status" />
                                            </td>
                                            <td class="align-middle text-center text-limit-30 border border-light">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold ">{{ $dataticket->Detail }}</span>
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
                        <div
                            style="padding: 15px 16px; border-top: 1px solid #e4e4e4; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff;">

                            <div style="display: flex; gap: 12px; align-items: center;">

                                <!-- Pagination Info as Dropdown -->
                                <div class="dropdown" style="position: relative;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="paginationDisplay">1-10 dari {{ $data_ticket->count() }}</span>
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

                                <!-- Filter Jenis Pengaduan Dropdown – SUDAH DITAMBAH атрибут POPPER -->
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        type="button" id="filterJenisPengaduanBtn" data-bs-toggle="dropdown"
                                        data-bs-boundary="window" <!-- ← TAMBAHAN: batasi terhadap window -->
                                        data-bs-display="dynamic" <!-- ← TAMBAHAN UTAMA: aktifkan flip otomatis -->
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

                                <!-- Filter Status Dropdown – SUDAH DITAMBAH атрибут POPPER -->
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                        type="button" id="filterStatusBtn" data-bs-toggle="dropdown"
                                        data-bs-boundary="window" <!-- ← TAMBAHAN -->
                                        data-bs-display="dynamic" <!-- ← TAMBAHAN UTAMA -->
                                        aria-expanded="false">
                                        <span id="filterStatusDisplay">Status</span>
                                        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterStatusBtn"
                                        style="font-size: 13px; min-width: 150px;">
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="status" data-filter-value=""
                                                style="padding: 8px 16px;">Semua</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="status" data-filter-value="open"
                                                style="padding: 8px 16px;">Open</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="status" data-filter-value="on process"
                                                style="padding: 8px 16px;">On Process</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="status" data-filter-value="escalated"
                                                style="padding: 8px 16px;">Escalated</a></li>
                                        <li><a class="dropdown-item filter-option" href="#"
                                                data-filter-type="status" data-filter-value="close"
                                                style="padding: 8px 16px;">Close</a></li>
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
                    targets: [0, 1],
                    orderable: true
                }, {
                    targets: [2, 3],
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

            // Filter Dropdown: Status
            $(document).on('click', '.filter-option[data-filter-type="status"]', function(e) {
                e.preventDefault();
                var filterValue = $(this).data('filter-value');
                $('#filterStatusDisplay').text($(this).text()); // Update button text
                filterTable();
            });

            function filterTable() {
                const selectedJenis = $('#filterJenisPengaduanDisplay').text().trim();
                const selectedStatus = $('#filterStatusDisplay').text().trim();

                const isAllJenis = selectedJenis === 'Jenis Pengaduan' || selectedJenis === 'Semua';
                const isAllStatus = selectedStatus === 'Status' || selectedStatus === 'Semua';

                hasActiveFilter = !(isAllJenis && isAllStatus); // FIX: Set flag true jika ada filter spesifik

                filteredData = [];
                originalData.forEach(function(row) {
                    var $row = $(row);
                    var jenis = $row.data('jenis-pengaduan') || '';
                    var status = $row.data('status') || '';

                    var matchJ = isAllJenis || jenis.toLowerCase().includes(selectedJenis.toLowerCase());
                    var matchS = isAllStatus || status.toLowerCase().includes(selectedStatus.toLowerCase());

                    if (matchJ && matchS) {
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

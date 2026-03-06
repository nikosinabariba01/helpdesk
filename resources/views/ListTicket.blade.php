@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Ticket List</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Ticket List</h6>
</nav>
@endsection
@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="col-lg-12 mb-lg-0 mb-4 ">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0">All Ticket List</h6>
            <div class="d-flex">
                <!-- Kolom Pencarian dengan input-group -->
                <div class="input-group input-group-sm">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" id="search" class="form-control" placeholder="Search" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2 h-500">
            <style>
                @media (max-width: 768px) {
                    .table-responsive-custom {
                        height: 400px !important;
                        max-height: 400px !important;
                    }
                }

                @media (min-width: 769px) and (max-width: 1024px) {
                    .table-responsive-custom {
                        height: 600px !important;
                        max-height: 600px !important;
                    }
                }

                @media (min-width: 1025px) {
                    .table-responsive-custom {
                        height: 550px !important;
                        max-height: 550px !important;
                    }
                }
            </style>
            @if($teknisi_data_ticket->isEmpty())
            <div class="table-responsive margin-right: 15px; position: relative; table-responsive-custom" style="overflow-y: auto;">
                <!-- Add your button here -->
                <a href="{{ route('customer.tickets') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
            </div>
            @else
            <div class="table-responsive margin-right: 15px; table-responsive-custom" style="overflow-y: auto;">
                <table class="table align-items-center mb-0" id="TicketTable">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">subject</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">User</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Deskripsi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Aksi Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teknisi_data_ticket as $teknisidataticket)
                        <tr class="align-middle text-sm border border-light" data-created-at="{{ $teknisidataticket->created_at->timestamp }}" data-jenis-pengaduan="{{ $teknisidataticket->Jenis_Pengaduan }}" data-status="{{ $teknisidataticket->status }}" data-subject="{{ $teknisidataticket->subject }}" data-user="{{ $teknisidataticket->user->name }}">
                            <td class="align-middle text-sm border border-light">
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                            <a href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                {{ $teknisidataticket->subject }}
                                            </a>
                                        </h6>

                                        <div class="d-flex list-inline">
                                            <li class="text-xs list-inline-item text-secondary"><i class="fa fa-circle fa-xs text-danger"></i>{{'sp-' . substr(preg_replace('/[^0-9]/', '', $teknisidataticket->id), -3) . \Carbon\Carbon::parse($teknisidataticket->created_at)->format('dmy') . ($teknisidataticket->Jenis_Pengaduan == 0 ? '0' : '1');}}</li>
                                            <li class="text-xs list-inline-item text-secondary" title="type"><i class="fa fa-circle fa-xs text-primary"></i>{{ $teknisidataticket->Jenis_Pengaduan }}</li>
                                            <li class="text-xs list-inline-item text-secondary" title="Created Date"><i class="fa fa-circle fa-xs text-secondary"></i></i> {{ $teknisidataticket->formattedTanggalPengaduan }}</li>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-center text-sm text-limit-20 border border-light">
                                {{ $teknisidataticket->user->name }}
                            </td>
                            <td class="align-middle text-center text-sm border border-light">
                                <x-status-badge :status="$teknisidataticket->status" />
                            </td>
                            <td class="align-middle text-center text-limit-30 border border-light">
                                <span class="text-secondary text-xs font-weight-bold ">{{ $teknisidataticket->Detail }}</span>
                            </td>
                            <td class="align-middle text-center text-sm border border-light">

                                @if($teknisidataticket->status == 'on process')
                                @if($teknisidataticket->isNotAssigned)
                                <!-- Tombol Contribute untuk user yang belum assign -->
                                <form action="{{ route('tickets.contribute', $teknisidataticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary btn-transparent text-secondary">
                                        Contribute
                                    </button>
                                </form>
                                @else
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pengurus')
                                <!-- Admin/Pengurus: Cancel Process, Escalate, Close -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-warning">
                                        Cancel Process
                                    </button>
                                </form>
                                <!-- Tombol Escalate hanya muncul jika belum ada assignee pemilik -->
                                @if(!$teknisidataticket->hasOwnerAssignee)
                                <form method="POST" action="{{ route('ticketsteknisi.requestFollowup', $teknisidataticket->id) }}" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info btn-transparent text-info">
                                        Escalate
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}" id="closeTicketForm-{{ $teknisidataticket->id }}" class="mb-2">
                                    @method('PUT')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                                        data-bs-toggle="modal" data-bs-target="#modal-confirmation"
                                        data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                        Close
                                    </button>
                                </form>
                                @elseif(Auth::user()->role == 'pemilik')
                                <!-- Pemilik: Cancel Process dan Close -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-warning">
                                        Cancel Process
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}" id="closeTicketForm-{{ $teknisidataticket->id }}" class="mb-2">
                                    @method('PUT')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                                        data-bs-toggle="modal" data-bs-target="#modal-confirmation"
                                        data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                        Close
                                    </button>
                                </form>
                                @endif
                                @endif

                                @elseif($teknisidataticket->status == 'escalated')
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'pengurus')
                                <!-- Escalated: Cancel Escalation -->
                                <form method="POST" action="{{ route('ticketsteknisi.cancelRequestFollowUp', $teknisidataticket->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                                        Cancel Escalation
                                    </button>
                                </form>
                                @elseif(Auth::user()->role == 'pemilik')
                                <!-- Pemilik: Accept Escalation -->
                                <form action="{{ route('tickets.accept_escalation', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                                        <i class="fa fa-check pe-2 text-success"></i> Accept Escalation
                                    </button>
                                </form>
                                @endif

                                @elseif($teknisidataticket->status == 'close')
                                <!-- Status: Close - tampilkan Reprocess -->
                                <form action="{{ route('tickets.assign', $teknisidataticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-secondary">
                                        Reprocess
                                    </button>
                                </form>

                                @elseif($teknisidataticket->status == 'open')
                                <!-- Status: Open - tampilkan Proceed -->
                                <form action="{{ route('tickets.assign', $teknisidataticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                                        Proceed
                                    </button>
                                </form>
                                @endif

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
            <div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="paginationDisplay">1-10 dari {{ $teknisi_data_ticket->count() }}</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc" style="padding: 8px 16px;"><i class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru</a></li>
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc" style="padding: 8px 16px;"><i class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama</a></li>
                        </ul>
                    </div>
                    <!-- Filter Jenis Pengaduan Dropdown -->
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;" type="button" id="filterJenisPengaduanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="filterJenisPengaduanDisplay">Jenis Pengaduan</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterJenisPengaduanBtn" style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="" style="padding: 8px 16px;">Semua</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="perbaikan" style="padding: 8px 16px;">Perbaikan</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="permintaan" style="padding: 8px 16px;">Permintaan</a></li>
                        </ul>
                    </div>

                    <!-- Filter Status Dropdown -->
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;" type="button" id="filterStatusBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="filterStatusDisplay">Status</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterStatusBtn" style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="" style="padding: 8px 16px;">Semua</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="open" style="padding: 8px 16px;">Open</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="on process" style="padding: 8px 16px;">On Process</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="escalated" style="padding: 8px 16px;">Escalated</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="close" style="padding: 8px 16px;">Close</a></li>
                        </ul>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="display: flex; gap: 6px;">
                        <button id="prevPage" class="btn btn-sm btn-outline-secondary" style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" title="Halaman Sebelumnya"><i class="fa fa-chevron-left" style="font-size: 11px;"></i></button>
                        <button id="nextPage" class="btn btn-sm btn-outline-secondary" style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" title="Halaman Berikutnya"><i class="fa fa-chevron-right" style="font-size: 11px;"></i></button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Confirmation -->
<div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="modal-confirmation"
    aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-confirmation">Are you sure?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <i class="ni ni-bell-55 ni-3x"></i>
                    <h4 class="text-gradient text-danger mt-4">Tindakan ini akan menandai tiket sebagai ditutup</h4>
                    <p>Apakah Anda yakin ingin menutup tiket ini ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="modal-submit-btn">Ya, close tiket</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Menangani klik tombol konfirmasi untuk mengirimkan form
    $('#modal-submit-btn').on('click', function() {
        var formId = $('#modal-confirmation').data('form-id'); // Ambil form id dari modal
        $('#' + formId).submit(); // Kirim form yang terkait dengan tombol
    });

    // Ketika modal ditampilkan, simpan form id yang terkait
    $('#modal-confirmation').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget); // Tombol yang memicu modal
        var formId = button.data('form-id'); // Ambil form id dari data atribut tombol
        $(this).data('form-id', formId); // Simpan formId dalam modal untuk digunakan nanti
    });
</script>

<script>
$(document).ready(function() {
    let currentSort = 'desc'; // Default sorting direction
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredData = [];   // Hasil filter/search
    let originalData = [];   // Master data semua row
    let hasActiveFilter = false;

    // Simpan semua row awal
    $('#TicketTable tbody tr').each(function() {
        originalData.push(this);
    });

    // --- Custom search ---
    $('#search').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();

        filteredData = originalData.filter(function(row) {
            const $row = $(row);
            const subject = ($row.data('subject') || '').toLowerCase();
            const user = ($row.data('user') || '').toLowerCase();
            return subject.includes(searchTerm) || user.includes(searchTerm);
        });

        hasActiveFilter = searchTerm !== '';
        currentPage = 1;
        updatePagination();
    });

    // --- Filter Dropdown: Jenis Pengaduan & Status ---
    $(document).on('click', '.filter-option[data-filter-type="jenis_pengaduan"], .filter-option[data-filter-type="status"]', function(e) {
        e.preventDefault();
        const type = $(this).data('filter-type');
        if(type === 'jenis_pengaduan') $('#filterJenisPengaduanDisplay').text($(this).text());
        if(type === 'status') $('#filterStatusDisplay').text($(this).text());
        applyFilter();
    });

    function applyFilter() {
        const selectedJenis = $('#filterJenisPengaduanDisplay').text().trim().toLowerCase();
        const selectedStatus = $('#filterStatusDisplay').text().trim().toLowerCase();

        const isAllJenis = selectedJenis === 'jenis pengaduan' || selectedJenis === 'semua';
        const isAllStatus = selectedStatus === 'status' || selectedStatus === 'semua';

        hasActiveFilter = !(isAllJenis && isAllStatus);

        filteredData = originalData.filter(function(row) {
            const $row = $(row);
            const jenis = ($row.data('jenis-pengaduan') || '').toLowerCase();
            const status = ($row.data('status') || '').toLowerCase();

            const matchJenis = isAllJenis || jenis.includes(selectedJenis);
            const matchStatus = isAllStatus || status.includes(selectedStatus);

            return matchJenis && matchStatus;
        });

        currentPage = 1;
        updatePagination();
    }

    // --- Reset filter Semua ---
    $('.filter-option[data-filter-value=""]').on('click', function() {
        hasActiveFilter = false;
        filteredData = [];
        currentPage = 1;
        updatePagination();
    });

    // --- Tombol sort header kolom 0-2 ---
    $('#TicketTable thead th').slice(0,3).on('click', function() {
        const columnIndex = $(this).index();
        const isAsc = !$(this).hasClass('sorting_asc');

        // Remove all sort classes
        $('#TicketTable thead th').removeClass('sorting_asc sorting_desc').addClass('sorting');
        $(this).removeClass('sorting').addClass(isAsc ? 'sorting_asc' : 'sorting_desc');

        currentSort = isAsc ? 'asc' : 'desc';

        // Sort seluruh dataset, bukan hanya page terlihat
        const allData = hasActiveFilter ? filteredData : originalData;
        allData.sort(function(a,b){
            let aVal, bVal;
            if(columnIndex === 0){ aVal = $(a).data('subject') || ''; bVal = $(b).data('subject') || ''; }
            else if(columnIndex === 1){ aVal = $(a).data('user') || ''; bVal = $(b).data('user') || ''; }
            else if(columnIndex === 2){ aVal = $(a).data('status') || ''; bVal = $(b).data('status') || ''; }
            aVal = String(aVal).toLowerCase();
            bVal = String(bVal).toLowerCase();
            return currentSort === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
        });

        updatePagination();
    });

    // --- Sorting by date (custom tombol) ---
    $(document).on('click', '.page-sort-option', function(e){
        e.preventDefault();
        currentSort = $(this).data('sort'); // 'asc' / 'desc'

        const allData = hasActiveFilter ? filteredData : originalData;
        allData.sort(function(a,b){
            const aTimestamp = parseInt($(a).data('created-at')) || 0;
            const bTimestamp = parseInt($(b).data('created-at')) || 0;
            return currentSort === 'desc' ? bTimestamp - aTimestamp : aTimestamp - bTimestamp;
        });

        updatePagination();
    });

    // --- Update pagination dan tampilkan per page ---
    function updatePagination() {
        let rowsToDisplay = hasActiveFilter ? filteredData : originalData;
        const totalRows = rowsToDisplay.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);

        if(currentPage > totalPages) currentPage = totalPages || 1;

        $('#TicketTable tbody').empty();
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalRows);

        for(let i=startIndex; i<endIndex; i++){
            $('#TicketTable tbody').append(rowsToDisplay[i]);
        }

        // Update info pagination custom
        if(totalRows === 0){
            $('#paginationDisplay').text('0-0 dari 0');
            $('#TicketTable tbody').append('<tr class="no-data-row"><td colspan="6" class="text-center text-secondary py-4">Tidak ada data ditemukan</td></tr>');
        } else {
            $('#paginationDisplay').text(`${startIndex+1}-${endIndex} dari ${totalRows}`);
        }

        // Enable/disable tombol prev/next
        $('#prevPage').prop('disabled', currentPage===1);
        $('#nextPage').prop('disabled', currentPage===totalPages || totalRows===0);
    }

    // --- Tombol prev/next page ---
    $('#prevPage').on('click', function(){
        if(currentPage > 1){ currentPage--; updatePagination(); }
    });
    $('#nextPage').on('click', function(){
        const totalRows = hasActiveFilter ? filteredData.length : originalData.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);
        if(currentPage < totalPages){ currentPage++; updatePagination(); }
    });

    // --- Initial load ---
    updatePagination();
});
</script>

@endsection
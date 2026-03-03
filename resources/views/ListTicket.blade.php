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
                        <tr class="align-middle text-sm border border-light" data-created-at="{{ $teknisidataticket->created_at->timestamp }}" data-jenis-pengaduan="{{ $teknisidataticket->Jenis_Pengaduan }}" data-status="{{ $teknisidataticket->status }}">
                            <td class="align-middle text-sm border border-light" data-subject="{{ $teknisidataticket->subject }}">
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
                            <td class="align-middle text-center text-sm text-limit-20 border border-light" data-user="{{ $teknisidataticket->user->name }}">
                                {{ $teknisidataticket->user->name }}
                            </td>
                            <td class="align-middle text-center text-sm border border-light" data-status="{{ $teknisidataticket->status }}">
                                <x-status-badge :status="$teknisidataticket->status" />
                            </td>
                            <td class="align-middle text-center text-limit-30 border border-light">
                                <span class="text-secondary text-xs font-weight-bold ">{{ $teknisidataticket->Detail }}</span>
                            </td>
                            <td class="align-middle text-center text-sm border border-light">
                                @if($teknisidataticket->status == 'on process')
                                <!-- Jika tiket sudah di-assign oleh teknisi lain, hanya tampilkan tombol Contribute -->
                                @if(!$teknisidataticket->asignees->isEmpty() && $teknisidataticket->asignees->first()->id != Auth::id())
                                <form action="{{ route('tickets.assign', $teknisidataticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary btn-transparent text-secondary">
                                        Contribute
                                    </button>
                                </form>
                                @else
                                <!-- Status: On Process - tombol Cancel Process, Escalate, Close disembunyikan -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-warning">
                                        Cancel Process
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ticketsteknisi.requestFollowup', $teknisidataticket->id) }}" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info btn-transparent text-info">
                                        Escalate
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}" id="closeTicketForm-{{ $teknisidataticket->id }}" class="mb-2">
                                    @method('PUT')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-transparent text-danger" data-bs-toggle="modal" data-bs-target="#modal-confirmation" data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                        Close
                                    </button>
                                </form>
                                @endif
                                @elseif($teknisidataticket->status == 'escalated')
                                <!-- Status: Escalated - tampilkan Cancel Escalation -->
                                <form method="POST" action="{{ route('ticketsteknisi.cancelRequestFollowUp', $teknisidataticket->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                                        Cancel Escalation
                                    </button>
                                </form>
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


<script>
$(document).ready(function() {

    let currentSort = 'desc';           // default: terbaru dulu
    let currentPage  = 1;
    const itemsPerPage = 10;

    let filteredData = [];              // array baris yang lolos filter (DOM elements)
    let originalData = [];              // semua baris asli (disimpan sekali di awal)

    var table = $('#TicketTable').DataTable({
        searching: true,
        ordering: true,
        paging: false,
        lengthChange: false,
        info: false,
        columnDefs: [
            { targets: [0,1,2], orderable: true  },
            { targets: [3,4,5], orderable: false }
        ]
    });

    $('#TicketTable_filter').hide();

    // Simpan semua baris asli sekali saja
    $('#TicketTable tbody tr').each(function() {
        originalData.push(this);
    });

    // Sorting kolom (subject, user, status)
    $('#TicketTable thead th').slice(0,3).on('click', function() {
        var idx   = $(this).index();
        var isAsc = $(this).hasClass('sorting_asc');

        $('#TicketTable thead th').removeClass('sorting_asc sorting_desc').addClass('sorting');
        $(this).removeClass('sorting').addClass(isAsc ? 'sorting_desc' : 'sorting_asc');

        sortByColumn(idx, !isAsc);
        currentPage = 1;
        renderCurrentPage();
    });

    function sortByColumn(colIndex, ascending) {
        let data = filteredData.length ? filteredData : originalData;

        data.sort((a,b) => {
            let va, vb;
            if (colIndex === 0) { va = $(a).data('subject')||''; vb = $(b).data('subject')||''; }
            else if (colIndex === 1) { va = $(a).data('user')||''; vb = $(b).data('user')||''; }
            else { va = $(a).data('status')||''; vb = $(b).data('status')||''; }

            va = String(va).toLowerCase();
            vb = String(vb).toLowerCase();

            return ascending ? va.localeCompare(vb) : vb.localeCompare(va);
        });
    }

    // Search — persis seperti kode awal Anda
    $('#search').on('keyup', function() {
        let term = this.value.toLowerCase().trim();

        if (term === '') {
            // reset search
            $.fn.dataTable.ext.search = [];
            table.draw();
            currentPage = 1;
            renderCurrentPage();
            sortByDate(currentSort);    // kembali urutkan sesuai sort terakhir
            return;
        }

        $.fn.dataTable.ext.search = [];
        $.fn.dataTable.ext.search.push(function(settings, data, index) {
            return data[0].toLowerCase().includes(term) ||
                   data[1].toLowerCase().includes(term);
        });

        table.draw();
        currentPage = 1;
        renderCurrentPage();
        sortByDate(currentSort);
    });

    // Tombol terbaru / terlama
    $(document).on('click', '.page-sort-option', function(e) {
        e.preventDefault();
        currentSort = $(this).data('sort');
        sortByDate(currentSort);
        currentPage = 1;
        renderCurrentPage();
    });

    function sortByDate(dir) {
        let data = filteredData.length ? filteredData : originalData;

        data.sort((a,b) => {
            let ta = parseInt($(a).data('created-at')) || 0;
            let tb = parseInt($(b).data('created-at')) || 0;
            return dir === 'desc' ? tb - ta : ta - tb;
        });
    }

    // Render halaman saat ini (inti fix bug stuck & no data)
    function renderCurrentPage() {
        // Selalu mulai dari kosong
        $('#TicketTable tbody').empty();

        let source = filteredData.length ? filteredData : originalData;
        let total   = source.length;

        if (total === 0) {
            $('#TicketTable tbody').html(
                '<tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada data ditemukan</td></tr>'
            );
            $('#paginationDisplay').text('0-0 dari 0');
            togglePaginationButtons(false);
            return;
        }

        let start = (currentPage - 1) * itemsPerPage;
        let end   = Math.min(start + itemsPerPage, total);

        // Tampilkan hanya potongan yang sesuai halaman
        for (let i = start; i < end; i++) {
            $('#TicketTable tbody').append(source[i]);
        }

        let dStart = start + 1;
        let dEnd   = end;

        if (currentSort === 'desc') {
            $('#paginationDisplay').text(`${dStart}-${dEnd} dari ${total}`);
        } else {
            let revStart = total - start;
            let revEnd   = Math.max(revStart - (itemsPerPage - 1), 1);
            $('#paginationDisplay').text(`${revStart}-${revEnd} dari ${total}`);
        }

        togglePaginationButtons(total > 0);
    }

    function togglePaginationButtons(enabled) {
        let disabledPrev = currentPage === 1;
        let totalPages   = Math.ceil((filteredData.length || originalData.length) / itemsPerPage);
        let disabledNext = currentPage >= totalPages;

        $('#prevPage')
            .prop('disabled', disabledPrev)
            .css({ opacity: disabledPrev ? 0.5 : 1, cursor: disabledPrev ? 'not-allowed' : 'pointer' });

        $('#nextPage')
            .prop('disabled', disabledNext || !enabled)
            .css({ opacity: disabledNext || !enabled ? 0.5 : 1, cursor: disabledNext || !enabled ? 'not-allowed' : 'pointer' });
    }

    $('#prevPage').on('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderCurrentPage();
        }
    });

    $('#nextPage').on('click', () => {
        let total = filteredData.length || originalData.length;
        let maxPage = Math.ceil(total / itemsPerPage);
        if (currentPage < maxPage) {
            currentPage++;
            renderCurrentPage();
        }
    });

    // Filter dropdown
    $(document).on('click', '.filter-option', function(e) {
        e.preventDefault();
        let type  = $(this).data('filter-type');
        let value = $(this).data('filter-value');
        let text  = $(this).text().trim();

        if (type === 'jenis_pengaduan') {
            $('#filterJenisPengaduanDisplay').text(text);
        } else if (type === 'status') {
            $('#filterStatusDisplay').text(text);
        }

        applyFilter();
    });

    function applyFilter() {
        let jenisTxt = $('#filterJenisPengaduanDisplay').text().trim();
        let statusTxt = $('#filterStatusDisplay').text().trim();

        filteredData = originalData.filter(tr => {
            let j = $(tr).data('jenis-pengaduan') || '';
            let s = $(tr).data('status') || '';

            let matchJ = (jenisTxt === 'Jenis Pengaduan' || jenisTxt === 'Semua') ||
                         j.toLowerCase().includes(jenisTxt.toLowerCase());

            let matchS = (statusTxt === 'Status' || statusTxt === 'Semua') ||
                         s.toLowerCase().includes(statusTxt.toLowerCase());

            return matchJ && matchS;
        });

        currentPage = 1;
        renderCurrentPage();
    }

    // Inisialisasi
    sortByDate('desc');
    renderCurrentPage();

});
</script>
@endsection
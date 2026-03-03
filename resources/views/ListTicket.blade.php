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
                        <tr class="align-middle text-sm border border-light" data-created-at="{{ $teknisidataticket->created_at->timestamp }}">
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
        let currentSort = 'desc';
        let currentPage = 1;
        const itemsPerPage = 10;
        
        var table = $('#TicketTable').DataTable({
            searching: true,
            ordering: true,
            paging: false,
            lengthChange: false,
            info: false,
            columnDefs: [{
                    targets: [0, 1, 2],
                    orderable: true
                },
                {
                    targets: [3, 4, 5],
                    orderable: false
                }
            ]
        });

        // Variables for storing filter values
        let currentJenisPengaduanFilter = '';
        let currentStatusFilter = '';
        let searchTerm = '';

        // Handle filter dropdown selections
        $(document).on('click', '.filter-option', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var filterType = $(this).data('filter-type');
            var filterValue = $(this).data('filter-value');
            var filterText = $(this).text().trim();

            if (filterType === 'jenis_pengaduan') {
                currentJenisPengaduanFilter = filterValue;
                var displayText = filterValue === '' ? 'Jenis Pengaduan' : filterText;
                $('#filterJenisPengaduanDisplay').text(displayText);
            } else if (filterType === 'status') {
                currentStatusFilter = filterValue;
                var displayText = filterValue === '' ? 'Status' : filterText;
                $('#filterStatusDisplay').text(displayText);
            }

            currentPage = 1; // Reset to first page after filter change
            applyFilters(); // Apply filters
        });

        // Handle the search box input
        $('#search').on('keyup', function() {
            searchTerm = this.value.toLowerCase();
            currentPage = 1; // Reset to first page after search
            applyFilters(); // Apply filters with search term
        });

        // Apply filters to table rows
        function applyFilters() {
            var allRows = $('#TicketTable tbody tr');
            var filteredRows = [];

            allRows.each(function() {
                var row = $(this);
                var jenisPengaduan = String(row.data('jenis-pengaduan')).trim().toLowerCase();
                var status = String(row.data('status')).trim().toLowerCase();
                var subject = String(row.data('subject')).trim().toLowerCase();
                var user = String(row.data('user')).trim().toLowerCase();

                // Apply search filter
                var matchesSearch = (subject.includes(searchTerm) || user.includes(searchTerm));

                // Apply jenis pengaduan filter
                var jenisPengaduanMatch = (currentJenisPengaduanFilter === '' || jenisPengaduan === currentJenisPengaduanFilter);

                // Apply status filter
                var statusMatch = (currentStatusFilter === '' || status === currentStatusFilter);

                // Check if row matches all filters
                if (matchesSearch && jenisPengaduanMatch && statusMatch) {
                    row.show();
                    filteredRows.push(row);
                } else {
                    row.hide();
                }
            });

            updatePagination(filteredRows); // Update pagination after applying filters
        }

        // Update pagination based on the filtered rows
        function updatePagination(filteredRows) {
            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);

            if (currentPage > totalPages) currentPage = totalPages || 1;
            
            filteredRows.forEach(function(row, index) {
                row.hide();
                if (index >= (currentPage - 1) * itemsPerPage && index < currentPage * itemsPerPage) {
                    row.show();
                }
            });

            var displayStart = (currentPage - 1) * itemsPerPage + 1;
            var displayEnd = Math.min(currentPage * itemsPerPage, totalRows);

            $('#paginationDisplay').text(displayStart + '-' + displayEnd + ' dari ' + totalRows);
            $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
            $('#nextPage').prop('disabled', currentPage === totalPages || totalPages === 0).css('opacity', currentPage === totalPages || totalPages === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalPages === 0 ? 'not-allowed' : 'pointer');
        }

        // Pagination next/prev buttons
        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                applyFilters(); // Reapply filters when page changes
            }
        });

        $('#nextPage').on('click', function() {
            var totalRows = $('#TicketTable tbody tr').length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                applyFilters(); // Reapply filters when page changes
            }
        });

        // Initialize table by applying all filters initially
        applyFilters();
    });
</script>

@endsection
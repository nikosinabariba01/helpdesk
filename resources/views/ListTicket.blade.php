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
            }],
            order: []
        });

        $('#TicketTable_filter').hide();

        // Store original data (before filter or search)
        $('#TicketTable tbody tr').each(function() {
            originalData.push(this); // Store all rows as original data
        });


        // Search filter (TIDAK DISENTUH, tetap seperti asli)
        $('#search').on('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            $('#search').on('keyup', function() {
                var searchTerm = this.value.toLowerCase();

                // Jika search term kosong dan tidak ada filter yang aktif
                if (searchTerm === '' && !hasActiveFilter) {
                    // Tampilkan data asli (originalData) ketika search dikosongkan dan tidak ada filter aktif
                    $('#TicketTable tbody').empty().append(originalData); // Menampilkan data asli
                    sortTableByDate(currentSort); // Urutkan data sesuai urutan yang diinginkan
                    updatePagination(); // Update pagination sesuai data yang ditampilkan
                    return;
                }

                // Jika search term kosong tapi ada filter yang aktif
                if (searchTerm === '') {
                    // Tampilkan data yang sudah difilter sebelumnya
                    if (filteredData.length > 0) {
                        // Tampilkan data yang sudah difilter (filteredData) dan pastikan urutannya benar
                        $('#TicketTable tbody').empty().append(filteredData); // Menampilkan kembali data yang sudah difilter
                        sortTableByDate(currentSort); // Urutkan data sesuai urutan yang diinginkan (misalnya berdasarkan tanggal)
                        updatePagination(); // Update pagination sesuai data yang ditampilkan
                    } else {
                        // Jika tidak ada data yang sudah difilter, tampilkan seluruh data asli
                        $('#TicketTable tbody').empty().append(originalData); // Menampilkan data asli
                        sortTableByDate(currentSort); // Urutkan data sesuai urutan yang diinginkan
                        updatePagination(); // Update pagination sesuai data yang ditampilkan
                    }
                    return;
                }

                // Jika search term tidak kosong, lakukan pencarian dan filter ulang
                $.fn.dataTable.ext.search = [];
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    return data[0].toLowerCase().includes(searchTerm) || data[1].toLowerCase().includes(searchTerm);
                });
                table.draw();
                currentPage = 1;
                sortTableByDate(currentSort); // Urutkan berdasarkan tanggal (terbaru ke terlama)
                updatePagination(); // Update pagination sesuai data yang ditampilkan
            });
            $.fn.dataTable.ext.search = [];
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                return data[0].toLowerCase().includes(searchTerm) || data[1].toLowerCase().includes(searchTerm);
            });
            table.draw();
            currentPage = 1;
            sortTableByDate(currentSort); // Ensure latest items are first
            updatePagination();
            // After searching, re-sort the table by date
        });

        // Menangani pemilihan filter dropdown "Semua"
        $('.filter-option[data-filter-value=""]').on('click', function() {
            // Reset filter untuk status dan jenis pengaduan
            hasActiveFilter = false; // Reset status filter
            filteredData = []; // Hapus data yang sudah difilter

            // Tampilkan semua data dan reset tampilan
            $('#TicketTable tbody').empty().append(originalData); // Tampilkan seluruh data asli
            sortTableByDate(currentSort); // Urutkan data sesuai urutan yang diinginkan
            updatePagination(); // Update pagination sesuai data yang ditampilkan
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

            $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
            $('#nextPage').prop('disabled', currentPage === totalPages || totalRows === 0).css('opacity', currentPage === totalPages || totalRows === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalRows === 0 ? 'not-allowed' : 'pointer');
        }

        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                sortTableByDate('asc');
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
                updatePagination();
            }
        }
        updatePagination(); // Update pagination after sorting
        sortTableByDate('desc');
    });
</script>

@endsection
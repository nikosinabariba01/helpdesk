@extends('mainlayout.layout')
@section('navbar')
    @include('mainlayout.navbar.nav')
@endsection
@section('pages')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Active</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Active</h6>
    </nav>
@endsection
@section('upnav')
    @include('mainlayout.navbar.upnav')
@endsection
@section('container')
    <div class="card mb-4">
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">Deskripsi</th>
                                    <th class="text-secondary text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
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
                                                        <li class="text-xs list-inline-item text-secondary" title="type">
                                                            <i
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
                                        <td class="align-middle text-center">
                                            <div class="dropdown">
                                                <a class="btn text-primary dropdown-toggle" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                    <a id="editButton" class="dropdown-item text-success" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModalMessage"
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
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    href="#"
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
                @endif
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
                    <!-- Filter Jenis Pengaduan Dropdown -->
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <button class="btn btn-sm btn-outline-secondary"
                            style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                            type="button" id="filterJenisPengaduanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="filterJenisPengaduanDisplay">Jenis Pengaduan</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterJenisPengaduanBtn"
                            style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan"
                                    data-filter-value="" style="padding: 8px 16px;">Semua</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan"
                                    data-filter-value="perbaikan" style="padding: 8px 16px;">Perbaikan</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan"
                                    data-filter-value="permintaan" style="padding: 8px 16px;">Permintaan</a></li>
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
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                    data-filter-value="close" style="padding: 8px 16px;">Close</a></li>
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
                    @if ($data_ticket->isEmpty())
                        <!-- Tampilkan tombol untuk membuat tiket baru -->
                        <a href="{{ route('customer.index') }}" class="btn btn-primary">Buat Tiket Baru</a>
                    @else
                        <form method="POST" action="{{ route('tickets.update', $dataticket->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Submit ticket</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
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
            const ticketgambar = button.getAttribute('data-ticket-gambar');

            // Set the form values based on the ticket details
            modal.querySelector('#subject').value = ticketSubject;
            modal.querySelector('#Jenis_Pengaduan').value = ticketJenis;
            modal.querySelector('#Lokasi').value = ticketLokasi;
            modal.querySelector('#Detail').value = ticketDetail;
            modal.querySelector('#gambar').value = ticketgambar;
        });
    });
</script>

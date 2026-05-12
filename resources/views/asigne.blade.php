@extends('mainlayout.layout')
@section('navbar')
    @include('mainlayout.navbar.nav')
@endsection
@section('pages')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Assigned</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">My Assigned Ticket</h6>
    </nav>
@endsection
@section('upnav')
    @include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
    <div class="col-lg-12 mb-lg-0 mb-4 ">
        <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                <h6 class="mb-0">assign ticket</h6>
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
                @if ($teknisi_data_ticket->isEmpty())
                    <div class="table-responsive margin-right: 15px; position: relative; table-responsive-custom"
                        style="overflow-y: auto;">
                        <!-- Add your button here -->
                        <a href="{{ route('teknisi.index') }}"
                            class="btn btn-primary position-absolute top-50 start-50 translate-middle">assign ticket</a>
                    </div>
                @else
                    <div class="table-responsive margin-right: 15px; table-responsive-custom" style="overflow-y: auto;">
                        <table class="table align-items-center mb-0" id="TicketTable">
                            <thead>
                                <tr>
                                    <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">subject</th>
                                    <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">User</th>
                                    <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">Deskripsi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">Aksi Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                        style="padding: 10px;">aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teknisi_data_ticket as $teknisidataticket)
                                    <tr class="align-middle text-sm border border-light"
                                        data-created-at="{{ $teknisidataticket->created_at->timestamp }}"
                                        data-jenis-pengaduan="{{ $teknisidataticket->Jenis_Pengaduan }}"
                                        data-status="{{ $teknisidataticket->status }}"
                                        data-subject="{{ $teknisidataticket->subject }}"
                                        data-user="{{ $teknisidataticket->user->name }}">
                                        <td class="align-middle text-sm border border-light">
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                                        <a
                                                            href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                            {{ $teknisidataticket->subject }}
                                                        </a>
                                                    </h6>

                                                    <div class="d-flex list-inline">
                                                        <li class="text-xs list-inline-item text-secondary">
                                                            <i class="fa fa-circle fa-xs text-danger"></i>
                                                            {{ 'sp-' . substr(preg_replace('/[^0-9]/', '', $teknisidataticket->id), -3) . \Carbon\Carbon::parse($teknisidataticket->created_at)->format('dmy') . ($teknisidataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}
                                                        </li>
                                                        <li class="text-xs list-inline-item text-secondary" title="type">
                                                            <i
                                                                class="fa fa-circle fa-xs text-primary"></i>{{ $teknisidataticket->Jenis_Pengaduan }}
                                                        </li>
                                                        <li class="text-xs list-inline-item text-secondary"
                                                            title="Created Date">
                                                            <i class="fa fa-circle fa-xs text-secondary"></i>
                                                            {{ \Carbon\Carbon::parse($teknisidataticket->created_at)->format('d-m-Y H:i') }}
                                                        </li>

                                                        @if ($teknisidataticket->status === 'close' && $teknisidataticket->Tanggal_Selesai)
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Closed Date">
                                                                <i class="fa fa-circle fa-xs text-success"></i>
                                                                {{ \Carbon\Carbon::parse($teknisidataticket->Tanggal_Selesai)->format('d-m-Y H:i') }}
                                                            </li>
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Time Taken to Close">
                                                                <i class="fa fa-circle fa-xs text-info"></i>
                                                                {{ \Carbon\Carbon::parse($teknisidataticket->created_at)->diffForHumans(\Carbon\Carbon::parse($teknisidataticket->Tanggal_Selesai)) }}
                                                            </li>
                                                        @elseif ($teknisidataticket->status === 'on process' || $teknisidataticket->status === 'escalated')
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Processing Time">
                                                                <i class="fa fa-circle fa-xs text-warning"></i>
                                                                {{ \Carbon\Carbon::parse($teknisidataticket->Tanggal_Proses)->diffForHumans() }}
                                                            </li>
                                                        @else
                                                            <li class="text-xs list-inline-item text-secondary"
                                                                title="Processing Time">
                                                                <i class="fa fa-circle fa-xs text-warning"></i>
                                                                Pending...
                                                            </li>
                                                        @endif
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
                                        <td class="align-middle text-center border border-light">
                                            <span
                                                class="text-secondary text-xs font-weight-bold ">{{ Str::limit($teknisidataticket->Detail, 40, '...') }}</span>
                                        </td>
                                        <td class="align-middle text-center text-sm border border-light">
                                            <!-- Cek apakah tiket sudah diassign oleh pemilik -->
                                            @if (!$teknisidataticket->hasOwnerAssignee)
                                                <!-- Jika belum ada pemilik yang meng-assign, tampilkan tombol cancel assign -->
                                                @if ($teknisidataticket->status != 'escalated')
                                                    <form
                                                        action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}"
                                                        method="POST" class="mb-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-warning btn-transparent text-warning">Cancel
                                                            Process</button>
                                                    </form>
                                                @endif
                                                <!-- Tombol Request Follow-up jika tiket belum escalated -->
                                                <form method="POST"
                                                    action="{{ $teknisidataticket->status == 'escalated' ? route('ticketsteknisi.cancelRequestFollowUp', $teknisidataticket->id) : route('ticketsteknisi.requestFollowup', $teknisidataticket->id) }}"
                                                    class="mb-2">
                                                    @csrf
                                                    @if ($teknisidataticket->status == 'escalated')
                                                        @method('PUT')
                                                        <!-- Menggunakan PUT karena kita akan memperbarui status tiket -->
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                                                            Cancel Escalation
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-info btn-transparent text-info">
                                                            Escalate
                                                        </button>
                                                    @endif
                                                </form>
                                                <!-- Tombol Close -->
                                                @if ($teknisidataticket->status != 'escalated')
                                                    <form method="POST"
                                                        action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}"
                                                        id="closeTicketForm-{{ $teknisidataticket->id }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                                                            data-bs-toggle="modal" data-bs-target="#modal-confirmation"
                                                            data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                                            Close
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <!-- Jika sudah ada pemilik yang meng-assign, hanya tampilkan tombol Cancel Assign -->
                                                @if ($teknisidataticket->status != 'escalated')
                                                    <form
                                                        action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}"
                                                        method="POST" class="mb-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-warning btn-transparent text-warning">Cancel
                                                            Process</button>
                                                    </form>
                                                @endif
                                                <!-- Tombol Close -->
                                                @if ($teknisidataticket->status != 'escalated')
                                                    <form method="POST"
                                                        action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}"
                                                        id="closeTicketForm-{{ $teknisidataticket->id }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                                                            data-bs-toggle="modal" data-bs-target="#modal-confirmation"
                                                            data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                                            Close
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                        <!-- Detail button -->
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
                    <div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; background-color: #ffffff;"
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap">

                        <!-- Left side: Filters / Dropdowns -->
                        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <!-- Sort Dropdown -->
                            <div class="dropdown" style="position: relative;">
                                <button class="btn btn-sm btn-outline-secondary"
                                    style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="paginationDisplay">1-10 dari {{ $teknisi_data_ticket->count() }}</span>
                                    <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                </button>
                                <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                                    <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc"><i
                                                class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru</a></li>
                                    <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc"><i
                                                class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama</a></li>
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
                                            data-filter-type="jenis_pengaduan" data-filter-value="perbaikan">Perbaikan</a>
                                    </li>
                                    <li><a class="dropdown-item filter-option" href="#"
                                            data-filter-type="jenis_pengaduan"
                                            data-filter-value="permintaan">Permintaan</a></li>
                                </ul>
                            </div>

                            <!-- Filter Status -->
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
                                            data-filter-value="">Semua</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                            data-filter-value="on process">On Process</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter-type="status"
                                            data-filter-value="escalated">Escalated</a></li>
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
                    user: $tr.data('user').toString().toLowerCase(),
                    status: $tr.data('status').toString().toLowerCase(),
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

                    // String sorting
                    if (typeof valA === 'string') {
                        valA = valA.toLowerCase();
                        valB = valB.toLowerCase();
                        if (valA < valB) return order === 'asc' ? -1 : 1;
                        if (valA > valB) return order === 'asc' ? 1 : -1;
                        return a.createdAt - b.createdAt; // tie-break
                    }

                    // Number sorting (createdAt)
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

                $('#TicketTable tbody tr').hide();

                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;

                const pageData = filteredData.slice(startIndex, endIndex);
                const $tbody = $('#TicketTable tbody');
                const rowsToShow = pageData.map(item => item.trElement.detach());
                $tbody.append(rowsToShow);
                rowsToShow.forEach(r => r.show());

                // ========================
                // Update pagination display
                // ========================
                const totalRows = filteredData.length;
                const displayStart = startIndex + 1;
                const displayEnd = Math.min(endIndex, totalRows);
                $('#paginationDisplay').text(`${displayStart}-${displayEnd} dari ${totalRows}`);

                // Enable/disable Prev/Next
                $('#prevPage').prop('disabled', currentPage <= 1)
                    .css('opacity', currentPage <= 1 ? 0.5 : 1)
                    .css('cursor', currentPage <= 1 ? 'not-allowed' : 'pointer');
                $('#nextPage').prop('disabled', currentPage >= totalPages)
                    .css('opacity', currentPage >= totalPages ? 0.5 : 1)
                    .css('cursor', currentPage >= totalPages ? 'not-allowed' : 'pointer');

                // Update sort icons
                $('#TicketTable thead th.sorting').removeClass('sorting_asc sorting_desc');
                $('#TicketTable thead th.sorting .sort-icons').remove();
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
                currentFilters[filterType] = filterValue.toLowerCase();

                // Update teks dropdown
                if (filterType === 'status') {
                    $('#filterStatusDisplay').text(filterValue ? `Status: ${$(this).text()}` : 'Status');
                } else if (filterType === 'jenis_pengaduan') {
                    $('#filterJenisPengaduanDisplay').text(filterValue ?
                        `Jenis Pengaduan: ${$(this).text()}` : 'Jenis Pengaduan');
                }

                currentPage = 1;
                renderTable();
            });

            // Dropdown sort terbaru/terlama
            $('.page-sort-option').click(function(e) {
                e.preventDefault();
                currentSort.column = 'createdAt';
                currentSort.order = $(this).data('sort');
                currentPage = 1;
                renderTable();
            });

            // Sorting klik th
            $('#TicketTable thead th.sorting').click(function() {
                const colText = $(this).text().trim().toLowerCase();
                if (['subject', 'user', 'status'].includes(colText)) currentSort.column = colText;
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

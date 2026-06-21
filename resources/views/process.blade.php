@extends('mainlayout.layout')
@section('navbar')
    @include('mainlayout.navbar.nav')
@endsection
@section('pages')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">My Tickets</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">My Tickets</h6>
    </nav>
@endsection
@section('upnav')
    @include('mainlayout.navbar.upnav')
@endsection
@section('container')
    <div class="card mb-4">
        <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                <h6 class="mb-0">All Ticket list</h6>
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
                @if ($data_ticket->isEmpty())
                    <div class="table-responsive margin-right: 15px; position: relative; table-responsive-custom" style="overflow-y: auto;">
                        <!-- Add your button here -->
                        <a href="{{ route('customer.tickets') }}"
                            class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
                    </div>
                @else
                    <div class="table-responsive margin-right: 15px; table-responsive-custom" style="overflow-y: auto;">
                        <table class="table align-items-center mb-0 " id="TicketTable">
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
                            <tbody>
                                @foreach ($data_ticket as $dataticket)
                                    <tr class="align-middle text-sm border border-light"
                                        data-created-at="{{ $dataticket->created_at->timestamp }}"
                                        data-jenis-pengaduan="{{ $dataticket->Jenis_Pengaduan }}"
                                        data-status="{{ $dataticket->status }}" data-subject="{{ $dataticket->subject }}"
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
                                        <td class="align-middle text-center text-sm border border-light">
                                            <x-status-badge :status="$dataticket->status" />
                                        </td>
                                        <td class="align-middle text-center border border-light">
                                            <span
                                                class="text-secondary text-xs font-weight-bold ">{{ Str::limit($dataticket->Detail, 40, '...') }}</span>
                                        </td>
                                        <td class="align-middle text-center border border-light">
                                            <a class="dropdown-item"
                                                href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
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
                                <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc"><i class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru</a></li>
                                <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc"><i class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama</a></li>
                            </ul>
                        </div>

                        <!-- Filter Jenis Pengaduan -->
                        <div class="dropdown" style="position: relative; display: inline-block;">
                            <button class="btn btn-sm btn-outline-secondary"
                                style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                                type="button" id="filterJenisPengaduanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="filterJenisPengaduanDisplay">Jenis Pengaduan</span>
                                <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterJenisPengaduanBtn" style="font-size: 13px; min-width: 150px;">
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="">Semua</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="perbaikan">Perbaikan</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="jenis_pengaduan" data-filter-value="permintaan">Permintaan</a></li>
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
                            <ul class="dropdown-menu" aria-labelledby="filterStatusBtn" style="font-size: 13px; min-width: 150px;">
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="">Semua</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="open">Open</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="on process">On Process</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="escalated">Escalated</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter-type="status" data-filter-value="close">Close</a></li>
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

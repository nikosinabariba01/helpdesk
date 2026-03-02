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
                <a href="{{ route('teknisi.index') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">assign ticket</a>
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
                        <tr>
                            <td class="align-middle text-sm border border-light">
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                            <a href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                {{ $teknisidataticket->subject }}
                                            </a>
                                        </h6>

                                        <div class="d-flex list-inline">
                                            <li class="text-xs list-inline-item text-secondary">
                                                <i class="fa fa-circle fa-xs text-danger"></i>
                                                {{ 'sp-' . substr(preg_replace('/[^0-9]/', '', $teknisidataticket->id), -3) . \Carbon\Carbon::parse($teknisidataticket->created_at)->format('dmy') . ($teknisidataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}
                                            </li>
                                            <li class="text-xs list-inline-item text-secondary" title="type">
                                                <i class="fa fa-circle fa-xs text-primary"></i>{{ $teknisidataticket->Jenis_Pengaduan }}
                                            </li>
                                            <li class="text-xs list-inline-item text-secondary" title="Created Date">
                                                <i class="fa fa-circle fa-xs text-secondary"></i>
                                                {{ \Carbon\Carbon::parse($teknisidataticket->created_at)->format('d-m-Y H:i') }}
                                            </li>

                                            @if ($teknisidataticket->status === 'close' && $teknisidataticket->Tanggal_Selesai)
                                            <li class="text-xs list-inline-item text-secondary" title="Closed Date">
                                                <i class="fa fa-circle fa-xs text-success"></i>
                                                {{ \Carbon\Carbon::parse($teknisidataticket->Tanggal_Selesai)->format('d-m-Y H:i') }}
                                            </li>
                                            <li class="text-xs list-inline-item text-secondary" title="Time Taken to Close">
                                                <i class="fa fa-circle fa-xs text-info"></i>
                                                {{ \Carbon\Carbon::parse($teknisidataticket->created_at)->diffForHumans(\Carbon\Carbon::parse($teknisidataticket->Tanggal_Selesai)) }}
                                            </li>
                                            @elseif ($teknisidataticket->status === 'on process' || $teknisidataticket->status === 'escalated')
                                            <li class="text-xs list-inline-item text-secondary" title="Processing Time">
                                                <i class="fa fa-circle fa-xs text-warning"></i>
                                                {{ \Carbon\Carbon::parse($teknisidataticket->updated_at)->diffForHumans() }}
                                            </li>
                                            @else
                                            <li class="text-xs list-inline-item text-secondary" title="Processing Time">
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
                            <td class="align-middle text-center text-limit-30 border border-light">
                                <span class="text-secondary text-xs font-weight-bold ">{{ $teknisidataticket->Detail }}</span>
                            </td>
                            <td class="align-middle text-center text-sm border border-light">
                                <!-- Cek apakah tiket sudah diassign oleh pemilik -->
                                @if($teknisidataticket->asignees->where('role', 'pemilik')->isEmpty())
                                <!-- Jika belum ada pemilik yang meng-assign, tampilkan tombol cancel assign -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-secondary">Cancel Process</button>
                                </form>
                                <!-- Tombol Request Follow-up jika tiket belum escalated -->
                                <form method="POST" action="{{ $teknisidataticket->status == 'escalated' ? route('ticketsteknisi.cancelRequestFollowUp', $teknisidataticket->id) : route('ticketsteknisi.requestFollowup', $teknisidataticket->id) }}" class="mb-2">
                                    @csrf
                                    @if($teknisidataticket->status == 'escalated')
                                    @method('PUT') <!-- Menggunakan PUT karena kita akan memperbarui status tiket -->
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-danger">
                                        <i class="fa fa-times pe-2 text-danger"></i>Cancel Escalation
                                    </button>
                                    @else
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                                        <i class="fa fa-refresh pe-2 text-success"></i>Escalate
                                    </button>
                                    @endif
                                </form>
                                <!-- Tombol Close -->
                                <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}" id="closeTicketForm-{{ $teknisidataticket->id }}">
                                    @method('PUT')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-transparent text-danger" data-bs-toggle="modal" data-bs-target="#modal-confirmation" data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                        <i class="fa fa-check pe-2 text-danger"></i>Close
                                    </button>
                                </form>
                                @else
                                <!-- Jika sudah ada pemilik yang meng-assign, hanya tampilkan tombol Cancel Assign -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-secondary">Cancel Process</button>
                                </form>
                                <!-- Tombol Close -->
                                <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}" id="closeTicketForm-{{ $teknisidataticket->id }}">
                                    @method('PUT')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-transparent text-danger" data-bs-toggle="modal" data-bs-target="#modal-confirmation" data-form-id="closeTicketForm-{{ $teknisidataticket->id }}">
                                        <i class="fa fa-check pe-2 text-danger"></i>Close
                                    </button>
                                </form>
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
            <div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <!-- Pagination Info as Dropdown -->
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="paginationDisplay">1-10 dari {{ $teknisi_data_ticket->count() }}</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc" style="padding: 8px 16px;">
                                    <i class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru
                                </a></li>
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc" style="padding: 8px 16px;">
                                    <i class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama
                                </a></li>
                        </ul>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; align-items: center;">
                    <!-- Pagination Navigation -->
                    <div style="display: flex; gap: 6px;">
                        <button id="prevPage" class="btn btn-sm btn-outline-secondary" style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" title="Halaman Sebelumnya">
                            <i class="fa fa-chevron-left" style="font-size: 11px;"></i>
                        </button>
                        <button id="nextPage" class="btn btn-sm btn-outline-secondary" style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" title="Halaman Berikutnya">
                            <i class="fa fa-chevron-right" style="font-size: 11px;"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Confirmation -->
<div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="modal-confirmation" aria-hidden="true">
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
    $(document).ready(function() {
        let currentSort = 'desc'; // Default: Terbaru
        let currentPage = 1;
        const itemsPerPage = 10;
        let selectedFormId = null; // Store selected form ID

        var table = $('#TicketTable').DataTable({
            searching: true,
            ordering: false,
            paging: false,
            lengthChange: false,
            info: false,
            columnDefs: [{
                targets: [2, 3, 4, 5],
                orderable: false
            }]
        });

        // Menyembunyikan elemen pencarian bawaan
        $('#TicketTable_filter').hide();
        $('#TicketTable_length').hide();
        $('#TicketTable_paginate').hide();

        // Initial pagination
        updatePagination();

        // Handle modal show event - simpan form ID yang dipilih
        $('#modal-confirmation').on('show.bs.modal', function(e) {
            const button = e.relatedTarget; // Tombol yang memicu modal
            selectedFormId = button.getAttribute('data-form-id');
        });

        // Handle modal submit button
        $('#modal-submit-btn').on('click', function() {
            if (selectedFormId) {
                document.getElementById(selectedFormId).submit();
            }
        });

        // Custom search hanya kolom Subject dan User (kolom 0 dan 1)
        $('#search').on('keyup', function() {
            var searchTerm = this.value.toLowerCase();

            $.fn.dataTable.ext.search = [];
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    // Kolom subject dan user (kolom ke-0 dan ke-1)
                    var subject = data[0].toLowerCase(); // subject
                    var user = data[1].toLowerCase(); // user

                    return subject.includes(searchTerm) || user.includes(searchTerm);
                }
            );

            table.draw();
            currentPage = 1;
            updatePagination();
        });

        // Handle pagination sort option clicks (from paginationInfo dropdown)
        $(document).on('click', '.page-sort-option', function(e) {
            e.preventDefault();
            currentSort = $(this).data('sort');

            // Sort rows
            sortTableByDate(currentSort);
            currentPage = 1;
            updatePagination();
        });

        // Function to sort table by date
        function sortTableByDate(direction) {
            var rows = $('#TicketTable tbody tr').get();

            rows.sort(function(a, b) {
                // Ambil teks dari kolom pertama (yang berisi nomor tiket dengan tanggal)
                var aText = $(a).find('li:first').text(); // sp-123012401
                var bText = $(b).find('li:first').text(); // sp-456012402

                // Ekstrak tanggal dari format sp-xxxddmmyy
                var aDate = extractDateFromTicket(aText);
                var bDate = extractDateFromTicket(bText);

                if (direction === 'desc') {
                    return new Date(bDate) - new Date(aDate);
                } else {
                    return new Date(aDate) - new Date(bDate);
                }
            });

            $.each(rows, function(index, row) {
                $('#TicketTable tbody').append(row);
            });
        }

        // Function to extract date from ticket format
        function extractDateFromTicket(ticketText) {
            // Format: sp-xxxddmmyy
            var match = ticketText.match(/sp-(\d{3})(\d{6})/);
            if (match) {
                var dateStr = match[2]; // ddmmyy
                var day = dateStr.substring(0, 2);
                var month = dateStr.substring(2, 4);
                var year = '20' + dateStr.substring(4, 6);
                return new Date(year, parseInt(month) - 1, day);
            }
            return new Date(0);
        }

        // Function to update pagination display
        function updatePagination() {
            var allRows = $('#TicketTable tbody tr');
            var totalRows = allRows.length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);

            // Batasi halaman
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            // Hide semua rows
            allRows.hide();

            // Show rows untuk halaman saat ini
            var startIndex = (currentPage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            allRows.slice(startIndex, endIndex).show();

            // Update pagination display berdasarkan sorting
            var displayStart, displayEnd;

            if (currentSort === 'desc') {
                // Terbaru: tampil normal (1-10, 11-20, dst)
                displayStart = totalRows === 0 ? 0 : startIndex + 1;
                displayEnd = Math.min(endIndex, totalRows);
            } else {
                // Terlama: tampil terbalik (100-90, 90-80, dst)
                displayStart = totalRows - startIndex;
                displayEnd = totalRows - endIndex + 1;

                // Pastikan displayEnd tidak kurang dari 1
                if (displayEnd < 1) {
                    displayEnd = 1;
                }
            }

            $('#paginationDisplay').text(displayStart + '-' + displayEnd + ' dari ' + totalRows);

            // Update button states
            $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
            $('#nextPage').prop('disabled', currentPage === totalPages || totalPages === 0).css('opacity', currentPage === totalPages || totalPages === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalPages === 0 ? 'not-allowed' : 'pointer');
        }

        // Handle pagination buttons
        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });

        $('#nextPage').on('click', function() {
            var totalRows = $('#TicketTable tbody tr').length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
    });
</script>

@endsection
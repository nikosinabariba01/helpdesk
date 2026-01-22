@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Closed Ticket</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">My Closed Ticket</h6>
</nav>
@endsection
@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="col-lg-12 mb-lg-0 mb-4 ">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Closed Ticket</h6>
            <div class="d-flex">
                <!-- Kolom Pencarian dengan input-group -->
                <div class="input-group input-group-sm">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" id="search" class="form-control" placeholder="Search" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2 h-500">
            @if($teknisi_data_ticket->isEmpty())
            <div class="table-responsive margin-right: 15px; position: relative;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <!-- Add your button here -->
                <a href="{{ route('teknisi.index') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">assign ticket</a>
            </div>
            @else
            <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <table class="table align-items-center mb-0" id="TicketTable">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">subject</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">User</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Deskripsi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Asign</th>
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
                                <form action="{{ route('tickets.assign', $teknisidataticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning text-secondary">Re-assign</button>
                                </form>
                            </td>
                            <!-- "Edit" button within a dropdown -->
                            <td class="align-middle text-center border border-light">
                                <div class="dropdown">
                                    <a class="btn btn-link" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v fa-sm"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                        <li>
                                        <li>
                                            <a class="dropdown-item text-info" href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                <i class="fa fa-eye pe-2 text-info"></i>Detail
                                            </a>
                                        </li>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('tickets.destroy', $teknisidataticket->id) }}">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" href="#" onclick="return confirm ('are you sure?')"><i class="fa fa-trash pe-2 text-danger"></i>delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
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
            ordering: false,
            paging: false,
            lengthChange: false,
            info: false,
            columnDefs: [{targets: [2, 3, 4, 5], orderable: false}]
        });
        $('#TicketTable_filter').hide();
        $('#TicketTable_length').hide();
        $('#TicketTable_paginate').hide();
        updatePagination();
        $('#search').on('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            $.fn.dataTable.ext.search = [];
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                return data[0].toLowerCase().includes(searchTerm) || data[1].toLowerCase().includes(searchTerm);
            });
            table.draw();
            currentPage = 1;
            updatePagination();
        });
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
                var aDate = extractDateFromTicket($(a).find('li:first').text());
                var bDate = extractDateFromTicket($(b).find('li:first').text());
                return direction === 'desc' ? new Date(bDate) - new Date(aDate) : new Date(aDate) - new Date(bDate);
            });
            $.each(rows, function(index, row) {$('#TicketTable tbody').append(row);});
        }
        function extractDateFromTicket(ticketText) {
            var match = ticketText.match(/sp-(\d{3})(\d{6})/);
            if (match) {
                var dateStr = match[2];
                return new Date('20' + dateStr.substring(4, 6), parseInt(dateStr.substring(2, 4)) - 1, dateStr.substring(0, 2));
            }
            return new Date(0);
        }
        function updatePagination() {
            var allRows = $('#TicketTable tbody tr');
            var totalRows = allRows.length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            if (currentPage > totalPages) currentPage = totalPages || 1;
            allRows.hide();
            var startIndex = (currentPage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            allRows.slice(startIndex, endIndex).show();
            var displayStart, displayEnd;
            if (currentSort === 'desc') {
                displayStart = totalRows === 0 ? 0 : startIndex + 1;
                displayEnd = Math.min(endIndex, totalRows);
            } else {
                displayStart = totalRows - startIndex;
                displayEnd = totalRows - endIndex + 1;
                if (displayEnd < 1) displayEnd = 1;
            }
            $('#paginationDisplay').text(displayStart + '-' + displayEnd + ' dari ' + totalRows);
            $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
            $('#nextPage').prop('disabled', currentPage === totalPages || totalPages === 0).css('opacity', currentPage === totalPages || totalPages === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalPages === 0 ? 'not-allowed' : 'pointer');
        }
        $('#prevPage').on('click', function() {if (currentPage > 1) {currentPage--; updatePagination();}});
        $('#nextPage').on('click', function() {var totalRows = $('#TicketTable tbody tr').length; const totalPages = Math.ceil(totalRows / itemsPerPage); if (currentPage < totalPages) {currentPage++; updatePagination();}});
    });
</script>

@endsection
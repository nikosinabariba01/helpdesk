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
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')

<div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3" style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
      <div class="numbers" style="flex: 1;">
        <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Current Ticket</p>
        <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
          {{ $totalTickets  }}
        </h5>
      </div>
      <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
          <i class="fa fa-copy text-lg opacity-10" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3" style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
      <div class="numbers" style="flex: 1;">
        <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Assigned Ticket</p>
        <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
          {{ $totalOnProcessTickets }}
        </h5>
      </div>
      <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
          <i class="fa fa-clipboard contact text-lg opacity-10" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6 col-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3" style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
      <div class="numbers" style="flex: 1;">
        <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Closed Ticket</p>
        <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
          {{ $totalClosedTickets }}
        </h5>
      </div>
      <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
          <i class="fa fa-minus round text-lg opacity-10" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6 col-6">
  <div class="card">
    <div class="card-body p-3" style="min-height: 120px; display: flex; align-items: center; justify-content: space-between;">
      <div class="numbers" style="flex: 1;">
        <p class="text-sm mb-2 text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">All Ticket</p>
        <h5 class="font-weight-bolder mb-0" style="font-size: 28px; line-height: 1.2;">
          {{ $totalAllTickets }}
        </h5>
      </div>
      <div class="d-flex align-items-center justify-content-center" style="flex: 0 0 auto; margin-left: 12px;">
        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
          <i class="fa fa-folder text-lg opacity-10" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row mt-4">
  <!-- LINE CHART (KIRI) -->
  <div class="col-lg-8 mb-4">
    <div class="card z-index-2 h-100 shadow-lg" style="border: 1px solid #e4e4e4;">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <div>
          <h6 class="mb-0">Tickets per Bulan (Permintaan vs Perbaikan)</h6>
          <small class="text-secondary">
            Tahun: {{ $selectedYear }} • Scope:
            <b>{{ $scope === 'all' ? 'All (Open+Close)' : strtoupper($scope) }}</b>
          </small>
        </div>

        <div class="d-flex align-items-center gap-2">
          <!-- Dropdown scope -->
          <select id="scopeSelect" class="form-select form-select-sm" style="width: 165px;">
            <option value="all" {{ $scope==='all' ? 'selected' : '' }}>All (Open+Close)</option>
            <option value="open" {{ $scope==='open' ? 'selected' : '' }}>Open</option>
            <option value="close" {{ $scope==='close' ? 'selected' : '' }}>Close</option>
          </select>

          <!-- Dropdown tahun -->
          <select id="yearSelect" class="form-select form-select-sm" style="width: 110px;">
            @foreach($years as $y)
            <option value="{{ $y }}" {{ (int)$selectedYear === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="card-body">
        <div style="height: 330px;">
          <canvas id="ticketsLineChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- PIE CHART (KANAN) -->
  <div class="col-lg-4 mb-4">
    <div class="card z-index-2 h-100 shadow-lg" style="border: 1px solid #e4e4e4;">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Komposisi Status</h6>

        <select id="pieMonthSelect" class="form-select form-select-sm" style="width: 140px;">
          <option value="0" selected>All Year</option>
          <option value="1">Januari</option>
          <option value="2">Februari</option>
          <option value="3">Maret</option>
          <option value="4">April</option>
          <option value="5">Mei</option>
          <option value="6">Juni</option>
          <option value="7">Juli</option>
          <option value="8">Agustus</option>
          <option value="9">September</option>
          <option value="10">Oktober</option>
          <option value="11">November</option>
          <option value="12">Desember</option>
        </select>
      </div>
      <div class="card-body">
        <div style="height: 330px;">
          <canvas id="ticketsPieChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-12 mb-lg-0 mb-4 ">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Current Ticket</h6>
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
          <a href="{{ route('customer.tickets') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
        </div>
        @else
        <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
          <table class="table align-items-center mb-0" id="TicketTable">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">subject</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assign</th>
                <th class="text-secondary text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">aksi</th>
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
                        <li class="text-xs list-inline-item text-secondary"><i class="fa fa-circle fa-xs text-danger"></i>{{'sp-' . substr(preg_replace('/[^0-9]/', '', $teknisidataticket->id), -3) . \Carbon\Carbon::parse($teknisidataticket->created_at)->format('dmy') . ($teknisidataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}</li>
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
                    <button type="submit" class="btn btn-sm btn-transparent text-primary">Assign</button>
                  </form>
                </td>
                <!-- "Edit" button within a dropdown -->
                <td class="align-middle text-center border border-light">
                  <a class="dropdown-item" href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
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


  <!-- Modal -->
  <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          @if ($teknisi_data_ticket->isEmpty())
          <!-- Tampilkan tombol untuk membuat tiket baru -->
          <a href="{{ route('customer.index') }}" class="btn btn-primary">Buat Tiket Baru</a>
          @else
          <!-- Iterasi melalui data tiket jika tidak kosong -->
          @forelse ($teknisi_data_ticket as $teknisidataticket)
          <form method="POST" action="{{ route('ticketsteknisi.update', $teknisidataticket->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="subject">Subject</label>
                  <input type="text" id="subject" name="subject" class="form-control border-input" value="">
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
                  <select id="Jenis_Pengaduan" name="Jenis_Pengaduan" class="form-control border-input">
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
                  <input type="text" id="Lokasi" name="Lokasi" class="form-control border-input">
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
                  <textarea id="Detail" name="Detail" rows="5" class="form-control border-input" placeholder="Here can be your description" value=""></textarea>
                  @error('Detail')
                  <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="gambar">Gambar Pendukung</label>
                <input class="form-control form-control-sm" id="gambar" name="gambar" type="file">
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
          @empty
          <p>Tidak ada data tiket yang tersedia.</p>
          @endforelse
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
    let currentSort = 'desc'; // Default: Terbaru
    let currentPage = 1;
    const itemsPerPage = 10;

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

      // Set the form values based on the ticket details
      modal.querySelector('#subject').value = ticketSubject;
      modal.querySelector('#Jenis_Pengaduan').value = ticketJenis;
      modal.querySelector('#Lokasi').value = ticketLokasi;
      modal.querySelector('#Detail').value = ticketDetail;
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // LINE (jenis) + DOUGHNUT (status)
    const chartLine = @json($chartLine); // <-- permintaan vs perbaikan (open/close/all)
    const chartData = @json($chartData); // <-- status untuk doughnut

    const fmt = (n) => new Intl.NumberFormat('id-ID').format(n);

    // =========================
    // YEAR SELECT (untuk LINE)
    // =========================
    const chartYearEl = document.getElementById('chartYear');
    if (chartYearEl) chartYearEl.textContent = chartLine?.year ?? '';

    const yearSelect = document.getElementById('yearSelect');
    const scopeSelect = document.getElementById('scopeSelect');

    function reloadWithParams() {
      const url = new URL(window.location.href);

      if (yearSelect) url.searchParams.set('year', yearSelect.value);
      if (scopeSelect) url.searchParams.set('scope', scopeSelect.value);

      window.location.href = url.toString();
    }

    if (yearSelect) yearSelect.addEventListener('change', reloadWithParams);
    if (scopeSelect) scopeSelect.addEventListener('change', reloadWithParams);

    // =========================
    // PLUGIN: hover crosshair line (line chart)
    // =========================
    const hoverLinePlugin = {
      id: 'hoverLine',
      afterDatasetsDraw(chart) {
        const active = chart.tooltip?.getActiveElements?.() || [];
        if (!active.length) return;

        const {
          ctx,
          chartArea: {
            top,
            bottom
          }
        } = chart;
        const x = active[0].element.x;

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(x, top);
        ctx.lineTo(x, bottom);
        ctx.setLineDash([4, 4]);
        ctx.lineWidth = 1;
        ctx.strokeStyle = 'rgba(0,0,0,.12)';
        ctx.stroke();
        ctx.restore();
      }
    };

    // =========================
    // PLUGIN: center text (doughnut)
    // =========================
    const centerTextPlugin = {
      id: 'centerText',
      afterDraw(chart, args, opts) {
        if (chart.config.type !== 'doughnut') return;

        const {
          ctx
        } = chart;
        const meta = chart.getDatasetMeta(0);
        if (!meta?.data?.length) return;

        const x = meta.data[0].x;
        const y = meta.data[0].y;

        const data = chart.data.datasets[0].data || [];
        const total = data.reduce((a, b) => a + b, 0);

        // close rate
        const closeIndex = chart.data.labels.indexOf('close');
        const closed = closeIndex >= 0 ? (data[closeIndex] || 0) : 0;
        const closeRate = total ? (closed / total * 100) : 0;

        const topText = opts?.topText ?? 'Total Ticket';
        const midText = opts?.midText ?? fmt(total);
        const botText = opts?.botText ?? `Close rate ${closeRate.toFixed(1)}%`;

        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        ctx.fillStyle = 'rgba(52,71,103,.70)';
        ctx.font = '600 12px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial';
        ctx.fillText(topText, x, y - 18);

        ctx.fillStyle = 'rgba(52,71,103,.95)';
        ctx.font = '800 22px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial';
        ctx.fillText(midText, x, y + 2);

        ctx.fillStyle = 'rgba(52,71,103,.70)';
        ctx.font = '600 12px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial';
        ctx.fillText(botText, x, y + 22);

        ctx.restore();
      }
    };

    Chart.register(hoverLinePlugin, centerTextPlugin);

    // ==========================================================
    // ✅ LINE CHART: Permintaan vs Perbaikan (Jan–Des) + scope
    // ==========================================================
    const lineCtx = document.getElementById('ticketsLineChart');
    let lineDelayed = false;

    const lineLabels = chartLine.labels; // Jan..Des
    const types = chartLine.types; // ['perbaikan','permintaan']

    // warna 2 garis (dashboard look)
    const typeColors = {
      perbaikan: {
        stroke: "rgba(94,114,228,1)",
        fillTop: "rgba(94,114,228,.20)"
      },
      permintaan: {
        stroke: "rgba(245,158,11,1)",
        fillTop: "rgba(245,158,11,.18)"
      },
    };

    const lineDatasets = types.map((t) => ({
      label: t,
      data: chartLine.monthlyByType[t] || Array(12).fill(0), // ✅ bulan kosong tetap 0
      borderColor: typeColors[t].stroke,
      backgroundColor: (context) => {
        const chart = context.chart;
        const {
          ctx,
          chartArea
        } = chart;
        if (!chartArea) return typeColors[t].fillTop;

        const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        g.addColorStop(0, typeColors[t].fillTop);
        g.addColorStop(1, 'rgba(255,255,255,0)');
        return g;
      },
      fill: true,
      tension: 0.38,
      borderWidth: 2,
      pointRadius: 2.8,
      pointHoverRadius: 6,
      pointHitRadius: 12,
      pointBorderWidth: 0,
    }));

    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: lineLabels,
        datasets: lineDatasets
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              boxWidth: 10,
              padding: 14
            }
          },
          tooltip: {
            padding: 12,
            callbacks: {
              label: (ctx) => ` ${ctx.dataset.label}: ${fmt(ctx.parsed.y)}`,
              footer: (items) => {
                const total = items.reduce((sum, it) => sum + (it.parsed.y || 0), 0);
                return `Total bulan ini: ${fmt(total)}`;
              }
            }
          }
        },
        layout: {
          padding: {
            top: 6,
            right: 10,
            bottom: 0,
            left: 6
          }
        },
        animation: {
          duration: 900,
          easing: 'easeOutQuart',
          onComplete: () => {
            lineDelayed = true;
          },
          delay: (ctx) => {
            if (ctx.type === 'data' && ctx.mode === 'default' && !lineDelayed) {
              return ctx.dataIndex * 45 + ctx.datasetIndex * 80;
            }
            return 0;
          }
        },
        animations: {
          tension: {
            duration: 650,
            easing: 'easeOutQuart',
            from: 0.2,
            to: 0.38
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: 'rgba(52,71,103,.65)'
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0,
              color: 'rgba(52,71,103,.65)',
              callback: (v) => fmt(v)
            },
            grid: {
              color: 'rgba(0,0,0,.06)',
              borderDash: [6, 4]
            }
          }
        }
      }
    });

    // ==========================================================
    // ✅ DOUGHNUT: Komposisi Status - default bulan sekarang
    // ==========================================================
    const pieCtx = document.getElementById('ticketsPieChart');
    const pieMonthSelect = document.getElementById('pieMonthSelect');

    const statuses = chartData.statuses; // ["open","on process","close","escalated"]

    const statusColors = {
      "open": {
        stroke: "rgba(94,114,228,1)"
      },
      "on process": {
        stroke: "rgba(245,158,11,1)"
      },
      "close": {
        stroke: "rgba(46,204,113,1)"
      },
      "escalated": {
        stroke: "rgba(231,76,60,1)"
      }
    };

    const MONTHS_FULL = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const currentMonth = new Date().getMonth() + 1; // 1..12

    function getPieData(monthValue) {
      if (monthValue === 0) {
        // total setahun
        return statuses.map(s => (chartData.monthlyByStatus[s] || []).reduce((a, b) => a + b, 0));
      }
      const idx = Math.max(0, Math.min(11, monthValue - 1));
      return statuses.map(s => (chartData.monthlyByStatus[s] || [])[idx] || 0);
    }

    function pieTopText(m) {
      return m === 0 ?
        `Komposisi Status (${chartData.year})` :
        `Komposisi Status (${MONTHS_FULL[m - 1]} ${chartData.year})`;
    }

    function closeRateText(dataArr) {
      const total = dataArr.reduce((a, b) => a + b, 0);
      const closeIndex = statuses.indexOf('close');
      const closed = closeIndex >= 0 ? (dataArr[closeIndex] || 0) : 0;
      const rate = total ? (closed / total * 100) : 0;
      return `Close rate ${rate.toFixed(1)}%`;
    }

    if (pieMonthSelect) pieMonthSelect.value = String(currentMonth);

    const initialMonth = pieMonthSelect ? Number(pieMonthSelect.value) : currentMonth;
    const initialData = getPieData(initialMonth);

    const doughnutChart = new Chart(pieCtx, {
      type: 'doughnut',
      data: {
        labels: statuses,
        datasets: [{
          data: initialData,
          backgroundColor: statuses.map(s => statusColors[s]?.stroke || 'rgba(0,0,0,.4)'),
          borderColor: 'rgba(255,255,255,.85)',
          borderWidth: 2,
          hoverOffset: 10,
          borderRadius: 10,
          spacing: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              boxWidth: 10,
              padding: 14
            }
          },
          tooltip: {
            padding: 12,
            callbacks: {
              label: (ctx) => {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                const val = ctx.parsed || 0;
                const pct = total ? (val / total * 100) : 0;
                return ` ${ctx.label}: ${fmt(val)} (${pct.toFixed(1)}%)`;
              }
            }
          },
          centerText: {
            topText: pieTopText(initialMonth),
            midText: fmt(initialData.reduce((a, b) => a + b, 0)),
            botText: closeRateText(initialData)
          }
        },
        animation: {
          duration: 900,
          easing: 'easeOutQuart',
          animateRotate: true,
          animateScale: true
        }
      }
    });

    if (pieMonthSelect) {
      pieMonthSelect.addEventListener('change', () => {
        const m = Number(pieMonthSelect.value);
        const data = getPieData(m);

        doughnutChart.data.datasets[0].data = data;
        doughnutChart.options.plugins.centerText.topText = pieTopText(m);
        doughnutChart.options.plugins.centerText.midText = fmt(data.reduce((a, b) => a + b, 0));
        doughnutChart.options.plugins.centerText.botText = closeRateText(data);

        doughnutChart.update();
      });
    }
  });
</script>
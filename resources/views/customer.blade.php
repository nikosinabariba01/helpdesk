@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav')
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endpush
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
@include('mainlayout.navbar.upnav')
@endsection


@section('container')

<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3">
      <div class="row">
        <div class="col-8">
          <div class="numbers">
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Open</p>
            <h5 class="font-weight-bolder">
              {{ $OpenTic }}
            </h5>
          </div>
        </div>
        <div class="col-4 text-end">
          <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
            <i class="fa fa-copy text-lg opacity-10" aria-hidden="true"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3">
      <div class="row">
        <div class="col-8">
          <div class="numbers">
            <p class="text-sm mb-0 text-uppercase font-weight-bold">On Process</p>
            <h5 class="font-weight-bolder">
              {{ $OnProcessTickets }}
            </h5>

          </div>
        </div>
        <div class="col-4 text-end">
          <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
            <i class="fa fa-clipboard text-lg opacity-10" aria-hidden="true"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
  <div class="card">
    <div class="card-body p-3">
      <div class="row">
        <div class="col-8">
          <div class="numbers">
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Close</p>
            <h5 class="font-weight-bolder">
              {{ $closedtic }}
            </h5>
          </div>
        </div>
        <div class="col-4 text-end">
          <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
            <i class="fa fa-minus text-lg opacity-10" aria-hidden="true"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-sm-6">
  <div class="card">
    <div class="card-body p-3">
      <div class="row">
        <div class="col-8">
          <div class="numbers">
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Ticket</p>
            <h5 class="font-weight-bolder">
              {{ $totalTickets }}
            </h5>
          </div>
        </div>
        <div class="col-4 text-end">
          <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
            <i class="fa fa-folder text-lg opacity-10" aria-hidden="true"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div class="row mt-4">
  <div class="col-lg-8 mb-lg-0 mb-4 ">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Ticket list</h6>
        <div class="d-flex">
          <!-- Kolom Pencarian dengan input-group -->
          <div class="input-group input-group-sm">
            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input type="text" id="search" class="form-control" placeholder="Search" onfocus="focused(this)" onfocusout="defocused(this)">
          </div>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2 h-500">
        @if($data_ticket->isEmpty())
        <div class="table-responsive margin-right: 15px; position: relative;" style="height: 400px; max-height: 400px; overflow-y: auto;">
          <!-- Add your button here -->
          <a href="{{ route('customer.tickets') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
        </div>
        @else
        <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
          <table class="table align-items-center mb-0 " id="TicketTable">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">subject</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Status</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Deskripsi</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data_ticket as $dataticket)
              <tr>
                <td class="align-middle text-sm border border-light">
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-s text-limit-35" title="Subject">
                        <a href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
                          {{ $dataticket->subject }}
                        </a>
                      </h6>
                      <div class="d-flex list-inline">
                        <li class="text-xs list-inline-item text-secondary"><i class="fa fa-circle fa-xs text-danger"></i>{{'sp-' . substr(preg_replace('/[^0-9]/', '', $dataticket->id), -3) . \Carbon\Carbon::parse($dataticket->created_at)->format('dmy') . ($dataticket->Jenis_Pengaduan == 0 ? '0' : '1') }}</li>
                        <li class="text-xs list-inline-item text-secondary" title="type"><i class="fa fa-circle fa-xs text-primary"></i>{{ $dataticket->Jenis_Pengaduan }}</li>
                        <li class="text-xs list-inline-item text-secondary" title="Created Date"><i class="fa fa-circle fa-xs text-secondary"></i></i> {{ $dataticket->formattedTanggalPengaduan }}</li>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="align-middle text-center text-sm border border-light">
                  <x-status-badge :status="$dataticket->status" />
                </td>
                <td class="align-middle text-center text-limit-30 border border-light">
                  <span class="text-secondary text-xs font-weight-bold ">{{ $dataticket->Detail }}</span>
                </td>
                <!-- "Edit" button within a dropdown -->
                <td class="align-middle text-center border border-light">
                  <div class="dropdown">
                    <a class="btn text-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class=""></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                      <li>
                      <li>
                        <a class="dropdown-item text-info" href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
                          <i class="fa fa-eye pe-2 text-info"></i>Detail
                        </a>
                      </li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalMessage" data-ticket-id="{{ $dataticket->id }}" data-ticket-subject="{{ $dataticket->subject }}" data-ticket-jenis="{{ $dataticket->Jenis_Pengaduan }}" data-ticket-lokasi="{{ $dataticket->Lokasi }}" data-ticket-detail="{{ $dataticket->Detail }}">
                        <i class="fa fa-pencil pe-2 text-success"></i>edit
                      </a>

                      </li>
                      <li>
                        <form method="POST" action="{{ route('tickets.destroy', $dataticket->id) }}">
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
        
        <!-- Custom Pagination and Sorting Controls -->
        <div class="d-flex justify-content-between align-items-center px-3 py-3" style="border-top: 1px solid #e4e4e4;">
          <!-- Pagination Info -->
          <div class="pagination-info">
            <small class="text-muted">
              Menampilkan <span id="pagingInfo">1-10</span> dari <span id="totalInfo">{{ $data_ticket->count() }}</span>
            </small>
          </div>
          
          <!-- Sorting and Pagination Buttons -->
          <div class="d-flex gap-2">
            <!-- Sort Button -->
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-sm btn-outline-secondary" id="sortBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-sort-amount-down pe-1"></i> Tanggal
              </button>
              <ul class="dropdown-menu dropdown-menu-end" id="sortDropdown">
                <li><a class="dropdown-item" href="#" data-sort="desc"><i class="fa fa-arrow-down pe-2"></i>Terbaru</a></li>
                <li><a class="dropdown-item" href="#" data-sort="asc"><i class="fa fa-arrow-up pe-2"></i>Terlama</a></li>
              </ul>
            </div>
            
            <!-- Pagination Buttons -->
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-outline-secondary" id="prevBtn" title="Halaman Sebelumnya">
                <i class="fa fa-chevron-left"></i>
              </button>
              <button class="btn btn-sm btn-outline-secondary" id="nextBtn" title="Halaman Berikutnya">
                <i class="fa fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>

  </div>
  <div class="col-lg-4 ms-auto">
    <div class="card shadow-lg overflow-hidden h-100 p-0">
      <div class="card-header bg-gradient-success border-0 p-3">
        <h5 class="mb-0 text-white">Announcement</h5>
      </div>
      <div class="card-body p-4" style="height: 500px; overflow-y: auto;">
        <div class="list-group">
          @if($pengumuman->isEmpty())
          <div class="text-center text-muted py-5">
            <i class="fa fa-inbox fa-3x mb-3 opacity-5"></i>
            <p>Tidak ada pengumuman</p>
          </div>
          @else
          @foreach($pengumuman as $item)
          <div class="list-group-item shadow-sm mb-3" style="padding: 12px 16px; border: 1px solid #e4e4e4; border-radius: 6px;">
            <!-- Pengirim Info -->
            <div class="d-flex align-items-center mb-2">
              <img src="{{ $item->creator && $item->creator->profile_photo ? route('profile.photo', ['filename' => basename($item->creator->profile_photo)]) : asset('default-profile.png') }}" 
                   alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; margin-right: 10px;">
              <div class="flex-grow-1">
                <h6 class="mb-0 text-dark" style="font-size: 14px; font-weight: 600;">{{ $item->creator->name }}</h6>
                <small class="text-muted" style="font-size: 11px;">{{ $item->creator->role }}</small>
              </div>
              <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
            </div>
            
            <!-- Judul Pengumuman -->
            <h5 class="mb-2 text-dark" style="font-size: 15px; font-weight: 600;">{{ $item->judul }}</h5>
            
            <!-- Deskripsi Pengumuman -->
            <p class="mb-0 text-muted" style="font-size: 13px; line-height: 1.4;">{{ Str::limit($item->deskripsi, 100) }}</p>
          </div>
          @endforeach
          @endif
        </div>
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
          <form method="POST" id="editTicketForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="ticketId" name="ticketId" value="">
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
                <input class="form-control form-control-sm @error('gambar') is-invalid @enderror" id="gambar" name="gambar" type="file" accept="image/*">
                @error('gambar')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
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
    const itemsPerPage = 5;
    let currentPage = 1;
    let allRows = [];
    let sortOrder = 'desc'; // 'desc' = terbaru, 'asc' = terlama
    let searchTerm = '';

    // Initialize
    function initTable() {
      const table = $('#TicketTable tbody');
      // Clone semua row tanpa menghapusnya dari DOM
      allRows = table.find('tr').map(function() {
        return $(this).clone(true);
      }).get();
      
      if (allRows.length > 0) {
        renderTable();
      }
    }

    // Render table based on current page and sort order
    function renderTable() {
      const table = $('#TicketTable tbody');
      table.empty();

      // Sort rows
      let rowsToSort = allRows.slice(); // Copy array
      rowsToSort.sort(function(a, b) {
        // Extract date from the ticket number (format: sp-123012401 where dmy = last 6 digits after sp-)
        const getDate = (row) => {
          const ticketNum = $(row).find('li:first').text().trim();
          // Extract date part - looking for pattern sp-XXXDDMMYY
          const match = ticketNum.match(/sp-\d{3}(\d{6})/);
          if (match) {
            return moment(match[1], 'DDMMYY').unix();
          }
          return 0;
        };

        const dateA = getDate(a);
        const dateB = getDate(b);

        if (sortOrder === 'desc') {
          return dateB - dateA; // Terbaru dulu
        } else {
          return dateA - dateB; // Terlama dulu
        }
      });

      // Filter by search term
      let filteredRows = rowsToSort.filter(function() {
        const text = $(this).text().toLowerCase();
        return text.includes(searchTerm);
      });

      // Calculate pagination
      const totalItems = filteredRows.length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      
      // Validate current page
      if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
      }
      if (currentPage < 1) {
        currentPage = 1;
      }

      // Get rows for current page
      const startIdx = (currentPage - 1) * itemsPerPage;
      const endIdx = startIdx + itemsPerPage;
      const pageRows = filteredRows.slice(startIdx, endIdx);

      // Append rows to table
      pageRows.forEach(row => {
        table.append($(row).clone(true));
      });

      // Update pagination info
      if (totalItems === 0) {
        $('#pagingInfo').text('0-0');
      } else {
        const displayStart = startIdx + 1;
        const displayEnd = Math.min(endIdx, totalItems);
        $('#pagingInfo').text(displayStart + '-' + displayEnd);
      }
      $('#totalInfo').text(totalItems);

      // Update button states
      $('#prevBtn').prop('disabled', currentPage === 1);
      $('#nextBtn').prop('disabled', currentPage >= totalPages || totalItems === 0);
    }

    // Pagination controls
    $('#prevBtn').on('click', function() {
      if (currentPage > 1) {
        currentPage--;
        renderTable();
      }
    });

    $('#nextBtn').on('click', function() {
      const totalItems = $('#TicketTable tbody tr').length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        renderTable();
      }
    });

    // Sorting
    $('#sortDropdown a').on('click', function(e) {
      e.preventDefault();
      sortOrder = $(this).data('sort');
      currentPage = 1; // Reset to first page
      renderTable();
    });

    // Search functionality
    $('#search').on('keyup', function() {
      searchTerm = this.value.toLowerCase();
      currentPage = 1; // Reset to first page
      renderTable();
    });

    // Initialize table on load
    setTimeout(function() {
      initTable();
    }, 100);
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
    const modal = document.getElementById('exampleModalMessage');
    modal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const ticketId = button.getAttribute('data-ticket-id');
      const ticketSubject = button.getAttribute('data-ticket-subject');
      const ticketJenis = button.getAttribute('data-ticket-jenis');
      const ticketLokasi = button.getAttribute('data-ticket-lokasi');
      const ticketDetail = button.getAttribute('data-ticket-detail');

      const form = modal.querySelector('#editTicketForm');
      form.action = `/tickets/${ticketId}`;
      form.querySelector('#ticketId').value = ticketId;
      form.querySelector('#subject').value = ticketSubject;
      form.querySelector('#Jenis_Pengaduan').value = ticketJenis;
      form.querySelector('#Lokasi').value = ticketLokasi;
      form.querySelector('#Detail').value = ticketDetail;
    });
  });
</script>
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
    <div class="card z-index-2 h-100 d-flex flex-column">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Ticket list</h6>
      </div>
      <div class="card-body px-0 pt-0 pb-2 h-500">
        @if($data_ticket->isEmpty())
        <div class="table-responsive margin-right: 15px; position: relative;" style="height: 400px; max-height: 400px; overflow-y: auto;">
          <!-- Add your button here -->
          <a href="{{ route('customer.tickets') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Buat Tiket</a>
        </div>
        @else
        <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
          <table class="table align-items-center mb-0">
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
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-s text-limit-35" title="Subject">
                        <a href="{{ route('viewtickets.index', ['id' => $dataticket->id]) }}">
                          {{ $dataticket->subject }}
                        </a>
                      </h6>
                      <div class="d-flex list-inline">
                        <li class="text-xs list-inline-item text-secondary"><i class="fa fa-circle fa-xs text-danger"></i>#CT-{{ $dataticket->id }}</li>
                        <li class="text-xs list-inline-item text-secondary" title="type"><i class="fa fa-circle fa-xs text-primary"></i>{{ $dataticket->Jenis_Pengaduan }}</li>
                        <li class="text-xs list-inline-item text-secondary" title="Created Date"><i class="fa fa-circle fa-xs text-secondary"></i></i> {{ $dataticket->formattedTanggalPengaduan }}</li>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="align-middle text-center text-sm">
                  <x-status-badge :status="$dataticket->status" />
                </td>
                <td class="align-middle text-center text-limit-30">
                  <span class="text-secondary text-xs font-weight-bold ">{{ $dataticket->Detail }}</span>
                </td>
                <!-- "Edit" button within a dropdown -->
                <td class="align-middle text-center">
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
        @endif
      </div>
    </div>

  </div>
  <div class="col-lg-4 ms-auto">
    <div class="card card-carousel overflow-hidden h-100 p-0 ">
      <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner border-radius-lg h-100">
          <div class="carousel-item h-100 active" style="background-image: url('style/assets/img/shintaeyong.jpg');
      background-size: cover; background-position: center;">
            <a href="https://bali.tribunnews.com/2024/01/28/piala-asia-peringatan-shin-tae-yong-soal-dampak-komentar-buruk-yang-berimbas-ke-mental-skuad-tim#:~:text=TRIBUN-BALI.COM%20-%20Head%20coach%20Shin%20Tae-yong%20mengingatkan%20untuk,knock%20out%20Piala%20Asia%2C%20Sabtu%2027%20Januari%202024." target="_blank" style="display: block; height: 100%;">
              <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                  <i class="ni ni-camera-compact text-dark opacity-10"></i>
                </div>
                <h5 class="text-white mb-1">Piala Asia:</h5>
                <p>Peringatan Shin Tae-yong Soal Dampak Komentar Buruk yang Berimbas ke Mental & Skuad Tim...</p>
              </div>
            </a>
          </div>
          <div class="carousel-item h-100" style="background-image: url('style/assets/img/pemilih-pemula-ikut-sosialiasi-pemilu.jpg');
      background-size: cover; background-position: center;">
            <a href="https://www.detik.com/sumut/berita/d-7163376/pemilih-pemula-wajib-tahu-begini-cara-mencoblos-di-pemilu-2024" target="_blank" style="display: block; height: 100%;">
              <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                  <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                </div>
                <h5 class="text-white mb-1">Cara Mencoblos di Pemilu 2024, Pemilih Pemula Wajib Tahu!</h5>
                <p>Sebagai pemilih pemula, salah satu hal yang menjadi kendala dalam memilih di pemilu adalah cara mencoblos...</p>
              </div>
            </a>
          </div>
          <div class="carousel-item h-100" style="background-image: url('style/assets/img/rapats.jpg');
      background-size: cover; background-position: center;">
            <a href="https://www.detik.com/sumut/berita/d-7163376/pemilih-pemula-wajib-tahu-begini-cara-mencoblos-di-pemilu-2024" target="_blank" style="display: block; height: 100%;">
              <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                  <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                </div>
                <h5 class="text-white mb-1">Pembahasan Rencana Kegiatan Tahun 2025 di DPRD Kota Semarang</h5>
                <p>umat, 3 Januari 2025, DPRD Kota Semarang mengadakan rapat pembahasan rencana kegiatan...</p>
              </div>
            </a>
          </div>
        </div>
        <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
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
        </div>
      </div>
    </div>
  </div>



</div>
<div class="row mt-4">

</div>

</div>
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
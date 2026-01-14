@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.admnav')
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

<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-8">
                    <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Current Ticket</p>
                        <h5 class="font-weight-bolder">
                            {{ $totalTickets  }}
                        </h5>
                        <p class="mb-0">
                        </p>
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
<div class="col-xl-3 col-sm-6">
    <div class="card">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-8">
                    <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">All Ticket</p>
                        <h5 class="font-weight-bolder">
                            {{ $totalAllTickets }}
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
                            <tr class="align-middle text-sm border border-light">
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
                                <td class="align-middle text-center text-sm">
                                    <x-status-badge :status="$teknisidataticket->status" />
                                </td>
                                <td class="align-middle text-center text-limit-30 border border-light">
                                    <span class="text-secondary text-xs font-weight-bold ">{{ $teknisidataticket->Detail }}</span>
                                </td>
                                <td class="align-middle text-center text-sm border border-light">
                                    <form action="{{ route('tickets.assign', $teknisidataticket->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Cek apakah tiket sudah memiliki asignees -->
                                        @if($teknisidataticket->asignees->isEmpty())
                                        <!-- Jika belum di-assign oleh siapapun -->
                                        <button type="submit" class="btn btn-sm btn-transparent text-primary">Assign</button>
                                        @elseif($teknisidataticket->asignees->first()->id == Auth::id())
                                        <!-- Jika sudah di-assign ke teknisi yang sedang login -->
                                        <button type="submit" class="btn btn-sm btn-outline-warning text-secondary">Re-assign</button>
                                        @else
                                        <!-- Jika sudah di-assign oleh teknisi lain -->
                                        <button type="submit" class="btn btn-sm btn-outline-success text-primary">Contribute</button>
                                        @endif
                                    </form>
                                </td>
                                <!-- "Edit" button within a dropdown -->
                                <td class="align-middle text-center ">
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
                @endif
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
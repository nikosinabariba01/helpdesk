@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.teknav6')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Escalation</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Escalation Queue</h6>
</nav>
@endsection
@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Escalated Ticket</h6>
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
                <a href="{{ route('teknisi.index') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">assign ticket</a>
            </div>
            @else
            <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <table class="table align-items-center mb-0" id="escalationTable">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="true">subject</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="true">User</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="false">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="false">Deskripsi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="false">Asign</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;" data-orderable="false">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teknisi_data_ticket as $teknisidataticket)
                        <tr>
                            <td class="align-middle text-center text-sm border border-light">
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-s text-limit-35" title="Subject">
                                            <a href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                {{ $teknisidataticket->subject }}
                                            </a>
                                        </h6>

                                        <div class="d-flex list-inline">
                                            <li class="text-xs list-inline-item text-secondary"><i class="fa fa-circle fa-xs text-danger"></i>#CT-{{ $teknisidataticket->id }}</li>
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
                                <!-- Tombol untuk Pemilik, hanya akan muncul "Accept Escalation" jika status tiket adalah "escalated" -->
                                @if($teknisidataticket->status == 'escalated')
                                <form action="{{ route('tickets.accept_escalation', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT') <!-- Menggunakan PUT karena kita akan memperbarui status tiket -->
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                                        <i class="fa fa-check pe-2 text-success"></i> Accept Escalation
                                    </button>
                                </form>
                                @else
                                <!-- Jika status bukan escalated, tidak ada tombol untuk pemilik -->
                                <span class="text-muted">No escalation required</span>
                                @endif
                            </td>
                            <!-- "Edit" button within a dropdown -->
                            <td class="align-middle text-center border border-light">
                                <div class="dropdown">
                                    <a class="btn btn-link" href="#" role="button" id="dropdownMenuLink" >
                                        <i class="fa fa-ellipsis-v fa-sm"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <a class="dropdown-item text-info" href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">
                                                <i class="fa fa-eye pe-2 text-info"></i>Detail
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}">
                                                @method('PUT')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" href="#" onclick="return confirm ('are you sure?')"><i class="fa fa-minus pe-2 text-danger"></i>close</button>
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

<script>
    $(document).ready(function() {
        var table = $('#escalationTable').DataTable({
            searching: true, // Tetap mengaktifkan pencarian
            ordering: false, // Menonaktifkan tombol sortir untuk semua kolom kecuali subject dan user
            paging: false, // Menonaktifkan pagination
            lengthChange: false, // Menonaktifkan dropdown jumlah entri
            info: false, // Menonaktifkan informasi tabel seperti "Showing 1 to 10 of 50 entries"
            columnDefs: [
                {
                    targets: [2, 3, 4, 5], // Menonaktifkan tombol sortir untuk kolom lainnya
                    orderable: false
                }
            ]
        });

        // Menyembunyikan elemen pencarian default DataTables
        $('#escalationTable_filter').hide(); // Menyembunyikan kolom pencarian default
        $('#escalationTable_length').hide(); // Menyembunyikan opsi "Show Entries"
        $('#escalationTable_paginate').hide(); // Menyembunyikan pagination

        // Menambahkan pencarian kustom menggunakan id "search"
        $('#search').on('keyup', function() {
            table.search(this.value).draw(); // Menyaring berdasarkan nilai input pencarian
        });
    });
</script>


@endsection

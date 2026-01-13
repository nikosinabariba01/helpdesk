@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.teknav2')
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
            @if($teknisi_data_ticket->isEmpty())
            <div class="table-responsive margin-right: 15px; position: relative;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <!-- Add your button here -->
                <a href="{{ route('teknisi.index') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">assign ticket</a>
            </div>
            @else
            <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <table class="table align-items-center mb-0">
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
                            <td>
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
                            <td class="align-middle text-center text-sm">
                                <!-- Cek apakah tiket sudah diassign oleh pemilik -->
                                @if($teknisidataticket->asignees->where('role', 'pemilik')->isEmpty())
                                <!-- Jika belum ada pemilik yang meng-assign, tampilkan tombol cancel assign -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-secondary">Cancel Assign</button>
                                </form>
                                <!-- Tombol Request Follow-up jika tiket belum escalated -->
                                <form method="POST" action="{{ $teknisidataticket->status == 'escalated' ? route('ticketsteknisi.cancelRequestFollowUp', $teknisidataticket->id) : route('ticketsteknisi.requestFollowup', $teknisidataticket->id) }}">
                                    @csrf
                                    @if($teknisidataticket->status == 'escalated')
                                    @method('PUT') <!-- Menggunakan PUT karena kita akan memperbarui status tiket -->
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-danger">
                                        <i class="fa fa-times pe-2 text-danger"></i>Cancel Request
                                    </button>
                                    @else
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                                        <i class="fa fa-refresh pe-2 text-success"></i>Request Follow-up
                                    </button>
                                    @endif
                                </form>
                                @else
                                <!-- Jika sudah ada pemilik yang meng-assign, hanya tampilkan tombol Cancel Assign -->
                                <form action="{{ route('tickets.cancel_assign', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-transparent text-secondary">Cancel Assign</button>
                                </form>
                                @endif
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
        $('#escalationTable_filter').hide();
        $('#escalationTable_length').hide();
        $('#escalationTable_paginate').hide();

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
        });
    });
</script>

@endsection
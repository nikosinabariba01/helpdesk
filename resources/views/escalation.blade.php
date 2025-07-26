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
                <!-- Kolom Pencarian -->
                <label for="search" class="text-white me-2">Search:</label>
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Search">
                <!-- Tombol Sortir -->
                <button class="btn btn-outline-light ms-2" data-bs-toggle="dropdown">
                    <i class="fa fa-sort"></i> Sort
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Sort by Name</a></li>
                    <li><a class="dropdown-item" href="#">Sort by Date</a></li>
                </ul>
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
                            <td>{{ $teknisidataticket->subject }}</td>
                            <td class="text-center">{{ $teknisidataticket->user->name }}</td>
                            <td class="text-center"><x-status-badge :status="$teknisidataticket->status" /></td>
                            <td class="text-center">{{ $teknisidataticket->Detail }}</td>
                            <td class="text-center">
                                @if($teknisidataticket->status == 'escalated')
                                <form action="{{ route('tickets.accept_escalation', $teknisidataticket->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                                        <i class="fa fa-check pe-2 text-success"></i> Accept Escalation
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">No escalation required</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <a class="btn btn-link" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v fa-sm"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item text-info" href="{{ route('viewticketteknisi.index', ['id' => $teknisidataticket->id]) }}">Detail</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('ticketsteknisi.close', $teknisidataticket->id) }}">
                                                @method('PUT')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" href="#" onclick="return confirm ('are you sure?')">close</button>
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
        // Inisialisasi DataTables
        $('#escalationTable').DataTable();
    });
</script>
@endsection

@extends('mainlayout.layout')

@section('navbar')
@include('mainlayout.navbar.teknav')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage Pengumuman</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Manage Pengumuman</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header d-flex justify-content-between align-items-center pb-3">
            <h5 class="mb-2">Daftar Pengumuman</h5>
            <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">Buat Pengumuman</a>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">ID</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Judul Pengumuman</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Deskripsi</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Pembuat</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengumuman as $item)
                    <tr style="border-bottom: 1px solid #e3e6f0;">
                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <span class="text-secondary text-xs font-weight-bold">{{ $item->id }}</span>
                        </td>
                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <span class="text-secondary text-xs font-weight-bold">{{ $item->judul }}</span>
                        </td>
                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <span class="text-secondary text-xs font-weight-bold">{{ Str::limit($item->deskripsi, 50) }}</span>
                        </td>
                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <span class="text-secondary text-xs font-weight-bold">{{ $item->creator->name }}</span>
                        </td>
                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <div class="dropdown">
                                <a class="btn text-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class=""></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink{{ $item->id }}">
                                    <li>
                                        <a class="dropdown-item text-info" href="{{ route('pengumuman.edit', $item->id) }}">
                                            <i class="fa fa-edit pe-2 text-info"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('pengumuman.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                <i class="fa fa-trash text-danger pe-2"></i>Delete
                                            </button>
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
    </div>
</div>
@endsection
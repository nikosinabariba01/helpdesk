@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.admnav2')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage User</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Manage</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <div>
                <div>
                    <h6 class="mb-1 p-1">Manage User</h6>
                </div>
                <div class="mt-3">
                    <a href="{{ route('user.create') }}" class="btn btn-primary">Create User</a>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2 h-500">
            @if($users->isEmpty())
            <div class="table-responsive" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <a href="{{ route('user.create') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Create New User</a>
            </div>
            @else
            <div class="table-responsive" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Email</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Role</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr style="border-bottom: 1px solid #e3e6f0;">
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->id }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->name }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->email }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->role }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <div class="dropdown">
                                    <a class="btn text-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class=""></i> <!-- Three dots vertical -->
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink{{ $user->id }}">
                                        <li>
                                            <a class="dropdown-item text-info" href="{{ route('admin.EditUser', $user->id) }}">
                                                <i class="fa fa-edit pe-2 text-info"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')">
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

            @endif
        </div>
    </div>
</div>
@endsection
@if (Auth::user()->role == 'admin')

    <ul class="navbar-nav ms-auto d-flex justify-content-end gap-2">
        {{-- PROFILE (ADMIN) --}}
        <li class="nav-item dropdown pe-0 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="profileDropdownAdmin"
                data-bs-toggle="dropdown" aria-expanded="false">

                <span class="d-none d-sm-inline-block me-2">
                    Hi! {{ Auth::user()->name }}
                </span>

                <img src="{{ Auth::user()->profile_photo
                    ? route('profile.photo', ['filename' => basename(Auth::user()->profile_photo)])
                    : asset('default-profile.png') }}"
                    alt="Profile Photo" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
            </a>

            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="profileDropdownAdmin">
                <li class="mb-2">
                    <a class="dropdown-item" href="{{ route('teknisi.profile') }}">
                        <img src="/style/assets/img/setting.png" /> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                        <img src="/style/assets/img/user-logout.png" /> Logout
                    </a>
                </li>
            </ul>
        </li>

        {{-- TOGGLER --}}
        <li class="nav-item d-xl-none ps-1 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </a>
        </li>
    </ul>
@elseif(Auth::user()->role == 'pengurus' || Auth::user()->role == 'pemilik')
    <ul class="navbar-nav ms-auto d-flex justify-content-end gap-2">
        {{-- NOTIF (PENGURUS/PEMILIK) --}}
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-white p-0" id="notifDropdownUser" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="notifDropdownUser">

                @if ($latestComments && $latestComments->isNotEmpty())
                    @foreach ($latestComments as $comment)
                        <li class="mb-2">
                            <a class="dropdown-item text-info"
                                href="{{ route('viewticketteknisi.index', ['id' => $comment->ticket_id]) }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="{{ $comment->user->profile_photo
                                            ? route('profile.photo', ['filename' => basename($comment->user->profile_photo)])
                                            : asset('default-profile.png') }}"
                                            class="avatar avatar-sm me-3" alt="{{ $comment->user->name }}">
                                    </div>

                                    <div class="d-flex flex-column justify-content-start">
                                        <h7 class="text-sm font-weight-bold mb-1 text-truncate"
                                            style="max-width: 250px;">
                                            {{ $comment->ticket->subject }}
                                        </h7>
                                        <p class="text-xs text-secondary mb-1">
                                            <span class="font-weight-normal">New message</span> from
                                            {{ $comment->user->name }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa-solid fa-clock"></i>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="mb-2">
                        <span class="text-secondary">Belum ada pesan yang diterima</span>
                    </li>
                @endif
            </ul>
        </li>

        {{-- PROFILE (PENGURUS/PEMILIK) --}}
        <li class="nav-item dropdown pe-0 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="profileDropdownUser"
                data-bs-toggle="dropdown" aria-expanded="false">

                <span class="d-none d-sm-inline-block me-2">
                    Hi! {{ Auth::user()->name }}
                </span>

                <img src="{{ Auth::user()->profile_photo
                    ? route('profile.photo', ['filename' => basename(Auth::user()->profile_photo)])
                    : asset('default-profile.png') }}"
                    alt="Profile Photo" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
            </a>

            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="profileDropdownUser">
                <li class="mb-2">
                    <a class="dropdown-item" href="{{ route('teknisi.profile') }}">
                        <img src="/style/assets/img/setting.png" /> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                        <img src="/style/assets/img/user-logout.png" /> Logout
                    </a>
                </li>
            </ul>
        </li>

        {{-- TOGGLER --}}
        <li class="nav-item d-xl-none ps-1 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </a>
        </li>
    </ul>
@else
    <p>User role not recognized.</p>
@endif

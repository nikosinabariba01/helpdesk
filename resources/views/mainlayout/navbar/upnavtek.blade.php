<ul class="navbar-nav ms-auto"> <!-- ms-auto will auto-align the items to the right -->
    <li class="nav-item dropdown pe-2 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell cursor-pointer"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <!-- Your dropdown menu content here -->
        </ul>
    </li>
    <li class="nav-item dropdown pe-0 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="d-none d-sm-inline-block me-2">
                Hi! {{ Auth::user()->name }}
            </span>
            <img src="{{ Auth::user()->profile_photo ? asset('storage/public/' . Auth::user()->profile_photo) : asset('default-profile.png') }}"
                alt="Profile Photo"
                class="rounded-circle"
                style="width: 30px; height: 30px; object-fit: cover;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 " aria-labelledby="dropdownMenuButton">
            <!-- Your dropdown menu content here -->
        </ul>
    </li>
    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
            </div>
        </a>
    </li>
</ul>
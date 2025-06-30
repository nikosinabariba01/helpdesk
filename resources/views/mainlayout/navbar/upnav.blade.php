          <ul class="navbar-nav  justify-content-end">
              <li class="nav-item dropdown pe-0 d-flex align-items-center">
                  <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <!-- Teks "Hi!" dengan Nama Pengguna -->
            <span class="d-none d-sm-inline-block me-2">
                Hi! {{ Auth::user()->name }}
            </span>
            <!-- Gambar Profil -->
            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('default-profile.png') }}" 
                alt="Profile Photo" 
                class="rounded-circle" 
                style="width: 30px; height: 30px; object-fit: cover;">
        </a>
                  <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 " aria-labelledby="dropdownMenuButton">
                      <li class="mb-2">
                          <a class="dropdown-item text-dark" href="{{route('customer.profile')}}">
                              <img src="/style/assets/img/setting.png" class="text-dark" /> Profile
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item text-warning" href="{{ route('logout') }}">
                              <img src="/style/assets/img/user-logout.png" /> Logout
                          </a>
                      </li>
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
<div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
  @if(Auth::user()->role == 'pemilik')
  <ul class="navbar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.index' ? 'active' : '' }}" href="{{route('teknisi.index')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(94,114,228,1);">
          <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Dashboard</span>
      </a>
    </li>
    
    <!-- Escalation Queue -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'tickets.viewEscalation' ? 'active' : '' }}" href="{{route('tickets.viewEscalation')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(245,158,11,1);">
          <i class="ni ni-flag text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Escalation Queue</span>
      </a>
    </li>
    
    <!-- Assigned Ticket (Proceeding) -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.viewasigne' ? 'active' : '' }}" href="{{route('teknisi.viewasigne')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(94,114,228,1);">
          <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Assigned Ticket</span>
      </a>
    </li>
    
    <!-- Closed Ticket -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.closeticket' ? 'active' : '' }}" href="{{route('teknisi.closeticket')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(255,0,0,1);">
          <i class="ni ni-archive text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Closed Ticket</span>
      </a>
    </li>
    
    <!-- Ticket List -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.ListTicket' ? 'active' : '' }}" href="{{route('teknisi.ListTicket')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(46,204,113,1);">
          <i class="ni ni-app text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Ticket List</span>
      </a>
    </li>
    
    <!-- Manage Pengumuman -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'pengumuman.index' ? 'active' : '' }}" href="{{route('pengumuman.index')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(155, 89, 182, 1);">
          <i class="ni ni-bell-55 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Manage Pengumuman</span>
      </a>
    </li>
  </ul>
  @elseif(Auth::user()->role == 'pengurus')
  <ul class="navbar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.index' ? 'active' : '' }}" href="{{route('teknisi.index')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(94,114,228,1);">
          <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Dashboard</span>
      </a>
    </li>
    
    <!-- Proceeding Ticket -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.viewasigne' ? 'active' : '' }}" href="{{route('teknisi.viewasigne')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(245,158,11,1);">
          <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Proceeding Ticket</span>
      </a>
    </li>
    
    <!-- Closed Ticket -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.closeticket' ? 'active' : '' }}" href="{{route('teknisi.closeticket')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(255,0,0,1);">
          <i class="ni ni-archive text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Closed Ticket</span>
      </a>
    </li>
    
    <!-- Ticket List -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'teknisi.ListTicket' ? 'active' : '' }}" href="{{route('teknisi.ListTicket')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(46,204,113,1);">
          <i class="ni ni-app text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Ticket List</span>
      </a>
    </li>
    
    <!-- Manage Pengumuman -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'pengumuman.index' ? 'active' : '' }}" href="{{route('pengumuman.index')}}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(155, 89, 182, 1);">
          <i class="ni ni-bell-55 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Manage Pengumuman</span>
      </a>
    </li>
  </ul>
  @elseif (Auth::user()->role == 'admin')
  <ul class="navbar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'admin.index' ? 'active' : '' }}" href="{{ route('admin.index') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(94,114,228,1);">
          <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Dashboard</span>
      </a>
    </li>
    
    <!-- Manage User -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'admin.manageuser' ? 'active' : '' }}" href="{{ route('admin.manageuser') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(52, 73, 94, 1);">
          <i class="ni ni-single-02 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Manage User</span>
      </a>
    </li>
  </ul>
  @elseif (Auth::user()->role == 'penyewa')
  <ul class="navbar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'customer.index' ? 'active' : '' }}" href="{{ route('customer.index') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(94,114,228,1);">
          <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Dashboard</span>
      </a>
    </li>
    
    <!-- Create Ticket -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'customer.tickets' ? 'active' : '' }}" href="{{ route('customer.tickets') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(46,204,113,1);">
          <i class="ni ni-fat-add text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Create Ticket</span>
      </a>
    </li>
    
    <!-- My Ticket -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'customer.viewprocess' ? 'active' : '' }}" href="{{ route('customer.viewprocess') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(245,158,11,1);">
          <i class="ni ni-bullet-list-67 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">My Ticket</span>
      </a>
    </li>
    
    <!-- Profile -->
    <li class="nav-item">
      <a class="nav-link {{ Route::currentRouteName() == 'customer.profile' ? 'active' : '' }}" href="{{ route('customer.profile') }}">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center" style="background: rgba(52, 73, 94, 1);">
          <i class="ni ni-single-02 text-white text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">Profile</span>
      </a>
    </li>
  </ul>
  @else
  <p>User role not recognized.</p>
  @endif
</div>
@extends('mainlayout.layout')

@section('navbar')
@include('mainlayout.navbar.nav')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage Pengumuman</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Announcements</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header d-flex justify-content-between align-items-center pb-3">
            <h5 class="mb-2">Announcements List</h5>
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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Deskripsi</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Pembuat</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Penerima</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Aksi</th>
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
                            <!-- Menampilkan penerima: jika semua penyewa menerima pengumuman, tampilkan "everyone" -->
                            @if($item->penerima_text == 'everyone')
                            <span class="text-secondary text-xs font-weight-bold">everyone</span>
                            @else
                            @foreach ($item->penerima as $penerima)
                            <span class="text-secondary text-xs font-weight-bold">{{ $penerima->name }}</span><br>
                            @endforeach
                            @endif
                        </td>

                        <td class="align-middle text-center text-sm" style="padding: 10px;">
                            <div class="dropdown">
                                <a class="btn text-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class=""></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink{{ $item->id }}">
                                    <li>
                                        <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#viewAnnouncementModal" 
                                           data-pengumuman-id="{{ $item->id }}"
                                           data-pengumuman-judul="{{ $item->judul }}"
                                           data-pengumuman-deskripsi="{{ $item->deskripsi }}"
                                           data-creator-name="{{ $item->creator->name }}"
                                           data-creator-role="{{ $item->creator->role }}"
                                           data-creator-photo="{{ $item->creator && $item->creator->profile_photo ? route('profile.photo', ['filename' => basename($item->creator->profile_photo)]) : asset('default-profile.png') }}"
                                           data-created-at="{{ $item->created_at }}">
                                            <i class="fa fa-eye pe-2 text-primary"></i>View
                                        </a>
                                    </li>
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

<!-- View Announcement Modal -->
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="viewAnnouncementModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center w-100">
          <img id="viewCreatorPhoto" src="" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 12px;">
          <div class="flex-grow-1">
            <h6 id="viewCreatorName" class="mb-0 text-dark" style="font-size: 14px; font-weight: 600;"></h6>
            <small id="viewCreatorRole" class="text-muted" style="font-size: 11px;"></small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="modal-body pt-2">
        <h5 id="viewJudul" class="text-dark mb-3" style="font-size: 18px; font-weight: 600;"></h5>
        <small id="viewCreatedAt" class="text-muted d-block mb-3" style="font-size: 12px;"></small>
        <p id="viewDeskripsi" class="text-muted" style="font-size: 14px; line-height: 1.6;"></p>
      </div>
      <div class="modal-footer border-0 pt-0">
        <small id="viewTimeAgo" class="text-muted me-auto" style="font-size: 12px;"></small>
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewAnnouncementModal = document.getElementById('viewAnnouncementModal');
    viewAnnouncementModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const judul = button.getAttribute('data-pengumuman-judul');
      const deskripsi = button.getAttribute('data-pengumuman-deskripsi');
      const creatorName = button.getAttribute('data-creator-name');
      const creatorRole = button.getAttribute('data-creator-role');
      const creatorPhoto = button.getAttribute('data-creator-photo');
      const createdAt = button.getAttribute('data-created-at');

      document.getElementById('viewJudul').textContent = judul;
      document.getElementById('viewDeskripsi').textContent = deskripsi;
      document.getElementById('viewCreatorName').textContent = creatorName;
      document.getElementById('viewCreatorRole').textContent = creatorRole;
      document.getElementById('viewCreatorPhoto').src = creatorPhoto;
      document.getElementById('viewCreatedAt').textContent = new Date(createdAt).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      document.getElementById('viewTimeAgo').textContent = moment(createdAt).fromNow();
    });
  });
</script>
@endsection
@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav6')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Detail Ticket</li>
  </ol>
  <h6 class="font-weight-bolder text-white mb-0">Detail Ticket</h6>
</nav>
@endsection

@section('container')

<div class="col-lg-9">
  <div class="row">
    <div class="col-md-12 mb-lg-0 mb-4">
      <div class="card h-100 mb-6">
        <div class="card-header pb-0 px-3">
          <div class="row">
            <div class="col-md-10">
              <h4 class="mb-0">{{ $ticket->subject }}</h4>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
              <i class="far fa-calendar-alt me-2"></i>
              <small>{{ $ticket->Tanggal_Pengaduan }}</small>
            </div>
          </div>
        </div>
        <div class="card-body pt-2 p-3">
          <div class="d-flex align-items-center">
            <div class="d-flex flex-column">
              <span class="mb-1 text-muted text-sm font-weight-normal">{{ $ticket->Detail }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 mb-lg-0 mb-3">
      <div class="card mt-4">
        @if (auth()->user()->telegram_chat_id)
        <!-- Tombol Add Comment -->
        <button id="toggleCommentForm" class="btn btn-sm btn-outline-danger btn-transparent text-danger rounded-pill">
          Add Comment
        </button>
        <!-- Comment Form -->
        <form id="commentForm" action="{{ route('comments.store', ['ticket' => $ticket]) }}" method="POST" style="display: none;">
          @csrf
          <div class="mb-3">
            <label for="commentText" class="form-label">Your Comment</label>
            <textarea class="form-control" name="commentText" id="commentText" rows="4"></textarea>
          </div>
          <button type="submit" id="submitComment" class="btn btn-primary" disabled>Submit</button>
        </form>
        @else
        <div class="d-flex flex-column align-items-center justify-content-center py-4">
          <p class="text-danger mb-2">
            <i class="fab fa-telegram-plane"></i>
            Untuk dapat berkomentar, silakan hubungkan akun Telegram Anda terlebih dahulu.
          </p>
          <!-- Widget Telegram Login -->
          <div id="telegram-login-widget"></div>
        </div>
        @endif

        <div class="card-header pb-0 p-3">
          <div class="row">
            <div class="col">
              <div class="d-flex align-items-center">
                <h6 class="mb-0">Comment</h6>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          <hr class="my-0">
          <ul class="list-group">
            @foreach($ticket->comments as $comment)
            <li class="list-group-item mt-0 d-flex align-items-start">
              <img
                src="{{ $comment->user && $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : asset('default-profile.png') }}"
                alt="Profile Photo"
                class="rounded-circle me-3"
                style="width: 40px; height: 40px; object-fit: cover;">
              <div style="flex-grow: 1;">
                <div class="d-flex justify-content-between align-items-center">
                  <span
                    class="fw-bold @if ($comment->user && $comment->user->role == 'penyewa') text-primary @elseif ($comment->user && in_array($comment->user->role, ['pengurus', 'admin', 'pemilik'])) text-danger 
                    @else text-muted 
                    @endif">
                    {{ $comment->user ? $comment->user->name : 'User Not Found' }}
                  </span>
                  <span class="text-muted small">{{ $comment->created_at->format('Y-m-d') }}</span>
                </div>
                <p class="mb-0">{{ $comment->comment }}</p>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-lg-3 ms-auto">
  <div class="card h-100" style="max-height: 300px;">
    <div class=" pb-0 p-3">
      <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-center">
          <h6 class="mb-0 ">Ticket Information</h6>
        </div>
      </div>
    </div>
    <div class="card-body p-3 pb-0">
      <ul class="list-group">
        <div class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
          <div class="d-flex flex-column">
            <h6 class="mb-2 text-dark font-weight-bold text-sm">{{ $userName }}</h6>
            <span class="text-xs">#sp-{{ $ticket->id }}</span>
          </div>
        </div>
        <div class="d-flex flex-column">
          <h7 class="mb-3 text-sm">{{ \Carbon\Carbon::parse($ticket->Tanggal_Pengaduan)->format('d F Y') }}</h7>
          <span class="mb-3 text-xs">Jenis Penganduan: <span class="text-dark font-weight-bold ms-sm-0">{{ $ticket->Jenis_Pengaduan }}</span></span>
          <span class="mb-3 text-xs">Lokasi: <span class="text-dark ms-sm-0 font-weight-bold">{{ $ticket->Lokasi }}</span></span>
          <span class="mb-3 text-xs">Status: <x-status-badge :status="$ticket->status" /></span>
          <span class="text-xs">
            assigned by:
            <span class="{{ $ticket->asignee ? 'text-dark' : 'text-danger' }} ms-sm-0 font-weight-bold">
              {{ $ticket->asignee->name ?? 'not taken yet!' }}
            </span>
          </span>
        </div>
        <a href="{{ route('tickets.downloadImage', ['ticket' => $ticket->id]) }}" class="btn btn-link text-dark text-sm mb-0 px-0  hover:scale-200" style="margin-top: -6px;">
          <i class="fas fa-file-pdf me-1"></i> <span style="color: blue;">Download file</span>
        </a>
        <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="kos74_bot" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
<script type="text/javascript">
  function onTelegramAuth(user) {
    alert('Logged in as ' + user.first_name + ' ' + user.last_name + ' (' + user.id + (user.username ? ', @' + user.username : '') + ')');
  }
</script>
      </ul>
    </div>
  </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $("#toggleCommentForm").click(function() {
      $("#commentForm").slideToggle();
    });

    $("#commentText").on("input", function() {
      const comment = $(this).val().trim();
      if (comment.length > 0) {
        $("#submitComment").prop("disabled", false).removeClass("btn-secondary").addClass("btn-primary");
      } else {
        $("#submitComment").prop("disabled", true).removeClass("btn-primary").addClass("btn-secondary");
      }
    });
  });

  @if(!auth()->user()->telegram_chat_id)
  // Render widget hanya jika user belum authorize
  document.addEventListener('DOMContentLoaded', function() {
    let tgWidget = document.createElement('script');
    tgWidget.async = true;
    tgWidget.src = 'https://telegram.org/js/telegram-widget.js?7';
    tgWidget.setAttribute('data-telegram-login', 'kos74_bot'); // GANTI username bot!
    tgWidget.setAttribute('data-size', 'large');
    tgWidget.setAttribute('data-userpic', 'false');
    tgWidget.setAttribute('data-request-access', 'write');
    tgWidget.setAttribute('data-on-auth', 'onTelegramAuth');
    document.getElementById('telegram-login-widget').appendChild(tgWidget);
  });
  @endif

  function onTelegramAuth(user) {
    fetch('/telegram/auth', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(user)
    }).then(response => location.reload());
  }
</script>

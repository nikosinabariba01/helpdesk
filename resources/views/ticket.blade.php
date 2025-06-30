@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav2')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Ticket</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Ticket</h6>
</nav>
@endsection
@section('upnav')
@include('mainlayout.navbar.upnav')
@endsection

@section('container')
<div class="col-12">
    <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Add Ticket</h6>
        </div>

        <!-- ALERT SUCCESS -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <!-- END ALERT SUCCESS -->

        <div class="container">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group card">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control border-input" value="{{ old('subject') }}">
                            @error('subject')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group card">
                            <label for="Jenis_Pengaduan">Jenis Pengaduan</label>
                            <select id="Jenis_Pengaduan" name="Jenis_Pengaduan" class="form-control border-input">
                                <option value="" {{ old('Jenis_Pengaduan') == '' ? 'selected' : '' }}>--Pilih Jenis Pengaduan--</option>
                                <option value="perbaikan" {{ old('Jenis_Pengaduan') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="permintaan" {{ old('Jenis_Pengaduan') == 'permintaan' ? 'selected' : '' }}>Permintaan</option>
                            </select>
                            @error('Jenis_Pengaduan')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group card">
                            <label for="Lokasi">Alamat</label>
                            <input type="text" id="Lokasi" name="Lokasi" class="form-control border-input" value="{{ old('Lokasi') }}">
                            @error('Lokasi')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group card">
                            <label for="Detail">Deskripsi</label>
                            <textarea id="Detail" name="Detail" rows="5" class="form-control border-input" placeholder="Here can be your description">{{ old('Detail') }}</textarea>
                            @error('Detail')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="gambar">Gambar Pendukung</label>
                        <input class="form-control form-control-sm" id="gambar" name="gambar" type="file">
                        @error('gambar')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-left mt-4">
                        <button type="submit" class="btn btn-info btn-fill btn-wd">Submit ticket</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Tambahkan Script untuk Auto-close Alert -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('fade-out');
                alert.addEventListener('transitionend', () => alert.remove());
            }, 3000); // Auto-close dalam 3 detik
        }
    });
</script>
@endpush
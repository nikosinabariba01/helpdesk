@extends('mainlayout.layout')

@section('navbar')
@include('mainlayout.navbar.nav')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage Pengumuman / Edit Pengumuman</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Edit Pengumuman</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header d-flex justify-content-between align-items-center pb-3">
            <h5 class="mb-2">Edit Pengumuman</h5>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Baris untuk Judul Pengumuman dan Choices -->
                <div class="row mb-3">
                    <!-- Judul Pengumuman -->
                    <div class="col-md-6">
                        <label for="judul" class="form-label">Judul Pengumuman:</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" class="form-control">
                        @error('judul')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Penyewa (Choices) -->
                    <div class="col-md-6">
                        <label for="penyewa" class="form-label">Pilih Penyewa:</label>
                        <select class="form-control" name="penyewa[]" id="choices-button" placeholder="search or choice" multiple>
                            <option value="all">Pilih Semua</option>
                            @foreach($penyewa as $p)
                            <option value="{{ $p->id }}" @if(in_array($p->id, $selectedPenerima)) selected @endif>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('penyewa')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi Pengumuman -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Pengumuman:</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $pengumuman->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Pengumuman</button>
                    <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom CSS untuk menimpa gaya Choices.js -->
<style>
    .form-control {
        line-height: 2.2rem;
    }

    .choices__inner {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.4rem;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d2d6da;
        appearance: none;
        border-radius: 0.5rem;
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
    }
    
    /* Anda bisa menambahkan lebih banyak aturan untuk menimpa gaya lain dari Choices.js, jika diperlukan */
</style>

<script>
    // Inisialisasi Choices.js untuk dropdown dengan multiple pilihan
    const choices = new Choices('#choices-button', {
        removeItemButton: true, // Menambahkan tombol hapus pada tag
        duplicateItems: false, // Tidak memperbolehkan duplikat item
        searchEnabled: true, // Mengaktifkan pencarian
        placeholder: true, // Menampilkan placeholder
        delimiter: ', ', // Pembatas tag
        maxItemCount: -1, // Tidak membatasi jumlah tag
        addItems: true, // Memungkinkan menambah item baru dari input
        itemSelectText: '', // Menghilangkan teks "Select"
        shouldSort: false // Disable automatic sorting untuk mempertahankan urutan HTML
    });
</script>

@endsection

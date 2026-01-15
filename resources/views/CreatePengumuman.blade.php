@extends('mainlayout.layout')

@section('navbar')
@include('mainlayout.navbar.admnav2')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage Pengumuman / Create Pengumuman</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Create Pengumuman</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column">
        <div class="card-header d-flex justify-content-between align-items-center pb-3">
            <h5 class="mb-2">Create Pengumuman</h5>
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

            <form action="{{ route('pengumuman.store') }}" method="POST">
                @csrf
                <!-- Baris untuk Judul Pengumuman dan Choices -->
                <div class="row mb-3">
                    <!-- Judul Pengumuman -->
                    <div class="col-md-6">
                        <label for="judul" class="form-label">Judul Pengumuman:</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="form-control">
                        @error('judul')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Penyewa (Choices) -->
                    <div class="col-md-6">
                        <label for="penyewa" class="form-label">Pilih Penyewa:</label>
                        <select class="form-control" name="choices-button" id="choices-button" placeholder="Choose or enter something" multiple>
                            <option value="Choice 1">Brazil</option>
                            <option value="Choice 2">Bucharest</option>
                            <option value="Choice 3">London</option>
                            <option value="Choice 4">USA</option>
                        </select>
                        @error('penyewa')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi Pengumuman -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Pengumuman:</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Kirim Pengumuman</button>
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
        itemSelectText: '' // Menghilangkan teks "Select"
    });

    // Mendengarkan event ketika ada item yang dipilih
    choices.passedElement.addEventListener('change', function(event) {
        // Ambil semua nilai yang dipilih dalam dropdown
        const selectedValues = event.target.value;

        // Menampilkan nilai yang dipilih di input text sebagai tags
        document.getElementById('choices-tags').value = selectedValues.join(', ');
    });
</script>

@endsection
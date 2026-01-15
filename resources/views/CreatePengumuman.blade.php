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
            <h5 class="mb-2 ">Create Pengumuman</h5>
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
                <div>
                    <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Pengumuman:</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="form-control">
                    @error('judul')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Pengumuman:</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="penyewa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Penyewa:</label>
                    <div class="choices__list choices__list--dropdown is-filled" aria-expanded="false"><input type="text" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="false" placeholder="" aria-activedescendant="choices--choices-button-item-choice-3">
                        <div class="choices__list" role="listbox">
                            <div id="choices--choices-button-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Choice 1" data-select-text="Press to select" data-choice-selectable="" aria-selected="false">Brazil</div>
                            <div id="choices--choices-button-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Choice 2" data-select-text="Press to select" data-choice-selectable="" aria-selected="false">Bucharest</div>
                            <div id="choices--choices-button-item-choice-3" class="choices__item choices__item--choice is-selected choices__item--selectable is-highlighted" role="option" data-choice="" data-id="3" data-value="Choice 3" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">London</div>
                            <div id="choices--choices-button-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Choice 4" data-select-text="Press to select" data-choice-selectable="">USA</div>
                        </div>
                    </div>
                    @error('penyewa')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="choices-tags" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tags Penyewa:</label>
                    <input class="form-control" id="choices-tags" data-color="dark" type="text" value="{{ old('penyewa', '') }}" placeholder="Masukkan nama penyewa" />
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Kirim Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
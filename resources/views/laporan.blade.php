@section('container')

<div class="row">

    {{-- FILTER CARD --}}
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">

                {{-- FILTER TAHUN --}}
                <form method="GET" action="{{ route('laporan.index') }}" 
                      class="d-flex align-items-center gap-2">

                    <label class="mb-0 fw-bold">Tahun:</label>

                    <select name="year" class="form-select form-select-sm" style="width:120px;">
                        @foreach($years as $y)
                            <option value="{{ $y }}" 
                                {{ (int)$selectedYear === (int)$y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                        Tampilkan
                    </button>
                </form>

                {{-- DOWNLOAD PDF --}}
                <a href="{{ route('laporan.download', ['year' => $selectedYear]) }}" 
                   class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>

            </div>
        </div>
    </div>


    {{-- SUMMARY CARDS --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-start border-4 border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Tiket</h6>
                <h4 class="fw-bold">{{ $totalTickets ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <h6 class="text-muted">Close</h6>
                <h4 class="fw-bold text-success">{{ $totalClose ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-start border-4 border-warning">
            <div class="card-body">
                <h6 class="text-muted">On Process</h6>
                <h4 class="fw-bold text-warning">{{ $totalOnProcess ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-start border-4 border-danger">
            <div class="card-body">
                <h6 class="text-muted">Escalated</h6>
                <h4 class="fw-bold text-danger">{{ $totalEscalated ?? 0 }}</h4>
            </div>
        </div>
    </div>


    {{-- CLOSE RATE CARD --}}
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Close Rate</h6>

                @php
                    $safeCloseRate = min(100, max(0, $closeRate ?? 0));
                @endphp

                <h2 class="fw-bold text-primary">{{ $safeCloseRate }}%</h2>

                <div class="progress mt-3" style="height:10px;">
                    <div class="progress-bar bg-primary"
                         role="progressbar"
                         style="width: {{ $safeCloseRate }}%;">
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- JENIS PENGADUAN --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Permintaan</h6>
                <h3 class="fw-bold text-info">{{ $totalPermintaan ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Perbaikan</h6>
                <h3 class="fw-bold text-secondary">{{ $totalPerbaikan ?? 0 }}</h3>
            </div>
        </div>
    </div>


    {{-- KINERJA PENGURUS --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Kinerja Pengurus</h5>
            </div>
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Total Ditangani</th>
                                <th>Selesai</th>
                                <th>Escalated</th>
                                <th>Close Rate</th>
                                <th>Rata-rata Hari</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($kinerja as $k)
                            <tr>
                                <td class="fw-semibold">{{ $k['nama'] }}</td>
                                <td>{{ $k['total'] }}</td>
                                <td class="text-success fw-bold">{{ $k['close'] }}</td>
                                <td class="text-danger">{{ $k['escalated'] }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $k['close_rate'] }}%
                                    </span>
                                </td>
                                <td>{{ $k['avg_days'] }} hari</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada data kinerja pada tahun ini.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

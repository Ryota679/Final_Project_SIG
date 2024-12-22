@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Detail Laporan Bencana') }}</span>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Jenis Bencana -->
                    <div class="mb-3">
                        <label class="fw-bold">Jenis Bencana</label>
                        <p>{{ $report->disaster->name }}</p>
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-3">
                        <label class="fw-bold">Lokasi</label>
                        <p>{{ $report->location }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi</label>
                        <p>{{ $report->description }}</p>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="fw-bold">Status</label>
                        <p>
                            <span class="badge bg-{{ $report->status === 'Pending' ? 'warning' : ($report->status === 'Verified' ? 'info' : 'success') }}">
                                {{ $report->status }}
                            </span>
                        </p>
                    </div>

                    <!-- Koordinat -->
                    <div class="mb-3">
                        <label class="fw-bold">Koordinat</label>
                        <p>Longitude (X): {{ $report->longitude }}, Latitude (Y): {{ $report->latitude }}</p>
                    </div>

                    <!-- Tanggal Laporan -->
                    <div class="mb-3">
                        <label class="fw-bold">Tanggal Laporan</label>
                        <p>{{ $report->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Foto Kejadian -->
                    @if($report->image1 || $report->image2 || $report->image3)
                        <div class="mb-3">
                            <label class="fw-bold">Foto Kejadian</label>
                            <div class="row">
                                @if($report->image1)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <img src="{{ asset('storage/'.$report->image1) }}" 
                                                 class="img-fluid rounded" alt="Foto 1"
                                                 onclick="window.open(this.src)" 
                                                 style="cursor: pointer; height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                                @if($report->image2)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <img src="{{ asset('storage/'.$report->image2) }}" 
                                                 class="img-fluid rounded" alt="Foto 2"
                                                 onclick="window.open(this.src)" 
                                                 style="cursor: pointer; height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                                @if($report->image3)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <img src="{{ asset('storage/'.$report->image3) }}" 
                                                 class="img-fluid rounded" alt="Foto 3"
                                                 onclick="window.open(this.src)" 
                                                 style="cursor: pointer; height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Peta -->
                    <div class="mb-3">
                        <label class="fw-bold">Lokasi di Peta</label>
                        <div id="map" style="height: 400px;" class="rounded"></div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning">Edit Laporan</a>
                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Laporan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    .img-fluid {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }
    .card {
        overflow: hidden;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: box-shadow 0.3s ease;
    }
    .img-fluid {
        transition: transform 0.3s ease;
    }
    .img-fluid:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Inisialisasi peta
    var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker
    L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
        .bindPopup(`
            <strong>{{ $report->disaster->name }}</strong><br>
            Lokasi: {{ $report->location }}<br>
            Status: {{ $report->status }}
        `)
        .addTo(map)
        .openPopup();
</script>
@endpush
@endsection

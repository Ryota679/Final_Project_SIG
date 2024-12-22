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
                    <div class="mb-3">
                        <label class="fw-bold">Jenis Bencana</label>
                        <p>{{ $report->disaster->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Lokasi</label>
                        <p>{{ $report->location }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Koordinat</label>
                        <p>Longitude (X): {{ $report->longitude }}, Latitude (Y): {{ $report->latitude }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi</label>
                        <p>{{ $report->description }}</p>
                    </div>

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

                    <div class="mb-3">
                        <label class="fw-bold">Status</label>
                        <p>
                            <span class="badge bg-{{ $report->status === 'Pending' ? 'warning' : ($report->status === 'Verified' ? 'info' : 'success') }}">
                                {{ $report->status }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tanggal Laporan</label>
                        <p>{{ $report->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div id="map" style="height: 400px;" class="mb-3"></div>

                    <div class="d-flex justify-content-between">
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { 
        height: 400px; 
        width: 100%;
        z-index: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Load GeoJSON
        fetch('/geojson/boundary.geojson')
            .then(response => response.json())
            .then(data => {
                var boundary = L.geoJSON(data).addTo(map);
            });

        // Tambah marker lokasi laporan
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
            .bindPopup(`
                <strong>{{ $report->disaster->name }}</strong><br>
                Lokasi: {{ $report->location }}<br>
                Status: {{ $report->status }}
            `)
            .addTo(map)
            .openPopup();
    });
</script>
@endpush
@endsection 
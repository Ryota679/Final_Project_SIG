@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Tombol Tambah Laporan -->
            <div class="mb-3">
                <a href="{{ route('reports.create') }}" class="btn btn-primary">Tambah Laporan Baru</a>
            </div>

            <!-- Card Peta -->
            <div class="card">
                <div class="card-header">{{ __('Dashboard Peta Bencana') }}</div>
                <div class="card-body">
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="card mt-4">
                <div class="card-header">{{ __('Daftar Laporan Bencana') }}</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Bencana</th>
                                    <th>Lokasi</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $report->disaster->name }}</td>
                                    <td>{{ $report->location }}</td>
                                    <td>{{ Str::limit($report->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $report->status === 'Pending' ? 'warning' : ($report->status === 'Verified' ? 'info' : 'success') }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #map { 
        height: 500px !important; 
        width: 100% !important;
        z-index: 1;
        position: relative;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Inisialisasi peta
            var map = L.map('map', {
                center: [-6.574161, 110.661398],
                zoom: 13
            });

            // Base map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Load GeoJSON
            fetch('/geojson/boundary.geojson')
                .then(response => response.json())
                .then(data => {
                    console.log('GeoJSON loaded:', data);
                    var boundary = L.geoJSON(data, {
                        style: {
                            color: 'red',
                            weight: 2,
                            fillColor: '#f03',
                            fillOpacity: 0.2
                        }
                    }).addTo(map);
                    map.fitBounds(boundary.getBounds());
                })
                .catch(error => {
                    console.error('Error loading GeoJSON:', error);
                });

            // Tambahkan marker untuk setiap laporan
            @foreach($reports as $report)
                L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
                    .bindPopup(`
                        <strong>{{ $report->disaster->name }}</strong><br>
                        Lokasi: {{ $report->location }}<br>
                        Status: {{ $report->status }}<br>
                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-info mt-2">Detail</a>
                    `)
                    .addTo(map);
            @endforeach
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    });
</script>
@endpush
@endsection
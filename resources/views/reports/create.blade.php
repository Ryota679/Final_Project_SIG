@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Buat Laporan Baru') }}</span>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Peta -->
                        <div class="mb-3">
                            <label class="form-label">Klik pada peta untuk memilih lokasi kejadian</label>
                            <div id="map" style="height: 400px;"></div>
                        </div>

                        <!-- Koordinat -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">X (Longitude)</span>
                                    <input type="text" class="form-control" id="longitude_display" readonly>
                                    <input type="hidden" name="longitude" id="longitude" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">Y (Latitude)</span>
                                    <input type="text" class="form-control" id="latitude_display" readonly>
                                    <input type="hidden" name="latitude" id="latitude" required>
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Bencana -->
                        <div class="mb-3">
                            <label for="disaster_id" class="form-label">Jenis Bencana</label>
                            <select class="form-select @error('disaster_id') is-invalid @enderror" id="disaster_id" name="disaster_id" required>
                                <option value="">Pilih Jenis Bencana</option>
                                @foreach($disasters as $disaster)
                                    <option value="{{ $disaster->id }}">{{ $disaster->name }}</option>
                                @endforeach
                            </select>
                            @error('disaster_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-3">
                            <label class="form-label">Foto Kejadian (Maksimal 3 Foto)</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="file" class="form-control @error('image1') is-invalid @enderror" name="image1" accept="image/jpeg,image/png,image/jpg">
                                    @error('image1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control @error('image2') is-invalid @enderror" name="image2" accept="image/jpeg,image/png,image/jpg">
                                    @error('image2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control @error('image3') is-invalid @enderror" name="image3" accept="image/jpeg,image/png,image/jpg">
                                    @error('image3')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #map { 
        height: 400px; 
        width: 100%;
        z-index: 1;
        cursor: crosshair;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([-6.574161, 110.661398], 13);
    var marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan kembali load GeoJSON untuk batas wilayah
    fetch('/geojson/boundary.geojson')
        .then(response => response.json())
        .then(data => {
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

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        // Update both display and hidden inputs
        document.getElementById('longitude_display').value = lng.toFixed(8);
        document.getElementById('latitude_display').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        document.getElementById('latitude').value = lat.toFixed(8);

        // Get location name
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('location').value = data.display_name;
            })
            .catch(error => {
                document.getElementById('location').value = `${lat}, ${lng}`;
            });
    });
});
</script>
@endpush
@endsection
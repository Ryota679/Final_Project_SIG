@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Laporan Bencana') }}</span>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reports.update', $report->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Jenis Bencana -->
                        <div class="mb-3">
                            <label for="disaster_id" class="form-label">Jenis Bencana</label>
                            <select class="form-select @error('disaster_id') is-invalid @enderror" id="disaster_id" name="disaster_id" required>
                                <option value="">Pilih Jenis Bencana</option>
                                @foreach($disasters as $disaster)
                                    <option value="{{ $disaster->id }}" {{ old('disaster_id', $report->disaster_id) == $disaster->id ? 'selected' : '' }}>
                                        {{ $disaster->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('disaster_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                id="location" name="location" value="{{ old('location', $report->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" required>{{ old('description', $report->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Pending" {{ old('status', $report->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Verified" {{ old('status', $report->status) == 'Verified' ? 'selected' : '' }}>Verified</option>
                                <option value="Completed" {{ old('status', $report->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Koordinat -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude (X)</label>
                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                        id="longitude" name="longitude" value="{{ old('longitude', $report->longitude) }}" required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude (Y)</label>
                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                        id="latitude" name="latitude" value="{{ old('latitude', $report->latitude) }}" required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Peta -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Lokasi di Peta</label>
                            <div id="map" style="height: 400px;"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Inisialisasi peta dengan koordinat yang ada
    var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
    var marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker dengan koordinat yang ada
    marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);

    // Fungsi untuk memperbarui marker dan input koordinat
    function updateMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    }

    // Event ketika peta diklik
    map.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });

    // Event ketika input koordinat diubah
    document.getElementById('latitude').addEventListener('change', function() {
        var lat = parseFloat(this.value);
        var lng = parseFloat(document.getElementById('longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            updateMarker(lat, lng);
        }
    });

    document.getElementById('longitude').addEventListener('change', function() {
        var lat = parseFloat(document.getElementById('latitude').value);
        var lng = parseFloat(this.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            updateMarker(lat, lng);
        }
    });
</script>
@endpush
@endsection 
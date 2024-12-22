<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Menampilkan daftar lokasi
    public function index()
    {
        // Mengambil semua lokasi yang ada di tabel 'locations'
        $locations = Location::all();

        // Mengembalikan view dengan data lokasi
        return view('locations.index', compact('locations'));
    }

    // Menampilkan form untuk membuat lokasi baru
    public function create()
    {
        return view('locations.create');
    }

    // Menyimpan lokasi baru ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        // Membuat dan menyimpan lokasi baru
        Location::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
        ]);

        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    // Menampilkan form untuk mengedit lokasi
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    // Memperbarui data lokasi
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        // Memperbarui lokasi
        $location->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
        ]);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    // Menghapus lokasi
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
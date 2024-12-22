<?php

namespace App\Http\Controllers;

use App\Models\Disaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisasterController extends Controller
{
    /**
     * Menampilkan daftar bencana.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua data bencana dari database
        $disasters = Disaster::all();

        return view('disasters.index', compact('disasters'));
    }

    /**
     * Menampilkan form untuk menambahkan bencana baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('disasters.create');
    }

    /**
     * Menyimpan data bencana baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Menyimpan data bencana baru
        Disaster::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
        ]);

        return redirect()->route('disasters.index')->with('success', 'Disaster created successfully.');
    }

    /**
     * Menampilkan form untuk mengedit bencana.
     *
     * @param \App\Models\Disaster $disaster
     * @return \Illuminate\Http\Response
     */
    public function edit(Disaster $disaster)
    {
        return view('disasters.edit', compact('disaster'));
    }

    /**
     * Mengupdate data bencana yang sudah ada.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Disaster $disaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disaster $disaster)
    {
        // Validasi input pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Mengupdate data bencana
        $disaster->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
        ]);

        return redirect()->route('disasters.index')->with('success', 'Disaster updated successfully.');
    }

    /**
     * Menghapus data bencana.
     *
     * @param \App\Models\Disaster $disaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disaster $disaster)
    {
        // Menghapus data bencana
        $disaster->delete();

        return redirect()->route('disasters.index')->with('success', 'Disaster deleted successfully.');
    }
}

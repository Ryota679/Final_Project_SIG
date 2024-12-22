<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use Illuminate\Http\Request;

class BencanaController extends Controller
{
    public function getBencanaData()
    {
        return Bencana::select('id', 'jenis_bencana', 'lokasi', 'latitude', 'longitude', 'tanggal', 'status')
            ->get();
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $reports = Report::with('disaster')->get();
        return view('dashboard', compact('reports'));
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Disaster;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('disaster')->get();
        return view('dashboard', compact('reports'));
    }

    public function create()
    {
        $disasters = Disaster::orderBy('name')->get();
        return view('reports.create', compact('disasters'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'disaster_id' => 'required|exists:disasters,id',
                'description' => 'required|string',
                'location' => 'required|string',
                'longitude' => 'required|numeric|between:-180,180',
                'latitude' => 'required|numeric|between:-90,90',
                'image1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'image2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'image3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Tambahkan status default
            $validated['status'] = 'Pending';

            // Debug info
            \Log::info('Attempting to create report:', $validated);

            // Handle image uploads
            foreach(['image1', 'image2', 'image3'] as $image) {
                if ($request->hasFile($image)) {
                    $file = $request->file($image);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('reports', $filename, 'public');
                    $validated[$image] = $path;
                }
            }

            // Create report
            $report = Report::create($validated);

            \Log::info('Report created successfully:', ['report_id' => $report->id]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Laporan berhasil dibuat');

        } catch (\Exception $e) {
            \Log::error('Error creating report:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat laporan. ' . $e->getMessage()]);
        }
    }

    public function edit(Report $report)
    {
        $disasters = Disaster::orderBy('name')->get();
        return view('reports.edit', compact('report', 'disasters'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'disaster_id' => 'required|exists:disasters,id',
            'description' => 'required|string',
            'location' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:Pending,Verified,Completed'
        ]);

        // Format koordinat untuk memastikan nilai yang benar
        $validated['latitude'] = (float) $request->latitude;
        $validated['longitude'] = (float) $request->longitude;

        $report->update($validated);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui');
    }

    public function show(Report $report)
    {
        // Debug informasi gambar
        \Log::info('Image paths:', [
            'image1' => $report->image1,
            'image2' => $report->image2,
            'image3' => $report->image3,
            'full_path1' => $report->image1 ? public_path('storage/'.$report->image1) : null,
            'exists1' => $report->image1 ? file_exists(public_path('storage/'.$report->image1)) : false
        ]);

        return view('reports.view', compact('report'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('dashboard')->with('success', 'Report deleted successfully.');
    }
}
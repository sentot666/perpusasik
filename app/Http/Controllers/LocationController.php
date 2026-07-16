<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        $locations = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:30|unique:locations,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Location::create($validated);
        return back()->with('success', 'Lokasi/Rak berhasil ditambahkan.');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:30|unique:locations,code,' . $location->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);
        return back()->with('success', 'Lokasi/Rak berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        if ($location->bookItems()->count() > 0) {
            return back()->with('error', 'Lokasi/Rak tidak bisa dihapus karena masih ada buku yang ditempatkan di sana.');
        }
        $location->delete();
        return back()->with('success', 'Lokasi/Rak berhasil dihapus.');
    }
}

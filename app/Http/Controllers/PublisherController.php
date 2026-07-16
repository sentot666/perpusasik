<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%");
        }
        $publishers = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('publishers.index', compact('publishers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'city'    => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
        ]);

        Publisher::create($validated);
        return back()->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'city'    => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
        ]);

        $publisher->update($validated);
        return back()->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return back()->with('error', 'Penerbit tidak bisa dihapus karena masih terikat ke buku.');
        }
        $publisher->delete();
        return back()->with('success', 'Penerbit berhasil dihapus.');
    }
}

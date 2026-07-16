<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('ddc', 'like', "%{$search}%");
        }
        $subjects = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ddc'  => 'nullable|string|max:30',
        ]);

        Subject::create($validated);
        return back()->with('success', 'Subyek berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ddc'  => 'nullable|string|max:30',
        ]);

        $subject->update($validated);
        return back()->with('success', 'Subyek berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->books()->count() > 0) {
            return back()->with('error', 'Subyek tidak bisa dihapus karena masih terikat ke buku.');
        }
        $subject->delete();
        return back()->with('success', 'Subyek berhasil dihapus.');
    }
}

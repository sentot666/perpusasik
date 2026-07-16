<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%");
        }
        $authors = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:personal,organization',
            'biography' => 'nullable|string',
        ]);

        Author::create($validated);
        return back()->with('success', 'Pengarang berhasil ditambahkan.');
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:personal,organization',
            'biography' => 'nullable|string',
        ]);

        $author->update($validated);
        return back()->with('success', 'Pengarang berhasil diperbarui.');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->count() > 0) {
            return back()->with('error', 'Pengarang tidak bisa dihapus karena masih terikat ke buku.');
        }
        $author->delete();
        return back()->with('success', 'Pengarang berhasil dihapus.');
    }
}

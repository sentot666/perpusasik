<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title')->paginate(10);
        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        return view('pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('pages.index')->with('success', 'Halaman berhasil dibuat.');
    }

    public function edit(Page $page)
    {
        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Halaman berhasil dihapus.');
    }
}

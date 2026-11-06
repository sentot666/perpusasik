<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected array $coreSlugs = [
        'visi-misi',
        'sejarah',
        'struktur-organisasi',
        'jam-layanan',
        'tata-tertib',
        'pustakawan',
        'program-kerja',
    ];

    public function index()
    {
        $fieldOrder = "'" . implode("', '", $this->coreSlugs) . "'";
        $pages = Page::orderByRaw("CASE WHEN slug IN ($fieldOrder) THEN 0 ELSE 1 END")
            ->orderByRaw("FIELD(slug, $fieldOrder)")
            ->orderBy('title')
            ->paginate(15);

        $coreSlugs = $this->coreSlugs;

        return view('pages.index', compact('pages', 'coreSlugs'));
    }

    public function create()
    {
        return view('pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        
        // Pastikan slug unik
        $baseSlug = $slug ?: 'halaman';
        $slug = $baseSlug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Page::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $this->cleanHtml($request->content),
            'is_active' => $request->boolean('is_active', true)
        ]);

        Cache::forget('opac_pages_menu');

        return redirect()->route('pages.index')->with('success', 'Halaman baru berhasil ditambahkan.');
    }

    public function edit(Page $page)
    {
        $coreSlugs = $this->coreSlugs;
        $isCore = in_array($page->slug, $this->coreSlugs);
        return view('pages.edit', compact('page', 'coreSlugs', 'isCore'));
    }

    public function update(Request $request, Page $page)
    {
        $isCore = in_array($page->slug, $this->coreSlugs);

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ];

        if (!$isCore && $request->filled('slug')) {
            $rules['slug'] = 'required|string|max:255|unique:pages,slug,' . $page->id;
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'content' => $this->cleanHtml($request->content),
            'is_active' => $request->boolean('is_active')
        ];

        if (!$isCore && $request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        }

        $page->update($data);

        Cache::forget('opac_pages_menu');

        return redirect()->route('pages.index')->with('success', 'Konten halaman ' . $page->title . ' berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        if (in_array($page->slug, $this->coreSlugs)) {
            return redirect()->route('pages.index')->with('error', 'Halaman profil utama OPAC tidak dapat dihapus.');
        }

        $page->delete();
        Cache::forget('opac_pages_menu');

        return redirect()->route('pages.index')->with('success', 'Halaman berhasil dihapus.');
    }

    /**
     * Bersihkan HTML dari skrip berbahaya (Stored XSS Protection)
     */
    protected function cleanHtml(?string $content): ?string
    {
        if (!$content) {
            return null;
        }

        // 1. Hapus tag script, iframe, object, embed, applet, meta, link
        $content = preg_replace('/<\s*(script|iframe|object|embed|applet|meta|link)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $content);
        $content = preg_replace('/<\s*(script|iframe|object|embed|applet|meta|link)\b[^>]*>/is', '', $content);

        // 2. Hapus inline javascript event handlers (onclick, onerror, onload, onmouseover, dsb.)
        $content = preg_replace('/(\bon\w+\s*=\s*["\'][^"\']*["\']|\bon\w+\s*=\s*[^>\s]+)/is', '', $content);

        // 3. Hapus pseudo-protocol berbahaya pada href/src (javascript:, vbscript:, data:text/html)
        $content = preg_replace('/(href|src)\s*=\s*["\']\s*(javascript|vbscript|data):[^"\']*["\']/is', '$1="#"', $content);

        return $content;
    }
}

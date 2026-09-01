<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\BookItem;
use App\Models\GuestBook;
use App\Models\Location;
use Illuminate\Http\Request;

class OpacController extends Controller
{
            public function index(Request $request)
    {
        $stats = [
            'total_books'     => Book::count(),
            'total_items'     => BookItem::count(),
            'total_members'   => Member::where('is_active', true)->count(),
            'total_visitors'  => GuestBook::sum('participants_count'),
        ];
        
        $locations = Location::orderBy('code')->get();

        return view('opac.index', compact('stats', 'locations'));
    }

    public function katalog(Request $request)
    {
        $tab = $request->get('tab', 'home');

        $query = Book::with(['authors', 'publisher'])
            ->withCount(['items', 'availableItems'])
            ->where('is_active', true);

        // Fetch filter data for all tabs
        $collectionTypes = Book::distinct()->pluck('collection_type')->sort();
        $years = Book::distinct()->orderByDesc('publication_year')->pluck('publication_year')->filter();
        $locations = Location::orderBy('code')->get();

        if ($tab == 'home') {
            // Newest books (limit 10)
            $newestBooks = (clone $query)->latest()->limit(10)->get();
            // Popular books (using items_count as a proxy for popular)
            $popularBooks = (clone $query)->orderByDesc('items_count')->limit(5)->get();

            return view('opac.katalog', compact('tab', 'newestBooks', 'popularBooks', 'collectionTypes', 'years', 'locations'));
        }

        if ($tab == 'digital') {
            $query->where(function($q) {
                $q->where('collection_type', 'LIKE', '%digital%')
                  ->orWhere('collection_type', 'LIKE', '%e-book%')
                  ->orWhere('collection_type', 'LIKE', '%ebook%');
            });
        }

        // Apply filters for 'koleksi' and 'digital'
        if ($search = $request->q) {
            $query->search($search);
        }
        if ($request->filled('collection_type') && $tab != 'digital') {
            $query->where('collection_type', $request->collection_type);
        }
        if ($year = $request->year) {
            $query->where('publication_year', $year);
        }
        if ($lang = $request->language) {
            $query->where('language', $lang);
        }
        if ($locId = $request->location_id) {
            $query->whereHas('items', function ($q) use ($locId) {
                $q->where('location_id', $locId);
            });
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('opac.katalog', compact('tab', 'books', 'collectionTypes', 'years', 'locations'));
    }

    public function autocomplete(Request $request)
    {
        $q = $request->get('q');
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Books
        $books = \App\Models\Book::where('title', 'LIKE', "%{$q}%")
            ->where('is_active', true)
            ->limit(5)
            ->get(['title']);
        
        foreach ($books as $book) {
            $results[] = [
                'text' => $book->title,
                'type' => 'Judul Buku',
                'icon' => 'bi-book'
            ];
        }

        // Authors
        $authors = \App\Models\Author::where('name', 'LIKE', "%{$q}%")
            ->limit(3)
            ->get(['name']);
            
        foreach ($authors as $author) {
            $results[] = [
                'text' => $author->name,
                'type' => 'Penulis',
                'icon' => 'bi-person'
            ];
        }

        // Subjects
        $subjects = \App\Models\Subject::where('name', 'LIKE', "%{$q}%")
            ->limit(3)
            ->get(['name']);
            
        foreach ($subjects as $subject) {
            $results[] = [
                'text' => $subject->name,
                'type' => 'Topik',
                'icon' => 'bi-tags'
            ];
        }

        return response()->json($results);
    }

    public function show(Book $book)
    {
        $book->load(['authors', 'publisher', 'subjects', 'items.location']);
        $relatedBooks = Book::with('authors')
            ->whereHas('subjects', fn($q) => $q->whereIn('subjects.id', $book->subjects->pluck('id')))
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        $totalCount = $book->items->count();
        $availableCount = $book->items->where('status', 'available')->count();

        return view('opac.show', compact('book', 'relatedBooks', 'totalCount', 'availableCount'));
    }

    public function agenda(Request $request)
    {
        $agendas = \App\Models\Agenda::where('is_published', true)
            ->latest('event_date')
            ->paginate(12);

        return view('opac.agenda', compact('agendas'));
    }

    public function programKerja()
    {
        $page = \App\Models\Page::where('slug', 'program-kerja')->where('is_active', true)->first();
        return view('opac.program-kerja', compact('page'));
    }

    public function sejarah()
    {
        $page = \App\Models\Page::where('slug', 'sejarah')->where('is_active', true)->first();
        return view('opac.sejarah', compact('page'));
    }

    public function visiMisi()
    {
        $page = \App\Models\Page::where('slug', 'visi-misi')->where('is_active', true)->first();
        return view('opac.visi-misi', compact('page'));
    }

    public function strukturOrganisasi()
    {
        $page = \App\Models\Page::where('slug', 'struktur-organisasi')->where('is_active', true)->first();
        return view('opac.struktur-organisasi', compact('page'));
    }

    public function pustakawan()
    {
        $page = \App\Models\Page::where('slug', 'pustakawan')->where('is_active', true)->first();
        return view('opac.pustakawan', compact('page'));
    }

    public function tataTertib()
    {
        $page = \App\Models\Page::where('slug', 'tata-tertib')->where('is_active', true)->first();
        return view('opac.tata-tertib', compact('page'));
    }

    public function jamLayanan()
    {
        $page = \App\Models\Page::where('slug', 'jam-layanan')->where('is_active', true)->first();
        return view('opac.jam-layanan', compact('page'));
    }
}

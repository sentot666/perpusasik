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
        $query = Book::with(['authors', 'publisher'])
            ->withCount(['items', 'availableItems'])
            ->where('is_active', true);

        if ($search = $request->q) {
            $query->search($search);
        }
        if ($type = $request->collection_type) {
            $query->where('collection_type', $type);
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

        $collectionTypes = Book::distinct()->pluck('collection_type')->sort();
        $years = Book::distinct()->orderByDesc('publication_year')->pluck('publication_year')->filter();
        $locations = Location::orderBy('code')->get();

        return view('opac.katalog', compact('books', 'collectionTypes', 'years', 'locations'));
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

        return view('opac.show', compact('book', 'relatedBooks'));
    }

    public function agenda(Request $request)
    {
        $agendas = \App\Models\Agenda::where('is_published', true)
            ->latest('event_date')
            ->paginate(12);

        return view('opac.agenda', compact('agendas'));
    }
}

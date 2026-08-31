<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Subject;
use App\Models\BookItem;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['authors', 'publisher'])
            ->withCount(['items', 'availableItems']);

        if ($search = $request->search) {
            $query->search($search);
        }

        if ($type = $request->collection_type) {
            $query->where('collection_type', $type);
        }

        $books = $query->latest()->paginate(20)->withQueryString();

        $collectionTypes = Book::distinct()->pluck('collection_type')->sort();

        return view('books.index', compact('books', 'collectionTypes'));
    }

    public function create()
    {
        $publishers = Publisher::orderBy('name')->get();
        $authors    = Author::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();
        $locations  = Location::orderBy('name')->get();

        return view('books.create', compact('publishers', 'authors', 'subjects', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:500',
            'subtitle'           => 'nullable|string|max:500',
            'isbn'               => 'nullable|string|max:30',
            'isbn13'             => 'nullable|string|max:30',
            'call_number'        => 'nullable|string|max:100',
            'ddc'                => 'nullable|string|max:30',
            'edition'            => 'nullable|string|max:50',
            'language'           => 'nullable|string|max:10',
            'publication_year'   => 'nullable|string|max:10',
            'place_of_publication' => 'nullable|string|max:100',
            'pages'              => 'nullable|integer|min:1',
            'abstract'           => 'nullable|string',
            'collection_type'    => 'required|string',
            'publisher_id'       => 'nullable|exists:publishers,id',
            'authors'            => 'nullable|array',
            'authors.*'          => 'exists:authors,id',
            'subjects'           => 'nullable|array',
            'subjects.*'         => 'exists:subjects,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $book = Book::create($validated);

            if ($request->authors) {
                $authorData = [];
                foreach ($request->authors as $order => $authorId) {
                    $authorData[$authorId] = ['role' => 'author', 'order' => $order + 1];
                }
                $book->authors()->sync($authorData);
            }

            if ($request->subjects) {
                $book->subjects()->sync($request->subjects);
            }

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('covers', 'public');
                $book->update(['cover_image' => $path]);
            }
        });

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['authors', 'publisher', 'subjects', 'items.location']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $book->load(['authors', 'subjects']);
        $publishers = Publisher::orderBy('name')->get();
        $authors    = Author::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();

        return view('books.edit', compact('book', 'publishers', 'authors', 'subjects'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:500',
            'subtitle'           => 'nullable|string|max:500',
            'isbn'               => 'nullable|string|max:30',
            'isbn13'             => 'nullable|string|max:30',
            'call_number'        => 'nullable|string|max:100',
            'ddc'                => 'nullable|string|max:30',
            'edition'            => 'nullable|string|max:50',
            'language'           => 'nullable|string|max:10',
            'publication_year'   => 'nullable|string|max:10',
            'place_of_publication' => 'nullable|string|max:100',
            'pages'              => 'nullable|integer|min:1',
            'abstract'           => 'nullable|string',
            'collection_type'    => 'required|string',
            'publisher_id'       => 'nullable|exists:publishers,id',
            'authors'            => 'nullable|array',
            'subjects'           => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated, $request, $book) {
            $book->update($validated);
            $book->authors()->sync([]);

            if ($request->authors) {
                $authorData = [];
                foreach ($request->authors as $order => $authorId) {
                    $authorData[$authorId] = ['role' => 'author', 'order' => $order + 1];
                }
                $book->authors()->sync($authorData);
            }

            if ($request->has('subjects')) {
                $book->subjects()->sync($request->subjects ?? []);
            }

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('covers', 'public');
                $book->update(['cover_image' => $path]);
            }
        });

        return redirect()->route('books.show', $book)
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    public function printBarcode(Request $request, Book $book)
    {
        $query = $book->items()->with('location');
        if ($request->has('items') && is_array($request->items)) {
            $query->whereIn('id', $request->items);
        }
        $items = $query->get();
        return view('books.barcode', compact('book', 'items'));
    }
}

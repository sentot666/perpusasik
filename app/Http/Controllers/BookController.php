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
    public function autocomplete(Request $request)
    {
        $q = $request->get('q');
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Books (do not filter by is_active so admins can find everything)
        $books = \App\Models\Book::where('title', 'LIKE', "%{$q}%")
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
            'digital_file'       => 'nullable|file|mimes:pdf,epub|max:51200',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $book = Book::create($validated);

            if ($request->authors) {
                $authorData = [];
                $validAuthors = array_filter($request->authors, function($val) { return !empty($val); });
                $order = 1;
                foreach ($validAuthors as $authorId) {
                    $authorData[$authorId] = ['role' => 'author', 'order' => $order++];
                }
                $book->authors()->sync($authorData);
            }

            if ($request->subjects) {
                $validSubjects = array_filter($request->subjects, function($val) { return !empty($val); });
                $book->subjects()->sync($validSubjects);
            }

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('covers', 'public');
                $book->update(['cover_image' => $path]);
            }

            // Handle digital file
            if ($request->hasFile('digital_file')) {
                $path = $request->file('digital_file')->store('digital_books', 'public');
                $book->update(['digital_file_path' => $path]);
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
            'digital_file'       => 'nullable|file|mimes:pdf,epub|max:51200',
        ]);

        DB::transaction(function () use ($validated, $request, $book) {
            $book->update($validated);
            $book->authors()->sync([]);

            if ($request->authors) {
                $authorData = [];
                $validAuthors = array_filter($request->authors, function($val) { return !empty($val); });
                $order = 1;
                foreach ($validAuthors as $authorId) {
                    $authorData[$authorId] = ['role' => 'author', 'order' => $order++];
                }
                $book->authors()->sync($authorData);
            }

            if ($request->has('subjects')) {
                $validSubjects = array_filter($request->subjects ?? [], function($val) { return !empty($val); });
                $book->subjects()->sync($validSubjects);
            }

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('covers', 'public');
                $book->update(['cover_image' => $path]);
            }

            if ($request->hasFile('digital_file')) {
                if ($book->digital_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->digital_file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($book->digital_file_path);
                }
                $path = $request->file('digital_file')->store('digital_books', 'public');
                $book->update(['digital_file_path' => $path]);
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
        
        $itemsInput = $request->get('items');
        if (!empty($itemsInput) && is_array($itemsInput)) {
            $query->whereIn('id', $itemsInput);
        }
        
        $items = $query->get();
        return view('books.barcode', compact('book', 'items'));
    }
}

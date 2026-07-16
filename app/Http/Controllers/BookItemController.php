<?php

namespace App\Http\Controllers;

use App\Models\BookItem;
use App\Models\Book;
use App\Models\Location;
use Illuminate\Http\Request;

class BookItemController extends Controller
{
    public function index(Book $book)
    {
        $items = $book->items()->with('location')->paginate(20);
        return view('book-items.index', compact('book', 'items'));
    }

    public function create(Book $book)
    {
        $locations        = Location::orderBy('name')->get();
        $accessionNumber  = BookItem::generateAccessionNumber();
        return view('book-items.create', compact('book', 'locations', 'accessionNumber'));
    }

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'barcode'           => 'required|unique:book_items,barcode',
            'accession_number'  => 'required|unique:book_items,accession_number',
            'location_id'       => 'nullable|exists:locations,id',
            'condition'         => 'nullable|string',
            'acquisition_date'  => 'nullable|date',
            'acquisition_price' => 'nullable|numeric|min:0',
            'acquisition_source'=> 'nullable|string|max:50',
            'notes'             => 'nullable|string',
        ]);

        $validated['book_id'] = $book->id;
        $validated['status']  = 'Tersedia';

        BookItem::create($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Eksemplar berhasil ditambahkan.');
    }

    public function show(BookItem $item)
    {
        $item->load(['book', 'location', 'circulations.member']);
        return view('book-items.show', compact('item'));
    }

    public function edit(BookItem $item)
    {
        $locations = Location::orderBy('name')->get();
        return view('book-items.edit', compact('item', 'locations'));
    }

    public function update(Request $request, BookItem $item)
    {
        $validated = $request->validate([
            'barcode'           => 'required|unique:book_items,barcode,' . $item->id,
            'accession_number'  => 'required|unique:book_items,accession_number,' . $item->id,
            'location_id'       => 'nullable|exists:locations,id',
            'condition'         => 'nullable|string',
            'status'            => 'required|in:Tersedia,Dipesan,Perbaikan,Hilang',
            'notes'             => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('book-items.show', $item)
            ->with('success', 'Data eksemplar berhasil diperbarui.');
    }

    public function destroy(BookItem $item)
    {
        $item->delete();
        return back()->with('success', 'Eksemplar berhasil dihapus.');
    }

    public function barcode(BookItem $item)
    {
        return view('book-items.barcode', compact('item'));
    }

    public function ajaxLookup(Request $request)
    {
        $barcode = $request->barcode;
        $item = BookItem::where('barcode', $barcode)
            ->with(['book.authors', 'location'])
            ->first();

        if (! $item) {
            return response()->json(['found' => false, 'message' => 'Barcode tidak ditemukan.']);
        }

        return response()->json([
            'found'   => true,
            'id'      => $item->id,
            'barcode' => $item->barcode,
            'status'  => $item->status,
            'book'    => [
                'title'       => $item->book->title,
                'author'      => $item->book->main_author,
                'call_number' => $item->book->call_number,
            ],
            'location' => $item->location?->name,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __invoke(Request $request)
    {
        session(['member_catalog_url' => $request->fullUrl()]);

        $query = Book::with(['authors', 'publisher'])
            ->withCount(['items', 'availableItems'])
            ->where('is_active', true);

        if ($search = $request->q) {
            $query->search($search);
        }
        if ($category = $request->category) {
            $query->where('collection_type', $category);
        }
        if ($sort = $request->sort) {
            if ($sort === 'newest') {
                $query->latest();
            } elseif ($sort === 'oldest') {
                $query->oldest();
            } elseif ($sort === 'title_asc') {
                $query->orderBy('title', 'asc');
            } elseif ($sort === 'title_desc') {
                $query->orderBy('title', 'desc');
            }
        } else {
            $query->latest();
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = Book::distinct()->pluck('collection_type')->filter()->sort();

        return view('member.catalog', compact('books', 'categories'));
    }

    public function show(Request $request, Book $book)
    {
        $book->load(['authors', 'publisher', 'subjects', 'items.location']);
        
        $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        // Smart back URL tracking
        $prevUrl = url()->previous();
        $isSelfOrReader = str_contains($prevUrl, '/member/catalog/' . $book->id) || str_contains($prevUrl, '/read');
        
        if ($prevUrl && !$isSelfOrReader && (str_contains($prevUrl, '/member') || str_contains($prevUrl, '/opac'))) {
            session(['member_catalog_back_url' => $prevUrl]);
        }
        
        $backUrl = session('member_catalog_back_url') 
            ?: session('member_catalog_url') 
            ?: route('member.catalog');

        return view('member.catalog_show', compact('book', 'inWishlist', 'backUrl'));
    }
}

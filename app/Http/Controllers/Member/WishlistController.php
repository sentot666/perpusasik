<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = Wishlist::with('book.authors', 'book.publisher')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('member.wishlists', compact('wishlists'));
    }

    public function store(Request $request, Book $book)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id
            ]);
        }

        return back()->with('success', 'Buku ditambahkan ke Wishlist!');
    }

    public function destroy(Request $request, Book $book)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->delete();

        return back()->with('success', 'Buku dihapus dari Wishlist!');
    }
}

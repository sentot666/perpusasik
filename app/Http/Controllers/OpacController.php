<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\BookItem;
use App\Models\GuestBook;
use App\Models\Location;
use App\Models\Subject;
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


        if ($tab == 'home') {
            // Newest books (limit 10)
            $newestBooks = (clone $query)->latest()->limit(10)->get();
            // Popular books (using circulation count as true proxy for popular, just like dashboard)
            $popularIds = \Illuminate\Support\Facades\DB::table('circulations')
                ->join('book_items', 'circulations.book_item_id', '=', 'book_items.id')
                ->join('books', 'book_items.book_id', '=', 'books.id')
                ->where('books.is_active', true)
                ->select('books.id', \Illuminate\Support\Facades\DB::raw('COUNT(circulations.id) as borrow_count'))
                ->groupBy('books.id')
                ->orderByDesc('borrow_count')
                ->limit(5)
                ->pluck('books.id');

            if ($popularIds->isEmpty()) {
                $popularBooks = (clone $query)->orderByDesc('items_count')->limit(5)->get();
            } else {
                $popularBooks = (clone $query)->whereIn('id', $popularIds)
                    ->orderByRaw("FIELD(id, " . $popularIds->implode(',') . ")")
                    ->get();
            }

            return view('opac.katalog', compact('tab', 'newestBooks', 'popularBooks'));
        }

        if ($tab == 'digital') {
            $query->where('collection_type', 'E-book');
        } else {
            $query->where(function($q) {
                $q->where('collection_type', '!=', 'E-book')
                  ->orWhereNull('collection_type');
            });
        }

        // Apply filters for 'koleksi' and 'digital'
        if ($search = $request->q) {
            $query->search($search);
        }
        
        if ($subjectId = $request->subject_id) {
            $query->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            });
        }
        
        // Remove old filters like collection_type, year, language, location_id

        $books = $query->latest()->paginate(12)->withQueryString();

        // Fetch all categories (Subjects) that have at least one active book
        $categories = Subject::whereHas('books', function ($q) {
            $q->where('is_active', true);
        })->orderBy('name')->get();
        $activeCategory = null;
        if ($request->subject_id) {
            $activeCategory = Subject::find($request->subject_id);
        }

        return view('opac.katalog', compact('tab', 'books', 'categories', 'activeCategory'));
    }

    public function autocomplete(Request $request)
    {
        $q = $request->get('q');
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        $books = \App\Models\Book::where('title', 'LIKE', "%{$q}%")
            ->where('is_active', true)
            ->limit(5)
            ->get(['id', 'title']);
        
        foreach ($books as $book) {
            $results[] = [
                'text' => $book->title,
                'type' => 'Judul Buku',
                'icon' => 'bi-book',
                'url'  => route('opac.show', $book->id)
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
            ->withCount(['items', 'availableItems'])
            ->whereHas('subjects', fn($q) => $q->whereIn('subjects.id', $book->subjects->pluck('id')))
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        $totalCount = $book->items->count();
        $availableCount = $book->items->where('status', 'Tersedia')->count();

        return view('opac.show', compact('book', 'relatedBooks', 'totalCount', 'availableCount'));
    }

    public function read(Book $book)
    {
        if (!$book->digital_file_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($book->digital_file_path)) {
            abort(404, 'File digital tidak ditemukan.');
        }

        $extension = pathinfo($book->digital_file_path, PATHINFO_EXTENSION);
        if (strtolower($extension) !== 'pdf') {
            return redirect(asset('storage/' . $book->digital_file_path));
        }

        return view('opac.read', compact('book'));
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

    public function jadwalKunjungan($level)
    {
        $levels = ['sd' => 'Sekolah Dasar (SD)', 'smp' => 'Menengah Pertama (SMP)', 'sma' => 'Menengah Atas (SMA)'];
        
        if (!array_key_exists($level, $levels)) {
            abort(404);
        }

        $levelName = $levels[$level];

        $schedules = \App\Models\ClassVisit::where('level', $level)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('time')
            ->get();

        return view('opac.jadwal-kunjungan', compact('level', 'levelName', 'schedules'));
    }
}

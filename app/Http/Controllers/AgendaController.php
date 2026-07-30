<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::latest('event_date');

        if ($search = $request->search) {
            $query->search($search);
        }

        if ($category = $request->category) {
            $query->where('category', $category);
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $agendas = $query->paginate(12)->withQueryString();

        // Stat counts
        $totalAgenda    = Agenda::count();
        $upcomingCount = Agenda::where('event_date', '>=', now()->toDateString())->count();
        $ongoingCount  = Agenda::where('event_date', now()->toDateString())->count();
        $pastCount     = Agenda::where('event_date', '<', now()->toDateString())->count();

        $categories = ['Workshop', 'Bedah Buku', 'Lomba', 'Pameran', 'Klub Baca', 'Lainnya'];
        $statuses   = ['Akan Datang', 'Berlangsung', 'Selesai', 'Dibatalkan'];

        return view('agendas.index', compact(
            'agendas',
            'totalAgenda',
            'upcomingCount',
            'ongoingCount',
            'pastCount',
            'categories',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'required|string|max:50',
            'description'     => 'nullable|string',
            'event_date'      => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'nullable',
            'location'        => 'required|string|max:255',
            'speaker'         => 'nullable|string|max:255',
            'target_audience' => 'nullable|string|max:255',
            'quota'           => 'nullable|integer|min:1',
            'status'          => 'required|string',
            'poster_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $validated['created_by'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('poster_image')) {
            $path = $request->file('poster_image')->store('agendas', 'public');
            $validated['poster_image'] = $path;
        }

        Agenda::create($validated);

        return redirect()->route('agendas.index')
            ->with('success', 'Agenda kegiatan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'required|string|max:50',
            'description'     => 'nullable|string',
            'event_date'      => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'nullable',
            'location'        => 'required|string|max:255',
            'speaker'         => 'nullable|string|max:255',
            'target_audience' => 'nullable|string|max:255',
            'quota'           => 'nullable|integer|min:1',
            'status'          => 'required|string',
            'poster_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('poster_image')) {
            if ($agenda->poster_image && Storage::disk('public')->exists($agenda->poster_image)) {
                Storage::disk('public')->delete($agenda->poster_image);
            }
            $path = $request->file('poster_image')->store('agendas', 'public');
            $validated['poster_image'] = $path;
        }

        $agenda->update($validated);

        return redirect()->route('agendas.index')
            ->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->poster_image && Storage::disk('public')->exists($agenda->poster_image)) {
            Storage::disk('public')->delete($agenda->poster_image);
        }

        $agenda->delete();

        return redirect()->route('agendas.index')
            ->with('success', 'Agenda kegiatan berhasil dihapus.');
    }
}

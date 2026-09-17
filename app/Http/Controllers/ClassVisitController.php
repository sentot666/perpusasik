<?php

namespace App\Http\Controllers;

use App\Models\ClassVisit;
use Illuminate\Http\Request;

class ClassVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassVisit::query();

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                  ->orWhere('teacher_name', 'like', "%{$search}%")
                  ->orWhere('day', 'like', "%{$search}%");
            });
        }

        $visits = $query->orderBy('level')->orderBy('day')->orderBy('time')->paginate(15)->withQueryString();

        $totalVisits = ClassVisit::count();
        $sdCount = ClassVisit::where('level', 'sd')->count();
        $smpCount = ClassVisit::where('level', 'smp')->count();
        $smaCount = ClassVisit::where('level', 'sma')->count();

        return view('class_visits.index', compact('visits', 'totalVisits', 'sdCount', 'smpCount', 'smaCount'));
    }

    public function create()
    {
        return view('class_visits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|in:sd,smp,sma',
            'day' => 'required|string|max:50',
            'date' => 'nullable|date',
            'time' => 'required|string|max:50',
            'class_name' => 'required|string|max:255',
            'teacher_name' => 'required|string|max:255',
        ]);

        ClassVisit::create($validated);
        return redirect()->route('class-visits.index')->with('success', 'Jadwal kunjungan berhasil ditambahkan.');
    }

    public function edit(ClassVisit $classVisit)
    {
        return view('class_visits.edit', compact('classVisit'));
    }

    public function update(Request $request, ClassVisit $classVisit)
    {
        $validated = $request->validate([
            'level' => 'required|in:sd,smp,sma',
            'day' => 'required|string|max:50',
            'date' => 'nullable|date',
            'time' => 'required|string|max:50',
            'class_name' => 'required|string|max:255',
            'teacher_name' => 'required|string|max:255',
        ]);

        $classVisit->update($validated);
        return redirect()->route('class-visits.index')->with('success', 'Jadwal kunjungan berhasil diperbarui.');
    }

    public function destroy(ClassVisit $classVisit)
    {
        $classVisit->delete();
        return redirect()->route('class-visits.index')->with('success', 'Jadwal kunjungan berhasil dihapus.');
    }
}

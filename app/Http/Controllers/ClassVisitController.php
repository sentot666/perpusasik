<?php

namespace App\Http\Controllers;

use App\Models\ClassVisit;
use Illuminate\Http\Request;

class ClassVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassVisit::query();

        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        $visits = $query->orderBy('level')->orderBy('day')->orderBy('time')->paginate(15);
        return view('class_visits.index', compact('visits'));
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

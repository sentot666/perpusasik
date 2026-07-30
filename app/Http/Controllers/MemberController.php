<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($search = $request->search) {
            $query->search($search);
        }
        if ($type = $request->member_type) {
            $query->where('member_type', $type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $members     = $query->latest()->paginate(20)->withQueryString();
        $memberTypes = Member::distinct()->pluck('member_type')->sort();

        return view('members.index', compact('members', 'memberTypes'));
    }

    public function create()
    {
        $memberCode = Member::generateCode();
        return view('members.create', compact('memberCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_code'    => 'required|unique:members,member_code',
            'name'           => 'required|string|max:200',
            'email'          => 'nullable|email|max:200',
            'phone'          => 'nullable|string|max:30',
            'identity_number'=> 'nullable|string|max:50',
            'identity_type'  => 'nullable|string|max:20',
            'gender'         => 'nullable|in:L,P',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:100',
            'birth_date'     => 'nullable|date',
            'member_type'    => 'required|string',
            'register_date'  => 'nullable|date',
            'expired_date'   => 'nullable|date',
            'is_active'      => 'boolean',
            'notes'          => 'nullable|string',
        ]);

        // Auto-generate barcode
        $validated['barcode'] = 'M' . str_pad($validated['member_code'], 10, '0', STR_PAD_LEFT);

        // Auto-set expired_date if not provided
        if (empty($validated['expired_date'])) {
            $years = (int) Setting::get('member_expiry_years', 1);
            $validated['expired_date'] = now()->addYears($years)->toDateString();
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members', 'public');
        }

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil didaftarkan.');
    }

    public function show(Member $member)
    {
        $member->load(['circulations.bookItem.book']);
        $activeLoans = $member->activeCirculations()->with('bookItem.book')->get();
        return view('members.show', compact('member', 'activeLoans'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_code'    => 'required|unique:members,member_code,' . $member->id,
            'name'           => 'required|string|max:200',
            'email'          => 'nullable|email|max:200',
            'phone'          => 'nullable|string|max:30',
            'identity_number'=> 'nullable|string|max:50',
            'identity_type'  => 'nullable|string|max:20',
            'gender'         => 'nullable|in:L,P',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:100',
            'birth_date'     => 'nullable|date',
            'member_type'    => 'required|string',
            'register_date'  => 'nullable|date',
            'expired_date'   => 'nullable|date',
            'is_active'      => 'boolean',
            'notes'          => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members', 'public');
        }

        $member->update($validated);

        return redirect()->route('members.show', $member)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    public function printCard(Member $member)
    {
        $libraryName = Setting::get('library_name', 'Perpustakaan');
        $members = collect([$member]); // Pass as collection so the view can handle both single and multiple
        return view('members.print-card', compact('members', 'libraryName'));
    }

    public function printBulk(Request $request)
    {
        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
        ]);

        $members = Member::whereIn('id', $request->member_ids)->get();
        $libraryName = Setting::get('library_name', 'Perpustakaan');
        
        return view('members.print-card', compact('members', 'libraryName'));
    }

    public function history(Member $member)
    {
        $circulations = $member->circulations()
            ->with('bookItem.book')
            ->latest()
            ->paginate(20);

        return view('members.history', compact('member', 'circulations'));
    }

    public function ajaxSearch(Request $request)
    {
        $members = Member::search($request->q ?? '')
            ->active()
            ->limit(10)
            ->get(['id', 'member_code', 'name', 'member_type', 'expired_date', 'is_active']);

        return response()->json($members->map(fn($m) => [
            'id'          => $m->id,
            'member_code' => $m->member_code,
            'name'        => $m->name,
            'member_type' => $m->member_type,
            'status'      => $m->status_label,
        ]));
    }

}

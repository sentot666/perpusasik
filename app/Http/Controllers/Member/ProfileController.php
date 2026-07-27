<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $member = auth()->user()->member;

        if (!$member) {
            abort(403, 'Anda tidak memiliki profil anggota yang terhubung.');
        }

        $generator = new BarcodeGeneratorPNG();
        $barcodeBase64 = base64_encode($generator->getBarcode($member->barcode, $generator::TYPE_CODE_128, 2, 60));

        return view('member.profile', compact('member', 'barcodeBase64'));
    }

    public function edit(Request $request)
    {
        $member = auth()->user()->member;
        return view('member.profile_edit', compact('member'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        $member = auth()->user()->member;
        
        $data = $request->only(['phone', 'address']);
        
        if ($request->hasFile('photo')) {
            if ($member->photo && \Storage::disk('public')->exists($member->photo)) {
                \Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $member->update($data);

        return redirect()->route('member.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('member.profile')->with('success', 'Password berhasil diubah.');
    }
}

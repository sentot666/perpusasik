<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'            => 'nullable|array',
            'librarian_stamp'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'librarian_signature' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->has('settings') && is_array($request->settings)) {
            foreach ($request->settings as $key => $value) {
                Setting::set($key, $value);
            }
        }

        // Hapus stempel jika diminta
        if ($request->boolean('remove_librarian_stamp')) {
            if (file_exists(public_path('images/stempel.png'))) {
                @unlink(public_path('images/stempel.png'));
            }
        }

        // Hapus tanda tangan jika diminta
        if ($request->boolean('remove_librarian_signature')) {
            if (file_exists(public_path('images/ttd.png'))) {
                @unlink(public_path('images/ttd.png'));
            }
        }

        // Simpan gambar stempel jika ada yang diupload
        if ($request->hasFile('librarian_stamp')) {
            $file = $request->file('librarian_stamp');
            $file->move(public_path('images'), 'stempel.png');
        }

        // Simpan gambar tanda tangan jika ada yang diupload
        if ($request->hasFile('librarian_signature')) {
            $file = $request->file('librarian_signature');
            $file->move(public_path('images'), 'ttd.png');
        }

        return back()->with('success', 'Pengaturan perpustakaan berhasil diperbarui.');
    }
}

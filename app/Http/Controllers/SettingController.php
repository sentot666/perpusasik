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
            'settings' => 'nullable|array',
            'librarian_stamp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->has('settings') && is_array($request->settings)) {
            foreach ($request->settings as $key => $value) {
                Setting::set($key, $value);
            }
        }

        // Simpan gambar stempel/TTD jika ada yang diupload
        if ($request->hasFile('librarian_stamp')) {
            $file = $request->file('librarian_stamp');
            $file->move(public_path('images'), 'stempel.png');
        }

        return back()->with('success', 'Pengaturan perpustakaan berhasil diperbarui.');
    }
}

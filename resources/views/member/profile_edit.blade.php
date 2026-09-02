@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">

    <div class="flex items-center gap-4">
        <a href="{{ route('member.profile') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors group">
            <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Profil
        </a>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">Edit Profil</h1>



    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
        <p class="font-bold mb-2">Ada kesalahan:</p>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    {{-- Profile Edit Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="bi bi-person-fill text-indigo-500"></i>
            <h3 class="font-bold text-slate-700">Informasi Pribadi</h3>
        </div>
        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Photo --}}
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-slate-100 border-2 border-slate-200 flex items-center justify-center flex-shrink-0" id="photoPreview">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover" id="photoImg">
                    @else
                        <i class="bi bi-person text-slate-400 text-4xl" id="photoIcon"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Profil</label>
                    <input type="file" name="photo" id="photoInput" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors cursor-pointer"
                        onchange="previewPhoto(this)">
                    <p class="text-xs text-slate-400 mt-1">Maks. 2MB, format JPG/PNG.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Name (read-only) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" value="{{ $member->name }}" class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm bg-slate-50 text-slate-500 cursor-not-allowed" disabled>
                </div>

                {{-- Member Code (read-only) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Anggota</label>
                    <input type="text" value="{{ $member->member_code }}" class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm bg-slate-50 text-slate-500 cursor-not-allowed font-mono" disabled>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                        class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none bg-white transition-colors"
                        placeholder="08xxxxxxxxxx">
                </div>

                {{-- Email (read-only) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" value="{{ $member->email }}" class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm bg-slate-50 text-slate-500 cursor-not-allowed" disabled>
                </div>
            </div>

            {{-- Address --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none resize-none bg-white transition-colors"
                    placeholder="Masukkan alamat lengkap...">{{ old('address', $member->address) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-gradient-blue text-white font-bold py-2.5 px-8 rounded-xl flex items-center gap-2 shadow-sm">
                    <i class="bi bi-save2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('member.profile') }}" class="py-2.5 px-6 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Change Password Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="bi bi-shield-lock-fill text-amber-500"></i>
            <h3 class="font-bold text-slate-700">Ganti Password</h3>
        </div>
        <form action="{{ route('member.profile.password') }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password"
                    class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm focus:ring-2 focus:ring-amber-200 focus:border-amber-400 outline-none bg-white transition-colors"
                    placeholder="Masukkan password lama...">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm focus:ring-2 focus:ring-amber-200 focus:border-amber-400 outline-none bg-white transition-colors"
                        placeholder="Minimal 8 karakter...">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-xl border border-slate-200 py-2.5 px-4 text-sm focus:ring-2 focus:ring-amber-200 focus:border-amber-400 outline-none bg-white transition-colors"
                        placeholder="Ulangi password baru...">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-8 rounded-xl flex items-center gap-2 shadow-sm transition-colors">
                    <i class="bi bi-shield-check"></i> Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />`;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

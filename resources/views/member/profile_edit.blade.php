@extends('layouts.member')

@section('title', 'Edit Profil')

@section('content')
<div class="space-y-6 pb-8 max-w-2xl mx-auto">

    <div>
        <a href="{{ route('member.profile') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors no-underline">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Profil</span>
        </a>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs">
            <p class="font-bold mb-1">Periksa kembali data Anda:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit Data --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
        <h2 class="font-bold text-slate-800 text-sm mb-4 pb-3 border-b border-slate-100">
            Perbarui Foto & Kontak Siswa
        </h2>

        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            {{-- Photo --}}
            <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 border border-slate-200">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 flex items-center justify-center flex-shrink-0" id="photoPreview">
                    @if($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="font-bold text-lg text-slate-400">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="flex-1">
                    <label class="block font-semibold text-slate-700 mb-1">Foto Profil</label>
                    <input type="file" 
                           name="photo" 
                           id="photoInput" 
                           accept="image/*"
                           class="block w-full text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                           onchange="previewPhoto(this)">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimal ukuran file 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ $member->name }}" class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-slate-100 text-slate-500 cursor-not-allowed" disabled>
                </div>

                <div>
                    <label class="block font-semibold text-slate-500 mb-1">Nomor Induk (NIS)</label>
                    <input type="text" value="{{ $member->member_code }}" class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-slate-100 text-slate-500 font-mono cursor-not-allowed" disabled>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">No. HP / WhatsApp</label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone', $member->phone) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-white focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-500 mb-1">Email</label>
                    <input type="email" value="{{ $member->email }}" class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-slate-100 text-slate-500 cursor-not-allowed" disabled>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Alamat Rumah</label>
                <textarea name="address" rows="2"
                          placeholder="Masukkan alamat..."
                          class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-white focus:border-indigo-500 outline-none resize-none">{{ old('address', $member->address) }}</textarea>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold cursor-pointer">
                    Simpan Perubahan
                </button>
                <a href="{{ route('member.profile') }}" 
                   class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold no-underline">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Form Ganti Password --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
        <h2 class="font-bold text-slate-800 text-sm mb-4 pb-3 border-b border-slate-100">
            Ganti Password
        </h2>

        <form action="{{ route('member.profile.password') }}" method="POST" class="space-y-3 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Password Saat Ini</label>
                <input type="password" 
                       name="current_password"
                       placeholder="Masukkan password saat ini..."
                       class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-white focus:border-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Password Baru</label>
                    <input type="password" 
                           name="password"
                           placeholder="Minimal 8 karakter..."
                           class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-white focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" 
                           name="password_confirmation"
                           placeholder="Ketik ulang password..."
                           class="w-full rounded-xl border border-slate-200 py-2 px-3 bg-white focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold cursor-pointer">
                    Perbarui Password
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

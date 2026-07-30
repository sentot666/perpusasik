@extends('layouts.app')

@section('title', 'Scan Buku Tamu')

@push('styles')
<style>
    /* Fullscreen mode adjustments */
    #scan-container {
        transition: all 0.3s ease;
    }
    .fullscreen-mode #scan-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }
    
    .barcode-input {
        caret-color: transparent; /* Hide cursor to make it look cleaner */
    }
    .barcode-input:focus {
        caret-color: #3b82f6; /* Show blue cursor on focus */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto py-8">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Scan Buku Tamu</h1>
            <p class="text-gray-600">Arahkan scanner ke barcode atau ketik manual ID anggota</p>
        </div>
        <a href="{{ route('guest-books.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div id="scan-container" class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 relative overflow-hidden">
        
        <!-- Toggle Fullscreen Button -->
        <button type="button" id="fullscreen-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" title="Layar Penuh">
            <i class="fas fa-expand text-xl"></i>
        </button>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-50 text-blue-600 rounded-full mb-4">
                <i class="fas fa-barcode text-5xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Mode Scan Aktif</h2>
            <p class="text-gray-500">Tujuan otomatis: <span class="font-semibold text-blue-600">{{ \App\Models\Setting::get('default_guest_purpose', 'Membaca') }}</span></p>
        </div>

        <div class="max-w-md mx-auto">
            <form id="scan-form" onsubmit="return false;">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-qrcode text-gray-400 text-xl"></i>
                    </div>
                    <input type="text" id="barcode-input" name="barcode" 
                           class="barcode-input block w-full pl-12 pr-4 py-4 text-center text-2xl font-bold text-gray-800 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder-gray-300"
                           placeholder="SCAN DI SINI..." 
                           autocomplete="off" 
                           autofocus>
                </div>
                <div class="mt-4 text-center">
                    <span id="scan-status" class="inline-flex items-center text-sm font-medium text-gray-500">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Menunggu scan...
                    </span>
                </div>
            </form>
        </div>
        
        <!-- Result Display Area -->
        <div id="result-area" class="mt-8 hidden flex-col items-center justify-center p-6 bg-green-50 rounded-xl border border-green-200">
            <img id="result-photo" src="" alt="Foto" class="w-24 h-24 object-cover rounded-full shadow-md mb-4 hidden border-4 border-white">
            <h3 id="result-name" class="text-2xl font-bold text-green-800 text-center mb-1">Nama Anggota</h3>
            <p id="result-type" class="text-green-600 font-medium">Tipe / Kelas</p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('barcode-input');
    const form = document.getElementById('scan-form');
    const statusText = document.getElementById('scan-status');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const scanContainer = document.getElementById('scan-container');
    
    // UI Elements for result
    const resultArea = document.getElementById('result-area');
    const resultName = document.getElementById('result-name');
    const resultType = document.getElementById('result-type');
    const resultPhoto = document.getElementById('result-photo');
    
    let isProcessing = false;

    // Keep input focused constantly
    document.addEventListener('click', function(e) {
        if(e.target.id !== 'fullscreen-btn' && !e.target.closest('#fullscreen-btn')) {
            input.focus();
        }
    });

    // Handle Fullscreen
    fullscreenBtn.addEventListener('click', function() {
        document.body.classList.toggle('fullscreen-mode');
        if(document.body.classList.contains('fullscreen-mode')) {
            fullscreenBtn.innerHTML = '<i class="fas fa-compress text-xl"></i>';
        } else {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand text-xl"></i>';
        }
        input.focus();
    });

    // Form submission via Enter (Scanner behavior)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if(isProcessing) return;
        
        const barcode = input.value.trim();
        if(!barcode) return;
        
        processBarcode(barcode);
    });

    function processBarcode(barcode) {
        isProcessing = true;
        input.disabled = true;
        statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        statusText.className = 'inline-flex items-center text-sm font-medium text-blue-500';
        
        // Hide previous result
        resultArea.classList.add('hidden');
        
        fetch('{{ route('guest-books.scan.submit') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Play success sound
                playSound('success');
                
                // Show result
                resultName.textContent = data.member.name;
                resultType.textContent = data.member.type || 'Anggota';
                
                if(data.member.photo) {
                    resultPhoto.src = data.member.photo;
                    resultPhoto.classList.remove('hidden');
                } else {
                    resultPhoto.classList.add('hidden');
                }
                
                resultArea.className = 'mt-8 flex flex-col items-center justify-center p-6 bg-green-50 rounded-xl border border-green-200';
                resultName.className = 'text-2xl font-bold text-green-800 text-center mb-1';
                resultType.className = 'text-green-600 font-medium';
                
                // Show SweetAlert popup temporarily
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                
            } else {
                playSound('error');
                
                resultArea.className = 'mt-8 flex flex-col items-center justify-center p-6 bg-red-50 rounded-xl border border-red-200';
                resultPhoto.classList.add('hidden');
                resultName.textContent = 'Gagal';
                resultName.className = 'text-2xl font-bold text-red-800 text-center mb-1';
                resultType.textContent = data.message;
                resultType.className = 'text-red-600 font-medium text-center';
                
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'error',
                    title: 'Gagal merekam data.',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            playSound('error');
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'error',
                title: 'Terjadi kesalahan sistem.',
                showConfirmButton: false,
                timer: 3000,
            });
        })
        .finally(() => {
            // Reset state
            input.value = '';
            input.disabled = false;
            isProcessing = false;
            statusText.innerHTML = '<span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2 animate-pulse"></span> Menunggu scan...';
            statusText.className = 'inline-flex items-center text-sm font-medium text-gray-500';
            
            setTimeout(() => input.focus(), 100);
        });
    }
    
    function playSound(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gainNode = ctx.createGain();
            
            osc.connect(gainNode);
            gainNode.connect(ctx.destination);
            
            if(type === 'success') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.1);
                gainNode.gain.setValueAtTime(0.5, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.2);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(300, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(200, ctx.currentTime + 0.3);
                gainNode.gain.setValueAtTime(0.5, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.3);
            }
        } catch(e) {
            console.log("Audio not supported");
        }
    }
});
</script>
@endpush

<div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<script>
    function showModernToast(type, message) {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `transform transition-all duration-500 translate-y-10 opacity-0 flex items-start gap-3 p-4 rounded-2xl shadow-2xl backdrop-blur-md pointer-events-auto min-w-[300px] max-w-[400px] border border-white/20`;
        
        let icon = '';
        if (type === 'success') {
            toast.classList.add('bg-emerald-500/90', 'text-white', 'shadow-emerald-500/20');
            icon = `<i class="bi bi-check-circle-fill text-xl drop-shadow-sm"></i>`;
        } else if (type === 'error') {
            toast.classList.add('bg-rose-500/90', 'text-white', 'shadow-rose-500/20');
            icon = `<i class="bi bi-x-circle-fill text-xl drop-shadow-sm"></i>`;
        } else if (type === 'warning') {
            toast.classList.add('bg-amber-500/90', 'text-white', 'shadow-amber-500/20');
            icon = `<i class="bi bi-exclamation-triangle-fill text-xl drop-shadow-sm"></i>`;
        } else {
            toast.classList.add('bg-slate-800/90', 'text-white', 'shadow-slate-800/20');
            icon = `<i class="bi bi-info-circle-fill text-xl drop-shadow-sm"></i>`;
        }

        toast.innerHTML = `
            <div class="flex-shrink-0 pt-0.5">${icon}</div>
            <div class="flex-1 font-medium text-sm leading-relaxed">${message}</div>
            <button onclick="closeToast(this.parentElement)" class="flex-shrink-0 w-6 h-6 flex items-center justify-center opacity-70 hover:opacity-100 transition-opacity bg-white/10 rounded-full hover:bg-white/20 focus:outline-none ml-2">
                <i class="bi bi-x text-lg"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });
        });
        
        // Auto remove
        setTimeout(() => {
            closeToast(toast);
        }, 4000);
    }
    
    function closeToast(toast) {
        if (!toast) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-10', 'opacity-0', 'scale-95');
        setTimeout(() => toast.remove(), 400);
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showModernToast('success', "{{ session('success') }}");
        @endif
        @if(session('error'))
            showModernToast('error', "{{ session('error') }}");
        @endif
        @if(session('warning'))
            showModernToast('warning', "{{ session('warning') }}");
        @endif
        @if(session('info'))
            showModernToast('info', "{{ session('info') }}");
        @endif
    });
</script>

import './bootstrap.js';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Preconfigured SweetAlert2 toast for quick notifications
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// Sidebar mobile toggle & responsive behavior
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    const closeSidebar = () => {
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    };

    const openSidebar = () => {
        if (sidebar) sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
    };

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Close sidebar on link click on mobile view
    if (sidebar) {
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) { // < lg breakpoint
                    closeSidebar();
                }
            });
        });
    }

    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            if (overlay) overlay.classList.add('hidden');
        }
    });
});

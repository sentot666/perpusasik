import './bootstrap.js';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Auto Refresh Widget Definition
window.autoRefreshWidget = function () {
    return {
        open: false,
        interval: 0,
        progress: 100,
        label: 'Auto Refresh: Off',
        timer: null,
        timeLeft: 0,
        options: [
            { value: 0, label: 'Off' },
            { value: 10, label: '10 detik' },
            { value: 30, label: '30 detik' },
            { value: 60, label: '1 menit' },
            { value: 300, label: '5 menit' },
        ],

        init() {
            const currentPath = window.location.pathname.toLowerCase();
            const allowed = ['dashboard', 'sirkulasi', 'circulations', 'users', 'members', 'books', 'guest-books'];
            const isAllowed = allowed.some(p => currentPath.includes(p));

            const container = document.getElementById('autoRefreshContainer');
            if (!isAllowed) {
                if (container) container.classList.add('hidden');
                return;
            } else {
                if (container) container.classList.remove('hidden');
            }

            try {
                this.interval = parseInt(localStorage.getItem('auto_refresh_interval') || '0');
            } catch (e) { this.interval = 0; }

            this.updateLabel();
            this.startTimer();
        },

        setInterval(val) {
            this.interval = val;
            try { localStorage.setItem('auto_refresh_interval', val); } catch (e) { }
            this.updateLabel();
            this.startTimer();
        },

        updateLabel() {
            const opt = this.options.find(o => o.value === this.interval);
            this.label = opt ? (this.interval === 0 ? 'Auto Refresh: Off' : `Auto: ${opt.label}`) : 'Auto Refresh: Off';
        },

        startTimer() {
            if (this.timer) clearInterval(this.timer);
            if (this.interval === 0) { this.progress = 100; return; }

            this.timeLeft = this.interval;
            this.progress = 100;

            this.timer = setInterval(() => {
                const active = document.activeElement;
                if (active && ['INPUT', 'SELECT', 'TEXTAREA'].includes(active.tagName)) {
                    this.timeLeft = this.interval;
                    this.progress = 100;
                    return;
                }
                this.timeLeft--;
                this.progress = (this.timeLeft / this.interval) * 100;
                if (this.timeLeft <= 0) {
                    clearInterval(this.timer);
                    this.refreshContent();
                }
            }, 1000);
        },

        refreshContent() {
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const cur = document.querySelector('.content-area');
                    const next = doc.querySelector('.content-area');
                    if (cur && next) cur.innerHTML = next.innerHTML;
                })
                .catch(e => console.error('[AutoRefresh]', e))
                .finally(() => this.startTimer());
        }
    };
};

Alpine.data('autoRefreshWidget', window.autoRefreshWidget);
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

// Sidebar collapsible & responsive drawer behavior
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    if (!sidebar) return;

    // Mobile Drawer Operations
    const closeMobileSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        body.classList.remove('overflow-hidden');
    };

    const openMobileSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        body.classList.add('overflow-hidden');
    };

    // Desktop Collapse Operations
    const toggleDesktopSidebar = () => {
        const isCollapsed = body.classList.toggle('sidebar-collapsed');
        document.documentElement.classList.toggle('sidebar-collapsed', isCollapsed);
        try {
            localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch (e) {}
    };

    // Unified Toggle Button
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (window.innerWidth < 1024) {
                // Mobile behavior: drawer open/close
                if (sidebar.classList.contains('-translate-x-full')) {
                    openMobileSidebar();
                } else {
                    closeMobileSidebar();
                }
            } else {
                // Desktop behavior: collapse / expand mini sidebar
                toggleDesktopSidebar();
            }
        });
    }

    // Mobile Close Button
    if (sidebarClose) {
        sidebarClose.addEventListener('click', (e) => {
            e.stopPropagation();
            closeMobileSidebar();
        });
    }

    // Overlay Click
    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }

    // ESC Key to close on mobile
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && window.innerWidth < 1024 && !sidebar.classList.contains('-translate-x-full')) {
            closeMobileSidebar();
        }
    });

    // Close mobile drawer when clicking navigation links
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeMobileSidebar();
            }
        });
    });

    // Handle Window Resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            if (overlay) overlay.classList.add('hidden');
            body.classList.remove('overflow-hidden');
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true') {
                    body.classList.add('sidebar-collapsed');
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}

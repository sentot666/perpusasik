import './bootstrap.js';

// Bootstrap 5 JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Sidebar toggle
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Auto-collapse active submenu
    const activeItems = document.querySelectorAll('.sidebar .nav-link.active');
    activeItems.forEach(item => {
        const collapse = item.nextElementSibling;
        if (collapse && collapse.classList.contains('collapse')) {
            const bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });
            bsCollapse.show();
        }
    });

    // Initialize all tooltips
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(el => new bootstrap.Tooltip(el));

    // Initialize all popovers
    const popoverEls = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverEls.forEach(el => new bootstrap.Popover(el));
});

// ============================================
// MENU BURGER (TOGGLE) — VERSION ROBUSTE
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    const toggle = document.getElementById('menuToggle');
    const menu = document.getElementById('sideMenu');
    const close = document.getElementById('closeMenu');
    const overlay = document.getElementById('menuOverlay');

    // Vérifier que tous les éléments existent
    if (!toggle || !menu || !close || !overlay) {
        console.warn('Menu burger : certains éléments sont manquants sur cette page.');
        return;
    }

    function openMenu() {
        menu.style.transform = 'translateX(0)';
        menu.style.transition = 'transform 0.3s ease-in-out';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeMenuFn() {
        menu.style.transform = 'translateX(100%)';
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    function toggleMenu() {
        if (menu.style.transform === 'translateX(0px)') {
            closeMenuFn();
        } else {
            openMenu();
        }
    }

    toggle.addEventListener('click', toggleMenu);
    close.addEventListener('click', closeMenuFn);
    overlay.addEventListener('click', closeMenuFn);

    // Si la fenêtre est redimensionnée en desktop, fermer le menu
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && menu.style.transform === 'translateX(0px)') {
            closeMenuFn();
        }
    });

});
// ============================================
// SPORTMAP BÉNIN — APP.JS
// Gestion de la page d'accueil
// ============================================

import { getCentres, getDisciplines, getCommunes } from './api.js';
import { initMap, updateMap, locateUser } from './map.js';

// État global
let currentPage = 1;
let perPage = 9;
let currentFilters = {};
let allCentres = [];

// ============================================
// ÉLÉMENTS DOM
// ============================================

const searchInput = document.getElementById('searchInput');
const disciplineFilter = document.getElementById('disciplineFilter');
const communeFilter = document.getElementById('communeFilter');
const searchBtn = document.getElementById('searchBtn');
const resultsContainer = document.getElementById('resultsContainer');
const paginationContainer = document.getElementById('pagination');

// ============================================
// CARROUSEL HERO (activé)
// ============================================

function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length === 0) return;

    let current = 0;

    // Initialiser la première slide comme active
    slides.forEach((s, i) => {
        s.classList.toggle('active', i === 0);
    });

    setInterval(() => {
        slides.forEach(s => s.classList.remove('active'));
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 5000);
}

// ============================================
// HEADER TRANSPARENT → SOLIDE AU SCROLL
// ============================================

function initHeaderScroll() {
    const header = document.getElementById('mainHeader');
    if (!header) return;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 80) {
            header.classList.add('header-solid');
            header.classList.remove('header-transparent');
        } else {
            header.classList.add('header-transparent');
            header.classList.remove('header-solid');
        }
    });
}

// ============================================
// CHARGEMENT DES FILTRES
// ============================================

async function loadFilters() {
    try {
        // Charger les disciplines
        const disciplines = await getDisciplines();
        disciplineFilter.innerHTML = `<option value="">Disciplines</option>`;
        disciplines.forEach(discipline => {
            const option = document.createElement('option');
            option.value = discipline.id_discipline;
            option.textContent = discipline.nom;
            disciplineFilter.appendChild(option);
        });

        // Charger les communes
        const communes = await getCommunes();
        communeFilter.innerHTML = `<option value="">Communes</option>`;
        communes.forEach(commune => {
            const option = document.createElement('option');
            option.value = commune;
            option.textContent = commune;
            communeFilter.appendChild(option);
        });

    } catch (error) {
        console.error('Erreur lors du chargement des filtres:', error);
    }
}

// ============================================
// CHARGEMENT DES CENTRES
// ============================================

async function loadCentres() {
    try {
        const params = {
            per_page: perPage,
            page: currentPage,
            ...currentFilters
        };

        const response = await getCentres(params);
        allCentres = response.data || [];

        // Mettre à jour la carte
        updateMap(allCentres);

        // Afficher les résultats
        renderCentres(allCentres);
        renderPagination(response);

    } catch (error) {
        console.error('Erreur lors du chargement des centres:', error);
        resultsContainer.innerHTML = `
            <div class="col-span-full text-center py-10">
                <p class="text-red-600">Impossible de charger les centres.</p>
            </div>
        `;
    }
}

// ============================================
// AFFICHAGE DES CENTRES
// ============================================

function renderCentres(centres) {
    if (!centres || centres.length === 0) {
        resultsContainer.innerHTML = `
            <div class="col-span-full text-center py-10">
                <p class="text-[#C4B5FD]">Aucun centre trouvé.</p>
                <p class="text-[#A5B4FC] text-sm mt-1">Essayez de modifier vos critères de recherche.</p>
            </div>
        `;
        return;
    }

    resultsContainer.innerHTML = centres.map(centre => `
        <div class="glass-card p-5">
            <div class="flex items-start gap-4">
                ${centre.logo ? `
                    <img src="${centre.logo}" alt="${centre.nom}" class="w-14 h-14 rounded-full object-cover flex-shrink-0 border border-[#A855F7]/30" />
                ` : `
                    <div class="w-14 h-14 rounded-full bg-[#1F0B3A] flex items-center justify-center flex-shrink-0 border border-[#A855F7]/30">
                        <i class="fas fa-building text-[#C4B5FD] text-xl"></i>
                    </div>
                `}
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-white text-lg truncate">${centre.nom}</h3>
                    <p class="text-[#C4B5FD] text-sm">
                        <i class="fas fa-map-marker-alt text-[#FACC15] w-4"></i>
                        ${centre.quartier || 'Quartier non spécifié'}, ${centre.commune || 'Commune non spécifiée'}
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-sm text-[#FACC15]">
                            <i class="fas fa-star text-[#FACC15]"></i> 4.8
                        </span>
                        <span class="text-sm text-[#A5B4FC]">•</span>
                        <span class="text-sm text-[#C4B5FD]">
                            ${centre.disciplines && centre.disciplines.length > 0 
                                ? centre.disciplines.map(d => d.nom).join(', ') 
                                : 'Aucune discipline'}
                        </span>
                    </div>
                    ${centre.telephone ? `
                        <div class="mt-2 flex items-center gap-3">
                            <a href="tel:${centre.telephone}" class="text-[#C4B5FD] text-sm hover:text-[#FACC15] transition">
                                <i class="fas fa-phone text-[#FACC15] mr-1"></i> ${centre.telephone}
                            </a>
                        </div>
                    ` : ''}
                    <a href="/centre.html?id=${centre.id_centre}" 
                       class="btn-secondary mt-3 inline-block px-4 py-1.5 rounded-full text-sm font-medium transition">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
    `).join('');
}

// ============================================
// PAGINATION
// ============================================

function renderPagination(response) {
    if (!response || !response.last_page || response.last_page <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }

    let html = '';

    if (response.prev_page_url) {
        html += `<button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" data-page="${response.current_page - 1}">
                    <i class="fas fa-chevron-left"></i>
                 </button>`;
    } else {
        html += `<button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed" disabled>
                    <i class="fas fa-chevron-left"></i>
                 </button>`;
    }

    for (let i = 1; i <= response.last_page; i++) {
        if (i === response.current_page) {
            html += `<button class="px-4 py-2 bg-[#FF6B00] text-white rounded-lg font-medium" data-page="${i}">${i}</button>`;
        } else if (i === 1 || i === response.last_page || Math.abs(i - response.current_page) <= 2) {
            html += `<button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" data-page="${i}">${i}</button>`;
        } else if (i === response.current_page - 3 || i === response.current_page + 3) {
            html += `<span class="px-2 text-gray-400">...</span>`;
        }
    }

    if (response.next_page_url) {
        html += `<button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" data-page="${response.current_page + 1}">
                    <i class="fas fa-chevron-right"></i>
                 </button>`;
    } else {
        html += `<button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed" disabled>
                    <i class="fas fa-chevron-right"></i>
                 </button>`;
    }

    paginationContainer.innerHTML = html;

    document.querySelectorAll('[data-page]').forEach(button => {
        button.addEventListener('click', (e) => {
            const page = parseInt(e.target.closest('[data-page]').dataset.page);
            if (page && page !== currentPage) {
                currentPage = page;
                loadCentres();
                document.querySelector('#results-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// ============================================
// RECHERCHE
// ============================================

function handleSearch() {
    const search = searchInput.value.trim();
    const discipline = disciplineFilter.value;
    const commune = communeFilter.value;

    currentFilters = {};

    if (search) currentFilters.search = search;
    if (discipline) currentFilters.id_discipline = discipline;
    if (commune) currentFilters.commune = commune;

    currentPage = 1;
    loadCentres();
}

// ============================================
// INITIALISATION
// ============================================

document.addEventListener('DOMContentLoaded', async () => {
    try {
        // 1. Initialiser le carrousel (ACTIVÉ)
        initHeroSlider();

        // 2. Initialiser le header au scroll
        initHeaderScroll();

        // 3. Initialiser la carte (vide)
        initMap([], 6.36, 2.39);

        // 4. Charger les filtres
        await loadFilters();

        // 5. Charger les centres (la carte sera mise à jour)
        await loadCentres();

        // 6. Écouter les événements
        searchBtn.addEventListener('click', handleSearch);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleSearch();
        });

        disciplineFilter.addEventListener('change', handleSearch);
        communeFilter.addEventListener('change', handleSearch);

        // 7. Demander la géolocalisation (après chargement)
        setTimeout(() => {
            locateUser();
        }, 1000);

    } catch (error) {
        console.error('Erreur lors du chargement initial:', error);
        resultsContainer.innerHTML = `
            <div class="col-span-full text-center py-10">
                <p class="text-red-600">Une erreur est survenue lors du chargement des données.</p>
                <p class="text-gray-500 text-sm mt-2">Veuillez réessayer plus tard.</p>
            </div>
        `;
    }
});

// ============================================
// CONTACT MODALE
// ============================================

const contactBtn = document.getElementById('contactBtn');
const contactModal = document.getElementById('contactModal');
const closeContactModal = document.getElementById('closeContactModal');
const contactForm = document.getElementById('contactForm');
const contactSubmit = document.getElementById('contactSubmit');
const contactError = document.getElementById('contactError');
const contactSuccess = document.getElementById('contactSuccess');

// Ouvrir la modale
contactBtn?.addEventListener('click', () => {
    contactModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

// Fermer la modale
closeContactModal?.addEventListener('click', closeModal);
contactModal?.addEventListener('click', (e) => {
    if (e.target === contactModal) closeModal();
});

function closeModal() {
    contactModal.classList.add('hidden');
    document.body.style.overflow = '';
    resetForm();
}

function resetForm() {
    contactForm.reset();
    contactError.classList.add('hidden');
    contactSuccess.classList.add('hidden');
    contactSubmit.disabled = false;
    contactSubmit.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer';
}

// Soumission du formulaire
contactForm?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nom = document.getElementById('nom').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    contactError.classList.add('hidden');
    contactSuccess.classList.add('hidden');
    contactSubmit.disabled = true;
    contactSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';

    try {
        const response = await fetch('/api/visiteur/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ nom, email, message }),
        });

        if (!response.ok) {
            const err = await response.json();
            throw new Error(err.message || 'Une erreur est survenue.');
        }

        const data = await response.json();
        contactSuccess.classList.remove('hidden');
        contactSuccess.textContent = data.message || '✅ Message envoyé avec succès.';
        contactForm.reset();

    } catch (error) {
        contactError.textContent = error.message || 'Erreur lors de l\'envoi. Veuillez réessayer.';
        contactError.classList.remove('hidden');
    } finally {
        contactSubmit.disabled = false;
        contactSubmit.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer';
    }
    
});

// ============================================
// MENU BURGER (Mobile)
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenu = document.getElementById('closeMenu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function () {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        if (closeMenu) {
            closeMenu.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }

        mobileMenu.addEventListener('click', function (e) {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Fermer le menu quand on clique sur un lien
        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    }

    // ============================================
    // FAQ ACCORDÉON
    // ============================================

    document.querySelectorAll('.faq-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.fa-chevron-down');

            // Fermer les autres FAQ
            document.querySelectorAll('.faq-answer').forEach(function (a) {
                if (a !== answer) {
                    a.classList.add('hidden');
                    const otherIcon = a.closest('.bg-white').querySelector('.fa-chevron-down');
                    if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Ouvrir/fermer celui-ci
            answer.classList.toggle('hidden');
            if (icon) {
                icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });

});

// ============================================
// MENU LATÉRAL (GLISSANT DEPUIS LA DROITE)
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById('menuToggle');
    const sideMenu = document.getElementById('sideMenu');
    const closeMenu = document.getElementById('closeMenu');
    const overlay = document.getElementById('menuOverlay');
    const menuIcon = document.getElementById('menuIcon');

    function openMenu() {
        sideMenu.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        if (menuIcon) {
            menuIcon.classList.remove('fa-bars');
            menuIcon.classList.add('fa-times');
        }
    }

    function closeMenuFunction() {
        sideMenu.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
        if (menuIcon) {
            menuIcon.classList.remove('fa-times');
            menuIcon.classList.add('fa-bars');
        }
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            if (sideMenu.classList.contains('translate-x-full')) {
                openMenu();
            } else {
                closeMenuFunction();
            }
        });
    }

    if (closeMenu) {
        closeMenu.addEventListener('click', closeMenuFunction);
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenuFunction);
    }

    // Fermer le menu après avoir cliqué sur un lien
    sideMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenuFunction);
    });

});

// ============================================
// AFFICHER / CACHER "À PROPOS"
// ============================================

const aboutBtn = document.getElementById('aboutBtn');
const aboutSection = document.getElementById('about-section');

if (aboutBtn && aboutSection) {
    aboutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        aboutSection.classList.toggle('hidden');

        // Scroller vers la section si elle est ouverte
        if (!aboutSection.classList.contains('hidden')) {
            aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
}

// ============================================
// FLÈCHE RETOUR EN HAUT
// ============================================

const scrollBtn = document.getElementById('scrollToTopBtn');

window.addEventListener('scroll', function () {
    if (window.scrollY > 400) {
        scrollBtn.classList.remove('hidden');
    } else {
        scrollBtn.classList.add('hidden');
    }
});

scrollBtn.addEventListener('click', function () {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
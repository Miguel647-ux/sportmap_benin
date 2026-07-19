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
        disciplineFilter.innerHTML = `<option value="">Toutes les disciplines</option>`;
        disciplines.forEach(discipline => {
            const option = document.createElement('option');
            option.value = discipline.id_discipline;
            option.textContent = discipline.nom;
            disciplineFilter.appendChild(option);
        });

        // Charger les communes
        const communes = await getCommunes();
        communeFilter.innerHTML = `<option value="">Toutes les communes</option>`;
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
                <p class="text-gray-500">Aucun centre trouvé.</p>
                <p class="text-gray-400 text-sm mt-1">Essayez de modifier vos critères de recherche.</p>
            </div>
        `;
        return;
    }

    resultsContainer.innerHTML = centres.map(centre => `
        <div class="card p-5">
            <div class="flex items-start gap-4">
                ${centre.logo ? `
                    <img src="${centre.logo}" alt="${centre.nom}" class="w-14 h-14 rounded-full object-cover flex-shrink-0" />
                ` : `
                    <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-building text-gray-400 text-xl"></i>
                    </div>
                `}
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-[#0A0A0A] text-lg truncate">${centre.nom}</h3>
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-map-marker-alt text-[#FF6B00] w-4"></i>
                        ${centre.quartier || 'Quartier non spécifié'}, ${centre.commune || 'Commune non spécifiée'}
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-sm text-[#FF6B00]">
                            <i class="fas fa-star text-[#FF6B00]"></i> 4.8
                        </span>
                        <span class="text-sm text-gray-400">•</span>
                        <span class="text-sm text-gray-500">
                            ${centre.disciplines && centre.disciplines.length > 0 
                                ? centre.disciplines.map(d => d.nom).join(', ') 
                                : 'Aucune discipline'}
                        </span>
                    </div>
                    ${centre.telephone ? `
                        <div class="mt-2 flex items-center gap-3">
                            <a href="tel:${centre.telephone}" class="text-gray-500 text-sm hover:text-[#FF6B00] transition">
                                <i class="fas fa-phone text-[#FF6B00] mr-1"></i> ${centre.telephone}
                            </a>
                        </div>
                    ` : ''}
                    <a href="/centre.html?id=${centre.id_centre}" 
                       class="btn-outline-orange mt-3 inline-block px-4 py-1.5 rounded-lg text-sm font-medium transition">
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
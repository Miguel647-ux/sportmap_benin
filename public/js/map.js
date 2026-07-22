// ============================================
// SPORTMAP BÉNIN — MAP.JS
// Gestion de la carte Leaflet
// ============================================

let map;
let markersLayer;
let userMarker;

/**
 * Initialise la carte Leaflet
 * @param {Array} centres - Liste des centres à afficher
 * @param {number} lat - Latitude par défaut
 * @param {number} lng - Longitude par défaut
 */
export function initMap(centres = [], lat = 6.36, lng = 2.39) {
    // Créer la carte
   map = L.map('map', {
    scrollWheelZoom: false
}).setView([lat, lng], 12);

    // Ajouter le fond de carte (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Créer le layer pour les marqueurs
    markersLayer = L.layerGroup().addTo(map);

    // Ajouter les centres sur la carte
    if (centres && centres.length > 0) {
        addMarkers(centres);
    }

    // Géolocalisation de l'utilisateur
    locateUser();

    // Gérer le zoom sur les marqueurs
    map.on('zoomend', () => {
        // On pourrait ajuster les marqueurs selon le zoom
    });
}

/**
 * Ajoute des marqueurs sur la carte
 * @param {Array} centres - Liste des centres
 */
export function addMarkers(centres) {

      if (!markersLayer) {
        console.warn('Markers layer not initialized yet');
        return;
    }
    
    // Vider les marqueurs existants
    markersLayer.clearLayers();

    centres.forEach(centre => {
        if (centre.latitude && centre.longitude) {
            const marker = L.marker([centre.latitude, centre.longitude], {
                title: centre.nom
            });

            // Popup au clic sur le marqueur
            marker.bindPopup(`
                <div class="p-2">
                    <h3 class="font-bold text-[#0A0A0A]">${centre.nom}</h3>
                    <p class="text-sm text-gray-600">${centre.quartier}, ${centre.commune}</p>
                    <a href="/centre.html?id=${centre.id_centre}" 
                       class="text-[#FF6B00] font-medium text-sm hover:underline">
                       Voir détails
                    </a>
                </div>
            `);

            marker.addTo(markersLayer);
        }
    });

    // Ajuster la vue si des marqueurs existent
    if (centres.length > 0) {
        try{
            const bounds = markersLayer.getBounds();
        if (bounds.isValid()) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        } catch (e) {
        
    }
    } 
}

/**
 * Localise l'utilisateur
 */
export function locateUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Ajouter un marqueur pour l'utilisateur
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                userMarker = L.circleMarker([lat, lng], {
                    radius: 8,
                    color: '#FF6B00',
                    fillColor: '#FF6B00',
                    fillOpacity: 0.7
                }).addTo(map);

                userMarker.bindPopup('Vous êtes ici');

                // Centrer la carte sur l'utilisateur
                map.setView([lat, lng], 13);
            },
            (error) => {
                console.warn('Géolocalisation refusée ou indisponible', error);
                // Garder la vue par défaut
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    } else {
        console.warn('La géolocalisation n\'est pas supportée par ce navigateur');
    }
}

/**
 * Met à jour la carte avec de nouveaux centres
 * @param {Array} centres - Nouvelle liste de centres
 */
export function updateMap(centres) {
    addMarkers(centres);
}

/**
 * Retourne l'instance de la carte
 * @returns {Object} - Instance Leaflet
 */
export function getMap() {
    return map;
}

/**
 * Retourne le layer des marqueurs
 * @returns {Object} - Layer Leaflet
 */
export function getMarkersLayer() {
    return markersLayer;
}
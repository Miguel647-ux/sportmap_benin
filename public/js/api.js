// ============================================
// SPORTMAP BÉNIN — API.JS
// Appels vers le backend Laravel
// ============================================

const API_BASE_URL = 'http://127.0.0.1:8000/api';

/**
 * Récupère la liste des centres (public)
 * @param {Object} params - Paramètres de recherche (filtres, pagination)
 * @returns {Promise<Object>} - Réponse de l'API
 */
export async function getCentres(params = {}) {
    const url = new URL(`${API_BASE_URL}/visiteur/centres`);

    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.append(key, params[key]);
        }
    });

    const response = await fetch(url);

    if (!response.ok) {
        throw new Error(`Erreur API: ${response.status}`);
    }

    return response.json();
}

/**
 * Récupère les détails d'un centre
 * @param {number} id - ID du centre
 * @returns {Promise<Object>} - Détails du centre
 */
export async function getCentreById(id) {
    const response = await fetch(`${API_BASE_URL}/visiteur/centres/${id}`);

    if (!response.ok) {
        throw new Error(`Erreur API: ${response.status}`);
    }

    return response.json();
}

/**
 * Récupère la liste des disciplines
 * @returns {Promise<Array>} - Liste des disciplines
 */
export async function getDisciplines() {
    const response = await fetch(`${API_BASE_URL}/visiteur/disciplines`);

    if (!response.ok) {
        throw new Error(`Erreur API: ${response.status}`);
    }

    return response.json();
}

/**
 * Récupère la liste des communes (depuis les centres)
 * @returns {Promise<Array>} - Liste des communes uniques
 */
export async function getCommunes() {
    const response = await getCentres({ per_page: 1000 });
    const communes = response.data
        .map(centre => centre.commune)
        .filter(commune => commune)
        .filter((value, index, self) => self.indexOf(value) === index)
        .sort();

    return communes;
}

/**
 * Connexion de l'administrateur
 * @param {string} email - Email de l'admin
 * @param {string} motDePasse - Mot de passe
 * @returns {Promise<Object>} - Token et données admin
 */
export async function loginAdmin(email, motDePasse) {
    const response = await fetch(`${API_BASE_URL}/admin/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, mot_de_passe: motDePasse }),
    });

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Erreur de connexion');
    }

    return response.json();
}

/**
 * Déconnexion de l'administrateur
 * @param {string} token - Token d'authentification
 * @returns {Promise<Object>}
 */
export async function logoutAdmin(token) {
    const response = await fetch(`${API_BASE_URL}/admin/logout`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur de déconnexion');
    }

    return response.json();
}

/**
 * Récupère les centres (admin)
 * @param {string} token - Token d'authentification
 * @param {Object} params - Paramètres de recherche
 * @returns {Promise<Object>}
 */
export async function getAdminCentres(token, params = {}) {
    const url = new URL(`${API_BASE_URL}/admin/centres`);

    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.append(key, params[key]);
        }
    });

    const response = await fetch(url, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`Erreur API: ${response.status}`);
    }

    return response.json();
}

/**
 * Crée un nouveau centre (admin)
 * @param {string} token - Token d'authentification
 * @param {Object} data - Données du centre
 * @returns {Promise<Object>}
 */
export async function createCentre(token, data) {
    const response = await fetch(`${API_BASE_URL}/admin/centres`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Erreur de création');
    }

    return response.json();
}

/**
 * Met à jour un centre (admin)
 * @param {string} token - Token d'authentification
 * @param {number} id - ID du centre
 * @param {Object} data - Données à mettre à jour
 * @returns {Promise<Object>}
 */
export async function updateCentre(token, id, data) {
    const response = await fetch(`${API_BASE_URL}/admin/centres/${id}`, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Erreur de mise à jour');
    }

    return response.json();
}

/**
 * Supprime un centre (admin)
 * @param {string} token - Token d'authentification
 * @param {number} id - ID du centre
 * @returns {Promise<Object>}
 */
export async function deleteCentre(token, id) {
    const response = await fetch(`${API_BASE_URL}/admin/centres/${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur de suppression');
    }

    return response.json();
}

/**
 * Publie un centre (admin)
 * @param {string} token - Token d'authentification
 * @param {number} id - ID du centre
 * @returns {Promise<Object>}
 */
export async function publierCentre(token, id) {
    const response = await fetch(`${API_BASE_URL}/admin/centres/${id}/publier`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur lors de la publication');
    }

    return response.json();
}

/**
 * Dépublie un centre (admin)
 * @param {string} token - Token d'authentification
 * @param {number} id - ID du centre
 * @returns {Promise<Object>}
 */
export async function depublierCentre(token, id) {
    const response = await fetch(`${API_BASE_URL}/admin/centres/${id}/depublier`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur lors de la dépublication');
    }

    return response.json();
}

/**
 * Upload d'une photo (admin)
 * @param {string} token - Token d'authentification
 * @param {number} centreId - ID du centre
 * @param {File} file - Fichier image
 * @param {string} type - 'logo' ou 'photo'
 * @returns {Promise<Object>}
 */
export async function uploadPhoto(token, centreId, file, type = 'photo') {
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('type', type);

    const response = await fetch(`${API_BASE_URL}/admin/centres/${centreId}/photos`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
        },
        body: formData,
    });

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Erreur lors de l\'upload');
    }

    return response.json();
}

/**
 * Supprime une photo (admin)
 * @param {string} token - Token d'authentification
 * @param {number} photoId - ID de la photo
 * @returns {Promise<Object>}
 */
export async function deletePhoto(token, photoId) {
    const response = await fetch(`${API_BASE_URL}/admin/photos/${photoId}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur de suppression de la photo');
    }

    return response.json();
}

/**
 * Récupère les disciplines (admin)
 * @param {string} token - Token d'authentification
 * @param {Object} params - Paramètres de pagination
 * @returns {Promise<Object>}
 */
export async function getAdminDisciplines(token, params = {}) {
    const url = new URL(`${API_BASE_URL}/admin/disciplines`);

    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.append(key, params[key]);
        }
    });

    const response = await fetch(url, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`Erreur API: ${response.status}`);
    }

    return response.json();
}

/**
 * Crée une discipline (admin)
 * @param {string} token - Token d'authentification
 * @param {Object} data - Données de la discipline
 * @returns {Promise<Object>}
 */
export async function createDiscipline(token, data) {
    const response = await fetch(`${API_BASE_URL}/admin/disciplines`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Erreur de création');
    }

    return response.json();
}

/**
 * Supprime une discipline (admin)
 * @param {string} token - Token d'authentification
 * @param {number} id - ID de la discipline
 * @returns {Promise<Object>}
 */
export async function deleteDiscipline(token, id) {
    const response = await fetch(`${API_BASE_URL}/admin/disciplines/${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur de suppression');
    }

    return response.json();
}
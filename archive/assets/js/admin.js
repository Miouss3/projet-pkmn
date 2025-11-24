/**
 * Scripts JavaScript pour l'administration
 * 
 * Ce fichier contient tout le JavaScript nécessaire pour le backoffice
 */

/**
 * Affiche le formulaire de bannissement d'un utilisateur
 * @param {string} userId - L'ID de l'utilisateur à bannir
 * @param {string} username - Le nom d'utilisateur
 */
function showBanForm(userId, username) {
    // Met à jour le nom d'utilisateur dans le titre de la modal
    document.getElementById('banUsername').textContent = username;
    
    // Récupère le formulaire et l'URL de base
    const banForm = document.getElementById('banForm');
    const baseUrl = banForm ? banForm.dataset.baseUrl || '' : '';
    
    // Met à jour l'action du formulaire avec l'ID de l'utilisateur
    if (banForm) {
        banForm.action = baseUrl + userId + '/ban';
    }
    
    // Affiche la modal
    const banModal = document.getElementById('banModal');
    if (banModal) {
        banModal.style.display = 'flex';
    }
}

/**
 * Ferme la modal de bannissement
 */
function closeBanModal() {
    const banModal = document.getElementById('banModal');
    if (banModal) {
        banModal.style.display = 'none';
    }
}

/**
 * Code exécuté quand la page est chargée
 * On attend que le DOM soit prêt avant d'attacher les événements
 */
document.addEventListener('DOMContentLoaded', function() {
    // ===== Gestion des confirmations de suppression =====
    // Trouve tous les boutons qui ont l'attribut data-confirm
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    
    // Pour chaque bouton, on ajoute un écouteur d'événement
    confirmButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            // Récupère le message de confirmation
            const message = this.getAttribute('data-confirm');
            
            // Si l'utilisateur annule, on empêche l'envoi du formulaire
            if (message && !confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // ===== Gestion des boutons de bannissement =====
    // Trouve tous les boutons de bannissement (avec data-user-id)
    const banButtons = document.querySelectorAll('.ban-user-btn, [data-user-id]');
    
    banButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Récupère les données de l'utilisateur depuis les attributs data-*
            const userId = this.getAttribute('data-user-id');
            const username = this.getAttribute('data-username');
            
            // Affiche le formulaire de bannissement
            if (userId && username) {
                showBanForm(userId, username);
            }
        });
    });

    // ===== Gestion du bouton de fermeture de la modal =====
    const closeModalBtn = document.getElementById('closeBanModal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeBanModal);
    }

    // ===== Gestion des selects de rôle =====
    // Quand on change le rôle d'un utilisateur, on soumet automatiquement le formulaire
    const roleSelects = document.querySelectorAll('.role-select');
    roleSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            // Soumet le formulaire parent
            this.form.submit();
        });
    });
});


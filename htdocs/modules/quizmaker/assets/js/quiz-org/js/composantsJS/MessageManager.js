export const MessageManager = {

// Initialisation unique
    init(containerId) {
        this.containerId = containerId;
    },
    
    /**
     * Affiche un message dans une fenêtre flottante stylisée.
     * @param {string} message - Le texte à afficher (peut contenir des <br>).
     * @param {number} duration - Durée en secondes avant fermeture auto et clic.
     * @param {string} btnId - ID du bouton à cliquer à la fin.
     * @param {Object} [options] - Options de style pour la fenêtre.
     */
    show(message, duration, btnId, options = {}) {
        // 1. Déstructuration des options avec valeurs par défaut (Style "image_0.png")
        const { 
            background = '#FDE9FF', // Couleur rose de l'image
            textColor = '#333333',      // Couleur texte de l'image
            fontSize = '18px',
            boxShadow = '0 4px 8px rgba(0,0,0,0.3)', // Ombre douce
            opacity = '0.25' // opacité
        } = options;

        //recuperation de l'objet contenair
        let container = document.getElementById(this.containerId);
        if(!container) {container = document.body;}
        
        // 2. Création du Masque (Overlay) qui couvre toute la page
        const overlay = document.createElement('div');
overlay.style.cssText = `
            position: absolute; /* Relative au conteneur si celui-ci est 'relative' */
            top: 0; left: 0; width: 100%; height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: var(--z-message);
        `;

        // Style de la boîte de message
        const messageBox = document.createElement('div');
messageBox.style.cssText = `
            background: ${background};
            color: ${textColor};
            font-size: ${fontSize};
            padding: 40px;
            border-radius: 20px;
            box-shadow: ${boxShadow};
            text-align: center;
            max-width: 80%;
            width: 500px;
        `;
        
        // On insère le message (on utilise innerHTML pour supporter les retours à la ligne <br>)
        messageBox.innerHTML = message;

        // 4. Assemblage
        overlay.appendChild(messageBox);
        container.appendChild(overlay);

        // 5. Logique de fermeture et de clic automatique
        setTimeout(() => {
            if (document.body.contains(overlay)) {
                container.removeChild(overlay);
            }
            
            // Clic automatique sur le bouton donné
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.click();
            } else {
                console.warn(`MessageManager : Bouton avec l'ID "${btnId}" introuvable.`);
            }
        }, duration * 1000);
    }
};
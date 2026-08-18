export const FocusManager = {
    overlay: null,

lock(containerId) {
    if (this.overlay) return;

    const container = document.getElementById(containerId);
    
    // 1. Forcez la position si elle est statique, sinon le z-index ne sera pas pris en compte
    if (container) {
        const style = window.getComputedStyle(container);
        if (style.position === 'static') {
            container.style.position = 'relative';
        }
    }

    // 2. Calcul du z-index
    if (container) {
        const containerZ = window.getComputedStyle(container).zIndex;
        // On vérifie que le z-index est bien un nombre valide avant de soustraire
        if (containerZ !== 'auto' && !isNaN(parseInt(containerZ))) {
            zIndex = parseInt(containerZ) - 1;
        }
    }
    let zIndex = 2000;

    this.overlay = document.createElement('div');
    this.overlay.style.cssText = `
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0, 0, 0, 0.3); 
        z-index: ${zIndex}; 
    `;
    document.body.appendChild(this.overlay);
},
    unlock() {
        if (this.overlay && document.body.contains(this.overlay)) {
            document.body.removeChild(this.overlay);
            this.overlay = null;
        }
    }
};


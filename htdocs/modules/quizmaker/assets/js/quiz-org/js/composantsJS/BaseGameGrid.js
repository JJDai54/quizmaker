export class BaseGameGrid {
    constructor() {
        // Plus besoin de stocker this.events ici
    }

    getPieces(enableDnD) {
        throw new Error("La méthode getPieces() doit être implémentée par la classe enfant.");
    }

    // On passe 'events' directement ici
    _buildAndShufflePool(sourcePieces, repeat, events = {}) {
        let pool = [];
        for (let i = 0; i < repeat; i++) {
            sourcePieces.forEach(p => {
                const clone = p.cloneNode(true);
                clone.toggleFlip = function() {
                    const isFlipped = this.dataset.flipped === "true";
                    this.dataset.flipped = isFlipped ? "false" : "true";
                    this.classList.toggle('flipped');
                };

                // Utilisation des événements passés en paramètre
                Object.entries(events).forEach(([eventName, handler]) => {
                    const normalizedEvent = eventName.toLowerCase().startsWith('on') 
                        ? eventName.toLowerCase().slice(2) 
                        : eventName.toLowerCase();

                    clone.addEventListener(normalizedEvent, (e) => handler(e, clone));
                });

                pool.push(clone);
            });
        }

        // Mélange Fisher-Yates
        for (let i = pool.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [pool[i], pool[j]] = [pool[j], pool[i]];
        }
        return pool;
    }

generatePuzzleGrid(events = {}, rotatable = false) {
        const sourcePieces = this.getPieces(true); 
        const pool = this._buildAndShufflePool(sourcePieces, 1, events); 
        
        if (rotatable) {
            pool.forEach(piece => {
                // Détermine si la pièce est carrée ou rectangulaire en fonction de ses dimensions
                const ratio = piece.naturalWidth / piece.naturalHeight;
                const isSquare = Math.abs(ratio - 1) < 0.05;
                
                // Si carré : 0, 90, 180, 270 | Si rectangle : 0 ou 180
                const randomRotations = isSquare ? [0, 90, 180, 270] : [0, 180];
                const initialRotation = randomRotations[Math.floor(Math.random() * randomRotations.length)];
                
                piece.dataset.rotation = initialRotation;
                piece.style.transform = `rotate(${initialRotation}deg)`;
            });
        }

        return { 
            elements: pool, 
            cols: this.cols, 
            rows: this.rows 
        };
    }    
    
    generateCustomGrid(targetCols, repeat = 1, events = {}, enableDnD = false) {
        const sourcePieces = this.getPieces(enableDnD);
        const pool = this._buildAndShufflePool(sourcePieces, repeat, events);
        const totalCards = pool.length;
        const targetRows = Math.ceil(totalCards / targetCols);
        
        return { elements: pool, cols: targetCols, rows: targetRows };
    }
}

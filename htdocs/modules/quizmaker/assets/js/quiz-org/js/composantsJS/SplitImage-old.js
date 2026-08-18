export class SplitImage {
    constructor(imageUrl, cols, rows, imgWidth, imgHeight, isPuzzle = true) {
        this.imageUrl = imageUrl;
        this.cols = cols;
        this.rows = rows;
        this.imgWidth = imgWidth;
        this.imgHeight = imgHeight;
        this.isPuzzle = isPuzzle;
    }

    getPieces(enableDnD = false) {
        const pieces = [];
        let indexCounter = 0; 
        
        for (let r = 0; r < this.rows; r++) {
            for (let c = 0; c < this.cols; c++) {
                const div = document.createElement('div');
                div.className = 'piece';
                
                if (enableDnD) {
                    div.setAttribute('draggable', 'true');
                }
                
                const bgPosX = this.cols > 1 ? (c / (this.cols - 1)) * 100 : 0;
                const bgPosY = this.rows > 1 ? (r / (this.rows - 1)) * 100 : 0;
                const bgPos = `${bgPosX}% ${bgPosY}%`;
                
//                 const aspectRatioValue = this.isPuzzle 
//                     ? `${this.rows} / ${this.cols}`  
//                     : '1 / 1';                        
                this.aspectRatioValue = (this.imgWidth/this.cols) / (this.imgHeight/this.rows);
                
                Object.assign(div.style, {
                    backgroundImage: `url(${this.imageUrl})`,
                    backgroundSize: `${this.cols * 100}% ${this.rows * 100}%`,
                    backgroundPosition: bgPos,
                    aspectRatio: this.aspectRatioValue
                });
    
                div.dataset.originalIndex = indexCounter++;
                div.dataset.flipped = "false";
                pieces.push(div);
            }
        }
        return pieces;
    }
    
    /**
     * Fonction mutualisée pour cloner, assigner les événements, dupliquer (repeat) et mélanger le pool.
     */
    _buildAndShufflePool(sourcePieces, repeat, events) {
        let pool = [];
        
        for (let i = 0; i < repeat; i++) {
            sourcePieces.forEach(p => {
                const clone = p.cloneNode(true);
                clone.toggleFlip = function() {
                    const isFlipped = this.dataset.flipped === "true";
                    this.dataset.flipped = isFlipped ? "false" : "true";
                    this.classList.toggle('flipped');
                };

                Object.entries(events).forEach(([eventName, handler]) => {
                    const normalizedEvent = eventName.toLowerCase().replace('on', '');
                    clone.addEventListener(normalizedEvent, (e) => handler(e, clone));
                });

                pool.push(clone);
            });
        }

        // Mélange aléatoire (Fisher-Yates)
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
        
        return { 
            elements: pool, 
            cols: targetCols, 
            rows: targetRows 
        };
    }
}
import { SplitImage } from "../../js/composantsJS/SplitImage.js";
import { ImageList  } from "../../js/composantsJS/ImageList.js";

export class MemosuiteComponent extends HTMLElement {
    constructor() {
        super();
        
        this.mode = this.getAttribute('mode') || 'split'; // 'split' ou 'list'
        this.imageUrl = this.getAttribute('image') || '';
        
// 1. Découpage réel de l'image d'origine (ex: 4 colonnes, 3 lignes)
        this.cols = parseInt(this.getAttribute('cols')) || 3;
        this.rows = parseInt(this.getAttribute('rows')) || 3;
        
// 2. Découpage visuel de la grille (ex: 6 colonnes pour un affichage sur 2 lignes)
        this.gridCols = parseInt(this.getAttribute('grid-cols')) || this.cols;

        this.imgWidth = parseInt(this.getAttribute('img-width')) || 503;
        this.imgHeight = parseInt(this.getAttribute('img-height')) || 256;
        this.sequenceLength = parseInt(this.getAttribute('sequence-length')) || 3;
        this.retryMode = this.getAttribute('retry-mode') || 0;
        
        this.maxAttempts = parseInt(this.getAttribute('max-attempts')) || 0;
        this.currentAttempts = 0;
        
        this.sequenceWidth = this.getAttribute('sequence-width') || this.getAttribute('preview-width') || '100%';
        this.gridWidth = this.getAttribute('grid-width') || '100%';
        this.previewHeight = this.getAttribute('sequence-height') || '45px';
        
        this.gap = this.getAttribute('gap') || '8px';
        this.radius = this.getAttribute('radius') || '4px';
        this.bgColor = this.getAttribute('background-color') || 'transparent';
        this.gridBg = this.getAttribute('grid-background') || 'rgba(0,0,0,0.02)';
        this.tempo = parseInt(this.getAttribute('tempo')) || 1000;
        this.inactiveOpacity = this.getAttribute('inactive-opacity') || '0.3';

        this.targetSequence = [];
        this.playerSequence = [];
        this.isPlaying = false;
        this.isSolved = false; // Suivi de la résolution
        this.pieces = [];
        
        // Normalisation des codes messages centralisés
        this.MESSAGES = {
            READY_BTN: this.getAttribute('msg-ready-btn') || "Cliquez sur le bouton pour générer et mémoriser la séquence !",
            NEXTSLIDE: this.getAttribute('msg-next-slide-btn') || "Slide suivant !",
            READY: "Cliquez sur le bouton pour générer et mémoriser la séquence !",
            PLAYER_TURN: `À vous de jouer ! Retrouvez les ${this.sequenceLength} images dans l'ordre.`,
            SUCCESS: "Bravo ! Séquence validée avec succès.",
            FAILURE: "Erreur dans la séquence ! Nouvelle tentative...",
            GAMEOVER: "Nombre maximal de tentatives atteint. Partie terminée !",
        };

        this.initDOM();
    }

    // Méthode demandée pour renvoyer true si la séquence est résolue
    checkIfSolved() {
        return this.isSolved;
    }
    
    initDOM() {
        this.style.display = 'block';
        this.style.margin = '0 auto';
        this.style.backgroundColor = this.bgColor;
        this.style.padding = '10px';
        this.style.borderRadius = this.radius;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = `
            @keyframes shake {
                0% { transform: translate(1px, 1px) rotate(0deg); }
                20% { transform: translate(-1px, -2px) rotate(-1deg); }
                40% { transform: translate(-3px, 0px) rotate(1deg); }
                60% { transform: translate(3px, 2px) rotate(0deg); }
                80% { transform: translate(1px, -1px) rotate(1deg); }
                100% { transform: translate(0px, 0px) rotate(0deg); }
            }
            .shake-animation {
                animation: shake 0.4s ease infinite;
            }
            #start-btn {
                display: block;
                width: ${this.gridWidth};
                margin: 15px auto 0 auto;
                padding: 10px 20px;
                font-size: 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                box-sizing: border-box;
            }
            #start-btn:disabled {
                background-color: #ccc;
                cursor: not-allowed;
            }
        `;
        this.appendChild(styleSheet);

        const containerHeightCalc = `calc(${this.previewHeight} + 16px)`;

        this.innerHTML += `
            <div id="memosuite-top-container" class="memosuite-top-div" style="display: flex; gap: 8px; margin: 0 auto 15px auto; width: ${this.sequenceWidth}; height: ${containerHeightCalc}; min-height: ${containerHeightCalc}; max-height: ${containerHeightCalc}; align-items: center; background: rgba(0,0,0,0.05); padding: 8px; border-radius: ${this.radius}; box-sizing: border-box; overflow-x: auto; overflow-y: hidden;">
            </div>

            <!-- Utilisation de gridCols pour définir les colonnes d'affichage CSS -->
            <div id="memosuite-grid-container" class="memosuite-grid" style="display: grid; grid-template-columns: repeat(${this.gridCols}, 1fr); gap: ${this.gap}; width: ${this.gridWidth}; margin: 0 auto; background: ${this.gridBg}; padding: 10px; border-radius: ${this.radius}; box-sizing: border-box;">
            </div>

            <button id="start-btn">${this.MESSAGES.READY_BTN}</button>
        `;
        
        this.topDiv = this.querySelector('#memosuite-top-container');
        this.gridContainer = this.querySelector('#memosuite-grid-container');
        this.startBtn = this.querySelector('#start-btn');
        
        this.createGrid();

        this.startBtn.addEventListener('click', () => {
            this.currentAttempts = 0;
            this.isSolved = false;
            this.start();
            this.startBtn.disabled = true;
            this.startBtn.style.opacity = 0.2;
            this.startBtn.style.background = 'red';
            this.emitMessage("ready", this.MESSAGES.READY);
        });
    }

    emitMessage(type, text) {
        this.dispatchEvent(new CustomEvent('game-message', {
            bubbles: true,
            composed: true,
            detail: { type: type, message: text }
        }));
    }

    shakeElement(elementOrSelector, durationMs = 1500) {
        const el = typeof elementOrSelector === 'string' ? document.querySelector(elementOrSelector) : elementOrSelector;
        if (!el) return;
        
        el.classList.add('shake-animation');
        setTimeout(() => {
            el.classList.remove('shake-animation');
        }, durationMs);
    }
    
    shakeStartBtn(duree = 1200){
        this.shakeElement(this.startBtn, duree);
    }

    createGrid(shuffle = true) {
        if (this.mode === 'list') {
            const imageUrls = this.imageUrl.includes(',') 
                ? this.imageUrl.split(',').map(s => s.trim()) 
                : [this.imageUrl];
                
            const imageList = new ImageList(imageUrls);
            this.pieces = imageList.getPieces(shuffle);
        } else {
            const splitter = new SplitImage(
                this.imageUrl, 
                this.cols, 
                this.rows, 
                this.imgWidth, 
                this.imgHeight, 
                shuffle
            );
            this.pieces = splitter.getPieces(false);
            
            for (let i = this.pieces.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [this.pieces[i], this.pieces[j]] = [this.pieces[j], this.pieces[i]];
            }
        }

        this.gridContainer.innerHTML = '';

        this.pieces.forEach((piece, index) => {
            piece.id = `memosuite-piece-${index}`;
            piece.style.opacity = '1';
            piece.style.transition = 'opacity 0.3s ease, transform 0.2s ease';
            piece.style.borderRadius = this.radius;
            piece.style.cursor = 'pointer';
            piece.style.width = '100%';
            piece.style.height = 'auto';

            piece.addEventListener('click', () => this.handleCellClick(index));
            this.gridContainer.appendChild(piece);
        });
    }
    
    start() {
        this.targetSequence = [];
        this.isSolved = false;
        this.generateNewSequence();
        this.playSequence();
    }

    generateNewSequence() {
        this.targetSequence = [];
        const totalPieces = this.mode === 'list' 
            ? this.pieces.length 
            : this.cols * this.rows;

        while (this.targetSequence.length < this.sequenceLength) {
            const randomIndex = Math.floor(Math.random() * totalPieces);
            if (!this.targetSequence.includes(randomIndex)) {
                this.targetSequence.push(randomIndex);
            }
        }
    }

    playSequence() {
        this.playerSequence = [];
        this.topDiv.innerHTML = '';
        this.isPlaying = false;
        let i = 0;

        this.pieces.forEach(p => p.style.opacity = this.inactiveOpacity);

        setTimeout(() => {
            const interval = setInterval(() => {
                this.pieces.forEach(p => p.style.opacity = this.inactiveOpacity);

                if (i < this.targetSequence.length) {
                    const activeIndex = this.targetSequence[i];
                    this.pieces[activeIndex].style.opacity = '1';
                    i++;
                } else {
                    clearInterval(interval);
                    this.pieces.forEach(p => p.style.opacity = '1');
                    this.isPlaying = true; 

                    this.emitMessage("player-turn", this.MESSAGES.PLAYER_TURN);
                }
            }, this.tempo);
        }, 600);
    }
    
    handleCellClick(index) {
        if (!this.isPlaying || this.isSolved) return;

        this.pieces[index].style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.pieces[index].style.transform = 'scale(1)';
        }, 150);

        this.playerSequence.push(index);
        this.updateTopDiv(index);

        const currentIndex = this.playerSequence.length - 1;
        
        if (this.playerSequence[currentIndex] !== this.targetSequence[currentIndex]) {
            this.isPlaying = false;
            this.currentAttempts++;

            if (this.maxAttempts > 0 && this.currentAttempts >= this.maxAttempts) {
                this.emitMessage("game-over", this.MESSAGES.GAMEOVER);
                this.startBtn.disabled = true;
                this.startBtn.style.background = 'red';
                this.pieces.forEach(p => p.style.opacity = '1');

                this.topDiv.innerHTML = '';
                this.targetSequence.forEach(targetIndex => {
                    this.updateTopDiv(targetIndex);
                });

                return;
            }

            this.emitMessage("game-failure", this.MESSAGES.FAILURE);

            if (this.retryMode == 0) {
                setTimeout(() => this.playSequence(), 1000);
            } else {
                setTimeout(() => {
                    this.generateNewSequence();
                    this.playSequence();
                }, 1000);
            }
            return;
        }

        if (this.playerSequence.length === this.targetSequence.length) {        
            this.isPlaying = false;
            this.isSolved = true; 
            this.startBtn.disabled = true;
            this.startBtn.style.opacity = 0.2;
            this.startBtn.style.background = "#FF9900";
            this.startBtn.textContent = this.MESSAGES.NEXTSLIDE;
            this.emitMessage("game-success", this.MESSAGES.SUCCESS);
        }
    }

    updateTopDiv(index) {
        const sourcePiece = this.pieces[index];
        const previewItem = document.createElement('div');
        
        previewItem.style.height = this.previewHeight;
        previewItem.style.borderRadius = this.radius;
        previewItem.style.boxSizing = 'border-box';
        previewItem.style.border = '2px solid #4CAF50';
        previewItem.style.backgroundImage = sourcePiece.style.backgroundImage;
        previewItem.style.backgroundSize = sourcePiece.style.backgroundSize || 'cover';
        previewItem.style.backgroundPosition = sourcePiece.style.backgroundPosition || 'center';
        previewItem.style.backgroundRepeat = 'no-repeat';

        if (this.mode === 'list') {
            const computedWidth = parseInt(this.previewHeight) || 45;
            previewItem.style.width = `${computedWidth}px`;
            previewItem.style.minWidth = `${computedWidth}px`;
        } else {
            const cellWidth = this.imgWidth / this.cols;
            const cellHeight = this.imgHeight / this.rows;
            const ratio = cellWidth / cellHeight;
            const computedHeight = parseInt(this.previewHeight) || 45;
            const computedWidth = Math.round(computedHeight * ratio);

            previewItem.style.width = `${computedWidth}px`;
            previewItem.style.minWidth = `${computedWidth}px`;
        }

        this.topDiv.appendChild(previewItem);
    }

    showGame() {
        if (this.targetSequence.length === 0) return;

        this.topDiv.innerHTML = '';
        this.playerSequence = [...this.targetSequence];

        this.targetSequence.forEach(index => {
            this.updateTopDiv(index);
        });

        this.isPlaying = false;
        this.isSolved = true;
        this.startBtn.disabled = false;
        this.startBtn.textContent = "Niveau suivant";
        
        this.pieces.forEach(p => p.style.opacity = '1');

        this.emitMessage("game-success", this.MESSAGES.SUCCESS);
    }    
}

customElements.define('memosuite-component', MemosuiteComponent);

import { SplitImage } from "../../js/composantsJS/SplitImage.js";

const sheet = new CSSStyleSheet();
sheet.replaceSync(`
    :host { display: block; font-family: sans-serif; }
    #game-board { 
        display: grid; 
        grid-template-columns: repeat(var(--taquin-cols, 3), 1fr);
        width: 90vw; 
        max-width: 600px; 
        gap: var(--taquin-gap, 3px); 
        padding: var(--taquin-gap, 3px); /* <-- Ajoute cette ligne */
        margin: 0 auto; 
        background-color: var(--taquin-bg, #2c3e50); 
        border-radius: var(--taquin-radius, 4px);
    }
    
    .piece { 
      width: 100%; 
      aspect-ratio: 1 / 1; 
      background-repeat: no-repeat; 
      background-size: cover; 
      border-radius: calc(var(--taquin-radius, 4px) - 2px); 
      cursor: pointer; 
      transition: transform 0.1s ease, box-shadow 0.2s ease, opacity 0.1s ease; 
    }    
    
    .piece:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .piece.empty-piece {
        background-image: none !important;
        background-color: var(--taquin-bg, #2c3e50);
        cursor: default;
        pointer-events: none;
        box-shadow: none !important;
        transform: none !important;
    }
`);

export class TaquinComponent extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.adoptedStyleSheets = [sheet];

        this.shadowRoot.innerHTML = `<div id="game-board"></div>`;
        this.boardElement = this.shadowRoot.getElementById('game-board');
        this.isPreviewing = false;
        this.isWon = false;
        this.isAnimating = false;
    }

    static get observedAttributes() {
        return ['image', 'cols', 'rows', 'game-width', 'imgwidth', 'imgheight', 'background-color', 'radius', 'gap'];
    }    

    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue !== newValue) {
            this[name] = newValue;
            if (this.isConnected) {
                this.initGame();
            }
        }
    }

    connectedCallback() {
        this.initGame();
    }

    initGame() {
        this.isWon = false;
        this.isPreviewing = false;
        this.isAnimating = false;

        this.imageUrl = this.getAttribute('image') || 'chien.jpg';
        this.cols = parseInt(this.getAttribute('cols'), 10) || 3;
        this.rows = parseInt(this.getAttribute('rows'), 10) || 3;
        this.arraySize = this.cols * this.rows;
        this.imgWidth = parseFloat(this.getAttribute('imgwidth')) || 400;
        this.imgHeight = parseFloat(this.getAttribute('imgheight')) || 400;
        
        const gameWidth = parseInt(this.getAttribute('game-width')) || 600;
        const bgColor = this.getAttribute('background-color');
        const radius = this.getAttribute('radius');
        const gap = this.getAttribute('gap');

        this.boardElement.style.width = gameWidth + 'px';
        this.boardElement.style.setProperty('--taquin-cols', this.cols);

        if (bgColor) { this.boardElement.style.setProperty('--taquin-bg', bgColor); }
        if (radius)  { this.boardElement.style.setProperty('--taquin-radius', radius + 'px'); }
        if (gap)     { this.boardElement.style.setProperty('--taquin-gap', gap + 'px'); }

        this.splitter = new SplitImage(this.imageUrl, this.cols, this.rows, this.imgWidth, this.imgHeight, true);
        
        const sourcePieces = this.splitter.getPieces(true);
        const indexes = this.generate_taquin(this.arraySize);
        this.taquinGrid = indexes.map(index => sourcePieces[index]);
        
        this.render();

        // Dispatch de l'événement d'initialisation (similaire à PuzzleComponent)
        const event = new CustomEvent('game-init', {
            detail: { isInit: true },
            bubbles: true,
            composed: true
        });
        this.dispatchEvent(event);
    }

    generate_taquin(arraySize) {
        const cols = this.cols;
        const rows = this.rows;
        let arr = Array(arraySize).fill(0);
        for (let h = 0; h < arraySize; h++) { arr[h] = h; }

        let taquin, inversions, emptyRowFromBottom;

        do {
            arr.sort(() => Math.random() - 0.5);
            taquin = [...arr];

            inversions = 0;
            let emptyIndex = -1;

            for (let i = 0; i < arraySize; i++) {
                if (taquin[i] === arraySize - 1) {
                    emptyIndex = i;
                    continue;
                }
                for (let j = i + 1; j < arraySize; j++) {
                    if (taquin[j] !== arraySize - 1 && taquin[i] > taquin[j]) {
                        inversions++;
                    }
                }
            }

            const emptyRowIndex = Math.floor(emptyIndex / cols);
            emptyRowFromBottom = rows - emptyRowIndex;

            if (cols % 2 !== 0) {
                if (inversions % 2 === 0) break;
            } else {
                const isEvenRowFromBottom = (emptyRowFromBottom % 2 === 0);
                if (isEvenRowFromBottom && inversions % 2 !== 0) break;
                if (!isEvenRowFromBottom && inversions % 2 === 0) break;
            }

        } while (true);

        return taquin;
    }

    async movePiece(clickedIndex) {
        if (this.isAnimating || this.isPreviewing || this.isWon) return; 

        const emptyIndex = this.getEmptyIndex();
        if (emptyIndex === -1) return;

        const clickedRow = Math.floor(clickedIndex / this.cols);
        const clickedCol = clickedIndex % this.cols;
        const emptyRow = Math.floor(emptyIndex / this.cols);
        const emptyCol = emptyIndex % this.cols;

        let step = 0;

        if (clickedRow === emptyRow) {
            step = clickedIndex > emptyIndex ? 1 : -1;
        } 
        else if (clickedCol === emptyCol) {
            step = clickedIndex > emptyIndex ? this.cols : -1 * this.cols;
        } else {
            return; 
        }

        this.isAnimating = true;

        while (this.getEmptyIndex() !== clickedIndex) {
            const currentEmpty = this.getEmptyIndex();
            const targetNeighbor = currentEmpty + step;
            
            this.swapPieces(currentEmpty, targetNeighbor);
            this.render(); 

            await new Promise(resolve => setTimeout(resolve, 120));
        }

        this.isAnimating = false;
        this.checkWinCondition();
    }

    swapPieces(indexA, indexB) {
        const temp = this.taquinGrid[indexA];
        this.taquinGrid[indexA] = this.taquinGrid[indexB];
        this.taquinGrid[indexB] = temp;
    }

    getEmptyIndex() {
        return this.taquinGrid.findIndex(piece => 
            piece && parseInt(piece.dataset.originalIndex, 10) === this.arraySize - 1
        );
    }        

    render() {
        this.boardElement.innerHTML = '';
        
        this.taquinGrid.forEach((piece, index) => {
            const originalIdx = parseInt(piece.dataset.originalIndex, 10);
            
            if (originalIdx === this.arraySize - 1 && !this.isWon && !this.isPreviewing) {
                piece.classList.add('empty-piece');
            } else {
                piece.classList.remove('empty-piece');
            }

            piece.onclick = () => {
                if (!this.isWon && !this.isPreviewing) {
                    this.movePiece(index);
                }
            };

            this.boardElement.appendChild(piece);
        });
    }
    
    checkIfSolved() {
        return this.taquinGrid.every((piece, index) => {
            return parseInt(piece.dataset.originalIndex, 10) === index;
        });
    }

    checkWinCondition() {
        const isCompleted = this.checkIfSolved();
        if (isCompleted) {
            this.isWon = true; 
            this.render();     
        }

        // Dispatch de l'événement de succès (similaire à PuzzleComponent)
        const event = new CustomEvent('game-success', {
            detail: { isSolved: isCompleted },
            bubbles: true,
            composed: true
        });
        this.dispatchEvent(event);
    }

    resetToOriginalOrder() {
        if (this.isAnimating || this.isWon) return;

        this.isPreviewing = true;
        const sourcePieces = this.splitter.getPieces(true);
        this.taquinGrid = [...sourcePieces]; 
        this.render();
    }

    preview(duree) {
        this.resetToOriginalOrder();
        setTimeout(() => this.initGame(), duree);
    }
}

customElements.define('taquin-component', TaquinComponent);
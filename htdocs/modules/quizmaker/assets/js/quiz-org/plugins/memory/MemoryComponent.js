import { SplitImage } from "../../js/composantsJS/SplitImage.js";
import { ImageList  } from "../../js/composantsJS/ImageList.js";

const sheet = new CSSStyleSheet();
sheet.replaceSync(`
    :host { display: block; font-family: sans-serif; }
    #controls { margin: 20px 0; gap: 10px; display: flex; flex-wrap: wrap; justify-content: center; }
    #game-board { 
        display: grid; 
        width: 90vw; 
        max-width: 600px; 
        gap: var(--memory-gap, 3px); 
        padding: var(--memory-gap, 3px); /* <-- Ajoute cette ligne */        
        background-color: var(--memory-bg, #2c3e50); 
        border-radius: var(--memory-radius, 4px);
        margin: 0 auto; 
        

    grid-template-columns: repeat(4, 1fr); /* Adaptez selon votre nombre de colonnes */
    align-items: start; /* Empêche les cellules de s'étirer verticalement pour faire la même taille */
    }
        
    #game-board.waiting .piece {
        cursor: wait !important;
        pointer-events: none; /* Empêche les clics intempestifs pendant l'attente */
    }
    .piece { 
        width: 100%; 
        aspect-ratio: 1 / 1; 
        background-repeat: no-repeat; 
        background-size: cover; 
        border-radius: calc(var(--memory-radius, 4px) - 2px); 
        cursor: pointer; 
        transition: transform 0.2s ease, box-shadow 0.2s ease; 
        background-color: var(--memory-background-mask, blue); 
    }

    .piece.waiting  {
        cursor: wait !important;
        pointer-events: none; /* Empêche les clics intempestifs pendant l'attente */
    }
    .piece:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .piece:active { cursor: grabbing; }
    .piece.flipped { background-color: transparent !important; }
    
    #game-board.masque .piece:not(.flipped) { 
        background-image: none !important; 
        background-color: var(--memory-background-mask, blue); 
    }
`);

export class MemoryComponent extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.adoptedStyleSheets = [sheet];
        this.shadowRoot.innerHTML = '';
        
        this.shadowRoot.innerHTML = `<div id="game-board"></div>`;
 /* bouton a utiliser uniquement pour le dev
        this.shadowRoot.innerHTML += `
            <div id="controls">
                <button id="btn-regen">Régénérer</button>
                <button id="btn-show">Tout Afficher</button>
                <button id="btn-hide">Tout Masquer</button>
                <button id="btn-timeout" style="background-color: #e67e22; color: white;">Test Séquence (3s)</button>
            </div>
        `;
 */       
    }

    static get observedAttributes() {
        return ['image', 'mode', 'cols', 'rows', 'target-cols', 'repeat', 'game-width', 'background-color', 'background-mask', 'radius', 'gap', 'max-attempts'];
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
        this.imageUrl = this.getAttribute('image') || 'fleurs_02.png';
        this.initGame();
        this.setupListeners();
    }

    initGame() {
        const cols = parseInt(this.getAttribute('cols')) || 4;
        const rows = parseInt(this.getAttribute('rows')) || 3;
        const targetCols = parseInt(this.getAttribute('target-cols')) || cols;
        const repeat = parseInt(this.getAttribute('repeat')) || 2; // Par défaut 2 pour un memory
        const imageAttr = this.getAttribute('image') || 'fleurs_02.png';
        const mode = this.getAttribute('mode') || 'split'; // 'split' ou 'list'
        const gameWidth = parseInt(this.getAttribute('game-width')) || 600;
        const imgWidth = parseFloat(this.getAttribute('imgwidth')) || 400;
        const imgHeight = parseFloat(this.getAttribute('imgheight')) || 400;

        const bgColor = this.getAttribute('background-color');
        const radius = this.getAttribute('radius');
        const gap = this.getAttribute('gap');
        const bgMask = this.getAttribute('background-mask');
        const tempo = parseFloat(this.getAttribute('tempo')) || 0.8;

        const maxAttemptsAttr = this.getAttribute('max-attempts');
        this.maxAttempts = maxAttemptsAttr ? parseInt(maxAttemptsAttr, 10) : null;
        this.currentAttempts = 0;

        const board = this.shadowRoot.getElementById('game-board');
        board.innerHTML = '';
        board.classList.remove('masque');
        
        board.style.width = gameWidth + 'px';
        board.style.setProperty('--memory-cols', cols);

        if (bgColor) { board.style.setProperty('--memory-bg', bgColor); }
        if (radius)  { board.style.setProperty('--memory-radius', radius + 'px'); }
        if (gap)     { board.style.setProperty('--memory-gap', gap + 'px'); } 
        if (bgMask)  { board.style.setProperty('--memory-background-mask', this.getAttribute('background-mask') ); } 

        // --- CHOIX DE LA CLASSE SELON LE MODE ---
//        alert(`===> mode = ${mode}`)
        if (mode === 'list') {
            // Si l'attribut image contient des virgules, on le transforme en tableau, sinon on gère un tableau unique
            const imageUrls = imageAttr.includes(',') 
                ? imageAttr.split(',').map(s => s.trim()) 
                : JSON.parse(imageAttr); // Ou un tableau direct si géré par JS, mais la chaîne séparée par virgules est très pratique en HTML.
                
            this.game = new ImageList(imageUrls);
        } else {
            // Mode SplitImage classique (grille découpée)
            // Note: imgWidth et imgHeight peuvent être omis si non utilisés dans SplitImage.js
            this.game = new SplitImage(imageAttr, cols, rows, imgWidth, imgHeight, false);
        }
        
        const useDragAndDrop = true; 

        this.flippedCards = [];
        this.isChecking = false;

        const gridData = this.game.generateCustomGrid(targetCols, repeat, {
            onclick: (e, piece) => {
                if (this.isChecking || piece.classList.contains('flipped') || piece.classList.contains('matched')) {
                    return;
                }

                piece.toggleFlip();
                this.flippedCards.push(piece);

                if (this.flippedCards.length === repeat) {
                    this.isChecking = true;

                    const firstOriginalIndex = this.flippedCards[0].dataset.originalIndex;
                    const allMatch = this.flippedCards.every(p => p.dataset.originalIndex === firstOriginalIndex);

                    if (allMatch) {
                        this.flippedCards.forEach(p => p.classList.add('matched'));
                        this.flippedCards = [];
                        this.isChecking = false;
                        let isCompleted = this.checkIfSolved();
                        if (isCompleted) {
                            const event = new CustomEvent('game-success', {
                                detail: { isSolved: true, attempts: this.currentAttempts },
                                bubbles: true,
                                composed: true 
                            });
                            this.dispatchEvent(event);
                            console.log("Bravo ! Toutes les pièces ont été découvertes ! 🎉");
                        }
                    } else {
                        this.currentAttempts++;
                        if (this.maxAttempts !== null && this.currentAttempts >= this.maxAttempts && this.maxAttempts > 0) {
                            setTimeout(() => {
                                this.flippedCards.forEach(p => p.toggleFlip());
                                this.flippedCards = [];
                                this.isChecking = false;
                                
                                console.log("Nombre maximum d'essais atteint ! ❌");
                            }, tempo * 1000);
                        } else {
/* aucune de ces solution fonctione pour ajouter le sablier durant ce timeout , a voir plus taard
board.classList.add('waiting');
const pieces = Array.from(board.children);
pieces.forEach(piece => piece.classList.add('waiting'));
board.classList.add('masque');                           
*/                              /* solutioin au problème précédent, ajout d'un evennement intercepté par l'appli
                                et lance la gauche durant le même temps que le timeout ci-dessou) */
                                const event = new CustomEvent('game-isnogood', {
                                    detail: { attempts: this.currentAttempts, isSolved: false },
                                    bubbles: true,
                                    composed: true
                                });
                                this.dispatchEvent(event);
                                
                            setTimeout(() => {
                                this.flippedCards.forEach(p => p.toggleFlip());
                                this.flippedCards = [];
                                this.isChecking = false;
                                //board.classList.remove('waiting');
                            }, tempo * 1000);
                        }
                    }
                }
            },
        }, useDragAndDrop);                

        board.style.gridTemplateColumns = `repeat(${gridData.cols}, 1fr)`;
        gridData.elements.forEach(p => board.appendChild(p));
    }
    
    resetToOriginalOrder() {
        const board = this.shadowRoot.getElementById('game-board');
        const pieces = Array.from(board.children);

        pieces.sort((a, b) => {
            return parseInt(a.dataset.originalIndex, 10) - parseInt(b.dataset.originalIndex, 10);
        });

        pieces.forEach(piece => board.appendChild(piece));
    }

    checkIfSolved() {
    
        const board = this.shadowRoot.getElementById('game-board');
        const pieces = Array.from(board.children);

        const isCompleted = pieces.every(piece => piece.classList.contains('matched'));
//alert(`checkIfSolved : ${isCompleted ? 'oui' : 'non'}`)
        return isCompleted;
    }     
           
    setupListeners() {
        const board = this.shadowRoot.getElementById('game-board');
 /* bouton a utiliser uniquement pour le dev 
        this.shadowRoot.getElementById('btn-regen').onclick = () => this.initGame();
        this.shadowRoot.getElementById('btn-show').onclick = () => board.classList.remove('masque');
        this.shadowRoot.getElementById('btn-hide').onclick = () => board.classList.add('masque');
        this.shadowRoot.getElementById('btn-timeout').onclick = () => {
            board.classList.remove('masque');
            setTimeout(() => board.classList.add('masque'), 3000);
        };
 */        
    }

    pieceOnclick(ev, piece){
        alert('pieceOnclick : ' + piece.dataset.id);
    }
    
    showGame(){
//alert(`showGame`)
        const board = this.shadowRoot.getElementById('game-board');
        board.classList.remove('masque');
        const pieces = Array.from(board.children);
        pieces.forEach(piece => piece.classList.add('matched'));
    }
    
    hiddeGame(){
        const board = this.shadowRoot.getElementById('game-board');
        board.classList.add('masque');
    }
      
    preview(duree){
        const board = this.shadowRoot.getElementById('game-board');
        board.classList.remove('masque');
        
        setTimeout(() => {
            board.classList.add('masque');
            
            const event = new CustomEvent('game-init', {
                detail: { isInit: true },
                bubbles: true,
                composed: true
            });
            this.dispatchEvent(event);
            
        }, duree);
    }    
}

customElements.define('memory-component', MemoryComponent);
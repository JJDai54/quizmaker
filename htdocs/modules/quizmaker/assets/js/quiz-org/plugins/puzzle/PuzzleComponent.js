import { SplitImage } from "../../js/composantsJS/SplitImage.js";

//htdocs\modules\quizmaker\assets\js\quiz-org\plugins\puzzle\PuzzleComponent.js
//alert(`+++ : ${quiz.urlMain}/js/composantsJS/SplitImage.js`)
//import { SplitImage } from `${quiz.urlMain}/js/composantsJS/SplitImage.js`;
//import { SplitImage } from `../../../../../../../modules/quizmaker/assets/js/quiz-org/js/composantsJS/SplitImage.js`;
//import { SplitImage } from `composantsJS/SplitImage.js`;

const sheet = new CSSStyleSheet();
sheet.replaceSync(`
    :host { display: block; font-family: sans-serif; }
    #controls { margin: 20px 0; gap: 10px; display: flex; flex-wrap: wrap; justify-content: center; }
    #game-board { 
        display: grid; 
        width: 90vw; 
        max-width: 600px; 
        /* Utilisation des variables avec valeurs par défaut */
        gap: var(--puzzle-gap, 3px); 
        padding: var(--puzzle-gap, 3px); /* <-- Ajoute cette ligne */        
        margin: 0 auto; 
        background-color: var(--puzzle-bg, #2c3e50); 
        border-radius: var(--puzzle-radius, 4px); /* Paramétrable ici */
    }
    
    .piece { 
        width: 100%; 
        aspect-ratio: 1 / 1; 
        background-repeat: no-repeat; 
        background-size: cover; 
        /* Si tu veux que chaque pièce ait aussi le radius du conteneur ou un autre paramètre : */
        border-radius: calc(var(--puzzle-radius, 4px) - 2px); 
        cursor: pointer; 
        transition: transform 0.2s ease, box-shadow 0.2s ease; 
        background-color: transparent; 
        rotate(0deg);
    }
    
    .piece:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .piece.drag-over {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.8);
        z-index: 10;
    }

    .piece:active { cursor: grabbing; }
    .piece.flipped { background-color: transparent !important; }
    #game-board.masque .piece:not(.flipped) { background-image: none !important; background-color: var(--puzzle-bg, #2c3e50); /* <-- Ici aussi */ }
`);

export class PuzzleComponent extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.adoptedStyleSheets = [sheet];
        
        this.shadowRoot.innerHTML = `<div id="game-board"></div>`;
//         this.shadowRoot.innerHTML = `
//             <div id="controls">
//                 <button id="btn-regen">Régénérer</button>
//                 <button id="btn-show">Tout Afficher</button>
//                 <button id="btn-hide">Tout Masquer</button>
//                 <button id="btn-timeout" style="background-color: #e67e22; color: white;">Test Séquence (3s)</button>
//             </div>
//             <div id="game-board"></div>
//         `;
    }

    static get observedAttributes() {
        return ['image', 'cols', 'rows', 'game-width', 'insertMode', 'max-attempts', 'imgWidth', 'imgHeight', 'background-color', 'radius', 'gap'];
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
        this.imageUrl = this.getAttribute('image') || 'chien.jpg';
        this.initGame();
        this.setupListeners();
    }


initGame() {
    const cols = Number(this.getAttribute('cols')) || 1;
    const rows = Number(this.getAttribute('rows')) || 1;
    const imgWidth = Number(this.getAttribute('imgWidth')) || 0;
    const imgHeight = Number(this.getAttribute('imgHeight')) || 0;    const image = this.getAttribute('image') || 'chien.jpg';
    const gameWidth = parseInt(this.getAttribute('game-width')) || 600;
    const bgColor = this.getAttribute('background-color');
    const radius = this.getAttribute('radius');
    const gap = this.getAttribute('gap');
    

    // 1. On vérifie si l'attribut 'rotatable' est présent sur le composant HTML
    const isRotatable = this.hasAttribute('rotatable');
    const insertMode = Number(this.getAttribute('insertMode')) || 1;
    
    this.game = new SplitImage(image, cols, rows, imgWidth, imgHeight);
    
    const board = this.shadowRoot.getElementById('game-board');
    board.innerHTML = '';
    board.classList.remove('masque');
    board.style.width = gameWidth + 'px';

    if (bgColor) {board.style.setProperty('--puzzle-bg', bgColor);}
    if (radius)  {board.style.setProperty('--puzzle-radius', radius + 'px');}
    if (gap)     {board.style.setProperty('--puzzle-gap', gap + 'px');}    

    let draggedPiece = null;
    const useDragAndDrop = true; 

    const maxAttempts = parseInt(this.getAttribute('max-attempts')) || 50; 
    let currentAttempts = 0; 

    // 2. On passe 'isRotatable' en deuxième argument
    const gridData = this.game.generatePuzzleGrid({
        onclick: (e, piece) => {
            if (!isRotatable) return;
            if (!piece) return;


            // Utiliser directement la propriété existante
            const tolerance = 0.01;
            const isSquare = Math.abs(this.game.aspectRatioValue - 1) < tolerance;
            const angle = isSquare ? 90 : 180;

            let currentRotation = parseInt(piece.dataset.rotation || '0', 10);
            currentRotation = (currentRotation + angle) % 360;
            //console.log(`angle = ${angle} - currentRotation = ${currentRotation}`);
            
            piece.dataset.rotation = currentRotation;
            piece.style.transition = 'transform 0.3s ease';

            //this.checkIfSolved();
            piece.addEventListener('transitionend', () => {
                this.checkIfSolved();
            }, { once: true });

            piece.style.transform = `rotate(${currentRotation}deg)`;

        },
        
        ondragstart: (e, piece) => {
            if (!useDragAndDrop) return;
            draggedPiece = piece;
            e.dataTransfer.setData('text/plain', piece.dataset.id);
        },

        ondragover: (e) => {
            if (!useDragAndDrop) return;
            e.preventDefault();
        },

        ondrop: (e, targetPiece) => {
            
            if (!useDragAndDrop) return;
            e.preventDefault();
            targetPiece.classList.remove('drag-over');

            if (!draggedPiece || draggedPiece === targetPiece) return;

            const parent = targetPiece.parentNode;

            // Mode Insert (1)
            if (insertMode === 1) {
                // On insère draggedPiece juste avant targetPiece
                parent.insertBefore(draggedPiece, targetPiece);
            } 
            // Mode Swap (0 ou autre)
            else {
                const draggedNextSibling = draggedPiece.nextSibling;
                const targetNextSibling = targetPiece.nextSibling;

                if (targetNextSibling === draggedPiece) {
                    parent.insertBefore(draggedPiece, targetPiece);
                } else {
                    parent.insertBefore(draggedPiece, targetNextSibling);
                }

                if (draggedNextSibling === targetPiece) {
                    parent.insertBefore(targetPiece, draggedPiece);
                } else {
                    parent.insertBefore(targetPiece, draggedNextSibling);
                }
            }

            currentAttempts++;

            const isSolved = this.checkIfSolved();

            if (!isSolved && currentAttempts >= maxAttempts && maxAttempts > 0) {
                const maxEvent = new CustomEvent('game-maxattempts', {
                    detail: { attempts: currentAttempts },
                    bubbles: true,
                    composed: true
                });
                this.dispatchEvent(maxEvent);
            }
        }
    }, isRotatable); // <-- Passage de l'argument ici

    board.style.gridTemplateColumns = `repeat(${gridData.cols}, 1fr)`;
    gridData.elements.forEach(p => board.appendChild(p));
    
    
    
    
        // On crée et on dispatch un événement personnalisé vers l'application parente
        const event = new CustomEvent('game-init', {
            detail: { isInit: true },
            bubbles: true,
            composed: true // Nécessaire pour traverser le Shadow DOM et remonter au parent
        });
        this.dispatchEvent(event);
    
    
    
}    

    // Remettre dans l'ordre d'origine
    resetToOriginalOrder() {
        const board = this.shadowRoot.getElementById('game-board');
        const pieces = Array.from(board.children);

        // On trie strictement selon l'index numérique d'origine
        pieces.sort((a, b) => {
            return parseInt(a.dataset.originalIndex, 10) - parseInt(b.dataset.originalIndex, 10);
        });

        pieces.forEach(piece => {
            // Réinitialise la rotation (adaptez selon la façon dont vous l'appliquez)
            piece.style.transform = `rotate(0deg)`;
            // Si vous stockez l'angle dans un dataset (ex: dataset.rotation = 0), pensez à le réinitialiser aussi :
            piece.dataset.rotation = '0';

            board.appendChild(piece);
        });
    }
    
    // Tester si le puzzle est résolu (version simple)

    checkIfSolved() {
        const board = this.shadowRoot.getElementById('game-board');
        const pieces = Array.from(board.children);

        // On vérifie la position et la rotation pour chaque pièce
        const isCompleted = pieces.every((piece, index) => {
            const isGoodPosition = parseInt(piece.dataset.originalIndex, 10) === index;
            const currentRotation = parseInt(piece.dataset.rotation || '0', 10);
            const isGoodRotation = currentRotation % 360 === 0;
//console.log(`checkIfSolved===>rotation = : ${currentRotation} - ${currentRotation % 360}`)
            return isGoodPosition && isGoodRotation;
        });

//if(isCompleted) alert(`checkIfSolved isCompleted`)
        // On crée et on dispatch un événement personnalisé vers l'application parente
        const event = new CustomEvent('game-success', {
            detail: { isSolved: isCompleted },
            bubbles: true,
            composed: true // Nécessaire pour traverser le Shadow DOM et remonter au parent
        });
        this.dispatchEvent(event);

        return isCompleted;
    }
            
    setupListeners() {
        const board = this.shadowRoot.getElementById('game-board');
/*
        this.shadowRoot.getElementById('btn-regen').onclick = () => this.initGame();
        this.shadowRoot.getElementById('btn-show').onclick = () => board.classList.remove('masque');
        this.shadowRoot.getElementById('btn-hide').onclick = () => board.classList.add('masque');
        this.shadowRoot.getElementById('btn-timeout').onclick = () => {
            board.classList.remove('masque');
            setTimeout(() => board.classList.add('masque'), 3000);
        };
*/        
    }


    // gestion des evennements
    
    pieceOnclick(ev, piece){
        //piece.toggleFlip();
        //alert(ev.currentTarget.id)
        alert('pieceOnclick : ' + piece.dataset.id)
    }
    
    showGame(){
        const board = this.shadowRoot.getElementById('game-board');
        board.classList.remove('masque');
    }
    
    hiddeGame(){
        const board = this.shadowRoot.getElementById('game-board');
        board.classList.add('masque');
    }
    
    preview(duree){
        this.resetToOriginalOrder();
        setTimeout(() => this.initGame(), duree);
    }
    
    
}

customElements.define('puzzle-component', PuzzleComponent);

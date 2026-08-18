export { Gauge } from './Gauge.js';
export { Disc } from './Disc.js';
export { MessageManager } from './MessageManager.js';
export { FocusManager } from './FocusManager.js';
export { BaseGameGrid } from './BaseGameGrid.js';
export { SplitImage } from './SplitImage.js';
export { ImageList } from './ImageList.js';
export { ChronosComponent } from './Chronos.js';
import { ChronosComponent } from './Chronos.js';

// ... ainsi de suite pour vos 30 classes

// Et on ajoute ceci pour le support global (le "salon")
import { Gauge } from './Gauge.js';
import { Disc } from './Disc.js';
import { MessageManager } from './MessageManager.js';
import { FocusManager } from './FocusManager.js';
import { BaseGameGrid } from './BaseGameGrid.js';
import { SplitImage } from './SplitImage.js';
import { ImageList } from './ImageList.js';

import { PuzzleComponent }    from '../../plugins/puzzle/PuzzleComponent.js';
import { TaquinComponent }    from '../../plugins/taquin/TaquinComponent.js';
import { MemoryComponent }    from '../../plugins/memory/MemoryComponent.js';
import { MemosuiteComponent } from '../../plugins/memosuite/MemosuiteComponent.js';

//config de z-index
window.QuizConfig = {
    baseZIndex: 5000,
    // Vous pouvez définir des paliers logiques
    layers: {
        background: 0,
        gameContainer: 100,    // 5100
        focusOverlay: 99,      // 5099 (juste en dessous du jeu)
        messageManager: 200    // 5200 (au-dessus du jeu)
    }
};

// 3. Transfert vers le CSS (pour permettre l'utilisation via var(--nom-variable))
const root = document.documentElement;
root.style.setProperty('--base-z-index', window.QuizConfig.baseZIndex);
root.style.setProperty('--z-overlay', window.QuizConfig.baseZIndex + window.QuizConfig.layers.focusOverlay);
root.style.setProperty('--z-message', window.QuizConfig.baseZIndex + window.QuizConfig.layers.messageManager);


// Au lieu de polluer window directement avec 30 noms
window.QuizMaker = {
    Gauge: Gauge,
    Disc: Disc,
    MessageManager: MessageManager,
    FocusManager: FocusManager,  
    SplitImage: SplitImage,  
    ImageList: ImageList,  
    ChronosComponent: ChronosComponent, // <--- AJOUTEZ CETTE LIGNE
    // ...
};

document.documentElement.style.setProperty('--base-z-index', window.QuizConfig.baseZIndex);

console.log("QuizMaker a été chargé avec succès :", window.QuizMaker);
///////////////////////////////////////////

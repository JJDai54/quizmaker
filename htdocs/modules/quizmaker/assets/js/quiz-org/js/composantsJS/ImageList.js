import { BaseGameGrid } from './BaseGameGrid.js';

export class ImageList extends BaseGameGrid {
    constructor(imageUrls) {
        super(); // Plus besoin de passer events ici
        this.imageUrls = imageUrls; // Un tableau de chemins d'images
    }

    getPieces(enableDnD = false) {
        const pieces = [];

        this.imageUrls.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'piece';
            if (enableDnD) div.setAttribute('draggable', 'true');
            
            Object.assign(div.style, {
                backgroundImage: `url(${url})`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                aspectRatio: '1 / 1'
            });

            div.dataset.originalIndex = index;
            div.dataset.flipped = "false";
            pieces.push(div);
        });

        return pieces;
    }
}
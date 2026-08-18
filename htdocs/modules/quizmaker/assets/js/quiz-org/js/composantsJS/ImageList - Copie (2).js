import { BaseGameGrid } from './BaseGameGrid.js';

export class ImageList extends BaseGameGrid {
    constructor(imageUrls) {
        super();
        this.imageUrls = imageUrls;
    }

    getPieces(enableDnD = false) {
        const pieces = [];

        this.imageUrls.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'piece';
            if (enableDnD) div.setAttribute('draggable', 'true');

            // Le conteneur s'adapte proprement
            Object.assign(div.style, {
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                width: '100%',
                backgroundColor: 'transparent',
                overflow: 'hidden'
            });

            const img = document.createElement('img');
            img.src = url;
            
            // Astuce : on s'assure que l'image s'ajuste à la largeur de la case
            // tout en gardant sa proportion naturelle grâce au comportement de l'objet, 
            // ou en s'appuyant sur le chargement.
            Object.assign(img.style, {
                width: '100%',
                height: 'auto',
                maxHeight: '100%',
                objectFit: 'contain',
                display: 'block'
            });

            // Petit plus pour que le navigateur gère le ratio dès le chargement si besoin :
            img.onload = () => {
                if (img.naturalWidth && img.naturalHeight) {
                    div.style.aspectRatio = `${img.naturalWidth} / ${img.naturalHeight}`;
                }
            };

            div.appendChild(img);

            div.dataset.originalIndex = index;
            div.dataset.flipped = "false";
            pieces.push(div);
        });

        return pieces;
    }
}
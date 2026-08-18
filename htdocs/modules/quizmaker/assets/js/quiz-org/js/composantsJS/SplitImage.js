import { BaseGameGrid } from './BaseGameGrid.js';

export class SplitImage extends BaseGameGrid {
    constructor(imageUrl, cols, rows, imgWidth, imgHeight, isPuzzle = true, events = {}) {
        super(events);
    //alert(`SplitImage - events nb : ${this.events.length}`)
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
                if (enableDnD) div.setAttribute('draggable', 'true');
                
                const bgPosX = this.cols > 1 ? (c / (this.cols - 1)) * 100 : 0;
                const bgPosY = this.rows > 1 ? (r / (this.rows - 1)) * 100 : 0;
                
                this.aspectRatioValue = (this.imgWidth / this.cols) / (this.imgHeight / this.rows);
                
                Object.assign(div.style, {
                    backgroundImage: `url(${this.imageUrl})`,
                    backgroundSize: `${this.cols * 100}% ${this.rows * 100}%`,
                    backgroundPosition: `${bgPosX}% ${bgPosY}%`,
                    aspectRatio: this.aspectRatioValue
                });
    
                div.dataset.originalIndex = indexCounter++;
                div.dataset.flipped = "false";
                pieces.push(div);
            }
        }
        return pieces;
    }
}
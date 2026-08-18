export class Disc {
    constructor(parentElement) {
        this.el = document.createElement('div');

        // On initialise la variable CSS à 360deg (cercle complet)
        this.el.style.setProperty('--angle', '360deg');
        
        this.el.style.cssText = `
            width: 100px; height: 100px; border-radius: 50%;
            transition: --angle ${duration}s linear;
            background: conic-gradient(#FF9800 var(--angle), yellow 0deg);
        `;
        
        if(typeof(parentElement) === 'string'){parentElement = document.getElementById(parentElement);}
        parentElement.appendChild(this.el);
    }

    start(duration = 3) {
        // Pour déclencher l'animation, on change la valeur de la variable
        this.el.style.setProperty('--angle', '0deg');
    }
}
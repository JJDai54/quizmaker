
export class Gauge {
    constructor(parentElement, orientation = 'horizontal', sens = 0, longueur=200, epaisseur=12) {
        let radius = Math.round(epaisseur / 2) + 'px';
        parentElement.innerHTML = '';
        this.wrapper = document.createElement('div');
        this.wrapper.style.cssText = "background:yellow; border:1px solid #999; overflow:hidden;";
        this.wrapper.style.borderRadius = radius;
        this.fill = document.createElement('div');
        this.fill.id = parentElement + '-' + 'tempo';
        this.fill.style.backgroundColor = '#CCFFFF';
        this.fill.style.borderRadius = radius;
        //this.fill.style.zIndex = 10000;
        
        // Dimensions
        //const size = orientation === 'horizontal' ? {w: `${}px`, h: '20px'} : {w: '20px', h: '200px'};
        const size = orientation === 'horizontal' ? {w: `${longueur}px`, h: `${epaisseur}px`} : {w: `${epaisseur}px`, h: `${longueur}px`};
        this.wrapper.style.width = size.w;
        this.wrapper.style.height = size.h;
        this.fill.style.width = size.w;
        this.fill.style.height = size.h;
        
        if(typeof(parentElement) === 'string'){parentElement = document.getElementById(parentElement);}
        this.wrapper.appendChild(this.fill);
        parentElement.appendChild(this.wrapper);
        this.sens = sens;
        this.duration = 3;
        this.prop = orientation === 'horizontal' ? 'width' : 'height';
    }
    
 /* *****************************************
 *
 * ****************************************** */
setColor(bgBarre, bgText){
        this.fill.style.backgroundColor = bgBarre;
        this.wrapper.style.backgroundColor = bgText;
}


    start(duration = 3, options = {}) {

        const { 
                showMask = true, 
                color = 'rgba(0,0,0)' ,
                opacity = .25
                } = options;

        
        if(!duration) duration = this.duration;
        if(this.sens == 1){
            var debut = '100%'
            var fin   = '0%'
        }else{
            var debut = '0%'
            var fin   = '100%'
        }
        this.fill.style.transition = 'none';
        this.fill.style[this.prop] = debut;
        this.fill.offsetHeight;
        this.fill.style.transition = `${this.prop} ${duration}s linear`;
        this.fill.style[this.prop] = fin;
        //-------------------------------------------------
    if (showMask) {
        // Création du masque avec les options
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: ${color}; 
            opacity : ${opacity};
            z-index: 9999;
        `;
        document.body.appendChild(overlay);
        
        // Suppression automatique
        setTimeout(() => {
            document.body.removeChild(overlay);
        }, duration * 1000);
    }
        console.log(`Démarrage pour ${duration}s avec couleur: ${color}`);
    }
    
}


//alert('chargement de Gauje.js ala fin du js');

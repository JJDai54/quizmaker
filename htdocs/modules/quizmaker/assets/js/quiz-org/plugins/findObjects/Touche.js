 /*******************************************************************
  *                     class Touche
  * *****************************************************************/
class Touche{
parent = null;
#rootId='touche-';
id='';
chrono = 0;                     // numero du touche. il pourra etre différent de l'index dans le tableau si remove est géré
x = 0;                          // coordonnée horizontale
y = 0;                          // coordonnées vertical à partir du haut
w = 0;                          // largeur de l'image, a utiliser pour remettre à l'échelle
h = 0;                          // hauteur de l'image, a utiliser pour remettre à l'échelle 
wRef = 0;                       // largeur de l'image de reference
hRef = 0;                       // hauteur de l'image de référence
tics = 0;                       // nom de fois ou la touche a été clicquée
caption='';                     // Titre de la touche
divTouche = [null,null,null];   //l'index 0 restera inutilise : liste des div pour l'image 1 et l'image 2

borderColor = 'red';            // Couleur par defaut de la bordure
borderRadius = 25;              // rayon des coins du div en %
borderWidth = 2;                // Epaisseur par défaut de la bordure
points = 1;

// impoetant : index est une valeur volatile du numero d'ordre dans la collection retourné suite à une recherche
//             a utilisé immédiatement après la recherche car elle peut changé en cas de suppression ou d'ajout
index = -1;

/* ***************************************
chrono : numero du touche. il pourra etre différent de l'index dans le tableau si remove est géré
x : coordonnée horizontale
y : coordonnées vertical à partir du haut
r : rayon des coins du div
w : largeur de l'image, a utiliser pour remettre à l'échelle
h : hauteur de l'image, a utiliser pour remettre à l'échelle 
wRef : largeur de l'image de reference
hRef : hauteur de l'image de référence

**************************************** */
constructor(chrono, x, y, w, h,  wRef, hRef, borderRadius = 50, borderWidth=2){
    this.chrono = chrono;
    this.id = this.#rootId + this.chrono;
    this.x = x*1;
    this.y = y*1;
    this.w = w*1;
    this.h = h*1;
    this.borderRadius = borderRadius;
    this.borderWidth = borderWidth;
    this.wRef = wRef;
    this.hRef = hRef;
}

/* ***************************************
return un id unique pour chaque balise div qui reprente une touche
**************************************** */
getToucheId(numImage){
    return `divToucheId-${this.chrono}-${numImage}`;
}

/* ***************************************
calcule du coefficient de redimentionnement de la taille et de la position
 par rapport à l'image principale.
obSource : div ou se trouve les divImg a déplaver ou retailler
**************************************** */
getCoef(obSource){

    //return (obSource.offsetWidth*1) / (this.parent.imgSize1.w*1);
    //return (obSource.offsetWidth*1) / (this.wRef*1);
    return {'w' : (obSource.offsetWidth*1) / (this.wRef*1), 'h' : (obSource.offsetHeight*1) / (this.hRef*1)} ;
}
/* ***************************************
calcule du coefficient de redimentionnement de la taille et de la position
 par rapport à l'image principale.
obSource : div ou se trouve les divImg a déplaver ou retailler
**************************************** */
getId(sType){
    return `findObject[${this.chrono}][${sType}]`; 
}
/* ***************************************

**************************************** */
setBorderColor(kolor){
    this.borderColor = kolor;
    if(this.divTouche[1]) this.divTouche[1].style.borderColor = kolor;
    if(this.divTouche[2]) this.divTouche[2].style.borderColor = kolor;
}
/* ***************************************

**************************************** */
getBorderColor(){
    return this.borderColor;
}

/* ***************************************
calcul la position et la taille relative au divImg 
qui peut etre de taille différente de l'original
**************************************** */
getRelativeSize(obSource = null){

    //var coef = (obSource) ? this.parent.getCoef(obSource) : this.parent.coef;
    var coef = this.getCoef(obSource).w;
 // alert('getRelativeSize = ' + coef)  
    
    //option d'arrondir au pixel plus pratique pour debuguer
    var x = this.x*1*coef;
    var y = this.y*1*coef;
    var w = this.w * coef;
    var h = this.h * coef;
    var rw =  this.w * coef / 2;
    var rh =  this.h * coef / 2;
    
    var xyAbsolute = this.getAbsolutePosition(obSource);
    //console.log(`getRelativeSize->absolute : ${xyAbsolute.x} - ${xyAbsolute.y}`);
    var coX = x + xyAbsolute.x - rw;
    var coY = y + xyAbsolute.y - rh;
    
    console.log(`getRelativeSize : x = ${x} - y = ${y}`);
    return {'x': coX, 'y': coY, 'w' : w, 'h' : h, 'rw' : rw, 'rh' : rh};
}

/* ***************************************
algorithme qui calcul la position absolue d'un div sur une page html 
**************************************** */
getAbsolutePosition(element) {
  return { 'x': 0, 'y': 0 };
  let x = 0;
  let y = 0;
  let currentElement = element;

  while (currentElement && currentElement !== document.body) {
    x += currentElement.offsetLeft;
    y += currentElement.offsetTop;
    currentElement = currentElement.offsetParent;
  }
    console.log(`getAbsolutePosition :  obSource : ${element.id} - x = ${x} - y = ${y}`);

  return { 'x': x, 'y': y };
}


/* ***************************************

**************************************** */
moveDiv(obSource, numImage=1){
  var rps = this.getRelativeSize(obSource);
  var divTouche = this.divTouche[numImage];

if(!divTouche){
    var divTouche = this.getNewDivTouche(obSource, numImage);
}  
//alert('moveDiv : ' + divTouche.id);
  console.log(`moveDiv :  obSource : ${obSource.id} - x = ${rps.x} - y = ${rps.y}`);

  //    alert(divTouche.id + `xr = ${xr} - rps = {yr}`);
   divTouche.style.left = rps.x + unitPx;
   divTouche.style.top  = rps.y + unitPx;

    divTouche.style.width  = Math.round(rps.w) + unitPx;
    divTouche.style.height = Math.round(rps.h) + unitPx;
    divTouche.style.borderColor = this.borderColor;
    divTouche.style.borderRadius = this.borderRadius + unitPercent;
    divTouche.style.borderWidth  = this.borderWidth  + unitPx;
}
/* ***************************************

**************************************** */
getNewTouche(numImage){
var obSource = (numImage == 2) ? this.parent.divImg2 : this.parent.divImg1;
    if(!obSource) return false;
    return this.getNewDivTouche(obSource,numImage);
}

/* ***************************************

**************************************** */
getNewDivTouche(obSource, numImage){
    //l'element html est deja créee
    if(this.divTouche[numImage]) {
        //alert('getNewDivTouche : la touche existe : ' + this.divTouche[numImage].id)
        return this.divTouche[numImage];
    }

    var rps = this.getRelativeSize(obSource); 
    this.divTouche[numImage] = obSource.appendChild(document.createElement("div"));
    var newDiv = this.divTouche[numImage];
    newDiv.id = this.getToucheId(numImage);

    newDiv.setAttribute('name', this.parent.toucheName + numImage);
    newDiv.classList.add("touche");
//alert(`getNewDivTouche : rps.x = ${rps.x}`);
    newDiv.style.left = rps.x + unitPx;
    newDiv.style.top  = rps.y + unitPx;

console.log(`getNewDivTouche : ${rps.x}px / ${rps.y}px`);
    newDiv.style.width  = Math.round(rps.w) + unitPx;
    newDiv.style.height = Math.round(rps.h) + unitPx;
    
    newDiv.style.borderColor  = this.borderColor; 
    newDiv.style.borderRadius = this.borderRadius + unitPercent;
    newDiv.style.borderWidth  = this.borderWidth  + unitPx;
    //console.log(`=>getNewDivTouche : id ${this.divToucheId1} - x = ${rps.x} - y = ${rps.y} - w ${this.w} - h = ${this.h}`);
    //newDiv.style.background = 'red'; 
    
    //pas utile utilisé utilisé pendant le div
//     newDiv.setAttribute('title','zzzzzzzzzzz');
//     newDiv.style.background = 'red'; 
    
    return newDiv;
}
/* ***************************************

**************************************** */
isClickInTouche(x,y,coef=0){
    if (this.borderRadius*1 > 36){
        return this.isClickInEllipse(x,y,coef);
    }else{
        return this.isClickInRectangle(x,y,coef);
    }
}

/* ***************************************
'y' est multiplier par -1 parceque les coordonnée y=0 sont en haut
on se retrouve donc dans le mauvais quart du repere autonormé

C'est pourtant pas compliqué, tout est dit dans l'équation d'une ellipse :
( (x - a)/X )² + ( (y - b)/Y )² = 1
(a, b) étant le centre de l'ellipse.

Si le touche (x, y) vérifie cette équation, alors il appartient à l'ellipse. Le touche se trouve à l'intérieur si on a < 1 au lieu de = 1 et à l'extérieur si on a > 1.

Si l'ellipse est définie par les coordonnées des deux touches A et B du rectangle circonscrit, on a:
a = (xA + xB)/2
b = (yA + yB)/2
X = |xB - xA|/2
Y = |yB - yA|/2 
**************************************** */
isClickInEllipse(x,y,coef=0){
console.log(`===> isClickInEllipse : x = ${x} - y = ${y} - borderRadius = ${this.borderRadius}`);
/* test de verification de la formue
x=50;
y=60;
a=55;
b=55;
r=20;
*/
    //touche cliqué avec la souris
    x = x * 1;
    y = y * 1;
    if(coef == 0) coef = 1;
    
    //centre de l'ellipse
    var a = this.x*1*coef;
    var b = this.y*1*coef;
    
    //rayons de l'ellipse
    var rw = this.w*1/2;
    var rh = this.h*1/2;
    var res = Math.pow( (x - a)/rw ,2) + Math.pow( (y - b)/rh ,2); // = 1
    var ret = (res < 1);
var exp = `isClickInEllipse=> : \ncoef : ${coef} -  x = ${x} - y = ${y} - a = ${a} - b = ${b} - rw = ${rw} - rh = ${rh} - res = ${res}`;    

    return ret;
}

/* ***************************************

**************************************** */
isClickInRectangle(x,y,coef=0){
console.log(`===> isClickInRectangle : x = ${x} - y = ${y} - borderRadius = ${this.borderRadius}`);
    //touche cliqué avec la souris
    x = x * 1;
    y = y * 1;
    if(coef == 0) coef = 1;
    
    
    //centre de l'ellipse
    var rx1 = (this.x*1-(this.w*1/2))*coef;
    var ry1 = (this.y*1-(this.h*1/2))*coef;
    
    var rw = this.w*1;
    var rh = this.h*1;
    
    //rayons de l'ellipse
    var rx2 = rx1 + this.w*coef;
    var ry2 = ry1 + this.h*coef;
    
    var ret = ((x >= rx1 && x <= rx2) && (y >= ry1 && y <= ry2));
    
var exp = `===> isClickInRectangle=> : \ncoef : ${coef} -  rx1 = ${rx1} - ry1 = ${ry1} - rx2 = ${rx2} - ry2 = ${ry2} - rw = ${rw} - rh = ${rh}`;    
console.log (exp);
    console.log((ret) ? 'Bingo' : 'Pas Glop');

    return ret;
}

/* ***************************************

**************************************** */

toString(toLog = true){
    var exp=[];
    exp.push('Touche =>' + this.caption + "\n");
    exp.push('id = ' + this.id);
    exp.push('x = ' + this.x);
    exp.push('y = ' + this.y);
    exp.push('w = ' + this.w);
    exp.push('h = ' + this.h);
    exp.push('wRef = ' + this.wRef);
    exp.push('hRef = ' + this.hRef);
    var ret = exp.join(' | ');
    if(toLog) console.log(ret);
    return ret;
}


} // ------ fin de la classe ----

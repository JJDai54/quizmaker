 var unitPx = 'px';
 var unitPercent = '%';
 /*******************************************************************
  *                     class Touches (collection de Touche)
  * *****************************************************************/
  
/*
Cette classe est destinée a créer des jeux basés sur une ou deux images et des touches à trouver
Une touche est défin pa les coordonnées d'un points et une surface autour de ce point
cette surface peut être elliptique ou rectangulaire

cas n° 1  il n'y a qu'une image principale.
Le jeu pourra consister par exeple à trouver des objets cachés 
ou tous les objets répondant à un critère, 
par exemple tous ceux commençant par la lettre "k"

cas n¨ 2 : Il y a en plus une image secondaire.
Le jeu pourra consister par exemple à trouver les différences
ou au contraire les touches communs comme des objets cachés.

il faudra procéder en deux temps:
- definir les touches avec leur position et leur taille par rapport à l'image principale
- definir le jeu avec une ou deux images qui pourront être de taille différente
 lors de la conception. la tille et la possition sont recalculer 
 en fonction du coefficient :
 largeur de l'imge du jeu / largeur de l'image de préparation 
 

*/

class Touches{

#version = 1.8;
#dateRelease = '2025-05-11';
#author = 'JJDai (jjdelalandre@orange.fr)';

//collection de Touche - classe Touche
collection = [];

//numero chronologique affecter lors de l'ajout d'un nouveau touche
chrono = 0;

//nombre maximum de touches qu'il est possible d'ajouter
//exemple 7 : pour le jeu chercher les 7 diff&érences
maxTouches = 0;

//Nombre maximum d'essais   
// le nombre d'essai pourra etre inférieur au nombre de touche à trouver
//dans ce cas le joueur aura gagnési il a trouver maxAttempts touches sans se tromper
// si maxAttempts = 5 et que le joueur cliquer 4 fois dans le mille mais une fois à côté il aura perdu
//maxAttempts = 0 : il n'y a pas de limite au nombre d'essai
attempts = {'max': 0, 'total': 0,'winning': 0, 'losing' : 0, 'totalWinning' : 0};

nbTouches = 0;

//stocke le dernier numero d'erreur 
lastErreur = 0;
/*
 0 : pas d'erreur
 1 : Aucune touche n'est definie.
 2 : Le nombre de touche maximum est atteind.
 3 : Aucune touche de trouvée pour ce critère.
 4 : Le point clicker n'appartient à aucune touche.
 2 : Le nombre d'essai est dépassé.
*/

//attribur "namz" affecter au divTouche.
//permet de les supprimer plus facilement en recupérent la collection
//document.getElementsByName(toucheName);
toucheName = 'allDivTouches';

//Taille en largeur et hauteur des touches
toucheSize = {'w':36, 'h':36, 'borderRadius' : 50, 'borderWidth' : 2}; //taille des touches par defaut w=width, h=height, b=border

//Image principale sur la quelle il faudra definir ou trouver les touche clickable
divImg1 = null;
//taille de l'image de reference 1 initialisé dans le constructeur
//imgSize1 = null; 

//image secondaire utilisée pour par exemple "Chercher les différences"
divImg2 = null;
//taille de l'image de reference 2 initialisé dans le constructeur
//imgSize2 = null; 


//utiliser pour le dev.
//Couleur de bordure affectée lors de la cration d'un divTouche afin de mieux les repérer
#colorArr = ['red','green','blue','cyan','yellow','braun','lime', 'violet','magenta','saumon',
             'fushia','yellowgreen', 'rosybrown','sienna','mistyrose','lightgray','white','black','maroon','orange'];
//Coefficient calcullé à partir de la largeur des deux images
//si la deuxième image n'est pas défini, le coef sera toujours égal à 1
coef = 1;

lastErreur = 0;
    
/* ***************************************
maxTouches : nombre de touches maximum a créer - si =0 illimité
divImg1 : div qui contient l'image principale obligatoire
divImg2 : di qui contient l'image secondaire optionnelle
**************************************** */
constructor(maxTouches, maxAttemps, divImg1, divImg2=null){
    
    this.maxTouches = maxTouches;
    this.attempts.max = maxAttemps;
    
    this.divImg1 = divImg1;
    this.imgSize1 = {'w':divImg1.offsetWidth, 'h':divImg1.offsetHeight}; 

    if(divImg2) {
        this.divImg2 = divImg2;
        this.imgSize2 = {'w':divImg2.offsetWidth, 'h':divImg2.offsetHeight}; 
        this.coef = (divImg2.offsetWidth*1) / (this.divImg1.offsetWidth*1);
    }

}

/* ***************************************

**************************************** */
getVersion(){
    return `Classe \"Touches\" version ${this.#version} du ${this.#dateRelease} - Auteur ${this.#author}`;
}
    
/* ***************************************
calcule du coefficient de redimentionnement de la taille et de la position
 par rapport à l'image principale.
obSource : div ou se trouve les divImg a déplaver ou retailler
**************************************** */
getCoef(obSource){
    //return (obSource.offsetWidth*1) / (this.parent.imgSize1.w*1);
    return (obSource.offsetWidth*1) / (this.divImg1.offsetWidth*1);
}

/* ***************************************
defini la taille originale des touches par rapport à l'image principale 
**************************************** */
setToucheSize(width, height, borderRadius = 25, borderWidth = 2){
    this.toucheSize = {'w':width*1, 'h':height*1, 'borderRadius' : borderRadius, 'borderWidth' : borderWidth}; 
}


/* ***************************************
addNew : ajoute un touche à la collection.
x : coordonnée horizontale
y : coordonnées vertical à partir du haut
r : rayon du touche
w : largeur de l'image, a utiliser pour remettre à l'échelle
h : hauteur de l'image, a utiliser pour remettre à l'échelle 
wRef : largeur de l'image de reference
hRef : hauteur de l'image de référence
return : null : si le nombre de touches est superrieur ou egal a maxTouches
         nouveau touche si si le nombre de touches est inférieur a maxTouches
**************************************** */
addNew(x, y, wRef, hRef){
    /* désactivé provisoirement avant suppression definive si il s'avère que ce controle n'est pas utile
    if(this.maxTouches !=0 && this.collection.length >= this.maxTouches){
        this.lastErreur = 2;
        return null
    }
    */
    
    var clTouche = new Touche(this.chrono, x, y, this.toucheSize.w, this.toucheSize.h, wRef, hRef);
    clTouche.parent = this;
    clTouche.caption      =  `Touche n° ${clTouche.chrono}`;
    clTouche.borderColor  =  this.getColor(clTouche.chrono);
    clTouche.borderRadius =  this.toucheSize.borderRadius;
    clTouche.borderWidth  =  this.toucheSize.borderWidth;
    this.collection.push(clTouche);
    this.chrono++;
    //alert (clTouche.borderColor);
    this.nbTouches = this.collection.length;    
    this.lastErreur = 0;
    return clTouche;
}

/* *********************************************

************************************************ */
addNewFromBufferPHP(buffer){
    var dataArr = JSON.parse(JSON.stringify(buffer));
    return this.addNewFromBuffer(dataArr);
}
/* *********************************************

************************************************ */
addNewFromBufferJS(buffer){
    var txt = document.createElement("textarea");
    txt.innerHTML = buffer;
    buffer  = txt.value;
    var dataArr = JSON.parse(buffer);
    return this.addNewFromBuffer(dataArr);
}

/* *********************************************

************************************************ */
addNewFromBuffer(dataArr){
//exemple de buffer : 
//'{"txt":"Touche n\u00b0 0","answerId":"0","hidden":"red","inpx":"466","inpy":"108","inpw":"36","inph":"36","touches":"1","wRef":"700","hRef":"392"}'
//       alert(JSON.stringify(buffer));             
//var dataArr = JSON.parse(buffer);
//alert(dataArr.caption);
//var dataArr = JSON.parse(buffer);
    console.log(`addNewFromBuffer : ${dataArr['caption']} - ${dataArr['borderColor']} - ${dataArr['x']} ` );

     var clTouche = new Touche(this.chrono++,  dataArr['x']*1, dataArr['y']*1,  
                               dataArr['w']*1, dataArr['h']*1, 
                               dataArr['wRef']*1, dataArr['hRef']*1,
                               dataArr['borderRadius'],
                               dataArr['borderWidth']);
    clTouche.parent = this;
    clTouche.caption = dataArr['caption'];
    clTouche.borderColor = dataArr['borderColor'];
    clTouche.points = dataArr['points'];
    if(clTouche.points > 0) {this.attempts.totalWinning++;}
    //clTouche.borderWidth = this.toucheSize.borderWidth;
    this.collection.push(clTouche);
    //alert(this.divImg1);
    //clTouche.getNewDivTouche(document.getElementById(this.divImg1), 1);
    //clTouche.getNewDivTouche(this.divImg1, 1);
    this.nbTouches = this.collection.length;
return clTouche;
}


/* *********************************************

************************************************ */
refresh(obSource){
    for(var k = 0; k < this.collection.length; k++){
        var clTouche =  this.collection[k];
        clTouche.moveDiv(this.divImg1,1);
//alert('refresh : ' + clTouche.caption);
        clTouche.moveDiv(this.divImg2,2);
    }

}


/* ***************************************

**************************************** */
isFull(){
console.log(`isFull : ${this.collection.length} - ${this.maxTouches}`);
    return (this.collection.length >= this.maxTouches);
}
/* ***************************************

**************************************** */
setMaxAttempts(maxAttemps){
    this.attempts.max = maxAttemps;
}
getMaxAttempts(){
    return this.attempts.max;
}

/* ***************************************

**************************************** */
// length(){
//     return this.collection.length;
// }

/* ***************************************

**************************************** */
getErreur(){
    return this.lastErreur;
}

/* ***************************************
ne pas confondre index et chrono
index : numero dans le tableau
chrono: numero incrementer a chaque ajour, en cas de suppression peut etre différent de indes
**************************************** */
remove(index){
    return this.collection.splice(index, 1);
    this.nbTouches = this.collection.length;    
}
/* ***************************************
renvoi l'index du touche dans la collection
une recherche plutot que d'utiliser l'index du tableau au cas ou remove serait utilisé
**************************************** */
getToucheByChrono(chrono){
//alert('collection = ' + this.collection.length);
    if(this.collection.length == 0){
        this.lastErreur = 1;
        return null;
    }
    console.log(`getToucheByChrono : chrono = ${chrono}`);
    chrono = chrono * 1;
    for(var k = 0; k < this.collection.length; k++){
        var clTouche =  this.collection[k];
//console.log(`=====${k}\nx = ${x}\ny = ${y}\ndif-x = ${options.differences[k].x}\ndif-y = ${options.differences[k].y}\nrayon = ${options.rayon}`);             
        if(clTouche.chrono == chrono){
            console.log(clTouche.borderColor);
            this.lastErreur = 0;
            return clTouche;
            break;
        } 
    }
    this.lastErreur = 3;
    return null;
}
/* ***************************************
renvoi un touche en fonction de son numero d'ordre chronologique
ou
renvoi l'index du touche dans la collection
une recherche plutot que d'utiliser l'index du tableau au cas ou remove serait utilisé
asObject : true  = renvoi l'objet touche
           false = renvoi l'index dans le tableau de la collection
**************************************** */
getToucheByChrono(chrono, asObject = true){
//alert('collection = ' + this.collection.length);
    if(this.collection.length == 0){
        this.lastErreur = 2;
        return null;
    }
    console.log(`getToucheByChrono : chrono = ${chrono}`);
    chrono = chrono * 1;
    for(var k = 0; k < this.collection.length; k++){
        var clTouche =  this.collection[k];
//console.log(`=====${k}\nx = ${x}\ny = ${y}\ndif-x = ${options.differences[k].x}\ndif-y = ${options.differences[k].y}\nrayon = ${options.rayon}`);             
        if(clTouche.chrono == chrono){
            console.log(clTouche.borderColor);
            clTouche.index = k; 
            this.lastErreur = 0;
            return (asObject) ? clTouche : k;
            break;
        } 
    }
    this.lastErreur = 3;
    return null;
}
/* ***************************************
recherche un touche par ses coordonées
x et y sont les coordonnées de la souris par rapport au div(obSource) passé en parametre
si obSource est le divImg1 le coef sera egal à 1 sinon il sera fonction de la taille de obSource
**************************************** */
findToucheInXY(obSource, x, y, stillClicked = false){
    if(this.collection.length == 0){
        this.lastErreur = 1;
        return null;
    }


    this.attempts.total++;
    if(this.attempts.max > 0 && this.attempts.total > this.attempts.max){
        this.lastErreur = 5;

        return null;
    }
    //----------------------------------------------------------

    console.log(`findToucheInXY =================`);
    console.log(`findToucheInXY : this.w = ${this.imgSize1.w*1} - obSource.offsetWidth =  ${obSource.offsetWidth*1}`);
    //console.log(`findToucheInXY : x = ${x} - y =  ${y} - xRelative = ${xRelative} - yRelative = ${yRelative} - coef = ${coef}`);
    
    for(var k = 0; k < this.collection.length; k++){
        var clTouche =  this.collection[k];
        var coef = Math.round(obSource.offsetWidth / clTouche.wRef * 100) / 100;
    
        //if(clTouche) console.log('touche ok : ' + clTouche.caption);
//console.log(`=====${k}x = ${x} - y = ${y} - coef = ${coef}`);    
console.log(clTouche.toString());         
        if((clTouche.tics==0 || stillClicked) && clTouche.isClickInTouche(x, y, coef)){
            //si la tuche a déjà été& trouvée on compte 1 dans losing
            if(clTouche.tics == 0 && clTouche.points > 0 ){
                this.attempts.winning++;
            }else{
                this.attempts.losing++;
            }
            clTouche.tics++;
            clTouche.index = k; 
            this.lastErreur = 0;
            return clTouche;
            break;
        } 
    }
    this.attempts.losing++;
    this.lastErreur = 4;
    return null;
}
/* ***************************************
Actualise les dimentions de référence de chaque touche
il peut arriver que lors de la conception, l'imafe de refence est changé de taille (modification du CSSclasse divImageRef1 par exemple.)
il est donc nécéssaire de redéfinir wRef et hRef.
il sera peut être nécéssaire de replacer les touches apès ce traitement ce justifiera cette mise à jour
**************************************** */
updateSizeRef(obSource){
    var wRef = obSource.offsetWidth;
    var hRef = obSource.offsetHeight;
    

    for(var k = 0; k < this.collection.length; k++){
        var clTouche =  this.collection[k];
        alert(clTouche.caption);
        //si elle sont correcte intile de réajuster la position et la taille de la touche
        if(wRef == clTouche.wRef && hRef == clTouche.hRef ){continue;}
        var coef = Math.round(obSource.offsetWidth / clTouche.wRef * 100) / 100;
        
        //mise a jour des dimmentions de l'image de  référence 
        clTouche.wRef = wRef;
        clTouche.hRef = hRef;
        
        //réajustement de coordonnées de la touche
        clTouche.x = clTouche.x * coef;
        clTouche.y = clTouche.x * coef;
        
        //réajustement des dimention de la touche
        clTouche.w = clTouche.w * coef;
        clTouche.h = clTouche.h * coef;
        
    
    }
    this.lastErreur = 0;
    return true;
}
/* ***************************************
recherche les touches trouvés
et renvoi un tableau deces  touches
**************************************** */
getTouchesFound(){
var arr = [];    
    for(var k = 0; k < this.collection.length; k++){
        if (clTouche.tics > 0){
            arr.push(this.collection[k]);
        } 
    }
    return arr;
}
/* ***************************************
affecte une couleur de bordure pour mieux repérer les touche sur l'image
**************************************** */
getColor(index){
    index = (index*1) % this.#colorArr.length;
    return (index < this.#colorArr.length) ? this.#colorArr[index] : this.#colorArr[0];
}

/* ***************************************
supprime uniquement les balises divdéfini par les divTouche
mais ne supprime pas les touches de la collection
toto : utiliser plutot la collection avec this.divTouche[numImage]
**************************************** */
clearAllDivTouches(numImage = 0){
    if(numImage == 0){
        this.clearAllDivTouches(1);
        this.clearAllDivTouches(2);
    }else{
        for(var k = 0; k < this.collection.length; k++){
            clTouche = this.collection[k];
            clTouche.divTouche[numImage].remove;
        }
    }
    
}
clearAllDivTouches_old(numImage = 0){
    if(numImage == 0){
        this.clearAllDivTouches(1);
        this.clearAllDivTouches(2);
    }else{
      var arr = document.getElementsByName(colTouches.toucheName + numImage);
      //il faut commencer par la finsion on efface pas tou
      for(var k = arr.length-1; k >= 0 ; k--){
          arr[k].remove();
      }
    }
    
}


removeAll(){
    this.clearAllDivTouches();
    this.collection = [];
    this.nbTouches = this.collection.length;    
}


} // ------ fin de la classe ----


/* *******************************************
                CLASS Messages
********************************************** */
class Messages{
#messages = [
"00 : Classe Touches version {p0} du {p1} - Auteur {p2}",
"01 : La collection de touche est vide",
"02 : Impossible d'ajouter de nouvelles touches, le nombre maximum de {p0} est atteint",
"03 : Vous avez atteind le nombre d'essai maximum de {maxtry} essais",
"04 : Pas de chance vous avez tapé à côté en {p0} x {p1}",
"05 : Bingo vous avez tapé dans le mille - Touche touché : {p0} : {p1}",
"Nombre d'objets : "
];

divMessages = null;
chrono = 0;

/* *******************************************

********************************************** */
constructor(divMessageId){
    //alert('class Messages chargée');
    this.divMessages = document.getElementById(divMessageId);
}

/* *******************************************

********************************************** */
clear(){
    this.divMessages.innerHTML = '';
}

/* *******************************************

********************************************** */
show(exp, clearBefore = false){
    if (clearBefore){
        this.divMessages.innerHTML = `${this.chrono++} : ` + exp;
    }else{
        this.divMessages.innerHTML = `${this.chrono++} : ` + exp + '<br>' + this.divMessages.innerHTML;
    }
}
/* *******************************************

********************************************** */
showFIFO(exp, clearBefore = false){
    if (clearBefore){
        this.divMessages.innerHTML = `${this.chrono++} : ` + exp;
    }else{
        this.divMessages.innerHTML += '<br>' + `${this.chrono++} : ` + exp;
    }
}

/* *******************************************

********************************************** */
showNum(numMessage, paramArr=[]){
    this.divMessages.innerHTML = `${this.chrono++} : ` + this.getNum(numMessage, paramArr) + '<br>' + this.divMessages.innerHTML;
}
/* *******************************************

********************************************** */
showNumFIFO(numMessage, paramArr=[]){
    this.divMessages.innerHTML += '<br>' + `${this.chrono++} : ` +  this.getNum(numMessage, paramArr);
}
/* *******************************************

********************************************** */
getNum(numMessage, paramArr=[]){
    // si le message n'est pas défini affichage direct sans les paramètres
    if(!this.#messages[numMessage]) return '';
    
    var exp = this.#messages[numMessage];
    
    for(var k = 0; k < paramArr.length; k++){
        exp = exp.replaceAll(`\{p${k}\}`, paramArr[k]);
    }
    return exp;
}

} // --------------Fin de la class --------------------------
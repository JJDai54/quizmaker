function getPlugin_sortItems(question, slideNumber){
//alert(question.options.disposition);
    switch(question.options.variant){
    case '02-combobox'    : return new sortItems_combobox(question, slideNumber, 'sortItems_combobox'); break;
    case '03-listeapuces' : return new sortItems_ulDaDList(question, slideNumber, 'sortItems_ulDaDList'); break;
    case '04-imagesdadFixedHeight' : return new imgDaDSortItems(question, slideNumber, 'imgDaDSortItems'); break;
    case '05-imagesdadFixedWidth'  : return new imgDaDSortItems(question, slideNumber, 'imgDaDSortItems'); break;
    case '01-listbox': 
    default               : return new sortItems_listbox(question, slideNumber, 'sortItems_listbox')
    }
    //return new sortItems_combobox(question, slideNumber);
}  

 /*******************************************************************
  *                     sortItems
  * *****************************************************************/
/*
mettre dans le plugin sortItems les methodes communes et faire hériter les deux autre sur sortItems
*/
class sortItems extends Plugin_Prototype{
name = "sortItems";

/* *************************************
*
* ******** */
buildSlide (bShuffle = true){
    this.boolDog = true;
    return this.getInnerHTML(bShuffle);
    
 }

/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;

    //on force l'option de mélange des options sinon aucun intéret
    //currentQuestion.shuffleAnswers = 1;
    var tWords = [];
    for(var k=0; k < currentQuestion.answers.length; k++){
        tWords.push(replaceDoubleSlash(currentQuestion.answers[k].proposition)); 
    }

    
    this.data.words = tWords;  
    this.initMinMaxQQ(1);    
    this.idListbox =  this.getId('list',1);
} 
//---------------------------------------------------
verifNbItems(){
    if(this.data.words.length == 0){
        let  currentQuestion = this.question;
        var msg = `Question ${this.questionNumber} : ${currentQuestion.question}\nLa liste des expressions est vide\nVérifiez la !`;
        alert(msg);
        return false
    }

    return true;
}
//---------------------------------------------------
shuffleItems(){
    let  currentQuestion = this.question;
    if(!this.verifNbItems()) return null;
    
    var tItems = shuffleArray(this.data.words);
    var h=5;
    while(this.isListSorted(tItems) && h > 0){
        tItems = shuffleArray(this.data.words);
        h--;
    }
    return tItems;
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    //il n'y a pas de points par proposition, il faut trouver un ordre
    //on suppose que chaque items à sa place compte pour 1 points 
    //mais ce sera le nombre de points de la questions qui primera
    //this.scoreMaxiBP = this.question.answers.length;
    this.scoreMaxiBP = this.question.points;
}

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
      let  currentQuestion = this.question;


    var tReponses = [];
    var k = 0; 
    var t = [];
    for(var k in this.data.words){
        t.push ([k*1+1, this.data.words[k]]);
    }

    return formatArray0(t," - ", false);
 }

/* *************************************
*
* ******** */
isListSorted(tRep){

    let  currentQuestion = this.question;
    
    //transforme les listes des items en une chaine 
    //qui pourra etre comparée dans son ensemble au lieu de parrcourir les tableaux
    var strRep = tRep.join(',');
    var strProposition = this.data.words.join(',');
    
    //verifie si les deux chaine sont egales
    var bolOk = (strRep == strProposition);
    
    // si les deux chaine sont différente test l'ordre inverse si il est autorisé     
    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == strProposition);
    }
    //console.log('===>isListSorted : ' + ((bolOk)?'oui':'non'));
    return bolOk;
    
}

} /* ---------------- FIN DE LA CLASSE ----------------------------------*/



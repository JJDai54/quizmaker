function getPlugin_memosuite(question, slideNumber){
//alert(`plugin : ${question.options.variant}`);
    switch(question.options.variant){
    case 'liste'   : return new memosuite_liste(question, slideNumber, 'memosuite_liste'); break;
    default: 
    case 'grille'   : return new memosuite_grille(question, slideNumber, 'memosuite_grille'); break;
    }
}  


 /*******************************************************************
  *                     memosuite
  * *****************************************************************/
/*
mettre dans le plugin memosuite les methodes communes et faire hériter les deux autre sur memosuite
*/
class memosuite extends Plugin_Prototype{
name = "memosuite";
memosuiteIsLoad = false;
totalMinoration = 0;


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
getInnerHTML(bShuffle = true){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var memosuiteId = this.getId('memosuite');
    
    var imgUrl = options.gridUrl;
    var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.memosuiteIsLoad = false;
    var playEvent = `onclick='memosuite_play(event, ${this.slideNumber});'`;
    var playBtn = `<input type='button' id='${this.getId('playBtn')}'' name='${this.getId('playBtn')}' ${playEvent} value='${options.msg_newSequence}' class='memosuite_btn_play'>`;
        
  //alert(`===>getInnerHTML :! game = \n` +  game);    

    
    var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}`;
    html += `<center><div id='${this.getId('suite_maitre')}'></div>${playBtn}</center>`;
    html += `<div id='${memosuiteId}' variant='${options.variant}'></div></center>`;
    html += "<div id='mouchard'></div>";

    //////////////////////////////

    ////////////////////////////////////
//              + "nbClicks <input type='hidden' value='0'>";
//              + "nbClicks <input type='hidden' value='0'>";

    return html;
}

/* *************************************
*
* ******** */
getInnerHTML_sequence(bShuffle = true){
    
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    var clGridImg = this.clGridImg;
    var divWidth = 80;
    var divHeight = clGridImg.getCellHeight(divWidth);
    //document.getElementById(this.getId(suite)).height = 
    var divSuiteWidth = divWidth * options.nbImages; 
    //var event = `onclick='memosuite_masker_all(event, ${this.slideNumber},"${this.getId('memosuite')}");'`;
    //var event = `onclick='memosuite_play(event, ${this.slideNumber});'`;
    
    var html =`<center><div id='${this.getId('suite')}' class='memosuite_grille_divSequence' style='margin:auto;width:${divSuiteWidth}px;height:${divHeight};'>`;
    var style = `style='width:${divWidth}px;height:${divHeight}px;background:${options.background};'`;

    for(var h = 0; h < options.nbImages; h++){
        html += `<div id='${this.getId('carte')}-${h}' name='${this.getId('carte')}' numPiece='-1' class='memosuite_grille_divPieces' ${style}></div>`;
    }
    
    html +="</div></center>";
    return html;
 }
 /* *************************************
*
* ******** */
clear_sequence(){
    
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    var clGridImg = this.clGridImg;

    var selecteur = `div[name="${this.getId('carte')}"]`;
    var allObj = document.querySelectorAll(selecteur);
    for (var h=0; h< allObj.length; h++){
        allObj[h].style.backgroundImage='';
        allObj[h].setAttribute('numpiece',-1);
    }
    options.numCarte = 0;
    computeAllScoreEvent();        
    
  //document.getElementById(this.getId('suite')).classList.add('shake');
    
 }
 /* *************************************
*
* ******** */
show_sequence(goodSequence = true){
    
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    if(!options.newSuite && goodSequence == true) {
        alert(options.msgNoSequence);
        return false;
    }
    var clGridImg = this.clGridImg;

    var selecteur = `div[name="${this.getId('carte')}"]`;
    var allObj = document.querySelectorAll(selecteur);
    
    for (var h=0; h< allObj.length; h++){
        if(goodSequence){
            var divImgSource = document.getElementById(options.newSuite[h]);
        }else{
          var numPiece = getRandom(clGridImg.source.pieces-1);
          var divImgSource = document.getElementById(this.getId('piece', numPiece));
        }
        clGridImg.setImage(divImgSource, allObj[h]);

    }


 }
 /* *************************************
*
* ******** */
show_bad_sequence(){
    
    var currentQuestion = this.question;
    var options = currentQuestion.options;

    var clGridImg = this.clGridImg;

    var selecteur = `div[name="${this.getId('carte')}"]`;
    var allObj = document.querySelectorAll(selecteur);
    
    for (var h=0; h< allObj.length; h++){
        var numPiece = getRandom(clGridImg.source.pieces-1);
        var divImgSource = document.getElementById(this.getId('piece', numPiece));
        clGridImg.setImage(divImgSource, allObj[h]);

    }

 }

//---------------------------------------------------
onEnter() {
    super.onEnter();
      document.getElementById(this.getId('playBtn')).classList.add('memosuite_shake');
}

//---------------------------------------------------
onFinalyse (){

}


//---------------------------------------------------

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){
var score = 0;
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    if(!options.newSuite) return 0;
    
   var newSuite = options.newSuite
   var points = currentQuestion.answers[0].points; 
    
    var selecteur = `div[name="${this.getId('carte')}"]`;
    var allObj = document.querySelectorAll(selecteur);
    for (var h = 0; h < allObj.length; h++){
    var numPieceSequence = allObj[h].getAttribute('numPiece')*1; 
    var numPieceGrille = document.getElementById(options.newSuite[h]).getAttribute('numPiece');
    console.log(`${h}===>getScoreByProposition : \n numPieceSequence = ${numPieceSequence} \n numPieceGrille = ${numPieceGrille} \n score = ${score}`);
        if(numPieceSequence*1 == numPieceGrille*1){
            score += points;
        } 
    }
    
    if(score == this.scoreMaxiQQ){
        this.allowNextSlide = true;       
        this.show_sequence(); 
        //alert('Bingo');
    }     
    //var minoration = document.getElementById(this.getId('memosuite')).getAttribute('minoration')*1;    
    
   return score;
  }


/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "memosuite"
 }



/* ************************************
*
* **** */
reloadQuestion(reloadMode = reloadShuffle){
    //super.reloadQuestion(reloadMode);
    
    if(reloadMode == reloadOrg){
         var currentQuestion = this.question;
         var options = currentQuestion.options;
        
        options.newSuite = null;
        this.onFinalyse();
    }else if(reloadMode == reloadShuffle){
        this.show_sequence(false);
    }else{
        this.show_sequence(true);
    }
    
    
    
//    memosuite_reset(this.getId('memosuite'), true);
}


} // ----- fin de la class ------

//////////////////////////////////////////////////


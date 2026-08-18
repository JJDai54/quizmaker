//import { PuzzleComponent } from "PuzzleComponent.js";

function getPlugin_puzzle(question, slideNumber){
//alert(question.options.disposition);
//question.options.variant = 'puzzle';
//question.options.variant = 'memory';
//alert(question.options.variant);
/*
    switch(question.options.variant){
    case 'taquin'   : return new taquin(question, slideNumber, 'taquin'); break;
    case 'memory'   : return new memory(question, slideNumber, 'memory'); break;
    case 'lucioles' : return new lucioles(question, slideNumber, 'lucioles'); break;
    case 'puzzle'   : 
    default         : return new puzzle(question, slideNumber, 'puzzle');
    }
    //return new sortItems_combobox(question, slideNumber);
*/

    return new puzzle(question, slideNumber, 'puzzle')
    //return new puzzle(question, slideNumber);
}  

 /*******************************************************************
  *                     puzzle
  * *****************************************************************/
/*
mettre dans le plugin puzzle les methodes communes et faire hériter les deux autre sur puzzle
*/
class puzzle extends Plugin_Prototype{
name = "puzzle";

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
    this.gameId = this.getId('puzzle');
    
    let imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    let imgInfo = currentQuestion.answers[0].buffer.split('_');
    //var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.puzzleIsLoad = false;
 
    //var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${this.gameId}' data-slideNumber="${this.slideNumber}" ><hr><b>Puzzle</b><hr></div></center>`;
//alert('rotation : ' + options.rotation)
let rotatable = (options.rotation*1 === 1) ? 'rotatable' : '';
if(options.background=='black' || options.background=='#000000') {options.background = 'transparent';}
let html = `<puzzle-component 
    id="${this.gameId}"
    image="${imgUrl}" 
    cols="${options.imgCols}" 
    rows="${options.imgRows}" 
    imgWidth="${imgInfo[0]}"
    imgHeight="${imgInfo[1]}"
    game-width="${options.gameWidth}px" 
    max-attempts="${options.maxAttempts}" 
    insertMode="${options.insertMode}" 
    background-color="${options.background}"
    radius="${options.radius}"
    gap="${options.gap}"
    ${rotatable}>zzz
</puzzle-component>`;

   html += `<br><center><div id=${this.getId('jauge')} style='background:transparent'>gauge</div></center>`;
   //alert(html)
//alert (`===> ${quiz.urlMain}/plugins/puzzle/PuzzleComponent.js`);    
//alert (`===> ${quiz.urlMain}`);    
    return html;
}

/* *************************************
*
* ******** */
prepareData(){

    var currentQuestion = this.question;
    var options = currentQuestion.options;

    options.nbAttempts = 0;
    
//alert(`preview = ${options.preview}`);
    this.initMinMaxQQ (0);
    if(!options.preview) {options.preview  = 0;}
    if(!options.rotation) {options.rotation  = 0;}
} 

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    super.onFinalyse ();
    this.computeScore=true;
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    var tellFrom = `puzzle.onFinalyse[${this.gameId}]`; // pour tracer et debuger
//         updateButton('quiz_btn_nextSlide', 0, null, tellFrom);
    quiz_show_mask(true, 0.10, true);

        //document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //updateButton('quiz_btn_nextSlide', 0);

// On s'assure que le DOM est bien chargé
    this.puzzleIsLoad = false;

    const jeuElement = document.getElementById(this.gameId );
    if(options.preview > 0){
       //jeuElement.resetToOriginalOrder();
       jeuElement.preview(options.preview*1000);
    }else{
        updateButton('quiz_btn_nextSlide', 1, null, tellFrom);        
    }

    if(!this.obGauje){
        var divJauge = document.getElementById(this.getId('jauge'));
        //divJauge.innerHTML = "<span>xxxxxxxxxxxxx</span>";
        this.obGauje = new QuizMaker.Gauge(divJauge, 'horizontal', 1, 400);
        this.obGauje.setColor('green','silver');
    }
    this.obGauje.start(options.preview,{'opacity':0});

   //let obSlide = document.getElementById(this.getId()).parentNode;
   let obSlide = document.getElementById(this.getId('main'));
   obSlide.style.overflow = "hidden";   
 
    //this.puzzleIsLoad = true;
/* ******************************************************* */
const handleGameSuccess = (e) => {
    if (e.detail.isSolved && this.puzzleIsLoad == true) {
        console.log(" Gagné !!!!!!!!!!!!!!!!!");
        //alert(" Gagné !!!!!!!!!!!!!!!!!!");
        this.show_avertissement_WL(true);
        
        // Suppression de l'écouteur si on ne veut qu'une seule victoire par exemple
        jeuElement.removeEventListener('game-success', handleGameSuccess);
    }
};
// Définition de la fonction de callback pour les tentatives max
const handleMaxAttempts = (e) => {
    console.log(" Trop de mouvements ! Tentatives max atteintes :", e.detail.attempts);
    //alert("Nombre maximum de déplacements atteint ! Perdu..............");
    this.show_avertissement_WL(false);
    
    // Suppression de l'écouteur en cas de défaite
    jeuElement.removeEventListener('game-maxattempts', handleMaxAttempts);
};
/* ******************************************************* */
jeuElement.addEventListener('game-success', handleGameSuccess);
jeuElement.addEventListener('game-maxattempts', handleMaxAttempts);

document.addEventListener('game-init', (e) => {
    if (e.target.id === this.gameId ) {
        //console.log(" Trop de mouvements ! Tentatives max atteintes :", e.detail.attempts);
        //alert("Nombre maximum de déplacements atteint ! Perdu..............");
        this.puzzleIsLoad = true;
        quiz_show_mask(false);
    }
});
        
}

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    this.gameId = this.getId('puzzle');
    
    let imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<div><center><img id="${this.getId(0)}" src="${imgUrl}" width="300px" ></center></div>`;   
    return img

 }

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
var score = 0;
    let  currentQuestion = this.question;
    const jeuElement = document.getElementById(this.gameId );
    if(jeuElement.checkIfSolved() && this.puzzleIsLoad){
        score = this.scoreMaxiQQ;
    }else{
    }

   return score;
}

/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.onFinalyse();
}

/* *************************************
*
* ******** */
showGoodAnswers ()
  {
    const jeuElement = document.getElementById(this.gameId );
    jeuElement.resetToOriginalOrder();
    return true;
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
  const jeuElement = document.getElementById(this.gameId );
  jeuElement.initGame();
}
  
} // ----- fin de la class ------

//////////////////////////////////////////////////

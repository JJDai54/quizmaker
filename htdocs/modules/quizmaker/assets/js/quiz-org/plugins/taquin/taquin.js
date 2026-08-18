function getPlugin_taquin(question, slideNumber){

    return new taquin(question, slideNumber, 'taquin')
    //return new taquin(question, slideNumber);
}  

 /*******************************************************************
  *                     taquin
  * *****************************************************************/
/*
mettre dans le plugin taquin les methodes communes et faire hériter les deux autre sur taquin
*/
class taquin extends Plugin_Prototype{
name = "taquin";

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
    this.gameId = this.getId('taquin');
    
    let imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    let imgInfo = currentQuestion.answers[0].buffer.split('_');
    //var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.taquinIsLoad = false;
 
    //var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${this.gameId}' data-slideNumber="${this.slideNumber}" ><hr><b>Puzzle</b><hr></div></center>`;
//alert('rotation : ' + options.rotation)
let rotatable = (options.rotation*1 === 1) ? 'rotatable' : '';
if(options.background=='black' || options.background=='#000000') {options.background = 'transparent';}
let html = `<taquin-component 
    id="${this.gameId}"
    image="${imgUrl}" 
    cols="${options.imgCols}" 
    rows="${options.imgRows}" 
    imgWidth="${imgInfo[0]}"
    imgHeight="${imgInfo[1]}"
    game-width="${options.gameWidth}px" 
    max-attempts="${options.maxAttempts}" 
    background-color="${options.background}"
    radius="${options.radius}"
    gap="${options.gap}">component
</taquin-component>`

   html += `<br><center><div id=${this.getId('jauge')} style='background:transparent'>gauge</div></center>`;
   
//alert(html)
//alert (`===> ${quiz.urlMain}/plugins/taquin/PuzzleComponent.js`);    
//alert (`===> ${quiz.urlMain}`);    
    return html;
}

/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
    var options = currentQuestion.options;

    options.nbAttempts = 0;
    
//alert(`preview = ${options.preview}`);
    this.initMinMaxQQ (0);
    if(!options.preview) {options.preview  = 0;}
} 

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
/* *************************************
*
* ******** */
onFinalyse (){
    super.onFinalyse ();
    this.computeScore=true;
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    var tellFrom = `taquin.onFinalyse[${this.gameId}]`; // pour tracer et debuger
//         updateButton('quiz_btn_nextSlide', 0, null, tellFrom);
    quiz_show_mask(true, 0.10, true);

        //document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //updateButton('quiz_btn_nextSlide', 0);

// On s'assure que le DOM est bien chargé
    this.taquinIsLoad = false;

    const jeuElement = document.getElementById(this.gameId );
    if(options.preview > 0){
       //jeuElement.resetToOriginalOrder();
       jeuElement.preview(options.preview*1000);
    }else{
        updateButton('quiz_btn_nextSlide', 1, null, tellFrom);        
    }
    
   //let obSlide = document.getElementById(this.getId()).parentNode;
   //let obSlide = document.getElementById(this.getId('main'));
   //obSlide.style.overflow = "hidden";   
 
    if(!this.obGauje){
        var divJauge = document.getElementById(this.getId('jauge'));
        //divJauge.innerHTML = "<span>xxxxxxxxxxxxx</span>";
        this.obGauje = new QuizMaker.Gauge(divJauge, 'horizontal', 1, 400);
        this.obGauje.setColor('green','silver');
    }
    this.obGauje.start(options.preview,{'opacity':0});
    
    //this.taquinIsLoad = true;
/* ******************************************************* */
const handleGameSuccess = (e) => {
    if (e.detail.isSolved && this.taquinIsLoad == true) {
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
        this.taquinIsLoad = true;
        quiz_show_mask(false);
    }
});
    
}

//---------------------------------------------------
// computeScoresMinMaxByProposition(){
//     //il n'y a pas de points par proposition, il faut trouver un ordre
//     //on suppose que chaque items à sa place compte pour 1 points 
//     //mais ce sera le nombre de points de la questions qui primera
//     //this.scoreMaxiBP = this.question.answers.length;
//     this.scoreMaxiBP = this.question.points;
// }

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
var score = 0;
    let  currentQuestion = this.question;
    const jeuElement = document.getElementById(this.gameId );
    if(jeuElement.checkIfSolved() && this.taquinIsLoad){
        score = this.scoreMaxiQQ;
    }else{
    }

   return score;
}

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    let imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<div><center><img id="${this.getId('img')}" src="${imgUrl}" width="300px" ></center></div>`;   
    return img

 }


/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.obGauje = null;
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

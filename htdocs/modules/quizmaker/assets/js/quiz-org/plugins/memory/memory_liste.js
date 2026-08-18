
 /*******************************************************************
  *                     memory_liste
  * *****************************************************************/
/*
mettre dans le plugin memory les methodes communes et faire hériter les deux autre sur memory
*/
class memory_liste extends Plugin_Prototype{
name = "memory_liste";
memoryIsLoad = false;
totalMinoration = 0;

/* *************************************
*
alert(this.name + " herité de memory_parent")
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
    this.gameId = this.getId('memory');
    
//    var imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
//alert('liste des images : \n' + this.data.imgArr.join(',\n'))
let html =`<memory-component 
    id="${this.gameId}"
    mode='list';
    image="${this.data.imgArr.join(',')}" 
    cols="${options.imgCols}" 
    rows="${options.imgRows}" 
    target-cols="${options.gameCols}" 
    repeat="${options.doublons}" 
    game-width="${options.gameWidth}px"  
    gap="${options.gap}"  
    radius="${options.radius}"
    background-color="${options.background}"
    background-mask="${options.mask}"
    tempo="${options.tempo}"
    max-attempts="${options.maxAttempts}" >
</memory-component>`;

//alert(html);

   html += `<br><center><div id=${this.getId('jauge')} style='background:transparent'>gauge</div></center>`;

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
    let imgArr = [];
    //alert(`answers nb = ${currentQuestion.answers.length}`)
    for(let k = 0; k < currentQuestion.answers.length; k++){
        let ans = currentQuestion.answers[k];
        //alert('image : ' + ans.image1)
        imgArr.push(`${quiz.url}/${quiz.folderJS}/images/${ans.image1}`);
    }
    this.data.imgArr = imgArr;
//alert(`preview = ${options.preview}`);
    this.initMinMaxQQ (2);
    if(!options.preview) {options.preview  = 0;}
    
} 


//---------------------------------------------------
onEnter() {
    super.onEnter();
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    this.scoreMaxiBP = 0;
    
    if(currentQuestion.points > 0){
        this.scoreMaxiBP = currentQuestion.points;
    }else{
        for(let k = 0; k < currentQuestion.answers.length; k++){
            let ans = currentQuestion.answers[k];
            this.scoreMaxiBP += currentQuestion.answers[0].points;
        }
    }
    this.scoreMiniBP = 0;
    console.log(`${this.slideNumber} : computeScoresMinMaxByProposition = ${this.scoreMaxiBP}`)
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
    this.memoryIsLoad = false;

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
//    let obSlide = document.getElementById(this.getId('main'));
//    obSlide.style.overflow = "hidden";   
 
    //tthis.memoryIsLoad == true = true;
/* ******************************************************* */
const handleGameSuccess = (e) => {
    //if (e.detail.isSolved && this.memoryIsLoad == true) {
    if (e.detail.isSolved) {
        console.log(" Gagné !!!!!!!!!!!!!!!!!");
        //alert(" Gagné !!!!!!!!!!!!!!!!!!");
        this.show_avertissement_WL(true);
        
        // Suppression de l'écouteur si on ne veut qu'une seule victoire par exemple
        jeuElement.removeEventListener('game-success', handleGameSuccess);
        //computeAllScores();
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
        this.memoryIsLoad = true;
        quiz_show_mask(false);
        //    this.computeScore=true;
    }
});

document.addEventListener('game-isnogood', (e) => {
    this.obGauje.start(options.tempo, {opacity:0.01});
});


}


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
var score = 0;

    let  currentQuestion = this.question;
    const jeuElement = document.getElementById(this.gameId);
    if(!jeuElement) return 888;
    if(jeuElement.checkIfSolved() ){
        score = this.scoreMaxiQQ;
    }else{
        score = 0;
    }
    //alert(`getScoreByProposition : score = ` + this.scoreMaxiQQ)

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
    //jeuElement.initGame();
    //jeuElement.resetToOriginalOrder();    
    jeuElement.showGame();
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


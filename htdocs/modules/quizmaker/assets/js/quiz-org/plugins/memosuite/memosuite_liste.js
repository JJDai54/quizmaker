
 /*******************************************************************
  *                     memosuite_liste
  * *****************************************************************/

class memosuite_liste extends Plugin_Prototype{
name = "";
memoryIsLoad = false;
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
    this.gameId = this.getId('memorysuite');
    

    let imgInfo = currentQuestion.answers[0].buffer.split('_');
  
let html =`<memosuite-component 
    id="${this.gameId}"
    mode='list';
    image="${this.data.imgArr.join(',')}" 
    cols="${options.imgCols}" 
    rows="${options.imgRows}" 
    sequence-length="${options.sequenceLength}"
    retry-mode="${options.retryMode}"
    sequence-width="${options.sequenceWidth}px"
    sequence-height="${options.sequenceHeight}px"
    grid-cols="${options.gridCols}"
    grid-width="${options.gridWidth}px"
    grid-background="${options.GridBackground}"
    gap="${options.gap}px"
    inactive-opacity="0.2"
    radius="${options.radius}px"
    tempo="${options.sequenceTempo*1000}"
    max-attempts="${options.maxAttempts}"
    msg-ready-btn="${options.msg_ready_btn}"
    msg-next-slide-btn="${options.msg_next_slide_btn}" >
</memosuite-component>`;


/*
alert(options.msg_next_slide_btn)
alert(html)
<memosuite-component 


    game-width="${options.gameWidth}px"  




</memosuite-component>




let html =`<memosuite-component 
    id="${this.gameId}"
    mode='split';
    image="${imgUrl}" 
    retry-sequence="${options.retrySequence}" 
    inactive-opacity="0.2"
    target-cols="${options.gameCols}" 
    repeat="${options.doublons}" 
    game-width="${options.gameWidth}px"  
    gap="${options.gap}"  
    radius="${options.radius}"
    grid-background="${options.background}"
    tempo="${options.tempo}"
    max-attempts="${options.maxAttempts}" >memosuite
</memosuite-component>`;

   <memosuite-component 
        gap="8px"
        radius="6px"
        tempo="1000">
    </memosuite-component>
*/ 



//alert(html);

   //html += `<br><center><div id=${this.getId('jauge')} style='background:transparent'>gauge</div></center>`;

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
    
    if(currentQuestion.points > 0){
        this.scoreMaxiBP = currentQuestion.points;
    }else{
        this.scoreMaxiBP = currentQuestion.answers[0].points ;
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
    
    var tellFrom = `memorysuite.onFinalyse[${this.gameId}]`; // pour tracer et debuger
//         updateButton('quiz_btn_nextSlide', 0, null, tellFrom);
//    quiz_show_mask(true, 0.10, true);

        //document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //updateButton('quiz_btn_nextSlide', 0);

// On s'assure que le DOM est bien chargé
    this.memoryIsLoad = false;

    const jeuElement = document.getElementById(this.gameId );
    jeuElement.shakeStartBtn()    
    quiz_show_avertissement (options.msg_ready, options.msg_duree, options.msg_background, false);    

/*
    if(options.preview > 0){
       //jeuElement.resetToOriginalOrder();
       //jeuElement.preview(options.preview*1000);
    }else{
        updateButton('quiz_btn_nextSlide', 1, null, tellFrom);        
    }
*/    

//     if(!this.obGauje){
//         var divJauge = document.getElementById(this.getId('jauge'));
//         //divJauge.innerHTML = "<span>xxxxxxxxxxxxx</span>";
//         this.obGauje = new QuizMaker.Gauge(divJauge, 'horizontal', 1, 400);
//         this.obGauje.setColor('green','silver');
//     }
//    this.obGauje.start(options.preview,{'opacity':0});

    
   //let obSlide = document.getElementById(this.getId()).parentNode;
//    let obSlide = document.getElementById(this.getId('main'));
//    obSlide.style.overflow = "hidden";   
 
    //tthis.memoryIsLoad == true = true;
/* ******************************************************* */

        const gameElement = document.getElementById(this.gameId);
//alert('this.gameId = ' + gameElement.id)
jeuElement.addEventListener('game-message', (e) => {
    const { type, message } = e.detail;
    let messageAppli = '';
    
    
     
    switch (type) {
        case 'ready':
            
            // Code pour afficher le message de préparation (ex: initialiser le bandeau ou le message flottant)
            //messageAppli =  "Cliquez sur le bouton pour générer et mémoriser la séquence !";
            //this.show_avertissement_WL(true);
            //messageAppli = options.msg_ready; 
            //quizDivChronos.restart(currentQuestion.timer);
            this.reStartChronos();
            return;
            break;
            
        case 'player-turn':
            // Code lorsque c'est au tour du joueur
            //let sequenceLength = '###' // valeur a récuperer dans l'appli
            //messageAppli =  `À vous de jouer ! Retrouvez les ${sequenceLength} images dans l'ordre.`;
            messageAppli = options.msg_player_turn.replace('{sequenceLength}', options.sequenceLength); 

            break;
            
        case 'game-failure':
            // Code en cas d'erreur dans la séquence // "Erreur dans la séquence ! Nouvelle tentative...";
            messageAppli = options.msg_game_failure 
            break;
            
        case 'game-success':
            // Code en cas de réussite de la séquence
            //messageAppli =  "Bravo ! Séquence validée avec succès.";
            this.show_avertissement_WL(true);
            return;
            break;
            
        case 'game-over':
            // Code en cas de depassement des tentatives en echec
            //messageAppli =  "Nombre maximal de tentatives atteint. Partie terminée !";
            this.show_avertissement_WL(false);
            return;
            break;
            
        default:
            messageAppli =  "message par defaut, ne devrait jamais arrivé";
            break;
    }
    
    if(!messageAppli) {messageAppli = message;}
    
    quiz_show_avertissement (messageAppli, options.msg_duree, options.msg_background, false);       

    
//     if(messageAppli){
//         console.log("Message de l'application :\n", messageAppli);
//         //alert("Message de l'application :\n" + messageAppli);
//     }else{
//         console.log("Message :", message);
//         alert(message);
//     }
});

}

/*
jeuElement.addEventListener('game-message', (e) => {
    const { type, message } = e.detail;
    let messageAppli = '';
    
    
}

*/
/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
var score = 0;

    let  currentQuestion = this.question;
    const jeuElement = document.getElementById(this.gameId);
    if(!jeuElement) return 0;
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
  jeuElement.initDOM();
}
  

 
} // ----- fin de la class ------

//////////////////////////////////////////////////


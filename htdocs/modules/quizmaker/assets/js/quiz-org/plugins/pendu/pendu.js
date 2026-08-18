


/*******************************************************************
*                     pendu
* *****************************************************************/
function getPlugin_pendu(question, slideNumber){
    return new pendu(question, slideNumber);
}

 /*******************************************************************
  *                     pendu
  * *****************************************************************/
class pendu extends Plugin_Prototype{ 
name = 'pendu';
nbLives = 8  // la 0 est pour le de but du jeu et la 9eme est gardé pour la victoire reste 7 vies de 1 à 8
  
/* *************************************
*
* ******** */
buildSlide (bShuffle = true){
    this.boolDog = true;
    return this.getInnerHTML(bShuffle);
    
 }


/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var prompt = getKeybordImg(options.prompt, options.wordSelected, options.promptHeight, '_', ' -');
    var livesStr =  "❤️ " . repeat(this.nbLives);
    var file = parseFileName(options.penduImg);
    //var penduUrl = `${quiz.url}/${quiz.folderJS}/images/${file.shortName}/pendu_${options.progression}.png`;
    var penduUrl = `${currentQuestion.urlPlugin}/img/${file.shortName}/pendu_${options.progression}.png`;
    var img = `<img id="${this.getId('pendu')}" src="${penduUrl}" style="height:${options.penduImgHeight}px">`;

    //-----------------------------------------------------------
    
    
    
 
    var html = this.getDisposition(options.disposition)
              .replace('{livesStr}', livesStr)
              .replace('{img}', img)
              .replace('{prompt}', prompt);
    
 http://127.0.0.16/uploads/quizmaker/quiz-js/Plugin_Pendu/images/cartoon/pendu_0.png   
    

    return html;
}

/* *************************************
*
* ******** */
prepareData(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;

    //options.wordSelected = sanityseAccents(currentQuestion.answers[getRandom(currentQuestion.answers.length-1)].proposition.toUpperCase());
    options.wordSelected = sanityseAccents(currentQuestion.answers[getRandom(currentQuestion.answers.length-1)].proposition, 1);

    options.progression = 0;
    //options.viesRestantes = this.nbLives-1;
    //alert(`mot = ${options.wordSelected }`)
    //this.initMinMaxQQ (0);
    this.scoreMaxiBP = currentQuestion.points*1;   


} 

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    super.onFinalyse ();
}

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var score = 0;
   
    if(this.pendu_isOk()){
        score = currentQuestion.points;
    }

   return score;

  }


/* *************************************
*
* ******** */
pendu_isOk(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var nbFound = 0;
    

    var promptArr = document.getElementById(this.getId('divPrompt')).querySelectorAll('img');
    
    promptArr.forEach((objImg) => {

            if (objImg.getAttribute('status')*1 == 2){
                nbFound++;
            }

    });
//alert(`pendu_isOk : ${nbFound} / ${promptArr.length}`);        
console.log(`pendu_isOk : ${nbFound} / ${promptArr.length}`);        
    return (promptArr.length == nbFound);
}

/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;

    options.wordSelected = sanityseAccents(currentQuestion.answers[getRandom(currentQuestion.answers.length-1)].proposition, 1);
    options.progression = 0;
    //options.viesRestantes = nbLives-1;

    super.reloadQuestion(bShuffle);
    this.onFinalyse();
//options.nbAttempts = 0;   
}

/* ************************************
*
* **** */
endOfGame(div = null){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
 //alert(`endOfGame : ${this.nbLives+1} - ${}` )  
    if(this.pendu_isOk()){
        // affiche la dernier images celle de la victoire
        this.setPenduImg(this.nbLives + 1); 
        this.show_avertissement_WL(true);
    }else if (options.progression >= this.nbLives){
        //le nombre d'essai est dépassé
        this.show_avertissement_WL(false);
    }else{
        //on ne fait rien le jeu continue, il reste qielques vies
        //alert('on continue');
    }
}

/* *************************************
*
* ******** */
setPenduImg(numEtape = null){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    if(numEtape){options.progression = numEtape;}
    var objPendu = document.getElementById(this.getId('pendu'));
    var h = objPendu.src.lastIndexOf('/');
    var i = objPendu.src.lastIndexOf('.');
    var newSrc = objPendu.src.substring(0, h+1) + 'pendu_' + options.progression + objPendu.src.substring(i);
    objPendu.src = newSrc;
    
    var viesRestantes = this.nbLives - options.progression;
    if(viesRestantes > 0){
        document.getElementById(this.getId('divLives')).innerText = "❤️ ".repeat(viesRestantes);
    }else{

    }

    return true;
}
/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "Pendu"
 }

/* *************************************
*    
* ******** */
showGoodAnswers ()
  {
    let  currentQuestion = this.question;
    var options = currentQuestion.options;

        var promptArr = document.getElementById(this.getId('divPrompt')).querySelectorAll('img');
        promptArr.forEach((objImg) => {

                var h = objImg.src.lastIndexOf('/');
                var newSrc = objImg.src.substring(0, h+1) + objImg.getAttribute('file');
                objImg.src = newSrc;
                objImg.setAttribute('status', '2');

        });
    this.setPenduImg(9);
    
    //var prompt = getKeybordImg(options.prompt, options.wordSelected, options.promptHeight, null);
    //document.getElementById(this.getId('divPrompt')).innerHTML = prompt;
    //computeAllScoreEvent();
    
    
    return true;
  
  } 


/* ************************************
*
* **** */
showBadAnswers()
{
    let  currentQuestion = this.question;
    var options = currentQuestion.options;

        var promptArr = document.getElementById(this.getId('divPrompt')).querySelectorAll('img');
        promptArr.forEach((objImg) => {
            if(getRandom(1) == 1){
                var h = objImg.src.lastIndexOf('/');
                var newSrc = objImg.src.substring(0, h+1) + objImg.getAttribute('file');
                objImg.src = newSrc;
                objImg.setAttribute('status', '2');
            }

        });
    this.setPenduImg(this.nbLives);
    options.progression = this.nbLives;
    
    //var prompt = getKeybordImg(options.prompt, options.wordSelected, options.promptHeight, null);
    //document.getElementById(this.getId('divPrompt')).innerHTML = prompt;
    //computeAllScoreEvent();
    
    
    return true;
}
  
/* ***************************************
*
* *** */

getDisposition(disposition, directive = null){
    var currentQuestion = this.question;
    var options = this.question.options;
    var tpl = '';
//alert (disposition);    

    switch(disposition){
        default:
        case 'disposition-00':
            var keyBoard = getKeybordImg(options.keyboard, 'ABCDEFGHIJKLM|NOPQRSTUVWXYZ-', options.keyboardHeight, null, false, `pendu_onclick(event, ${this.slideNumber})`);

            tpl = `<div id="${this.getId('divLives')}" class="pendu_lives">{livesStr}</div>`
                + `<div id="${this.getId('divPendu')}">{img}</div>`
                + `<div id="${this.getId('divPrompt')}">{prompt}</div>`
                + `<div id="${this.getId('divKeybord')}">${keyBoard}</div><br>`;
            break;
    
        case 'disposition-01':
            var keyBoard = getKeybordImg(options.keyboard, 'ABCDEFG|HIJKLMN|OPQRSTU|VWXYZ-', options.keyboardHeight, null, false, `pendu_onclick(event, ${this.slideNumber})`);
            //var keyBoard = getKeybordImg(options.keyboard, 'ABCDEFGHI|JKLMNOPQR|STUVWXYZ-', options.keyboardHeight, null, false, `pendu_onclick(event, ${this.slideNumber})`);
            tpl = `<center><table><tr>`
                + `<td width='50%'>`
                + `<div id="${this.getId('divLives')}" class="pendu_lives">{livesStr}</div>`
                + `<div id="${this.getId('divPendu')}">{img}</div>`
                + `</td>`
                + `<td width='50%'>`
                + `<div id="${this.getId('divKeybord')}">${keyBoard}</div>`
                + `</td>`
                + `</tr>`
                + `<tr><td  width='100%' colspan='2'>`
                + `<div id="${this.getId('divPrompt')}">{prompt}</div>`
                + `</td></tr>`
                + `</table></center>`;
            break;
    }
    
    return tpl;
}
  

} // *************** fin de la class ********************


/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function pendu_event_gotoNextSlide(ev, slideNumber){
    selectImages_event_gotoNextSlide(ev, slideNumber);
}

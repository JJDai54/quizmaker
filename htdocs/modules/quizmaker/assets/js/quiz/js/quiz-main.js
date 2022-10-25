// alert ("ok : " + myQuestions.length);
// alert ("ok : " + myQuestions[0].question);


// myQuestions.forEach((currentQuestion, questionNumber) => {
//     alert ("ok : " + currentQuestion.question);
// });

const quiz_config = {
    name : 'Quizmaker',
    version : "4.01",
    date_creation : "25-01-2019",
    date_release : "12-09-2021",
    author : "J°J°D",
    email : "jjdelalandre@orange.fr",
}
const quiz_const = {
    formQuestion: 0,
    formIntro   : 1,
    formEncart  : 2,
    formResult  : 3,
    regexAllLetters : /\{[\w+\0123456789 àéèêëîïôöûüù]*\}/gi,
    regexAllLettersPP : /\{[\w+\0123456789 àéèêëîïôöûüù,\;\-\?\!\.\_\=\/]*\}/gi //PP pour plus ponctuation
}

var quizard = [];
var currentSlide = 0;
 
(function theQuiz(){
  // Functions

function togodo() {
    alert ("togodo");
    return isInputOk();
}
//alert ("01 : " + myQuestions.length);

/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function buildQuiz (){
//    alert ("02 : " + myQuestions);
filtrerQuestions(); //pour tester chaque type de question une par une

  const buttons = `<button id="horloge"    class="quizHorloge ${quiz.theme}-itemButton">00:00</button>
                   <button id="previous"   class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnPrevious}</button>
                   <button id="next"       class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnNext}</button>
                   <button id="submitAnswers" class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnSubmit}</button>
                   <button id="reload"     class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnReload}</button>
                   <button id="show-good-answers" class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnAntiseche}</button>
                   <button id="show-bad-answers" class="quizButton ${quiz.theme}-itemButton" style="transform: rotate(0.5turn);">${quiz_messages.btnAntiseche}</button>`;
//<button id="antiseche2" class="quizButton ${quiz.theme}-itemButton">${quiz_messages.btnAntiseche}</button>`;
    var legend  = getMessage2(quiz.legend);
    var results = quiz_messages.results;
    var htmlSlides = buildSlides();
    var version = `${quiz_config.name} - Version ${quiz_config.version} du ${quiz_config.date_release} - contact : <a href="mailto:${quiz_config.email}?subject=${quiz_config.name}">${quiz_config.author}</a>`;
    
    
    const output = [];
    output.push(`<div id="quiz_box_title" name="quiz_box_title" class="itemRound-top ${quiz.theme}-itemHead">${quiz.name}</div>
                 <div id="quiz_box_all_slides" name="quiz_box_all_slides" class="itemRound-no ${quiz.theme}-itemBody">${htmlSlides}</div>
                 <div id="quiz_box_buttons" name="quiz_box_buttons" class="itemRound-no ${quiz.theme}-itemBody">${buttons}</div>
                 <div id="quiz_progressbar" name="quiz_progressbar" class=" itemRound-no ${quiz.theme}-itemBody" style="padding: 0px 0px 5px 0px;"><center>${getProgressbarHTML()}</center></div>
                 <div id="quiz_box_results" name="quiz_box_results" class="itemRound-no ${quiz.theme}-itemFoot">${results}</div>
                 <div id="quiz_box_foot" name="quiz_box_foot" class="itemRound-bottom ${quiz.theme}-itemHead">${version}</div>`);
/*
                 <div id="quiz_box_legend" name="quiz_box_legend" class=" itemRound-no ${quiz.theme}-itemFoot">${legend}</div>
*/

//    if (quiz.showResultPopup) {
    if (quiz.showReponsesBottom) {
      output.push(`<br>
                   <div id="quiz_box_log" name="quiz_box_log" style="display: block" class="itemRound-all ${quiz.theme}-itemInfo">${quiz_messages.showReponses}
                   <span class="question-reponsesOk">
                   <div id="quiz-reponses-itemRound-bottom" ></div>
                   </span></div>`);
    }

    //---------------------------------------------------------------------
      output.push(`<div id="divDisabledAll" name="divDisabledAll">
            <div id="quiz_popup_main" name="quiz_popup_main" class="itemRound-all ${quiz.theme}-itemBody">
            <div id="quiz_popup_results" name="quiz_popup_results" class="itemRound-all ${quiz.theme}-itemBody">?????</div>
            <center><button id="continue" name="continue" class="quizButton ${quiz.theme}-itemButton" >${quiz_messages.btnContinue}</button></center>
            </div></div>`);
    //---------------------------------------------------------------------
    const quiz_box_main = document.getElementById('quiz_box_main');
    quiz_box_main.innerHTML = output.join('');
  
//       const quizContainer = document.getElementById('quiz-container');
//       quizContainer.innerHTML = output.join('');

/*
var el = document.getElementById('quiz_questions_js');
el.removeChild(); // Removes the div with the 'div-02' id
el.setAttribute('src',"");
//var myQuestions = "zzzzzzzzzzzz";
alert(el.id);
*/

pb_init(quizard.length, 1);
//pb_run();

}
/**************************************************************************
 *   
 * ************************************************************************/
function getProgressbarHTML(){
return `<div id="pb_contenair" name="pb_contenair">
  <div id="pb_text" width="80px">0 / 0</div>
  <div id="pb_base">
      <div id="pb_indicator"></div>
  </div>
</div>`;


}

/**************************************************************************
 *   
 * ************************************************************************/
function filtrerQuestions(){
var newQuestions = [];
var chrono = 0;
//alert ("zz : " + myQuestions.length);
// alert ("zz : " + myQuestions[0].question);

    myQuestions.forEach((currentQuestion, questionNumber) => {
if(currentQuestion){

//alert ("filtrerQuestions : nb quizard = " +  quizard.length + "\n" + currentQuestion.type + " - \n" + currentQuestion.question);
      // debut du type de slide a traiter
      if (typeOk(currentQuestion.type)){
          currentQuestion.questionNumber = chrono;
          
          //evite de le faire individuellement dans chaque classe
          for (var k=0; k < currentQuestion.answers.length; k++){
          currentQuestion.answers[k].proposition = decodeHTMLEntities(currentQuestion.answers[k].proposition);
          }
        //-------------------------------------------------------
        quizard.push(getTplNewClass (currentQuestion, chrono++));
//alert ("filtrerQuestions : nb quizard = " +  quizard.length + "\n" + currentQuestion.type + " - \n" + currentQuestion.question);
        
      }
}
    });
}


/**************************************************************************
 *   GENERATION DE tous les SLIDES
 * ************************************************************************/
  function buildSlides (){
    // variable to store the HTML output
    const output = [];

    // for each question...
    quizard.forEach((clQuestion, questionNumber) => {
//alert ("===> buildSlides : " + clQuestion.question.typeQuestion  + " - " + clQuestion.question.question);
    output.push(buildHtmlSlide (clQuestion));
      });

    // finally combine our output list into one string of HTML and put it on the page
    //quizBoxAllSlides.innerHTML = output.join('');

    return output.join('');

  }
/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function buildHtmlSlide (clQuestion){
//  alert(clQuestion.typeName);
        const answers = [];  
        //-------------------------------------------------------
var questionNumber = clQuestion.question.questionNumber;
        statsTotal.nbQuestions = clQuestion.incremente_question(statsTotal.nbQuestions);

        statsTotal.scoreMaxi += clQuestion.scoreMaxi;
        statsTotal.scoreMini += clQuestion.scoreMini;
        
       var comment1 = '';  
       if (clQuestion.question.comment1) {
         comment1 = getMessage(clQuestion.question.comment1);
         comment1 = comment1.replace("{scoreMaxi}", clQuestion.scoreMaxi).replace("{timer}", clQuestion.question.timer);
         comment1 = `<hr class="quiz-style-two"><span style="color:blue;font-style:oblique;font-size:0.8em;">${comment1}</span>`;
         
//          comment1 = `<div class="quiz_slide_comment1"><hr class="quiz-style-two">
//                     ${comment1}
//                 </div>`;
       }

//        var comment2 =  getMessage(clQuestion.question.comment2);
//        comment2 = comment2.replace("{points}", clQuestion.scoreMaxi);
//        comment2 = comment2.replace("{timer}", clQuestion.timer);
       
       var divPoints = "";
       if(clQuestion.question.isQuestion == 1){
         var divPoints = quiz_messages.forPoints.replace("{pointsMin}", clQuestion.scoreMini).replace("{pointsMax}", clQuestion.scoreMaxi);
         if (quiz.useTimer == 1){
              var divChrono =  `<label id="question${questionNumber}-slideTimer" class="quiz-timer">${clQuestion.question.timer}</label>`;
              divChrono  = quiz_messages.forChrono.replace("{timer}", divChrono);
              divPoints += " " + divChrono;
         }
         divPoints += "<br>";

       }else if(clQuestion.question.timer > 0 && quiz.useTimer == 1){
        //c'est une page info
              var divChrono =  `<label id="question${questionNumber}-slideTimer" class="quiz-timer">${clQuestion.question.timer}</label>`;
              divChrono  = quiz_messages.readerTimer.replace("{timer}", divChrono);
              divPoints += " " + divChrono + "<br>";
       }
       
       //JJDai - type
       var sType = (quiz.showTypeQuestion) ? `<br><span style="color:white;">(${clQuestion.question.typeQuestion}/${clQuestion.question.typeForm} - questId = ${clQuestion.question.quizId}/${clQuestion.question.questId}) - ${clQuestion.question.timestamp}</span>`: '';
       //var title = `${questionNumber+1} : ${divPoints}${clQuestion.question.question}${sType}`;
       var title = `${divPoints}<div  class="quiz-shadowbox-question" disabled>${questionNumber+1} : ${clQuestion.question.question}${comment1}</div>${sType}`;



        // add this question and its answers to the output    
        var output = [];
                                                         
        output.push(
          `<div id="slide[${questionNumber}]" name="slide${questionNumber}" class="quiz_slide_main" >
            <div class="quiz_slide_question_main  itemRound-no ${quiz.theme}-itemInfo">
                <div class="quiz_slide_question">${title}</div>
                
            </div>
            
            <div class="quiz_slide_body itemRound-no ${quiz.theme}-itemBody">
                <div style='margin:auto;width:90%'>${clQuestion.build()}</div>
            </div>
          </div>`
        );       

    return output.join('');
}  



 /************************************************************************
  *   TESTE SI LE USER A REPONDU
  * ***********************************************************************/
  function isInputOk (){
//alert("isInputOk");
    var bolOk = false;

    const answerContainers = quizBoxAllSlides.querySelectorAll('.answers');
    const answerContainer = answerContainers[currentSlide];
    currentQuestion = quizard[currentSlide];

    //-------------------------------------------------------

    bolOk = currentQuestion.isInputOk(answerContainer,currentSlide);
    //-------------------------------------------------------

//alert("isInputOk===> " + currentQuestion.type + " | " + currentSlide +  " | " + bolOk);

       return bolOk;

 }

/************************************************************************
 *  calcul le nombre de reponses, vraies ou fausses,
 *  faite par l'utilisateur pour permettre le passage au slide suivant
 * *********************************************************************/
  function countInputOk (){
//alert("isInputOk");
var bolOk = false;
var answerContainer;
 
//    var answerALLContainers = quizBoxAllSlides.querySelectorAll('.answers');
    var reponses = 0;

    quizars.forEach( (currentQuestion, questionNumber) => {
//        var answerContainer = answerALLContainers[questionNumber];
        answerContainer = currentQuestion.answers;
        
        bolOk = false;
        //-------------------------------------------------------

            bolOk = currentQuestion.isInputOk(answerContainer, currentSlide);
        //-------------------------------------------------------
        if (bolOk) reponses++;
    });

       return reponses;

 }
/**************************************************************************
 *   renvois les réponses OK pour chaque slide, cad celle qui donnent des points.
 * ************************************************************************/

 function getGoodReponses (currentQuestion){
//alert("isInputOk");
      let reponseOk = "";

        //-------------------------------------------------------

            reponseOk = currentQuestion.getGoodReponses();
        //-------------------------------------------------------
        //------------------------------------------
//alert("getGoodReponses : " + reponseOk);

//        var obRep = document.getElementById("quiz_box_log");
//        obRep.style.display = "block";
       showDiv("quiz_box_log", 1);

       return reponseOk;

 }

/**************************************************************************
 *   renvois toutes les réponses pour chaque slide. Utilisé pour le develeoppement,
 *   ces réponses sont affichées en bas du formulaire pour faciliter les tests
 * ************************************************************************/

 function getAllReponses (currentQuestion){
//alert("isInputOk");
      let reponseOk = "";

        //-------------------------------------------------------

             reponseOk = currentQuestion.getAllReponses();
        //-------------------------------------------------------

//alert("getAllReponses : " + reponseOk);

//        var obRep = document.getElementById("quiz_box_log");
//        obRep.style.display = "block";
       showDiv("quiz_box_log", 1);

       return reponseOk;

 }
 /**********************************************************************
  *  CALCUL DU SCORE MAXIMUM POUR CHAQUE QUESTION / SLIDE
  * ********************************************************************/
 function getScoreMinMax (currentQuestion){

      //score = quizard[currentQuestion].getScoreMinMax();
      score = currentQuestion.getScoreMinMax();
      //-------------------------------------------------------
       return score;
 }
 /**********************************************************************
  *  CALCUL DU SCORE MAXIMUM POUR CHAQUE QUESTION / SLIDE
  * ********************************************************************/
 function getAllScoreMinMax (){
    //currentQuestion = myQuestions[numQuestion];
    
    AllScoresMinMax = {min:0, max:0};
   
    quizard.forEach((clQuestion, questionNumber) => {
        var scoreMinMax = quizard.getScoreMinMax();
        AllScoresMinMax.max+= scoreMinMax.max;
        AllScoresMinMax.min+= scoreMinMax.min;
    });


        return AllScoresMinMax;
 }
 /************************************************************************
  *    CALCUL DES RESULTATS
  * ***********************************************************************/
  function getAllScores (){
    // gather answer containers from our quiz
    const answerContainers = quizBoxAllSlides.querySelectorAll('.answers');

    var result = {repondu: 0,
                  score: 0,
                  duree: 0};

    // for each question...
    quizard.forEach((clQuestion, questionNumber) => {
        //result.repondu +=  clQuestion.getScore(answerContainers[questionNumber]);// ((points>0) ? points : 0;
        result.repondu  += (clQuestion.isInputOk( answerContainers[questionNumber]) ? 1 : 0);  
        //result.score  += clQuestion.points*1;  
        result.score  += clQuestion.getScore()*1;  
        //clQuestion.getScore()*1;  
        //result.score  += clQuestion.points*1;
        if (clQuestion.isAntiseche && quiz.minusOnShowGoodAnswers != 0) {
            result.repondu  = 0;  
            result.score  -= quiz.minusOnShowGoodAnswers;  
        }
          
    });
    return result;
  }
//----------------------------------------------------------
  function submitAnswers(){
    currentQuestion = quizard[currentSlide];
    currentQuestion.submitAnswers();
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
//currentQuestion.showGoodAnswers(currentQuestion, quizBoxAllSlides);
//         alert('submitAnswers');
//       }
  }
//----------------------------------------------------------
  function showResults (){
    // gather answer containers from our quiz
    var answerContainers = quizBoxAllSlides.querySelectorAll('.answers');
    //if(currentSlide==0)updateOptions();
    // keep track of user's answers
//     let numCorrect = 0;
//     let numPoints = 0;
//     let points = 0;


    var results = getAllScores();
    var currentQuestion = quizard[currentSlide];
    if(currentQuestion.isQuestion){
        scoreCurrentSlide = "";
    }else{
        var score = currentQuestion.getScore();
        var scoreMax = currentQuestion.scoreMaxi;
        scoreCurrentSlide = quiz_messages.resultThisSlide.replace("{score}",score).replace("{scoreMax}",scoreMax);  
    }
    //alert (scoreCurrentSlide);

statsTotal.score = results.score;
statsTotal.repondu = results.repondu;

    var exp = quiz_messages.resultOnSlide;
    exp = exp.replace("{reponses}", results.repondu);  //countInputOk()    numCorrect
    exp = exp.replace("{questions}", statsTotal.nbQuestions);
    exp = exp.replace("{points}", results.score);
    exp = exp.replace("{totalPoints}", statsTotal.scoreMaxi);
    //exp = exp.replace("{horloge}", horloge);
   // exp = exp.replace("{rnd}", rnd);

    quizBoxResults.innerHTML = scoreCurrentSlide + "<br>\n" + exp;
    //resultsContainer.innerHTML = `resultat(${chrono}) : ${numCorrect} out of ${myQuestions.length} | points = ${numPoints}`;
  }

//----------------------------------------------------------
  function showFinalResults (){
    // gather answer containers from our quiz
    var answerContainers = quizBoxAllSlides.querySelectorAll('.answers');

    var results = getAllScores();
statsTotal.score = results.score;
statsTotal.repondu = results.repondu;
    
  }


/* *********************************
*
* */
  function showGoodAnswers  (evt) {
    currentQuestion = quizard[currentSlide];
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
    currentQuestion.showGoodAnswers(currentQuestion, quizBoxAllSlides);
    
    if (!quizard[currentSlide].isAntiseche) {
        quizard[currentSlide].isAntiseche = true;
    }
    showSlide(currentSlide);
    //// console.log(myQuestions[currentSlide].question);
    //alert("showCurrentSlide");
    return true;
  }
  
/* *********************************
*
* */
  function showBadAnswers  (evt) {
    currentQuestion = quizard[currentSlide];
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
    
    currentQuestion.showBadAnswers(currentQuestion, quizBoxAllSlides);
    
    if (!quizard[currentSlide].isAntiseche) {
        quizard[currentSlide].isAntiseche = true;
    }
    showSlide(currentSlide);
    //// console.log(myQuestions[currentSlide].question);
    //alert("showCurrentSlide");
    return true;
  }

/* *********************************
*
* */
function reloadQuestion() {
    currentQuestion = quizard[currentSlide];

    currentQuestion.reloadQuestion(quizBoxAllSlides);
    showSlide(currentSlide);
    //// console.log(myQuestions[currentSlide].question);
//setTimeout(sleep, 3000);   
    
    currentQuestion.setFocus();
    return true;
}


/* *********************************
*
* */
  function showCurrentSlide  () {
        showSlide(currentSlide);
        //alert("showCurrentSlide");
        return true;
  }
 
/* ***********************************************
*
*   function showSlide (n) {
    var isNewSlide = (currentSlide != n);
    if (isNewSlide) {
    slides[currentSlide].classList.remove('quiz_slide_main_active');
    slides[n].classList.add('quiz_slide_main_active');
    }
    currentSlide = n;
    pb_setValue(n+1);

* */

  function showSlide (n) {
    showSlide_new (n - currentSlide);
    return;
    
   }

/* ***********************************************
*
*   function showSlide (offset)
*   @ offset int : 0=current slide, >=1 next slide, <=1 previous slide
*
* */

  function showSlide_new (offset=0) {
    //affichage du popup des solutions si osset > 0 uniquement
    if (currentSlide > 0 && quiz.showResultPopup && offset>0) event_showResult(currentSlide);

    var newSlide = currentSlide + offset;
    if (newSlide >= slides.length) newSlide = slides.length-1;
    if (newSlide < 0) newSlide = 0;
  
    slides[currentSlide].classList.remove('quiz_slide_main_active');
    slides[newSlide].classList.add('quiz_slide_main_active');

    var isNewSlide = (currentSlide != newSlide);
    currentSlide = newSlide;
    //maj de la barre de progression
    pb_setValue(currentSlide + 1);
    
       if (isNewSlide){
         clearInterval(idSlideTimer);
         statsTotal.slideTimer = 0;
         idSlideTimer=0;
         quizard[currentSlide].setFocus();

       }
    //----------------------------------------------
    // pour faciliter la lecture du code    
    var firstSlide  = (currentSlide === 0);
    var lastSlide   = (currentSlide === (slides.length-1));
    var secondSlide = (currentSlide === 1); //en realité la premiere question normalement
    var isQuestion  = (quizard[currentSlide].isQuestion);    
    //est-que le quizTimer est activé et y-a-il un timer sur le slide;
    if (quizard[currentSlide].question.timer > 0 && idSlideTimer == 0 && quiz.useTimer && !lastSlide){
    //alert("start slide timer : |" + quizard[currentSlide].question.timer + "|");
        statsTotal.slideTimer = quizard[currentSlide].question.timer;
        idSlideTimer = setInterval(updateSlideTimer, 1000);
    }
    //----------------------------------------------
    if (quiz.showReponsesBottom)
        quizReponsesBottom.innerHTML = getAllReponses(quizard[currentSlide]);
      
        var bolOk = isInputOk() || !quizard[currentSlide].isQuestion();
        var allowedGotoNextslide = (bolOk &&  quiz.answerBeforeNext) || !quiz.answerBeforeNext;
    //------------------------------------------
    if(firstSlide){
        // c'est le premier slide - présentation du quiz
        enableButton(previousButton, 0);
        enableButton(nextButton, 1);
        
        if (allowedGotoNextslide){
        enableButton(nextButton, 1);
        }else{
        enableButton(nextButton, 0);
        }
        //enableButton(submitButton, ((quiz.allowedSubmit)?1:0));
        enableButton(submitButton, 0);
        
        
    }else if(lastSlide){
        //c'est le dernier slide
        showFinalResults();
        quizard[currentSlide].reloadQuestion();
        stopTimer();
        
        enableButton(previousButton, ((quiz.allowedPrevious && !quiz.useTimer)?1:0));
        enableButton(nextButton, 0);
        //enableButton(submitButton, 1);
        //alert("allowedSubmit = " + quiz.allowedSubmit);
        enableButton(submitButton, ((quiz.allowedSubmit) ? 1 : 0));
   
        
    }else{
        if(secondSlide){
            //alert("premiser slide");
          //c'est le 1er slide de question - démarage du chrono - le premier slide est le 0
          //au cas ou le bouton précédent est ctivé evite de ralancer le chrono
          if (idQuizTimer == 0 ) startTimer();
        }
        if (!isQuestion){
        //c'est une 'page_info' hors premier et dernier slide
        //c'est une 'page_group hors premier et dernier slide
        quizard[currentSlide].reloadQuestion();
//         if (quizard[currentSlide].typeForm == quiz_const.formResult)
//             stopTimer();
        }
             
        enableButton(previousButton, ((quiz.allowedPrevious && quizard[currentSlide].question.timer == 0 && !quiz.useTimer)?1:0));
        enableButton(nextButton, ((allowedGotoNextslide) ? 1 : 0));
        enableButton(submitButton, 0);
    }
   
   //dans tous les cas - ce sont des action a activer dans l'admin pour le développement 
   enableButton(showGoodAnswersButton, ((quiz.showGoodAnswers) ? 1 : 3));
   enableButton(showBadAnswersButton,  ((quiz.showBadAnswers)  ? 1 : 3));
   enableButton(showReloadAnswersButton,  ((quiz.showReloadAnswers)  ? 1 : 3));

  if (quiz.showResultAllways) showResults();
  if (currentSlide == 1 && quiz.showReponsesBottom)  updateOptions();  

   }

//--------------------------------------------------------------------
  function showNextSlide () {
    if (currentSlide > 0 && quiz.showResultPopup) event_showResult(currentSlide);
    showSlide(currentSlide + 1);
  }

  function showPreviousSlide () {
    showSlide(currentSlide - 1);
  }


  /**************************************************************
   *       TIMER
   * ************************************************************/
  function updateSlideTimer (){
     currentQuestion =  quizard[currentSlide];
     //if (currentQuestion.isQuestion == 0) return false;

     var obSlideTimer = document.getElementById("question" + currentSlide + "-slideTimer");
     //var obSlideTimer = document.getElementById("question" + currentQuestion.questionNumber + "-slideTimer");
     if(!obSlideTimer) alert("obSlideTimer pas trouvé");
     obSlideTimer.innerHTML = statsTotal.slideTimer;

      if (statsTotal.slideTimer == 0){
         clearInterval(idSlideTimer);
         idSlideTimer = 0;
         showNextSlide();
      }
        statsTotal.slideTimer --;
        //alert("slideTimer = " + statsTotal.slideTimer);
  }


  //----------------------------------------------------------------
  function updateQuizTimer (){
        horloge.innerHTML = formatChrono(statsTotal.counter ++);
  }
  function startTimer () {
    idQuizTimer = setInterval(updateQuizTimer, 1000);
  }
  function stopTimer () {
    clearInterval(idQuizTimer);
  }


/*****************************************************************
 *    FUNCTION EVENEMENT
 * ****************************************************************/

  function testClick () {
    a = getRandomInt(100);
    alert("testClick : " + a);
  }

  function eventList_delItem (e) {
    a = getRandomInt(100);
    alert("eventList_delItem : " + a);
  }

 
/* ***********************************************
*
* */
  function eventList_init () {
   //alert("testClick : " + tEvents[0].id + " - "  + tEvents[0].evnt + " - " + tEvents[0].fnc);


    tEvents.forEach(
      (ev, evNumber) => {
      ObEvent = document.getElementById(ev.id);
     // alert(ObEvent);
    //alert("eventList_init : " + ObEvent.id + " : " + ev.id + " - "  + ev.event + " - " + ev.fnc);
      ObEvent.addEventListener(ev.event, ev.fnc);

/*
*/
    })

  }

 
/* ***********************************************
*
* */

function event_showResult(currentSlide) {
     var currentQuestion = quizard[currentSlide];

     var divDisabledAll = document.getElementById("divDisabledAll");
     divDisabledAll.style.visibility = "visible";
     //divDisabledAll.style.display = "block";
    //alert (divDisabledAll.id + " - currentSlide  = " + currentSlide);

     var quizPopupResults = document.getElementById("quiz_popup_results");
     //exp.push();
     scoreMinMax = getScoreMinMax(currentQuestion);
     results = getAllScores();
     if (results.score == scoreMinMax.max){
        msg1 = quiz_messages.resultBravo1;
     }else if(results.score == scoreMinMax.min){
        msg1 = quiz_messages.resultBravo3;
     }else{
        msg1 = quiz_messages.resultBravo2;
     }

     msg2 = quiz_messages.resultScore.replace("{points}", results.score);
     msg2 = msg2.replace("{totalPoints}", scoreMinMax.max);


     exp = [];
     exp.push(`${quiz_messages.resultBravo0}<br>`);
     exp.push(`${msg1}<br>`);
     //exp.push(`Score : ${} / ${}<hr>`);
     exp.push(`${msg2}<hr>`);
     exp.push(getGoodReponses(currentQuestion)); //JJDai
/*
     exp.push(`<hr>`);
     exp.push(getAllReponses(currentQuestion));
*/

     quizPopupResults.innerHTML = exp.join("\n");
    return true;
}

function event_hideResult() {
     var quizPopupResults = document.getElementById("quiz_popup_results");
     quizPopupResults.innerHTML = "";

     var divDisabledAll = document.getElementById("divDisabledAll");
    //alert (divDisabledAll.id);
     divDisabledAll.style.visibility = "hidden";
     //divDisabledAll.style.display = "none";
    return true;
}

  /**************************************************************
   *       FONCTIONS GENERALES
   * ************************************************************/
 function change_theme(){
  var ttheme=["default",
                 "grey1",
                 "blue",
                 "blue2",
                 "green",
                 "green2",
                 "purple",
                 "purple2",
                 "red",
                 "red2",
                 "saumon",
                 "saumon2",
                 "yellow",
                 "yellow2",
                 "braun2",
                 "France",
                 "allBlack"];



    var index = getRandomInt(ttheme.length -1);
    return ttheme[index];

 }


 
/* ***********************************************
*
* */
 function showDiv (id, etat=0){
// console.log(id); //JJDai
       var obRep = document.getElementById(id);
       if(!obRep) alert(`|${id}|`);
       obRep.style.display = (etat == 1) ? "block" : "none";

 }

 
/* ***********************************************
*
* */
function getRandomInt (max) {
  return Math.floor(Math.random() * Math.floor(max));
}


 
/* ***********************************************
*
* */
function shuffleMyquiz () {

    var Intro;
    var Result;
    var newQuestions = [];

    myQuestions.forEach(
      (currentQuestion, questionNumber) => {
        if (currentQuestion.type == "Intro"){
           Intro = currentQuestion;
        }else if (currentQuestion.type == "Result"){
          Result = currentQuestion;
        }else{
          newQuestions.push(currentQuestion);
        }
      });

      if (quiz.shuffleQuestions)
            newQuestions = shuffleArray(newQuestions);

      newQuestions.unshift(Intro);
      newQuestions.push(Result);
      myQuestions = newQuestions;

      return true;
}

 
/* ***********************************************
*
* */
  function enableButton (btn, etat) {
    switch (etat) {
        case 0: //disable
            btn.style.display = 'inline-block';
            btn.style.visibility="visible";
            btn.disabled = 'disabled';
            break;
            
        case 2: // Masquer et inline
            btn.style.visibility="hidden";
            break;
        case 3: // masquer et not inline
            btn.style.visibility="hidden";
            btn.style.display = 'none';
            break;
            
        case 1: // visible et enabled
        default:
            btn.style.visibility="visible";
            btn.style.display = 'inline-block';
            btn.disabled = '';
            break;
            
    }
 }

 
/* ***********************************************
*
* */
  function getMessage (message, message2="", sep=" - "){

       if(message in quiz_messages){
            newMessage = quiz_messages[message];
       }else{
            newMessage = message;
       }
       
       if (message2 != "" && message2  in quiz_messages){
            newMessage += sep +  quiz_messages[message2];
       }else if(message2 != ""){
            newMessage +=  sep +  message2;
       }
       
       return newMessage;
  }

 
/* ***********************************************
*
* */
  function getMessage2 (exp){
      for(key in quiz_messages){
        exp = exp.replaceAll("{" + key + "}", quiz_messages[key]);
      }
      return exp;
  }



 
/* ***********************************************
*
* */
function updateOptions (){
       var ob;

       ob = document.getElementById("quiz-onclick");
       if(!ob)return false;
       quiz.onClickSimple = ob.checked == 1;

       var ob = document.getElementById("quiz-answerBeforeNext");
       quiz.answerBeforeNext = ob.checked == 1;

       ob = document.getElementById("quiz-allowedPrevious");
       quiz.allowedPrevious = ob.checked;

       ob = document.getElementById("quiz-allowedSubmit");
       quiz.allowedSubmit = (ob.checked == 1) ? 1: 0;

       ob = document.getElementById("quiz-showResultAllways");
       quiz.showResultAllways = (ob.checked == 1) ? 1: 0;
       showDiv("results", ob.checked);

       ob = document.getElementById("quiz-showReponses");
       quiz.showReponsesBottom = (ob.checked == 1) ? 1: 0;
       showDiv("quiz_box_log", ob.checked);

       ob = document.getElementById("quiz-useTimer");
       quiz.useTimer = (ob.checked == 1) ? 1: 0;

       ob = document.getElementById("quiz-showLog");
       quiz.showLog = (ob.checked == 1) ? 1: 0;
       showDiv("quiz_box_log", ob.checked);
//alert("updateOptions");
return true;
 }
 
/* ***********************************************
*
* */
 function getHtmlOptions (){
    var tQuizOptions = [];
//updateOptions();

    checked = (quiz.onClickSimple) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-onclick" name="quiz-onclick" value="1" ${checked}>
            onclick : dÃ©fini l'evennement de sÃ©lection dans les listbox: true = click - false = ondblclick
          </label>`
    )


    checked = (quiz.answerBeforeNext) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-answerBeforeNext" name="quiz-answerBeforeNext" value="1" ${checked}>
            answerBeforeNext : Force l utilisateur ÃƒÂ  rÃƒÂ©pondre avant de passer ÃƒÂ  la question suivante
          </label>`
    )

    checked = (quiz.allowedPrevious) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-allowedPrevious" name="quiz-allowedPrevious" value="1" ${checked}>
            allowedPrevious : Permet ÃƒÂ  l'utilisateur de revenir en arriÃƒÂ¨re
          </label>`
    )

    checked = (quiz.allowedSubmit) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-allowedSubmit" name="quiz-allowedSubmit" value="1" ${checked}>
            allowedSubmit : Permet de valider ÃƒÂ  chaque question pour veririfer le rÃƒÂ©sultat
          </label>`
    )

    checked = (quiz.showResultAllways) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-showResultAllways" name="quiz-showResultAllways" value="1" ${checked}>
            showResultAllways : Affiche le rÃƒÂ©sultat en bas de chaque question
          </label>`
    )

    checked = (quiz.showReponsesBottom) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-showReponses" name="quiz-showReponses" value="1" ${checked}>
            showReponses : Affiche les rÃƒÂ©sultats en bas de page pour les tests de developpement
          </label>`
    )


    checked = (quiz.useTimer) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-useTimer" name="quiz-useTimer" value="1" ${checked}>
            useTimer : Active ou dÃƒÂ©sactive le time pour chaque questions - utilisÃƒÂ©e pour le dÃƒÂ©veloppement
          </label>`
    )

    checked = (quiz.showLog) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-showLog" name="quiz-showLog" value="1" ${checked}>
            showLog : affiche les log - utilisé pour le developement
          </label>`
    )


      return tOptions.join('<br>');
 }

/*****************************************************************
 *    INITIALISATION DES VARIABLES
 * ****************************************************************/


const tEvents = [];


  // Variables

  //const helpAllQuiz = document.getElementById('helpAllQuiz');



  //const myQuestions  defitions mises dans un fichiers js annexe


/**************************************************************
 *     GENERATION DU QUIZ
 * ************************************************************/
//    quiz.theme = change_theme(); //changement aleatoire du theme utilisé pour les tests
    shuffleMyquiz();
    buildQuiz();
//eventList_init();

/*****************************************************************
 *    INITIALISATION DES OBJETS APRES CONSTUCTION DU QUIZ
 * ****************************************************************/

  const quizBoxResults = document.getElementById('quiz_box_results');
  const quizBoxAllSlides = document.getElementById('quiz_box_all_slides');

  const continueButton = document.getElementById('continue');
  const submitButton = document.getElementById('submitAnswers');
  const previousButton = document.getElementById("previous");
  const startButton = document.getElementById("startQuiz");
  const nextButton = document.getElementById("next");
  const showGoodAnswersButton = document.getElementById("show-good-answers");
  const showBadAnswersButton = document.getElementById("show-bad-answers");
  const showReloadAnswersButton = document.getElementById("reload");
  
  const resultsContainer = document.getElementById('results');
  const quizReponsesBottom = document.getElementById('quiz-reponses-itemRound-bottom');
  const horloge = document.getElementById("horloge");
  const slides = document.querySelectorAll(".quiz_slide_main");
  //const quizLog = document.getElementById("quiz_box_log");

  continueButton.addEventListener("click", event_hideResult);
  previousButton.addEventListener("click", showPreviousSlide);
  startButton.addEventListener("click", showNextSlide);
  nextButton.addEventListener("click", showNextSlide);
  submitButton.addEventListener('click', submitAnswers);
  showGoodAnswersButton.addEventListener('click', showGoodAnswers);
  showBadAnswersButton.addEventListener('click', showBadAnswers);
  showReloadAnswersButton.addEventListener('click', reloadQuestion);
  
  quizBoxAllSlides.addEventListener("click", showCurrentSlide);
  quizBoxAllSlides.addEventListener("input", showCurrentSlide);
  //quizBoxAllSlides.addEventListener("change", showCurrentSlide);


  // Pagination
  //const quizBoxAllSlides = document.getElementById("quiz_box_all_slides");
  let currentSlide = 0;
  let idSlideTimer = 0;
  let idQuizTimer = 0;
  // Show the first slide

  // Event listeners



  //quizBoxAllSlides.addEventListener("keypress", showCurrentSlide);


//      const togodo = document.getElementById('togodo');
//   togodo.addEventListener("onclick", testClick);






/**********************************************************************
 *     AFFICHAGE DU PREMIER SLIDE ET LANCEMENT DU CHRONO
 * ********************************************************************/
  showSlide(currentSlide);
  //startTimer();
})();


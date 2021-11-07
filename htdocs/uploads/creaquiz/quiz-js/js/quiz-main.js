// alert ("ok : " + myQuestions.length);
// alert ("ok : " + myQuestions[0].question);


// myQuestions.forEach((currentQuestion, questionNumber) => {
//     alert ("ok : " + currentQuestion.question);
// });

var quizard = [];
 
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

  const buttons = `<button id="horloge"   class="quizHorloge ${quiz.colorset}-itemButton"">00:00</button>
                   <button id="previous"  class="quizButton ${quiz.colorset}-itemButton"">${quiz_messages.btnPrevious}</button>
                   <button id="next"      class="quizButton ${quiz.colorset}-itemButton"">${quiz_messages.btnNext}</button>
                   <button id="submit"    class="quizButton ${quiz.colorset}-itemButton"">${quiz_messages.btnSubmit}</button>
                   <button id="reload"    class="quizButton ${quiz.colorset}-itemButton"">${quiz_messages.btnReload}</button>
                   <button id="antiseche" class="quizButton ${quiz.colorset}-itemButton"">${quiz_messages.btnAntiseche}</button>`;

    var legend  = getMessage2(quiz.legend);
    var results = "Resultats";
    var htmlSlides = buildSlides();

    const output = [];
    output.push(`<div id="quiz-title" class="item-round-top ${quiz.colorset}-itemHead">${quiz.name}
                 </div>
                 <div id="quiz-slides" class="item-round-no ${quiz.colorset}-itemBody">${htmlSlides}
                 </div>
                 <div id="quiz-buttons" class="item-round-no ${quiz.colorset}-itemBody">${buttons}
                 </div>
                 <div id="quiz-legend" class=" item-round-no ${quiz.colorset}-itemFoot">${legend}
                 </div>
                 <div id="quiz-results-foot" class=" item-round-bottom ${quiz.colorset}-item-legend">${results}
                 </div>`);


//    if (quiz.showResultPopup) {
    if (quiz.showReponsesBottom) {
      output.push(`<br>
                   <div id="quizReponsesBottomContainer" name="quizReponsesBottomContainer" style="display: block" class="item-round-all ${quiz.colorset}-itemInfo">${quiz_messages.showReponses}
                   <span class="question-reponsesOk">
                   <div id="quiz-reponses-bottom" ></div>
                   </span></div>`);
    }

    //---------------------------------------------------------------------
      output.push(`<div id="divDisabledAll" name="divDisabledAll">
            <div id="divResultPopup" class="item-round-all ${quiz.colorset}-itemBody">
            <div id="divResultPopup2" class="item-round-all ${quiz.colorset}-itemBody">?????</div>
            <center><button id="continue" name="continue" class="quizButton ${quiz.colorset}-itemButton" >${quiz_messages.btnContinue}</button></center>
            </div></div>`);
    //---------------------------------------------------------------------

      const quizContainer = document.getElementById('quiz-container');
      quizContainer.innerHTML = output.join('');

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
// alert ("zz : " + currentQuestion.type + " - " + currentQuestion.question);

      // debut du type de slide a traiter
      if (typeOk(currentQuestion.type)){
          currentQuestion.questionNumber = chrono;
          
          //eviter de la faire individuellement dans chaque classe
          for (var k=0; k < currentQuestion.answers.length; k++){
          currentQuestion.answers[k].proposition = decodeHTMLEntities(currentQuestion.answers[k].proposition);
          }
        //-------------------------------------------------------
        quizard.push(getTplNewClass (currentQuestion, chrono++));
        
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
//alert ("===> buildSlides : " + clQuestion.question.question);
    output.push(getHtmlSlide (clQuestion));
      });

    // finally combine our output list into one string of HTML and put it on the page
    //quizSlides.innerHTML = output.join('');

    return output.join('');

  }
/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function getHtmlSlide (clQuestion){
        const answers = [];  
        //-------------------------------------------------------
var questionNumber = clQuestion.question.questionNumber;
        statsTotal.nbQuestions = clQuestion.incremente_question(statsTotal.nbQuestions);

        statsTotal.scoreMaxi += clQuestion.scoreMaxi;
        statsTotal.scoreMini += clQuestion.scoreMini;
       var comment1 =  getMessage(clQuestion.question.comment1, clQuestion.question.type);

       var comment2 =  getMessage(clQuestion.question.comment2);
       comment2 = comment2.replace("{points}", clQuestion.scoreMaxi);
       comment2 = comment2.replace("{timer}", clQuestion.timer);
       
       var divChrono = "";
       if(clQuestion.question.isQuestion == 1){
         var divChrono = quiz_messages.forPoints.replace("{pointsMin}", clQuestion.scoreMini).replace("{pointsMax}", clQuestion.scoreMaxi);
         if (quiz.useChrono == 1){
              var exp =  `<label id="question${questionNumber}-chrono" class="quiz-timer">${clQuestion.timer}</label>`;
              exp  = quiz_messages.forChrono.replace("{timer}", exp);
              divChrono += " " + exp;
         }
         divChrono += "<br>";

       }
       
       //JJDai - type
       var sType = (quiz.showType) ? `<br><span style="color:white;">(${clQuestion.question.type})(questId = ${clQuestion.question.questId})</span>`: '';
       var title = `${questionNumber+1} : ${divChrono}${clQuestion.question.question}${sType}`;

        // add this question and its answers to the output    
        var output = [];
                                                         
        output.push(
          `<div id="slide[${questionNumber}]" name="slide${questionNumber}" class="slide" >
            <div class="quiz-question  item-round-no ${quiz.colorset}-itemInfo">${title}</div>

            <div class="question item-round-no ${quiz.colorset}-itemInfo">
                <div class="quiz-comment1">${comment1}</div>
                <div class="quiz-comment1" id="question${questionNumber}-timer">${comment2}</div>
                <div class="quiz-comment1"><hr class="quiz-style-two"></div>

            </div>
            <div class="item-round-no ${quiz.colorset}-itemBody">
            <div class="answers  item-round-no ${quiz.colorset}-itemBody"> ${clQuestion.build()} </div></div>
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

    const answerContainers = quizSlides.querySelectorAll('.answers');
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
 
//    var answerALLContainers = quizSlides.querySelectorAll('.answers');
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

//        var obRep = document.getElementById("quizReponsesBottomContainer");
//        obRep.style.display = "block";
       showDiv("quizReponsesBottomContainer", 1);

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

//        var obRep = document.getElementById("quizReponsesBottomContainer");
//        obRep.style.display = "block";
       showDiv("quizReponsesBottomContainer", 1);

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
    const answerContainers = quizSlides.querySelectorAll('.answers');

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
        if (clQuestion.isAntiseche && quiz.bonusAntiSeche != 0) {
            result.repondu  = 0;  
            result.score  = quiz.bonusAntiSeche;  
        }
          
    });
    return result;
  }
//----------------------------------------------------------
  function showResults (){
    // gather answer containers from our quiz
    var answerContainers = quizSlides.querySelectorAll('.answers');
    //if(currentSlide==0)updateOptions();
    // keep track of user's answers
    let numCorrect = 0;
    let numPoints = 0;
    let points = 0;


    var results = getAllScores();


statsTotal.score = results.score;
statsTotal.repondu = results.repondu;

    var exp = quiz_messages.resultOnSlide;
    exp = exp.replace("{reponses}", results.repondu);  //countInputOk()    numCorrect
    exp = exp.replace("{questions}", statsTotal.nbQuestions);
    exp = exp.replace("{points}", results.score);
    exp = exp.replace("{totalPoints}", statsTotal.scoreMaxi);
    //exp = exp.replace("{horloge}", horloge);
   // exp = exp.replace("{rnd}", rnd);

    quizResultsFoot.innerHTML = exp;
    //resultsContainer.innerHTML = `resultat(${chrono}) : ${numCorrect} out of ${myQuestions.length} | points = ${numPoints}`;
  }



/* *********************************
*
* */
  function showAntiSeche  () {
    currentQuestion = quizard[currentSlide];

    currentQuestion.showAntiSeche(currentQuestion, quizSlides);
    if (!quizard[currentSlide].isAntiseche) {
        quizard[currentSlide].isAntiseche = true;
    }
    showSlide(currentSlide);
    //console.log(myQuestions[currentSlide].question);
    //alert("showCurrentSlide");
    return true;
  }
//--------------------------------------------------------------------------------
/* *********************************
*
* */
function reloadQuestion() {
    currentQuestion = quizard[currentSlide];

    currentQuestion.reloadQuestion(quizSlides);
    showSlide(currentSlide);
    //console.log(myQuestions[currentSlide].question);
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
* */

  function showSlide (n) {
    if (n < slides.length && n >= 0) {
    slides[currentSlide].classList.remove('active-slide');
    slides[n].classList.add('active-slide');
    var isNewSlide = (currentSlide != n);
    currentSlide = n;
    }
    //----------------------------------------------
//    if (isNewSlide) eventList_init();



        //alert("showSlide");
    if (quiz.showReponsesBottom)
      quizReponsesBottom.innerHTML = getAllReponses(quizard[currentSlide]);
    //----------------------------------------------
    if (quizard[currentSlide].typeName === "Result"){
        stopTimer();
//        let results = getAllScores();
        //alert("showSlide : " + results.repondu);
//         statsTotal.repondu = results.repondu;
//         statsTotal.score = results.score;
        quizard[currentSlide].reloadQuestion();

    }
    //----------------------------------------------
        if (idChrono == 0 && currentSlide == 1)
            startTimer();
    //----------------------------------------------
       //alert ("idTimer = " + idTimer);
       // clearInterval(idTimer);
       if (isNewSlide){
         clearInterval(idTimer);
         statsTotal.timer = 0;
         idTimer=0;
       }


        if (quizard[currentSlide].timer > 0 && idTimer == 0 && quiz.useChrono){
     //alert("il y a un timer") ;
       //var obTimer = document.getElementById("question" + currentSlide + "-timer");
       statsTotal.timer = quizard[currentSlide].timer;

       idTimer = setInterval(updateChrono, 1000);
       //updateChrono();
       //alert ("idTimer = " + idTimer);
    }
    //----------------------------------------------
    var bolOk = isInputOk();
    var allowedGotoNextslide = (bolOk &&  quiz.answerBeforeNext) || !quiz.answerBeforeNext;

    if(currentSlide === 0){
      enableButton(previousButton, 0);
      enableButton(nextButton, 1);
      if (allowedGotoNextslide){
      enableButton(nextButton, 1);
      }else{
      enableButton(nextButton, 0);
      }
      enableButton(submitButton, ((quiz.allowedSubmit)?1:0));
    }else if(currentSlide === (slides.length-1) ){
//    alert("fin");
      enableButton(previousButton, ((quiz.allowedPrevious)?1:0));
      enableButton(nextButton, 0);
      enableButton(submitButton, 1);
    }else{
      enableButton(previousButton, ((quiz.allowedPrevious)?1:0));

      if (allowedGotoNextslide){
      enableButton(nextButton, 1);
      }else{
      enableButton(nextButton, 0);
      }

      enableButton(submitButton, ((quiz.allowedSubmit)?1:0));
    }
    
 
   enableButton(antisecheButton, ((quiz.showAntiSeche)?1:0));
        

    if (quiz.showResultAllways)
      showResults();
    if (currentSlide == 1 && quiz.showReponsesBottom)  updateOptions();  }
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
  function updateChrono (){
     currentQuestion =  quizar[currentSlide];
     if (currentQuestion.isQuestion == 0) return false;

     var obChrono = document.getElementById("question" + currentSlide + "-chrono");
     obChrono.innerHTML = statsTotal.timer;

      if (statsTotal.timer == 0){
         clearInterval(idTimer);
         idTimer = 0;
         showNextSlide();
      }
      statsTotal.timer --;
  }


  //----------------------------------------------------------------
  function updateTimer (){
        horloge.innerHTML = formatChrono(statsTotal.counter ++);
  }
  function startTimer () {
    idChrono = setInterval(updateTimer, 1000);
  }
  function stopTimer () {
    clearInterval(idChrono);
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

     var divResultPopup = document.getElementById("divDisabledAll");
     divResultPopup.style.visibility = "visible";
     //divResultPopup.style.display = "block";
    //alert (divResultPopup.id + " - currentSlide  = " + currentSlide);

     var divResultPopup2 = document.getElementById("divResultPopup2");
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

     divResultPopup2.innerHTML = exp.join("\n");
    return true;
}

function event_hideResult() {
     var divResultPopup2 = document.getElementById("divResultPopup2");
     divResultPopup2.innerHTML = "";

     var divResultPopup = document.getElementById("divDisabledAll");
    //alert (divResultPopup.id);
     divResultPopup.style.visibility = "hidden";
     //divResultPopup.style.display = "none";
    return true;
}

  /**************************************************************
   *       FONCTIONS GENERALES
   * ************************************************************/
 function change_colorset(){
  var tColorset=["default",
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



    var index = getRandomInt(tColorset.length -1);
    return tColorset[index];

 }


 
/* ***********************************************
*
* */
 function showDiv (id, etat=0){
console.log(id); //JJDai
       var obRep = document.getElementById(id);
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
    if (etat === 0){
        btn.style.visibility="visible";
      //btn.style.display = 'none';
        btn.disabled = 'disabled';
        //btn.style.backgroundColor = quiz.btnColor2;
        //btn.style.cursor = "not-allowed";
    }else if (etat === 2){
        btn.style.visibility="hidden";
    }else{
        btn.style.visibility="visible";
      //btn.style.display = 'inline-block';
        btn.disabled = '';
        //btn.style.backgroundColor = quiz.btnColor1;
        //btn.style.cursor = "pointer";
        //btn.className="quizButton";
    }
 }

 
/* ***********************************************
*
* */
  function getMessage (message, message2=""){

       if(message in quiz_messages){
            newMessage = quiz_messages[message];
       }else if (message2 != "" && message2  in quiz_messages){
            newMessage = quiz_messages[message2];
       }else{
            newMessage = message;
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
       quiz.onclickList = ob.checked == 1;

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
       showDiv("quizReponsesBottomContainer", ob.checked);

       ob = document.getElementById("quiz-useChrono");
       quiz.useChrono = (ob.checked == 1) ? 1: 0;

       ob = document.getElementById("quiz-showLog");
       quiz.showLog = (ob.checked == 1) ? 1: 0;
       showDiv("quiz-log", ob.checked);
//alert("updateOptions");
return true;
 }
 
/* ***********************************************
*
* */
 function getHtmlOptions (){
    var tQuizOptions = [];
//updateOptions();

    checked = (quiz.onclickList) ? "checked": "";
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


    checked = (quiz.useChrono) ? "checked": "";
    tQuizOptions.push(
           `<label>
            <input type="checkbox" id="quiz-useChrono" name="quiz-useChrono" value="1" ${checked}>
            useChrono : Active ou dÃƒÂ©sactive le time pour chaque questions - utilisÃƒÂ©e pour le dÃƒÂ©veloppement
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

  const helpAllQuiz = document.getElementById('helpAllQuiz');



  //const myQuestions  defitions mises dans un fichiers js annexe


/**************************************************************
 *     GENERATION DU QUIZ
 * ************************************************************/
    quiz.colorset = change_colorset();
    shuffleMyquiz();
    buildQuiz();
//eventList_init();

/*****************************************************************
 *    INITIALISATION DES OBJETS APRES CONSTUCTION DU QUIZ
 * ****************************************************************/

  const continueButton = document.getElementById('continue');
  const submitButton = document.getElementById('submit');
  const previousButton = document.getElementById("previous");
  const nextButton = document.getElementById("next");
  const antisecheButton = document.getElementById("antiseche");
  const reloadButton = document.getElementById("reload");
  
  const quizSlides = document.getElementById('quiz-slides');
  const resultsContainer = document.getElementById('results');
  const quizResultsFoot = document.getElementById('quiz-results-foot');
  const quizReponsesBottom = document.getElementById('quiz-reponses-bottom');
  const horloge = document.getElementById("horloge");
  const slides = document.querySelectorAll(".slide");
  //const quizLog = document.getElementById("quiz-log");

  continueButton.addEventListener("click", event_hideResult);
  previousButton.addEventListener("click", showPreviousSlide);
  nextButton.addEventListener("click", showNextSlide);
  submitButton.addEventListener('click', showResults);
  antisecheButton.addEventListener('click', showAntiSeche);
  reloadButton.addEventListener('click', reloadQuestion);
  
  quizSlides.addEventListener("click", showCurrentSlide);
  quizSlides.addEventListener("input", showCurrentSlide);
  //quizSlides.addEventListener("change", showCurrentSlide);


  // Pagination
  //const quizSlides = document.getElementById("quiz-slides");
  let currentSlide = 0;
  let idTimer = 0;
  let idChrono = 0;
  // Show the first slide

  // Event listeners



  //quizSlides.addEventListener("keypress", showCurrentSlide);


//      const togodo = document.getElementById('togodo');
//   togodo.addEventListener("onclick", testClick);






/**********************************************************************
 *     AFFICHAGE DU PREMIER SLIDE ET LANCEMENT DU CHRONO
 * ********************************************************************/
  showSlide(currentSlide);
  //startTimer();
})();


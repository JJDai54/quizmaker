// alert ("ok : " + myQuestions.length);
// alert ("ok : " + myQuestions[0].question);


// myQuestions.forEach((currentQuestion, questionNumber) => {
//     alert ("ok : " + currentQuestion.question);
// });

 // a traiter showResultAllways


// permet de récupérer les variables $_GET ou $_POST utililser pour des messages personnalisés
const quiz_request_keys=['uid','uname','name','email','ip','quiz_id'];
var quiz_rgp = requestGetPost();
//alert ("quiz_rgp : " + quiz_rgp.uname + "\nquiz_id : " + quiz_rgp.quiz_id);

const quiz_config = {
    name : 'Quizmaker',
    version : "5.01",
    date_creation : "25-01-2019",
    date_release : "30-11-2023",
    author : "J°J°D",
    email : "jjdelalandre@orange.fr",
    urlQuizImg :   (quiz_execution == 1) ? `${quiz.url}/${quiz.folderJS}/images` : `images`,
    urlCommonImg : (quiz_execution == 1) ? `${quiz.url}/images` : `../images`,
    regexAllLetters : /\{[\w+\0123456789 àéèêëîïôöûüù]*\}/gi,
    regexAllLettersPP : /\{[\w+\0123456789 àéèêëîïôöûüù,\;\-\?\!\.\_\=\/]*\}/gi, //PP pour plus ponctuation
    dad_flip_img  :  0, //echange des deux images par l'attribut src
    dad_shift_img :  1, // decalage d'image par remplacement successif
    dad_move_img  :  2, // deplace l'image et changement de div contenair
    dad_flip_div  :  3, //echange des deux div
};

//alert (`quiz_execution = ${quiz_execution} - urlQuizImg = ${quiz_config.urlQuizImg} - folderJS = ${quiz.folderJS}`);
var aze = 'togodo';

const quiz_css = {
    header      : `item-round-top ${quiz.theme}-item-head`,
    navigation  : `item-round-no  ${quiz.theme}-item-body`,
    
    slide       : `item-round-no ${quiz.theme}-item-body `,
    question    : `item-round-no ${quiz.theme}-item-info`,
    proposition : `item-round-no ${quiz.theme}-item-body`,
    
    //buttons     : `item-round-no ${quiz.theme}-item-body`,
    buttons     : `quizButton ${quiz.theme}-item-button`,
    horloge     : `quizHorloge ${quiz.theme}-item-button`,
    progressbar : `item-round-no ${quiz.theme}-item-body`,
    message     : `item-round-no ${quiz.theme}-item-foot`,
    footer      : `item-round-bottom ${quiz.theme}-item-head`,
    
    popup       : `item-round-all ${quiz.theme}-item-body`,
    log         : `item-round-all ${quiz.theme}-item-body`,
    consignes   : `background: #fafadc;opacity:1;text-align:left;`      
    
};

var quizard = [];
///  zzz.togodo();
// ----------------------------------------------------------
// ------------------- the Quiz ----------------------------
// ----------------------------------------------------------

const zzzz = (function theQuiz(){

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

//alert('===>buildQuiz');    
var content = `  
    <div id='quiz_div_main'>
      ${getHtmlHeader()}
      <div id='quiz_div_body'>
        ${getHtmlPopup()}
        <div id='quiz_div_all_slides' name='quiz_div_all_slides' class="${quiz_css.slide}">
            ${getHtmlAllSlides()}
        </div>
        <div id='quiz_div_navigation' class='${quiz_css.navigation}'>
          ${getHtmlButtons()}
          ${getProgressbarHTML()}
        </div>  
      </div>
      ${getHtmlMessage()}
      ${getHtmlFooter()}
    </div>
    <br>${getHtmlLog()}`; 

    //    style='background:blue;'
    //---------------------------------------------------------------------
    const quizQuizmaker = document.getElementById('quiz_div_module_xoops');
    //quizDivMain.innerHTML = output.join('');
    quizQuizmaker.innerHTML = content;
  

    pb_init(quizard.length, 1);
/*
*/
    quizard.forEach((clQuestion, index) => {
        //alert ("===> test : " + clQuestion.question.typeQuestion  + " - " + clQuestion.question.question);
        clQuestion.initSlide ();
      });


}
///////////////////////////////////////////////////////////////////////////////////////////////
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlHeader(){
var build = (quiz.showTypeQuestion) ? ` [build${quiz_messages.twoPoints}${quiz.build}]` : "";

    return `<div id="quiz_div_header" name="quiz_div_header" class="${quiz_css.header}">${quiz.name}${build}</div>`;
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlFooter(){
                 
    return `<div id="quiz_div_footer" name="quiz_div_footer" class="${quiz_css.footer}">
            ${getVersion()}
            </div>`;
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlMessage(){
    //return `<div id="quiz_div_message" name="quiz_div_message" class="${quiz_css.message}">${quiz_messages.score}</div>`;
    return `<div id="quiz_div_message" name="quiz_div_message" class="${quiz_css.message}">${quiz_messages.messages}</div>`;
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlPopup(){
    return `<div id="quiz_div_disabled_all"    name="quiz_div_disabled_all">
              <div id="quiz_div_popup_main"    name="quiz_div_popup_main" class="${quiz_css.popup}">
                <div id="quiz_div_popup_emoji" name="quiz_div_popup_emoji"     class="${quiz_css.popup}">
                    <img  id="quiz_popup_emoji_icon" name="quiz_popup_emoji_icon" src="__EMOJI__" style="width:80px;margin:5px;float:left;"><br>
                    <span id="quiz_popup_emoji_text" name="quiz_popup_emoji_text">__EMOJI_TEXT__</span>
                </div>
                <hr class="quiz-style-two">
                <div id="quiz_div_popup_quest" name="quiz_div_popup_quest" class="${quiz_css.popup}">__QUESTION__</div>
                <center><div id="quiz_div_popup_answers" name="quiz_div_popup_answers" class="${quiz_css.popup}" style="clear:both;">__ANSWERS__</div></center>
                <div id="quiz_div_popup_points"   name="quiz_div_popup_points"   class="${quiz_css.popup}">__POINTS__</div>
                <hr class="quiz-style-two">
                <div id="quiz_div_popup_score"    name="quiz_div_popup_score"    class="${quiz_css.popup}">__SCORE__</div>

                <br><button id="btnPopContinue" class="${quiz_css.buttons}" onclick="event_hide_popup_result();">${quiz_messages.btnContinue}</button>
              </div>
            </div>`;
    
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlPopup2(){
    return `<div id="quiz_div_disabled_all"    name="quiz_div_disabled_all">
              <div id="quiz_div_popup_main"    name="quiz_div_popup_main" class="${quiz_css.log}">
                <div id="quiz_div_popup_emoji" name="quiz_div_popup_emoji"     class="${quiz_css.log}">
                    <img  id="quiz_popup_emoji_icon" name="quiz_popup_emoji_icon" src="__EMOJI__" style="width:80px;margin-top:5px;"><br>
                    <span id="quiz_popup_emoji_text" name="quiz_popup_emoji_text">__EMOJI_TEXT__</span>
                </div>
                <hr class="quiz-style-two">
                <div id="quiz_div_popup_quest" name="quiz_div_popup_quest" class="${quiz_css.log}">__QUESTION__</div>
                <center><div id="quiz_div_popup_answers" name="quiz_div_popup_answers" class="${quiz_css.log}">__ANSWERS__</div></center>
                <div id="quiz_div_popup_points"   name="quiz_div_popup_points"   class="${quiz_css.log}">__POINTS__</div>
                <hr class="quiz-style-two">
                <div id="quiz_div_popup_score"    name="quiz_div_popup_score"    class="${quiz_css.log}">__SCORE__</div>

                <br><button id="btnPopContinue" class="${quiz_css.buttons}" onclick="event_hide_popup_result();">${quiz_messages.btnContinue}</button>
              </div>
            </div>`;
    
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlLog(){
      return `<br>
             <div id="quiz_div_log" name="quiz_div_log" style="display: none;overflow-y: scroll;overflow: hidden;" class="${quiz_css.log}">${quiz_messages.showReponses}
             <span class="question-reponsesOk">
             <div id="quiz_div_answers_bottom" ></div>
             </span></div>`;

}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlButtons(){                       //   style='background:blue;'
  return  `<div id="quiz_div_buttons" name="quiz_div_buttons">
            <button id="quiz_div_horloge"        class="${quiz_css.horloge}">00:00</button>
            <button id="quiz_btn_previousSlide"     class="${quiz_css.buttons}">${quiz_messages.btnPrevious}</button>
            <button id="quiz_btn_nextSlide"         class="${quiz_css.buttons}">${quiz_messages.btnNext}</button>
            <button id="quiz_btn_submitAnswers"     class="${quiz_css.buttons}">${quiz_messages.btnSubmit}</button>
            <button id="quiz_btn_reload_answers"    class="${quiz_css.buttons}">${quiz_messages.btnReload}</button>
            <button id="quiz_btn_show_good_answers" class="${quiz_css.buttons}">${quiz_messages.btnAntiseche}</button>
            <button id="quiz_btn_show_bad_answers"  class="${quiz_css.buttons}" style="transform: rotate(0.5turn);">${quiz_messages.btnAntiseche}</button>
            <button id="quiz_btn_goto_slide"        class="${quiz_css.buttons}" >${quiz_messages.btnGotoSlide}</button>
            ${getHtmlConsignesButtons()}
            </div>`;

}
/**************************************************************************
 *  Affiche le bouton 'consignes'
 *  mis à par pour envisager un placement personaliser, à voir
 * ************************************************************************/
function getHtmlConsignesButtons(){
 // voir la classe quiz_infobulle
var consigneWidth  = 300;   // voir la classe quiz_infobulle
var consigneHeight = 150;  // voir la classe quiz_infobulle
var imgHeight = 48;
var offset1 = 16;
var offset2 = offset1*2;
var consigneTop = imgHeight-offset1;

    switch(quiz.showConsigne){          
    case 2:  var btnPosition = "top:+5px;right:5px;";     var ConsignePosition = `top:+${offset2}px;left:-${consigneWidth+offset1}px;`;  break;   // Top/Right
    case 3:  var btnPosition = "bottom:+5px;right:5px;";  var ConsignePosition = `top:-${consigneHeight}px;left:-${consigneWidth+offset1}px;`;  break;    // Bottom/Right
    case 4:  var btnPosition = "bottom:+5px;left:5px;";   var ConsignePosition = `top:-${consigneHeight}px;left:${offset2}px;`;  break;    // Bottom/Left
    default: var btnPosition = "top:+5px;left:5px;";      var ConsignePosition = `top:+${consigneTop}px;left:${offset2}px;`;  break;    // Top/Left
    }                                                                                                                //
    
  return  `<div id='quiz_btn_showConsigne' name='quiz_btn_showConsigne' class='quiz_infobulle' style='position:absolute;${btnPosition};' >
          <img src="${quiz_config.urlCommonImg}/Help.png" alt="" title="" style="max-height:48px;"/>    
          <div class="custom" width350="" style="${quiz_css.consignes};${ConsignePosition}">
            <span id='quiz_consignes' name='quiz_consignes' >
              Texte des consignes<br>
              Texte des consignes
            </span>
          </div>    
          </div> `;
    
}
/**************************************************************************
 *   
 * ************************************************************************/
function getProgressbarHTML(){
return `<div id="quiz_div_progressbar_main" name="quiz_div_progressbar_main" style="padding: 0px 0px 5px 0px;"><center>
          <div id="pb_contenair" name="pb_contenair" class='${quiz_css.navigation}'>
            <div id="pb_text" name="pb_text" width="80px">0 / 0</div>
            <div id="pb_base" name="pb_base">
                <div id="pb_indicator"></div>
            </div>
          </div>
        </center></div>`;
}

/**************************************************************************
 *   GENERATION DE tous les SLIDES
 * ************************************************************************/
  function getHtmlAllSlides (){
    // variable to store the HTML output
    const output = [];

    quizard.forEach((clQuestion, index) => {
        //alert ("===> buildSlides : " + clQuestion.question.typeQuestion  + " - " + clQuestion.question.question);
        output.push(getHtmlSlide (clQuestion));
      });

    return output.join("\n");

  }
  
/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function getHtmlSlide (clQuestion){
//  alert(clQuestion.typeName);
        const answers = [];  
        //-------------------------------------------------------
        var questionNumber = clQuestion.question.questionNumber;
        var slideNumber = clQuestion.slideNumber;
        statsTotal.quiz_questions = clQuestion.incremente_question(statsTotal.quiz_questions);

        statsTotal.quiz_score_max += clQuestion.scoreMaxiQQ;
        statsTotal.quiz_score_min += clQuestion.scoreMiniQQ;
        
       var comment1 = '';  
       if (clQuestion.question.comment1) {
         comment1 = clQuestion.balises2Values(getMessage(clQuestion.question.comment1));
         //comment1 = comment1.replace("{scoreMaxiQQ}", clQuestion.scoreMaxiQQ).replace("{timer}", clQuestion.question.timer);
         comment1 = `<hr class="quiz-style-two"><span style="color:blue;font-style:oblique;font-size:0.8em;">${comment1}</span>`;
         
       }

       var divPoints = "";
       //est-ce qu'on affiche le score min et max ?
       if(clQuestion.question.isQuestion == 1 && quiz.showScoreMinMax == 1){
        var forPoints = ((clQuestion.scoreMiniQQ == 0) ? quiz_messages.forPoints0 : quiz_messages.forPoints1 );
         var divPoints = forPoints.replace("{pointsMin}", clQuestion.scoreMiniQQ).replace("{pointsMax}", clQuestion.scoreMaxiQQ);
         //var divPoints = quiz_messages.forPoints.replace("{pointsMin}", clQuestion.scoreMiniQQ).replace("{pointsMax}", clQuestion.scoreMaxiQQ);
         //Ajout du timer si il est utilisé (quiz_timer)
         if (quiz.useTimer == 1){
              var divChrono =  `<label id="question${slideNumber}-slideTimer" class="quiz-timer">${clQuestion.question.timer}</label>`;
              divChrono  = quiz_messages.forChrono.replace("{timer}", divChrono);
              divPoints += " " + divChrono;
         }
         //divPoints += "<br>";

       }else if(clQuestion.question.timer > 0 && quiz.useTimer == 1 && quiz.showScoreMinMax == 1){
        //c'est une page begin, end ou group
              var divChrono =  `<label id="question${slideNumber}-slideTimer" class="quiz-timer">${clQuestion.question.timer}</label>`;
              divChrono  = quiz_messages.readerTimer.replace("{timer}", divChrono);
              divPoints += " " + divChrono + "<br>";
       }
       
       //JJDai - type - deplacer dans showResults
       //var sTypeQuestion = (quiz.showTypeQuestion) ? `<br><span style="color:white;">(${clQuestion.question.typeQuestion}/${clQuestion.question.typeForm} - questId = ${clQuestion.question.quizId}/${clQuestion.question.questId}) - ${clQuestion.question.timestamp}</span>`: '';

      // var question = clQuestion.question.question.replace('/','<br>') 
       var question =  clQuestion.balises2Values(clQuestion.question.question, true);
       //alert(question) ;
         
         
         
         
         
       var title = `${divPoints}<div  class="quiz-shadowbox-question" disabled>${slideNumber}${quiz_messages.twoPoints}${question}${comment1}</div>`;



        // add this question and its answers to the output    
       var output = [];
       var classCSS = `quiz_div_slide_main ${quiz_css.slide}` + ((clQuestion.question.typeQuestion == 'pageBegin') ? " quiz_div_slide_begin" : "");
        //---------------------------------------------------------------                                                 
        output.push(
          `<div id="slide[${questionNumber}]" name="slide${questionNumber}" class="${classCSS}" >
            <div class="quiz_slide_question_main ${quiz_css.question}">
                <div class="quiz_slide_question">${title}</div>
                
            </div>
            
            <div class="quiz_slide_propositions ${quiz_css.proposition}">
                <div style='margin:auto;width:90%;'>${clQuestion.build()}</div>
            </div>
          </div>`
        );       

    return output.join('');
}  


/**************************************************************************
 *   
 * ************************************************************************/

function filtrerQuestions(){
var newQuestions = [];
var chrono = 0; //index dans la table quizard
var slideNumber = 0; //n° du slide y compris les pageBegin, pageEnd et pageGroup
var questionNumber = 0; // numero de slide hors page_begin, page_end et page_group,
//alert ("zz : " + myQuestions.length);
// alert ("zz : " + myQuestions[0].question);

    myQuestions.forEach((currentQuestion, index) => {
      if(currentQuestion){
      
      //alert ("filtrerQuestions : nb quizard = " +  quizard.length + "\n" + currentQuestion.type + " - \n" + currentQuestion.question);
            // debut du type de slide a traiter
            var newTplClass = getTplNewClass (currentQuestion, chrono);
            if(newTplClass){
                newTplClass.slideNumber = slideNumber++;
                newTplClass.question.questionNumber = (newTplClass.isQuestion()) ? ++questionNumber : 0;
                chrono++;
                currentQuestion.questionNumber = chrono;
                quizard.push(newTplClass);
                
                //evite de le faire individuellement dans chaque classe
                for (var k=0; k < currentQuestion.answers.length; k++){
                  currentQuestion.answers[k].proposition = decodeHTMLEntities(currentQuestion.answers[k].proposition);
                }
            }
      }
    });
}



 /************************************************************************
  *   TESTE SI LE USER A REPONDU
  * ***********************************************************************/
  function isInputOk (){
//alert("isInputOk");
    var bolOk = false;

    const answerContainers = quizDivAllSlides.querySelectorAll('.answers');
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
 
//    var answerALLContainers = quizDivAllSlides.querySelectorAll('.answers');
    var reponses = 0;

    quizars.forEach( (currentQuestion, index) => {
//        var answerContainer = answerALLContainers[index];
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

      let reponseOk = currentQuestion.getGoodReponses();
      
//alert("getGoodReponses : " + reponseOk);

//        var obRep = document.getElementById(qdic.divLog);
//        obRep.style.display = "block";
       showDiv('quiz_div_log', quiz.showLog);    

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
    showDiv('quiz_div_log', quiz.showLog);
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
  *  CALCUL DU SCORE MAXIMUM ET MINIMUM POUR CHAQUE QUESTION / SLIDE
  * ********************************************************************/
 function getAllScoreMinMax (){
    //currentQuestion = myQuestions[numQuestion];
    
    AllScoresMinMax = {min:0, max:0};
   
    quizard.forEach((clQuestion, index) => {
        var scoreMinMax = quizard.getScoreMinMax();
        AllScoresMinMax.max+= scoreMinMax.max;
        AllScoresMinMax.min+= scoreMinMax.min;
    });


        return AllScoresMinMax;
 }
 /************************************************************************
  *    CALCUL DES RESULTATS
  *    allSlide = true : calcul le score pour tous les slides
  *    allSlide = false ; calcul le score jusqu'au slide courant (utile en cas de retour arrière)
  * ***********************************************************************/
  function getAllScores (allSlide = false){
    // gather answer containers from our quiz
    const answerContainers = quizDivAllSlides.querySelectorAll('.answers');

    var result = {repondu: 0,
                  score: 0,
                  duree: 0};

    // for each question...
    quizard.forEach((clQuestion, index) => {
        if(index <= currentSlide) {
          //alert ('getAllScores ===> ' +  index + " / " + currentSlide);
          //result.repondu +=  clQuestion.getScore(answerContainers[index]);// ((points>0) ? points : 0;
          result.repondu  += (clQuestion.isInputOk( answerContainers[index]) ? 1 : 0);  
          //result.score  += clQuestion.points*1;  
          result.score  += clQuestion.getScore()*1;  
          //clQuestion.getScore()*1;  
          //result.score  += clQuestion.points*1;
          if (clQuestion.isAntiseche && quiz.minusOnShowGoodAnswers != 0) {
              result.repondu  = 0;  
              result.score  -= quiz.minusOnShowGoodAnswers;  
          }
        };
          
    });
    return result;
  }
  
 /************************************************************************
  *    CALCUL DES RESULTATS
  * ***********************************************************************/
  function getStatistiques (currentQuestion = null){
/*
const statsTotal = {
      nbQuestions:  0,
      scoreMaxiQQ:    0,
      scoreMiniQQ:    0,
      repondu:      0,
      score:        0,
      counter:      0,
      timer:        0,
      quiz_score_max:   0,
      quiz_score_min:   0,
      quiz_questions:   0;
      cumul_questions:  0;
      cumul_max:        0,
      cumul_min:        0,
      cumul_score:      0,
      cumul_timer:      0,
      question_max:     0,
      question_min:     0,
      question_points:  0
  };
*/    
                        
    //Ajout de la question courante
    if(currentQuestion) {
        statsTotal.question_number = currentQuestion.question.questionNumber;
        statsTotal.question_min = currentQuestion.scoreMiniBP;
        statsTotal.question_max = currentQuestion.scoreMaxiBP; 
        statsTotal.question_points = currentQuestion.getScoreByProposition(0); 
        }
    
    return statsTotal;
}

//----------------------------------------------------------
  function submitAnswers(){
    currentQuestion = quizard[currentSlide];
    currentQuestion.submitAnswers();
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
//currentQuestion.showGoodAnswers(currentQuestion, quizDivAllSlides);
//       }
  }
//----------------------------------------------------------
  function showResults (){
    // gather answer containers from our quiz
    var answerContainers = quizDivAllSlides.querySelectorAll('.answers');
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
        var scoreMaxi = currentQuestion.scoreMaxiQQ;
        scoreCurrentSlide = quiz_messages.resultThisSlide.replace("{score}",score).replace("{scoreMaxi}", scoreMaxi);  
    }
    //alert (scoreCurrentSlide);

    statsTotal.cumul_score = results.score;
    statsTotal.cumul_questions = results.repondu;

    var exp = quiz_messages.resultOnSlide;
    exp = exp.replace("{reponses}", results.repondu);  //countInputOk()    numCorrect
    exp = exp.replace("{questions}", statsTotal.quiz_questions);
    exp = exp.replace("{points}", results.score);
    exp = exp.replace("{totalPoints}", statsTotal.quiz_score_max);
    //exp = exp.replace("{horloge}", horloge);
   // exp = exp.replace("{rnd}", rnd);

    //pour le dev ajout du type de question, en prod a desativer dans le formulaire du quiz
    if(quiz.showTypeQuestion)
        exp += `<br><span style="font-size:1.2em;font-weight:800;">[ ${currentQuestion.question.typeQuestion} 
               | quiz_id = ${currentQuestion.question.quizId} 
               | quest_id = ${currentQuestion.question.questId} ]`; 
               //- ${currentQuestion.question.timestamp})</span>`;
    
    quizDivMessage.innerHTML = scoreCurrentSlide + "<br>\n" + exp;
    //resultsContainer.innerHTML = `resultat(${chrono}) : ${numCorrect} out of ${myQuestions.length} | points = ${numPoints}`;
    
       
  }

//----------------------------------------------------------
  function showFinalResults (){
    // gather answer containers from our quiz
    var answerContainers = quizDivAllSlides.querySelectorAll('.answers');

    var results = getAllScores();
    statsTotal.cumul_score = results.score;
    statsTotal.cumul_questions = results.repondu;
    
  }


/* *********************************
*
* */
  function showGoodAnswers  (evt) {
    currentQuestion = quizard[currentSlide];
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
    currentQuestion.showGoodAnswers(currentQuestion, quizDivAllSlides);
    
    if (!quizard[currentSlide].isAntiseche) {
        quizard[currentSlide].isAntiseche = true;
    }
    showSlide(currentSlide);
    //// this.blob(myQuestions[currentSlide].question);
    //alert("showCurrentSlide");
    return true;
  }
  
/* *********************************
*
* */
  function showBadAnswers  (evt) {
    currentQuestion = quizard[currentSlide];
//alert ("showAntiSeche => lMinMax = " + evt.target.id);
    
    currentQuestion.showBadAnswers(currentQuestion, quizDivAllSlides);
    
    if (!quizard[currentSlide].isAntiseche) {
        quizard[currentSlide].isAntiseche = true;
    }
    showSlide(currentSlide);
    //// this.blob(myQuestions[currentSlide].question);
    //alert("showCurrentSlide");
    return true;
  }
  
/* *********************************
*
* */
  function gotoSlide (evt) {
    var exp = btnGotoSlide.innerHTML;
    if(exp == quiz_messages.btnGotoSlide) exp= null;
    if(!exp) exp = prompt(quiz_messages.numSlideToGo);
    btnGotoSlide.innerHTML = quiz_messages.btnGotoSlide;
    
    numSlide = parseInt(exp);
    if (Number.isInteger(numSlide)){
    //alert("numSlide : " + numSlide);
        showSlide(numSlide);
    }else{
      var bolOk = false;
      for (var h=0; h<quizard.length; h++){
          currentQuestion = quizard[h].question;
          if(currentQuestion.identifiant == exp){
              bolOk = true;
              showSlide(h); 
              break;
          }
      }
      if(!bolOk) alert (`Identifiant "${exp}" non trouvé`);
    }

    return true;
  }


/* *********************************
*
* */
function reloadQuestion() {
    currentQuestion = quizard[currentSlide];

    currentQuestion.reloadQuestion(quizDivAllSlides);
    showSlide(currentSlide);
    //// this.blob(myQuestions[currentSlide].question);
//setTimeout(sleep, 3000);   
    
    currentQuestion.setFocus();
    return true;
}


/* *********************************
*
* */
  function showCurrentSlide  () {
        showSlide_new(0);
        //alert("showCurrentSlide");
        return true;
  }
 

  function showSlide (n) {
    showSlide_new (n - currentSlide);
    return;
    
   }

  function showNextSlide () {
    //if (currentSlide > 0 && quiz.showResultPopup) event_show_popup_result(currentSlide);
    showSlide_new(+1);
  }

  function showPreviousSlide () {
    showSlide_new(-1);
  }
//--------------------------------------------------------------------

/* ***********************************************
*
*   function showSlide (offset)
*   @ offset int : 0=current slide, >=1 next slide, <=1 previous slide
*
* */

  function showSlide_new (offset=0) {
    //affichage du popup des solutions si osset > 0 uniquement
    if (currentSlide > 0 && quiz.showResultPopup && offset>0) event_show_popup_result(currentSlide);

    var newSlide = currentSlide + offset;
    if (newSlide >= objAllSlides.length) newSlide = objAllSlides.length-1;
    if (newSlide < 0) newSlide = 0;
  
    objAllSlides[currentSlide].classList.remove('quiz_div_slide_begin');
    objAllSlides[currentSlide].classList.remove('quiz_div_slide_question');
    

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
    var lastSlide   = (currentSlide === (objAllSlides.length-1));
    var secondSlide = (currentSlide === 1); //en realité la premiere question normalement
    var isQuestion  = (quizard[currentSlide].isQuestion());  


var consigne = quizard[currentSlide].question['consigne'];
if(!consigne) consigne = quiz_consignes[quizard[currentSlide].question['type']];
enableButton(btnShowConsigne, ((quiz.showConsigne && consigne) ? 0 : 3));    
var obHelp = document.getElementById("quiz_consignes");
if(obHelp) obHelp.innerHTML = consigne;
      
    //est-que le quizTimer est activé et y-a-il un timer sur le slide;
    if (quizard[currentSlide].question.timer > 0 && idSlideTimer == 0 && quiz.useTimer && !lastSlide){
    //alert("start slide timer : |" + quizard[currentSlide].question.timer + "|");
        statsTotal.slideTimer = quizard[currentSlide].question.timer;
        btnNextSlide.innerHTML = `${quiz_messages.btnNext} (${statsTotal.slideTimer})`;
        idSlideTimer = setInterval(updateSlideTimer, 1000);
    }
    //----------------------------------------------
    if (quiz.showReponsesBottom)
        QuizDivAnswersBottom.innerHTML = getAllReponses(quizard[currentSlide]);
      
    var bolOk = isInputOk() || !quizard[currentSlide].isQuestion();
    var allowedGotoNextslide = (bolOk &&  quiz.answerBeforeNext) || !quiz.answerBeforeNext;
    //------------------------------------------
    if(firstSlide){
        showSlide_first(newSlide);              
    }else if(lastSlide){
        showSlide_last(newSlide);   
    }else if(!isQuestion){                   
        showSlide_group(newSlide,secondSlide,allowedGotoNextslide);   
    }else{
        showSlide_question(newSlide,secondSlide,allowedGotoNextslide);   
    }
   
  if (quiz.showResultAllways) showResults();
  if (currentSlide == 1 && quiz.showReponsesBottom)  updateOptions();  

   }
   
/* ******************************************

********************************************* */   
  function showSlide_first (newSlide) {
//        objAllSlides[currentSlide].classList.remove('quiz_div_slide_question');
    objAllSlides[newSlide].classList.add('quiz_div_slide_begin');
        
        // c'est le premier slide - présentation du quiz
        enableButton(btnPreviousSlide, 0);
        enableButton(btnNextSlide, 0);
        
       if ( quiz_rgp.isAnonymous){        
            enableButton (btnStartQuiz, 0);
            document.getElementById("quiz_pseudo").focus();
       }
          
       showDiv('quiz_div_navigation', false);
  }
  
/* ******************************************

********************************************* */   
  function showSlide_last (newSlide) {
        objAllSlides[newSlide].classList.add('quiz_div_slide_begin');
    
        //c'est le dernier slide
        showFinalResults();
        quizard[currentSlide].reloadQuestion();
        stopTimer();
        
        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && !quiz.useTimer) ? 1 : 0));
        enableButton(btnNextSlide, 0);

        enableButton(btnSubmit, ((quiz.allowedSubmit) ? 1 : 3));
        showDiv('quiz_div_navigation', false);       
  }
  function showSlide_group(newSlide,secondSlide,allowedGotoNextslide) {

        objAllSlides[newSlide].classList.add('quiz_div_slide_question');

        if(secondSlide){
            showDiv('quiz_div_navigation', true);       

            //alert("premiser slide");
            //c'est le 1er slide de question - démarage du chrono - le premier slide est le 0
            //au cas ou le bouton précédent est ctivé evite de ralancer le chrono
            if (idQuizTimer == 0 ) startTimer();
        }
        

        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && quizard[currentSlide].question.timer == 0 && !quiz.useTimer)?1:0));
        enableButton(btnNextSlide, ((allowedGotoNextslide) ? 1 : 0));
        enableButton(btnSubmit, 3);
        
        enableButton(btnReloadAnswers, (quiz.showReloadAnswers ? 0 : 3));        
        enableButton(btnShowGoodAnswers, (quiz.showGoodAnswers ? 0 : 3));        
        enableButton(btnShowBadAnswers, (quiz.showBadAnswers  ? 0 : 3));        
        enableButton(btnGotoSlide, (quiz.showGoToSlide  ? 1 : 3));        
       
        
        
  }
  function showSlide_question(newSlide,secondSlide,allowedGotoNextslide) {
        objAllSlides[newSlide].classList.add('quiz_div_slide_question');
        
        //au cas ou l'appel aurait été fait pa l'appel de gotoSlide externe à l'objet
        showDiv('quiz_div_navigation', true);       

        if(secondSlide){
 
            //alert("premiser slide");
            //c'est le 1er slide de question - démarage du chrono - le premier slide est le 0
            //au cas ou le bouton précédent est ctivé evite de ralancer le chrono
            if (idQuizTimer == 0 ) startTimer();
        }
             
        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && quizard[currentSlide].question.timer == 0 && !quiz.useTimer)?1:0));
        enableButton(btnNextSlide, ((allowedGotoNextslide) ? 1 : 0));
        enableButton(btnSubmit, 3);

        enableButton(btnReloadAnswers, (quiz.showReloadAnswers ? 1 : 3));        
        enableButton(btnShowGoodAnswers, (quiz.showGoodAnswers ? 1 : 3));        
        enableButton(btnShowBadAnswers, (quiz.showBadAnswers  ? 1 : 3));        
        enableButton(btnGotoSlide, (quiz.showGoToSlide  ? 1 : 3));        
        
  }

  /**************************************************************
   *  TIMER : mise à jour du délai de la question et passage à la suivante si dépassé
   * ************************************************************/
  function updateSlideTimer (){
     currentQuestion =  quizard[currentSlide];
     //if (currentQuestion.isQuestion == 0) return false;

     var obSlideTimer = document.getElementById("question" + currentSlide + "-slideTimer");
     //var obSlideTimer = document.getElementById("question" + currentQuestion.questionNumber + "-slideTimer");
     if(obSlideTimer) obSlideTimer.innerHTML = statsTotal.slideTimer;
//      if(!obSlideTimer) alert("obSlideTimer pas trouvé");
//      obSlideTimer.innerHTML = statsTotal.slideTimer;
    
    //ajout du timer dans le bouton "next"
    if(statsTotal.slideTimer >= 0){
        btnNextSlide.innerHTML = `${quiz_messages.btnNext} [${statsTotal.slideTimer}]`;
//        alert ('updateSlideTimer - ' + btnNextSlide.innerHTML);
    } else{
        btnNextSlide.innerHTML = quiz_messages.btnNext;
    }
                                              

      //passage a la question suivante si le timer est a zéro
      if (statsTotal.slideTimer < 0){
         clearInterval(idSlideTimer);
         idSlideTimer = 0;
         showNextSlide();
      }
      
      statsTotal.slideTimer --;
      //alert("slideTimer = " + statsTotal.slideTimer);
  }


  //----------------------------------------------------------------
  function updateQuizTimer (){
        quizDivHorloge.innerHTML = formatChrono(statsTotal.cumul_timer ++);
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

function event_show_popup_result(currentSlide) {
//alert("event_show_popup_result");
    
     var currentQuestion = quizard[currentSlide];

    if (!currentQuestion.isQuestion()) {
        return false;
    } 
    
     var divDisabledAll = document.getElementById('quiz_div_disabled_all');     
    getStatistiques(currentQuestion);
/*
    'min':this.scoreMiniBP, 
    'max': this.scoreMaxiBP, 
    'nbGnswers' : 0,
    'nbGoodAnswers' : 0,
    'points': this.getScoreByProposition(0),
*/     

    if (statsTotal.question_points == statsTotal.question_max){
        var emoji = 'emoji_icon_01.png';
        var message = quiz_messages.emoji_text_01;
    }else if (statsTotal.question_points == statsTotal.question_min){
        var emoji = 'emoji_icon_00.png';
        var message = quiz_messages.emoji_text_00;
    } else{
        var emoji = 'emoji_icon_02.png';
        var message = quiz_messages.emoji_text_02;
    }
    document.getElementById('quiz_popup_emoji_icon').src= `${quiz.url}/images/emoji/${emoji}`;
    document.getElementById('quiz_popup_emoji_text').innerHTML = message;
     //quiz_div_popup_results_emoji
     
    document.getElementById('quiz_div_popup_quest').innerHTML = currentQuestion.question.question;     

     


     msg2 = quiz_messages.popupScore.replace("{points}", statsTotal.question_points);
     msg2 = msg2.replace("{max}", statsTotal.question_max);
    document.getElementById('quiz_div_popup_points').innerHTML = msg2;

   
     msgScore =  quiz_messages.popupScoreCumule.replace("{nbReponses}", statsTotal.question_number);
     msgScore =  msgScore.replace("{nbQuestions}", statsTotal.quiz_questions);    
     msgScore =  msgScore.replace("{score}", statsTotal.cumul_score);    
     msgScore =  msgScore.replace("{total}", statsTotal.quiz_score_max);    
     document.getElementById('quiz_div_popup_score').innerHTML = msgScore;
    
    //var ans = getAllReponses(currentQuestion); //JJDai
    var ans = currentQuestion.getAllReponses();
    document.getElementById('quiz_div_popup_answers').innerHTML = ans;
     
     divDisabledAll.style.visibility = "visible";    
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
// this.blob(id); //JJDai
       var obRep = document.getElementById(id);
       if(!obRep) alert(`|${id}|`);
       obRep.style.display = (etat == 1) ? "block" : "none";

 }

/* ***********************************************
*
* */
function shuffleMyquiz () {

    var Intro;
    var Result;
    var newQuestions = [];

    myQuestions.forEach(
      (currentQuestion, idxQuestion) => {
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
        case 0: // disable and visible
        case 2: // Masquer et inline
        case 3: // masquer et not inline
        case 1: // visible et enabled
**************************************************** */
  function enableButton (btn, etat, debug=false) {
  if(debug){
    alert ("enableButton : " + btn.id + "\netat = " + etat);
  }
  
    switch (etat) {
        case 0: // disable and visible
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
       showDiv('quiz_div_log', ob.checked);

       ob = document.getElementById("quiz-useTimer");
       quiz.useTimer = (ob.checked == 1) ? 1: 0;

       ob = document.getElementById("quiz-showLog");
       quiz.showLog = (ob.checked == 1) ? 1: 0;
       showDiv('quiz_div_log', ob.checked);
//alert("updateOptions");
return true;
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

  const quizDivAllSlides = document.getElementById('quiz_div_all_slides');
  const quizDivMessage = document.getElementById('quiz_div_message');

//  const btnContinue = document.getElementById('quiz_btn_continue');
  const btnSubmit = document.getElementById('quiz_btn_submitAnswers');
  const btnPreviousSlide = document.getElementById('quiz_btn_previousSlide');
  const btnNextSlide = document.getElementById('quiz_btn_nextSlide');
  const btnStartQuiz = document.getElementById('quiz_btn_startQuiz');
  const btnEndQuiz = document.getElementById('quiz_btn_endQuiz');
  const btnReloadAnswers = document.getElementById('quiz_btn_reload_answers');
  const btnShowGoodAnswers = document.getElementById('quiz_btn_show_good_answers');
  const btnShowBadAnswers = document.getElementById('quiz_btn_show_bad_answers');
  const btnGotoSlide = document.getElementById('quiz_btn_goto_slide');
  const quizDivHorloge = document.getElementById('quiz_div_horloge');
  
  //const resultsContainer = document.getElementById('results');
  const QuizDivAnswersBottom = document.getElementById('quiz_div_answers_bottom');
  const quizDivLog = document.getElementById('quiz_div_log');  
  const btnShowConsigne = document.getElementById('quiz_btn_showConsigne');


//  btnContinue.addEventListener("click", event_hide_popup_result);
  btnSubmit.addEventListener('click', submitAnswers);
  btnPreviousSlide.addEventListener("click", showPreviousSlide);
  btnNextSlide.addEventListener("click", showNextSlide);
  btnStartQuiz.addEventListener("click", showNextSlide);
  btnEndQuiz.addEventListener("click", submitAnswers);
  btnReloadAnswers.addEventListener('click', reloadQuestion);
  btnShowGoodAnswers.addEventListener('click', showGoodAnswers);
  btnShowBadAnswers.addEventListener('click', showBadAnswers);
  btnGotoSlide.addEventListener('click', gotoSlide);
  
  quizDivAllSlides.addEventListener("click", showCurrentSlide);
  quizDivAllSlides.addEventListener("input", showCurrentSlide);
  //quizDivAllSlides.addEventListener("onmouseover", showCurrentSlide);
  //quizDivAllSlides.addEventListener("onDrop", showCurrentSlide);
  //quizDivAllSlides.addEventListener("change", showCurrentSlide);


  // Pagination
  const objAllSlides = document.querySelectorAll(".quiz_div_slide_main");
  let currentSlide = 0;
  let idSlideTimer = 0;
  let idQuizTimer = 0;
  // Show the first slide

  // Event listeners



  //quizDivAllSlides.addEventListener("keypress", showCurrentSlide);


//      const togodo = document.getElementById('togodo');
//   togodo.addEventListener("onclick", testClick);






/**********************************************************************
 *     AFFICHAGE DU PREMIER SLIDE ET LANCEMENT DU CHRONO
 * ********************************************************************/
  showSlide(currentSlide);
  //startTimer();
})();
//const zzz = new theQuiz();

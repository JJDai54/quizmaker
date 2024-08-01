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
var qbr =  '<br>' ;

const quiz_config = {
    name : 'Quizmaker',
    version : "6.00 beta 1",
    date_creation : "25-01-2019",
    date_release : "16-06-2024",
    author : "J°J°D",
    email : "jjdelalandre@orange.fr",
    urlQuizImg :   (quiz_execution == 1) ? `${quiz.url}/${quiz.folderJS}/images` : `images`,
    urlCommonImg : (quiz_execution == 1) ? `${quiz.url}/images` : `../images`,
    regexAllLetters : /\{[\w+\0123456789 àéèêëîïôöûüù]*\}/gi,
    regexAllLettersPP : /\{[\w+\0123456789 àéèêëîïôöûüùç,\;\-\?\!\.\_\=\/]*\}/gi, //PP pour plus ponctuation
    dad_flip_img  :  0, //echange des deux images par l'attribut src
    dad_shift_img :  1, // decalage d'image par remplacement successif
    dad_move_img  :  2, // deplace l'image et changement de div contenair
    dad_flip_div  :  3, //echange des deux div
};

//alert (`quiz_execution = ${quiz_execution} - urlQuizImg = ${quiz_config.urlQuizImg} - folderJS = ${quiz.folderJS}`);
var aze = 'quizmaker';

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
    log         : `item-round-all ${quiz.theme}-item-body`
};

var h = 0;
var optionsIhm = quiz.optionsIhm*1;
quiz.allowedSubmit     = isBitOk(h++, optionsIhm); 
quiz.showScoreMinMax   = isBitOk(h++, optionsIhm); 
quiz.showAllSolutions  = isBitOk(h++, optionsIhm); 
quiz.answerBeforeNext  = isBitOk(h++, optionsIhm); 
quiz.allowedPrevious   = isBitOk(h++, optionsIhm); 
quiz.useTimer          = isBitOk(h++, optionsIhm); 
quiz.shuffleQuestions  = isBitOk(h++, optionsIhm);  
quiz.showResultPopup   = isBitOk(h++, optionsIhm);  
//quiz.minusOnShowGoodAnswers = isBitOk(h++, optionsIhm);  //avirer
 

//alert("optionsDev = " + quiz.optionsDev);
var h = 0;
var optionsDev = quiz.optionsDev*1;
quiz.showTypeQuestion   = isBitOk(h++, optionsDev); 
quiz.showReloadAnswers  = isBitOk(h++, optionsDev); 
quiz.showGoToSlide      = isBitOk(h++, optionsDev); 
quiz.showGoodAnswers    = isBitOk(h++, optionsDev); 
quiz.showBadAnswers     = isBitOk(h++, optionsDev); 
quiz.showLog            = isBitOk(h++, optionsDev); 
quiz.showResultAllways  = isBitOk(h++, optionsDev);
quiz.showReponsesBottom = isBitOk(h++, optionsDev); 

var quizard = [];
///  zzz.quizmaker();
// ----------------------------------------------------------
// ------------------- the Quiz ----------------------------
// ----------------------------------------------------------

const zzzz = (function theQuiz(){

function quizmaker() {
    alert ("quizmaker");
    return isInputOk();
}
//alert ("01 : " + myQuestions.length);


/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function buildQuiz (){
//    alert ("02 : " + myQuestions);

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
        ${getHtmlMessage()}
        ${getHtmlFooter()}
      </div>
      <input type='hidden' name='quiz_data' id = 'quiz_data0' value=''>
      <input type='hidden' name='quiz_data' id = 'quiz_data1' value=''>
      <input type='hidden' name='quiz_data' id = 'quiz_data2' value=''>
    ${build_timer_html()}
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
        //alert ("===> test : " + clQuestion.question.pluginName  + " - " + clQuestion.question.question);
        clQuestion.initSlide ();
      });
      
     //lors de la construction des slide les score mini et maxi ne sont pas encore completement connu
     //il faut réactualiser le slide pageBegin pour pour pouvoir afficcer ces valeurs apres parcours de touts les slides 
    quizard[0].onUpdate();
    
}

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
    var libBegin = (quiz.libBegin) ? quiz.libBegin : quiz_messages.btnStartQuiz;
    var libEnd   = (quiz.libEnd)   ? quiz.libEnd   : quiz_messages.btnSubmit;

    return `<div id="quiz_div_message" name="quiz_div_message" class="${quiz_css.message}">${quiz_messages.messages}</div>
            <div id="quiz_div_start" name="quiz_div_start" class="${quiz_css.message}">
            <center><br>
            <button id="quiz_btn_startQuiz"     name="quiz_btn_startQuiz"     class="${quiz_css.buttons}" style="font-size:1.8em;visibility: visible; display: inline-block;z-index:9999;">${libBegin}</button>
            <button id="quiz_btn_submitAnswers"     name="quiz_btn_submitAnswers"     class="${quiz_css.buttons}" style="font-size:1.8em;visibility: visible; display: inline-block;z-index:9999;">${libEnd}</button>

            </center>
            </div>`
}
/**************************************************************************
 *   
 * ************************************************************************/
function getHtmlPopup(){
    //icone provisoir pour eviter une erreur JS par absence d'image, 
    var EMOJI   = quiz.url + '/images/emoji/emoji_icon_02.png';    
      
    return `<div id="quiz_div_disabled_all"    name="quiz_div_disabled_all">
              <div id="quiz_div_popup_main"    name="quiz_div_popup_main" class="${quiz_css.popup}">
                <div id="quiz_div_popup_emoji" name="quiz_div_popup_emoji"     class="${quiz_css.popup}">
                    <img  id="quiz_popup_emoji_icon" name="quiz_popup_emoji_icon" src="${EMOJI}" style="width:80px;margin:5px;float:left;"><br>
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
            <button id="quiz_div_horloge"           class="${quiz_css.horloge}">00:00</button>
            <button id="quiz_btn_previousSlide"     class="${quiz_css.buttons}">${quiz_messages.btnPrevious}</button>
            <button id="quiz_btn_nextSlide"         class="${quiz_css.buttons}">${quiz_messages.btnNext}</button>
            <button id="quiz_btn_reload_answers"    class="${quiz_css.buttons}">${quiz_messages.btnReload}</button>
            <button id="quiz_btn_show_good_answers" class="${quiz_css.buttons}">${quiz_messages.btnAntiseche}</button>
            <button id="quiz_btn_show_bad_answers"  class="${quiz_css.buttons}" style="transform: rotate(0.5turn);">${quiz_messages.btnAntiseche}</button>
            <button id="quiz_btn_goto_slide"        class="${quiz_css.buttons}">${quiz_messages.btnGotoSlide}</button>
            <button id="quiz_btn_goto_plugin_begin"  class="${quiz_css.buttons}">${quiz_messages.btnGotoSlideBegin}</button>
            <input  type="hidden" id="quiz_goto_slide" value="">
            </div>${getHtmlConsignesButtons()}
            `;

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
    case 0: return '';        
    case 2:  var btnPosition = "top:+5px;right:5px;";     var ConsignePosition = `top:+${offset2}px;left:-${consigneWidth+offset1}px;`;  break;   // Top/Right
    case 3:  var btnPosition = "bottom:+5px;right:5px;";  var ConsignePosition = `top:-${consigneHeight}px;left:-${consigneWidth+offset1}px;`;  break;    // Bottom/Right
    case 4:  var btnPosition = "bottom:+5px;left:5px;";   var ConsignePosition = `top:-${consigneHeight}px;left:${offset2}px;`;  break;    // Bottom/Left
    default: var btnPosition = "top:+5px;left:5px;";      var ConsignePosition = `top:+${consigneTop}px;left:${offset2}px;`;  break;    // Top/Left
    }                                                                                                                //
    
  return  `<div id='quiz_btn_showConsigne' name='quiz_btn_showConsigne' class='quiz_infobulle' style='position:absolute;${btnPosition};' >
          <img src="${quiz_config.urlCommonImg}/Help.png" alt="" title="" style="max-height:48px;"/>    
          <div id="quiz_div_consigne" class="custom" width350="" style="${ConsignePosition}">
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
var extra = (quiz.showGoToSlide == 1) ? 'onclick="event_pb_gotoSlide(event);" style="cursor: pointer;"':  ''; 
return `<div id="quiz_div_progressbar_main" name="quiz_div_progressbar_main" style="padding: 0px 0px 5px 0px;"><center>
          <div id="pb_contenair" name="pb_contenair" class='${quiz_css.navigation}'>
            <div id="pb_text" name="pb_text" width="80px">0 / 0</div>
            <div id="pb_base" name="pb_base" ${extra}>
                <div id="pb_indicator"></div>
            </div>
          </div>
        </center></div>`;
}
/**************************************************************************
 *   
 * ************************************************************************/

function getHtmlAllSlides(){
var newQuestions = [];
var slideNumber = 0;        //n° du slide y compris les pageBegin, pageEnd et pageGroup
var questionNumber = 0;     //n° du slide hors page_begin, page_end et page_group,
//alert ("zz : " + myQuestions.length);
// alert ("zz : " + myQuestions[0].question);
    const output = [];

    myQuestions.forEach((currentQuestion, index) => {
      if(currentQuestion){
      
      //alert ("getHtmlAllSlides : nb quizard = " +  quizard.length + "\n" + currentQuestion.type + " - \n" + currentQuestion.question);
            // debut du type de slide a traiter
            var clQuestion = getTplNewClass (currentQuestion, slideNumber++);
            if(clQuestion){
                clQuestion.question.questionNumber = (clQuestion.isQuestion) ? ++questionNumber : 0;
                quizard.push(clQuestion);

                statsTotal.quiz_questions  += (clQuestion.isQuestion ? 1 : 0);
                statsTotal.quiz_score_maxi += clQuestion.scoreMaxiQQ;
                statsTotal.quiz_score_mini += clQuestion.scoreMiniQQ;
                
                output.push(getHtmlSlide (clQuestion));
            }
      }
    });
    return output.join("\n");
}

/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function getHtmlSlide (clQuestion){
//  alert(clQuestion.typeName);
    var questionNumber = clQuestion.question.questionNumber;
    var slideNumber = clQuestion.slideNumber;
        
 //alert(`getHtmlSlide - maxiQQ = ${clQuestion.scoreMaxiQQ}\n${clQuestion.question.question}\n${statsTotal.quiz_score_maxi}`)  ;    
       //quiz.questPosComment1 = 2; 
       var comment1 = '';  
       var comment2 = '';  
       if (!quiz.questPosComment1) {quiz.questPosComment1 = 1;}
       if (!clQuestion.question.posComment1) {clQuestion.question.posComment1 = 0;}
       var posComment1 = (clQuestion.question.posComment1 == 0 ) ? quiz.questPosComment1 : clQuestion.question.posComment1;

       if (clQuestion.question.comment1) {
            var comment = clQuestion.balises2Values(getMessage(clQuestion.question.comment1));
            switch(posComment1){
            case 1:
                comment1 = `<hr class="quiz-style-two"><span style="color:blue;font-style:oblique;font-size:0.8em;">${comment}</span>`;
                break;
            case 2:
                comment2 = `<div class="quiz-shadowbox"  style='width:90%;' disabled>${comment}</div><br>`;
                break;
            }
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
       
      // var question = clQuestion.question.question.replace('/','<br>') 
       var question =  clQuestion.balises2Values(clQuestion.question.question, true);
       //alert(question) ;
         
         
       var title = `${divPoints}<div  class="quiz-shadowbox-question" disabled>${slideNumber}${quiz_messages.twoPoints}${question}${comment1}</div>`;

        // add this question and its answers to the output    
       var output = [];
       var classCSS = `quiz_div_plugin_main ${quiz_css.slide}` + ((clQuestion.question.pluginName == 'pageBegin') ? " quiz_div_plugin_begin" : "");
        //---------------------------------------------------------------     
        output.push(
          `<div id="slide[${questionNumber}]" name="slide${questionNumber}" class="${classCSS}" >
            <div class="quiz_plugin_question_main ${quiz_css.question}">
                <div class="quiz_plugin_question">${title}</div>
                
            </div>
            
            <div class="quiz_plugin_propositions ${quiz_css.proposition}">
                <div id="${clQuestion.divMainId}" class="${clQuestion.typeName}" style='margin:auto;width:95%;'>${comment2}${clQuestion.build()}</div>
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

      let reponseOk = currentQuestion.getGoodReponses();
      
//alert("getGoodReponses : " + reponseOk);

//        var obRep = document.getElementById(qdic.divLog);
//        obRep.style.display = "block";
       showDivById('quiz_div_log', quiz.showLog);    

       return reponseOk;

 }

/**************************************************************************
 *   renvois toutes les réponses pour chaque slide. Utilisé pour le develeoppement,
 *   ces réponses sont affichées en bas du formulaire pour faciliter les tests
 * ************************************************************************/

 function getAllReponses (currentQuestion){

    let reponseOk = "";
    //-------------------------------------------------------
    reponseOk = currentQuestion.getAllReponses();
    //-------------------------------------------------------
    showDivById('quiz_div_log', quiz.showLog);
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
          result.score  += clQuestion.getScoreByProposition()*1;  
          //alert(clQuestion.question.question + '->' + clQuestion.getScoreByProposition()*1);
          //clQuestion.getScore()*1;  
          //result.score  += clQuestion.points*1;
          /*
          if (clQuestion.isAntiseche && quiz.minusOnShowGoodAnswers != 0) {
              result.repondu  = 0;  
              result.score  -= quiz.minusOnShowGoodAnswers;  
          }
          */
        };
          
    });

    return result;
  }
  
 /************************************************************************
  *    CALCUL DES RESULTATS
  * ***********************************************************************/
  function getStatistiques (currentQuestion = null){
                        
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
//alert ("submitAnswers");
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

//alert("showResults");
    var results = getAllScores();
    var currentQuestion = quizard[currentSlide];
    if(currentQuestion.isQuestion){
        scoreCurrentSlide = "";
    }else{
        var score = currentQuestion.getScore();
        var scoreMaxi = currentQuestion.scoreMaxiQQ;
        scoreCurrentSlide = quiz_messages.resultThisSlide.replaceAll("{score}",score).replaceAll("{scoreMaxi}", scoreMaxi);  
    }
    //alert (scoreCurrentSlide);

    statsTotal.cumul_score = results.score;
    statsTotal.cumul_questions = results.repondu;

    var exp = quiz_messages.resultOnSlide;
    exp = exp.replaceAll("{reponses}", results.repondu)
             .replaceAll("{questions}", statsTotal.quiz_questions)
             .replaceAll("{points}", results.score)
             .replaceAll("{totalPoints}", statsTotal.quiz_score_maxi);
    //.replaceAll("{horloge}", horloge);


    //pour le dev ajout du type de question, en prod a desativer dans le formulaire du quiz
    if(quiz.showTypeQuestion)
        exp += `<br><span style="font-size:1.2em;font-weight:800;">[ ${currentQuestion.question.pluginName} 
               | quiz_id = ${currentQuestion.question.quizId} 
               | quest_id = ${currentQuestion.question.questId} ]`; 
               //- ${currentQuestion.question.timestamp})</span>`;
    
    quizDivMessage.innerHTML = scoreCurrentSlide + "<br>\n" + exp;
    //resultsContainer.innerHTML = `resultat(${chrono}) : ${numCorrect} out of ${myQuestions.length} | points = ${numPoints}`;
    
       
  }

//----------------------------------------------------------
  function showFinalResults (){
    // gather answer containers from our quiz
    //var answerContainers = quizDivAllSlides.querySelectorAll('.answers');
    var results = getAllScores();
//console.log('showFinalResults => score = ' + results.score);
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
* evenment onClick du bouton btnGotoSlide
* */
  function gotoSlide (evt) {
      var bolOk = false;
    var obGotoSlide = document.getElementById("quiz_goto_slide");
    var exp = obGotoSlide.value;
    obGotoSlide.value = ""; // raz : c'est juste un transfert de valeur
    
    if(!exp) {exp = prompt(quiz_messages.numSlideToGo);}    
    if(!exp) {return;}
    
    var numSlide = parseInt(exp);    
    
    if (Number.isInteger(numSlide)){
    //alert("numSlide : " + numSlide);
        showSlide(numSlide);
        bolOk = true;
      //alert("quiz-main => gotoSlide");
    }else{
      for (var h=0; h<quizard.length; h++){
          currentQuestion = quizard[h].question;
          if(currentQuestion.identifiant == exp){
              bolOk = true;
              showSlide(h); 
              break;
          }
      }
    }
    if (!bolOk) {showSlide(0);}
    //alert("gotoSlide : " + numSlide);
    //evt.stopPropagation();
    return false;
  }
/* *********************************
* evenment onClick du bouton btnGotoSlide
* */
  function gotoSlideBegin (evt) {
    showSlide(0);   
    //evt.stopPropagation();
    return false;
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
        //alert("showCurrentSlide");
        showSlide_new();
        return true;
  }
 

  function showSlide (n) {
    //alert("showSlide : " + n);
    showSlide_new (n - currentSlide);
    quizard[currentSlide].setFocus();
    return true;
    
   }

  function showNextSlide () {
  console.log("===>showNextSlide");
    //alert("showNextSlide");
    //if (currentSlide > 0 && quiz.showResultPopup) event_show_popup_result(currentSlide);
    showSlide_new(+1);
    quizard[currentSlide].setFocus();

  }

  function showPreviousSlide () {
    //alert("showPreviousSlide");
    showSlide_new(-1);
    quizard[currentSlide].setFocus();
    
  }
//--------------------------------------------------------------------

/* ***********************************************
*
*   function showSlide_new  (offset)
*   @ offset int : 0=current slide, >=1 next slide, <=1 previous slide
*
* */

  function showSlide_new (offset=0) {
    moveWindowPosTo('quiz_div_module_xoops');
    onTimesUp();
    console.log("===>showSlide_new - offset=" + offset);
    //affichage du popup des solutions si osset > 0 uniquement
    if (currentSlide > 0 && quiz.showResultPopup && offset>0) event_show_popup_result(currentSlide);
    //alert("showSlide_new : " + offset);

    var newSlide = currentSlide + offset;
    if (newSlide >= objAllSlides.length) newSlide = objAllSlides.length-1;
    if (newSlide < 0) newSlide = 0;
  
    objAllSlides[currentSlide].classList.remove('quiz_div_plugin_begin');
    //objAllSlides[currentSlide].classList.remove('quiz_div_plugin_question' + quiz.showResultAllways);
    objAllSlides[currentSlide].classList.remove('quiz_div_plugin_question0');
    objAllSlides[currentSlide].classList.remove('quiz_div_plugin_question1');
    

    var isNewSlide = (currentSlide != newSlide);
    currentSlide = newSlide;
    //maj de la barre de progression
    pb_setValue(currentSlide + 1);
    
    
        if (isNewSlide){
          showFinalResults();
          clearInterval(idSlideTimer);
          statsTotal.slideTimer = 0;
          idSlideTimer=0;
          quizard[currentSlide].onEnter();
          quizard[currentSlide].setFocus();

        }
    //----------------------------------------------
    // pour faciliter la lecture du code    
    //var firstSlide  = (currentSlide === 0);
    //var lastSlide   = (currentSlide === (objAllSlides.length-1));
    var secondSlide = (currentSlide === 1); //en realité la premiere question normalement
    var isQuestion  = (quizard[currentSlide].isQuestion);  
    //var startTimer  = (quizard[currentSlide].name != 'pageBegin');  
    var bStopTimer  = (currentSlide === (objAllSlides.length-1) && !quizard[currentSlide].isQuestion);  

var currentQuestion = quizard[currentSlide].question;
var consigne = currentQuestion['consigne'];
if(!consigne) consigne = quiz_consignes[currentQuestion['type']];
enableButton(btnShowConsigne, ((quiz.showConsigne && consigne) ? 0 : 3));    
var obHelp = document.getElementById("quiz_consignes");
if(obHelp) obHelp.innerHTML = consigne;
      
    //est-que le quizTimer est activé et y-a-il un timer sur le slide;
    if (currentQuestion.timer > 0 && idSlideTimer == 0 && (quiz.useTimer || currentQuestion.startTimer) && !bStopTimer){
    //alert("start slide timer : |" + currentQuestion.timer + "|");
        statsTotal.slideTimer = currentQuestion.timer;
        btnNextSlide.innerHTML = `${quiz_messages.btnNext} (${statsTotal.slideTimer})`;
        idSlideTimer = setInterval(updateSlideTimer, 1000);
        startChronometre(currentQuestion.timer);
    }
    //----------------------------------------------
    if (quiz.showReponsesBottom)
        QuizDivAnswersBottom.innerHTML = getAllReponses(quizard[currentSlide]);
      
    var bolOk = isInputOk() || !quizard[currentSlide].isQuestion;
    var allowedGotoNextslide = (bolOk &&  quiz.answerBeforeNext) || !quiz.answerBeforeNext;
    //------------------------------------------
    showDivById('quiz_div_start',  false);
    showDivById('quiz_div_message', quiz.showResultAllways);

    if(quizard[currentSlide].name == 'pageBegin'){
        showSlide_pageBegin(newSlide);  
    }else if(quizard[currentSlide].name == 'pageEnd'){
        showSlide_pageEnd(newSlide);   
    }else if(!isQuestion){  //c'est donc une pageGroup ou pageInfo                  
        showSlide_group(newSlide,allowedGotoNextslide,bStopTimer);   

    }else{  //c'est une question
        showSlide_question(newSlide,allowedGotoNextslide,bStopTimer);   
    }
    currentQuestion = quizard[currentSlide];
    currentQuestion.onUpdate(); 
    //showDivById('quiz_btn_submitAnswers', true);

   
  //alert("showSlide_new : " + offset);
  if (quiz.showResultAllways) showResults();
  //if (currentSlide == 1 && quiz.showReponsesBottom)  updateOptions();  
   }
   
/* ******************************************

********************************************* */   
  function moveWindowPosTo (objId) {
  
var container = document.getElementById(objId);
        var newPos = container.offsetTop;
        console.log('===> moveWindowPosTo : ' + newPos);
        window.scroll(0, newPos);
  }
  


/* ******************************************

********************************************* */   
  function showSlide_pageBegin (newSlide) {
//        objAllSlides[currentSlide].classList.remove('quiz_div_plugin_question');
    objAllSlides[newSlide].classList.add('quiz_div_plugin_begin');
        
        // c'est le premier slide - présentation du quiz
        enableButton(btnPreviousSlide, 0);
        enableButton(btnNextSlide, 0);
        
       if ( quiz_rgp.isAnonymous){        
            enableButton (btnStartQuiz, 0);
            document.getElementById("quiz_pseudo").focus();
       }
          
       showDivById('quiz_div_message', false);
       showDivById('quiz_div_start',  true);
       showDivById('quiz_btn_startQuiz', true);
       showDivById('quiz_btn_submitAnswers', false);

       showDivById('quiz_div_navigation', false);
       quizDivMessage.innerHTML = "";//`<button id="quiz_btn_startQuiz"  name="quiz_btn_startQuiz" class="${quiz_css.buttons}" style="font-size:1.8em;visibility: visible; display: inline-block;z-index:9999;">${quiz_messages.btnStartQuiz}</button>`;
               
  }
  
/* ******************************************

********************************************* */   
  function showSlide_pageEnd (newSlide) {
        objAllSlides[newSlide].classList.add('quiz_div_plugin_begin');
    
        //c'est le dernier slide
        showFinalResults();
        //quizard[currentSlide].reloadQuestion();
        stopTimer();
        
        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && !quiz.useTimer) ? 1 : 0));
        enableButton(btnNextSlide, 0);

        showDivById('quiz_div_message', false);
        showDivById('quiz_div_start',  true);
        showDivById('quiz_btn_startQuiz', false);
        showDivById('quiz_btn_submitAnswers', true);

        //enableButton(btnSubmit, ((quiz.allowedSubmit) ? 1 : 3));
        showDivById('quiz_div_navigation', false);       
  }
/* ******************************************

********************************************* */   
  function showSlide_group(newSlide,allowedGotoNextslide,bStopTimer) {

        objAllSlides[newSlide].classList.add('quiz_div_plugin_question' + quiz.showResultAllways);

        showDivById('quiz_div_navigation', true);       

        //alert("premiser slide");
        //c'est le 1er slide de question - démarage du chrono - le premier slide est le 0
        //au cas ou le bouton précédent est ctivé evite de ralancer le chrono
        if (idQuizTimer == 0 ) startTimer();

        

        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && quizard[currentSlide].question.timer == 0 && !quiz.useTimer) ? 1 : 0));
         //enableButton(btnPreviousSlide, ((quiz.allowedPrevious)?1:0));

        enableButton(btnNextSlide, ((allowedGotoNextslide && currentSlide != objAllSlides.length-1) ? 1 : 0));        
        //enableButton(btnSubmit, 3);
        
        enableButton(btnReloadAnswers, (quiz.showReloadAnswers ? 0 : 3));        
        enableButton(btnShowGoodAnswers, (quiz.showGoodAnswers ? 0 : 3));        
        enableButton(btnShowBadAnswers, (quiz.showBadAnswers  ? 0 : 3));        
        enableButton(btnGotoSlide, (quiz.showGoToSlide  ? 1 : 3));        
        enableButton(btnGotoSlideBegin, (quiz.showGoToSlide  ? 1 : 3));        
       
        if(bStopTimer){stopTimer();}
        
  }
  function showSlide_question(newSlide,allowedGotoNextslide,bStopTimer) {
        objAllSlides[newSlide].classList.add('quiz_div_plugin_question' + quiz.showResultAllways);
        //au cas ou l'appel aurait été fait pa l'appel de gotoSlide externe à l'objet
        showDivById('quiz_div_navigation', true);       

        //alert("premiser slide");
        //c'est le 1er slide de question - démarage du chrono - le premier slide est le 0
        //au cas ou le bouton précédent est activé evite de ralancer le chrono
        if (idQuizTimer == 0 ) startTimer();

             
        //enableButton(btnPreviousSlide, ((quiz.allowedPrevious && quizard[currentSlide].question.timer == 0 && !quiz.useTimer) ? 1 : 0));
        enableButton(btnPreviousSlide, ((quiz.allowedPrevious && !quiz.useTimer && currentSlide != 0) ? 1 : 0));
        enableButton(btnNextSlide, ((allowedGotoNextslide && currentSlide != objAllSlides.length-1) ? 1 : 0));
        //enableButton(btnSubmit, 3);

        enableButton(btnReloadAnswers, (quiz.showReloadAnswers ? 1 : 3));        
        enableButton(btnShowGoodAnswers, (quiz.showGoodAnswers ? 1 : 3));        
        enableButton(btnShowBadAnswers, (quiz.showBadAnswers  ? 1 : 3));        
        enableButton(btnGotoSlide, (quiz.showGoToSlide  ? 1 : 3));        
        enableButton(btnGotoSlideBegin, (quiz.showGoToSlide  ? 1 : 3));        
        
        if(bStopTimer){stopTimer();}
//alert("showSlide_question : " + newSlide);        
  }

  /**************************************************************
   *  TIMER : mise à jour du délai de la question et passage à la suivante si dépassé
   * ************************************************************/
  function updateSlideTimer (){
     currentQuestion =  quizard[currentSlide];

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
         onTimesUp();
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
    a = getRandom(100);
    alert("testClick : " + a);
  }

  function eventList_delItem (e) {
    a = getRandom(100);
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

    if (!currentQuestion.isQuestion) {
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
     msgScore =  msgScore.replaceAll("{total}", statsTotal.quiz_score_maxi);    
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



    var index = getRandom(ttheme.length -1);
    return ttheme[index];

 }


 
/* ***********************************************
*
* */
 function showDivById (id, etat=0){
    return showDivObj(document.getElementById(id), etat);
 }
/* ***********************************************
*
* */
 function showDivObj (obDiv, etat=0){
// this.blob(id); //JJDai
    if(!obDiv) {
        alert(`showDivById : |${id}| n'existe pas`);
        return false;
    }
    obDiv.style.display = (etat == 1) ? "block" : "none";
    return true;
 }
/* ***********************************************
*
* */
 function showDivByName (name, etat=0){
// this.blob(id); //JJDai
    var obs = document.getElementsByName(name);
    obs.forEach((obDiv, index) => { 
    //alert(obDiv.getAttribute("name") +"-"+ index);
       obDiv.style.display = (etat == 1) ? "block" : "none";
    })
 }

/* ***********************************************
*
* */
function shuffleMyquiz () {
    //rechercher des groupes
    var i = 0;
    var j = 0;
    var allGroups = [];
    var newGroupe = true;
    
    //recherchez les groupes si il existent
    for(var h = 0; h < myQuestions.length; h++){
        console.log ("pluginName = " + myQuestions[h].pluginName);
        if (myQuestions[h].pluginName == "pageGroup" | myQuestions[h].pluginName == "pageEnd"){
            if ( i > 0){
                var newGroups = myQuestions.slice(i,j+1);
                allGroups.push(newGroups);
            }
            i = h;
            j = i;
        }else if (i > 0){
            j = h;
        }
    }
    console.log("++++++++++++++++++++++++++++++++++++++++++++");
    //---------------------------------------------------------------
    if(allGroups.length == 0){
        // il n'y a pas de groupe
        var newQuestions = myQuestions.slice(1,myQuestions.length-1);
        newQuestions = shuffleArray(newQuestions);

        newQuestions.unshift(myQuestions[0]);    
        newQuestions.push(myQuestions[myQuestions.length-1]);    

    }{
            //un ou plusieurs groupes
        var nbq = 0;
        allGroups = shuffleArray(allGroups);    
        var newQuestions = [];
        newQuestions.push(myQuestions[0]);    
        
        for(var h = 0; h < allGroups.length; h++){
            console.log(`groupe ${h} - nb questions = ${allGroups[h].length} - ${allGroups[h][0].pluginName} - ${allGroups[h][0].question}`);
            nbq += allGroups[h].length;
            
            var newgroup = allGroups[h].slice(1,allGroups.length);
            newgroup = shuffleArray(newgroup);
            newgroup.unshift(allGroups[h][0]);   
            //newQuestions.push(newgroup);     
            newQuestions = newQuestions.concat(newgroup);
        }
          newQuestions.push(myQuestions[myQuestions.length-1]);    
        
        console.log(`shuffleMyquiz - nb groups = ${allGroups.length} - nbq = ${nbq} <=> ${myQuestions.length}`);
        console.log("===========================================")
    }
    
    
    //--------------------------------------------------------

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
  if(!btn) return false;
  
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
       showDivById("results", ob.checked);

       ob = document.getElementById("quiz-showReponses");
       quiz.showReponsesBottom = (ob.checked == 1) ? 1: 0;
       showDivById('quiz_div_log', ob.checked);

       ob = document.getElementById("quiz-useTimer");
       quiz.useTimer = (ob.checked == 1) ? 1: 0;

       ob = document.getElementById("quiz-showLog");
       quiz.showLog = (ob.checked == 1) ? 1: 0;
       showDivById('quiz_div_log', ob.checked);
//alert("updateOptions");
return true;
 }
* */
 

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
    if (quiz.shuffleQuestions) {shuffleMyquiz();}
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
  //const btnEndQuiz = document.getElementById('quiz_btn_endQuiz');
  const btnReloadAnswers = document.getElementById('quiz_btn_reload_answers');
  const btnShowGoodAnswers = document.getElementById('quiz_btn_show_good_answers');
  const btnShowBadAnswers = document.getElementById('quiz_btn_show_bad_answers');
  const btnGotoSlide = document.getElementById('quiz_btn_goto_slide');
  const btnGotoSlideBegin = document.getElementById('quiz_btn_goto_plugin_begin');
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
  //btnEndQuiz.addEventListener("click", submitAnswers);
  btnReloadAnswers.addEventListener('click', reloadQuestion);
  btnShowGoodAnswers.addEventListener('click', showGoodAnswers);
  btnShowBadAnswers.addEventListener('click', showBadAnswers);
  btnGotoSlide.addEventListener('click', gotoSlide);
  btnGotoSlideBegin.addEventListener('click', gotoSlideBegin);
  
  quizDivAllSlides.addEventListener("click", showCurrentSlide);
  quizDivAllSlides.addEventListener("input", showCurrentSlide);
  //quizDivAllSlides.addEventListener("onmouseover", showCurrentSlide);
  //quizDivAllSlides.addEventListener("onDrop", showCurrentSlide);
  //quizDivAllSlides.addEventListener("change", showCurrentSlide);


  // Pagination
  const objAllSlides = document.querySelectorAll(".quiz_div_plugin_main");
  let currentSlide = 0;
  let idSlideTimer = 0;
  let idQuizTimer = 0;
  // Show the first slide

  // Event listeners



  //quizDivAllSlides.addEventListener("keypress", showCurrentSlide);


//      const quizmaker = document.getElementById('quizmaker');
//   quizmaker.addEventListener("onclick", testClick);






/**********************************************************************
 *     AFFICHAGE DU PREMIER SLIDE ET LANCEMENT DU CHRONO
 * ********************************************************************/
 //alert('start');
    if (!quizIsStarted){
        showSlide(currentSlide);
        quizIsStarted = true;
    }
  //startTimer();
  //initTimer(64,80,80,14);
  initTimer_for_quiz(quiz.showTimer,48,14);
  //initTimer_for_quiz(4,48,14);
  //startChronometre(25);
})();
//const zzz = new theQuiz();

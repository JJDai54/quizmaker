/*******************************************************************
*                     pageEnd
* *****************************************************************/
function getPlugin_pageEnd(question, slideNumber){
    return new pageEnd(question, slideNumber);
}

 /*******************************************************************
  *                     pageEnd
  * *****************************************************************/

class pageEnd extends Plugin_Prototype{
name = "pageEnd";

//---------------------------------------------------
buildSlide (bShuffle = true){
    var currentQuestion = this.question;
    return this.getInnerHTML(bShuffle);
 }
  
/* ***************************************
*
* *** */
getInnerHTML(bShuffle = true){

var currentQuestion=this.question;
var name = this.getName();

    var img = this.getImage();
    //alert((this.isLettrine) ? 'c est une lettrine' : 'pas de lettrine');
    const htmlArr = [];
    if(!this.isLettrine) {htmlArr.push(img);}
    
    for(var k in currentQuestion.answers){
      var id = this.getId(k);
      if(currentQuestion.answers[k].proposition == '') continue;
      //console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
      if(k==0 && this.isLettrine) {
        currentQuestion.answers[k].proposition = img + currentQuestion.answers[k].proposition;
      }
     
      //Les div seront remplis dans le update
      htmlArr.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled></div>`);
        
    }
    htmlArr.push(this.getFormSubmitAnswers());
    
    if(quiz.submitBtnPosition){
        htmlArr.push(this.getHtmlSubmitBtn());
    }

    htmlArr.push(qbr);
    return htmlArr.join("\n");

  }
//---------------------------------------------------
isInputOk (answerContainer){
    return false;
 }

//---------------------------------------------------
getHtmlSubmitBtn(){
    var style=`margin-top:50px;font-size:1.5em;height:40px;padding: 0px 24px 0px 24px;`;
    var onclick = `submitAnswers();`;
    var caption = (quiz.libEnd)   ? quiz.libEnd   : quiz_messages.btnSubmit;
        
    var html = `<input type='button' value='${caption}' style='${style}'' onclick='${onclick}'>`;
    return html;
}

//---------------------------------------------------
getFormSubmitAnswers(){
    endGame(); // quiz.hide_interface
    var tNamesId = ['quiz_id', 'uid', 'answers_total', 'answers_achieved', 
                    'score_achieved', 'score_max', 'score_min', 'duration', 'isAnonymous', 'pseudo'];
                 
    var tHtml = [];
    
    tHtml.push(`<form name="form_submit_quizmaker" id="form_submit_quizmaker" action="/modules/quizmaker/results_submit.php?op=submit_answers" method="post">`);
    
    for (var h = 0; h < tNamesId.length; h++){
        tHtml.push(`<input type="hidden" name="${tNamesId[h]}" id="${tNamesId[h]}" value="0" />`);
    }
    tHtml.push(`</form>`);
    
    
    return "\n" + tHtml.join("\n") + "\n";
}  
//---------------------------------------------------
submitAnswers(){
//console.log("submitAnswers begin");
    //---------------------------------------------
/*
    document.form_submit_quizmaker.quiz_id.value = quiz.quizId;
    document.form_submit_quizmaker.uid.value = 0;// quiz.uid;
    document.form_submit_quizmaker.answers_total.value = statsTotal.quiz_questions;
    
    document.form_submit_quizmaker.answers_achieved.value = statsTotal.cumul_questions;
    document.form_submit_quizmaker.score_achieved.value = statsTotal.cumul_score;
    document.form_submit_quizmaker.score_max.value = statsTotal.quiz_score_maxi;
    document.form_submit_quizmaker.score_min.value = statsTotal.quiz_score_mini;
    document.form_submit_quizmaker.duration.value = statsTotal.cumul_timer;
    
    document.form_submit_quizmaker.isAnonymous.value = quiz_rgp.isAnonymous;
    document.form_submit_quizmaker.pseudo.value = quiz_rgp.uname;

    //---------------------------------
    document.form_submit_quizmaker.submit();
    //pas utile mais evite un bug inhérent au language
    alert("submitAnswers end");
*/    
    submitAnswers();
}

/* *********************************************
Mise à jour de l'affichage des scores pour cette page intermédiaire
************************************************ */
onEnter() {
    var currentQuestion=this.question;  

    for(var k in currentQuestion.answers){
      var id = this.getId(k);
      if(currentQuestion.answers[k].proposition == '') continue;
      //console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
        var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition, this.slideNumber);
        document.getElementById(id).innerHTML = exp;
    }
  }

} // ----- fin de la class ------

//---------------------------------------------------
function submitAnswers(){
//console.log("submitAnswers begin");
    //---------------------------------------------
    document.form_submit_quizmaker.quiz_id.value = quiz.quizId;
    document.form_submit_quizmaker.uid.value = 0;// quiz.uid;
    document.form_submit_quizmaker.answers_total.value = statsTotal.quiz_questions;
    
    document.form_submit_quizmaker.answers_achieved.value = statsTotal.cumul_questions;
    document.form_submit_quizmaker.score_achieved.value = statsTotal.cumul_score;
    document.form_submit_quizmaker.score_max.value = statsTotal.quiz_score_maxi;
    document.form_submit_quizmaker.score_min.value = statsTotal.quiz_score_mini;
    document.form_submit_quizmaker.duration.value = statsTotal.cumul_timer;
    
    document.form_submit_quizmaker.isAnonymous.value = quiz_rgp.isAnonymous;
    document.form_submit_quizmaker.pseudo.value = quiz_rgp.uname;

    //---------------------------------
    document.form_submit_quizmaker.submit();
    //pas utile mais evite un bug inhérent au language
    //alert("submitAnswers end");
    //console.log("submitAnswers end");

}

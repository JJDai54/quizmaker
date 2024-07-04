
 /*******************************************************************
  *                     _End
  * *****************************************************************/

class pageEnd extends quizPrototype{
name = "pageEnd";

//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    return this.getInnerHTML() ;
 }
  
/* ***************************************
*
* *** */
getInnerHTML (){

var currentQuestion=this.question;
var name = this.getName();

    const htmlArr = [];
    htmlArr.push(this.getImage());
    
    for(var k in currentQuestion.answers){
      var id = this.getId(k);
      if(currentQuestion.answers[k].proposition == '') continue;
      console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
      //Les div seront remplis dazns le update
      htmlArr.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled></div>`);
        
    }
    htmlArr.push(this.buildFormSubmitAnswers());


    htmlArr.push(qbr);
    return htmlArr.join("\n");

  }
//---------------------------------------------------
isInputOk (answerContainer){
    return false;
 }

//---------------------------------------------------
buildFormSubmitAnswers(){
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
console.log("submitAnswers begin");
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
    alert("submitAnswers end");
}

/* *********************************************
Mise à jour de l'affichage des scores pour cette page intermédiaire
************************************************ */
onEnter() {
    var currentQuestion=this.question;  

    for(var k in currentQuestion.answers){
      var id = this.getId(k);
      if(currentQuestion.answers[k].proposition == '') continue;
      console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
        var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition, 0);
        document.getElementById(id).innerHTML = exp;
    }
  }

} // ----- fin de la classe ------


 /*******************************************************************
  *                     _Group
  * *****************************************************************/

class pageGroup extends quizPrototype{


//---------------------------------------------------
 constructor(question, chrono) {
    super(question, chrono);
  }

//---------------------------------------------------
build (){
var currentQuestion=this.question;
var name = this.getName();

      const answers = [];

      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
          answers.push(
            `<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${currentQuestion.answers[k].proposition}</div>
        `);
          
      }
//       if(this.typeForm == 3){
//           answers.push(this.buildFormSubmitAnswers());
//       }
//alert(answers);
      return answers.join("\n");

  }

build2 (){
var currentQuestion=this.question;
var name = this.getName();

      const answers = [];
      answers.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>`);

      for(var k in currentQuestion.answers){
        var id = this.getId(k);
          answers.push(
            `${currentQuestion.answers[k].proposition}<br>
        `);
          
      }
      answers.push(`</div>`);
      if(this.typeForm == 3){
          answers.push(this.buildFormSubmitAnswers());
      }
//alert(answers);
      return answers.join("\n");

  }
  
//---------------------------------------------------
buildFormSubmitAnswers(){
    var tNamesId = ['quiz_id', 'uid', 'answers_total', 'answers_achieved', 
                    'score_achieved', 'score_max', 'score_min', 'duration'];
                 
    var tHtml = []
    
    tHtml.push(`<form name="form_submit_quizmaker" id="form" action="/modules/quizmaker/results_submit.php?op=submit_answers" method="post">`);
    
    for (var h = 0; h < tNamesId.length; h++){
        tHtml.push(`<input type="hidden" name="${tNamesId[h]}" id="${tNamesId[h]}" value="0" />`);
    }
    tHtml.push(`</form>`);
    
    
    return "\n" + tHtml.join("\n") + "\n";
}  
//---------------------------------------------------
submitAnswers(){
    if(this.typeForm != 3) return false;
    //---------------------------------------------
    //alert('submitAnswers in pageinfo - typeForm = ' + this.typeForm);
    document.form_submit_quizmaker.quiz_id.value = quiz.quizId;
    document.form_submit_quizmaker.uid.value = 0;// quiz.uid;
    document.form_submit_quizmaker.answers_total.value = statsTotal.nbQuestions;
    
    document.form_submit_quizmaker.answers_achieved.value = statsTotal.repondu;
    document.form_submit_quizmaker.score_achieved.value = statsTotal.score;
    document.form_submit_quizmaker.score_max.value = statsTotal.scoreMaxi;
    document.form_submit_quizmaker.score_min.value = statsTotal.scoreMini;
    document.form_submit_quizmaker.duration.value = statsTotal.counter;
    
    //---------------------------------
    document.form_submit_quizmaker.submit();
}

//---------------------------------------------------
isQuestion (){
              
    return false;         
}

//---------------------------------------------------
  getScore (answerContainer){
    return 0;
  }
  
//---------------------------------------------------
  isInputOk(currentQuestion, answerContainer,questionNumber){
    return false;
  }
  
//---------------------------------------------------
  getAllReponses  (currentQuestion){
      return "";
  }
  
//---------------------------------------------------
  getGoodReponses (currentQuestion){
      return '';
  }
  
  
 
//---------------------------------------------------
  update(nameId, questionNumber) {
  }
  
//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions;
  } 
  
//---------------------------------------------------
reloadQuestion()
  {
    var currentQuestion = this.question;
    for(var k in currentQuestion.answers){
      //if(currentQuestion.answers[k].proposition == '') continue;
      var id = this.getId(k);
      var obDiv = document.getElementById(id);
      if(!obDiv) continue;
      obDiv.innerHTML = replaceBalisesByValues(currentQuestion.answers[k].proposition);
    }
  } 
  


} // ----- fin de la classe ------

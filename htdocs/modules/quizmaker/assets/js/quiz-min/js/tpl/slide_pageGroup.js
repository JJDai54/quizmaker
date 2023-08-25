
 /*******************************************************************
  *                     _Group
  * *****************************************************************/

class pageGroup extends quizPrototype{
name = "pageGroup";

//---------------------------------------------------
build (){
var currentQuestion=this.question;
var name = this.getName();

      const answers = [];
/*
    if(currentQuestion.image){
        var imageMain = `<div><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></div>`;
        answers.push(imageMain);
    }
    if(currentQuestion.image){
        var imgUrl = quiz_config.urlQuizImg + '/' + currentQuestion.image;
        var imageMain = `<div class='highslide-gallery'>
                        <a href='${imgUrl}' class='highslide' onclick='return hs.expand(this);'>
                        <img src='${imgUrl}'  alt='' style="max-height:${currentQuestion.options.imgHeight}px;" />
                        </a></div>`;
        answers.push(imageMain);
    }
*/
    if(currentQuestion.image){
      if(currentQuestion.answers[0].proposition != '') {
          var imgHtml = get_highslide_a(quiz_config.urlQuizImg + '/' + currentQuestion.image, null, currentQuestion.options.imgHeight,null,true);
          currentQuestion.answers[0].proposition = imgHtml  + currentQuestion.answers[0].proposition;
      }else{
          var imgHtml = get_highslide_a(quiz_config.urlQuizImg + '/' + currentQuestion.image, null, currentQuestion.options.imgHeight,null,false);
          answers.push(imgHtml);
      }
    }

    /*
          $ret['definition_img'] = "<div>"
            . "</a>"
            . "<div class='highslide-heading'></div>"
            . "</div>"
            . $ret['definition'];
    
    */
      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
            var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition);
            answers.push(
                `<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${exp}</div>
                `);
          
      }
      
      //pour que l'ombre du bas du dernuer texte ne soit pas oupé, un padding serait peut être mieux
      answers.push('<br>'); 
//       if(this.typeForm == 3){
//           answers.push(this.buildFormSubmitAnswers());
//       }
//alert(answers);
      return answers.join("\n");

  }

//---------------------------------------------------
/*
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
*/

//---------------------------------------------------
isQuestion (){
              
    return false;         
}

//---------------------------------------------------
  getScoreByProposition (answerContainer){
    return 0;
  }
  
//---------------------------------------------------
  isInputOk(currentQuestion, answerContainer,chrono){
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
  update(nameId, chrono) {
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

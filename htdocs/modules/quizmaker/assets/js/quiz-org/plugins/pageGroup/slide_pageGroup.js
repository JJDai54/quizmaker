
 /*******************************************************************
  *                     _Group
  * *****************************************************************/

class pageGroup extends quizPrototype{
name = "pageGroup";

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
            console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
            var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition, currentQuestion.questId);
            answers.push(
                `<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${exp}</div>
                `);
          
      }
      
      //pour que l'ombre du bas du dernuer texte ne soit pas oupé, un padding serait peut être mieux
      answers.push(qbr); 
//       if(this.typeForm == 3){
//           answers.push(this.buildFormSubmitAnswers());
//       }
//alert(answers);
      return answers.join("\n");

  }

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
  


} // ----- fin de la classe ------

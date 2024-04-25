
 /*******************************************************************
  *                     _Begin
  * *****************************************************************/

class pageBegin extends quizPrototype{
name = "pageBegin";

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
       answers.push(`<div style='height:430px;'>`);    // background:blue;
      
    if(currentQuestion.image){
        var imageMain = `<div><img src="${ quiz_config.urlQuizImg}/${currentQuestion.options.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></div>`;
        answers.push(imageMain);
    }
      
      //si l'utilisateur n'est pas connecté on lui demande de saisir un pseudo
      if ( quiz_rgp.isAnonymous){
          var id = this.getId('pseudo');
          answers.push(
            `<div class="quiz-shadowbox "  style='width:90%;' disabled>
            <center>${quiz_messages.notConnected}</center><br> 
            ${quiz_messages.inputYourPseudo} : <input type="text" id="quiz_pseudo" name="quiz_pseudo" oninput="quiz_input_pseudo_event(event, '${id}');">
            </div>`);
      }
      
      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
        console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
          var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition, 0);
          answers.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${exp}</div>`);
          
      }

      //answers.push(`<br><button id="quiz_btn_startQuiz"  name="quiz_btn_startQuiz" class="${quiz_css.buttons}" style="font-size:1.8em;visibility: visible; display: inline-block;z-index:9999;">${quiz_messages.btnStartQuiz}</button>`);
       answers.push(`</div>`);
      
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


function quiz_input_pseudo_event(ev, id) {
//alert("quiz_input_pseudo_event : " + id + "\n" + ev.currentTarget.id + "\n" + ev.currentTarget.value);
    ///var pseudo = ev.currentTarget.value;
//     pseudo = pseudo.replace('-','');
//     pseudo = pseudo.replace('_','z');
    var pseudo = ev.currentTarget.value.replace(/#|_/g, '').replace(/[^\w\s]/gi, '');
    ev.currentTarget.value = pseudo;
    
    var btn = document.getElementById('quiz_btn_startQuiz');
    
    if (pseudo == 'Anonymous' || pseudo.length < 5){    
        //alert('pas ok - pseudo = ' + pseudo);    
        //theQuiz.enableButton (btnStartQuiz, 0, true);
        quiz_rgp.uname = pseudo;        
        
        btn.style.display = 'inline-block';
        btn.style.visibility="visible";
        btn.disabled = 'disabled';
        
    }else{
        //alert('ok - pseudo = ' + pseudo);    
        //enableButton (btnStartQuiz, 1, true);
        quiz_rgp.uname = pseudo;   
        quiz_rgp.isAnonymous = false;       
              
        btn.style.visibility="visible";
        btn.style.display = 'inline-block';
        btn.disabled = '';
    }

}



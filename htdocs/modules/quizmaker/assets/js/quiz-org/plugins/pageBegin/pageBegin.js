/*******************************************************************
*                     pageBegin
* *****************************************************************/
function getPlugin_pageBegin(question, slideNumber){
    return new pageBegin(question, slideNumber);
}

 /*******************************************************************
  *                     pageBegin
  * *****************************************************************/

class pageBegin extends Plugin_Prototype{
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

    const htmlArr = [];
    htmlArr.push(this.getImage());
          
    //si l'utilisateur n'est pas connecté on lui demande de saisir un pseudo
    if ( quiz_rgp.isAnonymous){
        var id = this.getId('pseudo');
        htmlArr.push(
          `<div class="quiz-shadowbox "  style='width:90%;' disabled>
          <center>${quiz_messages.notConnected}</center><br> 
          ${quiz_messages.inputYourPseudo} : <input type="text" id="quiz_pseudo" name="quiz_pseudo" oninput="quiz_input_pseudo_event(event, '${id}');">
          </div>`);
    }
    
    for(var k in currentQuestion.answers){
      var id = this.getId(k);
      if(currentQuestion.answers[k].proposition == '') continue;
      console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
      //Les div seront remplis dans le update
      htmlArr.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled></div>`);
        
    }

    htmlArr.push(qbr);    
    return htmlArr.join("\n");

  }
//---------------------------------------------------
isInputOk (answerContainer){
    return false;
 }

//---------------------------------------------------
onUpdate() {
  this.onEnter();
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




 /*******************************************************************
  *                     _pageGroup
  * *****************************************************************/

class pageGroup extends Plugin_Prototype{
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

    const htmlArr = [];
      htmlArr.push(this.getImage());
      
      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
        console.log("IDS ===>" + currentQuestion.questId + "-" + currentQuestion.parentId);
        //Les div seront remplis dazns le update
        htmlArr.push(`<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled></div>`);
          
      }
      
      //pour que l'ombre du bas du dernuer texte ne soit pas coupé, un padding serait peut être mieux
      htmlArr.push(qbr); 

      return htmlArr.join("\n");

  }
//---------------------------------------------------
isInputOk (answerContainer){
    return false;
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

/*******************************************************************
*                     textTextarea
* *****************************************************************/
function getPlugin_textTextarea(question, slideNumber){
    return new textTextarea(question, slideNumber);
}

 /*******************************************************************
  *                     textTextarea
  * *****************************************************************/

class textTextarea extends textMixte{
name = "textTextarea";  
//---------------------------------------------------

/* ***************************************
*
* *** */
  
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var html = '';

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    html = `${this.getImage()}<textarea id="${this.data.textId}"  name="${this.getName()}" class="slide-proposition" rows="${nbRows}">${this.data.text}</textarea>`;

    return html;
}
//---------------------------------------------------

get_all_inputs (k){
    return '';
}

prepareData_local(){
    var options = this.question.options;

    this.data  =  transformTextWithMask(options.prose, options.strToReplace);

}




//---------------------------------------------------
getScoreByProposition (answerContainer){
var currentQuestion = this.question;
var obList = null;
var points = 0;


    //dans tous les cas on retire tous les caractères inutiles  
    var obText = document.getElementById(this.data.textId);
    var reponse = sanityseTextForComparaison(obText.innerHTML);   

    console.log("===>textTextarea->getScoreByProposition\n" 
              + reponse + "\n------------------\n" 
              + this.data.textSanized 
              + "\n------------------\n");
    return ( this.data.textSanized == reponse) ? this.scoreMaxiBP : 0;
    
    return points;
}

/* ************************************
*
* **** */
 showGoodAnswers()
  {
      var currentQuestion = this.question;

      var obText = document.getElementById(this.data.textId);
      obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n");
      computeAllScoreEvent();    
      return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {     
        var currentQuestion = this.question;

            var obText = document.getElementById(this.data.textId);
            obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n")
                                               .replaceAll('la', 'ta')
                                               .replaceAll('o', 'au');

      computeAllScoreEvent();    
    return true;
  
  } 
 
} // ----- fin de la class ------

//------------------------------------------------------------------------
function textTextarea_update_event(e, idText, idParentList, slideNumber, tag) {
 this.blob(`===> quiz_textareaListbox_event - ${idText} - ${slideNumber} - ${tag}`);
    clQuestion = quizard[slideNumber];
    var obExp = document.getElementById(idText);
    var exp = clQuestion.data.text;
    
    var obLists = document.querySelectorAll(`#${idParentList}` + ' ' + tag);
    
    obLists.forEach( (obInput, index) => {
        if(obInput.value != ""){
            exp = exp.replaceAll("{"+(index*1+1)+"}", obInput.value);
        }
    });
    obExp.innerHTML = exp;

    
    //change la couleur des mots choisis
    obLists.forEach( (obInput, index) => {
        if(obInput.value != ""){
            var  tokenName = clQuestion.getName('token',index);
            tokens = document.getElementsByName(tokenName);
            //alert(tokens.length);
            //alert(tokenName + ' - ' + tokens[0].innerHTML + '===>' + tokens[0].style.color);
            tokens.forEach( (token, index) => {
                token.style.color = clQuestion.question.options.wordColor;
            });
        }
    });





    computeAllScoreEvent();
    if(e) {e.stopPropagation();}    
    return false;
}


function clearListbox(listId){
    var obList =  document.getElementById(listId);
    obList.selectedIndex = -1;
    obList.onchange();
}
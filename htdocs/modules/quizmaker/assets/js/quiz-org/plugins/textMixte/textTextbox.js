/*******************************************************************
*                     textTextbox
* *****************************************************************/
function getPlugin_textTextbox(question, slideNumber){
    return new textTextbox(question, slideNumber);
}

 /*******************************************************************
  *                     textTextbox
  * *****************************************************************/

class textTextbox extends textMixte{
name = "textTextbox";  
//---------------------------------------------------
buildSlide (bShuffle = true){
    var currentQuestion = this.question;
    return this.getInnerHTML(bShuffle);
 }

/* ***************************************
*
* *** */
get_all_inputs (k){

    var oninput=`oninput="return textTextbox_update_event(event,'${this.data.textId}','${this.data.listId}',${this.slideNumber},'input');"`;
    var obInput = `<input type="text" id="${this.getId(k,'tlb')}" name="${this.getName('inp')}" value="" class="slide-proposition2" ${oninput}>`;

    return obInput;
}


//---------------------------------------------------
prepareData_local(){
    var options = this.question.options;
    
    var currentQuestion = this.question;
    this.data = transformTextWithToken(options.prose, options.tokenColor, this.getName('token'));
    this.data.allWords = duplicateArray(this.data.words);
}

//---------------------------------------------------
getScoreByProposition (answerContainer){
var currentQuestion = this.question;
var obList = null;
var points = 0;

    obList = this.getQuerySelector('input', this.getName('inp'))
    for(var i=0; i < obList.length; i++) {
        var ob = document.getElementById(this.getId(i));
        console.log(`textarea->getScoreByProposition : ${obList[i].id} - ${obList[i].value} - ${this.data.words[i]}`);
        if(sanityseTextForComparaison(obList[i].value) == sanityseTextForComparaison(this.data.words[i])){
            points += currentQuestion.options.scoreByGoodWord*1;
        }
    }
    
    return points;
}

/* ************************************
*
* **** */
 showGoodAnswers()
  {
    var currentQuestion = this.question;
    
    var obText = document.getElementById(this.data.textId);
    //obText.innerHTML = this.data.textOk;
    var tag =  (this.question.options.variant == 'listbox1') ? 'select' : 'input';
    var obs = this.getQuerySelector(tag);    
    obs.forEach( (obInput, index) => {
        obInput.value = this.data.words[index];
    });

    computeAllScoreEvent();    
    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {     
    var currentQuestion = this.question;
        var tag =  (this.question.options.variant == 'listbox1') ? 'select' : 'input';
        var obs = this.getQuerySelector(tag);    
        obs.forEach( (obInput, index) => {
            var idx = getRandom(this.data.allWords.length-1);
            obInput.value = this.data.allWords[idx];
        });
        
        textTextbox_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
    return true;
  
  } 
 
} // ----- fin de la class ------

//------------------------------------------------------------------------
function textTextbox_update_event(e, idText, idParentList, slideNumber, tag) {
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
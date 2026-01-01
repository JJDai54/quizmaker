/*******************************************************************
*                     textListbox1
* *****************************************************************/
function getPlugin_textListbox1(question, slideNumber){
    return new textListbox1(question, slideNumber);
}

 /*******************************************************************
  *                     textListbox1
  * *****************************************************************/

class textListbox1 extends textMixte{
name = "textListbox1";  

/* ***************************************
*
* *** */
  

//---------------------------------------------------
get_all_inputs (k){
    var listId = this.getId(k,'tlb');
    var tAllWords = shuffleNewArray(this.data.allWords);
    var onclick = `onchange="return textListbox1_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
    var cssClass = "class='textListbox1_select'";
    var obList = getHtmlCombobox( this.getName('inp'), listId,tAllWords, cssClass +' '+ onclick, false);
    var btlClear = `<input type='button' value='...'style="width:25px;" title='' onclick="clearListbox('${listId}');">`;
    return obList +  btlClear;
}


//---------------------------------------------------
prepareData_local(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
//    var intrusArr = this.getIntrusArr();
       
    this.data = transformTextWithToken(options.prose, currentQuestion.options.tokenColor, this.getName('token'));
    this.data.allWords = duplicateArray(this.data.words);
    
    //Ajout des intrus - la boucle s'arrete quand il n'y a plus d'intrus
    for(var h = 0; h < 20; h++){
        if(!options[`intrus_${h}`]) break;
        this.data.allWords.push(options[`intrus_${h}`]);
    }

    return true;
}

//---------------------------------------------------
getScoreByProposition (answerContainer){
var currentQuestion = this.question;
var obList = null;
var points = 0;


     obList = this.getQuerySelector('select', this.getName('inp'));
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
        //alert(`showGoodAnswers : ` + this.data.words[index])
    });

    textListbox1_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
    //computeAllScoreEvent();    
    return true;
  
  } 
/* ************************************
*
* **** */
 initFirstIntrus()
  {
  
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

    textListbox1_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
    return true;
  
  } 
 
} // ----- fin de la class ------

//------------------------------------------------------------------------
function textListbox1_update_event(e, idText, idParentList, slideNumber, tag) {
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
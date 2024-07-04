
 /*******************************************************************
  *                     _textareaMixte
  * *****************************************************************/

class textareaMixte extends quizPrototype{
name = "textareaMixte";  
//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    return this.getInnerHTML() ;
 }
/* ***************************************
*
* *** */
  
getInnerHTML (){

    switch(this.question.options.presentation){
        case 'listbox' : return this.getInnerHTML_allbox() ; break;
        case 'textbox' : return this.getInnerHTML_allbox() ; break;
        default: 
        case 'textarea': return this.getInnerHTML_textarea() ; break;
    
    }
}

/* ***************************************
*
* *** */
  
getInnerHTML_textarea (){
    var currentQuestion = this.question;
    var html = '';
    // this.blob("build : " + currentQuestion.question);

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    html = `${this.getImage()}<textarea id="${this.data.textId}"  name="${this.getName}" class="slide-proposition" rows="${nbRows}">${this.data.text}</textarea>`;

    return html;
}
//---------------------------------------------------

getInnerHTML_allbox (){

    var currentQuestion = this.question;
    var name = this.getName();
    
    if(currentQuestion.options.disposition == "disposition-01"){
      var tpl0 = `{image}<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'><div id='${this.data.listId}'>{listbox}</div></td></tr></table>`;
      var textboxClass = "quiz-shadowbox";    
    }else{
      var tpl0 = `{image}<table class='question'><tr><td>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${this.data.listId}'>{listbox}</div></table>`;
      var textboxClass = "quiz-shadowbox-medium";    
    }
    var textbox = `<div id="${this.data.textId}" name="${name}" class="quiz-shadowbox-medium ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;

//------------------------------------------------------------------
    var htmlArr = [];
    var tWordsA = this.data.words;
    var h = 0;
    for (var k=0; k < tWordsA.length; k++) {
        h++;
        if(this.question.options.presentation == 'listbox'){
            var box = this.get_all_listbox(k);
        }else{
            var box = this.get_all_textbox(k);
        }
        htmlArr.push(`${k*1+1} : ${box}`);        
    }
    var allBox = htmlArr.join("<br>\n"); 
//------------------------------------------------------------------
    tpl0 = tpl0.replace("{textbox}", textbox)
               .replace("{listbox}", allBox)
               .replace("{image}", this.getImage());
    return tpl0;
}

//---------------------------------------------------
get_all_listbox (k){

    var tAllWords = shuffleNewArray(this.data.allWords);
    var onclick = `onchange="return textareaMixte_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
    var obList = getHtmlCombobox( `${name}-${k}`, this.getId('tlb'),tAllWords, onclick, false);
    return obList;
}
//---------------------------------------------------
get_all_textbox (k){

    var oninput=`oninput="return textareaMixte_update_event(event,'${this.data.textId}','${this.data.listId}',${this.slideNumber},'input');"`;
    var obInput = `<input type="text" id="${this.getId('tlb')}" name="${name}-${k}" value="" class="slide-proposition2" ${oninput}>`;

    return obInput;
}


//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    //recupere un tableau :
    //ret.text   = texte avec mask
    //ret.words  = tableau des mots trouvés
    //ret.textOk = text sans les accolades
    
    switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            this.data = transformTextWithToken(currentQuestion.answers[0].proposition, currentQuestion.options.tokenColor);
            break;
        default: 
        case 'textarea': 
            this.data  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options.strToReplace);
            break;
    
    }


    this.initMinMaxQQ(2);    

    this.data.allWords = duplicateArray(this.data.words);
    if(this.question.options.presentation == 'listbox' || currentQuestion.answers[0].buffer){
      var badWords = currentQuestion.answers[0].buffer.split(',');
      for (var k = 0; k < badWords.length; k++){
          if (badWords[k]) {this.data.allWords.push(badWords[k]);}
      }
    }
    this.data.textSanized = sanityseTextForComparaison(this.data.textOk); 
    this.data.textId = currentQuestion.answers[0].ansId + '-divText';
    this.data.listId = this.getId("list");
    
    return true;
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;

    //this.scoreMaxiBP = this.data.words.length * (currentQuestion.options.scoreByGoodWord*1);
    this.scoreMaxiBP = this.question.points*1;
    this.scoreMiniBP = 0;

}

//---------------------------------------------------
getScoreByProposition (answerContainer){
      var currentQuestion = this.question;

    //dans tous les cas on retire tous les caractères inutiles  
    var obText = getObjectById(this.data.textId);
    //alert((obText) ?'ok':'pas glop');
    var reponse = sanityseTextForComparaison(obText.innerHTML);   

    return ( this.data.textSanized == reponse) ? this.scoreMaxiBP : 0;
}
/* **********************************************
*
* ********************************************** */
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    var name = this.getName() + '.antiseche';
    
var textboxClass = "quiz-shadowbox2";    
    var textbox = `<label>
        <div id="${this.data.textId}-rep" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
        </label>`;
        
    return textbox;
 }

/* ************************************
*
* **** */
 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var tag = '';
    
    switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            var obText = document.getElementById(this.data.textId);
            //obText.innerHTML = this.data.textOk;
            tag =  (this.question.options.presentation == 'listbox') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                obInput.value = this.data.words[index];
            });
        
            textareaMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
            break;

        default: 
        case 'textarea': 
            var obText = document.getElementById(this.data.textId);
            obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n");
            break;
    }
        
    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {     
        var currentQuestion = this.question;
        var tag = '';
        
        switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            tag =  (this.question.options.presentation == 'listbox') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                var idx = getRandom(this.data.allWords.length-1);
                obInput.value = this.data.allWords[idx];
            });
            
            textareaMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
            break;

        default: 
        case 'textarea': 
            var obText = document.getElementById(this.data.textId);
            obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n")
                                               .replaceAll('la', 'ta')
                                               .replaceAll('o', 'au');
            break;
    }

    return true;
  
  } 
 
} // ----- fin de la classe ------

//------------------------------------------------------------------------
function textareaMixte_update_event(e, idText, idParentList, slideNumber, tag) {
// this.blob(`===> quiz_textareaListbox_event - ${action} - ${idText} - ${name}`);
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


    if(e) {e.stopPropagation();}    
    return false;
}


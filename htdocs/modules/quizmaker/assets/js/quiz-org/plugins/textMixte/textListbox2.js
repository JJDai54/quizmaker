/*******************************************************************
*                     textListbox2
* *****************************************************************/
function getPlugin_textListbox2(question, slideNumber){
    return new textListbox2(question, slideNumber);
}

 /*******************************************************************
  *                     textListbox2
  * *****************************************************************/

class textListbox2 extends textMixte{
name = "textListbox2";  

/* ***************************************
*
* *** */
  

//---------------------------------------------------
get_all_inputs (k){
    var currentQuestion = this.question;
    
//     if(currentQuestion.answers.length == 0){
//       return this.get_all_inputsFromAllPropositions(); 
//     }
    if(currentQuestion.answers[k].allWords.length > 1){
      var tAllWords = shuffleNewArray(currentQuestion.answers[k].allWords);
    }else{
      var tAllWords = shuffleNewArray(this.data.words);
    }
    
//     if(k >= 3){
//          alert(`get_all_inputs proposition : ${k}`);
//         for(var h=0; h<tAllWords.length; h++){
//             alert(`get_all_inputs : ${tAllWords[h]}`);
//         }
//     }
    
    var listId = this.getId(k,'tlb');
//    alert(k);

      var onclick = `onchange="return textListbox2_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
      var cssClass = "class='textListbox2_select'";
      var obList = getHtmlCombobox( this.getName('inp'), listId,tAllWords, cssClass +' '+ onclick, false);
      var btlClear = `<input type='button' value='...'style="width:25px;" title='' onclick="clearListbox('${listId}');">`;
      return obList +  btlClear;
}
//---------------------------------------------------
get_all_inputsFromAllPropositions (){
    var currentQuestion = this.question;
    
    
    var listId = this.getId(k,'tlb');
    ret.words   = tWordsA;      //Tableau des mots entre accolades
        
    var tAllWords = shuffleNewArray(this.data.words);
    var onclick = `onchange="return textListbox2_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
    var cssClass = "class='textListbox2_select'";
    var obList = getHtmlCombobox( this.getName('inp'), listId,tAllWords, cssClass +' '+ onclick, false);
    var btlClear = `<input type='button' value='...'style="width:25px;" title='' onclick="clearListbox('${listId}');">`;
    return obList +  btlClear;
    
}


/* **********************************
    //recupere un tableau :
    //ret.text   = texte avec mask
    //ret.words  = tableau des mots trouvés
    //ret.textOk = text sans les accolades
************************************* */    
prepareData_local(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    this.data = transformTextWithToken(options.prose, currentQuestion.options.tokenColor, this.getName('token'));
    //this.data.allWords = duplicateArray(this.data.words);
    
    //on mets tous les intrus dans la même gamelle pour les avoir tous dans toutes les listes
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
//alert(`prepareData_local : ${k} : ===> ${options.variant} ===>` + ans.proposition) ;
//            alert(`prepareData_local : k = ${k} / ${currentQuestion.answers.length}`);
        
        var badWords = ans.buffer.split('|');
        if(!badWords[0]) {badWords.shift();}
        //alert(`prepareData_local : ${badWords.length} ===> ${badWords[0]}`);
        // si il n'y a pas d'intrus on reprends l'ensenble des mots entre accolades
        if(badWords.length == 0){
            ans.allWords = duplicateArray(this.data.words);
        }else{
            ans.allWords = [];
            //ans.allWords.push(ans.proposition);
            for (var k = 0; k < badWords.length; k++){
                if (badWords[k]) {ans.allWords.push(badWords[k].replaceAll("&#039;", "'"));}
            }
        }
        
// alert(`prepareData_local : ${k} : ===>` + ans.allWords.length) ;
    }
    return true;
}


//---------------------------------------------------
initSlide(){
    this.showBadAnswers( this.question.options.initText);
}
//---------------------------------------------------
getScoreByProposition (answerContainer){
var currentQuestion = this.question;
var obList = null;
var points = 0;


     obList = this.getQuerySelector('select', this.getName('inp'));
    for(var i=0; i < obList.length; i++) {
        var ob = document.getElementById(this.getId(i));
        //console.log(`textarea->getScoreByProposition : ${obList[i].id} - ${obList[i].value} - ${this.data.words[i]}`);
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
    var tag = '';
    
    var obText = document.getElementById(this.data.textId);
    //obText.innerHTML = this.data.textOk;
    tag =  'select';
    var obs = this.getQuerySelector(tag);    
    
    for(var k = 0; k < obs.length; k++){
      //var ans = currentQuestion.answers[k];
      //obInput.value = this.proposition;
      obs[k].value = this.data.words[k];
      //alert(`showGoodAnswers : ` + this.data.words[k])

    }

    textListbox2_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers(initText = 2)
  {     
      var currentQuestion = this.question;

      var tag =  'select';
      var obs = this.getQuerySelector(tag);    
      var idx = -1;

     for(var k = 0; k < obs.length; k++){
        var ans = currentQuestion.answers[k];
        //alert(k + '===>' + ans.proposition);
            idx = getRandom(ans.allWords.length-1);
        if(initText == 2){
            idx = getRandom(ans.allWords.length-1);
        }else if(initText == 1){
            idx = 1;
        }else{
            idx = -1;
        }
        
//         if(!ans.allWords[idx] || idx==-1 || idx > ans.allWords.length){
//             alert(`showBadAnswers : k = ${k} - idx = ${idx} - length = ${ans.allWords.length}`);
//         }
        //console.log(`showBadAnswers : k = ${k} - idx = ${idx} - length = ${ans.allWords.length} - word = ${ans.allWords[idx]}`);
        obs[k].value = ans.allWords[idx];
      }

      textListbox2_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
      return true;
  
  } 


} // ----- fin de la class ------

//------------------------------------------------------------------------
function textListbox2_update_event(e, idText, idParentList, slideNumber, tag) {
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
            let  tokenName = clQuestion.getName('token',index);
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



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
console.log('===>' + this.question.options.presentation);
    switch(this.question.options.presentation){
        case 'listbox' : return this.getInnerHTML_listbox() ; break;
        case 'textbox' : return this.getInnerHTML_textbox() ; break;
        default: 
        case 'textarea': return this.getInnerHTML_textarea() ; break;
    
    }
}

/* ***************************************
*
* *** */
  
getInnerHTML_textarea (){
    var currentQuestion = this.question;
    const htmlArr = [];
    // this.blob("build : " + currentQuestion.question);

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    htmlArr.push(
        `${this.getImage()}<textarea id="${this.getId(0)}"  name="${this.getName}" class="slide-proposition" rows="${nbRows}">${this.data.propositiontext}</textarea>`
    );
//alert("getInnerHTML_textarea\n" + htmlArr.join("\n"));

    return htmlArr.join("\n");

}
getInnerHTML_listbox (){
console.log("getInnerHTML");
 alert(this.data.allWords.join("\n"));    
    var currentQuestion = this.question;
    var idText = this.getId('text');
    var name = this.getName();
    var idDivList = this.getId("list");
    
    if(currentQuestion.options.disposition == "disposition-01"){
      var tpl0 = `{image}<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'><div id='${idDivList}'>{listbox}</div></td></tr></table>`;
      var textboxClass = "quiz-shadowbox";    
    }else{
      var tpl0 = `{image}<table class='question'><tr><td>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${idDivList}'>{listbox}</div></table>`;
      var textboxClass = "quiz-shadowbox-medium";    
    }

//------------------------------------------------------------------
    
    var textbox = `<div id="${idText}" name="${name}" class="quiz-shadowbox-medium ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.proposition.text}</div>`;
        
    var htmlArr = [];
    var tWordsA = this.data.words;
 //alert(this.data.allWords.join("\n"));    
/*
    for (var k=0; k < tWordsA.length; k++) {
        var tAllWords = shuffleNewArray(this.data.allWords);

        var h=k*1+1;
        var onclick = `onchange="return quiz_textareaListbox_event(event,'update','${idText}', '${idDivList}', ${this.slideNumber});" style="margin-bottom:2px"`;
        var obList = getHtmlCombobox(this.getId('lb'), `${name}-${k}`, tAllWords, onclick, false);
        htmlArr.push(`${k*1+1} : ${obList}`);        
    }
*/

    tpl0 = tpl0.replace("{textbox}", textbox)
               .replace("{listbox}", htmlArr.join("<br>\n"))
               .replace("{image}", this.getImage());
    return tpl0;

}

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    //recupere un tableau :
    //ret.text   = texte avec mask
    //ret.words  = tableau des mots trouvés
    //ret.textOk = text sans les accolades

    this.data.proposition  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options.strToReplace);
    this.initMinMaxQQ(2);    



    this.data.allWords = this.data.proposition.words;
    var badWords = currentQuestion.answers[0].buffer.split(',');
    for (var k = 0; k < badWords.length; k++){
        if (badWords[k]) {this.data.allWords.push(badWords[k]);}
    }
 alert(this.data.allWords.join("\n"));    
// 
// alert(currentQuestion.answers[0].buffer);    
// //alert("prepareData\n" + this.data.words.join("\n"));
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    
    switch(currentQuestion.options.presentation){
        case 'listbox' : return this.getInnerHTML_listbox() ; break;
        case 'textbox' : return this.getInnerHTML_textbox() ; break;
        default: 
        case 'textarea':  
            this.scoreMaxiBP = this.data.words.length * (currentQuestion.options.scoreByGoodWord*1);
            this.scoreMiniBP = 0;
            break;
    }
//alert('computeScoresMinMaxByProposition : ' + this.scoreMaxiBP)
}

getScoreByProposition (answerContainer){
var points = 0;
var k = 0;
      var currentQuestion = this.question;

      var obText = getObjectById(this.getId(0));
      var proposition = sanityseTextForComparaison(this.data.proposition.textOk);
      var reponse = sanityseTextForComparaison(obText.value);   
//alert(proposition + "\n\n" + reponse);

      if (proposition != reponse) { return 0;}


    switch(currentQuestion.options.presentation){
        case 'listbox' : return this.getInnerHTML_listbox() ; break;
        case 'textbox' : return this.getInnerHTML_textbox() ; break;
        default: 
        case 'textarea': return this.scoreMaxiBP; break;
    }
      return points;

}
/* **********************************************
*
* ********************************************** */
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    var id = this.getId(0);
    var name = this.getName() + '.antiseche';
    
var textboxClass = "quiz-shadowbox2";    
    var textbox = `<label>
        <div id="${id}" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.proposition.textOk}</div>
        </label>`;
        
    return textbox;
 }

/* ************************************
*
* **** */
 showGoodAnswers()
  {
    var currentQuestion = this.question;
    
    var obText = document.getElementById(this.getId(0));
    obText.value = this.data.proposition.textOk.replaceAll(qbr, "\n");


    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {
    var obText = document.getElementById(this.getId(0));
    
    obText.value = this.data.proposition.textOk.replaceAll(qbr, "\n")
                                   .replaceAll('la', 'ta')
                                   .replaceAll('o', 'au');
    return true;
  
  } 
 
} // ----- fin de la classe ------

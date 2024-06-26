﻿
 /*******************************************************************
  *                     _textareaSimple
  * *****************************************************************/

class textareaSimple extends quizPrototype{
name = "textareaSimple";  
//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    return this.getInnerHTML() ;
 }

/* ***************************************
*
* *** */
  
getInnerHTML (){
    var currentQuestion = this.question;

    const answers = [];
    // this.blob("build : " + currentQuestion.question);

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    answers.push(
        `<textarea id="${this.getId(0)}"  name="${this.getName}" class="slide-proposition" rows="${nbRows}">${this.data.text}</textarea>`
    );

    return answers.join("\n");

}

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options.strToReplace);
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    this.scoreMaxiBP = currentQuestion.answers[0].points*1;
    return true;
}

//---------------------------------------------------
getScoreByProposition (answerContainer){
var points = 0;
var k = 0;
      var currentQuestion = this.question;

      var obText = getObjectById(this.getId(0));
      var proposition = sanityseTextForComparaison(this.data.textOk);
      var reponse = sanityseTextForComparaison(obText.value);   
//alert(proposition + "\n\n" + reponse);
      if (proposition == reponse) 
            points = parseInt(currentQuestion.answers[0].points*1) ;

      return points;

}

//---------------------------------------------------
 isInputOk (){
      var currentQuestion = this.question;

      var obText = getObjectById(this.getId(0));
      var proposition = sanityseTextForComparaison(this.data.text);
      var reponse = sanityseTextForComparaison(obText.value);   

      return (proposition != reponse);
 }


/* **********************************************
*
* ********************************************** */
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName() + '.antiseche';
    
var textboxClass = "quiz-shadowbox2";    
    var textbox = `<label>
        <div id="${id}" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
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
    obText.value = this.data.textOk.replaceAll(qbr, "\n");


    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {
    var currentQuestion = this.question;
    var obText = document.getElementById(this.getId(0));
    
    var exp = this.data.text;
/*
*/    
    //var tWords = this.data.textOk.split('');
    var tWords = shuffleNewArray(this.data.words);
//alert(tWords.join('|'));    
    while (exp.includes(currentQuestion.options.strToReplace)){
        var idx = getRandomIntInclusive(0, tWords.length);
        exp = exp.replace(currentQuestion.options.strToReplace, tWords[idx]);
    }
    
    
    obText.value = exp;
    return true;
  
  } 
 
} // ----- fin de la classe ------

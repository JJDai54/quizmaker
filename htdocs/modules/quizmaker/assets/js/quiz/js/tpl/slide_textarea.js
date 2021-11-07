
 /*******************************************************************
  *                     _textarea
  * *****************************************************************/

class textarea extends quizPrototype{
name = "textarea";  

 constructor(question, chrono) {
    super();
    this.question = question;
    this.typeName = question.type;
    this.name = question.type;
    this.chrono = chrono;
// console.log("dans la classe ---> " + question.type)
    
    this.prepareData();
    this.computeScoresMinMax();

  }
  
/* ***************************************
*
* *** */
  
build (){
    var currentQuestion = this.question;

    const answers = [];
    // console.log("build : " + currentQuestion.question);

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    answers.push(
        `<textarea id="${this.getId(0)}"  name="${this.getName}" class="slide-proposition" rows="${nbRows}">${this.data.text}</textarea>`
    );

    return answers.join("\n");

}

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options);
}
//---------------------------------------------------
computeScoresMinMax(){
    var currentQuestion = this.question;
    this.scoreMaxi = currentQuestion.answers[0].points*1;
    return true;
}

//---------------------------------------------------
getScore (answerContainer){
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
    var questionNumber = this.question.questionNumber;
    
    if(currentQuestion.options == "H"){
var tpl0 = "<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox1";    
    }else{
var tpl0 = "<table class='question'><tr><td>{textbox}</td></tr><tr><td>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox2";    
    }

    
    var textbox = `<label>
        <div id="${id}" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
        </label>`;
        
    return textbox;
 }



//---------------------------------------------------
 incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 
/* ************************************
*
* **** */
 reloadQuestion(quizBoxAllSlides)
  {
    var currentQuestion = this.question;
    
    var ob = document.getElementById(this.getId(0));
    ob.value = this.data.text;


    return true;
  
  } 
/* ************************************
*
* **** */
 showGoodAnswers()
  {
    var currentQuestion = this.question;
    
    var obText = document.getElementById(this.getId(0));
    obText.value = this.data.textOk.replaceAll('<br>', "\n");


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
    while (exp.includes(currentQuestion.options)){
        var idx = getRandomIntInclusive(0, tWords.length);
        exp = exp.replace(currentQuestion.options, tWords[idx]);
    }
    
    
    obText.value = exp;
    return true;
  
  } 
 
} // ----- fin de la classe ------

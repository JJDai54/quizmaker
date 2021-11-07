
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
console.log("dans la classe ---> " + question.type)
    
    this.prepareData();
    this.computeScoresMinMax();

  }
  
/* ***************************************
*
* *** */
  
build (){
    var currentQuestion = this.question;

    const answers = [];
    console.log("build : " + currentQuestion.question);

    answers.push(
        `<label>
        <textarea id="${this.getId(0)}"  name="${this.getName}" class="slide-proposition" rows="${this.data.nbRows}">${this.data.text}</textarea>
        </label>`
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
    this.scoreMaxi = currentQuestion.answers[0].points
    return true;
}

//---------------------------------------------------
getScore (answerContainer){
var points = 0;
var k = 0;
      var currentQuestion = this.question;

      var obText = getObjectById(this.getId(0));
      var proposition = this.sanityseText(this.data.textOk);
      var reponse = this.sanityseText(obText.value);   

      if (proposition == reponse) points = parseInt(currentQuestion.answers[0].points) *1;

      return points;

}

//---------------------------------------------------
 isInputOk (){
      var currentQuestion = this.question;

      var obText = getObjectById(this.getId(0));
      var proposition = this.sanityseText(this.data.text);
      var reponse = this.sanityseText(obText.value);   

      return (proposition != reponse);
 }


//---------------------------------------------------
sanityseText(exp){
var regAccent;
var car2rep;

    var reponse = exp.replaceAll("\n","").replaceAll("\r","").replaceAll(" ","").toLowerCase();
    
    var cars2del = new RegExp('[ \.\!\?\,\;]', 'gi');
    reponse = reponse.replace(cars2del, "");
    
    regAccent = new RegExp('[àâä]', 'gi');
    car2rep = 'a';
    reponse = reponse.replace(regAccent, car2rep);
    
    
    regAccent = new RegExp('[éèêë]', 'gi');
    car2rep = 'e';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[îï]', 'gi');
    car2rep = 'i';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[ùüü]', 'gi');
    car2rep = 'u';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[ôö]', 'gi');
    car2rep = 'o';
    reponse = reponse.replace(regAccent, car2rep);
    
    return reponse;
}

getAllReponses (flag = 0){
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName();
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
 reloadQuestion(quizSlides)
  {
    var currentQuestion = this.question;
    
    var ob = document.getElementById(this.getId(0));
    ob.value = this.data.text;


    return true;
  
  } 
/* ************************************
*
* **** */
 showAntiSeche()
  {
    var currentQuestion = this.question;
    
    var ob = document.getElementById(this.getId(0));
    ob.value = this.data.textOk;


    return true;
  
  } 
  
 
} // ----- fin de la classe ------

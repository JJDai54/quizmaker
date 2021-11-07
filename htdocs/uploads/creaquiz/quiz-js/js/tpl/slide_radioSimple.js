
 /*******************************************************************
  *                     _radioSimple
  * *****************************************************************/

class radioSimple extends quizPrototype{
//---------------------------------------------------
 constructor(question, chrono) {
    super();
    this.question = question;
    this.typeName = question.type;
    this.name = question.type;
    this.chrono = chrono;
console.log("dans la classe ---> " + question.type)

        this.computeScoresMinMax();

  }

//---------------------------------------------------
  
  
  
build (){
//alert("Class : " + this.name); 
const answers = [];
    
    var  currentQuestion = this.question;
    
      for(var k in currentQuestion.answers){
//alert("build : " + currentQuestion.answers[k].proposition);
          answers.push(
            `<label>
              <input type="radio" id="${this.getId(k)}"  name="${this.getName()}" value="${k}">
              ${getNumAlpha(k,quiz.numerotation)} : ${currentQuestion.answers[k].proposition}
            </label>`
          );
      }
//alert(answers);
      return answers.join("\n");

}



//---------------------------------------------------

computeScoresMinMax(){

    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (currentQuestion.answers[i].points > 0)
                this.scoreMaxi += currentQuestion.answers[i].points;
          if (currentQuestion.answers[i].points < 0)
                this.scoreMini += currentQuestion.answers[i].points;
      }

     return true;
 }
//---------------------------------------------------

getScore (answerContainer){
var points = 0;
      // find selected answer
      // find selected answer
      var  currentQuestion = this.question;

      //const answerContainer = answerContainers[questionNumber];
//       const selector = `input[name=${this.getName()}]:checked`;
//       const userAnswer = (answerContainer.querySelector(selector) || {}).value;
      
var userAnswer = getObjectValueByName(this.getName(), "input", "radio", "checked") //extra a tuliser avec checked par exemple
//alert(userAnswer);
      
      console.log("===>userAnswer : " + userAnswer);
      //alert("getScoreRadio ===> " + currentQuestion.question + " ===> " +  questionNumber);

      if(userAnswer ){
        var points = currentQuestion.answers[userAnswer].points;
        //alert("getScoreRadio->user reponse = " + userAnswer + "===> " + points + " points");
      }

//alert ("getScore->score = " + points);

//alert ("getScoreRadio->score = " + score[0] + "|" + score[1]);
//alert ("getScore : "  + points);
    this.points = points;
    return points;
 }



//---------------------------------------------------
isInputOk (answerContainer){
       // find selected answer
//alert("isInputOk_radio");
var bolOk = false;
      var  currentQuestion = this.question;

      const selector = `input[name=${this.getName(currentQuestion)}]:checked`;
      //const userAnswer = (answerContainer.querySelector(selector) || {}).value;
      const userAnswer = (document.querySelector(selector) || {}).value;

      if(userAnswer ){
        bolOk = true;
      }
      return bolOk;
 }


//---------------------------------------------------
getAllReponses (flag = 0){
      var  currentQuestion = this.question;


    var tReponses = [];
    //var k = 0;
    for(var k in currentQuestion.answers){
        var rep = currentQuestion.answers[k];
        //if(rep.points > 0 || flag == 0)
        //tReponses.push (formatReponseTD(rep.proposition, rep.points));
        //tReponses.push ({'reponse': 'points':});
        tReponses.push ([[rep.proposition], [rep.points]]);
    }
    tReponses = sortArrayArray(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>");

 }





//---------------------------------------------------
update(nameId) {
}

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return (nbQuestions*1)+1;
  } 
 
/* ************************************
*
* **** */
reloadQuestion() {
      var  currentQuestion = this.question;


    for (var h=0; h < currentQuestion.answers.length; h++){
        var id = this.getId(h);
console.log("reloadQuestion  - id = " + id);
        var ob = document.getElementById(id);
        ob.checked = false;
    }
    return true;
  
}
/* ************************************
*
* **** */

showAntiSeche()//, answerContainer
  {
      var  currentQuestion = this.question;

    console.log(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var bolOk = false;
    var points = 0; 


    for (var h=0; h < currentQuestion.answers.length; h++){
        var id = this.getId(h);

        console.log (id + " : " + currentQuestion.answers[h].proposition + "\npoints = " + currentQuestion.answers[h].points);
         //var ob = quizSlides.getElementById(id);
         if(points < currentQuestion.answers[h].points){
            points = currentQuestion.answers[h].points;
            var ob = document.getElementById(id);
            ob.checked = true;
         }
         
    }
    return true;
  
  } 
  
  
} // ----- fin de la classe ------

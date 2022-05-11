
 /*******************************************************************
  *                     _Intro
  * *****************************************************************/

class Intro extends quizPrototype{


//---------------------------------------------------
 constructor(question, chrono) {
    super();
    this.question = question;
    this.typeName = question.type;
    this.name = question.type;
    this.chrono = chrono;
console.log("dans la classe ---> " + question.type)
  }

//---------------------------------------------------
build (){
var currentQuestion=this.question;
      const answers = [];
      for(var k in currentQuestion.answers){
          answers.push(
            `<label>
              ${currentQuestion.answers[k].proposition}
            </label>`
          );
      }
//alert(answers);
      return answers.join("\n");

  }
  

//---------------------------------------------------
  getScore (answerContainer){
    return 0;
  }
  
//---------------------------------------------------
  isInputOk(currentQuestion, answerContainer,questionNumber){
    return false;
  }
  
//---------------------------------------------------
  getAllReponses  (currentQuestion){
      return "";
  }
  
//---------------------------------------------------
  getGoodReponses (currentQuestion){
      return '';
  }
  
  
 
//---------------------------------------------------
  update(nameId, questionNumber) {
  }
  
//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions;
  } 
  
//---------------------------------------------------
//   showAntiSeche(currentQuestion, quizSlides)//, answerContainer
//   {
//     return true;
//   } 
 
 
} // ----- fin de la classe ------

// JavaScript Document
A faire : 

- modifier build
id="${currentQuestion.answers[k].id}"  name="${currentQuestion.answers[k].name}"

-selector modifier name
const selector = `input[name=${this.getName(currentQuestion)}]:???`;



/* ************************************
*
* **** */
static  showAntiSeche(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    // this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);
    var questionNumber = currentQuestion.questionNumber;
    var bolOk = false;
 


    for (var h=0; h < currentQuestion.answers.length; h++){
//        // this.blob (currentQuestion.answers[h].proposition);
        var id = currentQuestion.answers[h].id;
        // this.blob (id + " : " + currentQuestion.answers[h].proposition);
         //var ob = quizDivAllSlides.getElementById(id);
         var ob = document.getElementById(id);
         //ob.checked = (currentQuestion.answers[h].points>0) ? "checked" : false;
         ob.checked = (currentQuestion.answers[h].points>0) ? true: false;
//         // this.blob ((currentQuestion.answers[h].points>0) ? "checked" : "non");
    }



    return true;
  
  } 
  
  
  

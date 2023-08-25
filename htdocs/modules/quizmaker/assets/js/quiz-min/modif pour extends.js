// JavaScript Document

1 : class Ajouter 
 extends quizPrototype

2 : modifier build
id="${currentQuestion.answers[k].id}"  name="${currentQuestion.answers[k].name}"

3 : selector modifier name
const selector = `input[name=${this.getName(currentQuestion)}]:???`;

4 : suprimer les fonctions
toString()
incremente_question()

5 : ajouter la fonction showAntiSeche

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
  
  
  

//alert ("Chargement de Result");
 /*******************************************************************
  *                     Result
  * *****************************************************************/

class Result extends quizPrototype{

//---------------------------------------------------
 constructor(question, chrono) {
    super();
    this.question = question;
    this.typeName = question.type;
    this.name = question.type;
    this.chrono = chrono;
console.log("dans la classe ---> " + question.type)
  }

build (){
var currentQuestion=this.question;
    var stats = "<div id='statistiques' name='statistiques'>"
                + "Emplacement pour les résultats"
                + "</div>";
    return  stats;
 }

//---------------------------------------------------
  getScore (answerContainer){
    return 0;
  }
  

//---------------------------------------------------
  isInputOk(answerContainer){
    return false;
  }
  

//---------------------------------------------------
  getAllReponses  (){
      return "";
  }
  

//---------------------------------------------------
  getGoodReponses (){
      return "";
  }
  
 

//---------------------------------------------------
  update(nameId) {
  }
  

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions;
  } 
 
//---------------------------------------------------
reloadQuestion()
  {
var currentQuestion = this.question;
     const tHtml = [];
     //     var results = getAllScores();
     for(var i in currentQuestion.answers){
         var proposition = currentQuestion.answers[i].proposition;
console.log(i + " ===> " + currentQuestion.answers[i].proposition); 
         proposition = proposition.replace("{repondu}", statsTotal.repondu);
         proposition = proposition.replace("{totalQuestions}", statsTotal.nbQuestions);
         proposition = proposition.replace("{score}", statsTotal.score);
         proposition = proposition.replace("{scoreMaxi}", statsTotal.scoreMaxi);
         proposition = proposition.replace("{scoreMini}", statsTotal.scoreMini);
         proposition = proposition.replace("{duree}",  formatChrono(statsTotal.counter, "{minutes} minutes et {secondes} secondes"));


        tHtml.push(
          `<label>
            <span name="question${this.chrono}" ">${proposition}</span><br>
          </label>`
        );
        
        
     }
    //return answers.join("\n");

    var obStats = document.getElementById("statistiques");
    obStats.innerHTML = tHtml.join("\n");
    
    //return answers.join("\n");
//    return "a revoir";
    
  } 
 
} // ----- fin de la classe ------

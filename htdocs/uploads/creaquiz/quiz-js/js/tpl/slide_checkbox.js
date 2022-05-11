
 /*******************************************************************
  *                     _checkbox
  * *****************************************************************/
class checkbox extends quizPrototype{

//---------------------------------------------------
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

    var name = this.getName();
    
    
    const answers = [];
    answers.push(`<div id=${name}-famille>`);
    answers.push(getHtmlCheckbox(name, shuffleArray(this.data.words), -1, quiz.numerotation));
    answers.push(`</div>`);

    return answers.join("\n");
 }

//---------------------------------------------------
 prepareData(){
    
    var tWords = [];
    var tPoints = [];
    var tKeyPoints = [];
    
    var currentQuestion = this.question;
    
    for(var k in currentQuestion.answers){
        tWords.push(currentQuestion.answers[k].proposition);
        tPoints.push(currentQuestion.answers[k].points)
        tKeyPoints[currentQuestion.answers[k].proposition] = currentQuestion.answers[k].points*1;
    }

    this.data.words =  tWords;   
    this.data.points =  tPoints;   
    this.data.keyPoints = tKeyPoints;
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
   getScore ( answerContainer){
var points = 0;

    var keyPoints = this.data.keyPoints;
    var obs = getObjectsByName(this.getName(), "input", "checkbox", "checked");

    obs.forEach((obInput, index) => {
        points += keyPoints[obInput.getAttribute('caption')]*1;
    });
    
    this.points = points;    
    return points;
  }
///////////////////////////////
//---------------------------------------------------
  isInputOk (answerContainer){
    var obs = getObjectsByName(this.getName(), "input", "checkbox", "checked");
    return (obs.length > 0) ? true : false ;

 }

//---------------------------------------------------
//   getAllReponses (){
//     var tReponses = [];
//     for(var i in this.question.answers){
//         var rep = this.question.answers[i];
//         tReponses.push (`${rep.proposition} ===> ${rep.points} points`) ;
//     }
// 
//     return tReponses.join("<br>");
// 
//  }

getAllReponses (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0 || flag == 0)
            //tReponses.push ({'reponse':rep.proposition, 'points':rep.points});    
            tReponses.push ([[rep.proposition], [rep.points]]);    
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>");


 }

//---------------------------------------------------
  update(nameId, questionNumber) {
}

//---------------------------------------------------
   incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

/* ************************************
*
* **** */
 reloadQuestion() {
    var name = this.getName();
    var obFamille = document.getElementById(`${name}-famille`)

    obFamille.innerHTML = getHtmlCheckbox(name, shuffleArray(this.data.words), -1, quiz.numerotation)
    return true;
}
/* ************************************
*
* **** */
  showAntiSeche()
  {
  var points = 0;
  
    var name = this.getName();
    var obs = getObjectsByName(name, "input","checkbox"); 
    var keyPoints = this.data.keyPoints;
  
    obs.forEach((obInput, index) => {
        var p = keyPoints[obInput.getAttribute('caption')]*1;
         obInput.checked = (p > 0) ? true : false;
    });

    return true;
  
  } 
 

} // ----- fin de la classe ------

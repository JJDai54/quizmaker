
 /*******************************************************************
  *                     _radioSimple
  * *****************************************************************/

class radioSimple extends quizPrototype{
name = 'radioSimple';
//---------------------------------------------------
 constructor(question, chrono) {
    super(question, chrono);
  }


//---------------------------------------------------
  
build (){

    var currentQuestion = this.question;
    var name = this.getName();
    
    const answers = [];
    answers.push(`<div id="${name}-famille" style="text-align:left;padding-left:30px;margin-top:10px;">`); 
    this.data.styleCSS = getMarginStyle(currentQuestion.answers.length);
        
    answers.push(getHtmlRadioKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering,0, this.data.styleCSS));
    answers.push(`</div>`);

//     answers.push(`<div id="${name}-famille" style="text-align:left;padding-left:30px;">`);
//     answers.push(getHtmlRadio(name, this.shuffleArray(this.data.words), -1, currentQuestion.numbering));
//     answers.push(`</div>`);
    this.focusId = name + "-" + "0";
    return answers.join("\n");
 }

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    
    var tWords = [];
    var tPoints = [];
    var tKeyPoints = [];
    var tItems = new Object;    
    
    for(var k in currentQuestion.answers){
        var key = "ans-" + k.padStart(3, '0');        
        //var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);        
        var tWP = {'key': key,
                   'word': currentQuestion.answers[k].proposition, 
                   'points' : currentQuestion.answers[k].points*1};
        tItems[key] = tWP;
    }
//alert(tWords.join("-"));
    this.data.items = tItems;
}

//---------------------------------------------------

computeScoresMinMax(){
var lMin = 0;
var lMax = 0;

    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (lMax < currentQuestion.answers[i].points*1)
              lMax = currentQuestion.answers[i].points;
              
          if (lMin > currentQuestion.answers[i].points*1)
              lMin = currentQuestion.answers[i].points;
      }
      
      
      this.scoreMaxi += lMax * 1;
      this.scoreMini += lMin * 1;
     return true;
 }
// computeScoresMinMax(){

//     var currentQuestion = this.question;
//      for(var i in currentQuestion.answers){
//           if (currentQuestion.answers[i].points > 0 && )
//                 this.scoreMaxi += currentQuestion.answers[i].points*1;
//           if (currentQuestion.answers[i].points < 0)
//                 this.scoreMini += currentQuestion.answers[i].points*1;
//       }
// 
//      return true;
//  }
//---------------------------------------------------

getScore (answerContainer){
var points = 0;
      // find selected answer
      // find selected answer
      var  currentQuestion = this.question;

      //const answerContainer = answerContainers[questionNumber];
//       const selector = `input[name=${this.getName()}]:checked`;
//       const userAnswer = (answerContainer.querySelector(selector) || {}).value;
      
    var userAnswer = getObjectsByName(this.getName(), "input", "radio", "checked"); //extra a uliser avec checked par exemple
//alert("lg = " + userAnswer.length);
      
      // console.log("===>userAnswer : " + userAnswer);
      //alert("getScoreRadio ===> " + currentQuestion.question + " ===> " +  questionNumber);

      if(userAnswer.length>0 ){
        //alert('name = ' + userAnswer[0].name);
        var caption = userAnswer[0].getAttribute('caption');
        var points = this.data.items[caption].points;
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
    var name = this.getName();
    var obFamille = document.getElementById(`${name}-famille`)
    obFamille.innerHTML = getHtmlRadioKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS);
    return true;
}

/* ************************************
*
* **** */
showGoodAnswers()
  {
   var  currentQuestion = this.question;
   var bolOk = false;
   var points = 0; 
   
   var obs = getObjectsByName(this.getName(), 'input', 'radio');
      obs.forEach((obInput, index) => {
      var caption = obInput.getAttribute('caption');
      if (points < this.data.items[caption].points){
        points = this.data.items[caption].points;
        obInput.checked = true;
      }
    });
    return true;

  } 

/* ************************************
*
* **** */
showBadAnswers()
  {
   var  currentQuestion = this.question;
   var bolOk = false;
   var points = 999; 
   
   var obs = getObjectsByName(this.getName(), 'input', 'radio');
      obs.forEach((obInput, index) => {
      var caption = obInput.getAttribute('caption');
      if (points > this.data.items[caption].points){
        points = this.data.items[caption].points;
        obInput.checked = true;
      }
    });
    return true;
  
  } 
  
  
} // ----- fin de la classe ------

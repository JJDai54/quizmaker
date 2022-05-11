

 /*******************************************************************
  *                     _radioMultiple1
  * *****************************************************************/

class radioMultiple1 extends quizPrototype{
name = "radioMultiple1";  
  
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
  
  
  build (currentQuestion, questionNumber){
//alert ("radioMultiple : " + currentQuestion.question);
    var tOpt = currentQuestion.options.split(",");
//alert ("radioMultiple : " + tOpt[0] );

    if (tOpt[0] == "V"){
        return this.build_multiRadioV (currentQuestion, questionNumber);
    }else{
        return this.build_multiRadioH (currentQuestion, questionNumber);
    }
 }
 
 /*******************************************************************
  *                     build_multiRadioH
  * *****************************************************************/

 build_multiRadioH (currentQuestion, questionNumber){
    var id = currentQuestion.answers[0].id;
    var name = currentQuestion.answers[0].name;

      const html = [];

      html.push(`<table class="question">`);

     for(var k in currentQuestion.answers){
      html.push(`<tr>`);

        var tWords = currentQuestion.answers[k].proposition.split(",");
        currentQuestion.answers[k].words = duplicateArray(tWords);
        //html.push(`<td>`);
        shuffleArray(tWords);



        for (var i = 0; i < tWords.length; i++){
          html.push(
            `<td><label>
              <input type="radio" name="${name}-${k}" value="${tWords[i]}">
              ${tWords[i]}
            </label></td>`
          );
        }
       // html.push(`</td>`);
      html.push(`</tr>`);

     }

      html.push(`</table>`);


//alert(answers);
      return html.join("\n");
}

 /*******************************************************************
  *                     build_multiRadioV
  * *****************************************************************/
    build_multiRadioV (currentQuestion, questionNumber){
    var id = currentQuestion.answers[0].id;
    var name = currentQuestion.answers[0].name;

      const html = [];

      html.push(`<table class="question"><tr>`);

     for(var k in currentQuestion.answers){

        var tWords = currentQuestion.answers[k].proposition.split(",");
        currentQuestion.answers[k].words = duplicateArray(tWords);
        html.push(`<td>`);
        shuffleArray(tWords);



        for (var i = 0; i < tWords.length; i++){
          html.push(
            `<label>
              <input type="radio" name="${name}-${k}" value="${tWords[i]}">
              ${tWords[i]}
            </label>`
          );
        }
        html.push(`</td>`);

     }

      html.push(`</tr></table>`);


//alert(answers);
      return html.join("\n");
}
//---------------------------------------------------
prepareData(){
}
//---------------------------------------------------
computeScoresMinMax(){
}


//---------------------------------------------------
   getScore (currentQuestion, answerContainer, questionNumber){
      // find selected answer
      //const answerContainer = answerContainers[questionNumber];
var bolOk = true;
var points = 0;
var score;

    var id = currentQuestion.answers[0].id;
    var name = currentQuestion.answers[0].name;

      for(var k in currentQuestion.answers){
        //var name = `question${questionNumber}-${k}`;
        const selector = `input[name=${name}-${k}]:checked`;
        const userAnswer = (answerContainer.querySelector(selector) || {}).value;
        if(userAnswer ){
 //alert('isInputOk : ' + k);
//          alert(userAnswer);
          var idx = currentQuestion.answers[k].words.indexOf(userAnswer);
          if (idx>0) bolOk = false;
        }
      }

  if (this.isInputOk (myQuestions, answerContainer,questionNumber)){
    score = this.getScoreMinMax(currentQuestion);
    points = bolOk ? score.max : score.min;
  }else{
    points = 0;
  };




/*


      const selector = `input[name=question${questionNumber}]:checked`;
      const userAnswer = (answerContainer.querySelector(selector) || {}).value;
      //alert("getScoreRadio ===> " + currentQuestion.question + " ===> " +  questionNumber);
      var points = 0;

//       if(userAnswer ){
//         var points = currentQuestion.answers[userAnswer].points;
//         //alert("getScoreRadio->user reponse = " + userAnswer + "===> " + points + " points");
//       }
*/


//alert ("getScoreRadio->score = " + score[0] + "|" + score[1]);
      return points;
 }


//---------------------------------------------------
   isInputOk (myQuestions, answerContainer,questionNumber){
var bolOk = true;

    var id = currentQuestion.answers[0].id;
    var name = currentQuestion.answers[0].name;

      //const answerContainer = answerContainers[questionNumber];
      for(var k in currentQuestion.answers){
        //var name = `question${questionNumber}-${k}`;
        const selector = `input[name=${name}-${k}]:checked`;
        const userAnswer = (answerContainer.querySelector(selector) || {}).value;
        if(!userAnswer ){
 //alert('isInputOk : ' + k);
          bolOk = false;
        }
      }
      return bolOk;

 }


//---------------------------------------------------
  getAllReponses (currentQuestion){
   // var tReponses = [];
var tRep = [];

    for(var k in currentQuestion.answers){
       var tRep = currentQuestion.answers[k].proposition.split(",");
       tRep.push(tRep[0]);
    }

    var tPoints = currentQuestion.answers[0].points.split(",");
    var reponse = tRep.join(" - ") + " ===> " + tPoints[0];


    return reponse;
 }


//---------------------------------------------------
  getGoodReponses (currentQuestion){
   // var tReponses = [];
    tRep = [];
    for(k in currentQuestion.answers){
       t = currentQuestion.answers[k].proposition.split(",");
       tRep.push(t[0]);
    }

    tPoints = currentQuestion.answers[0].points.split(",");

    var reponse = tRep.join(" - ") + " ===> " + tPoints[0];


    return reponse;
 }


//---------------------------------------------------
  getScoreMinMax (currentQuestion){
     var score = {min:0, max:0};
     var tPoints = currentQuestion.answers[0].points.split(",");
     score.max = tPoints[0]*1;
     score.min = tPoints[1]*1;

     return score;
 }

//---------------------------------------------------
  update(nameId, questionNumber) {
}

//---------------------------------------------------
 incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 
//---------------------------------------------------
  toString(currentQuestion)
  {
    return this.name + " | " + currentQuestion.question;
  } 
 
} // ----- fin de la classe ------


 /*******************************************************************
  *                     _multiTextbox
  * *****************************************************************/
class multiTextbox extends quizPrototype{
  
  
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


//---------------------------------------------------

  
//---------------------------------------------------
build(){
    var id = this.getName;
    var currentQuestion = this.question;
    
    /*
    
    const answers = [];
        answers.push(`<div id=${name}-famille>`);
        //answers.push(getHtmlTextbox2(name, this.question.answers.length, "slide-proposition", quiz.numerotation, 0));
        answers.push(getHtmlTextbox3(name, this.question.answers, 3, "slide-proposition3", quiz.numerotation, 0));
        answers.push("</div>");
        
    return answers.join("\n");
    /////////////////////////////////////////////////////////////////////////////
    */
    var tHtml = [];
    tHtml.push(`<div id=${name}-famille>`);
        
        
        
    for (var k=0; k < currentQuestion.answers.length; k++){
        var name = this.getId(k);
        tHtml.push(`<label>${getNumAlpha(k*1,quiz.numerotation,0)} : ${currentQuestion.answers[k].caption}<br>`);


            tHtml.push(getHtmlTextbox2b(name, this.data.ans[k].nbInput, "", "", ""));
            //tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>`);
        tHtml.push(`</label>`);
    }
        
    tHtml.push("</div>");
    
    
    return tHtml.join("\n");



 }

//---------------------------------------------------
 prepareData(){
    
    var currentQuestion = this.question;
    
        this.data.ans =[];  
    
    for(var k in currentQuestion.answers){
        var ans = [] ;

        ans.nbInput = currentQuestion.answers[k].inputs * 1; 
        ans.words = currentQuestion.answers[k].proposition.split(",");
// alert(currentQuestion.answers[k].proposition);       
        // mise en minuscule des clés
        ans.points = currentQuestion.answers[k].points.split(",");
// alert(ans.points.length);       
        var tWordsMinuscule = currentQuestion.answers[k].proposition.toLowerCase().split(",");
        ans.points = padArray(ans.points, ans.words.length);
        ans.keyPoints = conbineArrayKeys(tWordsMinuscule, ans.points);  

        this.data.ans.push(ans); 
        
    }
}
//---------------------------------------------------
computeScoresMinMax(){
    var currentQuestion = this.question;

     for(var k in this.question.answers){
     //alert(this.question.answers[0].points.toString());
         var tPoints = this.question.answers[k].points.toString().split(',');
         for (var i = 0; i < tPoints.length; i++) {
           if (this.scoreMaxi < tPoints[i]*1) this.scoreMaxi = tPoints[i]*1;
           if (this.scoreMini > tPoints[i]*1) this.scoreMini = tPoints[i]*1;
         }
        
      }

     return true;
 }
//---------------------------------------------------
getScore (answerContainer){
    var points = 0;
    var currentQuestion = this.question;

    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < currentQuestion.answers.length; k++){
      var tKeyPoints = this.data.ans[k].keyPoints;
      var obs = getObjectsByName(this.getId(k), "input");
        obs.forEach( (obInput, index) => {
            var key = obInput.value.toLowerCase(); 
            if(key!='' && key in tKeyPoints) 
                points += tKeyPoints[key]*1;
        });
    }

      return points;
  }

//---------------------------------------------------
isInputOk (answerContainer){
      // find selected answer
      var bolOk = true;
    var currentQuestion = this.question;

      const selector = `input[name=question${this.question.questionNumber}]`;
      var obs = answerContainer.querySelectorAll(selector);
      var lMin = (this.question.minReponse > 0) ? this.question.minReponse : 1;
      //alert('nb obs ===> ' + obs.length);
      //alert('minReponse ===> ' +  lMin + " | " + this.question.type + " | " + this.question.minReponse);
      
      obs.forEach( (ob, index) => {
      //alert(ob.value);
        var reponse = ob.value.toLowerCase().trim();
        if (reponse.length < lMin) bolOk = false;
      });


      return bolOk;
 }

/* *******************************************
* getAllReponses : renvoie les réponse à la question
* @ flag int: 0 = toutes les réponses / 1 = que les bonnes réponses
* ********** */
getAllReponses (flag=0){
    //pour cette question 0=1
    
    var currentQuestion = this.question;
    var tReponses = [];

    
    for (var k=0; k < currentQuestion.answers.length; k++){
        var tGroup = [];

        var tKeyPoints = sortArrayKey(this.data.ans[k].keyPoints, 'a');
        //var tKeyPoints = this.data.ans[k].keyPoints;
        for(var key in tKeyPoints)
        {
              tGroup.push ({'reponse':key,'points': tKeyPoints[key]}) ;
        }
        tReponses.push(tGroup) ;
    }    

    return formatArray2(tReponses, '=');
}
/* *******************************************
* getAllReponses : renvoie les réponse à la question
* @ flag int: 0 = toutes les réponses / 1 = que les bonnes réponses
* ********** */
getAllReponses2 (flag=0){
    //pour cette question 0=1
    
    var currentQuestion = this.question;
    var tReponses = [];
    tReponses.push (`<table><tr>`) ;
    
    for (var k=0; k < currentQuestion.answers.length; k++){
    tReponses.push (`<td><table>`) ;
      var tKeyPoints = this.data.ans[k].keyPoints;
      for(var key in tKeyPoints)
      {
            tReponses.push (formatReponseTD(key, tKeyPoints[key])) ;
           
      }
    tReponses.push (`</table></td>`) ;
    }    

    tReponses.push (`</tr></table>`) ;
    return tReponses.join("\n");
}

//---------------------------------------------------
  update(nameId, questionNumber) {
}

 
/* ************************************
*
* **** */

 reloadQuestion()
  {
  
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < currentQuestion.answers.length; k++){
      var obs = getObjectsByName(this.getId(k), "input");
      obs.forEach( (obInput, index) => {
          obInput.value = "";
      });
    }
    return true;
  } 

/* ************************************
*
* **** */

 showAntiSeche()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < currentQuestion.answers.length; k++){
        var tWords = shuffleArray(this.data.ans[k].words)
        var obs = getObjectsByName(this.getId(k), "input");
        obs.forEach( (obInput, index) => {
        obInput.value = tWords[index];
        });
    }
    return true;
  } 
  
 
} // ----- fin de la classe ------


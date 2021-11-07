
 /*******************************************************************
  *                     _multiTextbox
  * *****************************************************************/
class multiTextbox extends quizPrototype{
name='multiTextbox';  
  
//---------------------------------------------------
 constructor(question, chrono) {
    super(question, chrono);
  }


//---------------------------------------------------

  
//---------------------------------------------------
build(){
    var id = this.getName;
    var currentQuestion = this.question;
   
    /*
    
    const answers = [];
        answers.push(`<div id=${name}-famille>`);
        //answers.push(getHtmlTextbox2(name, this.question.answers.length, "slide-proposition", currentQuestion.numbering, 0));
        answers.push(getHtmlTextbox3(name, this.question.answers, 3, "slide-proposition3", currentQuestion.numbering, 0));
        answers.push("</div>");
        
    return answers.join("\n");
    /////////////////////////////////////////////////////////////////////////////
    */
    var tHtml = [];
    tHtml.push(`<div id=${name}-famille style='text-align:left;'>`);
        
        
        
    for (var k=0; k < currentQuestion.answers.length; k++){
        var name = this.getId(k);
        tHtml.push(`<label>${getNumAlpha(k*1,currentQuestion.numbering,0)} : ${currentQuestion.answers[k].caption}<br>`);


            tHtml.push(getHtmlTextbox2b(name, this.data.ans[k].nbInput, "", "", ""));
            //tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>`);
        tHtml.push(`</label><hr>`);
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
        for (var h = 0 ; h < tWordsMinuscule.length; h++){
            tWordsMinuscule[h] = sanityseTextForComparaison(tWordsMinuscule[h]);
        }
            
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
     //tWordsA = [...new Set(tWordsA)];
         var tPoints = this.question.answers[k].points.toString().split(',');
         tPoints.sort().reverse();
         for (var i = 0; i < currentQuestion.answers[k].inputs * 1; i++) {
           this.scoreMaxi += tPoints[i]*1;

         }
        
      }

     this.scoreMini = 0;
     return true;
 }
computeScoresMinMaxInd(){
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
            var key = sanityseTextForComparaison(obInput.value); 
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
      //var obs = answerContainer.querySelectorAll(selector);
      var obs = getObjectsByName(this.getName(currentQuestion), "input");
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
getAllReponses  (flag=0){
    //pour cette question 0=1
    
    var currentQuestion = this.question;
    var tReponses = [];

    
    for (var k=0; k < currentQuestion.answers.length; k++){
        var tGroup = [];

        var tKeyPoints = sortArrayKey(this.data.ans[k].keyPoints, 'a');
        //var tKeyPoints = this.data.ans[k].keyPoints;
        for(var key in tKeyPoints)
        {
             tGroup.push ({'inputs':key,'points': tKeyPoints[key]}) ;
        }
        
        tReponses.push(tGroup);
    }    

    return formatArray2(tReponses, '=');
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
/***************************************************/

 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < this.data.ans.length; k++){
        var tKeyPoints = shuffleArrayKeys(this.data.ans[k].keyPoints);    
        var tReponses = [];
        for(var key in tKeyPoints)
        {
            //alert(`${key} = ${tKeyPoints[key]}`);
            if (tKeyPoints[key] > 0) tReponses.push (key) ;
        }

        var obs = getObjectsByName(this.getId(k), "input");
            obs.forEach( (obInput, index) => {
            obInput.value = tReponses[index];
        });
    }
    return true;
  } 
  
/* ************************************
*
* **** */
/***************************************************/

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < this.data.ans.length; k++){
        var tKeyPoints = shuffleArrayKeys(this.data.ans[k].keyPoints);    
        var tReponses = [];
        for(var key in tKeyPoints)
        {
            //alert(`${key} = ${tKeyPoints[key]}`);
            if (tKeyPoints[key] <= 0) tReponses.push (key) ;
        }

        var obs = getObjectsByName(this.getId(k), "input");
            obs.forEach( (obInput, index) => {
            obInput.value = (index < tReponses.length) ? tReponses[index] : '';
        });
    }
    return true;
  } 
 
} // ----- fin de la classe ------


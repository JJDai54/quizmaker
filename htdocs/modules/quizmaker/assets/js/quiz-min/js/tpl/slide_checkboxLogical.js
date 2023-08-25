

 /*******************************************************************
  *                     _checkboxLogical.
  * *****************************************************************/

class checkboxLogical extends quizPrototype{
name = "checkboxLogical";  

/* ***************************************
*
* *** */
build (currentQuestion, questionNumber){
    var currentQuestion = this.question;

    return this.getInnerHTML();
}
/* ***************************************
*
* *** */
getInnerHTML(){
    var currentQuestion = this.question;
    var name = this.getName();
    var id = this.getId(0);
    const answers = [];

    var imageMain = this.getInnerHTML_img();    
    this.data.styleCSSTxt = getMarginStyle(this.data.words.length);
    var obListText = getHtmlSpan(name, this.shuffleArray(this.data.words),  currentQuestion.numbering, 0, this.data.styleCSSTxt);
    answers.push(`<table width="90%" class="question" style="margin:auto;"><tr>${imageMain}
                  <td id="${name}-famille" style="text-align:left;width:50%;">${obListText}</td>`);
    
     this.data.styleCSSInp = getMarginStyle(this.data.reponses.length);
     var obListInput = getHtmlCheckbox(name, this.shuffleArray(this.data.reponses), -1, currentQuestion.numbering, this.data.words.length, this.data.styleCSSInp);
    answers.push(`<td id="${name}-cartes" style="text-align:left;width:50%">${obListInput}</td></tr></table>`);

    this.focusId = name + "-" + "0";
    //alert (this.focusId);
    return answers.join("\n");
 }
 
getInnerHTML_img(){
    var currentQuestion = this.question;
    var imageMain = '';
    
    if(currentQuestion.image){
        imageMain = `<td><img src="${ quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></td>`;
    }
    return imageMain;
    
}

/* ***************************************
*
* *** */
 reloadQuestion()
  {
    var currentQuestion = this.question;
    this.data.styleCSSTxt = getMarginStyle(this.data.words.length);
     this.data.styleCSSInp = getMarginStyle(this.data.reponses.length);

    var name = this.getName();
    var id = this.getId(0);
    
    var obTd = document.getElementById(`${name}-famille`);
    var tWords = this.shuffleArray(this.data.words);
    obTd.innerHTML = getHtmlSpan(name, tWords,  currentQuestion.numbering, 0, this.data.styleCSSTxt);

    var obTd = document.getElementById(`${name}-cartes`);
    var tReponses = this.shuffleArray(this.data.reponses);
    obTd.innerHTML = getHtmlCheckbox(name, tReponses, -1,  currentQuestion.numbering, tWords.length, this.data.styleCSSInp);
  }                  


/* ***************************************
*
* *** */
prepareData(){
    var currentQuestion = this.question;

    this.data.words =  currentQuestion.answers[0].proposition.split(",");   
    this.data.reponses =  currentQuestion.answers[1].proposition.split(",");   
    this.data.points =  currentQuestion.answers[1].points.split(",");   
    this.data.keyPoints = conbineArrayKeys(this.data.reponses, this.data.points);  
     
    
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
     var score = {min:0, max:0};


     var tPropos = this.data.reponses;
     var tPoints = padArray(this.data.points, tPropos.length);

      for (var i = 0; i < tPropos.length; i++) {
          if (tPoints[i]>0) this.scoreMaxiBP += parseInt(tPoints[i])*1;
          if (tPoints[i]<0) this.scoreMiniBP += parseInt(tPoints[i])*1;
      }

     return true;
}
//---------------------------------------------------

//---------------------------------------------
//calcul le nombre de points obtenus d'une question/slide
//---------------------------------------------------
getScoreByProposition (answerContainer){
var points = 0;
    var currentQuestion = this.question;

    var keyPoints = this.data.keyPoints;
    var obs = getObjectsByName(this.getName(currentQuestion), "input", "checkbox", "checked");

    obs.forEach((obInput, index) => {
        points += keyPoints[obInput.getAttribute('caption')]*1;
    });
    
    return points*1;
}

  

//---------------------------------------------------
  isInputOk (myQuestions, answerContainer,chrono){
    var obs = getObjectsByName(this.getName(currentQuestion), "input", "checkbox", "checked");
    return (obs.length > 0) ? true : false ;
 }

//---------------------------------------------------
getAllReponses2 (){
    var currentQuestion = this.question;
    
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;

    var tReponses = [];
     for (var i = 0; i < tPropos.length; i++) {
        tReponses.push (`${tPropos[i]} ===> ${tPoints[i]} points`) ;
     }

    return tReponses.join("<br>");
}

getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];
     

     for (var i = 0; i < tPropos.length; i++) {
        //tpl1 = tpl.replace("{word}",).replace("{sep}","===>").replace("{points}",);
        tReponses.push ([[tPropos[i]], [tPoints[i]]]) ;
        //tReponses.push (`${tPropos[i]} ===> ${tPoints[i]} points`) ;
     }

    return formatArray0(tReponses, "=>");
}

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 

/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var keyPoints = this.data.keyPoints;
  
    var obs = getObjectsByName(this.getName(), "input", "checkbox");
    obs.forEach((obInput, index) => {
        obInput.checked = (keyPoints[obInput.getAttribute('caption')]*1 > 0) ? true : false;
    });
  } 
 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var keyPoints = this.data.keyPoints;
  
    var obs = getObjectsByName(this.getName(), "input", "checkbox");
    obs.forEach((obInput, index) => {
        obInput.checked = (keyPoints[obInput.getAttribute('caption')]*1 <= 0) ? true : false;
    });
  } 
 
} // ----- fin de la classe ------

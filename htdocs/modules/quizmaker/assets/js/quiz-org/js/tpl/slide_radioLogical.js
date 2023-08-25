

 /*******************************************************************
  *                     _radioLogical
  * *****************************************************************/

class radioLogical extends quizPrototype{
name = "radioLogical";  
  
/* ***************************************
*
* *** */
build (){
    var currentQuestion = this.question;
    return this.getInnerHTML();
}

/* ***************************************
*
* *** */
getInnerHTML(){
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();

    
    const answers = [];

    var imageMain = this.getInnerHTML_img();    
    this.data.styleCSSTxt = getMarginStyle(this.data.words.length, 1); //line-height: 1;
    var obListText = getHtmlSpan(name, this.data.words, currentQuestion.numbering, 0, this.data.styleCSSTxt);
    answers.push(`<table width="500px" class="question"><tr>${imageMain}
                  <td id="${name}-famille" style='text-align:left'>${obListText}`);

    this.data.styleCSSRadio = getMarginStyle(this.data.reponses.length);    
    var obListRadio = getHtmlRadio(name, this.data.reponses, -1, currentQuestion.numbering, this.data.words.length, this.data.styleCSSRadio);
    answers.push(`</td><td id="${name}-cartes" style='text-align:left'>${obListRadio}</td></tr></table>`);
    
    this.focusId = name + "-" + "0";
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
    this.data.styleCSSTxt = getMarginStyle(this.data.words.length, 1); //line-height: 1;
    this.data.styleCSSRadio = getMarginStyle(this.data.reponses.length);    

    var id = this.getId(0);
    var name = this.getName();
    
    var obTd = document.getElementById(`${name}-famille`);
    var tWords = this.shuffleArray(this.data.words);
    obTd.innerHTML = getHtmlSpan(name, tWords,  currentQuestion.numbering, 0, this.data.styleCSSTxt);

    var obTd = document.getElementById(`${name}-cartes`);
    var tReponses = this.shuffleArray(this.data.reponses);
    obTd.innerHTML = getHtmlRadio(name, tReponses, -1,  currentQuestion.numbering, tWords.length, this.data.styleCSSRadio);
  }                  

//---------------------------------------------------
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

     return score;
}

//---------------------------------------------

//calcul le nombre de points obtenus d'une question/slide
//---------------------------------------------------
getScoreByProposition (answerContainer){
    var currentQuestion = this.question;
var points = 0;

    var keyPoints = this.data.keyPoints;
    var obs = getObjectsByName(this.getName(), "input", "radio", "checked");
    if (obs.length > 0){

        points += keyPoints[obs[0].getAttribute('caption')]*1;
    }   
    return points*1;
}

//---------------------------------------------------
  isInputOk (myQuestions, answerContainer,chrono){
    var obs = getObjectsByName(this.getName(), "input", "radio", "checked");
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
//-------------------------------------------------------
getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];
     

     for (var i = 0; i < tPropos.length; i++) {
        tReponses.push ([[tPropos[i]], [tPoints[i]]]) ;
     }


    return formatArray0(sortArrayArray(tReponses, 1, "DESC"), "=>");
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
    var points = 0;
    var idxOk = -1;
    
    var obs = getObjectsByName(this.getName(), "input", "radio");
    obs.forEach((obInput, index) => {
        if (points < keyPoints[obInput.getAttribute('caption')]*1){
            points = keyPoints[obInput.getAttribute('caption')]*1;
            idxOk = index;
        }
    });
    obs[idxOk].checked = true;
  } 

/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var keyPoints = this.data.keyPoints;
    var points = 0;
    var idxOk = -1;
    
    var obs = getObjectsByName(this.getName(), "input", "radio");
    obs.forEach((obInput, index) => {
        if (points > keyPoints[obInput.getAttribute('caption')]*1){
            points = keyPoints[obInput.getAttribute('caption')]*1;
            idxOk = index;
        }
    });
    obs[idxOk].checked = true;
  } 
 
} // ----- fin de la classe ------


 /*******************************************************************
  *                     _textboxMultiple
  * *****************************************************************/
class textboxMultiple extends quizPrototype{
name='textboxMultiple';  
  
//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    return this.getInnerHTML() ;
 }
  
/* ***************************************
*
* *** */
getInnerHTML (){
    var currentQuestion = this.question;
    var sep = "";
    var id = this.getName;
    var currentQuestion = this.question;
    //var width = Math.floor(600 / this.data.nbInputsMax);        
      
    var html = this.getDisposition(currentQuestion.options.disposition, this.getId('table'), sep);

    html = html.replace ("{image}", this.getImage())
               .replace ("{input}", this.getHtmlTextbox(sep));
    return html;



 }
//---------------------------------------------------
getHtmlTextbox(sep){
    var currentQuestion = this.question;
    var tHtml = [];
    var nbInp = 0;
    var width = Math.floor(90 / this.data.nbInputsMax);      
        
    for (var k = 0; k < currentQuestion.answers.length; k++){
        var name = this.getId(k);
        tHtml.push(`<label>${getNumAlpha(k*1,currentQuestion.numbering,0)}${currentQuestion.answers[k].caption}</label><br>`);
        
        var tInp = [];
        nbInp = currentQuestion.answers[k].inputs*1; 
        for (var j=0; j < nbInp; j++){
          tInp.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" style="width:${width}%">`);
        }

        tHtml.push(tInp.join(sep));
        tHtml.push(`<hr>`);
    }
    
    
    return tHtml.join("\n");
}


//---------------------------------------------------
 prepareData(){
    
    var currentQuestion = this.question;
    this.data.subQuestions =[];  
    
    for(var k in currentQuestion.answers){
        var question = [] ;

        question.nbInput = currentQuestion.answers[k].inputs * 1; 
        var tExp = [];
        
        
        var tWords = currentQuestion.answers[k].proposition.split(",");
        var tPoints = currentQuestion.answers[k].points.split(",");
        tPoints = padArray(tPoints, tWords.length);
        var tSanitysed = []; //currentQuestion.answers[k].proposition.toLowerCase().split(",");        
        for (var h = 0 ; h < tWords.length; h++){
            var exp = [];
            exp.word = tWords[h];
            exp.points = tPoints[h];
            
            exp.sanitysed = sanityseTextForComparaison(tWords[h]);
            tSanitysed.push(exp.sanitysed);
            tExp.push(exp);
        }
        question.keyPoints = conbineArrayKeys(tSanitysed, tExp);  

        this.data.subQuestions.push(question); 
        
    }
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;


     for(var k in this.question.answers){
        var tPoints = strToArrayNum(this.question.answers[k].points.toString(),",");
        
        tPoints.sort();
        for (var i = 0; i < currentQuestion.answers[k].inputs * 1; i++) {
           this.scoreMiniBP += tPoints[i]*1;
         }
        tPoints.reverse();
        for (var i = 0; i < currentQuestion.answers[k].inputs * 1; i++) {
           this.scoreMaxiBP += tPoints[i]*1;
         }
      }

     return true;
 }
computeScoresMinMaxInd(){
}
//---------------------------------------------------
getScoreByProposition (answerContainer){
    var points = 0;
    var currentQuestion = this.question;

    var id = this.getId(0);
    var name = this.getName();
    
    for (var k=0; k < currentQuestion.answers.length; k++){
      var tKeyPoints = this.data.subQuestions[k].keyPoints;
      var obs = this.getQuerySelector("input", this.getId(k));
        obs.forEach( (obInput, index) => {
            var key = sanityseTextForComparaison(obInput.value); 
            if(key!='' && key in tKeyPoints) 
                points += tKeyPoints[key].points*1;
        });
    }

      return points;
  }

//---------------------------------------------------
isInputOk (answerContainer){
      // find selected answer
      var currentQuestion = this.question;
      var bolOk = true;

//       const selector = `input[name=question${currentQuestion.chrono}]`;
//       //var obs = answerContainer.querySelectorAll(selector);
//       var obs = this.getQuerySelector("input", this.getName(currentQuestion));
// 
//        //provisoire a vire des que le transfert de minReponses sera effectif partout
//        try {
//            var minReponses = currentQuestion.options.minReponses;
//        }catch(err) {
//            var minReponses = 0;
//        }
//       
//       //alert('nb obs ===> ' + obs.length);
//       //alert('minReponses ===> ' + minReponses  + " | " + currentQuestion.type + " | " + currentQuestion.options.minReponses);
//       
//       obs.forEach( (ob, index) => {
//       //alert(ob.value);
//         var reponse = ob.value.toLowerCase().trim();
//         if (reponse.length < minReponses) bolOk = false;
//       });

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

        var tKeyPoints = sortArrayKey(this.data.subQuestions[k].keyPoints, 'a');
        //var tKeyPoints = this.data.subQuestions[k].keyPoints;
        for(var key in tKeyPoints)
        {
             tGroup.push ({'inputs':tKeyPoints[key].word,'points': tKeyPoints[key].points}) ;
        }
        tReponses.push(tGroup);
    }    
    return formatArray2(tReponses, '=');
}

//---------------------------------------------------
  update(nameId, chrono) {
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
    
    for (var k=0; k < this.data.subQuestions.length; k++){
        var tKeyPoints = shuffleArrayKeys(this.data.subQuestions[k].keyPoints);    
        var tReponses = [];
        for(var key in tKeyPoints)
        {
            //alert(`${key} = ${tKeyPoints[key]}`);
            if (tKeyPoints[key].points > 0) tReponses.push (tKeyPoints[key].word) ;
        }

        var obs = this.getQuerySelector("input", this.getId(k));
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
    
    for (var k=0; k < this.data.subQuestions.length; k++){
        var tKeyPoints = shuffleArrayKeys(this.data.subQuestions[k].keyPoints);    
        var tReponses = [];
        for(var key in tKeyPoints)
        {
            //alert(`${key} = ${tKeyPoints[key]}`);
            if (tKeyPoints[key].points <= 0) tReponses.push (tKeyPoints[key].word) ;
        }

        var obs = this.getQuerySelector("input", this.getId(k));
            obs.forEach( (obInput, index) => {
            obInput.value = (index < tReponses.length) ? tReponses[index] : '';
        });
    }
    return true;
  } 
  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId, varByRef){
var currentQuestion = this.question;
var tpl = "";
// var hr = '<hr class="quiz-style-two">';
// var divAnswer = `<div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>{answer}</div>`
// var divDirective = '<span class="alphaSimple_directive">{directive}</span>';
//alert(disposition);
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
    
    if(!currentQuestion.image){
        switch(disposition)     { 
        case 'disposition-10' : disposition = 'disposition-00'; break;
        case 'disposition-11' : disposition = 'disposition-01'; break;
        case 'disposition-20' : disposition = 'disposition-00'; break;
        case 'disposition-21' : disposition = 'disposition-01'; break;
        }       
    }
    
    switch(disposition)     {
    default:
    case 'disposition-00':    
        tpl = `{input}`;
        varByRef = '&nbsp;';
        break;
    case 'disposition-01':    
        tpl = `{input}`;
        varByRef = '<br>';
        break;
    case 'disposition-10':
        tpl = `<center>{image}</center><br><div>{input}`;
        varByRef = '&nbsp;';
        break;
    case 'disposition-11':
        tpl = `<center>{image}</center><br><div>{input}`;
        varByRef = '<br>';
        break;
    case 'disposition-20':
        tpl = `<table><tr><td>{image}</td><td>{input}</td></tr></table>`;
        varByRef = '&nbsp;';
        break;
    case 'disposition-21':
        tpl = `<table><tr><td>{image}</td><td>{input}</td></tr></table>`;
        varByRef = '<br>';
        break;
    }
    return tpl;
}
 
} // ----- fin de la classe ------


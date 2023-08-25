

 /*******************************************************************
  *                     _textareaInput
  * *****************************************************************/

class textareaInput extends quizPrototype{
name = "textareaInput";  
 

/* ***************************************
*
* *** */
build (){
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName();
    //var questionNumber = this.question.questionNumber;
    var chrono = this.chrono;
    
    if(currentQuestion.options.orientation == "H"){
var tpl0 = "<table class='question'><tr><td width='60%'>{textbox}</td><td style='padding-left:15px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox";    
    }else{
var tpl0 = "<table class='question'><tr><td>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox-medium";    
    }

    
    var textbox = `<div id="${id}" name="${name}-textboxarea" class="${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;
        
    var tHtml = [];
    var tWordsA = this.data.words;
    for (var k in tWordsA) {
//alert(name);
        tHtml.push(
          `<div style='margin:5px;'>
            ${k*1+1} : <input type="text"  id="${name}-${k}" name="${name}" value="" class="slide-proposition2" oninput="quiz_textareaInput_event('update','${id}','${name}',${chrono});">
          </div>`);
    }

    tpl0 = tpl0.replace("{textbox}", textbox);
    //tpl0 = tpl0.replace("{btnReload}", btnreload);
    tpl0 = tpl0.replace("{listbox}", tHtml.join("\n"));
    return tpl0;
}

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data = transformTextWithToken(currentQuestion.answers[0].proposition);
    this.data.points = padStr2Array(currentQuestion.answers[0].points, this.data.words.length);
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
//     var tPoints = padStr2Array(currentQuestion.answers[0].points, this.data.words.length);
// 
//      this.data.points.forEach((v, index) => {
//          this.scoreMaxiBP +=  parseInt(tPoints[index])*1;
//      });
     this.scoreMaxiBP =  this.data.words.length * currentQuestion.options.scoreByWord;
     this.scoreMiniBP =  0;

    return true;   //  var score = {min:0, max:0};
}


//---------------------------------------------------
getScoreByProposition (answerContainer){
var points = 0;
    var currentQuestion = this.question;

    var obs = getObjectsByName(this.getName(), "input");
    var tWords = this.data.words;
    var nbWordsFound = 0;
    
    obs.forEach((obInput, index) => {
         if (obInput.value.toLowerCase()  == tWords[index].toLowerCase())
             //points +=  parseInt(this.data.points[index])*1;
             nbWordsFound++;
    });
    return nbWordsFound * currentQuestion.options.scoreByWord;

}

//---------------------------------------------------
 isInputOk (answerContainer){
var bolOk = true;
var rep = 0;
    var currentQuestion = this.question;

    var obs = getObjectsByName(this.getName(), "input");
    obs.forEach( (obInput, index) => {
        if (obInput.value != "")
            rep++;
    });

    bolOk = (rep >= currentQuestion.options.minReponses);
    return bolOk;


 }


//---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName() + '-antiseche';
    //var questionNumber = this.question.questionNumber;
    var chrono = this.chrono;
    
    if(currentQuestion.options.orientation == "H"){
var tpl0 = "<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox1";    
    }else{
var tpl0 = "<table class='question'><tr><td>{textbox}</td></tr><tr><td>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox2";    
    }

    
    var textbox = `<label>
        <div id="${id}" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
        </label>`;
        
    var tHtml = [];
    var tWordsA = this.data.words;
    for (var k in tWordsA) {
        tHtml.push(
          `
            ${k*1+1} : <input type="text"  id="${name}-${k}" name="${name}" value="${tWordsA[k]}" class="slide-proposition2" oninput="quiz_textareaInput_event('update','${id}','${name}',${chrono});" disabled>
            <br>`);
    }

    tpl0 = tpl0.replace("{textbox}", textbox);
    //tpl0 = tpl0.replace("{btnReload}", btnreload);
    tpl0 = tpl0.replace("{listbox}", tHtml.join("\n"));
    return tpl0;
 }


//---------------------------------------------------


/* ************************************
*
* **** */

 reloadQuestion()
  {
  
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    
    var obText = document.getElementById(id);
    //obText.value = this.data.textOk;
    obText.innerHTML = this.data.text;

    var obs = getObjectsByName(this.getName(), "input");
    obs.forEach( (obInput, index) => {
        obInput.value = "";
    });
    
    return true;
  
  } 

/* ************************************
*
* **** */

 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    
    var obText = document.getElementById(id);
    //obText.value = this.data.textOk;
    obText.innerHTML = this.data.textOk;
    
    var obs = getObjectsByName(this.getName(), "input");
    obs.forEach( (obInput, index) => {
        obInput.value = this.data.words[index];
    });
    
    return true;
  
  } 
  
/* ************************************
*
* **** */

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    
    var obText = document.getElementById(id);
    //obText.value = this.data.textOk;
    //obText.innerHTML = this.data.textOk;
    var exp = this.data.text;
    
    var tWords = shuffleNewArray(this.data.words);
    
    var obs = getObjectsByName(this.getName(), "input");
    obs.forEach( (obInput, index) => {
        obInput.value = tWords[index];
        exp = exp.replaceAll("{"+(index*1+1)+"}", obInput.value);
    });
    obText.innerHTML = exp;
    
    return true;
  
  } 
  
 
} // ----- fin de la classe ------

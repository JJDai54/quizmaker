

 /*******************************************************************
  *                     _textareaInput
  * *****************************************************************/

class textareaInput extends quizPrototype{
name = "textareaInput";  
  
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
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName();
    var questionNumber = this.question.questionNumber;
    
    if(currentQuestion.options == "H"){
var tpl0 = "<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox1";    
    }else{
var tpl0 = "<table class='question'><tr><td>{textbox}</td></tr><tr><td>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox2";    
    }

    
    var textbox = `<label>
        <div id="${id}" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>
        </label>`;
        
    var tHtml = [];
    var tWordsA = this.data.words;
    for (var k in tWordsA) {
        tHtml.push(
          `<label>
            ${k*1+1} : <input type="text"  id="${name}-${k}" name="${name}" value="" class="slide-proposition2" oninput="quiz_textareaInput_event('update','${id}','${name}',${questionNumber});">
          </label>`);
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
    this.data.points = padStr2Array(currentQuestion.answers[0].points, this.data.words.length)
}
//---------------------------------------------------
computeScoresMinMax(){
    var currentQuestion = this.question;
    var tPoints = padStr2Array(currentQuestion.answers[0].points, this.data.words.length)

     this.data.points.forEach((v, index) => {
         this.scoreMaxi +=  parseInt(tPoints[index])*1;
     });

    return true;   //  var score = {min:0, max:0};
}


//---------------------------------------------------
getScore (answerContainer){
var points = 0;
    var currentQuestion = this.question;

    var obs = getObjectsByName(this.getName(), "input");
    var tWords = this.data.words;

    obs.forEach((obInput, index) => {
        if (obInput.value.toLowerCase()  == tWords[index].toLowerCase())
            points +=  parseInt(this.data.points[index])*1;
    });
    return points;

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

    bolOk = (rep >= currentQuestion.minReponse);
    return bolOk;


 }


//---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    const answers = [];
    var id = this.getId(0);
    var name = this.getName();
    var questionNumber = this.question.questionNumber;
    
    if(currentQuestion.options == "H"){
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
            ${k*1+1} : <input type="text"  id="${name}-${k}" name="${name}" value="${tWordsA[k]}" class="slide-proposition2" oninput="quiz_textareaInput_event('update','${id}','${name}',${questionNumber});" disabled>
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

 showAntiSeche()
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
  
 
} // ----- fin de la classe ------

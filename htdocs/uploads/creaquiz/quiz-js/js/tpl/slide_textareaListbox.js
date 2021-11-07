

 /*******************************************************************
  *                     _textareaListbox
  * *****************************************************************/

class textareaListbox extends quizPrototype{
name = "textareaListbox";  
  

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

    var currentQuestion = this.question;
    var questionNumber = this.question.questionNumber;
    var id = this.getId(0);
    var name = this.getName();

    if(currentQuestion.options == "H"){
var tpl0 = "<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'>{listbox}</td></tr></table>";
var textboxClass = "quiz-shadowbox1";    
    }else{
var tpl0 = "<table class='question'><tr><td colspan=2>{textbox}</td></tr><tr><td>{listbox}</table>";
var textboxClass = "quiz-shadowbox2";    
    }

   
 
//------------------------------------------------------------------


    var textbox = `<label>
        <div id="${id}" name="${name}" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>
        </label>`;
        
    var tHtml = [];
    var tWordsA = this.data.words;
//alert("vertical = " + tWordsA.length)    
    for (var k=0; k < tWordsA.length; k++) {
        var tAllWords = shuffleNewArray(this.data.allWords);

        var h=k*1+1;
        var onclick = `onclick="quiz_textareaListbox_event('update','${id}','${name}',${questionNumber});"`;
        var obList = getHtmlCombobox(name, `${name}{${k}`, tAllWords, onclick, false);
        tHtml.push(`${k*1+1} : ${obList}`);        
    }

    tpl0 = tpl0.replace("{textbox}", textbox);
    //tpl0 = tpl0.replace("{btnReload}", btnreload);
    tpl0 = tpl0.replace("{listbox}", tHtml.join("<br>\n"));
    return tpl0;


}

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data = transformTextWithToken(currentQuestion.answers[0].proposition);
    this.data.points = padStr2Array(currentQuestion.answers[0].points, this.data.words.length)
    
    this.data.allWords = completearrWithwordList(this.data.words, currentQuestion.answers[1].proposition);
    for (var h=0; h<this.data.allWords.length; h++){
        this.data.allWords[h] = this.toProperName(this.data.allWords[h]);
    }
}
//---------------------------------------------------
toProperName(name){
    return name.charAt(0).toUpperCase() + name.substring(1).toLowerCase();
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

/* ***************************************
*
* *** */
getScore (answerContainer){
    var currentQuestion = this.question;
    var points = 0;
    var obs = getObjectsByName(this.getName(), "select");

    var tWords = this.data.words;
    var tPoints =this.data.points;

    obs.forEach((obInput, index) => {
        if (obInput.value.toLowerCase()  == tWords[index].toLowerCase() ){
            points +=  parseInt(tPoints[index])*1;
        }
    });
    return points;

}


//---------------------------------------------------
isInputOk (answerContainer){
var bolOk = true;
var rep = 0;
    var currentQuestion = this.question;
    var obs = getObjectsByName(this.getName(currentQuestion), "select");

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


/* ************************************
*
* **** */
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
  
/* ************************************
*
* **** */

 reloadQuestion(currentQuestion, quizSlides)
  {
    var currentQuestion = this.question;
    var id = this.getId(0);

//------------------------------------------------------------------
    var obText = document.getElementById(id);
    obText.innerHTML = currentQuestion.answers[0].texte;

    clearfillCollection(this.getName);
    return true;
  
  } 
/* ************************************
*
* **** */
 reloadQuestion(currentQuestion, quizSlides){
    var currentQuestion = this.question;

    var id = this.getId(0);
    var name = this.getName();
    var obText = document.getElementById(id);
    obText.innerHTML = this.data.text;

    var obs = getObjectsByName(name, "select");    
    for (var i=0; i < obs.length; i++) {
        obs[i].selectedIndex=-1;
    }

    return true;
 }
 
/* ************************************
*
* **** */
 showAntiSeche(currentQuestion, quizSlides)//, answerContainer
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    var word = "";
 
    //var exp = currentQuestion.answers[0].proposition;
    //var nbRows = exp.split("\n").length;

    var obs = getObjectsByName(name, "select");
    
    var tWordsA = this.data.words;
    for (var i in tWordsA) {

        obs[i].value = this.toProperName(tWordsA[i]);
    }

    var obText = document.getElementById(id);
    obText.innerHTML = this.data.textOk;
    return true;
  
  } 
  
 
} // ----- fin de la classe ------

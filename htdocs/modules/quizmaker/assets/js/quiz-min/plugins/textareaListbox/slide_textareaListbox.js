

 /*******************************************************************
  *                     _textareaListbox
  * *****************************************************************/

class textareaListbox extends quizPrototype{
name = "textareaListbox";  

build (){
    this.boolDog = false;
    return  this.getInnerHTML()
 }

  
/* ***************************************
*
* *** */
getInnerHTML (){
console.log("getInnerHTML");
    var currentQuestion = this.question;
    var id = this.getId('text');
    var name = this.getName();
    var idDivList = this.getId("list");
    
    if(currentQuestion.options.orientation == "H"){
      var tpl0 = `<table class='question'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;'><div id='${idDivList}'>{listbox}</div></td></tr></table>`;
      var textboxClass = "quiz-shadowbox";    
    }else{
      var tpl0 = `<table class='question'><tr><td>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${idDivList}'>{listbox}</div></table>`;
      var textboxClass = "quiz-shadowbox-medium";    
    }
console.log("zzzzzzzzzz");

   
 
//------------------------------------------------------------------


    var textbox = `<div id="${id}" name="${name}" class="quiz-shadowbox-medium ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;
        
    var tHtml = [];
    var tWordsA = this.data.words;
    for (var k=0; k < tWordsA.length; k++) {
        var tAllWords = shuffleNewArray(this.data.allWords);

        var h=k*1+1;
        var onclick = `onchange="return quiz_textareaListbox_event(event,'update','${id}', '${idDivList}', ${this.chrono});" style="margin-bottom:2px"`;
        var obList = getHtmlCombobox(this.getId('lb'), `${name}-${k}`, tAllWords, onclick, false);
        tHtml.push(`${k*1+1} : ${obList}`);        
    }

    tpl0 = tpl0.replace("{textbox}", textbox);
    tpl0 = tpl0.replace("{listbox}", tHtml.join("<br>\n"));
    return tpl0;


}
  

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    //alert(currentQuestion.options.text);
    // renvois un tableau avec les token et la liste des mots et le texte transformé
    // .text   - texte avec token  : {1}{2}{3} ...
    // .words  - Tableau des mots entre accolades
    // .textOk - texte sans les accollades
    this.data = transformTextWithToken(currentQuestion.options.text, currentQuestion.options.tokenColor);

    //constitution du tableau des points
    //this.data.points = padStr2Array(currentQuestion.answers[0].points, this.data.words.length);
    
    var tItems = [];    
    var allWords = [];
    for (var h = 0; h < this.data.words.length; h++){
        tItems[this.data.words[h].toLowerCase()] = currentQuestion.options.scoreByGoodWord; 
        allWords.push(this.data.words[h]);

    }
 //alert("prepareData : nb words = " + this.data.words);   
    //ajout des mauvais mots
    for (var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        tItems[ans.proposition.toLowerCase()] = currentQuestion.options.scoreByBadWord; 
        allWords.push(ans.proposition.toLowerCase());
    }


    this.data.items = tItems;
    this.data.allWords = allWords;//.join('');
    
    return true    
    
}


/* ***************************************
*
* *** */
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;

     this.scoreMaxiBP =  this.data.words.length * currentQuestion.options.scoreByGoodWord;
     this.scoreMiniBP =  this.data.words.length * currentQuestion.options.scoreByBadWord;

    return true;  
}

/* ***************************************
*
* *** */
getScoreByProposition (answerContainer){
    var currentQuestion = this.question;
    var points = 0;
    var obs = this.getListSelect();    

    var tWords  = this.data.words;
    var tPoints = this.data.points;
    obs.forEach((obInput, index) => {
        if (obInput.value.toLowerCase()  == this.data.words[index].toLowerCase() ){
            points +=  currentQuestion.options.scoreByGoodWord*1;
        }else if(obInput.value){
            points +=  currentQuestion.options.scoreByBadWord*1;
        }
    });
    return points;
}

/* ***************************************
*
* *** */
isInputOk (answerContainer){
var bolOk = true;
var rep = 0;
    var currentQuestion = this.question;
    var obs = this.getListSelect();    

    obs.forEach( (obInput, index) => {
        if (obInput.value != "")
            rep++;
    });

    bolOk = true; //(rep >= currentQuestion.options.minReponses);
    return bolOk;
 }

//---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;
    const answers = [];
    var id = this.getId('text');
    var name = this.getName();
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
        tHtml.push(`${k*1+1} : <input type="text"  id="${name}-${k}" name="${name}" value="${tWordsA[k]}" class="slide-proposition2" oninput="quiz_textareaInput_event('update','${id}','${name}',${chrono});" disabled><br>`);
    }

    tpl0 = tpl0.replace("{textbox}", textbox);
    tpl0 = tpl0.replace("{listbox}", tHtml.join("\n"));
    return tpl0;
 }


/* ************************************
* recherche la list des listbox
* **** */
getListSelect(){
    return document.querySelectorAll(`#${this.getId("list")}` + " select");    
}
 
/* ************************************
*
* **** */
 showGoodAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
  console.log("showGoodAnswers");

    var currentQuestion = this.question;
    var id = this.getId('text');
    var name = this.getName();
    var word = "";
    var obs = this.getListSelect();    
   
    var tWordsA = this.data.words;
    for (var i in tWordsA) {
        obs[i].value = tWordsA[i];
    }

    var obText = document.getElementById(id);
    obText.innerHTML = this.data.textOk;
    return true;
  } 
  
/* ************************************
*
* **** */
 showBadAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    var currentQuestion = this.question;
    var id = this.getId('text');
    var name = this.getName();
    var word = "";
    var obText = document.getElementById(id);
    var exp = this.data.text;

    var obs = this.getListSelect();    
    var tWordsA = this.data.allWords;
    obs.forEach( (obInput, index) => {
        var idx = getRandomIntInclusive(0, this.data.allWords.length-1);
        obInput.value = this.data.allWords[idx];
        exp = exp.replaceAll("{"+(index*1+1)+"}", obs[index].value);
    });

    //-----------------------------------------------------
    obText.innerHTML = exp;
    return true;
  }
//-----------------------------------------------
  
 
} // ----- fin de la classe ------

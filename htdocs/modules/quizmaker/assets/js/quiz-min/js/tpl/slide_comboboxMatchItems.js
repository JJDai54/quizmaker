 

 /*******************************************************************
  *                     _comboboxMatchItems
  * *****************************************************************/
class comboboxMatchItems extends quizPrototype{
name = 'comboboxMatchItems';  

//---------------------------------------------------
build (){
    this.boolDog = false;
    var currentQuestion = this.question;

      const html = [];
      html.push(`<div id="${this.getName()}">`);
      
      html.push(this.getHTML()) ;
    
      html.push(`</div>`);
      return html.join("\n");

 }
//-----------------------------------------------------------
getHTML (){
const answers = [];

    var currentQuestion = this.question;
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    
    var newKeys = this.shuffleArray(this.data.keys);
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    this.data.styleCSS = getMarginStyle(newKeys.length, 2, 'text-align:right;width:10%;');
     
    answers.push(`<table class="question">`);
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        var textId = textName + "-" + k;
        var listId = listName + "-" + k;
        //alert(key + "-" + tKeyWords[key].match);

        answers.push(`<tr>`);
        answers.push(`<td ${this.data.styleCSS}>${getNumAlpha(k,currentQuestion.numbering)} : </td>
                     <td width='30%'><input type="text" id="${textId}"  name="${textName}" value="${newKeys[k]}" class="question-comboboxMatchItems" disabled>
                     </td>`);       
        

         var tItems1 = shuffleNewArray(this.data.words );
         //alert(tWords.length + " <-> " + tItems1.length);
         var obList = getHtmlCombobox(listName, listId, tItems1);
        answers.push(`<td style="text-align:left;width:30%;">${obList}</td>`);
        answers.push(`</tr>`);
    }
    answers.push(`</table>`);
    
   
    //return "en construction";
    return answers.join("\n");

    
 }

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    
    var tWords = [];
    var tKeyWords = [];
    var tKeys = [];

    for(var k=0; k < currentQuestion.answers.length; k++){
        var tCouple = currentQuestion.answers[k].proposition.split(","); 
        //alert(tCouple[0] + " = " + tCouple[1]);
        tKeyWords[tCouple[0]] = {key : tCouple[0], match : tCouple[1], points : currentQuestion.answers[k].points};

        tWords.push(tCouple[1]);
        tKeys.push(tCouple[0])
    }
    
    this.data.words = tWords;
    this.data.kitems = tKeyWords;
    this.data.keys = tKeys;
    
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
     
    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        this.scoreMaxiBP += tKeyWords[key].points*1;
     }
     return true;
}

/* ************************************
*
* **** */
getScoreByProposition (answerContainer){
//alert("getScore");
var points = 0;
var key ='';
var match = '';

    var currentQuestion = this.question;
    var newKeys = this.data.keys;
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
    
    var tKeyWords = this.data.kitems;
    
    obTexts.forEach((obInput, index) => {
        key = obInput.value;
        match = obLists[index].value;
    this.blob("getScoreByProposition : " + match + " - " + tKeyWords[key].match);        
        if (match == tKeyWords[key].match)
            points +=  tKeyWords[key].points*1;
    
    });

      return points;

  }


/* ************************************
*
* **** */
isInputOk (answerContainer){
var rep = 0;
    var currentQuestion = this.question;
    var name = this.getName();
    var listName = `${name}-list`;

    var selector = `select[name=${listName}]`;
    var obLists = document.querySelectorAll(selector);
    
    obLists.forEach((obList, index) => {
        
        if (obList.value != "") rep++; 
    
    });

   //provisoir a vire des que le transfert de minReponses sera effectif partout
   try {
       var minReponses = currentQuestion.options.minReponses;
   }catch(err) {
       var minReponses = 0;
   }

    var bolOk = (rep >= minReponses);
    return bolOk;
 }



// //---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push([[tKeyWords[key].key], [tKeyWords[key].match], [tKeyWords[key].points]]);
     }

    return formatArray0(tReponses, "=>");
 }




//---------------------------------------------------
getGoodReponses (){
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push(`${tKeyWords[key].key} => ${tKeyWords[key].match} => ${tKeyWords[key].points}`);
     }

    return tReponses.join("<br>");
 }

/* ************************************
*
* **** */
reloadQuestion(quizDivAllSlides) {
    var obDiv = document.getElementById(this.getName());
    obDiv.innerHTML = this.getHTML();
}

reloadQuestion_old(quizDivAllSlides) {
    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
   
    var newKeys = this.shuffleArray(this.data.keys);
    
    obTexts.forEach((obInput, index) => {
        obInput.value = newKeys[index];
        obLists[index].selectedIndex=-1; 
    
    });
  } 
  
/* ************************************
*
* **** */
showGoodAnswers(quizDivAllSlides) {

    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
    
    var tKeyWords = this.data.kitems;
    
    obTexts.forEach((obInput, index) => {
    this.blob("showAntiSeche : " + obLists[index].value + " - " + tKeyWords[obInput.value].match);
        obLists[index].value = tKeyWords[obInput.value].match; 
        //var obList = document.getElementById(listName + "-" + index);
        //obList.value = tKeyWords[obInput.value].match;
    
    });
  } 
  
/* ************************************
*
* **** */
showBadAnswers(quizDivAllSlides) {
    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
    
    var tKeyWords = this.data.kitems;
    var tKeys = this.shuffleArray(Object.keys(this.data.kitems));
    
 //alert ("showBadAnswers : " + tKeys.join('-'));   
    obTexts.forEach((obInput, index) => {
    // this.blob("showAntiSeche : " + obInput.value);
    //alert(tKeys[index]);
        obLists[index].value = tKeyWords[tKeys[index]].match ; 
        //var obList = document.getElementById(listName + "-" + index);
        //obList.value = tKeyWords[obInput.value].match;
    
    });
  } 
 
} // ----- fin de la classe ------

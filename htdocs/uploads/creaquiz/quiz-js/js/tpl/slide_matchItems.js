

 /*******************************************************************
  *                     _matchItems
  * *****************************************************************/
class matchItems extends quizPrototype{
  
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
build (){
const answers = [];

    var currentQuestion = this.question;
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    
    var newKeys = shuffleArrayKeys(this.data.keys);
    var listName = `${name}-list`;
    var textName = `${name}-text`;
 
    answers.push(`<table class="question">`);
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        var textId = textName + "-" + k;
        var listId = listName + "-" + k;
        //alert(key + "-" + tKeyWords[key].match);

        answers.push(`<tr>`);
        answers.push(`<td style="text-align:right;">${getNumAlpha(k,quiz.numerotation)} : </td>
                     <td><input type="text" id="${textId}"  name="${textName}" value="${newKeys[k]}" class="question-matchItems" disabled>
                     </td>`);       
        

         var tItems1 = shuffleNewArray(this.data.words );
         //alert(tWords.length + " <-> " + tItems1.length);
         var obList = getHtmlCombobox(listName, listId, tItems1);
        answers.push(`<td>${obList}</td>`);
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
        //alert(t[0] + " = " + t[1]);
        tKeyWords[tCouple[0]] = {key : tCouple[0], match : tCouple[1], points : currentQuestion.answers[k].points};

        tWords.push(tCouple[1]);
        tKeys.push(tCouple[0])
    }
    
    this.data.words = tWords;
    this.data.keyWords = tKeyWords;
    this.data.keys = tKeys;
    
}
//---------------------------------------------------
computeScoresMinMax(){
    var currentQuestion = this.question;
     
    var newKeys = this.data.keys;     
    var tKeyWords = this.data.keyWords;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        this.scoreMaxi += tKeyWords[key].points*1;
     }
     return true;
}

/* ************************************
*
* **** */
getScore (answerContainer){
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
    
    var tKeyWords = this.data.keyWords;
    
    obTexts.forEach((obInput, index) => {
        key = obInput.value;
        match = obLists[index].value;
        
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

    var bolOk = (rep >= currentQuestion.minReponse);
    return bolOk;
 }



// //---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.keyWords;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push([[tKeyWords[key].key], [tKeyWords[key].match], [tKeyWords[key].points]]);
     }

    return formatArray0(tReponses, "=>");
 }




//---------------------------------------------------
// getGoodReponses (){
//     var currentQuestion = this.question;
//      var tReponses = [];
// 
//     var newKeys = this.data.keys;     
//     var tKeyWords = this.data.keyWords;     
//      
//     for(var k = 0; k< newKeys.length; k++){
//         var key = newKeys[k];
//         tReponses.push(`${tKeyWords[key].key} => ${tKeyWords[key].match} => ${tKeyWords[key].points}`);
//      }
// 
//     return tReponses.join("<br>");
//  }

/* ************************************
*
* **** */
reloadQuestion(quizSlides) {
    var currentQuestion = this.question;
    var newKeys = shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
     var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
   
    var newKeys = shuffleArray(this.data.keys);
    
    obTexts.forEach((obInput, index) => {
        obInput.value = newKeys[index];
        obLists[index].selectedIndex=-1; 
    
    });
  } 
  
/* ************************************
*
* **** */
showAntiSeche(quizSlides) {
    var currentQuestion = this.question;
    var newKeys = shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obLists = getObjectsByName(listName, "select", "");
    
    var tKeyWords = this.data.keyWords;
    
    obTexts.forEach((obInput, index) => {
    console.log("showAntiSeche : " + obInput.value);
        obLists[index].value = tKeyWords[obInput.value].match; 
        //var obList = document.getElementById(listName + "-" + index);
        //obList.value = tKeyWords[obInput.value].match;
    
    });
  } 
 
} // ----- fin de la classe ------

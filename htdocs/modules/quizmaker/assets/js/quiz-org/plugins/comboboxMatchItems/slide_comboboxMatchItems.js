 

 /*******************************************************************
  *                     _comboboxMatchItems
  * *****************************************************************/
class comboboxMatchItems extends quizPrototype{
name = 'comboboxMatchItems';  

//---------------------------------------------------
build (){
    this.boolDog = false;
    return this.getInnerHTML() ;
 }
//-----------------------------------------------------------
getInnerHTML (){
const htmlArr = [];

    var currentQuestion = this.question;
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    
    var newKeys = this.shuffleArray(this.data.keys);
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    this.data.styleCSS = getMarginStyle(newKeys.length, 2);
     
    if(currentQuestion.options.disposition == 'disposition-02'){
        var tpl=`<td><span>{numbering}</span></td>
                 <td right>{obList}</td>
                 <td left><input type="text" id="{textId}" left name="${textName}" value="{mot1}" disabled></td>`;      
                 var attAllignement = "right"; 
    }else{
        var tpl=`<td><span>{numbering}</span></td>
                 <td right><input type="text" id="{textId}" right name="${textName}" value="{mot1}" disabled></td>       
                 <td left>{obList}</td>`;
                 var attAllignement = "left"; 
    }
    
    var tItems1 = shuffleNewArray(this.data.words );
    if(currentQuestion.options.intrus){
       var tIntrus = splitAllSep(currentQuestion.options.intrus);
       for(var $h = 0; $h < tIntrus.length; $h++){
            tItems1.push(tIntrus[$h]);
       }
    }
    
    htmlArr.push(this.getImage());
    
    htmlArr.push(`<center><table>`);
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        var textId = textName + "-" + k;
        var listId = listName + "-" + k;
        
        htmlArr.push(`<tr>`);
        var htmlTD = tpl.replace("{numbering}", getNumAlpha(k,currentQuestion.numbering))
                        .replace("{mot1}", newKeys[k])
                        .replace("{obList}", getHtmlCombobox(listName, listId, tItems1, attAllignement));
        
        
        //alert(key + "-" + tKeyWords[key].match);

        htmlArr.push(htmlTD);
        htmlArr.push(`</tr>`);
    }
    htmlArr.push(`</table></center>`);
    
   
    //return "en construction";
    return htmlArr.join("\n");

    
 }

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    
    var tWords = [];
    var tKeyWords = [];
    var tKeys = [];

    for(var k=0; k < currentQuestion.answers.length; k++){
        var tCouple = currentQuestion.answers[k].proposition.split(","); 
        if(currentQuestion.options.disposition == 'disposition-02') {tCouple.reverse()};
                
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
    
    var obTexts = this.getQuerySelector("input", textName, "text");
    var obLists = this.getQuerySelector("select", listName, "");
    
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
showGoodAnswers(quizDivAllSlides) {

    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    
    var obAllInp = this.getQuerySelector("input", textName, "text");
    var obLists = this.getQuerySelector("select", listName, "");
    
    var tKeyWords = this.data.kitems;
    
    obAllInp.forEach((obInput, index) => {
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
    var name = this.getName();
    var listName = `${name}-list`;
    
    var obLists = this.getQuerySelector("select", listName, "");
    
    var tKeyWords = this.data.kitems;
    
    var tItems1 = this.data.words;
    var tKeys = shuffleArray(Object.keys(this.data.kitems));
    if(currentQuestion.options.intrus){
       var tIntrus = splitAllSep(currentQuestion.options.intrus);
       for(var $h = 0; $h < tIntrus.length; $h++){
            tItems1.push(tIntrus[$h]);
       }
    }
    
   
    obLists.forEach((obInput, index) => {
        var exp = tItems1[rnd(0,tItems1.length-1)];
        obLists[index].value = exp; 
        //console.log(index + "===>" + exp); 
    });
  } 
 
} // ----- fin de la classe ------

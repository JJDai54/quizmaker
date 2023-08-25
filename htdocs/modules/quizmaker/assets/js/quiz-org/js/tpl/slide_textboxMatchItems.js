 

 /*******************************************************************
  *                     _textboxMatchItems
  * *****************************************************************/
class textboxMatchItems extends quizPrototype{
name = 'textboxMatchItems';  

//---------------------------------------------------
build (){
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
    var inpName = `${name}-input`;
    var textName = `${name}-text`;
 
    answers.push(`<table class="question">`);
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        var textId = textName + "-" + k;
        var inptId = inpName + "-" + k;
        //alert(key + "-" + tKeyWords[key].match);

        answers.push(`<tr>`);
        answers.push(`<td style="text-align:right;width:15%;">${getNumAlpha(k,currentQuestion.numbering)} : </td>
                     <td width='40%'><input type="text" id="${textId}"  name="${textName}" value="${newKeys[k]}" class="question-comboboxMatchItems" disabled>
                     </td>`);       
        

  //answers.push(<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>);     
        
        answers.push(`<td style="text-align:left;width:30%;">`);
        answers.push(`<input type="text"  id="${inptId}" name="${inpName}" class="question-comboboxMatchItems question-inputMatchItems" tabindex="${k+1}" value="" >`);           
        answers.push(`</td></tr>`);
    }
    answers.push(`</table>`);
    this.focusId = inpName + "-" + "0";
   
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
        var tCouple = currentQuestion.answers[k].proposition.split("|"); 
        var tRep = tCouple[1].toLowerCase().split(",");
        for(var i=0 ; i < tRep.length; i++) tRep[i] = tRep[i].trim();
        //alert(t[0] + " = " + t[1]);
        tKeyWords[tCouple[0]] = {key : tCouple[0], match : tCouple[1], rep : tRep, points : currentQuestion.answers[k].points};

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
    var inpName = `${name}-input`;    
    var textName = `${name}-text`;
    var obTexts = getObjectsByName(textName, "input", "text");
    var obInps = getObjectsByName(inpName, "input", "text");
//alert(inpName)    ;
    var tKeyWords = this.data.kitems;
    
    obTexts.forEach((obInput, index) => {
        key = obInput.value;
        match = obInps[index].value.toLowerCase();
        
        switch (currentQuestion.options *1 ){
        case 1: 
            if(this.compareAvecAccent(match, tKeyWords[key].rep, ','))
                points +=  tKeyWords[key].points*1;
            break;
        case 2: 
            if(this.compareAvecAccentSouple(match, tKeyWords[key].rep, ','))
                points +=  tKeyWords[key].points*1;
            break;
        case 0: 
        default: 
            if ( tKeyWords[key].rep.indexOf(match.toLowerCase()) >= 0)
                points +=  tKeyWords[key].points*1;
            break;
        }
                
    
    });

    return points;

  }

/* ************************************
*
* **** */
compareAvecAccent(exp, tRep, sep=','){
var bolOk = false;
    var newExp = sanityseTextForComparaison(exp);
    for (var i = 0; i < tRep.length; i++){
        if(newExp == sanityseTextForComparaison(tRep[i])){
            //alert(newExp + "===" + sanityseTextForComparaison(tRep[i]));
            bolOk = true;
            break;
        }
    }
    return bolOk;
}

/* ************************************
*
* **** */
compareAvecAccentSouple(exp, tRep, sep=','){
var bolOk = false;
 
    var tExp = exp.split(" ");
    
    for (var i = 0; i < tRep.length; i++){
        for (var k = 0; k < tExp.length; k++){
            var mot = sanityseTextForComparaison(tExp[k]);
                //alert(mot + "===" + sanityseTextForComparaison(tRep[i]));
            if(mot == sanityseTextForComparaison(tRep[i])){
                bolOk = true;
                break;
            }
            if (bolOk) break;
        }
        
        //alert(newExp + "===" + sanityseTextForComparaison(tRep[i]));
    }
    return bolOk;
}
/* ************************************
*
* **** */
compareAvecAccentSouple_pas_bon(exp, tRep, sep=','){
var bolOk = false;
    var newExp = sanityseTextForComparaison(exp);
    for (var i = 0; i < tRep.length; i++){
        //alert(newExp + "===" + sanityseTextForComparaison(tRep[i]));
        if(sanityseTextForComparaison(tRep[i]).indexOf(newExp) >= 0){
            //alert(newExp + "===" + sanityseTextForComparaison(tRep[i]));
            bolOk = true;
            break;
        }
    }
    return bolOk;
}
/* ************************************
*
* **** */
isInputOk (answerContainer){
var rep = 0;
    var currentQuestion = this.question;
    var name = this.getName();
    var inpName = `${name}-input`;
        
    var selector = `select[name=${inpName}]`;
    var obLists = document.querySelectorAll(selector);
    
    obLists.forEach((obList, index) => {
        
        if (obList.value != "") rep++; 
    
    });

    var bolOk = (rep >= currentQuestion.options.minReponses);
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
//        alert([tKeyWords[key].match]);
        tReponses.push([[tKeyWords[key].key], [tKeyWords[key].match], [tKeyWords[key].points]]);
     }

    return formatArray0(tReponses, "=>");
 }


/* ************************************
*
* **** */
reloadQuestion(quizDivAllSlides) {
    var obDiv = document.getElementById(this.getName());
    obDiv.innerHTML = this.getHTML();
    this.setFocus();
}

/* ************************************
*
* **** */
showGoodAnswers(quizDivAllSlides) {
    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var inpName = `${name}-input`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obInps = getObjectsByName(inpName, "input", "text");
    
    var tKeyWords = this.data.kitems;
   
    
    obTexts.forEach((obInput, index) => {
        var key = obInput.value;
        // Pick a remaining element...
        var rnd = getRandomIntInclusive(0, tKeyWords[key].rep.length-1);
    // this.blob("showAntiSeche : " + obInput.value);
        obInps[index].value = tKeyWords[key].rep[rnd]; 

    });
  } 
  
/* ************************************
*
* **** */
showBadAnswers(quizDivAllSlides) {
    var currentQuestion = this.question;
    var newKeys = this.shuffleArrayKeys(this.data.keys);
    var name = this.getName();
    var inpName = `${name}-input`;
    var textName = `${name}-text`;
    
    var obTexts = getObjectsByName(textName, "input", "text");
    var obInps = getObjectsByName(inpName, "input", "text");
    
    var tKeyWords = this.data.kitems;
   
    
    obTexts.forEach((obInput, index) => {
        var key = obInput.value;
        // Pick a remaining element...
        var rnd = getRandomIntInclusive(0, tKeyWords[key].rep.length-1);
    // this.blob("showAntiSeche : " + obInput.value);
        obInps[index].value = tKeyWords[key].rep[rnd].split("").reverse().join("");; 

    });
  } 
  

} // ----- fin de la classe ------

/*******************************************************************
*                     radioMultiple
* *****************************************************************/
function getPlugin_radioMultiple(question, slideNumber){
    return new radioMultiple(question, slideNumber);
}

 /*******************************************************************
  *                     radioMultiple
  * *****************************************************************/

class radioMultiple extends Plugin_Prototype{
name = "radioMultiple";  
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
    var id = this.getId(0);
    var name = this.getName();

    const htmlArr = [];
    
    if(currentQuestion.image){
        htmlArr.push(this.getImage());
    }
    if(currentQuestion.options.directive){
        htmlArr.push(`<span>${currentQuestion.options.directive}</span>`);
    }
     
    htmlArr.push(`<div id="${this.getName()}">`);
      //var newAns = shuffleArray(currentQuestion.ans2) ;     
      var newAns = currentQuestion.ans2;   
      //alert(currentQuestion.options.orientation)  
      if (currentQuestion.options.orientation == "vertical"){
          htmlArr.push(this.getInnerHTML_vertical (newAns, this.getName())) ;
      }else{
          htmlArr.push(this.getInnerHTML_horizontal (newAns, this.getName())) ;
      }
  
    htmlArr.push(`</div>`);

    this.focusId = name + "-" + "0";      
    return htmlArr.join("\n");

 }


 /*******************************************************************
  *                     getInnerHTML_horizontal
  * *****************************************************************/

getInnerHTML_horizontal (answers, tblId){
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();

      const htmlArr = [];

      htmlArr.push(`<table>`);

     for(var k in answers){
      htmlArr.push(`<tr>`);

        var tWords = shuffleArray(answers[k].words);

        for (var i = 0; i < tWords.length; i++){
          htmlArr.push(
            `<td class='radioMultiple_radio_H'><label>
              <input type="radio" id="${name}-${k}" name="${name}-${k}" value="${tWords[i]}" ${this.data.attributeSens}>
              ${tWords[i]}
            </label></td>`
          );
        }
      htmlArr.push(`</tr>`);
     }

      htmlArr.push(`</table>`);
      return htmlArr.join("\n");
}

 /*******************************************************************
  *                     getInnerHTML_vertical
  * *****************************************************************/
getInnerHTML_vertical (answers, tblId){
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();

      const htmlArr = [];

      const tAns = [];
      for(var k in answers){
        var tWords = shuffleArray(answers[k].words);
        tAns.push(duplicateArray(tWords));
      }
      //----------------------------------------------------------  
      htmlArr.push(`<table>`);
  
      var nbWords = tAns[0].length;  
      for (var i=0; i<nbWords; i++){
        htmlArr.push(`<tr>`);
        
        for(var k in tAns){
          htmlArr.push(
            `<td class='radioMultiple_radio_V'><label>
             <input type="radio" name="${name}-${k}" value="${tAns[k][i]}" ${this.getName()}="${currentQuestion.options.orientation}">
             ${tAns[k][i]}
             </label></td>`);
         }
        htmlArr.push(`</tr>`);
      }
      htmlArr.push(`</table>`);
      return htmlArr.join("\n");
}
//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;

    //force l'option de mélange des options sinon aucun intéret
//    currentQuestion.shuffleAnswers = 1;

    //var currentQuestion = this.question;
    this.transposeArray();
    this.initMinMaxQQ(2)    
}

/* *************************************
* transposition des propositions pour permettre le mélange des options
* czla consiste a faire en sorte que chaque bouton qui correspond a une bonne reponse
* soit dans un groupe de bouton radio différent, sinon impossible de cocher la bonne solution
* ******** */
transposeArray(){
var currentQuestion = this.question;
var ans2 = [];

    for (var k in currentQuestion.answers){
        var tWords = currentQuestion.answers[k].proposition.split(",");
        currentQuestion.answers[k].words = tWords;
    }
    //------------------------------------------------------------
    for (var w in currentQuestion.answers[0].words){
        var tw = [];
        for (var k in currentQuestion.answers){
            tw.push(currentQuestion.answers[k].words[w]);
        }
        ans2.push({id   :this.getId(currentQuestion,w), 
                  name  :this.getName(currentQuestion) ,
                  words :tw});
    }

// verif

/*

    for (var k in ans2){
        // this.blob("---------------------");
        // this.blob(ans2[k].id + " | " + ans2[k].name);
        // this.blob(ans2[k].words.join("-"));
        
    }
*/

    currentQuestion.ans2 = ans2;
} 

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
var maxPoints = 0;
var minPoints = 99999;
var ans = null;

    for  (var k in currentQuestion.answers){
        ans = currentQuestion.answers[k];
        if (maxPoints < ans.points*1){
            maxPoints = ans.points*1;
        }
        if (minPoints > ans.points*1){
            minPoints = ans.points*1;
        }
    }

     this.scoreMaxiBP = maxPoints ;
     this.scoreMiniBP = minPoints;
     return true;
}


 /*******************************************************************
  *                     
  * *****************************************************************/
   getScoreByProposition (answerContainer){
    var currentQuestion = this.question;
      // find selected answer
      //const answerContainer = answerContainers[questionNumber];
var bolOk = true;
var points = 0;
var score;
var selector = ``;
var ansFound = -1;
var nbExp2Found = 0 ; 

    //console.clear();
    //// this.blob(questionNumber + " : " + currentQuestion.question);
    //var obSlide=document.getElementById(this.divMainId);
    //var obs = this.getQuerySelector('radio', ``, 'input', `:checked`);
    
    
    var selector = `input[type=radio]:checked`;
    //var obs = obSlide.querySelectorAll(selector);
    var obs = this.getObDivMain().querySelectorAll(selector);
    if (!obs){return 0;}
    
    var tRep=[];
    obs.forEach((obInput, index) => {
        tRep.push(obInput.value);
    });
    //il faut trier les reponses et les propositions pour pouvoir les comparer
    var rep = tRep.sort().join(",");
    
    console.log(rep);
    for  (var k in currentQuestion.answers){
        if (rep == currentQuestion.answers[k].proposition.split(",").sort().join(",")){
            points = currentQuestion.answers[k].points;
            break;
        } 
    }

      return  points * 1;
 }


//---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;
   // var tReponses = [];
var tRep = [];

var tReponses = [];
    for  (var k in currentQuestion.answers){
        //ne mettre ques les combinaisons qui donnent un nombre de points != 0
        //les combinaisons égalent à 0 peuvent nombreuses et sont inutile pour le résultats
        if ([currentQuestion.answers[k].points*1] > 0){
            if(currentQuestion.answers[k].caption){
                tReponses.push([[currentQuestion.answers[k].caption], [currentQuestion.answers[k].proposition], [currentQuestion.answers[k].points*1]]);
                tReponses = sortArrayArray(tReponses, 2, "DESC");
            }else{
                tReponses.push([[currentQuestion.answers[k].proposition], [currentQuestion.answers[k].points*1]]);
                tReponses = sortArrayArray(tReponses, 1, "DESC");
            }
        }
    }
    return formatArray0(tReponses, "=>");

}

/* *************************************
*
* ******** */
showGoodAnswers(){
var currentQuestion = this.question;
var maxPoints = 0;
var ansFound = -1;
    
    for  (var k in currentQuestion.answers){
        if (maxPoints < currentQuestion.answers[k].points){
            maxPoints = currentQuestion.answers[k].points;
            ansFound = k;
        }
    }

    var tWords = currentQuestion.answers[ansFound].words;
    //recherche des boutons radios du slide
    var obs = this.getQuerySelector(`input[type=radio]`);
     
    obs.forEach((obInput, index) => {
        let exp = obInput.value;
        if (tWords.indexOf(exp) >= 0)  obInput.checked = true;
    });
  } 
 
/* *************************************
*
* ******** */
showBadAnswers(){
var currentQuestion = this.question;
var minPoints = 999;
var ansFound = -1;

    for  (var k in currentQuestion.answers){
        if (minPoints > currentQuestion.answers[k].points*1){
            minPoints = currentQuestion.answers[k].points*1;
            ansFound = k;
        }
    }
    var tWords = currentQuestion.answers[ansFound].words;
    
    var obs = this.getQuerySelector(`input[type=radio]`);
     
    obs.forEach((obInput, index) => {
        let exp = obInput.value;
        if (tWords.indexOf(exp) >= 0)  obInput.checked = true;
    });
  } 
  
} // ----- fin de la classe ------


 /*******************************************************************
  *                     lucioles
  * *****************************************************************/
/*
mettre dans le plugin lucioles les methodes communes et faire hériter les deux autre sur lucioles
*/
class lucioles extends Plugin_Prototype{
name = "lucioles";

/* *************************************
*
* ******** */
buildSlide (bShuffle = true){
    this.boolDog = true;
    return this.getInnerHTML(bShuffle);
    
 }
/* *************************************
*
* ******** */
getInnerHTML(bShuffle = true){
    var  currentQuestion = this.question;
    var options = currentQuestion.options;
    //alert(`urlQuiz=${quiz_config.urlQuiz}\nquiz.url=${quiz.url}`);
    var luciolesId = this.getId('lucioles');
    
    var imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.luciolesIsLoad = false;
    
    var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${luciolesId}'><hr><b>Puzzle</b><hr></div></center>`;
    html += "<div id='mouchard'></div>";
//              + "nbClicks <input type='hidden' value='0'>";
//              + "nbClicks <input type='hidden' value='0'>";

    return html;
}

/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;

    if(!currentQuestion.options.maxAttemps){currentQuestion.options.maxAttemps = 99999;}
    if(currentQuestion.options.maxAttemps == 0){currentQuestion.options.maxAttemps = 99999;}
    
    this.nbPieces = currentQuestion.options.imgRows * currentQuestion.options.imgCols * currentQuestion.options.doublons;

    this.initMinMaxQQ (0);
//    alert(this.scoreMaxiBP );
} 

/* *************************************
*
* ******** */
// computeScoresMinMaxByProposition(){
//     var currentQuestion = this.question;
//     var options = currentQuestion.options;
//     
//     foreach(k in currentQuestion.answers){
//     }
//     this.scoreMaxiBP = options.imgCols * options.imgRows * currentQuestion.answers[0].points;
//     this.scoreMiniBP = 0;
//     
//     return true;
// }

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    var currentQuestion = this.question;
    super.onFinalyse ();
    
    if(this.luciolesIsLoad == false){
        var  currentQuestion = this.question;
        var options = currentQuestion.options;
        
        var obImg = document.getElementById(this.getId(0));
        var imgUrl = obImg.getAttribute('src');
        var luciolesId = this.getId('lucioles');
        
        options.urlPlugin = currentQuestion.urlPlugin;
        options.slideNumber = this.slideNumber;
        build_lucioles(luciolesId, imgUrl, currentQuestion.answers, options, true);
        
        var obMemory = document.getElementById(luciolesId);
        this.luciolesIsLoad = true;
    }
    var tellFrom = 'lucioles.onFinalyse';
    if(currentQuestion.options.preview > 0){
        lucioles_all(this.getId('lucioles'), false);
        updateButton('quiz_btn_nextSlide', 0, 'onFinalyse');
        quiz_show_mask(true, 0.10, true);
        setTimeout(lucioles_preview, currentQuestion.options.preview*1000, this.getId('lucioles'), true, tellFrom);
    }else{
        updateButton('quiz_btn_nextSlide', 1, 'onFinalyse');        
    }
//this.allowNextSlide = options.allowNext == 1;    
//     if(options.allowNext == 0){
//         document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
//     }
    }


//---------------------------------------------------
// computeScoresMinMaxByProposition(){
//     //il n'y a pas de points par proposition, il faut trouver un ordre
//     //on suppose que chaque items à sa place compte pour 1 points 
//     //mais ce sera le nombre de points de la questions qui primera
//     //this.scoreMaxiBP = this.question.answers.length;
//     this.scoreMaxiBP = this.question.points;
// }

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "lucioles"
 }

/* *************************************
*
* ******** */
isInputOk(answerContainer,currentSlide){
    return false;
}

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){
    var score = 0;
    var  currentQuestion = this.question;
    var options = currentQuestion.options;
    var points = 0;
    var piecesfound = 0;    
    
    //au cas ou il ne resterait qu'un jeu d'image a trouvé il faut basculer son statut
    lucioles_get_found(this.getId('lucioles'));
    
    var obMemory = document.getElementById(this.getId('lucioles'));
    var selecteur='div[piece="true"]';    
    allPieces = obMemory.querySelectorAll(selecteur);    
    
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('status') == 2){
            piecesfound++;
            points += allPieces[h].getAttribute('points')*1;
//            console.log(`getScoreByProposition : \n h = ${h} \n piecesfound = ${piecesfound}\n points = ${points}`)
        }
    }
    
    
    score = (points > 0) ? points / options.doublons*1 : 0;
//alert(`getScoreByProposition : \n allPieces.length = ${allPieces.length} \n points = ${points} \n score = ${score} \n this.scoreMaxiBP = ${this.scoreMaxiBP }`);

   if (score == this.scoreMaxiQQ){
        zoom_moins_event(null, this.slideNumber);
        //updateButton('quiz_btn_nextSlide', 1, 'getScoreByProposition');
        this.allowNextSlide = true;        
   }   
   return score;
  }

 

/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.onFinalyse();
//    lucioles_reset(this.getId('lucioles'), true);
}

/* *************************************
*
* ******** */
showGoodAnswers ( answerContainer)
  {
    lucioles_all(this.getId('lucioles'), false);
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
    lucioles_all(this.getId('lucioles'), true);
    return false;
  
}
  

 
  
 
} // ----- fin de la class ------

//////////////////////////////////////////////////

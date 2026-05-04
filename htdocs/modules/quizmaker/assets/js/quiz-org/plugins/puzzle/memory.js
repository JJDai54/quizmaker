
 /*******************************************************************
  *                     memory
  * *****************************************************************/
/*
mettre dans le plugin memory les methodes communes et faire hériter les deux autre sur memory
*/
class memory extends Plugin_Prototype{
name = "memory";

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
    var memoryId = this.getId('memory');
    
    var imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.memoryIsLoad = false;
    
    var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${memoryId}'><hr><b>Puzzle</b><hr></div></center>`;
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

    this.initMinMaxQQ (2);
} 

/* *************************************
*
* ******** */
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;

    this.scoreMaxiBP = options.imgCols * options.imgRows * currentQuestion.answers[0].points;
    this.scoreMiniBP = 0;
    
    return true;
}

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    var currentQuestion = this.question;
    super.onFinalyse ();
    
    if(this.memoryIsLoad == false){
        var  currentQuestion = this.question;
        var options = currentQuestion.options;
        
        var obImg = document.getElementById(this.getId(0));
        var imgUrl = obImg.getAttribute('src');
        var memoryId = this.getId('memory');
        
        options.urlPlugin = currentQuestion.urlPlugin;
        options.slideNumber = this.slideNumber;
        build_memory(memoryId, imgUrl, options, true);
        
        var obMemory = document.getElementById(memoryId);
        this.memoryIsLoad = true;
    }
    
    var tellFrom = 'memory.onFinalyse';
    if(currentQuestion.options.preview > 0){
        memory_all(this.getId('memory'), false);
        updateButton('quiz_btn_nextSlide', 0, 'onFinalyse');
        quiz_show_mask(true, 0.10, true);
        setTimeout(memory_preview, currentQuestion.options.preview*1000, this.getId('memory'), true, tellFrom);
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
    return "memory"
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
    
    var found = memory_get_found(this.getId('memory'));
    //alert(`this.nbPieces = ${this.nbPieces} \n found = ${found} \n points = ${this.scoreMaxiQQ}`);
    score = found / options.doublons * currentQuestion.answers[0].points;
    if (memory_get_found(this.getId('memory')) == this.nbPieces){
        //return this.question.points;
        //score = currentQuestion.answers[0].points;
        score = this.scoreMaxiQQ;
        this.allowNextSlide = true;    

//         updateButton('quiz_btn_nextSlide', 1, 'getScoreByProposition-memory');
 //        setTimeout(updateButton, 5000, 'quiz_btn_nextSlide',1,'getScoreByProposition-memory');
        //document.getElementById('quiz_btn_nextSlide').disabled = '';
        //setTimeout(show_message, 200, quiz_messages.bravo, this.slideNumber, false);
        //alert(`getScoreByProposition :  ${memory_get_found(this.getId('memory'))}/ this.nbPieces = ${this.nbPieces}`);
    }
   if (score == this.scoreMaxiQQ){zoom_moins_event(null, this.slideNumber);}   
   return score;
  }

 

/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.onFinalyse();
//    memory_reset(this.getId('memory'), true);
}

/* *************************************
*
* ******** */
showGoodAnswers ( answerContainer)
  {
    memory_all(this.getId('memory'), false);
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
    memory_all(this.getId('memory'), true);
    return false;
  
}
  

 
  
 
} // ----- fin de la class ------

//////////////////////////////////////////////////

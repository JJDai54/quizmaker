function getPlugin_taquin(question, slideNumber){

    return new taquin(question, slideNumber, 'taquin')
    //return new taquin(question, slideNumber);
}  

 /*******************************************************************
  *                     taquin
  * *****************************************************************/
/*
mettre dans le plugin taquin les methodes communes et faire hériter les deux autre sur taquin
*/
class taquin extends Plugin_Prototype{
name = "taquin";

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
    var taquinId = this.getId('taquin');
    
    var imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.taquinIsLoad = false;
    
    var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${taquinId}'><hr><b>Taquin</b><hr></div></center>`;

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

    this.initMinMaxQQ (0);
} 

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
/* *************************************
*
* ******** */
onFinalyse (){
    super.onFinalyse ();
    var  currentQuestion = this.question;
    var options = currentQuestion.options;
    var taquinId = this.getId('taquin');
    
    if(this.taquinIsLoad == false){
        
        var obImg = document.getElementById(this.getId(0));
        var imgUrl = obImg.getAttribute('src');
        
        build_taquin(taquinId, imgUrl, options, true);
        
        var obPuzzle = document.getElementById(taquinId);
        this.taquinIsLoad = true;
    }

    var tellFrom = `taquinId.onFinalyse[${taquinId}]`; // pour tracer et debuger
    if(options.preview > 0){
         taquin_reset(taquinId, false);
         updateButton('quiz_btn_nextSlide', 0, tellFrom);    
         quiz_show_mask(true, 0.10, true);
         setTimeout(taquin_preview, options.preview*1000, taquinId, options.nextSlideDelai, tellFrom);    
    }else{
        updateButton('quiz_btn_nextSlide', 1, tellFrom);        
    }

    
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
    return "taquin"
 }


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){
var score = 0;
    var  currentQuestion = this.question;
    if (taquin_is_ok(this.getId('taquin')) && this.taquinIsLoad){
    //alert(`points = ${this.question.answers[0].points}`);
        //return this.question.points;
        //score = currentQuestion.answers[0].points;
        taquin_reset(this.getId('taquin'), false);
        score = this.scoreMaxiQQ;
        //setTimeout(show_message, 200, quiz_messages.bravo, this.slideNumber, false);
        
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

/* ************************************
*
* **** */
/* *************************************
*
* ******** */
showGoodAnswers ()
  {
    taquin_reset(this.getId('taquin'), false);
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
  taquin_reset(this.getId('taquin'), true);
  var currentQuestion = this.question;   

  
}
  

 
  
 
} // ----- fin de la class ------

//////////////////////////////////////////////////

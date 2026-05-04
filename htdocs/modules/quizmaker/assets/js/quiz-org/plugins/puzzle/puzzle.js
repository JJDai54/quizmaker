function getPlugin_puzzle(question, slideNumber){
//alert(question.options.disposition);
//question.options.variant = 'puzzle';
//question.options.variant = 'memory';
//alert(question.options.variant);
    switch(question.options.variant){
    case 'taquin'   : return new taquin(question, slideNumber, 'taquin'); break;
    case 'memory'   : return new memory(question, slideNumber, 'memory'); break;
    case 'lucioles' : return new lucioles(question, slideNumber, 'lucioles'); break;
    case 'puzzle'   : 
    default         : return new puzzle(question, slideNumber, 'puzzle');
    }
    //return new sortItems_combobox(question, slideNumber);

    return new puzzle(question, slideNumber, 'puzzle')
    //return new puzzle(question, slideNumber);
}  

 /*******************************************************************
  *                     puzzle
  * *****************************************************************/
/*
mettre dans le plugin puzzle les methodes communes et faire hériter les deux autre sur puzzle
*/
class puzzle extends Plugin_Prototype{
name = "puzzle";

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
    var puzzleId = this.getId('puzzle');
    
    var imgUrl = `${quiz.url}/${quiz.folderJS}/images/${currentQuestion.answers[0].image1}`;
    var img = `<img id="${this.getId(0)}" src="${imgUrl}" width="1px" height="1px" style="position:absolute 0 0;visibility:hidden" title="pour test">`;   
    this.puzzleIsLoad = false;
    
    var html = `${img}<center>${this.getImage()}${currentQuestion.answers[0].proposition}<div id='${puzzleId}' slideNumber="${this.slideNumber}" ><hr><b>Puzzle</b><hr></div></center>`;

    return html;
}

/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
    var options = currentQuestion.options;

    if(!options.maxAttemps){options.maxAttemps = 99999;}
    if(options.maxAttemps == 0){options.maxAttemps = 99999;}
//alert(`preview = ${options.preview}`);
    this.initMinMaxQQ (0);
    if(!options.preview) {options.preview  = 0;}
    if(!options.rotation) {options.rotation  = 1;}
} 

//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    super.onFinalyse ();
    this.computeScore=true;
    var  currentQuestion = this.question;
    var options = currentQuestion.options;
    var puzzleId = this.getId('puzzle');
    
    if(this.puzzleIsLoad == false){
        
        var obImg = document.getElementById(this.getId(0));
        var imgUrl = obImg.getAttribute('src');
//alert(options.)        
        options.urlPlugin = currentQuestion.urlPlugin;
        build_puzzle(puzzleId, imgUrl, options, true);
        
        var obPuzzle = document.getElementById(puzzleId);
        this.puzzleIsLoad = true;
        
        //this.computeScore=false;
        //this.allowNextSlide = false;
        
    }

    var tellFrom = `puzzle.onFinalyse[${puzzleId}]`; // pour tracer et debuger

    if(options.preview > 0){
        puzzle_reset(puzzleId, false, tellFrom);
        updateButton('quiz_btn_nextSlide', 0, tellFrom);
        quiz_show_mask(true, 0.10, true);
        setTimeout(puzzle_preview, options.preview*1000, puzzleId, options.nextSlideDelai, tellFrom);
    }else{
        updateButton('quiz_btn_nextSlide', 1, tellFrom);        
    }


    
        //document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //updateButton('quiz_btn_nextSlide', 0);
}
/* *************************************
*
* ******** */
isInputOk(answerContainer,currentSlide){
    var  currentQuestion = this.question;
    //return    ( this.getScoreByProposition() == this.scoreMaxiQQ);
    return true;
}

//---------------------------------------------------
// computeScoresMinMaxByProposition(){
// }

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "puzzle"
 }


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
var score = 0;
    var  currentQuestion = this.question;
    if (puzzle_is_ok(this.getId('puzzle')) && this.puzzleIsLoad){

    //alert(`points = ${this.question.points}`);
        //return this.question.points;
        //score = currentQuestion.answers[0].points;
        score = this.scoreMaxiQQ;
        //document.getElementById('quiz_btn_nextSlide').disabled = '';
        //this.allowNextSlide = true;
        //setTimeout(show_message, 200, quiz_messages.bravo, this.slideNumber, false);
        updateButton('quiz_btn_nextSlide', 1, 'getScoreByProposition');
    }else{
        //this.allowNextSlide = false;
        //document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        if(currentQuestion.options.delaiNexSlide*1 > 0){
            updateButton('quiz_btn_nextSlide', 0, 'getScoreByProposition');
        }
        
    }
   if (score == this.scoreMaxiQQ){zoom_moins_event(null, this.slideNumber);}   
   return score;
if (!this.computeScore) return 0;
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
showGoodAnswers ()
  {
    puzzle_reset(this.getId('puzzle'), false, 'showGoodAnswers');
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
  puzzle_reset(this.getId('puzzle'), true, 'showBadAnswers');
  var currentQuestion = this.question;   

  
}
  

 
  
 
} // ----- fin de la class ------

//////////////////////////////////////////////////

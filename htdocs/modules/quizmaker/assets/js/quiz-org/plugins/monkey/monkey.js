
function getPlugin_monkey(question, slideNumber){
//alert(`plugin : ${question.options.variant}`);

    return new monkey(question, slideNumber, 'monkey');  
}  


 /*******************************************************************
  *                     monkey
  * *****************************************************************/
/*
mettre dans le plugin monkey les methodes communes et faire hériter les deux autre sur monkey
*/
class monkey extends Plugin_Prototype{
name = "monkey";
monkeyIsLoad = false;
totalMinoration = 0;


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
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var monkeyId = this.getId('monkey');
    this.monkeyId = monkeyId;
    //var html = monkey_creerGrille(monkeyId, options.tblRows, options.tblCols);   
    
    var tpl = this.getDisposition();
    
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{grille}', monkey_creerGrille(this.slideNumber));
    
   html += `<br><center><div id=${this.getId('jauge')} style='background:transparent'></div></center>`;
    
    //////////////////////////////

    ////////////////////////////////////
//              + "nbClicks <input type='hidden' value='0'>";
//              + "nbClicks <input type='hidden' value='0'>";

    return html;
}

getDisposition(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var tpl = '';
let  styleImg ="width:10%;border-width: 0px;";
let  styleGrille ="width:90%;border-width: 0px;";

    
    

    switch(options.disposition){

        case 'disposition-01':
            tpl = `<table style="width:100%"><tr><td style="${styleImg}">{image}</td><td style="${styleGrille}">{grille}</td></tr></table>`;
            break;
        
        case 'disposition-02':
            tpl = `<table style="width:100%"><tr><td style="${styleGrille}">{grille}</td><td style="${styleImg}">{image}</td></tr></table>`;
            break;
        
        default :
            tpl = '{grille}';
            break;
    }
    return tpl;
}
/* *************************************
*
* ******** */
prepareData(){
    var currentQuestion = this.question;
    var options = this.question.options;

//alert("prepareData")
    this.initMinMaxQQ (0);
    options.itemsArr = null;    
    options.totalItems = 0;
    options.prochainNombre = 0;
    options.nbAttempts = 1;
    this.score = 0;

    this.initMinMaxQQ(1);
//    alert(this.scoreMaxiBP );
} 

//---------------------------------------------------
onEnter() {
    super.onEnter();

}

//---------------------------------------------------
onFinalyse (){
//alert("onFinalyse")

    if(!this.obGauje){
        var divJauge = document.getElementById(this.getId('jauge'));
        //divJauge.innerHTML = "<span>xxxxxxxxxxxxx</span>";
        this.obGauje = new QuizMaker.Gauge(divJauge, 'horizontal', 1, 400);
        this.obGauje.setColor('green','silver');
    }
//divJauge.appenChild(obGauje);
    this.initNewAttempts();
 
}

//---------------------------------------------------
initNewAttempts (){
//alert("initNewAttempts")
    var currentQuestion = this.question;
    var options = this.question.options;
    
    if(options.keepSameGrid*1 && options.itemsArr){
        monkey_showGame(this.slideNumber)
    }else{
        monkey_ClearTable(this.monkeyId);
        var itemsIndex = getRandom(currentQuestion.answers.length-1);
        options.itemsArr =  currentQuestion.answers[itemsIndex].proposition.replace(/[^a-zA-Z0-9]/g, "|").split('|');
        options.totalItems = options.itemsArr.length;
        monkey_buildNewGame(this.slideNumber);  
    }
    
    //alert(currentQuestion.answers[itemsIndex].proposition);
    //options.itemsArr = currentQuestion.answers[itemsIndex].proposition.replaceAll(' ','').split(',');
    //options.itemsArr =  currentQuestion.answers[itemsIndex].proposition.replace(/[anb]/g, "|").split('|');
    options.prochainNombre = 0;
    this.score = 0;

    //quiz_show_mask(true);
    this.obGauje.start(options.preview,{'opacity':0});    
    setTimeout(monkey_hiddeGame, options.preview * 1000, this.slideNumber);

//var obDisc = new QuizMaker.Disc(document.getElementById(this.getId('monkey')));
// var obDisc = new QuizMaker.Disc(this.getId('monkey'));
// obDisc.start(3);
}

//---------------------------------------------------

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){
var score = 0;
    
   return this.score;
  }


/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "monkey"
 }



/* ************************************
*
* **** */
reloadQuestion(reloadMode = reloadShuffle){
    var currentQuestion = this.question;
    var options = this.question.options;
    options.itemsArr = null;    
    super.reloadQuestion(reloadMode);
    this.obGauje = null;
    this.onFinalyse();
    options.nbAttempts = 1;
}

/* ************************************
*
* **** */
showGoodAnswers(){
    var currentQuestion = this.question;
    
    monkey_showGame(this.slideNumber);
    this.score = currentQuestion.points;

}
/* ************************************
*
* **** */
showBadAnswers(){
    var currentQuestion = this.question;
    
    monkey_showGame(this.slideNumber);
    this.score = 0;

}

} // ----- fin de la class ------

//////////////////////////////////////////////////


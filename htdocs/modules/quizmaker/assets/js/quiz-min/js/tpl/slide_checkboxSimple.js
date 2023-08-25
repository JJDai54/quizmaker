
 /*******************************************************************
  *                     _checkboxSimple
  * *****************************************************************/
class checkboxSimple extends quizPrototype{
name = 'checkboxSimple';
  
/* ***************************************
*
* *** */
build (){
    this.boolDog = true;

    var currentQuestion = this.question;
    var name = this.getName();

    if(currentQuestion.image){
        return this.getInnerHTML_img();
    }else{
        return this.getInnerHTML();
    }
/*


//alert("image : " + currentQuestion.image);
    const answers = [];
    answers.push(`<div id="${name}-famille" style="text-align:left;margin-left:100px;">`);
    this.data.styleCSS = getMarginStyle(currentQuestion.answers.length);
        
    answers.push(getHtmlCheckboxKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));
    answers.push(`</div>`);

    this.focusId = name + "-" + "0";
    //alert (this.focusId);

    return answers.join("\n");
*/

 }

getInnerHTML(){
    var currentQuestion = this.question;
    var name = this.getName();
//alert("image : " + currentQuestion.image);
    const answers = [];
    answers.push(`<div id="${name}-famille" style="text-align:left;margin-left:100px;">`);
    this.data.styleCSS = getMarginStyle(currentQuestion.answers.length);
        
    answers.push(getHtmlCheckboxKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));
    answers.push(`</div>`);

    this.focusId = name + "-" + "0";
    //alert (this.focusId);

    return answers.join("\n");
}

getInnerHTML_img(){
    var currentQuestion = this.question;
    const answers = [];
    var imageMain = `<img src="${ quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px">`;
    return `<table><tr><td>${imageMain}</td><td>${this.getInnerHTML()}</td></tr></table>`;
}

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;

    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    
    var currentQuestion = this.question;
    
    for(var k in currentQuestion.answers){
        
        var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);
        //alert (key);
        var key = "ans-" + k.padStart(3, '0');
        var tWP = {'key': key,
                   'word': currentQuestion.answers[k].proposition, 
                   'points' : currentQuestion.answers[k].points*1};
        tItems[key] = tWP;

    }

// var keys = Object.keys(tItems);    
// alert("prepareData : " + keys.join(' - '));
    this.data.items = tItems;
    
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (currentQuestion.answers[i].points > 0)
                this.scoreMaxiBP += currentQuestion.answers[i].points*1;
          if (currentQuestion.answers[i].points < 0)
                this.scoreMiniBP += currentQuestion.answers[i].points*1;
      }

     return true;
 }

//---------------------------------------------------
getScore ( answerContainer){
var points = 0;

    var tItems = this.data.items;
    var obs = getObjectsByName(this.getName(), "input", "checkbox", "checked");

    obs.forEach((obInput, index) => {
        points += tItems[obInput.getAttribute('caption')].points*1;
    });
    //alert('getScore = ' + points);
    
    this.points = points;    
    return points;
  }
///////////////////////////////
//---------------------------------------------------
  isInputOk (answerContainer){
    var obs = getObjectsByName(this.getName(), "input", "checkbox", "checked");
    return (obs.length > 0) ? true : false ;

 }

//---------------------------------------------------
getAllReponses (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0 || flag == 0)
            //tReponses.push ({'reponse':rep.proposition, 'points':rep.points});    
            tReponses.push ([[rep.proposition], [rep.points]]);    
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>");


 }

//---------------------------------------------------
  update(nameId, chrono) {
}

//---------------------------------------------------
   incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

/* ************************************
*
* **** */
 reloadQuestion() {
    var name = this.getName();
    var obFamille = document.getElementById(`${name}-famille`);

    obFamille.innerHTML = getHtmlCheckboxKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS);
    return true;
}

/* ************************************
*
* **** */
  showGoodAnswers()
  {
  var points = 0;
  
    var name = this.getName();
    var obs = getObjectsByName(name, "input","checkbox"); 
    var tItems = this.data.items;
  
    obs.forEach((obInput, index) => {
        var p = tItems[obInput.getAttribute('caption')].points*1;
         obInput.checked = (p > 0) ? true : false;
    });

    return true;
  
  } 

/* ************************************
*
* **** */
  showBadAnswers()
  {
  var points = 0;
  
    var name = this.getName();
    var obs = getObjectsByName(name, "input","checkbox"); 
    var tItems = this.data.items;
  
    obs.forEach((obInput, index) => {
        var p = tItems[obInput.getAttribute('caption')].points*1;
         obInput.checked = (p <= 0) ? true : false;
    });

    return true;
  
  } 
 

} // ----- fin de la classe ------

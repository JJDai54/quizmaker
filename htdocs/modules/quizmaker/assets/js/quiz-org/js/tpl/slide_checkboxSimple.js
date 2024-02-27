
 /*******************************************************************
  *                     _checkboxSimple
  * *****************************************************************/
class checkboxSimple extends quizPrototype{
name = 'checkboxSimple';
typeInput = 'checkbox';  
/* ***************************************
*
* *** */
build (){
    this.boolDog = false;
    var name = this.getName();

    const htmlArr = [];
    htmlArr.push(`<div id="${name}-famille" style="text-align:left;margin-left:100px;">`);
    htmlArr.push(this.getInnerHTML());
    htmlArr.push(`</div>`);
    
        return htmlArr.join("\n");

 }

/* ***************************************
*
* *** */
getInnerHTML(){
    var currentQuestion = this.question;
    var name = this.getName();

    if(currentQuestion.image){
        if(currentQuestion.options.familyWords){
            var tpl = `<table><tr><td colspan='2'>${this.get_img()}</td><tr><td>${this.get_listFamilywords()}</td><td>${this.get_optionsList()}</td></tr></table>`;
        }else{
            var tpl = `<table><tr><td>${this.get_img()}</td><td>${this.get_optionsList()}</td></tr></table>`;
        }
    }else{
        if(currentQuestion.options.familyWords){
            var tpl = `<table><tr><td>${this.get_listFamilywords()}</td><td>${this.get_optionsList()}</td></tr></table>`;
            //var tpl = `<table><tr><td>${this.get_optionsList()}</td></tr></table>`;
        }else{
            var tpl = `<table><tr><td>${this.get_optionsList()}</td></tr></table>`;
        }
    }
    
    this.focusId = name + "-" + "0";
    return tpl;
}

/* ***************************************
*
* *** */
get_optionsList(){
    var currentQuestion = this.question;
    var name = this.getName();
//alert("image : " + currentQuestion.image);
    const htmlArr = [];
    this.data.styleCSS = getMarginStyle(currentQuestion.answers.length);
    htmlArr.push(`<div id="${name}-famille" style="text-align:left;margin-left:100px;">`);
    htmlArr.push(getHtmlCheckboxKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));
    htmlArr.push(`</div>`);

    //alert (this.focusId);
   return htmlArr.join("\n");
}
/* ***************************************
*
* *** */
get_img(){
    var name = this.getName();
    var currentQuestion = this.question;
    return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></center>`;
}

/* ***************************************
*
* *** */
get_listFamilywords(){ 
    var name = this.getName();
    var currentQuestion = this.question;
//alert (currentQuestion.options.familyWords);
    var fw = currentQuestion.options.familyWords.replace(';', ',').replace('-', ',').replace('|', ',').split(',');
        this.data.styleCSSTxt = getMarginStyle(fw.length);
    return getHtmlSpan(name, this.shuffleArray(fw),  currentQuestion.numbering, 0, this.data.styleCSSTxt);
}
/*
*/
/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;

    var tItems = new Object;
    
    for(var k in currentQuestion.answers){
        
        var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);
        //alert (key);
        var key = "ans-" + k.padStart(3, '0');
        var tWP = {'key': key,
                   'word': currentQuestion.answers[k].proposition, 
                   'points' : currentQuestion.answers[k].points*1};
        tItems[key] = tWP;
// alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);

    }

//  var keys = Object.keys(tItems);    
//  alert("prepareData : " + keys.join(' - '));

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
getScoreByProposition ( answerContainer){
var points = 0;
var bolOk = 1;
//alert("answerContainer");
    var tItems = this.data.items;
    var obs = getObjectsByName(this.getName(), "input", this.typeInput, "checked");

    obs.forEach((obInput, index) => {
        var p = tItems[obInput.getAttribute('caption')].points*1;
    
        if (p == 0) bolOk = 0;
        points += p;
    });
    //alert('getScore = ' + points);
    
    this.points = points * bolOk;    
    return this.points;
  }
//---------------------------------------------------
/*
getPopupResults (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    //parcours toutes les réponses
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0 || flag == 0)
            //tReponses.push ({'reponse':rep.proposition, 'points':rep.points});    
            tReponses.push ([[rep.proposition], [rep.points]]);    
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>");


 }
*/

///////////////////////////////
//---------------------------------------------------
  isInputOk (answerContainer){
    var obs = getObjectsByName(this.getName(), "input", this.typeInput, "checked");
    return (obs.length > 0) ? true : false ;

 }

//---------------------------------------------------
getAllReponses (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0 || flag == 0) {
            //tReponses.push ({'reponse':rep.proposition, 'points':rep.points});    
            tReponses.push ([[rep.proposition], [rep.points]]);    
        }
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>", true, 1);


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

    obFamille.innerHTML = this.getInnerHTML();
    return true;
}

/* ************************************
*
* **** */
  showGoodAnswers()
  {
  var points = 0;
  
    var name = this.getName();
    var tItems = this.data.items;
    var obs = getObjectsByName(name, "input", this.typeInput); 
  
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
    var obs = getObjectsByName(name, "input", this.typeInput); 
    var tItems = this.data.items;
  
    obs.forEach((obInput, index) => {
        var p = tItems[obInput.getAttribute('caption')].points*1;
         obInput.checked = (p <= 0) ? true : false;
    });

    return true;
  
  } 
 

} // ----- fin de la classe ------


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
    return this.getInnerHTML();
 }

/* ***************************************
*
* *** */
getInnerHTML(){
    var currentQuestion = this.question;
    var name = this.getName();
    
    var tpl = this.getDisposition(currentQuestion.image, currentQuestion.options.familyWords);
    var html = tpl.replace("{image}", this.getImage())
                  .replace("{familyWords}", this.get_listFamilywords())
                  .replace("{optionsList}", this.get_optionsList())
    
    this.focusId = name + "-" + "0";
    return html;
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
    htmlArr.push(`<div id="${name}-famille" familyWords>`);
    htmlArr.push(getHtmlCheckboxKeys(name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));
    htmlArr.push(`</div>`);

    //alert (this.focusId);
   return htmlArr.join("\n");
}

/* ***************************************
*
* *** */
get_listFamilywords(){ 
    var name = this.getName();
    var currentQuestion = this.question;
//alert (currentQuestion.options.familyWords);

    var fw = splitAllSep(currentQuestion.options.familyWords);
        this.data.styleCSSTxt = getMarginStyle(fw.length);
   
    return getHtmlSpan2(name, "familyWords", this.shuffleArray(fw), currentQuestion.numbering, 0, this.data.styleCSSTxt , "<br>");
}
/* *************************************
*
* ******** */
getDisposition(bolImage, bolFamilyWords){
var disposition = ((bolImage) ? "image" : "") 
                + ((bolImage && bolFamilyWords) ? "-" : "")
                + ((bolFamilyWords) ? "familyWords" : "");

    switch(disposition){
    case "image-familyWords":
            var tpl = 
`<table class='${this.typeName}'>
<tr>
    <td colspan='2'>{image}</td>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    case "image":
        var tpl = 
`<table class='${this.typeName}'>
<tr>
    <td>{image}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    case "familyWords":
        var tpl = 
`<table class='${this.typeName}'>
<tr>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    default:
            var tpl = 
`<table class='${this.typeName}'>
<tr>
    <td>{optionsList}</td>
</tr>
</table>`;
        break;
    }    
    return '<center>' + tpl + '</center>';
}
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
//alert("getScoreByProposition");
    var tItems = this.data.items;
    var obs = this.getQuerySelector("input", this.getName(), this.typeInput, "checked");

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
    var obs = this.getQuerySelector("input", this.getName(), this.typeInput, "checked");
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


/* ************************************
*
* **** */
  showGoodAnswers()
  {
  var points = 0;
  
    var name = this.getName();
    var tItems = this.data.items;
    var obs = this.getQuerySelector("input", name, this.typeInput); 
  
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
    var obs = this.getQuerySelector("input", name, this.typeInput); 
    var tItems = this.data.items;
  
    obs.forEach((obInput, index) => {
        var p = tItems[obInput.getAttribute('caption')].points*1;
         obInput.checked = (p <= 0) ? true : false;
    });

    return true;
  
  } 
 

} // ----- fin de la classe ------

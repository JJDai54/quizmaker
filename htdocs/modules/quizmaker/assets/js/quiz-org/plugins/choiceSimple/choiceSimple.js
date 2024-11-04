
 /*******************************************************************
  *                     _choiceSimple
  * *****************************************************************/
class choiceSimple extends Plugin_Prototype{
name = 'choiceSimple';
delaiNextSlide = 1500;  
  
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
    var msgNextSlide = '';
    var divNexSlide = '';
    
    if (currentQuestion.options.msgNextSlide){
        msgNextSlide = replaceBalisesByValues(currentQuestion.options.msgNextSlide);
        divNexSlide = `<div id='${this.getId('nextquestion')}' style='background:${currentQuestion.options.msgNextSlideBG}' nextquestion>${msgNextSlide}</div>`;
    }else{
        divNexSlide = '';
    }

    var tpl = this.getDisposition(currentQuestion.image, currentQuestion.options.familyWords);
    var html = tpl.replace("{image}", this.getImage())
                  .replace("{familyWords}", this.get_listFamilywords())
                  .replace("{optionsList}", this.get_optionsList())
                  .replace('{nextslide}',  divNexSlide);

    
    //this.focusId = name + "-" + "0";
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
    htmlArr.push(`<div id="${name}-famille" style="text-align:left;margin-left:10px;">`);
    htmlArr.push(this.getHtmlInputKeys(name, this.data.inputType, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));  
    htmlArr.push(`</div>`);

    //alert (this.focusId);
   return htmlArr.join("\n");
}

/* ******************************************
*
* ******************************************** */
 getHtmlInputKeys(name, typeInp2, tItems, numerotation, offset=0, extra="", sep="<br>"){
var item;
    var currentQuestion = this.question;
    var keys = Object.keys(tItems);
    var typeInp = '';
    var eventOnClick = '';
    
    switch(currentQuestion.options.inputType*1){
        case 0 :
            typeInp = 'checkbox';
            break;
        case 2:
            eventOnClick = `onclick="choiceSimple_event_gotoNextSlide(event, ${this.delaiNextSlide});"`;
            // pas de break
        case 1:
            typeInp = 'radio';
        break;
    }

    var tHtml = [];
    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
        tHtml.push(`<label>
                 <input type="${typeInp}" id="${name}-${j}" name="${name}" value="${j}" ${extra} caption="${item.key}" ${eventOnClick}>
                 ${getNumAlpha(j,numerotation,offset)}${item.word}
                 </label>${sep}`);
    
    
    }
    return tHtml.join("\n");

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

//---------------------------------------------------
onEnter() {
    //document.getElementById('quiz_btn_nextSlide').disabled = '';
    //alert("onEnter");
}       
onFinalyse() {
    var currentQuestion = this.question;
    if (currentQuestion.options.inputType == 2){
        //document.getElementById('quiz_btn_nextSlide').setAttribute('disabled','disabled');
        document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //alert("onEnter");
    }else{
        document.getElementById('quiz_btn_nextSlide').disabled = '';
    }
}       

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;

    var tItems = new Object;
    var ansArr = this.shuffleAnswers();
        
    for(var k in ansArr){
        var ans = ansArr[k];
        var key = sanityseTextForComparaison(ans.proposition);
        //alert (key);
        var key = "ans-" + k.padStart(3, '0');
        var tWP = {'key': key,
                   'word': ans.proposition, 
                   'points' : ans.points*1};
        tItems[key] = tWP;
// alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);

    }

    //pour compatibilité avec checkboxSimple et radioSimple obsolettes
    if(!currentQuestion.options.inputType){currentQuestion.options.inputType = 0;}
    
    this.data.items = tItems;
    this.data.inputType = (currentQuestion.options.inputType == 0) ? 'checkbox'  : 'radio';
    this.initMinMaxQQ(2);
    
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    if(this.question.options.inputType == 0){
        this.computeScoresMinMaxCheckbox();
    }else{
        this.computeScoresMinMaxRadio();
    }
}
//---------------------------------------------------
computeScoresMinMaxCheckbox(){
    this.scoreMaxiBP = 0;
    this.scoreMiniBP = 0;
    
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
computeScoresMinMaxRadio(){
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

//---------------------------------------------------
getScoreByProposition ( answerContainer){
var points = 0;
var bolOk = 1;
//alert("getScoreByProposition");
    var tItems = this.data.items;
    var obs = this.getQuerySelector("input", this.getName(), this.data.inputType, "checked");

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
`<table>
<tr>
    <td colspan='2'>{image}</td>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    case "image":
        var tpl = 
`<table>
<tr>
    <td>{image}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    case "familyWords":
        var tpl = 
`<table>
<tr>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr></table>`;
        break;

    default:
            var tpl = 
`<table>
<tr>
    <td>{optionsList}</td>
</tr>
</table>`;
        break;
    }    

    return `<div>{nextslide}</div><div id='{contenairId}'><center>${tpl}</center></div><br>`;

}

} // ----- fin de la classe ------

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceSimple_event_gotoNextSlide(ev, delaiNextSlide){
    console.log("choiceImages_event_gotoNextSlide");
    
    
    //dans tous les cas on reactive le bouton nextSlide
    idDivNextQuestion = ev.currentTarget.name + '-nextquestion';
    
    //document.getElementById(idDivNextQuestion).style.visibility = 'visible';      
    if(choiceSimple_show_message(idDivNextQuestion)){
        setTimeout(choiceImages_next_slide, delaiNextSlide, idDivNextQuestion);
    }else{
        setTimeout(choiceImages_next_slide, delaiNextSlide/2, idDivNextQuestion);
    }
    ev.stopPropagation();
}
/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceSimple_next_slide(idDivNextQuestion){
    var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';   
        btnNextSlide.click(); 
    
   obNextSlide =  document.getElementById(idDivNextQuestion)
   if(obNextSlide){
     obNextSlide.style.visibility = 'hidden';
     obNextSlide.style.opacity = '0';
     obNextSlide.classList.remove('choiceImages_vignets');        
   }
   
}

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceSimple_show_message(idDivNextQuestion){
    obNextSlide =  document.getElementById(idDivNextQuestion)
    if(obNextSlide){
      obNextSlide.style.visibility = 'visible';        
      obNextSlide.classList.add('choiceImages_vignets');  
      return true;      
    }else{
        return false;
    }

}


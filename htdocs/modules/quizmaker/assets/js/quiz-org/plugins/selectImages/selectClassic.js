/*******************************************************************
*                     selectClassic
* *****************************************************************/
function getPlugin_selectInputs(question, slideNumber){
    return new selectInputs(question, slideNumber);
}
 /*******************************************************************
  *                     selectInputs
  * *****************************************************************/
class selectClassic extends Plugin_Prototype{
name = 'selectClassic';
 
  
/* ***************************************
*
* *** */
buildSlide (bShuffle = true){
    this.boolDog = false;
    return this.getInnerHTML(bShuffle);
 }

/* ***************************************
*
* *** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var name = this.getName();
   
    var tpl = this.getDisposition(currentQuestion.options.disposition, currentQuestion.image, currentQuestion.options.familyWords);
    var html = tpl.replace("{image}", this.getImage())
                  .replace("{familyWords}", this.get_listFamilywords())
                  .replace("{optionsList}", this.get_optionsList());

    
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
    
    var styleArr = getMarginStyleArr(currentQuestion.answers.length,2);

    if(!currentQuestion.options.trHeight){currentQuestion.options.trHeight = 0;}
    if(currentQuestion.options.trHeight == 0){
        //to : a revoir
        var trHeight = 12; //Math.floor((350/(this.data.items.length+1)));
    }else{
        var trHeight = currentQuestion.options.trHeight;
            delete(styleArr['padding-top']);
            delete(styleArr['padding-bottom']);
    styleArr['height'] = `${trHeight}px`;
    styleArr['vertical-align'] = 'middle';
    }
    
//var height = 'height:' + trHeight + 'px;';

    
    this.data.styleCSS = getStyleFromArr(styleArr);    
    //this.data.styleCSS = `style='${getMarginStyle(currentQuestion.answers.length,2)}${height}'`;    

    
    
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
    var options = currentQuestion.options;
    var keys = Object.keys(tItems);
    var typeInp = '';
    var eventOnClick = '';
//alert(extra);  

    switch(this.data.inputType){
        case 'checkbox' :
            typeInp = 'checkbox';
            break;
        default:
        case 'radio':
            typeInp = 'radio';
        break;
    }
    var eventOnClick = `onclick="selectClassic_event_gotoNextSlide(event, ${this.slideNumber}, ${options.msgNextSlideDelai});"`;

    var tHtml = [];
    tHtml.push('<table>');
    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
        tHtml.push(`<tr><td proposition ${extra}>       
<input type="${typeInp}" id="${name}-${j}" name="${name}" value="${j}"caption="${item.key}" ${eventOnClick}>
</td><td>`);

if(numerotation==0){
    tHtml.push(`<td proposition ${extra} ><label for="${name}-${j}" style='text-indent:0;margin:0px 0px 0px 0px'>
    ${item.word}
</label><td>`);
}else{
    tHtml.push(`<td proposition ${extra}><label for="${name}-${j}">
${getNumAlpha(j,numerotation,offset)}${item.word}
</label></td>`);
}
    }
    tHtml.push('</table>');
    return tHtml.join("\n");

}

/* ***************************************
*
* *** */
get_listFamilywords(){ 
    var name = this.getName();
    var currentQuestion = this.question;
//alert (currentQuestion.options.familyWords);

    var fw = splitArrayAllSep(currentQuestion.options.familyWords);
    this.data.styleCSSTxt = `style='${getMarginStyle(fw.length)}'`;    
   
    return getHtmlSpan2(name, "familyWords", this.shuffleArray(fw), currentQuestion.numbering, 0, this.data.styleCSSTxt , "<br>");
}

//---------------------------------------------------
onEnter() {
    
}       
onFinalyse() {
    super.onFinalyse();
}       

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;
    var options = this.question.options;
    
    var tItems = new Object;
    var ansArr = this.shuffleAnswers();
        
    // petite verrue pour corriger le changement de comportement de inputType,
    // il faut forcer "radio" si il y a qu'une seule réponse
    //ça ne règle pas tous les cas mais la plus part
    this.countAnsNotNull = 0; 
    let nbGoodAns = 0;
    
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
        if((ans.points*1) !=0 ) this.countAnsNotNull++;
        if((ans.points*1) > 0 ) nbGoodAns++;
    }

    //petite verue pour compatibilité avec l'acinne version "selectInputs"
    // a virer des que possible
    if(options.inputType == "0"){
        options.inputType = 'checkbox';
    }else if(options.inputType == "1"){
        options.inputType = 'radio';
    }else{
        options.inputType = 'auto';
    }
    
    
    
    
    if(options.inputType == 'auto'){
        this.data.inputType = (nbGoodAns == 1) ? 'radio' : 'checkbox';
    } else{
        this.data.inputType = options.inputType;
    }
//alert(`${options.inputType} - nbGoodAns = ${nbGoodAns} - this.data.inputType = ${this.data.inputType}`)
    
    this.data.items = tItems;
    this.initMinMaxQQ(2);
    
    
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    if(this.data.inputType == 'checkbox'){
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
getScoreByProposition ( answerContainer = null){
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
getAllPropositions (flag = 0){
    let  currentQuestion = this.question;
    var tReponses = [];
return currentQuestion.explanation;    
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
getDisposition(disposition, bolImage, bolFamilyWords){
var sDispo = disposition + ((bolImage) ? "1" : "0") + ((bolFamilyWords) ? "1" : "0");
//alert (`getDisposition : ${sDispo}`)

    switch(sDispo){
//----------------------------------------------------
    case "disposition-011":
            var tpl = 
`<tr>
    <td colspan='2' familyWords>{familyWords}</td>
</tr><tr>
    <td>{image}</td>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-010":
    case "disposition-110":
            var tpl = 
`<tr>
    <td>{image}</td>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-001":
    case "disposition-401":
    case "disposition-501":
            var tpl = 
`<tr>
    <td colspan='2' familyWords>{familyWords}</td>
</tr><tr>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-111":
            var tpl = 
`<tr>
    <td>{image}</td>
    <td familyWords>{familyWords}</td>
</tr><tr>
    <td colspan='2'>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-210":
    case "disposition-410":
    case "disposition-510":
            var tpl = 
`<tr>
    <td>{image}</td>
</tr><tr>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-101":
            var tpl = 
`<tr>
    <td>{optionsList}</td>
</tr><tr>
    <td familyWords>{familyWords}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-211":
            var tpl = 
`<tr>
    <td colspan='2'>{image}</td>
</tr><tr>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-201":
    case "disposition-301":
            var tpl = 
`<tr>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-311":
            var tpl = 
`<tr>
    <td familyWords>{familyWords}</td>
    <td>{optionsList}</td>
</tr><tr>
    <td colspan='2'>{image}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-310":
            var tpl = 
`<tr>
    <td>{optionsList}</td>
</tr><tr>
    <td>{image}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-411":
            var tpl = 
`<tr>
    <td>{image}</td>
    <td familyWords>{familyWords}</td>
</tr><tr>
    <td colspan='2'>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-511":
            var tpl = 
`<tr>
    <td familyWords>{familyWords}</td>
    <td>{image}</td>
</tr><tr>
    <td colspan='2'>{optionsList}</td>
</tr>`;
        break;
//----------------------------------------------------
    case "disposition-000":
    case "disposition-100":
    case "disposition-200":
    case "disposition-300":
    case "disposition-400":
    case "disposition-500":
    default:
            var tpl = `<tr><td>{optionsList}</td></tr>`;
        break;
    }
    
    return `<div id='{contenairId}'><center><table>${tpl}</table></center></div><br>`;    
    
    //return this.getDisposition(bolImage, bolFamilyWords);
}

} // ----- fin de la class ------


/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function selectClassic_event_gotoNextSlide(ev, slideNumber, msgNextSlideDelai){
    //console.log(`selectImages_event_gotoNextSlide : slideNumber = ${slideNumber}`);

    var clQuestion = quizard[slideNumber];
    var options = clQuestion.question.options;
    
    var gotoNexSlide = false;
    if((options.msg_nextslide_duree * 1) > 0 && options.msg_nextslide_gotonext > 0){
        if(this.data.inputType == 'radio'){
            gotoNexSlide = true;
        }else if(this.data.inputType == 'checkbox'){
            var obs = clQuestion.getQuerySelector("input", clQuestion.getName(), clQuestion.data.inputType, "checked");
            gotoNexSlide = (obs.length == clQuestion.countAnsNotNull);
        }
    }
    //alert (clQuestion.getScoreByProposition() + " / " + clQuestion.scoreMaxiBP);
    if(gotoNexSlide){
        var msg = (clQuestion.getScoreByProposition() == clQuestion.scoreMaxiBP) ? options.msg_nextslide_winner : options.msg_nextslide_looser;
        msg = fo_sprint(msg);
        
        quiz_show_avertissement (msg, options.msg_nextslide_duree, options.msg_nextslide_background);
    }  
    ev.stopPropagation();   
}



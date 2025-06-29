﻿/*******************************************************************
*                     alphaSimple
* *****************************************************************/
function getPlugin_alphaSimple(question, slideNumber){
    return new alphaSimple(question, slideNumber);
}

 /*******************************************************************
  *                     _alphaSimple
  * *****************************************************************/
class alphaSimple extends Plugin_Prototype{
name = 'alphaSimple';
typeInput = 'alpha';  
idDivReponse = '';
sep = "-"; //separateurs pour les réponses
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
var varByRef = { sep: " - " }; //modifie par getDisposition et utiliser pour aligner les mots h ou v

    var name = this.getName();
    this.idDivReponse = this.getName('letterSelected');
       
    const htmlArr = [];
    htmlArr.push(`<div id="${name}-alpha" style="text-align:left;margin-left:00px;">`);
    
    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId('tbl'), varByRef);
    var html = tpl.replace("{img}", this.getImage())
                  .replace("{words}", this.get_htmlWords(varByRef))
                  .replace("{answer}", "-?".repeat(this.data.nbSoluces).substring(1))
                  .replace("{directive}", currentQuestion.options.directive)
                  .replace("{alphanum}", this.get_htmlLetters());


    htmlArr.push(html);
    htmlArr.push(`</div>`);

    return htmlArr.join("\n");
}
/* ***************************************
*
* *** */
get_htmlWords(varByRef){
//var html = '<table class="alphaSimple_words"><tr>';
//alert(this.data.tWords.join(varByRef.sep));
    return this.data.tWords.join(varByRef.sep);
}


/* ***************************************
*
* *** */
get_htmlLetters(){
var html = '<div class="alphaSimple_letters">';
//alert('|' + this.idDivReponse + '|');
var tLetters = this.data.allExp;

    for(var k = 0; k < tLetters.length; k++){
        if (tLetters[k] == ''){
            html += qbr; 
        }else{
            //var onclick = `document.getElementById('${this.idDivReponse}').innerHTML='${tLetters[k]}';`;
            //var onclick = `eventOnClickAlpha('${this.idDivReponse}','${tLetters[k]}',${this.data.nbSoluces},'${this.sep}');`;
            var onclick = `eventOnClickAlpha(${this.slideNumber}, '${tLetters[k]}');`;
            //alert('|' + onclick + '|');
            html += `<a onclick="${onclick}">${tLetters[k]}</a>`; 
        }
    
    }
 
    html += '</div>';
    return html;
}

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;
    var nbSoluces = 0;
    var sep = '|';
    
    this.data.tWords = getExpInAccolades(this.question.question);  
//alert( this.data.tWords.join('-')) ;  
    //var listExp = setAllSepByNewSep(this.question.options.propositions, sep);  
    var tExp =  splitAllSep(this.question.options.propositions, sep);  
    
    var tItems = new Object;
    
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        ans.proposition = ans.proposition.toUpperCase();
        if(currentQuestion.options.ignoreAccents){
            ans.proposition = sanityseAccents(ans.proposition);
        }
        var key = sanityseTextForComparaison(ans.proposition);
        //alert (key);
        if ((ans.points*1) > 0 ) {
            nbSoluces += 1;
        }else{
        }
        
        console.log('test-sanitise===>' + ans.proposition + '===>' + sanityseAccents(ans.proposition,1));
        if(tExp.indexOf(ans.proposition) == -1){
//         var test = 'É-é';
//         console.log('test-sanitise===>' + test + '===>' + sanityseAccents(test,0));
//         var test = 'É-é';
//         console.log('test-sanitise===>' + test + '===>' + sanityseAccents(test,-1));
        
//        var test = ans.proposition.toUpperCase();
//        console.log('sanitise===>' + test + '===>' + sanityseAccents(test,false));
            tExp.push(ans.proposition);
        }
// alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);

    }
    this.data.allExp = tExp;
    
//  var keys = Object.keys(tItems);    
//  alert("prepareData : - nbSoluces = " + nbSoluces);

//    this.data.items = tItems;
    this.data.nbSoluces = nbSoluces;
    this.data.nbClicks = 0;
    
}

//---------------------------------------------------
onEnter(){
    super.onEnter();
}
//---------------------------------------------------
onFinalyse() {
    super.onFinalyse();
}       
//---------------------------------------------------
getScoreByProposition ( answerContainer){
var points = 0;
var bolOk = 1;
var idx = 0;

    var  currentQuestion = this.question;
    
    var tReponses = document.getElementById(this.idDivReponse).innerHTML.toLowerCase().split(this.sep);

    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        if(ans.points > 0) {
        idx = tReponses.indexOf(ans.proposition.toLowerCase());
            //console.log(tReponses.join('-') + '===>' + ans.proposition + '- idx =' + idx + '-' + tReponses.length);
            if(idx >= 0){
                tReponses.splice(idx, 1);
                points += ans.points*1
            }
        
        }
    }

    
    this.points = points;    
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
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0) {
  
            tReponses.push(rep.proposition);    
        }
    }
    var reponses = tReponses.join(this.sep);
    document.getElementById(this.idDivReponse).innerHTML = reponses;
    return true;
  
  } 

/* ************************************
*
* **** */
  showBadAnswers()
  {
    var tRep = [];
    var idx = 0;
    
    var tLetters =  this.data.allExp;
    for (var h=0; h < this.data.nbSoluces; h++){
        idx = getRandom(tLetters.length-1, 0); 
//alert(tLetters.length + "-" + idx);
        tRep.push(tLetters[idx]);
    }
    
    
    document.getElementById(this.idDivReponse).innerHTML=tRep.join(this.sep);
    //document.getElementById(this.idDivReponse).innerHTML="$-£-?";
    return true;
  } 
 
 getDisposition(disposition, tableId, varByRef){
var currentQuestion = this.question;
var tpl = "";
var hr = '<hr class="quiz-style-two">';
var divAnswer = `<div name='${name}' id='${this.idDivReponse}' selected>{answer}</div>`;

var isImage = currentQuestion.image;
var isWords = (this.data.tWords.length > 0);
var isDirective = currentQuestion.options.directive;
var tokenImage = '';

//alert(disposition);
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
    switch(disposition)     {
    
    default:
    case 'disposition-01':
        if(isImage) {tpl += '{img}';}
        if(isWords) {tpl += '<center><span words>{words}</span></center>';}
        tpl += divAnswer;
        tpl += hr;
        if(isDirective) {tpl += '<center><span directive>{directive}</span></center>'}
        tpl += '<center><span>{alphanum}</span></center>';
        break;

    case 'disposition-02':
        if(isWords) {tpl += '<center><span words>{words}</span></center>';}
        tpl += divAnswer;
        if(isImage) {tpl += '{img}';}
        tpl += hr;
        if(isDirective) {tpl += '<center><span directive>{directive}</span></center>'}
        tpl += '<center><span>{alphanum}</span></center>';
        break;

    case 'disposition-03':
        tpl += divAnswer;
        if(isImage) {tpl += '{img}';}
        if(isWords) {tpl += '<center><span words>{words}</span></center>';}
        tpl += hr;
        if(isDirective) {tpl += '<center><span directive>{directive}</span></center>'}
        tpl += '<center><span>{alphanum}</span></center>';
        break;

    case 'disposition-04':
        if(isImage){
            tpl += "<table><tr><td>{img}</td>"
                +  `<td>${divAnswer}</td></tr></table>`;
            if(isWords) {tpl += '<center><span words>{words}</span></center>';}
            if(isDirective) {tpl += '<center><span directive>{directive}</span></center>'}
            tpl += '<center><span>{alphanum}</span></center>';
        
        }else{
            tpl = this.getDisposition('disposition-03', tableId, varByRef);
        }
        break;

    case 'disposition-05':
        if(isImage){
            tpl += `<table><tr><td>${divAnswer}</td>`
                 + "<td>{img}</td></tr></table>";
            if(isWords) {tpl += '<center><span words>{words}</span></center>';}
            if(isDirective) {tpl += '<center><span directive>{directive}</span></center>'}
            tpl += '<center><span>{alphanum}</span></center>';
        
        }else{
            tpl = this.getDisposition('disposition-03', tableId, varByRef);
        }
        break;

    }
    return tpl;
}

  /* *********************************************
  
  ************************************************ */
getDisposition_old(disposition_old, tableId, varByRef){
var currentQuestion = this.question;
var tpl = "";
var hr = '<hr class="quiz-style-two">';
var divAnswer = `<div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>{answer}</div>`;
var divDirective = '<span class="alphaSimple_directive">{directive}</span>';
//alert(disposition);
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
    switch(disposition)     {
    
    default:
    case 'disposition-01':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
              <tr><td>${divAnswer}</td></tr>
              <tr><td>${hr}${divDirective}</td></tr>
              <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-02':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
              <tr><td class='alphaSimple_words'>{words}</td></tr>
              <tr><td>${divAnswer}</td></tr>
              <tr><td>${divDirective}</td></tr>
              <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-03':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
              <tr><td>${divAnswer}</td></tr>
              <tr><td class='alphaSimple_words'>{words}</td></tr>
              <tr><td>${hr}${divDirective}</td></tr>
              <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-04':
        varByRef.sep = "<br>";
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
              <tr><td>${divAnswer}</td></tr>
                  <td class='alphaSimple_words'>{words}</td>
              <tr><td colspan='2'>${hr}${divDirective}</td></tr>
              <tr><td colspan='2'>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-11':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr><td>{img}</td></tr>
                <tr><td>${divAnswer}</td></tr>
                <tr><td>${hr}${divDirective}</td></tr>
                <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-12':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr><td>${divAnswer}</td></tr>
                <tr><td>{img}</td></tr>
                <tr><td>${hr}${divDirective}</td></tr>
                <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-13':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr>
                    <td style='width:25%'>{img}</td>
                    <td style='width:75%'>${divAnswer}</td>
                </tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td colspan='2'>{alphanum}</td></tr>
              </tbody></table>`;
        break;
    case 'disposition-13':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr>
                    <td style='width:75%'>${divAnswer}</td>
                    <td style='width:25%'>{img}</td>
                </tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td colspan='2'>{alphanum}</td></tr>
              </tbody></table>`;
        break;
    case 'disposition-21':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr><td>{img}</td>
                <td class='alphaSimple_words'>{words}</td>
                <tr><td>${divAnswer}</td></tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td>{alphanum}</td></tr>
            </tbody></table>`;
        break;
    case 'disposition-22':
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr>
                    <td style='width:25%'>{img}</td>
                    <td style='width:75%'>${divAnswer}</td>
                </tr>
                <tr><td colspan='2' class='alphaSimple_words'>{words}</td></tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td colspan='2'>{alphanum}</td></tr>
              </tbody></table>`;
        break;
    case 'disposition-23':
        varByRef.sep = "<br>";
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr>
                    <td style='width:25%' class='alphaSimple_words'>{words}</td>
                    <td style='width:75%'>{img}</td>
                </tr>
                <tr><td colspan='2'></td>${divAnswer}</tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td colspan='2'>{alphanum}</td></tr>
              </tbody></table>`;
        break;
    case 'disposition-24':
        varByRef.sep = "<br>";
        tpl = `<table  name='${tableId}' id='${tableId}' class='alphaSimple'><tbody>
                <tr>
                    <td style='width:75%'>{img}</td>
                    <td style='width:25%' class='alphaSimple_words'>{words}</td>
                </tr>
                <tr><td colspan='2'></td>${divAnswer}</tr>
                <tr><td colspan='2'>${hr}${divDirective}</td></tr>
                <tr><td colspan='2'>{alphanum}</td></tr>
              </tbody></table>`;
        break;
    }
    return tpl;
}
} // ----- fin de la classe ------

//-------------------------------
//----- Evenements du slide -----
//-------------------------------
function eventOnClickAlpha(slideNumber, newValue){
    var clQuestion = quizard[slideNumber];
    
var idReponse = clQuestion.idDivReponse;
var nbSoluces = clQuestion.data.nbSoluces;
var sep = clQuestion.sep;

//alert("eventOnClickAlpha - "  + " - "  + idReponse + ' - ' + newValue);
    var obRep = document.getElementById(idReponse);
    var tRep = obRep.innerHTML.split(sep);
//alert (obRep.innerHTML);    
    

    if (nbSoluces == 1){
      obRep.innerHTML = newValue;
      clQuestion.data.nbClicks++;
    }else{
        var tRep = obRep.innerHTML.split(sep);
console.log (nbSoluces + "-" + tRep.length);
        tRep.push(newValue);
        while (tRep.length > nbSoluces){
            tRep.shift();
        }
        obRep.innerHTML = tRep.join(sep);
        clQuestion.data.nbClicks++;

    }

    if(clQuestion.question.options.nextSlideDelai * 1 > 0 && clQuestion.data.nbClicks == nbSoluces){
        quiz_show_avertissement( clQuestion.question.options.nextSlideMessage , clQuestion.question.options.nextSlideDelai, clQuestion.question.options.nextSlideBG);
    }  

}

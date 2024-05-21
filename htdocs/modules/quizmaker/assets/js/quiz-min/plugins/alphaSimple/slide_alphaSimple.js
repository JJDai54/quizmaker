
 /*******************************************************************
  *                     _alphaSimple
  * *****************************************************************/
class alphaSimple extends quizPrototype{
name = 'alphaSimple';
typeInput = 'alpha';  
idDivReponse = '';
sep = "-"; //separateurs pour les réponses
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
var varByRef = { sep: " - " }; //modifie par getDisposition et utiliser pour aligner les mots h ou v

    var name = this.getName();
    this.idDivReponse = this.getName() + '-letterSelected';
       
    const htmlArr = [];
    htmlArr.push(`<div id="${name}-alpha" style="text-align:left;margin-left:00px;">`);
    
//alert(currentQuestion.options.join("\n"));
//alert("getInnerHTML - disposition : " + currentQuestion.options.disposition);
    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId('tbl'), varByRef);
    var html = tpl.replace("{img}", this.get_img())
                  .replace("{words}", this.get_htmlWords(varByRef))
                  .replace("{answer}", "-?".repeat(this.data.nbSoluces).substring(1))
                  .replace("{directive}", currentQuestion.options.directive)
                  .replace("{alphanum}", this.get_htmlLetters());


    htmlArr.push(html);
    
    htmlArr.push(`</div>`);

//     var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
//     if(currentQuestion.image){
//         tpl = this.get_img() + tpl;
//     }
    
    return htmlArr.join("\n");
}
/* ***************************************
*
* *** */
get_htmlWords(varByRef){
var html = '<table class="alphaSimple_words"><tr>';
    return this.data.tWords.join(varByRef.sep);
}


/* ***************************************
*
* *** */
getLetters(sep = '|'){
  //alert('|' + this.idDivReponse + '|');

  var letters = setAllSepByNewSep(this.question.options.propositions, sep);  
  var tGroups = letters.split(sep + sep);  
  for(var h in tGroups){
        if(this.question.options.shuffleAnswers == 1){
            var tLetters = tGroups[h].split(sep);        
            shuffleArrayFY(tLetters);        
            tGroups[h] = tLetters.join(sep);        
        }
        var letters = tGroups.join(sep + sep);
  }  
  var tLetters = letters.split(sep);
  return tLetters;
}


/* ***************************************
*
* *** */
get_htmlLetters(){
var html = '<div class="alphaSimple_letters">';
//alert('|' + this.idDivReponse + '|');
var tLetters = this.getLetters();

    for(var k = 0; k < tLetters.length; k++){
        if (tLetters[k] == ''){
            html += '<br>'; 
        }else{
            //var onclick = `document.getElementById('${this.idDivReponse}').innerHTML='${tLetters[k]}';`;
            var onclick = `eventOnClickAlpha('${this.idDivReponse}','${tLetters[k]}',${this.data.nbSoluces},'${this.sep}');`;
            //alert('|' + onclick + '|');
            html += `<a onclick="${onclick}">${tLetters[k]}</a>`; 
        }
    
    }
 
    html += '</div>';
    return html;
}

/* ***************************************
*
* *** */
get_img(){
    var name = this.getName();
    var currentQuestion = this.question;
    return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></center>`;
//alert("get_img - disposition : " + currentQuestion.options.disposition);
}

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;
    var nbSoluces = 0;
    
    this.data.tWords = getExpInAccolades(this.question.question);;    
    
    var tItems = new Object;
    
    for(var k in currentQuestion.answers){
        
        var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);
        //alert (key);
        var key = "ans-" + k.padStart(3, '0');
        var tWP = {'key': key,
                   'word': currentQuestion.answers[k].proposition, 
                   'points' : currentQuestion.answers[k].points*1};
        tItems[key] = tWP;
        if ((currentQuestion.answers[k].points*1) > 0 ) nbSoluces += 1;
// alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);

    }

//  var keys = Object.keys(tItems);    
//  alert("prepareData : - nbSoluces = " + nbSoluces);

    this.data.items = tItems;
    this.data.nbSoluces = nbSoluces;
    
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


    var  currentQuestion = this.question;
    var tReponses = document.getElementById(this.idDivReponse).innerHTML.split(this.sep);
    for(var k in currentQuestion.answers){
        var rep = currentQuestion.answers[k];
        if(rep.points > 0) {
            for(var i in tReponses){
                if(rep.proposition == tReponses[i]){
//alert(rep.proposition + " = " + rep.points + " ===> " + points);
                    points += rep.points*1
                }
            }
        }
    }

    
    this.points = points;    
    return this.points;
  }
//---------------------------------------------------

  isInputOk (answerContainer){
//     var obs = this.getQuerySelector("input", this.getName(), this.typeInput, "checked");
//     return (obs.length > 0) ? true : false ;
    return true;
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
    
    var tLetters = this.getLetters();
    for (var h=0; h < this.data.nbSoluces; h++){
        idx = rnd(tLetters.length-1, 0); 
//alert(tLetters.length + "-" + idx);
        tRep.push(tLetters[idx]);
    }
    
    
    document.getElementById(this.idDivReponse).innerHTML=tRep.join(this.sep);
    //document.getElementById(this.idDivReponse).innerHTML="$-£-?";
    return true;
  } 
 
  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId, varByRef){
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
function eventOnClickAlpha(idReponse, newValue, nbSoluces, sep){
//alert("eventOnClickAlpha - "  + " - "  + idReponse + newValue);
    var obRep = document.getElementById(idReponse);
    var tRep = obRep.innerHTML.split(sep);
//alert (obRep.innerHTML);    

    if (nbSoluces == 1){
      obRep.innerHTML = newValue;
    }else{
        var tRep = obRep.innerHTML.split(sep);
console.log (nbSoluces + "-" + tRep.length);
        tRep.push(newValue);
        while (tRep.length > nbSoluces){
            tRep.shift();
        }
        obRep.innerHTML = tRep.join(sep);

    }
}

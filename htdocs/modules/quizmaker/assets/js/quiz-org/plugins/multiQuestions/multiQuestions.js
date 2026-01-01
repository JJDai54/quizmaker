/*******************************************************************
*                     multiQuestions
* *****************************************************************/
function getPlugin_multiQuestions(question, slideNumber){
    return new multiQuestions(question, slideNumber);
}

 /*******************************************************************
  *                     multiQuestions
  * *****************************************************************/
class multiQuestions extends Plugin_Prototype{
name='multiQuestions';  
  
//---------------------------------------------------
buildSlide (bShuffle = true){
    var currentQuestion = this.question;
    return this.getInnerHTML(bShuffle);
 }
  
/* ***************************************
*
* *** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var sep = "";
    var id = this.getName;
    var currentQuestion = this.question;
    //var width = Math.floor(600 / this.data.nbInputsMax);        
    var interligne = `<div style='width:100%;height:${currentQuestion.options.interligne}px'></div>`
    var br = this.getBr(currentQuestion.options.disposition);
    var tpl = this.getDisposition(currentQuestion.options.disposition);
    //-----------------------------------------------------
    var html = [];  
    //html.push(this.getImage());  
    
    
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var chrono = (currentQuestion.numbering > 0) ?  getNumAlpha(k, currentQuestion.numbering) : '';
        html.push(`<b>${chrono}${ans.caption}<br></b>`);
        
        switch(ans.group){
            case 1: //textbox
                html.push(this.getHtmlMultiTextbox(k, ans.inputs*1, br));
                break;
            case 2: //checkbox
            case 3: //radio
                var strType = (ans.group == 3) ? 'radio' : 'checkbox';
                html.push(this.getHtmlMultiCheckbox(k, ans.itemsArr, strType, br));          
                break;
            case 0: //listbox
            default:
                html.push(this.getHtmlMultiListbox(k, ans.itemsArr, ans.inputs*1, br));
                break;
        }
        html.push('<br><br>');
//         ans['proposArr'] = currentQuestion.answers[k].proposition.split(",");        
//         ans['intrusArr'] = currentQuestion.answers[k].buffer.split(",");        
//         ans['itemsArr'] = ans['proposArr'].concat(ans['intrusArr']);        
        
        html.push(interligne);
                
    }

//     html = html.replace ("{image}", this.getImage())
//                .replace ("{input}", this.getHtmlTextbox(sep));
    return tpl.replace('{options}', html.join("\n"))
              .replace('{image}', this.getImage())  ;



 }
//---------------------------------------------------
getHtmlMultiListbox(k, listArr, nbInp, br){
    var itemName = this.getId(k);

    var tHtml = [];
    var  size = 50/nbInp;
    var  inpWidth = (br) ? 80 : 80/nbInp;
console.log('===>getHtmlTextbox : nbInp = ' + nbInp)    ; 
    for (var j = 0; j < nbInp; j++){
        var itemId = this.getId(k, j);
      //tInp.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" style="width:${width}%">`);
      //tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" minlength="4" size="${size}" maxlength="${maxLength}">`);
      tHtml.push(getHtmlCombobox(itemName, itemId, listArr, `left style='width:${inpWidth}%;'`));
    }

 
    var sep = (br) ? '<br>' : '&nbsp;';
    return tHtml.join(sep);
}
//---------------------------------------------------
getHtmlMultiTextbox(k, nbInp, br){
    var name = this.getId(k);

    var tHtml = [];
    var  inpWidth = (br) ? 80 : 80/nbInp;
    var maxLength= (br) ? 50 : 50/nbInp;
console.log('===>getHtmlTextbox : nbInp = ' + nbInp)    ; 
    for (var j = 0; j < nbInp; j++){
      //tInp.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" style="width:${inpWidth}%">`);
      tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" style="width:${inpWidth}%;margin:2px 0px 2px 0px;" minlength="${maxLength}"   maxlength="${maxLength}">`);
    }

    var sep = (br) ? '<br>' : '&nbsp;';
    return tHtml.join(sep);
}

//---------------------------------------------------
getHtmlMultiCheckbox(k, itemsArr, strType, br){
    var tHtml = [];
    var name = this.getId(k);
    
   // var itemsArr = shuffleArray(itemsArr);
   var nbInp = itemsArr.length;
    var  inpWidth = (br) ? 80 : 80/nbInp;
    
    for (var j = 0; j < itemsArr.length; j++){
      var itemId = this.getId(k, j);
      //var sel = (j == itemDefault) ? "checked" : "" ;  
      tHtml.push(`<label class="quiz"style="width:${inpWidth}%;margin:2px 0px 2px 0px;" >
                 <input type="${strType}" id="${itemId}" name="${name}" value="${sanityseTextForComparaison(itemsArr[j])}" caption="${itemsArr[j]}">
                 ${itemsArr[j]}</label>`);

    }
    var sep = (br) ? '<br>' : '&nbsp;';
    return tHtml.join(sep);
}

//---------------------------------------------------
 prepareData(){
    
    var currentQuestion = this.question;
    
    var newSep = '|';
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var idx = 0;
            
        ans['proposArr']  = ans.proposition.split(",");      
        ans['sanitysArr'] =  sanityseTextForComparaison(setAllSepByNewSep(ans.proposition, newSep)).split(newSep);
        //ans['intrusArr']  =  sanityseTextForComparaison(setAllSepByNewSep(ans.buffer, newSep)).split(newSep);
        ans['intrusArr']  =  setAllSepByNewSep(ans.buffer, newSep).split(newSep);
    
            //supression des bonnes répopnses dans les intrus pour éviter de les avoir en double dans les listes déroulantes, les checkbox, ...
            for(var h=0; h< ans['proposArr'].length; h++){
                idx = ans['intrusArr'].indexOf(ans['proposArr'][h]);
                if (idx >= 0 ){
                    ans['intrusArr'].splice(idx,idx);
                } 
            }

        ans['itemsArr']   = shuffleArray(ans['proposArr'].concat(ans['intrusArr']));        
        
        //pour faciliter le code
        ans.typeInput = ans.group;
                
    }
    this.initMinMaxQQ(2);
}

//---------------------------------------------------
getScoreByProposition (answerContainer){
    var points = 0;
    var currentQuestion = this.question;


     for (var k=0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        var bolOk = this.getValuesArr(k, ans);
        if (bolOk) {points += ans.points;}
     }

      return points;
  }
/* ************************************
*
* **** */
/***************************************************/

 getValuesArr(k, ans){
 console.log(`===>getValuesArr : ${ans.caption}`);
    var currentQuestion = this.question;
    var obs = document.getElementsByName(this.getId(k));    
    
    var values = [];
    var bolOk = false;
    var nbRep = 0;
    var goodRepArr = ans.sanitysArr;
    
    if(ans.typeInput < 2){
        obs.forEach((obInput, index) => {
            if (values.indexOf(obInput.value) == -1){
                values.push(obInput.value);
                if (ans.sanitysArr.indexOf(sanityseTextForComparaison(obInput.value)) >= 0){
                    nbRep++;
                }
            }
        });
        bolOk = (obs.length == nbRep);
    }else if(ans.typeInput == 2){
        obs.forEach((obInput, index) => {
            if (obInput.checked){
                if (ans.sanitysArr.indexOf(obInput.value) >= 0){
                    nbRep++;
                }
                
            }
        });
        bolOk = (ans.sanitysArr.length == nbRep);
    }else{
        obs.forEach((obInput, index) => {
            if (obInput.checked){
                if (ans.sanitysArr.indexOf(obInput.value) >= 0){
                    nbRep++;
                }
                
            }
        });
        bolOk = (nbRep > 0);
    }
    
    return bolOk;
 }

/* *******************************************
* getAllReponses : renvoie les réponse à la question
* @ flag int: 0 = toutes les réponses / 1 = que les bonnes réponses
* ********** */
getAllReponses  (flag=0){

    var currentQuestion = this.question;
    var tReponses = [];
    var html = [];
    
    for (var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        var html = `<b>Question ${k} : ${ans.caption}</b>` + qbr
                 + 'Liste des réponses' + qbr
                 + ans.proposition + qbr;
        tReponses.push(html);
    }    

/*


    
    var currentQuestion = this.question;
    var tReponses = [];

    
    for (var k=0; k < currentQuestion.answers.length; k++){
        var tGroup = [];

        var tKeyPoints = sortArrayKey(this.data.subQuestions[k].keyPoints, 'a');
        //var tKeyPoints = this.data.subQuestions[k].keyPoints;
        for(var key in tKeyPoints)
        {
             tGroup.push ({'inputs':tKeyPoints[key].word,'points': tKeyPoints[key].points}) ;
        }
        tReponses.push(tGroup);
    }    
    return html.join("\n");
*/    
    return formatArray2(tReponses, '=');
}


/* ************************************
*
* **** */
/***************************************************/

 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
console.clear();    
    for (var k=0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
 console.log(`===>showGoodAnswers : ${ans.caption} - type = ${ans.typeInput}`);
        if (ans.typeInput < 2){
            this.showAnswers_listbox_and_textbox(k, ans, true);
        }else{
            this.showAnswers_checktbox_and_radio(k, ans, true);
        }
    
    }
    return true;
  } 
  
/* ************************************
*
* **** */
/***************************************************/

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getId(0);
    var name = this.getName();
    
    for (var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        if (ans.typeInput < 2){
            this.showAnswers_listbox_and_textbox(k, ans, false);
        }else{
            this.showAnswers_checktbox_and_radio(k, ans, false);
        }
    
    }
    return true;
  } 
/* ************************************
*
* **** */
/***************************************************/

 showAnswers_listbox_and_textbox(k, ans, goodbad){
 console.log(`===>showAnswers_listbox_and_textbox : ${ans.caption}`);
    var currentQuestion = this.question;
    var listArr = (goodbad) ? shuffleArray(ans.proposArr) : shuffleArray(ans.itemsArr);  
    
    for (var h = 0; h < (ans.inputs*1); h++){
        var itemId = this.getId(k, h);
        var ob = document.getElementById(itemId);
        ob.value = listArr[h];
    }

    
 }
/* ************************************
*
* **** */
/***************************************************/

 showAnswers_checktbox_and_radio(k, ans, goodbad){
 console.log(`===>showAnswers_listbox_and_textbox : ${ans.caption}`);
    var currentQuestion = this.question;
    var listArr = (goodbad) ? shuffleArray(ans.proposArr) : shuffleArray(ans.itemsArr);  
    var obs = document.getElementsByName(this.getId(k));
    
    if(goodbad){
        obs.forEach( (obInput, index) => {
            obInput.checked = (ans.sanitysArr.indexOf(obInput.value) >= 0);
        })
    }else{
        obs.forEach( (obInput, index) => {
            obInput.checked = getRandomBool();
        })
    }
    
//     for (var h = 0; h < (ans.inputs*1); h++){
//         var itemId = this.getId(k, h);
//         var ob = document.getElementById(itemId);
//         ob.value = listArr[h];
//     }

    
 }

  /* *********************************************
  
  ************************************************ */
getBr(disposition){

    switch(disposition){
    case 'disposition-11':
    case 'disposition-21':
        var br = true;
        break;
    default:
    case 'disposition-10':
    case 'disposition-20':
        var br = false;
        break;
    }
    return br;
}    
  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, contenairId){
    var currentQuestion = this.question;

var tpl="";

    if(this.isImage()) {disposition += '-img';}
    
// alert(disposition)  ; 
    switch(disposition){
    case 'disposition-10-img':
    case 'disposition-11-img':
        tpl = `<center>{image}</center>{options}`;
        break;
    
    
    
    case 'disposition-20-img':
    case 'disposition-21-img':
        tpl = `<center><table><tr>
               <td style='padding-right:12px;'>{image}</td>
               <td>{options}</td>
               </tr></table></center>`;
        
        break;
        
    default:
    case 'disposition-10':
    case 'disposition-20':
    case 'disposition-11':
    case 'disposition-21':
        tpl = `{options}`;
        break;
    }
    
    
    

    //return `${disposition}<br>` + tpl;
    return tpl;
}
 
} // ----- fin de la class ------


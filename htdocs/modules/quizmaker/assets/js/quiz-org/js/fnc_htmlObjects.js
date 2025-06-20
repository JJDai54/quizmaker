/* ************************************************************ */
/* ******** functions de construction d'objets HTML  ********** */
/* ************************************************************ */

function getHtmlCombobox(name, id, tItems, extra="", addBlank=false){
    var tHtml = [];
    tHtml.push(`<SELECT id="${id}" name="${name}" ${extra}>`);
                                                                  
    if (addBlank)  
        {tHtml.push(`<OPTION VALUE="">`)}
    else
        {tHtml.push( '<option value="" selected disabled hidden></option>');}
    
    for (var j=0; j < tItems.length; j++){
        tHtml.push(`<OPTION VALUE="${tItems[j]}">${tItems[j]}`);
    }
    tHtml.push(`</SELECT>`);
    return tHtml.join("\n");

}
function fillListObject(obList, tItems, itemDefault = -1, addBlank=false){
    if(typeof( obList) !== 'object') obList = document.getElementById(obList);
    obList.innerHTML = "";
    for (h=0; h < tItems.length; h++){
        var option = document.createElement("option");
        option.text = tItems[h];
        option.value = tItems[h];
        obList.add(option);
    }
    obList.selectedIndex = itemDefault;
}
function getHtmlRadio(name, tItems, itemDefault = -1, numerotation, offset=0, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
      var sel = (j == itemDefault) ? "checked" : "" ;  
      tHtml.push(`<label>
                 <input type="radio" name="${name}" id="${name}-${j}" value="${j}" ${sel} ${extra} caption="${tItems[j]}">
                 ${getNumAlpha(j*1,numerotation, offset)}${tItems[j]}
                 </label><br>`);

    }
    return tHtml.join("\n");

}

function getHtmlCheckbox(name, tItems, itemDefault = -1, numerotation, offset, extra="", sep=''){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
      var sel = (j == itemDefault) ? "checked" : "" ;  
      tHtml.push(`<label class="quiz" >
                 <input type="checkbox" id="${name}-${j}" name="${name}" value="${j}" ${sel} ${extra} caption="${tItems[j]}">
                 ${getNumAlpha(j*1,numerotation,offset)}${tItems[j]}
                 </label>${sep}`);

    }
    return tHtml.join("\n");

}

function getHtmlListbox(name, id, tItems, nbRows, itemDefault = -1, numerotation, offset, extra=""){
    var tHtml = [];


     tHtml.push(`<SELECT id="${id}" name="${name}" class="question-combobox"  size="${nbRows}" ${extra}" style='height:300px'>`);
  
      for(var j in tItems){
          tHtml.push(`<OPTION VALUE="${tItems[j]}">${tItems[j]}</OPTION>`);
      }
      tHtml.push(`</SELECT>`);

    return tHtml.join("\n");
}
function getHtmlTextbox(name, txtClass = "", numerotation, offset, extra=""){
  
    
    for (var j=0; j < tItems.length; j++){
 
      tHtml.push(`<label>
            ${getNumAlpha(j*1,numerotation,offset)}<input type="text"  id="${name}-${j}" name="${name}" value="${tItems[j]}" class="${txtClass}" ${extra}>
          </label>`);

    }
    return tHtml.join("\n");

}

function getHtmlTextbox1(name, tItems, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
 
      tHtml.push(`<label>
            ${getNumAlpha(j*1,numerotation,offset)}<input type="text"  id="${name}-${j}" name="${name}" value="${tItems[j]}" class="${txtClass}" ${extra}>
          </label>`);

    }
    return tHtml.join("\n");

}

function getHtmlTextbox2(name, alength, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < alength; j++){
 
      tHtml.push(`<label>
            ${getNumAlpha(j*1,numerotation,offset)}<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>
          </label>`);

    }
    return tHtml.join("\n");

}

function getHtmlTextbox3(name, tItems, nbInput, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var i=0; i < tItems.length; i++){
        tHtml.push(`<label>${getNumAlpha(i*1,numerotation,offset)}${tItems[i].caption}<br>`);
        for (var j=0; j < nbInput; j++){
            tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>`);

        }
        tHtml.push(`</label>`);
    }
    return tHtml.join("\n");

}
/*

function getHtmlSpanZZZ(name, tItems, numerotation=3, offset =0, spanClass = 'slide-label', extra="", sep="<br>"){  
    var tHtml = [];

    for (var j=0; j < tItems.length; j++){
      tHtml.push(`${getNumAlpha(j*1,numerotation,offset)} - ${tItems[j]}`);
    }

    return  `<span class="${spanClass}" ${extra}>` 
            + tHtml.join(sep + "\n")
            + `</span>` + "\n";

}
*/
function getHtmlSpan(name, tItems, numerotation=3, offset =0, extra="", spanClass = 'slide-label', sep="<br>"){  
    var tHtml = [];
    

    for (var j=0; j < tItems.length; j++){
      tHtml.push(`<span class="${spanClass}" ${extra}>${getNumAlpha(j*1,numerotation,offset)} - ${tItems[j]}</span>`);
    }

    return tHtml.join(sep + "\n");

}
function getHtmlSpan2(name, selecteur, tItems, numerotation=3, offset = 0, extra="", sep="<br>"){  
    var tHtml = [];
    

    for (var j=0; j < tItems.length; j++){
      tHtml.push(`<div  ${extra}>${getNumAlpha(j*1,numerotation,offset)} - ${tItems[j]}</div>`);
    }

    return tHtml.join(sep + "\n");

}

///////////////////////////////////////////

function formatReponseTD(arr, sep='===>', showUnite = false, colonneFalse=-1){
    var k = 0;
    if(arr[k] == '<hr>'){
        return `<td colspan="${arr.length+2}">${arr[k]}</td>`;    
    }
    var unite = (showUnite) ? 'points' : '';
    var styleGoodAns = "color:blue;";
    if(colonneFalse >= 0){
        var styleBadAns  = "color:red;background-color:#FFCCCC;";
        var styleAns = (arr[colonneFalse]*1 > 0) ? styleGoodAns : styleBadAns;    
    }else{
        var styleAns = styleGoodAns;
    }
    
    
    //-----------------------------------------
    var tHtml = [];

   // var tdClass = (arr[1]*1 > 0) ? 'quiz_div_popup_good_answer' : 'quiz_div_popup_bad_answer';
    for (var k = 0; k < arr.length; k++){
    
        var td = `<td style='${styleAns}'>${arr[k]}</td>`;
        //var td = `<td class='${tdClass}'>${arr[k]}</td>`;
        if (k < arr.length-1) td += `<td style='${styleAns}'>${sep}</td>`;
        if (unite !== '' && k == arr.length-1) td += `<td style='${styleAns}'>${unite}</td>`;
        tHtml.push(td);
    }
    
    var tdArr = tHtml.join("\n");
    return `<tr style="background-color:yellow;">${tdArr}</tr>`;

}
//-----------------------------------------------------------------------
function formatArray0(tReponses, sep='===>', showUnite = false, colonneFalse=-1){
    var tplTable = "<table class='showResult'>{content}</table>";
    
    var tHtml = [];
    for (var k=0; k < tReponses.length; k++){
        //tHtml.push(tplTD.replace("{word}",tReponses[k][0]).replace("{sep}",sep).replace("{points}",tReponses[k][1]).replace("{unite}", unite));
        
        //tHtml.push(formatReponseTD (tReponses[k][0], tReponses[k][1], unite, sep));
        tHtml.push(formatReponseTD (tReponses[k], sep, showUnite, colonneFalse));
    }
    return tplTable.replace("{content}", tHtml.join("\n"));;
}
//-----------------------------------------------------------------------

function formatArray1(tReponses, sep='===>', unite="points"){
    var tplTable = "<table class='showResult'>{content}</table>";
    
    var tHtml = [];
    for (var k=0; k < tReponses.length; k++){
        //tHtml.push(tplTD.replace("{word}",tReponses[k].reponse).replace("{sep}",sep).replace("{points}",tReponses[k].points));
        
        tHtml.push(formatReponseTD (tReponses[k].inputs, tReponses[k].points, sep, unite));
    }
    return tplTable.replace("{content}", tHtml.join("\n"));
}
//-----------------------------------------------------------------------

function formatArray2(tReponses, sep='===>', unite="points"){
    var tplDblTable = "<table><tr>{content}</tr></table>";
    var tplDblTD = "<td><table class='showResult'>{content}</table></td>";
    var tplTD = `<tr><td>{word}</td><td>{sep}</td><td>{points} ${quiz_messages.points}</td></tr>`;
    
    var tHtml = [];
    for (var j=0; j < tReponses.length; j++){
        var tTdHtml = [];
        for (var k=0; k < tReponses[j].length; k++){
            tTdHtml.push(tplTD.replace("{word}",tReponses[j][k].inputs).replace("{sep}",sep).replace("{points}",tReponses[j][k].points));
        }
        tHtml.push(tplDblTD.replace('{content}',tTdHtml.join("\n")));
    }
    
    
    return tplDblTable.replace("{content}", tHtml.join("\n"));
/*
    
    tplReponseTable : "<table>{content}</table>",
*/    
    

}

//-------------------------------------------------

function  clearfillCollection(name, fillWithExp="")
  {
    var name = currentQuestion.answers[0].name;

    const selector = `input[name=${name}]`;
    const obs =  document.querySelectorAll(selector) ;
    for (var i=0; i < obs.length; i++) {
        obs[i].value = fillWithExp;
    }

    return true;
  
  } 
 

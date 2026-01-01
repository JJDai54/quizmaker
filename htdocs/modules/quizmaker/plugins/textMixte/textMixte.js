
console.log("js textMixte chargé");
/*
Il n'est pire aveugle que celui qui ne veut pas voir.\nIl n'est pire sourd que celui qui ne veut pas entendre.
*/

//-------------------------------------------------------
function textMixte_addAccolades(idTexteArea){

    //alert ("textMixte_addAccolades : " + idTexteArea);
    var obText = document.getElementById(idTexteArea);
//alert(idTexteArea + "\n" + obText.value);
    var selStart = obText.selectionStart;
    var selEnd = obText.selectionEnd;
    var selLen = selEnd - selStart;
    
    if(selLen == 0){
        var allChar = " .,;:?!'";
        selEnd = getNextChar(obText.value, selStart, allChar);
        selStart =  getPreviousChar(obText.value, selStart, allChar);
        selLen = selEnd - selStart;
        //alert(`Positions : ${selStart} - ${selEnd} = ${selLen}`);   
    }
    //textMixte_getSelStartEnd(idTexteArea, selStart, selLen);   
    
    
var selection = obText.value.substr(selStart, selLen); 
    if (selection.substr(selection.length - 1) == " ") { selLen--;selEnd--;}
    
    var newTxt = obText.value;
console.log ("===>textMixte_addAccolades\nselection = " + selection + `/===>${selStart}-${selEnd}-${selLen}`);     
    if (selEnd > selStart){
    
    newTxt = newTxt.substr(0,selStart) + '{' + newTxt.substr(selStart, selLen) + '}' + newTxt.substr(selEnd);
    newTxt = newTxt.replaceAll('{{','{').replaceAll('}}','}');
    obText.value = newTxt;
    }
    //alert(obText.value + "\nStart = " + selStart + "\nEnd = " + selEnd );
    console.log (obText.value + "\nStart = " + selStart + "\nEnd = " + selEnd + "\nlen = " + selLen);
    textMixte_updateButtons(idTexteArea);
}
//-------------------------------------------------------
function getNextChar(exp, currentPos, nextCharsToFind = ' , ; .'){
    var i = -1;
    var j = exp.length;
    
    for (var h = 0; h < nextCharsToFind.length; h++){
    //alert('recherche de : ' + nextCharsToFind[h]);
        i = exp.indexOf(nextCharsToFind[h], currentPos);
        if (j > i && i >= 0) j = i;
        //alert(`getNextChar : ${h} - ${currentPos} - ${i} - ${j}`);
    }
    return j;
}

function getPreviousChar(exp, currentPos, nextCharsToFind = ' , ; .'){
    var i = -1;
    var j = 0;
    
    for (var h = 0; h < nextCharsToFind.length; h++){
        i = exp.lastIndexOf(nextCharsToFind[h], currentPos)+1;
        if (j < i && i >= 0) j = i;
        //alert(`getPreviousChar : ${h} - ${currentPos} - ${i} - ${j}`);
    }
    return j;
}


//-------------------------------------------------------
function textMixte_removeAccolades(idTexteArea){

    //alert ("textMixte_removeAccolades : " + idTexteArea);
    var obText = document.getElementById(idTexteArea);
    
    var selStart = obText.selectionStart;
    var selEnd = obText.selectionEnd;
    
    var posAcc1 = obText.value.indexOf('}', selEnd);
    if (posAcc1 == -1) return false;
    var posAcc2 = obText.value.indexOf('{', selEnd);
    var posAcc3 = obText.value.lastIndexOf('{', selEnd);
    console.log("1 = " + posAcc1 + "\n2 = " + posAcc2 + "\n3 = " + posAcc3);
    if (posAcc1 > posAcc2 && posAcc2 != -1) return false;
    
    console.log ("posAcc1 = " + posAcc1);
    
    var newTxt = obText.value; 
    
    var newTxt = newTxt.substr(0,posAcc3) 
              +  newTxt.substr(posAcc3+1 , posAcc1 - posAcc3 -1) 
              +  newTxt.substr(posAcc1+1) ;
    obText.value = newTxt;
    textMixte_updateButtons(idTexteArea);
}

//-------------------------------------------------------
function textMixte_ClearAccolades(idTexteArea, msg="Confirmer"){

    var r = confirm(msg);
    //alert ("textMixte_ClearAccolades : " + idTexteArea);
    if (r){
      var obText = document.getElementById(idTexteArea);
      obText.value = obText.value.replaceAll('{', '').replaceAll('}', '');
      textMixte_updateButtons(idTexteArea);
    }
}

//-------------------------------------------------------
function textMixte_updateButtons(idTexteArea){

// alert('textMixte_updateButtons' + "\n" 
//      + '->idTexteArea : ' + idTexteArea + "\n"
//      + '->btnAdd : ' + idTexteArea + '[addAccollades]');
    console.log ("textMixte_updateButtons : " + idTexteArea);
    var obText = document.getElementById(idTexteArea);
    var btnAdd = document.getElementById(idTexteArea + '[addAccollades]');    
    var btnRemove = document.getElementById(idTexteArea + '[removeAccollades]');    
    var btnClear = document.getElementById(idTexteArea + '[clearAccollades]');    
    
    var posAcc1 = obText.value.indexOf('{');
    var posAcc2 = obText.value.indexOf('}');
    
    
    textMixte_setButton2(idTexteArea, true, true, true);
    
    
    
    
    console.log (`posAcc1 = ${posAcc1} - posAcc2 = ${posAcc2}`);
    if (posAcc1 == -1 && posAcc2 == -1){
//         textMixte_setButton(btnAdd,true);
//         textMixte_setButton(btnRemove,true);
         textMixte_setButton(btnClear,false);
//        textMixte_setButton2(idTexteArea, true, false, false);
    }
    
    var selStart = obText.selectionStart;
    var selEnd = obText.selectionEnd;
    
    var h = obText.value.lastIndexOf('\{', selStart);
    var i = obText.value.lastIndexOf('\}', selStart);
    var posAcc1 = (h>i) ? h: -1;
    
    
    var h = obText.value.lastIndexOf('\{', selEnd);
    var i = obText.value.indexOf('\}', selEnd);
    var posAcc2 = (h<i) ? h: -1;
    
    if(posAcc1 < posAcc2 || posAcc1==-1 || posAcc2==-1) {
        textMixte_setButton(btnRemove,false);
    }
    
console.log(`------------------------------------------`);    
console.log(`selStart = ${selStart} - selEnd = ${selEnd}`);    
console.log(`posAcc1 = ${posAcc1} - posAcc2 = ${posAcc2}`);    
console.log(`h = ${h} - i = ${i}`);    
    if(selStart >= selEnd) {
        textMixte_setButton(btnAdd,false);
    }
 
 /*
    var selection =  obText.value.substr(selStart, selEnd - selStart);
    var h = selection.lastIndexOf('\{');
    var i = selection.indexOf('\}');
 */
    
    
    
    var selStart = obText.selectionStart;
    var selEnd = obText.selectionEnd;
        var h1 = getNextChar(obText.value, selEnd, '{');
        var h2 = getNextChar(obText.value, selEnd, '}');
        h = (h1 > h2 && h2 != -1) ? 1 : -1;
        console.log(`Positions : ${h1} - ${h2} = ${h}`);   
        
        h1 =  getPreviousChar(obText.value, selStart, '{');
        h2 =  getPreviousChar(obText.value, selStart, '}');
        i = (h1 > h2 && h2 != -1) ? 1 : -1;
        selLen = selEnd - selStart;
        //alert(`Positions : ${selStart} - ${selEnd} = ${selLen}`);   
        console.log(`Positions : ${h1} - ${h2} = ${i}`);   
    
    
    
    
//console.log(`selection = ${selection}`);    
console.log(`Positions : h = ${h} - i = ${i}`);    
    if(h > -1 || i > -1 ) {
        textMixte_setButton(btnAdd,false);
    }else{
        textMixte_setButton(btnAdd,true);
    }
 
    
 
 
    
//     
//     
//     
//     if (posAcc1 == -1) return false;
//     var posAcc2 = obText.value.indexOf('{', selEnd);
//     var posAcc3 = obText.value.lastIndexOf('{', selEnd);
//     
//     
//     var btn;
//     btn = document.getElementById(idTexteArea + '[addAccollades]');
//     btn.disabled = true;
//     btn.style.background = colorOn;
//     
//     
//     document.getElementById(idTexteArea + '[removeAccollades]').disabled = true;
//     document.getElementById(idTexteArea + '[clearAccollades]').disabled = true;


}

//-------------------------------------------------------
function textMixte_setButton2(idTexteArea, bAdd, bRemove, bClear){
    console.log('textMixte_setButton2->idTexteArea : ' + idTexteArea); 
    textMixte_setButton(document.getElementById(idTexteArea + "[addAccollades]") , bAdd);
    textMixte_setButton(document.getElementById(idTexteArea + "[removeAccollades]") , bRemove);
    textMixte_setButton(document.getElementById(idTexteArea + "[clearAccollades]") , bClear);
    
}
//-------------------------------------------------------
function textMixte_setButton(btn, etat){
if(!btn) return false;
    //console.log('textMixte_setButton->idTexteArea : ' + btn.id); 
    var colorOn = 'lime';
    var colorOf = 'grey';
    
    if (etat == true){
        btn.disabled = false;
        btn.style.background = colorOn;
    }else{
        btn.disabled = true;
        btn.style.background = colorOf;
    }
}
//-------------------------------------------------------
function textMixte_verif(idTexteArea, msgErr="zzz"){

    var obText = document.getElementById(idTexteArea);
    
    //console.log("===>textMixte_verif" + "->" + idTexteArea);
    
        var h = 0;
        var i = 0;
        var posAO1 = 0;
        var posAF1 = 0;
        var posAO2 = 0;
        var posAF2 = 0;
        while (h < obText.value.length){
            if(obText.value.substr(h,1) == '{' ) {
                posAO2 = posAO1;
                posAO1 = h;
                i++;
            }else if(obText.value.substr(h,1) == '}' ){
                posAF2 = posAF1;
                posAF1 = h;
                i--;
            }
//console.log (`h = ${h} - i = ${i} - posAO1 = ${posAO1} - posAF1 = ${posAF1}`)  ;
            if (i < 0 || i > 1) break;
            h++;
        }
        
        if (i < 0){
            alert(msgErr);
            obText.selectionStart = posAF2;
            selEnd = obText.selectionEnd = posAF1+1;
            setTimeout(textMixte_setFocus, 200, idTexteArea);
        }else if (i > 1){
            alert(msgErr);
            obText.selectionStart = posAO2;
            selEnd = obText.selectionEnd = posAO1+1;
            setTimeout(textMixte_setFocus, 200, idTexteArea);
        }
  
                
    
        
}
//-------------------------------------------------------
function textMixte_setFocus(idTexteArea){

    var obText = document.getElementById(idTexteArea);
        obText.focus();

}

//-------------------------------------------------------
function textMixte_addTextDefault(idTexteArea, exemple){

    var obText = document.getElementById(idTexteArea);
    if (exemple == 1){
        obText.value = "Il n'est pire aveugle que celui qui ne veut pas voir.\nIl n'est pire sourd que celui qui ne veut pas entendre.";
    }else{
        obText.value = "Il n'est pire {aveugle} que celui qui ne veut pas {voir}.\nIl n'est pire sourd que celui qui ne veut pas entendre.";
    }
    
    var btnAdd = document.getElementById(idTexteArea + '[addAccollades]');    
    var btnRemove = document.getElementById(idTexteArea + '[removeAccollades]');    
    var btnClear = document.getElementById(idTexteArea + '[clearAccollades]');    
    
    textMixte_updateButtons(idTexteArea);
//     textMixte_verif(idTexteArea);
//     
         obText.focus();
// 
//         //obText.select(0,12);
//         obText.selectionStart = 3;
//         selEnd = obText.selectionEnd = 12;

    
}

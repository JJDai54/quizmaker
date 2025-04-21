
console.log("js textareaMixte chargé");
/*
Il n'est pire aveugle que celui qui ne veut pas voir.\nIl n'est pire sourd que celui qui ne veut pas entendre.
*/

//-------------------------------------------------------
function textareaMixte_addAccolades(racine){

idTexteArea = racine + '[proposition]';


    //alert ("textareaMixte_addAccolades : " + idTexteArea);
    var obText = document.getElementById(idTexteArea);
    var selStart = obText.selectionStart;
    var selEnd = obText.selectionEnd;
    var selLen = selEnd - selStart;
var selection = obText.value.substr(selStart, selLen); 
    if (selection.substr(selection.length - 1) == " ") { selLen--;selEnd--;}
    
    var newTxt = obText.value;
console.log ("selection = /" + selection + "/");     
    if (selEnd > selStart){
    
    newTxt = newTxt.substr(0,selStart) + '{' + newTxt.substr(selStart, selLen) + '}' + newTxt.substr(selEnd);
    newTxt = newTxt.replaceAll('{{','{').replaceAll('}}','}');
    obText.value = newTxt;
    }
    //alert(obText.value + "\nStart = " + selStart + "\nEnd = " + selEnd );
    console.log (obText.value + "\nStart = " + selStart + "\nEnd = " + selEnd + "\nlen = " + selLen);
    textareaMixte_updateButtons(racine);
}
//-------------------------------------------------------
function textareaMixte_removeAccolades(racine){
idTexteArea = racine + '[proposition]';

    //alert ("textareaMixte_removeAccolades : " + idTexteArea);
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
    textareaMixte_updateButtons(racine);
}

//-------------------------------------------------------
function textareaMixte_ClearAccolades(racine, msg="Confirmer"){
idTexteArea = racine + '[proposition]';
    var r = confirm(msg);
    //alert ("textareaMixte_ClearAccolades : " + idTexteArea);
    if (r){
      var obText = document.getElementById(idTexteArea);
      obText.value = obText.value.replaceAll('{', '').replaceAll('}', '');
      textareaMixte_updateButtons(racine);
    }
}

//-------------------------------------------------------
function textareaMixte_updateButtons(racine){
idTexteArea = racine + '[proposition]';
    console.log ("textareaMixte_updateButtons : " + idTexteArea);
    var obText = document.getElementById(idTexteArea);
    var btnAdd = document.getElementById(racine + '[addAccollades]');    
    var btnRemove = document.getElementById(racine + '[removeAccollades]');    
    var btnClear = document.getElementById(racine + '[clearAccollades]');    
    
    var posAcc1 = obText.value.indexOf('{');
    var posAcc2 = obText.value.indexOf('}');
    
    
        textareaMixte_setButton2(racine, true, true, true);
    
    
    
    
    console.log (`posAcc1 = ${posAcc1} - posAcc2 = ${posAcc2}`);
    if (posAcc1 == -1 && posAcc2 == -1){
//         textareaMixte_setButton(btnAdd,true);
//         textareaMixte_setButton(btnRemove,true);
         textareaMixte_setButton(btnClear,false);
//        textareaMixte_setButton2(racine, true, false, false);
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
        textareaMixte_setButton(btnRemove,false);
    }
    
console.log(`------------------------------------------`);    
console.log(`selStart = ${selStart} - selEnd = ${selEnd}`);    
console.log(`posAcc1 = ${posAcc1} - posAcc2 = ${posAcc2}`);    
console.log(`h = ${h} - i = ${i}`);    
    if(selStart >= selEnd) {
        textareaMixte_setButton(btnAdd,false);
    }
 
    var selection =  obText.value.substr(selStart, selEnd - selStart);
    var h = selection.lastIndexOf('\{');
    var i = selection.indexOf('\}');
console.log(`selection = ${selection}`);    
console.log(`h = ${h} - i = ${i}`);    
    if(h > -1 || i > -1 || selEnd == selStart) {
        textareaMixte_setButton(btnAdd,false);
    }else{
        textareaMixte_setButton(btnAdd,true);
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
//     btn = document.getElementById(racine + '[addAccollades]');
//     btn.disabled = true;
//     btn.style.background = colorOn;
//     
//     
//     document.getElementById(racine + '[removeAccollades]').disabled = true;
//     document.getElementById(racine + '[clearAccollades]').disabled = true;


}

//-------------------------------------------------------
function textareaMixte_setButton2(racine, bAdd, bRemove, bClear){
    
    textareaMixte_setButton(document.getElementById(racine + "[addAccollades]") , bAdd);
    textareaMixte_setButton(document.getElementById(racine + "[removeAccollades]") , bAdd);
    textareaMixte_setButton(document.getElementById(racine + "[clearAccollades]") , bAdd);
    
}
//-------------------------------------------------------
function textareaMixte_setButton(btn, etat){
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
function textareaMixte_verif(racine,msgErr="zzz"){
idTexteArea = racine + '[proposition]';
    var obText = document.getElementById(idTexteArea);
    
    console.log("===>textareaMixte_verif" + "->" + racine);
    
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
console.log (`h = ${h} - i = ${i} - posAO1 = ${posAO1} - posAF1 = ${posAF1}`)  ;
            if (i < 0 || i > 1) break;
            h++;
        }
        
        if (i < 0){
            alert(msgErr);
            obText.selectionStart = posAF2;
            selEnd = obText.selectionEnd = posAF1+1;
            setTimeout(textareaMixte_setFocus, 200, racine);
        }else if (i > 1){
            alert(msgErr);
            obText.selectionStart = posAO2;
            selEnd = obText.selectionEnd = posAO1+1;
            setTimeout(textareaMixte_setFocus, 200, racine);
        }
  
                
    
        
}
//-------------------------------------------------------
function textareaMixte_setFocus(racine){
idTexteArea = racine + '[proposition]';
    var obText = document.getElementById(idTexteArea);
        obText.focus();

}

//-------------------------------------------------------
function textareaMixte_addTextDefault(racine, exemple){
idTexteArea = racine + '[proposition]';
    var obText = document.getElementById(idTexteArea);
    if (exemple == 1){
        obText.value = "Il n'est pire aveugle que celui qui ne veut pas voir.\nIl n'est pire sourd que celui qui ne veut pas entendre.";
    }else{
        obText.value = "Il n'est pire {aveugle} que celui qui ne veut pas {voir}.\nIl n'est pire sourd que celui qui ne veut pas entendre.";
    }
    
    var btnAdd = document.getElementById(racine + '[addAccollades]');    
    var btnRemove = document.getElementById(racine + '[removeAccollades]');    
    var btnClear = document.getElementById(racine + '[clearAccollades]');    
    
    textareaMixte_updateButtons(racine);
//     textareaMixte_verif(racine);
//     
         obText.focus();
// 
//         //obText.select(0,12);
//         obText.selectionStart = 3;
//         selEnd = obText.selectionEnd = 12;

    
}

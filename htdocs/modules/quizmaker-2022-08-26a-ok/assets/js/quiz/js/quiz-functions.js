
/*
Construit le code html de chaque question/slide.
function build_ (currentQuestion, questionNumber){
}

calcul le nombre de points obtenus d'une question/slide
function getScore_ (){
}

Renvois vrai ou faux selon que l'utilisateur a répondu ou non à la question/slide
la valeur de retour tient compte du minReponse de chaque questionr/proposition
function isInputOk_ (){
}

renvois toutes les réponses valide d'un question/slide
utilisé uniquement pour de le developpement et les faciliter les tests
function getAllReponses_ (){
}
        case "textarea":
            break;

*/
const statsTotal = {
      nbQuestions:  0,
      scoreMaxi:    0,
      scoreMini:    0,
      repondu:      0,
      score:        0,
      counter:      0,
      timer:        0
  };
const quiz_bit = {
  previous:  1,  // retour arriere
  next:      2,  // next si repondu
  popup:     4,  // popup des réponse valide
  timer:     8,  // timer
  reponses: 16   //affichage des réponses et des points en bas de page pour des tests
  };

const quiz_mode = {
    developpement: 1+2+4+16,
    trainning:     1+4,
    pro:           8+2,
    timer:         8
};  
 /*******************************************************************
  *                     typeOk
  * *****************************************************************/
function alertId(currentQuestion, msg = ""){
alert(`${currentQuestion.quizId} / ${currentQuestion.questId}`  + " - " + msg);
}


 /*******************************************************************
  *                     typeOk
  * *****************************************************************/
function typeOk(sType){
var bolOk = false;

    switch(sType){
    case "pageInfo":
    
    
    case "textarea":
    case "radioLogical":
    case "checkboxLogical":
    case "textareaInput":
    case "matchItems":
    case "textareaListbox":
    case "sortCombobox":        
    case "radioSimple":
    case "checkbox":
    case "listboxIntruders1":
    case "listboxIntruders2":
    case "radioMultiple2":
    case "multiTextbox":
    case "listboxSortItems":
        bolOk = true;
        break;


//-----------------------------------------
    
        
//-----------------------------------------
    default: bolOk = false; 
        break;
    }
    
    return bolOk;
}


/**************************************************************************
 *   GENERATION DES SLIDES
 * ************************************************************************/
  function getTplClass (className){
  var obj;

    switch (className){
    case "pageInfo":           obj = pageInfo;           break;
    case "checkbox":           obj = checkbox;           break;    
    case "radioSimple":        obj = radioSimple;        break;    
    case "radioMultiple1":     obj = radioMultiple1;     break;
    case "radioMultiple2":     obj = radioMultiple2;     break;
    case "multiTextbox":       obj = multiTextbox;       break;
    case "textarea":           obj = textarea;           break;
    case "textareaInput":      obj = textareaInput;      break;
    case "textareaListbox":    obj = textareaListbox;    break;
    case "sortCombobox":       obj = sortCombobox;       break;
    case "matchItems":         obj = matchItems;         break;
    case "radioLogical":       obj = radioLogical;       break;
    case "checkboxLogical":    obj = checkboxLogical;    break;
    case "listboxIntruders1":  obj = listboxIntruders1;  break;
    case "listboxIntruders2":  obj = listboxIntruders2;  break;
    case "listboxSortItems":   obj = listboxSortItems;   break;

    default: alert("getTplClass - Classe absente : " + className); break;
    }

//var togodo=getTplNewClass (className);
    return obj;
}

/**************************************************************************
 *   get instance de classe
 * ************************************************************************/
  function getTplNewClass (currentQuestion, chrono){
/*
  const nomDeLaclasse="toto";
const mp=new Map([["titi",titi],["toto",toto],["tata",tata]]);
const obj=new (mp.get(nomDeLaclasse))(/params si besoin ici .../);
*/


  var obj;
//    alert("getTplNewClass : " + currentQuestion.type);

    switch (currentQuestion.type){
    case "pageInfo":        obj = new (pageInfo)(currentQuestion, chrono);          break;
    case "multiTextbox":    obj = new (multiTextbox)(currentQuestion, chrono);      break;
    case "checkbox":        obj = new (checkbox)(currentQuestion, chrono);          break;
    case "radioSimple":     obj = new (radioSimple)(currentQuestion, chrono);       break;
    case "radioMultiple1":  obj = new (radioMultiple1)(currentQuestion, chrono);    break;
    case "radioMultiple2":  obj = new (radioMultiple2)(currentQuestion, chrono);    break;
    case "textarea":        obj = new (textarea)(currentQuestion, chrono);          break;
    case "textareaInput":   obj = new (textareaInput)(currentQuestion, chrono);     break;
    case "textareaListbox": obj = new (textareaListbox)(currentQuestion, chrono);   break;
    case "sortCombobox":    obj = new (sortCombobox)(currentQuestion, chrono);      break;
    case "matchItems":      obj = new (matchItems)(currentQuestion, chrono);        break;
    case "radioLogical":    obj = new (radioLogical)(currentQuestion, chrono);      break;
    case "checkboxLogical": obj = new (checkboxLogical)(currentQuestion, chrono);   break;
    
    case "listboxIntruders1":   obj = new (listboxIntruders1)(currentQuestion, chrono); break;
    case "listboxIntruders2":   obj = new (listboxIntruders2)(currentQuestion, chrono); break;
    case "listboxSortItems":    obj = new (listboxSortItems)(currentQuestion, chrono);  break;
    
/*

*/    

    default: alert("getTplNewClass - Classe absente : " + currentQuestion.type); break;
    }

//alert ("getTplNewClass ===> " + obj.question.question);
    return obj;
}


/**
 * 
 */
function decodeHTMLEntities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = text;
  return textArea.value;
}





/***********************************
 * 
 * ********************************/
function getNumAlpha(index, mode=0, offset=0){
//alert ("mode = " + mode + " - offset = " + offset);
    switch (mode){
    case 1:         //renvoi la numerotation en lettre majuscule "A B C ..."
        return String.fromCharCode((index*1)+65+offset); 
        break;
    case 2:         //renvoi la numerotation en lettre minuscule "a b c ..."
        return String.fromCharCode((index*1)+65+offset).toLowerCase(); 
        break;
    case 0:         //retourne l'index+1 pour numeroter à partir de 1
    default:        // renvoi l'index tel que
        return (index*1)+1+offset;
        break;
 
    }

 }

function duplicateArray (array) {
 var tabLanguages_2 = new Array();

   newArray = [];
   for (var i = 0; i < array.length; i++){
     newArray[i] = array[i];
   }
    return newArray;

}

function shuffleArray (array) {
  var currentIndex = array.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

function shuffleArrayFY(arr){
    for(var i =arr.length-1 ; i>0 ;i--){
        var j = Math.floor( Math.random() * (i + 1) ); //random index
        [arr[i],arr[j]]=[arr[j],arr[i]]; // swap
    }
}

/* **********************************
* shuffleArrayKeys : mélange un tableau associatif
* @arrKeys array : tableau a mélanger
* @return newArr : renvoi un nouveau tableau
* var tr1 = {'une':'one','deux':'two','trois':'three', "quatre":"foor","cinq":"five","six":"six","sept":"seven"};
* *** */
function shuffleArrayKeys(arrKeys)  
  {
  
    var keys = Object.keys(arrKeys);
    shuffleArrayFY(keys);  
  
    var newArrKeys = [];
       for(var h=0; h < keys.length; h++){
           newArrKeys[keys[h]] = arrKeys[keys[h]];
       }
       
       
    return newArrKeys;
}

/* **********************************
*
* * *** */
function conbineArrayKeys(arrKeys, arrValues)  
  {
   var newArrKeys = [];
   var newArrValues = padArray(arrValues, arrKeys.length);
   
   for(var h=0; h < arrKeys.length; h++){
       newArrKeys[arrKeys[h]] = newArrValues[h];
   }
   return newArrKeys;
}

function echoArrayKeys(arrKeys){
  for(var key in arrKeys)
  {
    // console.log(key + " = " +  arrKeys[key]);
  }

}


function padStr2Array (exp, length) {
    sep=",";
    array = exp.toString().split(sep);
    for(i=array.length; i < length; i++){
        array[i] = array[array.length-1];
    }
    return array;

}
function padArray (array, length) {
    for(i=array.length; i < length; i++){
        array[i] = array[array.length-1];
    }
    return array;

}

function sliceArray(arr){

    var middle = Math.trunc((arr.length / 2));
    var arr1 = arr.slice(0, middle); 
    var arr2 = arr.slice(-(arr.length-middle)); 
    
    return {'arr1':arr1, 'arr2':arr2};
}

function shuffleNewArray (arraySource) {
  array = duplicateArray(arraySource);

  var currentIndex = array.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

  function formatChrono (chrono, tplFormatChrono = "{minutes}:{secondes}"){
        var minutes = Math.floor(chrono/60);

        var expMinutes = minutes.toString().padStart(2, '0');
        var secondes = chrono - (minutes*60);
        var expSecondes = secondes.toString().padStart(2, '0');

        var tplFormatChrono = tplFormatChrono.replace("{minutes}", expMinutes);
        var tplFormatChrono = tplFormatChrono.replace("{secondes}", expSecondes);
        return tplFormatChrono;
  }


/* *******************************
*
* *** */
function completeArrWithwordList(words, lstWordsToAdd = "", sep = ","){
  var tWordsB = lstWordsToAdd.split(sep);
  var tWordsAll = tWordsB.concat(words);      
// console.log (lstWordsToAdd) + " - " + words.length + " - " + tWordsB.length + "-" + tWordsAll.length;
  
  return tWordsAll;
  
}


/* *******************************
*
* *** */
function transformTextWithToken(exp, lstWordToadd = "", sep=","){
var ret = {textOk:'', text:'', words:[], nbRows:0};
var textOk = '';


    ret.nbRows = exp.split("\n").length; //nombre de ligne du texte
    exp = exp.replaceAll("\n","<br>"); //avec mise en formede crlf
    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_const.regexAllLetters;
    
    var tWordsA = exp.match(regex);
    tWordsA = [...new Set(tWordsA)];


    //remplacement des mot entre accolade par des chifres entre accolade
    var exp2 = exp;
    for (var i in tWordsA) {
        var token = "{" + (i*1+1) + "}";
        var word =  quiz_messages.tplWord.replace("{word}", token);
// console.log("token = " + token +  "-" + tWordsA[i]);
        
        exp2 = exp2.replaceAll(tWordsA[i], word);

        
      tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }
    textOk = exp2;
    for (var i in tWordsA) {
        var token = "{" + (i*1+1) + "}";
        textOk = textOk.replaceAll(token, tWordsA[i]);
    }


//------------------------------------------------------------------
        
    ret.text    = exp2;         //texte avec token  : {1}{2}{3} ...
    ret.words   = tWordsA;      //Tableau des mots entre accolades
    ret.textOk  = textOk;       //texte sans les accollades
    return ret;
//-------------------------------------------------

}  

/* *******************************
*
* *** */
function transformTextWithMask(exp, mask){
var ret = {textOk:'', text:'', words:[], nbRows:0};
var textOk = '';

    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_const.regexAllLetters;

    
    var tWordsA = exp.match(regex);
    //alert (tWordsA.join('|'));
    tWordsA = [...new Set(tWordsA)]; // elimine les doublons
//    alert(tWordsA.join('|'));
//----------------------------------------------
    //remplacement des mots entre accolade par le mask defini dans options
    var exp2 = exp.replaceAll('<br>', "\n");
    ret.nbRows = exp2.split("\n").length; //nombre de lignes du texte
//    exp = exp.replaceAll("\n","<br>");



    for (var i in tWordsA) {
//alert (`${tWordsA[i]} ===> ${mask}`) ;   
        //replacement des mots entre accolade par le mask
        exp2 = exp2.replaceAll(tWordsA[i], mask);
        
        //suppression des accolades dans le tableau des mots
        tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }

    var textOk = exp.replaceAll('{', '').replaceAll('}','');


//------------------------------------------------------------------
        
    ret.text   = exp2;      //texte avec mask
    ret.words  = tWordsA;   //tableau des mots trouvés
    ret.textOk = textOk;    //text sans les accolades
    // console.log(ret.textOk);
    return ret;
//-------------------------------------------------

}  

/*********************************************
 * extra a utiiser avec checked par exemple  
 * **** */
function getObjectsByName(name, balise, typeObj = "", extra="", extra2 = "")
{ 
    var selector = `${balise}`;
    if (name != '') selector += `[name=${name}]`;;    
    if (typeObj != '') selector += `[type=${typeObj}]`;    
    if (extra != '') {
        if (extra[0] == "["){
    selector += `${extra}`    
        }else{
    selector += `:${extra}`    
        }
    }
    if (extra2 != '') {
        if (extra2[0] == "["){
    selector += `${extra2}`    
        }else{
    selector += `:${extra2}`    
        }
    }
// console.log("getObjectsByName === >" + selector);
    var obs = document.querySelectorAll(selector);
    // console.log ("getObjectsByName : " + selector + " - nb = " + obs.length);
    return obs;
}

/*********************************************
 * extra a utiiser avec checked par exemple  
 * **** */
function getObjectValueByName(name, balise, typeObj = "", extra="")
{ 
    var selector = `${balise}[name=${name}]`;
    if (typeObj != '') selector += `[type=${typeObj}]`;    
    if (extra != '') selector += `:${extra}`    
    //var ob = document.querySelectorAll(selector);
    var value = (document.querySelector(selector) || {}).value;    
// console.log("===> getObjectValueByName : " + selector + " | value = " + value);    
    return value;
 
    
}
function getObjectById(id)
{ 
    var ob = document.getElementById(id);    
    return ob;
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
 
///////////////////////////////////////////
function getHtmlCombobox(name, id, tItems, extra="", addBlank=false){
    var tHtml = [];
    tHtml.push(`<SELECT id="${id}" name="${name}" class="question-matchItems" ${extra}>`);
//         tHtml.push(`<SELECT id="${name}{${k}" name="${name}" class="question-textareaListbox" onclick="quiz_textareaListbox_event('update','${id}','${name}',${questionNumber})">`);        
    if (addBlank)  
        tHtml.push(`<OPTION VALUE="">`)
    else
        tHtml.push( '<option value="" selected disabled hidden></option>');
    
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
function getHtmlRadio(name, tItems, itemDefault = -1, numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
      var sel = (j == itemDefault) ? "checked" : "" ;  
      tHtml.push(`<label>
                 <input type="radio" name="${name}" id="${name}-${j}" value="${j}" ${sel} ${extra} caption="${tItems[j]}">
                 ${getNumAlpha(j*1,numerotation, offset)} : ${tItems[j]}
                 </label><br>`);

    }
    return tHtml.join("\n");

}

function getHtmlCheckbox(name, tItems, itemDefault = -1, numerotation, offset, extra="", sep="<br>"){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
      var sel = (j == itemDefault) ? "checked" : "" ;  
      tHtml.push(`<label class="quiz" >
                 <input type="checkbox" name="${name}" value="${j}" ${sel} ${extra} caption="${tItems[j]}">
                 ${getNumAlpha(j*1,numerotation,offset)} : ${tItems[j]}
                 </label>${sep}`);

    }
    return tHtml.join("\n");

}

function getHtmlListbox(name, id, tItems, nbRows, itemDefault = -1, numerotation, offset, extra=""){
    var tHtml = [];


     tHtml.push(`<SELECT id="${id}" name="${name}" class="question-combobox"  size="${nbRows}" ${extra}">`);
  
      for(var j in tItems){
          tHtml.push(`<OPTION VALUE="${tItems[j]}">${tItems[j]}`);
      }
      tHtml.push(`</SELECT>`);

    
//   if (typeof tItems === 'number') {
//       tHtml.push(`<label><SELECT id="${id}" name="${name}" class="question-combobox"  size="${tItems}" ${extra}">`);
//       tHtml.push(`</SELECT></label>`);
//   }else{
//      tHtml.push(`<label><SELECT id="${id}" name="${name}" class="question-combobox"  size="${tItems.length}" ${extra}">`);
//   
//       for(var j in tItems){
//           tHtml.push(`<OPTION VALUE="${tItems[j]}">${tItems[j]}`);
//       }
//       tHtml.push(`</SELECT></label>`);
//   }

    return tHtml.join("\n");
}

function getHtmlTextbox1(name, tItems, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < tItems.length; j++){
 
      tHtml.push(`<label>
            ${getNumAlpha(j*1,numerotation,offset)} : <input type="text"  id="${name}-${j}" name="${name}" value="${tItems[j]}" class="${txtClass}" ${extra}>
          </label>`);

    }
    return tHtml.join("\n");

}

function getHtmlTextbox2(name, alength, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var j=0; j < alength; j++){
 
      tHtml.push(`<label>
            ${getNumAlpha(j*1,numerotation,offset)} : <input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>
          </label>`);

    }
    return tHtml.join("\n");

}
function getHtmlTextbox2b(name, alength, sep="", txtClass = "", extra = ""){
    var tHtml = [];
    
    
    for (var j=0; j < alength; j++){
 
      tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>`);

    }
    return tHtml.join(sep + "\n");

}

function getHtmlTextbox3(name, tItems, nbInput, txtClass = "", numerotation, offset, extra=""){
    var tHtml = [];
    
    
    for (var i=0; i < tItems.length; i++){
        tHtml.push(`<label>${getNumAlpha(i*1,numerotation,offset)} : ${tItems[i].caption}<br>`);
        for (var j=0; j < nbInput; j++){
            tHtml.push(`<input type="text"  id="${name}-${j}" name="${name}" value="" class="${txtClass}" ${extra}>`);

        }
        tHtml.push(`</label>`);
    }
    return tHtml.join("\n");

}
/*

function getHtmlSpan(name, tItems, numerotation=3, offset =0, spanClass = 'slide-label', extra="", sep="<br>"){  
    var tHtml = [];

    for (var j=0; j < tItems.length; j++){
      tHtml.push(`${getNumAlpha(j*1,numerotation,offset)} - ${tItems[j]}`);
    }

    return  `<span class="${spanClass}" ${extra}>` 
            + tHtml.join(sep + "\n")
            + `</span>` + "\n";

}
*/
function getHtmlSpan(name, tItems, numerotation=3, offset =0, spanClass = 'slide-label', extra="", sep="<br>"){  
    var tHtml = [];
    

    for (var j=0; j < tItems.length; j++){
      tHtml.push(`<span class="${spanClass}" ${extra}>${getNumAlpha(j*1,numerotation,offset)} - ${tItems[j]}</span>`);
    }

    return tHtml.join(sep + "\n");

}


function formatReponseTD(arr, sep='===>', unite=''){

    
    var tHtml = [];
    for (var k = 0; k< arr.length; k++){
        tHtml.push(`<td>${arr[k]}</td>`);
    }
    if (unite !== ''){
        if (sep !== '')  tHtml.push(`<td>${sep}</td>`);
        tHtml.push(`<td>${unite}</td>`);
    }
    var tr = tHtml.join("\n");
    return `<tr>${tr}</tr>`;

}
//-----------------------------------------------------------------------
function formatArray0(tReponses, sep='===>', unite="points"){
    var tplTable = "<table class='showResult'>{content}</table>";
    
    var tHtml = [];
    for (var k=0; k < tReponses.length; k++){
        //tHtml.push(tplTD.replace("{word}",tReponses[k][0]).replace("{sep}",sep).replace("{points}",tReponses[k][1]).replace("{unite}", unite));
        
        //tHtml.push(formatReponseTD (tReponses[k][0], tReponses[k][1], unite, sep));
        tHtml.push(formatReponseTD (tReponses[k], unite, sep));
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


/* *****************************************
* sortArrayKey
* tri un tableau associatif avec une valeur :  key => value
* @keyArr object : tableau associatif a trier
* @order string : ordre de tri
* $return object : tableau associatif trié
* ****************************************** */
function sortArrayKey(KeyArr, order="ASC"){
var tvk=[];
var newKeyArray=[];
const sep = "_$_";

    for(var key in KeyArr)
    {
        var newKey = ('' + KeyArr[key]).padStart(5,'0').padEnd(25, '0') + sep + key;
        //var newKey = ('' + KeyArr[key]).padStart(5,'0').padEnd(25, '.') + sep + key;
        // console.log("===newKey = " + newKey);
        tvk.push(newKey)
    }
    
    tvk.sort();
    if (order[0].toUpperCase() != "A") tvk.reverse();
    
    for (var index in tvk){
        var t = tvk[index].split(sep);
        newKeyArray[t[1]] =  KeyArr[t[1]];
    }
     return newKeyArray;


}

/* *****************************************
* sortArrayKey1
* tri un tableau de tableau associatif :  [] => objectArray
* @arr tableau : tableau a trier
* @name name : nom de la clé sur laquel trié
* @order string : ordre de tri
* $return tableau : tableau trié
* ****************************************** */
function sortArrayObject(arr, name, order="ASC"){
var tvk=[];
var newKeyArray=[];
const sep = "_$_";

    for(var index in arr)
    {
        var newKey = ('' + arr[index][name]).padStart(5,'0').padEnd(25, '+') + sep + index;
        // console.log("===>newKey = " + newKey);
        tvk.push(newKey);
    }
    
    tvk.sort();
    if (order[0].toUpperCase() != "A") tvk.reverse();
    
    for (var index in tvk){
        var t = tvk[index].split(sep);
        newKeyArray.push(arr[t[1]*1]);
    }
     return newKeyArray;


}

/* *****************************************
* sortArrayKey2
* tri un tableau associatif de tableau associatif :  ObjectKeyArray => objectArray
* @keyArr object : tableau associatif a trier
* @name name : nom de la clé sur laquel trié
* @order string : ordre de tri
* $return object : tableau associatif trié
* ****************************************** */
function sortArrayKey2(KeyArr,name, order="ASC"){
var tvk=[];
var newKeyArray=[];
const sep = "_$_";

    for(var key in KeyArr)
    {
        var newKey = ('' + KeyArr[key][name]).padStart(5,'0').padEnd(25, '+') + sep + key;
        // console.log("===>newKey = " + newKey);
        tvk.push(newKey);
    }
    
    tvk.sort();
    if (order[0].toUpperCase() != "A") tvk.reverse();
    
    for (var index in tvk){
        var t = tvk[index].split(sep);
        newKeyArray[t[1]] =  KeyArr[t[1]];
    }
     return newKeyArray;


}

/* *****************************************
* sortArrayKey2
* tri un tableau associatif de tableau associatif :  ObjectKeyArray => objectArray
* @keyArr object : tableau associatif a trier
* @name name : nom de la clé sur laquel trié
* @order string : ordre de tri
* $return object : tableau associatif trié
* ****************************************** */
function sortArrayArray(arr, index2sort, order="ASC"){
var tvk=[];
var newArray=[];
const sep = "_$_";

    for (var k in arr){
        var newKey = ('' + arr[k][index2sort]).padStart(5,'0').padEnd(25, '+') + sep +  k.padStart(5,'0');
        // console.log("===>newKey = " + newKey);
        tvk.push(newKey);
    }
    
    tvk.sort();
    if (order[0].toUpperCase() != "A") tvk.reverse();
    
    //////////////////////////////////////
    for (var index in tvk){
        var t = tvk[index].split(sep);
        newArray.push(arr[t[1]*1]);
    }
     return newArray;


}
/* *********************************************
* renvoie un entier aléatoire entre une valeur min (incluse)
* et une valeur max (incluse).
* *********************************************** */
function getRandomIntInclusive(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min +1)) + min;
}


/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function sanityseTextForComparaison(exp){
var regAccent;
var car2rep;

    var reponse = exp.replaceAll("<br>","").replaceAll("\n","").replaceAll("\r","").replaceAll(" ","").toLowerCase();
    
    var cars2del = new RegExp('[ \'\.\!\?\,\;]', 'gi');
    reponse = reponse.replace(cars2del, "");
    
    regAccent = new RegExp('[àâä]', 'gi');
    car2rep = 'a';
    reponse = reponse.replace(regAccent, car2rep);
    
    
    regAccent = new RegExp('[éèêë]', 'gi');
    car2rep = 'e';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[îï]', 'gi');
    car2rep = 'i';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[ùüü]', 'gi');
    car2rep = 'u';
    reponse = reponse.replace(regAccent, car2rep);
    
    regAccent = new RegExp('[ôö]', 'gi');
    car2rep = 'o';
    reponse = reponse.replace(regAccent, car2rep);
    
    return reponse;
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function replaceBalisesByValues(exp)
{
    var newExp = exp.replace("{repondu}", statsTotal.repondu);
    newExp = newExp.replace("{totalQuestions}", statsTotal.nbQuestions);
    newExp = newExp.replace("{score}", statsTotal.score);
    newExp = newExp.replace("{scoreMaxi}", statsTotal.scoreMaxi);
    newExp = newExp.replace("{scoreMini}", statsTotal.scoreMini);
    newExp = newExp.replace("{duree}",  formatChrono(statsTotal.counter, "{minutes} minutes et {secondes} secondes"));
    
    return newExp;
    
  } 

/* ******************************************
*
* ******************************************** */
function getHtmlRadioKeys(name, tItems, numerotation, offset, extra="", sep="<br>"){
    
    var keys = Object.keys(tItems);
    var tHtml = [];

    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
    //alert('getHtmlCheckboxKeys : ' + keys[j] + ' ===> ' + tItems[keys[j]].word);
      tHtml.push(`<label class="quiz" >
                 <input type="radio" name="${name}"  id="${name}-${j}" value="${j}" ${extra} caption="${item.key}">
                 ${getNumAlpha(j,numerotation,offset)} : ${item.word}
                 </label>${sep}`);
    }
    return tHtml.join("\n");

}
/* ******************************************
*
* ******************************************** */
function getHtmlCheckboxKeys(name, tItems, numerotation, offset, extra="", sep="<br>"){

    var keys = Object.keys(tItems);
//alert("getHtmlCheckboxKeys\n" + keys.join(" - "));  


    var tHtml = [];
    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
    //alert('getHtmlCheckboxKeys : ' + keys[j] + ' ===> ' + tItems[keys[j]].word);
      tHtml.push(`<label class="quiz" >
                 <input type="checkbox" name="${name}" value="${j}" ${extra} caption="${item.key}">
                 ${getNumAlpha(j,numerotation,offset)} : ${item.word}
                 </label>${sep}`);
    
    
    }
/*
    var j=0;

    tItems.forEach((item, index) => {  
    alert('getHtmlCheckboxKeys : ' + item.word);
    });  
*/    

    return tHtml.join("\n");

}

/*    ----- progress Bar ----- */
var pb = {
    maxWidth : 0,
    maxValue : 250,   // total à atteindre
    value : 0,  // valeur courante
    itv : 0  // id pour setinterval
};

function pb_stop()
{
    clearInterval(pb.itv);
    pb.itv = 0;
}

function pb_runAuto(maxValue, newValue = 0){
    pb_init(maxValue, newValue);
    pb_run();
}

function pb_run()
{
  if(pb.itv == 0){
    pb.itv = setInterval(pb_run, 100);
    pb.value=0;
    return;
  }

  if(pb.value >= pb.maxValue) 
  {
    pb_stop();   	
    return;
  }	
  pb.value += 1;	
  if(pb.value >= pb.maxValue) stop();   
  pb_showProgression();   
}

function pb_init(maxValue, newValue = 0)
{
  pb.maxValue = maxValue;
  pb.value = newValue;
  var obContenair = document.getElementById("pb_contenair");
  var obBase = document.getElementById("pb_base");
  var obText = document.getElementById("pb_text");
  
  pb.maxWidth = obContenair.offsetWidth - obText.offsetWidth - 12;
  obBase.style.width = pb.maxWidth + 'px';
  pb.value = newValue;
  pb_showProgression();   
}


function pb_increment()
{
  if(pb.value >= pb.maxValue) 
  {
    return;
  }	
  pb.value += 1;	
  pb_showProgression();
}
function pb_setValue(newValue)
{
  pb.value = newValue;	
  pb_showProgression();
}

function pb_showProgression()
{
  var obContenair = document.getElementById("pb_contenair");
  //var obBase = document.getElementById("pb_base");
  //obBase.style.width = pb.maxWidth + 'px';
  var obText = document.getElementById("pb_text");
  var obIndicator = document.getElementById("pb_indicator");
  
  var newWidth = pb.value / pb.maxValue * pb.maxWidth;
  obIndicator.style.width=newWidth + "px";
  obText.innerHTML = pb.value + ' / ' + pb.maxValue;
}


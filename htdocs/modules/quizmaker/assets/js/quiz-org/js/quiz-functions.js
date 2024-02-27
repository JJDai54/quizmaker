
/*
Construit le code html de chaque question/slide.
function build_ (currentQuestion, index){
}

calcul le nombre de points obtenus d'une question/slide
function getScore_ (){
}

Renvois vrai ou faux selon que l'utilisateur a répondu ou non à la question/slide
la valeur de retour peut tenir compte du minReponses si il est défini dans les options des propositions
function isInputOk_ (){
}

renvois toutes les réponses valide d'un question/slide
utilisé uniquement pour de le developpement et les faciliter les tests
function getAllReponses_ (){
}

*/
var boolDog = true; 

const statsTotal = {
      quiz_score_max:   0,
      quiz_score_min:   0,
      quiz_questions:   0,
      cumul_questions:  0,
      cumul_max:        0,
      cumul_min:        0,
      cumul_score:      0,
      cumul_timer:      0,
      question_number:  0,
      question_max:     0,
      question_min:     0,
      question_points:  0
  };
  /*
const statsTotal = {
      .quiz_score_max:   0,
      .quiz_score_min:   0,
      .quiz_questions:   0;
      .cumul_questions:  0;
      .cumul_max:        0,
      .cumul_min:        0,
      .cumul_score:      0,
      .cumul_timer:      0,
      .question_number:   0,
      .question_max:     0,
      .question_min:     0,
      .question_points:  0
  };
  */
  
/*
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
*/

function sleep(){
    blob("===> sleep");
    //nothing
}

 /*******************************************************************
  *                     
  * *****************************************************************/
function getVersion(){
   
    var version = quiz_messages.version.replace('{name}',quiz_config.name).replace('{version}',quiz_config.version).replace('{date_release}',quiz_config.date_release).replace('{email}',quiz_config.email).replace('{author}',quiz_config.author);

    return version;
}
 /*******************************************************************
  *                     
  * *****************************************************************/
function alertId(currentQuestion, msg = ""){
alert(`${currentQuestion.quizId} / ${currentQuestion.questId}`  + " - " + msg);
}
 /*******************************************************************
  *                     
  * *****************************************************************/
function rnd(max = 99, min = 0){
    //return Math.floor((Math.random() * max) + min);
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min +1)) + min;    
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
    case 1:         // renvoi l'index tel que
        return (index*1)+1+offset + quiz_messages.twoPoints;
    case 2:         //renvoi la numerotation en lettre majuscule "A B C ..."
        return String.fromCharCode((index*1)+65+offset) + quiz_messages.twoPoints; 
        break;
    case 3:         //renvoi la numerotation en lettre minuscule "a b c ..."
        return String.fromCharCode((index*1)+65+offset).toLowerCase() + quiz_messages.twoPoints; 
        break;
    case 0:         //pas de numérotation, a utiliser de préférence avec ldes images par exemple
    default:         
        return "";
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

function shuffleIndexArr (nbIndex, bolShuffleArray = true, firstIndex = 0) {
    var tIndex = [];
    for(h = 0; h < nbIndex; h++){
        tIndex.push(firstIndex + h);
    }
    //alert(shuffleArray(tIndex).join('-'));
    if(bolShuffleArray){
        return shuffleArray(tIndex);
    }else{
        return tIndex;
    }
}

function shuffleArray2 (array) {
  var currentIndex = array.length, temporaryValue, randomIndex;
   var newArray = duplicateArray (array);

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = newArray[currentIndex];
    newArray[currentIndex] = newArray[randomIndex];
    newArray[randomIndex] = temporaryValue;
  }

  return newArray;
}
function shuffleArray(array) {

   var newArray = duplicateArray (array);
   for (var i = newArray.length - 1; i > 0; i--) {
   
       // Generate random number
       var j = Math.floor(Math.random() * (i + 1));
                   
       var temp = newArray[i];
       newArray[i] = newArray[j];
       newArray[j] = temp;
   }
       
   return newArray;
}

function shuffleArrayFY(arr){
    for(var i = arr.length-1 ; i > 0; i--){
        var j = Math.floor( Math.random() * (i + 1) ); //random index
        [arr[i],arr[j]] = [arr[j],arr[i]]; // swap
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
    // blob(key + " = " +  arrKeys[key]);
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
    //randomIndex = Math.floor(Math.random() * currentIndex);
    randomIndex = getRandomInt(currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

/* *******************************
* On renvoie un entier aléatoire entre une valeur min (incluse)
* et une valeur max (incluse).
* *** */

function getRandomInt(max, min=0) {
  min = Math.ceil(min);
  max = Math.floor(max)+1;
  return Math.floor(Math.random() * (max - min)) + min;
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
// blob (lstWordsToAdd) + " - " + words.length + " - " + tWordsB.length + "-" + tWordsAll.length;
  
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
    var regex = quiz_config.regexAllLettersPP;
    
    var tWordsA = exp.match(regex);
    tWordsA = [...new Set(tWordsA)];


    //remplacement des mot entre accolade par des chifres entre accolade
    var exp2 = exp;
    for (var i in tWordsA) {
        var token = "{" + (i*1+1) + "}";
        var word =  quiz_messages.tplWord.replace("{word}", token);
// blob("token = " + token +  "-" + tWordsA[i]);
        
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
function getExpInAccolades(exp){
var ret = {textOk:'', text:'', words:[], nbRows:0};

    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;
    var tWordsA = exp.match(regex);
    tWordsA = [...new Set(tWordsA)]; // elimine les doublons
    
    for (var i in tWordsA) {
        tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }
    //alert(tWordsA.join('|'));
    return tWordsA;
}  

/* *******************************
*
* *** */
function transformTextWithMask(exp, mask){
var ret = {textOk:'', text:'', words:[], nbRows:0};
var textOk = '';

    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;

    
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
    // blob(ret.textOk);
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
// blob("getObjectsByName === >" + selector);
    var obs = document.querySelectorAll(selector);
    // blob ("getObjectsByName : " + selector + " - nb = " + obs.length);
    return obs;
}

/*********************************************
 * extra a utiiser avec checked par exemple  
 * **** */
function getObjectValueByName(name, balise, typeObj = "", extra="")
{ 
    var selector = `${balise}[name=${name}]`;
    if (typeObj != '') selector += `[type=${typeObj}]`;    
    if (extra != '') selector += `:${extra}`;    
    //var ob = document.querySelectorAll(selector);
    var value = (document.querySelector(selector) || {}).value;    
// blob("===> getObjectValueByName : " + selector + " | value = " + value);    
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
    tHtml.push(`<SELECT id="${id}" name="${name}" class="question-comboboxMatchItems" ${extra}>`);
                                                                  
//         tHtml.push(`<SELECT id="${name}{${k}" name="${name}" class="question-textareaListbox" onclick="quiz_textareaListbox_event('update','${id}','${name}',${questionNumber})">`);        
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

function getHtmlCheckbox(name, tItems, itemDefault = -1, numerotation, offset, extra="", sep="<br>"){
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
    var tHtml = [];
    
    
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
        // blob("===newKey = " + newKey);
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
        // blob("===>newKey = " + newKey);
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
        // blob("===>newKey = " + newKey);
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
        // blob("===>newKey = " + newKey);
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
    
    var cars2del = new RegExp('[ \'\.\!\?\,\;-]', 'gi');
  //var cars2del = new RegExp('[ \'\.\!\?\,\;\-\_\/]', 'gi');
        
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

/* ******************************************************
function requestGetPost : rempli un objet associatif avec les valeur GET et POST, correspondant aux cléfs du tableau quiz_request_keys
@ return : objet associatif key;value
function requestGetPost(){
    var arr = {};
    const paramsUrl = new URLSearchParams(window.location.search);
    for(var i =0; i < quiz_request_keys.length; i++){
        var key = quiz_request_keys[i];
        obAtt = document.getElementById('user.' + key);
        if (obAtt){
            arr[key] = obAtt.value;
        }else{
            arr[key] = parseInt(paramsUrl.get(key)); 
        }
    }
    return arr;
}
********************************************************* */
function requestGetPost(){
    var arr = {};
    const paramsUrl = new URLSearchParams(window.location.search);

    quiz_request_keys.forEach((key) => {
        obAtt = document.getElementById('user.' + key);
        if (obAtt){
            arr[key] = obAtt.value;
        }else{
            arr[key] = paramsUrl.get(key); 
        }
    });
    
    //const quiz_request_keys=['uid','','name','email','ip','quiz_id'];  
    if(!arr.uname) arr.uname = "Anonymous";
    if(!arr.name)  arr.name  = arr.uname;
    if(!arr.ip)    arr.ip    = "0.0.0.0";

    arr.isAnonymous = (arr.uname == "Anonymous");
       
    return arr;
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function replaceBalisesByValues(exp)
{
    var newExp = exp.replace("{repondu}", statsTotal.cumul_questions);
    newExp = newExp.replace("{totalQuestions}", statsTotal.quiz_questions);
    newExp = newExp.replace("{score}", statsTotal.cumul_score);
    newExp = newExp.replace("{scoreMaxiQQ}", statsTotal.quiz_score_max);
    newExp = newExp.replace("{scoreMiniQQ}", statsTotal.quiz_score_min);
    newExp = newExp.replace("{scoreMaxi}", statsTotal.quiz_score_max);
    newExp = newExp.replace("{scoreMini}", statsTotal.quiz_score_min);
    newExp = newExp.replace("{duree}",  formatChrono(statsTotal.cumul_timer, "{minutes} minutes et {secondes} secondes"));
    
    quiz_request_keys.forEach((key) => {
        //alert(key + " = " + quiz_rgp[key]);
        newExp = newExp.replace(`{${key}}`, quiz_rgp[key]);
    });

    return newExp;
    
  } 

/* ******************************************
*
* ******************************************** */
function getHtmlRadioKeys(name, tItems, numerotation, offset=0, extra="", sep="<br>"){
    
    var keys = Object.keys(tItems);
    var tHtml = [];

    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
    //alert('getHtmlCheckboxKeys : ' + keys[j] + ' ===> ' + tItems[keys[j]].word);
      tHtml.push(`<label class="quiz" >
                 <input type="radio" name="${name}"  id="${name}-${j}" value="${j}" ${extra} caption="${item.key}">
                 ${getNumAlpha(j,numerotation,offset)}${item.word}
                 </label>${sep}`);
    }
    return tHtml.join("\n");

}
/* ******************************************
*
* ******************************************** */
function getHtmlCheckboxKeys(name, tItems, numerotation, offset=0, extra="", sep="<br>"){

    var keys = Object.keys(tItems);
//alert("getHtmlCheckboxKeys\n" + keys.join(" - "));  


    var tHtml = [];
    for(var j=0; j < keys.length; j++){
        item = tItems[keys[j]];
    //alert('getHtmlCheckboxKeys : ' + keys[j] + ' ===> ' + tItems[keys[j]].word);
      tHtml.push(`<label class="quiz" >
                 <input type="checkbox" id="${name}-${j}" name="${name}" value="${j}" ${extra} caption="${item.key}">
                 ${getNumAlpha(j,numerotation,offset)}${item.word}
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

//function getMarginStyle(nbItems, min=5, max=12, numStyle=0, extra=''){
function getMarginStyle(nbItems, numStyle=0, extra='', min=5, max=10){
    var margin = Math.trunc((250-10) / (nbItems * 2));
    margin = Math.min(Math.max(parseInt(margin), min), max);
    switch(numStyle){
        case 1:  var strStyle =`style='line-height: ${margin*3}px;' ${extra}`; break;
        case 2:  var strStyle =`style='padding: ${margin}px;${extra}'`; break;
        default: var strStyle =`style='margin:${margin}px 10px ${margin}px 0px;' ${extra}`; break;
    }
    return strStyle;
}

/* ------------------------ */
/* ----- progress Bar ----- */
/* ------------------------ */
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
  var obContenair = document.getElementById('pb_contenair');
  var obBase = document.getElementById('pb_base');
  var obText = document.getElementById('pb_text');
  
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
  var obContenair = document.getElementById('pb_contenair');
  //var obBase = document.getElementById('pb_base');
  //obBase.style.width = pb.maxWidth + 'px';
  var obText = document.getElementById('pb_text');
  var obIndicator = document.getElementById('pb_indicator');
  
  var newWidth = pb.value / pb.maxValue * pb.maxWidth;
  obIndicator.style.width=newWidth + "px";
  obText.innerHTML = pb.value + ' / ' + pb.maxValue;
}


/* ***************************************
*
* *** */
function blob(message)
  { 
    if(!boolDog) return;
    if(Array.isArray(message)){
        console.log(`......................`);
        for(var i = 0; i < t.length; i++){
            blob(`>array : ${i} : ${t[i]}`);
        }
    }else{
        console.log(">>> functions : " + message);
    }
  } 
  
/* ***************************************
*
* *** */
function get_highslide_a(imgUrl, width = '', height = '', path = '', lettrine = false){
    if(path) {
        imgUrl = path + '/' + imgUrl;
    }
    
    var style = "";
    if (width)  {style += `max-width:${width}px;`;}
    if (height) {style += `max-height:${height}px;`;}
    if (style)  {style =  `style='${style}'`;}
    
    if(lettrine){
        var divStyle = "style='float: left;margin-right:10px;'";
    }else{
        var divStyle = '';
    }
    
    var html = `<div class='highslide-gallery' ${divStyle}>`  
             + `<a href='${imgUrl}' class='highslide' onclick='return hs.expand(this);'>`
             + `<img src='${imgUrl}'  alt='' ${style}/>`
             + `</a></div>`;
    return html; 
}
        

/* ***************************************
*
* *** */
function toProperName(name){
    return name.charAt(0).toUpperCase() + name.substring(1).toLowerCase();
}
/* ***************************************
*
* *** */
function getBoolBin(value, binOctet){
    return ((value & binOctet) != 0);
}

/*
https://code-boxx.com/drag-drop-sortable-list-javascript/
*/
function quiz_init_slist (target) {
  // (A) SET CSS + GET ALL LIST ITEMS
  target.classList.add("quiz_slist");
  let items = target.getElementsByTagName("li"), current = null;
//alert('ok=>' + target.id + "\n nb items = " + items.length);  
//return true;
  // (B) MAKE ITEMS DRAGGABLE + SORTABLE
  for (let i of items) {
    // (B1) ATTACH DRAGGABLE
    i.draggable = true;

    // (B2) DRAG START - YELLOW HIGHLIGHT DROPZONES
    i.ondragstart = e => {
      current = i;
      for (let it of items) {
        if (it != current) { it.classList.add("hint"); }
      }
    };

    // (B3) DRAG ENTER - RED HIGHLIGHT DROPZONE
    i.ondragenter = e => {
      if (i != current) { i.classList.add("active"); }
    };

    // (B4) DRAG LEAVE - REMOVE RED HIGHLIGHT
    i.ondragleave = () => i.classList.remove("active");

    // (B5) DRAG END - REMOVE ALL HIGHLIGHTS
    i.ondragend = () => { for (let it of items) {
      it.classList.remove("hint");
      it.classList.remove("active");
    }};

    // (B6) DRAG OVER - PREVENT THE DEFAULT "DROP", SO WE CAN DO OUR OWN
    i.ondragover = e => e.preventDefault();

    // (B7) ON DROP - DO SOMETHING
    i.ondrop = e => {
      e.preventDefault();
      if (i != current) {
        let currentpos = 0, droppedpos = 0;
        for (let it=0; it<items.length; it++) {
          if (current == items[it]) { currentpos = it; }
          if (i == items[it]) { droppedpos = it; }
        }
        if (currentpos < droppedpos) {
          i.parentNode.insertBefore(current, i.nextSibling);
        } else {
          i.parentNode.insertBefore(current, i);
        }
      }
      //declenchement du calcul du score
      computeAllScoreEvent();
    };
  }
}

/* *********************************
*
* */
  function gotoSlide (exp) {
    const btnGotoSlide = document.getElementById('quiz_btn_goto_slide');
    btnGotoSlide.innerHTML = exp;
    btnGotoSlide.click();
    return true;
  }
/* *********************************
*
  function gotoSlideByNum (numSlide) {
    const btnGotoSlide = document.getElementById('quiz_btn_goto_slide');
    btnGotoSlide.innerHTML = numSlide;
    btnGotoSlide.click();
    return true;
  }
* */
/* *********************************
*
  function gotoSlideByName (identifiant) {
    var bolOk = false;
    for (var h=0; h<quizard.length; h++){
        currentQuestion = quizard[h].question;
        if(currentQuestion.identifiant == identifiant){
            bolOk = true;
            gotoSlideByNum (h); 
            break;
        }
    }
    if(!bolOk) alert (`Identifiant "${identifiant}" non trouvé`);
    return true;
  }
* 
* 

* */

function load_css(cssId){
    //var cssId = 'myCss';  // you could encode the css path itself to generate id..
    if (document.getElementById(cssId))  return true;
    
    var urlCSS = quiz.urlMain + '/js/tpl/slide_' + cssId + '.css';

    let xhr = new XMLHttpRequest();
    xhr.open('GET', urlCSS, false);
    xhr.send();
    //alert ('load_css : ' + urlCSS + ' - retour : ' + xhr.status);
    // 'cssURL' is the stylesheet's URL, i.e. /css/styles.css
    if(xhr.status != 200) {
        //creation d'un div bidon masqué pour ne pas refaire le XMLHttpRequest à chaque type de question si le css n'existe pas
        var newDiv = `<div id='${cssId}' style='display:none;'><hr>div "${cssId}" bidon en remplacement des CSS inexistant pour éviter les tentive suivante de chargement des css propre à chaque type de question<hr></div>`; 
        document.write(newDiv);
        return false;
    }
    
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = cssId;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = urlCSS;
    link.media = 'all';
    head.appendChild(link);
    return true;    
}



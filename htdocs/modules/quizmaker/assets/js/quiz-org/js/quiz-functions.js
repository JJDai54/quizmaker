

var boolDog = true; 
var quizIsStarted = false;

const statsTotal = {
      quiz_score_maxi:   0,
      quiz_score_mini:   0,
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
/* *******************************
* On renvoie un entier aléatoire entre une valeur mini (incluse)
* et une valeur maxi (incluse).
* *** */

function getRandom(maxi, mini=0) {
//console.log(`+++ maxi = ${maxi} - mini = ${mini}`);
  var mini = Math.ceil(mini);
  var maxi = Math.floor(maxi)+1; // 
  return Math.floor(Math.random() * (maxi - mini)) + mini;
}
function getRandomArray(arr) {
    var index = getRandom(arr.length-1, 0);
    return arr[index];
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
    case 4:         // renvoi l'index tel que
        return '{' + ((index*1)+1+offset) + '}' + quiz_messages.twoPoints;
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
    randomIndex = getRandom(currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}


/* *******************************
*
* *** */
  function formatChrono (chrono){
        var minutes = Math.floor(chrono/60);
        var expMinutes = minutes.toString().padStart(2, '0');

        var secondes = chrono - (minutes*60);
        var expSecondes = secondes.toString().padStart(2, '0');
    
        if(minutes == 0){
            var tplFormatChrono = quiz_messages.formarDureeS;
        }else{
            var tplFormatChrono = quiz_messages.formarDureeMS;
        }

        return tplFormatChrono.replace("{minutes}", expMinutes)
                              .replace("{secondes}", expSecondes);
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
function transformTextWithMask(exp, mask){
var ret = {textOk:'', text:'', words:[], nbRows:0};

    ret.nbRows = exp.split("\n").length; //nombre de ligne du texte
    exp = exp.replaceAll("\n", qbr); //avec mise en forme de crlf
    textOk = exp.replaceAll('{','').replaceAll('}','');


    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;

    
    var tWordsA = exp.match(regex);
    //alert (tWordsA.join('|'));
    tWordsA = [...new Set(tWordsA)]; // elimine les doublons
//    alert(tWordsA.join('|'));
//----------------------------------------------
    //remplacement des mots entre accolade par le mask defini dans options
    var exp2 = exp.replaceAll(qbr, "\n");
    ret.nbRows = exp2.split("\n").length; //nombre de lignes du texte
//    exp = exp.replaceAll("\n","<br>");



    for (var i in tWordsA) {
//alert (`${tWordsA[i]} ===> ${mask}`) ;   
        //replacement des mots entre accolade par le mask
        exp2 = exp2.replaceAll(tWordsA[i], mask);
        
        //suppression des accolades dans le tableau des mots
        tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }



//------------------------------------------------------------------
        
    ret.text   = exp2;      //texte avec mask
    ret.words  = tWordsA;   //tableau des mots trouvés
    ret.textOk = textOk;    //text sans les accolades
    // blob(ret.textOk);
    return ret;
//-------------------------------------------------

}  

/* *******************************
*
* *** */
function transformTextWithToken(exp, tokenColor = '#0000FF'){
var ret = {textOk:'', text:'', words:[], nbRows:0};
var textOk = '';

    ret.nbRows = exp.split("\n").length; //nombre de ligne du texte
    exp = exp.replaceAll("\n", qbr); //avec mise en forme de crlf
    textOk = exp.replaceAll('{','').replaceAll('}','');
    
    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;
    
    var tWordsA = exp.match(regex);
    tWordsA = [...new Set(tWordsA)];

    var tpl = "<span style='color:{tokenColor};'>{word}</span>";
    //remplacement des mots entre accolades par des chifres entre accolade
    var exp2 = exp;
    for (var i in tWordsA) {
        var token = "{" + (i*1+1) + "}";
       // var word =  quiz_messages.tplWord.replace("{word}", token);
        var word =  tpl.replace("{word}", token)
                       .replace("{tokenColor}", tokenColor); 
// blob("token = " + token +  "-" + tWordsA[i]);
        
        exp2 = exp2.replaceAll(tWordsA[i], word);

        
    tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }
    
//     textOk = exp2;
//     for (var i in tWordsA) {
//         var token = "{" + (i*1+1) + "}";
//         textOk = textOk.replaceAll(token, tWordsA[i]);
//     }


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
//    var regex = /{[^{}]+}/gi ;                //quiz_config.regexAllLettersPP;
    var regex = quiz_config.regexAllLettersPP;

    var tWordsA = exp.match(regex);
    //alert( tWordsA.join('-')) ;
    tWordsA = [...new Set(tWordsA)]; // elimine les doublons
    
    for (var i in tWordsA) {
        tWordsA[i] = tWordsA[i].replace("{","").replace("}","");
    }
    //alert(tWordsA.join('|'));
    return tWordsA;
}  


/*********************************************
 * extra a utiiser avec checked par exemple  
 * **** */
function getObjectsByName(balise, name, typeObj = "", extra="", extra2 = "")
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
    return document.getElementById(id);
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

function strip_tag(string) {
    return string.replace(/(<([^>]+)>)/gi, "");
}
/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function sanityseTextForComparaison(exp, bolToLower = true){
var regAccent;
var car2rep;

    if (bolToLower){exp = exp.toLowerCase();}
    exp = strip_tag(exp);
    var reponse = exp.replaceAll("<br>","").replaceAll("\n","").replaceAll("\r","").trim(); //.replaceAll(" ","")
    
    var cars2del = new RegExp('[\ \'\.\!\?\,\;-@]', 'gi');
    //var cars2del = new RegExp('[ \'\.\!\?\,\;-]', 'gi');
  //var cars2del = new RegExp('[ \'\.\!\?\,\;\-\_\/]', 'gi');
        
    reponse = reponse.replaceAll(cars2del, "");
    reponse = sanityseAccents(reponse);
/*
    regAccent = new RegExp('[àâä]', 'gi');
    car2rep = 'a';
    reponse = reponse.replaceAll(regAccent, car2rep);
    
    
    regAccent = new RegExp('[éèêë]', 'g');
    car2rep = 'e';
    reponse = reponse.replaceAll(regAccent, car2rep);
    
    regAccent = new RegExp('[ÉÈÊË]', 'g');
    car2rep = 'E';
    reponse = reponse.replaceAll(regAccent, car2rep);
    
    regAccent = new RegExp('[îï]', 'gi');
    car2rep = 'i';
    reponse = reponse.replaceAll(regAccent, car2rep);
    
    regAccent = new RegExp('[ôö]', 'gi');
    car2rep = 'o';
    reponse = reponse.replaceAll(regAccent, car2rep);
    
    regAccent = new RegExp('[ùüü]', 'gi');
    car2rep = 'u';
    reponse = reponse.replaceAll(regAccent, car2rep);
*/    
    
    return reponse;
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function sanityseAccents(exp, setCasse=0){
var regAccent;
var car2rep;
var arrExp1 = new Array ('aàâä', 'eéèêë', 'iîï', 'oôö', 'uùüü');
var reponse = '';
    
    if(setCasse > 0){
        reponse = exp.toUpperCase();
    }else if (setCasse < 0){
        reponse = exp.toLowerCase();
    }else{
        reponse = exp;
    }
    //var globalParam = (ignoreCasse) ?  'gi' : 'g';
    var globalParam = 'g';
    
    for(var h = 0; h < arrExp1.length;  h++){
        regAccent = new RegExp('[' + arrExp1[h].substring(1) + ']', globalParam);
        car2rep = arrExp1[h][0];
        //console.log('sanityseAccents : ' + regAccent + "--->" + car2rep)
        reponse = reponse.replaceAll(regAccent, car2rep);
        
        regAccent = new RegExp('[' + arrExp1[h].substring(1).toUpperCase() + ']', globalParam);
        car2rep = arrExp1[h][0].toUpperCase();
        //console.log('sanityseAccents : ' + regAccent + "--->" + car2rep)
        reponse = reponse.replaceAll(regAccent, car2rep);

       //if(!ignoreCasse)
    }
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
function replaceBalisesByValues(exp, questId = 0)
{
    var newExp = exp.replaceAll("{repondu}", statsTotal.cumul_questions)
                    .replaceAll("{totalQuestions}", statsTotal.quiz_questions)
                    .replaceAll("{score}", statsTotal.cumul_score)
                    .replaceAll("{scoreMaxiQQ}", statsTotal.quiz_score_maxi)
                    .replaceAll("{scoreMiniQQ}", statsTotal.quiz_score_mini)
                    .replaceAll("{scoreMaxi}", statsTotal.quiz_score_maxi)
                    .replaceAll("{scoreMini}", statsTotal.quiz_score_mini)
                    .replaceAll("{duree}",  formatChrono(statsTotal.cumul_timer));
    
    quiz_request_keys.forEach((key) => {
        //alert(key + " = " + quiz_rgp[key]);
        newExp = newExp.replaceAll(`{${key}}`, quiz_rgp[key]);
    });
    
    if (newExp.search('{sommaire}') >= 0)     {newExp = newExp.replaceAll("{sommaire}", get_sommaire(0));}
    if (newExp.search('{groups}') >= 0)       {newExp = newExp.replaceAll("{groups}", get_sommaire(1));}
    if (newExp.search('{allquestions}') >= 0) {newExp = newExp.replaceAll("{allquestions}", get_sommaire(2,0));}
    if (newExp.search('{questions}') >= 0)    {newExp = newExp.replaceAll("{questions}", get_sommaire(2, questId));}
   
    var newExp = newExp.replaceAll('//',  '<br>');
    return newExp;
    
  } 
/* ******************************************
*
* ******************************************** */
function get_sommaire(selection = 0, questId = 0){
var isGroup = false;
//var numSlide = 0;
var bolOk = true;
var tRet = [];
    quizard.forEach((clQuestion, numSlide) => {
        switch (selection){
        default :
        case 0: //sommaire détaillée on prend tout
            bolOk = true;
            break;
        case 1: // liste des groupes uniquement
            bolOk = (!clQuestion.question.isQuestion); 
            break;
        case 2: // liste des questions sans les groupes filtrer eventuellement par parentId
            bolOk = (clQuestion.question.isQuestion && (clQuestion.question.parentId == questId || questId == 0)  ); 
//console.log(`=>get_sommaire ${selection} - questId = ${questId}`);
            break;
        }
        if (bolOk){
            console.log ("===> test : " + clQuestion.question.pluginName  + " - " + clQuestion.question.question);
            var onClick = `onClick="gotoSlideNum(${numSlide});"`;
            var exp = `${numSlide}-${clQuestion.question.pluginName }-${clQuestion.sanityse_question()}`;
            
            if( clQuestion.question.isQuestion){
                var link =`<h2 ${onClick}>${exp}</h2>` 
            }else{
                var link =`<h1 ${onClick}>${exp}</h1>` 
            } 
            tRet.push(link);
         }
    
      });

    console.log(tRet.join("<br>\n"));
    return "<div name='quiz_div_sommaire' sommaire class='quiz_sommaire'>" + tRet.join("<br>\n") + "</div>";
}

//function getMarginStyle(nbItems, min=5, max=12, numStyle=0, extra=''){
function getMarginStyle(nbItems, numStyle=0, extra='', min=2, max=8){
    var margin = Math.trunc((400-100-(10*nbItems)) / (nbItems * 2));
    
    //var margin = Math.trunc((250-10) / (nbItems * 2));
    margin = Math.min(Math.max(parseInt(margin), min), max);
    switch(numStyle){
        case 1:  var strStyle =`style='line-height: ${margin*3}px;${extra}' `; break;
        case 2:  var strStyle =`style='padding: ${margin}px;${extra}'`; break;
        default: var strStyle =`style='margin:${margin}px 10px ${margin}px 0px;${extra}' `; break;
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
  { //return true;
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
             + `<a href='http://127.0.0.16/uploads/quizmaker/quiz-js/QuizFevier2024-v02-5784/${imgUrl}' class='highslide' onclick='return hs.expand(this);'>`
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
  function gotoSlideNum (exp) {
    console.log("gotoSlideNum => " + exp);

    document.getElementById("quiz_goto_slide").value = exp;
    document.getElementById('quiz_btn_goto_slide').click();
    //alert("gotoSlideNum => " + exp);
    //evt.stopPropagation();
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
*** */


/* *********************************
*
* */
  function setAllSepByNewSep(exp, newSep = "|") {
  //alert(exp + "===>" + newSep);
    //return exp.replaceAll(/\;\,\_\|/gi, newSep); // a revoir
    return exp.trim().replaceAll(';',newSep)
                     .replaceAll('-',newSep)
                     .replaceAll(',',newSep)
                     .replaceAll('|',newSep)
                     .replaceAll('/',newSep);
  }
/* *********************************
*
* */
  function splitAllSep(exp, newSep = "|") {
    return setAllSepByNewSep(exp, newSep).split(newSep);
  }
/* *********************************
*
* */
  function set_param(exp, numParam = 0) {
    document.getElementById('quiz_data' + numParam).value = exp;
    return true;
  }
/* *********************************
*
* */
  function get_param(numParam = 0) {
    return document.getElementById('quiz_data' + numParam).value ;
  }
  
/* *********************************
*
* */
  function getShortName(fullName) {
    var pos = fullName.lastIndexOf('/');
    return (pos >= 0) ? fullName.substring(pos+1) :  fullName;
  }
/* *********************************
*
* */
function strToArrayNum(strExp, sep=","){
    var strArr = strExp.split(sep);
    var intArr = new Int8Array(strArr.length);
    
    for (var i = 0; i < strArr.length; i++) {
      intArr[i] = strArr[i]*1;
     //console.log(`computeScoresMinMaxByProposition - inputs = ${currentQuestion.answers[k].inputs} - ${this.question.answers[k].proposition} -(${tPoints[i]}`))
    }
    return intArr;
}
/* *********************************
*
* */
function arrayToArrayNum(strArr){
    var intArr = new Int8Array(strArr.length);
    for (var i = 0; i < strArr.length; i++) {
      intArr[i] = strArr[i]*1 ;
     //console.log(`computeScoresMinMaxByProposition - inputs = ${currentQuestion.answers[k].inputs} - ${this.question.answers[k].proposition} -(${tPoints[i]}`))
    }
    return intArr;
}

function isBitOk(numBit, value){
    return  ((value & Math.pow(2, numBit)) > 0) ? 1 : 0 ;

}

function rgbToHex(rgb) {
    var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
    var result, r, g, b, hex = "";
    if ( (result = rgbRegex.exec(rgb)) ) {
        r = componentFromStr(result[1], result[2]);
        g = componentFromStr(result[3], result[4]);
        b = componentFromStr(result[5], result[6]);

        hex = "#" + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
    return hex;
}

function componentFromStr(numStr, percent) {
    var num = Math.max(0, parseInt(numStr, 10));
    return percent ?
        Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
}
 function modifCSSRule(sChemin, sPropriete, sVal){
  var bFind = false;
  var aStyleSheets = document.styleSheets;
  var exp_reg =  new RegExp(sChemin,"gi");
  // si la css est externe et d'un autre nom de domaine
  // cssRules: lève une DOMException: "The operation is insecure."
  // code: 18 
  // message: "The operation is insecure."
  // name: "SecurityError"
  //
  for(var i = 0; i < aStyleSheets.length; ++i){
  //console.log(aStyleSheets[i].cssRules);
    try{
      var aCssRules =  aStyleSheets[i].cssRules;
      console.log("modifCSSRule===> aCssRules : " + aCssRules)
      for(var j = 0; j < aCssRules.length; ++j){   
        if(exp_reg.test(aCssRules[j].selectorText)){ 
          aCssRules[j].style[sPropriete]= sVal;
          bFind = true;
        }//if
      }//for
    }catch(error) {
      //cssRules: lève une DOMException: "The operation is insecure."
      console.log('error');
      continue
    }
  }
  return bFind; 
}

 function setStyleAttribute(id, sAttribut, sVal){
    var ob = document.getElementById(id); 
    if(ob) {
        ob.setAttribute("style", sAttribut + ':' + sVal);
        //obSilouhette.style.background=currentQuestion.options.bgSilhouette;
    }
}

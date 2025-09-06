/* *************************************** */
/* ******* functions de tableau ********** */
/* *************************************** */

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

/* **********************************

* *** */
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
function shuffleNewArray (arraySource) {
  newArray = duplicateArray(arraySource);

  var currentIndex = newArray.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    //randomIndex = Math.floor(Math.random() * currentIndex);
    randomIndex = getRandom(currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = newArray[currentIndex];
    newArray[currentIndex] = newArray[randomIndex];
    newArray[randomIndex] = temporaryValue;
  }

  return newArray;
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
* sortArrayObjectrrayKey1
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
* sortArrayKey
* tri un tableau de tableau selon une des colonne
* @arr object : tableau à trier
* @index2sort : nom de la clé sur laquel trié
* @order string : ordre de tri
* $return object : tableau trié
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

/* *****************************************

* ****************************************** */
function getRandomArray(arr) {
    var mini = 0;
    var maxi = arr.length; 
    
    var index = Math.floor(Math.random() * (maxi - mini)) + mini; 
    return arr[index];
}

/* *********************************
*
* */
function strToArrayNum(strExp, sep=","){
    var strArr = strExp.split(sep);
    var intArr = new Int8Array(strArr.length);
    
    for (var i = 0; i < strArr.length; i++) {
      intArr[i] = strArr[i]*1;
     //console.log(`computeScoresMinMaxByProposition - inputs = ${currentQuestion.answers[k].inputs} - ${this.question.answers[k].proposition} -(${tPoints[i]}`));
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
     //console.log(`computeScoresMinMaxByProposition - inputs = ${currentQuestion.answers[k].inputs} - ${this.question.answers[k].proposition} -(${tPoints[i]}`));
    }
    return intArr;
}

/* *********************************
*
* */
function splitAllSep(exp, newSep = "|") {
  return setAllSepByNewSep(exp, newSep).split(newSep);
}

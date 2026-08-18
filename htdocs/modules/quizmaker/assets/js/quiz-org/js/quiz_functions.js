//import "./composantsJS/Chronos.js"; // Doit être présent tout en haut !

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
      begin_timer:      0,
      question_number:  0,
      question_max:     0,
      question_min:     0,
      question_points:  0
  };
  

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
function sleep(){
    blob("===> sleep");
    //nothing
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
function getRandomBool() {
//console.log(`+++ maxi = ${maxi} - mini = ${mini}`);
  var mini = 0;
  var maxi = 999; // 
  var r = Math.floor(Math.random() * (maxi - mini)) + mini;
  return ((r%2)==1) ? true : false;
}


/*********************************************
 * extra a utiliser avec checked par exemple  
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



/* ********************************************************* */
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
            bolOk = (!clQuestion.question.isQuestion && clQuestion.name != 'pageInfo'); 
            break;
        case 2: // liste des questions sans les groupes filtrer eventuellement par parentId
            bolOk = ((clQuestion.question.isQuestion || clQuestion.name == 'pageInfo') && (clQuestion.question.parentId == questId || questId == 0)); 
//console.log(`=>get_sommaire ${selection} - questId = ${questId}`);
            break;
        }
        
        if (bolOk){
            //console.log ("===> test : " + clQuestion.question.pluginName  + " - " + clQuestion.question.question);
            var onClick = `onClick="gotoSlideNum(${numSlide});"`;
            var exp = `${numSlide}-${clQuestion.question.pluginName}-${clQuestion.sanityse_question()}`;
            
            if( clQuestion.question.isQuestion || clQuestion.name == 'pageInfo'){
                var link =`<h2 ${onClick}>${exp}</h2>`; 
                //var link =`<a ${onClick}>${exp}</a>`; 
            }else{
                var link =`<h1 ${onClick}>${exp}</h1>`; 
            } 
            tRet.push(link);
         }
    
      });

    //console.log(tRet.join("<br>\n"));
    return ('<div name="quiz_div_sommaire" sommaire class="quiz_sommaire">' + tRet.join("<br>\n") + "</div>");
    //return ("<div name='quiz_div_sommaire' sommaire class='quiz_sommaire'>" + tRet.join("<br>\n") + "</div>");
}

/* ***************************************
* calcule une marge moyenne selon le nombre d'item afin d'arer la présentation
* *** */
function getMarginStyle(nbItems, numStyle=0, extra='', min=3, max=8, unit='px'){
    var margin = Math.trunc((400-100-(10*nbItems)) / (nbItems * 2));
    
    //var margin = Math.trunc((250-10) / (nbItems * 2));
    margin = (min == max) ? min : Math.min(Math.max(parseInt(margin), min), max);
    if(extra){
        extra += ";";
    }
    switch(numStyle){
        case 1:  var strStyle =`line-height: ${margin*3}${unit};${extra}`; break;
        case 2:  var strStyle =`padding-top: ${margin}${unit};padding-bottom: ${margin}${unit};${extra}`; break;
        default: var strStyle =`margin:${margin}${unit} 10${unit} ${margin}${unit} 0${unit};${extra}`; break;
    }
    return strStyle;
}
/* ***************************************
* todo : rempacer la fonction "getMarginStyle" par "getStyleArr" 
* qui permet des changement au retour avant insertion du style dans les balises html
* calcule une marge moyenne selon le nombre d'item afin d'arer la présentation
* *** */
function getMarginStyleArr(nbItems, numStyle=0, min=3, max=8, unit='px'){
    var margin = Math.trunc((400-100-(10*nbItems)) / (nbItems * 2));
    margin = (min == max) ? min : Math.min(Math.max(parseInt(margin), min), max);
    var styleArr=[];
    
    switch(numStyle){
        case 1:  
            styleArr['line-height'] = `${margin*3}${unit}`;
            break;
        case 2: 
            styleArr['padding-top'] = `${margin}${unit}`;
            styleArr['padding-bottom'] = `${margin}${unit}`;
            break;
        default: 
            styleArr['padding-top:'] = `${margin}${unit}`;
            styleArr['padding-bottom'] = `${margin}${unit}`;
            break;
    }
    
    return styleArr;
}
function getStyleFromArr(styleArr, extra = ''){
    var retArr = [];
    for(var key in styleArr){
        retArr.push(`${key}:${styleArr[key]}`);
    }
    var styleStr = retArr.join(';');
    if(extra) styleStr += extra + ";";
    return `style='${styleStr};'`;
}
/* ***************************************
* todo : rempacer la fonction "getMarginStyle" par "getStyleArr" 
* qui permet des changement au retour avant insertion du style dans les balises html
* calcule une marge moyenne selon le nombre d'item afin d'arer la présentation
* *** */
function getMarginStyleArr2(nbItems, numStyle=0, min=3, max=8, unit='px'){
    var margin = Math.trunc((400-100-(10*nbItems)) / (nbItems * 2));
    margin = (min == max) ? min : Math.min(Math.max(parseInt(margin), min), max);
    var styleArr = [];
    
    switch(numStyle){
        case 1:  
            styleArr.push(`line-height: ${margin*3}${unit}`);
            styleArr.push(`${extra}`);
            break;
        case 2:  var strStyle = 
            styleArr.push(`padding-top: ${margin}${unit}`);
            styleArr.push(`padding-bottom: ${margin}${unit}`);
            //styleArr.push(`${extra}`);
            break;
        default: 
            styleArr.push(`padding-top:${margin}${unit}`);
            styleArr.push(`padding-bottom:${margin}${unit}`);
            //styleArr.push(`${extra}`);
            break;
    }
    
    return styleArr;
}
function getStyleFromArr2(styleArr, extra = ''){
    var styleStr = styleArr.join(';');
    if(extra) styleStr += extra + ";";
    return `style='${styleStr};'`;
}

/* ***************************************
*
* *** */
function blob(message)
  { //return true;
    if(!boolDog) return;
    if(Array.isArray(message)){
        //console.log(`......................`);
        for(var i = 0; i < t.length; i++){
            blob(`>array : ${i} : ${t[i]}`);
        }
    }else{
        //console.log(">>> functions : " + message);
    }
  } 
  
/* ***************************************
* inutilisé pour l'instant, à voir pour une intégratin dans certains plugins
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
  

/* ***************************************
*
* *** */
function getBoolBin(value, binOctet){
    return ((value & binOctet) != 0);
}

/* *********************************
*
* */
function isBitOk(numBit, value){
    //return  ((value & Math.pow(2, numBit)) > 0) ? 1 : 0 ;
    return  ((value & (1 << numBit)) > 0) ? 1 : 0 ;

}

/* *********************************
* converti une couleur au foramt RGB (r,g,b) en couleur hexa
* */
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

/* *********************************
* utilisé par rgbToHex
* */
function componentFromStr(numStr, percent) {
    var num = Math.max(0, parseInt(numStr, 10));
    return percent ?
        Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
}

/* *********************************
*
* */
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
      console.log("modifCSSRule===> aCssRules : " + aCssRules);
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


/* *********************************
*
* */
 function setStyleAttribute(id, sAttribut, sVal){
    var ob = document.getElementById(id); 
    if(ob) {
        ob.setAttribute("style", sAttribut + ':' + sVal);
        //obSilouhette.style.background=currentQuestion.options.bgSilhouette;
    }
}



/* *********************************
*
* */
function playSound(src){
  let audio = new Audio(src);
    audio.play();

}

/* *******************************************
* affiche un message d'avertissement et passe au slide suivant
* @message string : message a afficher
* @duree integer : duree en seconde d'affichage et passage au slide suivant
* @background strinf : couleur de fon du message
* @bolGoToNextSlide bollean : true passage au slide suivant, false no action, <-1 : soummission des résultat
* ********** */
function quiz_show_avertissement(message, duree, background='#FFCCFF', bolGoToNextSlide = true){
    //une petite rustine au cas ou des seconde serait passée au lieu de miliseconde

    computeAllScoreEvent();    
    QuizMaker.MessageManager.show(replaceBalisesByValues(message), duree,'',{'background=': background, 'fontSize':'1.5em','textColor':'blue'});
    quizDivChronos.stop();
    quizDivChronos.hide();
    
    if(bolGoToNextSlide < 0){
        //cas particulier du dernier slide avec soumission automatique des résultats
        setTimeout(submitAnswers, duree * 1000);
    }else if(bolGoToNextSlide){
        setTimeout(gotoNextSlide, duree * 1000);
    }
    return true;      
}



  
/* ******************************************

********************************************* */   
function isObject(value) {
  return typeof value === 'object' &&
         value !== null &&
         !Array.isArray(value);
}


/* *********************************************************************** */
/* ************ Parcourir une structure dez connée object ou tableau ===== */
/* *********************************************************************** */

/**
 * 2. LE JEU DE DONNÉES
 */
const developpeurs = [
    {
        id: 101,
        nom: "Alice",
        competences: ["JavaScript", "React"],
        estDisponible: true
    },
    {
        id: 102,
        nom: "Bob",
        competences: ["Node.js"],
        estDisponible: false
    }
];



var resultatTableau = [];
var levelStructure = 0;
var levelMax = 0;
var separateLineLength = 30
/**
 * 1. LA FONCTION PRINCIPALE
 * Elle parcourt la structure et pousse les données dans le tableau accumulateur via le callback.
 */
function parcourirStructure(data, callback, cleOuIndex = "racine") {
//alert(levelStructure);
levelStructure++;
if(levelMax > 0 && levelStructure > levelMax){levelStructure--; return;}
//if (levelStructure <3 ){callback('???', '----------------------')};
//if (levelStructure == 2){callback('niveau', '----------------------')};

    if (Array.isArray(data)) {
        if(data.length == 0){
            callback('array', '===>Tableau vide');
        }else{
          data.forEach((element, index) => {
            callback('array', `===>Index [${cleOuIndex}]`);
            parcourirStructure(element, callback, `Index [${index}]`);
          });
        }
    } 
    else if (typeof data === 'object') {
        if (data == null){
            callback('object', '===>Objet est nulle');
        }else{
          callback('object', `===>Index [${cleOuIndex}]`);
          Object.keys(data).forEach(key => {
              parcourirStructure(data[key], callback, key);
          });
        }
    } 
    else {
        // On transmet la clé et la valeur au callback
        callback(cleOuIndex, data);
    }
levelStructure--;
}
// Le callback remplit le tableau au fur et à mesure du parcours
//a utilisr pour mettre dans une table par exemple
function ajouterAuTableau2(propriete, valeur) {
    resultatTableau.push({
        Propriete: propriete,
        Valeur: valeur,
        Type: typeof valeur
    });
}
function ajouterAuTableau(propriete, valeur) {
    //resultatTableau.push(`Propriete = ${propriete} | Valeur = ${valeur} | Type = ${typeof valeur}`);
    if (levelStructure == 1){resultatTableau.push('='.repeat(separateLineLength))};

    var tabulationStr = "-".repeat(levelStructure);
    resultatTableau.push(`${levelStructure}${tabulationStr}${propriete} = ${valeur}`);
    //resultatTableau.push(`${levelStructure}${tabulationStr}${valeur}===>${propriete}`);
}

function objToString(obj, cleOuIndex = '', level = 0, showAlert = false){

    levelMax = level;
    resultatTableau = [];

    levelStructure = 0;    
    parcourirStructure(obj, ajouterAuTableau, cleOuIndex = 'racine');
    resultatTableau.push('='.repeat(separateLineLength));
    
    //parcourirStructure(developpeurs, ajouterAuTableau);

    // Affichage du tableau final sous forme de table textuelle
    console.table(resultatTableau);    


    //alert(resultatTableau.join("\n"));
    if(showAlert){alert(resultatTableau.join("\n"));}
}


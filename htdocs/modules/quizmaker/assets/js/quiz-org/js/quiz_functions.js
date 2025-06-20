

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
    return  ((value & Math.pow(2, numBit)) > 0) ? 1 : 0 ;

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
* ********** */
function quiz_set_mask(visible){
 
    divMask =  document.getElementById('quiz_mask');    
    if(visible){
        divMask.style.visibility = 'visible';
    }else{
        divMask.style.visibility = 'hidden';
    }
    return true;
}

/* *******************************************
* affiche me message d'avertissement et passe au slide suivant
* ********** */
function quiz_show_avertissement(message, nextSlideDelai, background='#FFCCFF'){
 
    quiz_set_mask(true);
    var avertissementID = 'quiz_avertissement';
    divAvertissement =  document.getElementById(avertissementID); 

    //actualisation des scores et avancement dans le quiz
    computeAllScoreEvent();    

    //remplacement des tokens par les scores
    divAvertissement.innerHTML = replaceBalisesByValues(message);
    divAvertissement.style.background = background;


    divAvertissement.style.visibility = 'visible';
    divAvertissement.classList.add('avertissement_fondu');  
console.log(`quiz_show_avertissement : nextSlideDelai = ${nextSlideDelai}`);      

      var ida = setTimeout(quiz_hidde_avertissement, nextSlideDelai*1000, avertissementID);
      //clearTimeout(ida);
      return true;      
}

/* *******************************************
* 
* ********** */

function quiz_hidde_avertissement(avertissementId){
//console.log(`quiz_hidde_avertissement : delai = ${nextSlideDelai}`);
    var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';   
        btnNextSlide.click(); 
    
    divAvertissement =  document.getElementById(avertissementId);
    divAvertissement.style.opacity = '0';
    divAvertissement.classList.remove('avertissement_fondu');        

    divAvertissement.style.visibility = 'hidden';
    quiz_set_mask(false);
//alert('ok'); 
}

/* ***************************************
algorithme qui calcul la position absolue d'un div sur une page html 
**************************************** */
function getAbsolutePosition(element) {
  //return { 'x': 0, 'y': 0 };
  let x = 0;
  let y = 0;
  let currentElement = element;

  while (currentElement && currentElement !== document.body) {
    x += currentElement.offsetLeft;
    y += currentElement.offsetTop;
    currentElement = currentElement.offsetParent;
  }
    console.log(`getAbsolutePosition :  obSource : ${element.id} - x = ${x} - y = ${y}`);

  return { 'x': x, 'y': y };
}


/* ***************************************************************** */
/* ***** functions sur les chaines de caractères et les textes ***** */
/* ***************************************************************** */

/* ***************************************
*
* *** */
function toProperName(name){
    return name.charAt(0).toUpperCase() + name.substring(1).toLowerCase();
}

/* *********************************
*
* */
function getShortName(fullName) {
  var pos = fullName.lastIndexOf('/');
  return (pos >= 0) ? fullName.substring(pos+1) :  fullName;
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


/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de poncuation
* - remplace les caractères accetués
* *********************************************** */
function compareExp(exp1, exp2, bolToLower = true){
    var newExp1 = sanityseTextForComparaison(exp1, bolToLower);
    var newExp2 = sanityseTextForComparaison(exp2, bolToLower);
    //alert(`compareExp=====>|${newExp1}|===|${newExp2}|`);
    //var bolOk = (newExp1 == newExp2) ? 'true' : 'false';
    //console.log (`compareExp==${bolOk}===>|${newExp1}|===|${newExp2}|`);
    
    return (newExp1 == newExp2) ? true : false;
}


/* *********************************************

* *********************************************** */
function strip_tag(string) {
    return string.replace(/(<([^>]+)>)/gi, "");
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - supprime les "<br>" et les  "|n"
* - supprime les caractères de ponctuation
* - remplace les caractères accetués
* *********************************************** */
function sanityseTextForComparaison(exp, bolToLower = true){
var regAccent;
var car2rep;

    if (bolToLower){exp = exp.toLowerCase();}
    exp = strip_tag(exp);
    var reponse = exp.replaceAll("<br>","").replaceAll("\n","").replaceAll("\r","").trim(); //.replaceAll(" ","")
    
    var cars2del = new RegExp('[\ \'\.\!\?\,\;\@]', 'gi');
        
    reponse = reponse.replaceAll(cars2del, "");
    reponse = reponse.replaceAll('-', "");
    reponse = sanityseAccents(reponse);
    //console.log (`sanityseTextForComparaison=====>|${reponse}|`);
    //alert(reponse);    
    return reponse;
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - remplace les caractères accetués
* *********************************************** */
function sanityseAccents(exp, setCasse=0){
var regAccent;
var car2rep;
var arrExp1 = new Array ('aàâä', 'eéèêë', 'iîï', 'oôö', 'uùüü', 'cç', 'nñ',
                         'AÀÂÄ', 'EÉÈÊË', 'IÎÏ', 'OÔÖ', 'UÙÜÜ', 'CÇ', 'NÑ');

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
        //console.log('sanityseAccents : ' + regAccent + "--->" + car2rep);
        reponse = reponse.replaceAll(regAccent, car2rep);
        
        regAccent = new RegExp('[' + arrExp1[h].substring(1).toUpperCase() + ']', globalParam);
        car2rep = arrExp1[h][0].toUpperCase();
        //console.log('sanityseAccents : ' + regAccent + "--->" + car2rep);
        reponse = reponse.replaceAll(regAccent, car2rep);

       //if(!ignoreCasse)
    }
    return reponse;
}

/* *******************************
* renvoi un tableau des expressions entre accolades
* *** */
function getToken2(exp, keepAccolade = true){

    var regex = quiz_config.regexAllLettersPP;
    
    var allTokens = exp.match(regex);
    allTokens = [...new Set(allTokens)];

    return allTokens;
}  

/* *******************************
* renvoi un tableau des expressions entre accolades
* *** */
function getToken(exp, keepAccolade = true){

    var regex = quiz_config.regexAllLettersPP;
    var allTokens = exp.match(regex);
    allTokens = [...new Set(allTokens)];
    //alert("===>getToken 1 :\n" + allTokens.join("\n"));
    
    var key = '';
    tokensArr = new Object;
    for (var h = 0; h < allTokens.length; h++) {
        key = allTokens[h].replaceAll('{','').replaceAll('}','');
        token = allTokens[h];
        tokensArr[key] = token;
        //alert('tokensArr : ' + tokensArr['k-' + token] );
    }

/*
    var tExp = [];
    
    for(var key in tokensArr){
        tExp.push(`+++>${key} = ${tokensArr[key]}`);
        alert(`+++>${key} = ${tokensArr[key]}`);
    }
    
    alert("===>getToken 2 :\n" + tExp.join("\n"));
*/    
    return allTokens;
}  


/* *********************************************
* remplace tous les token par leur valeur
* utilisé pour l'affichage des score notamment dans "pageEnd" et "pageGroup"
* *********************************************** */
function replaceBalisesByValues(exp, slideNumber = 0, replaceDblSlash = true)
{
    var allTokens = getToken(exp);
    var token = '';
    var newExp = exp;
    var currentQuestion = null;
    var parentId = 0;
    
    if(slideNumber > 0)  {
        var currentQuestion = quizard[slideNumber].question;
        parentId = currentQuestion.questId;
    }
    
    for(var key in tokensArr){
        token = tokensArr[key];
        
        newExp = newExp.replaceAll(`{${token}}`, quiz_messages[token]);
        
        switch(token){
        case "{repondu}"        : newExp = newExp.replaceAll(token, statsTotal.cumul_questions); break; 
        case "{totalQuestions}" : newExp = newExp.replaceAll(token, statsTotal.quiz_questions); break; 
        case "{score}"          : newExp = newExp.replaceAll(token, statsTotal.cumul_score); break;
        case "{scoreMaxiQQ}"    : newExp = newExp.replaceAll(token, statsTotal.quiz_score_maxi); break;
        case "{scoreMiniQQ}"    : newExp = newExp.replaceAll(token, statsTotal.quiz_score_mini); break;
        case "{scoreMaxi}"      : newExp = newExp.replaceAll(token, statsTotal.quiz_score_maxi); break;
        case "{scoreMini}"      : newExp = newExp.replaceAll(token, statsTotal.quiz_score_mini); break;
        case "{duree}"          : newExp = newExp.replaceAll(token, formatChrono(statsTotal.cumul_timer)); break;
        case "{sommaire}"       : newExp = newExp.replaceAll(token, get_sommaire(0,0)); break;
        case "{groups}"         : newExp = newExp.replaceAll(token, get_sommaire(1,0)); break;
        case "{allquestions}"   : newExp = newExp.replaceAll(token, get_sommaire(2,0)); break;
        case "{questions}"      : newExp = newExp.replaceAll(token, get_sommaire(2, parentId)); break;
        }
    }
    if(currentQuestion)  {
        //var currentQuestion = quizard[slideNumber].question;
                   
        switch(token){
        case "{slideNumber}": newExp = newExp.replaceAll(token, slideNumber); break; 
        case "{timer}"      : newExp = newExp.replaceAll(token, formatChrono(currentQuestion.timer)); break; 
        }
     }
                   
                   
    //remplacement des parametre de connexion : userName, uid, ...               
    quiz_request_keys.forEach((key) => {
        newExp = newExp.replaceAll(`{${key}}`, quiz_rgp[key]);
    });
    
    //remplacemnet des double slash par des retour a la ligne
    //sauf pour les url             
    if(newExp.indexOf('http') < 0){
        newExp = newExp.replaceAll('\/\/',  '<br>');
    }

    return newExp;
    
  }
/* *********************************************
* remplace tous les token par leur valeur
* utilisé pour l'affichage des score notamment dans "pageEnd" et "pageGroup"
* *********************************************** */
function replaceBalisesByValues_old(exp, slideNumber = 0, parentId = 0, replaceDblSlash = true)
{alert(getToken(exp).join("\n"));
    var newExp = exp;
       //alert('replaceBalisesByValues : silderNum = ' + slideNumber + "\n" + currentQuestion.question + "\n" + exp);
    
    for (const codeLg in quiz_messages) {
        //console.log(`${codeLg}: ${quiz_messages[codeLg]}`);
        newExp = newExp.replaceAll(`{${codeLg}}`, quiz_messages[codeLg]);
    }

    newExp = newExp.replaceAll("{repondu}", statsTotal.cumul_questions)
                   .replaceAll("{totalQuestions}", statsTotal.quiz_questions)
                   .replaceAll("{score}", statsTotal.cumul_score)
                   .replaceAll("{scoreMaxiQQ}", statsTotal.quiz_score_maxi)
                   .replaceAll("{scoreMiniQQ}", statsTotal.quiz_score_mini)
                   .replaceAll("{scoreMaxi}", statsTotal.quiz_score_maxi)
                   .replaceAll("{scoreMini}", statsTotal.quiz_score_mini)
                   .replaceAll("{duree}",  formatChrono(statsTotal.cumul_timer));
//alert(`slideNumber = ${slideNumber} \n exp = ${exp}`);                   
    if(slideNumber)  {
        var currentQuestion = quizard[slideNumber].question;
        newExp = newExp.replaceAll("{slideNumber}", slideNumber)
                      .replaceAll("{timer}",  formatChrono(currentQuestion.timer));
//                   .replaceAll("{scoreQuestion}", currentQuestion.scoreMaxiQQ)
    }             
                   //.replaceAll("{scoreQuestion}", currentQuestion.getScoreByProposition() );
    //alert(`duree = ${formatChrono(statsTotal.cumul_timer)} - cumul_timer = ${statsTotal.cumul_timer}`);
    quiz_request_keys.forEach((key) => {
        //alert(key + " = " + quiz_rgp[key]);
        newExp = newExp.replaceAll(`{${key}}`, quiz_rgp[key]);
    });
    if (newExp.search('{sommaire}') >= 0)     {newExp = newExp.replaceAll("{sommaire}", get_sommaire(0,0));}
    if (newExp.search('{groups}') >= 0)       {newExp = newExp.replaceAll("{groups}", get_sommaire(1,0));}
    if (newExp.search('{allquestions}') >= 0) {newExp = newExp.replaceAll("{allquestions}", get_sommaire(2,0));}
    if (newExp.search('{questions}') >= 0)    {newExp = newExp.replaceAll("{questions}", get_sommaire(2, parentId));}

    
    if(newExp.indexOf('http') < 0){
        newExp = newExp.replaceAll('\/\/',  '<br>');
    }

    return newExp;
    
  }
  
/* *******************************************
* remplace les code entre accollades par leur valeur
* ********** */
function fo_sprint(exp, attempts=0, collectionLength=0){
    if(!exp) {
        return 'zzz';
    }
    exp = exp.replace('{winning}',attempts.winning)
             .replace('{total}', attempts.total)
             .replace('{max}', attempts.max)
             .replace('{length}', collectionLength);

    exp = exp.replace('\/\/', '<br>');
//    exp = exp.replace('\/', '<br>');
    exp = exp.replace('/' + '/', '<br>');
        
    return exp;
}
/* *******************************************
* remplace les // par des retour a la ligne
* toutefois il est possible de les remplace par une autre expression
* exp : Expression a modifier
* newString : expression de remplacement des "//"
* ********** */
function replaceDoubleSlash(exp, newString='<br>'){
    if(!exp) {
        return 'yyy';
    }
      
    exp = exp.replaceAll('\/\/', newString);
//    exp = exp.replace('\/', newString);
    exp = exp.replaceAll('/' + '/', newString);
       
    return exp;
}
  
/* *********************************
* remplace tout separateur potentiel par le seul separateur newSep, par defaut : "|"
* */
function setAllSepByNewSep(exp, newSep = "|") {
//alert('setAllSepByNewSep : ' + exp + "===>" + newSep);
  //return exp.replaceAll(/\;\,\_\|/gi, newSep); // a revoir
  return exp.trim().replaceAll(';',newSep)
                   .replaceAll('-',newSep)
                   .replaceAll(',',newSep)
                   .replaceAll('|',newSep)
                   .replaceAll('/',newSep);
}


/**
 * 
 */
function decodeHTMLEntities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = text;
  return textArea.value;
}

/* *******************************
*
* *** */
  function formatChrono (chrono, nbDigits = 0){
        var minutes = Math.floor(chrono/60);
        var secondes = chrono - (minutes*60);
        
        if(nbDigits > 0){
          var expMinutes = minutes.toString().padStart(2, '0');
          var expSecondes = secondes.toString().padStart(2, '0');
        }else{
          var expMinutes = minutes.toString();
          var expSecondes = secondes.toString();
        }
    
        if(minutes == 0){
            var tplFormatChrono = quiz_messages.formatDureeS;
        }else{
            var tplFormatChrono = quiz_messages.formatDureeMS;
        }

        return tplFormatChrono.replace("{minutes}", expMinutes)
                              .replace("{secondes}", expSecondes);
  }




function isEmail(exp)
{
/*
/^(([^<()[\]\\.,;:\s@\]+(\.[^<()[\]\\.,;:\s@\]+)*)|(.+))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/
//Si dans votre site internet, vous acceptez les caractères unicode, utilisez l'expression suivante :
/^(([^<>()[].,;:s@]+(.[^<>()[].,;:s@]+)*)|(.+))@(([^<>()[].,;:s@]+.)+[^<>()[].,;:s@]{2,})$/i
*/
    var expressionReguliere = /^(([^<>()[]\.,;:s@]+(.[^<>()[]\.,;:s@]+)*)|(.+))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/;
    return expressionReguliere.test(exp);
}

/* *************************************
show_message : affiche le message.
utilisé après un setTimeout pour permettre le rafraichissement du slide suite a un déplacement par exemple
@exp string : : message a afficher
@questId int : numero de la question qui a généré le message
@replaceDblSlash bool : remplace '//' par des retours à la ligne
**************************************** */
function show_message(exp, slideNumber = 0, replaceDblSlash = true){
//alert(`show_message : slideNumber = ${slideNumber}`)
    exp =  replaceBalisesByValues(exp, slideNumber, 0, replaceDblSlash);
    alert(exp);    
}

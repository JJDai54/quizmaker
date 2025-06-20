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
    exp1 = sanityseTextForComparaison(exp1, bolToLower);
    exp2 = sanityseTextForComparaison(exp2, bolToLower);
    return (exp1 == exp2);
}


/* *********************************************

* *********************************************** */
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
        
    reponse = reponse.replaceAll(cars2del, "");
    reponse = sanityseAccents(reponse);
    
    return reponse;
}

/* *********************************************
* prépare un texte pour une comparaison avec un autre texte saisi
* - remplace les caractères accetués
* *********************************************** */
function sanityseAccents(exp, setCasse=0){
var regAccent;
var car2rep;
var arrExp1 = new Array ('aàâä', 'eéèêë', 'iîï', 'oôö', 'uùüü', 'cçnñ',
                         'AÀÂÄ', 'EÉÈÊË', 'IÎÏ', 'OÔÖ', 'UÙÜÜ', 'CÇNÑ');

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

/* *********************************************
* remplace tous les token par leur valeur
* utilisé pour l'affichage des score notamment dans "pageEnd" et "pageGroup"
* *********************************************** */
function replaceBalisesByValues(exp, questId = 0)
{
    var newExp = exp;
    
    for (const codeLg in quiz_messages) {
        console.log(`${codeLg}: ${quiz_messages[codeLg]}`);
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
    
    quiz_request_keys.forEach((key) => {
        //alert(key + " = " + quiz_rgp[key]);
        newExp = newExp.replaceAll(`{${key}}`, quiz_rgp[key]);
    });
    
    if (newExp.search('{sommaire}') >= 0)     {newExp = newExp.replaceAll("{sommaire}", get_sommaire(0,0));}
    if (newExp.search('{groups}') >= 0)       {newExp = newExp.replaceAll("{groups}", get_sommaire(1,0));}
    if (newExp.search('{allquestions}') >= 0) {newExp = newExp.replaceAll("{allquestions}", get_sommaire(2,0));}
    if (newExp.search('{questions}') >= 0)    {newExp = newExp.replaceAll("{questions}", get_sommaire(2, questId));}
   
    var newExp = newExp.replaceAll('\/\/',  '<br>');
    return newExp;
    
  } 
/* *********************************
* remplace tout separateur potentiel par le seul separateur newSep, par defaut : "|"
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
  function formatChrono (chrono){
        var minutes = Math.floor(chrono/60);
        var expMinutes = minutes.toString().padStart(2, '0');

        var secondes = chrono - (minutes*60);
        var expSecondes = secondes.toString().padStart(2, '0');
    
        if(minutes == 0){
            var tplFormatChrono = quiz_messages.formatDureeS;
        }else{
            var tplFormatChrono = quiz_messages.formarDureeMS;
        }

        return tplFormatChrono.replace("{minutes}", expMinutes)
                              .replace("{secondes}", expSecondes);
  }


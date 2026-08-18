function getPlugin_equation(question, slideNumber){
    return new equation(question, slideNumber, 'equation')
}  

 /*******************************************************************
  *                     equation
  * *****************************************************************/
/*
mettre dans le plugin equation les methodes communes et faire hériter les deux autre sur equation
*/
class equation extends Plugin_Prototype{
name = "equation";

/* *************************************
*
* ******** */
buildSlide (bShuffle = true){
    this.boolDog = true;
    return this.getInnerHTML(bShuffle);
    
 }
/* *************************************
*
* ******** */
getInnerHTML(bShuffle = true){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var skin = parseFileName(options.skin);    
    var imgUrl = `${currentQuestion.urlPlugin}/img/skins/${skin.shortName}`;    
    options.nbMouvements = 0;

    //var equationArr = this.parseEquation(ans.proposition);
    
    var html = [];
    
    var numAns = getRandom(currentQuestion.answers.length-1);
    var ans = currentQuestion.answers[numAns];
    
 /*
    html .push(`====== EQUATION ====================<br>`);
    //html.push(this.parseEquation(ans.proposition).join('|');
    html.push('<br>');
    html.push(ans.buffer);
 */   
    //il faut recupereer gridColumns qui est caolculé dans parseEquation;
    var equation = this.parseEquation(ans.proposition);

    html.push('<div class="divbody">');
//alert(options.gridColumns)
    var imgEquation = `background-image: url(${this.data.imgUrl}/equation.png`;
    html.push(`<div id="${this.getId('equation')}" class="equation-grid"  style="width:${options.gameWidth}px;grid-template-columns: repeat(${options.gridColumns}, auto);${imgEquation}" slidenumber="${this.slideNumber}" numans="${numAns}">`);
    html.push(equation);
    html.push('</div>');

    var imgStock = `background-image: url(${this.data.imgUrl}/stock.png`;
    html.push(`<div id="${this.getId('stock')}" class="theStock" style="${imgStock}">`);
    html.push(this.parseValues(ans.buffer));
    html.push('</div>');
    
    html.push('</div>');
    
    //ajout pour le dev a gader ça peut encore servire
    //html.push(`<button class="btnVerifier" onclick="verifier(${this.slideNumber})" style="margin-top: 20px; padding: 10px 20px;">Vérifier</button>`);
    
    return html.join("\n");
}




/* *************************************
*
* ******** */
parseEquation(equation){
const parentheses = "()[]"; //pas d'accolades qui sont utilisée pour les valeurs à trouver
const operateurs = "+-/*:*x^?" ;
const comparaison = '=<>!'; //inutilisé pour l'instant
const allSymboles = operateurs + comparaison + parentheses;





    var newEquation = equation;
    var gridColumns = 0;
    var options = this.question.options;
    for (var h = 0; h < allSymboles.length; h++){
        newEquation = newEquation.replaceAll(allSymboles[h], `|${allSymboles[h]}|`);
    }
    newEquation = newEquation.replaceAll('||','|');
    if(newEquation[0] == '|'){
        newEquation = newEquation.substring(1);
    }
    if(newEquation[newEquation.length-1] == '|'){
        newEquation = newEquation.substring(0, newEquation.length-1);
    }
    newEquation = newEquation.replaceAll('|>|=|','|>=|')
                             .replaceAll('|<|=|','|<=|')
                             .replaceAll('|<|>|','|<>|')
                             .replaceAll('|!|=|','|!=|')
                             .replaceAll('|*|*|','|**|')
                             .replaceAll('|x|x|','|xx|')
                             .replaceAll('|)|²|','|)²|')
                             .replaceAll('|?|²|','|?²|');
    //---------------------------------------
// 
// //const regex = /\?\^(\d)/g;
// const regex = /\|\?\|\^\|(\d)\|/g;
// 
//  if(this.slideNumber == 12){
//  }   
// alert(newEquation);
// /*
//  equation = equation.replace(/<sup>(\d)<\/sup>/g, (match, n) => {
//     return exposantsMap[n] || match;
// });
// */
// // 4. Le remplacement pour l'AFFICHAGE
// newEquation = newEquation.replace(regex, (match, n) => {
//     // Retourne le ? original (qui est le 1er caractère de 'match')
//     // suivi de l'exposant correspondant au chiffre capturé
//     return "|?" + exposantsMap[n] + '|';
// });
    newEquation = newEquation.replaceAll('|^|','^')
//alert(newEquation);



// La regex capture deux groupes : 
// 1. Le caractère avant le ^ (ex: ? ou 2)
// 2. Le chiffre après le ^ (ex: 3)
//const regex = /\|(\?|\d)\|\^\|(\d)\|/g;

/* je garde ppour plus tard quand j'aurai la solution pour traiter l'exposant dans l'evennement
*/
// La regex cherche le ^ suivi d'un chiffre
newEquation = newEquation.replace(/\^(\d)/g, (match, groupe1) => {
    // match est toute l'expression trouvée (ex: "^3")
    // groupe1 est le chiffre capturé (ex: "3")
    return exposantsMap[groupe1] || match;
});

// newEquation = newEquation.replace(/\|\?\|\s*\^\s*\|\s*(\d)\s*\|/g, (match, n) => exposantsMap[n]);
//alert(newEquation);
    
//alert("test ^ : " + eval("2**3"));    
    
//alert(newEquation);    
    var tHtml = [];
    var arr = newEquation.split('|');
    var style = '';
    options.gridColumns = arr.length;
    var size = ((options.gameWidth-(options.padding*4)) / options.gridColumns)  ;
    var tokenSize =options.tokenSize*1;
    var operateurSize = tokenSize/5*3  ;
    //var fontSize = `${options.fontSize}em`;
    var tokenFontSize = `${Math.round(tokenSize/3*2)}px`;
    var operateurFontSize = `${Math.round(operateurSize/3*2)}px`;
//                                   'tokenColor'      => 'black',                                  
//                                   'inconnueColor'       => 'red',                                  
//                                   'movedColor'      => 'green',                                  
    for (var h = 0; h < arr.length; h++){
    //alert(arr[h])
        if(arr[h] == ''){continue;}
        var idParam = this.getId('param',h);
        if(arr[h][0] == '?' ){ //|| arr[h] == '?²'
            style = this.getStyle('inconnue', tokenSize, tokenSize,  tokenFontSize, options.inconnueColor, null,  null);
            tHtml.push(`<div id=${idParam} class="inconnue" style="${style}" ondrop="equation_drop(event)" ondragover="allowDrop(event)">${arr[h]}</div>`); 
//         }else if(arr[h] == '='){
//             var style = `font-size:${tokenFontSize};width:${operateurSize}px;height:${operateurSize}px;background-image: url(${this.data.imgUrl}/operateur.png`;
//             tHtml.push(`<br><div id=${idParam} class="operateur" style="${style}">${arr[h]}</div><br>`); 
//         }else if(arr[h] == '²'){
//             var style = `margin-bottom:20px;font-size:${tokenFontSize/2};color:${options.inconnueColor};width:${tokenSize/2}px;height:${tokenSize/2}px;background-image: url(${this.data.imgUrl}/inconnue.png`;
//             tHtml.push(`<div id=${idParam} class="inconnue" style="${style}" ondrop="equation_drop(event)" ondragover="allowDrop(event)">${arr[h]}</div>`); 
        }else if(arr[h] == ')²' || parentheses.indexOf(arr[h]) >=0){
            style = this.getStyle('parenthese', tokenSize/2, tokenSize,  tokenFontSize, options.tokenColor, tokenSize/2,  tokenSize);
            tHtml.push(`<div id=${idParam} class="operateur" style="${style}">${arr[h]}</div>`); 
        }else if(operateurs.indexOf(arr[h]) >= 0 || comparaison.indexOf(arr[h]) >= 0 ){
            style = this.getStyle('operateur', operateurSize, operateurSize,  operateurFontSize, options.tokenColor, operateurSize,  operateurSize);
            tHtml.push(`<div id=${idParam} class="operateur" style="${style}">${arr[h]}</div>`); 
        }else{
            style = this.getStyle('constante', tokenSize, tokenSize,  tokenFontSize, options.tokenColor, null,  null);
            tHtml.push(`<div id=${idParam} class="operande" style="${style}">${arr[h]}</div>`); 
        }
        //gridColumns++;
        //if (this.slideNumber=3) alert(style);
    }
    //this.question.options.gridColumns = gridColumns;
    return tHtml.join("\n");
}
/* *************************************
*
getStyle('inconnue', tokenSize, tokenSize,  tokenFontSize, options.inconnueColor, null,  null);
* ******** */

getStyle(imgBG, divHidth, divHeight,  fontSize, color, bgWidth = null, bgHeigth = null){
    var styleArr = [];
    styleArr.push(`background-image: url(${this.data.imgUrl}/${imgBG}.png)`);
    styleArr.push(`font-size:${fontSize}`);
    styleArr.push(`color:${color}`);
    styleArr.push(`width:${divHidth}px`);
    styleArr.push(`height:${divHeight}px`);
    if(bgWidth){styleArr.push(`background-size: ${bgWidth}px ${bgHeigth}px`);}
//    styleArr.push(``);
    
   return styleArr.join(';') + ';';
}

/* *************************************
*
* ******** */
parseValues(values){
    var options = this.question.options;


    var tokenSize = options.tokenSize*1;
    var tokenFontSize = `${Math.round(tokenSize/3*2)}px`;
    

    
    var style = `font-size:${tokenFontSize};color:${options.tokenColor};width:${tokenSize}px;height:${tokenSize}px;background-image: url(${this.data.imgUrl}/token.png`;
    var arr = values.split('|');
    var tHtml = [];

    
    for (var h = 0; h < arr.length; h++){
        //var style = `background-image: url(${this.data.imgUrl}/token.png`;
        tHtml.push(`<div class="jeton" draggable="true" ondragstart="equation_drag(event)" id="${this.getId('jeton_',h)}" style="${style}">${arr[h]}</div>`); 
    }
    return tHtml.join("\n");
}






/* *************************************
*
* ******** */
prepareData(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
   if (currentQuestion.points*1 == 0) {currentQuestion.points = 1;}
   this.scoreMaxiBP = currentQuestion.points*1;   
   //alert(`prepareData : currentQuestion.points = ${currentQuestion.points}`)
   this.scoreMiniQP = 0;   

    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var rep = this.transformEquation(ans.proposition, ans.buffer);
        
        ans.proposition = rep.equation;
        ans.solution = rep.solution;
        ans.valuesArr = rep.valuesArr;
        ans.buffer = rep.values;
        
        
    }
    
    options.nbMouvements = 0;
    var skin = parseFileName(options.skin);    
    this.data.imgUrl = `${currentQuestion.urlPlugin}/img/skins/${skin.shortName}`;    

    
}

/* *******************************
* transformEquation : prepare l''équation et les valeurs proposées pour l'affichage sur le slide
* @propositioin string : equation à mettre en forme
* buffer string : valeurs proposées dans le stock
* *** */
transformEquation(proposition, buffer){
var ret = {solution : '', values : '', valuesArr : null, equation : ''};
const sep = '|';

    var exp = proposition.toLowerCase().replaceAll(' ','').replaceAll('*','x');
    
    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;
    //var valuesTofindArr = exp.match(regex);
    var valuesTofindArr = exp.match(/{[^{}]+}/gi);
    //ca c'est idiot ça supprime les doublons
    //valuesTofindArr = [...new Set(valuesTofindArr)];

    //remplacement des mots entre accolades par des chifres entre accolade
    var exp2 = exp;
    for (var i in valuesTofindArr) {
        exp2 = exp2.replaceAll(valuesTofindArr[i], '?');
    }

//------------------------------------------------------------------
    var allValues = buffer.replaceAll(' ','').replaceAll(',','.').replaceAll('/',sep); 
    allValues += sep + valuesTofindArr.join(sep).replaceAll('{','').replaceAll('}','');  
    ret.valuesArr = shuffleArray(allValues.split(sep));
                
    ret.equation = exp2; // equation avec les valeurs entre accolades remplacées par des "?"
    ret.solution = exp.replaceAll('{','').replaceAll('}',''); //exp sans les accolades
    ret.values = ret.valuesArr.join(sep);   //Tableau des bonnes et mauvaises valeurs    
/*
alert('equation : ' + ret.equation);  
alert('solution : ' + ret.solution);  
alert('values : ' + ret.values);  
*/    
    return ret;
//-------------------------------------------------

}  

//---------------------------------------------------


/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "equation"
 }


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var score = 0;
    
    if(this.equation_isOk()){
        score = currentQuestion.points;
    }

   return score;

  }

/* ************************************************
*
* ************************************************/
equation_isOk() {
    var bolOk = false;
    
    // Récupère tous les éléments de la grille dans l'ordre
    var obEquation = document.getElementById(this.getId('equation'));
    const elements =  obEquation.querySelectorAll('div');
    var exp = '';
   
    let expression = "";
    
    elements.forEach(el => {
        // Si c'est un inconnue vide, on prend son contenu, sinon la valeur fixe
        exp = el.innerText
//         if(exp.indexOf('²') >= 0){
//             //exp = exp.replace('²','');
//             //exp = `Math.sqrt(${exp.replace('²','')})`;
//             exp = `Math.pow(${exp.replace('²','')},2)`;
//            // .replace(/²/g, '^2')
//         }
        expression += exp;
    });
    expression = expression.replaceAll('x', '*')
                           .replaceAll('²','**2')   
                           .replaceAll('^','**')   
                           .replaceAll(')²','**2');   

/////////////////////////////////
//expression = preparerPourEval(expression)
/////////////////////////////////////

//expression = expression.replace(/\^(\d)/g, "**$1");

    var keys = Object.keys(inverseCorrespondances);
    //alert(keys.length)
    for(var h = 0; h < keys.length; h++){
    expression = expression.replaceAll(keys[h] , '**' + inverseCorrespondances[keys[h]])
    }
    


//alert(expression)





    
//     var comparaisonList = "=<>";
//     for(var h = 0; h < comparaisonList.length; h++){
//         if (expression.indexOf(comparaisonList[h]) >= 0){
//             var comparaison = comparaisonList[h];
//             break;
//         }
//     }
    // Sépare l'équation en deux parties au signe '='
    var comparaison = searchComparaison(expression);
    const [gauche, droite] = expression.split(comparaison);
/*
if(this.slideNumber == 9){
}
*/
    try {
//alert(`equation_isOk => ${this.slideNumber} - comparaison =>>> \"${comparaison}\" :\n` + gauche + '<===>' + droite);
        var valGauche = eval(gauche);
        var valDroite = eval(droite)
//alert(`apres eval => ${this.slideNumber} - comparaison =>>> \"${comparaison}\" :\n` + valGauche + '<===>' + valDroite);
        
        switch(comparaison){
            case '!=' :
            case '<>' : bolOk = (valGauche != valDroite);   break
            case '>=' : bolOk = (valGauche >= valDroite);   break
            case '<=' : bolOk = (valGauche <= valDroite);   break
            case '>'  : bolOk = (valGauche > valDroite);    break
            case '<'  : bolOk = (valGauche < valDroite);    break
            default:
            case '=': bolOk = (valGauche === valDroite); break
        }
        // eval() calcule la chaîne mathématique
        if (bolOk) {
            console.log("Félicitations, équation réussie !");
        } else {
            console.log("Dommage, le calcul est faux : " + eval(gauche) + " != " + eval(droite));
        }
    } catch (e) {
        console.log("Veuillez remplir tous les emplacements !");//  + "\n" + gauche + " = " + droite
    }
    return bolOk;
}
 
/* ************************************
*
* **** */
endOfGame(div = null){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
        computeAllScoreEvent();    

 console.log(`endOfGame mouvements : ${options.nbMouvements} / ${options.maxMouvements}` )  
    if(this.equation_isOk()){
        if(options.msg_nextslide_duree > 0){
            quiz_show_avertissement (options.msg_nextslide_winner, options.msg_nextslide_duree, options.msg_nextslide_background, options.msg_nextslide_duree > 0);      
        }
    }else if (options.maxMouvements > 0 && options.nbMouvements >= options.maxMouvements){
        //le nombre d'essai est dépassé
        if(options.msg_nextslide_duree > 0){
            quiz_show_avertissement (options.msg_nextslide_looser, options.msg_nextslide_duree, options.msg_nextslide_background, options.msg_nextslide_duree > 0);      
        }
    }else{
        //on ne fait rien le jeu continue, il reste qielques vies
        //alert('on continue');
    }
}



/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.onFinalyse();
//options.nbAttempts = 0;   
}

/* *************************************
*
* ******** */
showGoodAnswers ()
  {
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    
    var obEquation = document.getElementById(this.getId('equation'));
    var numAns = obEquation.getAttribute('numans')*1;
    var ans = currentQuestion.answers[numAns];
    //alert(`numAns = ${numAns} - solution = ${ans.solution}`);
    obEquation.innerHTML = this.parseEquation(ans.solution);

    computeAllScoreEvent();    
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var rnd = 0;
    
    var obEquation = document.getElementById(this.getId('equation'));
    var numAns = obEquation.getAttribute('numans')*1;
    var ans = currentQuestion.answers[numAns];
    var exp = ans.proposition;
    //alert(`numAns = ${numAns} - solution = ${ans.solution}`);
    while(exp.indexOf('?') >= 0){
        rnd = getRandom(ans.valuesArr.length-1);
        exp = exp.replace('?', ans.valuesArr[rnd]);
    }
    obEquation.innerHTML = this.parseEquation(exp);

   
    computeAllScoreEvent(); 
    //comparerEtats(); 
}
  
///////////////////////////////

} // ----- fin de la class ------

//////////////////////////////////////////////////

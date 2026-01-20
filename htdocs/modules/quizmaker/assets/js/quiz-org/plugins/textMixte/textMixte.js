/*******************************************************************
*                     textMixte
* *****************************************************************/
function getPlugin_textMixte(question, slideNumber){
    //return new textMixte(question, slideNumber);
//alert(`getPlugin_textMixte : ${question.options.variant} ===> ${question.question}`);
    switch(question.options.variant){
    case 'listbox2' : return new textListbox2(question, slideNumber, 'textListbox2'); break;
    case 'textbox'  : return new textTextbox(question, slideNumber,  'textTextbox'); break;
    //case 'textarea' : return new textTextarea(question, slideNumber, 'textTextarea'); break;
    case 'listbox1' :
    default         : return new textListbox1(question, slideNumber, 'textListbox1'); break;
    }


}

 /*******************************************************************
  *                     textMixte
  * *****************************************************************/

class textMixte extends Plugin_Prototype{
name = "textMixte";  
//---------------------------------------------------
buildSlide (bShuffle = true){
    var currentQuestion = this.question;
    return this.getInnerHTML(bShuffle);
 }
/* ***************************************
*
* *** */
  
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var options = this.question.options;
    var name = this.getName();

    var tpl0 = this.getDisposition(currentQuestion.options.disposition);
    var textboxClass = "textMixte_shadowbox";    
    var tplNum = `<span style="color:${currentQuestion.options.tokenColor};font-size:1.2em;padding-bottom:5px;">{numbering}</span>`;
    
    //alert(currentQuestion.options.lineheight);
    var fontSize = (((currentQuestion.options.fontsize*1)+10)*0.1).toFixed(1);                   
    var lineHeight = (((currentQuestion.options.lineheight*1)+10)*0.1).toFixed(1);                   
    var textbox = `<div id="${this.data.textId}" name="${name}" class="${textboxClass}" style="font-size:${fontSize}em;line-height:${lineHeight}em;" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;

//------------------------------------------------------------------
    var htmlArr = [];
    var tWordsA = this.data.words;
    var h = 0;
    for (var k=0; k < tWordsA.length; k++) {
        h++;
        //if(!currentQuestion.answers[k]) {continue;}
        var box = this.get_all_inputs(k);
        htmlArr.push(`<div style="margin-bottom:${options.intervalVertical}px">` + tplNum.replace('{numbering}', getNumAlpha(k*1,4,0)) + box + '</div>');        
        //htmlArr.push(tplNum.replace('{numbering}', getNumAlpha(k*1,4,0)) + box);        
        
    }
    var allBox = htmlArr.join("\n"); 
    this.focusId = this.getName(0,'tlb');
//------------------------------------------------------------------
    tpl0 = tpl0.replace("{textbox}", textbox)
               .replace("{listbox}", allBox)
               .replace("{image}", this.getImage());
    return tpl0;
}

//---------------------------------------------------
get_all_inputs (k){
    return '';
}


//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    //petite bidouille à la C.. pour corriger le bug du au "'" apostrophe
    //il faudra peut être l'étendre et le généraliser pour toutes les entités
    options.prose =  options.prose.replaceAll('&#039;', "'");
    //options.prose =  options.prose.replaceAll('[sup]', "<sup>");
    //options.prose =  options.prose.replaceAll('[/sup]', "</sup>");
    options.prose =  options.prose.replaceAll('[', "<");
    options.prose =  options.prose.replaceAll(']', ">");
    
  

    //recupere un tableau :
    //ret.text   = texte avec mask
    //ret.words  = tableau des mots trouvés
    //ret.textOk = text sans les accolades
    //appel des spécifité"d de la variante du plugin
    this.prepareData_local();
    this.initMinMaxQQ(2);    
    
    this.data.textSanized = sanityseTextForComparaison(this.data.textOk); 
    //alert("texte nettoyé 2 : \n" + this.data.textOk);
    //this.data.textId = currentQuestion.answers[0].ansId + '-divText';
    this.data.textId = this.getId( 'divText');
    this.data.listId = this.getId("list");
    
    return true;
}
//---------------------------------------------------
prepareData_local(){
    return true;
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    
    this.scoreMaxiBP = this.data.words.length * (currentQuestion.options.scoreByGoodWord*1);
    //this.scoreMaxiBP = this.question.points*1;
    this.scoreMiniBP = 0;

}

//---------------------------------------------------
getScoreByProposition (answerContainer){
    return 0;
}
/* **********************************************
*
* ********************************************** */
getAllPropositions (flag = 0){
    var currentQuestion = this.question;

    var name = this.getName() + '.antiseche';
    
var textboxClass = "quiz-shadowbox2";    
    var html = `<label>
        <div id="${this.data.textId}-rep" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
        </label>`;
        
    return html;
 }

/* ************************************
*
* **** */
 showGoodAnswers()
  {
    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {     
    return true;
  
  } 
 
/* ************************************
*
* **** */
 getDisposition(disposition){    
    var currentQuestion = this.question;
    
    if(disposition == "disposition-01"){
      var tpl0 = `{image}<table class='textListbox1_table'><tr><td width='${currentQuestion.options.textWidth}%'>{textbox}</td><td style='padding-left:15px;'><div id='${this.data.listId}' style='text-align:right;padding-right:8px' >{listbox}</div></td></tr></table>`;
    }else{
      var tpl0 = `{image}<table class='textListbox1_table'><tr><td width='${currentQuestion.options.textWidth}%'>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${this.data.listId}'>{listbox}</div></td></tr></table>`;
    }
    
    return tpl0;
 }

} // ----- fin de la class ------

//------------------------------------------------------------------------
function textMixte_update_event(e, idText, idParentList, slideNumber, tag) {
 this.blob(`===> quiz_textareaListbox_event - ${idText} - ${slideNumber} - ${tag}`);
    clQuestion = quizard[slideNumber];
    var obExp = document.getElementById(idText);
    var exp = clQuestion.data.text;
    
    var obLists = document.querySelectorAll(`#${idParentList}` + ' ' + tag);
    
    obLists.forEach( (obInput, index) => {
        if(obInput.value != ""){
            exp = exp.replaceAll("{"+(index*1+1)+"}", obInput.value);
        }
    });
    obExp.innerHTML = exp;

    
    //change la couleur des mots choisis
    obLists.forEach( (obInput, index) => {
        if(obInput.value != ""){
            var  tokenName = clQuestion.getName('token',index);
            tokens = document.getElementsByName(tokenName);
            //alert(tokens.length);
            //alert(tokenName + ' - ' + tokens[0].innerHTML + '===>' + tokens[0].style.color);
            tokens.forEach( (token, index) => {
                token.style.color = clQuestion.question.options.wordColor;
            });
        }
    });





    computeAllScoreEvent();
    if(e) {e.stopPropagation();}    
    return false;
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
function transformTextWithToken(exp, tokenColor = '#0000FF', tokenName){
var ret = {textOk:'', text:'', words:[], nbRows:0};
var textOk = '';
    
    //remplace les accollades 
    ret.nbRows = exp.split("\n").length; //nombre de ligne du texte
    exp = exp.replaceAll("\n", qbr); //avec mise en forme de crlf
    textOk = exp.replaceAll('{','').replaceAll('}','');
    
    //var regex = /\{[\w+\àéèêëîïôöûüù]*\}/gi;
    var regex = quiz_config.regexAllLettersPP;
    
    var tWordsA = exp.match(regex);
    tWordsA = [...new Set(tWordsA)];

    var tpl = "<span name='{name}' style='color:{tokenColor};'>{word}</span>";
    //remplacement des mots entre accolades par des chifres entre accolade
    var exp2 = exp;
    for (var i in tWordsA) {
        var token = "{" + (i*1+1) + "}";
        var word =  tpl.replace("{word}", token)
                       .replace("{name}", tokenName + "-" + i) 
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
    ret.textOk  = textOk;       //texte sans les accolades
    return ret;
//-------------------------------------------------

}  

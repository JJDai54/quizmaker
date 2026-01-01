/*******************************************************************
*                     textMixte
* *****************************************************************/
function getPlugin_textMixte(question, slideNumber){
    return new textMixte(question, slideNumber, 'textMixte'); 

//     switch(question.options.variant){
//     case 'listbox2' : return new textMixte(question, slideNumber, 'textareaListbox2'); break;
//     case 'textarea' : return new textMixte(question, slideNumber, 'textareaTextarea'); break;
//     case 'textbox'  : return new textMixte(question, slideNumber, 'textareaTextbox'); break;
//     case 'listbox1' :
//     default         : return new textMixte(question, slideNumber, 'textMixte'); break;
//     }

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

    switch(this.question.options.variant){
        case 'listbox1' : return this.getInnerHTML_allbox() ; break;
        case 'textbox' : return this.getInnerHTML_allbox() ; break;
        default: 
        case 'textarea': return this.getInnerHTML_textarea() ; break;
    
    }
}

/* ***************************************
*
* *** */
  
getInnerHTML_textarea (){
    var currentQuestion = this.question;
    var html = '';

    var nbRows = (this.data.nbRows > 8) ? 8 : this.data.nbRows;
    html = `${this.getImage()}<textarea id="${this.data.textId}"  name="${this.getName}" class="slide-proposition" rows="${nbRows}">${this.data.text}</textarea>`;

    return html;
}
//---------------------------------------------------

getInnerHTML_allbox (){

    var currentQuestion = this.question;
    var options = this.question.options;
    var name = this.getName();
   
    if(currentQuestion.options.disposition == "disposition-01"){
      var tpl0 = `{image}<table class='textMixte_table'><tr><td width='${currentQuestion.options.textWidth}%'>{textbox}</td><td style='padding-left:15px;'><div id='${this.data.listId}' style='text-align:right;padding-right:8px' >{listbox}</div></td></tr></table>`;
      var textboxClass = "textMixte_shadowbox";    
    }else{
      var tpl0 = `{image}<table class='textMixte_table'><tr><td width='${currentQuestion.options.textWidth}%'>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${this.data.listId}'>{listbox}</div></td></tr></table>`;
      var textboxClass = "textMixte_shadowbox";    
    }
    
      var tplNum = `<span style="color:${currentQuestion.options.tokenColor};font-size:1.2em;padding-bottom:5px;">{numbering}</span>`;
    
    //alert(currentQuestion.options.lineheight);
    var fontSize = (((currentQuestion.options.fontsize*1)+10)*0.1).toFixed(1);                   
    var lineHeight = (((currentQuestion.options.lineheight*1)+10)*0.1).toFixed(1);                   
    var textbox = `<div id="${this.data.textId}" name="${name}" class="${textboxClass}" style="font-size:${fontSize};line-height:${lineHeight}em;" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;

//------------------------------------------------------------------
    var htmlArr = [];
    var tWordsA = this.data.words;
    var h = 0;
    for (var k=0; k < tWordsA.length; k++) {
        h++;
        if(this.question.options.variant == 'listbox1'){
            var box = this.get_all_listbox(k);
        }else{
            var box = this.get_all_textbox(k);
        }
        htmlArr.push(`<div style="margin-bottom:${options.intervalVertical}px">` + tplNum.replace('{numbering}', getNumAlpha(k*1,4,0)) + box + '</div>');        
        //htmlArr.push(tplNum.replace('{numbering}', getNumAlpha(k*1,4,0)) + box);        
        
    }
    var allBox = htmlArr.join("<br>\n"); 
    this.focusId = this.getName(0,'tlb');
//------------------------------------------------------------------
    tpl0 = tpl0.replace("{textbox}", textbox)
               .replace("{listbox}", allBox)
               .replace("{image}", this.getImage());
    return tpl0;
}

//---------------------------------------------------
get_all_listbox (k){
    var listId = this.getId(k,'tlb');
    var tAllWords = shuffleNewArray(this.data.allWords);
    var onclick = `onchange="return textMixte_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
    var cssClass = "class='textMixte_select'";
    var obList = getHtmlCombobox( this.getName('inp'), listId,tAllWords, cssClass +' '+ onclick, false);
    var btlClear = `<input type='button' value='...'style="width:25px;" title='' onclick="clearListbox('${listId}');">`;
    return obList +  btlClear;
}
//---------------------------------------------------
get_all_textbox (k){

    var oninput=`oninput="return textMixte_update_event(event,'${this.data.textId}','${this.data.listId}',${this.slideNumber},'input');"`;
    var obInput = `<input type="text" id="${this.getId(k,'tlb')}" name="${this.getName('inp')}" value="" class="slide-proposition2" ${oninput}>`;

    return obInput;
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
  
   // alert("texte nettoyé 1 : \n" + this.data.textOk);    switch(this.question.options.variant){
    if(options.prose){
        switch(this.question.options.variant){
            case 'listbox1' : 
            case 'textbox' : 
                this.data = transformTextWithToken(options.prose, currentQuestion.options.tokenColor, this.getName('token'));
                break;
            default: 
            case 'textarea': 
                this.data  =  transformTextWithMask(options.prose, currentQuestion.options.strToReplace);
                break;
        
        }
    }else{
        switch(this.question.options.variant){
            case 'listbox1' : 
            case 'textbox' : 
                this.data = transformTextWithToken(currentQuestion.answers[0].proposition, currentQuestion.options.tokenColor, this.getName('token'));
                break;
            default: 
            case 'textarea': 
                this.data  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options.strToReplace);
                break;
        
        }
    }

    this.initMinMaxQQ(2);    
    
    //gestion des intrus pour les liste déroulante uiquement
    if(this.question.options.variant == 'listbox1'){
        //on mets tous les intrus dans la même gamelle pour les avoir tous dans toutes les listes
        this.data.allWords = duplicateArray(this.data.words);
        for(var k in currentQuestion.answers){
           var badWords = currentQuestion.answers[k].proposition.split('|');
           
                for (var k = 0; k < badWords.length; k++){
                    if (badWords[k]) {this.data.allWords.push(badWords[k]);}
                }

        }
        
    }else{
    }
    
    
    
    
    this.data.textSanized = sanityseTextForComparaison(this.data.textOk); 
    //alert("texte nettoyé 2 : \n" + this.data.textOk);
    //this.data.textId = currentQuestion.answers[0].ansId + '-divText';
    this.data.textId = this.getId( '-divText');
    this.data.listId = this.getId("list");
    
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
var currentQuestion = this.question;
var obList = null;
var points = 0;

    if(this.question.options.variant == 'textarea' || currentQuestion.points > 0){
        //dans tous les cas on retire tous les caractères inutiles  
        var obText = document.getElementById(this.data.textId);
        var reponse = sanityseTextForComparaison(obText.innerHTML);   

        console.log("===>textMixte->getScoreByProposition\n" + reponse + "\n------------------\n" + this.data.textSanized + "\n------------------\n");
        return ( this.data.textSanized == reponse) ? this.scoreMaxiBP : 0;
    
    }else if(this.question.options.variant == 'listbox1'){
        obList = this.getQuerySelector('select', this.getName('inp'));
    }else if(this.question.options.variant == 'textbox'){
        obList = this.getQuerySelector('input', this.getName('inp'))
    }

    for(var i=0; i < obList.length; i++) {
        var ob = document.getElementById(this.getId(i));
        console.log(`textarea->getScoreByProposition : ${obList[i].id} - ${obList[i].value} - ${this.data.words[i]}`);
        if(sanityseTextForComparaison(obList[i].value) == sanityseTextForComparaison(this.data.words[i])){
            points += currentQuestion.options.scoreByGoodWord*1;
        }
    }
    
    return points;
}
/* **********************************************
*
* ********************************************** */
getAllReponses (flag = 0){
    var currentQuestion = this.question;

    var name = this.getName() + '.antiseche';
    
var textboxClass = "quiz-shadowbox2";    
    var textbox = `<label>
        <div id="${this.data.textId}-rep" name="${name}-textboxarea" class="quiz-shadowbox ${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.textOk}</div>
        </label>`;
        
    return textbox;
 }

/* ************************************
*
* **** */
 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var tag = '';
    
    switch(this.question.options.variant){
        case 'listbox1' : 
        case 'textbox' : 
            var obText = document.getElementById(this.data.textId);
            //obText.innerHTML = this.data.textOk;
            tag =  (this.question.options.variant == 'listbox1') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                obInput.value = this.data.words[index];
            });
        
            textMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
            break;

        default: 
        case 'textarea': 
            var obText = document.getElementById(this.data.textId);
            obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n");
            break;
    }
          computeAllScoreEvent();    
    return true;
  
  } 
  
/* ************************************
*
* **** */
 showBadAnswers()
  {     
        var currentQuestion = this.question;
        var tag = '';
        
        switch(this.question.options.variant){
        case 'listbox1' : 
        case 'textbox' : 
            tag =  (this.question.options.variant == 'listbox1') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                var idx = getRandom(this.data.allWords.length-1);
                obInput.value = this.data.allWords[idx];
            });
            
            textMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
            break;

        default: 
        case 'textarea': 
            var obText = document.getElementById(this.data.textId);
            obText.innerHTML = this.data.textOk.replaceAll(qbr, "\n")
                                               .replaceAll('la', 'ta')
                                               .replaceAll('o', 'au');
            break;
    }

    return true;
  
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

function clearListbox(listId){
    var obList =  document.getElementById(listId);
    obList.selectedIndex = -1;
    obList.onchange();
}
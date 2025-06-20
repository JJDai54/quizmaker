/*******************************************************************
*                     textareaMixte
* *****************************************************************/
function getPlugin_textareaMixte(question, slideNumber){
    return new textareaMixte(question, slideNumber);
}

 /*******************************************************************
  *                     textareaMixte
  * *****************************************************************/

class textareaMixte extends Plugin_Prototype{
name = "textareaMixte";  
//---------------------------------------------------
buildSlide (bShuffle = true){
    var currentQuestion = this.question;
    return this.getInnerHTML(bShuffle);
 }
/* ***************************************
*
* *** */
  
getInnerHTML(bShuffle = true){

    switch(this.question.options.presentation){
        case 'listbox' : return this.getInnerHTML_allbox() ; break;
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
    var name = this.getName();
    
    if(currentQuestion.options.disposition == "disposition-01"){
      var tpl0 = `{image}<table class='textareaMixte_table'><tr><td width='50%'>{textbox}</td><td style='padding-left:15px;' width='50%'><div id='${this.data.listId}' style='text-align:right;padding-right:70px' >{listbox}</div></td></tr></table>`;
      var textboxClass = "textareaMixte_shadowbox";    
    }else{
      var tpl0 = `{image}<table class='textareaMixte_table'><tr><td>{textbox}</td></tr><tr><td style='text-align:center;padding-top:10px;'><div id='${this.data.listId}'>{listbox}</div></td></tr></table>`;
      var textboxClass = "textareaMixte_shadowbox";    
    }
    
    
    
    
    var textbox = `<div id="${this.data.textId}" name="${name}" class="${textboxClass}" rows="${this.data.nbRows}" disabled>${this.data.text}</div>`;

//------------------------------------------------------------------
    var htmlArr = [];
    var tWordsA = this.data.words;
    var h = 0;
    for (var k=0; k < tWordsA.length; k++) {
        h++;
        if(this.question.options.presentation == 'listbox'){
            var box = this.get_all_listbox(k);
        }else{
            var box = this.get_all_textbox(k);
        }
        htmlArr.push(`${getNumAlpha(k*1,currentQuestion.numbering,0)}${box}`);        
    }
    var allBox = htmlArr.join("<br>\n"); 
//------------------------------------------------------------------
    tpl0 = tpl0.replace("{textbox}", textbox)
               .replace("{listbox}", allBox)
               .replace("{image}", this.getImage());
    return tpl0;
}

//---------------------------------------------------
get_all_listbox (k){

    var tAllWords = shuffleNewArray(this.data.allWords);
    var onclick = `onchange="return textareaMixte_update_event(event,'${this.data.textId}', '${this.data.listId}', ${this.slideNumber},'select');" style="margin-bottom:2px"`;
    var cssClass = "class='textareaMixte_select'";
    var obList = getHtmlCombobox( this.getName('inp'), this.getId('tlb'),tAllWords, cssClass +' '+ onclick, false);
    return obList;
}
//---------------------------------------------------
get_all_textbox (k){

    var oninput=`oninput="return textareaMixte_update_event(event,'${this.data.textId}','${this.data.listId}',${this.slideNumber},'input');"`;
    var obInput = `<input type="text" id="${this.getId('tlb')}" name="${this.getName('inp')}" value="" class="slide-proposition2" ${oninput}>`;

    return obInput;
}


//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    //recupere un tableau :
    //ret.text   = texte avec mask
    //ret.words  = tableau des mots trouvés
    //ret.textOk = text sans les accolades
    
    switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            this.data = transformTextWithToken(currentQuestion.answers[0].proposition, currentQuestion.options.tokenColor);
            break;
        default: 
        case 'textarea': 
            this.data  =  transformTextWithMask(currentQuestion.answers[0].proposition, currentQuestion.options.strToReplace);
            break;
    
    }


    this.initMinMaxQQ(2);    

    this.data.allWords = duplicateArray(this.data.words);
    if(this.question.options.presentation == 'listbox' || currentQuestion.answers[0].buffer){
      var badWords = currentQuestion.answers[0].buffer.split(',');
      for (var k = 0; k < badWords.length; k++){
          if (badWords[k]) {this.data.allWords.push(badWords[k]);}
      }
    }
    this.data.textSanized = sanityseTextForComparaison(this.data.textOk); 
    this.data.textId = currentQuestion.answers[0].ansId + '-divText';
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

    if(this.question.options.presentation == 'textarea' || currentQuestion.points > 0){
        //dans tous les cas on retire tous les caractères inutiles  
        var obText = document.getElementById(this.data.textId);
        var reponse = sanityseTextForComparaison(obText.innerHTML);   

        console.log("===>textareaMixte->getScoreByProposition\n" + reponse + "\n------------------\n" + this.data.textSanized + "\n------------------\n");
        return ( this.data.textSanized == reponse) ? this.scoreMaxiBP : 0;
    
    }else if(this.question.options.presentation == 'listbox'){
        obList = this.getQuerySelector('select', this.getName('inp'));
    }else if(this.question.options.presentation == 'textbox'){
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
    
    switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            var obText = document.getElementById(this.data.textId);
            //obText.innerHTML = this.data.textOk;
            tag =  (this.question.options.presentation == 'listbox') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                obInput.value = this.data.words[index];
            });
        
            textareaMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
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
        
        switch(this.question.options.presentation){
        case 'listbox' : 
        case 'textbox' : 
            tag =  (this.question.options.presentation == 'listbox') ? 'select' : 'input';
            var obs = this.getQuerySelector(tag);    
            obs.forEach( (obInput, index) => {
                var idx = getRandom(this.data.allWords.length-1);
                obInput.value = this.data.allWords[idx];
            });
            
            textareaMixte_update_event(null,  this.data.textId, this.data.listId,  this.slideNumber, tag);
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
 
} // ----- fin de la classe ------

//------------------------------------------------------------------------
function textareaMixte_update_event(e, idText, idParentList, slideNumber, tag) {
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

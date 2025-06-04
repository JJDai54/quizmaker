function getPlugin_sortItems(question, slideNumber){
//alert(question.options.disposition);
    switch(question.options.classe){
    case '02-combobox'    : return new sortItems_combobox(question, slideNumber, 'sortItems_combobox'); break;
    case '03-listeapuces' : return new sortItems_ulDaDList(question, slideNumber, 'sortItems_ulDaDList'); break;
    case '04-imagesdad'   : return new imgDaDSortItems(question, slideNumber, 'imgDaDSortItems'); break;
    case '01-listbox': 
    default               : return new sortItems_listbox(question, slideNumber, 'sortItems_listbox')
    }
    //return new sortItems_combobox(question, slideNumber);
}
 /*******************************************************************
  *                     sortItems
  * *****************************************************************/
/*
mettre dans la classe sortItems les methodes communes et faire hériter les deuxautre sur srtItemd
*/
class sortItems extends Plugin_Prototype{
name = "sortItems";

/* *************************************
*
* ******** */
build (){
    this.boolDog = true;
    return this.getInnerHTML();
    
 }

/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;

    //on force l'option de mélange des options sinon aucun intéret
    //currentQuestion.shuffleAnswers = 1;
    var tWords = [];
    for(var k=0; k < currentQuestion.answers.length; k++){
        tWords.push(currentQuestion.answers[k].proposition); 
    }

    
    this.data.words = tWords;  
    this.initMinMaxQQ(1);    
    this.idListbox =  this.getId('list',1);
} 
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    //il n'y a pas de points par proposition, il faut trouver un ordre
    //on suppose que chaque items à sa place compte pour 1 points 
    //mais ce sera le nombre de points de la questions qui primera
    //this.scoreMaxiBP = this.question.answers.length;
    this.scoreMaxiBP = this.question.points;
}

/* *************************************
*
* ******** */

getAllReponses (flag = 0){
      var  currentQuestion = this.question;


    var tReponses = [];
    var k = 0; 
    var t = [];
    for(var k in this.data.words){
        t.push ([k*1+1, this.data.words[k]]);
    }

    return formatArray0(t," - ", false);
 }

/* *************************************
*
* ******** */
isListSorted(tRep){
    var  currentQuestion = this.question;
    
    var strRep = tRep.join(',');
    var strProposition = this.data.words.join(',');
    var bolOk = (strRep == strProposition);
    
    // test l'ordre inverse     
    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == strProposition);
    }
    console.log('===>isListSorted : ' + ((bolOk)?'oui':'non'));
    return bolOk;
    
}

} /* --------------------------------------------------*/

 /*******************************************************************
  *                     sortItems_combobox
  * *****************************************************************/

class sortItems_combobox extends sortItems{
name = "sortItems_combobox";

/* *************************************
*
* ******** */
getInnerHTML(){
//alert('sortItems_combobox : ' + this.typeName);
//alert("comboboxSortItems - getInnerHTML");
    const tHtml = [];
    var currentQuestion = this.question;
    this.data.styleCSS = getMarginStyle(this.data.words.length, 2, 'text-align:center;');    
    var imgHeight = currentQuestion.height;
    //alert("getInnerHTML->imgHeight : " + imgHeight);
//alert(this.data.styleCSS );
    
    
    
    name = this.getName();
    var tWords = shuffleArray(this.data.words);
    var tPropositions = [];
    for(var i = 0; i < tWords.length; i++){
        var id = `${this.getId(i)}`;                 
        tPropositions.push(`<tr><td  ${this.data.styleCSS}>${getNumAlpha(i,currentQuestion.numbering)}</td>`);
        var obList = getHtmlCombobox(name,  id, tWords, false);
        tPropositions.push(`<td ${this.data.styleCSS}>${obList}</td></tr>`);
    }
    
    var tpl = this.combobox_getDisposition(currentQuestion.image);
    var html = tpl.replace("{title}", currentQuestion.options.title)
                  .replace("{propositions}", tPropositions.join("\n"))
                  .replace("{image}", this.getImage());
    
    return html;
}



 /* *************************************
*
* ******** */
combobox_getDisposition(bolImage){

var movingBtn = "{btn0}<br>{btn1}<br>{btn2}<br>{btn3}<br>";

    if(bolImage){
        var tpl=
`<center><table>
  <tbody>
    <tr><td colspan="2"><span>{title}</span></td></tr>
    <tr><td width='30%'>{image}</td><td><table>{propositions}</table></td></tr>
  </tbody>
</table></center>`;

    }else{
        var tpl=
`<center><table>
  <tbody>
    <tr><td><span>{title}</span></td></tr>
    <tr><td><table>{propositions}</table></td></tr>
  </tbody>
</table></center>`;

    }
    return tpl;
    
}

/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){
 //alert("getScore");
 var points = 0;
 var reponse = "";
    var currentQuestion = this.question;

    const obArr = this.getQuerySelector("select", this.getName(), "", "");
    
    var tRep = [];    
    obArr.forEach((obSelect, index) => {
          //this.blob('getScoreByProposition : ' + index + ' : ' + obSelect.value + '/-/' + tWords[index]);
           tRep.push(obSelect.value);
      });
    
    var bolOk = this.isListSorted(tRep);
    var p = (bolOk) ? currentQuestion.points : 0;
    console.log('getScoreByProposition : ' + p);
    return (bolOk) ? this.scoreMaxiBP : 0;
    
  }

 


/* ************************************
*
* **** */
/* *************************************
*
* ******** */
showGoodAnswers ( answerContainer)
  {
    var currentQuestion = this.question;   
    // this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var tWords = this.data.words;
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
         
    var obLists = this.getQuerySelector("select", this.getName(), "");

    for (var k=0; k < currentQuestion.answers.length; k++){
        obLists[k].value = currentQuestion.answers[k].proposition;
    }

    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
  var currentQuestion = this.question;   

  if(this.question.options.disposition == 'disposition-01'){
    var tAns = shuffleArray(this.question.answers);
    var obLists = this.getQuerySelector("select", this.getName(), "");
    for (var k=0; k < tAns.length; k++){
        obLists[k].value = tAns[k].proposition;
    }
  }else{
    this.reloadQuestion();
    var obLists = this.getQuerySelector("select", this.getName(), "");
  }
  
}
  

 
  
 
} // ----- fin de la classe ------

//////////////////////////////////////////////////
 /*******************************************************************
  *                     sortItems_listbox
  * *****************************************************************/

class sortItems_listbox extends sortItems{
name = "sortItems_listbox";

 
/* *************************************
*
* ******** */
getInnerHTML(){
//alert("sortItems -getInnerHTML ");
    var  currentQuestion = this.question;
    var name = this.getName();

    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    var extra = ''; 
    var listItems = getHtmlListbox(name, this.idListbox, tItems, tItems.length, -1, currentQuestion.numbering, 0, extra);
    var urlPlugin   = currentQuestion.urlPlugin;    



    // generation des boutons de daplacement   
    var styleBtnImg=`style='height:${currentQuestion.options.btnHeight}px'`;
    //var tplButton = `<img  ${styleBtnImg} src="../images/buttons/${currentQuestion.options.btnColor}/btn_{moveTo}.png" onclick="quiz_MoveItemTo('{id}','{moveTo}');">`;
    var tplButton = `<img  ${styleBtnImg} src="${urlPlugin}/img/buttons/${currentQuestion.options.btnColor}/btn_{moveTo}.png" onclick="quiz_MoveItemTo('{idListbox}','{moveTo}');">`;
    var btn0 = tplButton.replaceAll('{moveTo}','top').replace('{idListbox}',this.idListbox); 
    var btn1 = tplButton.replaceAll('{moveTo}','up').replace('{idListbox}',this.idListbox); 
    var btn2 = tplButton.replaceAll('{moveTo}','down').replace('{idListbox}',this.idListbox); 
    var btn3 = tplButton.replaceAll('{moveTo}','bottom').replace('{idListbox}',this.idListbox); 



//alert ("listboxSortItems");

    var tpl = this.listbox_getDisposition(currentQuestion.image);
    var html = tpl.replace("{title}", currentQuestion.options.title)
                  .replace('{listeItems}', listItems)
                  .replace('{image}', this.getImage())
                  .replace('{btn0}', btn0)
                  .replace('{btn1}', btn1)
                  .replace('{btn2}', btn2)
                  .replace('{btn3}', btn3);
    

    return html;
}  



/* *************************************
*
* ******** */
listbox_getDisposition(bolImage){

var movingBtn = "{btn0}<br>{btn1}<br>{btn2}<br>{btn3}<br>";

    if(bolImage){
var tpl=`<center>
<table id=${this.getId('table')} class=${this.typeName} >
<tbody>
<tr>
    <td colspan="2"><span>{title}</span><td>
<tr></tr>    
    <td style="vertical-align: middle;">{image}</td>    
    <td listbox=''>{listeItems}</td>    
    <td buttons>${movingBtn}</td>    
</tr>
</tbody>
</table></center>`;

    }else{

var tpl=`<center>
<table id=${this.getId('table')} class=${this.typeName} >
<tbody>
<tr>
    <td colspan="1"><span>{title}</span><td>
<tr></tr>    
    <td listbox=''>{listeItems}</td>    
    <td buttons>${movingBtn}</td>    
</tr>
</tbody>
</table></center>`;
    }
    return tpl;
}


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer){

    var currentQuestion = this.question;
    var listObj = document.getElementById(this.idListbox);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("OPTION");

    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        this.blob("===> getScore-listSortItems : " + options[i].text + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].text) 
    }

    var bolOk = this.isListSorted(tRep);
    var p = (bolOk) ? currentQuestion.points : 0;
    console.log('getScoreByProposition : ' + p);
    return (bolOk) ? this.scoreMaxiBP : 0;
  }

//---------------------------------------------------

 
/* *************************************
*
* ******** */
showGoodAnswers ( answerContainer)
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var ob = document.getElementById(this.idListbox);
    ob.innerHTML = "";


    var tItems = this.data.words;
    for(var key in tItems)
    {
    //alert(`showGoodAnswers - ${key} = ${tItems[key]}`);
        this.blob(key + " = " +  tItems[key]);

          var option = document.createElement("option");
          option.text = tItems[key];
          option.value = tItems[key];
          ob.add(option);

    }
}

/* ************************************
*
* **** */
showBadAnswers()
{
     this.reloadQuestion(); 
}
 
} // ----- fin de la classe ------

 /*******************************************************************
  *                     sortItems_ulDaDList
  * *****************************************************************/

class sortItems_ulDaDList extends sortItems{
name = "sortItems_ulDaDList";

/* *************************************
*
* ******** */
getInnerHTML(){
    if(this.isImage()){
        var html = `<table><tr><td width='33%'>${this.getImage()}</td><td width='66%'>${this.getInnerHTML_alone()}</td></tr></table>`;
        return html;
    }else{
        return this.getInnerHTML_alone();
    }
}

/* *************************************
*
* ******** */
getInnerHTML_alone(){

    var  currentQuestion = this.question;
    var name = this.getName();
    var id = this.getName();
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }

    const html = [];
//     html.push("<hr>ca commence ici<pre><code>");
//     html.push(this.initStyleCss());
//     html.push("</code></pre><hr>");
//  alert(this.initStyleCss());   
    if (currentQuestion.options.title) html.push(`<span>${currentQuestion.options.title}</span><br>`);
    html.push(`<center><div class='ulDaDSortList'>`);
    html.push(`<ul id="${id}" >`);
    
    for (var j=0; j < tItems.length; j++){
        html.push(`<li>${tItems[j]}</li>`);
    }    
    
    html.push(``);
    html.push(`</ul>`);
    html.push(`</div></center>`);

    return html.join("\n");
}
//---------------------------------------------------
initStyleCss (){
  var currentQuestion = this.question;
  var cssArr=[];
  
  cssArr.push("<style>");
  cssArr.push(`.${this.name}_slist li{background:${currentQuestion.options.liBgDefault};}`);
  cssArr.push(`.${this.name}_slist li.hint{background:${currentQuestion.options.liBgDefault};}`);
  cssArr.push(`.${this.name}_slist li.active{background:${currentQuestion.options.liBgActive};}`);
  cssArr.push(`.${this.name}_slist li.hover{background:${currentQuestion.options.liBgHover};}`);
  cssArr.push("</style>");
    
    return cssArr.join("\n");
 }
initSlide (){
//   var currentQuestion = this.question;
    this.ulDaDSortList_init_slist(document.getElementById(this.getName()));
    return true;
 }

/* **********************************
Mise à jour des couleurs de fond différentes pour chaque question
************************************* */
onEnter() {
   var currentQuestion = this.question;
//     //alert ("===> initSlide : " + this.question.pluginName  + " - " + this.question.question + " \n->" + this.getName());
//     this.ulDaDSortList_init_slist(document.getElementById(this.getName()));

// a deplacer dans le getInnerHTML et creer une balise style propre au slide
  modifCSSRule(".ulDaDSortList_slist li", "background", currentQuestion.options.liBgDefault) ;
  modifCSSRule(".ulDaDSortList_slist li.hint", "background", currentQuestion.options.liBgDefault) ;

  modifCSSRule(".ulDaDSortList_slist li.active", "background", currentQuestion.options.liBgActive) ;
  modifCSSRule(".ulDaDSortList_slist li.hover", "background", currentQuestion.options.liBgHover) ;

}

/* *************************************
*
* ******** */

getScoreByProposition ( answerContainer){
var bolOk = true;

    var currentQuestion = this.question;
    var id = this.getName();
    
    var listObj = document.getElementById(id);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("li"), current = null;
  
    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].innerHTML) 
    }
    var bolOk = this.isListSorted(tRep);
    return (bolOk) ? currentQuestion.points : 0;
  }

  
//--------------------------------------------------------------------------------
reloadQuestion() {
    var currentQuestion = this.question;
    var id = this.getName();
    var listObj = document.getElementById(id);
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    
    var options = listObj.getElementsByTagName("li"), current = null;
    
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        options[i].innerHTML = tItems[i];
    }
}
 
/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getName();
    var listObj = document.getElementById(id);
    var tItems = this.data.words;
    
    var options = listObj.getElementsByTagName("li"), current = null;
    
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        options[i].innerHTML = tItems[i];
    }
    
}

/* ************************************
*
* **** */
showBadAnswers()
  {
    this.reloadQuestion();
}
  
/* ************************************
*
* **** */
 ulDaDSortList_init_slist (target) {
  // (A) SET CSS + GET ALL LIST ITEMS
  target.classList.add("ulDaDSortList_slist");
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
} // ----- fin de la classe ------

 /*******************************************************************
  *                     imgDaDSortItems
  * *****************************************************************/

class imgDaDSortItems extends sortItems{
      
name = 'imgDaDSortItems';

/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var tHtml = [];
    
    //ajout de l'image principal sil elle existe
    var imgMain = this.getImage();
    if(imgMain){
        tHtml.push(`<div>${imgMain}</div>`);
    }
    
    //Ajout des images de référence si elles existent toute
    // si il en manque une ce div ne sera pas affiché
    var imgRef = this.getInnerHTML_img_ref(false);
    if(imgRef){
        tHtml.push(`<div class='imgDaDSortItems'>${imgRef}</div>`);
    }
    
    
    
    
    //Ajout de la directive si elle existe
    if(currentQuestion.options.directive){
        var divDirective = (currentQuestion.options.directive) ? `<span directive="blue">${currentQuestion.options.directive}</span>` : '';
        tHtml.push(`<div class='imgDaDSortItems' directive>${divDirective}</div>`);

    }    
    
    
    //Ajout de la sequence d'image a remettre dans l'ordre
    if (this.question.options.moveMode == 2){
        //deplacement des images par survol d'ne autre image
        var sequence =  this.getInnerHTML_ins(bShuffle);
    }else{
        //deplacement des images par inserion avec carret
        var sequence =  this.getInnerHTML_subs(bShuffle);
    }
    tHtml.push(`<div class='imgDaDSortItems'>${sequence}</div>`);
    
    //-----------------------------------------
    return tHtml.join("\n");

}

/* ************************************
*
* **** */
getInnerHTML_img_ref(bShuffle = false){
    var currentQuestion = this.question;
    var tHtmlmg = [];
    var img = '';
    var captionTop='';
    var captionBottom = '';    
 
    this.data.imagesRefExists = true;
      
    var posCaption = currentQuestion.options.showCaptions;    
    var ImgStyle=`style="height:${currentQuestion.options.imgHeight2}px;"`;


    if (bShuffle){
        var newSequence = shuffleArray(this.question.answers);
    }else{
        var newSequence = duplicateArray(this.question.answers);
    }

    for(var k in newSequence){
        var ans =  newSequence[k];
        if (ans.image2){
            var src = `${quiz_config.urlQuizImg}/${ans.image2}`;
            switch (posCaption){
                case 'T': captionTop    = ans.proposition + qbr ; break;
                case 'B': captionBottom = qbr + ans.proposition ; break;
                default: break;
            }
            tHtmlmg.push(`
                <div id="${ans.ansId}-div-img2" divRefMode="1"> 
                <img id="${ans.ansId}-img2"  src="${src}" title="${ans.image2}" ${ImgStyle} alt="" >
                ${captionBottom}</div>`);
        }else{
            this.data.imagesRefExists = false
            break;
        }

    }
  
    if(this.data.imagesRefExists){
        //currentQuestion.options.showCaptions = '';
        return tHtmlmg.join("\n");
    }else{
        return '';
    }
}

/* ************************************
*
* **** */
getInnerHTML_subs(bShuffle = true){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    
    
    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
if (this.data.imagesRefExists) {posCaption = "none";}
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
var tpl =`<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent=`
onDragStart="imgDaDSortItems_subs_start(event);"
onDragOver="return imgDaDSortItems_subs_over(event);" 
onDragLeave="imgDaDSortItems_subs_leave(event);"
onDrop="return imgDaDSortItems_subs_drop(event,${this.question.options.moveMode});"
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;


    var tHtmlSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
    if (bShuffle){
        var newSequence = shuffleArray(this.question.answers);
    }else{
        var newSequence = duplicateArray(this.question.answers);
    }
    for(var k in newSequence){
        var ans =  newSequence[k];
        var src = `${quiz_config.urlQuizImg}/${ans.image1}`;
        switch (posCaption){
            case 'T': captionTop    = ans.proposition + qbr ; break;
            case 'B': captionBottom = qbr + ans.proposition ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
        tHtmlSequence.push(`
            <div id="${ans.ansId}-div" divSequenceMode="0" draggable='true' ${eventImgToEvent} index=${k}>${captionTop}
            <img id="${ans.ansId}-img"  src="${src}" title="${ans.proposition}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}

/* ************************************
*
* **** */
getInnerHTML_ins(bShuffle = true){
//alert("getInnerHTML_ins = " + this.question.answers.length);
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    

    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
     
    var tpl =`<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent1=`
onDragStart="imgDaDSortItems_ins_start(event);"
onDragOver="return imgDaDSortItems_ins_over(event,false);" 
onDragLeave="imgDaDSortItems_ins_leave(event,false);"
onDrop="return imgDaDSortItems_ins_drop(event,false);"
 
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;

var eventImgToEvent2=`
onDragOver="return imgDaDSortItems_ins_over(event,true);" 
onDragLeave="imgDaDSortItems_ins_leave(event,true);"
onDrop="return imgDaDSortItems_ins_drop(event,true);"`;


//var tplEncar = `<div id="{id}-encart" encart style="width:25px;height:${currentQuestion.options.imgHeight1}px"></div>`;
//var tplEncar = "";
var  tplEncar = `<div id="{id}-encart" encart="1" style="height:${currentQuestion.options.imgHeight1}px" ${eventImgToEvent2}></div>`;


    var tHtmlSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
    if (bShuffle){
        var newSequence = shuffleArray(this.question.answers);
    }else{
        var newSequence = duplicateArray(this.question.answers);
    }
    for(var k in newSequence){
        var ans =  newSequence[k];
        var src = `${quiz_config.urlQuizImg}/${ans.image1}`;

        switch (posCaption){
            case 'T': captionTop    = ans.proposition + qbr ; break;
            case 'B': captionBottom = qbr + ans.proposition ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);

            if(k == 0){
              tHtmlSequence.push(tplEncar.replace("{id}",this.getId(999)));
            }
            tHtmlSequence.push(`
              <div id="${ans.ansId}-div" divSequenceMode="0" draggable='true' ${eventImgToEvent1}>${captionTop}
              <img id="${ans.ansId}-img"  src="${src}" title="${ans.proposition}" ${ImgStyle} alt="" >
              ${captionBottom}</div>`);
              tHtmlSequence.push(tplEncar.replace("{id}", ans.ansId));
            
           
    }
    //--------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}

//---------------------------------------------------
 prepareData(){
    
    //var sequence = [];
    
    var currentQuestion = this.question;
    var i=-1;
    var arrIdToFind = [];
    
    for(var k in currentQuestion.answers){
        currentQuestion.answers[k].id = this.getId(k);
        if( currentQuestion.answers[k].points <= 0) currentQuestion.answers[k].points =1;
        currentQuestion.answers[k].caption = currentQuestion.answers[k].caption.replace('/'+'/', qbr).replace('/', qbr);

        //sequence.push(currentQuestion.answers[k]);

            
    }   
    

//alert(`num image1s : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        

    this.data.idSelection = this.getId('selection');
    this.data.idSuggestion = this.getId('suggestion');
    
    // modifié par "getInnerHTML_img_ref" et mis a false si une image2 n'existe pas
    //permet de placer les titres sous image2 si elles existent sinon sous image1
    this.data.imagesRefExists = true; 
    this.initMinMaxQQ(1);    
        
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var bolOk = true;
//alert("imgDaDSortItems.getScoreByProposition");
    var currentQuestion = this.question;

    //var obDivImg = document.getElementById(this.getId('img'));
    //var obListImg = obDivImg.getElementsByTagName('img');
    var obListImg = document.querySelectorAll(`#${this.getId('img img')}`);
    
    for(var k=0; k < obListImg.length; k++){
    console.log("-------imgDaDSortItems-getScoreByProposition-----------");
    console.log(obListImg[k].getAttribute('src'));
    console.log(`${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`);

        //alert(obListImg[k].getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`);
        if(obListImg[k].getAttribute('src') != `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`){
            bolOk=false;
        }
    }

this.blob((bolOk) ? 'oui' : 'non');
    return  ((bolOk) ? this.scoreMaxiBP : 0);
}

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];
     
    
    //tReponses.push (['<hr>', '<hr>']);
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
            
    for(var k in this.question.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.question.answers[k];
          var img = `<div id="${ans.ansId}-div" ${divStyle}>
            <img src="${quiz_config.urlQuizImg}/${ans.image1}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">
            <br>${ans.proposition}</div>`;
          tReponses.push (img);
    }   


    return '<div>' + tReponses.join("\n") + '</div>';
}


/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    this.getObDivMain().innerHTML = this.getInnerHTML(false);
    return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    this.getObDivMain().innerHTML = this.getInnerHTML(true);
    return true;

  } 

} // ----- fin de la classe ------     

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par substitution des images                   */
/* **************************************************************** */
function imgDaDSortItems_subs_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
}
function imgDaDSortItems_subs_over(e){
    imgDaDSortItems_set_ref(e.currentTarget,2);

    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.setAttribute('divSequenceMode',"1");
    
    return false;
}

function imgDaDSortItems_set_ref(obSrc, reference){
    var posIndex = imgDaDSortItems_getIndex(obSrc, tagName="div");
 
     var t =  obSrc.id.split('-');
    //t[3] = obSrc.getAttribute("index");
    t[3] = posIndex;
    t[4] += "-img2";
    var idRef = t.join('-');
    console.log("imgDaDSortItems_subs_over : idRef  = "+  idRef);
    var obImgRef = document.getElementById(idRef);
    if(obImgRef){
        obImgRef.setAttribute("divRefMode", reference);
    }
    
    
}

function imgDaDSortItems_getIndex(obToFind, tagName="div"){

    console.log("_getIndex : ======================================================");
    var obChildren = obToFind.parentNode.getElementsByTagName(tagName);
    for (var h=0; h < obChildren.length; h++){
    console.log("_getIndex : " + h + "--->" + obChildren[h].id + "<===>" + obToFind.id);
        if (obChildren[h].id == obToFind.id){
            break;
        }
    }
    return h;
}

function imgDaDSortItems_subs_leave(e){
    imgDaDSortItems_set_ref(e.currentTarget,1);
    e.currentTarget.setAttribute('divSequenceMode',"0");

}
function imgDaDSortItems_subs_drop(e, mode=0){
//alert('dad_drop')
    imgDaDSortItems_set_ref(e.currentTarget,1);
    idFrom = e.dataTransfer.getData("text");

    e.currentTarget.setAttribute('divSequenceMode',"0");
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    switch(mode){
        case 1 : 
            imgDaDSortItems_shiftDivImg(obSource,obDest);
            break;
        case 0 : 
        default : 
            imgDaDSortItems_replaceDivImg(obSource,obDest);
            //alert('move mode : ' + mode);
            break;
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}
/* ****
 *
 ******************************* */


// Note: Cloned copy of element1 will be returned to get a new reference back
function imgDaDSortItems_replaceDivImg(element1, element2)
{
    var clonedElement1 = element1.cloneNode(true);
    var clonedElement2 = element2.cloneNode(true);

    element2.parentNode.replaceChild(clonedElement1, element2);
    element1.parentNode.replaceChild(clonedElement2, element1);

    return false;
}

/* ***
 *
 ******************************** */
function imgDaDSortItems_shiftDivImg(obSource,obDest){
//alert(`shiftDivImg : obSource = ${obSource.id}\nobDest = ${obDest.id}`)
 obSource.parentNode.insertBefore(obSource, obDest);
}


////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par insertion des images         */
/* **************************************************************** */
function imgDaDSortItems_ins_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
    set_param(e.target.parentNode.getAttribute("id"));
}
/* ************************************************ */
function imgDaDSortItems_ins_over(e, isEncart){
//console.log("imgDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    
    
    if(isEncart){
        var encart = e.currentTarget;
    }else{
        var encart = e.currentTarget.previousElementSibling;
    }
   
    
    var obSource = document.getElementById(get_param(0));
    console.log("------------");
    console.log(obSource.id);
    console.log(obSource.previousElementSibling.id);
    console.log(obSource.nextElementSibling.id);
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
        encart.setAttribute("encart","2");
    }
    
    
    return false;
}

/* ************************************************ */
function imgDaDSortItems_ins_leave(e, isEncart){
//console.log("imgDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    
    
    if(isEncart){
        var encart = e.currentTarget;
    }else{
        var encart = e.currentTarget.previousElementSibling;
    }
   
    
    var obSource = document.getElementById(get_param(0));
    console.log("------------");
    console.log(obSource.id);
    console.log(obSource.previousElementSibling.id);
    console.log(obSource.nextElementSibling.id);
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
        encart.setAttribute("encart","1");
    }
    
    
    return false;
}

/* ************************************************ */
function imgDaDSortItems_ins_drop(e, isEncart){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    
    if(isEncart){
        var encart = e.currentTarget;
        var obDest = e.currentTarget.nextElementSibling;
    }else{
        var encart = e.currentTarget.previousElementSibling;
        var obDest = e.currentTarget;
    }
    encart.setAttribute("encart","1");
    //l'element source est une image il faut prendre le contenair
    var obSource = document.getElementById(idFrom).parentNode;
    //pas d'insertion si c'est l'encart juste avant ou juste après
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
      imgDaDSortItems_ins_shiftDivImg(obSource,obDest);
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


/* ***
 *
 ******************************** */
function imgDaDSortItems_ins_shiftDivImg(obSource,obDest){
  var obNext = obSource.nextElementSibling ; 
 
 if(obNext) {
   obSource.parentNode.insertBefore(obSource, obDest);
   obSource.parentNode.insertBefore(obNext, obDest);
 }else{
   obSource.parentNode.append(obSource);
   obSource.parentNode.insertBefore(obNext);
 }

}

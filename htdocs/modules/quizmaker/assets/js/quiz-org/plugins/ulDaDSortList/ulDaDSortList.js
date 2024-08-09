

 /*******************************************************************
  *                     _plugin_ulDaDSortList
  * *****************************************************************/

class ulDaDSortList extends Plugin_Prototype{
name = "ulDaDSortList";

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
getInnerHTML(){

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
    html.push(`<center><div id='${this.getId('main')}'>`);
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
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
/*
    if(!currentQuestion.options){
        currentQuestion.options.btnHeight}px;
    }
*/    
    
    //on force l'option de mélange des options sinon aucun intéret
    //currentQuestion.shuffleAnswers = 1;
    var tWords = [];
    for(var k=0; k < currentQuestion.answers.length; k++){
        tWords.push(currentQuestion.answers[k].proposition); 
    }

    
    this.data.words = tWords;  
    this.initMinMaxQQ(1);    
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

  
/* *************************************
*
* ******** */
isListSorted(tRep){
    var  currentQuestion = this.question;
    
    var strRep = tRep.join(',');
    var strProposition = this.data.words.join(',');
    
    var bolOk = (strRep == strProposition);
    
    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == strProposition);
    }
    return bolOk;
    
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

    return formatArray0(t,"-","");
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

/*
https://code-boxx.com/drag-drop-sortable-list-javascript/
*/



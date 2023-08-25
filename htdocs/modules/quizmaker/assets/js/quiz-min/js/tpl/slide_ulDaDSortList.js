

 /*******************************************************************
  *                     _slide_ulDaDSortList
  * *****************************************************************/

class ulDaDSortList extends quizPrototype{
name = "ulDaDSortList";

/* *************************************
*
* ******** */
build (){

    return this.getInnerHTML();
    this.boolDog = true;
 }
 
/* *************************************
*
* ******** */
getInnerHTML(){
/*
  
<div style='width:50%;text-align:center;font-size:1.2em;'>
    <!-- (B) THE LIST -->
    <ul id="sortlistzzz" class="sortlist">
      <li>First</li>
      <li>Second</li>
      <li>Third</li>
      <li>Forth</li>
      <li>Fifth</li>
    </ul>
</div>  

*/
    var  currentQuestion = this.question;
    var name = this.getName();
    var id = this.getName();
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }

    const html = [];
    html.push(`<center><div id='${this.getId('main')}' class='ulDaDSortList' style='width:50%;text-align:left;font-size:1.2em;'>`);
    if (currentQuestion.options.title) html.push(`<span>${currentQuestion.options.title}</span><br>`);
    html.push(`<ul id="${id}" >`);
    
    for (var j=0; j < tItems.length; j++){
        html.push(`<li>${tItems[j]}</li>`);
    }    
    
    html.push(``);
    html.push(`</ul>`);
    html.push(`</div></center>`);
/*
    <!-- (C) CREATE SORTABLE LIST -->
    <script>
    </script>
*/    
    return html.join("\n");
}
//---------------------------------------------------
initSlide (){
  var currentQuestion = this.question;
    //alert ("===> initSlide : " + this.question.typeQuestion  + " - " + this.question.question + " \n->" + this.getName());
    ulDaDSortList_init_slist(document.getElementById(this.getName()));

  modifCSSRule(".quiz_ulDaDSortList_slist li", "background", currentQuestion.options.liBgDefault) ;
  modifCSSRule(".quiz_ulDaDSortList_slist li.hint", "background", currentQuestion.options.liBgDefault) ;

  modifCSSRule(".quiz_ulDaDSortList_slist li.active", "background", currentQuestion.options.liBgActive) ;
  modifCSSRule(".quiz_ulDaDSortList_slist li.hover", "background", currentQuestion.options.liBgHover) ;

    return true;
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
} 

/* *************************************
*
* ******** */
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
    
    this.scoreMaxiBP = currentQuestion.points;
    this.scoreMiniBP = 0;
    
     return true;
 }
 
/* *************************************
*
* ******** */

getScore ( answerContainer){
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
  
 
  
 
} // ----- fin de la classe ------

/*
https://code-boxx.com/drag-drop-sortable-list-javascript/
*/
function ulDaDSortList_init_slist (target) {
  // (A) SET CSS + GET ALL LIST ITEMS
  target.classList.add("quiz_ulDaDSortList_slist");
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
    try{
      var aCssRules =  aStyleSheets[i].cssRules;
      for(var j = 0; j < aCssRules.length; ++j){   
        if(exp_reg.test(aCssRules[j].selectorText)){ 
          aCssRules[j].style[sPropriete]= sVal;
          bFind = true;
        }//if
      }//for
    }catch(error) {
      //cssRules: lève une DOMException: "The operation is insecure."
      console.log(error);
      continue
    }
  }
  return bFind; 
}

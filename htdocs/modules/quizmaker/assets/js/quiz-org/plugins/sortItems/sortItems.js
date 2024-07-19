

 /*******************************************************************
  *                     _sortItems
  * *****************************************************************/

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
getInnerHTML(){
    if(this.question.options.disposition == 'disposition-01'){
        return this.combobox_getInnerHTML();
    }else{
        return this.listbox_getInnerHTML();
    }
    
    
}
/* *************************************
*
* ******** */
listbox_getInnerHTML(){
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
/* ************************************
*
* **** */
combobox_getInnerHTML(){
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
    this.idListbox =  this.getId('list',1);
    this.initMinMaxQQ(2);    
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
  if(this.question.options.disposition == 'disposition-01'){
      return this.combobox_getScoreByProposition();
  }else{
      return this.listbox_getScoreByProposition();
  }
}  

listbox_getScoreByProposition ( answerContainer){

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
combobox_getScoreByProposition (){
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

/* ************************************
*
* **** */
/* *************************************
*
* ******** */
showGoodAnswers ( answerContainer){
  if(this.question.options.disposition == 'disposition-01'){
      return this.combobox_showGoodAnswers();
  }else{
      return this.listbox_showGoodAnswers();
  }
}  

listbox_showGoodAnswers()
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
combobox_showGoodAnswers(quizDivAllSlides)
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
    //var tAns = this.shuffleAnswers();
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

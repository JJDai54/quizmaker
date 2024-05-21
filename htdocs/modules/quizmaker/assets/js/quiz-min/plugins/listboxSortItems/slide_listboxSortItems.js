

 /*******************************************************************
  *                     _listboxSortItems
  * *****************************************************************/

class listboxSortItems extends quizPrototype{
name = "listboxSortItems";

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
//alert("listboxSortItems -getInnerHTML ");
    var  currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;


    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    var extra = ''; 
    var listItems = getHtmlListbox(name, id, tItems, tItems.length, -1, currentQuestion.numbering, 0, extra);
    var urlPlugin   = currentQuestion.urlPlugin;    



    // generation des boutons de daplacement   
    var styleBtnImg=`style='height:${currentQuestion.options.btnHeight}px'`;
    //var tplButton = `<img  ${styleBtnImg} src="../images/buttons/${currentQuestion.options.btnColor}/btn_{moveTo}.png" onclick="quiz_MoveItemTo('{id}','{moveTo}');">`;
    var tplButton = `<img  ${styleBtnImg} src="${urlPlugin}/img/buttons/${currentQuestion.options.btnColor}/btn_{moveTo}.png" onclick="quiz_MoveItemTo('{id}','{moveTo}');">`;
    var btn0 = tplButton.replaceAll('{moveTo}','top').replace('{id}',id); 
    var btn1 = tplButton.replaceAll('{moveTo}','up').replace('{id}',id); 
    var btn2 = tplButton.replaceAll('{moveTo}','down').replace('{id}',id); 
    var btn3 = tplButton.replaceAll('{moveTo}','bottom').replace('{id}',id); 



//alert ("listboxSortItems");

    var tpl = this.getDisposition(currentQuestion.image);
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
} 

/* *************************************
*
* ******** */
getDisposition(bolImage){

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
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
    
    this.scoreMaxiBP = currentQuestion.points;
    this.scoreMiniBP = 0;
    
     return true;
 }
 
/* *************************************
*
* ******** */

getScoreByProposition ( answerContainer){
var bolOk = true;

    var currentQuestion = this.question;
    var id = `${this.getName()}-1`;
    
    var listObj = document.getElementById(id);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("OPTION");

    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        this.blob("===> getScore-listSortItems : " + options[i].text + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].text) 
    }

    var bolOk = this.isListSorted(tRep);
   
    return (bolOk) ? currentQuestion.points : 0;
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
    
    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == strProposition);
    }
    return bolOk;
    
}

/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
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

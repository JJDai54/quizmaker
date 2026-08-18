
 /*******************************************************************
  *                     sortItems_listbox
  * *****************************************************************/

class sortItems_listbox extends sortItems{
name = "sortItems_listbox";

 
/* *************************************
*
* ******** */
getInnerHTML(bShuffle = true){
//alert("sortItems -getInnerHTML ");
    let  currentQuestion = this.question;
    
    if(!this.verifNbItems()) return "";

    var tItems = this.shuffleItems();
    if(!tItems) {return "";}

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
    //console.log('getScoreByProposition : ' + p);
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
 
} // ----- fin de la class ------


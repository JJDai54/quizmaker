 /*******************************************************************
  *                     sortItems_combobox
  * *****************************************************************/

class sortItems_combobox extends sortItems{
name = "sortItems_combobox";

/* *************************************
*
* ******** */
getInnerHTML(bShuffle = true){
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
        tPropositions.push(`<tr><td  style='${this.data.styleCSS}'>${getNumAlpha(i,currentQuestion.numbering)}</td>`);
        var obList = getHtmlCombobox(name,  id, tWords, false);
        tPropositions.push(`<td style='${this.data.styleCSS}'>${obList}</td></tr>`);
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
    //console.log('getScoreByProposition : ' + p);
    return (bolOk) ? this.scoreMaxiBP : 0;
    
  }

 


/* ************************************
*
* **** */
reloadQuestion(reloadMode = reloadShuffle){
    var currentQuestion = this.question;   
    // this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var tWords = this.data.words;
    var index = 0;
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
         
    var obLists = this.getQuerySelector("select", this.getName(), "");

    for (var k = 0; k < obLists.length; k++){
        if(reloadMode == reloadOrg){
            obLists[k].value = "";
        }else if(reloadMode == reloadShuffle){
            index = getRandom(currentQuestion.answers.length-1);
            obLists[k].value = currentQuestion.answers[index].proposition;
        }else{
            obLists[k].value = currentQuestion.answers[k].proposition;
        }
    }

    return true;

}


  
} // ----- fin de la class ------


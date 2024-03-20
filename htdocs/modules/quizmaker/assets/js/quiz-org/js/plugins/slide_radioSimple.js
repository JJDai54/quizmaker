
 /*******************************************************************
  *                     _radioSimple
  * *****************************************************************/

class radioSimple extends checkboxSimple{
name = 'radioSimple';
typeInput = 'radio'; //utilisé dans la classe parente
/* ***************************************
*
* *** */
get_optionsList(){
    var currentQuestion = this.question;
    var name = this.getName();
//alert("image : " + currentQuestion.image);
    const htmlArr = [];
    this.data.styleCSS = getMarginStyle(currentQuestion.answers.length);
    htmlArr.push(`<div id="${name}-famille" style="text-align:left;margin-left:100px;">`);
    htmlArr.push(getHtmlRadioKeys   (name, this.shuffleArrayKeys(this.data.items), currentQuestion.numbering, 0, this.data.styleCSS));  
    htmlArr.push(`</div>`);

    //alert (this.focusId);
   return htmlArr.join("\n");
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (currentQuestion.answers[i].points > 0  && currentQuestion.answers[i].points > this.scoreMaxiBP)
                this.scoreMaxiBP += currentQuestion.answers[i].points*1;
          if (currentQuestion.answers[i].points < 0 && currentQuestion.answers[i].points < this.scoreMiniBP  )
                this.scoreMiniBP = currentQuestion.answers[i].points*1;
      }

     return true;
 }
  
} // ----- fin de la classe ------


 /*******************************************************************
  *                     _comboboxSortItems
  * *****************************************************************/

class comboboxSortItems extends quizPrototype{
name = "comboboxSortItems";  
  
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }


/* ************************************
*
* **** */
getInnerHTML(){
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
        var id = `${this.getId(i)}`;    // `question${chrono}-lb-${i}`;                
        tPropositions.push(`<tr><td  ${this.data.styleCSS}>${getNumAlpha(i,currentQuestion.numbering)}</td>`);
        var obList = getHtmlCombobox(name,  id, tWords, false);
        tPropositions.push(`<td ${this.data.styleCSS}>${obList}</td></tr>`);
    }
    
    var tpl = this.getDisposition(currentQuestion.image);
    var html = tpl.replace("{title}", currentQuestion.options.title)
                  .replace("{propositions}", tPropositions.join("\n"))
                  .replace("{image}", this.getImage());
    
    return html;
}

//---------------------------------------------------
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
        var tpl=
`<center><table>
  <tbody>
    <tr><td colspan="2"><span>{title}</span></td></tr>
    <tr><td>{image}</td><td><table>{propositions}</table></td></tr>
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

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    this.scoreMaxiBP = currentQuestion.points*1;
    this.scoreMiniBP = 0;
    return true;
}

//---------------------------------------------------
getScoreByProposition (){
 //alert("getScore");
 var points = 0;
 var reponse = "";
     var currentQuestion = this.question;

      const obs = this.getQuerySelector("select", this.getName(), "", "");
      var tWords = this.data.words;
      
      //alert("getScore nb obs===> " + obs.length);
      const tReponse = [];
      var bolOk = true;
      
      obs.forEach((obSelect, index) => {
          //alert(ob.value);
          if(obSelect.value !== tWords[index]) bolOk = false ;
      });
      
      //test le sens inverse au cas ou      
      if(!bolOk && currentQuestion.options.ordre.toLowerCase() == "R"){ 
            var tWords = tWords.reverse();
            bolOk = true;
          obs.forEach((obSelect, index) => {
              //alert(ob.value);
              if(obSelect.value !== tWords[index]) bolOk = false ;
          });
      }          
      
      
      return (bolOk) ? currentQuestion.points : 0;
  }


//---------------------------------------------------
isInputOk (answerContainer){
    var currentQuestion = this.question;
      // find selected answer
      var bolOk = true;

      //const selector = `select[name=question${questionNumber}]`;
//       const selector = `select[name=${this.getName(currentQuestion)}]`;      
//       const obs = answerContainer.querySelectorAll(selector);
      //alert("getScore nb obs===> " + obs.length);
      const obs = this.getQuerySelector("select", this.getName(), "", "");
      
      obs.forEach((ob, index) => {
        //alert("getScore nb obs===> " + obs.length + " | " + ob.value);
        if (ob.value === "") bolOk = false ;
      });

      return bolOk;

 }


//---------------------------------------------------
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




//---------------------------------------------------
update(nameId, chrono) {
}

/* ************************************
*
* **** */
showGoodAnswers(quizDivAllSlides)
  {
    var currentQuestion = this.question;   
    // this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var tWords = this.data.words;
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
         
    var obLists = this.getQuerySelector("select", this.getName(), "");

    for (var h=0; h < tWords.length; h++){
        obLists[h].value = tWords[h];
    }

    return true;
  
  } 
  
/* ************************************
*
* **** */
showBadAnswers(quizDivAllSlides)
  {
    var currentQuestion = this.question;   
    // this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var tWords = this.data.words;
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
         
    var obLists = this.getQuerySelector("select", this.getName(), "");

    for (var h=1; h < tWords.length; h++){
        obLists[h].value = tWords[h-1];
    }
    obLists[0].value = tWords[tWords.length-1];

    return true;
  
  } 

} // ----- fin de la classe ------

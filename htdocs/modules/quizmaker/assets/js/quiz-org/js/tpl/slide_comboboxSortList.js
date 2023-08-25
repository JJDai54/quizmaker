
 /*******************************************************************
  *                     _comboboxSortList
  * *****************************************************************/

class comboboxSortList extends quizPrototype{
name = "comboboxSortList";  
  
//---------------------------------------------------
build (){
    const answers = [];
    var currentQuestion = this.question;
    this.data.styleCSS = getMarginStyle(this.data.words.length, 2, 'text-align:right;');    
    
    answers.push(`<style>
                  table.question_csl {
                  border: 0px solid black;
                  border-style:none;
                  border-collapse: collapse;
                  width:50%;}
                  </style>`);
    
    answers.push(`<center><table class="question_csl">`);
    answers.push(`<tr><td></td><td>${currentQuestion.options.title}</td></tr>`);
    name = this.getName();
    var tWords = this.shuffleArray(this.data.words);
    for(var i = 0; i < tWords.length; i++){
        var id = `${this.getId(i)}`;    // `question${chrono}-lb-${i}`;                
        answers.push(`<tr><td  ${this.data.styleCSS}>${getNumAlpha(i,currentQuestion.numbering)} : </td>`);
        var obList = getHtmlCombobox(name,  id, this.shuffleArray(this.data.words), false);
        answers.push(`<td>${obList}</td></tr>`);
    }
        answers.push(`</tr></table></center>`);

    return answers.join("\n");
 }

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data.words = currentQuestion.answers[0].proposition.split(',');
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    this.scoreMaxiBP = currentQuestion.points*1;
    this.scoreMiniBP = 0;
    return true;
}

//---------------------------------------------------
getScore (){
 //alert("getScore");
 var points = 0;
 var reponse = "";
     var currentQuestion = this.question;

      const obs = getObjectsByName(this.getName(), "select", "", "");
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
      const obs = getObjectsByName(this.getName(), "select", "", "");
      
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

    return formatArray0(t,"-","");

 }




//---------------------------------------------------
update(nameId, chrono) {
}

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
  
/* ************************************
*
* **** */
reloadQuestion(quizDivAllSlides)
  {
    var currentQuestion = this.question;   
    //// this.blob(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);
    var tWords = this.data.words;
//alert("reloadQuestion - " + tWords.length);
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
    var obLists = getObjectsByName(this.getName(), "select", "");
//alert(obLists.length);    
    for (var h = 0; h < tWords.length; h++){
        fillListObject(obLists[h], this.shuffleArray(tWords))
    }

    return true;
  
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
         
    var obLists = getObjectsByName(this.getName(), "select", "");

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
         
    var obLists = getObjectsByName(this.getName(), "select", "");

    for (var h=1; h < tWords.length; h++){
        obLists[h].value = tWords[h-1];
    }
    obLists[0].value = tWords[tWords.length-1];

    return true;
  
  } 

} // ----- fin de la classe ------

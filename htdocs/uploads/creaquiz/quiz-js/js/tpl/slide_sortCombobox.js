
 /*******************************************************************
  *                     _sortCombobox
  * *****************************************************************/

class sortCombobox extends quizPrototype{
name = "sortCombobox";  
  
 constructor(question, chrono) {
    super();
    this.question = question;
    this.typeName = question.type;
    this.name = question.type;
    this.chrono = chrono;
console.log("dans la classe ---> " + question.type)
    
    this.prepareData();
    this.computeScoresMinMax();

  }
  

//---------------------------------------------------

  
build (){
    const answers = [];
    var currentQuestion = this.question;
    
    for(var i in currentQuestion.answers){
        var tPtoposition = currentQuestion.answers[i].proposition.split(',');
                
        answers.push(`<table class="question">`);
        name = this.getName();
        
        for(var k in tPtoposition){
            tPtoposition = shuffleArray(tPtoposition);

            var id = `${this.getId(k)}`    // `question${questionNumber}-lb-${i}`;
            answers.push(`<tr><td style="text-align:right;">${getNumAlpha(k,quiz.numerotation)} : </td>`);
            
            var obList = getHtmlCombobox(name,  id, tPtoposition, false);
            answers.push(`<td>${obList}</td></tr>`);

            
        }
        answers.push(`</tr></table>`);


    }

    return answers.join("\n");
 }

//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    this.data.words = currentQuestion.answers[0].proposition.split(',');
}
//---------------------------------------------------
computeScoresMinMax(){
    var currentQuestion = this.question;

     this.scoreMaxi = currentQuestion.answers[0].points;

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
      if(!bolOk && currentQuestion.options.toLowerCase() == "R"){ 
            var tWords = tWords.reverse();
            bolOk = true;
          obs.forEach((obSelect, index) => {
              //alert(ob.value);
              if(obSelect.value !== tWords[index]) bolOk = false ;
          });
      }          
      
      
      return (bolOk) ? currentQuestion.answers[0].points : 0;
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
        //t.push(["zzz",this.data.words[k],"zzz"]);
        t.push ([k*1+1, this.data.words[k]]);
    }

    return formatArray0(t,"-","");

 }




//---------------------------------------------------
update(nameId, questionNumber) {
}

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
  
/* ************************************
*
* **** */
reloadQuestion(quizSlides)
  {
    var currentQuestion = this.question;   
    //console.log(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);
    var tWords = this.data.words;
//alert("reloadQuestion - " + tWords.length);
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
    var obLists = getObjectsByName(this.getName(), "select", "");
//alert(obLists.length);    
    for (var h = 0; h < tWords.length; h++){
        fillListObject(obLists[h], shuffleArray(tWords))
    }

    return true;
  
  } 
 
/* ************************************
*
* **** */
showAntiSeche(quizSlides)
  {
    var currentQuestion = this.question;   
    console.log(currentQuestion.question + " - nbPropositions = " + currentQuestion.answers.length);

    var tWords = this.data.words;
    
    //utiliser pour les tests
    //tReponses = tReponses.reverse();
         
    var obLists = getObjectsByName(this.getName(), "select", "");

    for (var h=0; h < tWords.length; h++){
        obLists[h].value = tWords[h];
    }

    return true;
  
  } 

} // ----- fin de la classe ------

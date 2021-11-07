

 /*******************************************************************
  *                     _listboxIntruders1
  * *****************************************************************/

class listboxIntruders1 extends quizPrototype{
name = "listboxIntruders1"
//---------------------------------------------------
 constructor(question, chrono) {
    super(question, chrono);
  }

//---------------------------------------------------
  


  
/* *************************************
*
* ******** */
build (){
    var  currentQuestion = this.question;

    const answers = [];
    
    var keys = this.shuffleArray(Object.keys(this.data.keyWords));
    var name = this.getName();
    var click = (quiz.onClickSimple) ? "onclick" : "ondblclick";
    var wordsList = keys.join(",");
    //-------------------------------------
    var id = `${name}-1`;
    var extra = `${click}="quiz_deleteValue('${id}');"`;
    var html = getHtmlListbox(name, id, keys, keys.length, -1, currentQuestion.numbering, 0, extra);
    return html;
 }
 
  
/* *************************************
*
* ******** */
prepareData(){
var tKeyWords = [];
    var currentQuestion = this.question;
        
     for (var k=0; k < 1; k++){
       var tw = currentQuestion.answers[k].proposition.split(",");  
       var tp = padStr2Array(currentQuestion.answers[k].points, tw.length);    
       currentQuestion.answers[k].words = tw;  
       
       for (var h=0; h < tw.length; h++){
           tKeyWords[tw[h]] = tp[h]*1;
       }
     }
     
     this.data.keyWords = this.shuffleArrayKeys(tKeyWords);  
     currentQuestion.answers[0].keys = Object.keys(tKeyWords);  
    
} 

/* *************************************
*
* ******** */
computeScoresMinMax(){

    var currentQuestion = this.question;
    var tKeyWords = this.data.keyWords;
    
    for(var key in tKeyWords)
    {
        if (tKeyWords[key]*1 > 0) this.scoreMaxi += tKeyWords[key]*1;
        if (tKeyWords[key]*1 < 0) this.scoreMini += tKeyWords[key]*1;
    }
     return true;
 }
 
/* *************************************
*
* ******** */

getScore ( answerContainer){
var points = 0;
var bolOk = true;

    var currentQuestion = this.question;

    var id = `${this.getName()}-1`;
    var obList = getObjectById(id);
    
    //recupe des items restants
    var tOptions = obList.options;
    var tSelected = [];
    for(var i = 0; i < tOptions.length; i++) {
        tSelected.push(tOptions[i].value);
   }
    
    //tout a été sélectioné c'est comme si rien avit été sélectioné, renvois 0
    if (tOptions.length === 0) return 0;
    //si aucun element selectionné renvoi 0 point    
    if (tOptions.length === currentQuestion.answers[0].keys.length) return 0;
       //-----------------------------------

    //recheche des élément absent de la list
    var tKeyWords = this.data.keyWords;
      for(var key in tKeyWords)
      {
        if (tSelected.indexOf(key) == -1){
            points += tKeyWords[key]*1;
        }
        
      }
       
      return points;

  }

  
/* *************************************
*
* ******** */
isInputOk ( answerContainer){
    var currentQuestion = this.question;

var bolOk = true;

    var id = `${this.getName()}-1`;
    var obList = getObjectById(id);

       var tOptions = obList.options;
       var minReponse = currentQuestion.minReponse;
       var nbRep = currentQuestion.answers[0].keys.length - tOptions.length;

       if (minReponse == 0){
         bolOk = (nbRep > 0);
       }else{
         bolOk = (nbRep >= minReponse);
       }



      return bolOk;

 }

  
/* *************************************
*
* ******** */

getAllReponses (flag = 0){
    var currentQuestion = this.question;
    
    var tReponses = [];
    var tKeyWords = this.data.keyWords;
    
    
    //tri desc sur le tableau
    tKeyWords = sortArrayKey(tKeyWords,"d");
    
     
    for(var key in tKeyWords)
    {
        //tReponses.push (`${key} ===> ${tKeyWords[key]} points`) ;       
        tReponses.push ([key, tKeyWords[key]]) ;       
    }

    return formatArray0(sortArrayArray(tReponses, 1, "DESC"), "=>");
 }

  
/* *************************************
*
* ******** */

// getGoodReponses (){
//     var currentQuestion = this.question;
//     var tReponses = [];
//     var tKeyWords = this.data.keyWords;
// 
//     for(var key in tKeyWords)
//     {
//         if (tKeyWords[key] > 0) tReponses.push (`${key} ===> ${tKeyWords[key]} points`) ;       
//     }
// 
//     return tReponses.join("<br>");
//  }

  

  
/* *************************************
*
* ******** */

update(nameId) {
}

  
/* *************************************
*
* ******** */

incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

//--------------------------------------------------------------------------------
reloadQuestion() {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";

    var tKeyWords = this.shuffleArrayKeys(this.data.keyWords);
    for(var key in tKeyWords)
    {
        // console.log(key + " = " +  tKeyWords[key]);
        var option = document.createElement("option");
        option.text = key;
        option.value = key;
        ob.add(option);
    }

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


    var tKeyWords = this.shuffleArrayKeys(this.data.keyWords);
    for(var key in tKeyWords)
    {
    //alert(`showGoodAnswers - ${key} = ${tKeyWords[key]}`);
        // console.log(key + " = " +  tKeyWords[key]);
        if ((tKeyWords[key]*1) <= 0) {
          var option = document.createElement("option");
          option.text = key;
          option.value = key;
          ob.add(option);
        }
    }
}

/* ************************************
*
* **** */
showBadAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";


    var tKeyWords = this.shuffleArrayKeys(this.data.keyWords);
    for(var key in tKeyWords)
    {
        // console.log(key + " = " +  tKeyWords[key]);
        if ((tKeyWords[key]*1) > 0) {
          var option = document.createElement("option");
          option.text = key;
          option.value = key;
          ob.add(option);
        }
    }
}
  
 
  
 
} // ----- fin de la classe ------

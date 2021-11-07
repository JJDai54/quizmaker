 /*******************************************************************
  *                     _quizPrototype
  * *****************************************************************/
class quizPrototype{
name = "quizPrototype";  
question = Object;
typeName = '';
chrono = 0;    
timer = 0;
scoreMini = 0;
scoreMaxi = 0;
points = 0;
reponseOk = 0;
isAntiseche = false;
data  = [];

stats = {
      scoreMin:  0,
      repondu:   0,
      score:     0
    };
//---------------------------------------------------
 constructor() {
  }
  
 build (){}
  
//---------------------------------------------------
// build (){
//     var tHtml = [];
//     tHtml.push (`<div id="slide[${this.chrono}]" name="slide${this.chrono}" class="slide" >`);
//     tHtml.push (this.build_header());
// alert("proto-build");    
//     
//     tHtml.push (`<div class="item-round-no ${quiz.colorset}-itemBody">
//                 <div class="answers  item-round-no ${quiz.colorset}-itemBody">`); 
//     tHtml.push (this.build_body());
//     tHtml.push (`</div></div>`);
// 
//     tHtml.push (this.build_footer());
//     tHtml.push (`</div>`);
// //     
// //     return tHtml.join("\n");
// return this.build_body()
//  }
// // //---------------------------------------------------
// 
// build_header (){
// alert("build_header");
// var questionNumber = this.chrono;
//        var comment1 =  getMessage(clQuestion.question.comment1, clQuestion.question.type);
// 
//         var comment2 =  getMessage(this.question.comment2);
//         comment2 = comment2.replace("{points}", this.stats.scoreMinMax1);
//        comment2 = comment2.replace("{timer}", this.timer);
// 
//        var sType = (quiz.showType) ? `<br><span style="color:red;">(${this.question.type})</span>`: '';
//        var title = `${questionNumber+1} : ${divChrono}${this.question.question}${sType}`;
// 
//        var html = `<div class="quiz-question  item-round-no ${quiz.colorset}-itemInfo">${title}</div>
// 
//             <div class="question item-round-no ${quiz.colorset}-itemInfo">
//                 <div class="quiz-comment1">${comment1}</div>
//                 <div class="quiz-comment1" id="question${questionNumber}-timer">${comment2}</div>
//                 <div class="quiz-comment1"><hr class="quiz-style-two"></div>
// 
//             </div>`
//           ;
//           
//     return html;
//  }
// //---------------------------------------------------
// build_body (){
//     return "<center>construction du body</center>";
//  }
// //---------------------------------------------------
build_footer (){
    return "<center>construction du footer</center>";
 }
//---------------------------------------------------
getName (){
              
    return `question-${this.name}-${this.chrono}`;         
}
//---------------------------------------------------
getId (index){
    return `question-${this.name}-${this.chrono}-${index}`;         
}
//---------------------------------------------------
prepareData(){
}
//---------------------------------------------------
computeScoresMinMax(){
}
//---------------------------------------------------
getScore (){
  }

//---------------------------------------------------
static  isInputOk (answerContainer){
 }

/* *******************************************
* getAllReponses : renvoie les réponse à la question
* @ flag int: 0 = toutes les réponses / 1 = que les bonnes réponses
* ********** */
getAllReponses (flag=0){
 }

//---------------------------------------------------
getGoodReponses (){
//    return ('Fonction "getGoodReponses" à développer pour la classe : ' + this.name);
    return this.getAllReponses (1);
 }

//---------------------------------------------------
//---------------------------------------------------
getScoreMinMax (){
    return {'min':this.scoreMini, 'max': this.scoreMaxi};
 }
//---------------------------------------------------
static  update(nameId, questionNumber) {
}

//---------------------------------------------------
   incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
/* ************************************
*
* **** */
reloadQuestion(currentQuestion, quizSlides)//, answerContainer
  {
    alert(currentQuestion.type + "reloadQuestion.(currentQuestion, , quizSlides){}\n===> Fonction à developper")
  } 
/* ************************************
*
* **** */
showAntiSeche(currentQuestion, quizSlides)//, answerContainer
  {
    alert(currentQuestion.type + ".showAntiSeche(currentQuestion, , quizSlides){}\n===> Fonction à developper")
  } 
//---------------------------------------------------
toString()
  {
    return this.name + " | " + this.question.question;
  } 

} // ----- fin de la classe ------

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
focusId = '';


stats = {
      scoreMin:  0,
      repondu:   0,
      score:     0
    };
//---------------------------------------------------
 constructor(question = null, chrono = 0) {
    if(question){
    this.name = question.type;
    this.question = question;
    this.typeName = question.type;
    this.typeQuestion = question.type;
    this.typeForm = question.typeForm;
    this.chrono = chrono;
// console.log("dans la classe ---> " + question.type)


    this.prepareData();
    this.computeScoresMinMax();
    }


  }
  
 build (){}
  
//---------------------------------------------------
build_footer (){
    return "<center>construction du footer</center>";
 }
//---------------------------------------------------
submitAnswers(){ 
    return false;
}
/* *******************************************
* isQuestion : permet d'activer ou désactiver le bouton suivant notamment
* @ return: bool : valeur par defaut surchargée pour l'intro les encart et le resultat
* ********** */
isQuestion (){
              
    return true;         
}

/* *******************************************
* getName : renvoi le nom utiliser pour l'attribut name des objets du dom
* @ return: nom pour l'attribut "name"
* ********** */
getName (){
              
    return `question-${this.name}-${this.chrono}`;         
}

/* *******************************************
* getId : renvoi un id unique composé de getName et d'un index pour l'attribut id des objets du dom
* @ return: id pour l'attribut "id"
* ********** */
getId (index){
    return `question-${this.name}-${this.chrono}-${index}`;         
}

/* *******************************************
* prepareData : prépare les données dns le tableau data pour faciliter les traitements
* @ return: null
* ********** */
prepareData(){
}

/* *******************************************
* * @ return: null
* ********** */
setFocus(){
    //alert("setFocus");
    if(this.focusId != '')
        document.getElementById(this.focusId).focus({focusVisible:false});
}

/* *******************************************
* shuffleArr :  Mélanges les propositions de réponse si this.shuffle_answers est vrai
* @ arr : tableau à mélanger
* @ return: le tableau mélangé
* ********** */
 shuffleArray(arr){
 //alert(this.question.shuffleAnswers);
    if(this.question.shuffleAnswers == 1){
        var arr = shuffleArray(arr);
    }
    return arr;
 }
/* *******************************************
* shuffleArr :  Mélanges les propositions de réponse si this.shuffle_answers est vrai
* @ arr : tableau à mélanger
* @ return: le tableau mélangé
* ********** */
 shuffleArrayKeys(arr){
//    alert(this.name + ' ===> shuffleArrayKeys = ' + this.question.shuffleAnswers)
    if(this.question.shuffleAnswers == 1){
        arr = shuffleArrayKeys(arr);
    }
    return arr;
 }

/* *******************************************
* computeScoresMinMax : calcul les scores minimum et maximum dans min et max
* @ return: null
* ********** */
computeScoresMinMax(){
}

/* *******************************************
* isInputOk : renvoie le nombre de points obtenu pour la question
* @ return: nombre de poits obtenus
* ********** */
getScore (){
  }


/* *******************************************
* isInputOk : renvoie vrai si reponsemin = 0 ou si le nombre minimum de réponse requise est atteint
* @ answerContainer: tableau des réponses/propositions
* ********** */
isInputOk (answerContainer){
    return true;
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
reloadQuestion(currentQuestion, quizBoxAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + "reloadQuestion.(currentQuestion, , quizBoxAllSlides){}\n===> Fonction à developper")
  } 
/* ************************************
*
* **** */
showGoodAnswers(currentQuestion, quizBoxAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + ".showAntiSeche(currentQuestion, , quizBoxAllSlides){}\n===> Fonction à developper")
  } 
  
/* ************************************
*
* **** */
showBadAnswers(currentQuestion, quizBoxAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + ".showAntiSeche(currentQuestion, , quizBoxAllSlides){}\n===> Fonction à developper")
  } 
//---------------------------------------------------
toString()
  {
    return this.name + " | " + this.question.question;
  } 

} // ----- fin de la classe ------

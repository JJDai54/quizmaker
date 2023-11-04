 /*******************************************************************
  *                     _quizPrototype
  * *****************************************************************/
class quizPrototype{
name = "quizPrototype";  
question = Object;
typeName = '';
chrono = 0;    
slideNumber = 0;
timer = 0;
scoreMiniBP = 0;
scoreMaxiBP = 0;
points = 0;
reponseOk = 0;
isAntiseche = false;
data  = [];
focusId = '';
boolDog = false;
questionNumber  = 0;

stats = {
      scoreMin:  0,
      repondu:   0,
      score:     0
    };
//---------------------------------------------------
 constructor(question = null, chrono = 0) {

    this.name = question.type;
    this.question = question;
    this.typeName = question.type;
    this.typeQuestion = question.type;
    this.typeForm = question.typeForm;
    this.chrono = chrono;
// this.blob("dans la classe ---> " + question.type)

    this.getOptionsDefaults();
    this.prepareData();
    this.computeScoresMinMax();
    
    load_css(this.name);    
    //uniquement pour palier un changement d'orientation quand a la gestion de ce parametre
    // a virer quand les quiz existants auront  ete mis a jour
    //if (!this.question.options) this.question.options = [];
    //if (!this.question.options.minReponses) this.question.options.minReponses = 0;
    }

  
//---------------------------------------------------
 build (){}
  
/* **********************************************************

************************************************************* */
getOptionsDefaults (){
    var currentQuestion = this.question;
    
    if(!currentQuestion.options){
        currentQuestion.options = Object.create(currentQuestion.optionsDefault);
        return true;
    }
    
    var arr = [];
    var keys = Object.keys(currentQuestion.optionsDefault);    
    keys.forEach((key, index) => { 
        if(currentQuestion.options[key]){
            arr[key] = currentQuestion.options[key];
        }else{
            arr[key] = currentQuestion.optionsDefault[key];
        }
    
    });            
    currentQuestion.options = Object.create(arr);
    return true;
 }
//---------------------------------------------------
initSlide (){
    return false;
 }
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
* @index : string
* @ indexElement : integer // permet d'identifier des sous élément d'un ensemble ex: image d'un div principal
* @ return : string pour l'attribut "id"
* ********** */
getId (index, indexElement = null){
    var id = `question-${this.name}-${this.chrono}-${index}`;      
    if (indexElement != null)   {id += '-' + indexElement};
    return id;
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
    if(this.focusId != ''){
        try{
        document.getElementById(this.focusId).focus({focusVisible:false});
        }catch{}
    }
}

/* *******************************************
* shuffleArr :  Mélanges les propositions de réponse si this.shuffle_answers est vrai
* @ arr : tableau à mélanger
* @ return: le tableau mélangé
* ********** */
 shuffleArray(arr){
 //alert(this.question.shuffleAnswers);
    try{
      if(this.question.options.shuffleAnswers == 1){
          var arr = shuffleArray(arr);
      }
    }catch{
          //var arr = shuffleArray(arr);
    }
        
        
    return arr;
 }
/* *******************************************
* shuffleArr :  Mélanges les propositions de réponse si this.shuffle_answers est vrai
* @ arr : tableau à mélanger
* @ return: le tableau mélangé
* ********** */
 shuffleArrayKeys(arr){
    //this.blob (this.name + ' ===> shuffleArrayKeys = ' + this.question.options.shuffleAnswers)
    try{
      if(this.question.options.shuffleAnswers == 1){
          arr = shuffleArrayKeys(arr);
      }
    }catch{
      if(this.question.shuffleAnswers == 1){
          arr = shuffleArrayKeys(arr);
      }
    }
    
    return arr;
 }

/* *******************************************
* computeScoresMinMax : calcul les scores minimum et maximum dans min et max
* @ return: null
* ********** */
computeScoresMinMax(){
    var currentQuestion = this.question;   
    
    this.computeScoresMinMaxByProposition();
    
    //si c'est le score de la question qui prime
    if(currentQuestion.points > 0){
          this.scoreMaxiQQ = currentQuestion.points;
          this.scoreMiniQQ = 0;
    }else{
          this.scoreMaxiQQ = this.scoreMaxiBP;
          this.scoreMiniQQ = this.scoreMiniBP;
    }     
    this.blob(`computeScoresMinMax BP [1] - ${this.getName()}: ${this.scoreMaxiBP}   - ${this.scoreMiniBP}`);
    this.blob(`computeScoresMinMax QQ [2] - ${this.getName()}: ${this.scoreMaxiQQ} - ${this.scoreMiniQQ}`);
        return true;
}

/* *******************************************
* computeScoresMinMax : calcul les scores minimum et maximum dans min et max
* @ return: null
* ********** */
computeScoresMinMaxByProposition(){
    return 0;
}
/* *******************************************
* isInputOk : renvoie le nombre de points obtenu pour la question
* @ return: nombre de poits obtenus
* ********** */
getScore (answerContainer){
var points = 0;

    var currentQuestion = this.question;
    var score = this.getScoreByProposition(answerContainer);
    this.blob(`getScore - ${this.getName()} : ${score} - ${this.scoreMaxiBP} - ${this.scoreMaxiQQ}`);
    
    if(currentQuestion.points > 0 && score == this.scoreMaxiBP){
        return currentQuestion.points
    }else if(currentQuestion.points > 0 && score != 0){ 
        return 0;
    }else{return score;}
}

getScoreInfos (){
    return {'question_min': this.scoreMiniBP, 
            'question_max': this.scoreMaxiBP, 
            'question_points': this.this.getScoreByProposition(0), 
            'quiz_nb_answers' : 0,
            'quiz_nb_questions' : 0,
            'quiz_score': 0,
             };
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
    return {'min':this.scoreMiniBP, 'max': this.scoreMaxiBP};
 }
//---------------------------------------------------
static  update(nameId, chrono) {
}

//---------------------------------------------------
   incremente_question(nbQuestions)
  {
    //return nbQuestions+1;
    if (this.question.isQuestion == 1){
        return nbQuestions+1;
    }else{
        return nbQuestions;
    }
  } 
  
/* ************************************
*
* **** */
reloadQuestion(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + "reloadQuestion.(currentQuestion, , quizDivAllSlides){}\n===> Fonction à developper")
  } 
/* ************************************
*
* **** */
showGoodAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + ".showAntiSeche(currentQuestion, , quizDivAllSlides){}\n===> Fonction à developper")
  } 
  
/* ************************************
*
* **** */
showBadAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    alert(currentQuestion.type + ".showAntiSeche(currentQuestion, , quizDivAllSlides){}\n===> Fonction à developper")
  } 
//---------------------------------------------------
toString()
  {
    return this.name + " | " + this.question.question;
  } 

//---------------------------------------------------
blob(message)
  {
    if(!this.boolDog) return;
    if(Array.isArray(message)){
        console.log(`......................`);
        for(var i = 0; i < t.length; i++){
            console.log(`>array : ${i} : ${t[i]}`);
        }
    }else{
        console.log(">>> " + this.getName() + " : " + message);

    }
  } 

}  // ----- fin de la classe ------

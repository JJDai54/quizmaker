 /*******************************************************************
  *                     _Plugin_Prototype
  * *****************************************************************/
class Plugin_Prototype{
name = "Plugin_Prototype";  
isQuestion = true;
divMainId = "";
question = Object;
typeName = '';
slideNumber = 0;     // numero du slide y compris les pageBegin, pageEnd, pageGroup et page Info
questionNumber  = 0; //numero desslide question uniquement
timer = 0;
scoreMiniBP = 0;
scoreMaxiBP = 0;
points = 0;
reponseOk = 0;
isAntiseche = false;
data  = [];
focusId = '';
boolDog = false;
isZoomed = false;

stats = {
      scoreMin:  0,
      repondu:   0,
      score:     0
    };
//---------------------------------------------------
 constructor(question = null, slideNumber = 0, className='') {
//alert('ptototype : ' + this.typeName);

    this.name = question.type;
    this.question = question;
    this.typeName =  (className) ? className : question.type;
    //this.pluginName = question.type;
    this.isQuestion = (question.type.substring(0, 4) != 'page');         
//alert(`constructor - ${question.type} - ` + ((this.isQuestion) ? 'oui' : 'non'));    

    this.slideNumber = slideNumber;
    this.divMainId =  this.getId('main');//mais apres affectation de slideNumber si même id    

    this.getOptionsDefaults();
    this.proto_initSlide();
    this.prepareData();
//    this.computeScoresMinMax();
    
    }

  
/* **********************************************************
Initialise toutes les données communes à tous les plugins
Appelé pour chaque slide à la fin du chargement du html
contrairement a "prepareData" qui n'accède pas encore au DOM
permet de finaliser l'initialisation du slide en accedant au DOM construction

************************************************************* */
proto_initSlide (){

    var currentQuestion = this.question;
    var ans = null;
    var points = 0;
    
    //calcul des poins min et max standarts
    for(var k in currentQuestion.answers){
        ans = currentQuestion.answers[k];
        //identifiant global du slide , contient le nom du plugin et le numero du slide tout slide compris 
        //pour eviter tout conflit entre slide
        ans.index = k;
        ans.ansId = this.getId("ans",k);
        ans.name  = this.getName('ans');
        ans.points = ans.points*1;
        
        //recupe des points pour chaque propositions et s'assure que c'est un numérique
        points = (ans.points) ? ans.points*1 : 0;    
        if(points > 0){
            ans.isOk = true;
            this.scoreMaxiBP += points;
            
         }else{
            ans.isOk = false;
            this.scoreMiniBP -= points;
        } 

        //decodage de chaque proposition (answer)                
        ans.proposition = decodeHTMLEntities(ans.proposition);
    }   

    this.initMinMaxQQ(0);
    this.blob(`===>computeScoresMinMax BP [1] - ${this.getName()}: ${this.scoreMaxiBP}   - ${this.scoreMiniBP}`, true);
    this.blob(`===>computeScoresMinMax QQ [2] - ${this.getName()}: ${this.scoreMaxiQQ} - ${this.scoreMiniQQ}`, true);
    return true;


}
/* **********************************************************
Initialise toutes les données communes à tous les plugins
modeDeCalcul:
0 : mode standart
1 : force les points défini au niveau de la question plutot qu'au niveau des propositions
2 : force le cacul spécifique défini dans le plugin
************************************************************* */
initMinMaxQQ (modeDeCalcul = 0){
    var currentQuestion = this.question;
    
    switch(modeDeCalcul){
    
        //les propositions n'ont pas de points attribué,
        //on recupere le nombre de points attribué à la question
        case 1 :
            this.scoreMaxiBP = currentQuestion.points;
            this.scoreMiniBP = 0;
            break;
            
        //le plugin a un mode de calcul spécifique,
        //on appelle la fonction computeScoresMinMaxByProposition du plugin
        case 2 :
            this.computeScoresMinMaxByProposition();
            break;
            
        //dans tous les autres cas on utilise le calcul fait dans proto_initSlide    
        default: break;
    }
    

    //si c'est le score de la question qui prime
    if(currentQuestion.points > 0){
          this.scoreMaxiQQ = currentQuestion.points;
          this.scoreMiniQQ = 0;
    }else{
          this.scoreMaxiQQ = this.scoreMaxiBP;
          this.scoreMiniQQ = this.scoreMiniBP;
    }     
}

/* **********************************************************

************************************************************* */
getObDivMain (){
    return document.getElementById(this.divMainId);
}

/* *******************************************
* getName : renvoi le nom utilisé pour l'attribut name des objets du dom
* @ return: nom pour l'attribut "name"
* ********** */
getName (suffix1 = null, suffix2 = null){
    var idn = `question-${this.slideNumber}`;    //  -${this.name}
    if (suffix1 != null)  {idn += '-' + suffix1};
    if (suffix2 != null)  {idn += '-' + suffix2};
    return idn;
}

/* *******************************************
* getId : renvoi un id unique composé de getName et d'un index pour l'attribut id des objets du dom
* @index : string
* @ indexElement : integer // permet d'identifier des sous élément d'un ensemble ex: image d'un div principal
* @ return : string pour l'attribut "id"
* ********** */
getId (uid, indexElement = null){
    return this.getName(uid, indexElement);
}

/* *******************************************
*
* ********** */
getScoreInfos (){
    return {'question_min': this.scoreMiniBP, 
            'question_max': this.scoreMaxiBP, 
            'question_points': this.this.getScoreByProposition(0), 
            'quiz_nb_answers' : 0,
            'quiz_nb_questions' : 0,
            'quiz_score': 0,
             };
}
//---------------------------------------------------
 build (){}
  
/* **********************************************************

************************************************************* */
getChildById(id){
    return document.getElementById(id);
    
    //return document.getElementById(this.divMainId).getElementById(id);
}
/* **********************************************************

************************************************************* */
// getQuerySelector (selector){
//     return document.getElementById(this.divMainId).querySelectorAll(selector);
// }
/*********************************************
 * extra a utiliser avec checked par exemple  
 * **** */
getQuerySelector(balise, name = "", typeObj = "", extra="", extra2 = "")
{ 
    var selector = balise;
    
    if (name != '') selector += `[name=${name}]`;    
    if (typeObj != '') selector += `[type=${typeObj}]`;    
    
    if (extra != '') {
        if (extra[0] == "["){
            selector += `${extra}`    
        }else{
            selector += `:${extra}`    
        }
    }
    if (extra2 != '') {
        if (extra2[0] == "["){
            selector += `${extra2}`    
        }else{
            selector += `:${extra2}`    
        }
    }

    return document.getElementById(this.divMainId).querySelectorAll(selector);
}

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
initSlide (){return false;}
//---------------------------------------------------
build_footer (){
    return "<center>construction du footer</center>";
 }
//---------------------------------------------------
submitAnswers(){return false;}

/* *******************************************
* prepareData : prépare les données dns le tableau data pour faciliter les traitements
* @ return: null
* ********** */
prepareData(){}

/* *******************************************
* computeScoresMinMaxByProposition : calcul les scores minimum et maximum dans min et max
* @ return: null
* ********** */
computeScoresMinMaxByProposition(){return 0;}

/* ***************************************
*
* *** */
getImage(){
    var name = this.getName();
    var currentQuestion = this.question;
    if (currentQuestion.image) {
        //return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.height}px"></center>`;
        return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" style="height:${currentQuestion.height}px;max-width:800px"></center>`;
    }else{
        return "";
    }
}

getBackground(){
//alert(quiz.background);
    var currentQuestion = this.question;

    if(currentQuestion.background){
        var background = currentQuestion.background;
    }else if(quiz.background){
        var background = quiz.background;
    }else{
        return false;
    }
    
    var url = `url(${quiz_config.urlQuizImg}/${background})`;
    var obDiv = document.getElementById(this.getId('main')).parentNode;
    obDiv.style.backgroundImage = url;
    
    var obDiv = document.getElementById(this.getId('main')).parentNode.parentNode;
    obDiv.style.backgroundImage = url;
    
}

/* ***************************************
*
* *** */
isImage(){
    return this.question.image;
}
/* ***************************************
*
* *** */
getImageAns(img){
    var name = this.getName();
    var currentQuestion = this.question;
    if (currentQuestion.image) {
        //return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.height}px"></center>`;
        return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" style="height:${currentQuestion.height}px;max-width:800px"></center>`;
    }else{
        return "";
    }
}

/* *******************************************
* * @ return: null
* ********** */
setFocus(){
    //alert("setFocus");
    if(this.focusId != ''){
        try{
        document.getElementById(this.focusId).focus({focusVisible:true});
        //document.getElementById(this.focusId).value='zzzzz';
        console.log('===>setFocus : ' + this.focusId + " = " + document.getElementById(this.focusId).value);
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
* shuffleArr :  Mélanges les propositions de réponse si this.shuffle_answers est vrai
* @ arr : tableau à mélanger
* @ return: le tableau mélangé
* ********** */
 shuffleAnswers(){
    //this.blob (this.name + ' ===> shuffleArrayKeys = ' + this.question.options.shuffleAnswers)
    var arr = null;    
//var ok =false;
    try{
        if(this.question.shuffleAnswers == 1){
            arr = shuffleArray(this.question.answers);
//            ok = true;
        }else{
            arr = this.question.answers;
        }
    }catch{
            arr = this.question.answers;
    }
//if (ok) alert("melange ok");
    return arr;


    
//     try{
//         //option remonter au niveau de la question, a supprimer des que possible
//       if(this.question.options.shuffleAnswers == 1){
//          arr = shuffleArray(this.question.answers);
//       }else{
//           arr = this.question.answers;
//       }
//     }catch{
//       if(this.question.shuffleAnswers == 1){
//           //arr = shuffleArrayKeys(this.question.answers);
//           arr = this.question.answers;
//       }
//     }
//     
//     return arr;
 }


/* *******************************************
* getScore : renvoie le nombre de points obtenu pour la question
* @ return: nombre de poits obtenus
* ********** */
getScore (answerContainer){
var points = 0;

    var currentQuestion = this.question;
    var score = this.getScoreByProposition(answerContainer);
    //alert('currentQuestion.question : ' + score);
    this.blob(`===> getScore : ${this.getName()} : score=${score} - scoreMaxiBP=${this.scoreMaxiBP} - scoreMaxiQQ=${this.scoreMaxiQQ}`);
    
    if(currentQuestion.points > 0 && score == this.scoreMaxiBP){
        return currentQuestion.points
    }else if(currentQuestion.points > 0 && score != 0){ 
        return 0;
    }else{return score;}
}

//---------------------------------------------------
  getScoreByProposition (answerContainer){return 0;}

/* *******************************************
* isInputOk : renvoie vrai si reponsemin = 0 ou si le nombre minimum de réponse requise est atteint
* @ answerContainer: tableau des réponses/propositions
* function obsolette
* ********** */
isInputOk (answerContainer){
    return true;
 }

/* *******************************************
* getAllReponses : renvoie les réponse à la question
* @ flag int: 0 = toutes les réponses / 1 = que les bonnes réponses
* ********** */
getAllReponses (flag=0){return "";}

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

/* ************************************************
Appelé aux modifications du contenu d'un slide
*************************************************** */
onUpdate() {}

/* ************************************************
Appelé au debut de l'affichage d'un nouveau slide
*************************************************** */
onEnter() {
}

/* ************************************************
Appelé à la fin de l'affichage d'un nouveau slide
*************************************************** */
onFinalyse() {
    var currentQuestion = this.question;
    if (currentQuestion.options.nextSlideDelai*1 > 0){
        //document.getElementById('quiz_btn_nextSlide').setAttribute('disabled','disabled');
        document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
    }else{
        document.getElementById('quiz_btn_nextSlide').disabled = '';
    }
}

//---------------------------------------------------
getDisposition(disposition, tableId=null){}  
//---------------------------------------------------
balises2Values(exp, bReplaceSlash = false)
{
    var newExp = exp.replaceAll("{scoreMaxiQQ}", this.scoreMaxiQQ);
        newExp = newExp.replaceAll("{timer}", this.question.timer);
        
    newExp = newExp.replaceAll('{', '').replaceAll('}', '');
    if (bReplaceSlash) {newExp = newExp.replaceAll('/', qbr);}

    return newExp;
    
  } 
sanityse_question(bReplaceSlash = false)
{
    var newExp = this.balises2Values(this.question.question, true); 
    return newExp;
    
  } 
  
/* ************************************
*
* **** */
reloadQuestion(bShuffle = true)
  {
    document.getElementById(this.divMainId).innerHTML = this.getInnerHTML(bShuffle);
    this.initSlide();
    this.setFocus();
  } 
  
/* ************************************
*
* **** */
showGoodAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    this.reloadQuestion(false);
  } 
  
/* ************************************
*
* **** */
showBadAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    this.reloadQuestion(true);
  } 
//---------------------------------------------------
toString()
  {
    return this.name + " | " + this.question.question + " | " + this.question.pluginName;
  } 

//---------------------------------------------------
blob(message, bForcer=false)
  {//return true;
    if(!this.boolDog && !bForcer) return;
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


 /*******************************************************************
  *                     _alphaSimple
  * *****************************************************************/
class alphaSimple extends quizPrototype{
name = 'alphaSimple';
typeInput = 'alpha';  
idDivReponse = '';
/* ***************************************
*
* *** */
build (){
    this.boolDog = false;
    var name = this.getName();
    this.idDivReponse = this.getName() + '-letterSelected';
       
    const htmlArr = [];
    
    
    
    htmlArr.push(`<div id="${name}-alpha" style="text-align:left;margin-left:00px;">`);
    htmlArr.push(this.getInnerHTML());
    htmlArr.push(`</div>`);
    
        return htmlArr.join("\n");

 }

/* ***************************************
*
* *** */
getInnerHTML(){
var currentQuestion = this.question;
var name = this.getName();

    var tpl = `<div class='alphaSimple_global'><center>${this.get_htmlWords()}<br><div name='${name}' id='${this.idDivReponse}' class='alphaSimple_letter_selected'>?</div><br>${this.get_htmlLetters()}</center></div>`;
    if(currentQuestion.image){
        tpl = this.get_img() + tpl;
    }
    
    return tpl;
}
/* ***************************************
*
* *** */
get_htmlWords(){
var html = '<table class="alphaSimple_words"><tr>';
    
    for(var k in this.data.tWords){
        html += `<td>${this.data.tWords[k]}</td>`; 
    
    }
 
    html += '</tr></table><br>';
    return html;
}

/* ***************************************
*
* *** */
get_htmlLetters(){
var html = '<div class="alphaSimple_letters">';
var sep = '|';
//alert('|' + this.idDivReponse + '|');

var letters = this.question.options.propositions.replaceAll('/',sep).replaceAll(',',sep).replaceAll('-',sep).replaceAll('_',sep);
var tLetters = letters.split(sep)
//alert(letters);    
    for(var k = 0; k < tLetters.length; k++){
        if (tLetters[k] == ''){
            html += '<br>'; 
        }else{
            var onclick = `document.getElementById('${this.idDivReponse}').innerHTML='${tLetters[k]}';`;
            //alert('|' + onclick + '|');
            html += `<a onclick="${onclick}">${tLetters[k]}</a>`; 
        }
    
    }
 
    html += '</div>';
    return html;
}
/* ***************************************
*
* *** */
get_htmlLetters_old(){
var html = '<div class="alphaSimple_letters">';
var sep = '|';
//alert('|' + this.idDivReponse + '|');

var letters = this.question.options.propositions.replaceAll('/',sep).replaceAll(',',sep).replaceAll('-',sep).replaceAll('_',sep);
//alert(letters);    
    for(var k =0; k<letters.length; k++){
        if (letters[k] == sep){
            html += '<br>'; 
        }else{
            var onclick = `document.getElementById('${this.idDivReponse}').innerHTML='${letters[k]}';`;
            //alert('|' + onclick + '|');
            html += `<a onclick="${onclick}">${letters[k]}</a>`; 
        }
    
    }
 
    html += '</div>';
    return html;
}

/* ***************************************
*
* *** */
get_img(){
    var name = this.getName();
    var currentQuestion = this.question;
    return `<center><img src="${quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></center>`;
}

/* **********************************************************

************************************************************* */
 prepareData(){
    var currentQuestion = this.question;

    this.data.tWords = getExpInAccolades(this.question.question);;    
    
    var tItems = new Object;
    
    for(var k in currentQuestion.answers){
        
        var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);
        //alert (key);
        var key = "ans-" + k.padStart(3, '0');
        var tWP = {'key': key,
                   'word': currentQuestion.answers[k].proposition, 
                   'points' : currentQuestion.answers[k].points*1};
        tItems[key] = tWP;
// alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);

    }

//  var keys = Object.keys(tItems);    
//  alert("prepareData : " + keys.join(' - '));

    this.data.items = tItems;
    
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (currentQuestion.answers[i].points > 0)
                this.scoreMaxiBP += currentQuestion.answers[i].points*1;
          if (currentQuestion.answers[i].points < 0)
                this.scoreMiniBP += currentQuestion.answers[i].points*1;
      }

     return true;
 }

//---------------------------------------------------
getScoreByProposition ( answerContainer){
var points = 0;
var bolOk = 1;


    var  currentQuestion = this.question;
    var tReponses = document.getElementById(this.idDivReponse).innerHTML.split("-");
    for(var k in currentQuestion.answers){
        var rep = currentQuestion.answers[k];
        if(rep.points > 0) {
            for(var i in tReponses){
                if(rep.proposition == tReponses[i]){
//alert(rep.proposition + " = " + rep.points + " ===> " + points);
                    points += rep.points*1
                }
            }
        }
    }

    
    this.points = points;    
    return this.points;
  }
//---------------------------------------------------

  isInputOk (answerContainer){
//     var obs = getObjectsByName(this.getName(), "input", this.typeInput, "checked");
//     return (obs.length > 0) ? true : false ;
    return true;
 }

//---------------------------------------------------
getAllReponses (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0 || flag == 0) {
            //tReponses.push ({'reponse':rep.proposition, 'points':rep.points});    
            tReponses.push ([[rep.proposition], [rep.points]]);    
        }
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>", true, 1);


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
 reloadQuestion() {
    var name = this.getName();
    var obContent = document.getElementById(`${name}-alpha`);

    obContent.innerHTML = this.getInnerHTML();
    return true;
}

/* ************************************
*
* **** */
  showGoodAnswers()
  {
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var rep = currentQuestion.answers[i];
        if(rep.points > 0) {
  
            tReponses.push(rep.proposition);    
        }
    }
    var reponses = tReponses.join("-");
    document.getElementById(this.idDivReponse).innerHTML = reponses;
    return true;
  
  } 

/* ************************************
*
* **** */
  showBadAnswers()
  {
    document.getElementById(this.idDivReponse).innerHTML="$-£-?";
    return true;
  } 
 

} // ----- fin de la classe ------

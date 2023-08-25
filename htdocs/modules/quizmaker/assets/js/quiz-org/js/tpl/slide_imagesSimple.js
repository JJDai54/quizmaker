
 /*******************************************************************
  *                     _imagesSimple
  * *****************************************************************/
class imagesSimple extends quizPrototype{
name = 'imagesSimple';
  
/* ***************************************
*
* *** */
build (){
    var currentQuestion = this.question;
    var name = this.getName();
    
    
    const answers = [];
    answers.push(`<div id="${name}">`);
    answers.push(this.getInnerHTML());
    answers.push(`</div>`);
    
    
//    this.focusId = name + "-" + "0";
    //alert (this.focusId);

    return answers.join("\n");

 }

/* ************************************
*
* **** */
 reloadQuestion() {
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML();
    return true;
}

/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    
    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
    var feuVert = `<img src="${quiz_config.urlCommonImg}/feu-vert.png" title="fv" alt="" >`;        
    var feuRouge = `<img src="${quiz_config.urlCommonImg}/feu-rouge.png" title="fr" alt="" >`;        
       
    var tpl = `<style>.imagesSimple img{margin:2px;height:${currentQuestion.options.imgHeight1}px;}</style>
               <div class='imagesSimple' style='border: none;text-align:left;'>
               <div>{sequence}</div>
               <hr><div id="${this.data.idSelection}" name="${this.data.idSelection}" >${feuVert}</div>
               <hr><center>${quiz_messages.message03}</center><br><div id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</div>
               </div>`;


    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var tImgSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
    for(var k in this.data.sequence){
        var ans =  this.data.sequence[k];
        //alert('inputs = ' + ans.inputs);
        if (ans.points*1 > 0){
            //var src = `${quiz_config.urlCommonImg}/substitut/${ans.image}`;
        }else{
            var src = `${quiz_config.urlQuizImg}/${ans.proposition}`;

        }
        img = `<img id="${ans.id}" src="${src}" title="${ans.proposition}" alt="zzz" >`;        
        tHtmlSequence.push(img);
    }
    //--------------------------------------------------------------
    var evTpl = "onclick=moveToNewParent('{idFrom}','{idTo}');";
   // var sug = this.shuffleArray(this.data.suggestion);
    
    //var tHtmlSuggestion = [];
          
    tHtmlSuggestion.push(feuRouge);
    var sug = duplicateArray(this.data.suggestion);
    shuffleArrayFY(sug);
      for(var k in sug){
          var ans =  sug[k];
          var idFrom = ans.id; //this.getId('suggestion',k);
          var ev = evTpl.replace('{idFrom}', idFrom);
          var ev = ev.replace('{idTo}',this.data.idSelection);
          //alert(ev);
          img = `<img id="${idFrom}" src="${quiz_config.urlQuizImg}/${ans.proposition}" ${ev} title="${ans.proposition}" alt="xxx" style="cursor:pointer;">`;
          tHtmlSuggestion.push(img);
      }


    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n")).replace('{suggestion}', tHtmlSuggestion.join("\n"));
    return tpl;
}
//---------------------------------------------------

 prepareData(){
    
    var currentQuestion = this.question;
    var sequence = [];
    var suggestion = [];
    var substitut = [];
    
    var i=-1;
    var arrIdToFind = [];
    
    for(var k in currentQuestion.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        currentQuestion.answers[k].id = this.getId(k);
        if(currentQuestion.answers[k].points > 0){
         arrIdToFind.push(k);
        }else{
          var inputs = currentQuestion.answers[k].inputs;
          if(inputs == 0 ){
              sequence.push(currentQuestion.answers[k]);
          }else{
              suggestion.push(currentQuestion.answers[k]); 
          }
        }
            
    }   
    
    var j = currentQuestion.answers.length;
    for(var k=0; k < arrIdToFind.length; k++){
        var newItem = JSON.parse(JSON.stringify(currentQuestion.answers[arrIdToFind[k]]));
        newItem.id = this.getId(k+j);
//         newItem.org = arrIdToFind[k];
//         newItem.idOrg = 99999;//this.getId(arrIdToFind[k]);
        suggestion.push(newItem);
//            alert('newItem.idOrg 3 : ' + newItem.idOrg);
    }
     //var newItem = currentQuestion.answers[i];
     

//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        
    this.data.sequence = sequence;        
    this.data.suggestion = suggestion;   
    this.data.arrIdToFind = arrIdToFind;     

    this.data.idSelection = this.getId('selection');
    this.data.idSuggestion = this.getId('suggestion');
        
        
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var bolOk = true;
    var currentQuestion = this.question;

      for(var k in this.data.suggestion){
          var ans =  this.data.suggestion[k];
          var idParent = document.getElementById(ans.id).parentNode.id;
          if(idParent == this.data.idSelection){
            points += ans.points*1;
            if(ans.points*1 == 0) bolOk = false;
          }
      }


    return (bolOk) ? points : 0;
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var score = {min:0, max:0};


     var nbItemTofind = 0;
     var arrMinus = [];
      for(var k in currentQuestion.answers){
          var points = currentQuestion.answers[k].points;  
          if (points > 0){
            this.scoreMaxiBP += parseInt(points)*1;
            nbItemTofind++;
          } 
          if (points < 0) arrMinus.push(points);
      }
      arrMinus.sort().reverse();
      if (nbItemTofind > arrMinus.length) nbItemTofind = arrMinus.length;
      //alert ("nbItemTofind = " + nbItemTofind + " -  arrMinus: " + arrMinus.length);
      for(var k = 0; k < nbItemTofind; k++)
          this.scoreMiniBP += parseInt(arrMinus[k])*1;

     return true;
}

/* ******************************************

********************************************* */
  isInputOk (myQuestions, answerContainer,chrono){
    
     var currentQuestion = this.question;
    if(currentQuestion.options.minReponses == 0) return true;
    //-------------------------------------------
    //- todo : a revoir
    var rep = 0;
    
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
        if (ans.points*1 > 0){
          var obImg = document.getElementById(ans.id);
          if (obImg.getAttribute('src') != `${quiz_config.urlCommonImg}/substitut/${ans.image}`)        
                rep++;
//alert('isInputOk : ' + rep + "\n" + obImg.getAttribute('src') + "\n" + "../images/substitut/" + ans.image);
        }
    }
    return (rep > currentQuestion.options.minReponses) ;
 }

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];

     
    tReponses.push (['<hr>', '<hr>']);
            
    for(var k in this.data.suggestion){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.data.suggestion[k];
          var img = `<img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">`; 
          tReponses.push ([img, ans.points]);
        if (ans.points*1 <= 0){
        }

    }   


    return formatArray0(tReponses, "=>");
}


//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 
/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    
    var sug = duplicateArray(this.data.suggestion);
    shuffleArrayFY(sug);
      for(var k in sug){
          var ans =  sug[k];
          if(ans.points > 0)
          {moveToNewParent(ans.id, this.data.idSelection , this.data.idSuggestion);}   
          else
          {moveToNewParent(ans.id, this.data.idSuggestion , this.data.idSelection)};   
      }
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    
    var sug = duplicateArray(this.data.suggestion);
    shuffleArrayFY(sug);
      for(var k in sug){
          var ans =  sug[k];
          if(rnd(2,0) == 1){
            moveToNewParent(ans.id, this.data.idSuggestion , this.data.idSelection);   
          }else{
            moveToNewParent(ans.id, this.data.idSelection, this.data.idSuggestion );   
          }
      }
     return true;
  } 

} // ----- fin de la classe ------

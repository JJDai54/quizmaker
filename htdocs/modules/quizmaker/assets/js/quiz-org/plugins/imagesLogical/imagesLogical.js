/*******************************************************************
*                     imagesLogical
* *****************************************************************/
function getPlugin_imagesLogical(question, slideNumber){
    return new imagesLogical(question, slideNumber);
}

 /*******************************************************************
  *                     imagesLogical
  * *****************************************************************/
class imagesLogical extends Plugin_Prototype{
name = 'imagesLogical';
  
/* ***************************************
*
* *** */
build (){
    this.boolDog = false;
    return this.getInnerHTML() ;
 }


/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;

    //var imgHeight = 64;   
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var tImgSequence = [];
    var img = '';

    var imgHeight1  = currentQuestion.options.imgHeight1 + "px";
    var imgHeight2   = currentQuestion.options.imgHeight2 + "px";
    var urlCommonImg = quiz_config.urlCommonImg; 
    var urlQuizImg   = quiz_config.urlQuizImg;
    //var urlMain   = quiz.urlMain + '/plugins/' + this.name;
    var urlPlugin   = currentQuestion.urlPlugin;

   
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    var tempImgIdTofind=[];
    
    for(var k in this.data.sequence){
        var ans =  this.data.sequence[k];
        //alert('inputs = ' + ans.inputs);
        if (ans.points*1 > 0){
            var src = `${urlQuizImg}/${ans.image1}`;
            tempImgIdTofind.push(ans.ansId);
        }else{
            var src = `${urlQuizImg}/${ans.proposition}`;
        }
        img = `<img id="${ans.ansId}" src="${src}" title="${ans.proposition}" alt="zzz" height="${imgHeight1}">`;        
        tHtmlSequence.push(img);
    }
    //--------------------------------------------------------------
    var onclickTpl = "onclick=setImgFromImg_event('{idFrom}','{idTo}');";
   // var sug = this.shuffleArray(this.data.suggestion);
    
    //var tHtmlSuggestion = [];
    for (var j = 0; j < this.data.toFind.length; j++){
        var seqToFind = currentQuestion.answers[this.data.toFind[j]];

          tHtmlSuggestion.push('<hr>');
          var idFrom = seqToFind.ansId + "-bis"; //this.getId('suggestion',k);
          var idTo = seqToFind.ansId; //this.getId('suggestion',k);
          var onclick = onclickTpl.replace('{idFrom}', idFrom).replace('{idTo}', tempImgIdTofind[j]);
          tHtmlSuggestion.push(`<img id='${idFrom}' src="${urlQuizImg}/${seqToFind.image1}" title="" alt="xxx" height="${imgHeight2}" ${onclick} isselectable>`);
          tHtmlSuggestion.push(`<img src="${urlPlugin}/img/2points-green.png" title="" alt="xxx" height="${imgHeight2}">`);
          
        var sug = duplicateArray(this.data.suggestion);
        shuffleArrayFY(sug);
          for(var k in sug){
              var ans =  sug[k];
              var idFrom = ans.ansId; //this.getId('suggestion',k);
              var onclick = onclickTpl.replace('{idFrom}', idFrom);
              var onclick = onclick.replace('{idTo}', this.getId(this.data.toFind[j]) );
              //alert(onclick);
              img = `<img id="${idFrom}" src="${urlQuizImg}/${ans.proposition}" ${onclick} title="${ans.proposition}" alt="xxx" height="${imgHeight2}" isselectable>`;
              tHtmlSuggestion.push(img);
          }
    }

    //---------------------------------------------------------------------
    var tpl = "<div>{sequence}</div><div>{suggestion}</div>";
    return tpl.replace('{sequence}', tHtmlSequence.join("\n")).replace('{suggestion}', tHtmlSuggestion.join("\n"));
    
}
//---------------------------------------------------

 prepareData(){
    
    var currentQuestion = this.question;
    var sequence = [];
    var suggestion = [];
    var substitut = [];
    
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    
    var i=-1;
    var arrToFind = [];
    for(var k in currentQuestion.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
    //alert(currentQuestion.answers[k].ansId);
        currentQuestion.answers[k].ansId = this.getId(k);
        if(currentQuestion.answers[k].points > 0) arrToFind.push(k);
            
        var inputs = currentQuestion.answers[k].inputs;
        if(inputs == 0){
            sequence.push(currentQuestion.answers[k]);
        }else{
            suggestion.push(currentQuestion.answers[k]); 
        }
    }   
    
    var j = currentQuestion.answers.length;
    for(var k=0; k < arrToFind.length; k++){
        var newItem = JSON.parse(JSON.stringify(currentQuestion.answers[arrToFind[k]]));
        newItem.ansId = this.getId(k+j);
        suggestion.push(newItem);
    }
        
//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrToFind.length} - ` + arrToFind.join("|"));        
    this.data.sequence = sequence;        
    this.data.suggestion = suggestion;   
    this.data.toFind = arrToFind;   
    
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;

    var currentQuestion = this.question;
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
        if (ans.points*1 > 0){
          var obImg = document.getElementById(ans.ansId);
          //alert(obImg.getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${ans.proposition}`);
          if (obImg.getAttribute('src') == `${quiz_config.urlQuizImg}/${ans.proposition}`)        
                points += ans.points*1;
        }      
                    
    }
    //return ((currentQuestion.points > 0) ? currentQuestion.points : points);
    return points;
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

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];
     

    for(var k in this.data.sequence){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.data.sequence[k];
        var img = `<img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">`; 
        tReponses.push ([img, ans.points]);

    }          

     
    tReponses.push (['<hr>', '<hr>']);
            
    for(var k in this.data.suggestion){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.data.suggestion[k];
        if (ans.points<=0){
          var img = `<img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight2}px">`; 
          tReponses.push ([img, ans.points]);
        }

    }   


    return formatArray0(tReponses, "=>");
}


/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    var currentQuestion = this.question;
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
//        alert(`propo = ${ans.proposition} - points = ${ans.points}` );
          if (ans.points*1 > 0){
            var obImg = document.getElementById(ans.ansId);
//            alert(obImg.getAttribute('src'));
            obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${ans.proposition}`);
          } 
      }



     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var currentQuestion = this.question;
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
//        alert(`propo = ${ans.proposition} - points = ${ans.points}` );
          if (ans.points*1 > 0){
            var idFrom = getRandom(this.data.suggestion.length -1);
            var obImg = document.getElementById(ans.ansId);
//            alert(obImg.getAttribute('src'));
            obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${this.data.suggestion[idFrom].proposition}` );
          } 
      }
     return true;
  } 

} // ----- fin de la classe ------

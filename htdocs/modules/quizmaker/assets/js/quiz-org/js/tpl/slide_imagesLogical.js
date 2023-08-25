
 /*******************************************************************
  *                     _imagesLogical
  * *****************************************************************/
class imagesLogical extends quizPrototype{
name = 'imagesLogical';
  
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
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
    var tpl = "<div style='border: none;text-align:left;'>"
            + "<div class='imagesLogical'>{sequence}</div><div class='imagesLogical'>{suggestion}</div></div>";
//     var tpl = "<style>.imagesLogical{border: none;text-align:left;}</style>"
//             + "<div class='imagesLogical'>{sequence}</div><div class='imagesLogical'>{suggestion}</div>";
    //var imgHeight = 64;   
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var currentQuestion = this.question;
    var tImgSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
    for(var k in this.data.sequence){
        var ans =  this.data.sequence[k];
        //alert('inputs = ' + ans.inputs);
        if (ans.points*1 > 0){
            var src = `${quiz_config.urlQuizImg}/${ans.image}`;
        }else{
            var src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        }
        img = `<img id="${ans.id}" src="${src}" title="${ans.proposition}" alt="zzz" height="${currentQuestion.options.imgHeight1}px">`;        
        tHtmlSequence.push(img);
    }
    //--------------------------------------------------------------
    var evTpl = "onclick=setImgFromImg_event('{idFrom}','{idTo}');";
   // var sug = this.shuffleArray(this.data.suggestion);
    
    //var tHtmlSuggestion = [];
    for (var j = 0; j < this.data.toFind.length; j++){
        var seqToFind = currentQuestion.answers[this.data.toFind[j]];

          tHtmlSuggestion.push('<hr>');
          var idFrom = seqToFind.id + "-bis"; //this.getId('suggestion',k);
          var idTo = seqToFind.id; //this.getId('suggestion',k);
          var ev = evTpl.replace('{idFrom}', idFrom).replace('{idTo}', idTo);
          tHtmlSuggestion.push(`<img src="${quiz_config.urlQuizImg}/${seqToFind.image}" title="" alt="xxx" height="${currentQuestion.options.imgHeight2}px">`);
          tHtmlSuggestion.push(`<img src="${this.data.urlCommonImg}/2points-gris.png" title="" alt="xxx" height="${currentQuestion.options.imgHeight2}px">`);
          
    var sug = duplicateArray(this.data.suggestion);
    shuffleArrayFY(sug);
      for(var k in sug){
          var ans =  sug[k];
          var idFrom = ans.id; //this.getId('suggestion',k);
          var ev = evTpl.replace('{idFrom}', idFrom);
          var ev = ev.replace('{idTo}', this.getId(this.data.toFind[j]) );
          //alert(ev);
          img = `<img id="${idFrom}" src="${quiz_config.urlQuizImg}/${ans.proposition}" ${ev} title="${ans.proposition}" alt="xxx" height="${currentQuestion.options.imgHeight2}px" style="cursor:pointer;">`;
          tHtmlSuggestion.push(img);
      }
    }

    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n")).replace('{suggestion}', tHtmlSuggestion.join("\n"));
    return tpl;
}
//---------------------------------------------------

 prepareData(){
    
    var sequence = [];
    var suggestion = [];
    var substitut = [];
    
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    
    var currentQuestion = this.question;
    var i=-1;
    var arrToFind = [];
    for(var k in currentQuestion.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        currentQuestion.answers[k].id = this.getId(k);
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
        newItem.id = this.getId(k+j);
//         newItem.org = arrToFind[k];
//         newItem.idOrg = 99999;//this.getId(arrToFind[k]);
        suggestion.push(newItem);
//            alert('newItem.idOrg 3 : ' + newItem.idOrg);
    }
     //var newItem = currentQuestion.answers[i];
     


     
    //alert("winner = " + i + " / " + currentQuestion.answers[i].proposition) + "/" + newItem.proposition;
     
     //this.data.idWinner = this.getId(k); 
        
        
//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrToFind.length} - ` + arrToFind.join("|"));        
    this.data.sequence = sequence;        
    this.data.suggestion = suggestion;   
    this.data.toFind = arrToFind;   
    
/*
    
    this.data.urlCommonImg = ${quiz_config.urlCommonImg}/substitut;
*/
    
    this.data.urlCommonImg = quiz_config.urlCommonImg;

    //this.data.substitut = substitut;        
        
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
          var obImg = document.getElementById(ans.id);
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

/* ******************************************

********************************************* */
  isInputOk (myQuestions, answerContainer,chrono){
    
     var currentQuestion = this.question;
    if(currentQuestion.options.minReponses == 0) return true;
    //-------------------------------------------
    var rep = 0;
    
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
        if (ans.points*1 > 0){
          var obImg = document.getElementById(ans.id);
          if (obImg.getAttribute('src') != `${quiz_config.urlQuizImg}/${ans.image}`)        
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
    var currentQuestion = this.question;
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < this.data.sequence.length; k++){
        var ans = this.data.sequence[k];
//        alert(`propo = ${ans.proposition} - points = ${ans.points}` );
          if (ans.points*1 > 0){
            var obImg = document.getElementById(ans.id);
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
            var idFrom = rnd(this.data.suggestion.length -1);
            var obImg = document.getElementById(ans.id);
//            alert(obImg.getAttribute('src'));
            obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${this.data.suggestion[idFrom].proposition}` );
          } 
      }
     return true;
  } 

} // ----- fin de la classe ------

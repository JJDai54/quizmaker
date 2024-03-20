
 /*******************************************************************
  *                     _imagesDaDLogical
  * *****************************************************************/
class imagesDaDLogical extends quizPrototype{
name = 'imagesDaDLogical';
  
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
    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
    var tpl = "<div style='border: none;text-align:left;'>"
            + `<div class='imagesDaDLogical'>{sequence}</div><hr>${quiz_messages.message02}<hr><div class='myimg0'>{suggestion}</div></div>`;
//     var tpl = "<style>.imagesLogical{border: none;text-align:left;}</style>"
//             + "<div class='imagesLogical'>{sequence}</div><div class='imagesLogical'>{suggestion}</div>";
   
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var currentQuestion = this.question;
    var tImgSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
var eventImgToStyle=`
style="height:${currentQuestion.options.imgHeight1}px;"
`;
var eventImgFromStyle=`
style="height:${currentQuestion.options.imgHeight2}px;"
`;
var eventImgToEvent=`
onDragStart="imagesDaDLogical_dad_start(event);"
onDragOver="return imagesDaDLogical_dad_over(event);" 
onDrop="return imagesDaDLogical_dad_drop(event,0);"
onDragLeave="imagesDaDLogical_dad_leave(event);"`;
    
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;cursor:pointer;"    
onDragStart="dad_start(event);"
`;
var caption;
    
tHtmlSequence.push('<table width="100%" style="font-size:0.8em;" class="myimg0"><tr>');    
    for(var k in this.data.sequence){
        var ans =  this.data.sequence[k];
        //alert('inputs = ' + ans.inputs);
        if (ans.points*1 > 0){
            var src = `${quiz_config.urlQuizImg}/${ans.image}`;
            caption = (ans.caption) ? '<br>' + ans.caption : ''; 
            img = `<td style='text-align:center;'><img id="${ans.id}"  class='myimg1' src="${src}" title="${ans.proposition}" alt="" ${eventImgToStyle} ${eventImgToEvent}>${caption}</td>`;        

        }else{
            var src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
            caption = (ans.caption) ? '<br>' + ans.caption : ''; 
            img = `<td style='text-align:center;'><img id="${ans.id}"  class='myimg1' src="${src}" title="${ans.proposition}" alt="" ${eventImgToStyle}>${caption}</td>`;        

        }
        tHtmlSequence.push(img);
    }
tHtmlSequence.push('</tr></table>');    
    //--------------------------------------------------------------
    
    
      var sug = duplicateArray(this.data.suggestion);
      shuffleArrayFY(sug);
      for(var k in sug){
          var ans =  sug[k];
          var idFrom = ans.id; //this.getId('suggestion',k);
          //var ev = evTpl.replace('{idFrom}', idFrom);
          //var ev = ev.replace('{idTo}', this.getId(this.data.toFind[j]) );
          //alert(ev);
          img = `<img id="${idFrom}"  class='myimg0 myimg1' src="${quiz_config.urlQuizImg}/${ans.proposition}" title="${ans.proposition}"  ${eventImgFromStyle} alt="xxx"  ${eventImgFrom}>`;
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
    
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var j = currentQuestion.answers.length;
    
    var i=-1;
    var arrToFind = [];
    for(var k in currentQuestion.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        currentQuestion.answers[k].id = this.getId(k,);
        if(currentQuestion.answers[k].points > 0){
            arrToFind.push(k);
            sequence.push(currentQuestion.answers[k]);
            
        var newItem = JSON.parse(JSON.stringify(currentQuestion.answers[k]));
        newItem.id = this.getId(k+j);
        suggestion.push(newItem);
            
        }else{
            suggestion.push(currentQuestion.answers[k]); 
        } 
            
    }   
    
    for(var k=0; k < arrToFind.length; k++){
//         newItem.org = arrToFind[k];
//         newItem.idOrg = 99999;//this.getId(arrToFind[k]);
//            alert('newItem.idOrg 3 : ' + newItem.idOrg);
    }
     //var newItem = currentQuestion.answers[i];
    
    //alert("winner = " + i + " / " + currentQuestion.answers[i].proposition) + "/" + newItem.proposition;
     
     //this.data.idWinner = this.getId(k); 
        
        
//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrToFind.length} - ` + arrToFind.join("|"));        
    this.data.sequence = sequence;        
    this.data.suggestion = suggestion;   
    this.data.toFind = arrToFind;   
    
    
    this.data.urlCommonImg = quiz_config.urlCommonImg;

        
        
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
        tReponses.push ([img, ans.caption, ans.points]);

    }          

     
    tReponses.push (['<hr>', '<hr>']);
            
    for(var k in this.data.suggestion){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.data.suggestion[k];
        if (ans.points<=0){
          var img = `<img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">`; 
          tReponses.push ([img, ans.caption, ans.points]);
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

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function imagesDaDLogical_dad_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
}
function imagesDaDLogical_dad_over(e){
    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDLogical_myimg1');
    e.currentTarget.classList.add('imagesDaDLogical_myimg2');
    return false;
}
function imagesDaDLogical_dad_leave(e){
   e.currentTarget.classList.remove('imagesDaDLogical_myimg2');
   e.currentTarget.classList.add('imagesDaDLogical_myimg1');
}
function imagesDaDLogical_dad_drop (e, mode=0){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDLogical_myimg2');
    e.currentTarget.classList.add('imagesDaDLogical_myimg1');
    
    
    var obSource = document.getElementById(idFrom);
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
        replaceImg(obSource,obDest);
    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}



 /*******************************************************************
  *                     _imagesDaDMatchItems
  * *****************************************************************/
class imagesDaDMatchItems extends quizPrototype{
name = 'imagesDaDMatchItems';
  
/* *************************************
*
* ******** */
build (){

    this.boolDog = true;
    return this.getInnerHTML();
 }


/* ************************************
*
* **** */
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }

/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    if(currentQuestion.options.disposition == "disposition-00"){
        return this.getInnerHTML_1(bShuffle);
    }else{
        return this.getInnerHTML_2(bShuffle);
    }
}
/* ************************************
*
* **** */
getInnerHTML_1(bShuffle = true){
    var currentQuestion = this.question;
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var img = '';
    var caption = "";
    var src = "";
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
var eventImgToStyle   =`style="height:${currentQuestion.options.imgHeight1}px;"`;
var eventImgFromStyle =`style="height:${currentQuestion.options.imgHeight2}px;"`;

var eventImgToEvent=`
onDragStart="imagesDaDMatchItems_start(event);"
onDragOver="return imagesDaDMatchItems_over(event);" 
onDrop="return imagesDaDMatchItems_drop(event,0);"
onDragLeave="imagesDaDMatchItems_leave(event);"`;
    
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;"    
onDragStart="dad_start(event);"`;
    
   
    //on place unioquement les silhouette des prposition pour points>0
    if(bShuffle){
        var tShuffleImage   = shuffleArray(currentQuestion.answers);
        var tShuffleCaption = shuffleArray(currentQuestion.answers);
    }else{
        var tShuffleImage   = currentQuestion.answers;
        var tShuffleCaption = currentQuestion.answers;
    }
    var j = 0;
    
    for(var k in tShuffleImage){
        var ansImg =  tShuffleImage[k];
        var ansCap =  tShuffleCaption[k];
        
           var idSequence = this.getId(j++,"sequence");
           ansImg.idSequence = idSequence;
        src = `${quiz_config.urlQuizImg}/${ansImg.proposition}`;
        caption = (ansCap.caption) ? '<br>' +  ansCap.caption : ansCap.proposition; 
        img = `<div><img id="${idSequence}" etat="1"  class='imagesDaDMatchItems_myimg1' src="${src}" title="" alt="" ${eventImgToStyle} ${eventImgToEvent}>${caption}</div>`;
        tHtmlSequence.push(img);
        
    }
    //---------------------------------------------------------------------
    //force le tpl a "disposition-00" parce que appelé pour show_goodAnswer dans tous les cas
    var tpl = this.getDisposition(currentQuestion.options.disposition, currentQuestion.options.directive)
            .replace("{message}", quiz_messages.message02)
            .replace('{sequence}', tHtmlSequence.join("\n"))
            .replace('{suggestion}', "");
    return tpl;
}
/* ************************************
*
* **** */
getInnerHTML_2(bShuffle = true){
    var currentQuestion = this.question;
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var img = '';
    var caption = "";
    var src = "";
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
var eventImgToStyle   =`style="height:${currentQuestion.options.imgHeight1}px;"`;
var eventImgFromStyle =`style="height:${currentQuestion.options.imgHeight2}px;"`;

var eventImgToEvent=`
onDragStart="imagesDaDMatchItems_start(event);"
onDragOver="return imagesDaDMatchItems_over(event);" 
onDrop="return imagesDaDMatchItems_drop(event,0);"
onDragLeave="imagesDaDMatchItems_leave(event);"`;
    
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;"    
onDragStart="dad_start(event);"`;
    
   
    //on place unioquement les silhouette des prposition pour points>0
    var tShuffle = shuffleArray(currentQuestion.answers);
    var j = 0;
    
    for(var k in tShuffle){
        var ans =  tShuffle[k];
        console.log("ans.idSequence = " + ans.idSequence);
        if(ans.isOk) {
            src = `${quiz_config.urlQuizImg}/${ans.image}`;
           var idSequence = this.getId(j++,"sequence");
           ans.idSequence = idSequence;
            caption = (ans.caption) ? '<br>' + ans.caption : ''; 
            img = `<div><img id="${idSequence}" etat="1"  class='imagesDaDMatchItems_myimg1' src="${src}" title="" alt="" ${eventImgToStyle} ${eventImgToEvent}>${caption}</div>`;        
            tHtmlSequence.push(img);
        }
    }
    
    //on remelange et on place les images a replacer au bon endroit
    var tShuffle = shuffleArray(currentQuestion.answers);
    var j = 0;
        
    for(var k in tShuffle){
        var ans =  tShuffle[k];
        console.log("ans.idSequence = " + ans.idSequence);
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        img = `<img id="${ans.id}" etat="0" class='imagesDaDMatchItems_myimg1' src="${quiz_config.urlQuizImg}/${ans.proposition}" title=""  ${eventImgFromStyle} alt="xxx"  ${eventImgFrom}>`;
        tHtmlSuggestion.push(img);
    }
    //---------------------------------------------------------------------
    var tpl = this.getDisposition(currentQuestion.options.disposition, currentQuestion.options.directive)
            .replace("{message}", quiz_messages.message02)
            .replace('{sequence}', tHtmlSequence.join("\n"))
            .replace('{suggestion}', tHtmlSuggestion.join("\n"));
    return tpl;
}
//---------------------------------------------------

 prepareData(){
    
    var currentQuestion = this.question;
    this.data.masks = 0;

    for(var k in currentQuestion.answers){
        currentQuestion.answers[k].id = this.getId(k,"ans");
        currentQuestion.answers[k].idSequence = "";
        if(currentQuestion.answers[k].points > 0){
            currentQuestion.answers[k].isOk = true;
            this.data.masks += 1;
            
         }else{
            currentQuestion.answers[k].isOk = false;
        } 
    }   
    this.data.urlCommonImg = quiz_config.urlCommonImg;
        
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;

    var currentQuestion = this.question;
      for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        var idSequence = ans.idSequence;
        if (ans.points*1 > 0){
          var obSequence = document.getElementById(ans.idSequence);
          if (obSequence.getAttribute('src') == `${quiz_config.urlQuizImg}/${ans.proposition}`)        
                points += ans.points*1;
        }      
                    
    }
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
        var ans = this.data.sequence[k];
        var img = `<img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">`; 
        tReponses.push ([img, ans.caption, ans.points]);
    }          
     
    tReponses.push (['<hr>', '<hr>']);
            
    for(var k in this.data.suggestion){
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
      document.getElementById(this.divMainId).innerHTML = this.getInnerHTML_1(false);
      
//     var currentQuestion = this.question;
//     var j = 0;
// 
//     this.reloadQuestion(false);
     return true;
  } 
 showGoodAnswers2()
  {
    var currentQuestion = this.question;
    var j = 0;

      for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        var idSequence = this.getId(k,"sequence");

          if (ans.points*1 > 0){
            var obImg = document.getElementById(ans.idSequence);
            obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${ans.proposition}`);
            document.getElementById(ans.id).setAttribute('src', `${quiz_config.urlQuizImg}/${ans.image}`);
          }else{
             document.getElementById(ans.id).setAttribute('src', `${quiz_config.urlQuizImg}/${ans.proposition}`);
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
    this.reloadQuestion();
    
    var tShuffle = [];
    for (var h = 0; h < currentQuestion.answers.length; h++){tShuffle.push(h);}
    tShuffle = shuffleArray(tShuffle);

    var tAnsAlea = shuffleArray(currentQuestion.answers);

    for(var k = 0; k < this.data.masks; k++){
        var idSequence = this.getId(k,"sequence");    
        var obSequence = document.getElementById(idSequence);
    
        var idAns = tAnsAlea[k].id;
        var obAns = document.getElementById(idAns);
        
        replaceImg(obAns, obSequence);
    }
    
     return true;
  } 

/* ***************************************
*
* *** */
getDisposition(disposition, directiveExists = false){
    var directive = "";

    switch(disposition){
        default:
        case 'disposition-00':
            if (directiveExists) directive= "{directive}<hr>";
            var tpl = `${directive}{sequence}`;
            break;
        case 'disposition-10':
            if (directiveExists) directive= "<tr><td><hr>{directive}<hr></td></tr>";
            var tpl = `<table width:"100%"'>
                        <tr><td>{sequence}</td></tr>
                        ${directive}
                        <tr><td>{suggestion}</td></tr>
                    </table>`;
            break;
        case 'disposition-11':
            if (directiveExists) directive= "<tr><td><hr>{directive}<hr></td></tr>";
            var tpl = `<table width:"100%"'>
                        ${directive}
                        <tr><td>{suggestion}</td></tr>
                        <tr><td>{sequence}</td></tr>
                    </table>`;
            break;
        case 'disposition-20':
            if (directiveExists) directive= "{directive}<hr>";
            var tpl = `${directive}<table><tr>
                        <td style='width:50%;vertical-align: top;'>{suggestion}</td>
                        <td style='width:50%;vertical-align: top;'>{sequence}</td>
                    </tr></table>`;
            break;
    }
    return tpl;
}

} // ----- fin de la classe ------

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function imagesDaDMatchItems_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.currentTarget.id);
    console.log("imagesDaDMatchItems_start : " + e.currentTarget.id);
}
function imagesDaDMatchItems_over(e){
   // if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDMatchItems_myimg1');
    e.currentTarget.classList.add('imagesDaDMatchItems_myimg2');
console.log("===>imagesDaDMatchItems_over : " + e.currentTarget.id);
    return false;
}
function imagesDaDMatchItems_leave(e){
   e.currentTarget.classList.remove('imagesDaDMatchItems_myimg2');
   e.currentTarget.classList.add('imagesDaDMatchItems_myimg1');
}
function imagesDaDMatchItems_drop (e, mode=0){
//alert('dad_drop')
    var idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDMatchItems_myimg2');
    e.currentTarget.classList.add('imagesDaDMatchItems_myimg1');
    
    
    var obSource = document.getElementById(idFrom);
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    
    if(obSource.getAttribute("etat")*1 == 0 && obDest.getAttribute("etat")*1 != 2){
        replaceImg(obSource,obDest, (obSource.getAttribute("etat")*1 == 0));  
        obDest.setAttribute("etat", 2);
    }else{
        replaceImg(obSource,obDest, false);  
        if(obSource.getAttribute("etat")*1 != 0){
          var etat = obSource.getAttribute("etat");
          obSource.setAttribute("etat", obDest.getAttribute("etat"));
          obDest.setAttribute("etat", etat);
        }
    }
    
  
    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


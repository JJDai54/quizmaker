
 /*******************************************************************
  *                     _imagesDaDLogical
  * *****************************************************************/
class imagesDaDLogical extends quizPrototype{
name = 'imagesDaDLogical';
  
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
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;

    var tpl = this.getDisposition(currentQuestion.options.disposition).replace("{message}", quiz_messages.message02);
    

   
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var currentQuestion = this.question;
    var tImgSequence = [];
    var img = '';
    var caption = "";
    var src = "";
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
var eventImgToStyle=`
style="height:${currentQuestion.options.imgHeight1}px;"`;
var eventImgFromStyle=`style="height:${currentQuestion.options.imgHeight2}px;"`;

var eventImgToEvent=`
onDragStart="imagesDaDLogical_start(event);"
onDragOver="return imagesDaDLogical_over(event);" 
onDrop="return imagesDaDLogical_drop(event,0);"
onDragLeave="imagesDaDLogical_leave(event);"`;
    
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;"    
 onDragStart="dad_start(event);"`;
    
    //tHtmlSequence.push('<table><tr>');    
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
            //img = `<td style='text-align:center;'><img id="${idSequence}"  class='imagesDaDLogical_myimg1' src="${src}" title="${ans.proposition}" alt="" ${eventImgToStyle} ${eventImgToEvent}>${caption}</td>`;        
            img = `<div><img id="${idSequence}"  class='imagesDaDLogical_myimg1' src="${src}" title="${ans.proposition}" alt="" ${eventImgToStyle} ${eventImgToEvent}>${caption}</div>`;        
            tHtmlSequence.push(img);
        }else{
        }
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        img = `<img id="${ans.id}"  class='imagesDaDLogical_myimg1' src="${quiz_config.urlQuizImg}/${ans.proposition}" title="${ans.proposition}"  ${eventImgFromStyle} alt="xxx"  ${eventImgFrom}>`;
        tHtmlSuggestion.push(img);
    }
    //tHtmlSequence.push('</tr></table>');    
    
    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n")).replace('{suggestion}', tHtmlSuggestion.join("\n"));
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
getDisposition(disposition){
    switch(disposition){
        default:
        case 'disposition-10':
          var tpl = `<table width:"100%"'>
                        <tr><td>{sequence}</td></tr>
                        <tr><td><hr>{message}<hr></td></tr>
                        <tr><td>{suggestion}</td></tr>
                    </table>`;
            break;
        case 'disposition-11':
          var tpl = `<table width:"100%"'>
                        <tr><td>{sequence}</td></tr>
                        <tr><td><hr>{message}<hr></td></tr>
                        <tr><td>{suggestion}</td></tr>
                    </table>`;
            break;
        case 'disposition-20':
          var tpl = `{message}<hr><table><tr>
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
function imagesDaDLogical_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.currentTarget.id);
    console.log("imagesDaDLogical_start : " + e.currentTarget.id);
}
function imagesDaDLogical_over(e){
   // if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDLogical_myimg1');
    e.currentTarget.classList.add('imagesDaDLogical_myimg2');
console.log("===>imagesDaDLogical_over : " + e.currentTarget.id);
    return false;
}
function imagesDaDLogical_leave(e){
   e.currentTarget.classList.remove('imagesDaDLogical_myimg2');
   e.currentTarget.classList.add('imagesDaDLogical_myimg1');
}
function imagesDaDLogical_drop (e, mode=0){
//alert('dad_drop')
    var idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDLogical_myimg2');
    e.currentTarget.classList.add('imagesDaDLogical_myimg1');
    
    
    var obSource = document.getElementById(idFrom);
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    replaceImg(obSource,obDest);
    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


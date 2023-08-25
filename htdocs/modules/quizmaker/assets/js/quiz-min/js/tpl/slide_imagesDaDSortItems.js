
 /*******************************************************************
  *                     _imagesDaDSortItems
  * *****************************************************************/

class imagesDaDSortItems extends quizPrototype{
      
name = 'imagesDaDSortItems';

/* ***************************************
*
* *** */
build (){
    var currentQuestion = this.question;
    var name = this.getName();
    this.boolDog = true;    
    
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
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    
    
    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
      
    var tpl =`<div id="${this.getId('img')}" class='imagesDaDSortItems' >\n{sequence}\n</div>`;

var eventImgToEvent=`
onDragStart="imagesDaDSortItems_dad_start(event);"
onDragOver="return imagesDaDSortItems_dad_over(event);" 
onDragLeave="imagesDaDSortItems_dad_leave(event);"
onDrop="return imagesSortItems_dad_drop(event,${this.question.options.moveMode});"
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;

    var tHtmlSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
    if (bShuffle){
        var newSequence = shuffleArray(this.question.answers);
    }else{
        var newSequence = duplicateArray(this.question.answers);
    }
    for(var k in newSequence){
        var ans =  newSequence[k];
        var src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        switch (posCaption){
            case 'T': captionTop = ans.caption.replace('/','<br>') + '<br>' ; break;
            case 'B': captionBottom = '<br>' + ans.caption.replace('/','<br>'); break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
//         img = `<img id="${this.getId(k)}" class='myimg1' src="${src}" title="" alt="zzz" ${eventImg}>`;     
//         tHtmlSequence.push(img);
        tHtmlSequence.push(`
            <div id="${ans.id}-div" class='imagesDaDSortItems_myimg1' draggable='true' ${eventImgToEvent}>${captionTop}
            <img id="${ans.id}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}

//---------------------------------------------------
 prepareData(){
    
    //var sequence = [];
    
    var currentQuestion = this.question;
    var i=-1;
    var arrIdToFind = [];
    
    for(var k in currentQuestion.answers){
        currentQuestion.answers[k].id = this.getId(k);
        if( currentQuestion.answers[k].points <= 0) currentQuestion.answers[k].points =1;
        //sequence.push(currentQuestion.answers[k]);

            
    }   
    

//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        

    this.data.idSelection = this.getId('selection');
    this.data.idSuggestion = this.getId('suggestion');
        
        
}
//---------------------------------------------------
computeScoresMinMaxByProposition(){
 var currentQuestion = this.question;
 this.scoreMaxiBP = 0;  
 this.scoreMiniBP = 0;  
   
    for(var k in currentQuestion.answers){
        this.scoreMaxiBP++;
    }
    
     return true;
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var bolOk = true;

    var currentQuestion = this.question;

    //var obDivImg = document.getElementById(this.getId('img'));
    //var obListImg = obDivImg.getElementsByTagName('img');
    var obListImg = document.querySelectorAll(`#${this.getId('img img')}`);
    
    for(var k=0; k < obListImg.length; k++){
        //alert(obListImg[k].getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`);
        if(obListImg[k].getAttribute('src') != `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`){
            bolOk=false;
        }
    }

this.blob((bolOk) ? 'oui' : 'non');
    return  ((bolOk) ? this.scoreMaxiBP : 0);
}

/* ******************************************

********************************************* */
  isInputOk (myQuestions, answerContainer,chrono){
    
    return 0;
 }

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
     var currentQuestion = this.question;
     var tPropos = this.data.reponses;
     var tPoints = this.data.points;
     var tpl1;
     var tReponses = [];
     
    
    //tReponses.push (['<hr>', '<hr>']);
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
            
    for(var k in this.question.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = this.question.answers[k];
        var caption = ans.caption.replace('/','<br>');
          var img = `<div id="${ans.id}-div" ${divStyle}>
            <img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">
            <br>${caption}</div>`;
          tReponses.push (img);
    }   


    return '<div>' + tReponses.join("\n") + '</div>';
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
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML(false);
    return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML(true);
    return true;

  } 

} // ----- fin de la classe ------     

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function imagesDaDSortItems_dad_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
}
function imagesDaDSortItems_dad_over(e){
    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDSortItems_myimg1');
    e.currentTarget.classList.add('imagesDaDSortItems_myimg2');
    return false;
}
function imagesDaDSortItems_dad_leave(e){
   e.currentTarget.classList.remove('imagesDaDSortItems_myimg2');
   e.currentTarget.classList.add('imagesDaDSortItems_myimg1');
}
function imagesSortItems_dad_drop(e, mode=0){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDSortItems_myimg2');
    e.currentTarget.classList.add('imagesDaDSortItems_myimg1');
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    switch(mode){
        case 1 : 
            shiftDivImg(obSource,obDest);
            break;
        case 0 : 
        default : 
            replaceDivImg(obSource,obDest);
            break;
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}

/* ****
 *
 ******************************* */
function replaceDivImg(obSource,obDest){
//alert(`replaceDivImg : obSource = ${obSource.id}\nobDest = ${obDest.id}`)
  var obNext = obSource.nextSibling; 
  var obPrevious = obSource.previousSibling; 
  //if(!obNext) obNext = obDest.previousSibling; 
  
  obSource.parentNode.insertBefore(obSource, obDest);
 if (obNext){
    obSource.parentNode.insertBefore(obDest, obNext);
 }else if(obPrevious){
    obSource.parentNode.insertAfter(obDest);
 }else{
    obSource.parentNode.appendChild(obDest);
 }
 return false; 
}

/* ***
 *
 ******************************** */
function shiftDivImg(obSource,obDest){
//alert(`shiftDivImg : obSource = ${obSource.id}\nobDest = ${obDest.id}`)
 obSource.parentNode.insertBefore(obSource, obDest);
}





/* *********************************** */
// function replaceImg(obSource,obDest){
// 
//     var srcTmp = obDest.getAttribute("src");
//     obDest.setAttribute("src", obSource.getAttribute("src"));
//     obSource.setAttribute("src", srcTmp);
// 
//     
// }


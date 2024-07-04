
 /*******************************************************************
  *                     _imagesDaDSortItems
  * *****************************************************************/

class imagesDaDSortItems extends quizPrototype{
      
name = 'imagesDaDSortItems';

/* ***************************************
*
* *** */
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }

/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    if (this.question.options.moveMode == 2){
        //deplacement des images par survol d'ne autre image
        return this.getInnerHTML_ins(bShuffle);
    }else{
        //deplacement des images par inserion avec carret
        return this.getInnerHTML_subs(bShuffle);
    }

}

/* ************************************
*
* **** */
getInnerHTML_subs(bShuffle = true){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    
    
    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
var divDirective = (currentQuestion.options.directive) ? `<span class="imagesDaDSortItems_directive">${currentQuestion.options.directive}</span>` : '';
//var tpl =`{questImage}\n${divDirective}<center><div id="${this.getId('img')}" class='imagesDaDSortItems_directive' >\n{sequence}\n</div></center><br>`;
var tpl =`{questImage}\n${divDirective}<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent=`
onDragStart="imagesDaDSortItems_start(event);"
onDragOver="return imagesDaDSortItems_over(event);" 
onDragLeave="imagesDaDSortItems_leave(event);"
onDrop="return imagesDaDSortItems_drop(event,${this.question.options.moveMode});"
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
            case 'T': captionTop    = ans.caption + qbr ; break;
            case 'B': captionBottom = qbr + ans.caption ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
//         img = `<img id="${this.getId(k)}" class='myimg1' src="${src}" title="" alt="zzz" ${eventImg}>`;     
//         tHtmlSequence.push(img);
        tHtmlSequence.push(`
            <div id="${ans.ansId}-div" class='imagesDaDSortItems_myimg1' draggable='true' ${eventImgToEvent}>${captionTop}
            <img id="${ans.ansId}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{questImage}', this.getImage())
           .replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}

/* ************************************
*
* **** */
getInnerHTML_ins(bShuffle = true){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    

    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
var divDirective = (currentQuestion.options.directive) ? `<span class="imagesDaDSortItems_directive">${currentQuestion.options.directive}</span><br>` : '';
     
    var tpl =`{questImage}\n${divDirective}<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent1=`
onDragStart="imagesDaDSortItems_ins_start(event);"
onDragOver="return imagesDaDSortItems_ins_over(event,false);" 
onDragLeave="imagesDaDSortItems_ins_leave(event,false);"
onDrop="return imagesDaDSortItems_ins_drop(event,false);"
 
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;

var eventImgToEvent2=`
onDragOver="return imagesDaDSortItems_ins_over(event,true);" 
onDragLeave="imagesDaDSortItems_ins_leave(event,true);"
onDrop="return imagesDaDSortItems_ins_drop(event,true);"`;


//var tplEncar = `<div id="{id}-encart" encart style="width:25px;height:${currentQuestion.options.imgHeight1}px"></div>`;
//var tplEncar = "";
var  tplEncar = `<div id="{id}-encart" encart  class='imagesDaDSortItems_myimg1' style="height:${currentQuestion.options.imgHeight1}px" ${eventImgToEvent2}></div>`;


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
            case 'T': captionTop    = ans.caption + qbr ; break;
            case 'B': captionBottom = qbr + ans.caption ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
//         img = `<img id="${this.getId(k)}" class='myimg1' src="${src}" title="" alt="zzz" ${eventImg}>`;     
//         tHtmlSequence.push(img);

            if(k == 0){
              tHtmlSequence.push(tplEncar.replace("{id}",this.getId(999)));
            }
            tHtmlSequence.push(`
              <div id="${ans.ansId}-div" class='imagesDaDSortItems_myimg1' draggable='true' ${eventImgToEvent1}>${captionTop}
              <img id="${ans.ansId}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
              ${captionBottom}</div>`);
              tHtmlSequence.push(tplEncar.replace("{id}", ans.ansId));
            
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{questImage}', this.getImage()) 
           .replace('{sequence}', tHtmlSequence.join("\n"));
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
        currentQuestion.answers[k].caption = currentQuestion.answers[k].caption.replace('/'+'/', qbr).replace('/', qbr);

        //sequence.push(currentQuestion.answers[k]);

            
    }   
    

//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        

    this.data.idSelection = this.getId('selection');
    this.data.idSuggestion = this.getId('suggestion');
        
        
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var bolOk = true;
//alert("imagesDaDSortItems.getScoreByProposition");
    var currentQuestion = this.question;

    //var obDivImg = document.getElementById(this.getId('img'));
    //var obListImg = obDivImg.getElementsByTagName('img');
    var obListImg = document.querySelectorAll(`#${this.getId('img img')}`);
    
    for(var k=0; k < obListImg.length; k++){
    console.log("-------imagesDaDSortItems-getScoreByProposition-----------");
    console.log(obListImg[k].getAttribute('src'));
    console.log(`${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`);

        //alert(obListImg[k].getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`);
        if(obListImg[k].getAttribute('src') != `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`){
            bolOk=false;
        }
    }

this.blob((bolOk) ? 'oui' : 'non');
    return  ((bolOk) ? this.scoreMaxiBP : 0);
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
          var img = `<div id="${ans.ansId}-div" ${divStyle}>
            <img src="${quiz_config.urlQuizImg}/${ans.proposition}" title="" alt="" height="${currentQuestion.options.imgHeight1}px">
            <br>${ans.caption}</div>`;
          tReponses.push (img);
    }   


    return '<div>' + tReponses.join("\n") + '</div>';
}


/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    this.getObDivMain().innerHTML = this.getInnerHTML(false);
    return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    this.getObDivMain().innerHTML = this.getInnerHTML(true);
    return true;

  } 

} // ----- fin de la classe ------     

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par substitution des images                   */
/* **************************************************************** */
function imagesDaDSortItems_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
}
function imagesDaDSortItems_over(e){
    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDSortItems_myimg1');
    e.currentTarget.classList.add('imagesDaDSortItems_myimg2');
    return false;
}
function imagesDaDSortItems_leave(e){
   e.currentTarget.classList.remove('imagesDaDSortItems_myimg2');
   e.currentTarget.classList.add('imagesDaDSortItems_myimg1');
}
function imagesDaDSortItems_drop(e, mode=0){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDSortItems_myimg2');
    e.currentTarget.classList.add('imagesDaDSortItems_myimg1');
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    switch(mode){
        case 1 : 
            imagesDaDSortItems_shiftDivImg(obSource,obDest);
            break;
        case 0 : 
        default : 
            imagesDaDSortItems_replaceDivImg(obSource,obDest);
            //alert('move mode : ' + mode);
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


// Note: Cloned copy of element1 will be returned to get a new reference back
function imagesDaDSortItems_replaceDivImg(element1, element2)
{
    var clonedElement1 = element1.cloneNode(true);
    var clonedElement2 = element2.cloneNode(true);

    element2.parentNode.replaceChild(clonedElement1, element2);
    element1.parentNode.replaceChild(clonedElement2, element1);

    return false;
}

/* ***
 *
 ******************************** */
function imagesDaDSortItems_shiftDivImg(obSource,obDest){
//alert(`shiftDivImg : obSource = ${obSource.id}\nobDest = ${obDest.id}`)
 obSource.parentNode.insertBefore(obSource, obDest);
}


////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par insertion des images         */
/* **************************************************************** */
function imagesDaDSortItems_ins_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
    set_param(e.target.parentNode.getAttribute("id"));
}

function imagesDaDSortItems_ins_over(e, isEncart){
//console.log("imagesDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    
    
    if(isEncart){
        var encart = e.currentTarget;
    }else{
        var encart = e.currentTarget.previousElementSibling;
    }
   
    
    var obSource = document.getElementById(get_param(0));
    console.log("------------");
    console.log(obSource.id);
    console.log(obSource.previousElementSibling.id);
    console.log(obSource.nextElementSibling.id);
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
        encart.classList.remove('imagesDaDSortItems_myimg1');
        encart.classList.add('imagesDaDSortItems_myimg2');
    }
    
    
    return false;
}
function imagesDaDSortItems_ins_over2(e, isEncart){
//console.log("imagesDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    if(isEncart){
      e.currentTarget.classList.remove('imagesDaDSortItems_myimg1');
      e.currentTarget.classList.add('imagesDaDSortItems_myimg2');
    }else{
        var encart = e.currentTarget.previousElementSibling;
        encart.classList.remove('imagesDaDSortItems_myimg1');
        encart.classList.add('imagesDaDSortItems_myimg2');
    }
    return false;
}
function imagesDaDSortItems_ins_leave(e, isEncart){
    if(isEncart){
       e.currentTarget.classList.remove('imagesDaDSortItems_myimg2');
       e.currentTarget.classList.add('imagesDaDSortItems_myimg1');
    }else{
        var encart = e.currentTarget.previousElementSibling;
       encart.classList.remove('imagesDaDSortItems_myimg2');
       encart.classList.add('imagesDaDSortItems_myimg1');
    }

}
function imagesDaDSortItems_ins_drop(e, isEncart){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    
    if(isEncart){
        var encart = e.currentTarget;
        var obDest = e.currentTarget.nextElementSibling;
    }else{
        var encart = e.currentTarget.previousElementSibling;
        var obDest = e.currentTarget;
    }
       encart.classList.remove('imagesDaDSortItems_myimg2');
       encart.classList.add('imagesDaDSortItems_myimg1');

    //l'element source est une image il faut prendre le contenair
    var obSource = document.getElementById(idFrom).parentNode;
    //pas d'insertion si c'est l'encart juste avant ou juste après
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
      imagesDaDSortItems_ins_shiftDivImg(obSource,obDest);
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


/* ***
 *
 ******************************** */
function imagesDaDSortItems_ins_shiftDivImg(obSource,obDest){
  var obNext = obSource.nextElementSibling ; 
 
 if(obNext) {
   obSource.parentNode.insertBefore(obSource, obDest);
   obSource.parentNode.insertBefore(obNext, obDest);
 }else{
   obSource.parentNode.append(obSource);
   obSource.parentNode.insertBefore(obNext);
 }

}



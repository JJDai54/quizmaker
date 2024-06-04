
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
        return this.getInnerHTML_ins(bShuffle);
    }else{
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
var tpl =`\n${divDirective}<div id="${this.getId('img')}" class='imagesDaDSortItems_directive' >\n{sequence}\n</div><br>`;

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
            case 'T': captionTop = ans.caption.replace('/','<br>') + '<br>' ; captionBottom='';  break;
            case 'B': captionBottom = '<br>' + ans.caption.replace('/','<br>'); captionTop=''; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
//         img = `<img id="${this.getId(k)}" class='myimg1' src="${src}" title="" alt="zzz" ${eventImg}>`;     
//         tHtmlSequence.push(img);
        tHtmlSequence.push(`
            <div id="${ans.id}-div" class='imagesDaDSortItems_myimg1' draggable='true' ${eventImgToEvent}> ${captionTop}
            <img id="${ans.id}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
           
    }

    //---------------------------------------------------------------------
    tpl=tpl.replace("{questImage}", this.getImage())
           .replace("{sequence}", tHtmlSequence.join("\n"));
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
var  tplEncar = `<div id="{id}-encart" encart  class="imagesDaDSortItems_myimg1" style="height:${currentQuestion.options.imgHeight1}px" ${eventImgToEvent2}></div>`;


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
            case 'T': captionTop = ans.caption.replace('/'+'/', qbr) + qbr; break;
            case 'B': captionBottom = qbr + ans.caption.replace('//', qbr); break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
//         img = `<img id="${this.getId(k)}" class='myimg1' src="${src}" title="" alt="zzz" ${eventImg}>`;     
//         tHtmlSequence.push(img);

            if(k == 0){
              tHtmlSequence.push(tplEncar.replace("{id}",this.getId(999)));
            }
            tHtmlSequence.push(`
              <div id="${ans.id}-div" class="imagesDaDSortItems_myimg1" draggable="true" ${eventImgToEvent1}>${captionTop}
              <img id="${ans.id}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
              ${captionBottom}</div>`);
              tHtmlSequence.push(tplEncar.replace("{id}", ans.id));
            
           
    }

    //---------------------------------------------------------------------
    tpl=tpl.replace("{questImage}", this.getImage()) 
           .replace("{sequence}", tHtmlSequence.join("\n"));
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
        if( currentQuestion.answers[k].points <= 0) {currentQuestion.answers[k].points = 1;}
        //sequence.push(currentQuestion.answers[k]);

            
    }   
//alert(`num propositions : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        

    this.data.idSelection = this.getId("selection");
    this.data.idSuggestion = this.getId("suggestion");
        
        
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
//alert("imagesDaDSortItems.getScoreByProposition");
    var currentQuestion = this.question;

    //var obDivImg = document.getElementById(this.getId('img'));
    //var obListImg = obDivImg.getElementsByTagName('img');
    var obListImg = document.querySelectorAll(`#${this.getId("img img")}`);
    
    for(var k=0; k < obListImg.length; k++){
    console.log("-------imagesDaDSortItems-getScoreByProposition-----------");
    console.log(obListImg[k].getAttribute("src"));
    console.log(`${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`);

        //alert(obListImg[k].getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`);
        if(obListImg[k].getAttribute("src") != `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].proposition}`){
            bolOk=false;
        }
    }

//this.blob((bolOk) ? "oui" : "non");
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
/*       Fonction de Drag And drop par substitution des images      */
/* **************************************************************** */
function imagesDaDSortItems_start(e, isDiv=false){
}
function imagesDaDSortItems_over(e){
}
function imagesDaDSortItems_leave(e){
}
function imagesDaDSortItems_drop(e, mode=0){
}

/* ****
 *
 ******************************* */
function imagesDaDSortItems_replaceDivImg(obSource,obDest){
 return false; 
}

/* ***
 *
 ******************************** */
function imagesDaDSortItems_shiftDivImg(obSource,obDest){
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
    return false;
}
function imagesDaDSortItems_ins_over2(e, isEncart){
    return false;
}
function imagesDaDSortItems_ins_leave(e, isEncart){
}
function imagesDaDSortItems_ins_drop(e, isEncart){
    return false;
}


/* ***
 *
 ******************************** */
function imagesDaDSortItems_ins_shiftDivImg(obSource,obDest){

}



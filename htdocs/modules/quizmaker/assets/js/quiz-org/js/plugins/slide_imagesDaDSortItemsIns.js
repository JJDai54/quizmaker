
 /*******************************************************************
  *                     _imagesDaDSortItems
  * *****************************************************************/

class imagesDaDSortItemsIns extends imagesDaDSortItems{
      
name = 'imagesDaDSortItemsIns';
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
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    
    var moveMode = this.question.options.moveMode;
    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
      
    var tpl =`<div id="${this.getId('img')}" class='imagesDaDSortItems' >\n{sequence}\n</div>`;

var eventImgToEvent1=`
onDragStart="imagesDaDSortItemsIns_start(event);"
onDragOver="return imagesDaDSortItemsIns_over(event,false);" 
onDragLeave="imagesDaDSortItemsIns_leave(event,false);"
onDrop="return imagesDaDSortItemsIns_drop(event,false);"
 
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;

var eventImgToEvent2=`
onDragOver="return imagesDaDSortItemsIns_over(event,true);" 
onDragLeave="imagesDaDSortItemsIns_leave(event,true);"
onDrop="return imagesDaDSortItemsIns_drop(event,true);"
`;

//var tplEncar = `<div id="{id}-encart" encart style="width:25px;height:${currentQuestion.options.imgHeight1}px"></div>`;
//var tplEncar = "";
var  tplEncar = `<div id="{id}-encart" encart  class='imagesDaDSortItemsIns_myimg1' style="height:${currentQuestion.options.imgHeight1}px" ${eventImgToEvent2}></div>`;


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

            if(k == 0){
              tHtmlSequence.push(tplEncar.replace("{id}",this.getId(999)));
            }
            tHtmlSequence.push(`
              <div id="${ans.id}-div" class='imagesDaDSortItems_myimg1' draggable='true' ${eventImgToEvent1}>${captionTop}
              <img id="${ans.id}-img"  src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
              ${captionBottom}</div>`);
              tHtmlSequence.push(tplEncar.replace("{id}", ans.id));
            
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}






} // ----- fin de la classe ------     

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function imagesDaDSortItemsIns_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
    set_param(e.target.parentNode.getAttribute("id"));
}

function imagesDaDSortItemsIns_over(e, isEncart){
//console.log("imagesDaDSortItemsIns_over : " + e.currentTarget.id);
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
        encart.classList.remove('imagesDaDSortItemsIns_myimg1');
        encart.classList.add('imagesDaDSortItemsIns_myimg2');
    }
    
    
    return false;
}
function imagesDaDSortItemsIns_over2(e, isEncart){
//console.log("imagesDaDSortItemsIns_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    if(isEncart){
      e.currentTarget.classList.remove('imagesDaDSortItemsIns_myimg1');
      e.currentTarget.classList.add('imagesDaDSortItemsIns_myimg2');
    }else{
        var encart = e.currentTarget.previousElementSibling;
        encart.classList.remove('imagesDaDSortItemsIns_myimg1');
        encart.classList.add('imagesDaDSortItemsIns_myimg2');
    }
    return false;
}
function imagesDaDSortItemsIns_leave(e, isEncart){
    if(isEncart){
       e.currentTarget.classList.remove('imagesDaDSortItemsIns_myimg2');
       e.currentTarget.classList.add('imagesDaDSortItemsIns_myimg1');
    }else{
        var encart = e.currentTarget.previousElementSibling;
       encart.classList.remove('imagesDaDSortItemsIns_myimg2');
       encart.classList.add('imagesDaDSortItemsIns_myimg1');
    }

}
function imagesDaDSortItemsIns_drop(e, isEncart){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");

    
    if(isEncart){
        var encart = e.currentTarget
        var obDest = e.currentTarget.nextElementSibling;
    }else{
        var encart = e.currentTarget.previousElementSibling;
        var obDest = e.currentTarget;
    }
       encart.classList.remove('imagesDaDSortItemsIns_myimg2');
       encart.classList.add('imagesDaDSortItemsIns_myimg1');

    //l'element source est une image il faut prendre le contenair
    var obSource = document.getElementById(idFrom).parentNode;
    //pas d'insertion si c'est l'encart juste avant ou juste après
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
      imagesDaDSortItemsIns_shiftDivImg(obSource,obDest);
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


/* ***
 *
 ******************************** */
function imagesDaDSortItemsIns_shiftDivImg(obSource,obDest){
  var obNext = obSource.nextElementSibling ; 
 
 if(obNext) {
   obSource.parentNode.insertBefore(obSource, obDest);
   obSource.parentNode.insertBefore(obNext, obDest);
 }else{
   obSource.parentNode.append(obSource);
   obSource.parentNode.insertBefore(obNext);
 }

}






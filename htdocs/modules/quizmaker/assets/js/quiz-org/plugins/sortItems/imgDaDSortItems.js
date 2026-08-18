

 /*******************************************************************
  *                     imgDaDSortItems
  * *****************************************************************/

class imgDaDSortItems extends sortItems{
      
name = 'imgDaDSortItems';

/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var tHtml = [];
    
    //ajout de l'image principal sil elle existe
    var imgMain = this.getImage();
    if(imgMain){
        tHtml.push(`<div>${imgMain}</div>`);
    }
    
    //Ajout des images de référence si elles existent toute
    // si il en manque une ce div ne sera pas affiché
    var imgRef = this.getInnerHTML_img_ref(false);
    if(imgRef){
        tHtml.push(`<div class='imgDaDSortItems'>${imgRef}</div>`);
    }
    
    
    
    
    //Ajout de la directive si elle existe
    if(currentQuestion.options.directive){
        var divDirective = (currentQuestion.options.directive) ? `<span directive="blue">${currentQuestion.options.directive}</span>` : '';
        tHtml.push(`<div class='imgDaDSortItems' directive>${divDirective}</div>`);

    }    
    
    
    //Ajout de la sequence d'image a remettre dans l'ordre
    if (this.question.options.moveMode == 2){
        //deplacement des images par survol d'ne autre image
        var sequence =  this.getInnerHTML_ins(bShuffle);
    }else{
        //deplacement des images par inserion avec carret
        var sequence =  this.getInnerHTML_subs(bShuffle);
    }
    tHtml.push(`<div class='imgDaDSortItems'>${sequence}</div>`);
    
    //-----------------------------------------
    return tHtml.join("\n");

}

/* ************************************
*
* **** */
getInnerHTML_img_ref(bShuffle = false){
    var currentQuestion = this.question;
    var tHtmlmg = [];
    var img = '';
    var captionTop='';
    var captionBottom = '';    
 
    this.data.imagesRefExists = true;
      
    var posCaption = currentQuestion.options.showCaptions;    
    if (currentQuestion.options.variant == '05-imagesdadFixedWidth'){
        var ImgStyle=`style="width:${currentQuestion.options.imgWidth1}px;"`;
    }else{
        var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
    }


    if (bShuffle){
        var newSequence = shuffleArray(this.question.answers);
    }else{
        var newSequence = duplicateArray(this.question.answers);
    }
//alert(currentQuestion.question);
    for(var k in newSequence){
        var ans =  newSequence[k];
        if (ans.image2){
            var src = `${quiz_config.urlQuizImg}/${ans.image2}`;
            switch (posCaption){
                case 'T': captionTop    = replaceDoubleSlash(ans.proposition) + qbr ; break;
                case 'B': captionBottom = qbr + replaceDoubleSlash(ans.proposition) ; break;
                default: break;
            }
            tHtmlmg.push(`
                <div id="${ans.ansId}-div-img2" divRefMode="1">${captionTop}
                <img id="${ans.ansId}-img2"  src="${src}" title="${ans.image2}" ${ImgStyle} alt="" >
                ${captionBottom}</div>`);
        }else{
            this.data.imagesRefExists = false
            break;
        }

    }
  
    if(this.data.imagesRefExists){
        //currentQuestion.options.showCaptions = '';
        return tHtmlmg.join("\n");
    }else{
        return '';
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
if (this.data.imagesRefExists) {posCaption = "none";}
    if (currentQuestion.options.variant == '05-imagesdadFixedWidth'){
        var ImgStyle=`style="width:${currentQuestion.options.imgWidth1}px;"`;
    }else{
        var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
    }

    var tpl =`<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent=`
onDragStart="imgDaDSortItems_subs_start(event);"
onDragOver="return imgDaDSortItems_subs_over(event);" 
onDragLeave="imgDaDSortItems_subs_leave(event);"
onDrop="return imgDaDSortItems_subs_drop(event,${this.question.options.moveMode});"
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
        var src = `${quiz_config.urlQuizImg}/${ans.image1}`;
        switch (posCaption){
            case 'T': captionTop    = replaceDoubleSlash(ans.proposition) + qbr ; break;
            case 'B': captionBottom = qbr + replaceDoubleSlash(ans.proposition) ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);
        tHtmlSequence.push(`
            <div id="${ans.ansId}-div" divSequenceMode="0" draggable='true' ${eventImgToEvent} index=${k}>${captionTop}
            <img id="${ans.ansId}-img"  src="${src}" title="${ans.proposition}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
           
    }
    //--------------------------------------------------------------


    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}

/* ************************************
*
* **** */
getInnerHTML_ins(bShuffle = true){
//alert("getInnerHTML_ins = " + this.question.answers.length);
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var captionTop='';
    var captionBottom = '';    

    //var tpl = `<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td id="${this.data.idSelection}" name="${this.data.idSelection}">{selection}</td></tr><tr><td> id="${this.data.idSuggestion }" name="${this.data.idSuggestion }">{suggestion}</td></tr></table>`;
var posCaption = currentQuestion.options.showCaptions;    
    if (currentQuestion.options.variant == '05-imagesdadFixedWidth'){
        var ImgStyle=`style="width:${currentQuestion.options.imgWidth1}px;"`;
    }else{
        var ImgStyle=`style="height:${currentQuestion.options.imgHeight1}px;"`;
    }

     
    var tpl =`<center><div id="${this.getId('img')}" sequence>\n{sequence}\n</div></center>`;

var eventImgToEvent1=`
onDragStart="imgDaDSortItems_ins_start(event);"
onDragOver="return imgDaDSortItems_ins_over(event,false);" 
onDragLeave="imgDaDSortItems_ins_leave(event,false);"
onDrop="return imgDaDSortItems_ins_drop(event,false);"
 
onclick="testOnClick(event);"
onmouseover="testMouseOver(event);"`;

var eventImgToEvent2=`
onDragOver="return imgDaDSortItems_ins_over(event,true);" 
onDragLeave="imgDaDSortItems_ins_leave(event,true);"
onDrop="return imgDaDSortItems_ins_drop(event,true);"`;


//var tplEncar = `<div id="{id}-encart" encart style="width:25px;height:${currentQuestion.options.imgHeight1}px"></div>`;
//var tplEncar = "";
let  tplEncar = `<div id="{id}-encart" encart="1" style="height:${currentQuestion.options.imgHeight1}px" ${eventImgToEvent2}></div>`;


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
        var src = `${quiz_config.urlQuizImg}/${ans.image1}`;

        switch (posCaption){
            case 'T': captionTop    = replaceDoubleSlash(ans.proposition) + qbr ; break;
            case 'B': captionBottom = qbr + replaceDoubleSlash(ans.proposition) ; break;
            default: break;
        }
        
        //alert('inputs = ' + ans.inputs);

            if(k == 0){
              tHtmlSequence.push(tplEncar.replace("{id}",this.getId(999)));
            }
            tHtmlSequence.push(`
              <div id="${ans.ansId}-div" divSequenceMode="0" draggable='true' ${eventImgToEvent1}>${captionTop}
              <img id="${ans.ansId}-img"  src="${src}" title="${ans.proposition}" ${ImgStyle} alt="" >
              ${captionBottom}</div>`);
              tHtmlSequence.push(tplEncar.replace("{id}", ans.ansId));
            
           
    }
    //--------------------------------------------------------------
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
        currentQuestion.answers[k].caption = currentQuestion.answers[k].caption.replace('/'+'/', qbr).replace('/', qbr);

        //sequence.push(currentQuestion.answers[k]);

            
    }   
    

//alert(`num image1s : - 0=>${sequence.length} - 1=>${suggestion.length} - toFind=>${arrIdToFind.length} - ` + arrIdToFind.join("|"));        

    this.data.idSelection = this.getId('selection');
    this.data.idSuggestion = this.getId('suggestion');
    
    // modifié par "getInnerHTML_img_ref" et mis a false si une image2 n'existe pas
    //permet de placer les titres sous image2 si elles existent sinon sous image1
    this.data.imagesRefExists = true; 
    this.initMinMaxQQ(1);    
        
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var bolOk = true;
//alert("imgDaDSortItems.getScoreByProposition");
    var currentQuestion = this.question;

    //var obDivImg = document.getElementById(this.getId('img'));
    //var obListImg = obDivImg.getElementsByTagName('img');
    var obListImg = document.querySelectorAll(`#${this.getId('img img')}`);
    
    for(var k=0; k < obListImg.length; k++){
    //console.log("-------imgDaDSortItems-getScoreByProposition-----------");
    //console.log(obListImg[k].getAttribute('src'));
    //console.log(`${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`);

        //alert(obListImg[k].getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`);
        if(obListImg[k].getAttribute('src') != `${quiz_config.urlQuizImg}/${currentQuestion.answers[k].image1}`){
            bolOk=false;
        }
    }

this.blob((bolOk) ? 'oui' : 'non');
    return  ((bolOk) ? this.scoreMaxiBP : 0);
}

} // ------------ FIN DE LA CLASSE ------------------------------


////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par substitution des images                   */
/* **************************************************************** */
function imgDaDSortItems_subs_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
}
function imgDaDSortItems_subs_over(e){
    imgDaDSortItems_set_ref(e.currentTarget,2);

    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.setAttribute('divSequenceMode',"1");
    
    return false;
}

function imgDaDSortItems_set_ref(obSrc, reference){
    var posIndex = imgDaDSortItems_getIndex(obSrc, tagName="div");
 
     var t =  obSrc.id.split('-');
    //t[3] = obSrc.getAttribute("index");
    t[3] = posIndex;
    t[4] += "-img2";
    var idRef = t.join('-');
    //console.log("imgDaDSortItems_subs_over : idRef  = "+  idRef);
    var obImgRef = document.getElementById(idRef);
    if(obImgRef){
        obImgRef.setAttribute("divRefMode", reference);
    }
    
    
}

function imgDaDSortItems_getIndex(obToFind, tagName="div"){

    //console.log("_getIndex : ======================================================");
    var obChildren = obToFind.parentNode.getElementsByTagName(tagName);
    for (var h=0; h < obChildren.length; h++){
    //console.log("_getIndex : " + h + "--->" + obChildren[h].id + "<===>" + obToFind.id);
        if (obChildren[h].id == obToFind.id){
            break;
        }
    }
    return h;
}

function imgDaDSortItems_subs_leave(e){
    imgDaDSortItems_set_ref(e.currentTarget,1);
    e.currentTarget.setAttribute('divSequenceMode',"0");

}
function imgDaDSortItems_subs_drop(e, mode=0){

    imgDaDSortItems_set_ref(e.currentTarget,1);
    idFrom = e.dataTransfer.getData("text");

    e.currentTarget.setAttribute('divSequenceMode',"0");
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    switch(mode){
        case 1 : 
            imgDaDSortItems_shiftDivImg(obSource,obDest);
            break;
        case 0 : 
        default : 
            imgDaDSortItems_replaceDivImg(obSource,obDest);
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
function imgDaDSortItems_replaceDivImg(element1, element2)
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
function imgDaDSortItems_shiftDivImg(obSource,obDest){
//alert(`shiftDivImg : obSource = ${obSource.id}\nobDest = ${obDest.id}`)
 obSource.parentNode.insertBefore(obSource, obDest);
}


////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop par insertion des images         */
/* **************************************************************** */
function imgDaDSortItems_ins_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.target.getAttribute("id"));
    set_param(e.target.parentNode.getAttribute("id"));
}
/* ************************************************ */
function imgDaDSortItems_ins_over(e, isEncart){
//console.log("imgDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    
    
    if(isEncart){
        var encart = e.currentTarget;
    }else{
        var encart = e.currentTarget.previousElementSibling;
    }
   
    
    var obSource = document.getElementById(get_param(0));
//     console.log("------------");
//     console.log(obSource.id);
//     console.log(obSource.previousElementSibling.id);
//     console.log(obSource.nextElementSibling.id);
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
        encart.setAttribute("encart","2");
    }
    
    
    return false;
}

/* ************************************************ */
function imgDaDSortItems_ins_leave(e, isEncart){
//console.log("imgDaDSortItems_ins_over : " + e.currentTarget.id);
    //if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    
    
    if(isEncart){
        var encart = e.currentTarget;
    }else{
        var encart = e.currentTarget.previousElementSibling;
    }
   
    
    var obSource = document.getElementById(get_param(0));
//     console.log("------------");
//     console.log(obSource.id);
//     console.log(obSource.previousElementSibling.id);
//     console.log(obSource.nextElementSibling.id);
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
        encart.setAttribute("encart","1");
    }
    
    
    return false;
}

/* ************************************************ */
function imgDaDSortItems_ins_drop(e, isEncart){

    idFrom = e.dataTransfer.getData("text");

    
    if(isEncart){
        var encart = e.currentTarget;
        var obDest = e.currentTarget.nextElementSibling;
    }else{
        var encart = e.currentTarget.previousElementSibling;
        var obDest = e.currentTarget;
    }
    encart.setAttribute("encart","1");
    //l'element source est une image il faut prendre le contenair
    var obSource = document.getElementById(idFrom).parentNode;
    //pas d'insertion si c'est l'encart juste avant ou juste après
    if(encart.id != obSource.previousElementSibling.id
    && encart.id != obSource.nextElementSibling.id){
      imgDaDSortItems_ins_shiftDivImg(obSource,obDest);
    }

    //-----------------------------------------------
    computeAllScoreEvent();
    e.stopPropagation();
    return false;
}


/* ***
 *
 ******************************** */
function imgDaDSortItems_ins_shiftDivImg(obSource,obDest){
  var obNext = obSource.nextElementSibling ; 
 
 if(obNext) {
   obSource.parentNode.insertBefore(obSource, obDest);
   obSource.parentNode.insertBefore(obNext, obDest);
 }else{
   obSource.parentNode.append(obSource);
   obSource.parentNode.insertBefore(obNext);
 }

}

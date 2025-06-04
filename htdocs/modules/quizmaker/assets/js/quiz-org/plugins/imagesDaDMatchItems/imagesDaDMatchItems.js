/*******************************************************************
*                     imagesDaDMatchItems
* *****************************************************************/
function getPlugin_imagesDaDMatchItems(question, slideNumber){
    return new imagesDaDMatchItems(question, slideNumber);
}

 /*******************************************************************
  *                     imagesDaDMatchItems
  * *****************************************************************/
class imagesDaDMatchItems extends Plugin_Prototype{
name = 'imagesDaDMatchItems';
  
/* *************************************
*
* ******** */
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
* cas N° 1 il n'y a qu'un div et on affiche pas les silhouettes
* mais on mélange les titres sous les images
* toutes les propositions doivent avoir un nombre de points >= 1
* si points == 0 même à leur place elles ne compteront pas
* **** */
getInnerHTML_1(bShuffle = true){
    var currentQuestion = this.question;
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var img = '';
    var caption = "";
    var src = "";
 
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
    
   
    //on place uniquement les silhouettes des prpositions pour points > 0
    if(bShuffle){
        var tAns = shuffleArray(currentQuestion.answers);
        var tCap = shuffleArray(currentQuestion.answers);
    }else{
        var tAns   = currentQuestion.answers;
        var tCap = currentQuestion.answers;
    }

    
    for(var k in tAns){
        var ansImg =  tAns[k];
        var ansCap =  tCap[k];
        
        src = `${quiz_config.urlQuizImg}/${ansImg.proposition}`;
        //au cas ou caption n'a pas ete renseigné, affiche le nom de l'image
        caption = (ansCap.caption) ? qbr +  ansCap.caption : ansCap.proposition; 
        img = `<div class='imagesDaDMatchItems_div_img'>`
            + `<img id="${ansImg.ansId}" etat="1"  class='imagesDaDMatchItems_img imagesDaDMatchItems_myimg1' src="${src}" goodImg='${ansCap.proposition}' title="" alt="" ${eventImgToStyle} ${eventImgToEvent}>`
            + `<span id=${ansCap.idCaption}>${caption}</span></div>`;
        
        tHtmlSequence.push(img);
        
    }
    //---------------------------------------------------------------------
    var tpl = this.getDisposition(currentQuestion.options.disposition, currentQuestion.options.directive)
            .replace("{message}", quiz_messages.message02)
            .replace("{directive}", currentQuestion.options.directive)
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

var eventImgToStyle   =`style="height:${currentQuestion.options.imgHeight1}px;"`;
var eventImgFromStyle =`style="height:${currentQuestion.options.imgHeight2}px;"`;

var moveAllow = (currentQuestion.options.moveAllow == 1) ? 'onDragStart="imagesDaDMatchItems_start(event);"' : '';
var eventImgToEvent = `${moveAllow}
onDragOver="return imagesDaDMatchItems_over(event,${currentQuestion.options.moveAllow});" 
onDrop="return imagesDaDMatchItems_drop(event,0,${currentQuestion.options.moveAllow});"
onDragLeave="imagesDaDMatchItems_leave(event);"`;
    
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;"    
onDragStart="dad_start(event);"`;
    
   
    //on place uniquement les silhouettes des propositions  pour isOk
    var tShuffle = shuffleArray(currentQuestion.answers);
    
    // si bShuffle on melange tout sinon on présente la bonne solution
    if(bShuffle){
        for(var k in tShuffle){
            var ans =  tShuffle[k];
            //console.log("ans.idImg = " + ans.idImg);
            if(ans.isOk) {
                src = `${quiz_config.urlQuizImg}/${ans.image1}`;
                caption = (ans.caption) ? qbr + ans.caption : ''; 
                img = `<div class='imagesDaDMatchItems_div_img'>`
                    + `<img id="${ans.ansId}" etat="1"  class='imagesDaDMatchItems_img imagesDaDMatchItems_myimg1' src="${src}" goodImg=${ans.proposition} title="" alt="" ${eventImgToStyle} ${eventImgToEvent}>`
                    + `<span>${caption}</span></div>`;        
                tHtmlSequence.push(img);
            }
        }
    }else{
        for(var k in tShuffle){
            var ans =  tShuffle[k];
            //console.log("ans.idImg = " + ans.idImg);
            if(ans.image1) {
                src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
                caption = (ans.caption) ? qbr + ans.caption : ''; 
                img = `<div class='imagesDaDMatchItems_div_img'>`
                    + `<img id="${ans.ansId}" etat="2"  class='imagesDaDMatchItems_img imagesDaDMatchItems_myimg1' src="${src}" goodImg=${ans.proposition} title="" alt="" ${eventImgToStyle} ${eventImgToEvent}>`
                    + `<span>${caption}</span></div>`;        
                tHtmlSequence.push(img);
            }
        }
    }
    
    
    //on remelange et on place les images a remplacer au bon endroit
    var tShuffle = shuffleArray(currentQuestion.answers);
    var j = 0;
        
    // si bShuffle on melange tout sinon on présenteque les images sans silhouette donc non affectée
    //ces images doivent avoir points=0
    for(var k in tShuffle){
        var ans =  tShuffle[k];
        if(!ans.image1 || bShuffle){
            var idBasket = this.getId('basket',k);
            // a propos de etat : 
            // etat = 0 : valeur quand tout est mélangé. affecte l'image dorine à la destination et supprime l'image d'origine
            // etat = 1 : etat de départ des images de destination
            // etat = 2 : indique que la destination a déjà été affecté une fois au moins, et fait un swap des images
            var etat = (bShuffle) ? 0 : 1;
            //console.log("ans.idImg = " + ans.idImg);
            src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
            img = `<img id="${idBasket}" etat="${etat}" class='imagesDaDMatchItems_img imagesDaDMatchItems_myimg1' src="${quiz_config.urlQuizImg}/${ans.proposition}" title=""  ${eventImgFromStyle} alt=""  ${eventImgFrom}>`;
            tHtmlSuggestion.push(img);
        }
    }
    //---------------------------------------------------------------------
//currentQuestion.options.directive = currentQuestion.options.disposition ; //pour test
    var tpl = this.getDisposition(currentQuestion.options.disposition, currentQuestion.options.directive)
            .replace("{message}", quiz_messages.message02)
            .replace("{directive}", currentQuestion.options.directive)
            .replace('{sequence}', tHtmlSequence.join("\n"))
            .replace('{suggestion}', tHtmlSuggestion.join("\n"));
    return tpl;
}
//---------------------------------------------------

 prepareData(){
    
    var currentQuestion = this.question;
    this.data.masks = 0;
//alert('prepareData : ' + this.slideNumber + ' - ' + currentQuestion.question + ' - '+ currentQuestion.options.disposition);
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        ans.idCaption = this.getId("img",k);
        if(ans.isOk){
            this.data.masks += 1;
         } 
    }   

}
/* **********************************
Mise à jour des couleurs de fond différentes pour chaque question
************************************* */
onEnter() {
   var currentQuestion = this.question;
    
    //setStyleAttribute(this.getId('source'), 'background-color', currentQuestion.options.bgSource);
    //setStyleAttribute(this.getId('silouhette'), 'background-color', currentQuestion.options.bgSilhouette);
}

//---------------------------------------------------
onFinalyse() {
    super.onFinalyse();
    var currentQuestion = this.question;

    if(currentQuestion.options.zoom == 2) {
        zoom_plus(event, this.slideNumber);  
    }  
console.log('===> onFinalyse : ' + currentQuestion.question);
}       

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;

    var currentQuestion = this.question;
    for(var k = 0; k < currentQuestion.answers.length; k++){
      var ans = currentQuestion.answers[k];
      var obImg = document.getElementById(ans.ansId);
  
      if(obImg){
      this.blob(`getScoreByProposition [${k}]: ${getShortName(obImg.getAttribute('src'))} <===> ${getShortName(obImg.getAttribute('goodImg'))}`);
      
      
      if(getShortName(obImg.getAttribute('src'))  == getShortName(obImg.getAttribute('goodImg'))){
              points += ans.points*1;
      }
      }
      
    }
    return points;
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
getDisposition(disposition, directive){
    var currentQuestion = this.question;
    var posDirective = "";
    var bgSource     = `background-color:${currentQuestion.options.bgSource}`;
    var bgSilhouette = `background-color:${currentQuestion.options.bgSilhouette}`;

    switch(disposition){
        default:
        case 'disposition-00':
            if (directive) posDirective = `${directive}<hr>`;
            var tpl = `<center>${posDirective}</center><br><div id='${this.getId('source')}' class='imagesDaDMatchItems_groups' style='max-width:700px;background:${currentQuestion.options.bgSource};'>{sequence}</div>`;
            break;
        case 'disposition-10':
            if (directive) posDirective = `<tr><td><hr>${directive}<hr></td></tr>`;
            var tpl = `<table width="700px">
                        <tr><td id='${this.getId('silouhette')}' style='${bgSilhouette};' silouhette>{sequence}</td></tr>
                        ${posDirective}
                        <tr><td  id='${this.getId('source')}' style='${bgSource};' source>{suggestion}</td></tr>
                    </table>`;
            break;
        case 'disposition-11':
            if (directive) posDirective= `<tr><td><hr>${directive}<hr></td></tr>`;
            var tpl = `<table  width="700px">
                        ${posDirective}
                        <tr><td  id='${this.getId('source')}' style='${bgSource};' source>{suggestion}</td></tr>
                        <tr><td id='${this.getId('silouhette')}' style='${bgSilhouette};' silouhette>{sequence}</td></tr>
                    </table>`;
            break;
        case 'disposition-20':
            if (directive) posDirective = `${directive}<hr>`;
            var tpl = `${posDirective}<table  width="700px"><tr>
                        <td  id='${this.getId('source')}' style='${bgSource};width:50%;vertical-align: top;' source>{suggestion}</td>
                        <td id='${this.getId('silouhette')}' style='${bgSilhouette};width:50%;vertical-align: top;' silouhette>{sequence}</td>
                    </tr></table>`;
            break;
        case 'disposition-21':
            if (directive) posDirective = `${directive}<hr>`;
            var tpl = `${posDirective}<table  width="700px"><tr>
                        <td id='${this.getId('silouhette')}' style='${bgSilhouette};width:50%;vertical-align: top;' silouhette>{sequence}</td>
                        <td  id='${this.getId('source')}' style='${bgSource};width:50%;vertical-align: top;' source>{suggestion}</td>
                    </tr></table>`;
            break;
    }
    
    //tpl = `<div>${tpl}</div>`;
    //alert(`slideNumber : ${this.slideNumber} - zoom = ${currentQuestion.options.zoom} - disposition = ${disposition}`);
    if(currentQuestion.options.zoom > 0){
        return zoom_getCapsule(tpl, this.slideNumber);
    }else{
        return tpl;
    }

}

} // ----- fin de la classe ------

////////////////////////////////////////////////////////////////////////////
/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function imagesDaDMatchItems_start(e, isDiv=false, moveAllow=1){
if(moveAllow == 0 && e.currentTarget.getAttribute("etat") == 2) return false;
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text", e.currentTarget.id);
    console.log("imagesDaDMatchItems_start : " + e.currentTarget.id);
}
/* *************************************************** */
function imagesDaDMatchItems_over(e, moveAllow=1){
//if(moveAllow == 0 ) return false;
if(moveAllow == 0 && e.currentTarget.getAttribute("etat") == 2) return false;
   // if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;
    e.currentTarget.classList.remove('imagesDaDMatchItems_myimg1');
    e.currentTarget.classList.add('imagesDaDMatchItems_myimg2');
console.log("===>imagesDaDMatchItems_over : " + e.currentTarget.id);
    return false;
}
/* *************************************************** */
function imagesDaDMatchItems_leave(e){
   e.currentTarget.classList.remove('imagesDaDMatchItems_myimg2');
   e.currentTarget.classList.add('imagesDaDMatchItems_myimg1');
}
/* *************************************************** */
function imagesDaDMatchItems_drop (e, mode=0, moveAllow=1){
    var idFrom = e.dataTransfer.getData("text");

    e.currentTarget.classList.remove('imagesDaDMatchItems_myimg2');
    e.currentTarget.classList.add('imagesDaDMatchItems_myimg1');
    
    
    var obSource = document.getElementById(idFrom);
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
if(moveAllow == 0 && obDest.getAttribute("etat") == 2) return false;
    
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


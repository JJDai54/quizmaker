
 /*******************************************************************
  *                     _imagesColor
  * *****************************************************************/
class imagesColor extends quizPrototype{
name = 'imagesColor';

//---------------------------------------------------
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }

/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    if (currentQuestion.options.colorSelectMode == 1){
        return this.getInnerHTML_color_picker();
    }else{
        return this.getInnerHTML_combobox();
    }
}
/* ************************************
*
* **** */
getInnerHTML_combobox(){
    var currentQuestion = this.question;
   
    
    
var TplColors = `<select name='{name}' id='{id}' onChange="imagesColor_onchange(event,'{idImg}','{idSelectColor}');">${this.data.colorsOptions}</select>`;    
var tplDiv = `<div id={divImg}><img src='{src}' name='{name}' id='{id}' alt='' title='' style='height:${currentQuestion.options.imgHeight1}px;background:${currentQuestion.options.colorDefault};' ><br>{selectColor}</div>`;
var tHtmlImgs = [];
var src ='';
var img = '';

    var allAns = this.shuffleAnswers();

     for(var k in allAns){
        var ans = allAns[k];
        
        var selectColor = TplColors.replace('{id}', ans.idColor)
                                   .replace('{name}', ans.idColor)
                                   .replace('{idImg}', ans.ansId)
                                   .replace('{idSelectColor}', ans.idColor);
        
              
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;        
        img = tplDiv.replace('{src}',src)
                    .replace('{name}',ans.name)
                    .replace('{id}',ans.ansId)
                    .replace('{selectColor}', selectColor)
                    .replace('{divImg}', this.getId('divImg',k));
        tHtmlImgs.push(img);
        if(currentQuestion.options.nbImagesByRow != 0 && ((k+1) % currentQuestion.options.nbImagesByRow) == 0 ){
            tHtmlImgs.push(qbr);
        
        }
     }

    
    return tHtmlImgs.join("\n");
}
/* ************************************
*
* **** */
getInnerHTML_color_picker(){
    var currentQuestion = this.question;
    var mouseover = `onclick="imagesColor_showPicker(event,'${this.getId('picker')}',1);"`;    //mouseover/mouseout
   // var mouseout  = '';//`onmouseout="imagesColor_showPicker(event,'${this.getId('picker')}',0);"`;    //mouseover/mouseout
    
    
    
var tplDiv = `<div id={idDivImg}><img src='{src}' name='{name}' id='{id}' colorCode='{colorCode}' alt='' title='' style='height:${currentQuestion.options.imgHeight1}px;background:${currentQuestion.options.colorDefault};' ${mouseover}></div>`;
var tHtmlImgs = [];
var src ='';
var img = '';
            tHtmlImgs.push(this.getHtmlPicker());

    var allAns = this.shuffleAnswers();

     for(var k in allAns){
        var ans = allAns[k];
        
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;        
        img = tplDiv.replace('{src}',src)
                    .replace('{name}',ans.name)
                    .replace('{id}',ans.ansId)
                    .replace('{idDivImg}', this.getId('divImg',k));
        tHtmlImgs.push(img);
        if(currentQuestion.options.nbImagesByRow != 0 && ((k+1) % currentQuestion.options.nbImagesByRow) == 0 ){
            tHtmlImgs.push(qbr);
        
        }
     }

    
    return tHtmlImgs.join("\n");
}
/* *********************************************************
*
* ********************************************************** */
getHtmlPicker(){
    var currentQuestion = this.question;
    var idPicker = this.getId('picker');
    var html = `<div id='${idPicker}' picker='0'  idImg=''>`;
    var onClick= `onclick="imagesColor_selectColor(event,'${idPicker}');"`;
    var divColorSize = 30;
       
    var divColor = `<div id='{idDivColor}' gridColor='{gridColor}' style='background:{color};width:${divColorSize}px;height:${divColorSize}px;' ${onClick}></div>`;
    
      for(var k in currentQuestion.answers){
         var ans = currentQuestion.answers[k];
         html += divColor.replaceAll('{color}' , ans.color)
                         .replace('{gridColor}', ans.color)
                         .replace('{idDivColor}', this.getId('idDivColor', k));
        
            if(currentQuestion.options.nbImagesByRow != 0 && ((k+1) % currentQuestion.options.nbImagesByRow) == 0 ){
            html += qbr;
            }
                 
     }
    
    
    html += `</div>`;
    
    
   
    
    return html;
}
/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;
    var colors = [];
    //var tplColors = `<option value='{color}' style='background-color:{color};color:{color};'>{color}</option>`;
    var tplColors = `<option value='{color}' style='background-color:{color};color:transparent;'>{color}</option>`;
    colors.push(tplColors.replaceAll('{color}', currentQuestion.options.colorDefault))
    
     for(var k in currentQuestion.answers){
         var ans = currentQuestion.answers[k];
         ans.idColor = this.getId('color', k);
         ans.caption = ans.caption.replace(' ',qbr).replace('/',qbr);
         
         if(ans.points <= 0) {ans.points = 1;}
         colors.push(tplColors.replaceAll('{color}', ans.color))
     }
       
     
     this.data.colorsOptions = colors.join("\n");

}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var ans;
var obSelectColor = null;
var obImg = null;

    var currentQuestion = this.question;

     for(var k in currentQuestion.answers){
         ans = currentQuestion.answers[k];
         //obSelectColor = document.getElementById(ans.idColor);
         obImg =  document.getElementById(ans.ansId);
         //if (colorConvertor.toHexa(obImg.style.background) == ans.color){
         if (rgbToHex(obImg.style.background) == ans.color){
            points += ans.points*1;
         }

//         console.log(`getScoreByProposition : ans.points = ${ans.points} - ans.color = ${ans.color} - obImg.background = ${rgbToHex(obImg.style.background)} - points = ${points}`);
     }
    return points;
} 

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){

var currentQuestion = this.question;
    
var tpl = `<img src='{src}' name='{name}' id='{id}' alt='' title='' style='height:${currentQuestion.options.imgHeight1}px;background:{color};'>`;
var tHtmlImgs = [];
var src ='';
var img = '';

    var allAns = this.shuffleAnswers();

     for(var k in allAns){
        var ans = allAns[k];
        
              
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;        
        img = tpl.replace('{src}',src)
                 .replace('{name}',ans.name)
                 .replace('{id}',ans.ansId)
                 .replace('{color}', ans.color);
        tHtmlImgs.push(img);
        if(currentQuestion.options.nbImagesByRow != 0 && ((k+1) % currentQuestion.options.nbImagesByRow) == 0 ){
            tHtmlImgs.push(qbr);
        
        }
     }

    
    return tHtmlImgs.join("\n");





}

/* ***************************************
*
* *** */

 showGoodAnswers()
  {
var ans;
var obSelectColor= null;

    var currentQuestion = this.question;

     for(var k in currentQuestion.answers){
        ans = currentQuestion.answers[k];
        obSelectColor = document.getElementById(ans.idColor);
        if (currentQuestion.options.colorSelectMode == 0){
           obSelectColor.value = ans.color;
           obSelectColor.style.background = ans.color;
        }
        document.getElementById(ans.ansId).style.background = ans.color;
        // console.log(`getScoreByProposition : ans.points = ${ans.points} - ans.color = ${ans.color} - obSelectColor.value = ${obSelectColor.value}`);
     }

     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
var ans;
var obSelectColor= null;

    var currentQuestion = this.question;

     for(var k in currentQuestion.answers){
         ans = currentQuestion.answers[k];
         var colorRnd =  currentQuestion.answers[getRandom(currentQuestion.answers.length)].color;
         
         obSelectColor = document.getElementById(ans.idColor);
        if (currentQuestion.options.colorSelectMode == 0){
         obSelectColor.value = colorRnd;
         obSelectColor.style.background = colorRnd;
        }         
         document.getElementById(ans.ansId).style.background = colorRnd;
         
         
        // console.log(`getScoreByProposition : ans.points = ${ans.points} - ans.color = ${ans.color} - obSelectColor.value = ${obSelectColor.value}`);
     }

     return true;
  } 


}  // =========== FIN DE LA CLASSE ========================

/* ******************************************************************** */
/* ============ evenements ============================================ */
/* ******************************************************************** */
function imagesColor_showPicker(e, idPicker, etat){
    obPicker = document.getElementById(idPicker);
    obDivImg =  e.currentTarget.parentNode;
    obDivColor =  e.currentTarget;
    
console.log("imagesColor_showPicker = " + obPicker.id);
console.log('top = ' +  obDivImg.id + " = " +  obDivImg.offsetTop);
console.log('==================');
    if (etat == 1){
        obPicker.style.display='block';
       // obPicker.style.top = e.currentTarget.style.top;
        obPicker.style.left = obDivImg.offsetLeft + "px";
        obPicker.style.top =  obDivImg.offsetTop + "px";
//         obPicker.left = "10px";
obPicker.setAttribute('idImg', e.currentTarget.id)
    }else{
        obPicker.style.display='none';
    }
}

/* ******************************************************************** 
 *
 * ******************************************************************** */
function imagesColor_selectColor(e, idPicker){
    var obPicker = document.getElementById(idPicker);
    var obDivColor =  e.currentTarget;
    
    var idDivImg =  obPicker.getAttribute('idImg');
    //console.log(obDivColor.id + " = " +  obDivColor.style.background);
    //console.log(idDivImg + " = " +  obDivColor.id + "===>" + obDivColor.getAttribute('background'));
    
    document.getElementById(idDivImg).style.background = obDivColor.style.background;
    obPicker.style.display='none';
    //e.stopPropagation();    
}

/* ******************************************************************** 
 *
 * ******************************************************************** */
function imagesColor_onchange(e, idImg, idSelectColor){
//console.log("===> dad => " + "imagesDaDGroups_start" + " - " + e.target.getAttribute("id"));
//alert(idImg);
    //document.getElementById(idImg).style.background = document.getElementById(idSelectColor).value;
    document.getElementById(idImg).style.background = e.currentTarget.value;
    e.currentTarget.style.background = e.currentTarget.value;
    return true;
   
}




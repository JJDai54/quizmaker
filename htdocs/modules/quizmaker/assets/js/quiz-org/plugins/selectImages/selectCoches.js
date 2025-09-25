
const selectCoches_opacity = 25;

/*******************************************************************
*                     selectCoches
* *****************************************************************/
function getPlugin_selectCoches(question, slideNumber){
    return new selectCoches(question, slideNumber);
}

 /*******************************************************************
  *                     selectCoches
  * *****************************************************************/
class selectCoches extends selectImages{ 
name = 'selectCoches';
msgNextSlideDelai = 1500;  
  


/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var tHtml = [];
    var i = 0;

    //alert ("selectCoches repartition : " + h*2);

    var imgStyle = `height:${options.imgHeight1}px;`;
    //var posLibelleV = [-20,50,90][3];
    var posLibelleV = options.posLibelleV;
    var pStyle = `top:${posLibelleV}%;font-size:${options.fontSize}em;padding-left:12px;`;
    
    var intervalVertical =  getMarginStyle(currentQuestion.answers.length,0,'',options.intervalVertical,options.intervalVertical,'em');   
    var divIntervalVertical = '';

    //var pStyle = `position:absolute;top:0px;transition:50% 30%;font-size:${options.fontSize}em;`;
    
    var ansArr = this.shuffleAnswers();

    var styleCoche = `height:${options.cocheImgHeight}px;filter:grayscale(1) opacity(${options.cocheOpacity}%);'`;
    var eventOnClick = `onclick="selectCoches_event_gotoNextSlide(event, ${this.slideNumber});"`;
    var nameCoche = this.getName('coche');
    
    if(options.trHeight == 0){
        options.trHeight = Math.floor((350/(ansArr.length+1)));
    }
    
    tHtml.push ('<table>');
    for(var k in ansArr){
        var ans = ansArr[k];
        //alert(src);
        if (ans.proposition == '') continue;
        if(k > 0){divIntervalVertical = intervalVertical;} //pas de marge sur le premier item
        var pStyle2 = pStyle + `color:${ans.color};`;       
         //alert( `reponse ${k} = ${ans.proposition} - img : ${ans.image1}`);
        
        var idCoche = ans.ansId + quiz_config.suffixCoche;
        var cocheImgName = (options.cocheImgName) ? options.cocheImgName : 'coche_01.png';
        var imgCoche= `${currentQuestion.urlPlugin}/img/coches/${cocheImgName} `;   
        var onClickLabel = `onclick="document.getElementById('${ans.cocheId}').click();"`;

        tHtml.push (`<tr style='height:${options.trHeight}px'>
                     <td><img id='${idCoche}' name='${nameCoche}' src='${imgCoche}' coche style='${styleCoche}' alt='' title='' ${eventOnClick}><td>
                     <td style='vertical-align:middle;'><label style='${pStyle2}' ${onClickLabel}>${getNumAlpha(k, currentQuestion.numbering, 0)}${ans.proposition}</label></td>
                     </tr>`);

    }
    //alert('msgNextSlideTxt = ' + options.msgNextSlideTxt);
    tHtml.push ('</table>');

    var tpl = this.getDisposition(options.disposition, this.getId('togodo'));
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"));
    
    return html;
}
/* ************************************
*
* **** */
getInnerHTML2(bShuffle = true){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var tHtml = [];
    var i = 0;

    //alert ("selectCoches repartition : " + h*2);

    var imgStyle = `height:${options.imgHeight1}px;`;
    //var posLibelleV = [-20,50,90][3];
    var posLibelleV = options.posLibelleV;
    var pStyle = `top:${posLibelleV}%;font-size:${options.fontSize}em;padding-left:12px;`;
    
    var intervalVertical =  getMarginStyle(currentQuestion.answers.length,0,'',options.intervalVertical,options.intervalVertical,'em');   
    var divIntervalVertical = '';

    //var pStyle = `position:absolute;top:0px;transition:50% 30%;font-size:${options.fontSize}em;`;
    
    var ansArr = this.shuffleAnswers();

    var styleCoche = `height:${options.cocheImgHeight}px;filter:grayscale(1) opacity(${options.cocheOpacity}%);'`;
    var eventOnClick = `onclick="selectCoches_event_gotoNextSlide(event, ${this.slideNumber});"`;
    var nameCoche = this.getName('coche');
    
    for(var k in ansArr){
        var ans = ansArr[k];
        //alert(src);
        if (ans.proposition == '') continue;
        if(k > 0){divIntervalVertical = intervalVertical;} //pas de mage sur le premier item
        var pStyle2 = pStyle + `color:${ans.color};`;       
         //alert( `reponse ${k} = ${ans.proposition} - img : ${ans.image1}`);
        
        var idCoche = ans.ansId + quiz_config.suffixCoche;
        var cocheImgName = (options.cocheImgName) ? options.cocheImgName : 'coche_01.png';
        var imgCoche= `${currentQuestion.urlPlugin}/img/coches/${cocheImgName} `;   
        var onClickLabel = `onclick="document.getElementById('${ans.cocheId}').click();"`;

        tHtml.push (`<div style='width:95%;${divIntervalVertical}'>
                     <img id='${idCoche}' name='${nameCoche}' src='${imgCoche}' coche style='${styleCoche}' alt='' title='' ${eventOnClick}>
                     <label style='${pStyle2}' ${onClickLabel}>${getNumAlpha(k, currentQuestion.numbering, 0)}${ans.proposition}</label>
                     </div>`);

    }
    //alert('msgNextSlideTxt = ' + options.msgNextSlideTxt);

    var tpl = this.getDisposition(options.disposition, this.getId('togodo'));
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"));
    
    return html;
}

/* *******************************************
* * change l'etat de l'image, cochée ou non
* ********** */
setValue(obCoche, newValue){

    var currentQuestion = this.question;
    obCoche.setAttribute('value', newValue);

    if(newValue == 1){
        obCoche.style.filter = "grayscale(0) opacity(100)";
    }else{
        obCoche.style.filter = `grayscale(1) opacity(${currentQuestion.options.cocheOpacity}%)`;
    }
    
}

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, contenairId){
    var currentQuestion = this.question;

var tpl="";
    
    if(!this.isImage()) {disposition = 'disposition-00';}
  
    switch(disposition){
    case 'disposition-02':
        var tdStyleImg = "style='width:250px'";
        var tdStyleTxt = "style='width:400px;padding-left:25px'";
        tpl = `<center><table><tr><td ${tdStyleImg}>{image}</td><td ${tdStyleTxt}><div class='${this.name}_divMaitre'>{options}</div></td></tr></table></center>`;
        break;
        
    case 'disposition-03':
        var tdStyleImg = "style='width:250px'";
        var tdStyleTxt = "style='width:400px'";
        tpl = `<center><table><tr><td ${tdStyleTxt}><div class='${this.name}_divMaitre'>{options}</div></td><td ${tdStyleImg}>{image}</td></tr></table></center>`;
        break;
        
    case 'disposition-01':
        tpl = `{image}<br><div class='${this.name}_divMaitre style='padding-left:20%''>{options}</div>`;
        break;
        
    case 'disposition-00':
    default:
        tpl = `<div class='${this.name}_divMaitre' style='padding-left:20%'>{options}</div>`;
    }

    return tpl;
}

} // *************** fin de la classe ********************


/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function selectCoches_event_gotoNextSlide(ev, slideNumber){
    selectImages_event_gotoNextSlide(ev, slideNumber);
}

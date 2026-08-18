/*******************************************************************
*                     selectImages
* *****************************************************************/
function getPlugin_selectImages(question, slideNumber){
    //return new selectImages(question, slideNumber);

    switch(question.options.variant){
    case '00-classic' : return new selectClassic(question, slideNumber, 'selectClassic'); break;
    case '02-texte'   : return new selectCoches(question, slideNumber, 'selectCoches'); break;
    case '01-image'   : 
    default           : return new selectImages(question, slideNumber, 'selectImages')
    }

}


 /*******************************************************************
  *                     selectImages
  * *****************************************************************/
class selectImages extends Plugin_Prototype{
name = 'selectImages';
msgNextSlideDelai = 1500;  
/* ***************************************
*
* *** */
buildSlide (bShuffle = true){
//alert("selectImages");
    this.boolDog = false;
    return this.getInnerHTML(bShuffle);
 }


/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
//alert("selectImages : getInnerHTML");
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var tHtml = [];
    var repartition = options.repartition + "321321321";
    var i = 0;
    var h = repartition[i];
    //alert ("selectImages repartition : " + h*2);

    var imgStyle = `height:${options.imgHeight1}px;`;
    //var posLibelleV = [-20,50,90][3];
    var posLibelleV = options.posLibelleV;
    var pStyle = `top:${posLibelleV}%;font-size:${options.fontSize}em;`;
    //var pStyle = `position:absolute;top:0px;transition:50% 30%;font-size:${options.fontSize}em;`;
    
    var cocheOnClick = `onclick="selectImages_event_gotoNextSlide(event, ${this.slideNumber});"`;
    var ansArr = this.shuffleAnswers();
    var nameCoche = this.getName('coche');
    var styleCoche = `height:${options.cocheImgHeight}px;filter:grayscale(1) opacity(${options.cocheOpacity}%);'`;
    var cocheImgFileName = (options.cocheImgName) ? options.cocheImgName : 'coche_01.png';
//     if(options.intervalVertical == 0) {
//         var intervalVertical =  getMarginStyle(currentQuestion.answers.length,2,'',3);   
//     }else{
//         var intervalVertical =  `margin-top:${options.intervalVertical}em;`;   
//     }
    var intervalVertical =  getMarginStyle(currentQuestion.answers.length,0,'',options.intervalVertical,options.intervalVertical, 'px');   
    //var divIntervalVertical = '';
// alert(ansArr.length);           
    for(var k in ansArr){
        var ans = ansArr[k];
        if(ans.image1 == ''){
            var src = quiz_config.urlImgRoot + '/buttons/' + ans.image2;
        // alert(`a : ${src} ===> ${ans.image2}`);
        }else{
            var src = quiz_config.urlQuizImg + '/' + ans.image1;
            //alert(`b : ${src}`);
        }
        //alert(ans.cocheId);
        //if (ans.proposition == '' && ans.image1 == '' && ans.image2 == '') continue;
//        console.log(` : ${ans.proposition} ===> ${ans.image1} === ${ans.image2}`);
        //----------------------------------------------------
        var imgOnClick = `onclick="document.getElementById('${ans.cocheId}').click();"`;
    
        var pStyle2 = pStyle + `color:${ans.color};`;       
         //alert( `reponse ${k} = ${ans.proposition} - img : ${ans.image1}`);
        var numbering = (currentQuestion.numbering == 0) ? '' : getNumAlpha(k, currentQuestion.numbering, 0, '') + "<br>";
        
        tHtml.push (`<div style='${intervalVertical}'>
        <img src='${src}' id='${ans.ansId}' name='${this.getName()}' style='${imgStyle}' ${imgOnClick}>
        <p style='${pStyle2}'  inert>${numbering}${ans.proposition}</p> 
        <img id='${ans.cocheId}' name=${nameCoche} ${cocheOnClick} src='${quiz_config.urlImgRoot}/coches/${cocheImgFileName}' coche  value='0' style='${styleCoche}' alt='' title=''>
        </div>`);
        //alert(`fld img : ${quiz_config.urlImgRoot}/coches/${cocheImgFileName}`);
        //<img id='${ans.cocheId}' name=${nameCoche} ${cocheOnClick} src='${currentQuestion.urlPlugin}/img/coches/${cocheImgFileName}' coche  value='0' style='${styleCoche}' alt='' title=''>
        h--;
        if (h == 0){
            tHtml.push (`<br>`);
            h = repartition[++i];
            while(repartition[i] == 0){
                tHtml.push (`<br>`);
                h = repartition[++i];
            }
         //divIntervalVertical =  intervalVertical;   
        }
    }
    //alert('msgNextSlideTxt = ' + options.msgNextSlideTxt);

    var tpl = this.getDisposition(options.disposition, this.getId('togodo')) + '<br>';
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"));
    
    return html;
}
//---------------------------------------------------
onEnter() {
}       
//---------------------------------------------------
onFinalyse() {
    super.onFinalyse();
}       

//---------------------------------------------------
 prepareData(){
    var currentQuestion = this.question;
    var options = this.question.options;
    
    this.countAnsNotNull = 0; 
    let nbGoodAns = 0;
    
    for(var k in currentQuestion.answers){
       var ans = currentQuestion.answers[k];
        ans.proposition = this.sanityse_exp(ans.proposition, 127);
        ans.cocheId = ans.ansId + quiz_config.suffixCoche;
        if((ans.points*1) !=0 ) this.countAnsNotNull++;
        if((ans.points*1) > 0 ) nbGoodAns++;
    }
    
    if(options.inputType == 'auto'){
        options.inputType = (nbGoodAns == 1) ? 'radio' : 'checkbox';
    } 
    
    this.initMinMaxQQ(2);
    
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;

    var currentQuestion = this.question;
    
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var v = document.getElementById(ans.cocheId).getAttribute('value')*1;
        var ansPoints = ans.points*1;
        if(currentQuestion.options.inputType == 'radio'){
            if (v == 1){
                points += ansPoints;
            }
        }else{
            if (v == 1){
                if (ansPoints == 0){
                    points = 0;
                    break;
                }
                points += ans.points*1;
            }
        }
        
//console.log(`getScoreByProposition [${k}]: ansId = ${ans.ansId} - value = ${v} `);
    }

    return points;
}
/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getNbImgChecked (){
var nbImgChecked = 0;
 
    var currentQuestion = this.question;

    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        if (document.getElementById(ans.ansId + quiz_config.suffixCoche).getAttribute('value')*1 == 1){
            nbImgChecked++;
        }
    }
    return nbImgChecked;
}


//---------------------------------------------------
computeScoresMinMaxByProposition(){
    if(this.question.options.inputType == 'checkbox'){
        this.computeScoresMinMaxCheckbox();
    }else{
        this.computeScoresMinMaxRadio();
    }
}
//---------------------------------------------------
computeScoresMinMaxCheckbox(){
    this.scoreMaxiBP = 0;
    this.scoreMiniBP = 0;
    
    var currentQuestion = this.question;
     for(var i in currentQuestion.answers){
          if (currentQuestion.answers[i].points > 0)
                this.scoreMaxiBP += currentQuestion.answers[i].points*1;
          if (currentQuestion.answers[i].points < 0)
                this.scoreMiniBP += currentQuestion.answers[i].points*1;
      }

     return true;
 }
//---------------------------------------------------
computeScoresMinMaxRadio(){
    var currentQuestion = this.question;
var maxPoints = 0;
var minPoints = 99999;
var ans = null;

    for  (var k in currentQuestion.answers){
        ans = currentQuestion.answers[k];
        if (maxPoints < ans.points*1){
            maxPoints = ans.points*1;
        }
        if (minPoints > ans.points*1){
            minPoints = ans.points*1;
        }
    }

     this.scoreMaxiBP = maxPoints ;
     this.scoreMiniBP = minPoints;
     return true;
}

/* **************************************************

***************************************************** */
getAllPropositions (flag = 0){
    var  currentQuestion = this.question;
    var tReponses = [];
    
    for(var i in currentQuestion.answers){
        var ans = currentQuestion.answers[i];
        if(ans.points > 0 || flag == 0) {
            //tReponses.push ({'reponse':ans.proposition, 'points':ans.points});    
            tReponses.push ([[ans.proposition] , [ans.points]]);    
        }
    }
    tReponses = sortArrayObject(tReponses, 1, "DESC");
    return formatArray0(tReponses, "=>", true, 1);

 }


/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    var  currentQuestion = this.question;

    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        this.setValue(document.getElementById(ans.ansId + quiz_config.suffixCoche), ((ans.points*1 > 0) ? 1 : 0));

    }
    
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var  currentQuestion = this.question;
    
    if(currentQuestion.options.inputType == 'checkbox'){
        for(var k in currentQuestion.answers){
            var ans = currentQuestion.answers[k];
            var newValue = getRandom(1);
            this.setValue(document.getElementById(ans.ansId + quiz_config.suffixCoche), newValue);
        }
    }else{
        for(var k in currentQuestion.answers){
            var ans = currentQuestion.answers[k];
            this.setValue(document.getElementById(ans.ansId + quiz_config.suffixCoche), 0);
        }

    
        var k = getRandom(currentQuestion.answers.length-1);
        var ans = currentQuestion.answers[k];
        this.setValue(document.getElementById(ans.ansId + quiz_config.suffixCoche), 1);

    }

     return true;
  } 
  

  /* *********************************************
  
  ************************************************ */
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
  //alert(disposition);  
    switch(disposition){
    case 'disposition-02':
        tpl = `<center><table><tr><td style='padding-left:20px;padding-right:20px;vertical-align: top;'>{image}</td><td><div class='${this.name}_divMaitre'>{options}</div></td></tr></table></center>`;
        break;
        
    case 'disposition-03':
        tpl = `<center><table><tr><td><div class='${this.name}_divMaitre'>{options}</div></td><td>{image}</td></tr></table></center>`;
        break;
        
    case 'disposition-01':
        tpl = `{image}<br><div class='${this.name}_divMaitre'>{options}<br><br></div>`;
        break;
        
    case 'disposition-00':
    default:
        tpl = `<div class='${this.name}_divMaitre'>{options}</div>`;
    }

    return tpl;
}

} // *************** fin de la class ********************


/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function selectImages_event_gotoNextSlide(ev, slideNumber){
    //console.log(`selectImages_event_gotoNextSlide : slideNumber = ${slideNumber}`);
//alert('selectImages_event_gotoNextSlide');
    var clQuestion = quizard[slideNumber];
    var options = clQuestion.question.options;
    selectImages_change_etat(ev.currentTarget, clQuestion);   
    
    
    var gotoNexSlide = false;
    if((options.msg_nextslide_duree * 1) > 0 && options.msg_nextslide_gotonext > 0){
        if(options.inputType == 'radio'){
            gotoNexSlide = true;
        }else if(options.inputType == 'checkbox'){
            var nbImgChecked = clQuestion.getNbImgChecked();
            //var obs = clQuestion.getQuerySelector("input", clQuestion.getName(), clQuestion.data.inputType, "checked");
            gotoNexSlide = (nbImgChecked == clQuestion.countAnsNotNull);
        }
    }

    //alert (clQuestion.getScoreByProposition() + " / " + clQuestion.scoreMaxiBP);
    if(gotoNexSlide){
        var winner =  (clQuestion.getScoreByProposition() == clQuestion.scoreMaxiBP);
        clQuestion.show_avertissement_WL (winner)
    }  
    //ev.stopPropagation();
}

/* *******************************************
* * Affecte les nouvelles valeurs
* ********** */
function selectImages_change_etat(obSelected, clQuestion){
    var idSelected = obSelected.id;

        var name = obSelected.name;    
        
        
    //si c'est un choix unique (bouton radio) mettre toutes les options à '0'
    if(clQuestion.question.options.inputType == 'radio'){
        var nameCoche = obSelected.name;
        //var nameCoche = clQuestion.getName('coche');
            
        var allOptions = document.getElementsByName(nameCoche);
        console.log('===>selectImages_change_etat : name = ' + nameCoche + '- nb = ' + allOptions.length);
        for(var i = 0; i < allOptions.length; i++){
            //console.log('===> cocheId = ' + allOptions[i].id);
            clQuestion.setValue(allOptions[i], 0);
        }
        
        clQuestion.setValue(obSelected, 1);

       
    }else if(obSelected.getAttribute('value')*1 == 1){
        clQuestion.setValue(obSelected, 0);
    }else{
        clQuestion.setValue(obSelected, 1);
    }
    
}

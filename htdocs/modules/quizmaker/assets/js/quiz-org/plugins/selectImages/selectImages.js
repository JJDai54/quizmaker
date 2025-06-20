/*******************************************************************
*                     selectImages
* *****************************************************************/
function getPlugin_selectImages(question, slideNumber){
    return new selectImages(question, slideNumber);
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
    var currentQuestion = this.question;
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var tHtml = [];
    var repartition = currentQuestion.options.repartition + "321321321";
    var i = 0;
    var h = repartition[i];
    //alert ("selectImages repartition : " + h*2);

    var imgStyle = `height:${currentQuestion.options.imgHeight1}px;`;
    //var posLibelleV = [-20,50,90][3];
    var posLibelleV = currentQuestion.options.posLibelleV;
    var pStyle = `top:${posLibelleV}%;font-size:${currentQuestion.options.fontSize}em;`;
    //var pStyle = `position:absolute;top:0px;transition:50% 30%;font-size:${currentQuestion.options.fontSize}em;`;
    
    var eventOnClick = `onclick="selectImages_event_gotoNextSlide(event, ${this.slideNumber});"`;
    var ansArr = this.shuffleAnswers();
    
    for(var k in ansArr){
        var ans = ansArr[k];
        if(ans.image1 == ''){
            var src = currentQuestion.urlPlugin + '/img/buttons/' + ans.image2;
        }else{
            var src = quiz_config.urlQuizImg + '/' + ans.image1;
        }
        //alert(src);
        if (ans.proposition == '') continue;
        var pStyle2 = pStyle + `color:${ans.color};`;       
         //alert( `reponse ${k} = ${ans.proposition} - img : ${ans.image1}`);
        
        var suffixCoche = quiz_config.suffixCoche;
        var cocheImgName = (currentQuestion.options.cocheImgName) ? currentQuestion.options.cocheImgName : 'coche_01.png';
        tHtml.push (`<div><img src='${src}' id='${ans.ansId}' name='${this.getName()}' style='${imgStyle}' ${eventOnClick} value='0'><p style='${pStyle2}'  inert>${ans.proposition}</p> 
        <img id='${ans.ansId}${suffixCoche}' src='${currentQuestion.urlPlugin}/img/coches/${cocheImgName}' coche height='${currentQuestion.options.cocheImgHeight}px' alt='' title=''>
        </div>`);
        h--;
        if (h == 0){
            tHtml.push (`<br>`);
            h = repartition[++i];
        }
    }
    //alert('msgNextSlideTxt = ' + currentQuestion.options.msgNextSlideTxt);

    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId('togodo'));
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"));
    
    return html;
}
//---------------------------------------------------
onEnter() {
    //document.getElementById('quiz_btn_nextSlide').disabled = '';
    //alert("onEnter");
}       
onFinalyse() {
    super.onFinalyse();
}       

//---------------------------------------------------
 prepareData(){
    var currentQuestion = this.question;

    this.countAnsNotNull = 0; 
    var ansArr = this.shuffleAnswers();
    
    for(var k in ansArr){
            var ans = ansArr[k];
        if((ans.points*1) !=0 ) this.countAnsNotNull++;
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
        if (document.getElementById(ans.ansId).getAttribute('value')*1 == 1){
            points += ans.points*1;
        }
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
        if (document.getElementById(ans.ansId).getAttribute('value')*1 == 1){
            nbImgChecked++;
        }
    }
    return nbImgChecked;
}


//---------------------------------------------------
computeScoresMinMaxByProposition(){
    if(this.question.options.multipleChoice == 1){
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
getAllReponses (flag = 0){
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
        var obOption = document.getElementById(ans.ansId);
        var obCoche =  document.getElementById(ans.ansId + quiz_config.suffixCoche);
        
        this.setValue(ans.ansId, ((ans.points*1 > 0) ? 1 : 0));
    }
    
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var  currentQuestion = this.question;
    
    if(currentQuestion.options.inputType == 0){
        for(var k in currentQuestion.answers){
            var ans = currentQuestion.answers[k];
            var newValue = getRandom(1);
            this.setValue(ans.ansId, newValue)    
        }
    }else{
        for(var k in currentQuestion.answers){
            var ans = currentQuestion.answers[k];
            this.setValue(ans.ansId, 0)    
        }

    
        var k = getRandom(currentQuestion.answers.length-1);
        var ans = currentQuestion.answers[k];
        this.setValue(ans.ansId, 1)

    }

     return true;
  } 
  
  /* *********************************************
  
  ************************************************ */
setValue(ansId, newValue){
    var  currentQuestion = this.question;
    var obOption = document.getElementById(ansId);
    
    obOption.setAttribute('value', newValue);
    var obCoche = document.getElementById(obOption.id + quiz_config.suffixCoche);
    obCoche.style.visibility = (newValue == 1) ? 'visible' : 'hidden';    
}  


  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, contenairId){
    var currentQuestion = this.question;
var imgHeight1 = currentQuestion.options.imgHeight1;
var btn = "div<img>";
var tpl="";
  //alert(disposition);  
    switch(disposition){
    case 'disposition-02':
        tpl = `<center><table><tr><td>{image}</td><td><div class='selectImages_divMaitre'>{options}</div></td></tr></table></center>`;
        break;
        
    case 'disposition-03':
        tpl = `<center><table><tr><td><div class='selectImages_divMaitre'>{options}</div></td><td>{image}</td></tr></table></center>`;
        break;
        
    case 'disposition-00':
    case 'disposition-01':
    default:
        tpl = `{image}<br><div class='selectImages_divMaitre'>{options}</div>`;
        break;
    }
    //return `<div id='{contenairId}' style='height:${imgHeight1}px;'>${tpl}</div>`;
    return `<div id='{contenairId}'>${tpl}</div><br>`;
}

} // *************** fin de la classe ********************

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function selectImages_event_gotoNextSlide(ev, slideNumber){
    console.log(`selectImages_event_gotoNextSlide : slideNumber = ${slideNumber}`);

    var clQuestion = quizard[slideNumber];
    var options = clQuestion.question.options;
    selectImages_change_etat(ev.currentTarget, options.inputType);   
    
    
    if(options.nextSlideDelai * 1 > 0){
        var gotoNexSlide = false;
        if(options.inputType == 1){
            gotoNexSlide = true;
        }else if(options.inputType == 0){
            var nbImgChecked = clQuestion.getNbImgChecked();
            //var obs = clQuestion.getQuerySelector("input", clQuestion.getName(), clQuestion.data.inputType, "checked");
            gotoNexSlide = (nbImgChecked == clQuestion.countAnsNotNull);
        }
    }
    
    if(gotoNexSlide){
        quiz_show_avertissement (options.nextSlideMessage, options.nextSlideDelai, options.nextSlideBG);
    }  
    ev.stopPropagation();
}


/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function selectImages_change_etat(obSelected, inputType){
    var idSelected = obSelected.id;

        var name = obSelected.name;    
        
        
    //si c'est un choix unique (bouton radio) mettre toutes les options à '0'
    if(inputType > 0){
        console.log('===>selectImages_change_etat : inputType = ' + inputType);
        var name = obSelected.name;
        var allOptions = document.getElementsByName(name);
        console.log('===>selectImages_change_etat : name = ' + name + '- nb = ' + allOptions.length);
        for(var i = 0; i < allOptions.length; i++){
            console.log('id = ' + allOptions[i].id);
            allOptions[i].setAttribute('value', 0);
            document.getElementById(allOptions[i].id + quiz_config.suffixCoche).style.visibility = 'hidden';    
        }
        
       obSelected.setAttribute('value', 1);
       document.getElementById(idSelected + quiz_config.suffixCoche).style.visibility = 'visible';    
       
    }else if(obSelected.getAttribute('value')*1 == 1){
    //mettre l'option selectionée à 1    
        obSelected.setAttribute('value', 0);
        document.getElementById(idSelected + quiz_config.suffixCoche).style.visibility = 'hidden';    
    }else{
        obSelected.setAttribute('value', 1);
        document.getElementById(idSelected + quiz_config.suffixCoche).style.visibility = 'visible';    
    }
    
}

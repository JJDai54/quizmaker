
 /*******************************************************************
  *                     _choiceImages
  * *****************************************************************/
class choiceImages extends Plugin_Prototype{
name = 'choiceImages';
delaiNextSlide = 1500;  
/* ***************************************
*
* *** */
build (){
//alert("choiceImages");
    this.boolDog = false;
    return this.getInnerHTML() ;
 }


/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var tHtml = [];
    var repartition = currentQuestion.options.repartition + "321321321";
    var i = 0;
    var h = repartition[i];
    //alert ("choiceImages repartition : " + h*2);
    var msgNextSlide = '';
    var divNexSlide = '';

    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId(''));
    var imgStyle = `height:${currentQuestion.options.imgHeight1}px;`;
    var pStyle = `top:${currentQuestion.options.posLibelleV}%;font-size:${currentQuestion.options.fontSize}em;`;
    
    var eventOnClick = `onclick="choiceImages_event_gotoNextSlide(event, ${currentQuestion.options.inputType},${this.delaiNextSlide});"`;
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
    
    if (currentQuestion.options.msgNextSlide){
        msgNextSlide = replaceBalisesByValues(currentQuestion.options.msgNextSlide);
        divNexSlide = `<div id='${this.getId('nextquestion')}' style='background:${currentQuestion.options.msgNextSlideBG}' nextquestion>${msgNextSlide}</div>`;
    }else{
        divNexSlide = '';
    }
    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId('togodo'));
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"))
                  .replace('{nextslide}', divNexSlide);
    
    return html;
}
//---------------------------------------------------
onEnter() {
    //document.getElementById('quiz_btn_nextSlide').disabled = '';
    //alert("onEnter");
}       
onFinalyse() {
    var currentQuestion = this.question;
    if (currentQuestion.options.inputType == 2){
        //document.getElementById('quiz_btn_nextSlide').setAttribute('disabled','disabled');
        document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //alert("onEnter");
    }else{
        document.getElementById('quiz_btn_nextSlide').disabled = '';
    }
}       
//---------------------------------------------------
 prepareData(){
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
    case 'disposition-00':
        tpl = `<div class='choiceImages_divMaitre'>{options}</div>`;
        break;

        break;
        
    case 'disposition-02':
        tpl = `<table><tr><td>{image}</td><td><div class='choiceImages_divMaitre'>{options}</div></td></tr></table>`;
        break;
        
    case 'disposition-03':
        tpl = `<table><tr><td><div class='choiceImages_divMaitre'>{options}</div></td><td>{image}</td></tr></table>`;
        break;
        
    case 'disposition-01':
    default:
        tpl = `{image}<br><div class='choiceImages_divMaitre'>{options}</div>`;
        break;
    }
    //return `<div id='{contenairId}' style='height:${imgHeight1}px;'>${tpl}</div>`;
    return `<div id='{contenairId}'>{nextslide}${tpl}</div><br>`;
}

} // ----- fin de la classe ------

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceImages_event_gotoNextSlide(ev, inputType, delaiNextSlide){
    console.log("choiceImages_event_gotoNextSlide");
    
    
    choiceImages_change_etat(ev.currentTarget, inputType);   
    
    //c'est un hoix unique avec passage au slide suivant
     if(inputType == 2){     
        //dans tous les cas on reactive le bouton nextSlide
        idDivNextQuestion = ev.currentTarget.name + '-nextquestion';

        if(choiceImages_show_message(idDivNextQuestion)){
            setTimeout(choiceImages_next_slide, delaiNextSlide, idDivNextQuestion);
        }else{
            setTimeout(choiceImages_next_slide, delaiNextSlide/2, idDivNextQuestion);
        }
        
    }
    ev.stopPropagation();
}
/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceImages_next_slide(idDivNextQuestion){
    var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';   
        btnNextSlide.click(); 
    
   if(obNextSlide){
     obNextSlide.style.visibility = 'hidden';
     obNextSlide.style.opacity = '0';
     obNextSlide.classList.remove('choiceImages_vignets');        
   }
   
}
/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceImages_show_message(idDivNextQuestion){
    obNextSlide =  document.getElementById(idDivNextQuestion)
    if(obNextSlide){
      obNextSlide.style.visibility = 'visible';        
      obNextSlide.classList.add('choiceImages_vignets');  
      return true;      
    }else{
        return false;
    }
}

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function choiceImages_change_etat(obSelected, inputType){
    var idSelected = obSelected.id;

        var name = obSelected.name;    
        
        
    //si c'est un choix unique (bouton radio) mettre toutes les options à '0'
    if(inputType > 0){
        console.log('===>choiceImages_change_etat : inputType = ' + inputType);
        var name = obSelected.name;
        var allOptions = document.getElementsByName(name);
        console.log('===>choiceImages_change_etat : name = ' + name + '- nb = ' + allOptions.length);
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

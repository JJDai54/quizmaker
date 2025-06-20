/*******************************************************************
*                     PluginName
* *****************************************************************/
function getPlugin_PluginName(question, slideNumber){
    return new PluginName(question, slideNumber);
}

 /*******************************************************************
  *                     PluginName
  * *****************************************************************/
class PluginName extends Plugin_Prototype{
name = 'PluginName';
msgNextSlideDelai = 1500;  
/* ***************************************
*
* *** */
buildSlide (bShuffle = true){
//alert("PluginName");
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
    
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var  currentQuestion = this.question;
    

     return true;
  } 
  

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, contenairId){
    var currentQuestion = this.question;

} // ----- fin de la classe ------

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function PluginName_event_gotoNextSlide(ev, inputType, msgNextSlideDelai){
    console.log("PluginName_event_gotoNextSlide");
    
    
    PluginName_change_etat(ev.currentTarget, inputType);   
    
    //c'est un hoix unique avec passage au slide suivant
     if(inputType == 2){     
        //dans tous les cas on reactive le bouton nextSlide
        var idDivNextQuestion = ev.currentTarget.name + '-nextquestion';

        if(PluginName_show_message(idDivNextQuestion)){
            setTimeout(PluginName_next_slide, msgNextSlideDelai, idDivNextQuestion);
        }else{
            setTimeout(PluginName_next_slide, msgNextSlideDelai/2, idDivNextQuestion);
        }
        
    }
    ev.stopPropagation();
}
/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function PluginName_next_slide(idDivNextQuestion){
    var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';   
        btnNextSlide.click(); 
    
   if(obNextSlide){
     obNextSlide.style.visibility = 'hidden';
     obNextSlide.style.opacity = '0';
     obNextSlide.classList.remove('PluginName_vignets');        

      var obMask = document.getElementById(idDivNextQuestion + '-mask');
      obMask.style.visibility = 'hidden';
   }
   
}
/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function PluginName_show_message(idDivNextQuestion){
//console.log('PluginName_show_message');
//console.log('PluginName_show_message : idDivNextQuestion = ' + idDivNextQuestion);      
    obNextSlide =  document.getElementById(idDivNextQuestion)
//console.log('PluginName_show_message : obNextSlide.id = ' + obNextSlide.id + '===>' + idDivNextQuestion);      
    if(obNextSlide){
      //actualisation des scores et avancement dans le quiz
      //remplacement des tokens par les scoring
      computeAllScoreEvent();    
      obNextSlide.innerHTML = replaceBalisesByValues(obNextSlide.innerHTML);

      obNextSlide.style.visibility = 'visible';        
      obNextSlide.classList.add('PluginName_vignets');  
      
      var obMask = document.getElementById(idDivNextQuestion + '-mask');
//console.log('PluginName_show_message : obMask.id = ' + obMask.id);      
      obMask.style.visibility = 'visible';
      return true;      
    }else{
        return false;
    }
}

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function PluginName_change_etat(obSelected, inputType){
    var idSelected = obSelected.id;

        var name = obSelected.name;    
        
        
    //si c'est un choix unique (bouton radio) mettre toutes les options à '0'
    if(inputType > 0){
        console.log('===>PluginName_change_etat : inputType = ' + inputType);
        var name = obSelected.name;
        var allOptions = document.getElementsByName(name);
        console.log('===>PluginName_change_etat : name = ' + name + '- nb = ' + allOptions.length);
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

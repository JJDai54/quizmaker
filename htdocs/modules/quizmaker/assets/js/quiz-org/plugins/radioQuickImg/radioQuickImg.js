
 /*******************************************************************
  *                     _radioQuickImg
  * *****************************************************************/
class radioQuickImg extends Plugin_Prototype{
name = 'radioQuickImg';
  
/* ***************************************
*
* *** */
build (){
//alert("radioQuickImg");
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
    //alert ("radioQuickImg repartition : " + h*2);

    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId(''));
    var imgStyle = `height:${currentQuestion.options.imgHeight1}px;`;
    var pStyle = `top:${currentQuestion.options.posLibelleV}%;font-size:${currentQuestion.options.fontSize}em;`;

    var eventOnClick = `onclick="radioQuickImg_event_gotoNextSlide(event, ${currentQuestion.options.gotoNextOnClick});"`;
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

        tHtml.push (`<div><img src='${src}' id='${ans.ansId}' style='${imgStyle}' ${eventOnClick} value='0'><p style='${pStyle2}'  inert>${ans.proposition}</p> </div>`);
        h--;
        if (h == 0){
            tHtml.push (`<br>`);
            h = repartition[++i];
        }
    }
    
    var tpl = this.getDisposition(currentQuestion.options.disposition, this.getId('togodo'));
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{options}',  tHtml.join("\n"));
    

    //var html = `<div class='radioQuickImg_divMaitre'>${tHtml.join("\n")}</div>`;
    //var html = tHtml.join("\n");
    //alert("innerHtml :\n" + html);
    return html;
}
//---------------------------------------------------
onEnter() {
    //document.getElementById('quiz_btn_nextSlide').disabled = '';
    //alert("onEnter");
}       
onFinalyse() {
    var currentQuestion = this.question;
    if (currentQuestion.options.gotoNextOnClick == 1){
    //document.getElementById('quiz_btn_nextSlide').setAttribute('disabled','disabled');
    document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //alert("onEnter");
    }else{
    document.getElementById('quiz_btn_nextSlide').disabled = '';
    }
}       
//---------------------------------------------------
 prepareData(){
    
//     var currentQuestion = this.question;
// 
//     var tItems = new Object;
//     
//     for(var k in currentQuestion.answers){
//         
//         var key = sanityseTextForComparaison(currentQuestion.answers[k].proposition);
//         //alert (key);
//         var key = "ans-" + k.padStart(3, '0');
//         var tWP = {'key': key,
//                    'word': currentQuestion.answers[k].proposition, 
//                    'points' : currentQuestion.answers[k].points*1};
//         tItems[key] = tWP;
// // alert("prepareData : " + tItems[key].word + ' = ' + tItems[key]. points);
// 
//     }
// 
//     //pour compatibilité avec checkboxSimple et radioSimple obsolettes
//     if(!currentQuestion.options.multipleChoice){currentQuestion.options.multipleChoice = 0;}
//     
//     this.data.items = tItems;
//     this.data.inputType = (currentQuestion.options.multipleChoice == 1) ? 'checkbox'  : 'radio';
    
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
//---------------------------------------------------
computeScoresMinMaxByProposition(){
//     if(this.question.options.multipleChoice == 1){
//         this.computeScoresMinMaxCheckbox();
//     }else{
//         this.computeScoresMinMaxRadio();
//     }
        this.computeScoresMinMaxRadio();
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
//     var currentQuestion = this.question;
// //alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
//       for(var k = 0; k < this.data.sequence.length; k++){
//         var ans = this.data.sequence[k];
// //        alert(`propo = ${ans.proposition} - points = ${ans.points}` );
//           if (ans.points*1 > 0){
//             var obImg = document.getElementById(ans.ansId);
// //            alert(obImg.getAttribute('src'));
//             obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${ans.proposition}`);
//           } 
//       }



     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
//     var currentQuestion = this.question;
// //alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
//       for(var k = 0; k < this.data.sequence.length; k++){
//         var ans = this.data.sequence[k];
// //        alert(`propo = ${ans.proposition} - points = ${ans.points}` );
//           if (ans.points*1 > 0){
//             var idFrom = getRandom(this.data.suggestion.length -1);
//             var obImg = document.getElementById(ans.ansId);
// //            alert(obImg.getAttribute('src'));
//             obImg.setAttribute('src', `${quiz_config.urlQuizImg}/${this.data.suggestion[idFrom].proposition}` );
//           } 
//       }
//      return true;
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
        tpl = `<div class='radioQuickImg_divMaitre'>{options}</div>`;
        break;

        break;
        
    case 'disposition-02':
        tpl = `<table><tr><td>{image}</td><td><div class='radioQuickImg_divMaitre'>{options}</div></td></tr></table>`;
        break;
        
    case 'disposition-03':
        tpl = `<table><tr><td><div class='radioQuickImg_divMaitre'>{options}</div></td><td>{image}</td></tr></table>`;
        break;
        
    case 'disposition-01':
    default:
        tpl = `{image}<br><div class='radioQuickImg_divMaitre'>{options}</div>`;
        break;
    }
    //return `<div id='{contenairId}' style='height:${imgHeight1}px;'>${tpl}</div>`;
    return `<div id='{contenairId}'>${tpl}</div>`;
}

} // ----- fin de la classe ------

/* *******************************************
* * Affecte la réponse et passe au slide suivant
* ********** */
function radioQuickImg_event_gotoNextSlide(ev, gotoNextSlide){
    document.getElementById('quiz_btn_nextSlide').disabled = '';
    
    if(gotoNextSlide == 1){
        ev.currentTarget.setAttribute('value', 1);
    //alert(ev.currentTarget.getAttribute('value'));
        document.getElementById('quiz_btn_nextSlide').click();
    }
    ev.stopPropagation();
}


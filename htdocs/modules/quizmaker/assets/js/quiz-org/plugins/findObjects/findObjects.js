/*******************************************************************
*                     findObjects
* *****************************************************************/
function getPlugin_findObjects(question, slideNumber){
    return new findObjects(question, slideNumber);
}

 /*******************************************************************
  *                     findObjects
  * *****************************************************************/
class findObjects extends Plugin_Prototype{
name = 'findObjects';
msgNextSlideDelai = 1500;  
colTouches = null;
urlSound='';

/* ***************************************
*
* *** */
build (){
//alert("findObjects");
    this.boolDog = false;
    return this.getInnerHTML() ;
 }


/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    //var tplOption = "<div ><img src='pingouin-02.jpg'><p>}{titre}</p></div>";
    var url = '';
    var imgId = '';
    var imgWidth = '';
//      for(var k in currentQuestion.answers){
//         var ans = currentQuestion.answers[k];
//         alert(ans.proposition + '===>' + ans.image1);
// 
//     }   
    //on s'occupe d'abor des images
    var ans = currentQuestion.answers[0];
    //alert('getInnerHTML chrslideNumberono = ' + this.slideNumber);
    var tpl = this.getDisposition(currentQuestion.options.disposition);
    var onclick = `onclick='findObjects_isToucheOk(event, ${this.slideNumber});'`;
    
    url = `${quiz_config.urlQuizImg}/${ans.image1}`;
    imgWidth = currentQuestion.options.imgWidth1;
    imgId = this.getId('img1');
    var img1 = `<div id='${imgId}' class='findObjects_divImageRef1' style="max-width:${imgWidth}px;min-width:${imgWidth}px;" ${onclick}><img src='${url}'></div>`;
    
    if(this.data.isDivImg2){
        url = `${quiz_config.urlQuizImg}/${ans.image2}`;
        imgWidth = currentQuestion.options.imgWidth2;
        imgId = this.getId('img2');
        var img2 = `<div id='${imgId}' class='findObjects_divImageRef1' style="max-width:${imgWidth}px;min-width:${imgWidth}px;" ${onclick}><img src='${url}'></div>`;
    }else{
        var img2 = ``;
    }
    
       
    var html = tpl.replace('{image1}', img1).replace('{image2}', img2).replace('{imageMain}', this.getImage());
     
    //alert(ans.proposition + '===>' + ans.image1);
    
    return html;
}

//---------------------------------------------------
onEnter() {
}
//---------------------------------------------------
initSlide() {
    var currentQuestion = this.question;
    var ans = null;    //document.getElementById('quiz_btn_nextSlide').disabled = '';
    var points = 0;
    //alert("onEnter");
    //this.colTouches = new Touches()
    //initialisation de coltouches maineant que l'on peu accéder au Dom
    var divImg1 = document.getElementById(this.getId('img1'));
    var divImg2 = document.getElementById(this.getId('img2'));

//alert(this.question.options.nextSlideMessageMax);    
    this.colTouches = new Touches(0, currentQuestion.options.maxAttemps, divImg1, divImg2);

       
    //alert(currentQuestion.answers.length);
    for (var k = 0; k < currentQuestion.answers.length; k++){
        ans = currentQuestion.answers[k];
        //ne pas prendre la premiere propositioin qui contient les images, voir grtInnrtHTML 
        if(ans.group == 1){continue;}
        
        ans.index = k;
        ans.ansId = this.getId("ans",k);
        ans.name  = this.getName('ans');
        ans.points = ans.points*1;
        points = (ans.points) ? ans.points : 1;    
        ans.isOk = true;
        this.scoreMaxiBP += points;
        
        this.colTouches.addNewFromBufferJS(ans.buffer);
        points += ans.points;
        //console.log (`onEnter : count = ${this.colTouches.nbTouches} - idImage1 = ${divImg1.id}\nbuffer = ${ans.buffer}`)
    
    }
    this.urlSound =  quiz.urlMain + '/plugins/findObjects/sounds/';
    this.initMinMaxQQ(0);
    
}   
//---------------------------------------------------
onFinalyse() {
    super.onFinalyse();
    var currentQuestion = this.question;
    if (currentQuestion.options.nextSlideDelai*1 > 0){
        //document.getElementById('quiz_btn_nextSlide').setAttribute('disabled','disabled');
        document.getElementById('quiz_btn_nextSlide').disabled = 'disabled';
        //alert("onEnter");
    }else{
        document.getElementById('quiz_btn_nextSlide').disabled = '';
    }

    var attempts = this.colTouches.attempts;
    document.getElementById(this.data.idAttempts).innerHTML = `${attempts.total}/${attempts.max}`;     
    document.getElementById(this.data.idObjetsFound).innerHTML = `${attempts.winning}/${attempts.totalWinning}`;    

    if(currentQuestion.options.zoom == 2) {
        zoom_plus(event, this.slideNumber);  
    }  
console.log('===> onFinalyse : ' + currentQuestion.question);
}       
//---------------------------------------------------
 prepareData(){
    //this.colTouches = new Touches()
      // colTouches = new Touches(0, 0, mydiv1, mydiv2);
    this.initMinMaxQQ(2);
    
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
 
    var currentQuestion = this.question;

    for(var h = 0; h < this.colTouches.nbTouches; h++){
        var clTouche = this.colTouches.collection[h];
        if(clTouche.tics > 0) {points += clTouche.points*1;}
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
  console.log('===> showGoodAnswers');
    var  currentQuestion = this.question;
    var divImg1 = document.getElementById(this.getId('img1'));
    var divImg2 = document.getElementById(this.getId('img2'));
    
//alert(`showGoodAnswers : divImg1.id = ${divImg1.id} - count = ${this.colTouches.nbTouches}`);
    for (var h=0; h < this.colTouches.nbTouches; h++){
        var clTouche = this.colTouches.collection[h];
        if(clTouche.points <= 0){continue;}
//alert(`showGoodAnswers : divImg1.id = ${divImg1.id} - h = ${h}- caption = ${clTouche.caption}`);
        clTouche.getNewDivTouche(divImg1, 1);        
        //clTouche.moveDiv(divImg1, 1);
        
        if(this.data.isDivImg2){
          clTouche.getNewDivTouche(divImg2, 2);        
          //clTouche.moveDiv(divImg2, 2);
        }
        clTouche.tics++;        
    }
  //alert('showGoodAnswers');
    
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
  console.log('===> showGoodAnswers');
    var  currentQuestion = this.question;
    var divImg1 = document.getElementById(this.getId('img1'));
    var divImg2 = document.getElementById(this.getId('img2'));
    
//alert(`showGoodAnswers : divImg1.id = ${divImg1.id} - count = ${this.colTouches.nbTouches}`);
    for (var h=0; h < this.colTouches.nbTouches; h++){
        var clTouche = this.colTouches.collection[h];
        clTouche.tics = getRandom(1,0);        
        if(clTouche.tics > 0){
    //alert(`showGoodAnswers : divImg1.id = ${divImg1.id} - h = ${h}- caption = ${clTouche.caption}`);
            clTouche.getNewDivTouche(divImg1, 1);        
            //clTouche.moveDiv(divImg1, 1);
            
            if(this.data.isDivImg2){
              clTouche.getNewDivTouche(divImg2, 2);        
              //clTouche.moveDiv(divImg2, 2);
            }
        }
    }
  //alert('showGoodAnswers');
    
     return true;
  } 
  

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, contenairId){
    var currentQuestion = this.question;
    var idTogodo = this.getId('togodo');
    this.data.idTogodo = idTogodo;
    disposition += (this.isImage()) ? '-img' : ''; 
    
    switch(disposition){
    case 'disposition-02v':
        var tpl =  `{image1}{image2}<div id="${idTogodo}"></div>`;
        this.data.isDivImg2 = true;
        break;
        
    case 'disposition-02v-img':
        var tpl =  `<table><tr><td>{imageMain}</td>{image1}{image2}<td></td></tr><tr><td colspan='2'><div id="${idTogodo}"></div></td></tr></table`;
        this.data.isDivImg2 = true;
        break;
        
    case 'disposition-02h':
        var tpl = `<table><tr><td>{image1}</td><td>{image2}</td></tr><tr><td colspan="2"><div id="${idTogodo}"></div></td></tr></table>`;
        this.data.isDivImg2 = true;
        break;
        
    case 'disposition-02h-img':
        var tpl = `<table><tr><td colspan='2'>{imageMain}</td></tr><tr><td>{image1}</td><td>{image2}</td></tr><tr><td colspan="2"><div id="${idTogodo}"></div></td></tr></table>`;
        this.data.isDivImg2 = true;
        break;
    
    case 'disposition-01v-img':
        var tpl = `{imageMain}{image1}<div id="${idTogodo}"></div>`;
        this.data.isDivImg2 = false;
        break;
        
    case 'disposition-01h-img':
        var tpl = `<table><tr><td>{imageMain}</td><td>{image1}</td></tr><tr><td colspan='2'><div id="${idTogodo}"></div></td></tr></table>`;
        this.data.isDivImg2 = false;
        break;
        
    case 'disposition-01h':
    case 'disposition-01v':
    default:
        var tpl = `{image1}<div id="${idTogodo}"></div>`;
        this.data.isDivImg2 = false;
        break;
        
    }
    
    var idContenair1 = this.getId('contenair1');
    var idContenair2 = this.getId('contenair2');
    this.data.idPrompt =  this.getId('prompt');
    this.data.idBravo =  this.getId('bravo');
    this.data.idAttempts =  this.getId('attempts');
    this.data.idObjetsFound =  this.getId('objetsFound');

    var btn1 = `<img src='${quiz_config.urlCommonImg}/plus.png'  class='quiz_btnZoom' onclick='zoom_plus(event, ${this.slideNumber});'>`;
    var btn2 = `<img src='${quiz_config.urlCommonImg}/moins.png' class='quiz_btnZoom' onclick='zoom_moins(event, ${this.slideNumber});'>`;
    //   var attempts = this.colTouches.attempts;
    if(currentQuestion.options.zoom > 0) {
        var btnZoom = zoom_getBtnZoom(this.slideNumber,false);      
    }else{
        var btnZoom = zoom_getBtnZoom(this.slideNumber, true);  
    }  

    var promptOnClick = `<table style="text-align: left; width: 100%;" border="0" cellpadding="2"
cellspacing="1">
<tbody>
<tr>
<td style="width:45%;vertical-align: top; text-align: right;">nombre d'essais</td>
<td id='${this.data.idAttempts}' class='findObjects_bravo'>0/0</td>
<td colspan="1" rowspan="2" style="width:148px;vertical-align: middle;">${btnZoom}</td>
</tr>
<tr>
<td style="width:45%;vertical-align: top; text-align: right;">Objets trouvés</td>
<td id='${this.data.idObjetsFound}' class='findObjects_bravo'>0/0</td>
</tr>
</tbody>
</table>`;

    return  `<center><div id='${idContenair1}' class='quiz_div_in_slide'>${promptOnClick}` + tpl + `</div></center>`;
        
}

} // ================== FIN DE LA CLASSE ======================



// ---------------- EVENEMENTS -----------------------------------

/* ***************************************

**************************************** */
function findObjects_isToucheOk(e, slideNumber)
{
console.log("=====>isToucheOk");

    //alert(`findObjects_isToucheOk : x = ${e.offsetX} - y = ${e.offsetY} - Slide n° ${slideNumber}`);
    var clQuestion = quizard[slideNumber];
    var urlSound = '';
    var attempts = clQuestion.colTouches.attempts;
    var PlaySound  = false;  
    
    var clTouche = clQuestion.colTouches.findToucheInXY(e.currentTarget, e.offsetX, e.offsetY, true);
    if(clTouche){
        //alert (`Bingo dans ${clTouche.caption} - x = ${clTouche.x} - y = ${clTouche.y} - e.offsetX = ${e.offsetX} - e.offsetY = ${e.offsetY}`);
        //clTouche.getNewDivTouche(e.currentTarget, 1);
        clTouche.getNewTouche(1);
        clTouche.getNewTouche(2);
        clTouche.tics++;
        urlSound = clQuestion.urlSound +  'bravo.mp3';
        if(PlaySound){playSound(urlSound)};
        if(attempts.winning == clQuestion.colTouches.nbTouches){
        }                    
    }else{
        urlSound = clQuestion.urlSound +  'boum.mp3';
        if(PlaySound){playSound(urlSound)};
    }
 
    
    document.getElementById(clQuestion.data.idAttempts).innerHTML = `${attempts.total}/${attempts.max}`;     
    document.getElementById(clQuestion.data.idObjetsFound).innerHTML = `${attempts.winning}/${attempts.totalWinning}`;    
    


    
    
    //si le nombre d'essai est atteind et si nextSlide=auto on passe au prochan slide   
//    alert(`delai = ${clQuestion.question.options.nextSlideDelai}`) ; 
    if( attempts.winning == clQuestion.colTouches.attempts.totalWinning && clQuestion.question.options.nextSlideDelai*1 > 0){
    //if( attempts.winning == clQuestion.colTouches.nbTouches && clQuestion.question.options.nextSlideDelai*1 > 0){
        zoom_moins(e, slideNumber);   
        message = fo_sprint(clQuestion.question.options.nextSlideMessageWin,attempts, clQuestion.colTouches.attempts.totalWinning);
        quiz_show_avertissement( message ,  clQuestion.question.options.nextSlideDelai*1, clQuestion.question.options.nextSlideBG);
    
    }else if( attempts.total >= attempts.max && clQuestion.question.options.nextSlideDelai*1 > 0){
        zoom_moins(e, slideNumber);  
 
        message = fo_sprint(clQuestion.question.options.nextSlideMessageMax,attempts, clQuestion.colTouches.nbTouches);
        quiz_show_avertissement(message ,  clQuestion.question.options.nextSlideDelai*1, clQuestion.question.options.nextSlideBG);
    }else if( attempts.winning == clQuestion.colTouches.attempts.totalWinning){
        document.getElementById(clQuestion.data.idBravo).innerHTML = `BRAVO ! : ${attempts.winning}/${clQuestion.colTouches.attempts.totalWinning}`; 
        zoom_moins(e, slideNumber);   
    }else if( attempts.total >= attempts.max){
        zoom_moins(e, slideNumber);   
    }
         
}

/* *******************************************
* remplace les code entre accollades par leur valeur
* ********** */
function fo_sprint(exp, attempts, collectionLength){

    exp = exp.replace('{winning}',attempts.winning)
             .replace('{total}', attempts.total)
             .replace('{max}', attempts.max)
             .replace('{length}', collectionLength);

    exp = exp.replace('\/\/', '<br>');
        
    return exp;
}

/* ***************************************

var lasIdTogodo='';
function togodo(exp, idTogodo=null, clearBefore=false){
if(!idTogodo){
   idTogodo = lasIdTogodo;
}
lasIdTogodo = idTogodo;
//alert(idTogodo + ' <-> ' + lasIdTogodo + '<===>' + exp);
    var divTogodo =  document.getElementById(idTogodo);

    if(clearBefore){
        divTogodo.innerHTML = exp;
    }else{
        divTogodo.innerHTML += '<br>' + exp;
    }
}
**************************************** */


/* *************************************** */
/* ********* functions d'interface  ********** */
/* *************************************** */

 /*******************************************************************
  *                     
  * *****************************************************************/
  function initGame(quizmakerMainId, hideInterface) {

    if (typeof window.QuizMaker !== 'undefined') {
        // L'objet est bien là, on peut travailler
        if(hideInterface) {window.QuizMaker.FocusManager.lock();}
        window.QuizMaker.MessageManager.init(quizmakerMainId); // quiz_div_main
    } else {
        // Sinon, on réessaie dans 100ms
        setTimeout(initGame, 100, quizmakerMainId, hideInterface);
    }
}
 /*******************************************************************
  *                     
  * *****************************************************************/
  function endGame() {

    if(quiz.hide_interface) {
        if (typeof window.QuizMaker !== 'undefined') {
            // L'objet est bien là, on peut travailler
            window.QuizMaker.FocusManager.unlock();

        } else {
            // Sinon, on réessaie dans 100ms
            setTimeout(endGame, 100);
        }
    }
    
    if (document.fullscreenElement && quiz.full_screen) {
        document.exitFullscreen();
        document.body.style.overflow = 'scroll';
    }
    
    quiz_config.screenMode = 0;

}

/* ***************************************
* 
* *************************************** */
function activerPleinEcran() {
  const element = document.documentElement; // Cible toute la page

  if (element.requestFullscreen) {
    element.requestFullscreen();
  } else if (element.mozRequestFullScreen) { // Firefox
    element.mozRequestFullScreen();
  } else if (element.webkitRequestFullscreen) { // Chrome, Safari, Opera
    element.webkitRequestFullscreen();
  } else if (element.msRequestFullscreen) { // IE/Edge ancien
    element.msRequestFullscreen();
  }
  //document.body.style.scroll = "hidden";
  document.body.style.overflow = 'hidden';
  //document.html.style.overflow = 'hidden';
  quiz_config.screenMode = 1;
}
/*
function desactiverPleinEcran() {
    if(quiz_config.screenMode == 1){
        endGame()
    }else{
        initGame('quiz_div_main', quiz.hide_interface);
        activerPleinEcran();
    }
}
*/
function desactiverPleinEcran() {
    if (document.fullscreenElement && quiz.full_screen) {
        document.exitFullscreen();
        document.body.style.overflow = 'scroll';
    }
    quiz_config.screenMode = 0;

}
function basculerPleinEcran() {
    if(quiz_config.screenMode == 1){
        desactiverPleinEcran()
    }else{
        activerPleinEcran();
    }
}

/* ***************************************
algorithme qui calcul la position absolue d'un div sur une page html 
**************************************** */
function getAbsolutePosition(element) {
  //return { 'x': 0, 'y': 0 };
  let x = 0;
  let y = 0;
  let currentElement = element;

  while (currentElement && currentElement !== document.body) {
    x += currentElement.offsetLeft;
    y += currentElement.offsetTop;
    currentElement = currentElement.offsetParent;
  }
    //console.log(`getAbsolutePosition :  obSource : ${element.id} - x = ${x} - y = ${y}`);

  return { 'x': x, 'y': y };
}

/* ******************************************

********************************************* */   
function moveWindowPosTo (objId) {
    var container = document.getElementById(objId);
    var newPos = container.offsetTop;
    //console.log('===> moveWindowPosTo : ' + newPos);
    window.scroll(0, newPos);
  }

/* *********************************
*
* */
  function gotoSlideNum (exp) {
    //console.log("gotoSlideNum => " + exp);

    document.getElementById("quiz_goto_slide").value = exp;
    document.getElementById('quiz_btn_goto_slide').click();
    //alert("gotoSlideNum => " + exp);
    //evt.stopPropagation();
    return true;
  }
/* *********************************
*
* */
  function gotoNextSlide () {
    //console.log("gotoSlideNum => " + exp);
    //btnNextSlide.click(); 
    quizDivChronos.stop();
    quizDivChronos.hide();
    document.getElementById('quiz_btn_nextSlide').click();
    return true;
  }
  
/* *******************************************
* affiche un mask qui empeche toute interaction avec le slide courant
* ********** */
function quiz_show_mask(visible, opacity = -1, setCursorWait=false, bgColor=null){
 
    divMask =  document.getElementById('quiz_mask');    
    if(visible){
        //alert('opacity = ' + divMask.style.opacity);
        //if(opacity){divMask.style.opacity = opacity;}
        if(opacity >= 0){
            divMask.style.filter = `grayscale(${opacity}) opacity(${opacity})`;
        }
        
        if(bgColor){divMask.style.backgroundColor = bgColor;}
        divMask.style.visibility = 'visible';
        if(setCursorWait){document.body.style.cursor = 'wait';}
    }else{
        document.body.style.cursor = 'default';
        divMask.style.visibility = 'hidden';
    }
    return true;
}

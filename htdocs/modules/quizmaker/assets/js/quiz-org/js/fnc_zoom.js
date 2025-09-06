/* *************************************** */
/* ********* functions de ZOOM  ********** */
/* *************************************** */

/* ***************************************

**************************************** */
function zoom_getCapsule(htmlSlide, slideNumber,  btnZoomStatus=1, btnNext=false){
console.log('===>zoom_capsule');
    var clQuestion = quizard[slideNumber];

    var idContenair1 = clQuestion.getId('contenair1');
    return  `<div id='${idContenair1}' class='quiz_div_in_slide'>${zoom_getHtmlBtn(slideNumber,btnZoomStatus,btnNext)}` + htmlSlide + `</div>`;

}
/* ***************************************

**************************************** */
function zoom_getHtmlBtn(slideNumber, btnZoomStatus, btnNext=false){
    var clQuestion = quizard[slideNumber];

    switch(btnZoomStatus*1){
        case -1:
        case  1:
            var clsStatus = 'quiz_btnZoomEnable';
            break;
        case  2:
        case -2:
            var clsStatus = 'quiz_btnZoomDisable'; 
            break;
        default : 
            var clsStatus = ''; 
    }
//alert(`===>getHtmlZoomBtn : ${clQuestion.question.question}\nslideNumber = ${slideNumber}\nbtnZoomStatus = ${btnZoomStatus}\nclsStatus = ${clsStatus}`);
 

    var btnZoom = `<img id='${clQuestion.getId('zoom')}' src='${quiz_config.urlCommonImg}/zoom_plus_01.png'  class='quiz_btnZoomAll ${clsStatus}' onclick='zoom_action(event, ${slideNumber},1);' title='Zoom +'>`;
    

    var clsStatus = (btnNext) ? 'quiz_btnZoomEnable' : 'quiz_btnZoomDisable';
    var btnNextSlide = `<img id='${clQuestion.getId('zoomNext')}' src='${quiz_config.urlCommonImg}/zoom_next_01.png' class='quiz_btnZoomAll ${clsStatus}' onclick='zoom_moins(event, ${slideNumber}, true);' title='${quiz_messages.btnNext}'>`;
    
    return `<div class='quiz_divZoomBtn'>${btnZoom} ${btnNextSlide}</div>`;

}

/* ***************************************
btnZoomStatus : -2 : zoom - gris
                -1 : zoom - vert
                 0 : pas de changement
                 1 : zoom + vert
                 2 : zoom + gris
btnNext : -1 = false, 0 = pas de changement , 1 = true
**************************************** */
function zoom_setBtnZoomStatus(slideNumber,  btnZoomStatus, btnNext){
console.log(`===>zoom_setBtnZoomStatus : slideNumber = ${slideNumber} - btnZoomStatus = ${btnZoomStatus}`);
    var clQuestion = quizard[slideNumber];
    
    var btn = document.getElementById(clQuestion.getId('zoom'));
    switch(btnZoomStatus){
        case -2:
            btn.classList.remove("quiz_btnZoomEnable");
            btn.classList.add("quiz_btnZoomDisable");
            btn.src = quiz_config.urlCommonImg + '/zoom_moins_01.png'; 
            break;
        case -1:
            btn.classList.remove("quiz_btnZoomDisable");
            btn.classList.add("quiz_btnZoomEnable");
            btn.src = quiz_config.urlCommonImg + '/zoom_moins_01.png';
            btn.onclick = function () {zoom_action(event, slideNumber,-1);}; 
            break;
        case 1:
            btn.classList.remove("quiz_btnZoomDisable");
            btn.classList.add("quiz_btnZoomEnable");
            btn.src = quiz_config.urlCommonImg + '/zoom_plus_01.png'; 
            btn.onclick = function () {zoom_action(event, slideNumber,1);}; 
            break;
        case 2:
            btn.classList.remove("quiz_btnZoomEnable");
            btn.classList.add("quiz_btnZoomDisable");
            btn.src = quiz_config.urlCommonImg + '/zoom_plus_01.png'; 
            break;
    }
   
    
    var btn = document.getElementById(clQuestion.getId('zoomNext'));
    if(btnNext > 0){
        btn.classList.remove('quiz_btnZoomDisable');
        btn.classList.add('quiz_btnZoomEnable');
    }else if(btnNext < 0){
        btn.classList.remove('quiz_btnZoomEnable');
        btn.classList.add('quiz_btnZoomDisable');
    }
}

/* ***************************************

**************************************** */
function zoom_action(ev, slideNumber, action){
    if(action == 1){
        zoom_plus(ev, slideNumber);
    }else if(action == -1){
        zoom_moins(ev, slideNumber,false);
    }
}
/* ***************************************

**************************************** */
function zoom_plus(ev, slideNumber){
console.log('===>zoom_plus');
    var clQuestion = quizard[slideNumber];
    if(clQuestion.isZoomed == true) {return true;}
//return false;

    var idContenair1 = clQuestion.getId('contenair1');
    //alert(idContenair1);
    var obContenair1 = document.getElementById(idContenair1);
    var absolutePosition = getAbsolutePosition(obContenair1);
    

    //alert( decalageH);

    document.body.appendChild(obContenair1);

    if(!obContenair1.classList.contains('quiz_div_out_slide')){
        obContenair1.classList.add('quiz_div_out_slide');
    }
    
    //calcul du decalage du au centrage de obContenair1
    var decalageH = (document.getElementById('quiz_div_main').offsetWidth - obContenair1.offsetWidth) / 2;
    obContenair1.style.left = (absolutePosition.x+decalageH) + 'px';
    obContenair1.style.top = absolutePosition.y + 'px';


    if(obContenair1.classList.contains('quiz_div_zoom_moins_begin')){
        obContenair1.classList.remove('quiz_div_zoom_moins_begin');
    } 
    if(!obContenair1.classList.contains('quiz_div_zoom_plus_begin')){
        obContenair1.classList.add('quiz_div_zoom_plus_begin');
    }
    clQuestion.isZoomed = true;
//alert('zoom_setBtnZoomStatus(slideNumber,  -1, 1)');
    zoom_setBtnZoomStatus(slideNumber,  -1, 1);    
    ev.stopPropagation();
    quiz_set_mask(true);   
    //setTimeout(zoom_realignWindow,3000,idContenair1);
    return true;
}
/* ***************************************

**************************************** */
function zoom_realignWindow(objId){
    if(quiz.realignWindowPos){moveWindowPosTo(objId);}

}

/* ***************************************

**************************************** */
function zoom_moins(ev, slideNumber, gotoNextSlide = false){
console.log(`===>zoom_moins : gotoNextSlide = ${gotoNextSlide}`);
    var clQuestion = quizard[slideNumber];
    if(clQuestion.isZoomed == false){
        var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';
        btnNextSlide.click();

        return false;
    }
    
console.log('===>zoom_moins : ' + 2);
    var idContenair1 = clQuestion.getId('contenair1');
    var idContenair2 = clQuestion.getId('main');

    var obContenair1 = document.getElementById(idContenair1);
    var obContenair2 = document.getElementById(idContenair2);
console.log('===>zoom_moins : ' + 3);
    /*
    */
     obContenair1.classList.remove('quiz_div_zoom_plus_begin');
     obContenair1.classList.add('quiz_div_zoom_moins_begin');


    setTimeout(zoom_end,1000, idContenair1, idContenair2, gotoNextSlide);
    clQuestion.isZoomed = false;
console.log('===>zoom_moins : ' + 4);
    zoom_setBtnZoomStatus(slideNumber,  1, -1);    
    ev.stopPropagation();

}

function zoom_end(idContenair1, idContenair2, gotoNextSlide){
console.log(`===>zoom_end : gotoNextSlide = ${gotoNextSlide}`);

     var obContenair1 = document.getElementById(idContenair1);
     var obContenair2 = document.getElementById(idContenair2);
     
     //histoire de replacer l'image au centre du slide
     //obContenair2.appendChild(obContenair1);
     obCenter = document.createElement('center');
     obCenter.appendChild(obContenair1);
     obContenair2.appendChild(obCenter);
     
     
    //obContenair1.classList.replace('quiz_div_zoom_moins_begin','quiz_div_in_slide');

    obContenair1.classList.remove('quiz_div_zoom_moins_begin');
    if(obContenair1.classList.contains('quiz_div_out_slide')){
        obContenair1.classList.remove('quiz_div_out_slide');
    }
    obContenair1.classList.add('quiz_div_in_slide');
    obContenair1.style.position = '';
    obContenair1.style.left = '';
    obContenair1.style.top ='';
    quiz_set_mask(false);   
    console.log('zoom_end' + '->' + obContenair1.classList);
    
    if(gotoNextSlide){
        var btnNextSlide = document.getElementById('quiz_btn_nextSlide');
        btnNextSlide.disabled = '';
        btnNextSlide.click();
    }

}
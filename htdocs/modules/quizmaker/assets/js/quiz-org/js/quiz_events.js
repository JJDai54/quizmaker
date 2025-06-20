  function testClick2(exp) {
    var a = 555;
    alert("testClick : exp = " + exp + " | a = " + a);
  }


//------------------------------------------------------------------------
function computeAllScoreEvent(){
  //declenchement du calcul du score
  document.getElementById('quiz_div_all_slides').click();
}
//------------------------------------------------------------------------
function quiz_textareaInput_event(eventName, id, name, slideNumber) {
//alert("quiz_textareaInput_event : " + eventName);
    clQuestion = quizard[slideNumber];
    var obExp = document.getElementById(id);
    
    var exp = clQuestion.data.text;
    
    var obs = getObjectsByName("input", name);
    switch(eventName){

    case "update":
        obs.forEach( (obInput, index) => {
            if(obInput.value != ""){
                exp = exp.replaceAll("{"+(index+1)+"}", obInput.value);
            }
        });
        obExp.innerHTML = exp;
        break;

    case "reload":
        //transferer dans la classe
        break;
    }
}

//------------------------------------------------------------------------
function quiz_textareaListbox_event(e, action, idText, idParentList, slideNumber) {
// this.blob(`===> quiz_textareaListbox_event - ${action} - ${idText} - ${name}`);
    clQuestion = quizard[slideNumber];
    var obExp = document.getElementById(idText);
    var exp = clQuestion.data.text;
    
    var obLists = document.querySelectorAll(`#${idParentList}` + ' select');
    
    switch(action){
    case "update":
        obLists.forEach( (obInput, index) => {
            if(obInput.value != ""){
                exp = exp.replaceAll("{"+(index*1+1)+"}", obInput.value);
            }
        });
        obExp.innerHTML = exp;
        break;

    case "reload":
        //transfeé dans la classe
        break;
    }

    e.stopPropagation();    
    return false;
}

//-----------------------------------------------------
function quiz_deleteValue(id) {
//alert(id);
     var ob = document.getElementById(id);
     index = ob.selectedIndex;
     if (ob.selectedIndex == -1) {
        //alert("Please select any item from the ListBox");
        return true;
     }else{
        //ob.options[Index].remove;// = null;
        ob.options.remove(index)
     }
     return true;
 }
//-----------------------------------------------------
function quiz_basculeValue(idLeft, idRight) {
    //alert (this.name);
     var obLeft  = document.getElementById(idLeft);
     var obRight = document.getElementById(idRight);
     var index = obLeft.selectedIndex;
     if (obLeft.selectedIndex == -1) {
        //alert("Please select any item from the ListBox");
        return true;
     }else{
         obRight.options.add(obLeft.options[index]);
     }
    return true;
}


/* *****************************
* function MoveToTop : deplave un iteme de listbox
* where : deplacement : top, bottom, up, down
* ***************************** */
 function quiz_MoveItemTo(idObj, where, arround = false) {
    var listObj = document.getElementById(idObj);
    
//alert ('quiz_MoveItemTo -> ' + listObj.name + ' -> '  + where);

    var options = listObj.getElementsByTagName("OPTION");
    for (var i = options.length-1 ; i >=0 ; i--) {
        if (options[i].selected) {
            var obSelected = options[i];
            var oldPos = i;
            listObj.removeChild(options[i]);
            continue;
        }
    }
    switch(where){
    case 'top':
          listObj.insertBefore(obSelected, options[0]);
    break;
    
    case 'bottom':
          listObj.insertBefore(obSelected, null);
    break;
    
    case 'up':
        if ((oldPos-1) >= 0)
          listObj.insertBefore(obSelected, options[oldPos-1]);
        else if (arround)
          listObj.insertBefore(obSelected, null);
        else
          listObj.insertBefore(obSelected, options[0]);
    break;
/*
    case 'down':
        if ((oldPos+1) > options.length)
          listObj.insertBefore(obSelected, null);
        else if (arround)
          listObj.insertBefore(obSelected, options[0]);
        else
          listObj.insertBefore(obSelected, options[oldPos+1]);
*/    
    case 'down':
        if ((oldPos+1) <= options.length)
          listObj.insertBefore(obSelected, options[oldPos+1]);
        else if (arround)
          listObj.insertBefore(obSelected, options[0]);
        else
          listObj.insertBefore(obSelected, null);
    break;
    }
    
};

 /* *****************************
 * function MoveToTop : deplace un iteme de listbox
 * where : deplacement : top, bottom, up, down
 * ***************************** */
//  function quiz_MoveItemTo(idObj, where) {
//     listObj = document.getElementById(idObj);
//     
// alert ('quiz_MoveItemTo -> ' + listObj.name + ' -> '  + where);
//     var selected = new Array();
//     var options = listObj.getElementsByTagName("OPTION");
//     for (var i = options.length-1 ; i >=0 ; i--) {
//         if (options[i].selected) {
//             obSelected = options[i]
//             selected.push(options[i]);
//             listObj.removeChild(options[i]);
//             var oldPos=i;
//             var obNext = options[i+1];
//             var obPrevious = options[i-1];
//             continue;
//         }
//     }
//     switch(where){
//     case 'top':
//       for (var i = 0; i < selected.length; i++) {
//           listObj.insertBefore(selected[i], options[0]);
//       }
//     break;
//     
//     case 'bottom':
//       for (var i = 0; i < selected.length; i++) {
//           listObj.insertBefore(selected[i], null);
//       }
//     break;
//     
//     case 'up':
//       for (var i = 0; i < selected.length; i++) {
//             alert (selected[i].text + ' => ' + oldPos);
//           listObj.insertBefore(selected[i], obPrevious);
//       }
//     break;
//     
//     case 'down':
//       for (var i = 0; i < selected.length; i++) {
//           listObj.insertBefore(selected[i], obNext);
//       }
//     break;
//     }
//     
// };

function permuteImg_event(idFrom, idTo, slideNumber) {
// this.blob(`===> quiz_textareaListbox_event - ${eventName} - ${id} - ${name}`);
//alert(`permuteImg_event : \n ${idFrom}\n ${idTo}`);

    //clQuestion = quizard[slideNumber];
    var obImgFrom = document.getElementById(idFrom);
    var obImgTo = document.getElementById(idTo);
    
    var tmp = obImgTo.getAttribute("src");
    obImgTo.setAttribute("src", obImgFrom.getAttribute("src"));
    obImgFrom.setAttribute("src", tmp);
    
}

function setImgFromImg_event(idFrom, idTo, slideNumber) {
// this.blob(`===> quiz_textareaListbox_event - ${eventName} - ${id} - ${name}`);
//alert(`setImgFromImg_event : \n ${idFrom}\n ${idTo}`);

    //clQuestion = quizard[slideNumber];
    var obImgFrom = document.getElementById(idFrom);
    var obImgTo = document.getElementById(idTo);
    
    obImgTo.setAttribute("src", obImgFrom.getAttribute("src"));
}


/* **************************************************************** */
/*       Fonction de Drag And drop sur des images                   */
/* **************************************************************** */
function dad_start(e, isDiv=false){
console.log("===> dad => " + "dad_start");
    e.dataTransfer.effectAllowed = "move";
    if(isDiv){
        e.dataTransfer.setData("text", e.target.parentNode.getAttribute("id"));
    }else{
        e.dataTransfer.setData("text", e.target.getAttribute("id"));
    }
    blob("dad_start : " + e.target.getAttribute("id") + " | " + e.target.getAttribute("src") );
}

/* *********************************** */
function dad_over(e){
console.log("===> dad => " + "dad_over");
//alert('dad_over')
//blob(`dad_over : ${e.dataTransfer.getData("text")} / ${e.currentTarget.getAttribute("id")}`);
    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;

/*
    e.currentTarget.classList.remove('myimg1');
    e.currentTarget.classList.add('myimg2');
*/
    
    e.currentTarget.parentNode.classList.remove('quiz_dad1');
    e.currentTarget.parentNode.classList.add('quiz_dad2');
    
    return false;
}

/* *********************************** */
function dad_drop(e, mode=0){
console.log("===> dad => " + "dad_drop");
    idFrom = e.dataTransfer.getData("text");

/*
    e.currentTarget.classList.remove('myimg2');
    e.currentTarget.classList.add('myimg1');
*/
    
    e.currentTarget.classList.remove('quiz_dad2');
    e.currentTarget.classList.add('quiz_dad1');


    
//     ob = e.dataTransfer.getData("text");
//     e.currentTarget.appendChild(document.getElementById(ob));
    
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
//    alert(obDest.id);
    var obSource = document.getElementById(idFrom);
    alert(obSource.id);
    
    //-----------------------------------------------
    switch(mode){
    case quiz_config.dad_shift_img:
        // decalage d'image par remplacement successif
        shiftImg(obSource,obDest);    
        break;
    case quiz_config.dad_move_img:
        // changement de div contenair
        alert('move');
        e.currentTarget.appendChild(document.getElementById(idFrom));
        break;
    case quiz_config.dad_move_div:
        // echange de deux div dans un div contenair
        replaceDiv(obSource,obDest);    
        break;
    case quiz_config.dad_flip_img:
    default:    
        //echange des deux images
        replaceImg(obSource,obDest);    
        break;
    }

    computeAllScoreEvent();
    //-----------------------------------------------
    
    e.stopPropagation();
    return false;
}

/* *********************************** */
function dad_leave(e){
console.log("===> dad => " + "dad_leave");

/*
   e.currentTarget.classList.remove('myimg2');
   e.currentTarget.classList.add('myimg1');
*/   
   
   e.currentTarget.parentNode.classList.remove('quiz_dad2');
   e.currentTarget.parentNode.classList.add('quiz_dad1');

}
/* *********************************** */
function replaceImg(obSource, obDest, deleteSource = false){

    var srcTmp = obDest.getAttribute("src");
    obDest.setAttribute("src", obSource.getAttribute("src"));
    if (deleteSource){
        obSource.parentNode.removeChild(obSource);
    }else{
        obSource.setAttribute("src", srcTmp);
    }

    
}
/* *********************************** */
function replaceDiv(obSource,obDest){
  var obNext = obSource.nextSibling; 
  var obPrevious = obSource.previousSibling; 
  //if(!obNext) obNext = obDest.previousSibling; 
  
  obSource.parentNode.insertBefore(obSource, obDest);
 if (obNext){
    obSource.parentNode.insertBefore(obDest, obNext);
 }else if(obPrevious){
    obSource.parentNode.insertAfter(obDest);
 }else{
    obSource.parentNode.appendChild(obDest);
 }
 return false; 
}

/* *********************************** */
function shiftImg(obSource, obDest){
 obSource.parentNode.insertBefore(obSource, obDest);
}

function testMouseOver(e){
     blob("testMouseOver : " + e.currentTarget.getAttribute("src"));
}

function testOnClick(e){
    var obSource = document.getElementById(e.currentTarget.getAttribute("id"));
    var obs = document.querySelectorAll(`#${obSource.parentNode.id}` + ' img');    

        for (var i = 0; i < obs.length; i++){
            blob("===> " + i + ' : ' + obs[i].getAttribute("src"))
        }

}

function event_hide_popup_result() {
///zzz.event_hide_popup_result();
//alert(zzz);
//alert("event_hide_popup_result");
     //document.getElementById('btnPopContinue').style.visibility = "hidden";
     var divDisabledAll = document.getElementById('quiz_div_disabled_all');
     divDisabledAll.style.visibility = "hidden";
     //divDisabledAll.style.display = "none";
     
     document.getElementById('quiz_div_popup_answers').innerHTML = "";
     document.getElementById('quiz_div_popup_quest').innerHTML = "";
     document.getElementById('quiz_div_popup_points').innerHTML = "";
     
    return true;
}

function event_pb_gotoSlide(ev) {

    var xy = cumulativeOffset(ev.currentTarget);
    var pbWidth = parseInt(ev.currentTarget.style.width, 10 );
    var posX = (ev.clientX - xy.left )  ;
    var numSlide= Math.floor((posX / pbWidth) * (quizard.length+1));
    //console.log ("numSlide = " + numSlide);
    
    gotoSlideNum(numSlide);    
    ev.stopPropagation();
}

var cumulativeOffset = function(element) {
    var top = 0, left = 0;
    do {
        top += element.offsetTop  || 0;
        left += element.offsetLeft || 0;
        element = element.offsetParent;
    } while(element);

    return {
        top: top,
        left: left
    };
};




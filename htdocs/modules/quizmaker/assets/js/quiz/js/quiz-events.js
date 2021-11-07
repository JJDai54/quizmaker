  function testClick2(exp) {
    var a = 555;
    alert("testClick : exp = " + exp + " | a = " + a);
  }


//------------------------------------------------------------------------
function quiz_textareaInput_event(eventName, id, name, questionNumber) {
//alert("quiz_textareaInput_event : " + eventName);
    clQuestion = quizard[questionNumber];
    var obExp = document.getElementById(id);
    
    var exp = clQuestion.data.text;
    
    var obs = getObjectsByName(name, "input");
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
function quiz_textareaListbox_event(eventName, id, name, questionNumber) {
// console.log(`===> quiz_textareaListbox_event - ${eventName} - ${id} - ${name}`);
    clQuestion = quizard[questionNumber];
    var obExp = document.getElementById(id);
    
    var obExp = document.getElementById(id);
    var exp = clQuestion.data.text;
    
    var obs = getObjectsByName(name, "select");
    
    switch(eventName){
    case "update":
        obs.forEach( (obInput, index) => {
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
/*
*/
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
     index = obLeft.selectedIndex;
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
 function quiz_MoveToTop(idObj, where, arround = false) {
    var listObj = document.getElementById(idObj);
    
//alert ('quiz_MoveToTop -> ' + listObj.name + ' -> '  + where);

    var options = listObj.getElementsByTagName("OPTION");
    for (var i = options.length-1 ; i >=0 ; i--) {
        if (options[i].selected) {
            var obSelected = options[i]
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

// /* *****************************
// * function MoveToTop : deplave un iteme de listbox
// * where : deplacement : top, bottom, up, down
// * ***************************** */
//  function quiz_MoveToTop(idObj, where) {
//     listObj = document.getElementById(idObj);
//     
// alert ('quiz_MoveToTop -> ' + listObj.name + ' -> '  + where);
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

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
console.log(`===> quiz_textareaListbox_event - ${eventName} - ${id} - ${name}`);
    clQuestion = quizard[questionNumber];
    var obExp = document.getElementById(id);
    
    var obExp = document.getElementById(id);
    var exp = clQuestion.data.text;
    
    var obs = getObjectsByName(name, "select");
    
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



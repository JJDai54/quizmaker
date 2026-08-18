/* ************************************************
*
* ************************************************/
function allowDrop(ev) { ev.preventDefault(); }

/* ************************************************
*
* ************************************************/
function equation_drag(ev) { 
    ev.dataTransfer.setData("text", ev.target.id); 
}

/* ************************************************
*
* ************************************************/
function equation_drop(ev) {
//alert(ev.dataTransfer.getData("text"));
    ev.preventDefault();
    var obSource = document.getElementById(ev.dataTransfer.getData("text"));
    var slideNumber = ev.currentTarget.parentNode.getAttribute('slidenumber')*1;  
    var clPlugin = quizard[slideNumber];
    var options = clPlugin.question.options;
    var carre = '';

    var temp = ev.target.innerText;
//       alert(temp + '===' + temp.indexOf('²'))
//       alert(temp.charCodeAt(1) + "===" + '²'.charCodeAt(0) + "===" + quiz_messages.carre.charCodeAt(0))
    if(temp.indexOf(quiz_messages.carre) > 0){ // pas la peine de tester la position 0 qui ne devrait jamais arrivé
      temp = temp.replace(quiz_messages.carre,'');
      var carre = quiz_messages.carre;
      //alert(`carre = ${carre}`)
    }

    keys = Object.keys(inverseCorrespondances);
    //alert(keys.length)
    for(var h = 0; h < keys.length; h++){
    //alert(keys[h])
    console.log(`equation_drop ===> ${temp} <===> {keys[h]}`)
        if(temp.indexOf(keys[h]) > 0){ // pas la peine de tester la position 0 qui ne devrait jamais arrivé
          temp = temp.replace(keys[h],'');
          var carre = keys[h];
        }
    }
    
    
    
    
    var bg = ev.target.style.backgroundImage;

    
    ev.target.innerText = obSource.innerText + carre;
    ev.target.style.backgroundImage = obSource.style.backgroundImage ;
    
    obSource.innerText  = temp;
    obSource.style.backgroundImage = bg;
    ev.target.setAttribute('draggable', 'true');
    ev.target.setAttribute('allowdrop', 'true');
    ev.target.style.cursor = 'grab';
    ev.target.style.color = options.movedColor;
    if(ev.target.innerText != '?'){
    }
    
    ev.target.addEventListener('dragstart', (event) => {
        equation_drag(event);
    });    
    
    if(obSource.innerText == '?' && obSource.parentNode.id == clPlugin.getId('stock')){
        obSource.remove();
    }else{
        obSource.style.color = options.inconnueColor;
    }

    clPlugin.question.options.nbMouvements++;
    clPlugin.endOfGame();
}
                                 

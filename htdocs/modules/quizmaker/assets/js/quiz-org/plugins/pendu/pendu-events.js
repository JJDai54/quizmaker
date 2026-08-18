function pendu_onclick(ev, slideNumber){
    //alert(ev.currentTarget.getAttribute('lettre') + ' = ' + ev.currentTarget.getAttribute('file'));
    if(ev.currentTarget.getAttribute('status') *1 == 0){ return;}
    var clPlugin = quizard[slideNumber]; 
    var options = clPlugin.question.options;

    if(clPlugin.isOk || options.progression >= clPlugin.nbImages){return;}
    var lettre = ev.currentTarget.getAttribute('lettre');
    
    if(options.wordSelected.indexOf(lettre) >= 0){
        promptArr = document.getElementById(clPlugin.getId('divPrompt')).querySelectorAll('img');
        
        promptArr.forEach((objImg) => {
            if (objImg.getAttribute('lettre') == lettre) {
                var h = objImg.src.lastIndexOf('/');
                var newSrc = objImg.src.substring(0, h+1) + objImg.getAttribute('file');
                objImg.src = newSrc;
                objImg.setAttribute('status', '2');
            }
        });
        
    }else{
        options.progression++
        clPlugin.setPenduImg();        
       
    }

        ev.currentTarget.style.opacity = "30%";
        ev.currentTarget.setAttribute('status', '0');
        clPlugin.endOfGame();
        
    
}


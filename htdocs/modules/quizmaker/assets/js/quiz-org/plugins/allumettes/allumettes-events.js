
currentGridSize = 0

function rotate(el, deg) {
    el.dataset.rotation = (parseFloat(el.dataset.rotation) || 0) + deg;
    el.style.transform = `rotate(${el.dataset.rotation}deg)`;
    //comparerEtats();
    computeAllScoreEvent();    
}

function test_position(ev, slideNumber){
    var clPlugin = quizard[slideNumber]; 
    clPlugin.compare_tableaux();
}

/* *************************************
*
* exemple : allumettes_call_events(event, ${this.slideNumber}, 'compare_tableaux')
* exemple : allumettes_call_events(event, ${this.slideNumber}, 'reloadQuestion')
* ***************************************/
function allumettes_call_events(ev, slideNumber, fncName){

    var clPlugin = quizard[slideNumber]; 
    var options = clPlugin.question.options;
    
    switch(fncName){
    case 'compare_tableaux' : clPlugin.compare_tableaux(); break;
    case 'reloadQuestion' :   clPlugin.reloadQuestion(); break;
    case 'addNewAllumettes' : 
        clPlugin.ajouterAllumette(10, 0, 0, 1); 
        //alert(`new allumettes : ${options.nbNewallumettes} / ${options.addAllumettes}`);
        options.nbNewallumettes++;
        if(options.nbNewallumettes >= options.addAllumettes){
            updateButton(clPlugin.getId('add_alumettes'), 0, null, 'allumettes_call_events');
        }    
        updateButton(clPlugin.getId('del_alumettes'), 1, null, 'allumettes_call_events');
        clPlugin.endOfGame();
        break;
    
    case 'delLastAllumette' :  
        var plateau = document.getElementById(clPlugin.idPlateau); 
        const allObj = plateau.querySelectorAll('.allumette');
        allObj[allObj.length-1].remove();
        options.nbNewallumettes--;
        updateButton(clPlugin.getId('add_alumettes'), 1, null, 'allumettes_call_events');
        if(options.nbNewallumettes == 0){
            updateButton(clPlugin.getId('del_alumettes'), 0, null, 'allumettes_call_events');
        }
        clPlugin.endOfGame();
        break;
    }
    
}

/*
*/
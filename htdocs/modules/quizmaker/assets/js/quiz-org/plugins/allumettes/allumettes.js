function getPlugin_allumettes(question, slideNumber){
    return new allumettes(question, slideNumber, 'allumettes')
}  

 /*******************************************************************
  *                     allumettes
  * *****************************************************************/
/*
mettre dans le plugin allumettes les methodes communes et faire hériter les deux autre sur allumettes
*/
class allumettes extends Plugin_Prototype{
name = "allumettes";

/* *************************************
*
* ******** */
buildSlide (bShuffle = true){
    this.boolDog = true;
    return this.getInnerHTML(bShuffle);
    
 }
/* *************************************
*
* ******** */
getInnerHTML(bShuffle = true){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    var html = [];
    html .push(`<div id="${this.getId('main_container')}">`)
    html .push(`<center><div id="${this.idPlateau}" class="allumettes_plateau" oncontextmenu="return false;" >`)
    html .push(`</div></center>`)
    html .push(`</div>`)
    //html .push(`<input type='button' value='${quiz_messages.btnReloadInitial}' onclick="allumettes_call_events(event, ${this.slideNumber}, 'compare_tableaux');">`)
    
    if(options.maxAttempts == 0 || options.addAllumettes > 0){
        html .push(`<br>`)
    }
    if(options.addAllumettes > 0){
        html .push(`<input type='button' id=${this.getId('add_alumettes')} value='${options.msg_addallumettes}' onclick="allumettes_call_events(event, ${this.slideNumber}, 'addNewAllumettes');" style='background:#CCFF00'>`)
        html .push(`<input type='button' id=${this.getId('del_alumettes')} value='${options.msg_delallumettes}' disabled onclick="allumettes_call_events(event, ${this.slideNumber}, 'delLastAllumette');" style='background:#FFCCFF'>`)
    }    
    if(options.maxAttempts == 0){
        html .push(`<input type='button' value='${quiz_messages.btnReloadInitial}' onclick="allumettes_call_events(event, ${this.slideNumber}, 'reloadQuestion');" style='background:#FFBB00'>`)
    }
    //html.push(`<div id=mouchard>mouchard</div>`);
    

    return html.join("\n");
}

/* *************************************
*
* ******** */
prepareData(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
   if (currentQuestion.points*1 == 0) {currentQuestion.points = 1;}
   this.scoreMaxiBP = currentQuestion.points*1;   
   //alert(`prepareData : currentQuestion.points = ${currentQuestion.points}`)
   this.scoreMiniQP = 0;   
   this.memoireArr = [null, null, null, null, null];   
   this.idPlateau = this.getId('plateau');
   
   if(!options.addAllumettes){options.addAllumettes = 0;}
    options.nbNewallumettes = 0;

   options.maxMouvements = options.maxMouvements*1;
   options.nbMouvements = 0
   options.maxAttempts = options.maxAttempts*1;
   options.nbAttempts = 0;
}
//---------------------------------------------------
onEnter() {
    super.onEnter();
}
//---------------------------------------------------
onFinalyse (){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    super.onFinalyse ();
    this.computeScore=true;
    var plateau = document.getElementById(this.idPlateau);
    

//function allumetes_appliquerConfigFromPlugin() {
    plateau.style.setProperty('--plateau-w', options['gameWidth'] + 'px');
    plateau.style.setProperty('--plateau-h', options['gameHeight'] + 'px');
    plateau.style.setProperty('--grid-size', options['gridSize'] + 'px');
    plateau.style.setProperty('--match-h',   options['allumetteHeight']  + 'px');
    plateau.style.setProperty('--match-w',  options['allumetteWidth']  + 'px');
    plateau.style.setProperty('--background-color',  options['backgroundColor']);
    
    //alert(`rotation = ${options.rotation}`);
    //transfert d variable pour garder les nom d'origine
    options.rotationAngle = 360 / options.rotation;
    currentGridSize  = parseInt(options.gridSize*1) ;
    options.gridSize  = parseInt(options.gridSize*1) ;
    options.nbNewallumettes = 0;

    
    //currentGridSize = parseInt(document.getElementById('g').value);
    //rotationAngle = parseFloat(document.getElementById('rot').value);
    this.restaurerFromPlugin();
//}

    
}

restaurerFromPlugin() {
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
    for (var id=0; id < currentQuestion.answers.length; id++){
        //alert(currentQuestion.answers[id].proposition);
      var data = JSON.parse(currentQuestion.answers[id].proposition);
      this.memoireArr[id] = data;
    }
    var id = 0;
    this.restaurer(id);
    

}

/* *************************************
*
* ******** */

getAllPropositions (flag = 0){
    return "allumettes"
 }


/* *************************************
*
* ******** */
getScoreByProposition ( answerContainer = ''){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
var score = 0;
    var idSolution = this.compare_tableaux();
    //console.log (`===>getScoreByProposition : solution = ${idSolution} `);
    if(idSolution*1 > 0){
        score = currentQuestion.points;  
        //alert(`getScoreByProposition : points = ${score} - ${currentQuestion.points}`);      
    }
    //alert(`getScoreByProposition : idSolution = ${idSolution}`);      
   //if (score == this.scoreMaxiQQ){zoom_moins_event(null, this.slideNumber);}   
   //this.getMouvements();
   return score;

  }

 
/* ************************************
*
* **** */
getMouvements()
{
  var currentQuestion = this.question;   
    var nbMouvements = 0;  
  
    var plateau = document.getElementById(this.idPlateau); 
  
    var nbMouvements = 0;
    //alert(plateau.querySelectorAll('.allumette[data-status="1"]').length)
    plateau.querySelectorAll('.allumette[data-status="1"]').forEach(a => { 
   console.log('===> a.dataset.nbMouvements = ' +  a.dataset.nbMouvements);
    
        if(a.dataset.nbMouvements*1 > 0) {
            nbMouvements++;
        }
    });
    //alert(`Nombre d'allumettes déplacées : ${nbMouvements}`); 
    //comparerEtats(); 
    return nbMouvements;

/*

// 1. On récupère les éléments avec le bon statut
var allItems = plateau.querySelectorAll('.allumette[data-status="1"]');

// 2. On transforme le NodeList en tableau et on filtre sur la valeur numérique
var items = Array.from(allItems).filter(item => {
    // On convertit l'attribut en nombre (le '+' ou Number() font la conversion)
    return parseInt(item.getAttribute('data-nbMouvements')*1) > 0;
});   
return items.length;   
*/
}

/* ************************************
*
* **** */
endOfGame(div = null){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
   if(div) {div.dataset.nbMouvements++;}
   options.nbMouvements = this.getMouvements(); 
        

   if(this.getScoreByProposition() > 0){
        this.show_avertissement_WL (true);
        return;      
   }else if(options.nbMouvements > options.maxMouvements && options.maxMouvements != 0){      
        options.nbAttempts++;
        if(options.nbAttempts >= options.maxAttempts && options.maxAttempts != 0){ 
            this.show_avertissement_WL (false);
        }else if(options.maxAttempts == 0){
            options.nbMouvements = 0
            quiz_show_avertissement (options.msg_replay, options.msg_duree, options.msg_background, false);            
            this.reloadQuestion();
        
        }else{
            options.nbMouvements = 0
            var exp = options.msg_replay + "\n" + options.msg_remaining.replace("{nbAttempts}", (options.maxAttempts-options.nbAttempts));
            //var exp = "togodo";
            quiz_show_avertissement (exp, options.msg_duree, options.msg_background, false);            
            this.reloadQuestion();
        }
        
       
        
        
        
   }
   
      
//    console.log('===> div.dataset.nbMouvements = ' +  div.dataset.nbMouvements)
//    console.log('===>nbMouvements = ' + options.nbMouvements)

/*
   options.nbMouvements++;
   console.log(`onmousedown :\n nbMouvements = ${options.nbMouvements} / ${options.maxMouvements}\n nbAttempts = ${options.nbAttempts} / ${options.maxAttempts}`);
   console.log('===>div.dataset.nbMouvements = ' + div.dataset.nbMouvements)
   this.getMouvements();
*/   

}

/* ************************************
*
* **** */
setMouvements2(div = null){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    
   if(div) {div.dataset.nbMouvements++;}
   options.nbMouvements = this.getMouvements(); 

   if(this.getScoreByProposition() > 0){
        quiz_show_avertissement (options.msg_nextslide_winner, options.messagesDelai, options.messagesBG, true);      
        return;      
   }else if(options.nbMouvements > options.maxMouvements && options.maxMouvements != 0){      
        options.nbAttempts++;
        if(options.nbAttempts >= options.maxAttempts && options.maxAttempts != 0){        
            quiz_show_avertissement (options.msgGameover, options.messagesDelai, options.messagesBG, true);            
        }else if(options.maxAttempts == 0){
            options.nbMouvements = 0
            quiz_show_avertissement (options.msgReplay, options.messagesDelai, options.messagesBG, false);            
            this.reloadQuestion();
        
        }else{
            options.nbMouvements = 0
            var exp = options.msgReplay + "\n" + options.msgremaining.replace("{nbAttempts}", (options.maxAttempts-options.nbAttempts));
            quiz_show_avertissement (exp, options.messagesDelai, options.messagesBG, false);            
            this.reloadQuestion();
        }
        
       
        
        
        
   }
   
      
//    console.log('===> div.dataset.nbMouvements = ' +  div.dataset.nbMouvements)
//    console.log('===>nbMouvements = ' + options.nbMouvements)

/*
   options.nbMouvements++;
   console.log(`onmousedown :\n nbMouvements = ${options.nbMouvements} / ${options.maxMouvements}\n nbAttempts = ${options.nbAttempts} / ${options.maxAttempts}`);
   console.log('===>div.dataset.nbMouvements = ' + div.dataset.nbMouvements)
   this.getMouvements();
*/   

}

/* ************************************
*
* **** */
reloadQuestion(bShuffle = true){
    super.reloadQuestion(bShuffle);
    this.onFinalyse();
//options.nbAttempts = 0;   
}

/* *************************************
*
* ******** */
showGoodAnswers ()
  {
    let  currentQuestion = this.question;
    var answers = currentQuestion.answers;
    var arr = [];
    for(var k = 1; k < answers.length; k++){
        if (answers[k]){
            arr.push(k);
        }
    }
    var idx = getRandom(arr.length);
    this.restaurer(arr[idx]);
    computeAllScoreEvent();    
    return true;
  
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
  var currentQuestion = this.question;   

    var plateau = document.getElementById(this.idPlateau); 
    plateau.querySelectorAll('.allumette').forEach(a => { 
        var r = getRandom(360);
        a.dataset.rotation = 0; 
        a.style.transform = `rotate(${r}deg)`;
    });
    computeAllScoreEvent(); 
    //comparerEtats(); 
}
  
///////////////////////////////
restaurer(id, plateaauId) { 
    //const data = (id === 1) ? this.memoireArr[0] : this.memoireArr[1];
    const data = this.memoireArr[id];
    if (!data) return;
    document.getElementById(this.idPlateau).innerHTML = '';
    data.forEach(a => this.ajouterAllumette(a.x, a.y, a.rotation, a.status));

    if(id != 0) {
        const data0 = this.memoireArr[0];
        data0.filter(a => a.status == 0)
            .forEach(b => this.ajouterAllumette(b.x, b.y, b.rotation, b.status));
    }

    //comparerEtats();
}

 
ajouterAllumette(x = 0, y = 0, rot = 0, status = 1) {
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    const div = document.createElement('div');
    div.className = 'allumette';
    div.style.left = x + 'px'; div.style.top = y + 'px';
    div.dataset.rotation = rot;
    div.dataset.nbMouvements = 0;
    div.style.transform = `rotate(${rot}deg)`;
    div.dataset.status = status;
        div.oncontextmenu = "oncontextmenu='return false;'"; //"seContextmenu();

    
    if(status*1 == 1){
        div.classList.add('allumette_mobile');
        var bgTete = 'red';
    }else{
        div.classList.add('allumette_fixe');
        var bgTete ='black';
    }
    div.innerHTML = `<div class="allumettes_tete" style='background:${bgTete}' oncontextmenu='return false;'></div>`
                  + `<div class="allumettes_corps" oncontextmenu='return false;'></div>`
                  + `<button class="allumettes_del_btn" onclick="this.parentElement.remove(); comparerEtats();">×</button>`;

        /* *******************************
        * onmousedown
        * ********************************* */
    div.onmousedown = (e) => {
        //if (deleteMode) return;
        e.stopPropagation();

        
        if (status == 0) { e.preventDefault(); return; }
        // Rotation clic droit (inverse)
        if (e.button === 2 && options.allowRotation == 1) { 
            e.preventDefault(); 
            rotate(div, -options.rotationAngle); 
            this.endOfGame(div);
            return; 
        }
        
        let isMoving = false;
        var newDiv = div; //e.currentTarget;
        var alRect = newDiv.getBoundingClientRect();
        
            var plateau = document.getElementById(this.idPlateau);
        
            draggedElement = e.currentTarget;
            const rectPlateau = plateau.getBoundingClientRect();
            
            // On calcule l'offset par rapport au coin du plateau
            startOffsetX = e.clientX - rectPlateau.left - parseFloat(draggedElement.style.left);
            startOffsetY = e.clientY - rectPlateau.top - parseFloat(draggedElement.style.top);

        /* *******************************
        * move
        * ********************************* */
        const move = (e) => {
    if(!isMoving){
    }
            isMoving = true;

        var currentGridSize = options.gridSize;
        
        const rectPlateau = plateau.getBoundingClientRect();
        
        let targetX = e.clientX - rectPlateau.left - startOffsetX;
        let targetY = e.clientY - rectPlateau.top - startOffsetY;

        // Magnétisme
        targetX = Math.round(targetX / currentGridSize) * currentGridSize;
        targetY = Math.round(targetY / currentGridSize) * currentGridSize;
        
        //deplacement interdit en dehors du plateau
/*
        if (targetX < newDiv.offsetWidtht/2 - currentGridSize) {targetX = -newDiv.offsetWidtht/2 + currentGridSize;}       
        else if(targetX > (rectPlateau.width -newDiv.offsetWidtht/2 - currentGridSize)) {targetX = (rectPlateau.width -newDiv.offsetWidtht/2 - currentGridSize);}
*/
        if (targetX < 0) {targetX = 0;} 
        else if(targetX > (rectPlateau.width - alRect.width/2 - currentGridSize)) {targetX = rectPlateau.width - alRect.width/2 - currentGridSize;}


        if (targetY < -newDiv.offsetHeight/2 + currentGridSize) {targetY = -newDiv.offsetHeight/2 + currentGridSize;}       
        else if(targetY > (rectPlateau.height -newDiv.offsetHeight/2 - currentGridSize)) {targetY = (rectPlateau.height -newDiv.offsetHeight/2 - currentGridSize);}

setMouchard(targetX, targetY);
        draggedElement.style.left = targetX + 'px';
        draggedElement.style.top  = targetY + 'px';
 
        };

/*
// --- NOUVEAU : Blocage dans les limites du plateau ---
    const maxX = rectPlateau.width - draggedElement.offsetWidth;
    const maxY = rectPlateau.height - draggedElement.offsetHeight;

    // On force les valeurs entre 0 et le maximum possible
    targetX = Math.max(0, Math.min(targetX, maxX));
    targetY = Math.max(0, Math.min(targetY, maxY));*/        
        /* *******************************
        * mouseup
        * ********************************* */
        const up = (e) => {
            document.removeEventListener('mousemove', move);
            // Rotation clic gauche (simple clic uniquement)
            if (!isMoving && e.button === 0  && options.allowRotation == 1) rotate(div, options.rotationAngle);
            //comparerEtats();
            this.endOfGame(div);
        };
        document.addEventListener('mousemove', move);
        document.addEventListener('mouseup', up, {once: true});
    };
    div.oncontextmenu = (e) => e.preventDefault();
    document.getElementById(this.idPlateau).appendChild(div);
    //comparerEtats();
}

 

/* **********************************************
* renvoie l'id de la solution qui correspond à la position actuelle des allumettees.
* Si aucune solutioni ne correspond, renvoie 0
* le renvoie d'un booleen ne permet pas de tracer correctement les solutions
* *********************************************** */
compare_tableaux(){
    let  currentQuestion = this.question;
    var options = currentQuestion.options;
    var resultat = 0;
    var bolOk = false;
    var solution = '';    
    var plateau = document.getElementById(this.idPlateau); 
    const data = Array.from(plateau.querySelectorAll('.allumette')).map(a => ({x: parseInt(a.style.left), y: parseInt(a.style.top), rotation: parseFloat(a.dataset.rotation), status: a.dataset.status}));
    
    //var solution = JSON.parse(this.memoireArr[id];
    for(var k = 1; k < currentQuestion.answers.length; k++){
        var solution = JSON.parse(currentQuestion.answers[k].proposition);
        bolOk = sontEquivalents2(data, solution);
        if(bolOk) {
            resultat = k;
            break;
        }
 
    }
    
            //alert(`compare_tableaux ${this.idPlateau}: resultat = ${resultat}`);
    console.log(`===>Les tableaux sont équivalents : ${resultat}`, bolOk);
    return resultat;
}

} // ----- fin de la class ------

//////////////////////////////////////////////////

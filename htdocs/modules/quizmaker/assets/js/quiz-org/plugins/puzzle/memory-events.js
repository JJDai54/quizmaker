
/* *********************************************** */
/* ----------- Drag And Drop Events ----------------*/
/* *********************************************** */



/* *************************************
      obMemory.setAttribute('t_clicks', 0);
      obMemory.setAttribute('t_winner', 0);
      obMemory.setAttribute('t_image', 0);
      obMemory.setAttribute('t_found', 0);

dblAction :  0 : pas d'action, l'utilisateur n'a pas encore cliquer le nombre de doublons
             1 : la tempo est active apres que l'utilisateur ai cliqué sur le nombre d'images=doublons et qu'elles ne sont pas identiques
            -1 : pas de tempo, les image découvertent seront masquées au prochain click souris
logimg : 0 : pas d'affichage, 
         1 : affiche le numéro et status de des images découverte ou non. a utiliser en mode developpement             
**************************************** */
const logimg = 0;

function memory_onclick(ev, slideNumber){
//ev.preventDefault();
// return false;
// ev.stopPropagation();
    currentTarget = ev.currentTarget;
    var obMemory = currentTarget.parentNode.parentNode;    
    //elimination des cas ou on ne fais rien
    //la tempo est en cours, voir memory_all_hidden
    if(obMemory.getAttribute('dblAction')*1 == 1){return false;} 
    if(obMemory.getAttribute('dblAction')*1 == -1){memory_all_hidden(obMemory.id)}
     
    //L'image est déjà découverte
    var status = currentTarget.getAttribute('status');
    if (status > 0) {
        return false;
    }
    //----------------------------------------------------------
    var p_image = currentTarget.getAttribute('numImage')*1;    
    
    var imgUrl   = obMemory.getAttribute('imgUrl');    
    var clicks   = (obMemory.getAttribute('t_clicks')*1)+1;
    var doublons =  obMemory.getAttribute('doublons')*1;    
    var t_image  = obMemory.getAttribute('t_image');
    
    var tMoucard = [];
    tMoucard.push(`clicks = ${clicks}`);
    tMoucard.push(`p_image = ${p_image}`);
    tMoucard.push(`t_image = ${t_image}`);
    //----------------------------------------------------------
    currentTarget.style.backgroundImage = `url('${imgUrl}')`;
    currentTarget.setAttribute('status', 1);
    
    if(clicks == 1){
        casTraite = 
        obMemory.setAttribute('t_image', p_image);
        obMemory.setAttribute('t_win', 'true');
        obMemory.setAttribute('t_clicks', clicks);
        
    }else if(clicks < doublons){
        if(t_image != p_image){
            obMemory.setAttribute('t_win', 'false');
        }
        obMemory.setAttribute('t_clicks', clicks);
    }else{
        if(t_image != p_image){
            obMemory.setAttribute('t_win', 'false');
        }
        obMemory.setAttribute('t_clicks', clicks);
        
        if(obMemory.getAttribute('t_win') == 'true'){
// alert(`t_win = ${obMemory.getAttribute('t_win')}`);       
            memory_set_status(obMemory.id, 1, 2);
            showMouchard(obMemory.id, 'green');
            obMemory.setAttribute('t_clicks', 0);
            //alert('zzzzzzzzzz');
        }else{
            obMemory.setAttribute('win', false);
            obMemory.setAttribute('t_clicks', 0);
            obMemory.setAttribute('t_image', '');
            var tempo = obMemory.getAttribute('tempo')*1;
            if(tempo == 0){
              obMemory.setAttribute('dblAction', -1);
            }else{
              obMemory.setAttribute('dblAction', 1);
              //document.body.style.cursor = 'wait';
              quiz_show_mask(true, 0.25, true);              
              //attend un délai pour masquer de nouveau les images lorsqu'elles ne sont pas identiques
              setTimeout(memory_all_hidden, obMemory.getAttribute('tempo')*1000, obMemory.id, true);
            }
        }
    }

   
    console.log(`memory_get_found = ${memory_get_found(obMemory.id)} - t_pieces = ${obMemory.getAttribute('t_pieces')}`);
        var clQuestion = quizard[obMemory.getAttribute('slideNumber')*1];
        var options = clQuestion.question.options;
    if(memory_get_found(obMemory.id) == obMemory.getAttribute('t_pieces')*1
    && (options.nextSlideDelai > 0)){
        var msg = (clQuestion.getScoreByProposition() == clQuestion.scoreMaxiBP) ? options.nextSlideMessageWinner : options.nextSlideMessageLooser;
        msg = fo_sprint(msg);
        quiz_show_avertissement (msg, options.nextSlideDelai, options.nextSlideBG);
    }
//    clQuestion.updateBtnNext();
//     tMoucard.push(`found = ${obMemory.getAttribute('t_found')}`);
//     document.getElementById('mouchard').innerHTML = tMoucard.join('<br>');
    computeAllScoreEvent();
    return true;
}

/* *************************************

**************************************** */
function memory_set_status(memoryId, fromStatus = 1, toStatus = 2){
    var obMemory = document.getElementById(memoryId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    
    for(var h = 0; h < allPieces.length; h++){
        obPiece = allPieces[h];
        if((obPiece.getAttribute('status')*1) == fromStatus){
            obPiece.setAttribute('status', toStatus);
        }
        
        showMouchard(memoryId, 'white');
    }
}
/* *************************************

**************************************** */
function memory_all_hidden(memoryId){
    var obMemory = document.getElementById(memoryId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    var piecesfound = 0;
    
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('status') < 2){
            allPieces[h].style.backgroundImage = '';
            allPieces[h].setAttribute('status', 0);
        }else{
            piecesfound++;
        }
    }
    obMemory.setAttribute('t_found', piecesfound);
    showMouchard(memoryId, 'red');
    obMemory.setAttribute('dblAction', 0);
    //document.body.style.cursor = 'default';
    quiz_show_mask(false);              

              

}
/* *************************************

**************************************** */
function memory_all(memoryId, bolHidden = false){
    var obMemory = document.getElementById(memoryId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    var piecesfound = 0;
    var imgUrl = `url('${obMemory.getAttribute('imgUrl')}')`;
    
    for(var h = 0; h < allPieces.length; h++){
        obPiece = allPieces[h];
        if(bolHidden){
            obPiece.style.backgroundImage = '';
            obPiece.setAttribute('status', 0);
        }else{
            obPiece.style.backgroundImage = imgUrl;   
            obPiece.setAttribute('status', 2);
            piecesfound++;
         //alert(`${h} => numPiece : ${obPiece.getAttribute('numPiece')}<br>${imgUrl}` );
        }
    }
    obMemory.setAttribute('t_found', 0);
    showMouchard(memoryId, 'magenta');
    obMemory.setAttribute('dblAction', 0);

}

/* *************************************

**************************************** */
function memory_get_found(memoryId){
    var obMemory = document.getElementById(memoryId);
    var doublons = obMemory.getAttribute('doublons');
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    var piecesfound = 0;
    
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('status') == 2){
            piecesfound++;
        }
    }
    
    //il ne reste plus qu'en jeu d'image a découvrir inutile de continuer pour rien
    //on considère que le dernier jeu d'image a ete cliqué
    if(piecesfound == allPieces.length - doublons){
        memory_all(memoryId, false);
        piecesfound = allPieces.length;
    }
    obMemory.setAttribute('t_found', piecesfound);
    return piecesfound;
}
/* *************************************

**************************************** */
function showMouchard(memoryId, foreColor = 'white'){
    if(logimg == 0) return false;
    var obMemory = document.getElementById(memoryId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    
    for(var h = 0; h < allPieces.length; h++){
        obPiece = allPieces[h];
        t = [];
        t.push(`${foreColor}` );
        t.push(`numImg : ${obPiece.getAttribute('numImage')}` );
        t.push(`status = ${obPiece.getAttribute('status')}` );
        
        obPiece.innerHTML = `<span style="color:${foreColor};">` + t.join('<br>') + '</span>';
    }
}


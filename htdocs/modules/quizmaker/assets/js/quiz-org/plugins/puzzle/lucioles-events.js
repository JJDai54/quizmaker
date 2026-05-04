
/* *********************************************** */
/* ----------- Drag And Drop Events ----------------*/
/* *********************************************** */



/* *************************************
      obMemory.setAttribute('t_clicks', 0);
      obMemory.setAttribute('t_winner', 0);
      obMemory.setAttribute('t_image', 0);
      obMemory.setAttribute('t_found', 0);

dblAction :  0 : pas daction, l'utilisateur n'a pas encore cliquer le nombre de doublons
             1 : la tempo est active apres que l'utilisateur est cliquer sur le nombre d'images=doublons et qu'elles ne sont pas identiques
            -1 : pas de tempo, les image découvertent seront masquées au prochain click souris
logimg : 0 : pas d'affichage, 
         1 : affiche le numéro et status de des images découverte ou non. a utiliser en mode developpement             
**************************************** */
const lucioles_logimg = 0;
//var timeOutId = 0;

//     if(obMemory.getAttribute('dblAction')*1 == 1){
//         clearTimeout(timeOutId);
//         lucioles_all_hidden(obMemory.id, true);
//         //return false;
//     } 


function lucioles_onclick(ev, slideNumber){
//ev.preventDefault();
// return false;
// ev.stopPropagation();
    currentTarget = ev.currentTarget;
    var obMemory = currentTarget.parentNode.parentNode;    
    //elimination des cas ou on ne fais rien
    //la tempo est en cours, voir lucioles_all_hidden
    if(obMemory.getAttribute('dblAction')*1 == 1){return false;} 
    if(obMemory.getAttribute('dblAction')*1 == -1){lucioles_all_hidden(obMemory.id);}
     
    //L'image est déjà découverte
    var status = currentTarget.getAttribute('status');
    if (status > 0) return false;
    //----------------------------------------------------------
    var p_image = currentTarget.getAttribute('numImage')*1;    
    
    var imgUrl   = currentTarget.getAttribute('imgUrl');    
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
            lucioles_set_status(obMemory.id, 1, 2);
            lucioles_showMouchard(obMemory.id, 'green');
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
              quiz_show_mask(true, 0.25, true);              
              //attend un délai pour masquer de nouveau les images lorsqu'elles ne sont pas identiques
              setTimeout(lucioles_all_hidden, obMemory.getAttribute('tempo')*1000, obMemory.id, true);
            }
        }
    }

   
    console.log(`lucioles_get_found = ${lucioles_get_found(obMemory.id)} - t_pieces = ${obMemory.getAttribute('t_pieces')}`);
        var clQuestion = quizard[obMemory.getAttribute('slideNumber')*1];
        var options = clQuestion.question.options;
    if(lucioles_get_found(obMemory.id) == obMemory.getAttribute('t_pieces')*1
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
function lucioles_set_status(luciolesId, fromStatus = 1, toStatus = 2){
    var obMemory = document.getElementById(luciolesId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    
    for(var h = 0; h < allPieces.length; h++){
        obPiece = allPieces[h];
        if((obPiece.getAttribute('status')*1) == fromStatus){
            obPiece.setAttribute('status', toStatus);
        }
        
        lucioles_showMouchard(luciolesId, 'white');
    }
}
/* *************************************

**************************************** */
function lucioles_all_hidden(luciolesId){
    var obMemory = document.getElementById(luciolesId);
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
    lucioles_showMouchard(luciolesId, 'red');
    obMemory.setAttribute('dblAction', 0);
    quiz_show_mask(false);              

}
/* *************************************

**************************************** */
function lucioles_all(luciolesId, bolHidden = false){
    var obMemory = document.getElementById(luciolesId);
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    var piecesfound = 0;
    
    for(var h = 0; h < allPieces.length; h++){
        obPiece = allPieces[h];
    var imgUrl = `url('${obPiece.getAttribute('imgUrl')}')`;
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
    lucioles_showMouchard(luciolesId, 'magenta');
    obMemory.setAttribute('dblAction', 0);

}

/* *************************************

**************************************** */
function lucioles_get_found(luciolesId){
    var obMemory = document.getElementById(luciolesId);
    var doublons = obMemory.getAttribute('doublons');
    var selecteur='div[piece="true"]';
    allPieces = obMemory.querySelectorAll(selecteur);
    var piecesfound = 0;
    
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('status') == 2){
            piecesfound++;
            //allPieces[h].setAttribute('cursor', 'not-allowed');
            allPieces[h].style.cursor = 'not-allowed';
        }else{
            allPieces[h].style.cursor = 'grab';
        }
    }
    
    //il ne reste plus qu'en jeu d'image a découvrir inutile de continuer pour rien
    //on considère que le dernier jeu d'image a ete cliqué
    if(piecesfound == allPieces.length - doublons){
        lucioles_all(luciolesId, false);
        piecesfound = allPieces.length;
    }
    
    obMemory.setAttribute('t_found', piecesfound);
    return piecesfound;
}
/* *************************************

**************************************** */
function lucioles_showMouchard(luciolesId, foreColor = 'white'){
    if(lucioles_logimg == 0) return false;
    var obMemory = document.getElementById(luciolesId);
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



/* *************************************
      obMemosuite.setAttribute('t_clicks', 0);
      obMemosuite.setAttribute('t_winner', 0);
      obMemosuite.setAttribute('t_image', 0);
      obMemosuite.setAttribute('t_found', 0);

dblAction :  0 : pas daction, l'utilisateur n'a pas encore cliquer le nombre de newDoublons
             1 : la tempo est active apres que l'utilisateur est cliquer sur le nombre d'images=newDoublons et qu'elles ne sont pas identiques
            -1 : pas de tempo, les image découvertent seront masquées au prochain click souris
logimg : 0 : pas d'affichage, 
         1 : affiche le numéro et status de des images découverte ou non. a utiliser en mode developpement             
**************************************** */
const memosuite_logimg = 0;

function memosuite_onclick(ev, slideNumber){
    //console.log(`--------------------------------------------------`);
    var currentTarget = ev.currentTarget;
    var obSlide = quizard[slideNumber];
    var options = quizard[slideNumber].question.options;
    obCarteId = obSlide.getId('carte', options.numCarte);
    var obCarte = document.getElementById(obCarteId);    
    var clGridImg = obSlide.clGridImg;

    document.getElementById(obSlide.getId('suite')).classList.remove('memosuite_shake');
    
    //aucune sequence n'a encore été généré, envoi message et sortie
    if(!options.newSuite){
        //show_message(options.msg_getSeqence); 
        quiz_show_avertissement (options.msg_getSeqence, options.msg_duree, options.msg_background, false);
        return true;
    }    
    //alert(`options.nbAttempts = ${options.nbAttempts} / ${options.maxAttempts}`);
    
    //--------------------------------------------------------
    //le nombre d'images sélectionnee est inférieur au nombre d'image de la sequence
    //affichage dans la sequence de l'image cliquée et incremantation de numCarte
    if(options.numCarte < options.nbImages){
        clGridImg.setImage(currentTarget, obCarte);
        options.numCarte = (options.numCarte + 1); 
    }
    
    //le nombre d'images est ok, et en plus la séquence est exacte
    if(options.numCarte >= options.nbImages && obSlide.isScoreOk()){
        quiz_show_avertissement (options.msg_bingo, options.msg_duree, options.msg_background, options.msg_nextslide_duree> 0 );
        return true;
    }
    
    //le nombre d'image est ok, mais la séquence est erronée
    if(options.numCarte >= options.nbImages && !obSlide.isScoreOk()){
        document.getElementById(obSlide.getId('suite')).classList.add('memosuite_shake');
        options.nbAttempts++;
        
        // si le nombre d'essais est dépassé affiche le message pour aller à la suitemessage
        if(options.nbAttempts >= options.maxAttempts && options.maxAttempts > 0){
            setTimeout(memosuite_replay, 500, obSlide, false);
            quiz_show_avertissement (options.msg_attemptsOut, options.msg_duree, options.msg_background, options.msg_nextslide_duree > 0);
            return;
        }
        quiz_show_avertissement (options.msg_lost, options.msg_duree, options.msg_background, false);
        setTimeout(memosuite_replay, 800, obSlide);
        return true;
    }
// alert(`options.numCarte  = ${options.numCarte } - options.nbImages = ${options.nbImages}`)   
    
    //normalement dans tous les autrescas:
     if (options.numCarte >= options.nbImages){
        //options.numCarte = 0; 
        //obSlide.clear_sequence();
        //alert ('Fin');

        
     }
    
    
}



function memosuite_replay(obSlide, replay=true){

        obSlide.clear_sequence();
        if(replay) {memosuite_play(null, obSlide.slideNumber);}
        updateButton(obSlide.getId('playBtn'), 0, 'red', 'memosuite_play' );        

}



/* ******************************************

********************************************* */
function memosuite_play(ev, slideNumber){
    var obSlide = quizard[slideNumber];
    obSlide.question.options.numCarte = 0;
    obSlide.clear_sequence();
    
     var clGridImg = obSlide.clGridImg;
//console.log(`===> mode = ${obSlide.question.options.mode}`);     
     //choix du mode de jeu
     if(!obSlide.question.options.newSuite || obSlide.question.options.mode == 2){
        // Il n'y a pas encore de séquence de générer ou bien il faut en générer une à chaque essai
        var newSuite = clGridImg.get_newSuite(obSlide.question.options.nbImages);
        obSlide.question.options.newSuite = newSuite;
     }else{
        //sinon on recuper la séquence déjà générée
        newSuite = obSlide.question.options.newSuite;
        
        //pas utile normalement le bouton est masqué
        if(obSlide.question.options.mode == 1){
            // si mode = 1 on ne remontre pas la séquence
            //alert('sortie')
            return;
        }
   
     }
        if(obSlide.question.options.mode == 1){
            // si mode = 1 on ne remontre pas la séquence en dé&sactivant le bouton
            //updateButton(obSlide.getId('playBtn'), 2, null, 'memosuite_play' );
            updateButton(obSlide.getId('playBtn'), 0, 'red', 'memosuite_play' );
        }
     
     
//alert(`===>get_newSuite : \n` + newSuite.join("\n"));
     
    memosuite_masker_all(obSlide, true);
     quiz_show_mask(true, 0, true);
     setTimeout(memosuite_showNewSuite, obSlide.question.options.tempoSequenceMS, newSuite, 0, obSlide);
    
}

function memosuite_masker_all(obSlide, bolHidden = true){
    
    var obGame = document.getElementById(obSlide.gridId);
    selecteur = `div[piece="true"]`;
    var allPieces = obGame.querySelectorAll(selecteur);
//console.log(`===>memosuite_masker_all : obSlide.gridId = ${obSlide.gridId} - nbPieces = ${allPieces.length}`);

    if(bolHidden){
      for(var h = 0; h < allPieces.length; h++){
          //console.log(`pieceId = ${allPieces[h].id}`);
          allPieces[h].classList.add('memosuite_masker_carte');
      }
    }else{
        for(var h = 0; h < allPieces.length; h++){
          //console.log(`pieceId = ${allPieces[h].id}`);
          allPieces[h].classList.remove('memosuite_masker_carte');
    }      
      }

}


function memosuite_showNewSuite(arr, index, obSlide, status=0){
var options = obSlide.question.options;
    if (index < arr.length && status == 0){
        document.getElementById(arr[index]).classList.remove('memosuite_masker_carte');
        //alert (`===>memosuite_showNewSuite : index = ${index} / ${arr.length } ===> arr[index] = ${arr[index]}`);
        setTimeout(memosuite_showNewSuite, options.tempoSequenceMS, arr, index, obSlide, 1);
    }else if (index < arr.length && status == 1){
        document.getElementById(arr[index]).classList.add('memosuite_masker_carte');
        setTimeout(memosuite_showNewSuite, options.tempoSequenceMS, arr, ++index, obSlide, 0);
    }else{
        memosuite_masker_all(obSlide, false);
        //alert(replaceDoubleSlash(obSlide.question.options.msg_atYou,'\n'));
        if(options.maxAttempts > 0){
            var msgAttempts = ` [${options.msgattemptsNum} ${options.nbAttempts+1} / ${options.maxAttempts}]`;
        }else{
            var msgAttempts = ` [${options.msgattemptsNum} ${options.nbAttempts+1}`;
        }
        quiz_show_avertissement (options.msg_atYou + msgAttempts, options.msg_duree, options.msg_background, false);
        quiz_show_mask(false);
    }

}


function memosuite_showNewSuite2(arr, index, obSlide){
    if (index < arr.length ){
        document.getElementById(arr[index]).classList.remove('memosuite_masker_carte');
        //alert (`===>memosuite_showNewSuite : index = ${index} / ${arr.length } ===> arr[index] = ${arr[index]}`);
        setTimeout(memosuite_showNewSuite, obSlide.question.options.tempoSequenceMS, arr, ++index, obSlide);
    }else{
        //alert('fin');
        //memosuite_masker_all(obSlide, false);
    }
}

// function memosuite_img_is_loaded(obSlide)[
// 
// ]

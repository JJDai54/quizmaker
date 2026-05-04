
/* *********************************************** */
/* ----------- Drag And Drop Events ----------------*/
/* *********************************************** */


//https://developer.mozilla.org/fr/docs/Web/API/HTML_Drag_and_Drop_API
var puzzle_dataTransferType= "text/plain";
/* *************************************

**************************************** */
function test(ev){console.log('test : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000))};

/* *************************************

**************************************** */
function puzzle_dragstart(ev){
//ev.preventDefault();
    console.log('puzzle_dragstart : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    ev.dataTransfer.dropEffect = "none";
    ev.dataTransfer.setData(puzzle_dataTransferType, ev.target.id);
    //ev.dataTransfer.setData("id", ev.target.id);
    
}

/* *************************************

**************************************** */
function puzzle_drop(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(puzzle_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);
    
    piece2Id = ev.currentTarget.id;
    piece2Ob = document.getElementById(piece2Id);
    
    if(piece1Ob.parentNode.parentNode.getAttribute("mode") == 1){
        puzzle_insert_piece(piece1Ob,piece2Ob);    
    }else{
        puzzle_flip_piece(piece1Ob,piece2Ob);  
    }  
    
    
    target = ev.currentTarget;
    var puzzleId = piece1Ob.parentNode.parentNode.id;
    var strStyle = `${puzzleId}_divCible`;    
    piece2Ob.classList.remove(strStyle);
    
    
    piece1Ob.classList.remove(strStyle);
    
    
    puzzle_compute_score(puzzleId);
//     //puzzle_is_ok(piece1Parent.parentNode.id);
//     computeAllScoreEvent();
//     /////////////////////////////////////////////////////
//     var slideNumber = document.getElementById(puzzleId).getAttribute('slideNumber');
//     var clQuestion = quizard[slideNumber];
//     var options = quizard[slideNumber].question.options;
// //    alert(`slideNumber = ${slideNumber}`);
// 
//      //alert(`puzzle_drop : options.nextSlideDelai = ${options.nextSlideDelai}\nclQuestion.getScoreByProposition() = ${clQuestion.getScoreByProposition()}\nclQuestion.scoreMaxiQQ = ${clQuestion.scoreMaxiQQ}`);
//      if(options.nextSlideDelai*1 > 0 && clQuestion.getScoreByProposition() == clQuestion.scoreMaxiQQ){
//         var msg = (clQuestion.getScoreByProposition() == clQuestion.scoreMaxiBP) ? options.nextSlideMessageWinner : options.nextSlideMessageLooser;
//         msg = fo_sprint(msg);
//         quiz_show_avertissement (msg, options.nextSlideDelai, options.nextSlideBG);
//         //updateButton('quiz_btn_nextSlide', 1, 'getScoreByProposition').click();
//      } else if(options.nextSlideDelai*1 > 0){
//         updateButton('quiz_btn_nextSlide', 0, 'getScoreByProposition');
//      } 
//     
//     /////////////////////////////////////////////////////
    ev.stopPropagation();
    
    return true;
}
/* *************************************

**************************************** */
function puzzle_compute_score(puzzleId){
    //puzzle_is_ok(piece1Parent.parentNode.id);
    computeAllScoreEvent();
    /////////////////////////////////////////////////////
    var slideNumber = document.getElementById(puzzleId).getAttribute('slideNumber');
    var clQuestion = quizard[slideNumber];
    var options = quizard[slideNumber].question.options;
//    alert(`slideNumber = ${slideNumber}`);

     //alert(`puzzle_drop : options.nextSlideDelai = ${options.nextSlideDelai}\nclQuestion.getScoreByProposition() = ${clQuestion.getScoreByProposition()}\nclQuestion.scoreMaxiQQ = ${clQuestion.scoreMaxiQQ}`);
     if(options.nextSlideDelai*1 > 0 && clQuestion.getScoreByProposition() == clQuestion.scoreMaxiQQ){
        var msg = (clQuestion.getScoreByProposition() == clQuestion.scoreMaxiBP) ? options.nextSlideMessageWinner : options.nextSlideMessageLooser;
        msg = fo_sprint(msg);
        quiz_show_avertissement (msg, options.nextSlideDelai, options.nextSlideBG);
        //updateButton('quiz_btn_nextSlide', 1, 'getScoreByProposition').click();
     } else if(options.nextSlideDelai*1 > 0){
        updateButton('quiz_btn_nextSlide', 0, 'getScoreByProposition');
     } 
    
    /////////////////////////////////////////////////////
    
    return true;
}


/* *************************************

**************************************** */
function puzzle_flip_piece(piece1Ob,piece2Ob){
    piece1Parent = piece1Ob.parentNode;
    obNextPiece = piece1Ob.nextSibling;
    
    piece2Parent = piece2Ob.parentNode;
    
    piece2Parent.insertBefore(piece1Ob, piece2Ob);   
      
    if(obNextPiece){
        piece1Parent.insertBefore(piece2Ob, obNextPiece);    
    }else{
        piece1Parent.appendChild(piece1Ob);    
    }

}
/* *************************************

**************************************** */
function puzzle_insert_piece(piece1Ob,piece2Ob){
    piece1Parent = piece1Ob.parentNode;
    obNextPiece = piece1Ob.nextSibling;
    
    piece2Parent = piece2Ob.parentNode;
    
    if(piece1Parent == piece2Parent){
        piece2Parent.insertBefore(piece1Ob, piece2Ob);   
    }else{
    
    
        var obPuzzle = piece1Parent.parentNode;
        var imgRows   = obPuzzle.getAttribute('imgRows')*1;
        var imgCols   = obPuzzle.getAttribute('imgCols')*1;
        piece2Parent.insertBefore(piece1Ob, piece2Ob);   

        var selecteur='div[draggable="false"]';
        allDiv = obPuzzle.querySelectorAll(selecteur);
        
        for(var h = 0; h < allDiv.length; h++){
            var nbPieces = allDiv[h].children.length; 
            if(nbPieces > imgCols){

                //alert(`h=${h} - imgCols=${imgCols} - children=${allDiv[h].children.length}`);
                var divToMove = allDiv[h].children[nbPieces-1];
                var firstDiv = allDiv[h+1].children[0];
                allDiv[h+1].insertBefore(divToMove, firstDiv);
                    
                   
            }else if(nbPieces < imgCols){
                var divToMove = allDiv[h+1].children[0];
                var firstDiv = allDiv[h].children[0];                
                

                allDiv[h].insertBefore(divToMove, firstDiv);
            
            }
    }
    
   
    }
    
}

/* *************************************

**************************************** */
function puzzle_getPuzzleId(target){
    return target.parentNode.parentNode.id;
}
/* *************************************

**************************************** */
function puzzle_dragover(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(puzzle_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);

    console.log('puzzle_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    if(target.id != piece1Id){
        var puzzleId = target.parentNode.parentNode.id;
        var strStyle = `${puzzleId}_divCible`;    
        ev.currentTarget.classList.add(strStyle);
    }

    ev.stopPropagation();
    return true;
}

/* *************************************

**************************************** */
function puzzle_dragexit(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(puzzle_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);
    
    console.log('puzzle_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    var puzzleId = target.parentNode.parentNode.id;
    var strStyle = `${puzzleId}_divCible`;    
    ev.currentTarget.classList.remove(strStyle);

    piece1Ob.classList.remove(strStyle);
    ev.stopPropagation();
    return true;
}
/* *************************************

**************************************** */
function puzzle_dragleave(ev){
ev.preventDefault();
    //piece1Id = ev.dataTransfer.getData(puzzle_dataTransferType);
    //piece1Ob = document.getElementById(piece1Id);
    
    console.log('puzzle_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    var puzzleId = target.parentNode.parentNode.id;
    var strStyle = `${puzzleId}_divCible`;    
    ev.currentTarget.classList.remove(strStyle);

    //piece1Ob.classList.remove(strStyle);
    ev.stopPropagation();
    return true;
}
/* *************************************

**************************************** */
function puzzle_dragenter(ev){
//ev.preventDefault();
   console.log('puzzle_dragenter : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
}

/* *************************************

**************************************** */
function puzzle_rotateImg(ev, puzzleId, rotationStep){
//alert(`rotateImg : puzzleId = ${puzzleId} - rotationStep = ${rotationStep}`);
//function rotateImg(ev){
//var puzzleId = 'puzzle0';
    if (rotationStep*1 == 0 ){
        //console.log('===>puzzle_rotateImg : pas de rotation');
        return false;
    }else{
        //console.log(`===>puzzle_rotateImg : rotation = ${rotationStep}`);
    }
    
    var target = ev.currentTarget;
    var oldRotation = target.getAttribute('rotationStep')*1;
    
    if(rotationStep*1 == 2){
        var newRotation = (oldRotation + 2) % 4;
    }else{
        var newRotation = (oldRotation + 1) % 4;
    }
    
    target.classList.remove(`${puzzleId}_rotate000`);        
    target.classList.remove(`${puzzleId}_rotate090`);        
    target.classList.remove(`${puzzleId}_rotate180`);        
    target.classList.remove(`${puzzleId}_rotate270`);        
    
    switch(newRotation){
        case 1:  newStyle = `${puzzleId}_rotate090`; break;       
        case 2:  newStyle = `${puzzleId}_rotate180`; break;       
        case 3:  newStyle = `${puzzleId}_rotate270`; break;       
        case 0:
        default: newStyle = `${puzzleId}_rotate000`; break;       
         
    }
    target.setAttribute('rotationStep', newRotation);
    target.classList.add(newStyle);        
    

console.log (`puzzle_rotateImg :\n oldRotation = ${oldRotation} \n newRotation = ${newRotation} \n rotationStep attribute = ${target.getAttribute('rotationStep')} `
     + `\n newStyle = ${newStyle}`) ;   
    
    //ev.currentTarget.classList.toggle(`${puzzleId}_rotate`);
    puzzle_compute_score(puzzleId);

}


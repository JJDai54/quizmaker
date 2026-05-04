
/* *********************************************** */
/* ----------- Events -----------------------------*/
/* *********************************************** */

/* *************************************

**************************************** */
function taquin_onclick(ev){
 
    //recheche des coordonnées de la piece cliquée
    var currentTarget = taquin_search_pos(ev.currentTarget);
    parentRow = currentTarget.obj.parentNode;
    console.log(`===>taquin_onclick\ncurrentTarget.obj.id=${currentTarget.obj.id}\nrow=${currentTarget.row}\ncol = ${currentTarget.col}`);

    var pieceEmpty = taquin_get_piece_empty(ev.currentTarget.parentNode.parentNode);
    
    var obTaquin = currentTarget.obj.parentNode.parentNode;
    console.log(`===>taquin_onclick\npieceEmpty.obj.id=${pieceEmpty.obj.id}\nrow=${pieceEmpty.row}\ncol = ${pieceEmpty.col}`);
    

    if((currentTarget.row != pieceEmpty.row && currentTarget.col != pieceEmpty.col)|| (currentTarget.row == pieceEmpty.row && currentTarget.col == pieceEmpty.col)){
        //alert('deplacement imposible');
        return false;
    }else if(currentTarget.row == pieceEmpty.row){
        taquin_moveH(currentTarget, pieceEmpty);
    }else if(currentTarget.col == pieceEmpty.col){
        taquin_moveV(currentTarget, pieceEmpty);
    }
    if(taquin_is_ordering(currentTarget.obj.parentNode.parentNode)){
    //alert("gagnant");
    }else{
    //alert("perdant");
    }
    computeAllScoreEvent();
    //ev.stopPropagation();
//    alert(`===>taquin_onclick\ncurrentTarget.obj.id=${currentTarget.obj.id}\nparentRow = ${parentRow.id}`);
}
/* *************************************

**************************************** */
function taquin_moveH(obPiece, pieceEmpty){
    var obParentRow = obPiece.obj.parentNode;
    var nextSibling = null;
    if(obPiece.col < pieceEmpty.col){
        obParentRow.insertBefore(pieceEmpty.obj, obPiece.obj);
    }else{
        var nextSibling = obPiece.obj.nextSibling;
//         if(nextSibling){
//             alert("nextsibling ok");
//         }else{
//             alert("nextsibling pas glop");
//         }
        obParentRow.insertBefore(pieceEmpty.obj, nextSibling);
    }
// alert('deplacement Horizontal');
}
/* *************************************

**************************************** */
function taquin_moveV(obPiece, pieceEmpty){
    var obTaquin = obPiece.obj.parentNode.parentNode;
    taquinId = obTaquin.id;
    var numCol = obPiece.col;
        //alert(`taquin_moveV : numCol = ${numCol}`);
    
    //recherche des ligne min  et max a traiter
    if((obPiece.row < pieceEmpty.row)){
        var rowMin = obPiece.row*1;
        var rowMax = pieceEmpty.row*1;
        var sens = 'up';
    }else{
        var rowMin = pieceEmpty.row*1;
        var rowMax = obPiece.row*1;
        var sens = 'down';
    }
    
    //recheche des pieces de la colonnes en cours
    var piecesToMove = [];
    var selecteur=`div[rowTaquin="true"]`;    
    var allDivRows = obTaquin.querySelectorAll(selecteur);
    for (h = rowMin; h <= rowMax; h++){
        var obToMove = allDivRows[h].children[numCol];
        piecesToMove.push(obToMove);
        //alert(obToMove.id);
    }
    //alert(`rowMin = ${rowMin} - rowMax = ${rowMax} - sens = ${sens} - nbRows = ${allDivRows.length}`);
    if(sens == 'down'){
        for (h = rowMin; h < rowMax; h++){
            i = h + 1;
            //alert(`${h} / ${i} - imgRows : \n nbChildren = ${allDivRows[h].children.length} \n numCol = ${numCol}`);
             var obToMove1 = allDivRows[h].children[numCol];
             var obToMove2 = allDivRows[i].children[numCol];
            var obNextSibling = obToMove2.nextSibling;
            //alert(`objets a deplacer ${sens} : \n obToMove1 = ${obToMove1.id} \n obToMove2 = ${obToMove2.id} \n obNextSibling = ${obNextSibling.id}`);
            
            allDivRows[h].insertBefore(obToMove2, obToMove1);
            if(obNextSibling){
                allDivRows[i].insertBefore(obToMove1, obNextSibling);
            }else{
                allDivRows[i].appendChild(obToMove1);
}
        }
    }else{
        for (h = rowMax; h > rowMin; h--){
            i = h - 1;
            //alert(`${h} / ${i} - imgRows : \n nbChildren = ${allDivRows[h].children.length} \n numCol = ${numCol}`);
             var obToMove1 = allDivRows[h].children[numCol];
             var obToMove2 = allDivRows[i].children[numCol];
            var obNextSibling = obToMove1.nextSibling;
            //alert(`objets a deplacer ${sens} : \n obToMove1 = ${obToMove1.id} \n obToMove2 = ${obToMove2.id} \n obNextSibling = ${obNextSibling.id}`);
            
            allDivRows[i].insertBefore(obToMove1, obToMove2);
            if(obNextSibling){
                allDivRows[h].insertBefore(obToMove2, obNextSibling);
            }else{
                allDivRows[h].appendChild(obToMove2);
            }
            //break;
        }
    }
    
    
    
    
    
    
//         html.push(`<div id="${taquinId}_row_${row}" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);  
//     
//     
//     
//     
//     var selecteur=`div[numCol="${obPiece.col}"]`;
//     var allDiv = obTaquin.querySelectorAll(selecteur);
// //alert(`taquin_moveV : nb pieces = ${allDiv.length}`);
//     
// alert(`rowMin = ${rowMin} - rowMax = ${rowMax}`);
//     for(var h = rowMin; h <= rowMax; h++){
//         var pieceId = allDiv[h].id
//         alert (`pieceId = ${pieceId} - deplacement ok \n obPiece.row = ${obPiece.row} \n pieceEmpty.row = ${pieceEmpty.row} \n rowMin ${rowMin} \n numRow = ` + allDiv[h].getAttribute('numRow'));
//     }
//     
// //     for(var h = 0; h < allDiv.length; h++){
// //         var i = allDiv[h].getAttribute('numRow')*1
// //         if(i < rowMin  || i > rowMax){
// //         alert (`${h} / ${i} - pas de deplacement \n obPiece.row = ${obPiece.row} \n pieceEmpty.row = ${pieceEmpty.row} \n rowMin ${rowMin} \n numRow = ` + allDiv[h].getAttribute('numRow'));
// //         }else{
// //         alert (`${h} / ${i} - deplacement ok \n obPiece.row = ${obPiece.row} \n pieceEmpty.row = ${pieceEmpty.row} \n rowMin ${rowMin} \n numRow = ` + allDiv[h].getAttribute('numRow'));
// //         }
// //     
// //     
// //     }
// 
//     var obParentRow = obPiece.obj.parentNode;
//     var nextSibling = null;
//     if(obPiece.row < pieceEmpty.row){
// //         for(var h = )
// //         obParentRow.insertBefore(pieceEmpty.obj, obPiece.obj)
//     }else{
// //         obParentRow.insertBefore(obPiece.obj, pieceEmpty.obj)
//     }
//alert('deplacement vertical');
}


/* *************************************

**************************************** */
function taquin_search_pos(obPiece, traceOk = false){
    //if(traceOk)  alert(`pieceId = ${obPiece.id}`);
    if(!obPiece) {return null;}
    var obParent = obPiece.parentNode;
    var row = obParent.getAttribute('rowNum');
    var col = -1;

    for (var i = 0; i < obParent.children.length; i++) {
        if(obParent.children[i].id == obPiece.id){
            col = i;
            break;
        }
    }   

    return {'obj':obPiece, 'row':row, 'col':col};
}

// /* *************************************
// 
// **************************************** */
// function taquin_search_pos(obPiece){
//     var obParent = obPiece.parentNode;
//     var row = obParent.getAttribute('rowNum');
//     var col = -1;
// 
//     for (var i = 0; i < obParent.children.length; i++) {
//         if(obParent.children[i].id == obPiece.id){
//             col = i;
//             break;
//         }
//     }   
// 
//     return {'obj':obPiece, 'row':row, 'col' : col};
//     
// }


//https://developer.mozilla.org/fr/docs/Web/API/HTML_Drag_and_Drop_API
var taquin_dataTransferType= "text/plain";
/* *************************************

**************************************** */
function test(ev){console.log('test : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000))};

/* *************************************

**************************************** */
function taquin_dragstart(ev){
//ev.preventDefault();
    console.log('taquin_dragstart : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    ev.dataTransfer.dropEffect = "none";
    ev.dataTransfer.setData(taquin_dataTransferType, ev.target.id);
    //ev.dataTransfer.setData("id", ev.target.id);
    
}

/* *************************************

**************************************** */
function taquin_drop(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(taquin_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);
    
    piece2Id = ev.currentTarget.id;
    piece2Ob = document.getElementById(piece2Id);
    
    if(piece1Ob.parentNode.parentNode.getAttribute("mode") == 1){
        taquin_insert_piece(piece1Ob,piece2Ob);    
    }else{
        taquin_flip_piece(piece1Ob,piece2Ob);  
    }  
    
    
    target = ev.currentTarget;
    var taquinId = piece1Ob.parentNode.parentNode.id;
    var strStyle = `${taquinId}_divCible`;    
    piece2Ob.classList.remove(strStyle);
    
    
    piece1Ob.classList.remove(strStyle);
    
    
    
    //taquin_is_ok(piece1Parent.parentNode.id);
    computeAllScoreEvent();
    ev.stopPropagation();
    
    return true;
}
/* *************************************

**************************************** */
function taquin_flip_piece(piece1Ob,piece2Ob){
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
function taquin_insert_piece(piece1Ob,piece2Ob){
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

        var selecteur='div[piece="true"]';
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
function taquin_getPuzzleId(target){
    return target.parentNode.parentNode.id;
}
/* *************************************

**************************************** */
function taquin_dragover(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(taquin_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);

    console.log('taquin_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    if(target.id != piece1Id){
        var taquinId = target.parentNode.parentNode.id;
        var strStyle = `${taquinId}_divCible`;    
        ev.currentTarget.classList.add(strStyle);
    }

    ev.stopPropagation();
    return true;
}

/* *************************************

**************************************** */
function taquin_dragexit(ev){
ev.preventDefault();
    piece1Id = ev.dataTransfer.getData(taquin_dataTransferType);
    piece1Ob = document.getElementById(piece1Id);
    
    console.log('taquin_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    var taquinId = target.parentNode.parentNode.id;
    var strStyle = `${taquinId}_divCible`;    
    ev.currentTarget.classList.remove(strStyle);

    piece1Ob.classList.remove(strStyle);
    ev.stopPropagation();
    return true;
}
/* *************************************

**************************************** */
function taquin_dragleave(ev){
ev.preventDefault();
    //piece1Id = ev.dataTransfer.getData(taquin_dataTransferType);
    //piece1Ob = document.getElementById(piece1Id);
    
    console.log('taquin_dragover : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
    target = ev.currentTarget;
    var taquinId = target.parentNode.parentNode.id;
    var strStyle = `${taquinId}_divCible`;    
    ev.currentTarget.classList.remove(strStyle);

    //piece1Ob.classList.remove(strStyle);
    ev.stopPropagation();
    return true;
}
/* *************************************

**************************************** */
function taquin_dragenter(ev){
//ev.preventDefault();
   console.log('taquin_dragenter : id' + ev.currentTarget.id + '==>' + Math.floor(Math.random()*1000));
}

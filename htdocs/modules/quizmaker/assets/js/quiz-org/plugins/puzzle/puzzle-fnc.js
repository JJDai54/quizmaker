
/*
https://fr.javascript.info/task/shuffle
*/
var rotArray = ['000', '090', '180', '270'];
            
/* *************************************
function puzzle : creation u puzzle dans le div en paramètre
puzzleId : Identifiant du div conteneur du puzzle
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du puzzle
imgCols : nombre de colonne du puzzle
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
0 : puzzle ordoné
1 : puzzle mélangé
2 : puzzleId ordonné
3 : puzzleId mélangé
**************************************** */
function build_puzzle(puzzleId, imgUrl, options, shuffle){
    var obPuzzle = document.getElementById(puzzleId);
    puzzle_init(puzzleId, imgUrl, options, shuffle);
    puzzle_add_style(puzzleId, imgUrl, options);
    //var cellsArr = puzzle_get_pieces(puzzleId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = puzzle_get_pieces(puzzleId, options, shuffle);
    obPuzzle.innerHTML = puzzle_build_game(puzzleId, options.imgRows, options.imgCols, cellsArr);
}

/* *************************************
function puzzle : creation u puzzle dans le div en paramètre
puzzleId : Identifiant du div conteneur du puzzle
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du puzzle
imgCols : nombre de colonne du puzzle
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
**************************************** */
function puzzle_init(puzzleId, imgUrl, options, shuffle){
    imgSizeArr = puzzle_getImgSize(imgUrl);  
    options.gameHeight = options.gameWidth * imgSizeArr.rhw;
    
//alert(`build_puzzle :  options.gameWidth=${options.gameWidth} - options.gameHeight =${options.gameHeight } - imgSizeArr.h=${imgSizeArr.h} - imgSizeArr.rhw=${imgSizeArr.rhw}`);    
    
    options.cellW = Math.round(options.gameWidth / options.imgCols);
    options.cellH = Math.round(options.gameHeight  / options.imgRows);

    //calcule de la rotationStep possible, selon si une cellulle est caré ou non
    if(options.rotation){
        //le calcul doit tenire compte des arrondi des hauteurs et largeurs de cellules
        if (Math.abs(options.cellW - options.cellH) < 2){
            options.rotationStep = 1; //les celules sont carrées
        }else{
            options.rotationStep = 2;//les celules sont rectangulaire
        }
    }else{
        options.rotationStep = 0; // pas de rotation
    }
//    alert('rotationStep = ' + options.rotationStep );
    
//     options.cellW = options.gameWidth / options.imgCols;
//     options.cellH = options.gameHeight  / options.imgRows;
    options.gameWidth = (options.cellW * options.imgCols) ;//+ (options.marge * options.imgCols);
//console.log(`build_puzzle :  options.cellW=${options.cellW} - coptions.cellH=${options.cellH}`);    
      //puzzle_add_style(puzzleId, imgUrl, options.gameWidth, options.gameHeight , options.cellW, options.cellH, options.marge, options.radius);
      
      var obPuzzle = document.getElementById(puzzleId);
      obPuzzle.classList.add("puzzle");
      obPuzzle.style.width = options.gameWidth + "px";
      //obPuzzle.style.widthMax = options.gameWidth + "px";
      obPuzzle.style.background = ((options.background=='#000000') ? '' : options.background);
      //obPuzzle.setAttribute('background', 'blue');
      obPuzzle.setAttribute('imgRows', options.imgRows);
      obPuzzle.setAttribute('imgCols', options.imgCols);
      obPuzzle.setAttribute('imgW', options.gameWidth);
      obPuzzle.setAttribute('imgUrl', imgUrl);
      obPuzzle.setAttribute('mode', options.mode);
      obPuzzle.setAttribute('bg', options.background);
      obPuzzle.setAttribute('marge', options.marge);
      obPuzzle.setAttribute('radius', options.radius);
      obPuzzle.setAttribute('rotation', options.rotation);
      obPuzzle.setAttribute('rotationStep', options.rotationStep);
      obPuzzle.setAttribute('preview', options.preview);
      //obPuzzle.innerHTML = `<div style="visibility:hidden;background:red;"><img src="${imgUrl}" width='1px' height="100px">zzzzz</div>`   
//  alert("zzzzzzzzzzzzzzzz")  ;
}

/* *************************************
function puzzle_reset : remélange ou réordonne le puzzle sans avoir a repasser tous les paramètres
puzzleId : Identifiant du div conteneur du puzzle
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
**************************************** */
function puzzle_reset(puzzleId, shuffle, tellFrom=''){
    var obPuzzle   = document.getElementById(puzzleId);
    if(!obPuzzle) {
        alert(`puzzle_reset : puzzleId = ${puzzleId} - tellFrom = ${tellFrom}`);
        return
    };
    var options = [];
    options.imgRows      = obPuzzle.getAttribute('imgRows')*1;
    options.imgCols      = obPuzzle.getAttribute('imgCols')*1;
    options.gameWidth    = obPuzzle.getAttribute('imgW')*1;
    options.mode         = obPuzzle.getAttribute('mode');
    options.background   = obPuzzle.getAttribute('bg');
    options.marge        = obPuzzle.getAttribute('marge');
    options.radius       = obPuzzle.getAttribute('radius');
    options.rotation     = obPuzzle.getAttribute('rotation');
    options.rotationStep = obPuzzle.getAttribute('rotationStep');
    options.preview      = obPuzzle.getAttribute('preview');
    var imgUrl = obPuzzle.getAttribute('imgUrl');
    
    puzzle_init(puzzleId, imgUrl, options, shuffle);
    //var cellsArr = puzzle_get_pieces(puzzleId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = puzzle_get_pieces(puzzleId, options, shuffle);
    obPuzzle.innerHTML = puzzle_build_game(puzzleId, options.imgRows, options.imgCols, cellsArr);
}

/* *************************************
function puzzle_get_pieces ; création de tous les div qui vont contenir les pièces
puzzleId : Identifiant du div conteneur du puzzle
imgRows : nombre de lignes du puzzle
imgCols : nombre de colonne du puzzle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
l'image de fond a été définie dans le style affecté à chaque piece
**************************************** */
function puzzle_get_pieces(puzzleId, options, shuffle){
    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    
    var events =`ondragstart='puzzle_dragstart(event);'
    ondrop='puzzle_drop(event);'
    ondragover='puzzle_dragover(event);'
    ondragexit='puzzle_dragexit(event);'
    ondragleave='puzzle_dragleave(event);'
    ondragenter='puzzle_dragenter(event);'`;
    
    if(options.rotation == 1) {
        events +=`ondblclick='puzzle_rotateImg(event,"${puzzleId}");'
                  onclick='puzzle_rotateImg(event,"${puzzleId}", ${options.rotationStep});'`;
    
    } 
    
    for (var row = 0; row < options.imgRows; row++){
      y = Math.round(row * options.cellH ); 
      for (var col = 0; col < options.imgCols; col++){
      //alert(`puzzle_get_pieces puzzleId = ${puzzleId} : options.rotation  = ${options.rotation }`);
        if(options.rotation > 0 && shuffle){
            var idxRotation = getRndRotation(options.rotationStep);
        }else{
            var idxRotation = 0;
        }
        var classRotation = `${puzzleId}_rotate${rotArray[idxRotation]}`;
    //alert(`rotationStep = ${rotationStep} \n class = ${classRotation}` );


        x = Math.round(col * options.cellW ); 
        attributs = `numPiece=${numPiece} numRow=${row} numCol=${col} rotationStep='${idxRotation}'`;
        cellArr.push(`<div id="${puzzleId}_piece_${numPiece}" ${attributs} draggable="true" class="${puzzleId}_divDragable ${classRotation}" ${events} style="background-position: -${x}px -${y}px;"></div>`);
        numPiece++;
      }
    }

        
    if(shuffle) {cellArr.sort(() => Math.random() - 0.5)};  
     return cellArr; 
}

/* *************************************

**************************************** */
function getRndRotation(rotationStep){
    
    //if(rotationStep == 0) return ;
    switch(rotationStep*1){
        case 1: 
            index = getRandom(3, 0);
            break;
        case 2:
            index = getRandom(1, 0) * 2;
            break;
        default: 
           index = 0;
    }
 
    return index;

}
/* *************************************

**************************************** */
function getRndRotation2(puzzleId, rotationStep){
    
    //if(rotationStep == 0) return ;
    switch(rotationStep*1){
        case 1: 
            var rotArray = ['000', '090', '180', '270'];
            index = getRandom(3, 0);
            var angle = rotArray[index];
            break;
        case 2:
            var rotArray = ['000', '180'];
            index = getRandom(1, 0);
            var angle = rotArray[index];
            break;
        default: 
            var angle = '000';
    }
 
    var classRotation = `${puzzleId}_rotate${angle}`;
    //alert(`rotationStep = ${rotationStep} \n class = ${classRotation}` );
    return classRotation;

}

/* *************************************
function puzzle_build_game
puzzleId : Identifiant du div conteneur du puzzle
imgRows : nombre de lignes du puzzle
imgCols : nombre de colonne du puzzle
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
function puzzle_build_game(puzzleId, imgRows, imgCols, cellsArr){
    //console.log(`puzzle_build_game : puzzleId=${puzzleId} - imgRows=${imgRows} - imgCols=${imgCols} - nbCells=${cellsArr.length}`);
//     for (var h = 0; h < cellsArr.length; h++){
//         console.log(cellsArr[h]);
//     }
//     console.log('--------------');
    
    var html = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var index = 0;
    
    for (var row = 0; row < imgRows; row++){
      html.push(`<div id="${puzzleId}_row_${row}" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);
      for (var col=0;col<imgCols;col++){
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  
     return html.join("\n"); 
}
      
/* *************************************
function puzzle_add_style : cré le style global de toutes les pièces, et l'effet appliqué sur chaque pièce au survole de la souris
puzzleId : Identifiant du div conteneur du puzzle
imgUrl : url de l'image a découper
imgW : largeur de l'image mise à l'échelle. La largeur est recalculé a partir du ratio de l'image originale
imgH : hauteur de l'image mise à l'échalle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
marge : marge en pixel des pieces
radius : border_radius en pixel des pieces
**************************************** */
//function puzzle_add_style(puzzleId, imgUrl, imgW, imgH, cellW, cellH, marge, radius){
function puzzle_add_style(puzzleId, imgUrl, options){
    const stylesheet = new CSSStyleSheet();
    
    //style des pieces du puzzle
    var strStyle=`.${puzzleId}_divDragable{
    width:${options.cellW}px;
    height:${options.cellH}px;
    background-image:url('${imgUrl}');
    background-size: ${options.gameWidth}px ${options.gameHeight}px;
    cursor: grab;
    margin:${options.marge}px;
    padding:0px;
    border: 0px;
    border-radius: ${options.radius}px;}`;
    //stylesheet.replaceSync(strStyle);
    stylesheet.insertRule(strStyle);  
/*
    var delta = 110;
    var strStyle=`.divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    }`;
*/  
    // classe a ajouter pour la rotationStep des pieces  
    var strStyle = `.${puzzleId}_rotate000{transform: rotate(000deg);}`;
    stylesheet.insertRule(strStyle);  
    var strStyle = `.${puzzleId}_rotate090{transform: rotate(090deg);}`;
    stylesheet.insertRule(strStyle);  
    var strStyle = `.${puzzleId}_rotate180{transform: rotate(180deg);}`;
    stylesheet.insertRule(strStyle);  
    var strStyle = `.${puzzleId}_rotate270{transform: rotate(270deg);}`;
    stylesheet.insertRule(strStyle);  


/*
var delta = 10;
    var scaleX = (cellW-delta) / cellW;
    var scaleY = (cellH-delta) / cellH;
    //style des pieces au onDragstart
    var strStyle=`.${puzzleId}_divDragable:hover{
    transform: scalex(${scaleX}%) scaley(${scaleY}%);}`;
    stylesheet.insertRule(strStyle);  
    transform: scalex(${delta}%) scaley(${delta}%);
    
    var strStyle=`.${puzzleId}_divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:80%;}`;
*/    
    var delta = 95;
    //style des pieces au onDragstart
    var strStyle=`.${puzzleId}_divDragable:hover{
    opacity:80%;}`;
    stylesheet.insertRule(strStyle);  
    
    //style des pieces au onDragHover
    var strStyle=`.${puzzleId}_divCible{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:100%;}`;
    stylesheet.insertRule(strStyle);  
    
     //fin de la nouvelle feuille de style
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, stylesheet];
}

/* *************************************
function puzzle_is_ok : vérifie si le puzzle est ordonné
puzzleId : Identifiant du div conteneur du puzzle
**************************************** */
function puzzle_is_ok(puzzleId){
    return puzzle_is_ordering(document.getElementById(puzzleId));
}

/* *************************************
function puzzle_is_ordering : vérifie si le puzzle est ordonné
obPuzzle : objet div du puzzle
**************************************** */
function puzzle_is_ordering(obPuzzle){
    var PieceId = '';
    var t = null;
    var bolOk = true; 
    var selecteur='div[draggable="true"]';
    
    allPieces = obPuzzle.querySelectorAll(selecteur);
    for(var h = 0; h < allPieces.length; h++){
        if((allPieces[h].getAttribute('numPiece')*1 != h )
        || (allPieces[h].getAttribute('rotationStep')*1 != 0 )){
            bolOk=false;
        }
    }
    
//     if(bolOk){
//         alert('puzzle_is_ordering : ' + obPuzzle.id + ' - nb pieces = ' + allPieces.length);
//     }
    return bolOk;
}

/* *************************************
function puzzle_getImgSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function puzzle_getImgSize(imgUrl){

    let imgOb = new Image();
    imgOb.src = imgUrl;

// if (!imgOb.complete) {
//     alert('image non chargées');
// }

    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
    //sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`puzzle_getImgSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}
/* *************************************
function puzzle_getImgObjSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function puzzle_getImgObjSize(imgOb){
    //let imgOb = document.createElement('img');
var imgUrl = imgOb.getAttribute('src');
    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
//    sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`puzzle_getImgObjSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}

/* ******************************************

********************************************* */
function puzzle_preview(puzzleId, nextSlideDelai, tellFrom){

    puzzle_reset(puzzleId, true);
    if(nextSlideDelai*1 == 0){
        updateButton('quiz_btn_nextSlide', 1, tellFrom);
    }
    quiz_show_mask(false);

}

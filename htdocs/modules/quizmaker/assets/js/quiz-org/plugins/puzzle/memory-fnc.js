
/*
https://fr.javascript.info/task/shuffle
*/
/* *************************************
function memory : creation u memory dans le div en paramètre
memoryId : Identifiant du div conteneur du memory
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du memory
imgCols : nombre de colonne du memory
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
0 : memory ordoné
1 : memory mélangé
2 : memoryId ordonné
3 : memoryId mélangé
**************************************** */
function build_memory(memoryId, imgUrl, options, shuffle){
    var obMemory = document.getElementById(memoryId);
    memory_init(memoryId, imgUrl, options, shuffle);
    memory_add_style(memoryId, imgUrl, options);
    //var cellsArr = memory_get_pieces(memoryId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = memory_get_pieces(memoryId, options, shuffle);
    obMemory.innerHTML = memory_build_game(memoryId, options, cellsArr);
}

/* *************************************
function memory : creation u memory dans le div en paramètre
memoryId : Identifiant du div conteneur du memory
imgUrl : url de l'image a découper
gameWidth : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du memory
imgCols : nombre de colonne du memory
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
**************************************** */
function memory_options_tostring(options){
 
    var arr = [];
    arr.push('===>memory_options_tostring');
  
    arr.push(`doublons   = ${options.doublons}`);
    arr.push(`gameWidth  = ${options.gameWidth}`);
    arr.push(`gameHeight = ${options.gameHeight}`);
    arr.push(`imgWidth   = ${options.imgWidth}`);
    arr.push(`imgHeight  = ${options.imgHeight}`);
    arr.push(`imgRatio   = ${options.imgRatio}`);
    arr.push(`imgCols    = ${options.imgCols}`);
    arr.push(`imgRows    = ${options.imgRows}`);
    arr.push(`----------------------------------`);
    arr.push(`bgW    = ${options.bgW}`);
    arr.push(`bgH    = ${options.bgH}`);
    arr.push(`----------------------------------`);
    arr.push(`cellW  = ${options.cellW}`);
    arr.push(`cellH  = ${options.cellH}`);
    arr.push(`----------------------------------`);
    arr.push(`gameWidth  = ${options.gameWidth}`);
    arr.push(`gameHeight = ${options.gameHeight}`);
    arr.push(`gameCols   = ${options.gameCols}`);
    arr.push(`gameRows   = ${options.gameRows}`);
    alert(arr.join("\n"));
    
}
/* *************************************

**************************************** */
function memory_init(memoryId, imgUrl, options, shuffle){
    imgSizeArr = memory_getImgSize(imgUrl, options);  
    //options.height = options.gameWidth * imgSizeArr.rhw;
    
//alert(`build_memory :  options.gameWidth=${options.gameWidth} - options.height =${options.height } - imgSizeArr.h=${imgSizeArr.h} - imgSizeArr.rhw=${imgSizeArr.rhw}`);
    options.gameRows = Math.floor((options.imgCols * options.imgRows * options.doublons) / options.gameCols);    
    options.bgW = options.gameWidth / options.gameCols * options.imgCols;
    options.bgH =  options.bgW * imgSizeArr.rhw;
 
    
// alert(`memory_init : imgSizeArr.rhw = ${imgSizeArr.rhw}`
// + `\n options.imgCols = ${options.imgCols} - options.imgRows = ${options.imgRows} - options.gameCols = ${options.gameCols}`
// + `\n options.gameRows = ${options.gameRows} - options.bgW = ${options.bgW} - options.bgH = ${options.bgH}`);
    
    options.cellW = options.gameWidth / options.gameCols;
    options.cellH = options.bgH / options.imgRows;
    //ne pas modifier la valeur d'"origine de gameWidth pour ne pas modifier la taille a chaque rechargement"    
    newGameWidth = (options.cellW * options.gameCols)+(options.marge*(options.gameCols*2));    
    options.gameHeight = options.cellH * options.gameRows;
    
//memory_options_tostring(options);

//console.log(`build_memory :  options.cellW=${options.cellW} - coptions.ellH=${options.cellH}`);    
      //memory_add_style(memoryId, imgUrl, options.width, options.height , options.cellW, options.cellH, options.marge, options.radius);
      
      var obMemory = document.getElementById(memoryId);
      obMemory.classList.add("memory");
      obMemory.style.width = newGameWidth + "px";
      //obMemory.style.widthMax = options.gameWidth + "px";
      obMemory.style.background = ((options.background=='#000000') ? '' : options.background);
      //obMemory.setAttribute('background', 'blue');
      obMemory.setAttribute('slideNumber', options.slideNumber);
      obMemory.setAttribute('doublons', options.doublons);
      obMemory.setAttribute('t_pieces', options.imgRows * options.imgCols * options.doublons);
      obMemory.setAttribute('tempo', options.tempo);
      obMemory.setAttribute('preview', options.preview);
      obMemory.setAttribute('dblAction', 0);
      obMemory.setAttribute('t_clicks', 0);
      obMemory.setAttribute('t_winner', 0);
      obMemory.setAttribute('t_image', 0);
      obMemory.setAttribute('t_found', 0);
      obMemory.setAttribute('imgRows', options.imgRows);
      obMemory.setAttribute('imgCols', options.imgCols);
      obMemory.setAttribute('gameWidth', options.gameWidth);
      obMemory.setAttribute('gameCols', options.gameCols);
      obMemory.setAttribute('imgUrl', imgUrl);
      obMemory.setAttribute('mode', options.mode);
      obMemory.setAttribute('bg', options.background);
      obMemory.setAttribute('marge', options.marge);
      obMemory.setAttribute('radius', options.radius);
      //obMemory.innerHTML = `<div style="visibility:hidden;background:red;"><img src="${imgUrl}" width='1px' height="100px">zzzzz</div>`   
//  alert("zzzzzzzzzzzzzzzz")  ;
}

/* *************************************
function memory_reset : remélange ou réordonne le memory sans avoir a repasser tous les paramètres
memoryId : Identifiant du div conteneur du memory
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
**************************************** */
function memory_reset(memoryId, shuffle){
    var obMemory   = document.getElementById(memoryId);
    var options = [];
    options.imgRows       = obMemory.getAttribute('imgRows')*1;
    options.imgCols       = obMemory.getAttribute('imgCols')*1;
    options.gameWidth    = obMemory.getAttribute('gameWidth')*1;
    options.gameCols    = obMemory.getAttribute('gameCols')*1;
    //options.mode       = obMemory.getAttribute('mode');
    options.background = obMemory.getAttribute('bg');
    options.marge      = obMemory.getAttribute('marge');
    options.radius     = obMemory.getAttribute('radius');
    var imgUrl = obMemory.getAttribute('imgUrl');


    options.doublons = obMemory.getAttribute('doublons');
    options.tempo = obMemory.getAttribute('tempo');
    options.dblAction = obMemory.getAttribute('dblAction');
    options.preview = obMemory.getAttribute('preview');
    options.t_clicks = obMemory.getAttribute('t_clicks');
    options.t_winner = obMemory.getAttribute('t_winner');
    options.t_image = obMemory.getAttribute('t_image');
    options.t_found = obMemory.getAttribute('t_found');

    memory_init(memoryId, imgUrl, options, shuffle);
    var cellsArr = memory_get_pieces(memoryId, options, shuffle);
    obMemory.innerHTML = memory_build_game(memoryId, options, cellsArr);
alert('memory_reset');    
}

/* *************************************
function memory_get_pieces ; création de tous les div qui vont contenir les pièces
memoryId : Identifiant du div conteneur du memory
imgRows : nombre de lignes du memory
imgCols : nombre de colonne du memory
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
l'image de fond a été définie dans le style affecté à chaque piece
**************************************** */
//function memory_get_pieces(memoryId,imgRows, imgCols, cellW, cellH, shuffle){
function memory_get_pieces(memoryId, options, shuffle){
    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    var numImage = 0;
    var events=`ondragstart='memory_dragstart(event);'
    onclick='memory_onclick(event);'`;
//alert(`memory_get_pieces : options.bgW = ${options.bgW} - options.bgH  = ${options.bgH }`);
    for (var row = 0; row < options.imgRows; row++){
      y = row * options.cellH ; 
      for (var col = 0; col < options.imgCols; col++){
        x = col * options.cellW ; 
        numImage++;
        attributs = `piece="true" numPiece=${numPiece} numImage=${numImage} status='0' numRow=${row} numCol=${col}`;

        for(var i = 0; i< options.doublons; i++){
            cellArr.push(`<div id="${memoryId}_piece_${numPiece}_${i}" ${attributs} class="${memoryId}_divDragable" ${events} style="background-position: -${x}px -${y}px;"></div>`); 
        }
        //cellArr.push(`<div id="${memoryId}_piece_${numPiece}b" ${attributs} draggable="true" class="${memoryId}_divDragable" ${events} style="background-position: -${x}px -${y}px;"></div>`);
        numPiece++;
      }
    }

        
    if(shuffle) {
        for(var i = 0; i < 5; i++){
        cellArr.sort(() => Math.random() - 0.5)
        }
    };  
     return cellArr; 
}


/* *************************************
function memory_build_game
memoryId : Identifiant du div conteneur du memory
imgRows : nombre de lignes du memory
imgCols : nombre de colonne du memory
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
function memory_build_game(memoryId, options, cellsArr){
//    console.log(`memory_build_game : memoryId=${memoryId} - options.gameRows=${options.gameRows} - options.gameCols=${options.gameCols} - nbCells=${cellsArr.length}`);
//     for (var h = 0; h < cellsArr.length; h++){
//         console.log(cellsArr[h]);
//     }
//     console.log('--------------');
    
    var html = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var index = 0;
//     imgRows = 3;
//     imgCols = 4;
// alert(`memory_build_game : cellsArr.length = ${cellsArr.length}`)   
    for (var row = 0; row < options.gameRows; row++){
      html.push(`<div id="${memoryId}_row_${row}" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);
      for (var col = 0; col < options.gameCols; col++){
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  
     return html.join("\n"); 
}
      
/* *************************************
function memory_add_style : cré le style global de toutes les pièces, et l'effet appliqué sur chaque pièce au survole de la souris
memoryId : Identifiant du div conteneur du memory
imgUrl : url de l'image a découper
imgW : largeur de l'image mise à l'échelle. La largeur est recalculé a partir du ratio de l'image originale
imgH : hauteur de l'image mise à l'échalle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
marge : marge en pixel des pieces
radius : border_radius en pixel des pieces
**************************************** */
//function memory_add_style(memoryId, imgUrl, imgW, imgH, cellW, cellH, marge, radius){
function memory_add_style(memoryId, imgUrl, options){
    const stylesheet = new CSSStyleSheet();
/*
    background-image:url('${imgUrl}');
        options.bgW = Math.floor(options.gameWidth / options.gridCols);
    options.bgH = Math.floor(options.height  / options.gridRows);

*/
    //style des pieces du memory
    var strStyle=`.${memoryId}_divDragable{
    width:${options.cellW}px;
    height:${options.cellH}px;
    background-size: ${options.bgW}px ${options.bgH}px;
    cursor: grab;
    background-color: yellow;
    margin:${options.marge}px;
    border-radius: ${options.radius}px;}`;
    //stylesheet.replaceSync(strStyle);
    stylesheet.insertRule(strStyle);  
/*
    var delta = 110;
    var strStyle=`.divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    }`;
*/    
/*
var delta = 10;
    var scaleX = (cellW-delta) / cellW;
    var scaleY = (cellH-delta) / cellH;
    //style des pieces au onDragstart
    var strStyle=`.${memoryId}_divDragable:hover{
    transform: scalex(${scaleX}%) scaley(${scaleY}%);}`;
    stylesheet.insertRule(strStyle);  
    transform: scalex(${delta}%) scaley(${delta}%);
*/    
    var delta = 95;
    //style des pieces au onDragstart
    var strStyle=`.${memoryId}_divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:80%;}`;
    stylesheet.insertRule(strStyle);  
    
    //style des pieces au onDragHover
    var strStyle=`.${memoryId}_divCible{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:100%;}`;
    stylesheet.insertRule(strStyle);  
    
     //fin de la nouvelle feuille de style
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, stylesheet];
}

/* *************************************
function memory_is_ok : vérifie si le memory est ordonné
memoryId : Identifiant du div conteneur du memory
**************************************** */
function memory_is_ok(memoryId){
    return memory_is_ordering(document.getElementById(memoryId));
}

/* *************************************
function memory_is_ordering : vérifie si le memory est ordonné
obMemory : objet div du memory
**************************************** */
function memory_is_ordering(obMemory){
    var PieceId = '';
    var t = null;
    var bolOk = true; 
    var selecteur='div[draggable="true"]';
    
    allPieces = obMemory.querySelectorAll(selecteur);
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('numPiece')*1 != h ){
            bolOk=false;
        }
    }
    
//     if(bolOk){
//         alert('memory_is_ordering : ' + obMemory.id + ' - nb pieces = ' + allPieces.length);
//     }
    return bolOk;
}

/* *************************************
function memory_getImgSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function memory_getImgSize(imgUrl, options){

    let imgOb = new Image();
    imgOb.src = imgUrl;

// if (!imgOb.complete) {
//     alert('image non chargées');
// }
    options.imgWidth  = imgOb.naturalWidth;
    options.imgHeight = imgOb.naturalHeight;
    options.imgRatio  = imgOb.naturalHeight/imgOb.naturalWidth;
    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
    //sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`memory_getImgSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}
/* *************************************
function memory_getImgObjSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function memory_getImgObjSize(imgOb){
    //let imgOb = document.createElement('img');
var imgUrl = imgOb.getAttribute('src');
    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
//    sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`memory_getImgObjSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}

/* ******************************************

********************************************* */
function memory_preview(memoryId, nextSlideDelai, tellFrom){

    memory_all(memoryId, true);    
    if(nextSlideDelai*1 == 0){
        updateButton('quiz_btn_nextSlide', 1, tellFrom);
    }
    quiz_show_mask(false);

}

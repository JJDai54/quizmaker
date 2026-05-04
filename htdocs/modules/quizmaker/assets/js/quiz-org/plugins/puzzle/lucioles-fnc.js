
/*
https://fr.javascript.info/task/shuffle
*/
/* *************************************
function lucioles : creation u lucioles dans le div en paramètre
luciolesId : Identifiant du div conteneur du lucioles
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du lucioles
imgCols : nombre de colonne du lucioles
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
0 : lucioles ordoné
1 : lucioles mélangé
2 : luciolesId ordonné
3 : luciolesId mélangé
**************************************** */
function build_lucioles(luciolesId, imgUrl, answers, options, shuffle){
    var obMemory = document.getElementById(luciolesId);
    lucioles_init(luciolesId, imgUrl, options, shuffle);
    lucioles_add_style(luciolesId, imgUrl, options);
    //var cellsArr = lucioles_get_pieces(luciolesId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = lucioles_get_pieces(luciolesId, answers, options, shuffle);
    obMemory.innerHTML = lucioles_build_game(luciolesId, options, cellsArr);
}

/* *************************************
function lucioles : creation u lucioles dans le div en paramètre
luciolesId : Identifiant du div conteneur du lucioles
imgUrl : url de l'image a découper
gameWidth : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du lucioles
imgCols : nombre de colonne du lucioles
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
**************************************** */
function lucioles_options_tostring(options){
 
    var arr = [];
    arr.push('===>lucioles_options_tostring');
  
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
function lucioles_init(luciolesId, imgUrl, options, shuffle){
    imgSizeArr = lucioles_getImgSize(imgUrl, options);  
    //options.height = options.gameWidth * imgSizeArr.rhw;
    
//alert(`build_lucioles :  options.gameWidth=${options.gameWidth} - options.height =${options.height } - imgSizeArr.h=${imgSizeArr.h} - imgSizeArr.rhw=${imgSizeArr.rhw}`);
    options.gameRows = Math.floor((options.imgCols * options.imgRows * options.doublons) / options.gameCols);    
    options.bgW = options.gameWidth / options.gameCols * options.imgCols;
    options.bgH =  options.bgW * imgSizeArr.rhw;
 
    
// alert(`lucioles_init : imgSizeArr.rhw = ${imgSizeArr.rhw}`
// + `\n options.imgCols = ${options.imgCols} - options.imgRows = ${options.imgRows} - options.gameCols = ${options.gameCols}`
// + `\n options.gameRows = ${options.gameRows} - options.bgW = ${options.bgW} - options.bgH = ${options.bgH}`);
    
    options.cellW = options.gameWidth / options.gameCols;
    options.cellH = options.bgH / options.imgRows;
    //ne pas modifier la valeur d'"origine de gameWidth pour ne pas modifier la taille a chaque rechargement"    
    newGameWidth = (options.cellW * options.gameCols)+(options.marge*(options.gameCols*2));    
    options.gameHeight = options.cellH * options.gameRows;
    
//lucioles_options_tostring(options);

//console.log(`build_lucioles :  options.cellW=${options.cellW} - coptions.ellH=${options.cellH}`);    
      //lucioles_add_style(luciolesId, imgUrl, options.width, options.height , options.cellW, options.cellH, options.marge, options.radius);
      
      var obMemory = document.getElementById(luciolesId);
      obMemory.classList.add("lucioles");
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
function lucioles_reset : remélange ou réordonne le lucioles sans avoir a repasser tous les paramètres
luciolesId : Identifiant du div conteneur du lucioles
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
**************************************** */
function lucioles_reset(luciolesId, answers, shuffle){
    var obMemory   = document.getElementById(luciolesId);
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

    lucioles_init(luciolesId, imgUrl, options, shuffle);
    var cellsArr = lucioles_get_pieces(luciolesId,answers,  options, shuffle);
    obMemory.innerHTML = lucioles_build_game(luciolesId, options, cellsArr);
alert('lucioles_reset');    
}

/* *************************************
function lucioles_get_pieces ; création de tous les div qui vont contenir les pièces
luciolesId : Identifiant du div conteneur du lucioles
imgRows : nombre de lignes du lucioles
imgCols : nombre de colonne du lucioles
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
l'image de fond a été définie dans le style affecté à chaque piece
**************************************** */
//function lucioles_get_pieces(luciolesId,imgRows, imgCols, cellW, cellH, shuffle){
function lucioles_get_pieces(luciolesId, answers, options, shuffle){
    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    var numImage = 0;
    var events=`ondragstart='lucioles_dragstart(event);'
    onclick='lucioles_onclick(event);'`;
//alert(`lucioles_get_pieces : options.bgW = ${options.bgW} - options.bgH  = ${options.bgH }`);

    for(var k in answers){
        var ans =  answers[k];
        var imgUrl = `${quiz_config.urlQuizImg}/${ans.image1}`;
        numImage++;    
//alert(`lucioles_get_pieces [${k}] : urlImg = ${imgUrl}`);

        attributs = `piece="true" numPiece=${numPiece} numImage=${numImage} status='0' points='${ans.points}' imgUrl='${imgUrl}'`;
        //var style = `background-image:url('${imgUrl}');`;
        var style = ``;
        for(var i = 0; i< options.doublons; i++){
            cellArr.push(`<div id="${luciolesId}_piece_${numPiece}_${i}" ${attributs} class="${luciolesId}_divDragable" ${events} style="${style}"></div>`); 
        }
        //cellArr.push(`<div id="${luciolesId}_piece_${numPiece}b" ${attributs} draggable="true" class="${luciolesId}_divDragable" ${events} style="background-position: -${x}px -${y}px;"></div>`);
        numPiece++;
    }
    
        
    if(shuffle) {
        for(var i = 0; i < 5; i++){
        cellArr.sort(() => Math.random() - 0.5)
        }
    };  
     return cellArr; 
}


/* *************************************
function lucioles_build_game
luciolesId : Identifiant du div conteneur du lucioles
imgRows : nombre de lignes du lucioles
imgCols : nombre de colonne du lucioles
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
function lucioles_build_game(luciolesId, options, cellsArr){
//    console.log(`lucioles_build_game : luciolesId=${luciolesId} - options.gameRows=${options.gameRows} - options.gameCols=${options.gameCols} - nbCells=${cellsArr.length}`);
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
// alert(`lucioles_build_game : cellsArr.length = ${cellsArr.length}`)   
    for (var row = 0; row < options.gameRows; row++){
      html.push(`<div id="${luciolesId}_row_${row}" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);
      for (var col = 0; col < options.gameCols; col++){
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  
     return html.join("\n"); 
}
      
/* *************************************
function lucioles_add_style : cré le style global de toutes les pièces, et l'effet appliqué sur chaque pièce au survole de la souris
luciolesId : Identifiant du div conteneur du lucioles
imgUrl : url de l'image a découper
imgW : largeur de l'image mise à l'échelle. La largeur est recalculé a partir du ratio de l'image originale
imgH : hauteur de l'image mise à l'échalle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
marge : marge en pixel des pieces
radius : border_radius en pixel des pieces
**************************************** */
function lucioles_add_style(luciolesId, imgUrl, options){
    const stylesheet = new CSSStyleSheet();
/*
    background-image:url('${imgUrl}');
        options.bgW = Math.floor(options.gameWidth / options.gridCols);
    options.bgH = Math.floor(options.height  / options.gridRows);

    height:${options.cellH}px;
    background-size: ${options.bgW}px ${options.bgH}px;
    background-size:100px 50px;
*/
    //style des pieces du lucioles
    var strStyle=`.${luciolesId}_divDragable{
    width:${options.cellW}px;
    height:${options.cellH}px;
    cursor: grab;
    background-size:${options.cellW}px ${options.cellH}px;
    background-color: yellow;
    margin:${options.marge}px;
    border-radius: ${options.radius}px;
    background-position: 0px 0px;
    object-fit: fill;}`;
    
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
    var strStyle=`.${luciolesId}_divDragable:hover{
    transform: scalex(${scaleX}%) scaley(${scaleY}%);}`;
    stylesheet.insertRule(strStyle);  
    transform: scalex(${delta}%) scaley(${delta}%);
*/    
    var delta = 95;
    //style des pieces au onDragstart
    var strStyle=`.${luciolesId}_divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:80%;}`;
    stylesheet.insertRule(strStyle);  
    
    //style des pieces au onDragHover
    var strStyle=`.${luciolesId}_divCible{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:100%;}`;
    stylesheet.insertRule(strStyle);  
    
     //fin de la nouvelle feuille de style
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, stylesheet];
}

/* *************************************
function lucioles_is_ok : vérifie si le lucioles est ordonné
luciolesId : Identifiant du div conteneur du lucioles
**************************************** */
function lucioles_is_ok(luciolesId){
    return lucioles_is_ordering(document.getElementById(luciolesId));
}

/* *************************************
function lucioles_is_ordering : vérifie si le lucioles est ordonné
obMemory : objet div du lucioles
**************************************** */
function lucioles_is_ordering(obMemory){
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
//         alert('lucioles_is_ordering : ' + obMemory.id + ' - nb pieces = ' + allPieces.length);
//     }
    return bolOk;
}

/* *************************************
function lucioles_getImgSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function lucioles_getImgSize(imgUrl, options){

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
    //alert(`lucioles_getImgSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}
/* *************************************
function lucioles_getImgObjSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function lucioles_getImgObjSize(imgOb){
    //let imgOb = document.createElement('img');
var imgUrl = imgOb.getAttribute('src');
    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
//    sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`lucioles_getImgObjSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}

/* ******************************************

********************************************* */
function lucioles_preview(lucioleId, nextSlideDelai, tellFrom){

    memory_all(lucioleId, true);    
    if(nextSlideDelai*1 == 0){
        updateButton('quiz_btn_nextSlide', 1, tellFrom);
    }
    quiz_show_mask(false);

}

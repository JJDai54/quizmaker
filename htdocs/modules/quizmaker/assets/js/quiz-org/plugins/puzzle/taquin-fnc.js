
/*
https://fr.javascript.info/task/shuffle
*/
/* *************************************
function taquin : creation u taquin dans le div en paramètre
taquinId : Identifiant du div conteneur du taquin
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du taquin
imgCols : nombre de colonne du taquin
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
0 : taquin ordoné
1 : taquin mélangé
2 : taquin ordonné
3 : taquin mélangé
**************************************** */
function build_taquin(taquinId, imgUrl, options, shuffle){
//alert(`build_taquin : taquinId = ${taquinId}`);
    var obPuzzle = document.getElementById(taquinId);
    taquin_init(taquinId, imgUrl, options, shuffle);
    taquin_add_style(taquinId, imgUrl, options);
    //var cellsArr = taquin_get_pieces(taquinId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = taquin_get_pieces(taquinId, options, shuffle);
    obPuzzle.innerHTML = taquin_build_game(taquinId, options.imgRows, options.imgCols, cellsArr);
}

/* *************************************
function taquin : creation u taquin dans le div en paramètre
taquinId : Identifiant du div conteneur du taquin
imgUrl : url de l'image a découper
width : largeur de l'image redimentionnée. La largeur est recalculé a partir du ration de l'image originale
imgRows : nombre de lignes du taquin
imgCols : nombre de colonne du taquin
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
mode : mode depacement des pieces 0 = flip - 1 = insert avec déplacement de toutes les pièces intermédiaires
**************************************** */
function taquin_init(taquinId, imgUrl, options, shuffle){
    imgSizeArr = taquin_getImgSize(imgUrl);  
    options.gameHeight = options.gameWidth * imgSizeArr.rhw;
    
//alert(`build_taquin :  options.gameWidth=${options.gameWidth} - options.gameHeight =${options.gameHeight } - imgSizeArr.h=${imgSizeArr.h} - imgSizeArr.rhw=${imgSizeArr.rhw}`);    
    
    options.cellW = Math.floor(options.gameWidth / options.imgCols);
    options.cellH = Math.floor(options.gameHeight  / options.imgRows);
    options.gameWidth = options.cellW * options.imgCols;
console.log(`build_taquin :  options.cellW=${options.cellW} - coptions.ellH=${options.cellH}`);    
      //taquin_add_style(taquinId, imgUrl, options.gameWidth, options.gameHeight , options.cellW, options.cellH, options.marge, options.radius);
      
      var obPuzzle = document.getElementById(taquinId);
      obPuzzle.classList.add("taquin");
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
      obPuzzle.setAttribute('preview', options.preview);
      //obPuzzle.innerHTML = `<div style="visibility:hidden;background:red;"><img src="${imgUrl}" width='1px' height="100px">zzzzz</div>`   
//  alert("zzzzzzzzzzzzzzzz")  ;
}

/* *************************************
function taquin_reset : remélange ou réordonne le taquin sans avoir a repasser tous les paramètres
taquinId : Identifiant du div conteneur du taquin
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
**************************************** */
function taquin_reset(taquinId, shuffle){
    var obPuzzle   = document.getElementById(taquinId);
    var options = [];
    options.imgRows    = obPuzzle.getAttribute('imgRows')*1;
    options.imgCols    = obPuzzle.getAttribute('imgCols')*1;
    options.gameWidth  = obPuzzle.getAttribute('imgW')*1;
    options.mode       = obPuzzle.getAttribute('mode');
    options.background = obPuzzle.getAttribute('bg');
    options.marge      = obPuzzle.getAttribute('marge');
    options.radius     = obPuzzle.getAttribute('radius');
    options.radius     = obPuzzle.getAttribute('preview');
    var imgUrl = obPuzzle.getAttribute('imgUrl');
    
    taquin_init(taquinId, imgUrl, options, shuffle);
    //var cellsArr = taquin_get_pieces(taquinId, options.imgRows, options.imgCols, options.cellW, options.cellH, shuffle);
    var cellsArr = taquin_get_pieces(taquinId, options, shuffle);
    obPuzzle.innerHTML = taquin_build_game(taquinId, options.imgRows, options.imgCols, cellsArr);
}

/* *************************************
function taquin_get_pieces ; création de tous les div qui vont contenir les pièces
taquinId : Identifiant du div conteneur du taquin
imgRows : nombre de lignes du taquin
imgCols : nombre de colonne du taquin
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
l'image de fond a été définie dans le style affecté à chaque piece
**************************************** */
//function taquin_get_pieces(taquinId,imgRows, imgCols, cellW, cellH, shuffle){
function taquin_get_pieces(taquinId, options, shuffle){
    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    var events=`onclick='taquin_onclick(event);'`;

//var caseVide = `<img  src='${options.urlPlugin}/img/buttons/pingouin-bleu.png' title='' alt='' style='width:${options.cellW}px;height:${options.cellH}px;object-fit:fill;' >`;        
//alert(caseVide);
    for (var row = 0; row < options.imgRows; row++){
      y = row * options.cellH ; 
      for (var col = 0; col < options.imgCols; col++){
        x = col * options.cellW ; 
        //si shuffle=true, il faut cacher la derniere case du tableau ordonné
        //afin de montrer l'image entière.
        if(row == options.imgRows-1 && col == options.imgCols-1 && shuffle) {
            styleSufixe = 'divEmpty';
            isEmpty = 'true';
        }else{
            styleSufixe = 'divDragable';
            isEmpty = 'false';
        }

        attributs = `piece='true' numPiece=${numPiece} numRow=${row} numCol=${col} isEmpty="${isEmpty}"`;
        cellArr.push(`<div id="${taquinId}_piece_${numPiece}" ${attributs} class="${taquinId}_${styleSufixe}" ${events} style="background-position: -${x}px -${y}px;"></div>`);
        numPiece++;
        //cellArr.push(`<div id="${taquinId}_piece_${numPiece++}_${col}_${row}" draggable="true" class="${taquinId}_divDragable" ${events} style="background-position: -${x}px -${y}px;"></div>`);
      }
    }

    //il f  aut faire un melange possible    
    if(shuffle) {
        var shuffleArr = get_taquin(cellArr.length);
        var newArr = [];
        for(var h = 0; h < shuffleArr.length; h++){
            newArr.push(cellArr[shuffleArr[h]]);
        }
        cellArr = newArr;
    };  
    return cellArr; 
}


/* *************************************
function taquin_build_game
taquinId : Identifiant du div conteneur du taquin
imgRows : nombre de lignes du taquin
imgCols : nombre de colonne du taquin
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
function taquin_build_game(taquinId, imgRows, imgCols, cellsArr){
    console.log(`taquin_build_game : taquinId=${taquinId} - imgRows=${imgRows} - imgCols=${imgCols} - nbCells=${cellsArr.length}`);
    for (var h = 0; h < cellsArr.length; h++){
        console.log(cellsArr[h]);
    }
    console.log('--------------');
    
    var html = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var index = 0;
    
    for (var row = 0; row < imgRows; row++){
      html.push(`<div id="${taquinId}_row_${row}" rowTaquin="true" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);
      for (var col = 0; col < imgCols; col++){
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  
     return html.join("\n"); 
}
      
/* *************************************
function taquin_add_style : cré le style global de toutes les pièces, et l'effet appliqué sur chaque pièce au survole de la souris
taquinId : Identifiant du div conteneur du taquin
imgUrl : url de l'image a découper
imgW : largeur de l'image mise à l'échelle. La largeur est recalculé a partir du ratio de l'image originale
imgH : hauteur de l'image mise à l'échalle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
marge : marge en pixel des pieces
radius : border_radius en pixel des pieces
**************************************** */
//function taquin_add_style(taquinId, imgUrl, imgW, imgH, cellW, cellH, marge, radius){
function taquin_add_style(taquinId, imgUrl, options){
    const stylesheet = new CSSStyleSheet();
    
    //style des pieces du taquin
    var strStyle=`.${taquinId}_divDragable{
    width:${options.cellW}px;
    height:${options.cellH}px;
    background-image:url('${imgUrl}');
    background-size: ${options.gameWidth}px ${options.gameHeight}px;
    cursor: grab;
    margin:${options.marge}px;
    border-radius: ${options.radius}px;}`;
    //stylesheet.replaceSync(strStyle);
    stylesheet.insertRule(strStyle);  
    
    //style des pieces du taquin
    var strStyle=`.${taquinId}_divEmpty{
    width:${options.cellW}px;
    height:${options.cellH}px;
    background-size: ${options.gameWidth}px ${options.gameHeight}px;
    cursor: not-allowed;;
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
    var strStyle=`.${taquinId}_divDragable:hover{
    transform: scalex(${scaleX}%) scaley(${scaleY}%);}`;
    stylesheet.insertRule(strStyle);  
    transform: scalex(${delta}%) scaley(${delta}%);
*/    
    var delta = 95;
    //style des pieces au onDragstart
    var strStyle=`.${taquinId}_divDragable:hover{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:80%;}`;
    stylesheet.insertRule(strStyle);  
    
    //style des pieces au onDragHover
    var strStyle=`.${taquinId}_divCible{
    transform: scalex(${delta}%) scaley(${delta}%);
    opacity:100%;}`;
    stylesheet.insertRule(strStyle);  
    
     //fin de la nouvelle feuille de style
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, stylesheet];
}

/* *************************************
function taquin_is_ok : vérifie si le taquin est ordonné
taquinId : Identifiant du div conteneur du taquin
**************************************** */
function taquin_is_ok(taquinId){
    return taquin_is_ordering(document.getElementById(taquinId));
}

/* *************************************
function taquin_is_ordering : vérifie si le taquin est ordonné
obPuzzle : objet div du taquin
**************************************** */
function taquin_is_ordering(obPuzzle){
//alert(`taquin_is_ordering : obPuzzle.id = ${obPuzzle.id}`);
    var PieceId = '';
    var t = null;
    var bolOk = true; 
    var selecteur='div[piece="true"]';
    var pieceEmpty = taquin_get_piece_empty(obPuzzle);
    
    allPieces = obPuzzle.querySelectorAll(selecteur);
    for(var h = 0; h < allPieces.length; h++){
        if(allPieces[h].getAttribute('numPiece')*1 != h ){
            bolOk=false;
        }
       //alert(`taquin_is_ordering :\n allPieces[h] = ${allPieces[h].id}`);
            var currentPiece = taquin_search_pos(allPieces[h],true);
//alert(`${h} - pieceId = ${allPieces[h].id}`);            
         //if(pieceEmpty && currentPiece){
            if(taquin_piece_is_movable(currentPiece, pieceEmpty)){
                allPieces[h].style.cursor = 'grab';
            }else{
                allPieces[h].style.cursor = 'not-allowed';
            }
         //}
    }
    
//     if(bolOk){
//         alert('taquin_is_ordering : ' + obPuzzle.id + ' - nb pieces = ' + allPieces.length);
//     }
    return bolOk;
}
////////////////////////////////////////////
/* *************************************
function taquin_is_ordering : vérifie si le taquin est ordonné
obPuzzle : objet div du taquin
**************************************** */
function taquin_piece_is_movable(currentPiece, pieceEmpty){
//alert(`obCurrentPiece = ${obCurrentPiece.id}`);
    //var currentPiece = taquin_search_pos(obCurrentPiece);
// alert(`taquin_piece_is_movable : obCurrentPiece = ${currentPiece.obj.id} `
//       + `\n currentPiece.row  = ${currentPiece.row } `
//       + `\n currentPiece.col = ${currentPiece.col} `
//       + `\n pieceEmpty.row = ${pieceEmpty.row} `
//       + `\n pieceEmpty.col = ${pieceEmpty.col}`);
if(!pieceEmpty) {return};
     if((currentPiece.row != pieceEmpty.row && currentPiece.col != pieceEmpty.col)
     || (currentPiece.row == pieceEmpty.row && currentPiece.col == pieceEmpty.col)){
        var ret = false;
     }else if(currentPiece.row == pieceEmpty.row){
        var ret = true;
     }else if(currentPiece.col == pieceEmpty.col){
        var ret = true;
     }else{
        //var ret = '?';
     }
    return ret;
// //    alert(`===>taquin_onclick\ncurrentPiece.obj.id=${currentPiece.obj.id}\nparentRow = ${parentRow.id}`);
}

/* *************************************
function taquin_is_ordering : vérifie si le taquin est ordonné
obPuzzle : objet div du taquin
**************************************** */
function taquin_get_piece_empty(obTaquin){
//alert(obTaquin.innerHTML);
    var selecteur='div[isEmpty="true"]';
    var allDiv = obTaquin.querySelectorAll(selecteur);
    if(allDiv.length == 0){return null}
    //alert('divId = ' + obTaquin.id + ' - children = ' + obTaquin.children.length + ' - nb = '  + allDiv.length);
    return taquin_search_pos(allDiv[0]);
    //return allDiv[0];
}

///////////////////////////////////////////
/* *************************************
function taquin_getImgSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function taquin_getImgSize(imgUrl){

    let imgOb = new Image();
    imgOb.src = imgUrl;

// if (!imgOb.complete) {
//     alert('image non chargées');
// }

    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
    //sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`taquin_getImgSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}
/* *************************************
function taquin_getImgObjSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
function taquin_getImgObjSize(imgOb){
    //let imgOb = document.createElement('img');
var imgUrl = imgOb.getAttribute('src');
    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
//    sizeArr={w:550, h:472, rhw:472/550};   
    //alert(`taquin_getImgObjSize : ${imgUrl}\nOriginal width = ` + sizeArr.w + "\n Original height1 = " + sizeArr.h + "\n rapport h/w = " + sizeArr.rhw);
    
    
    return sizeArr;
}

/* *************************************

https://www.youtube.com/results?search_query=taquin+micmath
https://www.dcode.fr/solveur-taquin
https://fr.wikipedia.org/wiki/Taquin
**************************************** */
function get_taquin(arraySize){
    var arr = Array(arraySize).fill(0); 
    for(var h = 0; h < arraySize; h++){arr[h] = h;}
    var exp1 = arr.join('-');
    
    var logArr = [];
    logArr.push(`get_taquin : arraySize = ${arraySize} \n`);
    arr.sort(() => Math.random() - 0.5);
    var k = 0;
    var taquin = Array(arraySize).fill(0);   
    for(var h = 0; h < arraySize; h++){taquin[h] = arr[h];}
    
    var result = 0;
    for(var h=0; h < arraySize-1; h++){
        for(var i = h; i < arraySize-1; i++){
        if(h == i){continue;}
            if(taquin[i] < taquin[h]) {result++;}
            k++;
            logArr.push(`${k} : [${h+1}/${i+1}] : taquin[h] = ${taquin[h]} - taquin[i] = ${taquin[i]} - result = ${result}`);
        }
    }
    
    var parite = result % 2;
    if(parite % 2 != 0){
//         var temp = taquin[0];
//         taquin[0] = taquin[1];
//         taquin[1] = temp;
        //alert('combinaison non conforme');
        return get_taquin(arraySize);
    }
    
    logArr.push(`parite= ${parite}`);
    logArr.push('-------------' + taquin.join('-'));
    //alert(logArr.join("\n"));
    
    //a garer pour des tests
    //taquin = [0,1,2,4,5,7,3,6,8];

    return taquin;
         
}

/* ******************************************

********************************************* */
function taquin_preview(taquinId, nextSlideDelai, tellFrom){

    taquin_reset(taquinId, true);
    if(nextSlideDelai*1 == 0){
        updateButton('quiz_btn_nextSlide', 1, tellFrom);
    }
    quiz_show_mask(false);

}

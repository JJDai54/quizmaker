newDoublons = 1;
/* ******************************************

********************************************* */
function memosuite_preview(gameId, msg_nextslide_duree, tellFrom){

    
    if(msg_nextslide_duree*1 == 0){
        updateButton('quiz_btn_nextSlide', 1, null, tellFrom);
    }
    quiz_show_mask(false);

}


/* ================================================================== */

/* *************************************

**************************************** */
function memosuite_get_found(memosuiteId){
return true;
}


/* *************************************

**************************************** */
function memosuite_set_status(memosuiteId, fromStatus = -1, toStatus = 2){
return true;
}



/* *************************************
function memosuite_add_style : cré le style global de toutes les pièces, et l'effet appliqué sur chaque pièce au survole de la souris
memosuiteId : Identifiant du div conteneur du memosuite
imgUrl : url de l'image a découper
imgW : largeur de l'image mise à l'échelle. La largeur est recalculé a partir du ratio de l'image originale
imgH : hauteur de l'image mise à l'échalle
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
marge : marge en pixel des pieces
radius : border_radius en pixel des pieces
**************************************** */
function memosuite_add_style(memosuiteId, imgUrl, options){
    const stylesheet = new CSSStyleSheet();
    var arr = [];
    arr.push(`width:${options.cellW}px;`);
    arr.push(`height:${options.cellH}px;`);
    arr.push(`cursor: grab;`);
    //arr.push(`background-size:${options.cellW}px ${options.cellH}px;`);
    arr.push(`background-color: ${options.background};`);
    arr.push(`margin:${options.marge}px;`);
    arr.push(`border: solid 1px grey;`);
    arr.push(`border-radius: ${options.radius}px;`);
    arr.push(`object-fit: fill;`);
    
    var obGame = document.getElementById(memosuiteId);
    if(obGame.getAttribute('variant') == 'grille'){
        arr.push(`background-size: ${options.bgW}px ${options.bgH}px;`);
    }else{
        arr.push(`background-position: 0px 0px;`);
        arr.push(`background-size:${options.cellW}px ${options.cellH}px;`);
    }
    
    var strStyle = `.${memosuiteId}_divPieces{${arr.join("\n")}}`;    
    
    //stylesheet.replaceSync(strStyle);
    stylesheet.insertRule(strStyle);  

    
     //fin de la nouvelle feuille de style
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, stylesheet];
}

/* *************************************
function memosuite_build_game
memosuiteId : Identifiant du div conteneur du memosuite
imgRows : nombre de lignes du memosuite
imgCols : nombre de colonne du memosuite
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
function memosuite_build_game(memosuiteId, options, cellsArr){
//    console.log(`memosuite_build_game : memosuiteId=${memosuiteId} - options.gameRows=${options.gameRows} - options.gameCols=${options.gameCols} - nbCells=${cellsArr.length}`);
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
// alert(`memosuite_build_game : cellsArr.length = ${cellsArr.length}`)   
    for (var row = 0; row < options.gameRows; row++){
      html.push(`<div id="${memosuiteId}_row_${row}" rowNum="${row}"  style="display: flex;background:transparent;" draggable="false" >`);
      for (var col = 0; col < options.gameCols; col++){
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  
     return html.join("\n"); 
}

/* ***************************************
*
var fonction_de_rappel = function() {

  //console.log('Image chargée'); 
  alert('Image chargée'); 
  // renvoie "Image chargée" quand 'image' est chargée

};
* **************************************** */
function fonction_de_rappel() {

  //console.log('Image chargée'); 
  //alert('Image chargée'); 
  // renvoie "Image chargée" quand 'image' est chargée

};

function imgReady(a,b){var i=null,f=function(){if(a.complete||(a.readyState===4)||(a.readyState==='complete')){clearInterval(i);b(a);return !0;}return !1;};if(!f()){i=setInterval(function(){f();},150);}}

async function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
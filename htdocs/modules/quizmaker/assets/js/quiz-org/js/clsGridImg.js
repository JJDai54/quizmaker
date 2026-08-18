
/*********************************************
 * ================ class clsGridImg =========
 * *******************************************/

class clsGridImg {
source = null;
cible = null;
urlImg = ''; 
colImg = [];


 constructor() {
    
 }
  constructor_old(urlImg, rows, cols) {
    
    this.source = {};
    this.source.name = 'Source';
    this.source.urlImg = urlImg;
    this.source.size = clsGame.getImgSize(urlImg);    
    
    this.source.pieces = rows * cols;
    this.source.rows = rows;
    this.source.cols = cols;
    this.source.cell = {w: this.source.size.w / cols, h: this.source.size.h / rows}; 
    this.source.cell.r = this.source.cell.h / this.source.cell.w;

    //-----------------------------------------------------------
 
    
    
    //alert('ok')
        
 }

 setSource(urlImg, rows, cols) {
    
    this.source = {};
    this.source.name = 'Source';
    this.source.urlImg = urlImg;
    this.source.size = clsGame.getImgSize(urlImg);    
    
    this.source.pieces = rows * cols;
    this.source.rows = rows;
    this.source.cols = cols;
    this.source.cell = {w: this.source.size.w / cols, h: this.source.size.h / rows}; 
    this.source.cell.r = this.source.cell.h / this.source.cell.w;

    //-----------------------------------------------------------
 
    
    
    //alert('ok')
        
 }
 
/*********************************************
 * 
 * *******************************************/
 setCible(cibleWidth, cibleCols = null) {
    //si gridCibleCols non defini, la grille cible a le même format que la source
    if(!cibleCols) {cibleCols = this.source.cols};
//console.log(`setCible : cibleWidth = ${cibleWidth} cibleCols = ${cibleCols}`)
    this.cible = {};
    this.cible.name = 'Cible';
    this.cible.cols = cibleCols;
    this.cible.rows = Math.floor(this.source.pieces / cibleCols);
    if((this.cible.cols * this.cible.rows) < this.source.pieces){this.cible.rows++;}
    
    var cellWidth = cibleWidth / this.cible.cols
    this.cible.cell = {w: cellWidth, h: cellWidth * this.source.cell.r};  
    
    
    this.cible.size = {w: cibleWidth, h: this.cible.cell.h * this.cible.rows};
    this.cible.bgsize = {w: this.cible.cell.w * this.source.cols, h: this.cible.cell.h * this.source.rows};

    return this.cible;
 }
 
/*********************************************
 * 
 * *******************************************/
getVersion(msg = 'ok'){return `clsGridImg : v 1.1 - 2026-05-25 - ${msg}`;}

/*********************************************
 * 
 * *******************************************/

setImage(divImgSource, divImgCible){
//console.log(`===>divImg.id = ${divImg.id}\nrow = ${row}\ncol = ${col}`);
    if(!isObject(divImgSource)){
        divImgSource = document.getElementById(this.colImg[divImgSource]);
    }
var cibleW = divImgCible.offsetWidth ;
var cibleH = divImgCible.offsetHeight;
    
var bgSuiteWidth  = cibleW * this.source.cols;
var bgSuiteHeight = cibleH * this.source.rows;

//var bgSuiteHeight = (bgSuiteWidth  * this.source.cell.r).toFixed(2);
var posX = - (divImgSource.getAttribute('numCol')*1 * cibleW)
var posY = - (divImgSource.getAttribute('numRow')*1 * cibleH); 
// var posX = - divImgSource.getAttribute('numCol')*1 * this.source.cell.w;
// var posY = - divImgSource.getAttribute('numRow')*1 * this.source.cell.h;; 

//console.log(`setImage : ${bgSuiteWidth}px ${bgSuiteHeight}px \n ${posX}px ${posY}px \n row=${divImgSource.getAttribute('numCol') } col = ${divImgSource.getAttribute('numRow')}`);
    divImgCible.style.backgroundImage = `url('${this.source.urlImg}')`;
    divImgCible.style.backgroundSize = `${bgSuiteWidth}px ${bgSuiteHeight}px`;
    divImgCible.style.backgroundPosition = `${posX}px ${posY}px`;
    
    var numPiece = divImgSource.getAttribute('numPiece');
    divImgCible.setAttribute('numPiece', numPiece);
    //divImgCible.setAttribute('numImage', (row * this.source.cols) + col);
    //alert(`divHeight  = ${divHeight }` );
    //divImgCible.innerHTML = 'numPiece = ' +  numPiece;
   
}


/* *************************************

**************************************** */
getCellRatio(){
    return this.source.cell.r.toFixed(2);
}

getCellHeight(w){
    return (w * this.source.cell.r).toFixed(2);
}

/* *************************************
function get_pieces ; création de tous les div qui vont contenir les pièces
rootId : Identifiant du div conteneur du memosuite
imgRows : nombre de lignes du memosuite
imgCols : nombre de colonne du memosuite
cellW : largeur en pixel des pieces
cellH : hauteur en pixel des pieces
shuffle : true=mélange les pièces - false : affiche l'image originale ordonnée
l'image de fond a été définie dans le style affecté à chaque piece
**************************************** */
get_pieces(rootId, slideNumber, evArr, shuffle = true){
var nbRows = this.source.rows;
var nbCols = this.source.cols;
// var nbRows = this.cible.rows;
// var nbCols = this.cible.cols;
var obSlide = quizard[slideNumber]; 

//alert(`${nbRows} x ${nbCols}`)
//     var cellW = '100'; //this.cible.cell.w;
//     var cellH =  '100'; //this.cible.cell.h;
    var cellW = this.cible.cell.w;
    var cellH = this.cible.cell.h;

    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    var numImage = 0;
    var obMemosuite = document.getElementById(rootId);
    var imgUrl = `url('${this.source.urlImg}')`; //this.source.urlImg; //`url('${obMemosuite.getAttribute('imgUrl')}')`;
    var cellId = '';
    
    var events = '';
    if(evArr['dragstart']) {
    //if(evArr.indexOf('dragstart') >= 0) {
        events += ` ondragstart='${rootId}_dragstart(event);'`;
    }   
    if(evArr['onclick']){
    //if(evArr.indexOf('onclick') >= 0) {
        events += ` onclick='${evArr.onclick}(event, ${slideNumber});'`;
    }   
//alert(events);    
//alert(`get_pieces : options.bgW = ${options.bgW} - options.bgH  = ${options.bgH }`);
    //var backgroundImg = `background-size:${this.cible.size.w}px ${this.cible.size.h}px;`;
    var backgroundImg = `background-size:${this.cible.bgsize.w}px ${this.cible.bgsize.h}px;`;
    var cellSize = `width:${cellW}px;height:${cellH}px;`;
    
    for (var row = 0; row < nbRows; row++){
      y = row * cellH ; 
      for (var col = 0; col < nbCols; col++){
        x = col * cellW ; 
        numImage++;
        attributs = `piece="true" numPiece='${numPiece}' numImage='${numImage}' status='0' numRow='${row}' numCol='${col}'`;
        var style = `background-position: -${x}px -${y}px;background-image:${imgUrl};${backgroundImg}${cellSize}` 
        cellId = obSlide.getId('piece', numPiece);

        cellArr.push(`<div id="${cellId}" ${attributs} class="${obSlide.name}_divPieces" ${events} style="${style}"></div>`); 
        numPiece++;
        //console.log(cellArr[cellArr.length-1]);
        this.colImg.push(cellId);
      }
    }

        
    if(shuffle) {
        for(var i = 0; i < 5; i++){
        cellArr.sort(() => Math.random() - 0.5)
        }
    }  
    //alert(`get_pieces : cellArr.length = ${cellArr.length}`);
    
     return cellArr; 
}


/* *************************************
function build_game
memosuiteId : Identifiant du div conteneur du memosuite
imgRows : nombre de lignes du memosuite
imgCols : nombre de colonne du memosuite
cellsArr : tableau des pièces a répartir selon les lignes et les colonnes
**************************************** */
build_game(rootId, slideNumber, evArr, shuffle = true){
//    console.log(`memosuite_build_game : rootId=${rootId} - options.gameRows=${options.gameRows} - options.gameCols=${options.gameCols} - nbCells=${cellsArr.length}`);
//     for (var h = 0; h < cellsArr.length; h++){
//         console.log(cellsArr[h]);
//     }
//     console.log('--------------');
/*
get_pieces(rootId, cellW, cellH, evArr, shuffle = true){
*/
    var cellsArr = this.get_pieces(rootId, slideNumber, evArr, shuffle);
    
    var html = [];
    var index = 0;
//     imgRows = 3;
//     imgCols = 4;
// alert(`memosuite_build_game : cellsArr.length = ${cellsArr.length}`)   
    for (var row = 0; row < this.cible.rows; row++){
      html.push(`<div id="${rootId}_row_${row}" rowNum="${row}"  style="display:flex;background:transparent; width:${this.cible.size.w}px" draggable="false" >`);
      for (var col = 0; col < this.cible.cols; col++){
        //html.push(cellsArr[index].replace('{row}',row).replace('{col}',col));
        html.push(cellsArr[index]);
        index++;
      }
      html.push(`</div>`);
    }
  //alert(`===>build_game \n` + html.join("\n"));
  //   return 'begin' + html.join("\n") + 'end'; 
     return html.join("\n"); 
}

/* *************************************
function get_newSuite : renvoie un tableau d'une séquence d'image
lgSuite : nombre d'image de la séquence à générer
**************************************** */
      
get_newSuite(lgSuite){
    var randArr = getRandomArray(lgSuite, this.source.pieces-1);
    var newSuite = [];
    for(var h=0; h < randArr.length; h++){
        newSuite.push(this.colImg[randArr[h]]);
    }
//alert(`===>getRandomArray : ` + arr.join('-'));
//alert(`===>get_newSuite : \n` + newSuite.join("\n"));
    return newSuite;
}

}


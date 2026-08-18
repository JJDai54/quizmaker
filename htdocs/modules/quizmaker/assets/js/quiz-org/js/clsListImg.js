/*********************************************
 * ================ class clsListImg =========
 * *******************************************/

class clsListImg extends clsGridImg{
source = null;
cible = null;
urlImg = ''; 
colImg = [];


 constructor(urlImg, rows, cols) {
 }
 
/*********************************************
 * 
 * *******************************************/
getVersion(msg = 'ok'){return `clsListImg : v 1.1 - 2026-05-28 - ${msg}`;}
 
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
get_pieces(memoryId, answers, options, shuffle){
    var cellArr = [];
    var x = 0, y=0;
    var posX=0, posY=0;
    var numPiece = 0;
    var attributs = '';
    var numImage = 0;
    var events=`ondragstart='memory_dragstart(event);'
    onclick='memory_onclick(event, ${this.slideNumber});'`;
//alert(`memory_get_pieces : options.bgW = ${options.bgW} - options.bgH  = ${options.bgH }`);

    for(var k in answers){
        var ans =  answers[k];
        var imgUrl = `${quiz_config.urlQuizImg}/${ans.image1}`;
        numImage++;    
//alert(`memory_get_pieces [${k}] : urlImg = ${imgUrl}`);

        attributs = `piece="true" numPiece=${numPiece} numImage=${numImage} status='0' points='${ans.points}' imgUrl='${imgUrl}'`;
        //var style = `background-image:url('${imgUrl}');`;
        var style = ``;
        for(var i = 0; i< options.doublons; i++){
            cellArr.push(`<div id="${memoryId}_piece_${numPiece}_${i}" ${attributs} class="${memoryId}_divDragable" ${events} style="${style}"></div>`); 
        }
        //cellArr.push(`<div id="${memoryId}_piece_${numPiece}b" ${attributs} draggable="true" class="${memoryId}_divDragable" ${events} style="background-position: -${x}px -${y}px;"></div>`);
        numPiece++;
    }
    
        
    if(shuffle) {
        for(var i = 0; i < 5; i++){
        cellArr.sort(() => Math.random() - 0.5)
        }
    };  
     return cellArr; 
}

}

    


//alert('zzz');
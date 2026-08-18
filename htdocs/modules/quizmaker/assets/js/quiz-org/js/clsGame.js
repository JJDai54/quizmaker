class clsGame {

/* *************************************
function getImgSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
static getImgSize(imgUrl, options = null){

    let imgOb = new Image();
    imgOb.src = imgUrl;
    imgOb.style.display = 'none';

imgReady(imgOb, fonction_de_rappel );
// if (!imgOb.complete) {
//     alert('image non chargées');
// }
//imgOb.onload = function() {
  //alert(imgOb.width)};
    if(options){
      options.imgWidth  = imgOb.naturalWidth;
      options.imgHeight = imgOb.naturalHeight;
      options.imgRatio  = imgOb.naturalHeight/imgOb.naturalWidth;
    }
    
   var ratio = (imgOb.naturalHeight/imgOb.naturalWidth).toFixed(2);
   var sizeArr = {w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth, r: ratio};   
   
   //console.log(`===> getImgSize : imgUrl = ${imgUrl}\n w = ${sizeArr.w}\n h = ${sizeArr.h}\n r = ${sizeArr.r}`);
    
    return sizeArr;
}

/* *************************************
function getImgObjSize : renvoi un tableau avec les dimentions originale de l'image et le ration lha    uteur/largeur
imgUrl : url de l'image a découper

**************************************** */
static getImgObjSize(imgOb){

    sizeArr={w:imgOb.naturalWidth, h:imgOb.naturalHeight, rhw: imgOb.naturalHeight/imgOb.naturalWidth};   
    return sizeArr;
}

/* *************************************

**************************************** */
static options_tostring(options, bolAlert = true){
    var arr = [];

    for(var key in options)
    {
        arr.push(`${key} = ${options[key]}`);
    }   
     
    if (bolAlert){
        alert(arr.join("\n"));
    }
    return arr.join("\n");
}

/* *************************************

**************************************** */
static showMouchard(gameId, selecteur, attArr, foreColor = 'white'){
    var obMemory = document.getElementById(gameId);

    var allPieces = obMemory.querySelectorAll(selecteur);
    var attName = '';
    
    for(var h = 0; h < allPieces.length; h++){
        var obPiece = allPieces[h];
        var t = [];
        for (var i=0; i < attArr.length; i++){
            attName = attArr[i];
            
            if(i == 0){
                t.push(`<b>${attName} : ${obPiece.getAttribute(attName)}</b>`);
            }else{
                t.push(`${attName} : ${obPiece.getAttribute(attName)}`);
            }
            
        }
        t.push(`${foreColor}` );
        
        obPiece.innerHTML = `<span style="color:${foreColor};">` + t.join('<br>') + '</span>';
    }
}

} // =========== FIN DE LA CLASSE =====================


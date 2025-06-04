
var idPicker = 'colorPicker';
var colorArr = ['red','green','blue','cyan','yellow','braun','lime', 'violet','magenta','saumon',
                'fushia','yellowgreen', 'rosybrown','sienna','mistyrose','lightgray','white','black','maroon','orange'];
/* ************************************
*
* **** */
function get_color_picker(){
    var obPicker = document.getElementById(idPicker);
    if(obPicker) {return obPicker;}
    
    var nbColorByCol = 5;
    var wPicker = 120;
    var wColor = wPicker / nbColorByCol;

    
    obPicker = document.createElement("div");
    obPicker.id = idPicker;
    obPicker.setAttribute('picker', '0');
    obPicker.setAttribute('idImg', '');
    obPicker.style.position = 'absolute';
    obPicker.style.width = wPicker + 'px';
    obPicker.style.height = 'auto';
    obPicker.style.background = 'grey';
    obPicker.style.display='none';
    //obPicker.onblur ='PickerColor_close';
    obPicker.addEventListener('mouseleave', function (event){PickerColor_close(event);});

    //obPicker.addEventListener('click', function (event){imagesColor_showPicker(event,0);});
    //----------ajout des ciouleur
     for(var k = 0; k < colorArr.length; k++){
        var kolor = colorArr[k];
        console.log(`get_color_picker :  = color ${kolor}`);
            var obColor = document.createElement("div");
    obColor.style.position = 'relative';
            obColor.style.float = 'left';
            obColor.style.width = wColor + 'px';
            obColor.style.height = wColor + 'px';
            obColor.style.background = kolor;
            obColor.style.display='block';
            //obColor.cursor = 'crosshair';
            //obColor.innerHTML = k;
            obColor.addEventListener('click', function (event){imagesColor_selectColor(event,setBorderColor);});
    //obColor.onblur ='PickerColor_close';
    //obColor.addEventListener('mouseout', function (event){PickerColor_close(event);});
            obPicker.appendChild(obColor);
//             var m = (k*1+1) % nbColorByCol;
//             console.log(`get_color_picker : k = ${k} - nbColorByCol = ${nbColorByCol} - m = ${m}`);
//         if( ((k+1) % nbColorByCol) == 0 ){obPicker.appendChild(document.createElement("br"));}
//         if( ((k+1) % nbColorByCol) == 0 ){obPicker.appendChild(document.createElement("br"));}
//                  
     }
    
    
    //------------------------------------------
    
    
    document.body.appendChild(obPicker);
    return obPicker;
}
/* ******************************************************************** */
/* ============ evenements ============================================ */
/* ******************************************************************** */
function PickerColor_close(e){
if(e.currentTarget.id = obPicker.id){
    imagesColor_showPicker(event);
    //alert('PickerColor_close : ' + e.currentTarget.id);
}
}
function imagesColor_showPicker(e, etat=0, chrono=0){
//    alert('imagesColor_showPicker : etat = ' + etat);
    obPicker = get_color_picker();
    obPicker.setAttribute('chrono', chrono);
    obSource =  e.currentTarget;
    //obDivColor =  e.currentTarget;
// console.log("imagesColor_showPicker = " + obPicker.id);
// console.log('top = ' +  obDivImg.id + " = " +  obDivImg.offsetTop);
// console.log('==================');
//obSource.appendChild(obPicker);
    if (etat == 1){
        position = getAbsolutePosition(obSource);
    
        obPicker.style.display='block';
       // obPicker.style.top = e.currentTarget.style.top;
        obPicker.style.left = position.x + "px";
        obPicker.style.top =  (position.y + obSource.offsetHeight) + "px";
//         obPicker.left = "10px";
        obPicker.setAttribute('obSourceId', e.currentTarget.id)
   //alert('imagesColor_showPicker : ' + obPicker.style.left + '-' + obPicker.style.top);
    }else{
        obPicker.style.display='none';
    }
}
/* ******************************************************************** 
 * evenement affecté au colorPicker
 * affecte la color choisie à l'objet passe en parametre idImg
 * ******************************************************************** */
function imagesColor_selectColor(e,callback){
    var obPicker = get_color_picker();
    var chrono = obPicker.getAttribute('chrono');
    var obDivColor =  e.currentTarget;
    
    var obSourceId = obPicker.getAttribute('obSourceId');

    document.getElementById(obSourceId).style.background = obDivColor.style.background;
    obPicker.style.display='none';
    callback(chrono, obDivColor.style.background);
    e.stopPropagation();    
}

/* ***************************************
algorithme qui calcul la position absolu d'un div sur une page html 
**************************************** */
function getAbsolutePosition(element) {
  let x = 0;
  let y = 0;
  let currentElement = element;

  while (currentElement && currentElement !== document.body) {
    x += currentElement.offsetLeft;
    y += currentElement.offsetTop;
    currentElement = currentElement.offsetParent;
  }

  return { 'x': x, 'y': y };
}


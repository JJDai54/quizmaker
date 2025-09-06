//alert("findObject.js chargé")


/* ***************************************
        fonction de l'appplication
**************************************** */
var mydiv1 = null;
var mydiv2 = null;
var mousePos = {'x':0, 'y':0}; //coordonnées relative de la souris par rapport à l'image(myDiv)
var colTouches = null;
var messages = null;

function domReady(f) {
  if (document.readyState === 'complete') {
    f();
  } else {
    document.addEventListener('DOMContentLoaded', f);
  }
}
function initFindObject(){

    messages = new Messages('log');

    mydiv1 = document.getElementById("divImg_answers_image1");
    mydiv1.onmousemove = mousemove;
    mydiv1.onclick = eventSetNewTouche;

    mydiv2 = document.getElementById("divImg_answers_image2");
    mydiv2.onmousemove = mousemove;
    mydiv2.onclick = isToucheOk;
    
    colTouches = new Touches(0, 0, mydiv1, mydiv2);
/*
    if(defaultValues.defaultWidth)          colTouches.toucheSize.w = defaultValues.defaultWidth;
    if(defaultValues.defaultHeight)         colTouches.toucheSize.h = defaultValues.defaultHeight;
    if(defaultValues.defaultBorderWidth)    colTouches.toucheSize.borderWidth = defaultValues.defaultBorderWidth;
    if(defaultValues.defaultBorderRadius)   colTouches.toucheSize.borderRadius = defaultValues.defaultBorderRadius;
    if(defaultValues.maxTouches)            colTouches.maxTouches = defaultValues.maxTouches;
*/
    //pas besoin de tester l'existence de la clé d'autant que si 0 renvooie faux
    colTouches.toucheSize.w = defaultValues.defaultWidth;
    colTouches.toucheSize.h = defaultValues.defaultHeight;
    colTouches.toucheSize.borderWidth = defaultValues.defaultBorderWidth;
    colTouches.toucheSize.borderRadius = defaultValues.defaultBorderRadius;
    colTouches.maxTouches = defaultValues.maxTouches;
    
//alert(defaultValues.defaultBorderRadius);
//alert(colTouches.toucheSize.borderRadius);
    loadDataFromBuffer();   
    setTimeout("refreshImg()",900);
}

function loadDataFromBuffer(){

    document.getElementById('coordonnees').innerHTML = '';
    colTouches.removeAll();             
    for(var k = 0; k < bufferArr.length; k++){
        clTouche = colTouches.addNewFromBufferPHP(bufferArr[k]);
        //alert(bufferArr[k]);             
        addNewCoordonnees(clTouche);
        addNewDivTouche(mydiv1,clTouche);
        clTouche.moveDiv(mydiv1, 1);
    }
}
function refreshImg(){
    colTouches.refresh(mydiv1); 

}

/* ***************************************

**************************************** */
function isToucheInFlag(obSource, x, y){
    var touche = colTouches.findToucheInXY(obSource, x, y, true);
    
    if(touche){
        var exp = messages.getNum(5,[touche.chrono, touche.caption]);
        var bgColor = 'lime'
    }else{
        var exp = messages.getNum(4,[x, y]);
        var bgColor = 'red'
    }
    var resultat = document.getElementById('resultat')
    resultat.innerHTML = exp;
    resultat.style.background = bgColor;
    return touche;
}

/* ***************************************

**************************************** */
function addNewTouche(obSource, x, y){
    var newTouche = colTouches.addNew(x, y, obSource.offsetWidth, obSource.offsetHeight);
    if(newTouche){
      console.log(`la touche ${newTouche.chrono} - ${newTouche.caption} a été ajoutée`);
      newTouche.toString(true);

      addNewCoordonnees(newTouche);
      addNewDivTouche(obSource,newTouche);
      return newTouche;
    }else{
      console.log(messages.getNum(2));
      return null;
    }

}


/* ***************************************

**************************************** */
function addNewCoordonnees(clTouche){
var sizeNum = 5;
    var divCoordonnees = document.getElementById('coordonnees');  
    newTr = document.createElement("tr");
    newTr.setAttribute('chrono', clTouche.chrono);
    newTr.addEventListener('mouseover', function (event){onHoverTr(event,clTouche.chrono);});
    newTr.addEventListener('mouseleave', function (event){onMouseLeaveTr(event);});
    divCoordonnees.appendChild(newTr);
    //-----------------------------------------
    
    var newInp = document.createElement("input");
    newInp.id = clTouche.getId('btnDelete');
    newInp.name = clTouche.getId('btnDelete');
    newInp.setAttribute('type', 'button');
    newInp.setAttribute('value', 'X');
    newInp.setAttribute('chrono', clTouche.chrono);
    newInp.setAttribute('size', 8);
    newInp.style.width = '25px';
    newInp.style.height = '25px';
    newInp.style.background = 'saumon';
    newInp.setAttribute('action', 'delete');
    newInp.addEventListener('click', function (event){delete_touche(event,clTouche.chrono);});
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    var newInp = document.createElement("input");
    newInp.id = clTouche.getId('caption');
    newInp.name = clTouche.getId('caption');
    newInp.setAttribute('type', 'text');
    newInp.setAttribute('value', clTouche.caption);
    newInp.setAttribute('size', 16);
    newInp.setAttribute('title', clTouche.caption);
    //document.getElementById('findObject_title').appendChild(newInp);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    //-------------------------------------------------------    
    var newInp = document.createElement("input");
    newInp.setAttribute('type', 'hidden');
    newInp.id = clTouche.getId('answerId');
    newInp.name = clTouche.getId('answerId');
    newInp.setAttribute('value', 0);
    //document.getElementById('findObject_title').appendChild(newInp);
    newTd.appendChild(newInp);
    
    var newInp = document.createElement("input");
    newInp.id = clTouche.getId('btnBorderColor');
    newInp.name = clTouche.getId('btnBorderColor');
    newInp.setAttribute('type', 'button');
    newInp.setAttribute('value', '');
    newInp.setAttribute('chrono', clTouche.chrono);
    //newInp.setAttribute('size', 8);
    newInp.style.width = '25px';
    newInp.style.height = '25px';
    newInp.style.background = clTouche.getBorderColor();
    newInp.setAttribute('action', 'color');
    newInp.addEventListener('click', function (event){imagesColor_showPicker(event,1, clTouche.chrono);});
    //newInp.addEventListener('click', function (event){event_move_touche(event);});
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);

    var newInp = document.createElement("input");
    newInp.setAttribute('type', 'hidden');
    newInp.id = clTouche.getId('borderColor');
    newInp.name = clTouche.getId('borderColor');
    newInp.setAttribute('value', clTouche.getBorderColor());
    newTd.appendChild(newInp);

    var newInp =  getNewInpNumber(clTouche.getId('x'), clTouche.x, clTouche.id, clTouche.chrono );
    newInp.setAttribute('action', 'moveX');
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);

    var newInp =  getNewInpNumber(clTouche.getId('y'), clTouche.y, clTouche.id, clTouche.chrono );
    newInp.setAttribute('action', 'moveY');
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    var newInp =  getNewInpNumber(clTouche.getId('w'), clTouche.w, clTouche.id, clTouche.chrono );
    newInp.setAttribute('action', 'redimW');
    newInp.style.background = '#C0F0FF';
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    var newInp =  getNewInpNumber(clTouche.getId('h'), clTouche.h, clTouche.id, clTouche.chrono );
    newInp.setAttribute('action', 'redimH');
    newInp.style.background = '#C0F0FF';
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    var newInp =  getNewInpNumber(clTouche.getId('borderRadius'), clTouche.borderRadius, clTouche.id, clTouche.chrono );
    newInp.setAttribute('min', 0);
    newInp.setAttribute('max', 50);
    newInp.setAttribute('action', 'redimBR');
    newInp.style.background = '#E0FF80';
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    var newInp =  getNewInpNumber(clTouche.getId('borderWidth'), clTouche.borderWidth, clTouche.id, clTouche.chrono );
    newInp.setAttribute('min', 1);
    newInp.setAttribute('max', 12);
    newInp.setAttribute('action', 'redimBW');
    newInp.style.background = '#E0FF80';
    newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    //----------------------------------------------------------------------
    var newInp =  getNewInpNumber(clTouche.getId('points'), clTouche.points, clTouche.id, clTouche.chrono );
    newInp.setAttribute('min', -12);
    newInp.setAttribute('max', 12);
    newInp.style.background = '#FFF0A0';
    //newInp.setAttribute('action', '');
    //newInp.addEventListener('change', event_move_touche);
    newTd = document.createElement("td");
    newTd.appendChild(newInp);
    newTr.appendChild(newTd);
    
    //----------------------------------------------------------------------
    //ajout d'information utile à la récupéraion lors du chargement du formulaire et au passage au quiz
    var newInp = document.createElement("input");
    newInp.setAttribute('type', 'hidden');
    newInp.id = clTouche.getId('wRef');
    newInp.name = clTouche.getId('wRef');
    newInp.setAttribute('value', clTouche.wRef);
    newTd.appendChild(newInp);

    var newInp = document.createElement("input");
    newInp.setAttribute('type', 'hidden');
    newInp.id = clTouche.getId('hRef');
    newInp.name = clTouche.getId('hRef');
    newInp.setAttribute('value', clTouche.hRef);
    newTd.appendChild(newInp);

    var message = clTouche.x + ' x ' + clTouche.y;
console.log('===>' + message);
}
/* ***************************************

**************************************** */
function getNewInpNumber(id, value, toucheid, chrono ){
var sizeNum = 3;

    var inp = document.createElement("input");
    inp.id=id;
    inp.name=id;
   //inp.setAttribute('name', id);
    inp.setAttribute('type', 'number');
    //inp.setAttribute('poil', '1');
    inp.setAttribute('size', sizeNum);
    inp.setAttribute('value', value);
    inp.setAttribute('min', '0');
    inp.setAttribute('toucheid', toucheid);
    inp.setAttribute('chrono', chrono);
    inp.setAttribute('maxWidth', '50px');
    inp.style.textAlign = 'right';
    inp.classList.add("inpNumber");
    return inp;
}

/* ***************************************

**************************************** */
function addNewDivTouche(obSource, touche){

    var newDiv = touche.getNewDivTouche(obSource,1);
    var newDiv = touche.getNewDivTouche(mydiv2,2);
}

/* ***************************************

**************************************** */
function pix(v){
    return v + 'px';
}

domReady(initFindObject);

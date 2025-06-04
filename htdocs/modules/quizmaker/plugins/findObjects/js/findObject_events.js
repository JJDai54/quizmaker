//alert("findObject_events");

/* ***************************************
        gestion des evenements
**************************************** */
function mousemove (e){
  var divimg = e.currentTarget;
  mousePos.x = e.clientX + window.pageXOffset;
  mousePos.y = e.clientY + window.pageYOffset;
  
  var x = Math.round(mousePos.x - divimg.offsetLeft);
  var y = Math.round(mousePos.y - divimg.offsetTop);

  document.getElementById("message").innerHTML = 
            "Pos x = " + e.offsetX + "/" + x + " - " 
          + "Pos y = " + e.offsetY + "/" + y + " - " 
          + 'nb objects : ' + colTouches.length;
}
/* ***************************************

**************************************** */
function isToucheOk(e)
{
console.log("=====>isToucheOk");
    touche = isToucheInFlag(e.currentTarget, e.offsetX, e.offsetY);
    
    if(touche){
        addNewDivTouche(e.currentTarget, touche);
    }
}


/* ***************************************

**************************************** */
function eventSetNewTouche(e)
{
    addNewTouche(e.currentTarget, e.offsetX, e.offsetY);
    //alert('eventSetNewTouche');
}

/* ***************************************

**************************************** */
function delete_touche(e, chrono){
//alert(`delete_touche : ${chrono}`);
    
    var clTouche = colTouches.getToucheByChrono(chrono);
    var k = clTouche.index;
    var nbp = colTouches.length;
    
    clTouche.divTouche[1].remove();
//alert(`delete_touche : chrrono =  ${chrono} - k = ${k} - nbp = ${nbp} - divToucheId2 = ${clTouche.divToucheId2}`);
    clTouche.divTouche[2].remove();

    var tdArr = document.getElementById('coordonnees').children;
    tdArr[k].remove(); 
    var clTouche = colTouches.remove(k);
    clTouche = null;
}
/* ***************************************

**************************************** */
function event_update_whRef(e, idDivImg1){
    var obSource = document.getElementById(idDivImg1);
//    colTouches.updateSizeRef(obSource);

    var wRef = obSource.offsetWidth;
    var hRef = obSource.offsetHeight;
    
    
    for(var h = 0; h < colTouches.length; h++){
        var clTouche = colTouches.collection[h];
        var idWref = clTouche.getId('wRef');
        document.getElementById(idWref).value = wRef;
        var idWref = clTouche.getId('hRef');
        document.getElementById(idWref).value = hRef;
        
    }
    
}
/* ***************************************

**************************************** */
function event_move_touche(ev){
   // alert('event_move_touche');
    var inp = ev.currentTarget;
    //var divTouche = document.getElementById(inp.getAttribute('toucheid'));
    var action = inp.getAttribute('action');
    var chrono = inp.getAttribute('chrono');
    var value = inp.value*1;
    
//alert(chrono = chrono)    ; 
//alert ('nbTouches = ' + colTouches.collection.length);
    var clTouche = colTouches.getToucheByChrono(chrono);

switch(action){
    case 'moveX'  : clTouche.x = value ; break;
    case 'moveY'  : clTouche.y = value ; break;
    case 'redimW' : clTouche.w = value ; break;
    case 'redimH' : clTouche.h = value ; break;
    case 'redimBR': clTouche.borderRadius = value ; break;
    case 'redimBW': clTouche.borderWidth  = value ; break;
} 
    
    clTouche.moveDiv(mydiv1,1);
    clTouche.moveDiv(mydiv2,2);
    
    
    console.log (`============\nid = ${inp.id}\n`
                +`chrono = ${chrono}\n` 
                +`toucheId = ${inp.getAttribute('toucheid')}\n` 
                +`action = ${action}\n`
                +`value = ${inp.value}\n-------------`);  

}

function clearDivTouche(numImage = 0){
    colTouches.clearAllDivTouches(numImage);
}

function setBorderColor(chrono, kolor){
    var cltouche = colTouches.getToucheByChrono(chrono);
    cltouche.setBorderColor(kolor);
    //alert(cltouche.getId('idden'));
    document.getElementById(cltouche.getId('borderColor')).value = kolor;
              
}

function onHoverTr(e, chrono){
    //alert('onHoverTr');
    clTouche = colTouches.getToucheByChrono(chrono);
    obDivTouche = clTouche.divTouche[1];
    obDivTouche.style.background = clTouche.borderColor;
    obDivTouche.classList.add('fade-in-element');
    setTimeout(remove_fadin,800,chrono);
    
}
function remove_fadin(chrono){
    clTouche = colTouches.getToucheByChrono(chrono);
    obDivTouche = clTouche.divTouche[1];
    obDivTouche.classList.remove('fade-in-element');
    obDivTouche.style.background = 'transparent';
    
//obDiv.border="red 2px solid";
//alert('ok');
}

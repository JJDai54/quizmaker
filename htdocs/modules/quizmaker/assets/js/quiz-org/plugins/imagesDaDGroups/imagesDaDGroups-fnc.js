/* ******************************************************************** */
/*       Fonction de Drag And drop sur des images                       */
/* https://www.javascripttutorial.net/web-apis/javascript-drag-and-drop */
/* ******************************************************************** */
var imagesDaDGroups_dataTransfer = "text";

function imagesDaDGroups_start(e, isDiv=false){
console.log("===> dad => " + "imagesDaDGroups_start" + " - " + e.target.getAttribute("id"));

    e.dataTransfer.setData(imagesDaDGroups_dataTransfer, e.target.parentNode.getAttribute("id"));
    
    //seul firefox peut acceder aux valeurx de dataTransfer dans le over
    //mais il y en a besoin pour idenfier le group survolé
    //alors stockage dans une balise "input type=hidden" globale
    set_param(e.target.parentNode.getAttribute("id"));
    
    blob("imagesDaDGroups_start : " + e.target.getAttribute("id") + " | " + e.target.getAttribute("src") );
    imagesDaDGroups_set_style(e.target.parentNode, 1);    
    e.dataTransfer.dropEffect = "move"; 
    return true;
   
}


/* *********************************** */
function imagesDaDGroups_over(e){
e.preventDefault();    
console.log("===> dad => " + "imagesDaDGroups_over");
    
    //seul firefox peut acceder aux valeurx de dataTransfer dans le over
    //mais il y en a besoin pour idenfier le group survolé
    //lors du start la valeur a ete stockée dans une balise "input type=hidden" globale
    idDivImg = get_param(0);
   // var idDivImg = e.dataTransfer.getData(imagesDaDGroups_dataTransfer);
    var obDivImg = document.getElementById(idDivImg);


//alert("===> dad => " + "imagesDaDGroups_over");
    var obOver = imagesDaDGroups_get_group(e.target);
    //var isGroup = (obOver.getAttribute("attSelGroup")=="1") ? true : false;
//     var zzz = (isGroup) ? "Oui" : "Non";
// console.log("===> dad => " + "imagesDaDGroups_over" + " - " + zzz + "-" + obOver.getAttribute("attSelGroup"));
//alert(idDivImg);   
 
if(obDivImg){
    var idParent = obDivImg.parentNode.getAttribute("id");

    
    //var idOver = obOver.getAttribute("id"); 
    
    if(obOver.id != idParent && obOver.isGroup){
      imagesDaDGroups_set_style(obOver.target, 1);      
      //alert(obOver.parentNode.firstChild.tagName);      
      //imagesDaDGroups_set_style(document.getElementById(idOver + "-span"), 1);      
    }
}else{
    if(obOver.isGroup){
      imagesDaDGroups_set_style(obOver.target, 1);      
      //alert(obOver.parentNode.firstChild.tagName);      
      //imagesDaDGroups_set_style(document.getElementById(idOver + "-span"), 1);      
    }
}
/*
*/
    e.dataTransfer.dropEffect = "copyMove"; 
    return false;
}

/* *********************************** */
function imagesDaDGroups_get_group(obOver, isGroup){
    var id = obOver.getAttribute("id");
    var obClone = document.getElementById(id);
    
    var isGroup = (obOver.getAttribute("attSelGroup")=="1") ? true : false;
     while (!isGroup){
         var obClone = obClone.parentNode;
         var isGroup = (obClone.getAttribute("attSelGroup")=="1") ? true : false;
         if (isGroup) {break;}
     }
    
    //return obClone;        
    return {'target':obClone, 'isGroup': isGroup, 'id':obClone.getAttribute("id")};
}

/* ************************************************************* */
function imagesDaDGroups_drop(e){
e.preventDefault();    
console.log("===> dad => " + "imagesDaDGroups_drop" + " | " + e.target.getAttribute("src"));
//alert("===> dad => " + "imagesDaDGroups_drop" + " | " + e.target.getAttribute("src"));
    obOver = imagesDaDGroups_get_group(e.target);
    
    idFrom = e.dataTransfer.getData(imagesDaDGroups_dataTransfer);
    var obDivImg = document.getElementById(idFrom);
        
    imagesDaDGroups_set_style(obDivImg , 0);
    imagesDaDGroups_set_style(obOver.target, 0);

    //deplace le div img dans le nouveau groupe    
    obOver.target.appendChild( obDivImg);
    //-----------------------------------------------
    
    computeAllScoreEvent();
    e.stopPropagation();
    return false;

}
/* *********************************** */
function imagesDaDGroups_leave(e){
console.log("===> dad => " + "imagesDaDGroups_leave");
    var isGroup = (e.target.getAttribute("attSelGroup")=="1") ? true : false;
    if(isGroup){
      imagesDaDGroups_set_style(e.target, 0);
    }

    return true;

}
/* *********************************** */
function imagesDaDGroups_end(e){
console.log("===> dad => " + "imagesDaDGroups_end");
    var idDivImg = e.dataTransfer.getData(imagesDaDGroups_dataTransfer);
    var obDivImg = document.getElementById(idDivImg);
    imagesDaDGroups_set_style(obDivImg, 0);
    
    return true;
    

}

/* *********************************** */
function imagesDaDGroups_set_style(ob, numStyle, mod = 2){
console.log("===> dad => " + "imagesDaDGroups_set_style");
    var oldStyle = ((numStyle*1)+1) % mod;

    ob.classList.remove('imagesDaDGroups_div' + oldStyle);
    ob.classList.add('imagesDaDGroups_div' + numStyle);
    //ob.classList.style.border="5px";
    
    var isGroup = (ob.getAttribute("attSelGroup")=="1") ? true : false;
    if(isGroup){
        var idGroup = ob.getAttribute("id");      
console.log (idGroup + " ===> " + 'imagesDaDGroups_div' + numStyle);
       imagesDaDGroups_set_style(document.getElementById(idGroup + "-span"), numStyle);      
    }

}

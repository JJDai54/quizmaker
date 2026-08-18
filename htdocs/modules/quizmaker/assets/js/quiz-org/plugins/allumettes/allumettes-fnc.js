
let allumette_goodAns = 1;
let alRect;

let draggedElement = null;
let startOffsetX, startOffsetY;

/* ***************************************************************** */
/*        ajout JJD evennement pour actualiser le tableau $option    */
/*         afin de garder la même logique de structure du plugin     */
/* ***************************************************************** */

/**
 * Vérifie si deux tableaux d'objets sont équivalents 
 * en comparant leurs propriétés x, y et rotation.
 */

function sontEquivalents2(tab1, tab2) {

    // On filtre une seule fois
/*
    const actifs1 = tab1.filter(a => a.status == 1);
    const actifs2 = tab2.filter(b => b.status == 1);
*/
const actifs1 = tab1
    .filter(obj => obj.status == 1) // 1. On ne garde que les éléments avec status == 1
    .map(obj => ({                  // 2. On transforme les éléments restants
        x : obj.x, 
        y : obj.y, 
        rotation: (obj.rotation + 360) % 180,
        status : obj.status
    }));

const actifs2 = tab2
    .filter(obj => obj.status == 1) // 1. On ne garde que les éléments avec status == 1
    .map(obj => ({                  // 2. On transforme les éléments restants
        x : obj.x, 
        y : obj.y, 
        rotation: (obj.rotation + 360) % 180,
        status : obj.status
    }));


  if (actifs1.length !== actifs2.length) {
    console.log(`les deux tableaux n'ont pas la même taille : \n ${actifs1.length} <===> ${actifs2.length}`);
    //alert(`les deux tableaux n'ont pas la même taille : \n ${tab1.length} <===> ${tab2.length}`)
    return false;
  }
   objToString(actifs1, '', 0, false); 
   objToString(actifs2, '', 0, false); 
/*
*/
   
   
    return actifs1.every(obj1 => {
        return actifs2.some(obj2 => 
            obj1.x === obj2.x && 
            obj1.y === obj2.y && 
            ((obj1.rotation+360) % 180) === ((obj2.rotation+180) % 180) &&
            obj1.status === obj2.status
        );
    });
}



function  setMouchard(exp1, exp2=''){
    var fullExp = `===>${exp1} / ${exp2}`;
//    document.getElementById('mouchard').innerHTML = fullExp;
}


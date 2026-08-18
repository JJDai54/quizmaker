let memoire1 = null, memoire2 = null, currentGridSize = 30, rotationAngle = 22.5, deleteMode = false;
let memoireArr = [null, null, null, null, null];
let currentMemory = 0;

var idPlateau = "allumettes_plateau";
let draggedElement = null;
let startOffsetX, startOffsetY;

// memoireArr[0] memoireArr[1]
// Normalise l'angle pour que 0, 180, 360 soient identiques (Modulo 180)
const normalizeAngle = (deg) => {
    let val = parseFloat(deg) % 180;
    if (val < 0) val += 180;
    return val;
};



function showStatus(msg) {
    const el = document.getElementById('allumettes_statusMessage');
    el.innerText = msg;
    setTimeout(() => { el.innerText = ''; }, 2000);
}

function comparerEtats() {
    const container = document.getElementById('rapportContainer');
    if (!memoireArr[0]) { container.innerHTML = lib_mem1_undifined; return; }
    
    const actuel = Array.from(document.querySelectorAll('.allumette')).map(a => ({
        x: parseInt(a.style.left), y: parseInt(a.style.top), rotation: parseFloat(a.dataset.rotation), statux: a.dataset.status
    }));
    
    let html = `<table><tr><th>ID</th><th>X</th><th>Y</th><th>Rot (mod 180)</th></tr>`;
    actuel.forEach((a, i) => {
        const m = memoireArr[0][i] || {x: null, y: null, rotation: null};
        const rotA = normalizeAngle(a.rotation);
        const rotM = m.rotation !== null ? normalizeAngle(m.rotation) : null;
        
        // La différence est marquée si la position change OU si la rotation normalisée change
        const isDiff = (a.x !== m.x || a.y !== m.y || Math.abs(rotA - rotM) > 0.1);
        
        html += `<tr class="${isDiff ? 'allumettes_diff' : ''}">
            <td>${i + 1}</td><td>${a.x}</td><td>${a.y}</td><td>${a.rotation.toFixed(0)}°</td>
        </tr>`;
    });
    container.innerHTML = html + "</table>";
}

function appliquerConfig() {
    document.documentElement.style.setProperty('--plateau-w', document.getElementById('w').value + 'px');
    document.documentElement.style.setProperty('--plateau-h', document.getElementById('h').value + 'px');
    document.documentElement.style.setProperty('--grid-size', document.getElementById('g').value + 'px');
    document.documentElement.style.setProperty('--match-h', document.getElementById('mh').value + 'px');
    document.documentElement.style.setProperty('--match-w', document.getElementById('mw').value + 'px');
    currentGridSize = parseInt(document.getElementById('g').value);
    if(document.getElementById('rot').value == 0){
        rotationAngle = 0;
    }else{
        //alert(document.getElementById('rot').value + "-" + 360 / document.getElementById('rot').value)
        rotationAngle = parseFloat(360 / document.getElementById('rot').value);
    }
    //rotationAngle = parseFloat(document.getElementById('rot').value);
    //alert(rotationAngle);
}

function appliquerConfigFromPlugin() {
    document.documentElement.style.setProperty('--plateau-w', document.getElementById('w').value + 'px');
    document.documentElement.style.setProperty('--plateau-h', document.getElementById('h').value + 'px');
    document.documentElement.style.setProperty('--grid-size', document.getElementById('g').value + 'px');
    document.documentElement.style.setProperty('--match-h', document.getElementById('mh').value + 'px');
    document.documentElement.style.setProperty('--match-w', document.getElementById('mw').value + 'px');
    currentGridSize = parseInt(document.getElementById('g').value);
    rotationAngle = parseFloat(document.getElementById('rot').value);
}

function toggleDeleteMode(lib_remove_on, lib_remove_off) {
    deleteMode = !deleteMode;
    document.getElementById('allumettes_plateau').classList.toggle('allumettes_mode_delete');
    document.getElementById('btnDeleteMode').innerText = deleteMode ? lib_remove_on : lib_remove_off;
}

function ajouterAllumette(x = 0, y = 0, rot = 0, status = 1) {
    const div = document.createElement('div');
    div.className = 'allumette';
    div.style.left = x + 'px'; div.style.top = y + 'px';
    div.dataset.rotation = rot;
    div.style.transform = `rotate(${rot}deg)`;
    div.dataset.status = status;
    div.oncontextmenu = "return false;"; 
    var bgTete = (status == 1) ? 'red' : 'black';
    div.innerHTML = `<div class="allumettes_tete" style='background:${bgTete}' oncontextmenu="return false;" ></div>`
                  + `<div class="allumettes_corps" oncontextmenu="return false;" >`
                  + `</div><button class="allumettes_del_btn" onclick="this.parentElement.remove(); comparerEtats();">×</button>`;
    
    div.onmousedown = (e) => {
        if (deleteMode) return;
        if (currentMemory != 0 && status == 0) return;
        e.stopPropagation();
        
        // Rotation clic droit (inverse)
        if (e.button === 2) { e.preventDefault(); rotate(div, -rotationAngle); return; }
        
        let isMoving = false;
        var alRect = div.getBoundingClientRect();
        let shiftX = e.clientX*1 - alRect.left*1;
        let shiftY = e.clientY*1 - alRect.top*1;
        /////////////////////////////////////////
            var plateau = document.getElementById(this.idPlateau);
        
            draggedElement = e.currentTarget;
            const rectPlateau = plateau.getBoundingClientRect();
            
            // On calcule l'offset par rapport au coin du plateau
            startOffsetX = e.clientX - rectPlateau.left - parseFloat(draggedElement.style.left);
            startOffsetY = e.clientY - rectPlateau.top - parseFloat(draggedElement.style.top);
        /////////////////////////////////////////
        const move = (e) => {
            isMoving = true;
 

        var newDiv = div;//e.currentTarget;
        var alRect = newDiv.getBoundingClientRect();
        const rectPlateau = plateau.getBoundingClientRect();
        
        let targetX = e.clientX - rectPlateau.left - startOffsetX;
        let targetY = e.clientY - rectPlateau.top - startOffsetY;

        // Magnétisme
        targetX = Math.round(targetX / currentGridSize) * currentGridSize;
        targetY = Math.round(targetY / currentGridSize) * currentGridSize;
        
        //deplacement interdit en dehors du plateau
/*
        if (targetX < newDiv.offsetWidtht/2 - currentGridSize) {targetX = -newDiv.offsetWidtht/2 + currentGridSize;}       
        else if(targetX > (rectPlateau.width -newDiv.offsetWidtht/2 - currentGridSize)) {targetX = (rectPlateau.width -newDiv.offsetWidtht/2 - currentGridSize);}
*/
        if (targetX < 0) {targetX = 0;} 
        else if(targetX > (rectPlateau.width - alRect.width/2 - currentGridSize)) {targetX = rectPlateau.width - alRect.width/2 - currentGridSize;}


        if (targetY < -newDiv.offsetHeight/2 + currentGridSize) {targetY = -newDiv.offsetHeight/2 + currentGridSize;}       
        else if(targetY > (rectPlateau.height -newDiv.offsetHeight/2 - currentGridSize)) {targetY = (rectPlateau.height -newDiv.offsetHeight/2 - currentGridSize);}

//setMouchard(targetX, targetY);
        draggedElement.style.left = targetX + 'px';
        draggedElement.style.top  = targetY + 'px';

        document.getElementById('allumette_info').innerHTML = `x = ${targetX} | y = ${targetY}| r = ${div.dataset.rotation}`;
            
        };
        
        const up = (e) => {
            document.removeEventListener('mousemove', move);
            // Rotation clic gauche (simple clic uniquement)
            if (!isMoving && e.button === 0) rotate(div, rotationAngle);
            comparerEtats();
        };
        document.addEventListener('mousemove', move);
        document.addEventListener('mouseup', up, {once: true});
    };
    div.oncontextmenu = (e) => e.preventDefault();
    document.getElementById('allumettes_plateau').appendChild(div);
    
    count_allumettes();
    comparerEtats();
}

function rotate(el, deg) {
    el.dataset.rotation = (parseFloat(el.dataset.rotation) || 0) + deg;
    el.style.transform = `rotate(${el.dataset.rotation}deg)`;
    comparerEtats();
}

function resetRotations() { 
    document.querySelectorAll('.allumette').forEach(a => { 
        a.dataset.rotation = 0; 
        a.style.transform = 'rotate(0deg)';
    }); comparerEtats(); 
}

/* ************************************************
*
* ************************************************* */
function memoriser(id) { 
    if(id == 0){
        selecteur = '.allumette';
    }else{
        selecteur = '.allumette[data-status="1"]';
    }
    const data = Array.from(document.querySelectorAll(`${selecteur}`)).map(a => ({x: parseInt(a.style.left), y: parseInt(a.style.top), rotation: parseFloat((a.dataset.rotation)), status: a.dataset.status}));
    memoireArr[id] = data; 
 
    
    //if (id === 1) { memoireArr[0] = data; comparerEtats(); } else memoireArr[1] = data;
    comparerEtats()
    //showStatus("Mém. " + id + " mémorisée !");
    showStatus((id == 1) ? lib_save_defi_ok : lib_save_solution_ok);
    
    
    //alert( JSON.stringify(data));
    document.getElementById(`answers[${id}][proposition-log]`).value = JSON.stringify(data);
    document.getElementById(`answers[${id}][proposition]`).value = JSON.stringify(data);
    //currentMemory = id;
}

function restaurer(id) { 
    //const data = (id === 1) ? memoireArr[0] : memoireArr[1];
    const data = memoireArr[id];
    document.getElementById('allumettes_plateau').innerHTML = '';
    currentMemory = id;
    count_allumettes();
    
    if(id != 0) {
        if(get_hidde_allumettes_fixed()==false) {
            const data0 = memoireArr[0];
            data0.filter(a => a.status == 0)
                .forEach(b => ajouterAllumette(b.x, b.y, b.rotation, b.status));
        }
     
        document.getElementById("btn-add-fixed").disabled = true; 
    }else{
        document.getElementById("btn-add-fixed").disabled = false; 
    }
    
    if (!data || data == '') return;
echo_plateau(id);    
    data.forEach(a => ajouterAllumette(a.x, a.y, a.rotation, a.status));
    comparerEtats();
}

/* ***************************************************************** */
/*        ajout JJD evennement pour actualiser le tableau $option    */
/*         afin de garder la même logique de structure du plugin     */
/* ***************************************************************** */
function delete_solution(id) { 

    currentMemory = id;
    if(confirm(lib_confirm_delete)){
        memoireArr[id] = null;
        document.getElementById('allumettes_plateau').innerHTML = '';
        document.getElementById(`answers[${id}][proposition]`).value = "";
        document.getElementById(`answers[${id}][proposition-log]`).value = "";
        memoriser(id);
        count_allumettes();
    }

}

/* ***************************************************************** */
/*        ajout JJD evennement pour actualiser le tableau $option    */
/*         afin de garder la même logique de structure du plugin     */
/* ***************************************************************** */
function restaurerFromPlugin() {
    for(var id = 0; id < 5; id++){
        try{
            var data = JSON.parse(document.getElementById(`answers[${id}][proposition]`).value);
            memoireArr[id] = data;
            //restaurer(id);
        }catch(err){
        }
    }
    restaurer(0);

}
function exporterJSON() { 
    const data = JSON.stringify(Array.from(document.querySelectorAll('.allumette')).map(a => ({x: parseInt(a.style.left), y: parseInt(a.style.top), rotation: parseFloat(a.dataset.rotation), status : a.dataset.status})));
    document.getElementById('importArea').value = data;
}

/*
$arr = [{"x":150,"y":90,"rotation":0},
        {"x":180,"y":90,"rotation":0},
        {"x":240,"y":90,"rotation":0},
        {"x":120,"y":90,"rotation":360},
        {"x":210,"y":90,"rotation":360}];
*/
async function collerPressePapier() {
    try {
        const items = JSON.parse(document.getElementById('importArea').value || await navigator.clipboard.readText());
        document.getElementById('allumettes_plateau').innerHTML = '';
        items.forEach(a => ajouterAllumette(a.x, a.y, a.rotation, a.status));
        comparerEtats();
    } catch (e) { showStatus("Erreur Import"); }
}

function update_gameWidth(ev){
    document.getElementById('quest_options[gameWidth]').value . value = ev.currentTarget.value;
}

function update_options(ev, optionName){
    document.getElementById(`quest_options[${optionName}]`).value = ev.currentTarget.value;
    appliquerConfig();    
}


function move_all_allumettes2(sens){

    document.querySelectorAll('.allumette').forEach(a => 
        { 
          switch(sens){
              case 'l': a.style.left = (a.offsetLeft - currentGridSize) + 'px'; break;
              case 'r': a.style.left = (a.offsetLeft + currentGridSize) + 'px'; break;
              case 't': a.style.top  = (a.offsetTop  - currentGridSize) + 'px'; break;
              case 'b': a.style.top  = (a.offsetTop  + currentGridSize) + 'px'; break;
          }
        }
    );
     comparerEtats(); 
}

function move_all_allumettes(sens){
    for(var h = 0; h < memoireArr.length; h++){
        try{
        for(i = 0; i < memoireArr[h].length; i++){
            var allumette = memoireArr[h][i];
          switch(sens){
              case 'l': allumette.x =  (allumette.x - currentGridSize); break;
              case 'r': allumette.x =  (allumette.x + currentGridSize); break;
              case 't': allumette.y  = (allumette.y  - currentGridSize); break;
              case 'b': allumette.y  = (allumette.y  + currentGridSize); break;
          }
        
        }
        }catch(err){
        }
        restaurer(currentMemory);
    }

     comparerEtats(); 
}
function test_solutions(){
    const data = Array.from(document.querySelectorAll('.allumette')).map(a => ({x: parseInt(a.style.left), y: parseInt(a.style.top), rotation: parseFloat(a.dataset.rotation), status: a.dataset.status}));
    var game = JSON.stringify(data); 

//     var id = 0;
//     var defi = JSON.stringify(memoireArr[id]);
    var idSolutionOk = 0;
    
    //on ne teste pas le défi lui même
    for(var id = 1; id < 5; id++){
        try{
          if(sontEquivalents2(data, memoireArr[id])){
            idSolutionOk = id; break;
          } 
        }catch(err){
        }
    }

    if(idSolutionOk > 0){
        alert (lib_good_solution.replace('{idSolution}', idSolutionOk+1));
    }else{
        alert (lib_bad_solution);
    }

}

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

function count_allumettes(){
    var id = currentMemory;
    var alRouges = 0
    var alNoires = 0;
    if(memoireArr[id]){
    console.log(`count_allumettes : id = ${id} nbTot = ${memoireArr[id].length}`);
    for(var h = 0; h < memoireArr[id].length; h++){
//alert(memoireArr[id][h].status);
        if(memoireArr[id][h].status == 1){
            alRouges++;
        }else if(memoireArr[id][h].status == 0){
            alNoires++;
        }
    
    }
    }
    var lib = (currentMemory == 0) ? lib_defi : lib_solution;

    document.getElementById('libMemoire').innerHTML = `${lib}`;
    document.getElementById('numMemoire').innerHTML = `${currentMemory+1}`;
    document.getElementById('nbAlRouge').innerHTML = `${alRouges} alummetes rouges`;
    document.getElementById('nbAlNoire').innerHTML = `${alNoires} alummetes noires`;
}
function hidde_allumettes_fixed(ev){
    restaurer(currentMemory);
    /*
    //alert(`hidde_allumettes_fixed : ` + (ev.currentTarget.checked) ? 'oui' : 'non');
    const checkbox = ev.currentTarget;
    if (checkbox.checked) {
        console.log("Valeur : true (Coché)");
    } else {
        console.log("Valeur : false (Décoché)");
    }    
    */
}

function get_hidde_allumettes_fixed(){
    return document.getElementById('hiddeAlFixes').checked;
}


function echo_plateau(id) {
    tab1 = memoireArr[id];
    // On filtre une seule fois

const actifs1 = tab1
    .filter(obj => obj.status == 1) // 1. On ne garde que les éléments avec status == 1
    .map(obj => ({                  // 2. On transforme les éléments restants
        x : obj.x, 
        y : obj.y, 
        rotation: (obj.rotation + 360) % 180,
        status : obj.status
    }));

   var exp = objToString(actifs1, '', 0, false); 
   // setMouchard(exp);
}

function  setMouchard(exp1, exp2=''){
    if(exp2){
        var fullExp = `===>${exp1} / ${exp2}`;
    }else{
        var fullExp = `===>${exp1}`;
    }
    document.getElementById('mouchard').innerHTML = fullExp;
}

function recaler_sur_la_grille(){
    var nbCorrections = 0;
    var nbAllumettes = 0;
    var x = 0; y = 0;
    
    for (var id = 0; id < memoireArr.length; id++){
        if(memoireArr[id]){
        for (var al = 0; al < memoireArr[id].length; al++){
            var allumette = memoireArr[id][al];
            x = Math.round(allumette.x / currentGridSize)  * currentGridSize;
            y = Math.round(allumette.y / currentGridSize)  * currentGridSize;
            if(x == 0 || allumette.x == 0){x = currentGridSize;}
            if(y == 0 || allumette.y == 0){y = currentGridSize;}
            
            if(allumette.x != x) {allumette.x = x; allumette.rotation=0; nbCorrections++;}
            if(allumette.y != y) {allumette.y = y; allumette.rotation=0; nbCorrections++;}
            nbAllumettes++;
//             allumette.x = Math.round(al.x / currentGridSize)  * currentGridSize;
//             allumette.y = Math.round(al.y / currentGridSize)  * currentGridSize;
        }
        }
    }
    if(nbCorrections > 0){
        alert(lib_recalage_done.replace('{nbAllumettes}', nbAllumettes).replace('{nbCorrections}', nbCorrections));
    }else{
        alert(lib_recalage_ok.replace('{nbAllumettes}', nbAllumettes));
        }
}

////////////////////////////////////////////////////////////
var resultatTableau = [];
var levelStructure = 0;
var levelMax = 0;
var separateLineLength = 30
/**
 * 1. LA FONCTION PRINCIPALE
 * Elle parcourt la structure et pousse les données dans le tableau accumulateur via le callback.
 */
function parcourirStructure(data, callback, cleOuIndex = "racine") {
//alert(levelStructure);
levelStructure++;
if(levelMax > 0 && levelStructure > levelMax){levelStructure--; return;}
//if (levelStructure <3 ){callback('???', '----------------------')};
//if (levelStructure == 2){callback('niveau', '----------------------')};

    if (Array.isArray(data)) {
        if(data.length == 0){
            callback('array', '===>Tableau vide');
        }else{
          data.forEach((element, index) => {
            callback('array', `===>Index [${cleOuIndex}]`);
            parcourirStructure(element, callback, `Index [${index}]`);
          });
        }
    } 
    else if (typeof data === 'object') {
        if (data == null){
            callback('object', '===>Objet est nulle');
        }else{
          callback('object', `===>Index [${cleOuIndex}]`);
          Object.keys(data).forEach(key => {
              parcourirStructure(data[key], callback, key);
          });
        }
    } 
    else {
        // On transmet la clé et la valeur au callback
        callback(cleOuIndex, data);
    }
levelStructure--;
}
// Le callback remplit le tableau au fur et à mesure du parcours
//a utilisr pour mettre dans une table par exemple
function ajouterAuTableau2(propriete, valeur) {
    resultatTableau.push({
        Propriete: propriete,
        Valeur: valeur,
        Type: typeof valeur
    });
}
function ajouterAuTableau(propriete, valeur) {
    //resultatTableau.push(`Propriete = ${propriete} | Valeur = ${valeur} | Type = ${typeof valeur}`);
    if (levelStructure == 1){resultatTableau.push('='.repeat(separateLineLength))};

    var tabulationStr = "-".repeat(levelStructure);
    resultatTableau.push(`${levelStructure}${tabulationStr}${propriete} = ${valeur}`);
    //resultatTableau.push(`${levelStructure}${tabulationStr}${valeur}===>${propriete}`);
}

function objToString(obj, cleOuIndex = '', level = 0, showAlert = false){

    levelMax = level;
    resultatTableau = [];

    levelStructure = 0;    
    parcourirStructure(obj, ajouterAuTableau, cleOuIndex = 'racine');
    resultatTableau.push('='.repeat(separateLineLength));
    
    //parcourirStructure(developpeurs, ajouterAuTableau);

    // Affichage du tableau final sous forme de table textuelle
    console.table(resultatTableau);    


    //alert(resultatTableau.join("\n"));
    if(showAlert){alert(resultatTableau.join("\n"));}
    return resultatTableau.join("<br>");
}


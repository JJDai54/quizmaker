

function monkey_creerGrille(slideNumber) {
    var currentSlide = quizard[slideNumber];
    var options = currentSlide.question.options;
    
    
//alert(`table = ${nbRows} x ${nbCols}`);
    var tHtml = [];
    //tHtml.push(`<hr>`);

    var tblStyle = `background:${options.backgroundColor};color:${options.fontColor};`;
    //tHtml.push(`<div>`);
    //tHtml.push(`<center><table id=${idGrille} class='monkey' style='background:${options.backgroundColor}' prochainNombre='0' totalItems='0' onclick='monkey_tbl_onclick(event, 0)'>`);
    tHtml.push(`<center><table id=${currentSlide.monkeyId} class='monkey' style='${tblStyle}' onclick='monkey_tbl_onclick(event, ${slideNumber})'>`);
    for (let i = 0; i < options.tblRows; i++) {
        tHtml.push(`<tr>`);
        
        for (let j = 0; j < options.tblCols; j++) {
            tHtml.push(`<td class='cellule' style='color:${options.fontColor}'></td>`);
        }
 
        tHtml.push(`</tr>`);
    }
    tHtml.push(`</table></center>`);
    //tHtml.push(`</div>`);
    //tHtml.push(`<hr>`);

    return tHtml.join("\n");
}


function monkey_ClearTable(idGrille) {
    var obTbl = document.getElementById(idGrille);
    
    const cellules = obTbl.querySelectorAll('.cellule');
    cellules.forEach(td => {
        td.textContent = '';
        td.className = 'cellule';
        delete td.dataset.chrono;
        delete td.dataset.valeur;
        updateCellule(td, true, null);
    });


}

function monkey_buildNewGame(slideNumber) {
    var currentSlide = quizard[slideNumber];
    var options = currentSlide.question.options;


    var obTbl = document.getElementById(currentSlide.monkeyId);
    options.prochainNombre = 0;
    
    const cellules = obTbl.querySelectorAll('.cellule');
    const listeCellules = Array.from(cellules);
                
    for (let i = 0; i <= options.itemsArr.length-1; i++) {
        //const index = Math.floor(Math.random() * listeCellules.length);
        const index = getRandom(listeCellules.length-1);
        const cellule = listeCellules.splice(index, 1)[0];
        cellule.textContent = options.itemsArr[i];
        cellule.dataset.chrono = i; 
        cellule.dataset.valeur = options.itemsArr[i]; 
    }
   
}

function monkey_hiddeGame(slideNumber) {
    var currentSlide = quizard[slideNumber];
    var options = currentSlide.question.options;
    var obTbl = document.getElementById(currentSlide.monkeyId);

    obTbl.querySelectorAll('.cellule').forEach(td => {
        if (td.dataset.chrono) {
            updateCellule(td, false, options);
            td.textContent = '';
            
        }
    });
    quiz_show_mask(false);
//    alert('A vous de jouer');
    quiz_show_avertissement (options.msg_atYou, options.msg_duree, options.background, false);
}

function monkey_showGame(slideNumber) {
    var currentSlide = quizard[slideNumber];
    var obTbl = document.getElementById(currentSlide.monkeyId);

    obTbl.querySelectorAll('.cellule').forEach(td => {
        if (td.dataset.chrono) {
            updateCellule(td, true, currentSlide.question.options);
            td.textContent = td.dataset.valeur;
        }
    });

}
/*
function updateCellule2(obTd, isVisible = true){
    
    if(isVisible){
            obTd.classList.remove('monkey_cache', 'monkey_a_deviner');
    }else{
            obTd.classList.add('monkey_cache', 'monkey_a_deviner');
    }
}
*/

function updateCellule(obTd, isVisible = true, options){
    
    if(isVisible){
            obTd.classList.add('monkey_cache', 'monkey_a_deviner');
            console.log('monkey_hiddeGame className = ' + obTd.className);
            obTd.style.background = 'transparent';
            //obTd.style.color = 'black';
            obTd.textContent = obTd.dataset.valeur;
            //td.style.borderColor = '#ffc107';
            //alert(obTd.dataset.chrono + " ===> " + obTd.classList.contains("a_deviner"));
    }else{
            obTd.classList.add('monkey_cache', 'monkey_a_deviner');
            console.log('monkey_hiddeGame className = ' + obTd.className);
            obTd.style.background = options.maskColor;
            //obTd.style.color = 'transparent';
            obTd.textContent = '';
    }
}
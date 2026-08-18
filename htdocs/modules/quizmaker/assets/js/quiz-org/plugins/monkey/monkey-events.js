


function monkey_tbl_onclick(ev, slideNumber) {
    var currentSlide = quizard[slideNumber];
    var options = currentSlide.question.options;
    const obTbl = event.target.closest('table');
    const td = event.target.closest('td');

    if (!td || !td.dataset.chrono || td.classList.contains('revele') || td.textContent) return;
    const chronoClique = parseInt(td.dataset.chrono);
    
    //alert(`${currentSlide.monkleyId} : monkey_tbl_onclick valeur = ${chronoClique} / totalItems  = ${options.totalItems}`)

    if (chronoClique === options.prochainNombre) {
        updateCellule(td, true);
        options.prochainNombre++;

        if (options.prochainNombre >= options.totalItems) {
            //message.textContent = "Bravo ! Nouvelle partie en cours...";
            //alert("Bravo ! Nouvelle partie en cours...");
            currentSlide.score = currentSlide.question.points;
            currentSlide.show_avertissement_WL (true);
        }
    } else {
        monkey_showGame(slideNumber);
        if(options.nbAttempts >= options.maxAttempts && options.maxAttempts > 0 ){
            currentSlide.show_avertissement_WL (false);
        }else{
            if(options.maxAttempts == 0){
                var msg = options.msg_attempts.replace('{nbAttempts}', options.nbAttempts);
            }else{
                var msg = options.msg_replay.replace('{nbAttempts}', options.maxAttempts - options.nbAttempts ).replace('{maxAttempts}', options.maxAttempts);
            }
            monkey_hiddeGame(slideNumber);
            quiz_show_avertissement (msg, options.msg_duree, options.background, false);
            setTimeout(monkey_reload, options.msg_duree*1000, slideNumber);
        }
        //message.textContent = "Perdu ! On recommence...";
        //alert("Perdu ! On recommence...");
        options.nbAttempts++;
    }
}
function monkey_reload(slideNumber){
    var currentSlide = quizard[slideNumber];
    //currentSlide.reloadQuestion();
    currentSlide.initNewAttempts();


}

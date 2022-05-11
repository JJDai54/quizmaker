
const quiz_bit = {
  previous:  1,  // retour arriere
  next:      2,  // next si repondu
  popup:     4,  // popup des réponse valide
  timer:     8,  // timer
  reponses: 16   //affichage des répnses et des points en bas de page pour des tests
  };

const quiz_mode = {
    developpement: 1+2+4+16,
    trainning:     1+4,
    pro:           8+2,
    timer:         8
};
//if ( (quiz.mode & quiz_bit.previous) != 0) {}
const  quiz = {
    name:               "Exemple n° 1",
    description:        "exemple de quiz avec les différents type de question",
    legend:             "{allType}",
    numerotation:       3,
    btnColor1:          "#227799",
    btnColor2:          "#999999",
    colorset:           "green",
    onclickList:         true,
    click:              "onclick",
    shuffleQuestions:   false,
    answerBeforeNext:   false,
    allowedPrevious:    true,
    allowedSubmit:      false,
    showResultAllways:  true,
    showResultPopup:    false,
    showReponsesBottom: true,
    useChrono:          false,
    showLog:            true,
    showType:           true,
    showAntiSeche:      true,
    bonusAntiSeche:      0, //retire des points si le bouton antische est clicker
    mode:  quiz_mode.developpement
  }


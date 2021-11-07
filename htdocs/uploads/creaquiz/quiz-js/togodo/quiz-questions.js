

/*
https://www.w3schools.com/js/js_json_intro.asp
var myObj = {name: "John", age: 31, city: "New York"};
var myJSON = JSON.stringify(myObj);
window.location = "demo_json.php?x=" + myJSON;*/

// var exp = '[{"question":"Quelle trag\u00e9die se cache ici ?","comment1":"blabla","comment2":"blibli","isquestion":1,"timer":12,"type":"multiRadio","answers":[{"proposition":"Rom\u00e9o,Donquichotte,Tristan,Paul","points":"5,0,0,0,0,-5"},{"proposition":"Juliette,Sancho Pansa,Iseult,Virginie","points":"5,0,0,0,0,-5"},{"proposition":"Shakespeare,Victor Hugo,Voltaire,Aragon","points":"5,0,0,0,0,-5"}]},{"question":"c\'est une deuxi\u00e8me question ?","comment1":"blabla","comment2":"blibli","isquestion":1,"timer":12,"type":"multiRadio","answers":[{"proposition":"Rom\u00e9o,Donquichotte,Tristan,Paul","points":"5,0,0,0,0,-5"},{"proposition":"Juliette,Sancho Pansa,Iseult,Virginie","points":"5,0,0,0,0,-5"},{"proposition":"Shakespeare,Victor Hugo,Voltaire,Aragon","points":"5,0,0,0,0,-5"}]},{"question":"et la la troisi\u00e8me question ?","comment1":"blabla","comment2":"blibli","isquestion":1,"timer":12,"type":"multiRadio","answers":[{"proposition":"Rom\u00e9o,Donquichotte,Tristan,Paul","points":"5,0,0,0,0,-5"},{"proposition":"Juliette,Sancho Pansa,Iseult,Virginie","points":"5,0,0,0,0,-5"},{"proposition":"Shakespeare,Victor Hugo,Voltaire,Aragon","points":"5,0,0,0,0,-5"}]}]';
//
//
// var obj = JSON.parse(exp);
// alert (obj);
//
// obj.forEach((question, index) => {
//     alert ("index : " + index +  "\n" + question);
// //     obj.forEach((option, idx) => {
// //         alert ("idx : " + idx +  "\n" + option);
// //     });
//      for(letter in question){
//         alert("letter : " + letter + " ===> " + question[0]);
//
//         if(letter=="answers"){
//             for(propo in question[0]){
//                 alert("propo : " + propo + " ===> " + question[0][propo].proposition
//                 + "\n" + "points = " + question[0][propo].points);
//             }
//         }
//      }
//
//
// });



var myQuestions = [

  {
    type: "Intro",
    question: "Test de connaissance générale",
    comment1: "Questionnaire à réponses multiples",
    comment2: "Les mauvaises réponses peuvent donner des scores négatifs<br> et la qualité des réponses donnent plus ou moins de points",
    isQuestion: 0,
    timer: 12,
    answers: [
      {
         proposition: "<br>",
         inputs: 0,
         points: 0
         },
      {
         proposition: "Auteur : J°J°D",
         inputs: 0,
         points: 0
         },
      {
         proposition: "Questionnaire d'évaluation des compétences.<br>Il n'a aucune valeur scientifique,<br>mais nous espérons que vous passerez un bon moment.",
         inputs: 0,
         points: 0
         },
      {
         proposition: "Ce script Javascript sera intégré dans le module \"creaquiz\" pour Xoops.",
         inputs: 0,
         points: 0
         }
    ]
  },
    {
    type: "Result",
    question: "LE Quiz est terminé",
    comment1: "cliquer sur le bouton valider pour enregistrer votre score",
    comment2: "",
    isQuestion: 0,
    timer: 0,
    answers: [
      {
         proposition: "<br>",
         inputs: 0,
         points: 0
         },
      {
         proposition: "- Nombre de réponses faites : {repondu} / {totalQuestions}",
         inputs: 0,
         points: 0
         },
      {
         proposition: "- Votre score est de {score} / {scoreMaxi} (score minimum : {scoreMini})",
         inputs: 0,
         points: 20
         },
      {
         proposition: "- Votre temps de réponse est de {duree}",
         inputs: 0,
         points: -10
         }
    ]
  },

  {
    type: "radioMultiple2",
    question: "Quelle tragédie et son auteur se cachent ici ?",
    comment1: "",
    comment2: "",
    options:   "H",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         proposition: "Roméo,Juliette,Shakespeare",
         inputs: 0,
         points: "5"
         },
      {
         proposition: "Donquichotte,Sancho Pansa,Voltaire",
         inputs: 1,
         points: "-10"
         },
      {
         proposition: "Virginie,Paul,Aragon",
         inputs: 1,
         points: "-20"
         },
      {
         proposition: "Iseult,Victor Hugo,Tristan",
         inputs: 1,
         points: "0"
         }
    ]
  },
  
  {
    type: "radioMultiple2",
    question: "Quelle sont les couleurs du drapeaud français ?",
    comment1: "",
    comment2: "",
    options:   "V",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         proposition: "Jaune,vert,indigo",
         inputs: 1,
         points: "3"
         },
      {
         proposition: "Saumon,Gris,Cyan",
         inputs: 1,
         points: "2"
         },
      {
         proposition: "Bleu,blanc,rouge",
         inputs: 0,
         points: "5"
         },
      {
         proposition: "Magenta,transparent,foncé",
         inputs: 1,
         points: "1"
         }
    ]
  },
  {
    type: "radioMultiple2",
    question: "Retouver les couleurs d'un drapeaud européens ? ",
    comment1: "certains donnent plus de points que d'autres",
    comment2: "",
    options:   "H",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         caption: "Allemagne",
         proposition: "Noir,Rouge,Jaune",
         inputs: 1,
         points: "3"
         },
      {
         caption: "Italie",
         proposition: "Vert,Blanc,Rouge",
         inputs: 1,
         points: "2"
         },
      {
         caption: "Espagne",
         proposition: "Rouge,Jaune,Rouge",
         inputs: 0,
         points: "5"
         },
      {
         caption: "France",
         proposition: "Bleu,Blanc,Rouge",
         inputs: 1,
         points: "1"
         }
    ]
  },

  {
    type: "radioSimple",
    question: "Quelle partie du cerveau commande la partie droite du corps ?",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         proposition: "Le cervelas",
         inputs: 0,
         points: 0
         },
      {
         proposition: "Lhémisphère nord",
         inputs: 1,
         points: -5
         },
      {
         proposition: "Lhémisphère gauche",
         inputs: 1,
         points: 5
         },
      {
         proposition: "L'hypocampe",
         inputs: 0,
         points: 0
         }
    ]
  },
  {
    type: "matchItems",
    question: "Quels sont ces couples ?",
    comment1: "Associez les éléments de la liste de gauche avec ceux de la liste de droite.",
    comment2: "",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "bleu,d'Auvergne",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "8"
         },
      {
         proposition: "jaune,d'oeuf",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "5"
         },
      {
         proposition: "blanc,de meudon",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "4"
         },
      {
         proposition: "vert,de malachite",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         },
      {
         proposition: "rouge,de Bavière",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "4"
         },
      {
         proposition: "noir,de jais",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         },
      {
         proposition: "rose,des sables",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         }

    ]
  },
  {
    type: "matchItems",
    question: "Rassemblez ces couples célèbres ?",
    comment1: "Associez les éléments de la liste de gauche avec ceux de la liste de droite.",
    comment2: "",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Paul,Virginie",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "8"
         },
      {
         proposition: "Adam,Eves",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "5"
         },
      {
         proposition: "Tristan,Iseult",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "4"
         },
      {
         proposition: "Roméo,Juliette",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         },
      {
         proposition: "Edith,Marcel",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "9"
         },
      {
         proposition: "César,Cléopâtre",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         },
      {
         proposition: "Marie,Pierre",
         //proposition: "auvergne,oeuf,meudon,malachite,bavière",
         inputs: 1,
         points: "7"
         }

    ]
  },
  {
    type: "textareaListbox",
    question: "Connaissez-vous le poeme de Baudelaire: l'homme et la mer ?",
    comment1: "Remplacez les \"{#}\" par les mots correspondants dans le poème.",
    comment2: "",
    options : "V",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Homme libre, {toujours} tu chériras la mer !\nLa mer est ton {miroir} ; tu contemples ton âme\nDans le déroulement infini de sa {lame},\nEt ton {esprit} n'est pas un gouffre moins amer.",
         //proposition: "Homme libre, {1} tu chériras la mer !\nLa mer est ton {2} ; tu contemples ton âme\nDans le déroulement infini de sa {3},\nEt ton {4} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: "8,7,2"
         },
      {
         proposition: "jamais,ce jour,tirroir,mouroir,rame,flamme,palme,coeur,corps", //uniquement les propositions fausses
         //proposition: "Homme libre, {1} tu chériras la mer !\nLa mer est ton {2} ; tu contemples ton âme\nDans le déroulement infini de sa {3},\nEt ton {4} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: "-1,-2,-3,-4,-5"
         }

    ]
  },

  {
    type: "textareaListbox",
    question: "Connaissez-vous cette comptine ?",
    comment1: "Remplacez les \"{#}\" par les mots correspondants dans le poème.",
    comment2: "",
    options : "H",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Il était une {fois}\nUne marchande de {foie}\nQui vendait du {foie}\nDans la ville de {Foix}\nElle se dit : ma {foi},\nC'est la dernière {fois}\nQue je vends du {foie}\nDans la ville de {Foix}.",
         //proposition: "Homme libre, {1} tu chériras la mer !\nLa mer est ton {2} ; tu contemples ton âme\nDans le déroulement infini de sa {3},\nEt ton {4} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: "8,7,6"
         },
      {
         //proposition: "foa,pois,toits,quoi", //uniquement les propositions fausses
         proposition: "foa", //uniquement les propositions fausses
         //proposition: "Homme libre, {1} tu chériras la mer !\nLa mer est ton {2} ; tu contemples ton âme\nDans le déroulement infini de sa {3},\nEt ton {4} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: "-1,-2,-3,-4,-5"
         }

    ]
  },

////////////////////////////////////
  {
    type: "textareaInput",
    question: "Connaissez-vous le poeme de Baudelaire: l'homme et la mer ?",
    comment1: "Remplacez les \"{#}\" par les mots correspondants dans le poème.",
    comment2: "",
    options : "V" ,
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Homme libre, {toujours} tu chériras la mer !\nLa mer est ton {miroir} ; tu contemples ton âme\nDans le déroulement infini de sa {lame},\nEt ton {esprit} n'est pas un gouffre moins amer.",
         //proposition: "Homme libre, {1} tu chériras la mer !\nLa mer est ton {2} ; tu contemples ton âme\nDans le déroulement infini de sa {3},\nEt ton {4} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: "8,7,6"
         }
    ]
  },
  
  {
    type: "textareaInput",
    question: "Connaissez-vous cette chanson ?",
    comment1: "Remplacez les \"{#}\" par les mots correspondants dans le poème.",
    comment2: "",
    options : "H" ,
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Au {clair} de la Lune\nMon {ami} Pierrot\nPrête-moi ta {plume}\nPour écrire un {mot}",
         inputs: 1,
         points: "2"
         }
    ]
  },

  {
    type: "textarea",
    question: "Connaissez-vous le poeme de Baudelaire: l'homme et la mer ?",
    comment1: "Remplacez les \"@@@@@\" par les mots correspondants dans le poème. Attention à ne pas modifier le reste du texte.",
    comment2: "La ponctuation, les retourS à la ligne, la casse et les accents ne sont pas pris en compte.",
    options : "@@@@@@@@@@",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Homme libre, {toujours} tu chériras la mer !\nLa mer est ton {miroir} ; tu contemples ton âme\nDans le déroulement infini de sa {lame},\nEt ton {esprit} n'est pas un gouffre moins amer.",
         inputs: 1,
         points: 8
         }

    ]
  },
 {
    type: "radioLogical",
    question: "Quelle est la suite logique ?",
    comment1: "trouvez l'animal qui réponds à la suite logique de gauche.",
    comment2: "forPointsTimer",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Chameau,Zébu,Dromadaire",
         inputs: 1,
         points: 5
         },
      {
         proposition: "Girafe,Baleine à bosse,Quazimodo,Gazelle,Sprintbox",
         inputs: 1,
         points: "0,3,-3,0,0"
         }

    ]
  },
 {
    type: "checkboxLogical",
    question: "Quelle sont éléments de la même famille ?",
    comment1: "trouvez l'animal qui réponds à la suite logique de gauche.",
    comment2: "forPointsTimer",
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "bleu,violet,Indigo,vert",
         inputs: 1,
         points: 5
         },
      {
         proposition: "orange,Rouge,Jaune,Mauve,Saumon,Cyan,Magenta,Ocre",
         inputs: 1,
         points: "1,1,1,-1"
         }

    ]
  },

  {
    type: "listboxIntruders2",
    question: "Basculer à droite les mamifères !",
    comment1: "message01",
    comment2: "",
    options:   "",  //M = répartietion aleatoire sur les deux listes
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Baleine,Chien,Orque,Otarie",
         inputs: 1,
         points: "1,1,1,1"   // "0,0,1,1,-5,2,0"
         },
      {
         proposition: "Faucon,Morue,Requin,Raie",
         inputs: 1,
         points: "-1,-3,-2,-1"   // "0,0,1,1,-5,2,0"
         }
    ]
  },
  {
    type: "listboxIntruders2",
    question: "Replacer droite les mamifères !",
    comment1: "message01",
    comment2: "",
    options:   "M",  //M = répartietion aleatoire sur les deux listes
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Hamster,Chien,Lamentin,Chat,Bouc",
         inputs: 1,
         points: "1,1,1,1"   // "0,0,1,1,-5,2,0"
         },
      {
         proposition: "Saumon,Morue,Requin,Raie",
         inputs: 1,
         points: "-1,-3,-2,-1"   // "0,0,1,1,-5,2,0"
         }
    ]
  },

  {
    type: "listboxIntruders1",
    question: "Chasser les intrus et ne garder que les mamifères !",
    comment1: "message01",
    comment2: "",
    options:   "", 
    isQuestion: 1,
    timer: 10,
    minReponse: 2,
    answers: [
      {
         proposition: "Baleine,Chien,Orque,Otarie",
         inputs: 1,
         points: "-1,-1,-1,-1"   // "0,0,1,1,-5,2,0"
         },
      {
         proposition: "Faucon,Morue,Requin,Raie",
         inputs: 1,
         points: "1,2,3,4"   // "0,0,1,1,-5,2,0"
         }
    ]
  },


  {
    type: "sortCombobox",
    question: "Classer ces animaux par ordre de taille croissante !",
    comment1: "message01",
    comment2: "",
    options:   "R",  //R = autorise l'ordre inverse
    isQuestion: 1,
    timer: 10,
    answers: [
      {
         proposition: "Souris,Chien,Elephant",
         inputs: 1,
         points: 5
         }
    ]
  },

    {
    type: "sortCombobox",
    question: "Quel est l'orde des couleurs de l'arc en ciel<br>peu importee le sens ?",
    comment1: "message01",
    comment2: "",
    options:   "R",  //R = autorise l'ordre inverse
    isQuestion: 1,
    timer: 25,
    answers: [
      {
         proposition: "rouge,orange,jaune,vert,bleu,indigo,violet",
         inputs: 1,
         points: 12
         }
    ]
  },
   {
    type: "checkbox",
    question: "Ou met-t-on un brassard ?",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    minReponse: 2,
    answers: [
      {
         proposition: "à la cheville",
         inputs: 1,
         points: -1
         },
      {
         proposition: "au bras",
         inputs: 1,
         points: 3
         },
      {
         proposition: "dans la poche",
         inputs: 1,
         points: -2
         },
      {
         proposition: "sur l'épaule",
         inputs: 1,
         points: 2
         },
      {
         proposition: "sur la tête",
         inputs: 1,
         points: 1
         }
    ]
  },
    {
    type: "radioSimple",
    question: "Un angle aigu est un angle :",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         proposition: "un angle plus petit que 180°",
         inputs: 0,
         points: 0
         },
      {
         proposition: "un angle plus petit que 90°",
         inputs: 0,
         points: 20
         },
      {
         proposition: "un angle plus grand que 90°",
         inputs: 0,
         points: -10
         }
    ]
  },
  {
    type: "radioSimple",
    question: "Que chasse t'on quand on fait le vide dans un récipient ?",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    answers: [
      {
         proposition: "Les mouches",
         inputs: 0,
         points: -5
         },
      {
         proposition: "L'air",
         inputs: 0,
         points: 3
         },
      {
         proposition: "L'eau",
         inputs: 0,
         points: 2
         },
      {
         proposition: "L'esprit",
         inputs: 1,
         points: 1
         }
    ]
  },
  {
    type: "multiTextbox",
    question: "Donnez un anagramme de 'gardée' ",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    minReponse: 6,
    answers: [
      {
         caption : "Attention aux accents",
         proposition: "dragée,gradée",
         inputs: 2,
         points: "5"
         }
    ]
  },
  {
    type: "multiTextbox",
    question: "Quelle est la 3eme couleur du spectre de l'arc en ciel (2 réponses possibles) ?",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    minReponse: 3,
    answers: [
      {
         caption : "Quelle est la 3eme couleur du spectre",
         proposition: "rouge,orange,jaune,vert,bleu,indigo,violet",
         inputs: 1,
         points: "0,0,3,0,3,0,0"
         }
    ]
  },
  {
    type: "multiTextbox",
    question: "Sitez respectivement dans l'ordre:<br><span style='font-size:smaller;'>une couleur primaire,<br>une mesure cosmique,<br>un polyèdre régulier convexe (solides de Platon)</span>",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    minReponse: 3,
    answers: [
      {
         caption: "une couleur primaire",
         proposition: "rouge,vert,bleu,magenta,cyan,jaune",
         inputs: 2,
         points: "7,8,9,10,11,12"
         },
      {
         caption: "une mesure cosmique",
         proposition: "parsec,année lumière,année galactique,année cosmique,Année sidérale,jour sidéral,jour stellaire,Temps de Planck",
         inputs: 3,
         points: "12,5,5,5,8,8,10,12"
         },
      {
         caption: "un polyèdre régulier convexe (solides de Platon)",
         proposition: "Tétraèdre,Hexaèdre,Cube,Octaèdre,Dodécaèdre,Icosaèdre",
         inputs: 2,
         points: "7"
         }
    ]
  },

  {
    type: "multiTextbox",
    question: "Sitez plusieurs mesures cosmiques (pas comiques)",
    comment1: "",
    comment2: "",
    isQuestion: 1,
    timer: 12,
    minReponse: 3,
    answers: [
      {
         caption : "Sitez plusieurs mesures cosmiques",
         proposition: "parsec,année lumière,année galactique,année cosmique,Année sidérale,jour sidéral,jour stellaire,Temps de Planck",
         inputs: 3,
         points: "12,5,5,5,8,8,10,12"
         }
    ]
  }

];



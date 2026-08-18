const regexExposant = /[°¹²³45678?]/g;

function preparerPourEval(chaine) {
    // 1. Remplacer l'exposant par **chiffre
    let chainePreparee = chaine.replace(regexExposant, (match) => {
        return '**' + inverseCorrespondances[match];
    });

    // 2. Nettoyer les symboles "|" et gérer le "?"
    // On remplace le ? par une variable ou une valeur numérique si nécessaire
    chainePreparee = chainePreparee.replace(/\|/g, '').replace(/\?/g, 'x'); 

    return chainePreparee;
}


/* ************************************************
*searchComparaion string : renvoie le premier comparateur trouvé
* exp string equation à séparer
* ************************************************/
function searchComparaison(exp) {
var arr=['>=','<=','!=','<>','=']; //l'ordre des comparateurs est important
    
    // recherche du comparateur, 
    for(var h = 0; h < arr.length; h++){
        if (exp.indexOf(arr[h]) >= 0){
            var comparaison = arr[h];
            break;
        }
    }
    return comparaison;
}

/* ************************************************
*
* ************************************************/
function verifier(slideNumber) {
    var clPlugin = quizard[slideNumber];
    var bolOk = false;
    
    // Récupère tous les éléments de la grille dans l'ordre
    var obEquation = document.getElementById(clPlugin.getId('equation'));
    //const elements = document.querySelectorAll('.equation-grid > div');
    //const elements = obEquation.children;
    const elements =  obEquation.querySelectorAll('div');
    //alert(elements.length)
 
    let expression = "";
    
    elements.forEach(el => {
        // Si c'est un inconnue vide, on prend son contenu, sinon la valeur fixe
        expression += el.innerText;
    });
    expression = expression.replaceAll('x', '*');    
//alert(expression)
    // Sépare l'équation en deux parties au signe '='
    const [gauche, droite] = expression.split('=');
        //alert(gauche + " = " + droite);
//alert(gauche + '<===>' + droite)
    try {
        // eval() calcule la chaîne mathématique
        if (eval(gauche) === eval(droite)) {
            //alert("Félicitations, équation réussie !");
            bolOk = true;
        } else {
            //alert("Dommage, le calcul est faux : " + eval(gauche) + " != " + eval(droite));
        }
    } catch (e) {
        //alert("Veuillez remplir tous les emplacements !");//  + "\n" + gauche + " = " + droite
    }
    return bolOk;
}

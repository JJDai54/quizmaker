const TAILLE_GRILLE = 5;
const TOTAL_ELEMENTS = 5;
const MODES = ['chiffre', 'lettre', 'romain'];

let modeJeuActuel = '';
let prochainNombre = 1;
const table = document.getElementById('monTableau');
const message = document.getElementById('message');

function getLabel(index, mode = 'chiffre', total = 5) {
    switch(mode) {
        case 'lettre':
            return String.fromCharCode(64 + index);
        case 'romain':
            const romains = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];
            return romains[index - 1] || index;
        default: 
            return index.toString();
    }
}

function creerGrille() {
    table.innerHTML = '';
    for (let i = 0; i < TAILLE_GRILLE; i++) {
        const tr = document.createElement('tr');
        for (let j = 0; j < TAILLE_GRILLE; j++) {
            const td = document.createElement('td');
            td.classList.add('cellule');
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
}

function reinitialiserJeu() {
    modeJeuActuel = MODES[Math.floor(Math.random() * MODES.length)];
    prochainNombre = 1;
    message.textContent = `Mode : ${modeJeuActuel.toUpperCase()} - Mémorisez les éléments...`;
    
    const cellules = document.querySelectorAll('.cellule');
    cellules.forEach(td => {
        td.textContent = '';
        td.className = 'cellule';
        delete td.dataset.valeur;
    });

    const listeCellules = Array.from(cellules);
    for (let i = 1; i <= TOTAL_ELEMENTS; i++) {
        const index = Math.floor(Math.random() * listeCellules.length);
        const cellule = listeCellules.splice(index, 1)[0];
        cellule.textContent = getLabel(i, modeJeuActuel, TOTAL_ELEMENTS);
        cellule.dataset.valeur = i; 
    }

    setTimeout(() => {
        document.querySelectorAll('.cellule').forEach(td => {
            if (td.dataset.valeur) {
                td.classList.add('cache', 'a-deviner');
            }
        });
        message.textContent = `Mode : ${modeJeuActuel.toUpperCase()} - À vous de jouer !`;
    }, 3000);
}

table.addEventListener('click', (event) => {
    const td = event.target.closest('td');
    if (!td || !td.dataset.valeur || td.classList.contains('revele')) return;

    const valeurCliquee = parseInt(td.dataset.valeur);

    if (valeurCliquee === prochainNombre) {
        td.classList.remove('cache', 'a-deviner');
        td.classList.add('revele');
        prochainNombre++;
        
        if (prochainNombre > TOTAL_ELEMENTS) {
            message.textContent = "Bravo ! Nouvelle partie en cours...";
            setTimeout(reinitialiserJeu, 1500);
        }
    } else {
        message.textContent = "Perdu ! On recommence...";
        td.classList.add('erreur');
        setTimeout(reinitialiserJeu, 1000);
    }
});

creerGrille();
reinitialiserJeu();
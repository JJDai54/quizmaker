function getPlugin_memory(question, slideNumber){
//alert(`plugin : ${question.options.variant}`);
    switch(question.options.variant){
    case 'liste'   : return new memory_liste(question, slideNumber, 'memory_liste'); break;
    default: 
    case 'grille'   : return new memory_grille(question, slideNumber, 'memory_grille'); break;
    }
}  



    var obs = getObjectsByName(this.getName(currentQuestion), "input", 'radio');
    var obs = getObjectsByName(name, "input");

obListText =  getHtmlTextbox(name,  currentQuestion.data.reponses);
    answers.push(`${obListText}`);



    
    var tHtml = [];
    
    if (numerotation.lowercase() == "n"){
      for (var j=0; j < tItems.length; j++){
        tHtml.push(`<span class="${spanClass}" ${extra}>${getNumAlpha(j*1+1,currentQuestion.numbering)} - ${tItems[j]}</span>`);
      }
    }{
      for (var j=0; j < tItems.length; j++){
        tHtml.push(`<span class="${spanClass}" ${extra}>${String.fromCharCode(j+65)} - ${tItems[j]}</span>`);
      }
    }
    return tHtml.join("<br>\n");


    obListText = getHtmlSpan(name, currentQuestion.data.words, 'n');
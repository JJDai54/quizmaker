/*******************************************************************
*                     matchItems
* *****************************************************************/
function getPlugin_matchItems(question, slideNumber){
    return new matchItems(question, slideNumber);
}

 /*******************************************************************
  *                     matchItems
  * *****************************************************************/
class matchItems extends Plugin_Prototype{
name = 'matchItems';  

//---------------------------------------------------
buildSlide (bShuffle = true){
    this.boolDog = false;
    return this.getInnerHTML(bShuffle);
    //this.focusId = this.get
 }
//-----------------------------------------------------------
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var htmlArr = [];

    //alert(currentQuestion.answers.length)
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    var allAns = this.shuffleAnswers();
    var item ='';
    //var allAns = currentQuestion.answers;
    var tplTextBox     = `<td style='width:{width}%;'><input type="text" id="{itemId}" value="{itemValue}" name="" {disabled} style='text-align:{textalign};background:{background};'} {typeobjet}></td>`; 
    //var tplTextBox     = `<td style='width:{width}%;'><input type="text" id="{itemId}" value="{itemValue}" name="{listName}" ansIndex='{index}'></td>`;
    var tplListbox     = `<td style='width:{width}%;'>{itemValue}</td>`; 
    var tplConjonction = `<td style='width:{width}%;background:{background};text-align:{textalign};padding-left:12px;padding-right:12px;'>{itemValue}</td>`; 
    var tplImage       = `<td style='width:{width}%;text-align:{textalign};'><img src="${quiz_config.urlQuizImg}/{image1}"  alt='' title='' height={height}px'></td>`; 
    var tplnumbering   = `<td style='width:3%;background:{background};text-align:right;font-size:1.2em'>{numbering}</td>`; 
    var tplEmpty       = `<td style='width:{width}%;background:{background};'></td>`; 
    var tplTitle       = `<td style='width:{width}%;text-align:center;''>{title}</td>`; 
    var nbColumns = currentQuestion.options.nbColumns;

    var delta = 100;
    for(var h = 0; h < nbColumns; h++){
        delta -= this.data.listArr[h].width;
    }

console.log(`getInnerHTML : ${currentQuestion.options.list1_type} - ${currentQuestion.options.list2_type}`);    
    htmlArr.push(`<center><table style="font-size:${currentQuestion.options.fontSize}em;">`);
    if(this.data.titleExists){
        if(delta  > 0){
            htmlArr.push(tplEmpty.replace('{width}', delta / 2));
        }
        if(currentQuestion.numbering > 0){
            item = tplnumbering.replace('{numbering}', '');
            htmlArr.push(item);
        }
        for(var h = 0; h < this.data.nbList; h++){
            htmlArr.push(tplTitle.replace('{width}', this.data.listArr[h].width)
                                 .replace('{title}', this.data.listArr[h].title));
        }
        if(delta  > 0){
            htmlArr.push(tplEmpty.replace('{width}', delta / 2));
        }
    }       
    
 //alert(this.slideNumber + '===>' + currentQuestion.question  + "\nnumbering = " + currentQuestion.numbering);       
    
    for(var k = 0; k < allAns.length; k++){
        var ans = allAns[k];
        //alert(ans.ansId)
        htmlArr.push('<tr>');
        if(delta  > 0){
            htmlArr.push(tplEmpty.replace('{width}', delta / 2));
        }
        if(currentQuestion.numbering > 0){
            item = tplnumbering.replace('{numbering}', getNumAlpha(k,currentQuestion.numbering));
            htmlArr.push(item);
        }
        var background = (ans.background == '#000000') ? currentQuestion.options.background : ans.background;
        //alert(`background : ${currentQuestion.options.background} ===> ${ans.background}`);
        for(var h = 0; h < this.data.nbList; h++){
            //var listWidth = (h == this.data.nbList) ? this.data.listArr[h].width+delta : this.data.listArr[h].width;
            var listWidth = this.data.listArr[h].width;
            //var background = this.data.listArr[h].background;
            var textalign = this.data.listArr[h].textalign;
            //ajout du numero de colonne dans l'id
            var itemId   = ans.ansId + `-${h}`;
            var itemName = ans.ansId + `-${h}`;
            
            switch(this.data.listArr[h].type*1){
                case 0: //label
                    item = "Label:" + ans.items[h];
                    item = tplTextBox.replace('{itemValue}', ans.items[h])
                                     .replace('{itemId}', itemId)
                                     .replace('{textalign}', textalign)
                                     .replace('{disabled}', 'disabled')
                                     .replace('{width}', listWidth)
                                     .replace('{background}', background);
                    break;
                case 1 : //combobox
                    //var newItems = shuffleNewArray(this.data.listArr[h].items);
                    var styleItem = `style='text-align:${textalign};background:${background};'`; 
                    item = tplListbox.replace('{itemValue}' , getHtmlCombobox(itemName, itemId, this.data.listArr[h].items, styleItem))
                                     .replace('{width}', listWidth);
                    if(k == 0) this.focusId = itemId; 
                    break;
                case 2 : //textbox
                    item = "textbox:" + ans.items[h];
                    item = tplTextBox.replace('{itemValue}', '')
                                     .replace('{itemId}', itemId)
                                     .replace('{listName}', this.data.list1Name)
                                     .replace('{textalign}', textalign)
                                     .replace('{disabled}', '')
                                     .replace('{width}', listWidth)
                                     .replace('{background}', background);

                    if(k == 0) this.focusId = itemId; 
                    break;
                case 4 : //image
                    item = tplImage.replace('{image1}', ans.image1)
                                   .replace('{height}', currentQuestion.options.imgHeight1);

                    break;
                case 5 : //image
                    item = tplImage.replace('{image1}', ans.image2)
                                   .replace('{height}', currentQuestion.options.imgHeight1);

                    break;
                case 3 : //conjonction
                default:
                    item = tplConjonction.replace('{itemValue}', ans.items[h].replaceAll(' ', '&nbsp;'))
                                         .replace('{textalign}', textalign)
                                         .replace('{width}', listWidth);
                    break;
             }
            htmlArr.push(item);

            
        }
        if(delta  > 0){
            htmlArr.push(tplEmpty.replace('{width}', delta / 2));
        }
        htmlArr.push('</tr>');
    }
    
    
///////////////////////////////////
    
    htmlArr.push(`</table></center>`);
    //var html = htmlArr.join("\n"); 
    var tpl = this.getDisposition(currentQuestion.options.disposition);
    var html = tpl.replace('{image}', this.getImage())
                  .replace('{allAnswers}', htmlArr.join("\n"));
    
    
    //return "en construction";
    return html;

    
 }

/* ***********************************************
*
* ************************************************ */
prepareData(){
    var currentQuestion = this.question;
    var itemId =   'idObInput';
    var itemName =  'nameObInput';
    var obInp = '';     
    var titleExists = false;     
    
    var nbMaxList = currentQuestion.options.nbMaxList;
    var listArr = [];
    console.log("=============> nbMaxList = " + nbMaxList);
    var nbColumns = currentQuestion.options.nbColumns;
    
    //chargement des listes
    for (var h = 0; h < nbColumns; h++){
        var collist = [];
        collist.id = this.getId(`list${h}`);
        collist.type = currentQuestion.options[`list${h}_type`];
        collist.width = currentQuestion.options[`list${h}_width`];
        //collist.background = currentQuestion.options[`list${h}_background`];
        collist.textalign = currentQuestion.options[`list${h}_textalign`];
        collist.title = currentQuestion.options[`list${h}_title`];
        collist.intrus = currentQuestion.options[`list${h}_intrus`].replaceAll(';','|').replaceAll(',','|');
        
        //Ajout des intrus si ils existent
        if(collist.intrus){
            collist.items = collist.intrus.split('|');
        }else{
            collist.items = [];
        }
        if (collist.title) {titleExists= true;}
//         console.log(`${h} ===> type = `  + currentQuestion.options[`list${h}_type`]);
//         console.log(`${h} ===> title = ` + currentQuestion.options[`list${h}_title`]);
//         console.log(`${h} ===> intrus = ` + currentQuestion.options[`list${h}_intrus`]);
//         console.log("------------");
        listArr.push(collist);
        
    }
    
    //chargement de tous les items pour chaque liste
    var answers = this.shuffleAnswers();
    
    for(var k = 0; k < answers.length; k++){
        var ans = answers[k];
        
        console.log(k + "--->" + ans.proposition);
        ans.items = [];
        
        var sep = (ans.proposition.indexOf('|') == -1 ) ? ',' : '|';
         
        var tExp = ans.proposition.split(sep); 
        for (var i = 0; i < nbColumns; i++){
            if(tExp[i] || ans.image1 || ans.image2){
                ans.items.push(tExp[i]);
                if(listArr[i].items.indexOf(tExp[i]) == -1){
                    listArr[i].items.push(tExp[i]);
                }
            }
        }
//         if(nbList > currentQuestion.answers[k].items.length){
//             nbList = currentQuestion.answers[k].items.length;
//         }
    }
    
    
    //a voir si il est judicieux dajouter un parametre pour trier, mélanger ou laisser la liste en l'état
    //pour l'instant on force le tri
//     if(true){
//         for (var h = 0; h < nbMaxList; h++){
//             listArr[h].items.sort();
//         }
//     }
    //mélange des items pour les combobox
    for (var i = 0; i < listArr.length; i++){
        if(listArr[i].type*1 == 1){
//    var lg1 = listArr[i].items.length;
            listArr[i].items = shuffleNewArray(listArr[i].items);
//    var lg2 = listArr[i].items.length;
//    if(lg1 != lg2) alert('problème');
        }
    }
 
    this.data.nbList = nbColumns;
    this.data.listArr = listArr;
    this.data.titleExists = titleExists;
    //alert("this.data.listArr[?].type = "  + this.data.listArr[0].type + " / " +  this.data.listArr[1].type );
}

/* ************************************
*
* **** */
getScoreByProposition (answerContainer){
  var currentQuestion = this.question;
  //alert("getScore");
  var points = 0;
  
 //<select id="question-1-ans-5-0-1" name="question-1-ans-5-0" left="" ansindex="5">
     for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        var p = ans.points;
        
  var nbRep = 0;
  var nbGood = 0;
        for(var h = 0; h < this.data.nbList; h++){
            var itemId   = ans.ansId + `-${h}`;
// console.log(`getScoreByProposition : ${p} - ${itemId}`);           
            switch(this.data.listArr[h].type*1){
                case 1 : //combobox
                    nbRep++;
                    var obInp = document.getElementById(itemId);
                    if(obInp.value == ans.items[h]) { nbGood++;}
                    console.log(`getScoreByProposition : ${p} - ${obInp.value}`);           
                    break;
                case 2 : //textbox
                    nbRep++;
                    var obInp = document.getElementById(itemId);
                    //if(sanityseTextForComparaison(obInp.value) == sanityseTextForComparaison(ans.items[h])) {nbGood++;}
                    if(compareExp(obInp.value, ans.items[h], true)) {nbGood++;}
                    //if((obInp.value.trim()) == (ans.items[h].trim() )) {nbGood++;}
                    break;
                    
                //compte pour rien    
                case 0: //label
                case 3 : //conjonction
                default:
                    //p = 0;
                    break;
             }
        }
        if (nbGood == nbRep) {points += ans.points;}
        console.log(`===> ${ans.items[h]} - points : ${nbGood} / ${nbRep} => ${points}`);
     }

    return points;
}

// //---------------------------------------------------
getAllPropositions (flag = 0){
 console.log('getAllPropositions');  
   var currentQuestion = this.question;
    var htmlArr = [];

    //alert(currentQuestion.answers.length)
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    var allAns = this.shuffleAnswers();
    var item ='';
    //var allAns = currentQuestion.answers;
    var tplnumbering   = `<td style='width:3%;text-align:right;'>{numbering}</td>`; 
    var tplBasic = `<td style='width:{width}%;' {alignement}>{itemValue}</td>`; 

    htmlArr.push(`${this.getImage()}<center><table>`);
    
    for(var k = 0; k < allAns.length; k++){
        var ans = allAns[k];
        htmlArr.push('<tr>');
        if(currentQuestion.numbering >0){
            item = tplnumbering.replace('{numbering}', getNumAlpha(k,currentQuestion.numbering));
            htmlArr.push(item);
        }
        
        for(var h = 0; h < this.data.nbList; h++){
            
            var itemId   = ans.ansId + `-${h}`;
            var itemName = ans.ansId + `-${h}`;
            
            switch(this.data.listArr[h].type*1){
                case 0: //label
                    item = tplBasic.replace('{itemValue}', ans.items[h])
                                   .replace('{alignement}', 'right')
                                   .replace('{width}', this.data.listArr[h].width);
                    break;
                case 1 : //combobox
                    item = tplBasic.replace('{itemValue}' , ans.items[h])
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width);
                    break;
                case 2 : //textbox
                    item = tplBasic.replace('{itemValue}', ans.items[h])
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width);

                    break;
                case 3 : //conjonction
                default:
                    item = tplBasic.replace('{itemValue}', ans.items[h].replaceAll(' ', '&nbsp;'))
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width); 
                    break;
             }
            htmlArr.push(item);

            
        }
        htmlArr.push('</tr>');
    }
    htmlArr.push(`</table></center>`);
   
    //return "en construction";
    return htmlArr.join("\n");
}




//---------------------------------------------------
getGoodReponses (){
console.log('getGoodReponses');  
  
    var currentQuestion = this.question;
    var htmlArr = [];

    //alert(currentQuestion.answers.length)
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    var allAns = this.shuffleAnswers();
    var item ='';
    //var allAns = currentQuestion.answers;
    var tplnumbering   = `<td style='width:3%;text-align:right;'>{numbering}</td>`; 
    var tplBasic = `<td style='width:{width}%;' {alignement}>{itemValue}</td>`; 
    var tplPoints = `<td style='width:{width}%;' left>===>{points}</td>`; 

    htmlArr.push(`${this.getImage()}<center><table>`);
    
    for(var k = 0; k < allAns.length; k++){
        var ans = allAns[k];
        htmlArr.push('<tr>');
        if(currentQuestion.numbering >0){
            item = tplnumbering.replace('{numbering}', getNumAlpha(k,currentQuestion.numbering));
            htmlArr.push(item);
        }
        
        for(var h = 0; h < this.data.nbList; h++){
            
            var itemId   = ans.ansId + `-${h}`;
            var itemName = ans.ansId + `-${h}`;
            
            switch(this.data.listArr[h].type*1){
                case 0: //label
                    item = tplBasic.replace('{itemValue}', ans.items[h])
                                   .replace('{alignement}', 'right')
                                   .replace('{width}', this.data.listArr[h].width);
                    break;
                case 1 : //combobox
                    item = tplBasic.replace('{itemValue}' , ans.items[h])
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width);
                    break;
                case 2 : //textbox
                    item = tplBasic.replace('{itemValue}', ans.items[h])
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width);

                    break;
                case 3 : //conjonction
                default:
                    item = tplBasic.replace('{itemValue}', ans.items[h].replaceAll(' ', '&nbsp;'))
                                   .replace('{alignement}', 'left')
                                   .replace('{width}', this.data.listArr[h].width); 
                    break;
             }
            htmlArr.push(item);

            item = tplPoints.replace('{points}',  ans.points);
            htmlArr.push(item);
            
        }
        htmlArr.push('</tr>');
    }
    htmlArr.push(`</table></center>`);
   
    //return "en construction";
    return htmlArr.join("\n");

 }

/* ************************************
*
* **** */
showGoodAnswers (answerContainer){
    this.showAnswers(true)
}
/* ************************************
*
* **** */
showBadAnswers (answerContainer){
    this.showAnswers(false)
}
/* ************************************
*
* **** */
showAnswers (goodAnswers = true){
console.log('===========> showAnswers');
  var currentQuestion = this.question;


     for(var k = 0; k < currentQuestion.answers.length; k++){
        var ans = currentQuestion.answers[k];
        for(var h = 0; h < this.data.nbList; h++){  
            var itemId   = ans.ansId + `-${h}`;
            if(goodAnswers){
                var value = ans.items[h];
            }else{
                var value = getRandomArray(this.data.listArr[h].items);
            }
            
            switch(this.data.listArr[h].type*1){
                case 1 : //combobox
                    var obInp = document.getElementById(itemId);
                    obInp.value = value; 
                    //console.log(`getScoreByProposition : ${p} - ${obInp.value}`);           
                    break;
                case 2 : //textbox
                    var obInp = document.getElementById(itemId);
                    obInp.value = value;
                    break;
             }

        }
     }

        
}

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId = ''){
    var currentQuestion = this.question;
    var tpl = '';

    if(!this.isImage()) {disposition = 'disposition-00';}

    switch(disposition) {
    case 'disposition-01':
        tpl = '{image}<br>{allAnswers}';
        break;
    
    case 'disposition-02':
        tpl = `<table>
                <tbody>
                  <tr>
                    <td>{image}</td>
                    <td>{allAnswers}</td>
                  </tr>
                </tbody>
              </table>`;
        break;
    
    case 'disposition-03':
        tpl = `<table>
                <tbody>
                  <tr>
                    <td>{allAnswers}</td>
                    <td>{image}</td>
                  </tr>
                </tbody>
              </table>`;
        break;
    
    case 'disposition-00':
    default:
        tpl = '{allAnswers}';
        break;
    
    }    

    return tpl + '<br>';

}

} // ----- fin de la class ------

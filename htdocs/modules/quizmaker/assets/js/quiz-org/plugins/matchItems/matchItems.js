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
build (){
    this.boolDog = false;
    return this.getInnerHTML() ;
 }
//-----------------------------------------------------------
getInnerHTML (){
    var currentQuestion = this.question;
    var htmlArr = [];

    //alert(currentQuestion.answers.length)
    var id = currentQuestion.answers[0].id;
    var name = this.getName();
    var allAns = this.shuffleAnswers();
    var item ='';
    //var allAns = currentQuestion.answers;
    var tplTextBox     = `<td style='width:{width}%;'><input type="text" id="{itemId}" value="{itemValue}" name="" {disabled} style='text-align:{textalign};'></td>`; 
    //var tplTextBox     = `<td style='width:{width}%;'><input type="text" id="{itemId}" value="{itemValue}" name="{listName}" ansIndex='{index}'></td>`;
    var tplListbox     = `<td style='width:{width}%;'>{itemValue}</td>`; 
    var tplConjonction = `<td style='width:{width}%;text-align:{textalign};'>{itemValue}</td>`; 
    var tplnumbering   = `<td style='width:3%;text-align:right;'>{numbering}</td>`; 
    var tplEmpty       = `<td style='width:{width}%;'></td>`; 
    var tplTitle       = `<td style='width:{width}%;text-align:center;''>{title}</td>`; 
 
    var nbColumns = currentQuestion.options.nbColumns;

    var delta = 100;
    for(var h = 0; h < nbColumns; h++){
        delta -= this.data.listArr[h].width;
    }


    
console.log(`getInnerHTML : ${currentQuestion.options.list1_type} - ${currentQuestion.options.list2_type}`);    
    htmlArr.push(`${this.getImage()}<center><table>`);
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
    
    
    for(var k = 0; k < allAns.length; k++){
        var ans = allAns[k];
        htmlArr.push('<tr>');
        if(delta  > 0){
            htmlArr.push(tplEmpty.replace('{width}', delta / 2));
        }
        
        if(currentQuestion.numbering > 0){
            item = tplnumbering.replace('{numbering}', getNumAlpha(k,currentQuestion.numbering));
            htmlArr.push(item);
        }
 
        for(var h = 0; h < this.data.nbList; h++){
            //var listWidth = (h == this.data.nbList) ? this.data.listArr[h].width+delta : this.data.listArr[h].width;
            var listWidth = this.data.listArr[h].width;
            var textalign = this.data.listArr[h].textalign;
            var itemId   = ans.ansId + `-${h}`;
            var itemName = ans.ansId + `-${h}`;
            
            switch(this.data.listArr[h].type*1){
                case 0: //label
                    item = "Label:" + ans.items[h];
                    item = tplTextBox.replace('{itemValue}', ans.items[h])
                                     .replace('{itemId}', itemId)
                                     .replace('{textalign}', textalign)
                                     .replace('{disabled}', 'disabled')
                                     .replace('{width}', listWidth);
                    break;
                case 1 : //combobox
                    item = tplListbox.replace('{itemValue}' , getHtmlCombobox(itemName, itemId, this.data.listArr[h].items, textalign))
                                     .replace('{width}', listWidth);
                    break;
                case 2 : //textbox
                    item = "textbox:" + ans.items[h];
                    item = tplTextBox.replace('{itemValue}', '')
                                     .replace('{itemId}', itemId)
                                     .replace('{listName}', this.data.list1Name)
                                     .replace('{textalign}', textalign)
                                     .replace('{disabled}', '')
                                     .replace('{width}', listWidth);

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
    
    
/////////////////////////////////

///////////////////////////////////

    
    htmlArr.push(`</table></center>`);
   
    //return "en construction";
    return htmlArr.join("\n");

    
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
    for(var k = 0; k < currentQuestion.answers.length; k++){
        console.log(k + "--->" + currentQuestion.answers[k].proposition);
        currentQuestion.answers[k].items = [];
        var tExp = currentQuestion.answers[k].proposition.split(","); 
        for (var i = 0; i < nbColumns; i++){
            if(tExp[i]){
                currentQuestion.answers[k].items.push(tExp[i]);
                if(listArr[i].items.indexOf(tExp[i]) == -1){
                    listArr[i].items.push(tExp[i]);
                }
            }
        }
//         if(nbList > currentQuestion.answers[k].items.length){
//             nbList = currentQuestion.answers[k].items.length;
//         }
    }
    
    
    //a voir si il est judicieux dajouter un parametre pour trier, mélénger ou laisser la liste en l'état
    //pour l'instant on force le tri
    if(true){
        for (var h = 0; h < nbMaxList; h++){
            listArr[h].items.sort();
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
                    if(sanityseTextForComparaison(obInp.value) == sanityseTextForComparaison(ans.items[h])) {nbGood++;}
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


      if(currentQuestion.points){
        return (points == this.scoreMaxiBP) ? currentQuestion.points :  0;
      }else{
        return points;
      }

}

// //---------------------------------------------------
getAllReponses (flag = 0){
 console.log('getAllReponses');  
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
/*
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push(`${tKeyWords[key].key} => ${tKeyWords[key].match} => ${tKeyWords[key].points}`);
     }

    return tReponses.join("<br>");
  
*/
  
  
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
//console.log('===========> showAnswers');
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





/*










    var currentQuestion = this.question;
    var tag = '';
    var obs = this.getObDivMain;
    var itemId = '';
    var allItems = null;
    var ansIndex = '';
    var couple = null;
    var rndIdx = 0;
    
    switch(currentQuestion.options.list1_type*1){
        case 0 : tag = 'zzz'    ; break; //label
        case 1 : tag = 'select' ; break; //combobox
        case 2 : tag = 'input'  ; break; //textbox
        default: tag = "?????"  ; break;
     }

    allItems = document.getElementsByName(this.data.list1Name);
    console.log('nb item by name : ' + allItems.length);
    allItems.forEach( (obInput, index) => {
    
//     
         var t = obInput.id.split('-');
//         ansIndex = t[3]*1;
//     console.log('ansIndex = ' + obInput.id + ' / ' + t[3] + ' / ' + ansIndex);
//     
        ansIndex = obInput.id.split('-')[3]*1;
    
     console.log('list 1 : ansIndex = ' + obInput.id + ' / ' + t[3] + ' / ' + ansIndex);
    
        if(goodAnswers){
            couple = currentQuestion.answers[ansIndex].proposition.split(',');
            obInput.value = couple[0];
        }else{
            obInput.value = getRandomArray(this.data.allItems1);
        }
    });
        
    //========================================================
    switch(currentQuestion.options.list1_type*2){
        case 0 : tag = 'zzz'    ; break; //label
        case 1 : tag = 'select' ; break; //combobox
        case 2 : tag = 'input'  ; break; //textbox
        default: tag = "?????"  ; break;
     }

    allItems = document.getElementsByName(this.data.list2Name);
    allItems.forEach( (obInput, index) => {
        ansIndex = obInput.id.split('-')[3]*1;
        if(goodAnswers){
            couple = currentQuestion.answers[ansIndex].proposition.split(',');
            obInput.value = couple[1];
        }else{
            obInput.value = getRandomArray(this.data.allItems2);
        }
        
//      //console.log('list 2 : ansIndex = ' + obInput.id + ' / ' + t[3] + ' / ' + ansIndex);
//      console.log('list 2 : ansIndex = ' + obInput.id + ' / ' + ansIndex);
// 
// console.log('proposition : ' + index + " = " + currentQuestion.answers[ansIndex].proposition);
// console.log('obInput.value : ' + index + " = " + obInput.value);
// console.log('couple : ' + couple[0] + " = " + couple[1]);

//alert('ici -> ' + obInput.id);
    });
*/
        
}


} // ----- fin de la classe ------

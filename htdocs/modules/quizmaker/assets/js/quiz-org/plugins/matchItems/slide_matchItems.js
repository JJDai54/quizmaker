 

 /*******************************************************************
  *                     _matchItems
  * *****************************************************************/
class matchItems extends quizPrototype{
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
    
    var newKeys = this.shuffleArray(this.data.keys);
    var listName = `${name}-list`;
    var textName = `${name}-text`;
    this.data.styleCSS = getMarginStyle(newKeys.length, 2);
    var conjonction = (currentQuestion.options.conjonction) ? `<td>${currentQuestion.options.conjonction}</td>` : "";
    var item1 = '';
    var item2 = '';
    var itemId = '';
    var row = '';
    
    var tplRow = `<tr><td><span>{numbering}</span></td>
                  <td right>{itemList1}</td>
                  ${conjonction}
                  <td left>{itemList2}</td></tr>`;      
                  
    var tplLabel   = `<input type="text" id="{itemId}" {alignement} value="{itemValue}" name="" disabled>`; 
    var tplTextBox = `<input type="text" id="{itemId}" {alignement} value="{itemValue}"  name="{listName}">`;
    htmlArr.push(`${this.getImage()}<center><table>`);
    
    var allAns = this.shuffleAnswers();
    //var allAns = currentQuestion.answers;
    
console.log(`getInnerHTML : ${currentQuestion.options.list1_type} - ${currentQuestion.options.list2_type}`);    
    for(var k = 0; k < allAns.length; k++){
//console.log(allAns[k].proposition);    
        var ans = allAns[k];
console.log(ans.proposition);    
        var tCouple = ans.proposition.split(","); 
        
        itemId = ans.ansId + '-left';
        switch(currentQuestion.options.list1_type*1){
            case 0: //label
                item1 = tplLabel.replace('{itemValue}', tCouple[0]).replace('{itemId}', itemId).replace('{alignement}', 'right');
                break
            case 1 : //combobox
                item1 = getHtmlCombobox(this.data.list1Name, itemId, this.data.allItems1, 'right');
                break
            case 2 : //textbox
                item1 = tplTextBox.replace('{itemValue}', '').replace('{itemId}', itemId).replace('{listName}', this.data.list1Name).replace('{alignement}', 'right');
                //item1 = tplTextBox.replace('{itemValue}', tCouple[0]).replace('{itemId}', itemId);
                break;
            default: item1="?????";
         }
         
        itemId = ans.ansId + '-right';
        switch(currentQuestion.options.list2_type*1){
            case 0: //label
                item2 = tplLabel.replace('{itemValue}', tCouple[1]).replace('{itemId}', itemId).replace('{alignement}', 'left');
                break
            case 1 : //combobox
                item2 = getHtmlCombobox(this.data.list2Name, itemId, this.data.allItems2, 'left');
                break
            case 2 : //textbox
                item2 = tplTextBox.replace('{itemValue}', '').replace('{itemId}', itemId).replace('{listName}', this.data.list2Name).replace('{alignement}', 'left');
                //item2 = tplTextBox.replace('{itemValue}', tCouple[1]).replace('{itemId}', itemId);
                break;
            default: item1="?????";
         }
         
         row = tplRow.replace('{numbering}', getNumAlpha(k,currentQuestion.numbering))
                     .replace('{itemList1}', item1)
                     .replace('{itemList2}', item2);
            htmlArr.push(row);
    }
    
    htmlArr.push(`</table></center>`);
   
    //return "en construction";
    return htmlArr.join("\n");

    
 }
//---------------------------------------------------
prepareData(){
    var currentQuestion = this.question;
    var allItems1 = [];
    var allItems2 = [];

    for(var k = 0; k < currentQuestion.answers.length; k++){
        var tCouple = currentQuestion.answers[k].proposition.split(","); 
        allItems1.push(tCouple[0]);
        allItems2.push(tCouple[1]);
    }
    if(currentQuestion.options.list1_intrus){
        var intrus = currentQuestion.options.list1_intrus.split(',');
        for(var i = 0; i < intrus.length; i++){
            if(intrus[i]){
                allItems1.push(intrus[i]);
            }
        }
    }
    if(currentQuestion.options.list2_intrus){
        var intrus = currentQuestion.options.list2_intrus.split(',');
        for(var i = 0; i < intrus.length; i++){
            if(intrus[i]){
                allItems2.push(intrus[i]);
            }
           
        }
    }
    
    this.data.allItems1 = allItems1;
    this.data.allItems2 = allItems2;
    this.data.list1Name = this.getId('list1');
    this.data.list2Name = this.getId('list2');
    
}

/* ************************************
*
* **** */
getScoreByProposition (answerContainer){
  var currentQuestion = this.question;
  //alert("getScore");
  var points = 0;
  var couple = null;
  var ans = null;
  var v1 = ''
  var v2 = '';

    for(var k = 0; k < currentQuestion.answers.length; k++){
        ans = currentQuestion.answers[k];
        couple = ans.proposition.split(',');
        v1 = document.getElementById(ans.ansId + '-left').value;
console.log(k + ' : ' + couple[0] +  ' / ' + v1);        
console.log(k + ' : ' + couple[1] +  ' / ' + v2);        
        v2 = document.getElementById(ans.ansId + '-right').value; 
        if(couple[0] == v1 && couple[1] == v2){
            points += ans.points;
        }
    }


      return points;

}

// //---------------------------------------------------
getAllReponses (flag = 0){
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push([[tKeyWords[key].key], [tKeyWords[key].match], [tKeyWords[key].points]]);
     }

    return formatArray0(tReponses, "=>");
 }




//---------------------------------------------------
//---------------------------------------------------
getGoodReponses (){
    var currentQuestion = this.question;
     var tReponses = [];

    var newKeys = this.data.keys;     
    var tKeyWords = this.data.kitems;     
     
    for(var k = 0; k< newKeys.length; k++){
        var key = newKeys[k];
        tReponses.push(`${tKeyWords[key].key} => ${tKeyWords[key].match} => ${tKeyWords[key].points}`);
     }

    return tReponses.join("<br>");
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
        
}


} // ----- fin de la classe ------

/*******************************************************************
*                     listboxClassItems
* *****************************************************************/
function getPlugin_listboxClassItems(question, slideNumber){
    return new listboxClassItems(question, slideNumber);
}

/************************************************************************
 *                 listboxClassItems
 * **********************************************************************/

class listboxClassItems extends Plugin_Prototype{
name = "listboxClassItems";

/* *************************************
*
* ******** */
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }
 /* ************************************
* la fontion loadAllList sera appelé apres par l'evennement "initSlide appelé par la prottoype"
* test d'approche pour comparer avec la solution prise par les autres slide avec reloadQuastion()
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    var click = (currentQuestion.options.mouseClick == 1) ? "onclick" : "ondblclick";
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';   
    var tHtml = [];   
    var styleDiv = '' ;
    
      var lWidth = (90 / this.data.groups.length);
      var nbRows = 8;
 
   
    for(var i=0; i < this.data.groups.length; i++) {
        var idFrom = this.data.groups[i].id;
//         if(this.data.groups.length > 1){
//             var indexTo = (i+1) % this.data.groups.length;
//             var onClick = `${click}="quiz_basculeValue('${idFrom}','${this.data.groups[indexTo].id}');"`;
//         }else{
//             var onClick = `${click}="quiz_deleteValue('${idFrom}');"`;
//         }
        var indexTo = (i+1) % this.data.groups.length;
        var onClick = `${click}="quiz_basculeValue('${idFrom}','${this.data.groups[indexTo].id}');"`;

        var attId  = (this._id) ? `id="${this._id}" name="${this._id}"` : ''; 
      
        if(currentQuestion.options.groupDefault == -2){
          styleDiv = (i > 0) ? "display:none;": `width:90%;margin:auto;` ;
        }else{
          styleDiv = `width:${lWidth}%;margin:auto;`;
        }
        tHtml.push(`<div style='text-align:center;${styleDiv}' >` ); 
        tHtml.push(`<span>${this.data.groups[i].libelle}</span><br>`); 
        tHtml.push(`<select id='${idFrom}' name='${idFrom}' size='${nbRows}' ${onClick} style='background:${this.data.groups[i].background};'>`);
        tHtml.push('</select>'); 
        tHtml.push('</div>'); 
    }
 
    return tHtml.join("\n"); 
}


/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;

    var groupsArr = [];
    for(var k = 0; k <= 3; k++){
        var key = 'group' + k;
        if(currentQuestion.options[key]) {
            var t = [];
            t.libelle = currentQuestion.options[key];
            t.id =  this.getId('group', k);
            t.propositions = []; //tableau des propositions du groupe
            t.background = currentQuestion.options[`bgGroup${k}`]; //couleur de fond des groupes
            groupsArr.push(t);
            //alert(this.data.groups[0].libelle);
        }
    }
    
    this.data.nbGroups = groupsArr.length;    
    
    

   
   //repartir les propositions par groupe
    for(var k in currentQuestion.answers){
        currentQuestion.answers[k].id = this.getId('item', k);
        
        var ans = currentQuestion.answers[k];
        ans.index = k;
        
        //au cas ou cela n'aurait été oublié lors de la création
        if(ans.points <= 0) {ans.points = 1;}
        //if(!groupsArr[ans.group*1]) groupsArr[ans.group*1] = [];
        groupsArr[ans.group*1].propositions.push(ans);
    }   
    
    //identification des groupes
    this.data.groups = groupsArr;
    
     if(currentQuestion.options.oneListOnly*1 == 1 && this.data.groups.length == 2){
        currentQuestion.options.groupDefault = -2;
        //alert(currentQuestion.options.groupDefault + ' / ' + this.data.groups.length);
    }    
}
/* *********************************************************
*
* ********************************************************** */
initSlide(){
    this.loadAlllistBox(false);
}
/* ************************************
*
* **** */
reloadQuestion()
  { 
    
    this.loadAlllistBox(false);
  } 

/* *********************************************************
* mode : mode de remplissage des listes selon le cas :
*        0 : random sur tous les groupes
*        2 : groupe par defaut
*        1 : bonnes réponses
* ********************************************************** */
loadAlllistBox(goodAnswers = false){ 
    var currentQuestion = this.question;
    var randGrp = 0;
    var mode = 0; //random sur tous les groupes
    
//     
//     if(currentQuestion.options.groupDefault == 0 && this.data.groups.length == 2){
//         var groupDefault = -2;
//     }else{
//         var groupDefault = currentQuestion.options.groupDefault;
//     }

   if(goodAnswers) {
        mode = 1;  //bonnes réponses
   }
    else if(currentQuestion.options.groupDefault >= 0)  {
        mode = 2; //tous les items dans le groupe par defaut
        var groupDefault = (this.data.groups.length == 1) ? 0 : currentQuestion.options.groupDefault;
    }else if(currentQuestion.options.groupDefault == -2){
        mode = 2; //tous les items dans le groupe par defaut
        var groupDefault = 0;
    }
 
//alert(`${mode} | ${groupDefault} | ${currentQuestion.options.groupDefault} | ${currentQuestion.question}`);    
//alert('loadAlllistBox : random = ' + isRandom);    
    var obLists = [];
    for(var i=0; i < this.data.groups.length; i++) {
        var ob = document.getElementById(this.data.groups[i].id);
        if(ob) ob.innerHTML = '';
        obLists.push(ob);
    }

    var shuffleIndex = shuffleIndexArr(currentQuestion.answers.length, currentQuestion.shuffleAnswers);
    //--------------------------------------------------------------------
//alert('parcour des propositions');    
    //for(var k=0; k < currentQuestion.answers.length; k++) {
    for(var k=0; k < shuffleIndex.length; k++) {
        var ans = currentQuestion.answers[shuffleIndex[k]]; 
        switch(mode){
            case 1:   randGrp = ans.group; break;
            case 2:   randGrp = groupDefault; break;
            default : randGrp = getRandom(obLists.length-1) ; break;
        }
        //var randGrp = (isRandom) ? getRandom(obLists.length-1) : ans.group;
        
//        alert(randGrp + "-" + ans.proposition);

        var bolOk = (!goodAnswers || ans.points >  0);
        if( obLists[randGrp] && bolOk){
            var option = document.createElement("option");
            option.value = ans.proposition;
            option.text = ans.proposition;
            option.id = ans.ansId;
            option.setAttribute('ansKey', ans.index);
            obLists[randGrp].appendChild(option);
        }
    }
    
}
        



/* *************************************
*
* ******** */

getScoreByProposition ( answerContainer){
var score = 0;
var isScoreOk = 1; //si une reponse a un nombre de points egal à zéro le score est é&gal à zéro
    //alert("getScoreByProposition");

    var currentQuestion = this.question;
    console.log (`===> ${currentQuestion.question}`);
//alert('nbgroupe = ' + this.data.groups.length);
    for(var i=0; i < this.data.groups.length; i++) {
        var GroupId = this.data.groups[i].id;
        var obGroup = document.getElementById(GroupId);
        if(!obGroup) continue;
        var items = obGroup.getElementsByTagName('option');   
                              
        for(var h = 0; h < items.length; h++){
            var ansKey = items[h].getAttribute('ansKey')*1;
            var points = currentQuestion.answers[ansKey].points*1;
            if(currentQuestion.answers[ansKey].group == i) {
                 score += points; 
//                 if (points == 0) {isScoreOk = 0;}
//                  score += points; 
//             }else{
//                 // score -= points; 
//                 isScoreOk = 0;
                 
            }
    //alert("getScoreByProposition : " + GroupId + "\nisScoreOk= " + isScoreOk + "\npoints = " + points);

            //this.blob (`===>${ansKey} => ${currentQuestion.answers[ansKey].points} => total=${score} => ${currentQuestion.answers[ansKey].proposition}`);
            this.blob (`+===>${ansKey} => ${currentQuestion.answers[ansKey].points} => total=${score} => ${items.text}`);
        }
    }
    

    //return score * isScoreOk;
    return score;
  }

/* *************************************
*
* ******** */

getAllReponses (flag = 0){
    var currentQuestion = this.question;
    console.log (`===> ${currentQuestion.question}`);

    var tGroups = [];
    var htmlArr = [];
//alert('nbgroupe = ' + this.data.groups.length);
    htmlArr.push("<table><tr>");
    for(var i=0; i < this.data.groups.length; i++) {
    var tAns = [];
        var GroupId = this.data.groups[i].id;

                              
        for(var k = 0; k < this.data.groups[i].propositions.length; k++){
            var ans =  this.data.groups[i].propositions[k]; 

            tAns.push ([[ans.proposition], [ans.points]]);
            ///this.blob (`${ansKey} => ${currentQuestion.answers[ansKey].points} => total=${points} => ${currentQuestion.answers[ansKey].proposition}`);
        }
        htmlArr.push("<td style='vertical-align: top;'><center>" + this.data.groups[i].libelle + "</center>");        
        htmlArr.push(formatArray0(tAns, "",""));
        htmlArr.push("</td>");        
    }
    
    htmlArr.push("</tr></table>");
    return  htmlArr.join("\n");

 }

/* ************************************
*
* **** */
showGoodAnswers(currentQuestion, quizDivAllSlides)//, answerContainer
  {
    this.loadAlllistBox(true);
  } 

/* ************************************
*
* **** */
showBadAnswers()
{
    this.loadAlllistBox(false);
}
 
} // ----- fin de la classe ------

/************************************************************************
 *                       quiz_listBox
 * **********************************************************************/

class quiz_listBoxOb{
select = null;
/* *************************************
*
* ******** */
constructor (idSelect, value=null, caption=''){     
    this.select = document.createElement("select");
    select.name = idSelect;
    select.id = idSelect;
    
}

addOption(vValue, sText, id=null){
    var option = document.createElement("option");
    option.value = vValue;
    option.text = sText;
    if(id) option.id = id;
    select.appendChild(this.select);

}

getSelect(){
    return this.select;
}
setToParent(idParent){
    document.getElementById().appendChild(this.select);
}
} // ----- fin de la classe ------
/************************************************************************
 *                       quiz_listBox
 * **********************************************************************/

class quiz_listBox{
_options = [];
_id = null;
/* *************************************
*
* ******** */
constructor (idSelect = null){   
//alert('quiz_listBox : ' + idSelect);  
    this._id = idSelect;
}

addOption(vValue, sText, id=null){
    this._options.push([vValue,sText,id]);
    //alert(vValue);
}

render(value=null, caption='', className=null, nbRows=5, onEvent=null, lWidth='90'){
      var tHtml = [];
      //className='question-combobox';
      var attId  = (this._id) ? `id="${this._id}" name="${this._id}"` : ''; 
      var attClass  = (className) ? ` class='${className}'` : ''; 
      var attSize  = (nbRows) ? ` size="${nbRows}"` : '';
      var attEvent = (onEvent) ? onEvent : '';
      var attStyle = ` style="height:300px;width:${lWidth}%;"`;

      tHtml.push(`<SELECT ${attId}${attClass}${attSize}${attEvent}${attStyle}>`);
      
      for (var h = 0; h < this._options.length; h++){
        tHtml.push(`<OPTION id='${this._options[h][3]}' VALUE="${this._options[h][0]}">${this._options[h][0]}</OPTION>`);
      }
      tHtml.push(this.renderOptions(value));
      tHtml.push(`</SELECT>`);
      return tHtml.join("\n");

}

renderOptions(value=null){
      var tHtml = [];
      for (var h = 0; h < this._options.length; h++){
        var selected = (value == h) ? ' selected' : ''; 
        tHtml.push(`<OPTION id='${this._options[h][3]}' VALUE="${this._options[h][0]}"${selected}>${this._options[h][0]}</OPTION>`);
      }
      return tHtml.join("\n");
}

} // ----- fin de la classe ------


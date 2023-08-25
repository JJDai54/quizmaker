
/************************************************************************
 *                 _listboxIntruders
 * **********************************************************************/

class listboxIntruders extends quizPrototype{
name = "listboxIntruders";

/* *************************************
*
* ******** */
build (){

    var  currentQuestion = this.question;

    return this.getInnerHTML();
 }
 /* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    var click = (currentQuestion.options.mouseClick == 1) ? "onclick" : "ondblclick";
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';   
    var tHtml = [];   
      tHtml.push(`<div class='quiz_slide_listboxIntruders_main'>` ); 
    
      var lWidth = (90 / this.data.groups.length);
      var nbRows = 8;
 
   
    for(var i=0; i < this.data.groups.length; i++) {
        var idFrom = this.data.groups[i].id;
        if(this.data.groups.length > 1){
            var indexTo = (i+1) % this.data.groups.length;
            var onClick = `${click}="quiz_basculeValue('${idFrom}','${this.data.groups[indexTo].id}');"`;
        }else{
            var onClick = `${click}="quiz_deleteValue('${idFrom}');"`;
        }
      var attId  = (this._id) ? `id="${this._id}" name="${this._id}"` : ''; 
      
      tHtml.push(`<div style='width:${lWidth}%;'>` ); 
      tHtml.push(`<span>${this.data.groups[i].libelle}</span><br>`); 
      tHtml.push(`<SELECT id='${idFrom}' name='${idFrom}' size='${nbRows}' ${onClick}>`);
      tHtml.push('</select>'); 
      tHtml.push('</div>'); 
    }
      tHtml.push('</div>'); 
    return tHtml.join("\n"); 
}


/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;
    
    var groups = [];

   
   //repartir les proposition par group
    for(var k in currentQuestion.answers){
        currentQuestion.answers[k].id = this.getId('item', k);
        
        var ans = currentQuestion.answers[k];
        ans.index = k;
        if(ans.points == 0) {ans.points = 1;}
        if(!groups[ans.group*1]) groups[ans.group*1] = [];
        groups[ans.group*1].push(ans);
    }   
    
    this.data.groups = [];
    //identification des groupes

    for(var k = 0; k <= 3; k++){
        var key = 'group' + k;
        if(currentQuestion.options[key]) {
            var t = [];
            t.libelle = currentQuestion.options[key];
            t.id =  this.getId('group', k);
            t.propositions = groups[k];
            this.data.groups.push(t);
            //alert(this.data.groups[0].libelle);
        }
    }
    
    
    

    this.data.urlCommonImg = quiz_config.urlCommonImg;
}
/* *********************************************************
*
* ********************************************************** */
initSlide(){
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
    if(goodAnswers)  mode = 1; //bonnes réponses
    else if(currentQuestion.options.groupDefault >= 0)  {
        mode = 2; //tous les items dans le groupe par defaut
        var groupDefault = (this.data.groups.length == 1) ? 0 : currentQuestion.options.groupDefault;
    }
 
//alert(`${mode} | ${groupDefault} | ${currentQuestion.options.groupDefault} | ${currentQuestion.question}`);    
//alert('loadAlllistBox : random = ' + isRandom);    
    var obLists = [];
    for(var i=0; i < this.data.groups.length; i++) {
        var ob = document.getElementById(this.data.groups[i].id);
        if(ob) ob.innerHTML = '';
        obLists.push(ob);
    }

    var shuffleIndex = shuffleIndexArr(currentQuestion.answers.length, currentQuestion.options.shuffleAnswers);
    //--------------------------------------------------------------------
//alert('parcour des propositions');    
    //for(var k=0; k < currentQuestion.answers.length; k++) {
    for(var k=0; k < shuffleIndex.length; k++) {
        var ans = currentQuestion.answers[shuffleIndex[k]]; 
        switch(mode){
            case 1:   randGrp = ans.group; break;
            case 2:   randGrp = groupDefault; break;
            default : randGrp = getRandomIntInclusive(0, obLists.length-1) ; break;
        }
        //var randGrp = (isRandom) ? getRandomIntInclusive(0, obLists.length-1) : ans.group;
        
//        alert(randGrp + "-" + ans.proposition);

        var bolOk = (!goodAnswers || ans.points >  0);
        if( obLists[randGrp] && bolOk){
            var option = document.createElement("option");
            option.value = ans.proposition;
            option.text = ans.proposition;
            option.id = ans.id;
            option.setAttribute('ansKey', ans.index);
            obLists[randGrp].appendChild(option);
        }
    }
    
}
        

/* *************************************
*
* ******** */
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
    
    for(var k in currentQuestion.answers){
        var points = currentQuestion.answers[k].points * 1; 
        if (points > 0) this.scoreMaxiBP += points;
        if (points < 0) this.scoreMiniBP += points;
    }
     return true;
 }


/* *************************************
*
* ******** */

getScore ( answerContainer){
var points = 0;
var bolOk = true;

    var currentQuestion = this.question;
    console.log (`===> ${currentQuestion.question}`);

    for(var i=0; i < this.data.groups.length; i++) {
        var GroupId = this.data.groups[i].id;
        var obSelect = document.getElementById(GroupId);
        if(!obSelect) continue;
        var obOpts = obSelect.getElementsByTagName('option');   
                              
        for(var h = 0; h < obOpts.length; h++){
            var ansKey = obOpts[h].getAttribute('ansKey')*1;
            //alert(ansKey);
            //alert(currentQuestion.answers[ansKey].proposition); 
            if(this.data.groups.length == 1){
                 points += currentQuestion.answers[ansKey].points*1; 
            }else{
              if (currentQuestion.answers[ansKey].group == i){
                 points += currentQuestion.answers[ansKey].points*1; 
              }else{
                 points -= currentQuestion.answers[ansKey].points*1; 
              }
            }
            console.log (`${ansKey} => ${currentQuestion.answers[ansKey].points} => total=${points} => ${currentQuestion.answers[ansKey].proposition}`);
        }
    }
    
    
    
    
//-------------------------------------------------------
    return points;
    var currentQuestion = this.question;

    var id1 = `${this.getName()}-1`;
    var ob1 = getObjectById(id1);
    
    //recupe des items restants
    var tOptions = ob1.options;
    var tSelected = [];
    for(var i = 0; i < tOptions.length; i++) {
        tSelected.push(tOptions[i].value);
   }
    
    //tout a été sélectioné c'est comme si rien avit été sélectioné, renvois 0
    if (tOptions.length === 0) return 0;
    //si aucun element selectionné renvoi 0 point    
    if (tOptions.length === currentQuestion.answers[0].keys.length) return 0;
       //-----------------------------------

    //recheche des élément absent de la list
    var tKeyWords = this.data.keyWords;
      for(var key in tKeyWords)
      {
        if (tSelected.indexOf(key) == -1){
            points += tKeyWords[key]*1;
        }
        
      }
       
      return points;

  }

/* *************************************
*
* ******** */
isInputOk ( answerContainer){
return true;
    var currentQuestion = this.question;

var bolOk = true;
return bolOk;
    var id1 = `${this.getName()}-1`;
    var ob1 = getObjectById(id1);

       var tOptions = ob1.options;
       var minReponses = currentQuestion.options.minReponses;
       var nbRep = currentQuestion.answers[0].keys.length - tOptions.length;

       if (minReponses == 0 && nbRep > 0){
         bolOk = (nbRep > 0);
       }else{
         bolOk = (nbRep >= minReponses);
       }



      return bolOk;

 }

/* *************************************
*
* ******** */

/* *************************************
*
* ******** */

getAllReponses (flag = 0){
    var currentQuestion = this.question;
    
    var tReponses = [];
    var tKeyWords = this.data.keyWords;
    
    
    //tri desc sur le tableau
    tKeyWords = sortArrayKey(tKeyWords,"d");
    
     
    for(var key in tKeyWords)
    {
        //tReponses.push (`${key} ===> ${tKeyWords[key]} points`) ;       
        tReponses.push ([key, tKeyWords[key]]) ;       
    }

    return formatArray0(sortArrayArray(tReponses, 1, "DESC"), "=>");
 }

//---------------------------------------------------
update(nameId, chrono) {
}


//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 
//--------------------------------------------------------------------------------
reloadQuestion() {
    this.loadAlllistBox(false);
//     if (this.question.options.repartition.toLowerCase() == "m")
//         this.reloadQuestion2();
//     else
//         this.reloadQuestion1();
}

  
/* ************************************
*
* **** */
showGoodAnswers()
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


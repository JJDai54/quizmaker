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
buildSlide (bShuffle = true){
    this.boolDog = false;
    return this.getInnerHTML(bShuffle);
 }
 /* ************************************
* la fontion loadAllList sera appelé apres par l'evennement "initSlide appelé par la prottoype"
* test d'approche pour comparer avec la solution prise par les autres slide avec reloadQuastion()
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    var click = (options.mouseClick == 1) ? "onclick" : "ondblclick";
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
        var indexTo = (i+1) % this.data.groups.length;
        var onClick = `${click}="quiz_basculeValue('${idFrom}','${this.data.groups[indexTo].id}');"`;

        var attId  = (this._id) ? `id="${this._id}" name="${this._id}"` : ''; 
      
        if(options.oneListOnly){
          styleDiv = (i > 0) ? "display:none;": `width:90%;margin:auto;` ;
        }else{
          styleDiv = `width:${lWidth}%;margin:auto;`;
        }
        tHtml.push(`<div style='text-align:center;${styleDiv}' >` ); 
        tHtml.push(`<span>${this.data.groups[i].caption}</span><br>`); 
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
    var options = currentQuestion.options;
    
    options.groupDefault = options.groupDefault*1;

    if (options.groupDefault == -2) {
        options.groupDefault = 0;
        options.oneListOnly = true;
    }else{
        options.oneListOnly = false;
    }
        
    this.data.groups = clsGroup.repartir(this, true);
      
return;
}
/* *********************************************************
*
* ********************************************************** */
initSlide(){
    this.reloadQuestion(true);
}

/* *********************************************************
* mode : mode de remplissage des listes selon le cas :
*        0 : random sur tous les groupes
*        2 : groupe par defaut
*        1 : bonnes réponses
* ********************************************************** */
reloadQuestion(reloadMode = reloadShuffle){
    var currentQuestion = this.question;
    var randGrp = 0;
    var mode = 0; //random sur tous les groupes
    
//     
//     if(currentQuestion.options.groupDefault == 0 && this.data.groups.length == 2){
//         var groupDefault = -2;
//     }else{
//         var groupDefault = currentQuestion.options.groupDefault;
//     }

   if(reloadMode == reloadClassified) {
        mode = 1;  //bonnes réponses
   }else if(currentQuestion.options.groupDefault >= 0)  {
        mode = 2; //tous les items dans le groupe par defaut
        var groupDefault = (this.data.groups.length == 1) ? 0 : currentQuestion.options.groupDefault;
    }
 
    //recupe des listBox
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

        var bolOk = (!reloadMode == reloadShuffle || ans.points >  0);
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
    var options = this.question.options;
    
    var groupDefault = options.groupDefault;    
    if(groupDefault == -2){groupDefault = 0;}
    
    
    
    
    //console.log (`===> ${currentQuestion.question}`);
//alert('nbgroupe = ' + this.data.groups.length);
    for(var i=0; i < this.data.groups.length; i++) {
        var GroupId = this.data.groups[i].id;
        var obGroup = document.getElementById(GroupId);
        if(!obGroup) continue;
        var items = obGroup.getElementsByTagName('option');   
                              
        for(var h = 0; h < items.length; h++){
            var ansKey = items[h].getAttribute('ansKey')*1;
            var points = currentQuestion.answers[ansKey].points*1;
            
            if(currentQuestion.answers[ansKey].group == i){
                if(options.groupDefaut == -1){
                    score += points;
                }else if (options.groupDefaut != i){
                    score += points;
                }
            }else{
                if (options.groupDefaut >= 0){
                    score -= points;
                }
            }            
            
            if(currentQuestion.answers[ansKey].group == i) {
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

getAllPropositions (flag = 0){
    var currentQuestion = this.question;
    //console.log (`===> ${currentQuestion.question}`);

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

 
} // ----- fin de la class ------


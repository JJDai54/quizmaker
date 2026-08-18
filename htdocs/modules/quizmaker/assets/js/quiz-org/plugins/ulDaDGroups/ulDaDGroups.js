/*******************************************************************
*                     plugin_ulSortList
* *****************************************************************/
function getPlugin_ulDaDGroups(question, slideNumber){
    return new ulDaDGroups(question, slideNumber);
}

 /* ******************************************************************
  *                     plugin_ulSortList
  * **************************************************************** */

class ulDaDGroups extends Plugin_Prototype{
name = "ulDaDGroups";

//---------------------------------------------------
buildSlide (bShuffle = true){
    this.boolDog = false;
    return this.getInnerHTML(bShuffle);
 }


/* ************************************
*
* **** */
getInnerHTML(bShuffle = true){
    var currentQuestion = this.question;
    var options = this.question.options;
    
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
var divHeight = options.imgHeight1*1+12;  
var posCaption = options.showCaptions;    
//var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
//var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

var ImgStyle=`style="height:${divHeight}px;"`;

    
//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0

var tpl = this.getDisposition(options.disposition, 'ulDaDGroups');
    //----------------------------------------------------------------------------------------

    var groups = [];
    var ans;
    var groupIndex;
    for(var k = 0; k < options.nbGroups; k++){
        groups[k] = [];
    }
    
   //repartir les propositions par group
   var shuffleArr = this.shuffleAnswers(true);
    for(var k in shuffleArr){
        //index = getRandom(options.nbGroups-1);
        groupIndex = (options.groupDefault*1 < 0)  ? getRandom(options.nbGroups-1) : options.groupDefault;
        groups[groupIndex].push(shuffleArr[k]);

    }
   

    for(var k = 0; k < options.nbGroups; k++){
        var tHtml = [];
        var groupName = this.getId('group', k);
        
        for(var j = 0; j < groups[k].length; j++){
            ans = groups[k][j];

//             switch (posCaption){
//                 case 'T': captionTop =    ans.caption + qbr ; break;
//                 case 'B': captionBottom = qbr + ans.caption ; break;
//                 default: break;
//             }
            var backGround = (ans.background) ? `background:${ans.background};` : '';
            //if (!ans.proposition.trim() == '$$$') ans.proposition = '&nbsp;';
            var caption = replaceDoubleSlash(ans.proposition);
            tHtml.push(`
            <li id='${ans.ansId}' class='quiz_slist' style='width:${options.ulWidth}%;${backGround}'>${caption}</li>`
            );

        }
        tpl=tpl.replace(`{contentGroup${k}}`, tHtml.join("\n")).
        replace(`{libGroup${k}}`, this.data.groups[k].caption);        
    }

    //---------------------------------------------------------------------

    return '<center>' + tpl + '</center>';
}
//---------------------------------------------------
initSlide (){
    //alert ("===> initSlide : " + this.question.pluginName  + " - " + this.question.question + " \n->" + this.getName());

    for(var k = 0; k < this.question.options.nbGroups; k++){ 
        var groupName = this.getId('group', k);
        var obGroup = document.getElementById(groupName);
        if(obGroup){
            this.init_slist(obGroup);
        }
    }
    
    return true;
 }
 
/* *********************************************************
https://code-boxx.com/drag-drop-sortable-list-javascript/
********************************************************* */
init_slist (target) {
  // (A) SET CSS + GET ALL LIST ITEMS
  target.classList.add("quiz_ulDaDGroups_slist");
  let items = target.getElementsByTagName("li"), current = null;
//alert('ok=>' + target.id + "\n nb items = " + items.length);  
//return true;
  // (B) MAKE ITEMS DRAGGABLE + SORTABLE
  for (let i of items) {
    // (B1) ATTACH DRAGGABLE
    i.draggable = true;
    
     // (B2) DRAG START - YELLOW HIGHLIGHT DROPZONES
    i.ondragstart = e => {
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.setData("text", e.target.getAttribute("id"));
        //console.log('init_slist.start ===>' + e.target.getAttribute("id"));
      }
    };
   
}


/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    options.groupDefault = options.groupDefault*1;
        
    this.data.groups = clsGroup.repartir(this, true);
return;
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    options.groupDefault = options.groupDefault*1;
    
    var groups = [];
    groups[0] = [];


   //repartir les proposition par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        ans.group = ans.group*1;
        ans.ansId = this.getId('item', k);
        ans.caption.replace(' ', qbr);
                        
        if(ans.points == 0) {ans.points = 1;}
        if(!groups[ans.group*1]) groups[ans.group*1] = [];
        groups[ans.group*1].push(ans);
    }   
    
    this.data.groups = groups;
    
    this.data.groupsLib=[];
    for(var k = 0; k <= 3; k++){
        var key = 'group' + k;
        if(currentQuestion.options[key]) {this.data.groupsLib.push(currentQuestion.options[key]);}
    }
    this.initMinMaxQQ(2);
    
}

/* *************************************
*
    this.scoreMaxiBP = options.nbImages * currentQuestion.answers[0].points;
    this.scoreMiniBP = 0;
    
    return true;
* ******** */
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
        
    this.scoreMaxiBP = 0;
    this.scoreMiniBP = 0;
    var ans;

    this.blob('computeScoresMinMaxByProposition -----------------------------------------');
    for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        //alert(`options.groupDefault = ${options.groupDefault} - ans.group = ${ans.group}`)
        if(options.groupDefault*1 >= 0){
            if(ans.group*1 != options.groupDefault*1){
                this.scoreMaxiBP += ans.points*1;
            }
        }else{
            this.scoreMaxiBP += ans.points*1;
        }            
                        
    }
    
     return true;

}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    var points = 0;
    var ans;
    var obAns;
    var idDivGood;
    var groupOk = 0 //=00 annule ne nombre de points sinon le compte;
/*
*/
    
//this.blob('showGoodAnswers -----------------------------------------');
      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        obAns = document.getElementById(ans.ansId);
        idDivGood =  this.getId('group', ans.group);
        //this.blob(`divGood = ${idDivGood} - divFound = ${obAns.parentNode.id}`);
        var groupNum = obAns.parentNode.getAttribute('groupNum');
        groupNum != options.groupDefault
        
        if (options.groupDefault*1 >= 0 && groupNum != options.groupDefault){
            if (idDivGood == obAns.parentNode.id && groupNum != options.groupDefault){
                points += ans.points*1;
            }else{
                points -= ans.points*1;
            }            
        
        
        }else{
            if (idDivGood == obAns.parentNode.id && groupNum != options.groupDefault){
                points += ans.points*1;
            }else{
                //points -= ans.points*1;
            }            
        }
        
                    
    }
    return points;
} 


/* **************************************************

***************************************************** */
getAllPropositions (flag = 0){
    var currentQuestion = this.question;
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    


var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;


var ImgStyle=`style="height:${divHeight}px;"`;

    
//------------------------------------------------------
    var nbGroups = this.data.groupsLib.length;
    var groups = [];
    var ans;
    var index;
    for(var k = 0; k < nbGroups; k++){
        groups[k] = [];
    }
    
   //repartir les propositions par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var g = ans.group;
 
        switch (posCaption){
            case 'T': captionTop    = ans.caption + qbr ; break;
            case 'B': captionBottom = qbr + ans.caption ; break;
            default: break;
        }

        
        groups[g].push(`
            <li id='${ans.ansId}' class='quiz_slist'>${ans.proposition}</li>
            ${captionBottom}`);
    }
        
var tHtml = [];   
    for(var k = 0; k < nbGroups; k++){
        tHtml.push('<div style="clear:both;color:red;"><hr>' + this.data.groupsLib[k] + '</div>');
        tHtml.push(groups[k].join(''));
    }

    //---------------------------------------------------------------------
    return tHtml.join("\n");

}

/* ************************************
*
* **** */
 reloadQuestion(reloadMode = reloadShuffle) {
    var currentQuestion = this.question;
    var options = this.question.options;
    
    var obGroups= [];
    var obGroup;
    var index = 0;
    
    for(k = 0; k < this.data.groups.length; k++){
        //var groupId = this.getId('group', k);
        //obGroups.push(document.getElementById(groupName));
        obGroups.push(document.getElementById(this.data.groups[k].id));
    }

    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        if(reloadMode){
            index = getRandom(options.nbGroups-1);
        }else{
            index = ans.group;
        }
        var obGroup = obGroups[index];
        obGroup.appendChild(document.getElementById(ans.ansId)); 


    }

     return true;
  }
  
  
/* ***************************************
*
* *** */

 showGoodAnswers()
  {
     this.reloadQuestion(false);
  } 
/* ***************************************
*
* *** */
 showBadAnswers()
  {
     this.reloadQuestion(true);
  } 

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId){
    var currentQuestion = this.question;

var DadEvent=`
onDragOver="return ulDaDGroups_dad_over(event);" 
onDrop="return ulDaDGroups_dad_drop(event,${quiz_config.dad_move_img});"
onDragLeave="ulDaDGroups_dad_leave(event);"`;

  var tdStyle = 'width:100%;';
  var tpl = '';
  var groupes = [];

for (var h = 0; h < 4; h++){
   var bg = currentQuestion.options[`bgGroup${h}`];
   var groupId = this.getId('group', h); 

    groupes.push(`<span style="background:${bg};">{libGroup${h}}</span><br>`
    + `<div id='${groupId}' class='myimg0' attSelGroup style="background:${bg}"`
    + ` ${DadEvent} groupNum="${h}">{contentGroup${h}}</div>`);
}


    switch(disposition)     {
    case 'disposition-02':
        tdStyle = 'width:50%;';
        tpl = `<table  class='${tableId}'>
                <tbody>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[1]}
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-03':
        tdStyle = 'width:33%;';
        tpl = `<table  class='${tableId}'>
                <tbody>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-12':
        tdStyle = 'width:50%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td colspan="2" rowspan="1" '${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'
                        ${groupes[1]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-13':
        tdStyle = 'width:33%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td colspan="3" rowspan="1" style='${tdStyle}'
                        ${groupes[0]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[3]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-20':
        tdStyle = 'width:100%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-21':
        tdStyle = 'width:50%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td colspan="1" rowspan="2" style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-22':
        tdStyle = 'width:50%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[3]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-30':
        tdStyle = 'width:100%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-31':
        tdStyle = 'width:50%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td colspan="1" rowspan="3" style='${tdStyle}'>
                        ${groupes[0]}                   
                    </td>
                    <td style='${tdStyle}'>
                        ${groupes[1]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[2]}                   
                    </td>
                  </tr>
                  <tr>
                    <td style='${tdStyle}'>
                        ${groupes[3]}                   
                    </td>
                  </tr>
                </tbody>
              </table>`;
        break;
        default:
            tpl = `tpl "${disposition}" non trouvé`;    
        break;
    }
    return tpl;

}


}  // FIN DE LA VARIANT

function ul_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
         e.dataTransfer.setData("text", e.target.getAttribute("id"));
}

/* ************************************************************* */

/* ------------------------ EVENTS ----------------------------- */
function ulDaDGroups_dad_drop(e, mode=0){

    idFrom = e.dataTransfer.getData("text");
    //alert('ulDaDGroups_drop' + '===>' + idFrom);

/*
    e.currentTarget.classList.remove('myimg2');
    e.currentTarget.classList.add('myimg1');
*/    
    
    e.currentTarget.parentNode.classList.remove('quiz_dad2');
    e.currentTarget.parentNode.classList.add('quiz_dad1');
    
    var obSource = document.getElementById(idFrom);
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    obDest.appendChild(obSource);

    computeAllScoreEvent();
    //-----------------------------------------------
    
    e.stopPropagation();
    return false;
}
function ulDaDGroups_dad_over(e){
    if(e.currentTarget.getAttribute("id") ==  e.dataTransfer.getData("text")) return false;

/*
    e.currentTarget.classList.remove('myimg1');
    e.currentTarget.classList.add('myimg2');
*/
    
    e.currentTarget.parentNode.classList.remove('quiz_dad1');
    e.currentTarget.parentNode.classList.add('quiz_dad2');
    
    return false;
}

function ulDaDGroups_dad_leave(e){

/*
   e.currentTarget.classList.remove('myimg2');
   e.currentTarget.classList.add('myimg1');
*/   
   
   e.currentTarget.parentNode.classList.remove('quiz_dad2');
   e.currentTarget.parentNode.classList.add('quiz_dad1');
}


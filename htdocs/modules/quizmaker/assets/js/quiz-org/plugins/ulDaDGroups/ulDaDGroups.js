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
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
//var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
//var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

var ImgStyle=`style="height:${divHeight}px;"`;

    
//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0
    var nbGroups = this.data.groupsLib.length;

var tpl = this.getDisposition(currentQuestion.options.disposition, 'ulDaDGroups');
    //----------------------------------------------------------------------------------------

    var groups = [];
    var ans;
    var groupIndex;
    for(var k = 0; k < nbGroups; k++){
        groups[k] = [];
    }
    
   //repartir les propositions par group
    for(var k in currentQuestion.answers){
        //index = getRandom(nbGroups-1);
        groupIndex = (currentQuestion.options.groupDefault*1 < 0)  ? getRandom(nbGroups-1) : currentQuestion.options.groupDefault;
        groups[groupIndex].push(currentQuestion.answers[k]);

    }
   

    for(var k = 0; k < nbGroups; k++){
        var tHtml = [];
        var groupName = this.getId('group', k);
        
        for(var j = 0; j < groups[k].length; j++){
            ans = groups[k][j];

            switch (posCaption){
                case 'T': captionTop =    ans.caption + qbr ; break;
                case 'B': captionBottom = qbr + ans.caption ; break;
                default: break;
            }
            var backGround = (ans.background) ? `background:${ans.background};` : '';
            //if (!ans.proposition.trim() == '$$$') ans.proposition = '&nbsp;';
            tHtml.push(`
            <li id='${ans.ansId}' class='quiz_slist' style='width:${currentQuestion.options.ulWidth}%;${backGround}'>${ans.proposition}</li>
            ${captionBottom}`
            );

        }
        tpl=tpl.replace(`{contentGroup${k}}`, tHtml.join("\n"));        
    }

    //---------------------------------------------------------------------
    for(var k = 0; k < this.data.groupsLib.length; k++){
        //tpl=tpl.replace(`{group-${k}}`, this.data.groupsLib[k]);
        tpl=tpl.replace(`{libGroup${k}}`, this.data.groupsLib[k]);        
    }
    return '<center>' + tpl + '</center>';
}
//---------------------------------------------------
initSlide (){
    //alert ("===> initSlide : " + this.question.pluginName  + " - " + this.question.question + " \n->" + this.getName());
    var nbGroups = 4;
    for(var k = 0; k < nbGroups; k++){ 
        var groupName = this.getId('group', k);
        var obGroup = document.getElementById(groupName);
        if(obGroup){
            this.init_slist(obGroup);
        }
    }
    //this.reloadQuestion();    
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
        console.log('init_slist.start ===>' + e.target.getAttribute("id"));
      }
    };
   
}


/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;
    var groups = [];
    groups[0] = [];
   
   //repartir les proposition par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
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
    
    
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var ans;
var obAns;
var idDivGood;
/*
*/
    var currentQuestion = this.question;
this.blob('showGoodAnswers -----------------------------------------');
      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        obAns = document.getElementById(ans.ansId);
        idDivGood =  this.getId('group', ans.group);
        //this.blob(`divGood = ${idDivGood} - divFound = ${obAns.parentNode.id}`);
        if (idDivGood == obAns.parentNode.id){
            points += ans.points*1;
        }else{
            //points -= ans.points*1;
        }            
                    
    }
    return points;
} 


/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
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
 reloadQuestion() {
    var currentQuestion = this.question;

/*    
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML();
    return true;
 */

    var obGroups= [];
    var obGroup;
    var nbGroups = this.data.groupsLib.length;
    var groupIndex = -1; //groupe de destination aleatoire
    
    for(k = 0; k < this.data.groupsLib.length; k++){
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }
    
    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        groupIndex = (currentQuestion.options.groupDefault*1 < 0)  ? getRandom(nbGroups-1) : currentQuestion.options.groupDefault;
//alert(currentQuestion.options.groupDefault + "-" + groupIndex);
        //alert ('groupIndex : ' + groupIndex);
        obGroup = obGroups[groupIndex];
        //alert(ans.ansId);
        obGroup.appendChild(document.getElementById(ans.ansId )); //+ "-div"

    }

     return true;
}
  
/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    var currentQuestion = this.question;
    var obGroups= [];
    var obGroup;
    
    for(k = 0; k < this.data.groupsLib.length; k++){
        var groupName = this.getId('group', k);
        obGroups.push(document.getElementById(groupName));
    }

    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        var obGroup = obGroups[ans.group];
        obGroup.appendChild(document.getElementById(ans.ansId)); 


    }

     return true;
  } 
/* ***************************************
*
* *** */
 showBadAnswers()
  {
    var currentQuestion = this.question;
    var obGroups= [];
    var obGroup;
    var nbGroups = this.data.groupsLib.length;
    var index; //groupe de destination aleatoire
    
    for(k = 0; k < this.data.groupsLib.length; k++){
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }
    
    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        index = getRandom(nbGroups-1);
        //alert ('index : ' + index);
        obGroup = obGroups[index];
        //alert(ans.ansId);
        //obGroup.appendChild(document.getElementById(ans.ansId + "-div")); 
        if(obGroup) {
            obGroup.appendChild(document.getElementById(ans.ansId)); 
        }

    }

     return true;
  } 

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId){
    var currentQuestion = this.question;
// var DadEvent =`
// onDragStart="dad_start(event);"
// onDragOver="return dad_over(event);" 
// onDrop="return imagesDaDGroups_drop(event,${quiz_config.dad_move_img});"
// onDragLeave="dad_leave(event);"`;

var DadEvent=`
onDragOver="return ulDaDGroups_dad_over(event);" 
onDrop="return ulDaDGroups_dad_drop(event,${quiz_config.dad_move_img});"
onDragLeave="ulDaDGroups_dad_leave(event);"`;

  var tdStyle = 'width:100%;';
  var tpl = '';
  var groupes = [];

for (var h = 0; h < 4; h++){
   var bg = currentQuestion.options[`bgGroup${h}`];
   var id = this.getId('group', h); 

groupes.push(`
<span style="background:${bg};">{libGroup${h}}</span><br>   
<div id='${id}' class='myimg0' attSelGroup style="background:${bg}" ${DadEvent}>{contentGroup${h}}</div>`);
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


}  // FIN DE LA CLASSE

function ul_start(e, isDiv=false){
    e.dataTransfer.effectAllowed = "move";
         e.dataTransfer.setData("text", e.target.getAttribute("id"));
}

/* ************************************************************* */

/* ------------------------ EVENTS ----------------------------- */
function ulDaDGroups_dad_drop(e, mode=0){
//alert('dad_drop')
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
//alert('dad_over')
//blob(`dad_over : ${e.dataTransfer.getData("text")} / ${e.currentTarget.getAttribute("id")}`);
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


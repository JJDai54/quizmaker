
 /*******************************************************************
  *                     _imagesDaDGroups
  * *****************************************************************/
class imagesDaDGroups extends quizPrototype{
name = 'imagesDaDGroups';

//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    var name = this.getName();
this.boolDog = true;
    
    
    const answers = [];
    answers.push(`<div id="${name}" style='width:100%;'>`);
    answers.push(this.getInnerHTML());
    answers.push(`</div>`);
    
    
//    this.focusId = name + "-" + "0";
    //alert (this.focusId);
    return answers.join("\n");

 }


/* ************************************
*
* **** */
getInnerHTML(){
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
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
//var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

var ImgStyle=`style="height:${divHeight}px;"`;

var eventImgToEvent=`
onDragStart="dad_start(event,true);"
onDragOver="return dad_over(event);" 
onDrop="return dad_drop(event,${quiz_config.dad_flip_div});"
onDragLeave="dad_leave(event);"
`;
    
//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0
    var nbGroups = this.data.groupsLib.length;
//alert("dads - getInnerHTML - disposition : " + currentQuestion.options.disposition)
var tpl = this.getDisposition(currentQuestion.options.disposition, 'imagesDaDGroups');
//var tpl = this.getDisposition('disposition-20', 'imagesDaDGroups');
    //----------------------------------------------------------------------------------------

    var groups = [];
    var ans;
    var index;
    for(var k = 0; k < nbGroups; k++){
        groups[k] = [];
    }
    
   //repartir les propositions par group
    for(var k in currentQuestion.answers){
        index = rnd(nbGroups-1);

        if(nbGroups > 2){
          //recherche un group différent que celui attribué pour mélanger les items 
          // pas sur que ce soit une bonne idée surtout si il n'y a que deux groupes, il suffit d'inveerser toutes les images
          while (currentQuestion.answers[k].group == index){
            index = rnd(nbGroups-1);
          }      
        }
        //if(!groups[index]) groups[index] = [];
        groups[index].push(currentQuestion.answers[k]);
        //alert("rnd : " + k + " | " + index);
    }
   
    for(var k = 0; k < nbGroups; k++){
        var tHtmlImgs = [];
        for(var j = 0; j < groups[k].length; j++){
            ans = groups[k][j];
            src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
            switch (posCaption){
                case 'T': captionTop = ans.caption.replace(' ','<br>') + '<br>' ; break;
                case 'B': captionBottom = '<br>' + ans.caption.replace(' ','<br>'); break;
                default: break;
            }

            tHtmlImgs.push(`
            <div id="${ans.id}-div" ${divStyle} draggable='true' >${captionTop}
            <img id="${ans.id}-img" src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`
            
            );
        }
        //tpl=tpl.replace(`{imgGgroup-${k}}`, tHtmlImgs.join("\n"));
        tpl=tpl.replace(`{contentGroup${k}}`, tHtmlImgs.join("\n"));
    }

    //---------------------------------------------------------------------
    for(var k = 0; k < this.data.groupsLib.length; k++){
        //tpl=tpl.replace(`{group-${k}}`, this.data.groupsLib[k]);
        tpl=tpl.replace(`{libGroup${k}}`, this.data.groupsLib[k]);
    }
    return tpl;
}
initSlide(){
    this.reloadQuestion();
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
        ans.id = this.getId('img', k);
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
    
    
    this.data.urlCommonImg = quiz_config.urlCommonImg;
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
        groupIndex = (currentQuestion.options.groupDefault < 0)  ? rnd(nbGroups-1) : currentQuestion.options.groupDefault;
        //alert ('groupIndex : ' + groupIndex);
        obGroup = obGroups[groupIndex];
        //alert(ans.id + "-img");
        var obChild = document.getElementById(ans.id + "-div"); 
        if(obGroup && obChild){
        obGroup.appendChild(obChild); 
        }

    }

     return true;
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var ans;
var obImg;
var idDivGood;
/*
*/
    var currentQuestion = this.question;
this.blob('showGoodAnswers -----------------------------------------');
      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        obImg = document.getElementById(ans.id + "-div");
        idDivGood =  this.getId('group', ans.group);
        this.blob(`divGood = ${idDivGood} - divFound = ${obImg.parentNode.id}`);
        if (idDivGood == obImg.parentNode.id){
            points += ans.points*1;
        }else{
            //points -= ans.points*1;
        }            
                    
    }
    //return ((currentQuestion.points > 0) ? currentQuestion.points : points);
    return points;
} 

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var score = {min:0, max:0};

      for(var k in currentQuestion.answers){
          var points = currentQuestion.answers[k].points;  
          if (points == 0) {points = 1;}        // force les points a une valeur supérieure à zéro
          if (points > 0){
            this.scoreMaxiBP += parseInt(points)*1;
          } 
          if (points < 0){
            this.scoreMiniBP += parseInt(points)*1;
          } 
      }

     return true;
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
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`; 
        switch (posCaption){
            case 'T': captionTop = ans.caption.replace(' ','<br>') + '<br>' ; break;
            case 'B': captionBottom = '<br>' + ans.caption.replace(' ','<br>'); break;
            default: break;
        }

        
        groups[g].push(`
            <div id="${ans.id}-div" ${divStyle} >${captionTop}
            <img id="${ans.id}-img" src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
    }
        
var tHtml = [];   
    for(var k = 0; k < nbGroups; k++){
        tHtml.push('<div style="clear:both;"><hr>' + this.data.groupsLib[k] + '</div><br>');
        tHtml.push(groups[k].join(' '));
    }

    //---------------------------------------------------------------------
    return tHtml.join("\n");

}

//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
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
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }

    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        obGroup = obGroups[ans.group];
        //alert(ans.id);
        obGroup.appendChild(document.getElementById(ans.id + "-div")); 

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
        index = rnd(nbGroups-1);
        //alert ('index : ' + index);
        obGroup = obGroups[index];
        //alert(ans.id);
        obGroup.appendChild(document.getElementById(ans.id + "-div")); 

    }

     return true;
  } 
  /* *********************************************
  
  ************************************************ */

  /* *********************************************
  
  ************************************************ */
getDisposition(disposition, tableId){
    var currentQuestion = this.question;
var DadEvent =`
onDragStart="dad_start(event);"
onDragOver="return dad_over(event);" 
onDrop="return imagesDaDGroups_drop(event,${quiz_config.dad_move_img});"
onDragLeave="dad_leave(event);"`;
//alert("tpl + " + disposition);
  var tdStyle = 'width:100%;';
  var tpl = '';
  var groupes = [];

for (var h = 0; h < 4; h++){
   var bg = currentQuestion.options[`bgGroup${h}`];
   var id = this.getId('group', h); 

groupes.push(`
<span style="background:${bg};">{libGroup${h}}</span><br>   
<div id='${id}' class='myimg0 myimg1' attSelGroup style="background:${bg}" ${DadEvent}>{contentGroup${h}}</div>`);
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
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
              </table>`
        break;
        default:
            tpl = `tpl "${disposition}" non trouvé`;    
        break;
    }
    return tpl;
}




}  // FIN DE LA CLASSE


/* ************************************************************* */
function imagesDaDGroups_drop(e, mode=0){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");
    // e.currentTarget.className="myimg1";
    e.currentTarget.classList.remove('myimg2');
    e.currentTarget.classList.add('myimg1');
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    obDest.appendChild(obSource);

    computeAllScoreEvent();
    //-----------------------------------------------
    
    e.stopPropagation();
    return false;
}


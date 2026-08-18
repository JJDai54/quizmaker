/*******************************************************************
*                     imagesDaDGroups
* *****************************************************************/
function getPlugin_imagesDaDGroups(question, slideNumber){
    return new imagesDaDGroups(question, slideNumber);
}
 /*******************************************************************
  *                     imagesDaDGroups
  * *****************************************************************/
class imagesDaDGroups extends Plugin_Prototype{
name = 'imagesDaDGroups';


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
    var src = '';
    var captionTop='';
    var captionBottom = '';    

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
//var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
//var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

var imgStyle=`style="height:${divHeight}px;opacity:0%;transform: rotate(0.5turn);pointer-events:none;`;//transform: scalex(150%) scaley(150%);"

//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0
    //var nbGroups = this.data.groupsLib.length;
//alert("dads - getInnerHTML - disposition : " + currentQuestion.options.disposition)
var tpl = this.getDisposition(currentQuestion.options.disposition, 'imagesDaDGroups');
//var tpl = this.getDisposition('disposition-20', 'imagesDaDGroups');
    //----------------------------------------------------------------------------------------

    var groups = [];
    var ans;
    var index;
    //alert(`getInnerHTML : this.data.groups.length = ${this.data.groups.length}`);
    
   
    for(var k = 0; k < this.data.groups.length; k++){
        var tHtmlImgs = [];
        for(var j = 0; j < this.data.groups[k].ansArr.length; j++){
            ans = this.data.groups[k].ansArr[j];
            src = `${quiz_config.urlQuizImg}/${ans.image1}`;
            var caption = "<b><span style='color:green;'>"+ ans.caption + "</span></b>"; 
//            alert(src);
            switch (posCaption){
                case 'T': captionTop    = caption; break;
                case 'B': captionBottom = caption; break;
                default: break;
            }

            tHtmlImgs.push(`
            <div id="${ans.ansId}-div"  portrait>${captionTop}
            <div id="${ans.ansId}-img" draggable='true' class='imagesDaDGroups_divimg' style="background-image:url('${src}');background-size:auto ${divHeight}px;">
            <img src="${src}"  title="${ans.caption}" ${imgStyle} alt="" >
            </div>
            ${captionBottom}</div>`
            
            
            );
        }
        //tpl=tpl.replace(`{imgGgroup-${k}}`, tHtmlImgs.join("\n"));
        tpl = tpl.replace(`{contentGroup${k}}`, tHtmlImgs.join("\n"));
        tpl=tpl.replace(`{libGroup${k}}`, this.data.groups[k].caption);
    }

    return tpl;
}


/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    var currentQuestion = this.question;
    var options = currentQuestion.options;
    
    options.groupDefault = options.groupDefault*1;
        
    this.data.groups = clsGroup.repartir(this, true);

}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
    var currentQuestion = this.question;
    var points = 0;
    var ans;

      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        var obGroup = document.getElementById(ans.ansId + "-img").parentNode.parentNode; 
        var groupNum = obGroup.getAttribute("groupNum")*1;
        
        if (currentQuestion.options.groupDefault < 0){
          if(groupNum == ans.group){
                points += ans.points*1;
          }else{
                //points -= ans.points*1;
          }

        }else if(groupNum != currentQuestion.options.groupDefault) {        
          if(groupNum == ans.group){
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
        src = `${quiz_config.urlQuizImg}/${ans.image1}`; 
        switch (posCaption){
            case 'T': captionTop    = ans.caption + qbr ; break;
            case 'B': captionBottom = qbr + ans.caption ; break;
            default: break;
        }

        
        groups[g].push(`
            <div id="${ans.ansId}-div" ${divStyle} >${captionTop}
            <img id="${ans.ansId}-img" src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
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

/* ***************************************
*
* *** */

reloadQuestion(reloadMode = reloadShuffle)
  {
    var currentQuestion = this.question;
    
    //let  groups = clsGroup.repartir(this, false);
    var obGroups= [];
    var obGroup;
    var index = 0;
    
    for(k = 0; k < this.data.groups.length; k++){
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }

    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        if(reloadMode == reloadShuffle || reloadMode == reloadOrg ){
          index = getRandom(this.data.nbGroups-1);
          obGroup = obGroups[index];
        }else{
          obGroup = obGroups[ans.group];
        }
        //alert(ans.ansId);
        obGroup.appendChild(document.getElementById(ans.ansId + "-div")); 

    }

     return true;
  } 

  /* *********************************************
https://developer.mozilla.org/fr/docs/Web/API/HTML_Drag_and_Drop_API  
  ************************************************ */
getDisposition(disposition, tableId){
    var currentQuestion = this.question;
var DadEvent =`
onDragStart  = "return imagesDaDGroups_start(event, true);"
onDragEnter  = "return imagesDaDGroups_over(event);" 
onDragOver   = "return imagesDaDGroups_over(event);" 
onDrop       = "return imagesDaDGroups_drop(event);"
onDragLeave  = "return imagesDaDGroups_leave(event);"
onDragEnd    = "return imagesDaDGroups_end(event);"`;
 
//var dataSource = "<input type="hidden" name="action" value="results">"
//console.log ('===> disposition : ' + disposition);
  var tdStyle = 'width:100%;';
  var tpl = '';
  var groupes = [];

//préparation des libellé des groupes repris dans les templates
for (var h = 0; h < 4; h++){
    var bg = currentQuestion.options[`bgGroup${h}`];
    var id = this.getId('group', h); 

    groupes.push(`
    <span id='${id}-span' style="background:${bg};" attSelGroup="0" >{libGroup${h}}</span><br>   
    <div id='${id}' name='${id}'  groupNum='${h}' attSelGroup="1" style="background:${bg}" ${DadEvent}>{contentGroup${h}}</div>`);
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
                    <td colspan="2" rowspan="1" style='${tdStyle}'>
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
                  </tr>
                </tbody>
              </table>`;
        break;
    case 'disposition-13':
        tdStyle = 'width:33%;';
        tpl = `<table  class='${tableId}'>    
                <tbody>
                  <tr>
                    <td colspan="3" rowspan="1" style='${tdStyle}'>
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






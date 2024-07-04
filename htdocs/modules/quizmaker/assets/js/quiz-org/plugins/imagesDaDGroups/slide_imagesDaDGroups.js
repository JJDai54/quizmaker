
 /*******************************************************************
  *                     _imagesDaDGroups
  * *****************************************************************/
class imagesDaDGroups extends quizPrototype{
name = 'imagesDaDGroups';

//---------------------------------------------------
build (){
    this.boolDog = false;
    return this.getInnerHTML();
 }


/* ************************************
*
* **** */
getInnerHTML(){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    //var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
//var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
//var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

var ImgStyle=`style="height:${divHeight}px;"`;

// var eventImgToEvent=`
// onDragStart="dad_start(event,true);"
// onDragOver="return dad_over(event);" 
// onDrop="return dad_drop(event,${quiz_config.dad_flip_div});"
// onDragLeave="dad_leave(event);"
// `;
    
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
        index = (currentQuestion.options.groupDefault >= 0) ? currentQuestion.options.groupDefault : getRandom(nbGroups-1);

        if(nbGroups > 2){
          //recherche un group différent que celui attribué pour mélanger les items 
          // pas sur que ce soit une bonne idée surtout si il n'y a que deux groupes, il suffit d'inveerser toutes les images
          while (currentQuestion.answers[k].group == index){
            index = getRandom(nbGroups-1);
          }      
        }
        //if(!groups[index]) groups[index] = [];
        groups[index].push(currentQuestion.answers[k]);
        //alert("getRandom : " + k + " | " + index);
    }
   
    for(var k = 0; k < nbGroups; k++){
        var tHtmlImgs = [];
        for(var j = 0; j < groups[k].length; j++){
            ans = groups[k][j];
            src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
            switch (posCaption){
                case 'T': captionTop    = ans.caption + qbr ; break;
                case 'B': captionBottom = qbr + ans.caption ; break;
                default: break;
            }

            tHtmlImgs.push(`
            <div id="${ans.ansId}-div"  portrait draggable='true' >${captionTop}
            <img id="${ans.ansId}-img" src="${src}"  draggable='true' title="${ans.caption}" ${ImgStyle} alt="" >
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

/* *********************************************************
*
* ********************************************************** */
 prepareData(){
 var pointsMaxi = 0;
    
    var currentQuestion = this.question;
    var groups = [];
    groups[0] = [];
 
 //       index = (currentQuestion.options.groupDefault >= 0) ? currentQuestion.options.groupDefault : getRandom(nbGroups-1);
   
   //repartir les proposition par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        
        
        
        //ans.ansId = this.getId('img', k);
        ans.caption = ans.caption.replace(' ',qbr).replace('/',qbr);
        var groupIdx = ans.group*1;
        
        if(ans.points <= 0) {ans.points = 1;}
        if(currentQuestion.options.groupDefault < 0){
            pointsMaxi += ans.points*1;
        }else if(groupIdx != currentQuestion.options.groupDefault){
        //alert(`${groupIdx}-${currentQuestion.options.groupDefault}`);
            pointsMaxi += ans.points*1;
        }
        if(!groups[groupIdx]) {groups[groupIdx] = []};
        groups[groupIdx].push(ans);
    }   

    this.scoreMaxiBP = pointsMaxi;
    //si c'est le score de la question qui prime
    if(currentQuestion.points > 0){
          this.scoreMaxiQQ = currentQuestion.points;
    }else{
          this.scoreMaxiQQ = this.scoreMaxiBP;
    }     
    
    this.data.groups = groups;

    
    this.data.groupsLib=[];
    for(var k = 0; k <= 3; k++){
        var key = 'group' + k;
        if(currentQuestion.options[key]) {
            this.data.groupsLib.push(currentQuestion.options[key]);
        }
    }
    
    
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var ans;
var obImg;
var idDivGood;
    var currentQuestion = this.question;
this.blob('getScoreByProposition -----------------------------------------');
      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
console.log("getScoreByProposition.obImg" + ans.ansId + "-img");         
        obImg = document.getElementById(ans.ansId + "-img"); //
        idDivGood =  this.getId('group', ans.group);

        var obGroup = obImg.parentNode.parentNode;
        var numGroup = obGroup.getAttribute("numGroup")*1;    
         //alert("groupe de l'image : " + numGroup);
                
        if (currentQuestion.options.groupDefault < 0){
            //i n'y a pas de groupe par defaut, les proposition sont répartie sur tous les groupes
         console.log("pas de groupe par defaut : ans.group=" + ans.group + " - groupDefault=" + currentQuestion.options.groupDefault + " -> ans.points=" + ans.points + ' - total points=' + points);
         console.log(`===>idDivGood = ${idDivGood}\n===>obImg.parentNode.id = ${obImg.parentNode.parentNode.id}`);
            if (idDivGood == obImg.parentNode.parentNode.id){
                points += ans.points*1;
            }else{
                //points -= ans.points*1;
            }
        }else if(numGroup != currentQuestion.options.groupDefault) {
            //il y a un groupe par defaut tous les proposition sont mises dedans au depart
         //alert("il y a un groupe par defaut : " + ans.group + " - " + currentQuestion.options.groupDefault + " -> " + ans.points);
            if (idDivGood == obImg.parentNode.parentNode.id){
              points += ans.points*1;
            }else{
                //points -= ans.points*1;
            }
        }            
    }
    //return ((currentQuestion.points > 0) ? currentQuestion.points : points);
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
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`; 
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
        //alert(ans.ansId);
        obGroup.appendChild(document.getElementById(ans.ansId + "-div")); 

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
console.log ('===> disposition : ' + disposition);
  var tdStyle = 'width:100%;';
  var tpl = '';
  var groupes = [];

//préparation des libellé des groupes repris dans les templates
for (var h = 0; h < 4; h++){
    var bg = currentQuestion.options[`bgGroup${h}`];
    var id = this.getId('group', h); 

    groupes.push(`
    <span id='${id}-span' style="background:${bg};" attSelGroup="0" >{libGroup${h}}</span><br>   
    <div id='${id}' name='${id}'  numGroup='${h}' attSelGroup="1" style="background:${bg}" ${DadEvent}>{contentGroup${h}}</div>`);
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
}  // FIN DE LA CLASSE

/* ******************************************************************** */
/*       Fonction de Drag And drop sur des images                       */
/* https://www.javascripttutorial.net/web-apis/javascript-drag-and-drop */
/* ******************************************************************** */

function imagesDaDGroups_start(e, isDiv=false){
console.log("===> dad => " + "imagesDaDGroups_start" + " - " + e.target.getAttribute("id"));

    e.dataTransfer.setData("idImg", e.target.parentNode.getAttribute("id"));
    
    //seul firefox peut acceder aux valeurx de dataTransfer dans le over
    //mais il y en a besoin pour idenfier le group survolé
    //alors stockage dans une balise "input type=hidden" globale
    set_param(e.target.parentNode.getAttribute("id"));
    
    blob("dad_start : " + e.target.getAttribute("id") + " | " + e.target.getAttribute("src") );
    imagesDaDGroups_set_style(e.target.parentNode, 1);    
    e.dataTransfer.dropEffect = "move"; 
    return true;
   
}


/* *********************************** */
function imagesDaDGroups_over(e){
console.log("===> dad => " + "imagesDaDGroups_over");
    
    //seul firefox peut acceder aux valeurx de dataTransfer dans le over
    //mais il y en a besoin pour idenfier le group survolé
    //lors du start la valeur a ete stockée dans une balise "input type=hidden" globale
    idDivImg = get_param(0);
   // var idDivImg = e.dataTransfer.getData("idImg");
    var obDivImg = document.getElementById(idDivImg);


//alert("===> dad => " + "imagesDaDGroups_over");
    var obOver = imagesDaDGroups_get_group(e.target);
    //var isGroup = (obOver.getAttribute("attSelGroup")=="1") ? true : false;
//     var zzz = (isGroup) ? "Oui" : "Non";
// console.log("===> dad => " + "imagesDaDGroups_over" + " - " + zzz + "-" + obOver.getAttribute("attSelGroup"));
//alert(idDivImg);   
 
if(obDivImg){
    var idParent = obDivImg.parentNode.getAttribute("id");

    
    //var idOver = obOver.getAttribute("id"); 
    
    if(obOver.id != idParent && obOver.isGroup){
      imagesDaDGroups_set_style(obOver.target, 1);      
      //alert(obOver.parentNode.firstChild.tagName);      
      //imagesDaDGroups_set_style(document.getElementById(idOver + "-span"), 1);      
    }
}else{
    if(obOver.isGroup){
      imagesDaDGroups_set_style(obOver.target, 1);      
      //alert(obOver.parentNode.firstChild.tagName);      
      //imagesDaDGroups_set_style(document.getElementById(idOver + "-span"), 1);      
    }
}
/*
*/
    e.dataTransfer.dropEffect = "copyMove"; 
e.preventDefault();    
    return false;
}

/* *********************************** */
function imagesDaDGroups_get_group(obOver, isGroup){
    var id = obOver.getAttribute("id");
    var obClone = document.getElementById(id);
    
    var isGroup = (obOver.getAttribute("attSelGroup")=="1") ? true : false;
     while (!isGroup){
         var obClone = obClone.parentNode;
         var isGroup = (obClone.getAttribute("attSelGroup")=="1") ? true : false;
         if (isGroup) {break;}
     }
    
    //return obClone;        
    return {'target':obClone, 'isGroup': isGroup, 'id':obClone.getAttribute("id")};
}

/* ************************************************************* */
function imagesDaDGroups_drop(e){
console.log("===> dad => " + "imagesDaDGroups_drop" + " | " + e.target.getAttribute("src"));
//alert("===> dad => " + "imagesDaDGroups_drop" + " | " + e.target.getAttribute("src"));
    obOver = imagesDaDGroups_get_group(e.target);
    
    idFrom = e.dataTransfer.getData("idImg");
    var obDivImg = document.getElementById(idFrom);
        
    imagesDaDGroups_set_style(obDivImg , 0);
    imagesDaDGroups_set_style(obOver.target, 0);

    //deplace le div img dans le nouveau groupe    
    obOver.target.appendChild( obDivImg);
    //-----------------------------------------------
    
    computeAllScoreEvent();
    e.stopPropagation();
    return false;

}
/* *********************************** */
function imagesDaDGroups_leave(e){
console.log("===> dad => " + "imagesDaDGroups_leave");
    var isGroup = (e.target.getAttribute("attSelGroup")=="1") ? true : false;
    if(isGroup){
      imagesDaDGroups_set_style(e.target, 0);
    }

    return true;

}
/* *********************************** */
function imagesDaDGroups_end(e){
console.log("===> dad => " + "imagesDaDGroups_end");
    var idDivImg = e.dataTransfer.getData("idImg");
    var obDivImg = document.getElementById(idDivImg);
    imagesDaDGroups_set_style(obDivImg, 0);
    
    return true;
    

}

/* *********************************** */
function imagesDaDGroups_set_style(ob, numStyle, mod = 2){
console.log("===> dad => " + "imagesDaDGroups_set_style");
    var oldStyle = ((numStyle*1)+1) % mod;

    ob.classList.remove('imagesDaDGroups_div' + oldStyle);
    ob.classList.add('imagesDaDGroups_div' + numStyle);
    //ob.classList.style.border="5px";
    
    var isGroup = (ob.getAttribute("attSelGroup")=="1") ? true : false;
    if(isGroup){
        var idGroup = ob.getAttribute("id");      
console.log (idGroup + " ===> " + 'imagesDaDGroups_div' + numStyle);
       imagesDaDGroups_set_style(document.getElementById(idGroup + "-span"), numStyle);      
    }
    
    

}





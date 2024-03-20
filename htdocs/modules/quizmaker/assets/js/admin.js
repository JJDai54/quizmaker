
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Quizmaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */


function reloadImgModeles(divId, imgHeight=80){
    //alert('reloadImgModeles : ' + divId);
    var obDivImg = document.getElementById(divId);
    var obInpTypeQuestion = document.getElementById('quest_type_question');
    var tOptions = obInpTypeQuestion.options;
    //alert(tOptions + '===>' + tOptions.length);
    
    var typeQuestion = obInpTypeQuestion.options[obInpTypeQuestion.selectedIndex].value;
    var btnAddQuestion = document.getElementById('btnAddQuestion');

    if(typeQuestion[0] == '>'){
        btnAddQuestion.style.display = 'none';
        obDivImg.innerHTML = `<div style="height:${imgHeight}px"></div>`;    
    }else{
        btnAddQuestion.style.display = 'block';
        var tImg = [];
        for(var i=0; i<3; i++){
            var url = `../plugins/${typeQuestion}/snapshoot/snapshoot-00${i}.jpg`;
            tImg.push(`<a href='${url}' class='highslide' onclick='return hs.expand(this);' >
                      <img src="${url}" alt="" style="max-height:${imgHeight}px" />
                   </a>`)
        }
        obDivImg.innerHTML = tImg.join("\n");
    }
    
    

    //alert('reloadImgModeles : ' + divId + " / " + typeQuestion); //obInpTypeQuestion.value
    
function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}}

function setValue2Input(id, exp) {
//alert (id);
    document.getElementById(id).value=exp;
  return true;
}


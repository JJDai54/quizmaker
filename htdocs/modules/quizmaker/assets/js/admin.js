
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

/*
$(document).ready(function(){
//alert("show_hide");
   $('.show_hide').showHide({
		speed: 500,  // speed you want the toggle to happen
		easing: '',  // the animation effect you want. Remove this line if you dont want an effect and if you haven't included jQuery UI
		changeText: 1, // if you dont want the button text to change, set this to 0

		showText: '<{$smarty.const._CC_MED_AFFICHER}> <img src="<{$smarty.const.JANUS_ICO32}>plus.png" width="16px" height="16px" alt="" />',// the button text to show when a div is closed
		hideText: '<{$smarty.const._CC_MED_CACHER}> <img src="<{$smarty.const.JANUS_ICO32}>moins.png"  width="16px" height="16px" alt="" />' // the button text to show when a div is open

// 		showText: '<{$smarty.const._MD_MED_AFFICHER}> <img src="<{$smarty.const._MED_URL}>/images/icons/20/view.gif" alt="" />',// the button text to show when a div is closed
// 		hideText: '<{$smarty.const._MD_MED_CACHER}> <img src="<{$smarty.const._MED_URL}>/images/icons/20/remove.gif" alt="" />' // the button text to show when a div is open
	});

}); 
//alert("ici");
*/
 

function reloadImgModeles(divId, imgHeight=80){
    //alert('reloadImgModeles : ' + divId);
    var obDivImg = document.getElementById(divId);
    var obInpTypeQuestion = document.getElementById('quest_plugin');
    var tOptions = obInpTypeQuestion.options;
    //alert(tOptions + '===>' + tOptions.length);
    
    var pluginName = obInpTypeQuestion.options[obInpTypeQuestion.selectedIndex].value;
    var btnAddQuestion = document.getElementById('btnAddQuestion');

    if(pluginName[0] == '>'){
        btnAddQuestion.style.display = 'none';
        obDivImg.innerHTML = `<div style="height:${imgHeight}px"></div>`;    
    }else{
        if (btnAddQuestion) btnAddQuestion.style.display = 'block';
        var tImg = [];
        for(var i=0; i<3; i++){
            var url = `../plugins/${pluginName}/snapshoot/snapshoot-00${i}.jpg`;
            tImg.push(`<a href='${url}' class='highslide' onclick='return hs.expand(this);' >
                      <img src="${url}" alt="" style="max-height:${imgHeight}px" />
                   </a>`)
        }
        obDivImg.innerHTML = tImg.join("\n");
    }
    
    

    //alert('reloadImgModeles : ' + divId + " / " + pluginName); //obInpTypeQuestion.value
    
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
/*********************************************
 *   
 *********************************************/ 
function quizmaker_scrollWin(offsetV = -100){
var intervalID = setTimeout(quizmaker_scrollWin2, 80, offsetV);
}
/*********************************************
 *   
 *********************************************/ 
function quizmaker_scrollWin2(offsetV){
//alert('scrollWin');
document.scrollTop = -100;
window.scroll(0, window.scrollY + offsetV);
}


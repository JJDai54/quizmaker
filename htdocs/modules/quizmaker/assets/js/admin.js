
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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xoopsfr.kiolo.fr>
 */
    var hsConfig = null;

function reloadPluginSnapshoots(divId, imgHeight=80){
    //alert('reloadPluginSnapshoots : ' + divId);
    var obDivImg = document.getElementById(divId);
    obDivImg.height = imgHeight+ 'px';
    var obInpPlugin = document.getElementById('quest_plugin');
    var tOptions = obInpPlugin.options;
    //alert(tOptions + '===>' + tOptions.length);
    
    var pluginName = obInpPlugin.options[obInpPlugin.selectedIndex].value;
    var btnAddQuestion = document.getElementById('btnAddQuestion');
//alert(pluginName);
    if(!pluginName[0] == '-'){
        btnAddQuestion.style.display = 'none';
        obDivImg.innerHTML = `<div style="height:${imgHeight}px"></div>`;    
    }else{
    console.log('===>reloadPluginSnapshoots : pluginName = ' + pluginName);
        if (btnAddQuestion) btnAddQuestion.style.display = 'block';
    hsConfig = {
        slideshowGroup: `group_${pluginName}`,
        thumbnailId: `thumb_${pluginName}`,
        transitions: ['expand', 'crossfade'],

        align : 'center',
        outlineType : 'rounded-white',
        fadeInOut : true,
        numberPosition : 'caption',
        dimmingOpacity : 0.75,
        // Add the controlbar
        interval: 5000,
        repeat: true,
        useControls: true,
        fixedControls: 'fit',
        overlayOptions: {
          opacity: 0.6,
          position: 'bottom center',
          hideOnMouseOut: true
        },
          	thumbstrip: {
          mode: 'horizontal',
          position: 'below',
          relativeTo: 'image'
        }

    };
    if (hs.addSlideshow) hs.addSlideshow(hsConfig);

 //console.log('hsConfig : ' + hsConfig.slideshowGroup) ;      
        var tImg = [];
        
        for(var h = 0; h < 3; h++){
            var url = `../plugins/${pluginName}/snapshoot/snapshoot-00${h}.jpg`;
                        if (!imageExists(url)) break;

                if (h == 0){
                    tImg.push (`<div class='highslide-gallery'>
                    <a id='thumb_${pluginName}' href='${url}' class='highslide' onclick='return hs.expand(this, hsConfig );'>
                        <img src="${url}" alt="slides" style="max-height:${imgHeight}px;" />
                    </a></div>
                    <div class='hidden-container'>`);
                }else{
                    tImg.push (`
                    <a  href='${url}' class='highslide' onclick='return hs.expand(this, hsConfig );'>
                        <img src="${url}" alt="slides" style="max-height:${imgHeight}px;" />
                    </a>`);
                }
        }
        tImg.push (`</div>\n`);
        console.log(tImg.join("\n"));
        //obDivImg.innerHTML = "=====" + pluginName + "=====<br>" + tImg.join("\n");
        obDivImg.innerHTML = tImg.join("\n");
    }
    
    
} 

function hs_AddConfig(pluginName){
    console.log('===>hs_AddConfig : pluginName = ' + pluginName);

    var hsConfig = {
        slideshowGroup: `group_${pluginName}`,
        thumbnailId: `thumb_${pluginName}`,
        transitions: ['expand', 'crossfade'],

        align : 'center',
        outlineType : 'rounded-white',
        fadeInOut : true,
        numberPosition : 'caption',
        dimmingOpacity : 0.75,
        // Add the controlbar
        interval: 5000,
        repeat: true,
        useControls: true,
        fixedControls: 'fit',
        overlayOptions: {
          opacity: 0.6,
          position: 'bottom center',
          hideOnMouseOut: true
        },
          	thumbstrip: {
          mode: 'horizontal',
          position: 'below',
          relativeTo: 'image'
        }

    };
    if (hs.addSlideshow) hs.addSlideshow(hsConfig);
}










        






/**
 * Quizmaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xoopsfr.kiolo.fr>
 */

function reloadPluginSnapshoots_3(divId, imgHeight=80){
    //alert('reloadPluginSnapshoots : ' + divId);
    var obDivImg = document.getElementById(divId);
    var obInpPlugin = document.getElementById('quest_plugin');
    var tOptions = obInpPlugin.options;
    //alert(tOptions + '===>' + tOptions.length);
    
    var pluginName = obInpPlugin.options[obInpPlugin.selectedIndex].value;
    var btnAddQuestion = document.getElementById('btnAddQuestion');

    if(!pluginName[0] == '-'){
        btnAddQuestion.style.display = 'none';
        obDivImg.innerHTML = `<div style="height:${imgHeight}px"></div>`;    
    }else{
    console.log('===>reloadPluginSnapshoots : pluginName = ' + pluginName);
        if (btnAddQuestion) btnAddQuestion.style.display = 'block';
        var tImg = [];
        tImg.push (`\n
<script  type="text/javascript">
        
var config_${pluginName} = {
    slideshowGroup: 'group_${pluginName}',
    thumbnailId: 'thumb_${pluginName}',
    transitions: ['expand', 'crossfade'],

    align : 'center',
    outlineType : 'rounded-white',
    fadeInOut : true,
    numberPosition : 'caption',
    dimmingOpacity : 0.75,
    // Add the controlbar
    interval: 5000,
    repeat: true,
    useControls: true,
    fixedControls: 'fit',
    overlayOptions: {
      opacity: 0.6,
      position: 'bottom center',
      hideOnMouseOut: true
    },
      	thumbstrip: {
      mode: 'horizontal',
      position: 'below',
      relativeTo: 'image'
    }

};
        
if (hs.addSlideshow) hs.addSlideshow(config_${pluginName});
</script>`);        
        
        tImg.push (`<div class='highslide-gallery'>`);
        for(var h = 0; h < 3; h++){
            var url = `../plugins/${pluginName}/snapshoot/snapshoot-00${h}.jpg`;
            if (!imageExists(url)) continue;
            var ancId = (h == 0) ? `id='thumb_${pluginName}'` : '' ;
            tImg.push (`
            <a ${ancId} href='${url}' class='highslide' onclick='return hs.expand(this, config_${pluginName} );'>
                <img src="${url}" alt="slides" style="max-height:${imgHeight}px;" />
            </a>`);
        }
        tImg.push (`</div>\n`);
        console.log(tImg.join("\n"));
        obDivImg.innerHTML = "=====" + pluginName + "=====<br>" + tImg.join("\n");
    }
    
    
}    
    

    //alert('reloadPluginSnapshoots : ' + divId + " / " + pluginName); //obInpPlugin.value
    
function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}

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


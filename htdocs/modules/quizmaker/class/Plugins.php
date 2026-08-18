<?php
namespace XoopsModules\Quizmaker;

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

use XoopsModules\Quizmaker AS FQUIZMAKER;
//echo "<hr>class : Plugin<hr>";
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Plugin   //extends \XoopsObject
 */
class Plugins 
{
var $version = '1.0';
var $release = '2025-05-12';
var $author  = 'JJDai - (jjdelaalandre@orange.fr)';
/*
Mode d'intégration dans le formulaire question
0 : integration dans le gormulaire question avec $form->addElement($clPlugin->getForm(...));
1 : $form->insertBreak($clPlugin->getForm(...)->render()); peermet de supprimer la colonne caption
*/
var $integration = 0;

var $questId = 0;
var $type = '';
var $pluginName = '';//idem type
var $name = '';

var $hasImageMain = false;
var $hasShuffleAnswers = false;
var $hasZoom = false;
var $hasGlobalPoints = true;


var $categoryWeight = 0;
var $description = '';
var $example = '';
var $image_fullName = '';
var $lgTitle = 80;
var $lgProposition = 80;
var $lgProposition2 = 80;
var $lgPoints = 5;
var $lgWeight = 5;
var $lgMot0 = 5;
var $lgMot1 = 20;
var $lgMot2 = 50;
var $lgMot3 = 80;
var $lgMot4 = 120;
var $lgMot5 = 250;

var $trayGlobal; 
var $maxPropositions = 12; // valeur par default
var $isQuestion = 0; // valeur par default
var $canDelete = 1; // valeur par default
var $category = ''; // valeur par default
var $categoryLib = ''; // valeur par default
var $typeForm = ''; // Type de slide : page intro, page groupe ou question
var $typeForm_lib = ''; // libelle du type de slide
var $isParent = false;  
var $consigne  = ''; // Consigne qui apparait dans l'icone du point d'iterogation de chaque slide
var $multiPoints = false;
var $numbering = -1;
var $optionsDefaults = array('test'=>'JJD');
var $optionsMsg = null;

//si true, ne sera plus dans les listes de sélection pour une création, 
//mais permet de garder la compatibilité avec des slides créés avec ce type de question.
var $obsolette = false; 
var $pathArr = false; 
var $prefix = '_LG_PLUGIN_';

//variables utilisé pour l'ajout de messages transmis au javascript
var $messagesArr  = null;

const bgColor1 = '#CCFFFF';
const bgColor2 = '#FFCC99';
const bgColor3 = '#FFFFCC';

const noClass = "00-none";     
        

	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct($pluginName, $questId = 0, $cat="")
	{
        $this->__init($pluginName, $questId, $cat);
	}
	public function __init($pluginName, $questId = 0, $cat="")
	{
        $this->type = $pluginName;
        $this->pluginName = $pluginName;
        $this->questId = $questId;
        $this->category = $cat;
        
        switch($pluginName){
        case 'pageBegin' : $this->typeForm = QUIZMAKER_TYPE_FORM_BEGIN;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_QUIZMAKER_FORM_INTRO;    break;
        case 'pageEnd'   : $this->typeForm = QUIZMAKER_TYPE_FORM_END;        $this->isParent = false; $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_QUIZMAKER_FORM_RESULT;   break;
        case 'pageGroup' : $this->typeForm = QUIZMAKER_TYPE_FORM_GROUP;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_GROUP;    break;
        case 'pageInfo'  : $this->typeForm = QUIZMAKER_TYPE_FORM_INFO;       $this->isParent = false; $this->isQuestion = 0; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_INFO;     break;
        case 'pageAnswer': $this->typeForm = QUIZMAKER_TYPE_FORM_ANSWER;     $this->isParent = false; $this->isQuestion = 0; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_ANSWER;   break;
        default          : $this->typeForm = QUIZMAKER_TYPE_FORM_QUESTION;   $this->isParent = false; $this->isQuestion = 1; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_QUESTION; break;
        }
//exit("this->isQuestion = {$this->isQuestion}");
//exit("->isQuestion = {$this->isQuestion} - ->typeForm = {$this->typeForm} - ->typeForm_lib = {$this->typeForm_lib}");


        $this->pathArr = $this->getPluginPath();
//echo "<hr><pre>" . print_r($this->pathArr,true) . "</pre><hr>";
        $this->name        = constant($this->prefix . strToUpper($pluginName));
        $this->description = constant($this->prefix . strToUpper($pluginName) . '_DESC');
        $this->consigne    = constant($this->prefix . strToUpper($pluginName) . '_CONSIGNE');
        $this->categoryLib = constant(QUIZMAKER_PREFIX_CAT . strToUpper($cat));
        $this->categoryWeight = constant('QUIZMAKER_PLUGIN_CAT_' . strToUpper($cat));
	}
	/**
	 * @static function &toString
	 *
	 * @param zrrzy of properties
	 */
	public function toString()
	{
    //echo "<hr>pluginName : " . $this->pluginName . '<hr>';
        $arr = array();
        $arr['pluginName'] = $this->pluginName;
        $arr['typeForm'] = $this->typeForm;
        $arr['isParent'] = ($this->isParent) ? 'true' : 'false';
        $arr['isQuestion'] = ($this->isQuestion) ? 'true' : 'false';
        $arr['canDelete'] = ($this->canDelete) ? 'true' : 'false';
        $arr['typeForm_lib'] = $this->typeForm_lib;
        $arr['pathArr'] = $this->pathArr;
        $arr['name'] = $this->name;
        $arr['description'] = $this->description;
        $arr['consigne'] = $this->consigne;
        $arr['category'] = $this->category;
        $arr['categoryLib'] = $this->categoryLib;
        $arr['categoryWeight'] = $this->categoryWeight;
        /*
        $arr[''] = $this->;
        $arr[''] = $this->;
        $arr[''] = ($this->) ? 'true' : 'false';
        $arr[''] = ($this->) ? 'true' : 'false';
        $arr[''] = ($this->) ? 'true' : 'false';
        */
        return $arr;
        
    }

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
	}
	/**
	 * @public function getVersion
	 * @param void
	 * @return string full version
	 */
	public function getVersion()
	{
        return 'Version ' . $this->version . ' - ' . $this->release . ' - ' . $this->author;
    }

	/**
	 * @public function setVersion
	 * @param $version string
	 * @param $release string
	 * @param $author string
	 * @return string full version
	 */
	public function setVersion($version, $release, $author)
	{
        $this->version = $version;
        $this->release = $release;
        $this->author  = $author;
    }
	/**
	 * @public function loadJS
	 * @return boolean
	 */
     
	/**
	 * @public function getVariantLibArr
	 * @return array : libelle des variant du plugin
	 */
     
	public function getVariantLibArr()
	{
        return Array();
    }

	public function loadJS()
	{

    }
	/**
	* function addMessages($messagegArr)
    * ajoute la liste des message spcifique au plugin dans le tableau des options
    * il seront afficher dans forOptions avec l'include :
    * $messagesArr = $this->messagegArr;    
    * @$messagegArr  : tableau cles des de messages a ajouter.(ils doivent tous avoir le même prefixe) 
    *  include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_messages.php");
        addMessages()
    * */
	public function addMessages($messagesArr = null)
	{
 $this->messagesArr = $messagesArr; 
  
        //$this->optionsMsg = array();
        $this->optionsDefaults['msg_nextslide_duree'] = 0;
        $this->optionsDefaults['msg_nextslide_gotonext'] = 0;
        $this->optionsDefaults['msg_nextslide_background'] = '#FFCCFF';
        
        $this->optionsDefaults['msg_nextslide_bgWinner'] = '#66FF66';
        $this->optionsDefaults['msg_nextslide_bgLooser'] = '#FF9966';
  
        $this->optionsDefaults['msg_nextslide_winner'] = (defined('_AP_QUIZMAKER_MSG_NEXTSLIDE_WINNER_0') ? _AP_QUIZMAKER_MSG_NEXTSLIDE_WINNER_0 : '');
        $this->optionsDefaults['msg_nextslide_looser'] = (defined('_AP_QUIZMAKER_MSG_NEXTSLIDE_LOOSER_0') ? _AP_QUIZMAKER_MSG_NEXTSLIDE_LOOSER_0 : '');
        //$this->messagesArr = array();
        
        //--- messages spécifiques au plugin
        //if($this->messagesArr)    echoArray($this->messagesArr, $this->pluginName);
        if($messagesArr){
            $plugin = $this->pluginName;
            
            $this->optionsDefaults['msg_duree'] = 3;
            $this->optionsDefaults['msg_background'] = '#99FF00';
        
            foreach($messagesArr as $key) {
                //$keyName = strtolower($key);
                $keyName = $key;
                $msgDefaut = "_LG_PLUGIN_{$plugin}_MSG_{$keyName}_0";
                $this->optionsDefaults["msg_{$keyName}"] = constant(strtoupper($msgDefaut));
            }
            //echoArray($this->messagesArr);
//echoArray($messagesArr,'addMessages',true);        
//echoArray($this->optionsDefaults,'addMessages',true);        
        }
	}


	/**
	 * @public function initFormForQuestion
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function initFormForQuestion()
	{
        $this->trayGlobal = new \XoopsFormElementTray  (_AM_QUIZMAKER_PROPOSITIONS, $delimeter = '<hr>');
        
        //l'insertion de l'aide a été déplacée dans le form de la question pour une meilleurs ergonomie
        //$this->trayGlobal->addElement($this->getSlideHelper());
	}

	/**
	 * @public function getSlideHelper
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getSlideHelper($isOpen = false)
	{

        //function __construct($caption, $name , $value, $isOpen = false)
        $trayHeader = new \XoopsFormShowHide(_AM_QUIZMAKER_PLUGIN_HELP, 'quizmaker_help', $this->getInpHelp()); 
        $trayHeader->setLibelle('caption', _AM_QUIZMAKER_PLUGIN_HELP_LIBELLE);
        $trayHeader->setLibelle('show', _AM_QUIZMAKER_PLUGIN_HELP_SHOW);
        $trayHeader->setLibelle('hide', _AM_QUIZMAKER_PLUGIN_HELP_HIDE);
        $trayHeader->setLibelle('close', _AM_QUIZMAKER_PLUGIN_HELP_CLOSE);
        $trayHeader->setIsOpen($isOpen);
        $trayHeader->setBackcolor('rgb(241,227,209)');
        return $trayHeader;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
    
	}
	public function getSlideHelper_old()
	{

        $trayHeader = new \XoopsFormElementTray  (_AM_QUIZMAKER_PLUGIN_CONSIGNE, $delimeter = '<br>'); 
        /* pas vraiment utile tout ça, fait double emploi
        $trayHeader->addElement(new \XoopsFormLabel('', $this->name));
        $trayHeader->addElement(new \XoopsFormLabel('', $this->description));
        $trayHeader->addElement(new \XoopsFormLabel('', '<hr>'));
        */
        $trayHeader->addElement($this->getInpHelp());

        return $trayHeader;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
    
	}


	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * ÿ et 郵翴 array
	 */
	public function getValuesPlugin()
	{
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
        
        $ret = array();
        $ret['version']     = $this->version;
        $ret['release']     = $this->release;
        $ret['author']      = $this->author;
        $ret['fullVersion'] = $this->getVersion();
      
        $ret['type']        = $this->type;
        $ret['pluginName']  = $this->pluginName;
        $ret['name']        = $this->name;
        $ret['category']    = $this->category;
        $ret['categoryWeight'] = $this->categoryWeight;
        $ret['obsolette']   = $this->obsolette;
        $ret['categoryLib'] = $this->categoryLib;
        $ret['short_type']  = $this->type; //substr($this->type, strlen($this->category)) ;
        $ret['description'] = $this->description;
        $ret['consigne']    = $this->getConsigne();
        $ret['isQuestion']  = $this->isQuestion;
        $ret['canDelete']   = $this->canDelete;
        $ret['modeles']     = $this->pathArr['snapshoot_url'];
        $ret['nbModeles']   = count($this->pathArr['snapshoot_url']);

         //echoArray($ret['modeles']);
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        $ret['modelesHtml'] =  $this->getHtmlImgModeles($ret['type']);
        $ret['isArchive']   =  $this->archiveExempleExist();
        $ret['isBuild']     =  $this->isBuild();
        return $ret;
	}
    
	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */

function getHtmlImgModeles($pluginName='', $height = 80){
    if(!$pluginName) $pluginName = $this->type;
    
        $tImg = array();
        $graphicDir = XOOPS_URL . '/Frameworks/highslide/graphics/';
        
        $tImg[] =  <<<___IMG___
<div id='modelesPluginId'>        
<script  type="text/javascript">

var config_{$pluginName} = {
    //graphicsDir: '{$graphicDir}',
    slideshowGroup: 'group_{$pluginName}',
    thumbnailId: 'thumb_{$pluginName}',
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


if (hs.addSlideshow) hs.addSlideshow(config_{$pluginName});

/*
console.log('===>' + config_{$pluginName}  + ' : ' + config_{$pluginName} .slideshowGroup);
console.log('===>graphicsDir : ' + hs.graphicsDir);

console.log((config_{$pluginName}.useControls) ? 'oui' : 'non');
*/
</script>
___IMG___;
        

        //echo "<hr>";
        $h = 0;
        foreach ($this->pathArr['snapshoot_url'] as $key=>$url)
        {
        //echo "{$url}<br>";
            if($h == 0){
                $tImg[] = "<div class='highslide-gallery'>";
                $tImg[] =  <<<___IMG___
                <a id='thumb_{$this->type}' href='{$url}' class='highslide' onclick='return hs.expand(this, config_{$this->type} );'>
                    <img src="{$url}" alt="slides" style="max-height:{$height}px;" />
                </a>
                ___IMG___;
                $tImg[] = "<div class='hidden-container'>";
            }else{
                $tImg[] =  <<<___IMG___
                <a href='{$url}' class='highslide' onclick='return hs.expand(this, config_{$this->type} );'>
                    <img src="{$url}" alt="slides" style="max-height:{$height}px;" />
                </a>
                ___IMG___;
            }
            
            
            $h++;
        }        
                $tImg[] = "</div>";
        
        

        $tImg[] = "</div></div>";
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        return implode("\n", $tImg);
}
    
function getHtmlImgModeles2($ret, $width = 80){
        
        $tImg = array();
        $tImg[] = "<div id='{$this->type}'  name='{$this->type}' class='highslide-gallery'>";

        //echo "<hr>";
        foreach ($this->pathArr['snapshoot_url'] as $key=>$url)
        {
        //echo "{$url}<br>";
            $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this, { slideshowGroup: {$this->type} });' name="{$ret['name']}">
                <img src="{$url}" alt="slides" style="max-width:{$width}px"  name='{$ret['name']}'/>
            </a>
            ___IMG___;
            $tImg[] = $img;

        }        
        $tImg[] = "</div>";
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        return implode("\n", $tImg);
}
    
/* **********************************************************
*
* *********************************************************** */
public function echoAns ($answers, $questId, $bExit = true) {
global $xoopDB;

    if(is_object($answers)) $answers = $xoopsDB->fetchArray($answers);
    
    //echo "<hr>Question questId = {$questId}<pre>" . print_r($answers, true) . "</pre><hr>";
    if ($bExit) exit;         
}



/* **********************************************************
*
* *********************************************************** */
	public function getformTextarea($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizmakerHelper;
        return \JANUS\getformTextarea($caption, $name, $value, $description, $rows, $cols);
}       
        
/* **********************************************************
*
* *********************************************************** */
	public function getformAdmin($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizmakerHelper;
        return \JANUS\getAdminEditor($quizmakerHelper, $caption, $name, $value);
}       
        


/* **********************************************************
*
* *********************************************************** */
 	public function getName()
 	{

    $numargs = func_num_args();
    $arg_list = func_get_args();
    
    switch($numargs){
        case 1: 
            return sprintf("answers[%s]",  $arg_list[0]);
            break;
        case 2: 
            return sprintf("answers[%s][%s]",  $arg_list[0], $arg_list[1]);
            break;
        case 3: 
            return sprintf("answers[%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2]);
            break;
        case 4: 
            return sprintf("answers[%s][%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3]);
            break;
        default: 
            return "answers";
            break;
    }
    
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $name, $value = "")
 	{
        return null;
    }

   
/* **********************************************************
*$jsonValues
* *********************************************************** */
 	public function getOptionsByQuestId($questId, $optionsDefaults = null)
    {
    global $questionsHandler, $myts;    
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $tValues = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        
       if(is_null($optionsDefaults)) $optionsDefaults = $this->optionsDefaults;
        
        foreach($optionsDefaults as $key=>$default){
            if(isset($tValues[$key])) {
                $optionsDefaults[$key] = $myts->htmlSpecialChars($tValues[$key]);
            }else{
                $optionsDefaults[$key] = $myts->htmlSpecialChars($default);
            }
        }
        return $optionsDefaults;
    }
/* **********************************************************
 * getOptions : obsolette. A remplacer par getOptionsByQuestId($questId, $optionsDefaults = null)
* *********************************************************** */
 	public function getOptions($jsonValues, $optionsDefaults = null)
 	{
    global $myts;
     //echo "<hr>{$jsonValues}<hr>";
       if(is_null($optionsDefaults)) $optionsDefaults = $this->optionsDefaults;
//echoArray($optionsDefaults,"optionsDefaults");
     
       if($jsonValues){
            $tValues = json_decode($jsonValues, true);
            
            foreach($optionsDefaults as $key=>$default){
                if(isset($tValues[$key])) {
                    $optionsDefaults[$key] = $myts->htmlSpecialChars($tValues[$key]);
                }else{
                    $optionsDefaults[$key] = $myts->htmlSpecialChars($default);
                }
            }
       }
//echoArray($tValues,"tValues",);
//echoArray($optionsDefaults,"optionsDefaults",true);
       return $optionsDefaults;
    }
    
/* **********************************************************
*
* *********************************************************** */
//  	public function isQuestion()
//  	{
//         return ($this->isQuestion);
//     }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getViewPlugin($addSnapShoot = 1)
 	{
        return $this->getInpHelp($addSnapShoot)->render();
	}
/* **********************************************************
*
* *********************************************************** */
 	public function getFormPlugin($pluginName)
 	{
	}
/* ********************************************
*
*********************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{global $utility, $answersHandler, $pluginsHandler;
   
    }
/* ********************************************
*  help for app javascript type_slide
*********************************************** */
 	public function getConsigne()
 	{
        //conteneur pour l'aide et les images
        if(file_exists($this->pathArr['consigne_path'])){
          $consigne = quizmaker_utf8_encode(\JANUS\FSO\loadtextFile($this->pathArr['consigne_path']));
          
          //echo "<hr>{$this->pathArr['consigne_path']}<{$consigne}><hr>";
        }else{
          $consigne = constant($this->prefix . strToUpper($this->pluginName) . '_CONSIGNE');
        }           

    return $consigne;
    }
    
/* ********************************************
*
*********************************************** */
 	public function getInpHelp($addSnapShoot = 1)
 	{global $xoopsConfig, $utility;
        //conteneur pour l'aide et les images
        $trayHelp = new \XoopsFormElementTray  ('', $delimeter = '');          
        //-------------------------------------------
           
        $help = \JANUS\FSO\loadtextFile($this->pathArr['help_path']);
        $help = quizmaker_utf8_encode($help);
        $inpHelp = new \XoopsFormLabel  ('', $help);
        //ajout du texte dans le conteneur
        $trayHelp->addElement($inpHelp);
        
        //----------------------------------------------   
  
        $trayHelp->addElement(new \XoopsFormLabel  ('', "<hr>"));
        //--------------------------------
        //$inpSnapShoot = new \XoopsFormLabel  ('', 'fgdfhghk');
    if($addSnapShoot == 1){
        $width  = 40; 

        foreach ($this->pathArr['snapshoot_url'] as $key=>$url)
        {
                $img =  <<<___IMG___
                <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                    <img src="{$url}" alt="slides" style="max-width:{$width}px" />
                </a>
                ___IMG___;
                $inpImg = new \XoopsFormLabel  ('', $img);  
                $inpImg->setExtra("class='highslide-gallery'");
                $trayHelp->addElement($inpImg);


        }        
    }else if($addSnapShoot == 2){
        $width  = 210; 

        foreach ($this->pathArr['snapshoot_url'] as $key=>$url)
        {
                $img =  "<img src='{$url}' alt='slides' style='max-width:{$width}px' />";
                
                $inpImg = new \XoopsFormLabel  ('', $img);  
                $trayHelp->addElement($inpImg);
        }
    }    
        //----------------------------------------------        
        return $trayHelp;
    }
    
/* ********************************************
*
*********************************************** */
  public function color($exp, $color = null){
    if($color)
        return "<span style='color:{$color};'></span>";
    else
        return $exp;

}     
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){

    $ret = array();
 
    $ret['answers'] =  _CO_QUIZMAKER_POINTS_UNDER_DEV;
    $ret['scoreMin'] = -99999;
    $ret['scoreMax'] = 99999;
    return $ret;

}     
/* ********************************************
*
*********************************************** */
  public function combineAndSorAnswer($ans, $sep=','){
    return $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
}     
/* ********************************************
*
*********************************************** */
  public function mergeAndSortArrays($exp2sort, $expLib, $sep=','){
    $arr2sort = explode($sep, $exp2sort);
    $arrExp   =  explode($sep, $expLib);
    $ret = array();
    foreach ($arr2sort as $i=>$v){
        $key = 'p=' . str_pad($v, 3, '0', STR_PAD_LEFT);
        $ret[$key]['points'] = $v;
        $ret[$key]['exp'] = $arrExp[$i];
//         $ret[$key] = $arrExp[$i];
    }
    
//    echoArray($ret);
    krsort($ret);
//    echoArray($ret);
    
    return $ret;
}     


/* *************************************************

*************************************************** */
function save_img(&$answer, $formName, $path, $prefix, &$nameOrg=''){
global $pluginsHandler;
    return $pluginsHandler->save_img($answer, $formName, $path, $prefix, $nameOrg);
}
/* *************************************************

*************************************************** */
function nameOrgParse($nameOrg, &$prefix){

    //on retire l'extension ou le prefix on met le nom de l'image
    $nameOrg = substr($ansObj->getVar('answer_proposition'), strlen($prefix)+1);
    $h= strrpos($nameOrg,'.');
    return str_replace('_', ' ', substr($nameOrg, $i, $h));
}

/* *************************************************

*************************************************** */
function delete_answer_by_image(&$answer, $path){
global $answersHandler;
//$this->echoAns ($answer,"delete_answer_by_image<br>{$path}", false);    
    if($answer['proposition'] !=''){
        $f = $path . '/' . $answer['proposition'];
        //Suppression de l'image
        //echo "{$f}<br>";
        if (file_exists($f)) {
          unlink ($f);
        }
    }
    $this->delete_image($answer['image1'] ,$path);
    $this->delete_image($answer['image2'] ,$path);
    $answersHandler->deleteId($answer['id']);
}
/* *************************************************

*************************************************** */
function delete_image($imgName, $path){
global $answersHandler;
    echo "<hr>delete_image - {$path} | {$imgName}<br>";
//    exit;
    if(!$imgName)  return false;
    $f = $path . '/' . $imgName;
    if (file_exists($f)) unlink ($f);
}

/* *************************************************
*
* ************************************************** */
 	public function getFormSelectImageDiv($caption, $imgName, $formName, $url, $listImg, $addNone, $maxWidth=80)
 	{ 
        global $quizmakerHelper;
        
//         $fulName = $path . "/" . $imgName;        
//         if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
//           $urlImg = XOOPS_URL . "";
//         }else{
//           $urlImg = QUIZMAKER_URL_UPLOAD . $path . "/" . $imgName;
//         }
        $urlImg = $url . "/" . $imgName;
//        echo "<hr>img : {$urlImg}<hr>";
        $inpImg= new \XoopsFormSelect('', $formName, $imgName);   
        if ($addNone) $inpImg->addOption('', _AM_QUIZMAKER_NONE);
        $inpImg->addOptionArray($listImg);
        
$select = $inpImg->render();
        $libImg = new \XoopsFormLabel('', "<div style='display:inline;'><img src='{$urlImg}'  name='{$formName}-img' id='{$formName}-img' alt='' style='max-width:{$maxWidth}px' title={$imgName}><br>{$select}</div>");
        
        
        return $libImg; 
             
     
     }
     
/* *************************************************
*
* ************************************************** */
 	public function getFormSelectImage($caption, $imgName, $formName, $url, $listImg, $addNone, $maxWidth=80)
 	{ 
        global $quizmakerHelper;
        
//         $fulName = $path . "/" . $imgName;        
//         if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
//           $urlImg = XOOPS_URL . "";
//         }else{
//           $urlImg = QUIZMAKER_URL_UPLOAD . $path . "/" . $imgName;
//         }
        $urlImg = $url . "/" . $imgName;
//        echo "<hr>img : {$urlImg}<hr>";
        //$libImg = new \XoopsFormLabel('', "<img src='{$urlImg}'  name='{$formName}-img' id='{$formName}-img' alt='' style='max-width:{$maxWidth}px' title={$imgName}>");
        
        $inpImg= new \XoopsFormSelect('', $formName, $imgName);   
        if ($addNone) $inpImg->addOption('', _AM_QUIZMAKER_NONE);
        $inpImg->addOptionArray($listImg);
        
        $imageTray  = new \XoopsFormElementTray($caption,""); 
        $imageTray->addElement($inpImg);
        $imageTray->addElement($libImg);
        return $imageTray; 
             
     
     }
     
/* *************************************************
* Le nombre d'items est limité, si il y en a plus il y a eu un problème de sauvegarde.
* Il faut supprimer les enregistrements en trop
* utilisé en dev pour remettre de l'ordre
* ************************************************** */
 	public function deleteToMuchItems($arr, $maxItems)
 	{ 
        global $answersHandler;

        
        for ($h = $maxItems; $h < count($arr); $h++)
            $answersHandler->delete($arr[$h], true);
        //$answersHandler->deleteId($arr['id']);
     }

/* *************************************************
*  inititlise une table avec xoopsformTableXtray (J°J°D)
*  pour la liste des propositions
* ************************************************** */
 	public function getNewXoopsTableXtray($caption='', $globalTdStyle = null , $extra = null)
 	{ 
        $tbl = new \XoopsFormTableTray($caption, $globalTdStyle, $extra);
        $tbl->addGlobalTdStyle('padding:0px 5px 0px 5px;line-height:2em;');
        $tbl->setOdd('background:#DFDFDF');
        $tbl->setEven('background:#7FE0F0');
        //$tbl->addTdStyle(0, 'text-align:center;width:150px;');
        return $tbl;
     }
     
/* *************************************************
*  inititlise une table avec xoopsformTableXtray (J°J°D)
*  pour la liste des options de chaque plugin
* ************************************************** */
 	public function getNewXFTableOptions($caption='', $globalTdStyle = null , $extra = null)
 	{ 
        $tbl = new \XoopsFormTableTray($caption, $globalTdStyle, $extra);
        $tbl->addGlobalTdStyle('padding:0px 5px 0px 5px;line-height:2em;');
        $tbl->addTdStyle(0,'background:#99CCFF');  
        $tbl->addTdStyle(0,'width:25%');  
        $tbl->addTdStyle(0, 'text-align:left;');
        $tbl->addTdStyle(1,'background:#E5E5E5');  
        return $tbl;
     }
/* //////////////////////////////////////////////////////////////////// */
    
/* ****************************************************

******************************************************* */
public function getPluginPath()
  {  
global $pluginsHandler;    
    return $pluginsHandler->getPluginPath($this->pluginName, true);  
  }
    
/* ****************************************************

******************************************************* */
public function getAnswerValues(&$ans, &$weight, $points=0){
    $tVal = array();
    $weight += 10;

    if (isset($ans)){
      $tVal['answerId']     = $ans->getVar('answer_id');
      $tVal['id']           = $ans->getVar('answer_id');
      $tVal['quest_id']     = $ans->getVar('answer_quest_id');
      $tVal['proposition']  = trim($ans->getVar('answer_proposition'));
      $tVal['buffer']       = trim($ans->getVar('answer_buffer'));
      $tVal['points']       = $ans->getVar('answer_points');
      $tVal['image1']       = $ans->getVar('answer_image1');
      $tVal['image2']       = $ans->getVar('answer_image2');
      $tVal['caption']      = $ans->getVar('answer_caption');
      $tVal['background']   = $ans->getVar('answer_background');
      $tVal['color']        = $ans->getVar('answer_color');
      $tVal['weight']       = $ans->getVar('answer_weight');
      $tVal['inputs']       = $ans->getVar('answer_inputs');
      $tVal['group']        = $ans->getVar('answer_group');
      $tVal['isNew']        = false;     
      $tVal['tPropos']      = explode(',', $tVal['proposition']);
      //$tVal['delete']      = true;
    }else{
      $tVal['answerId']     = 0;
      $tVal['id']           = 0;
      $tVal['quest_id']     = 0;
      $tVal['proposition']  = '';
      $tVal['buffer']       = '';
      $tVal['points']       = $points;
      $tVal['image1']       = '';
      $tVal['image2']       = '';
      $tVal['caption']      = '';
      $tVal['background']   = '';
      $tVal['color']        = '#FFFFFF';
      $tVal['weight']       = $weight;
      $tVal['inputs']       = 0;
      $tVal['group']        = 0;
      $tVal['isNew']        = true;     
      $tVal['tPropos']      = array();     
      //$tVal['delete']      = false;
     
    }

    for ($h = 0; $h>count($tVal['tPropos']); $h++) $tVal['tPropos'][$h] = trim($tVal['tPropos'][$h]);

    return $tVal;
}     

/* ****************************************************

******************************************************* */
public function playQuizExemple($plugin, $bolOk = false){
global $quizUtility, $quizHandler, $categoriesHandler,$quizmakerHelper;
    //verifier si il est déjà installer
    
    
    $url = "plugins.php?op=list";
    $catId = $categoriesHandler->getId(QUIZMAKER_CAT_NAME_FOR_EXEMPLE, true,true);

    if ($catId == 0){
      redirect_header($url, 5,  _AM_QUIZMAKER_CATEGORIE_NOT_EXIST);
    }
    
    $quizName = "plugin_" . $plugin;
    $quizId = $quizHandler->getId($quizName, $catId);
    if ($quizId == 0){
      redirect_header($url, 5,  _AM_QUIZMAKER_QUIZ_NOT_EXIST);
    }
    
    $quiz = $quizHandler->get($quizId);
    $quizValues = $quiz->getValuesQuiz();
    $url = $quizValues["quiz_html"].'?'.FQUIZMAKER\getParamsForQuiz(1);      
    
      redirect_header($url, 5,  _AM_QUIZMAKER_QUIZ_NOT_BUILD);
}
/* ****************************************************

******************************************************* */
public function installQuizExemple($plugin, $bolDelete = false){
global $quizUtility, $quizHandler, $categoriesHandler,$quizmakerHelper;
$quizUtility = new \XoopsModules\Quizmaker\Utility();
$pluginName = 'plugin_' . $plugin;

    $fullName = QUIZMAKER_PATH_PLUGINS_PHP . "/{$plugin}/{$pluginName}.zip";
    if(!file_exists($fullName)){
        return 2;
    }
    
    $catId = $categoriesHandler->getId(QUIZMAKER_CAT_NAME_FOR_EXEMPLE, true,true);

//echo "<hr>installQuizExemple : catId = {$catId}<br>";exit;
    $oldQuizId = $quizHandler->getId($pluginName, $catId);
//echo "<hr>installQuizExemple : oldQuizId = {$oldQuizId} - catId = {$catId}<br>";
    
    if ($oldQuizId > 0){
        if($bolDelete){
        	$quizHandler->delete($oldQuizId, true);
        }else{
            return 1;
            //redirect_header("plugins.php", 5,  "le quiz existe déjà");
        }
    }

//echo "<hr>{$catId}<hr>" ; exit;

    
    //nom complet de l'archive dans le dossier du plugin
//echo "archive : {$fullName}<br>";
    //dossier provisoir pour decompresser l'archive
    $newFldImport = "/files_new_quiz" ;  
    $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
    chmod($fullName, 0666);
    chmod($pathImport, 0777);
    \JANUS\unZipFile($fullName, $pathImport);
    \JANUS\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_IMPORT, 0777);
    
      $quizUtility::quiz_importFromYml($pathImport, $catId, $newQuizId, $pluginName);
//echo "<br>installQuizExemple : newQuizId = {$newQuizId}<hr>";
//exit;
 //sleep(int $seconds)      
      $quizUtility::buildQuiz($newQuizId);
 /*
      $quiz = $quizHandler->get($newQuizId);
      $quizValues = $quiz->getValuesQuiz();
            
      $url = $quizValues["quiz_html"].'?'.FQUIZMAKER\getParamsForQuiz(1);      
 */     
    return 0;
}
   
/* ****************************************************

******************************************************* */
public function copyArchiveInPluginFolder($archivesPath){

    $fullName = QUIZMAKER_PATH_PLUGINS_PHP . "/{$this->pluginName}/plugin_{$this->pluginName}.zip";
    //echo "<hr>copyArchiveInPluginFolder : <br>{$archivesPath}<br>{$fullName}<hr>";      
    if(file_exists($fullName)) {
        chmod($fullName, 0777);
        $bolOk = unlink($fullName);
        echo (($bolOk) ? 'fichier supprimé' : 'probleme de suppression');

//         while (file_exists($fullName)){
//             sleep(1);
//         }

    }
    copy($archivesPath, $fullName);
    chmod($fullName, 0775);

}
  
/* ****************************************************
*
* ******************************************************* */
public function archiveExempleExist(){
     $fullName = QUIZMAKER_PATH_PLUGINS_PHP . "/{$this->pluginName}/plugin_{$this->pluginName}.zip";
     //echo $fullName. '===>' .((file_exists($fullName)) ? 'ok' : 'non') . "<br>";
     return file_exists($fullName);  
}
    
/* ****************************************************
    //verifier si il est déjà installer
******************************************************* */
public function isBuild(){
global $quizUtility, $quizHandler, $categoriesHandler,$quizmakerHelper;

    $catId = $categoriesHandler->getId(QUIZMAKER_CAT_NAME_FOR_EXEMPLE, true,false);
    if ($catId == 0) return false;
    
    $quizName = "plugin_" . $this->pluginName;
    $quizId = $quizHandler->getId($quizName, $catId);
    if ($quizId == 0) return false;
    
    $quiz = $quizHandler->get($quizId);
    $quizValues = $quiz->getValuesQuiz();
    //echo $quizValues['quiz_html_path']. '===>' .((file_exists($quizValues['quiz_html_path'])) ? 'ok' : 'non') . "<br>";
    return file_exists($quizValues['quiz_html_path']);    
  
}

/* **************************************
    fonctions deplacées dans PluginsHandler 
   pour etre accessible sans le plugin 
   a virer des que possible 
****************************************** */

/* **********************************************************
*
* *********************************************************** */
public function getFormImage($caption, $name, $image, $folderJS)
{
  global $quizmakerHelper, $pluginsHandler;

    return  $pluginsHandler->getFormImage($caption, $name, $image, $folderJS);
  }


/* *************************************************
*
* ************************************************** */
public function getXoopsFormImageDiv($imgName, 
                                      $formName, 
                                      $path, 
                                      $class, 
                                      $delimiter=' ', 
                                      $delChkName='', 
                                      $caption = '', 
                                      $description = '')
    {
    global $quizmakerHelper, $pluginsHandler;
    return $pluginsHandler->getXoopsFormImageDiv($imgName, 
                                      $formName, 
                                      $path, 
                                      $class, 
                                      $delimiter, 
                                      $delChkName, 
                                      $caption, 
                                      $description);
    
    }
/* *************************************************
*
* ************************************************** */
public function getXoopsFormImage($imgName, 
                                      $formName, 
                                      $path, 
                                      $maxWidth=80, 
                                      $delimiter=' ', 
                                      $delChkName='', 
                                      $caption = '', 
                                      $description = '',
                                      $div = false)
 	{ 
     //echo "getXoopsFormImage - image : {$imgName}<br>";
    global $quizmakerHelper, $pluginsHandler;
        
  	return $pluginsHandler->getXoopsFormImage($imgName, 
                                      $formName, 
                                      $path, 
                                      $maxWidth, 
                                      $delimiter, 
                                      $delChkName, 
                                      $caption, 
                                      $description,
                                      $div);
    }


} //**************** fin de la class *********************************

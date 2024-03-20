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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker;
//echo "<hr>class : Type_question<hr>";
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Type_question   //extends \XoopsObject
 */
class Type_question 
{
var $questId = 0;
var $type = '';
var $typeQuestion = '';//idem type
var $name = '';
var $description = '';
var $multiPoints = false;
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
var $lgMot4 = 250;

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

var $renameImage = false; // Permet de garder le nom originale d'une image, a utiliser en dev uniquement
var $optionsDefaults = array('test'=>'JJD');
var $hasImageMain = false;

//si true, ne sera plus dans les listes de sélection pour une création, 
//mais permet de garder la compatibilité avec des slides créés avec ce type de question.
var $obsolette = false; 
var $pathArr = false; 
var $prefix = '_CO_QUIZMAKER_TYPE_';

	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct($typeQuestion, $parentId = 0, $cat="")
	{
        $this->__init($typeQuestion, $parentId, $cat);
	}
	public function __init($typeQuestion, $parentId = 0, $cat="")
	{
        $this->type = $typeQuestion;
        $this->typeQuestion = $typeQuestion;
        $this->questId = $parentId;
        $this->category = $cat;
        
        switch($typeQuestion){
        case 'pageBegin' : $this->typeForm = QUIZMAKER_TYPE_FORM_BEGIN;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_QUIZMAKER_FORM_INTRO;    break;
        case 'pageGroup' : $this->typeForm = QUIZMAKER_TYPE_FORM_GROUP;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_GROUP;    break;
        case 'pageEnd'   : $this->typeForm = QUIZMAKER_TYPE_FORM_END;        $this->isParent = false; $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_QUIZMAKER_FORM_RESULT;   break;
        default          : $this->typeForm = QUIZMAKER_TYPE_FORM_QUESTION;   $this->isParent = false; $this->isQuestion = 1; $this->canDelete = true;  $this->typeForm_lib = _CO_QUIZMAKER_FORM_QUESTION; break;
        }

        $this->pathArr = $this->getPluginPath();
//echoArray($this->pathArr, $typeQuestion);
        $this->name        = constant($this->prefix . strToUpper($typeQuestion));
        $this->description = constant($this->prefix . strToUpper($typeQuestion) . '_DESC');
        $this->consigne    = constant($this->prefix . strToUpper($typeQuestion) . '_CONSIGNE');
        $this->categoryLib = constant($this->prefix . 'CAT_' . strToUpper($cat));
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
	public function getSlideHelper()
	{

        $trayHeader = new \XoopsFormElementTray  (_AM_QUIZMAKER_SLIDE_CONSIGNE, $delimeter = '<br>'); 
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
	 * @return array
	 */
	public function getValuesType_question()
	{
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
        
        $ret = array();
        $ret['type'] = $this->type;
        $ret['name'] = $this->name;
        $ret['category'] = $this->category;
        $ret['obsolette'] = $this->obsolette;
        $ret['categoryLib'] = $this->categoryLib;
        $ret['short_type'] = substr($this->type, strlen($this->category)) ;
        $ret['description'] = $this->description;
        $ret['consigne'] = $this->getConsigne();
        $ret['isQuestion'] = $this->isQuestion;
        $ret['canDelete'] = $this->canDelete;
        $ret['image_fullName'] = QUIZMAKER_MODELES_IMG . "/slide_" . $this->type . '-00.jpg';
//         $ret['modeles'] = array();
//         for ($h = 0; $h < 3; $h++)
//         {
//             $f = QUIZMAKER_MODELES_IMG_PATH . "/slide_" . $this->type . "-0{$h}.jpg";
//             //$ret['modeles'][] = $f;
//             if(file_exists($f))
//                 $ret['modeles'][] = QUIZMAKER_MODELES_IMG . "/slide_" . $this->type . "-0{$h}.jpg";
//         }        
         $ret['modeles'] = $this->pathArr['snapshoot_url'];
         //echoArray($ret['modeles']);
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        $ret['modelesHtml'] =  $this->getHtmlImgModeles();
        return $ret;
	}
    
	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
function getHtmlImgModeles($width = 80){
        
        $tImg = array();
        $tImg[] = "<div id='modelesTypeQuestionId'  name='{$this->type}' class='highslide-gallery'>";

        //echo "<hr>";
        foreach ($this->pathArr['snapshoot_url'] as $key=>$url)
        {
        //echo "{$url}<br>";
            $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:{$width}px" />
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
    
    echo "<hr>Question questId = {$questId}<pre>" . print_r($answers, true) . "</pre><hr>";
    if ($bExit) exit;         
}



/* **********************************************************
*
* *********************************************************** */
	public function getformTextarea($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizmakerHelper;
        return \JJD\getformTextarea($caption, $name, $value, $description, $rows, $cols);
}       
        
/* **********************************************************
*
* *********************************************************** */
	public function getformAdmin($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizmakerHelper;
        return \JJD\getAdminEditor($quizmakerHelper, $caption, $name, $value);
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
*
* *********************************************************** */
 	public function getFormImage($caption, $optionName, $image, $folderJS = null)
 	{
      $name = 'image';  
      $path =  QUIZMAKER_UPLOAD_QUIZ_JS . "/{$folderJS}/images";  
      //$fullName =  QUIZMAKER_UPLOAD_QUIZ_JS . "/{$folderJS}/images/" . $tValues[$name];     
      //$inpImage = $this->getXoopsFormImage($tValues[$name], "{$optionName}[{$name}]", $path, 80,'<br>');  
      $inpImage = $this->getXoopsFormImage($image, $optionName, $path, 80,'<br>', 'del_image');  
      
      return $inpImage;
    }

   
/* **********************************************************
*
* *********************************************************** */
 	public function getOptions($jsonValues, $optionsDefaults=null)
 	{
     //echo "<hr>{$jsonValues}<hr>";
       if(is_null($optionsDefaults)) $optionsDefaults = $this->optionsDefaults;
     
       if($jsonValues){
            $tValues = json_decode($jsonValues, true);
            foreach($optionsDefaults as $key=>$default){
                if(!isset($tValues[$key])) $tValues[$key] = $default;
            }
       }else if($optionsDefaults){
            $tValues = $optionsDefaults;
//        }else{
//             $tValues = $this->optionsDefaults;
       }

       return $tValues;
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function isQuestion()
 	{
        return ($this->isQuestion);
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormType_question($typeQuestion)
 	{
	}
/* ********************************************
*
*********************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{global $utility, $answersHandler, $type_questionHandler;
   
    }
/* ********************************************
*  help for app javascript type_slide
*********************************************** */
 	public function getConsigne()
 	{
        //conteneur pour l'aide et les images
        if(file_exists($this->pathArr['consigne_path'])){
          $consigne = quizmaker_utf8_encode(\JJD\FSO\loadtextFile($this->pathArr['consigne_path']));
          
          //echo "<hr>{$this->pathArr['consigne_path']}<{$consigne}><hr>";
        }else{
          $consigne = constant($this->prefix . strToUpper($this->typeQuestion) . '_CONSIGNE');
        }           

    return $consigne;
    }
    
/* ********************************************
*
*********************************************** */
 	public function getInpHelp()
 	{global $xoopsConfig, $utility;
        //conteneur pour l'aide et les images
        $trayHelp = new \XoopsFormElementTray  ('', $delimeter = '');          
        //-------------------------------------------
           
        $help = \JJD\FSO\loadtextFile($this->pathArr['help_path']);
        $help = quizmaker_utf8_encode($help);
        $inpHelp = new \XoopsFormLabel  ('', $help);
        //ajout du texte dans le conteneur
        $trayHelp->addElement($inpHelp);
        
        //----------------------------------------------   
/*
        // --- Ajout de la copie d'écran du slide
        $url =  QUIZMAKER_QUIZ_JS_URL . "/quiz-php/images/slide_" . $this->type . '.jpg';
        $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:40px" />
            </a>

        ___IMG___;
        $inpImg = new \XoopsFormLabel  ('', $img);  
        $inpImg->setExtra("class='highslide-gallery'");
//\JJD\include_highslide();       
*/        
        //--------------------------------
        //$inpSnapShoot = new \XoopsFormLabel  ('', 'fgdfhghk');

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
*
* ************************************************** */
 	public function getXoopsFormImage($imgName, $formName, $path, $maxWidth=80, $delimiter=' ', $delChkName='')
 	{ 
     //echo "getXoopsFormImage - image : {$imgName}<br>";
        global $quizmakerHelper;
        $fulName = QUIZMAKER_UPLOAD_PATH . $path . "/" . $imgName;  
        $libImgName = new \XoopsFormLabel('', "[{$imgName}]");

//echo "fullName : {$fulName}<br>";
              
        if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
          $urlImg = "";
        }else{
          $urlImg = QUIZMAKER_UPLOAD_URL . $path . "/" . $imgName;
        }
        
        //affichage de l'image actuelle
        $img = new \XoopsFormLabel('', "<img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' style='max-width:{$maxWidth}px' title='{$imgName}'>");
        //$hiddenImg = nw \XoopsFormHidden());

        //creation du groupe 'traiImg)'
        
        //$imageTray->addElement($img); 
//echo "<hr>urlImg : {$urlImg}<br>"; 
        
        //Selection d'un image locale dans l'explorateur
        //$upload_size = 5000000;
        $upload_size = $quizmakerHelper->getConfig('maxsize_image'); 
        $inpLoadImg = new \XoopsFormFile('', $formName, $upload_size);
//echo "===>upload_size : {$upload_size}<br>";
 
        if($delChkName){
            if($urlImg){
              $delImg = new \XoopsFormCheckBox('', $delChkName);                        
              $delImg->addOption(1, _AM_QUIZMAKER_DELETE);
            }else{
              $delImg = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
            }
                                     
        }else $delImg = new \XoopsFormLabel('', ''); 
 /*
               $delProposition = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
              $delSubstitut = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
            }else{
              $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
              $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);

 */       
        //-------------------------------------------------------------
        $imageTray  = new \XoopsFormElementTray('',''); 
        $imageTray->addElement($img);
        $imageTray->addElement($delImg);
        $imageTray->addElement(new \XoopsFormLabel('', '<br>'));
//        $imageTray->addElement($libImgName);// a garder pour le dev
        $imageTray->addElement($inpLoadImg);
        //$imageTray->setDescription(_AM_QUIZMAKER_IMG_DESC . '<br>' . sprintf(_AM_QUIZMAKER_UPLOADSIZE, $upload_size / 1024), '<br>');
        return $imageTray; 
     }

/* *************************************************
*
* ************************************************** */
 	public function getXoopsFormImage_new($imgName, $name, $id, $path, $maxWidth=80, $delimiter=' ', $delChkName='')
 	{ 
     //echo "getXoopsFormImage - image : {$imgName}<br>";
        global $quizmakerHelper;
        $fulName = QUIZMAKER_UPLOAD_PATH . $path . "/" . $imgName;  
        $libImgName = new \XoopsFormLabel('', "[{$imgName}]");

//echo "fullName : {$fulName}<br>";
              
        if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
          $urlImg = "";
        }else{
          $urlImg = QUIZMAKER_UPLOAD_URL . $path . "/" . $imgName;
        }
        
        //affichage de l'image actuelle
        $formName  = $this->getName() . "_{$name}_{$id}";
        $img = new \XoopsFormLabel('', "<img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' style='max-width:{$maxWidth}px' title={$imgName}>");
        //$hiddenImg = new \XoopsFormHidden($this->getName(, $name), $imgName);

        //creation du groupe 'traiImg)'
        
        //$imageTray->addElement($img); 
//echo "<hr>urlImg : {$urlImg}<br>"; 
        
        //Selection d'un image locale dans l'explorateur
        //$upload_size = 5000000;
        $upload_size = $quizmakerHelper->getConfig('maxsize_image'); 
        $inpLoadImg = new \XoopsFormFile('', $formName, $upload_size);
//echo "===>upload_size : {$upload_size}<br>";
 
        if($delChkName){
            if($urlImg){
              $delImg = new \XoopsFormCheckBox('', $delChkName);                        
              $delImg->addOption(1, _AM_QUIZMAKER_DELETE);
            }else{
              $delImg = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
            }
                                     
        }else $delImg = new \XoopsFormLabel('', ''); 
 /*
               $delProposition = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
              $delSubstitut = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
            }else{
              $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
              $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);

 */       
        //-------------------------------------------------------------
        $imageTray  = new \XoopsFormElementTray('',''); 
        //$imageTray->addElement($hiddenImg);
        $imageTray->addElement($img);
        $imageTray->addElement($delImg);
        $imageTray->addElement(new \XoopsFormLabel('', '<br>'));
//        $imageTray->addElement($libImgName);// a garder pour le dev
        $imageTray->addElement($inpLoadImg);
        //$imageTray->setDescription(_AM_QUIZMAKER_IMG_DESC . '<br>' . sprintf(_AM_QUIZMAKER_UPLOADSIZE, $upload_size / 1024), '<br>');
        return $imageTray; 
     }

/* *************************************************

*************************************************** */
function save_img(&$answer, $formName, $path, $folderQuiz, $prefix, &$nameOrg=''){
global $quizmakerHelper, $quizUtility;
//echoArray(func_get_args());
    //if (!is_array($formName)) return false;
//echoArray($_FILES,'save_img',true);
    if(!$_POST['xoops_upload_file']) return false;    
    $nameOrg = '';
    $keyFile = array_search($formName, $_POST['xoops_upload_file']);    
    if(!$_FILES[$formName]['name']) return '';
    $savedFilename = '';
//echo "<hr>path : {$path}<hr>";    
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    
//     $filename       = $_FILES[$chrono]['name'];
//     $imgMimetype    = $_FILES[$chrono]['type'];
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , 
                    $quizmakerHelper->getConfig('mimetypes_image'), 
                    $quizmakerHelper->getConfig('maxsize_image'), null, null);
    
//---------------------------------------------------    
 //echo "save_img - chrono : {$chrono}<br>" ;  
    
    
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile])) {
        //$prefix = "quiz-{$answer['quest_id']}-{$answer['id']}";
        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile]);
        if (!$uploader->upload()) {
            $uploaderErrors = $uploader->getErrors();
        } else {
            $savedFilename = $uploader->getSavedFileName();
            $maxwidth  = (int)$quizmakerHelper->getConfig('maxwidth_image');
            $maxheight = (int)$quizmakerHelper->getConfig('maxheight_image');


            $nameOrg = $_FILES[$_POST['xoops_upload_file'][$keyFile]]['name'];       
            if($this->renameImage){
                //echo "===>savedFilename : {$savedFilename}<br>";  
                //modification du nom pour les repérer dans le dossier   
                $newName = $prefix . '-' . $quizUtility::sanitiseFileName($nameOrg);
                rename($path.'/'. $savedFilename,  $path.'/' . $newName);
                $savedFilename = $newName;
            }
            //retiir l'extension et remplace les _ par des espaces
            $h= strrpos($nameOrg,'.');
            $i=0;
            $nameOrg = str_replace('_', ' ', substr($nameOrg, $i, $h));

        }


    } else {
        //if ($filename > '') {
            $uploaderErrors = $uploader->getErrors();
        //}
        // il faut garder l'image existante si il n'y a pas eu de nouvelle selection
        // ou l'image sélectionée dans la liste
        //$slidesObj->setVar('sld_image', Request::getString('sld_image'));
        $savedFilename = '';
        exit("save_img");
    }
    return $savedFilename;
}
/* *************************************************

*************************************************** */
function nameOrgParse($nameOrg, &$prefix){

    //on retire l'extension ou le prefix on met le nom de l'image
    $nameOrg = substr($ansObj->getVar('answer_proposition'), strlen($prefix)+1);
    $h= strrpos($nameOrg,'.');
    return str_replace('_', ' ', substr($nameOrg, $i, $h));

    //echo "<hr>{$nameOrg}-{$i}-{$h}|{$prefix}|{$v['caption']}<hr>";exit;

}
/* *************************************************

*************************************************** */
function save_img_old(&$answer, $path, $folderQuiz){
global $quizmakerHelper;
    $chrono = $answer['chrono']-1;
    
    //le chrono ne correspond pad forcément à la clé dans files
    //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
    //un pour le champ "proposition" qui stocke l'image principale
    //et un pour le champ imge qui stocke l'image de substitution

    $ketFile = array_search($propositionName, $_FILES);    
    $savedFilename = '';
    
//echo "<hr>path : {$path}<hr>";    
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    
//     $filename       = $_FILES[$chrono]['name'];
//     $imgMimetype    = $_FILES[$chrono]['type'];
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , 
                                        $quizmakerHelper->getConfig('mimetypes_image'), 
                                        $quizmakerHelper->getConfig('maxsize_image'), null, null);
    
//---------------------------------------------------    
 //echo "save_img - chrono : {$chrono}<br>" ;  
    
/*
              $imgNameDef     = Request::getString('sld_short_name');
              
              //si le nom n'est pas renseigné on prend le nom du fichier image
              $shortName = Request::getString('sld_short_name', '');
              if($shortName == '') {
                if ($filename == '') $filename = Request::getString('sld_image', '');
                $posExt = strrpos($filename, '.');
                $shortName = substr($filename, 0, $posExt);
                $shortName = str_replace("_", " ", $shortName);
                $slidesObj->setVar('sld_short_name', $shortName);
              }else{
                $slidesObj->setVar('sld_short_name', $shortName);                
              }
*/
    
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$ketFile])) {
   
        $prefix = "quiz-{$answer['inputs']}-";
        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$ketFile]);
        if (!$uploader->upload()) {
            $uploaderErrors = $uploader->getErrors();
        } else {
            $savedFilename = $uploader->getSavedFileName();
            $maxwidth  = (int)$quizmakerHelper->getConfig('maxwidth_image');
            $maxheight = (int)$quizmakerHelper->getConfig('maxheight_image');
//echo "===>savedFilename : {$savedFilename}<br>";            
            /*
            if ($maxwidth > 0 && $maxheight > 0) {
                // Resize image
                $imgHandler                = new quizmaker\Common\Resizer();
                //$endFile = "{$theme}-{$savedFilename}" ;
                
                $imgHandler->sourceFile    = SLIDER_UPLOAD_IMAGE_PATH . "/slides/" . $savedFilename;
                $imgHandler->endFile       = SLIDER_UPLOAD_IMAGE_PATH . "/slides/" . $savedFilename;
                $imgHandler->imageMimetype = $imgMimetype;
                $imgHandler->maxWidth      = $maxwidth;
                $imgHandler->maxHeight     = $maxheight;
                $result                    = $imgHandler->resizeImage();
            }
            */
            $answer['proposition'] = $savedFilename;
        }


    } else {
        //if ($filename > '') {
            $uploaderErrors = $uploader->getErrors();
        //}
        // il faut garder l'image existante si il n'y a pas eu de nouvelle selection
        // ou l'image sélectionée dans la liste
        //$slidesObj->setVar('sld_image', Request::getString('sld_image'));
    
    }
    return $savedFilename;
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
//           $urlImg = QUIZMAKER_UPLOAD_URL . $path . "/" . $imgName;
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
//           $urlImg = QUIZMAKER_UPLOAD_URL . $path . "/" . $imgName;
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
        $tbl->addTdStyle(0, 'text-align:center;width:50px;');
        return $tbl;
     }
     
/* //////////////////////////////////////////////////////////////////// */
    
/* ****************************************************

******************************************************* */
public function getPluginPath()
  {  
global $type_questionHandler;    
    return $type_questionHandler->getPluginPath($this->typeQuestion, true);  
  }
    
/* ****************************************************

******************************************************* */
public function getAnswerValues(&$ans, &$weight){
    $tVal = array();
    $weight += 10;

    if (isset($ans)){
      $tVal['answerId']  = $ans->getVar('answer_id');
      $tVal['proposition']  = $ans->getVar('answer_proposition');
      $tVal['image1']  = $ans->getVar('answer_image1');
      $tVal['image2']  = $ans->getVar('answer_image2');
      $tVal['points']  = $ans->getVar('answer_points');
      $tVal['caption']  = $ans->getVar('answer_caption');
      $tVal['addNew']  = false;     
    }else{
      $tVal['answerId']  = 0;
      $tVal['proposition']  = '';
      $tVal['image1']  = '';
      $tVal['image2']  = '';
      $tVal['points']  = 0;
      $tVal['caption']  = '';
      $tVal['addNew']  = true;
    }

    $tVal['weight']  = $weight;
    
    return $tVal;
}     
/* ****************************************************

******************************************************* */
public function getAnswerInp(&$ans, &$i, $inputs, $path){
    $tInp = array();
    //$i++;
    foreach($ans as $k=>$v) $$k = $v;
    //--------------------------------------------
        
    $tInp['inpAnswerId']     = new \XoopsFormHidden($this->getName($i,'id'), $answerId);  
    $tInp['inpInputs']       = new \XoopsFormHidden($this->getName($i,'inputs'), $inputs);            
    $tInp['inpLibChrono']    = new \XoopsFormLabel('', $i+1); 
    $tInp['inpChrono']       = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);  
    $tInp['inpProposition']  = new \XoopsFormText($i+1 . ': ' . _AM_QUIZMAKER_SLIDE_LABEL, $this->getName($i,'proposition'), $this->lgMot1, $this->lgMot2, $proposition);
    $tInp['inpCaption']      = new \XoopsFormText(_AM_QUIZMAKER_CAPTION,  $this->getName($i,'caption'), $this->lgMot1, $this->lgMot1, $caption);            
            
            
    if($addNew){
      $tInp['delProposition'] = new \XoopsFormLabel('', _AM_QUIZMAKER_NEW);                        
    }else{
      $tInp['delProposition'] = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
      $tInp['delProposition']->addOption(1, _AM_QUIZMAKER_DELETE);
    }
           

    $tInp['inpWeight'] = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
    $tInp['inpWeight']->setMinMax(0, 900);
    $tInp['inpPoints'] = new \XoopsFormNumber(_AM_QUIZMAKER_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
    $tInp['inpPoints']->setMinMax(0, 30);
           
            
    $tInp['inpImage1'] = $this->getXoopsFormImage_new($image1, "image1", $i, $path, 80, '<br>', $this->getName($i,'delete_image1'));
    $tInp['inpImage1Lib'] = new \XoopsFormLabel('', $image1);                        
    
    $tInp['inpImage2'] = $this->getXoopsFormImage_new($image2, "image2", $i, $path, 80, '<br>', $this->getName($i,'delete_image2'));  
    $tInp['inpImage2Lib'] = new \XoopsFormLabel('', $image2);                        
    
/*
    $tInp['inpImage1'] = $this->getXoopsFormImage($image1, $this->getName($i, "_image1_{$i}"), $path, 80, '<br>', $this->getName($i,'delete_image1'));
    $tInp['inpImage1Lib'] = new \XoopsFormLabel('', $image1);                        
    
    $tInp['inpImage2'] = $this->getXoopsFormImage($image2, $this->getName($i, "_image2_{$i}"), $path, 80, '<br>', $this->getName($i,'delete_image2'));  
    $tInp['inpImage2Lib'] = new \XoopsFormLabel('', $image2);                        
*/    
      
//     $tInp['inpImgSubstitut'] = $this->getXoopsFormImage($image, $this->getName()."_substitut_{$i}", $path, 80, '<br>', $this->getName($i,'delete_image_Substitution'));            
//     $tInp['inpImageSubstitutLib'] = new \XoopsFormLabel('', $image);                        
            
                

      
    return $tInp;
}     


} // fin de la classe

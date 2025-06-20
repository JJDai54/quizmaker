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


/**
 * Class Object Handler Plugin
 */
class PluginsHandler 
{
    var $dirname = QUIZMAKER_PATH_MODULE . "/quiz/quiz-php/class";
    var $name = '';
    var $description= '';
    var $allPlugins = null;
    var $renameImage = false; // Permet de garder le nom originale d'une image, a utiliser en dev uniquement
        
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct()
	{
      $this->allPlugins = $this->getAllPluginsPath();    
      //echoArray($this->allPlugins );
	}


	/**
	 * Get Count Plugin in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCount()
	{
		return Count($this->getListKeyName());
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
	 * Get Count Quiz in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountQuiz()
	{
        
		return count($this->getListKeyName());
	}

/* ***********************

************************** */
public function getListKeyName($category = null, $boolShowCode = false, $addPagesBeginEnd = false){
        $listTQ = $this->getAll($category);
        $ret = array();

        
        foreach ($listTQ as $key=>$arr){
           if($arr['obsolette']) continue;
           if (!$addPagesBeginEnd && ($key == "pageBegin" || $key == "pageEnd")) continue;
           $ret[$arr['type']] = (($boolShowCode) ? $arr['type'] . ' : ' : '') . $arr['name'];  
        }

       //tri sur le poids poids 
       ksort ($ret);
       return $ret;

}

/* ************************

************************** */
public function getListByGroup($boolCode = false, $addPagesBeginEnd = false){
    $allTQ = $this->getAll(null, true);
    $list = array();

    foreach($allTQ as $key => $arr) {
       if($arr['obsolette']) continue;
       if (!$addPagesBeginEnd && ($key == "pageBegin" || $key == "pageEnd")) continue;
       if(!isset($list[$arr['category']])) $list[$arr['category']] = $arr['category'];
       
       $key = $arr['category'] .'-' . $arr['type'];
       $list[$key] = $arr['type'];
       
    }
    ksort($list);
    //echoArray($list);

    //--------------------------------------------------------
    $ret = array();
    $prefix = '--- ';
    
    foreach($list as $key => $v) {
        if ($key == $v){
            $ret['>' . $v] = constant(QUIZMAKER_PREFIX_CAT .  strToUpper($v));
        }else{
            $lib = $allTQ[$v]['name'];
            $ret[$v] = $prefix . $lib . (($boolCode) ? " [{$v}]"  : '') ;
        }
    }
    
    //echoArray($ret);
    return $ret;    
}
/* ***********************

************************** */
public function getCategories($addAll = false){
    $allTQ = $this->getAll(null, true);
    $cat = array();    
    if($addAll) $cat[QUIZMAKER_ALL] = '(*)';
    foreach($allTQ as $key => $arr) {
        if(!array_key_exists($arr['category'], $cat))
            $cat[$arr['category']] = $arr['category'] . " - " . constant(QUIZMAKER_PREFIX_CAT . strToUpper($arr['category']));
            $cat[$arr['category']] = constant(QUIZMAKER_PREFIX_CAT . strToUpper($arr['category'])) . " [{$arr['category']}]";
    }
    return $cat;    
}

/****************************************************************************
 * 
 ****************************************************************************/

public function getClassPlugin($pluginName){
    $clsName = "Plugin_" . $pluginName;   
    $f = QUIZMAKER_PATH_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.php";  
//    echo "<hr>getClassPlugin : {$f}<hr>";
    include_once($f);    
    $cls = new $clsName; 
    return $cls;

}
/****************************************************************************
 * 
 ****************************************************************************/
public function exists($pluginName){
    $clsName = "Plugin_" . $pluginName;   
    $f = QUIZMAKER_PATH_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.php";  
    return file_exists($f);
}
/****************************************************************************
 * isValid : verifie la validité d'un pluging
 * retour err bool:
 *  err 0 : le plugin est valid et a ete installé avec succes
 *  err 1 : le plugin n'est pas valid
 ****************************************************************************/
public function isValid($pluginName, $fullPath){
global $quizUtility;
    //verification, est-ce un plugin valide ?
    $source = "{$fullPath}/{$pluginName}"; 
    $f1 = "{$source}/{$pluginName}.php"; 
    $f2 = "{$source}/js/{$pluginName}/{$pluginName}.js"; 
    
    return (file_exists($f1) && file_exists($f2));
   }
/****************************************************************************
 * retour err int:
 * err 0 : le plugin est valid et a ete installé avec succes
 * err 1 : le plugin n'est pas valid
 ****************************************************************************/
public function install($pluginName, $fullPath){
global $quizUtility;
    $ret = 2;
    if(!$pluginName) return $ret;
    
$pluginPhpPath = QUIZMAKER_PATH_PLUGINS_PHP . "/" . $pluginName;
$pluginJsPath  = QUIZMAKER_PATH_QUIZ_JS . "/plugins/" . $pluginName;
echo "<hr>pluginHandler->install {$pluginName}<br>{$pluginPhpPath}<br>{$pluginJsPath}<br>{$fullPath}<hr>";

    //verification, est-ce un plugin valide ?
    if (!$this->isValid($pluginName, $fullPath)) return 1;
    //--------------------------------------------


    \JANUS\FSO\setChmodRecursif($pluginPhpPath, 0777);
    $quizUtility::deleteDirectory($pluginPhpPath);                      

    \JANUS\FSO\setChmodRecursif($pluginJsPath, 0777);
    $quizUtility::deleteDirectory($pluginJsPath); 
                         
//exit('pluginHandler->install');    
//exit('pluginHandler->install');    
    //copie des fichiers plugins/js et suppression de ces fichiers source
    $source = "{$fullPath}/{$pluginName}/js";
    $dest =  QUIZMAKER_PATH_QUIZ_JS . "/plugins";
    $quizUtility->copyFolder($source, $dest); 
    $quizUtility::deleteDirectory($source);    
//echo "<hr>pluginHandler->install {$pluginName}<br>source = {$source}<br>destination = {$dest}<hr>";
    
                      
    //copie des fichiers plugins/php                      
    $source = "{$fullPath}/{$pluginName}";
    $dest =  QUIZMAKER_PATH_PLUGINS_PHP . '/' . $pluginName;
    $quizUtility->copyFolder($source, $dest); 
//echo "<hr>pluginHandler->install {$pluginName}<br>source = {$source}<br>destination = {$dest}<hr>";
//exit('pluginHandler->install');    
    return 0;
}

/* **************************************************

***************************************************** */
public function getPlugin(&$pluginName)
  {global $GLOBALS, $xoTheme;
    // pour permettre une correction sans aceder à la base apres un changement de nom
    // A virer dès que les noms seront stabilisés
    // $pluginName est passé par référence pour remonter le transfert
    $this->getNewPluginName($pluginName);

      //if(!isset($pluginName[$pluginName])) echo "<hr>===> {$pluginName}<br>";
      if(isset($this->allPlugins))
        $f = $this->allPlugins[$pluginName]['php_path'];  
      else
        $f = QUIZMAKER_PATH_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.php";  
      
      $clsName = "Plugin_" . $pluginName;   
// echo "<hr>{$f}<br>{$pluginName}<br>{$clsName}<hr>";
      //----------------------------------------------
      //chargement du javascript du plugin
      $jsPath = QUIZMAKER_PATH_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.js";  
      if (file_exists($jsPath)){
        //echo "javascript du plugin <br>{$jsPath}<br>{$f}<hr>";
        $jsUrl = QUIZMAKER_URL_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.js";  
        $GLOBALS['xoTheme']->addScript($jsUrl);
      }
      $cssPath = QUIZMAKER_PATH_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.css";  
      if (file_exists($jsPath)){
        //echo "javascript du plugin <br>{$jsPath}<br>{$f}<hr>";
        $jsUrl = QUIZMAKER_URL_PLUGINS_PHP . "/{$pluginName}/{$pluginName}.css";  
        $GLOBALS['xoTheme']->addStylesheet($jsUrl);
      }
      //----------------------------------------------
      if (class_exists($clsName) ){
          $cls = new $clsName; 
          $cls->loadJS();
          return $cls;
      }else if (file_exists($f) && !class_exists($clsName) ){
          include_once($f);    
          $cls = new $clsName; 
          $cls->loadJS();
          return $cls;
      }
      else{
        return null;
      }
      
  }
  /* *****
  * transfert de classes obsolettes
  * pour permettre une correction sans acceder à la base apres un changement de nom
  * A virer dès que les noms seront stabilisés
  * $pluginName est passé par référence pour remonter le transfert
  * ****** */
public function getNewPluginName(&$pluginName){

    switch($pluginName){
        case 'checkboxLogical' :
            $pluginName = 'checkboxSimple'; 
        case 'listboxIntruders1' : 
        case 'listboxIntruders2' : 
        case 'listboxIntruders' : 
            $pluginName = 'listboxClassItems';   
            break;
        case 'imagesSortItems' :
        case 'imagesDaDSortItems' :
            $pluginName = 'sortItems'; 
            break;
        case 'ulSortList' :
            $pluginName = 'ulDaDSortList'; 
            break;
        case 'imagesDaDBasket' :
            $pluginName = 'imagesDaDGroups'; 
            break;
        case 'textarea' :
            $pluginName = 'textareaSimple'; 
            break;
        case 'radioMultiple2' :
            $pluginName = 'radioMultiple'; 
            break;
        case 'multiTextbox' :
            $pluginName = 'textboxMultiple'; 
            break;
        case 'imagesDaDLogical' :
            $pluginName = 'imagesDaDMatchItems'; 
            break;
        case 'imagesDaDSortItemsIns' :
            $pluginName = 'imagesDaDSortItems'; 
            break;
        case 'textareaSimple' :
        case 'textareaListbox' :
        case 'textareaInput' :
            $pluginName = 'textareaMixte'; 
            break;
        case 'comboboxMatchItems' :
        case 'textboxMatchItems' :
            $pluginName = 'matchItems'; 
            break;
        case 'checkboxSimple' :
        case 'radioLogical' :
        case 'radioSimple' :
            $pluginName = 'selectInputs'; 
            break;
        case 'sortCombobox' :
        case 'comboboxSortList' :
        case 'comboboxSortItems' :
        case 'listboxSortItems' :
            $pluginName = 'sortItems'; 
            break;
        case 'radioQuickImg' :
            $pluginName = 'selectImages'; 
            break;
        case 'textboxMultiple' :
            $pluginName = 'multiQuestions'; 
            break;
        case 'classicSelect' :
        case 'choiceSimple' :
            $pluginName = 'selectInputs'; 
            break;
        case 'choiceImages' :
            $pluginName = 'selectImages'; 
            break;
  }
}
/* ////////////////////////////////////////////////////////////////////// */

/* ****************************************************

******************************************************* */
public function getAllPluginsName(){
    return XoopsLists::getDirListAsArray(QUIZMAKER_PATH_PLUGINS_PHP);     
}
/* ****************************************************

******************************************************* */
public function getAllPlugins($category=null, $bolKeyType = false){
    if (!$category) $category=QUIZMAKER_ALL;
    
    $allPluginsName =  \XoopsLists::getDirListAsArray(QUIZMAKER_PATH_PLUGINS_PHP);
//echoArray($allPluginsName, QUIZMAKER_PATH_PLUGINS_PHP);    
    $ret = array();    
    foreach($allPluginsName as $key=>$pluginName){    
          
          $cls = $this->getPlugin($pluginName);
          if($cls->category == $category || $category == QUIZMAKER_ALL) {
          
          
          if($bolKeyType)
            $ret[$cls->type] = $cls->getValuesPlugin();
          else
            $ret[] = $cls->getValuesPlugin();
        }
        
    }
//echoArray($ret, 'getPluginPath');   
    return $ret;     
}

    
	/**
	 * Get All Plugin in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAll($category=null, $bolKeyType = false)
	{
        return  $this->getAllPlugins($category, $bolKeyType);
	}

/* ****************************************************

******************************************************* */
public function getAllPluginsPath(){

    $allPluginsName =  \XoopsLists::getDirListAsArray(QUIZMAKER_PATH_PLUGINS_PHP);
    $ret = array();    
    
    foreach($allPluginsName as $key=>$pluginName){   
        $ret[$pluginName] = $this->getPluginPath($pluginName); 
    }
//echoArray($ret, 'getPluginPath');  exit; 
    return $ret;     
}
/* ****************************************************

******************************************************* */
public function getPluginPath($pluginName, $includefiles = false )
  {  
    $language = empty( $GLOBALS['xoopsConfig']['language'] ) ? 'english' : $GLOBALS['xoopsConfig']['language'];
                      
    $pArr = array();
    $pArr['name'] = $pluginName;
    $pArr['path'] = QUIZMAKER_PATH_PLUGINS_PHP . '/' . $pluginName;
    $pArr['url']  = QUIZMAKER_URL_PLUGINS_PHP  . '/' . $pluginName;
    
    $pArr['php_path'] = $pArr['path'] . "/{$pluginName}.php";    
    $pArr['js_path']  = $pArr['path'] . "/{$pluginName}.js";
    $pArr['constants_path'] = $pArr['path'] . "/language/{$language}/constants.php";    
    $pArr['help_path'] = $pArr['path'] . "/language/{$language}/help.html";    
    $pArr['consigne_path'] = $pArr['path'] . "/language/{$language}/consigne.html";    
    $pArr['img'] = $pArr['path'] . '/img';    
    
    $fldSnapShoot = '/snapshoot';    
    //=====================================================================    
    //remplacer les path par un tableau

    //=====================================================================    
    
    $pArr['js_url']  = $pArr['url'] . "/{$pluginName}.js";
    $pArr['img_url'] = $pArr['url'] . '/img';    
   
    
//--------------------------------------------------------------------
    $filesList =  \XoopsLists::getFileListByExtension($pArr['path'].$fldSnapShoot, array('jpg'));    
    $pArr['snapshoot_path'] = array();    
    $pArr['snapshoot_url']  = array();    
//echo "<hr>{$pArr['zpath']['snapshoot']}<hr>";    
//echoArray($filesList);
    foreach($filesList AS $key=>$f){
        $pArr['snapshoot_path'][$key] = $pArr['path'] . $fldSnapShoot .'/' . $f;    
        $pArr['snapshoot_url'][$key]  = $pArr['url']  . $fldSnapShoot .'/' . $f;    
    }  
    include_once( $pArr['constants_path']);
    include_once( $pArr['php_path']);

    return $pArr;  
  }
    
/* *************************** gestion des images ************************ */

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
//echo 'save_img : ' . $path;exit;    
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
//             $maxwidth  = (int)$quizmakerHelper->getConfig('maxwidth_image');
//             $maxheight = (int)$quizmakerHelper->getConfig('maxheight_image');


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
    }
    //exit ($savedFilename);
    return $savedFilename;
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
        global $quizmakerHelper;
        $fulName = QUIZMAKER_PATH_UPLOAD . $path . "/" . $imgName;  
        $libImgName = new \XoopsFormLabel('', "[{$imgName}]");

//echo "fullName : {$fulName}<br>";
              
        if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
          $urlImg = "";
        }else{
          $urlImg = QUIZMAKER_URL_UPLOAD . $path . "/" . $imgName;
        }
        
        //affichage de l'image actuelle
        if($div){
            $img = "<div id='divImg_{$formName}' name='divImg_{$formName}'><img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' title=''></div>";
        }else{
            $img = "<img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' style='max-width:{$maxWidth}px' title='{$imgName}'>";
        }

        $inpImg = new \XoopsFormLabel('', $img);
        //$hiddenImg = nw \XoopsFormHidden());

        //affichage du titre
        //$inpCaption = new \XoopsFormLabel('', $caption);
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
              $delImg = new \XoopsFormLabel('', _CO_QUIZMAKER_NEW);                        
            }
                                     
        }else $delImg = new \XoopsFormLabel('', ''); 

        //-------------------------------------------------------------
        $imageTray  = new \XoopsFormElementTray($caption,''); 
        //$imageTray->addElement(new \xoopsFormHidden($formName, $imgName));
        $imageTray->addElement($inpImg);
        $imageTray->addElement($delImg);
        $imageTray->addElement(new \XoopsFormLabel('', '<br>'));
//        $imageTray->addElement($libImgName);// a garder pour le dev
        if($description) $imageTray->addElement(new \XoopsFormLabel('', $description . '<br>'));
        $imageTray->addElement($inpLoadImg);
        //$imageTray->setDescription(_AM_QUIZMAKER_IMG_DESC . '<br>' . sprintf(_AM_QUIZMAKER_UPLOADSIZE, $upload_size / 1024), '<br>');
        //$inpCaption = new \XoopsFormLabel('', $caption);
        return $imageTray; 
     }

public function getXoopsFormImageDiv($imgName, 
                                      $formName, 
                                      $path, 
                                      $class, 
                                      $delimiter=' ', 
                                      $delChkName='', 
                                      $caption = '', 
                                      $description = '')
    {
    
     //echo "getXoopsFormImage - image : {$imgName}<br>";
        global $quizmakerHelper;
        $fulName = QUIZMAKER_PATH_UPLOAD . $path . "/" . $imgName;  
        $libImgName = new \XoopsFormLabel('', "[{$imgName}]");

//echo "fullName : {$fulName}<br>";
              
        if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
          $urlImg = "";
        }else{
          $urlImg = QUIZMAKER_URL_UPLOAD . $path . "/" . $imgName;
        }
        
        //affichage de l'image actuelle

            $img = "<div id='divImg_{$formName}' name='divImg_{$formName}' class='$class'><img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' title=''></div>";

        $inpImg = new \XoopsFormLabel('', $img);
        //$hiddenImg = nw \XoopsFormHidden());

        //affichage du titre
        //$inpCaption = new \XoopsFormLabel('', $caption);
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
              $delImg = new \XoopsFormLabel('', _CO_QUIZMAKER_NEW);                        
            }
                                     
        }else $delImg = new \XoopsFormLabel('', ''); 

        //-------------------------------------------------------------
        $imageTray  = new \XoopsFormElementTray($caption,''); 
        //$imageTray->addElement(new \xoopsFormHidden($formName, $imgName));
        $imageTray->addElement($inpImg);
        $imageTray->addElement($delImg);
        $imageTray->addElement(new \XoopsFormLabel('', '<br>'));
//        $imageTray->addElement($libImgName);// a garder pour le dev
        if($description) $imageTray->addElement(new \XoopsFormLabel('', $description . '<br>'));
        $imageTray->addElement($inpLoadImg);
        //$imageTray->setDescription(_AM_QUIZMAKER_IMG_DESC . '<br>' . sprintf(_AM_QUIZMAKER_UPLOADSIZE, $upload_size / 1024), '<br>');
        //$inpCaption = new \XoopsFormLabel('', $caption);
        return $imageTray; 
    }

/* **********************************************************
*
* *********************************************************** */
public function getFormImage($caption, $name, $image, $folderJS)
{

    $path =  QUIZMAKER_FLD_UPLOAD_QUIZ_JS . "/{$folderJS}/images";  
//echo $path;exit;
    //$fullName =  QUIZMAKER_FLD_UPLOAD_QUIZ_JS . "/{$folderJS}/images/" . $tValues[$name];     
    //$inpImage = $this->getXoopsFormImage($tValues[$name], "{$optionName}[{$name}]", $path, 80,'<br>');  
    $inpImage = $this->getXoopsFormImage($image, $name, $path, 80,'<br>', 'del_' . $name, $caption);  
    
    return $inpImage;
  }


} // ********************* Fin de la class ******************************

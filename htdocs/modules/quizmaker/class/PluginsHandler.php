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
echo "<hr>pluginHandler->install {$pluginName}<br>source = {$source}<br>destination = {$dest}<hr>";
    
                      
    //copie des fichiers plugins/php                      
    $source = "{$fullPath}/{$pluginName}";
    $dest =  QUIZMAKER_PATH_PLUGINS_PHP . '/' . $pluginName;
    $quizUtility->copyFolder($source, $dest); 
echo "<hr>pluginHandler->install {$pluginName}<br>source = {$source}<br>destination = {$dest}<hr>";
//exit('pluginHandler->install');    
    return 0;
}


public function getPlugin(&$pluginName)
  {
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
      if (class_exists($clsName) ){
          $cls = new $clsName; 
          return $cls;
      }else if (file_exists($f) && !class_exists($clsName) ){
          include_once($f);    
          $cls = new $clsName; 
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
        case 'radioLogical' :
            $pluginName = 'radioSimple'; 
            break;
        case 'listboxIntruders1' : 
        case 'listboxIntruders2' : 
        case 'listboxIntruders' : 
            $pluginName = 'listboxClassItems';   
            break;
        case 'sortCombobox' :
        case 'comboboxSortList' :
            $pluginName = 'comboboxSortItems'; 
            break;
        case 'imagesSortItems' :
            $pluginName = 'imagesDaDSortItems'; 
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
        case 'radioSimple' :
            $pluginName = 'choiceSimple'; 
            break;
        case 'comboboxSortItems' :
        case 'listboxSortItems' :
            $pluginName = 'sortItems'; 
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
    



} //Fin de la class

<?php

namespace XoopsModules\Quizmaker\Utilities;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  quizmaker
 *
 * @package      \module\quizmaker\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use Xmf\Request;
use JANUS;
//include_once XOOPS_ROOT_PATH . "/modules/quizmaker/class/Utility.php";
                            
//$utility = new \XoopsModules\Quizmaker\Utility();

/**
 * Class Utility
 */
trait ModuleUtility
{

/* ************************************************
*
* ************************************************* */
public static function create_quiz_arborescense($path){
   // $path = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$folderName}";
//echo "<hr>{$path}<hr>";  
\JANUS\FSO\isFolder($path, true);  
\JANUS\FSO\isFolder($path . '/js', true);
\JANUS\FSO\isFolder($path . '/images', true);  
  
//     if (!is_dir($path))             mkdir($path, 0777, true);
//     if (!is_dir($path . '/js'))     mkdir($path . '/js', 0777, true);
//     if (!is_dir($path . '/images')) mkdir($path . '/images', 0777, true);
}


/****************************************************************************
// copie le contenu du repertoire $orig vers le repertoire $dest  
// copie tous les sous-reps de manière récursive 
// sous-entendu qu'on a les droits d'écriture, bien sûr! 
 ****************************************************************************/
public static function copyFolder($source, $dest) { 

  if (!is_dir($dest)) mkdir($dest, 0755);
  foreach (
    $iterator = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
    \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
    
    $dir = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
    if ($item->isDir()) {
        if(!is_dir($dir)) mkdir($dir);
    } else if(!is_dir($dir)){
        copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
    }
  }
}

/****************************************************************************
* supprime l'arborescense du dossier puis le dossier lui-même  
 ****************************************************************************/
public static function deleteFolder($dir){
    self::deleteTree($dir);
    rmdir($dir); // Et on le supprime
}
/****************************************************************************
* supprime l'arborescence d'un dossier, mais pas le dossier lui même  
 ****************************************************************************/
public static function deleteTree($dir){
    foreach(glob($dir . "/*") as $element){
        if(is_dir($element)){
            self::deleteTree($element); // On rappel la fonction deleteTree           
            rmdir($element); // Une fois le dossier courant vidé, on le supprime
        } else { // Sinon c'est un fichier, on le supprime
            unlink($element);
        }
        // On passe à l'élément suivant
    }
}

/****************************************************************************
* supprime dossier  
 ****************************************************************************/
public static function rmAllDir($strDirectory){
    $handle = opendir($strDirectory);
    while(false !== ($entry = readdir($handle))){
        if($entry != '.' && $entry != '..'){
            if(is_dir($strDirectory.'/'.$entry)){
                self::rmAllDir($strDirectory.'/'.$entry);
            }
            elseif(is_file($strDirectory.'/'.$entry)){
                unlink($strDirectory.'/'.$entry);
            }
        }
    }
    rmdir($strDirectory.'/'.$entry);
    closedir($handle);
}




/* ***********************

************************** */
public static function getNewBtn($caption, $op, $img,  $title ){
/*
<div class="xo-buttons">
<a class="ui-corner-all tooltip" href="questions.php?op=new" title="Add New Questions"><img src="https://xoopsfr.kiolo.fr/Frameworks/moduleclasses/icons/32/add.png" title="" alt="">Add New Questions</a>
&nbsp;</div>
*/

$html = <<<__HTML__
<a class="ui-corner-all tooltip" title="{$title}" onclick="document.quizmaker_select_filter.op.value='{$op}';document.quizmaker_select_filter.submit();">
<img src="{$img}" title="" alt="">{$caption}</a>
&nbsp;
__HTML__;

    return $html;

}






/**************************************************************
 * 
 * ************************************************************/
public static function submitQuizVerif($quizId, $uid, $uname)
{
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
        $criteria = new \CriteriaCompo(new \criteria('result_ip', $ip, "="));
        $criteria->add(new \criteria('result_quiz_id', $quizId, "="));
        $resultsCount = $resultsHandler->getCount($criteria);
        $attempt_max = 3;
        if ($resultsCount >= $attempt_max){
			redirect_header("categories.php?op=list&quiz_id={$quizId}&sender=", 3, _MA_QUIZMAKER_STILL_ANSWER);
        }        
		

}

    /**
     * @param \Xmf\Module\Helper $quizmakerHelper
     * @param array|null         $options
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
//      Avertissement: Declaration of XoopsModules/Quizmaker/QuizUtility::
//      getEditor($caption, $name, $value, $description = '', $newOptions = NULL, $quizmakerHelper = NULL) 
//      should be compatible with XoopsModules/Quizmaker/Utility::getEditor($quizmakerHelper = NULL, $options = NULL)
//       dans le fichier /modules/quizmaker/class/QuizUtility.php ligne 
    public static function getEditor2($caption, $name, $value, $description = "", $newOptions = null, $quizmakerHelper = null)
    {
        if ($quizmakerHelper === null) $quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
        $options           = [];
        $options['name']   = $name;
        $options['value']  = $value;
        $options['rows']   = 10;
        $options['cols']   = '100%';
        $options['width']  = '100%';
        $options['height'] = '300px';
        $options['editor'] = $quizmakerHelper->getConfig('editor_admin');
        
        if($newOptions !== null){
          $keys = array('rows','cols','width','height');
          for ($h=0; $h < count($keys); $h++){
                $key = $keys[$h];
                if (isset($newOptions[$key]) )  $options[$key] = $newOptions[$key];
          }
        }

        $isAdmin = $quizmakerHelper->isUserAdmin();

        if (class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor($caption, $name, $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor($caption, $name, $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea($caption, $name, $options['value'], '100%', '100%');
        }

        //        $form->addElement($descEditor);
        if($description) $descEditor->setDescription($description);
        return $descEditor;
    }
    
/**********************************************************************
 * 
 **********************************************************************/
    public static function loadTextFile ($fullName){


  if (!is_readable($fullName)){return '';}
  
  $fp = fopen($fullName,'rb');
  $taille = filesize($fullName);
  $content = fread($fp, $taille);
  fclose($fp);
  
  return $content;

}



////////////////////////////////////////////////////////////////
/* ************************************************
*
* ************************************************* */
public static function sanitise($exp){
    //$exp = str_replace("'","_",$exp);
    return html_entity_decode($exp,ENT_QUOTES);
}
/* ************************************************
*
* ************************************************* */
public static function sanitiseFileName($str, $replaceBlankBy = '_'){
//echo "nom du fichier avant : {$str}<br>";
   $str = self::minusculesSansAccents($str);

//echo "nom du fichier après : {$str}<br>";

   return $str;
}

/////////////////////////////////////
public static function minusculesSansAccents($str, $replaceBlankBy = '_'){
    //$str = mb_strtolower($str, 'UTF-8');
    $str = utf8_decode($str);
    $str = str_replace(
			array(
				'à', 'â', 'ä', 'á', 'ã', 'å',
				'î', 'ï', 'ì', 'í', 
				'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
				'ù', 'û', 'ü', 'ú', 
				'é', 'è', 'ê', 'ë', 
				'ç', 'ÿ', 'ñ',
				'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
				'Î', 'Ï', 'Ì', 'Í', 
				'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø', 
				'Ù', 'Û', 'Ü', 'Ú', 
				'É', 'È', 'Ê', 'Ë', 
				'Ç', 'Ÿ', 'Ñ'
			),
			array(
				'a', 'a', 'a', 'a', 'a', 'a', 
				'i', 'i', 'i', 'i', 
				'o', 'o', 'o', 'o', 'o', 'o', 
				'u', 'u', 'u', 'u', 
				'e', 'e', 'e', 'e', 
				'c', 'y', 'n', 
				'A', 'A', 'A', 'A', 'A', 'A', 
				'I', 'I', 'I', 'I', 
				'O', 'O', 'O', 'O', 'O', 'O', 
				'U', 'U', 'U', 'U', 
				'E', 'E', 'E', 'E', 
				'C', 'Y', 'N'
			),$str);
  
   if ($replaceBlankBy) $str = strtr($str," ", $replaceBlankBy);

return $str;
}



}  //fin de la classe

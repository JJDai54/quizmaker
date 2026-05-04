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
trait QuizExport
{

// =========================================================
// ============ Fonctions d'exportation des quiz ===========
// =========================================================
/* ***********************

************************** */
public static function quiz_export($quizId, $modeName = 0, $suffix = 0, $emptyfld=false){
    self::quiz_export_all(0, '', $quizId);
}

/* ***********************

************************** */
public static function quiz_export_one_quiz($quizId, $modeName = 0, $suffix = 0, $emptyfld=false){
global $quizHandler, $categoriesHandler, $quizUtility;
    self::quiz_export_all(0, '', $quizId);
    $quiz = $quizHandler->get($quizId);
    $uploadArr = self::quiz_export_once($quiz, $fldExport, $modeName, $suffix);
    redirect_header("download_file.php?fullName={$uploadArr['file']}&typeDownload=application/zip",0,'');
}

/* ***********************

************************** */
public static function quiz_export_clear_flags($flagToExclude = -1){
global $quizHandler;
        $criteria = new \Criteria('quiz_flag',$flagToExclude,'<>');                
        $quizHandler->updateAll('quiz_flag', 0, $criteria, true);                                       
        $quizHandler->updateAll('quiz_flag_text', '', $criteria, true);     
}
/* ***********************

************************** */
public static function quiz_export_all($catId, $subject, $quizId, 
                                       $modeName = 0, $suffix = 0, $emptyfld=false){
global $quizHandler, $categoriesHandler, $quizUtility;
//  echo "quiz_export_all : quizCatId = {$catId} - subject = {$subject} - quizId = {$quizId} - ";    
//  exit;                 
        self::quiz_export_clear_flags(3);                                         
        $criteria = new \CriteriaCompo();   
        if($quizId > 0)  {
            $criteria->add(new \Criteria("quiz_id",$quizId,"="));                                       
        }else if($subject && $subject != QUIZMAKER_ALL_ITEMS_KEY){
            $criteria->add(new \Criteria("quiz_subject",$subject,"="));                                       
            $criteria->add(new \Criteria("quiz_cat_id",$catId,"="));                                       
        }else if($catId > 0){
            $criteria->add(new \Criteria("quiz_cat_id",$catId,"="));                                       
        }
        
        $allQuiz = $quizHandler->getAllQuiz($criteria);
        //echo "<hr>" . count($allQuiz) . "<hr>";
        //-------------------------------------------------
        //dossier d'export
        if($quizId > 0){
              //cas ou quizId a ete passé en paramettre, export d'une seul quiz
              $key = array_key_first($allQuiz);
              //pour recupérer la categorie
              $quiz = $allQuiz[$key];
              $catId = $quiz->getVar('quiz_cat_id'); 
        }
        $cat  = $categoriesHandler->get($catId);
        $catName = \JANUS\sanityseNameForFile($cat->getVar('cat_name'));   
        $fldExport = getQMFolder('','u', 'export', $catName) ;
        
        \JANUS\FSO\isFolder(XOOPS_ROOT_PATH . '/' . $fldExport, true);
        if($emptyfld) $quizUtility->clearFolder(getQMFolder('p','',$fldExport));        
        
        //-------------------------------------------------
/*
        //exit($quizSubject);
        if($quizSubject && $quizSubject != QUIZMAKER_ALL_ITEMS_KEY) $criteria->add(new Criteria("quiz_subject",$quizSubject,"="));
*/        

        $msgArrOk = [];
        $msgArrErr = [];

		foreach(array_keys($allQuiz) as $j) {
            $quizId = $allQuiz[$j]->getVar('quiz_id');
            echo "===> quizId = {$quizId} => " . $allQuiz[$j]->getVar('quiz_name') . "<br>";

            //$uploadArr = $quizUtility::buildQuiz($quizId);
            //---------------------------------------'
            $uploadArr = self::quiz_export_once($allQuiz[$j], $fldExport, $modeName, $suffix);
            //---------------------------------------'
            if($uploadArr['err'] > 0){
                $msgArrErr[] = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_ALL_REF2,$uploadArr['quizId'],$uploadArr['name'],$uploadArr['err'],$uploadArr['errlib']);

            }else{
                $msgArrOk[] = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_ALL_REF1,$uploadArr['quizId'],$uploadArr['name'],$uploadArr['errlib']);
                            //. "===>{$uploadArr['path']}";
            }
        
        //$quizUtility::quiz_download_zip($uploadArr['href'], $uploadArr['name'], 2000);
        if($uploadArr['err'] == 0){
          $quizHandler->setValue($quizId,'quiz_flag', 3);            
          $quizHandler->setValue($quizId,'quiz_flag_text', $uploadArr['link']);            
        }else{
          $quizHandler->setValue($quizId,'quiz_flag', -1);            
          $quizHandler->setValue($quizId,'quiz_flag_text', '');            
        }

//echoArray($uploadArr);  
        }  

        $msg = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_ALL_RET, 
                        QUIZMAKER_PATH_UPLOAD_EXPORT,
                        count($msgArrOk), 
                        implode("<br>", $msgArrOk),
                        count($msgArrErr),
                        implode("<br>", $msgArrErr)); 

//exit;      
        return $msg;          
                                       
}

/* ***********************

************************** */
public static function quiz_export_once($quiz, $fldExport, $modeName = 0, $suffix = 0){
global $quizHandler, $categoriesHandler, $pluginsHandler, $quizUtility;
//echo "<hr>quiz_export - quizId = {$quizId}<hr>";
    
    $quizId =  $quiz->getVar('quiz_id');
    $rootExp = getQMFolder('p', '', $fldExport);
    $rootUrl = getQMFolder('u', '', $fldExport);
    //echo $rootExp . "<br>";exit;
    \JANUS\FSO\isFolder($rootExp, true);  
 
    $name = $quiz->getVar('quiz_name');
    $folderJS = $quiz->getVar('quiz_folderJS');   
     

    $retArr = array('id'     => $quizId,
                    'quizId' => $quizId,
                    'name'   => $name,
                    'href'   => '',
                    'link'   => '',
                    'path'   => '',
                    'err'    => 0,
                    'errlib' => 'ok');
    
    $retArr['err'] = self::verif_exportable($quizId);
    if ($retArr['err'] > 0){
        $retArr['errlib'] = constant("_AM_QUIZMAKER_QUIZ_EXPORT_ERR{$retArr['err']}");
        return $retArr;
    }
    
    //suppression des images non référencées dans les réponses
    $quizHandler->purgerImages($quizId);
    
    $exportPath = $rootExp . "/{$folderJS}/";
    \JANUS\FSO\isFolder($exportPath, true);  

    //echo "$exportPath" ; exit;
    //---------------------------------------
    // ======== Export des table en YML ==========
    self::quiz_exportToYml($quizId, $exportPath);
    //---------------------------------------
    
    //$expName = ($modeName == 0) ? $folderJS : \JANUS\sanityseNameForFile($name);    
    $expName = \JANUS\sanityseNameForFile($name);    
//echo "<hr>quiz_export->expName : {$expName}<hr>";    
    //if($prefixCat) {$expName = '{/{$catName}}-{$expName}'; 
    
    switch($suffix){
    case 1:  $expName .= '-' . date("Y-m-d_H-m-s") . '.zip'; break;
    case 2:  $expName .= '-' . rand(10000,99999) . '.zip';   break;
    default: $expName .= '.zip';   break;
    }   

    $outZipPath = $rootExp . "/{$expName}";
    $outZipUrl = $rootUrl . "/{$expName}";
//echo "{$outZipPath}<br>{$outZipUrl}<br>{$fldExport}";exit;

    \JANUS\ZipReccurssiveDir($exportPath, $outZipPath);   
    chmod ($outZipPath , 0666);
    
    if(!\JANUS\FSO\deleteDirectory ($exportPath, true)){
        //$retArr['errlib'] = "Dossier source non supprimé, vérifiez les droits !";
        echo!array($retArr);
        exit;
    }

    //petite verue pour copier le zip dans le dossier du plugin si s'en est un
    $cat = $categoriesHandler->get($quiz->getVar('quiz_cat_id'));
    if($cat->getVar('cat_name') == QUIZMAKER_CAT_NAME_FOR_EXEMPLE){
        $quizName = $quiz->getVar('quiz_name');
        $h = strrpos($quizName, "_");
        $plugin = substr($quizName,$h+1);
        $clsPlugin = $pluginsHandler->getPlugin($plugin);
        $clsPlugin->copyArchiveInPluginFolder($outZipPath);
    }
        
    $retArr['href'] = $outZipUrl;
    $retArr['link'] =  $fldExport . "/{$expName}";
    $retArr['file'] =  $outZipPath;
    return $retArr;

    //    return true;
}

/* ***********************
obsolette
************************** */
public static function quiz_download_zip($outZipUrl, $name, $delai=2000){
 //exit("href = {$outZipUrl} - name = {$name} - delai = {$delai}"); 
	$GLOBALS['xoopsTpl']->assign('download', 1);        
	$GLOBALS['xoopsTpl']->assign('href', $outZipUrl);        
	$GLOBALS['xoopsTpl']->assign('delai', $delai);        
	$GLOBALS['xoopsTpl']->assign('name', $name);        
}
/**************************************************************
 * return : > 0 : n° de la causse qui empeche l'export
 * ************************************************************/
public static function verif_exportable($quizId){
    global $xoopsConfig, $quizHandler, $questionsHandler, $xoopsDB;
    
    $nbQuestions = $questionsHandler->getCountQuestionsOfQuiz($quizId);
    if ($nbQuestions == 0) return 1; //pas de question dans le quiz

    $nbQuestions = $questionsHandler->getCountQuestionsOfQuiz($quizId);
    if ($nbQuestions == 0) return 2; //pas de question dans le quiz
    
    //placer ici les autres critère qui ne permettent pas d'exporter le quiz
    //todo : verifier les permissions
    
    //i n'y a pas d'empechement retour 0
    return 0;

}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_exportToYml($quizId, $pathTemp)
{
    global $xoopsConfig, $quizHandler, $xoopsDB;
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
    
    $name = $quiz->getVar('quiz_folderJS');    
    //$pathTemp = QUIZMAKER_PATH_UPLOAD_EXPORT . "/{$name}/export/";
    //$pathTemp = QUIZMAKER_PATH_UPLOAD_EXPORT . "/{$name}/";
    if (!is_dir($pathTemp))
        mkdir($pathTemp, 0777, true);
    //----------------------------------------------------
    $criteria = new \CriteriaCompo(new \Criteria('quiz_id',$quizId,'='));
    $shortName = 'quiz';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $pathTemp . $shortName . '.yml', $criteria);
    
    //-----------------------------------------------------    
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
    $criteria->add(new \Criteria('quest_actif',true,'='));
    $shortName = 'questions';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $pathTemp . $shortName . '.yml', $criteria);
    
    //--------------------------------------------
    $questIdList = $quizHandler->getChildrenIds($quizId);
//echo "<hr>{$questIdList}";
    $shortName = 'answers';
    $tbl = 'quizmaker_' . $shortName;

    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $pathTemp . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //categorie
    $criteria = new \CriteriaCompo(new \Criteria('cat_id',$quiz->getVar('quiz_cat_id'),'='));
    $shortName = 'categories';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $pathTemp . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //copie des dossier de ressources : images, sounds, ...
    
    //copie des dossiers de ressources : images, sounds, ...
    $folderJS = $quiz->getVar('quiz_folderJS');
    $fldArr = ['images','sounds'];
    for($h = 0; $h < count($fldArr); $h++){
        $fld = $fldArr[$h];
        $pathSource = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$folderJS}/{$fld}/";
        chmod($pathTemp, 0777);
//echo "<hr>===>{$fld}<br>path source : {$pathSource}<br>path destination : {$pathTemp}{$fld}/<hr>";
        if(file_exists($pathSource)){
            $pathTo = "{$pathTemp}{$fld}";
            \JANUS\FSO\isFolder($pathTo, true, 0777);
            //\JANUS\FSO\setChmodRecursif($pathTo, 0777);
            self:: copyFolder ($pathSource, $pathTo) ;
        }
    }
    
}
 
/* ******************************************

********************************************* */
function getQuizExportArr($flag = 3, $clearAfter = true){
global $quizHandler, $sysUrlIcon16;
        // ajout de la liste des suiz esporté si il en a eu
        $criteria = new \CriteriaCompo();   
        $criteria->add(new \Criteria("quiz_flag",$flag,"="));  
        $allQuiz = $quizHandler->getAllQuiz($criteria);
//exit;   
		//$GLOBALS['xoopsTpl']->assign('exportCount', count($allQuiz)); 
                                          
            
		if (count($allQuiz) > 0) {
            $tbl = new \XoopsFormTableTray(_AM_QUIZMAKER_UPLOAD_QUIZ, '', '');
            $tbl->addGlobalTdStyle('padding:0px 5px 0px 5px;line-height:2em;');
            $tbl->setOdd('background:#DFDFDF');
            //$tbl->setEven('background:#7FE0F0');
            $tbl->setBgRowsColors('#DFDFDF', '#7FE0F0');
            //$tbl->addTdStyle(0, 'text-align:center;width:150px;');
            //$tbl->addTitleArray(['Id','Quiz','Fichiers']);
            $k = 0;
                foreach(array_keys($allQuiz) as $i) {
    			    $quizValues = $allQuiz[$i]->getValuesQuiz();
                    
                    $url = XOOPS_URL . '/'. $quizValues['flagTxt'];
                    $label = sprintf(_AM_QUIZMAKER_UPLOAD, $quizValues['name']);
                    
                    $col= 0;
                    $tbl->addXoopsLabel('', $quizValues['id'], $col, $k);
                    $tbl->addXoopsLabel('', $quizValues['name'], ++$col, $k);
                    //$tbl->addXoopsLabel('', $quizValues['flagTxt'], $col, $k);
                    
                    $img = "<img src='{$sysUrlIcon16}/download.png' title='' alt=''>" ;
                    $link = "<a href='{$url}' download>{$img}</a>";
                    $tbl->addXoopsLabel('', $link, ++$col, $k);
                    $tbl->addXoopsLabel('', $quizValues['subject'], ++$col, $k);
                    $tbl->addXoopsLabel('', $quizValues['author'], ++$col, $k);
                    $tbl->addXoopsLabel('', $quizValues['folderJS'], ++$col, $k);
                    
                    $link = "<a href='{$url}' download>{$label}</a>";
                    $tbl->addXoopsLabel('', $link, ++$col, $k);
                    
//                     $link2 = "<a href='{$url}' target='_blank'>{$label}</a>";
//                     $tbl->addXoopsLabel('', $link2, ++$col, $k);
                    //echo "===>{$quizValues['id']} - {$quizValues['name']} - {$quizValues['flagTxt']} - {$url}<br>";
                    $k++;
                }
    		  //$GLOBALS['xoopsTpl']->assign('exportList', $tbl->render());            

             if($clearAfter) self::quiz_export_clear_flags(0);
      	     return $tbl;
         }else{
            return null;
         }
}

}  //fin de la class

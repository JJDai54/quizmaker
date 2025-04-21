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
public static function quiz_export($quizId, $modeName = 0, $suffix = 0){
global $quizHandler;
//echo "<hr>quiz_export - quizId = {$quizId}<hr>";

        //suppression des images non référencées dans les réponses
        $quizHandler->purgerImages($quizId);
        
        $quiz = $quizHandler->get($quizId);
        self::quiz_exportToYml($quizId);
        
\JANUS\FSO\isFolder(QUIZMAKER_PATH_UPLOAD_EXPORT, true);  
        $name = $quiz->getVar('quiz_name');
        $folderJS = $quiz->getVar('quiz_folderJS');    
        $expName = ($modeName == 1) ? $folderJS : \JANUS\sanityseNameForFile($name);    
//echo "<hr>quiz_export->expName : {$expName}<hr>";    
        
        switch($suffix){
        case 1:  $expName .= '-' . date("Y-m-d_H-m-s"); break;
        case 2:  $expName .= '-' . rand(10000,99999);   break;
        }   
        
        $sourcePath = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$folderJS}/export/";
        $outZipPath = QUIZMAKER_PATH_UPLOAD_EXPORT . "/{$expName}.zip";
        $outZipUrl = QUIZMAKER_URL_UPLOAD_EXPORT . "/{$expName}.zip";
        
        //\JANUS\zipSimpleDir($sourcePath, $outZipPath);   
        \JANUS\ZipReccurssiveDir($sourcePath, $outZipPath);   
chmod ($outZipPath , 0666);

		$GLOBALS['xoopsTpl']->assign('download', 1);        
		$GLOBALS['xoopsTpl']->assign('href', $outZipUrl);        
		$GLOBALS['xoopsTpl']->assign('delai', 2000);        
		$GLOBALS['xoopsTpl']->assign('name', $name);        
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_exportToYml($quizId, $modeName = 0)
{
    global $xoopsConfig, $quizHandler, $xoopsDB;
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
    
    $name = $quiz->getVar('quiz_folderJS');    
    $path = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}/export/";
    if (!is_dir($path))
        mkdir($path, 0777, true);
    //----------------------------------------------------
    $criteria = new \CriteriaCompo(new \Criteria('quiz_id',$quizId,'='));
    $shortName = 'quiz';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //-----------------------------------------------------    
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
    $criteria->add(new \Criteria('quest_actif',true,'='));
    $shortName = 'questions';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //--------------------------------------------
    $questIdList = $quizHandler->getChildrenIds($quizId);
//echo "<hr>{$questIdList}";
    $shortName = 'answers';
    $tbl = 'quizmaker_' . $shortName;

    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //categorie
    $criteria = new \CriteriaCompo(new \Criteria('cat_id',$quiz->getVar('quiz_cat_id'),'='));
    $shortName = 'categories';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //copie du dossier des images
    //\JANUS\FSO\isFolder(QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}/images", true2);
    $pathSource = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}/images/";
    //\JANUS\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}/images", 0777);
    \JANUS\FSO\setChmodRecursif($path, 0777);
    self:: copyFolder ($pathSource,$path . '/images/') ;
}
 

}  //fin de la classe

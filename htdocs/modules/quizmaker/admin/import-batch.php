<?php
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

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;

//use JANUS;
/*
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";
// if(count($_GET)>0) echo "<hr>GET : <pre>" . print_r($_GET, true) . "</pre><hr>";
 if(count($_POST)>0) echo "<hr>POST : <pre>" . print_r($_POST, true) . "</pre><hr>";
 if(count($_FILES)>0) echo "<hr>FILES : <pre>" . print_r($_FILES, true) . "</pre><hr>";
*/

$toCatId = Request::getInt('to_cat_id', 0);
if($toCatId == 0) $toCatId = array_key_first($catArr);
$toQuizSet = Request::getString('to_quiz_subject', '');

////////////////////////////////////////////////////////////////////////
    switch($op){
    case 'getform':
        //----------------------------------------------- 
        //affichage des imports par lot
        //----------------------------------------------- 
        $form = new \XoopsThemeForm($title, $formName, $importMainFile, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'import'));
        $form->addElement(new \XoopsFormHidden('sender', ''));
        addXformImportType($form, $typeImportName, $typeImport);
          
        //---------------------------------------------------  
    	$form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_DEST_TO_CLONE));
        //---------------------------------------------------  
          
        // ----- Listes de selection From pour filtrage -----  
        $form->addElement(new XoopsFormLabel('',_AM_QUIZMAKER_BATCH_IMPORT_DESC));
            
        $allQuiz2Import = XoopsLists::getFileListByExtension(QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH,  array('zip'), '');        
//echo QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH . "<br>";
//echoArray($allQuiz2Import,'liste des zip');
        if(count($allQuiz2Import) > 0){
          // ----- Listes de selection To pour filtrage -----  
          addXformCat  ($form, 'to_cat_id',   $toCatId, true);
          addXformSet  ($form, 'to_quiz_subject', $toQuizSet, $toCatId, true);

          //---------------------------------------------------  
    	  $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_QUIZ_TO_IMPORT));
          //---------------------------------------------------  

          $inpCheckbox = new \XoopsFormCheckboxAll('Import', 'zipFiles', 1, '<br>');
          $inpCheckbox->addOptionCheckAll('Tous les fichiers',-1);
          $inpCheckbox->setColorCheckAll('red');
          foreach($allQuiz2Import as $key=>$quiz){   
            $inpCheckbox->addOption($key, $key);
            $inpFullName = new XoopsFormHidden("fullName[{$key}]", QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH . "/" . $quiz);
            $form->addElement($inpFullName);
          }
          $form->addElement($inpCheckbox);


          //-------------------------------------------------------------------
          addXformBtnSubmit($form);
   
        }
		$GLOBALS['xoopsTpl']->assign('form', $form->render()); 

//echoArray($ret, 'getPluginPath');   
        break;
    case 'confirm':
        break;
        
    case 'import':
        $filesOk = Request::getArray('zipFiles');
        $allFiles = Request::getArray('fullName');
        
        foreach($filesOk as $key=>$name){
            echo $allFiles[$name] . "<br>";
            $fullName = $allFiles[$name];
          //dossier provisoir pour decompresser l'archive
          $newFldImport = "/files_new_quiz" ;  
          $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
          chmod($fullName, 0666);
          chmod($pathImport, 0777);
          \JANUS\unZipFile($fullName, $pathImport);
          \JANUS\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_IMPORT, 0777);
          $ret = $quizUtility::quiz_importFromYml($pathImport, $catId, $newQuizId);
          //sleep(int $seconds)      
          //exit;
          $quizUtility::buildQuiz($newQuizId);
        }
        
        redirect_header("quiz.php", 3, sprintf(_AM_QUIZMAKER_IMPORT_QUIZ_OK, count($filesOk)));
       
        break;
    default : break;
    }



     
    


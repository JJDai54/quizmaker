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


$toCatId = Request::getInt('to_cat_id', 0);
if($toCatId == 0) $toCatId = array_key_first($catArr);
$toQuizSet = Request::getString('to_quiz_subject', '');
$toQuizId = Request::getInt('to_quiz_id', 0);

    switch($op){
    case 'getform':
        if(!isset($errors)) {
          if($objError->getErrors())
              $errors = $objError->getHtmlErrors();
          else
              $errors = '';
        }
        
      $GLOBALS['xoopsTpl']->assign('error', $errors);
      $objError = new \XoopsObject();     
      //----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('buttons', '');        
    
        //$quizUtility::deleteTree($pathImport);                      
        //$quizUtility::rmAllDir($pathImport);     exit;  
        $quizUtility::deleteDirectory(QUIZMAKER_PATH_UPLOAD_IMPORT . "/files_new_quiz");                      
        $quizUtility::createFolder(QUIZMAKER_PATH_UPLOAD_IMPORT . "/files_new_quiz");                      
        $quizUtility::createFolder(QUIZMAKER_PATH_UPLOAD_IMPORT . "/files_new_quiz/images");                      
        
        $utility = new FQuizmaker\Utility();
        //$utility::rrmdir($pathImport . '/images');
        $utility::clearFolder($pathImport . '/images');
        $utility::clearFolder($pathImport );
    
        /** @var Quizmaker\Utility $utility */
    


               
//		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
// 		if (false === $action) {
// 			$action = $_SERVER['REQUEST_URI'];
// 		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		
        
        // Title
		$title = _AM_QUIZMAKER_IMPORT;        
		// Get Theme Form
		//xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, $formName, $importMainFile, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'import'));
        $form->addElement(new \XoopsFormHidden('sender', ''));
        addXformImportType($form, $typeImportName, $typeImport);
        
        // ----- Listes de selection From pour filtrage -----  
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_DEST_TO_CLONE));
  	    $form->addElement(new XoopsFormLabel(_AM_QUIZMAKER_IMPORT_QUEST_CAUTION1,_AM_QUIZMAKER_IMPORT_QUEST_CAUTION2));
        
        
        addXformCat  ($form, 'to_cat_id',   $toCatId, true);
        addXformSet  ($form, 'to_quiz_subject', $toQuizSet, $toCatId, true);
        addXformQuiz ($form, 'to_quiz_id',  $toQuizId, $toCatId, $toQuizSet, $addEvent = false);
        
        //-------------------------------------------------------        
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_IMPORT_SELECT_PLUGINS));
        //-------------------------------------------------------        

        //Liste des types de question
        $inpCheckbox = new \XoopsFormCheckboxAll(_CO_QUIZMAKER_PLUGIN, 'plugins_selected', 1, '<br>');
        $inpCheckbox->addOptionChecboxkAll('all_plugins_selected', 'Tous les types de question', -1);
        $inpCheckbox->setColorCheckAll('red');
        $inpCheckbox->addOptionArray($pluginsHandler->getListKeyName(null, true));    
        $form->addElement($inpCheckbox);

        //-------------------------------------------------------        
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_IMPORT_SELECT_QUIZ_ARCHIVE));
        //-------------------------------------------------------        
        $uploadTray = new \XoopsFormFile(_AM_QUIZMAKER_FILE_TO_LOAD, 'quizmaker_files', $upload_size);     
        $uploadTray->setDescription(_AM_QUIZMAKER_FILE_DESC . '<br>' . sprintf(_AM_QUIZMAKER_FILE_UPLOADSIZE . " ($upload_size)", intval($upload_size / 1024)), '<br>');
        $form->addElement($uploadTray, true);

        //-------------------------------------------------------------------
        addXformBtnSubmit($form);
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
        break;
    
    case 'confirm':
        break;
        
    case 'import':
       //echoGPF("GPF","quest_import",true);
        if (loadFile2Import()){
            $pluginNameSelected = Request::getArray('plugins_selected',null);

            $newQuizId = $quizUtility::quiz_importOnlyQuestFromYml($pathImport, $toQuizId, $pluginNameSelected, array('pageBegin','pageEnd'));                      

            $msg = sprintf(_AM_QUIZMAKER_IMPORT_OK,$newQuizId);
            $url = "questions.php?op=list&quiz_id={$newQuizId}&sender=&libErr={$msg}";
        }else{
            //echo "<hr>03-Errors upload : {$uploaderErrors}<hr>";
            $msg = sprintf(_AM_QUIZMAKER_IMPORT_ERROR_01, $upload_size/1000 . "ko");
            $url = "import.php?op=error&numerr=1";
        }
//  exit("{$msg}<br>{$url}");
            redirect_header($url, 5, $msg);
        break;
    default : break;
    }



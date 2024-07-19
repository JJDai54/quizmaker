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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;


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
    


               
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
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
		$form = new \XoopsThemeForm($title, 'form_import', 'import.php', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'quest_import'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

  	    $form->addElement(new XoopsFormLabel(_AM_QUIZMAKER_IMPORT_QUEST_CAUTION1,_AM_QUIZMAKER_IMPORT_QUEST_CAUTION2));
        
        // ----- Listes de selection pour filtrage -----  
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        //$inpCategory->addOption(0, _AM_QUIZMAKER_SELECT_CATEGORY_ORG);
        $inpCategory->addOptionArray($catArr);
        //$inpCategory->setDescription(_AM_QUIZMAKER_SELECT_CATEGORY_DESC);
        $inpCategory->setExtra("onchange='quizmaker_reload_import_quest(event,\"{$typeImport}\");'".FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $form->addElement($inpCategory);

        $quizArr = $quizHandler->getListKeyName($catId);
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_TO, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setDescription(_AM_QUIZMAKER_QUIZ_TO_DESC);
        $inpQuiz->setExtra( FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $form->addElement($inpQuiz);

        //Liste des types de question
        $inpCheckbox = new \XoopsFormCheckboxAll(_CO_QUIZMAKER_PLUGIN, 'plugins_selected', 1, '<br>');
        $inpCheckbox->addOptionChecboxkAll('all_plugins_selected', 'Tous les types de question', -1);
        $inpCheckbox->setColorCheckAll('red');
        $inpCheckbox->addOptionArray($pluginsHandler->getListKeyName(null, true));    
        $form->addElement($inpCheckbox);

        $uploadTray = new \XoopsFormFile(_AM_QUIZMAKER_FILE_TO_LOAD, 'quizmaker_files', $upload_size);     
        $uploadTray->setDescription(_AM_QUIZMAKER_FILE_DESC . '<br>' . sprintf(_AM_QUIZMAKER_FILE_UPLOADSIZE . " ($upload_size)", intval($upload_size / 1024)), '<br>');
        $form->addElement($uploadTray, true);







        //----------------------------------------------- 
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_IMPORTER, 'submit'));
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        


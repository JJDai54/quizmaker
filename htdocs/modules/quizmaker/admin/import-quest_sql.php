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

$fromCatId = Request::getInt('from_cat_id', 0);
if($fromCatId == 0) $fromCatId = array_key_first($catArr);
$fromQuizSet = Request::getString('from_quiz_subject', '');
$fromQuizId = Request::getInt('from_quiz_id', 0);
$fromOrderBy = Request::getString('from_order_by', 'quest_question');

$toCatId = Request::getInt('to_cat_id', 0);
if($toCatId == 0) $toCatId = array_key_first($catArr);
$toQuizSet = Request::getString('to_quiz_subject', '');
$toGroup = Request::getString('to_group');
        
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
  	    $form->addElement(new XoopsFormLabel(_AM_QUIZMAKER_IMPORT_QUEST_CAUTION1,_AM_QUIZMAKER_IMPORT_QUEST_CAUTION2));
        

        //-----------------Quiz source ------------------------------

        //$form->insertBreak("<div style='background:red;color:white;'><center>" . _AM_QUIZMAKER_SELECT_QUIZ_FROM . "</center></div>");
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_QUIZ_FROM));
        
        addXformCat  ($form, 'from_cat_id',   $fromCatId, true);
        addXformSet  ($form, 'from_quiz_subject', $fromQuizSet, $fromCatId, true);
        addXformQuiz ($form, 'from_quiz_id',  $fromQuizId, $fromCatId, $fromQuizSet, true);
        
        addXformQestOrderBy($form, 'from_order_by', $fromOrderBy, true);        
        
        $criteria = new CriteriaCompo(new Criteria('quest_quiz_id',  $fromQuizId,'='));
        $criteria->add(new Criteria('quest_plugin',  'pageBegin','<>'));
        $criteria->add(new Criteria('quest_plugin',  'pageEnd','<>'));
        $criteria->setSort("{$fromOrderBy},quest_id");
        $criteria->setOrder('ASC');
        //$allQuestions = $questionsHandler->getAllQuestionsArr($criteria,array('quest_plugin','quest_question'));
        
        switch($fromOrderBy){
            case 'quest_weight': 
                $lib = '<span style="color:red;">%3$s</span> <span style="color:%4$s;">%2$s</span> [<span style="color:blue;">#%1$s</span>]';

                break;
            case 'quest_question': 
                $lib = '<span style="color:%4$s;">%2$s</span> [<span style="color:blue;">#%1$s</span>] <span style="color:red;">%3$s</span>';
                break;
            case 'quest_id': 
                $lib = '[<span style="color:blue;">#%1$s</span>] <span style="color:%4$s;">%2$s</span> <span style="color:red;">%3$s</span>';
                break;

            
            default;
            case 'quest_plugin': 
                $lib = '<span style="color:red;">%3$s</span> <span style="color:%4$s;">%2$s</span> [<span style="color:blue;">#%1$s</span>]';
                break;
        }
                //$lib = "[<span style='color:blue;'>#%1$s</span>] <span style='color:red;'>%3$s</span> : <span style='color:%4$s;'>%2$s'</span>'";
        $allQuestions = $questionsHandler->getAll($criteria,array('quest_plugin','quest_question'),false,true);
        
        $options = array();
        $sep1 = '';
        foreach($allQuestions AS $key=>$arr){
            $colorQuest = ($arr['quest_plugin'] == 'pageGroup') ? 'blue': 'black';
            
            $strId = str_pad($key,5,'0',STR_PAD_LEFT);
            //$options[$key] = "<span style='color:{$colorQuest};'>{$arr['quest_question']}'</span>' [<span style='color:blue;'>#{$key}</span>] <span style='color:red;'>{$arr['quest_plugin']}</span>";
            $options[$key] = "[<span style='color:blue;'>#{$strId}</span>] <span style='color:red;'>{$arr['quest_plugin']}</span> : <span style='color:{$colorQuest};'>{$arr['quest_question']}'</span>'  ";
            $options[$key] = sprintf($lib, $strId, $arr['quest_question'], $arr['quest_plugin'], $colorQuest);
        }

        if($fromQuizId){
        //Liste des questions
        $values = Request::getArray('questions_selected');
        $inpQuestions = new \XoopsFormCheckboxAll(_CO_QUIZMAKER_QUESTIONS, 'questions_selected', $values, '<br>');
        $inpQuestions->addOptionArray($options);    
        $idCheckAllQuestions = $inpQuestions->addOptionChecboxkAll('all_questions_selected', 'Toutes les questions', -1, false);
        $inpQuestions->setColorCheckAll('red');
        $form->addElement($inpQuestions);
        //echo "<hr>idCheckAllPlugin = {$idCheckAllPlugin}<hr>";
        $form->addElement(new xoopsFormHidden('select_checkboxAllQuestionsId', $idCheckAllQuestions));
        }
        
       
        //-----------------Quiz de destination------------------------------
        //$form->insertBreak("<div style='background:red;color:white;'><center>" . _AM_QUIZMAKER_SELECT_QUIZ_DEST . "</center></div>");
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_QUIZ_DEST));
        
        // ----- Listes de selection pour filtrage -----  
        addXformCat  ($form, 'to_cat_id',   $toCatId, true);
        addXformSet  ($form, 'to_quiz_subject', $toQuizSet, $toCatId, true);
        addXformQuiz ($form, 'to_quiz_id',  $toQuizId, $toCatId, $toQuizSet, false);
        
        $inpGroupTo = new \XoopsFormText(_AM_QUIZMAKER_GROUP_TO,'to_group',50,50,$groupTo);
        $inpGroupTo->setDescription(_AM_QUIZMAKER_GROUP_TO_DESC);
        $inpGroupTo->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_GROUP));
  	    $form->addElement($inpGroupTo);
        
        //----------------------------------------------- 
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_IMPORTER, 'submit'));
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
        break;
        
    case 'confirm':
        break;
        
    case 'import':
//echoGPF();

        //$quest_Ids = explode(",","5182,5183,5184,5185");
        $quest_Ids = Request::getArray('questions_selected');

        //$orderBy = Request::getInt('select_order_by');
        $quizUtility->quiz_import_sql($quest_Ids, $fromQuizId, $toQuizId,$toGroup);


        $url = "questions.php?op=list&quiz_id={$quizIdTo}";
        $msg = "Importation ok!";
        redirect_header($url, 5, $msg);
        break;
    default : break;
    }



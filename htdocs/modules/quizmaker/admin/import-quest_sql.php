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
		$form->addElement(new \XoopsFormHidden('type_import', 'quest_sql'));
		$form->addElement(new \XoopsFormHidden('op', 'import'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

  	    $form->addElement(new XoopsFormLabel(_AM_QUIZMAKER_IMPORT_QUEST_CAUTION1,_AM_QUIZMAKER_IMPORT_QUEST_CAUTION2));
        
        //-----------------Quiz source ------------------------------

        $form->insertBreak("<div style='background:red;color:white;'><center>" . _AM_QUIZMAKER_SELECT_QUIZ_FROM . "</center></div>");
        
        // ----- Listes de selection pour filtrage -----  
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, 'select_from_cat_id', $fromCatId);
        //$inpCategory->addOption(0, _AM_QUIZMAKER_SELECT_CATEGORY_ORG);
        $inpCategory->addOptionArray($catArr);
        //$inpCategory->setDescription(_AM_QUIZMAKER_SELECT_CATEGORY_DESC);
        $inpCategory->setExtra("onchange='quizmaker_reload_import(event);'" . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $form->addElement($inpCategory);

        $quizArr = $quizHandler->getListKeyName($fromCatId);
        if ($fromQuizId == 0) {
            $fromQuizId = array_key_first($quizArr);
        }
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_FROM, 'select_from_quiz_id', $fromQuizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setDescription(_AM_QUIZMAKER_QUIZ_FROM_DESC);
        $inpQuiz->setExtra("onchange='quizmaker_reload_import(event);'" . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $form->addElement($inpQuiz);
        
        if(!$orderBy) $orderBy = 'quest_plugin';
        $inpOrderBy = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_ORDER_BY, 'select_order_by', $orderBy);
        $inpOrderBy->addOptionArray(['quest_plugin'=> _AM_QUIZMAKER_QUIZ_ORDER_BY_PLUGINS,
                                     'quest_weight'       => _AM_QUIZMAKER_QUIZ_ORDER_BY_WEIGHT,
                                     'quest_question'     => _AM_QUIZMAKER_QUIZ_ORDER_BY_QUESTION,
                                     'quest_id'           => _AM_QUIZMAKER_QUIZ_ORDER_BY_ID]);

        $inpOrderBy->setExtra("onchange='quizmaker_reload_import(event);' " . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $form->addElement($inpOrderBy);
        
        /* a garder - un jour peut-etre filtrage sur type de question
        if($fromQuizId){
        //Liste des types de question
        $inpCheckbox = new \XoopsFormCheckboxAll(_CO_QUIZMAKER_PLUGIN, 'plugins_selected', 1, '<br>');
        $inpCheckbox->addOptionArray($questionsHandler->getPluginOf($fromQuizId));    
        $idCheckAllPlugin = $inpCheckbox->addOptionChecboxkAll('all_plugins_selected', 'Tous les types de question', -1, true);
        $inpCheckbox->setColorCheckAll('red');
        $form->addElement($inpCheckbox);
        //echo "<hr>idCheckAllPlugin = {$idCheckAllPlugin}<hr>";
        $form->addElement(new xoopsFormHidden('select_checkboxAllPluginId', $idCheckAllPlugin));
        }
        */
        
        $criteria = new CriteriaCompo(new Criteria('quest_quiz_id',  $fromQuizId,'='));
        $criteria->add(new Criteria('quest_plugin',  'pageBegin','<>'));
        $criteria->add(new Criteria('quest_plugin',  'pageEnd','<>'));
        $criteria->setSort("{$orderBy},quest_id");
        $criteria->setOrder('ASC');
        //$allQuestions = $questionsHandler->getAllQuestionsArr($criteria,array('quest_plugin','quest_question'));
        
        switch($orderBy){
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
        define('_CO_QUIZMAKER_QUESTIONS',"Questions");
        $inpQuestions = new \XoopsFormCheckboxAll(_CO_QUIZMAKER_QUESTIONS, 'questions_selected', 1, '<br>');
        $inpQuestions->addOptionArray($options);    
        $idCheckAllQuestions = $inpQuestions->addOptionChecboxkAll('all_questions_selected', 'Toutes les questions', -1, false);
        $inpQuestions->setColorCheckAll('red');
        $form->addElement($inpQuestions);
        //echo "<hr>idCheckAllPlugin = {$idCheckAllPlugin}<hr>";
        $form->addElement(new xoopsFormHidden('select_checkboxAllQuestionsId', $idCheckAllQuestions));
        }
        
       
        //-----------------Quiz de destination------------------------------
        $form->insertBreak("<div style='background:red;color:white;'><center>" . _AM_QUIZMAKER_SELECT_QUIZ_DEST . "</center></div>");
        // ----- Listes de selection pour filtrage -----  
        $inpToCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, 'select_to_cat_id', $toCatId);
        //$inpToCategory->addOption(0, _AM_QUIZMAKER_SELECT_CATEGORY_ORG);
        $inpToCategory->addOptionArray($catArr);
        //$inpToCategory->setDescription(_AM_QUIZMAKER_SELECT_CATEGORY_DESC);
        $inpToCategory->setExtra("onchange='quizmaker_reload_import(event);'".FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $form->addElement($inpToCategory);

/*
*/                  
        $quizArr = $quizHandler->getListKeyName($toCatId);
        if ($toCatId == 0) {
            $toCatId = array_key_first($quizArr);
        }
        $inpToQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_TO, 'select_to_quiz_id', $toQuizId);
        $inpToQuiz->addOptionArray($quizArr);
        $inpToQuiz->setDescription(_AM_QUIZMAKER_QUIZ_TO_DESC);
        $inpToQuiz->setExtra( FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $form->addElement($inpToQuiz);

        $inpGroupTo = new \XoopsFormText(_AM_QUIZMAKER_GROUP_TO,'select_group_to',50,50,$groupTo);
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
        $quizIdTo = Request::getInt('select_to_quiz_id');
        $quizIdFrom = Request::getInt('select_from_quiz_id');
        $groupTo = Request::getString('select_group_to');
        //$orderBy = Request::getInt('select_order_by');
        $quizUtility->quiz_import_sql($quest_Ids, $quizIdFrom, $quizIdTo,$groupTo);


        //$url = "import.php?op=list&type_import={$typeImport}";
        $url = "questions.php?op=list&quiz_id={$quizIdTo}";
        $msg = "Importation ok!";
        redirect_header($url, 5, $msg);
        break;
    default : break;
    }



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
 * QuizMaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use Xmf\Request;
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quest_id

$sender  = Request::getString('sender', '');
$catId   = Request::getInt('cat_id', 0);
if ($sender == 'cat_id') {
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
  $quizId  = Request::getInt('quiz_id', 1);
}


$questId = Request::getInt('quest_id', 0);
$quest_type_question = Request::getString('quest_type_question', '');

// $gp = array_merge($_GET, $_POST);
// echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

function getParams2list($quizId, $quest_type_question, $sender = ""){
global $quizHandler;
    $catId = $quizHandler->getParentId($quizId);
    return $params = "sender={$sender}&cat_id={$catId}&quiz_id={$quizId}&quest_type_question={$quest_type_question}";
}

//////////////////////////////////////////////
switch($op) {
	case 'list':
	default:
		$templateMain = 'quizmaker_admin_questions.tpl';
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
        
        
        //----------------------------------------------
        //recupe du quiz a afficher
        $quiz = $quizHandler->get($quizId);
        if ($quiz) {
            $quizValues = $quiz->getValuesQuiz();
            $catId = $quiz->getVar('quiz_cat_id');
        }
        
        // ----- Listes de selection pour filtrage -----  
        $catArr = $categoriesHandler->getListKeyName(null, false, false);
        if ($catId == 0) $catId = array_key_first($catArr);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        
        $quizArr = $quizHandler->getListKeyName($catId);
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
       // ----- /Listes de selection pour filtrage -----        

        //---------------------------------------------        
        //Ajout d'une question selon le type de selectTypeQuestion
        $btn = array();
        $inpTypeQuest = new \XoopsFormSelect(_CO_QUIZMAKER_TYPE_QUESTION, 'quest_type_question', $quest_type_question);
        $inpTypeQuest->addOptionArray($type_questionHandler->getListKeyName());
        $GLOBALS['xoopsTpl']->assign('inpTypeQuest', $inpTypeQuest->render());
        
        $btnNewQuestion = $quizUtility->getNewBtn('<=== ' . _ADD, 'new_question', QUIZMAKER_ICONS_URL."/16/add.png",  _AM_QUIZMAKER_SELECT_TYPE_BEFORE_ADD);
		$GLOBALS['xoopsTpl']->assign('btnNewQuestion', $btnNewQuestion);
        

        
        
        //---------------------------------------------       
        //update weight 
        $initWeight = $quizUtility->getNewBtn(_AM_QUIZMAKER_COMPUTE_WEIGHT, 'init_weight', QUIZMAKER_ICONS_URL."/16/generer-1.png",  _AM_QUIZMAKER_COMPUTE_WEIGHT);
		$GLOBALS['xoopsTpl']->assign('initWeight', $initWeight);
        //---------------------------------------------      
        //export jSon 
        $expQuiz = $quizUtility->getNewBtn(_AM_QUIZMAKER_BUILD_QUIZ, 'export_json', QUIZMAKER_ICONS_URL."/16/download.png",  _AM_QUIZMAKER_BUILD_QUIZ);
		$GLOBALS['xoopsTpl']->assign('expQuiz', $expQuiz);
        //---------------------------------------------        
        //test du quiz
        if($quiz){
        if($quizValues["quiz_html"] != '' ){
            $imgTest = new XoopsFormImg(_AM_QUIZMAKER_TEST_QUIZ . ' : ' . $quizValues['build'], QUIZMAKER_ICONS_URL . "/32/quiz-1.png", $quizValues["quiz_html"] );
        }else{
            $imgTest = new XoopsFormImg(_AM_QUIZMAKER_TEST_QUIZ . ' : ' . $quizValues['build'], QUIZMAKER_ICONS_URL . "/32/quiz-0.png");
        } 
        $imgTest->setExtra("target='blank'");
        $btn['imgTest'] = $imgTest->render();
        }
        
        
        $btn['exportQuiz'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_EXPORT_YML, 'export_quiz', QUIZMAKER_ICONS_URL."/16/add.png",  _AM_QUIZMAKER_EXPORT_QUIZ_YML);
        $btn['importQuiz'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_IMPORT_YML, 'import_quiz', QUIZMAKER_ICONS_URL."/16/add.png",  _AM_QUIZMAKER_IMPORT_QUIZ_YML);
        $btn['restorQuiz'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_RESTOR_YML, 'restor_quiz', QUIZMAKER_ICONS_URL."/16/add.png",  _AM_QUIZMAKER_RESTOR_QUIZ_YML);

        //---------------------------------------------------
		$GLOBALS['xoopsTpl']->assign('btn', $btn);
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        //---------------------------------------------------
 //echo "<hr>catid = {$catId} - quizId = {$quizId}<hr>";       
    
        //================================================
        // recupe des infos du quiz
        if (!$quiz) break;
        //---------------------------------------
        
		
        /* 
        $adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        */
        if ($quizId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('quest_quiz_id',$quizId, "="));
          $questionsCount = $questionsHandler->getCountQuestions($criteria);
          $questionsAll = $questionsHandler->getAllQuestions($criteria, $start, $limit, 'quest_weight ASC, quest_question');
        }else{
          $questionsCount = 0;
          $questionsAll = null;
        }
		$GLOBALS['xoopsTpl']->assign('questions_count', $questionsCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
        

        
        
		// Table view questions
		if ($questionsCount > 0) {
			foreach(array_keys($questionsAll) as $i) {
				$Questions = $questionsAll[$i]->getValuesQuestions();
				$GLOBALS['xoopsTpl']->append('questions_list', $Questions);
				unset($Questions);
			}
			// Display Navigation
			if ($questionsCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($questionsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_QUESTIONS);
		}

	break;
    
	case 'addingroup':
        
	case 'new_question':
//         $quizId = Request::getInt('quiz_id', 0);
//         echo "===> {$op} - questionId = {$quizId} - typeQuestion = {$typeQuestion}";

	case 'new':
		$templateMain = 'quizmaker_admin_questions.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$questionsObj = $questionsHandler->create();
        if ($op == 'new_question'){
            $typeQuestion = Request::getString('quest_type_question', "");

            $questionsObj->setVar('quest_quiz_id', $quizId);
            $questionsObj->setVar('quest_type_question', $typeQuestion);
            $questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
            $questionsObj->setVar('quest_visible', 1);
            $questionsObj->setVar('quest_actif', 1);
            $questionsObj->setVar('quest_parent_id', Request::getInt('quest_parent_id', 0));
/*
            $lanquage = $xoopsConfig['language'];
            //$f = XOOPS_ROOT_PATH . "/modules/quizmaker/language/{$lanquage}/slide/slide_resultats.html";
            $f = QUIZMAKER_PATH . "/language/{$lanquage}/slide/slide_resultats.html";
            $slideresultats = $quizUtility->loadTextFile($f);
echo "<hr>{$f}<hr>{$slideresultats}<hr>";    
*/            
        
        }else if($op == 'addingroup'){
            $typeQuestion = Request::getString('quest_type_question', "");
        }
        
		//$form = $questionsObj->getFormQuestions(false, $quizId, $typeQuestion);
		$form = $questionsObj->getFormQuestions(false, $sender);
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
    
	case 'edit':
		$templateMain = 'quizmaker_admin_questions.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$questionsObj = $questionsHandler->get($questId);
		$form = $questionsObj->getFormQuestions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'clone':
		$templateMain = 'quizmaker_admin_questions.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$questionsObj = $questionsHandler->get($questId);
        //$questionsObj->setVar('quest_id', 0);
        $questionsObj->setNew();
        
		//$questionsObj = $questionsHandler->create();
		$form = $questionsObj->getFormQuestions(true);
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
    
	case 'save':
//    echo "<hr>POST<pre>" . print_r($_POST, true) . "</pre><hr>";
//echo "<hr>questId ===>zzz " . $questId . "<br>"; 
		// Security Check

// 		if (!$GLOBALS['xoopsSecurity']->check()) {
// 			redirect_header('questions.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
// 		}
		if ($questId > 0) {
			$questionsObj = $questionsHandler->get($questId);
		} else {
			$questionsObj = $questionsHandler->create();
    		$questionsObj->setVar('quest_creation', \JJD\getSqlDate());
		}
		// Set Vars
        $typeQuestion = Request::getString('quest_type_question', '');
        $quizId = Request::getInt('quest_quiz_id', 0);
		$questionsObj->setVar('quest_parent_id', Request::getInt('quest_parent_id', 0));
		$questionsObj->setVar('quest_quiz_id', $quizId);
		$questionsObj->setVar('quest_question', Request::getString('quest_question', ''));
		$questionsObj->setVar('quest_options', Request::getString('quest_options', ''));
		$questionsObj->setVar('quest_comment1', Request::getText('quest_comment1', ''));
		$questionsObj->setVar('quest_explanation', Request::getText('quest_explanation', ''));
		$questionsObj->setVar('quest_type_question', $typeQuestion);
		$questionsObj->setVar('quest_type_form', Request::getInt('quest_type_form', QUIZMAKER_TYPE_FORM_NONE));
		$questionsObj->setVar('quest_minReponse', Request::getInt('quest_minReponse', 0));
		$questionsObj->setVar('quest_numbering', Request::getInt('quest_numbering', 0));
		$questionsObj->setVar('quest_shuffleAnswers', Request::getInt('quest_shuffleAnswers', 1));
		$questionsObj->setVar('quest_weight', Request::getInt('quest_weight', 0));
		$questionsObj->setVar('quest_timer', Request::getInt('quest_timer', 0));
//		$questionsObj->setVar('quest_isQuestion', Request::getInt('quest_isQuestion', 0));
		$questionsObj->setVar('quest_visible', Request::getInt('quest_visible', 1));
		$questionsObj->setVar('quest_actif', Request::getInt('quest_actif', 1));
		$questionsObj->setVar('quest_update', \JJD\getSqlDate());

		// Insert Data
		if ($questionsHandler->insert($questionsObj)) {
            $questId = $questionsObj->getVar(quest_id);
//echo "<hr>questId ===> " . $questId ; exit;
            //$cls = $utility::getClassTypeQuestion($typeQuestion);
            $cls = $type_questionHandler->getClassTypeQuestion($typeQuestion);
            $cls->saveAnswers($questId, Request::getArray('answers', []));
//echo "<hr>" .  getParams2list($quizId, $quest_type_question); exit;

          if ($sender == 'type_question_list')
			redirect_header('type_question.php?op=list&' . getParams2list($quizId, $quest_type_question, $sender), 2, _AM_QUIZMAKER_FORM_OK);
          else
			redirect_header('questions.php?op=list&' . getParams2list($quizId, $quest_type_question, ""), 2, _AM_QUIZMAKER_FORM_OK);
            
		}
//    exit;
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
		$form = $questionsObj->getFormQuestions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
    
	case 'delete':
		$questionsObj = $questionsHandler->get($questId);
		$questQuiz_id = $questionsObj->getVar('quest_quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_type_question), 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($questionsHandler->delete($questionsObj)) {
				redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_type_question), 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(['ok' => 1, 'quest_id' => $questId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $questionsObj->getVar('quest_quiz_id')));
		}
	break;

    case 'init_weight':
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_type_question)."#question-{$questId}";
        \redirect_header($url, 0, "");
	break;
    
    case 'weight':
        $action = Request::getString('sens', "down") ;
        $questionsHandler->updateWeight($questId, $action);
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_type_question)."#question-{$questId}";
        //echo "<hr>{$url}<hr>";exit;
        \redirect_header($url, 0, "");
        break;

	case 'export_json':
        $quizUtility::build_quiz($quizId);
        //$utility::export_questions2Jason($quizId);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_type_question), 5, "Export effectue");
		//redirect_header('questions.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
	break;
        
	case 'change_etat':
        $field = Request::getString('field');
        $doItForGroup = ($field == 'quest_actif') ? true : false;
        $questionsHandler->changeEtat($questId, $field, $doItForGroup);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}#question-{$questId}", 5, "Etat de {$field} Changé");
	break;
    //------------------------------------------------------
	case 'export_quiz':
        //$quizUtility->saveData($quizId);
        $quizUtility->saveDataKeepId($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
	break;
    
	case 'restor_quiz':
        //$quizUtility->loadData($quizId);
        $quizUtility->loadData($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
	break;
	case 'import_quiz':
        //$quizUtility->loadData($quizId);
        $quizUtility->loadAsNewData($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
	break;

    } // fin du switch maitre
    
require __DIR__ . '/footer.php';

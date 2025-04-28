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

require __DIR__ . '/header.php';
$clPerms->addPermissions($criteriaCatAllowed, 'edit_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$catId  = Request::getInt('cat_id', array_key_first($catArr));



// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request answer_id

// echo "<hr><pre>" . print_r($_POST, true) . "</pre><hr>";

$sender  = Request::getString('sender', '');
$catId   = Request::getInt('cat_id', 1);

if($sender == 'cat_id'){
    $quizId  = $quizHandler->getFirstIdOfParent($catId);
    $questId = $questionsHandler->getFirstIdOfParent($quizId);
}else if ($sender == 'quiz_id'){
    $quizId  = Request::getInt('quiz_id', 1);
    $questId = $questionsHandler->getFirstIdOfParent($quizId);
}else{
    $quizId  = Request::getInt('quiz_id', 1);
    $questId = Request::getInt('quest_id', -1);
    //exit("zzzz");
}

$answerId = Request::getInt('answer_id');
// echo "op = {$op}<br>sender = {$sender}<br>catId = {$catId}<br>quizId = {$quizId}<br>questId = {$questId}<br>";
// echo "<hr><pre>POST : " . print_r($_POST, true) . "</pre><hr>";
// echo "<hr><pre>GET : " . print_r($_GET, true) . "</pre><hr>";

/* ******************************************************
*
* ******************************************************* */
function getParams2list($questId){
global $quizHandler, $questionsHandler;
    $quizId = $questionsHandler->getParentId($questId);
    $catId  = $quizHandler->getParentId($quizId);
    $params = "&sender=&cat_id={$catId}&quiz_id={$quizId}&quest_id={$questId}";
    //echo "<hr>params = {$params}<hr>"; exit;
    return $params;
}

switch($op) {
	case 'list':
	default:
        $GLOBALS['xoopsTpl']->assign('buttons', '');    
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_answers.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
		/*
        $adminObject->addItemButton(_AM_QUIZMAKER_ADD_ANSWERS, 'answers.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        */
        
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('answer_quest_id', $questId, "="));
		$answersCount = $answersHandler->getCountAnswers($criteria);
		$answersAll = $answersHandler->getAllAnswers($criteria, $start, $limit, 'answer_weight, answer_proposition');
		$GLOBALS['xoopsTpl']->assign('answers_count', $answersCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);

        // ----- Listes de selection pour filtrage -----  
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra(QUIZMAKER_SELECT_ONCHANGE . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT) );
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());

        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
        $inpQuiz->setExtra(QUIZMAKER_SELECT_ONCHANGE . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
        
        $inpQuest = new \XoopsFormSelect(_AM_QUIZMAKER_QUESTION, 'quest_id', $questId);
        $inpQuest->addOptionArray($questionsHandler->getListKeyName($quizId));
        $inpQuest->setExtra(QUIZMAKER_SELECT_ONCHANGE . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUEST));
  	    $GLOBALS['xoopsTpl']->assign('inpQuest', $inpQuest->render());
        
        $inpNextQuestion = new XoopsFormButton('', 'btnNextQuestion', _AM_QUIZMAKER_NEXT_QUESTION);
        $inpNextQuestion->setExtra("onclick='showNexQuestion(1);'");
  	    $GLOBALS['xoopsTpl']->assign('inpNextQuestion', $inpNextQuestion->render());
        
        $inpNextQuestion = new XoopsFormButton('', 'btnPrevioustQuestion', _AM_QUIZMAKER_PREVIOUS_QUESTION);
        $inpNextQuestion->setExtra("onclick='showNexQuestion(-1);'");
  	    $GLOBALS['xoopsTpl']->assign('inpPreviousQuestion', $inpNextQuestion->render());
        

       // ----- /Listes de selection pour filtrage -----
/*
        $btnNewAnswer = $quizUtility->getNewBtn(_AM_QUIZMAKER_ADD_NEW_ANSWER, 'new_answer', QUIZMAKER_URL_ICONS."/16/add.png",  _AM_QUIZMAKER_ADD_NEW_ANSWER);
		$GLOBALS['xoopsTpl']->assign('btnNewAnswer', $btnNewAnswer);
        //---------------------------------------------        
        //update weight 
        $initWeight = $quizUtility->getNewBtn(_AM_QUIZMAKER_COMPUTE_WEIGHT, 'init_weight', QUIZMAKER_URL_ICONS."/16/generer-1.png",  _AM_QUIZMAKER_COMPUTE_WEIGHT);
		$GLOBALS['xoopsTpl']->assign('initWeight', $initWeight);
*/               

        //--------------------------------------------
        //options de la question - tableau json
        if($questId > 0){
            $obQuest = $questionsHandler->get($questId);
		    $GLOBALS['xoopsTpl']->assign('questOptions', $obQuest->getVar('quest_options'));
		    $GLOBALS['xoopsTpl']->assign('quesTypeQuestion', $obQuest->getVar('quest_plugin'));
        }  
        //--------------------------------------------
        
		// Table view answers
		$GLOBALS['xoopsTpl']->assign('answersCount', $answersCount);
		if ($answersCount > 0) {
			foreach(array_keys($answersAll) as $i) {
				$Answers = $answersAll[$i]->getValuesAnswers();
				$GLOBALS['xoopsTpl']->append('answers_list', $Answers);
				unset($Answers);
			}
			// Display Navigation
			if ($answersCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($answersCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
			$GLOBALS['xoopsTpl']->assign('error', '');
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_ANSWERS);
		}
	break;

	case 'new_answer':
 
	case 'new':
		$templateMain = 'quizmaker_admin_answers.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ANSWERS_LIST, 'answers.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$answersObj = $answersHandler->create();
        
        if ($op == 'new_answer') {
            $answersObj->setVar('answer_quest_id', $questId);
            $answersObj->setVar('answer_weight', $answersHandler->getMax('answer_weight', $questId) + 10);
            $answersObj->setVar('answer_inputs', 1);
        }
		$form = $answersObj->getFormAnswers(false, $questId);
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
    
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('answers.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($answerId > 0) {
			$answersObj = $answersHandler->get($answerId);
		} else {
			$answersObj = $answersHandler->create();
		}
		// Set Vars
		$questId = Request::getInt('answer_quest_id', 0);
		$answersObj->setVar('answer_quest_id', Request::getInt('answer_quest_id', 0));
		$answersObj->setVar('answer_proposition', Request::getString('answer_proposition', ''));
		$answersObj->setVar('answer_caption', Request::getString('answer_caption', ''));
        
        $color = (Request::getInt('answer_isColor', 0)) ? Request::getString('answer_color', '') : '';
		$answersObj->setVar('answer_color', $color);
        $background = (Request::getInt('answer_isBackground', 0)) ? Request::getString('answer_background', '') : '';
		$answersObj->setVar('answer_background', $background);
        
		$answersObj->setVar('answer_background', Request::getString('answer_background', ''));
		$answersObj->setVar('answer_points', Request::getInt('answer_points', ''));
		$answersObj->setVar('answer_weight', Request::getInt('answer_weight', 0));
		$answersObj->setVar('answer_inputs', Request::getInt('answer_inputs', 1));
		$answersObj->setVar('answer_group',  Request::getInt('answer_group', 1));
		$answersObj->setVar('answer_image1', Request::getString('answer_image1', ''));
		$answersObj->setVar('answer_image2', Request::getString('answer_image2', ''));
        
		// Insert Data
		if ($answersHandler->insert($answersObj)) {
			redirect_header('answers.php?op=list' . getParams2list($questId), 2, _AM_QUIZMAKER_FORM_OK);
		}
        exit;
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $answersObj->getHtmlErrors());
		$form = $answersObj->getFormAnswers();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'edit':
		$templateMain = 'quizmaker_admin_answers.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_ANSWERS, 'answers.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_ANSWERS_LIST, 'answers.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$answersObj = $answersHandler->get($answerId);
		$form = $answersObj->getFormAnswers();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
		$answersObj = $answersHandler->get($answerId);
		$answerQuestion_id = $answersObj->getVar('answer_quest_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('answers.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($answersHandler->delete($answersObj)) {
				redirect_header('answers.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $answersObj->getHtmlErrors());
			}
		} else {
            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $answerId, $answersObj->getVar('answer_proposition'));
			xoops_confirm(['ok' => 1, 'answer_id' => $answerId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;

    case 'init_weight':
        $answersHandler->incrementeWeight($questId);
        \redirect_header("answers.php?op=list" . getParams2list($questId), 0, "");
	break;
    
    case 'weight':
       $action = Request::getString('sens', "asc") ;
       //echo "redirect questId = {$questId} : " . getParams2list($questId) . "<br>"; 
       $answersHandler->updateWeight($answerId, $action);
       //echo "redirect questId = {$questId} : " . getParams2list($questId) . "<br>"; //exit;
        \redirect_header("answers.php?op=list" . getParams2list($questId), 0, "");
        break;

    } // fin du switch maitre

require __DIR__ . '/footer.php';

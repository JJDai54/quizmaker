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

//$resultId = Request::getInt('result_id');


require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_RESULT,'QUIZMAKER_PERMIT_RESULT', "index.php");

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quest_id

$sender   = Request::getString('sender', '');
$catId    = Request::getInt('cat_id', 0);
$resultId = Request::getInt('result_id', 0);

if ($sender == 'cat_id') {
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
  $quizId = Request::getInt('result_quiz_id', 0);
  if ($quizId == 0) $quizId  = Request::getInt('quiz_id', 1);
}


//  $quizId  = Request::getInt('quiz_id', 1);

// $questId = Request::getInt('quest_id', 0);
// $quest_plugin = Request::getString('quest_plugin', '');

//$gp = array_merge($_GET, $_POST);
//echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_results.tpl';
        ///-------------------------------------------------------
    
        //recupe du quiz a afficher
        $quiz = $quizHandler->get($quizId);
        if ($quiz) {
            $quizValues = $quiz->getValuesQuiz();
            $catId = $quiz->getVar('quiz_cat_id');
        }
        
        // ----- Listes de selection pour filtrage -----  
        $catArr = $categoriesHandler->getListKeyName();
        if ($catId == 0) $catId = array_key_first($catArr);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"'.FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
       
        $quizArr = $quizHandler->getListKeyName($catId);
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
       // ----- /Listes de selection pour filtrage -----    
        $btn['razResults'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_RAZ_RESULTS, 'delete_all', QUIZMAKER_URL_ICONS."/16/delete.png",  _AM_QUIZMAKER_RAZ_RESULTS);

        //---------------------------------------------------
		$GLOBALS['xoopsTpl']->assign('btn', $btn);
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
           
        ///-------------------------------------------------------
        if ($quizId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
          $resultsCount = $resultsHandler->getCountResults($criteria);
          $resultsAll = $resultsHandler->getAllResults($criteria, $start, $limit, 'result_note ASC, result_uname');
//echo "quizId = {$quizId}<br>";    
        }else{
          $resultsCount = 0;
          $resultsAll = null;
        }

        ///-------------------------------------------------------
		//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('results.php'));
        
		//$resultsCount = $resultsHandler->getCountResults();
		//$resultsAll = $resultsHandler->getAllResults($start, $limit);
		$GLOBALS['xoopsTpl']->assign('results_count', $resultsCount);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		// Table view results
		if ($resultsCount > 0) {
			foreach(array_keys($resultsAll) as $i) {
				$Results = $resultsAll[$i]->getValuesResults();
				$GLOBALS['xoopsTpl']->append('results_list', $Results);
				unset($Results);
			}
			// Display Navigation
			if ($resultsCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($resultsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_RESULTS);
		}
	break;

	case 'edit':
		$templateMain = 'quizmaker_admin_results.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('results.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$resultsObj = $resultsHandler->get($resultId);
		$form = $resultsObj->getFormResults();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());    
	break;

	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('results.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($resultId == 0) {
			redirect_header('results.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		$resultsObj = $resultsHandler->get($resultId);
        
		$resultsObj->setVar('result_score_achieved', Request::getInt('result_score_achieved', '0'));        
		$resultsObj->setVar('result_score_max', Request::getInt('result_score_max', '0'));        
		$resultsObj->setVar('result_score_min', Request::getInt('result_score_min', '0'));        
		//$resultsObj->setVar('result_answers_achieved', Request::getInt('result_answers_achieved', '0'));        
		$resultsObj->setVar('result_answers_total', Request::getInt('result_answers_total', '0'));        

		$resultsObj->setVar('result_answers_achieved', $resultsObj->getVar('result_answers_total'));   
             
        $score_achieved = Request::getInt('result_score_achieved', '0');    
        $score_max = Request::getInt('result_score_max', '0');
        $res = str_replace(',', '.', (sprintf("%s",round($score_achieved / $score_max * 100, 2)) ));
//        echo "{$res}<br>";
        $resultsObj->setVar('result_note',$res);
        
		// Insert Data
		if ($resultsHandler->insert($resultsObj)) {
			redirect_header("results.php?op=list&quiz_id={$quizId}", 2, _AM_QUIZMAKER_FORM_OK);
		}
/* *************************** */        
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
		$form = $resultsObj->getFormResults();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());



	break;
/*


	case 'submit_answers':
	break;


	case 'new':
	break;

    
	break;
*/
	case 'delete':
        $resultId = Request::getInt('result_id', 0);
		$resultsObj = $resultsHandler->get($resultId);
        //$quizId = 
		//$resultQuiz_id = $resultsObj->getVar('result_quiz_id');
        
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('results.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($resultsHandler->delete($resultsObj)) {
				redirect_header("results.php?quiz_id={$quizId}", 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
			}
		} else {
//             $quiz = $quizHandler->get($quizId);
//             $name = $quiz->getVar('quiz_name');
            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $resultsObj->getVar('result_id'), "");
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'result_id' => $resultId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;

	case 'delete_all':
		//$resultsObj = $resultsHandler->get($resultId);
		//$quizId = $resultsObj->getVar('quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('results.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
            $criteria = new \CriteriaCompo();            
            $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
            
            $ret = $resultsHandler->deleteAll($criteria);
			redirect_header("results.php?quiz_id={$quizId}", 3, _AM_QUIZMAKER_DELETE_RESULTS_OK);
		} else {
            $quiz = $quizHandler->get($quizId);
            //$quizValues = $quiz->getValuesQuiz();
            $name = $quiz->getVar('quiz_name');
        
            $msg = sprintf(_AM_QUIZMAKER_CONFIRM_RAS_RESULTS, $name, $quizId); 
            //sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $resultsObj->getVar('result_quiz_id'))
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'delete_all'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;
}
require __DIR__ . '/footer.php';

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



//-----------------------------------------------
switch($op) {
	case 'list':
	default:

		// Define Stylesheet
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
        $allRst = $resultsHandler->getAllResultsArr($criteria);    
        
		$GLOBALS['xoopsTpl']->assign('countEnr', count($allRst));     
		$GLOBALS['xoopsTpl']->assign('results_list', $allRst);         
         //-------------------------------------------------------
		if (count($allRst) == 0) {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_RESULTS);
		}
	break;

	case 'edit':
		$templateMain = 'quizmaker_admin_participation_results.tpl';
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
			redirect_header($redirectURL, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($resultId == 0) {
			redirect_header($redirectURL, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
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
			redirect_header($redirectURL, 2, _AM_QUIZMAKER_FORM_OK);
		}
/* *************************** */        
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
		$form = $resultsObj->getFormResults();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
    
	case 'export_csv':

        $fullName = $resultsHandler->exportResultsToCSV($quizId);
		if ($fullName) {
            redirect_header("download_file.php?fullName={$fullName}",0,"");
        }else{
			redirect_header($redirectURL, 2, _AM_QUIZMAKER_RST_IS_EMPTY);

        }
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
				redirect_header($redirectURL, 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}

			if ($resultsHandler->delete($resultsObj)) {
				redirect_header($redirectURL, 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
			}
		} else {
            //$quiz = $quizHandler->get($quizId);
            $uname = $resultsObj->getVar('result_uname') ;           
            $email = $resultsObj->getVar('result_email') ;           
            //$quizName = $quiz->getVar('quiz_name');

            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE_PARTICIPATION, $quizId, $resultId, $uname, $email);
            $msg = FQUIZMAKER\getMsgStyle($msg, 'bred');
            //$msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE_PARTICIPATION, $resultsObj->getVar('result_id'), "");
            xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'result_id' => $resultId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;

	case 'delete_all':
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header($redirectURL, 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
            $criteria = new \CriteriaCompo();            
            $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
            
            $ret = $resultsHandler->deleteAll($criteria);
			redirect_header($redirectURL, 3, _AM_QUIZMAKER_DELETE_RESULTS_OK);
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


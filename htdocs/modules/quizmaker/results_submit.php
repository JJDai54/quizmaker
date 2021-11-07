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
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $helper->getConfig('userpager'));
$quizId = Request::getInt('quiz_id', 0);
$sender = 'quiz_id';

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);

$keywords = [];

$permEdit = $permissionsHandler->getPermGlobalSubmit();
$GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $quizId > 0);
 	
switch($op) {
	case 'submit_answers':
// echoArray($_POST);
// echo "Enregistrement des résultats";
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
        $criteria = new \CriteriaCompo(new \criteria('result_ip', $ip, "="));
        $criteria->add(new \criteria('result_quiz_id', $quizId, "="));
        $resultsCount = $resultsHandler->getCount($criteria);
        $attempt_max = 12;
        if ($resultsCount >= $attempt_max){
			redirect_header("categories.php?op=list&quiz_id={$quizId}&sender=", 3, _MA_QUIZMAKER_STILL_ANSWER);
        }        
		
        
        //$templateMain = 'quizmaker_admin_result.tpl';
		//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('results.php'));
		//$adminObject->addItemButton(_AM_QUIZMAKER_RESULTS_LIST, 'results.php', 'list');
		//$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$resultsObj = $resultsHandler->create();
        
        $quizId = Request::getInt('quiz_id', 0);
        $score_max = Request::getInt('score_max', 0);
        $score_achieved = Request::getInt('score_achieved', 0);        
        
        $resultsObj->setVar('result_quiz_id', $quizId);
        $resultsObj->setVar('result_score_max', $score_max);        
        $resultsObj->setVar('result_score_achieved', $score_achieved);        
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
//echo "<hr>ip : {$ip}<hr>";            
        $resultsObj->setVar('result_ip', $ip);
        
        
        $resultsObj->setVar('result_uid', $GLOBALS['xoopsUser']->uid());
        $resultsObj->setVar('result_uname', $GLOBALS['xoopsUser']->getVar('uname'));
        $resultsObj->setVar('result_answers_total', Request::getInt('answers_total', 0));
        $resultsObj->setVar('result_answers_achieved', Request::getInt('answers_achieved', 0));
        $resultsObj->setVar('result_score_min', Request::getInt('score_min', 0));
        $resultsObj->setVar('result_duration', Request::getInt('duration', 0));

        $res = str_replace(',', '.', (sprintf("%s",round($score_achieved / $score_max * 100, 2)) ));
        echo "{$res}<br>";
        $resultsObj->setVar('result_note',$res);
        //$resultsObj->setVar('result_note', 0);
        $resultsObj->setVar('result_creation', \JJD\getSqlDate());
        $resultsObj->setVar('result_update', \JJD\getSqlDate());
//setlocale(LC_NUMERIC, 'fr_FR');
		if ($resultsHandler->insert($resultsObj)) {
            //exit ('enregistrement ok');
			redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
		}
//    exit;
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
		//$form = $questionsObj->getFormQuestions();
		//$GLOBALS['xoopsTpl']->assign('form', $form->render());
        exit;
        redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
//exit;

	break;
    // ------------------------------------------------------------------------
    case 'submit_pseudo':
    break;
    default:
        redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
    

}

// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_QUIZ];


// Description
quizmakerMetaDescription(_MA_QUIZMAKER_QUIZ_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', QUIZMAKER_URL.'/quiz.php');
$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);

// View comments
//require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

require __DIR__ . '/footer.php';

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
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $quizmakerHelper->getConfig('userpager'));
$quizId = Request::getInt('quiz_id', 0);
$sender = 'quiz_id';

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$keywords = [];

//$permEdit = $permEdit = $clPerms->getPermissionsOld(16,'global_ac');

$GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $quizId > 0);
 
// echoGPF();	

switch($op) {
	case 'submit_answers':
//echoGPF('P',"submit_answers",true); 
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
//echo "<hr>===>" . $GLOBALS['xoopsUser']->uid() . "<===<hr>";        

if ($GLOBALS['xoopsUser']) {
        $resultsObj->setVar('result_uid', $GLOBALS['xoopsUser']->uid());
        $resultsObj->setVar('result_uname', $GLOBALS['xoopsUser']->getVar('uname'));
}else{
        $resultsObj->setVar('result_uid', 3);
        $resultsObj->setVar('result_uname', Request::getString('pseudo'));
}       
        $resultsObj->setVar('result_answers_total', Request::getInt('answers_total', 0));
        $resultsObj->setVar('result_answers_achieved', Request::getInt('answers_achieved', 0));
        $resultsObj->setVar('result_score_min', Request::getInt('score_min', 0));
        $resultsObj->setVar('result_duration', Request::getInt('duration', 0));
        
        $scoreStr = ($score_max > 0) ? round($score_achieved / $score_max * 100, 2) : 0;
        $res = str_replace(',', '.', (sprintf("%s",$scoreStr) ));
//        echo "{$res}<br>";
        $resultsObj->setVar('result_note',$res);
        //$resultsObj->setVar('result_note', 0);
        $resultsObj->setVar('result_creation', \JANUS\getSqlDate());
        $resultsObj->setVar('result_update', \JANUS\getSqlDate());
//setlocale(LC_NUMERIC, 'fr_FR');

		if ($resultsHandler->insert($resultsObj)) {
			//redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
			$newResultId = $resultsObj->getNewInsertedIdResults();
            $url = "solutions.php?quiz_id={$quizId}&sender={$sender}&result_id={$newResultId}";
            redirect_header($url, 2, _MA_QUIZMAKER_FORM_OK);
            
		}

		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
		//$form = $questionsObj->getFormQuestions();
		//$GLOBALS['xoopsTpl']->assign('form', $form->render());

        $url = "solutions.php?quiz_id={$quizId}&sender={$sender}";
        redirect_header($url, 2, _MA_QUIZMAKER_FORM_OK);
        //redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
	break;
    // ------------------------------------------------------------------------
    case 'submit_pseudo':
    break;
    default:
        redirect_header("results.php?op=list&quiz_id={$quizId}&sender={$sender}", 2, _MA_QUIZMAKER_FORM_OK);
    

}

// Bÿ ad �翴 bs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_QUIZ];


// Description
FQUIZMAKER\metaDescription(_MA_QUIZMAKER_QUIZ_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', QUIZMAKER_URL_MODULE.'/quiz.php');
$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);

// View comments
//require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

require __DIR__ . '/footer.php';

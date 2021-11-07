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
// Request result_id
$resultId = Request::getInt('result_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_results.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('results.php'));
		$resultsCount = $resultsHandler->getCountResults();
		$resultsAll = $resultsHandler->getAllResults($start, $limit);
		$GLOBALS['xoopsTpl']->assign('results_count', $resultsCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
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





	case 'submit_answers':
	break;


	case 'new':
	break;

    
	break;
	case 'save':
	break;
	case 'edit':
	break;
	case 'delete':
		$resultsObj = $resultsHandler->get($resultId);
		$resultQuiz_id = $resultsObj->getVar('result_quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('results.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($resultsHandler->delete($resultsObj)) {
				redirect_header('results.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $resultsObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(['ok' => 1, 'result_id' => $resultId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $resultsObj->getVar('result_quiz_id')));
		}
	break;
}
require __DIR__ . '/footer.php';

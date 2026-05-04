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



switch($op) {
	case 'list':
	default:
        if ($quizId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('cookie_quiz_id',$quizId, "="));
          $countEnr = $cookiesHandler->getCountCookies($criteria);
          $cookiesAll = $cookiesHandler->getAllCookies($criteria, $start, $limit, 'cookie_email, cookie_id');
//echo "quizId = {$quizId}<br>";    
        }else{
          $countEnr = 0;
          $cookiesAll = null;
        }

        ///-------------------------------------------------------
		//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('cookies.php'));
        
		//$countEnr = $cookiesHandler->getCountCookies();
		//$cookiesAll = $cookiesHandler->getAllCookies($start, $limit);
    	$GLOBALS['xoopsTpl']->assign('countEnr', $countEnr);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		// Table view cookies
		if ($countEnr > 0) {
			foreach(array_keys($cookiesAll) as $i) {
				$Cookies = $cookiesAll[$i]->getValuesCookies();
				$GLOBALS['xoopsTpl']->append('cookies_list', $Cookies);
				unset($Cookies);
			}
			// Display Navigation
			if ($countEnr > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($countEnr, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_COOKIES);
		}
	break;

	case 'edit':
		$templateMain = 'quizmaker_admin_cookies.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('cookies.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$cookiesObj = $cookiesHandler->get($cookieId);
		$form = $cookiesObj->getFormCookies();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());    
	break;

	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header($redirectURL, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($cookieId == 0) {
			redirect_header($redirectURL, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		$cookiesObj = $cookiesHandler->get($cookieId);
        
		$cookiesObj->setVar('cookie_readme', Request::getInt('cookie_readme', '0'));        
		$cookiesObj->setVar('cookie_attempts', Request::getInt('cookie_attempts', '0'));        
		$cookiesObj->setVar('cookie_score_max', Request::getInt('cookie_score_max', '0'));        
        
		// Insert Data

		if ($cookiesHandler->insert($cookiesObj)) {
			redirect_header($redirectURL."&op=list", 2, _AM_QUIZMAKER_FORM_OK);
		}
/* *************************** */        
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $cookiesObj->getHtmlErrors());
		$form = $cookiesObj->getFormCookies();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());



	break;

	case 'export_csv':

        $fullName = $cookiesHandler->exportCookiesToCSV($quizId);
		if ($fullName) {
            redirect_header("download_file.php?fullName={$fullName}",0,"");
        }else{
			redirect_header("participation.php?op=list{$params}", 2, _AM_QUIZMAKER_RST_IS_EMPTY);

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
        $cookieId = Request::getInt('cookie_id', 0);
		$cookiesObj = $cookiesHandler->get($cookieId);
        //$quizId = 
		//$cookieQuiz_id = $cookiesObj->getVar('cookie_quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header($redirectURL, 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($cookiesHandler->delete($cookiesObj)) {
				redirect_header($redirectURL, 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $cookiesObj->getHtmlErrors());
			}
		} else {
//             $quiz = $quizHandler->get($quizId);
//             $name = $quiz->getVar('quiz_name');
            $uname = $cookiesObj->getVar('cookie_uname') ;
            $email = $cookiesObj->getVar('cookie_email') ;           
            
            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE_COOKIE, $quizId,$cookieId , $uname, $email);            
            $msg = FQUIZMAKER\getMsgStyle($msg, 'bred');      
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'cookie_id' => $cookieId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;

	case 'delete_all':
		//$cookiesObj = $cookiesHandler->get($cookieId);
		//$quizId = $cookiesObj->getVar('quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('cookies.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
            $criteria = new \CriteriaCompo();            
            $criteria->add(new \Criteria('cookie_quiz_id',$quizId, "="));
            
            $ret = $cookiesHandler->deleteAll($criteria);
			redirect_header("cookies.php?quiz_id={$quizId}", 3, _AM_QUIZMAKER_DELETE_COOKIES_OK);
		} else {
            $quiz = $quizHandler->get($quizId);
            //$quizValues = $quiz->getValuesQuiz();
            $name = $quiz->getVar('quiz_name');
        
            $msg = sprintf(_AM_QUIZMAKER_CONFIRM_RAS_COOKIES, $quizId, $name); 
            //sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $cookiesObj->getVar('cookie_quiz_id'))
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'delete_all'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;
}


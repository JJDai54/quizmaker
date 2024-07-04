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

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz_display.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op    = Request::getCmd('op', 'run');
$quizId = Request::getInt('quiz_id', 0);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);


// Check permissions
//$permEdit = $permEdit = $clPerms->getPermissionsOld(16,'global_ac');
// if (!$permEdit) {
// 	redirect_header('quiz.php?op=list', 3, _NOPERM);
//}
// Check params
if (0 == $quizId) {
	redirect_header('categories.php?op=list', 3, _MA_QUIZMAKER_INVALID_PARAM);
}
///////////////////////////////////////////////////
//     $rootApp = QUIZMAKER_PATH_QUIZ_JS . "/quiz-js";
//     $urlApp  = QUIZMAKER_URL_QUIZ_JS  . "/quiz-js";
// 
//     //insertion des CSS
//     $tCss = \JJD\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//     $urlCss = QUIZMAKER_URL_QUIZ_JS. "/quiz-js/css";
//     foreach($tCss as $css){
// 		$GLOBALS['xoTheme']->addStylesheet($urlCss .'/'. $css , null );    
//     }
//     //----------------------------------------------
//     //insertion du prototype des tpl
//     $xoTheme->addScript($urlApp . '/' . 'slide__prototype.js');    


		$quizObj = $quizHandler->get($quizId);
        $catId = $quizObj->getVar('quiz_cat_id');
        //$attempt_max = $quizObj->getVar('quiz_attempts');
        $attempt_max = 3; //provisoir
        
        global $xoopsUser;
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
        $uid = ($xoopsUser) ? $xoopsUser->uid() : 2;
        //--------------------------------
        
        //recherche du nombre de uid
        $criteria = new \CriteriaCompo(new \criteria('result_quiz_id', $quizId, "="));
        $criteria->add(new \criteria('result_uid', $uid, "="));        
        $uidCount = $resultsHandler->getCount($criteria);
        
        //recherche du nombre d'IP
        $criteria = new \CriteriaCompo(new \criteria('result_quiz_id', $quizId, "="));
        $criteria->add(new \criteria('result_ip', $ip, "="));        
        $ipCount = $resultsHandler->getCount($criteria);
      
        //---------------------------------------------------------
        switch ($uid){
        case 1:
            $ok = true;
            break;
        case 2:
            $ok = ($ipCount < $attempt_max);
            break;
        default:
            $ok = ($uidCount < $attempt_max);
            break;
        }
       
        if (!$ok)
			redirect_header("categories.php?op=list&cat_id={$catId}&sender=", 3, _MA_QUIZMAKER_STILL_ANSWER);
        


		
// $paramsForQuiz = array('uid'  => $xoopsUser->uid(),
//                    'uname' => $xoopsUser->getVar('uname', 'e'),
//                    'name' => $xoopsUser->getVar('name', 'e'),
//                    'email' => $xoopsUser->getVar('email', 'e'),
//                    'ip'   => XoopsUserUtility::getIP(true));
    //$GLOBALS['xoopsTpl']->assign('allParams', $allParams);    
    $GLOBALS['xoopsTpl']->assign('paramsForQuiz', FQUIZMAKER\getParamsForQuiz(0));
    
//echo "<hr>XoopsUserUtility<pre>" . print_r( XoopsUserUtility::getUnameFromIds(2), true) . "</pre><hr>";    
///////////////////////////////////////////////////
		// Get Form

        $quizValues = $quizObj->getValuesQuiz();
		$GLOBALS['xoopsTpl']->assign('quiz_html', $quizValues['quiz_tpl_path']);        
        




////////////////////////////////////////////////
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_QUIZ];

// Keywords
/*
FQUIZMAKER\metaKeywords($quizmakerHelper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);
*/

// Description
FQUIZMAKER\metaDescription(_MA_QUIZMAKER_QUIZ_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', QUIZMAKER_URL_MODULE.'/quiz.php');
$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);

// View comments
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

require __DIR__ . '/footer.php';

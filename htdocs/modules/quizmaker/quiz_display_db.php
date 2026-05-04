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
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz_display.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op    = Request::getCmd('op', 'run');
$quizId = Request::getInt('quiz_id', 0);
$playerId = Request::getInt('player_id', 1);
$readmeOk = Request::getInt('readmeOk', 0);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
// $GLOBALS['xoopsTpl']->assign('sysUtlIcon16',  $sysUrlIcon16);
// $GLOBALS['xoopsTpl']->assign('sysUrlIcon32',  $sysUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon16',  $modUrlIcon16);
$GLOBALS['xoopsTpl']->assign('modUrlIcon32',  $modUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon256', $modUrlIcon256);
$GLOBALS['xoopsTpl']->assign('modUrlImages',  $modUrlImages);

$GLOBALS['xoopsTpl']->assign('player_id',  $playerId);

// Check permissions
//$permEdit = $permEdit = $clPerms->getPermissionsOld(16,'global_ac');
// if (!$permEdit) {
// 	redirect_header('quiz.php?op=list', 3, _NOPERM);
//}
// Check params
if (0 == $quizId) {
	redirect_header('categories.php?op=list', 3, _MA_QUIZMAKER_INVALID_PARAM);
}
$quizObj = $quizHandler->get($quizId);
        
// geestion des cookies pour verifier si k'utilisateur a dé&hà tenter ce quiz sans le valider dans le temps imparti par le cookie
$maxAttempt = $quizObj->getVar('quiz_max_attempts');
$delaiCookie = $quizObj->getVar('quiz_delai_cookie'); //delai du cookie en secondes
$catId = $quizObj->getVar('quiz_cat_id');
$catObj = $categoriesHandler->get($catId);

/*
*/
// 
// if ($maxAttempt > 0){
//     $coookieName = "quizmaker" . "-" . $quizId;
//     $tentativesArr =  Request::getInt($coookieName , 1,'COOKIE');
//     if($tentatives > $maxAttempt) {
//         //echo "<hr>vous avez déjà tenté de faire ce quiz sans enregistrer les résultats. Vous devez patienter quelques heures avant de recommencer<hr>";
//         //setcookie($coookieName, "", time() - 3600);
//         redirect_header('categories.php?cat_id=' . $catId, 8, _MA_QUIZMAKER_MAX_ATTEMPTS_EXCEEDS);
//         exit;
//     }
//     setcookie($coookieName, $tentatives+1, time()+$delaiCookie);  /* expire dans 1 heure */
// }else{
//     setcookie($coookieName, $tentatives+1, time() - 3600);  /* Suppression Du Cookie */
// }

//********************************************************************/
// recherche dans la table cookies en fonction du uid, et du quizId si il ya connextion
// sinon recherche à partir utilisation des coolies du navigateur.
// function quizmaker_readme2($catId, $readmeStatus){
//     global $xoopsUser, $readmeHandler;
//     $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;    
//     $readme = false;    
//     
//     if($readmeStatus > 0){
//         $readmeCount =  $readmeHandler->getReadmeCount($catId, $uid);
//         if($readmeStatus == 1 && $readmeCount == 0) {
//             $readme = true;
//         }else if($readmeStatus == 2){
//             $readme = true;
//             }
//     }
//     //echo "1-readmeStatus = {$readmeStatus} - readmeOk = {$readmeOk} - readmeCount = {$readmeCount} - readme = " . (($readme) ? 'true': 'false'). "<br>";
//     return $readme;
// }
//---------------------------------------------------------------------
if($xoopsUser){    
    /*
    $readme = quizmaker_readme($catId, $catObj->getVar('cat_readme_status'));
    if($readme && $readmeOk == 0){
        $urlTo = 'readme.php' . "?op=isReadmeOk&cat_id={$catId}&quiz_id={$quizId}";
        redirect_header( $urlTo, 5, _MA_QUIZMAKER_READ_CAT_INFO);
    }
    */
    
    if(FQUIZMAKER\isReadme($catObj, $readmeOk)){
        $urlTo = 'readme.php' . "?op=isReadmeOk&cat_id={$catId}&quiz_id={$quizId}&$fromdispplay=";
        redirect_header( $urlTo, 5, _MA_QUIZMAKER_READ_CAT_INFO);
    }
   
    $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;    
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cookie_quiz_id', $quizId, '='));
    $criteria->add(new Criteria('cookie_uid',  $uid, '='));
    $rstCookies = $cookiesHandler->getAllCookies($criteria);
    //echoArray($rstCookies, 'allCookies');
    
    if(count($rstCookies) > 0){
        $cookieObj = array_shift($rstCookies);
        $attempts = $cookieObj->getVar('cookie_attempts') ;
        $oldDeadLine = 0;
    }else{
        $cookieObj = $cookiesHandler->create();
        $cookieObj->setVar('cookie_cat_id', $catId);
        $cookieObj->setVar('cookie_quiz_id', $quizId);
        $cookieObj->setVar('cookie_uid', $uid);
        $cookieObj->setVar('cookie_email', $xoopsUser->getVar('email', 'e'));
        $cookieObj->setVar('cookie_uname', $xoopsUser->getVar('uname', 'e'));
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
        $cookieObj->setVar('cookie_ip', $ip);
        $attempts = 0;
        $oldDeadLine = $cookieObj->getVar('cookie_dead_line');
    }
    
    if ($maxAttempt > 0){    
        if ($attempts >= $maxAttempt){
            $msg = sprintf(_MA_QUIZMAKER_MAX_ATTEMPTS_EXCEEDS, $attempts, $maxAttempt);
            $bolPlayQuiz = false;
        }else{
            $deadLine = $cookieObj->getVar('cookie_dead_line');
            $now = time();
            //echo "<hr>deadLine = {$deadLine}<br>now = {$now}<hr>";
            if($deadLine > $now && $deadLine != 0){
                $strDelai = formatDeadLine($deadLine);
                $msg = sprintf(_MA_QUIZMAKER_MAX_ATTEMPTS_WAIT, $strDelai);
                $bolPlayQuiz = false;
            }else{
                $newDeadLine = $now + $delaiCookie;
                $cookieObj->setVar('cookie_dead_line', $newDeadLine);
                $bolPlayQuiz = true;
            }
            //exit;
        }    
    }else{
        $bolPlayQuiz = true;
    }
    
    //enregistrement des nouvelle données avant redirection si besoin
    //echo "<hr>delai = " . $quizObj->getVar('quiz_delai_cookie');
    $cookieObj->setVar('cookie_attempts', $attempts + 1);
    $cookieObj->setVar('cookie_creation', \JANUS\getSqlDate());
    $cookieObj->setVar('cookie_update', \JANUS\getSqlDate());
    $cookiesHandler->insert($cookieObj);
    
    if(!$bolPlayQuiz){
        redirect_header("quiz.php?cat_id={$catId}&player_id={$playerId}", 8, $msg);
    }
    
    }else{
        $coookieName = QUIZMAKER_DIRNAME . "-" . $quizId;
        if ($maxAttempt > 0){
            $cookieArr =  explode('|', Request::getString($coookieName , '','COOKIE'));
            $tentatives =  intVal($cookieArr[0]) ;
        //echoRequest('C',"max = {$maxAttempt} - tentatives = {$cookieArr[0]}");
            if($cookieArr[0] > $maxAttempt) {
                //echo "<hr>vous avez déjà tenté de faire ce quiz sans enregistrer les résultats. Vous devez patienter quelques heures avant de recommencer<hr>";
                //setcookie($coookieName, "", time() - 3600);
            }
            $deadLine = time() + $delaiCookie;
            setcookie($coookieName, ($tentatives+1) . '|' . $deadLine, $deadLine);   
    }else{
        setcookie($coookieName, 0, time() - 3600);  /* Suppression Du Cookie */
    }

}





/**********************************************************************
*
* **********************************************************************/
function formatDeadLine ($timestamp1, $timestamp2 = null){
    if(!$timestamp2) $timestamp2 = time();
        $seconds = $timestamp1 - $timestamp2;
//echo "<hr>formatDeadLine : timestamp1 = {$timestamp1}<br>timestamp2 = {$timestamp2}<br>seconds = {$seconds}<hr>";    exit;
    
    $arr = ['d' => floor($seconds / (3600*24)),
            'h' => floor(($seconds / 3600) % 24),
            'm' => floor(($seconds / 60) % 60),
            's' => $seconds % 60];
            
    $expArr = [];
    if($arr['d'] > 0) $expArr[] = $arr['d'] . " " . _MA_QUIZMAKER_UNIT_DAYS;
    if($arr['h'] > 0) $expArr[] = $arr['h'] . " " . _MA_QUIZMAKER_UNIT_HOURS;
    if($arr['m'] > 0) $expArr[] = $arr['m'] . " " . _MA_QUIZMAKER_UNIT_MINUTES;
    if($arr['s'] > 0) $expArr[] = $arr['s'] . " " . _MA_QUIZMAKER_UNIT_SECONDS;

    $exp = implode(' ', $expArr);    
    //$exp = ": {$arr['d']} jours {$arr['h']} heures {$arr['m']} minutes {$arr['s']} secondes";        
// echo "<hr>formatDelai : {$exp}<hr>"; exit;           
    return $exp;
}


//$diffInSeconds = $date2->getTimestamp() - $date->getTimestamp();

//echoArray($_COOKIE, "===> _COOKIE : {$coookie}");exit;

///////////////////////////////////////////////////
//     $rootApp = QUIZMAKER_PATH_QUIZ_JS . "/quiz-js";
//     $urlApp  = QUIZMAKER_URL_QUIZ_JS  . "/quiz-js";
// 
//     //insertion des CSS
//     $tCss = \JANUS\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//     $urlCss = QUIZMAKER_URL_QUIZ_JS. "/quiz-js/css";
//     foreach($tCss as $css){
// 		$GLOBALS['xoTheme']->addStylesheet($urlCss .'/'. $css , null );    
//     }
//     //----------------------------------------------
//     //insertion du prototype des tpl
  


		$quizObj = $quizHandler->get($quizId);
        $catId = $quizObj->getVar('quiz_cat_id');
        $maxAttempt = $quizObj->getVar('quiz_max_attempts');
        //$maxAttempt = 3; //provisoir
        
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
            $ok = ($ipCount < $maxAttempt || $maxAttempt == 0);
            break;
        default:
            $ok = ($uidCount < $maxAttempt || $maxAttempt == 0);
            break;
        }
       
        if (!$ok){
			redirect_header("quiz.php?op=list&cat_id={$catId}&player_id={$playerId}&sender=", 3, _MA_QUIZMAKER_STILL_ANSWER);
        }
        


		
// $paramsForQuiz = array('uid'  => $xoopsUser->uid(),
//                    'uname' => $xoopsUser->getVar('uname', 'e'),
//                    'name' => $xoopsUser->getVar('name', 'e'),
//                    'email' => $xoopsUser->getVar('email', 'e'),
//                    'ip'   => XoopsUserUtility::getIP(true));
    //$GLOBALS['xoopsTpl']->assign('allParams', $allParams);    
    $GLOBALS['xoopsTpl']->assign('paramsForQuiz', FQUIZMAKER\getParamsForQuiz(0,0,$playerId));
    
//echo "<hr>XoopsUserUtility<pre>" . print_r( XoopsUserUtility::getUnameFromIds(2), true) . "</pre><hr>";    
///////////////////////////////////////////////////
		// Get Form

        $quizValues = $quizObj->getValuesQuiz();
		$GLOBALS['xoopsTpl']->assign('quiz_html', $quizValues['quiz_tpl_path']);        
        




////////////////////////////////////////////////
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_CATEGORIES, 'link' => "categories.php?cat_id={$catId}&player_id={$playerId}"];
$xoBreadcrumbs[] = ['title' => $catObj->getVar('cat_name'), 'link' => "quiz.php?cat_id={$catId}&player_id={$playerId}"];
$xoBreadcrumbs[] = ['title' => $quizValues['name']];

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

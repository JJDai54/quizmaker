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
use XoopsModules\Quizmaker\Utlity;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_results_list.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$catId  = Request::getInt('cat_id', array_key_first($catArr));
$playerId  = Request::getInt('player_id', 1);
//echoArray($catArr);

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $quizmakerHelper->getConfig('userpager'));
$quizId = Request::getInt('quiz_id', 0);
$sender = Request::getString('sender', '');



//  $pg = array_merge($_GET, $_POST);
//  echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";

if($quizId > 0 && $sender =='quiz_id'){
    $quizObj = $quizHandler->get($quizId);
    $catId = $quizObj->getVar('quiz_cat_id');
}else{
    $quizId=$quizHandler->getFirstIdOfParent($catId);
    $quizObj = $quizHandler->get($quizId);
}
$quiz = $quizObj->getValuesQuiz();

// Define Stylesheet
\JANUS\load_css('', false);

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$keywords = [];
//----------------------------------------------------
$utility = new \XoopsModules\Quizmaker\Utility();
//$quizPerm = $utility::getPermissionQuiz();        
//$catPerm = $utility::getPermissionCat();        

//----------------------------------------------------
//$permEdit = $clPerms->getPermissionsOld(16,'global_ac');
//echoArray($permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $catId > 0);
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_CATEGORIES, 'link' => "categories.php?cat_id={$catId}&player_id={$playerId}"];
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_RESULTS . ' : <b>' . $quiz['name'] . '</b>'];

$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

        // ----- Listes de selection pour filtrage -----  
        $selector = array();
        $style="style='width:80%;'";
        $inpCategory = new \XoopsFormSelect(_MA_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    //$GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        $selector['inpCategory'] = $inpCategory->render();
        
        $catObj = $categoriesHandler->get($catId);
		$GLOBALS['xoopsTpl']->assign('catTheme', $catObj->getVar('cat_theme'));        
        //-------------------------------------
        $allQuiz = $quizHandler->getAllQuizAllowed($catId,true);   
        $quizCount = count($allQuiz);  
        $inpQuiz = new \XoopsFormSelect(_MA_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($allQuiz);
        
        
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
        $selector['inpQuiz'] = $inpQuiz->render();
  	    $GLOBALS['xoopsTpl']->assign('selector', $selector);
        // ----- /Listes de selection pour filtrage -----   

        // recherche des résultats
        $criteria= new \CriteriaCompo(new \Criteria('result_quiz_id', $quizId, '=')); 
//        $criteria->add(new \Criteria('result_uid', 3, '<>'));     
        $resultsCount = $resultsHandler->getCount($criteria);
        
        $criteria->setSort('result_score_achieved DESC,result_uname');
        $criteria->setOrder('DESC'); 
        $criteria->setStart($start); 
        $criteria->setLimit($limit); 
        $resulstAll = $resultsHandler->getAll($criteria);
        
  	    $GLOBALS['xoopsTpl']->assign('resultsCount', $resultsCount);

        if($resultsCount > 0 && count($allQuiz) > 0){
          $chrono = $start + 1;
  		    foreach(array_keys($resulstAll) as $i) {
              //if (!in_array($i, $catPerm)) continue;
  			$results[$i] = $resulstAll[$i]->getValuesResults();
              $results[$i]['chrono'] = $chrono++;
          }
          //echoArray($results);
    		$GLOBALS['xoopsTpl']->assign('results_count', $resultsCount);        
    		$GLOBALS['xoopsTpl']->assign('results', $results);        
    		$GLOBALS['xoopsTpl']->assign('quiz', $quiz);        
      
      
           $GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));
          // Display Navigation
          if ($resultsCount > $limit) {
          	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
          	$pagenav = new \XoopsPageNav($resultsCount, $limit, $start, 'start', "op=list&quiz_id={$quizId}&sender=quiz_id&limit={$limit}");
          	$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
          }

      }
    //recherche des quiz de la catégorie
    

//echoArray($categories);    
		unset($categories);
////////////////////////////////////////////////////////////
        
require __DIR__ . '/footer.php';

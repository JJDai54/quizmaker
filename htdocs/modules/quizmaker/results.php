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
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utlity;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_results_list.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';


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
    $catId = Request::getInt('cat_id', 1);
    $quizId=$quizHandler->getFirstIdOfParent($catId);
    $quizObj = $quizHandler->get($quizId);
}
$quiz = $quizObj->getValuesQuiz();

// Define Stylesheet
$style1 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-color.css";
$style2 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-design.css";
$GLOBALS['xoTheme']->addStylesheet( $style1, null );
$GLOBALS['xoTheme']->addStylesheet( $style2, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);

$keywords = [];
//----------------------------------------------------
$utility = new \XoopsModules\Quizmaker\Utility();
//$quizPerm = $utility::getPermissionQuiz();        
//$catPerm = $utility::getPermissionCat();        

//----------------------------------------------------
$permEdit = $permissionsHandler->getPermGlobalView();
//echoArray($permEdit);
$GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $catId > 0);
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_TITLE, 'link' => QUIZMAKER_URL . '/'];

$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

        // ----- Listes de selection pour filtrage -----  
        $selector = array();
        $style="style='width:80%;'";
        $cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($cat);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    //$GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        $selector['inpCategory'] = $inpCategory->render();
        
        $catObj = $categoriesHandler->get($catId);
		$GLOBALS['xoopsTpl']->assign('catTheme', $catObj->getVar('cat_theme'));        
        //-------------------------------------
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $tQuiz = $quizHandler->getListKeyName($catId,null,null,'view');
        $inpQuiz->addOptionArray($tQuiz);
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
        $selector['inpQuiz'] = $inpQuiz->render();
  	    $GLOBALS['xoopsTpl']->assign('selector', $selector);
        // ----- /Listes de selection pour filtrage -----   

/* ***************************************
SELECT `result_quiz_id`,`result_uid`,`result_uname`,
avg(`result_answers_achieved`) AS answers_achieved,
avg(`result_answers_total`) AS answers_total,
round(avg(`result_score_achieved`),2) AS score_achieved,
round(max(`result_score_max`),2) AS score_max,
avg(`result_note`) AS note

FROM `x251_quizmaker_results` 
 WHERE result_quiz_id=5
 GROUP BY `result_quiz_id`,`result_uid`
****************************************** */        
//$stat = $resultsHandler->getStatistics();         
//$stat = $quizHandler->getStatistics();   
        $criteria= new \CriteriaCompo(new \Criteria('result_quiz_id', $quizId, '='));        
        $criteria->add(new \Criteria('result_uid', 3, '<>'));     
        $resultsCount = $resultsHandler->getCount($criteria);
        
        $criteria->setSort('result_score_achieved DESC,result_uname');
        $criteria->setOrder('DESC'); 
        $criteria->setStart($start); 
        $criteria->setLimit($limit); 
        $resulstAll = $resultsHandler->getAll($criteria);
        
  	    $GLOBALS['xoopsTpl']->assign('resultsCount', $resultsCount);

        if($resultsCount > 0 && count($tQuiz) > 0){
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


/*

	$crCategories = new \CriteriaCompo();
	if ($catId > 0) {
		$crCategories->add( new \Criteria( 'cat_id', $catId ) );
	}
	$categoriesCount = $categoriesHandler->getCount($crCategories);
	$GLOBALS['xoopsTpl']->assign('categoriesCount', $categoriesCount);
	$crCategories->setStart( $start );
	$crCategories->setLimit( $limit );
	$categoriesAll = $categoriesHandler->getAll($crCategories);
	if ($categoriesCount > 0) {
		$categories = [];
		// Get All Categories
		foreach(array_keys($categoriesAll) as $i) {
			$categories[$i] = $categoriesAll[$i]->getValuesCategories();
			$keywords[$i] = $categoriesAll[$i]->getVar('cat_name');
		}
		$GLOBALS['xoopsTpl']->assign('categories', $categories);
		unset($categories);
		// Display Navigation
		if ($categoriesCount > $limit) {
			include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
			$pagenav = new \XoopsPageNav($categoriesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
			$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
		}
		$GLOBALS['xoopsTpl']->assign('type', $quizmakerHelper->getConfig('table_type'));
		$GLOBALS['xoopsTpl']->assign('divideby', $quizmakerHelper->getConfig('divideby'));
		$GLOBALS['xoopsTpl']->assign('numb_col', $quizmakerHelper->getConfig('numb_col'));
	}
      
      
*/
        
        
        
        
require __DIR__ . '/footer.php';

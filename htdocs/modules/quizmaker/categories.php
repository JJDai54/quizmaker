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
use XoopsModules\Quizmaker\Utlity;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_categories.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

// $pg = array_merge($_GET, $_POST);
// echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $helper->getConfig('userpager'));
$catId = Request::getInt('cat_id', 0);

// Define Stylesheet
$style1 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-color.css";
$style2 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-design.css";
$GLOBALS['xoTheme']->addStylesheet( $style1, null );
$GLOBALS['xoTheme']->addStylesheet( $style2, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);

$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

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

        // ----- Listes de selection pour filtrage -----  
        //JJDai : a corriger pour n'afficher que les categorie visible par le groupe
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
        
//         $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
//         $tQuiz = $quizHandler->getListKeyName($catId,null,null,'view');
//         $inpQuiz->addOptionArray($tQuiz);
//         $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
//   	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
//         $selector['inpQuiz'] = $inpQuiz->render();
  	    $GLOBALS['xoopsTpl']->assign('selector', $selector);
        // ----- /Listes de selection pour filtrage -----   



// 	$crCategories = new \CriteriaCompo();
// 	$categoriesCount = $categoriesHandler->getCount($crCategories);
// 	$GLOBALS['xoopsTpl']->assign('categoriesCount', $categoriesCount);
// 	$categoriesAll = $categoriesHandler->getAll($crCategories);
	$categoriesAll = $categoriesHandler->getAllowed('view');
    $categoriesCount = count($categoriesAll);
$stat = $quizHandler->getStatistics();
    
    if ($categoriesCount > 0) {
		$categories = [];
		// Get All Categories
		foreach(array_keys($categoriesAll) as $i) {
            //if (!in_array($i, $catPerm)) continue;
			$categories[$i] = $categoriesAll[$i]->getValuesCategories();
            
            //$crQuiz = new \CriteriaCompo(new \Criteria('quiz_publishQuiz', 0, ">"));
            $crQuiz = new \CriteriaCompo(new \Criteria('quiz_actif', 1, "="));
            if ($catId > 0){
              if ($catId == $categories[$i]['cat_id']){
                  $crQuiz->add( new \Criteria( 'quiz_cat_id', $catId, "=") );
              }else continue;
            }else{
                  $crQuiz->add( new \Criteria( 'quiz_cat_id', $i, "=") );
            }
            $allQuiz = $quizHandler->getAllowed('view', $crQuiz);            
            
            
//             $crQuiz->setSort('quiz_name');
//             $crQuiz->setOrder('ASC');
//             $quizCount = $quizHandler->getCount($crQuiz);
//             $allQuiz = $quizHandler->getAll($crQuiz);
               $quizCount = count($allQuiz);  
        		$quizArr = [];            
        	if ($quizCount > 0) {

        		// Get All Categories
        		foreach(array_keys($allQuiz) as $j) {
                    //if (!in_array($j, $quizPerm)) continue;
                    $tQuiz = $allQuiz[$j]->getValuesQuiz();
                    //Ajout des statistiques
                    
                    
        			//if(	$tQuiz['periodeOK']) $quizArr[$j] = $tQuiz;
        			$quizArr[$j] = $tQuiz;
                    
                    if (isset($stat[$j])){
                        $quizArr[$j]['stat'] = $stat[$j];
                    }else{
                    }
                    $quizArr[$j]['statOk'] = isset($stat[$j]);
                    
        		}
                $categories[$i]['quiz'] = $quizArr;
        		//$GLOBALS['xoopsTpl']->assign('quizArr', $quizArr);
        		//unset($quizArr);
            }
            
		}
    }
    
    //recherche des quiz de la catégorie
    
		$GLOBALS['xoopsTpl']->assign('categories', $categories);
$GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));        
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
		$GLOBALS['xoopsTpl']->assign('type', $helper->getConfig('table_type'));
		$GLOBALS['xoopsTpl']->assign('divideby', $helper->getConfig('divideby'));
		$GLOBALS['xoopsTpl']->assign('numb_col', $helper->getConfig('numb_col'));
	}
      
      
*/
        
        
        
        
require __DIR__ . '/footer.php';

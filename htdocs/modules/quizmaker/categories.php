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
use XoopsModules\Quizmaker\Utlity;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_categories.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$catId  = Request::getInt('cat_id', array_key_first($catArr));
//echoArray($catArr);
// $pg = array_merge($_GET, $_POST);
// echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $quizmakerHelper->getConfig('userpager'));

// Define Stylesheet
\JJD\load_css('', false);

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

$keywords = [];
//----------------------------------------------------
$utility = new \XoopsModules\Quizmaker\Utility();

//----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('showItem', $catId > 0);
        // ----- Listes de selection pour filtrage -----  
        $selector = array();
        $style="style='width:80%;'";

        $inpCategory = new \XoopsFormSelect(_CO_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
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

//-----------------------------------------------------------
    
    //$catObj = $categoriesHandler->get($catId);
    $allQuiz = $quizHandler->getAllQuizAllowed($catId);    
    $quizCount = count($allQuiz);  
   
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
        $i=0;
        //mis dans un tableau pour compatibilite quand toutes les categories puvaient etre affichées.
        //a modifier à l'ocation en assignat le tableau des quiz directement
        $catObj = $categoriesHandler->get($catId);
        $categories[$i] = $catObj->getValuesCategoriesLight();
        $categories[$i]['quiz'] = $quizArr;
    }
    
    //recherche des quiz de la catégorie
    $GLOBALS['xoopsTpl']->assign('paramsForQuiz', FQUIZMAKER\getParamsForQuiz(1));
    
	$GLOBALS['xoopsTpl']->assign('categories', $categories);
    $GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));        
//echoArray($categories);    
		unset($categories);
////////////////////////////////////////////////////////////

        
require __DIR__ . '/footer.php';

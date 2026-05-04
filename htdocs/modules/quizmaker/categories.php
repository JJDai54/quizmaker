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
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_categories.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
//-----------------------------------------------------------
//recherche des categories autorisées

// Define Stylesheet
\JANUS\load_css('', false);

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$GLOBALS['xoopsTpl']->assign('sysUtlIcon16', $sysUrlIcon16);
$GLOBALS['xoopsTpl']->assign('sysUrlIcon32', $sysUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon16', $modUrlIcon16);
$GLOBALS['xoopsTpl']->assign('modUrlIcon32', $modUrlIcon32);

$keywords = [];
//----------------------------------------------------
//$utility = new \XoopsModules\Quizmaker\Utility();
//echoArray("gp");
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_CATEGORIES];
\JANUS\load_css('', false);    

//echoArray($catArr);
//----------------------------------------------------
        $categories = array();
        $allCats = $categoriesHandler->getAllowedArr();   
        foreach ($allCats as $key=>$cat){
        
            $allQuiz = $quizHandler->getAllQuizAllowed($cat['cat_id']);    
            $quizCount = count($allQuiz);  
            if($quizCount == 0) continue;
            $categories[] = $cat;
        }
		// Get All Categories
        
    	$GLOBALS['xoopsTpl']->assign('categories', $categories);
        $GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));        
		unset($allCats);
        
require __DIR__ . '/footer.php';

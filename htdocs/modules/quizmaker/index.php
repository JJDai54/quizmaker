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

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_index.tpl';
//$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_categories.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
redirect_header('categories.php', 0, '');

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $quizmakerHelper->getConfig('userpager'));
$catId = Request::getInt('cat_id', 0);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
$GLOBALS['xoopsTpl']->assign('modUrlImg', QUIZMAKER_IMAGE_URL);

$keywords = [];

$permEdit = $permissionsHandler->getPermGlobalSubmit();
$GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
$GLOBALS['xoopsTpl']->assign('showItem', $catId > 0);
//echoArray($permEdit, 'Permissions', true);


$mid = $GLOBALS['xoopsModule']->getVar('mid');   
$utility = new \XoopsModules\Quizmaker\Utility();
//$tPerms = $utility::getPermissions();
//echoArray($tPerms, "Permissions mid : {$mid}", true);    

		$crCategories = new \CriteriaCompo();
		if ($catId > 0) {
			$crCategories->add( new \Criteria( 'cat_id', $catId ) );
		}
        if (count($tPerms['cat']) > 0){
			$crCategories->add( new \Criteria( 'cat_id',"(" . implode(',', $tPerms['cat']) . ")", "IN"), 'AND');
        }else{
        	redirect_header(XOOPS_URL, 3, _NOPERM);
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
                
                $categories[$i]['quizChildren'] = $categoriesAll[$i]->getChildren($tPerms['quiz']);
			}
//              echoArray($categories);
            
            
            
            
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


// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_CATEGORIES];

// Keywords
quizmakerMetaKeywords($quizmakerHelper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);

// Description
quizmakerMetaDescription(_MA_QUIZMAKER_CATEGORIES_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', QUIZMAKER_URL.'/categories.php');
$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);

require __DIR__ . '/footer.php';

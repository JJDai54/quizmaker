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
// Request cat_id
$catId = Request::getInt('cat_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_categories.tpl';
	$adminObject->displayNavigation('categories.php');
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_CATEGORIES, 'categories.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$categoriesCount = $categoriesHandler->getCountCategories();
		$categoriesAll = $categoriesHandler->getAllCategories($start, $limit, 'cat_weight,cat_name');
		$GLOBALS['xoopsTpl']->assign('categories_count', $categoriesCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
		// Table view categories
		if ($categoriesCount > 0) {
			foreach(array_keys($categoriesAll) as $i) {
				$Categories = $categoriesAll[$i]->getValuesCategories();
				$GLOBALS['xoopsTpl']->append('categories_list', $Categories);
				unset($Categories);
			}
			// Display Navigation
			if ($categoriesCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($categoriesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_CATEGORIES);
		}
	break;
	case 'new':
		$templateMain = 'quizmaker_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$categoriesObj = $categoriesHandler->create();
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('categories.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($catId > 0) {
			$categoriesObj = $categoriesHandler->get($catId);
		} else {
			$categoriesObj = $categoriesHandler->create();
    		$categoriesObj->setVar('cat_creation', \JJD\getSqlDate());
		}
		// Set Vars
		$categoriesObj->setVar('cat_name', Request::getString('cat_name', ''));
		$categoriesObj->setVar('cat_description', Request::getText('cat_description', ''));
		$categoriesObj->setVar('cat_weight', Request::getString('cat_weight', 0));
		$categoriesObj->setVar('cat_theme', Request::getString('cat_theme', 'default'));
        
		$categoriesObj->setVar('cat_update', \JJD\getSqlDate());

        
		// Insert Data
		if ($categoriesHandler->insert($categoriesObj)) {
			$newCatId = $categoriesObj->getNewInsertedIdCategories();
			$permId = isset($_REQUEST['cat_id']) ? $catId : $newCatId;
			$grouppermHandler = xoops_getHandler('groupperm');
			$mid = $GLOBALS['xoopsModule']->getVar('mid');
			// Permission to view_categories
			$grouppermHandler->deleteByModule($mid, 'quizmaker_view_categories', $permId);
			if (isset($_POST['groups_view_categories'])) {
				foreach($_POST['groups_view_categories'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_view_categories', $permId, $onegroupId, $mid);
				}
			}
			// Permission to submit_categories
			$grouppermHandler->deleteByModule($mid, 'quizmaker_submit_categories', $permId);
			if (isset($_POST['groups_submit_categories'])) {
				foreach($_POST['groups_submit_categories'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_submit_categories', $permId, $onegroupId, $mid);
				}
			}
			// Permission to approve_categories
			$grouppermHandler->deleteByModule($mid, 'quizmaker_approve_categories', $permId);
			if (isset($_POST['groups_approve_categories'])) {
				foreach($_POST['groups_approve_categories'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_approve_categories', $permId, $onegroupId, $mid);
				}
			}
			redirect_header('categories.php?op=list', 2, _AM_QUIZMAKER_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'edit':
		$templateMain = 'quizmaker_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_CATEGORIES, 'categories.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$categoriesObj = $categoriesHandler->get($catId);
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
		$categoriesObj = $categoriesHandler->get($catId);
		$catName = $categoriesObj->getVar('cat_name');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('categories.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($categoriesHandler->delete($categoriesObj)) {
				redirect_header('categories.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(['ok' => 1, 'cat_id' => $catId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $categoriesObj->getVar('cat_name')));
		}
	break;

    case 'weight':
        $action = Request::getString('sens', "down") ;
        $categoriesHandler->updateWeight($catId, $action);
        //$questionsHandler->incrementeWeight($catId);
        $url = "categories.php?op=list&cat_id={$catId}";            // ."#question-{$catId}";
        //echo "<hr>{$url}<hr>";exit;
        \redirect_header($url, 0, "");
        break;

    
}
require __DIR__ . '/footer.php';

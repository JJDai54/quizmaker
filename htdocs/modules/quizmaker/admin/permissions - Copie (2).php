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

// Template Index
$templateMain = 'quizmaker_admin_permissions.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('permissions.php'));

$op = Request::getCmd('op', 'global');

// Get Form
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
xoops_load('XoopsFormLoader');
$permTableForm = new \XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
$formSelect = new \XoopsFormSelect('', 'op', $op);
$formSelect->setExtra('onchange="document.fselperm.submit()"');
$formSelect->addOption('global', _AM_QUIZMAKER_PERMISSIONS_GLOBAL);
$formSelect->addOption('approve_quiz', _AM_QUIZMAKER_PERMISSIONS_APPROVE . ' Quiz');
$formSelect->addOption('submit_quiz', _AM_QUIZMAKER_PERMISSIONS_SUBMIT . ' Quiz');
$formSelect->addOption('view_quiz', _AM_QUIZMAKER_PERMISSIONS_VIEW . ' Quiz');
$formSelect->addOption('approve_categories', _AM_QUIZMAKER_PERMISSIONS_APPROVE . ' Categories');
$formSelect->addOption('submit_categories', _AM_QUIZMAKER_PERMISSIONS_SUBMIT . ' Categories');
$formSelect->addOption('view_categories', _AM_QUIZMAKER_PERMISSIONS_VIEW . ' Categories');
$permTableForm->addElement($formSelect);
$permTableForm->display();
switch($op) {
	case 'global':
	default:
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_GLOBAL;
		$permName = 'quizmaker_ac_quiz';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_GLOBAL_DESC;
		$globalPerms = array( '4' => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_4, '8' => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_8, '16' => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_16 );
	break;
	case 'approve_quiz':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_APPROVE;
		$permName = 'quizmaker_approve_quiz';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_APPROVE_DESC . ' Quiz';
		$handler = $quizmakerHelper->getHandler('quiz');
	break;
	case 'submit_quiz':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_SUBMIT;
		$permName = 'quizmaker_submit_quiz';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_SUBMIT_DESC . ' Quiz';
		$handler = $quizmakerHelper->getHandler('quiz');
	break;
	case 'view_quiz':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_VIEW;
		$permName = 'quizmaker_view_quiz';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_VIEW_DESC . ' Quiz';
		$handler = $quizmakerHelper->getHandler('quiz');
	break;
	case 'approve_categories':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_APPROVE;
		$permName = 'quizmaker_approve_categories';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_APPROVE_DESC . ' Categories';
		$handler = $quizmakerHelper->getHandler('categories');
	break;
	case 'submit_categories':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_SUBMIT;
		$permName = 'quizmaker_submit_categories';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_SUBMIT_DESC . ' Categories';
		$handler = $quizmakerHelper->getHandler('categories');
	break;
	case 'view_categories':
		$formTitle = _AM_QUIZMAKER_PERMISSIONS_VIEW;
		$permName = 'quizmaker_view_categories';
		$permDesc = _AM_QUIZMAKER_PERMISSIONS_VIEW_DESC . ' Categories';
		$handler = $quizmakerHelper->getHandler('categories');
	break;
}
$moduleId = $xoopsModule->getVar('mid');
$permform = new \XoopsGroupPermForm($formTitle, $moduleId, $permName, $permDesc, 'admin/permissions.php');
$permFound = false;
if ('global' === $op) {
	foreach($globalPerms as $gPermId => $gPermName) {
		$permform->addItem($gPermId, $gPermName);
	}
	$GLOBALS['xoopsTpl']->assign('form', $permform->render());
	$permFound = true;
}
if ($op === 'approve_quiz' || $op === 'submit_quiz' || $op === 'view_quiz') {
	$quizCount = $quizHandler->getCountQuiz();
	if ($quizCount > 0) {
		$quizAll = $quizHandler->getAllQuiz(0, 'quiz_cat_id');
		foreach(array_keys($quizAll) as $i) {
			$permform->addItem($quizAll[$i]->getVar('quiz_id'), $quizAll[$i]->getVar('quiz_name'));
		}
		$GLOBALS['xoopsTpl']->assign('form', $permform->render());
	}
	$permFound = true;
}
if ($op === 'approve_categories' || $op === 'submit_categories' || $op === 'view_categories') {
	$categoriesCount = $categoriesHandler->getCountCategories();
	if ($categoriesCount > 0) {
		$categoriesAll = $categoriesHandler->getAllCategories(0, 'cat_name');
		foreach(array_keys($categoriesAll) as $i) {
			$permform->addItem($categoriesAll[$i]->getVar('cat_id'), $categoriesAll[$i]->getVar('cat_name'));
		}
		$GLOBALS['xoopsTpl']->assign('form', $permform->render());
	}
	$permFound = true;
}
unset($permform);
if (true !== $permFound) {
	redirect_header('permissions.php', 3, _AM_QUIZMAKER_NO_PERMISSIONS_SET);
	exit();
}
require __DIR__ . '/footer.php';

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

// Template Index
$templateMain = 'quizmaker_admin_permissions.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('permissions.php'));

$op = Request::getCmd('op', 'global_ac');

// Get Form
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
xoops_load('XoopsFormLoader');
$permTableForm = new \XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
$formSelect = new \XoopsFormSelect('', 'op', $op);
$formSelect->setExtra('onchange="document.fselperm.submit()"');
$formSelect->addOption('global_perms', _AM_QUIZMAKER_PERMISSIONS_GLOBAL);
$formSelect->addOption('approve_quiz', _AM_QUIZMAKER_PERMISSIONS_APPROVE . ' Quiz');
$formSelect->addOption('submit_quiz', _AM_QUIZMAKER_PERMISSIONS_SUBMIT . ' Quiz');
$formSelect->addOption('view_quiz', _AM_QUIZMAKER_PERMISSIONS_VIEW . ' Quiz');
$formSelect->addOption('approve_categories', _AM_QUIZMAKER_PERMISSIONS_APPROVE . ' Categories');
$formSelect->addOption('submit_categories', _AM_QUIZMAKER_PERMISSIONS_SUBMIT . ' Categories');
$formSelect->addOption('view_categories', _AM_QUIZMAKER_PERMISSIONS_VIEW . ' Categories');
$permTableForm->addElement($formSelect);
$permTableForm->display();


$domaines = explode('_', $op . '__');
echo '===>' . $op . '--->' . '_AM_QUIZMAKER_PERMISSIONS_' . strtoupper($domaines[0]). '<br>';
	$formTitle = constant('_AM_QUIZMAKER_PERMISSIONS_' . strtoupper($domaines[0])) ;
	$permName = 'quizmaker_' . $op;
    $cst = strtoupper("_AM_QUIZMAKER_PERMISSIONS_".strtoupper($domaines[0])."_DESC");    
	$permDesc = constant($cst) . ' ' . $domaines[0] ;
	$permFound = true;
    
//exit;
switch($domaines[1]) {
	case 'ac':
		$permArr = array('4'  => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_4, 
                         '8'  => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_8, 
                         '16' => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_16,
                         '32' => '_AM_QUIZMAKER_PERMISSIONS_GLOBAL_32' );
        break;
	case 'categories':
        $permArr = $categoriesHandler->getList();
        break;
	case 'quiz':
        $permArr = $quizHandler->getList();
        break;
	default:
	$permFound = false;
}

//echoArray($permArr, "op= {$op} - domaine={$domaine}");
    $permform = $permissionsHandler->getPermForm($formTitle, $permName, $permDesc, $permArr);
    $GLOBALS['xoopsTpl']->assign('form', $permform->render());


unset($permform);
if (true !== $permFound) {
	redirect_header('permissions.php', 3, _AM_QUIZMAKER_NO_PERMISSIONS_SET);
	exit();
}
require __DIR__ . '/footer.php';

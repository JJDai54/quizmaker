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
use XoopsModules\Quizmaker AS FQUIZMAKER;
include_once("../class/Helper.php");
$dirname       = basename(dirname(__DIR__));
$moduleHandler = xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname($dirname);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');
//$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();

include_once(XOOPS_ROOT_PATH . '/Frameworks/JJD-Framework/class/Permissions.php');
include_once(XOOPS_ROOT_PATH . '/modules/quizmaker/include/common.php');


$clPerms = new jjdPermissions('quizmaker');

$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU1,
	'link' => 'admin/index.php',
	'icon' => $sysPathIcon32.'/dashboard.png',
];

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_CATMAN)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU2,
	'link' => 'admin/categories.php',
	'icon' => 'assets/icons/32/block.png',
];
}

$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU3,
	'link' => 'admin/quiz.php',
	'icon' => 'assets/icons/32/album.png',
];
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU4,
	'link' => 'admin/questions.php',
	'icon' => 'assets/icons/32/faq.png',
];
/*
*/
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU5,
	'link' => 'admin/answers.php',
	'icon' => 'assets/icons/32/index.png',
];
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU6,
	'link' => 'admin/plugins.php',
	'icon' => 'assets/icons/32/wizard.png',
];

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_EXPORT)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_EXPORT,
	'link' => 'admin/export.php',
	'icon' => $sysPathIcon32 . '/upload.png',
];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_IMPORTG)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_IMPORT,
	'link' => 'admin/import.php?op=getform',
	'icon' => $sysPathIcon32 . '/download.png',
];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_MINIFY)){
// if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_MINIFY)
//     && $quizmakerHelper->getConfig('use_js_minified')){
$adminmenu[] = [
  	'title' => _MI_QUIZMAKER_MINIFY,
  	'link' => 'admin/minify.php',
  	'icon' => $sysPathIcon32.'/discount.png',
  ];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_RESULT)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU8,
	'link' => 'admin/results.php',
	'icon' => 'assets/icons/32/calculator.png',
];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_PERMISSIONS, true)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU9,
	'link' => 'admin/permissions.php',
	'icon' => $sysPathIcon32.'/permissions.png',
];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_MESSAGEJS)){
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU11,
	'link' => 'admin/messages.php',
	'icon' => $sysPathIcon32.'/translations.png',
];
}

if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_CLONE)){
$adminmenu[] = [
    'title' => _MI_QUIZMAKER_ADMENU_CLONE,
    'link' => 'admin/clone.php',
    'icon' => $sysPathIcon32.'/page_copy.png',
];
}

$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU12,
	'link' => 'admin/options.php',
	'icon' => 'assets/icons/32/index.png',
];

$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ADMENU10,
	'link' => 'admin/feedback.php',
	'icon' => $sysPathIcon32.'/mail_foward.png',
];
$adminmenu[] = [
	'title' => _MI_QUIZMAKER_ABOUT,
	'link' => 'admin/about.php',
	'icon' => $sysPathIcon32.'/about.png',
];

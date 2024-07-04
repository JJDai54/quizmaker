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
include dirname(dirname(__DIR__)) . '/mainfile.php';
$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
include __DIR__ . '/include/common.php';
include __DIR__ . '/include/permissions.php';
include __DIR__ . '/include/quizFlagAscii.php';
include_once __DIR__ . '/include/functions.php';

$moduleDirName = basename(__DIR__);
// Breadcrumbs
$xoBreadcrumbs = [];
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_TITLE, 'link' => QUIZMAKER_URL_MODULE . '/'];
// Get instance of module
$quizHandler = $quizmakerHelper->getHandler('Quiz');
$questionsHandler = $quizmakerHelper->getHandler('Questions');
$categoriesHandler = $quizmakerHelper->getHandler('Categories');
$type_questionHandler = $quizmakerHelper->getHandler('Type_question');
$answersHandler = $quizmakerHelper->getHandler('Answers');
$resultsHandler = $quizmakerHelper->getHandler('Results');
$messagesHandler = $quizmakerHelper->getHandler('Messages');
$permissionsHandler = $quizmakerHelper->getHandler('Permissions');
// 
$myts = MyTextSanitizer::getInstance();
// Default Css Style
$style = QUIZMAKER_URL_MODULE . '/assets/css/style.css';
// Smarty Default
$sysPathIcon16 = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16 = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32 = $GLOBALS['xoopsModule']->getInfo('modicons16');
// Load Languages
xoops_loadLanguage('main', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('common', $moduleDirName);
xoops_loadLanguage('type_questions', $moduleDirName);

include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");
//\jjd\load_trierTableauHTML();
$clPerms = new jjdPermissions();




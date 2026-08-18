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
// echo "<hr>" . __FILE__ . "<hr>";
//include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once ("../../../include/cp_header.php");

$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
include_once dirname(__DIR__) . '/include/common.php';
include_once dirname(__DIR__) . '/include/functions.php';

xoops_load('XoopsFormLoader');

$sysUrlIcon16   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysUrlIcon32   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons32');
//$modUrlIcon16   = QUIZMAKER_URL_MODULE . '/' . $GLOBALS['xoopsModule']->getInfo('modicons16') . '/';

$modUrlIcon16   = getQMFolder('u', 'm', 'assets/icons/16');
$modUrlIcon32   = getQMFolder('u', 'm', 'assets/icons/32');
$modUrlIcon256  = getQMFolder('u', 'm', 'assets/icons/256');
$modUrlImages   = getQMFolder('u', 'm', 'assets/images');

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once XOOPS_ROOT_PATH . '/class/template.php';
	$xoopsTpl = new \XoopsTpl();
}
$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());

// Load languages
xoops_loadLanguage('admin');
xoops_loadLanguage('modinfo');
xoops_loadLanguage('plugins', 'quizmaker');

// Local admin menu class
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
if (file_exists($GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php'))) {
	include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
} else {
	redirect_header('../../../admin.php', 5, _AM_MODULEADMIN_MISSING);
}

xoops_cp_header();

// System icons path
$GLOBALS['xoopsTpl']->assign('sysUtlIcon16',  $sysUrlIcon16);
$GLOBALS['xoopsTpl']->assign('sysUrlIcon32',  $sysUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon16',  $modUrlIcon16);
$GLOBALS['xoopsTpl']->assign('modUrlIcon32',  $modUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon256', $modUrlIcon256);
$GLOBALS['xoopsTpl']->assign('modUrlImages',  $modUrlImages);

$adminObject = \Xmf\Module\Admin::getInstance();
$xoTheme->addStylesheet(QUIZMAKER_URL_ASSETS . '/css/admin/style.css');

xoops_load('XoopsLists', 'core');
$utility = new \XoopsModules\Quizmaker\Utility();
$quizUtility = new \XoopsModules\Quizmaker\Utility();

if(!is_dir(XOOPS_ROOT_PATH . "/Frameworks/janus")){redirect_header("load_janus.php");}
include_once (XOOPS_ROOT_PATH . "/Frameworks/janus/load.php");
\JANUS\loadAllXForms();   
\JANUS\load_trierTableauHTML();
$janusPathIco32 = JANUS_ICO32;
$clPerms = new \JanusPermissions();
//include_once(JANUS_PATH_XFORMS . '/formtabletray.php');        
// include_once dirname(__DIR__) . '/class/xoopsform/formnumber.php';
// include_once dirname(__DIR__) . '/class/xoopsform/formimg.php';
$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
//$moduleDirName      = 'quizmaker';
xoops_loadLanguage('common', $moduleDirName);

// Get instance of module
// $mid = $GLOBALS['xoopsModule']->getVar('mid');
// echo "<hr>===>mid = {$mid}<hr>";
$categoriesHandler = $quizmakerHelper->getHandler('Categories');
$quizHandler       = $quizmakerHelper->getHandler('Quiz');
$questionsHandler  = $quizmakerHelper->getHandler('Questions');
$answersHandler    = $quizmakerHelper->getHandler('Answers');
$resultsHandler    = $quizmakerHelper->getHandler('Results');
$pluginsHandler    = $quizmakerHelper->getHandler('Plugins');
$optionsHandler    = $quizmakerHelper->getHandler('Options');
$messagesHandler   = $quizmakerHelper->getHandler('Messages');
$cookiesHandler    = $quizmakerHelper->getHandler('Cookies');
$readmeHandler     = $quizmakerHelper->getHandler('Readme');

$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');

$myts = MyTextSanitizer::getInstance();
//echoArray($quizmakerHelper);
/*
*/
require_once("../include/quizFlagAscii.php");
/* remplacer pa les fonction getflags dans les classes des tables
$xoopsTpl->register_function("quizFlagAscii", "smarty_function_quizFlagAscii", false);
$xoopsTpl->register_function("quizFlagAlpha", "smarty_function_quizFlagAlpha", false);
*/

//$xoopsTpl->register_compiler_function("quizFlagAscii2", "quizFlagAscii2", false);

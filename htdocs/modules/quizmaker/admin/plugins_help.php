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

include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once dirname(__DIR__) . '/include/common.php';
xoops_loadLanguage('common', QUIZMAKER_DIRNAME);
xoops_loadLanguage('plugins', QUIZMAKER_DIRNAME);

xoops_load('XoopsFormLoader');
include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");
//\JJD\include_highslide(null,"quizmaker");
//\JJD\include_highslide();

$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
	//include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once XOOPS_ROOT_PATH . '/class/template.php';
	$xoopsTpl = new \XoopsTpl();
}

    $templateMain = 'quizmaker_admin_plugins_help.tpl';
    $clAbout = new \About($quizmakerHelper);


      $plugin = Request::getString('plugin', '');
      //$plugin = $_GET['plugin'];
      //echo "<hr>plugin : {$plugin}<hr>";
      $pluginsHandler = $quizmakerHelper->getHandler('Plugins');
      $clsPlugin = $pluginsHandler->getPlugin($plugin);

      $xoopsTpl->assign('localHeaderInfo', $clAbout->localHeaderInfo(false));
      $xoopsTpl->assign('plugin', $plugin);
      $xoopsTpl->assign('viewHelpPlugin', $clsPlugin->getViewPlugin(2));
      $xoopsTpl->display(XOOPS_ROOT_PATH . "/modules/quizmaker/templates/admin/quizmaker_admin_plugins_help.tpl");


?>

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

include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once dirname(__DIR__) . '/include/common.php';
xoops_loadLanguage('common', QUIZMAKER_DIRNAME);
xoops_loadLanguage('type_questions', QUIZMAKER_DIRNAME);
echo "<hr>" . QUIZMAKER_DIRNAME .  "<hr>";
xoops_load('XoopsFormLoader');
include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");

$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
	//include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
// if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
 	include_once XOOPS_ROOT_PATH . '/class/template.php';
// 	$xoopsTpl = new \XoopsTpl();
// }
  
  $xoopsTpl = new XoopsTpl();
  //$xoopsTpl->assign('url_base', $this->url);
  

      //$templateMain = 'quizmaker_admin_type_question_help.tpl';
      //$plugin = Request::getString('plugin', '');
      $plugin = $_GET['plugin'];
      //echo "<hr>plugin : {$plugin}<hr>";
$type_questionHandler = $quizmakerHelper->getHandler('Type_question');
      $clsTypeQuestion = $type_questionHandler->getTypeQuestion($plugin);

      
      $xoopsTpl->assign('viewHelpTypeQuestion', $clsTypeQuestion->getViewType_question());
      //$GLOBALS['xoopsTpl']->assign('viewHelpTypeQuestion', $plugin);

     $xoopsTpl->display(XOOPS_ROOT_PATH . "/modules/quizmaker/templates/admin/quizmaker_admin_type_question_help.tpl");  

 // $xoopsTpl = new XoopsTpl();
 // $xoopsTpl->assign('url_base', $this->url);
  
//    $GLOBALS['xoopsTpl']-->display(quizmaker_admin_type_question_help.tpl);  



//
//require __DIR__ . '/footer.php';
?>

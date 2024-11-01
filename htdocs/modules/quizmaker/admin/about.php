<?php
namespace XoopsModules\Quizmaker;
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * quizmaker - Slides management module for xoops
 *
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         JJDai - Email:<jjdelalandre@orange.fr> - Website:<http://jubile.fr>
 */
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Helper;
use XoopsModules\Quizmaker\Constants;

//require_once XOOPS_ROOT_PATH . '/modules/quizmaker/admin/header.php';
require_once 'header.php';


$clAbout = new \About($quizmakerHelper,
                      'MUUZPTPGJSB9G',
                      "https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif",
                      "https://www.paypal.com/en_FR/i/scr/pixel.gif");


/************************************************************************/
$adminObject->displayNavigation('about.php');
// $GLOBALS['xoopsTpl']->assign('box', $clAbout->getBox());
// //$GLOBALS['xoopsTpl']->assign('tplAbout', XOOPS_ROOT_PATH . "/Frameworks/janus/templates/admin_about.tpl");
// $GLOBALS['xoopsTpl']->display(XOOPS_ROOT_PATH . "/Frameworks/janus/templates/admin_about.tpl");
$clAbout->display();
require __DIR__ . '/footer.php';

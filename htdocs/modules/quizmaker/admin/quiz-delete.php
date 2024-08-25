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
use XoopsModules\Quizmaker\Utility;
//use JANUS;

        $clPerms->checkAndRedirect('delete_quiz', $quizCat_id,'$quizCat_id', "quiz.php?op=list&cat_id={$quizCat_id}");

		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($quizHandler->delete($quizObj)) {
				redirect_header("quiz.php?cat_id={$quizCat_id}", 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $quizObj->getHtmlErrors());
			}
		} else {
            $msg =  sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $quizObj->getVar('quiz_id'), $quizObj->getVar('quiz_name'));
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}

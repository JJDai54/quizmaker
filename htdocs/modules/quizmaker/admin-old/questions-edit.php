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

		$templateMain = 'quizmaker_admin_questions.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');
		// Get Form
		$questionsObj = $questionsHandler->get($questId);
		$form = $questionsObj->getFormQuestions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

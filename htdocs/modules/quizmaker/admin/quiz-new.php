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

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JANUS;

        $clPerms->checkAndRedirect('create_quiz', $catId,'$catId', "quiz.php?op=list&cat_id={$catId}");

		$templateMain = 'quizmaker_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUIZ_LIST, 'quiz.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$quizObj = $quizHandler->create();
        $quizObj->setVar('quiz_weight', $quizHandler->getMax("quiz_weight", $catId) + 10);
        $quizObj->setVar('quiz_cat_id', $catId);

		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

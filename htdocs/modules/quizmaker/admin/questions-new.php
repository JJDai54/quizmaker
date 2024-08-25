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


        $pluginName = Request::getString('quest_plugin', "");
        if(!$pluginsHandler->exists($pluginName))
            redirect_header("questions.php?op=list&quiz_id={$quizId}", 5, _AM_QUIZMAKER_PLUGIN_NOT_EXISTS);
		
        $templateMain = 'quizmaker_admin_questions.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUESTIONS_LIST, 'questions.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$questionsObj = $questionsHandler->create();
        if ($op == 'new'){

            $questionsObj->setVar('quest_quiz_id', $quizId);
            $questionsObj->setVar('quest_plugin', $pluginName);
            $questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
            $questionsObj->setVar('quest_timer', 0);
            $questionsObj->setVar('quest_start_timer', 0);
            $questionsObj->setVar('quest_visible', 1);
            $questionsObj->setVar('quest_actif', 1);
            $questionsObj->setVar('quest_parent_id', Request::getInt('quest_parent_id', 0));

        }else if($op == 'addingroup'){

        }
        
		//$form = $questionsObj->getFormQuestions(false, $quizId, $pluginName);
		$form = $questionsObj->getFormQuestions(false, $sender);
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
    


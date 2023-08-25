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
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;

  $questionsObj = $questionsHandler->get($questId);
  $questQuiz_id = $questionsObj->getVar('quest_quiz_id');
  
  if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
  	if (!$GLOBALS['xoopsSecurity']->check()) {
  		redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_type_question), 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
  	}
  	if ($questionsHandler->deleteCascade($questionsObj)) {
  		redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_type_question), 3, _AM_QUIZMAKER_FORM_DELETE_OK);
  	} else {
  		$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
  	}
  } else {
  	xoops_confirm(['ok' => 1, 'quest_id' => $questId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $questionsObj->getVar('quest_quiz_id')));
  }

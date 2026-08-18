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

  $questionsObj = $questionsHandler->get($questId);
  $questQuiz_id = $questionsObj->getVar('quest_quiz_id');
  
  if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
  	if (!$GLOBALS['xoopsSecurity']->check()) {
  		redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_plugin), 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
  	}

    $quizIdTo = $quizmakerHelper->getConfig('action_on_quest_deleted');
    //recherche d'une page de réponse
    $quiquestIds = array($questId);
    $pageReponse = $questionsObj->getPageReponse();
    if($pageReponse) $quiquestIds[] = $pageReponse->getVar('quest_id');
//     echoArray($quiquestIds);
    
    //
    if($quizIdTo > 0 && $quizIdTo != $questQuiz_id){
        $quizIdTo = $quizmakerHelper->getConfig('action_on_quest_deleted');
    //exit ("quiquestIds = {$quiquestIds} - questQuiz_id = {$questQuiz_id} - quizIdTo = {$quizIdTo}");
        $utility->quiz_import_sql($quiquestIds, $questQuiz_id, $quizIdTo, $toGroup = '');    
        $msg = _AM_QUIZMAKER_FORM_MOVE_OK;
    }else{
        $msg = _AM_QUIZMAKER_FORM_DELETE_OK;
    }
 
  	if ($questionsHandler->deleteCascade($questId)) {
  		redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_plugin), 3, $msg);
  	} else {
  		$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
  	}

    
  } else {
    $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $questionsObj->getVar('quest_id'), $questionsObj->getVar('quest_question'));
  	xoops_confirm(['ok' => 1, 'quest_id' => $questId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
  }

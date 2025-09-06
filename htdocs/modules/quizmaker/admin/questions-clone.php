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

    $clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_CLONE,'QUIZMAKER_PERMIT_CLONE', 'index.php');



    $questionsObj = $questionsHandler->get($questId);
    $quizObj = $quizHandler->get($questionsObj->getVar('quest_quiz_id'));
    $fldJS = $quizObj->getVar('quiz_folderJS');
    $pathImg = $quizObj->getFolderJS(1, 'images');
  
  if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
  	if (!$GLOBALS['xoopsSecurity']->check()) {
  		redirect_header('questions.php?' . getParams2list($questQuiz_id, $quest_plugin), 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
    }



    $newQuestion = $questionsObj->cloneQuestion();
    
    if($questionsObj->getVar('quest_image')){
        $newQuestion->setVar('quest_image',JANUS\FSO\cloneFile($questionsObj->getVar('quest_image'), $pathImg));
    }
    if($questionsObj->getVar('quest_background')){
        $newQuestion->setVar('quest_background',JANUS\FSO\cloneFile($questionsObj->getVar('quest_background'), $pathImg));
    }
   
    
    $questionsHandler->insert($newQuestion, true);
    $newQuestId = $questionsHandler->getInsertId();

    //echo "newId = {$newQuestId}";
     $criteria = new Criteria('answer_quest_id',  $questId);
     $answersAll = $answersHandler->getAll($criteria);
     
 	foreach(array_keys($answersAll) as $i) {
 		$newAns = $answersAll[$i]->cloneAnswer($newQuestId);
        if($newAns->getVar('answer_image1')){
            $newAns->setVar('answer_image1',JANUS\FSO\cloneFile($newAns->getVar('answer_image1'), $pathImg));
        }
        if($newAns->getVar('answer_image2')){
            $newAns->setVar('answer_image2',JANUS\FSO\cloneFile($newAns->getVar('answer_image2'), $pathImg));
        }
        
        
        $answersHandler->insert($newAns);
     }
     $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_CLONE_OK, $newQuestId);
   	redirect_header('questions.php?op=list&' . getParams2list($quizId, $quest_plugin, "", $quest_parent_id), 2, $msg);


  } else {
    $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_CLONE, $questionsObj->getVar('quest_id'), $questionsObj->getVar('quest_question'));
  	xoops_confirm(['ok' => 1, 'quest_id' => $questId, 'op' => 'clone'], $_SERVER['REQUEST_URI'], $msg);
  }



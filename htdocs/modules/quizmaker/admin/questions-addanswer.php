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

    $clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_CLONE,'QUIZMAKER_PERMIT_CLONE', 'index.php', QUIZMAKER_ADMIN_PERM);
    
    
    
    $questionsObj = $questionsHandler->get($questId);
    $quizId = $questionsObj->getVar('quest_quiz_id');
    $identifiant = $questionsObj->getVar('quest_identifiant1');
    
    //verifie si il y a déjà une page info liée à cette question
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
    $criteria->add(new \Criteria('quest_plugin', 'pageAnswer', '='));
    $criteria->add(new \Criteria('quest_identifiant2', $identifiant, '='));
    
    
	$nbEnr = $questionsHandler->getCountQuestions($criteria);

    if ($nbEnr > 0){
      	redirect_header('questions.php?op=list&' . getParams2list($quizId, $quest_plugin, "", $questId), 2, _AM_QUIZMAKER_PAGEINFO_ALREADY_EXISTS);    
    }


    //demande confirmation pour l'ajout d'une page d'information si pas OK
    if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
    
    
    //recupe du chemin des image pour dupliquer celle de la question
    
    $quizObj = $quizHandler->get($questionsObj->getVar('quest_quiz_id'));
    $fldJS = $quizObj->getVar('quiz_folderJS');
    $pathImg = $quizObj->getFolderJS(1, 'images');
    //------------------------------------------------------

    $quest_parent_id = $questionsObj->getVar('quest_parent_id');
    $newPlugin = 'pageAnswer';
    
    $answerObj = $questionsHandler->get(0);
    $answerObj->setVar('quest_plugin', $newPlugin);
    $answerObj->setVar('quest_parent_id', $questionsObj->getVar('quest_id'));
    $answerObj->setVar('quest_parent_id', $quest_parent_id);
    $answerObj->setVar('quest_quiz_id', $questionsObj->getVar('quest_quiz_id'));
    
    if(true){
        $answerObj->setVar('quest_question', _AM_QUIZMAKER_ANSWER_TO_PREVIEW_QUESTION);
        $answerObj->setVar('quest_comment1', $questionsObj->getVar('quest_question'));
    }else{
        $answerObj->setVar('quest_question', $questionsObj->getVar('quest_question'));
        $answerObj->setVar('quest_comment1', $questionsObj->getVar('quest_comment1'));
    }
    
    
    $answerObj->setVar('quest_comment2', $questionsObj->getVar('quest_comment2'));
    $answerObj->setVar('quest_identifiant1', FQUIZMAKER\getNewIdentifiant());
    $answerObj->setVar('quest_identifiant2', $questionsObj->getVar('quest_identifiant1'));
    $answerObj->setVar('quest_posComment1', $questionsObj->getVar('quest_posComment1'));
    $answerObj->setVar('quest_visible', $questionsObj->getVar('quest_visible'));
    $answerObj->setVar('quest_actif', $questionsObj->getVar('quest_actif'));
    //$answerObj->setVar('quest_image', $questionsObj->getVar('quest_image'));
    $answerObj->setVar('quest_image_style', $questionsObj->getVar('quest_image_style'));
    //$answerObj->setVar('quest_background', $questionsObj->getVar('quest_background'));
    $answerObj->setVar('quest_timer', $questionsObj->getVar('quest_timer'));
    $answerObj->setVar('quest_weight', $questionsObj->getVar('quest_weight')+1);
    
    
    $answerObj->setVar('quest_isQuestion', 0);
    $answerObj->setVar('quest_points', 0);
    $answerObj->setVar('quest_zoom', 0);
    
    if($questionsObj->getVar('quest_image')){
        $answerObj->setVar('quest_image',JANUS\FSO\cloneFile($questionsObj->getVar('quest_image'), $pathImg));
    }
    if($questionsObj->getVar('quest_background')){
        $answerObj->setVar('quest_background',JANUS\FSO\cloneFile($questionsObj->getVar('quest_background'), $pathImg));
    }

    //enregistrement de la nouvelle pageInfo et recuperation de son Id
	$questionsHandler->insert($answerObj);
    $answerObjId = $answerObj->getVar('quest_id');
    $questionsObj->setVar('quest_reference_id', $answerObjId);
    $questionsHandler->insert($questionsObj);
    
    //Ajout d'une proposition avec l'explication de la question en référence
    $newAns = $answersHandler->get(0);
    $newAns->setVar('answer_quest_id', $answerObjId);
    $newAns->setVar('answer_proposition', $questionsObj->getVar('quest_explanation'));
    $newAns->setVar('answer_weight', 0);
    $newAns->setVar('answer_points', 0);
    $newAns->setVar('answer_inputs', 0);
    $newAns->setVar('answer_group', 0);
    $newAns->setVar('answer_buffer', '');
    $newAns->setVar('answer_image1', '');
    $newAns->setVar('answer_image2', '');
    $newAns->setVar('answer_color', '');
    $newAns->setVar('answer_background', '');
    $newAns->setVar('answer_caption', '');
    
    //enregistrement de la proposition
    $answersHandler->insert($newAns);
    
    
     $msg = _AM_QUIZMAKER_PAGEINFO_ADDED_OK;
   	//redirect_header('questions.php?op=edit&' . getParams2list($quizId, $quest_plugin, "", 999), 2, $msg);
   	redirect_header('questions.php?op=list&' . getParams2list2($answerObj, $quizObj->getVar('quiz_subject'), ""), 2, $msg);    
    
    } else {
        $msg = sprintf(_AM_QUIZMAKER_PAGEINFO_CONFIRM_ADD, $questionsObj->getVar('quest_id'), $questionsObj->getVar('quest_question'));
    	xoops_confirm(['ok' => 1, 'quest_id' => $questId, 'op' => 'addanswer'], $_SERVER['REQUEST_URI'], $msg);
    }

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

//   echo "<hr>POST<pre>" . print_r($_POST, true) . "</pre><hr>";
//
   
/*
    exit;
echo "<hr>questId ===>zzz " . $questId . "<br>"; 
*/
		// Security Check

// 		if (!$GLOBALS['xoopsSecurity']->check()) {
// 			redirect_header('questions.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
// 		}
		if ($questId > 0) {
			$questionsObj = $questionsHandler->get($questId);
		} else {
			$questionsObj = $questionsHandler->create();
    		$questionsObj->setVar('quest_creation', \JJD\getSqlDate());
		}
		// Set Vars
        $typeQuestion = Request::getString('quest_type_question', '');
		$questionsObj->setVar('quest_type_question', $typeQuestion);
        $quizId = Request::getInt('quest_quiz_id', 0);
		$quest_parent_id = Request::getInt('quest_parent_id', 0);
		$questionsObj->setVar('quest_parent_id', Request::getInt('quest_parent_id', 0));
		$questionsObj->setVar('quest_quiz_id', $quizId);
		$questionsObj->setVar('quest_question', Request::getString('quest_question', ''));
		$questionsObj->setVar('quest_identifiant', Request::getString('quest_identifiant', ''));
        
        $options = Request::getArray('quest_options', null);
//echoArray($options,'options',true);        
//echoArray($_POST,'options',true);        
		//$questionsObj->setVar('quest_options', implode('|', $options));
		$questionsObj->setVar('quest_options', json_encode($options));
        
        
        
		//$questionsObj->setVar('quest_options', Request::getString('quest_options', ''));
		$questionsObj->setVar('quest_comment1', Request::getText('quest_comment1', ''));
		$questionsObj->setVar('quest_explanation', Request::getText('quest_explanation', ''));
		$questionsObj->setVar('quest_consigne', Request::getText('quest_consigne', ''));
		$questionsObj->setVar('quest_learn_more', Request::getString('quest_learn_more', ''));
		$questionsObj->setVar('quest_see_also', Request::getString('quest_see_also', ''));
		$questionsObj->setVar('quest_points', Request::getInt('quest_points', 0));
		$questionsObj->setVar('quest_height', Request::getInt('quest_height', 0));
		$questionsObj->setVar('quest_numbering', Request::getInt('quest_numbering', 2));
		$questionsObj->setVar('quest_shuffleAnswers', Request::getInt('quest_shuffleAnswers', 1));
		$questionsObj->setVar('quest_weight', Request::getInt('quest_weight', 0));
		$questionsObj->setVar('quest_timer', Request::getInt('quest_timer', 0));
		$questionsObj->setVar('quest_visible', Request::getInt('quest_visible', 1));
		$questionsObj->setVar('quest_actif', Request::getInt('quest_actif', 1));
		$questionsObj->setVar('quest_update', \JJD\getSqlDate());

//exit ("===> poids = " .+ Request::getInt('quest_weight', 0));

        // --- gestion de limage de la question
        $cls = $type_questionHandler->getClassTypeQuestion($typeQuestion);
        $quiz = $quizHandler->get($quizId);
        $folderJS = $quiz->getVar('quiz_folderJS');
        $path = QUIZMAKER_UPLOAD_PATH . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";  
        
        //recupe de la nouvelle image si elle a ete selectionée
        $questImage = $cls->save_img($ans, 'quest_image', $path, $folderJS, 'question', $nameOrg);
        
        //suppression de l'image existant si besoin si la case a ete coche 
        //ou si le nom de la nouvelle image est différent de l'ancienne
        $delImage = Request::getInt('del_image', 0);
        if($delImage == 1 || $questImage){
            $fullName = $path . '/' . $questionsObj->getVar('quest_image');
            unlink($fullName);
        }
        //enregistrement de l'image
        if ($questImage) $questionsObj->setVar('quest_image', $questImage);

//exit("{$path} |===> {$questImage} |===> {$nameOrg}");
		// Insert Data
		if ($questionsHandler->insert($questionsObj)) {
            $questId = $questionsObj->getVar('quest_id');
//echo "<hr>questId ===> " . $questId ; exit;

            $cls->saveAnswers(Request::getArray('answers', []), $questId, $quizId);
        
       
//echo "<hr>" .  getParams2list($quizId, $quest_type_question); exit;
         $questionsHandler->incrementeWeight($quizId);
//=============================================================================
          if ($addNew)
			redirect_header('questions.php?op=new&' . getParams2list($quizId, $quest_type_question, "", $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);
          else if ($sender == 'type_question_list')
			redirect_header('type_question.php?op=list&' . getParams2list($quizId, $quest_type_question, $sender, $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);
          else
			redirect_header('questions.php?op=list&' . getParams2list($quizId, $quest_type_question, "", $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);

            
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
		$form = $questionsObj->getFormQuestions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

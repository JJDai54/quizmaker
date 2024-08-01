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
//use JJD;


		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('quiz.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($quizId > 0) {
            $clPerms->checkAndRedirect('edit_quiz', $catId,'$catId', "quiz.php?op=list&cat_id={$catId}");
			$quizObj = $quizHandler->get($quizId);
		} else {
            $clPerms->checkAndRedirect('create_quiz', $catId,'$catId', "quiz.php?op=list&cat_id={$catId}");
			$quizObj = $quizHandler->create();
    		$quizObj->setVar('quiz_creation', \JJD\getSqlDate());
		}
		$quizObj->setVar('quiz_update', \JJD\getSqlDate());
		// Set Vars
		$quizObj->setVar('quiz_cat_id', Request::getInt('quiz_cat_id', 0));
		$quizObj->setVar('quiz_name', Request::getString('quiz_name', ''));
		$quizObj->setVar('quiz_author', Request::getString('quiz_author', ''));
        $oldFolder = $quizObj->getVar('quiz_folderJS');
        $newFolder = Request::getString('quiz_folderJS', '');
		$quizObj->setVar('quiz_folderJS',  $newFolder);
		$quizObj->setVar('quiz_description', Request::getText('quiz_description', ''));
		$quizObj->setVar('quiz_weight', Request::getInt('quiz_weight', 0));
        
		$QuizDateBeginArr = Request::getArray('quiz_dateBegin');
		//$QuizDateBegin = strtotime($QuizDateBeginArr['date']) + (int)$QuizDateBeginArr['time'];
	    //$quizObj->setVar('quiz_dateBegin', $QuizDateBegin);
		//$quizObj->setVar('quiz_dateBegin', \JJD\getSqlDate($QuizDateBegin));
		$quizObj->setVar('quiz_dateBegin', \JJD\getSqlDate($QuizDateBeginArr));
        
		$QuizDateEndArr = Request::getArray('quiz_dateEnd');
		//$QuizDateEnd = strtotime($QuizDateEndArr['date']) + (int)$QuizDateEndArr['time'];
		//$quizObj->setVar('quiz_dateEnd', $QuizDateEnd);
		//$quizObj->setVar('quiz_dateEnd', \JJD\getSqlDate($QuizDateEnd));
		$quizObj->setVar('quiz_dateEnd', \JJD\getSqlDate($QuizDateEndArr));
                
		$quizObj->setVar('quiz_publishResults',    Request::getInt('quiz_publishResults', 0));
		$quizObj->setVar('quiz_publishAnswers',    Request::getInt('quiz_publishAnswers', 0));
		$quizObj->setVar('quiz_publishQuiz',       Request::getInt('quiz_publishQuiz', 0));
		$quizObj->setVar('quiz_theme',             Request::getString('quiz_theme', 'defaut'));
		$quizObj->setVar('quiz_questPosComment1',  Request::getInt('quiz_questPosComment1', 1));
		$quizObj->setVar('quiz_legend',            Request::getText('quiz_legend', ''));
		$quizObj->setVar('quiz_dateBeginOk',       Request::getInt('quiz_dateBeginOk', 0));
		$quizObj->setVar('quiz_dateEndOk',         Request::getInt('quiz_dateEndOk', 0));
		$quizObj->setVar('quiz_build',             Request::getInt('quiz_build', 0));
		$quizObj->setVar('quiz_optionsIhm',        Request::getInt('quiz_optionsIhm', 0));
		$quizObj->setVar('quiz_optionsDev',        Request::getInt('quiz_optionsDev', 0));
		$quizObj->setVar('quiz_actif',             Request::getInt('quiz_actif', 1));
		$quizObj->setVar('quiz_showConsigne',      Request::getInt('quiz_showConsigne', 0)) ;
		$quizObj->setVar('quiz_showTimer',         Request::getInt('quiz_showTimer', 0)) ;
		$quizObj->setVar('quiz_libBegin',          Request::getString('quiz_libBegin', _CO_QUIZMAKER_LIB_BEGIN_DEFAULT));
		$quizObj->setVar('quiz_libEnd',            Request::getString('quiz_libEnd', _CO_QUIZMAKER_LIB_END_DEFAULT));

		// Insert Data
		if ($quizHandler->insert($quizObj)) {
    		if ($quizId == 0) {
			 $quizId = $quizObj->getNewInsertedIdQuiz();
             $newQuiz = true;
            }else{$newQuiz = false;}
            
		// Set Vars
        if($newQuiz){
             //------------------------------------------------------------------
             //ajout automatique des pages d'info et de résultat
             //------------------------------------------------------------------
             // page de présentation
             $questionsObj = $questionsHandler->create();
             $questionsObj->setVar('quest_quiz_id', $quizId);
             $questionsObj->setVar('quest_plugin', 'pageBegin');
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', -99999);
             $questionsObj->setVar('quest_timer', 0);
             $questionsObj->setVar('quest_start_timer', 0);
             $questionsObj->setVar('quest_visible', 1);
             $questionsObj->setVar('quest_actif', 1);
             $questionsObj->setVar('quest_parent_id', 0);
             $questionsObj->setVar('quest_question', _AM_QUIZMAKER_QUIZ_PRESENTATION);
             $questionsObj->setVar('quest_identifiant', 'slide_' . rand(10000,100000));
		     $questionsHandler->insert($questionsObj);      
			 $questId = $questionsObj->getNewInsertedIdQuestions();
             
             $answerObj = $answersHandler->create();
             $answerObj->setVar('answer_quest_id',$questId);
             $answerObj->setVar('answer_proposition', _AM_QUIZMAKER_PAGEBEGIN_DEFAULT1);
             $answerObj->setVar('answer_weight',0);
		     $answersHandler->insert($answerObj);             
             
             //------------------------------------------------------------------
             // page de résultats
             //--------------------------             
             $questionsObj = $questionsHandler->create();
             $questionsObj->setVar('quest_quiz_id', $quizId);
             $questionsObj->setVar('quest_plugin', 'pageEnd');
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', 9999);
             $questionsObj->setVar('quest_timer', 0);
             $questionsObj->setVar('quest_start_timer', 0);
             $questionsObj->setVar('quest_visible', 1);
             $questionsObj->setVar('quest_actif', 1);
             $questionsObj->setVar('quest_parent_id', 0);
             $questionsObj->setVar('quest_question', _AM_QUIZMAKER_QUIZ_RESULTATS);
             $questionsObj->setVar('quest_identifiant', 'slide_' . rand(10000,100000));
		     $questionsHandler->insert($questionsObj);      
			 $questId = $questionsObj->getNewInsertedIdQuestions();
             
             $answerObj = $answersHandler->create();
             $answerObj->setVar('answer_quest_id',$questId);
             $answerObj->setVar('answer_proposition', _AM_QUIZMAKER_QUIZ_RESULTATS_DESC);
             $answerObj->setVar('answer_weight',0);
		     $answersHandler->insert($answerObj);  
                        
        }else if ($oldFolder !== $newFolder){
            //Le quiz a changé de dossier qu'il faut renomer
            rename(QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $oldFolder, QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $newFolder);
        }
       
         
            $url = "quiz.php?op=list&quiz_id={$quizId}&sender=";
			redirect_header("quiz.php?op=list&quiz_id={$quizId}&sender=", 2, _AM_QUIZMAKER_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $quizObj->getHtmlErrors());
		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
        

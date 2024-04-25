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
use XoopsModules\Quizmaker\Utility;
//use JJD;

		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('quiz.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($quizId > 0) {
			$quizObj = $quizHandler->get($quizId);
		} else {
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
		$quizObj->setVar('quiz_showAllSolutions',  Request::getInt('quiz_showAllSolutions', 0));
		$quizObj->setVar('quiz_publishQuiz',       Request::getInt('quiz_publishQuiz', 0));
		$quizObj->setVar('quiz_theme',             Request::getString('quiz_theme', 'defaut'));
		$quizObj->setVar('quiz_questPosComment1',  Request::getInt('quiz_questPosComment1', 1));
		$quizObj->setVar('quiz_answerBeforeNext',  Request::getInt('quiz_answerBeforeNext', 0));
		$quizObj->setVar('quiz_allowedPrevious',   Request::getInt('quiz_allowedPrevious', 0));
		$quizObj->setVar('quiz_allowedSubmit',     Request::getInt('quiz_allowedSubmit', 0));
		$quizObj->setVar('quiz_showScoreMinMax',   Request::getInt('quiz_showScoreMinMax', 0));
		$quizObj->setVar('quiz_shuffleQuestions',  Request::getInt('quiz_shuffleQuestions', 0));
		$quizObj->setVar('quiz_showGoodAnswers',   Request::getInt('quiz_showGoodAnswers', 0));
		$quizObj->setVar('quiz_showBadAnswers',    Request::getInt('quiz_showBadAnswers', 0));
		$quizObj->setVar('quiz_showReloadAnswers', Request::getInt('quiz_showReloadAnswers', 0));
		$quizObj->setVar('quiz_showGoToSlide',     Request::getInt('quiz_showGoToSlide', 0));
		$quizObj->setVar('quiz_minusOnShowGoodAnswers', Request::getInt('quiz_minusOnShowGoodAnswers', 0));
		$quizObj->setVar('quiz_useTimer',          Request::getInt('quiz_useTimer', 0));
		$quizObj->setVar('quiz_showResultPopup',   Request::getInt('quiz_showResultPopup', 0));
		$quizObj->setVar('quiz_showTypeQuestion',  Request::getInt('quiz_showTypeQuestion', 0));
		$quizObj->setVar('quiz_showResultAllways', Request::getInt('quiz_showResultAllways', 0));
		$quizObj->setVar('quiz_showReponsesBottom', Request::getInt('quiz_showReponsesBottom', 0));
		$quizObj->setVar('quiz_showLog',           Request::getInt('quiz_showLog', 0));
		$quizObj->setVar('quiz_legend',            Request::getText('quiz_legend', ''));
		$quizObj->setVar('quiz_dateBeginOk',       Request::getInt('quiz_dateBeginOk', 0));
		$quizObj->setVar('quiz_dateEndOk',         Request::getInt('quiz_dateEndOk', 0));
		$quizObj->setVar('quiz_build',             Request::getInt('quiz_build', 0));
		$quizObj->setVar('quiz_actif',             Request::getInt('quiz_actif', 1));
		$quizObj->setVar('quiz_showConsigne',        Request::getInt('quiz_showConsigne', 0)) ;
        //exit;
        
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
             $questionsObj->setVar('quest_type_question', 'pageBegin');
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', -99999);
             $questionsObj->setVar('quest_visible', 1);
             $questionsObj->setVar('quest_actif', 1);
             $questionsObj->setVar('quest_parent_id', 0);
             $questionsObj->setVar('quest_question', _AM_QUIZMAKER_QUIZ_PRESENTATION);
             $questionsObj->setVar('quest_identifiant', 'slide_' . rand(10000,100000));
		     $questionsHandler->insert($questionsObj);      
			 $questId = $questionsObj->getNewInsertedIdQuestions();
             
             $answerObj = $answersHandler->create();
             $answerObj->setVar('answer_quest_id',$questId);
             $answerObj->setVar('answer_proposition', _AM_QUIZMAKER_QUIZ_PRESENTATION);
             $answerObj->setVar('answer_weight',0);
		     $answersHandler->insert($answerObj);             
             
             //------------------------------------------------------------------
             // page de résultats
             //--------------------------             
             $questionsObj = $questionsHandler->create();
             $questionsObj->setVar('quest_quiz_id', $quizId);
             $questionsObj->setVar('quest_type_question', 'pageEnd');
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', 9999);
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

        
//             exit;       
/* ==================================================================


===================================================================== */        

/*
            $lanquage = $xoopsConfig['language'];
            //$f = XOOPS_ROOT_PATH . "/modules/quizmaker/language/{$lanquage}/slide/slide_resultats.html";
            $f = QUIZMAKER_PATH_MODULE . "/language/{$lanquage}/slide/slide_resultats.html";
            $slideresultats = $quizUtility->loadTextFile($f);
echo "<hr>{$f}<hr>{$slideresultats}<hr>";    
*/            
        

            
        //echo "<hr>quiz : {$quizId}<br>newQuizId : {$newQuizId}<hr>";exit;
			//$permId = isset($_REQUEST['quiz_id']) ? $quizId : $newQuizId;
			$permId = $quizId;
			$grouppermHandler = xoops_getHandler('groupperm');
			$mid = $GLOBALS['xoopsModule']->getVar('mid');
			// Permission to view_quiz
			$grouppermHandler->deleteByModule($mid, 'quizmaker_view_quiz', $permId);
			if (isset($_POST['groups_view_quiz'])) {
				foreach($_POST['groups_view_quiz'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_view_quiz', $permId, $onegroupId, $mid);
				}
			}
			// Permission to submit_quiz
			$grouppermHandler->deleteByModule($mid, 'quizmaker_submit_quiz', $permId);
			if (isset($_POST['groups_submit_quiz'])) {
				foreach($_POST['groups_submit_quiz'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_submit_quiz', $permId, $onegroupId, $mid);
				}
			}
			// Permission to approve_quiz
			$grouppermHandler->deleteByModule($mid, 'quizmaker_approve_quiz', $permId);
			if (isset($_POST['groups_approve_quiz'])) {
				foreach($_POST['groups_approve_quiz'] as $onegroupId) {
					$grouppermHandler->addRight('quizmaker_approve_quiz', $permId, $onegroupId, $mid);
				}
			}
            $url = "quiz.php?op=list&quiz_id={$quizId}&sender=";
			redirect_header("quiz.php?op=list&quiz_id={$quizId}&sender=", 2, _AM_QUIZMAKER_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $quizObj->getHtmlErrors());
		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

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
 * QuizMaker module for xoops
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

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz_solutions.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op     = Request::getCmd('op', 'list');
$quizId = Request::getInt('quiz_id', 0);
$start = 0; //Request::getInt('start', 0);
$limit = 0; //Request::getInt('limit', $helper->getConfig('adminpager'));
$resultId = Request::getInt('result_id', 0);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);



// Check permissions
// if (!$permissionsHandler->getPermGlobalSubmit()) {
// 	redirect_header('quiz.php?op=list', 3, _NOPERM);
//}
// Check params
if (0 == $quizId) {
	redirect_header('categories.php?op=list', 3, _MA_QUIZMAKER_INVALID_PARAM);
}
///////////////////////////////////////////////////
//     $rootApp = QUIZMAKER_QUIZ_JS_PATH . "/quiz-js";
//     $urlApp  = QUIZMAKER_QUIZ_JS_URL  . "/quiz-js";
// 
//     //insertion des CSS
//     $tCss = \JJD\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//     $urlCss = QUIZMAKER_QUIZ_JS_URL. "/quiz-js/css";
//     foreach($tCss as $css){
// 		$GLOBALS['xoTheme']->addStylesheet($urlCss .'/'. $css , null );    
//     }
//     //----------------------------------------------
//     //insertion du prototype des tpl
//     $xoTheme->addScript($urlApp . '/' . 'slide__prototype.js');    


		$quizObj = $quizHandler->get($quizId);
        $quiz = $quizObj->getValuesQuiz();
        $catId = $quizObj->getVar('quiz_cat_id');
        
        //Recupe des questions du quiz
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('quest_quiz_id',$quizId, "="));
        
        $questionsCount = $questionsHandler->getCountQuestions($criteria);
        //ça ne devrait pas arrivé mais au cas ou, pas de question retour aux categories
		if ($questionsCount == 0) 
	           redirect_header('categories.php?op=list', 3, _MA_QUIZMAKER_INVALID_PARAM);
        $questionsAll = $questionsHandler->getAllQuestions($criteria, $start, $limit, 'quest_weight ASC, quest_question');
        
        //chargement des données des questions
        $questions = array();
        $numQuestion = 0;
		foreach(array_keys($questionsAll) as $i) {
			$question = $questionsAll[$i]->getValuesQuestions();
            $question['solutions'] = $questionsAll[$i]->getSolutions();
            $question['solutions']['libScoreMax'] = sprintf(_CO_QUIZMAKER_POINTS_FOR_ANSWER2, $question['solutions']['scoreMin'], $question['solutions']['scoreMax']);
            if($question['type_question'] !== 'pageInfo') 
                $question['numQuestion'] = ++$numQuestion;
            else
                $question['numQuestion'] = 0;
            
            //chargement des réponses correctes   pour chaque question
            $questions[] = $question;
		}
        
		//	$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_QUESTIONS);        
// Define Stylesheet
$style1 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-color.css";
$style2 =  QUIZMAKER_QUIZ_JS_URL . "/css/style-item-design.css";
$GLOBALS['xoTheme']->addStylesheet( $style1, null );
$GLOBALS['xoTheme']->addStylesheet( $style2, null );
		$xoopsTpl->assign('quiz', $quiz);        
		$xoopsTpl->assign('questions', $questions);        
		$xoopsTpl->assign('admin', true);        
		$xoopsTpl->assign('modPathIcon16', $modPathIcon16);        

$GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_SOLUTIONS];

//---------------------------------------------------
//  ajout du resultat du participant le cas échéant
//----------------------------------------------------
    $tResult = array();
    $tResult['result_id'] = $resultId;
	$xoopsTpl->assign('result', $tResult);        
//echo "<hr>Result <pre>" . print_r($tResult, true) . "</pre><hr>";
//exit;
//----------------------------------------------------



require __DIR__ . '/footer.php';

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


require __DIR__ . '/header.php';

use Xmf\Request;
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JJD;

\JJD\loadAllXForms();

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$catId  = Request::getInt('cat_id', 1);
$quizId = Request::getInt('quiz_id');
$sender = Request::getString('');

$utility = new \XoopsModules\Quizmaker\Utility();  
//  $gp = array_merge($_GET, $_POST);
//  echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

//echo "quizId = {$quizId}<br>sender = {$sender}";

switch($op) {
	case 'list':
	default:
if($quizId > 0 && $sender != 'cat_id'){
  $quiz = $quizHandler->get($quizId);
  $catId = $quiz->getVar('quiz_cat_id');
}else{
    $quizId = $quizHandler->getFirstIdOfParent($catId);    
}
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUIZ, 'quiz.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$quizCount = $quizHandler->getCountQuiz();
        
        $criteria = new CriteriaCompo();
        if ($catId > 0)
        $criteria->add(new Criteria('quiz_cat_id',$catId));
		$quizAll = $quizHandler->getAllQuiz($criteria, $start, $limit);
		$GLOBALS['xoopsTpl']->assign('quiz_count', $quizCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);

      // ----- Listes de selection pour filtrage -----  
      $cat = $categoriesHandler->getListKeyName(null, true, false);
      $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
      $inpCategory->addOptionArray($cat);
      $inpCategory->setExtra('onchange="document.quizmaker_select_filter.submit()"');
	  $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
       
     // ----- /Listes de selection pour filtrage -----        
	  //pour affichage de la categorie dans la liste
      $GLOBALS['xoopsTpl']->assign('cat', $cat);

        
		
        // Table view quiz
		if ($quizCount > 0) {
			foreach(array_keys($quizAll) as $i) {
				$Quiz = $quizAll[$i]->getValuesQuiz();
				$GLOBALS['xoopsTpl']->append('quiz_list', $Quiz);
				unset($Quiz);
			}
			// Display Navigation
			if ($quizCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($quizCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_QUIZ);
		}
        
	break;
	case 'new':
		$templateMain = 'quizmaker_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_QUIZ_LIST, 'quiz.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$quizObj = $quizHandler->create();
		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
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
		$quizObj->setVar('quiz_fileName', Request::getString('quiz_fileName', ''));
		$quizObj->setVar('quiz_description', Request::getText('quiz_description', ''));
        
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
                
		$quizObj->setVar('quiz_publishResults', Request::getInt('quiz_publishResults', 0));
		$quizObj->setVar('quiz_publishAnswers', Request::getInt('quiz_publishAnswers', 0));
		$quizObj->setVar('quiz_publishQuiz', Request::getInt('quiz_publishQuiz', 0));
		$quizObj->setVar('quiz_onClickSimple', Request::getInt('quiz_onClickSimple', 0));
		$quizObj->setVar('quiz_theme', Request::getString('quiz_theme', 'defaut'));
		$quizObj->setVar('quiz_answerBeforeNext', Request::getInt('quiz_answerBeforeNext', 0));
		$quizObj->setVar('quiz_allowedPrevious', Request::getInt('quiz_allowedPrevious', 0));
		$quizObj->setVar('quiz_allowedSubmit', Request::getInt('quiz_allowedSubmit', 0));
		$quizObj->setVar('quiz_shuffleQuestions', Request::getInt('quiz_shuffleQuestions', 0));
		$quizObj->setVar('quiz_showGoodAnswers', Request::getInt('quiz_showGoodAnswers', 0));
		$quizObj->setVar('quiz_showBadAnswers', Request::getInt('quiz_showBadAnswers', 0));
		$quizObj->setVar('quiz_showReloadAnswers', Request::getInt('quiz_showReloadAnswers', 0));
		$quizObj->setVar('quiz_minusOnShowGoodAnswers', Request::getInt('quiz_minusOnShowGoodAnswers', 0));
		$quizObj->setVar('quiz_useTimer', Request::getInt('quiz_useTimer', 0));
		$quizObj->setVar('quiz_showResultPopup', Request::getInt('quiz_showResultPopup', 0));
		$quizObj->setVar('quiz_showTypeQuestion', Request::getInt('quiz_showTypeQuestion', 0));
		$quizObj->setVar('quiz_showResultAllways', Request::getInt('quiz_showResultAllways', 0));
		$quizObj->setVar('quiz_showReponsesBottom', Request::getInt('quiz_showReponsesBottom', 0));
		$quizObj->setVar('quiz_showLog', Request::getInt('quiz_showLog', 0));
		$quizObj->setVar('quiz_legend', Request::getText('quiz_legend', ''));
		$quizObj->setVar('quiz_dateBeginOk', Request::getInt('quiz_dateBeginOk', 0));
		$quizObj->setVar('quiz_dateEndOk', Request::getInt('quiz_dateEndOk', 0));
		$quizObj->setVar('quiz_build', Request::getInt('quiz_build', 0));
		$quizObj->setVar('quiz_actif', Request::getInt('quiz_actif', 1));
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
             $questionsObj->setVar('quest_type_question', 'pageInfo');
             $questionsObj->setVar('quest_type_form', QUIZMAKER_TYPE_FORM_INTRO);
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', 0);
             $questionsObj->setVar('quest_visible', 1);
             $questionsObj->setVar('quest_actif', 1);
             $questionsObj->setVar('quest_parent_id', 0);
             $questionsObj->setVar('quest_question', _AM_QUIZMAKER_QUIZ_PRESENTATION);
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
             $questionsObj->setVar('quest_type_question', 'pageInfo');
             $questionsObj->setVar('quest_type_form',QUIZMAKER_TYPE_FORM_RESULT );
             //$questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizId) + 10);
             $questionsObj->setVar('quest_weight', 9999);
             $questionsObj->setVar('quest_visible', 1);
             $questionsObj->setVar('quest_actif', 1);
             $questionsObj->setVar('quest_parent_id', 0);
             $questionsObj->setVar('quest_question', _AM_QUIZMAKER_QUIZ_RESULTATS);
		     $questionsHandler->insert($questionsObj);      
			 $questId = $questionsObj->getNewInsertedIdQuestions();
             
             $answerObj = $answersHandler->create();
             $answerObj->setVar('answer_quest_id',$questId);
             $answerObj->setVar('answer_proposition', _AM_QUIZMAKER_QUIZ_RESULTATS_DESC);
             $answerObj->setVar('answer_weight',0);
		     $answersHandler->insert($answerObj);             
        }
        
//             exit;       
/* ==================================================================


===================================================================== */        

/*
            $lanquage = $xoopsConfig['language'];
            //$f = XOOPS_ROOT_PATH . "/modules/quizmaker/language/{$lanquage}/slide/slide_resultats.html";
            $f = QUIZMAKER_PATH . "/language/{$lanquage}/slide/slide_resultats.html";
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
	break;
	case 'edit':
		$templateMain = 'quizmaker_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUIZ, 'quiz.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_QUIZ_LIST, 'quiz.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$quizObj = $quizHandler->get($quizId);
		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
		$quizObj = $quizHandler->get($quizId);
		$quizCat_id = $quizObj->getVar('quiz_cat_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($quizHandler->delete($quizObj)) {
				redirect_header('quiz.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $quizObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $quizObj->getVar('quiz_cat_id')));
		}
	break;
    
	case 'export_json':
        $quizUtility::build_quiz($quizId);
		//redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Export effectue");
	break;        
	case 'export':
        $quizUtility->saveData($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("quiz.php?op=list", 5, "Etat de {$field} Changé");
	break;
        
	case 'change_etat':
        $field = Request::getString('field');
        $modulo = Request::getInt('modulo', 2);
        $quizHandler->changeEtat($quizId, $field, $modulo);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Etat de {$field} Changé");
	break;
    
	case 'config_options':
        $config = Request::getInt('config', 0);
        $quizHandler->config_options($quizId, $config);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Options mise à jour");
	break;
    
}
require __DIR__ . '/footer.php';

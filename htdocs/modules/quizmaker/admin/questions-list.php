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

        $quizArr = $quizHandler->getListKeyName($catId);
        if ($quizId == 0 ) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
//echoArray('gp', "===>quizId = {$quizId}");
		$templateMain = 'quizmaker_admin_questions.tpl';
  	    $GLOBALS['xoopsTpl']->assign('buttons', '');
  	    $GLOBALS['xoopsTpl']->assign('form', '');
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
        
        //$download = Request::getInt('download', 0);
        if(!isset($download))  $GLOBALS['xoopsTpl']->assign('download', 0);
        //----------------------------------------------
        //recupe du quiz a afficher
        $quiz = $quizHandler->get($quizId);
        if ($quiz) {
            $quizValues = $quiz->getValuesQuiz();
            $catId = $quiz->getVar('quiz_cat_id');
        }
        
        // ----- Listes de selection pour filtrage -----  
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"'.FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"'.FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
       // ----- /Listes de selection pour filtrage -----     
          
  	    $GLOBALS['xoopsTpl']->assign('cat_id', $catId);
  	    $GLOBALS['xoopsTpl']->assign('quiz_id', $quizId);
  	    $GLOBALS['xoopsTpl']->assign('quest_id', $questId);

        //---------------------------------------------        
        //Liste des types de question
        $imgModelesHeight = 50;
        if (!$quest_type_question) $quest_type_question = 'checkboxSimple';
        $inpTypeQuest = new \XoopsFormSelect(_CO_QUIZMAKER_TYPE_QUESTION, 'quest_type_question', $quest_type_question);
        $inpTypeQuest->addOptionArray($type_questionHandler->getListByGroup(true));
        $inpTypeQuest->setExtra("onchange='reloadImgModeles(\"modelesTypeQuestionId\",{$imgModelesHeight});'".FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
        $GLOBALS['xoopsTpl']->assign('inpTypeQuest', $inpTypeQuest->render());
\JJD\include_highslide(null,"quizmaker");     
$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');
        //liste des images de type de question - a faire
        global $quizUtility, $type_questionHandler;
        // recupe de la classe du type de question
        $clTypeQuestion = $type_questionHandler->getTypeQuestion($quest_type_question);
        if($clTypeQuestion){
          $imgModelesHtml = new \XoopsFormLabel('', $clTypeQuestion->getHtmlImgModeles($imgModelesHeight));  
          $GLOBALS['xoopsTpl']->assign('imgModelesHtml', $imgModelesHtml->render());
        }

        
        //---------------------------------------------        
        //Ajout d'une question selon le type de selectTypeQuestion
        $btnNewQuestion = $quizUtility->getNewBtn('<=== ' . _ADD . '===>', 'new', QUIZMAKER_URL_ICONS."/16/add.png",  _AM_QUIZMAKER_SELECT_TYPE_BEFORE_ADD);
		$GLOBALS['xoopsTpl']->assign('btnNewQuestion', $btnNewQuestion);
        

        //---------------------------------------------       
        //edition du quiz 
        $btnEditQuiz = $quizUtility->getNewBtn(_AM_QUIZMAKER_EDIT_QUIZ, 'edit_quiz', QUIZMAKER_URL_ICONS."/16/edit.png",  _EDIT);
 		$GLOBALS['xoopsTpl']->assign('btnEditQuiz', $btnEditQuiz);
        
        //retour a la categorie
        $btnCategory = $quizUtility->getNewBtn(_AM_QUIZMAKER_CATEGORY, 'goto_category', QUIZMAKER_URL_ICONS."/16/up.png",  _AM_QUIZMAKER_CATEGORY);
		$GLOBALS['xoopsTpl']->assign('btnCategory', $btnCategory);
        
        //---------------------------------------------       
        //update weight 
        $btnInitWeight = $quizUtility->getNewBtn(_AM_QUIZMAKER_COMPUTE_WEIGHT, 'init_weight', QUIZMAKER_URL_ICONS."/16/generer-1.png",  _AM_QUIZMAKER_COMPUTE_WEIGHT);
		$GLOBALS['xoopsTpl']->assign('btnInitWeight', $btnInitWeight);
        //---------------------------------------------      
        //export jSon : génération du quiz en Html
        $btnBuildHtml = $quizUtility->getNewBtn(_AM_QUIZMAKER_BUILD_QUIZ, 'build_quiz', QUIZMAKER_URL_ICONS."/16/film.png",  _AM_QUIZMAKER_BUILD_QUIZ);
		$GLOBALS['xoopsTpl']->assign('btnBuildHtml', $btnBuildHtml);
        //---------------------------------------------        
        //test du quiz : affiche l'icone avec un "?" bleu si le quiz a ete générérer, permet de le tester
        if($quiz && isset($quizValues["quiz_html"])){
            $lib =  _AM_QUIZMAKER_TEST_QUIZ . ' : ' . $quizValues['build'];
            if($quizValues["quiz_html"] != '' ){
                $imgTestHtml = new XoopsFormImg($lib, QUIZMAKER_URL_ICONS . "/32/quiz-1.png", $quizValues["quiz_html"].'?'.FQUIZMAKER\getParamsForQuiz(1) );
            }else{
                $imgTestHtml = new XoopsFormImg($lib, QUIZMAKER_URL_ICONS . "/32/quiz-0.png");
            } 
            $imgTestHtml->setExtra("target='blank'");
            //$btn['imgTest'] = $imgTest->render();
  		    $GLOBALS['xoopsTpl']->assign('imgTestHtml', $imgTestHtml->render());
        }
        
        $btnPurgerImg = $quizUtility->getNewBtn(_AM_QUIZMAKER_PURGER_IMAGES, 'purger_images', QUIZMAKER_URL_ICONS."/16/delete.png",  _AM_QUIZMAKER_QUIZ_PURGER_IMAGES);
		$GLOBALS['xoopsTpl']->assign('btnPurgerImg', $btnPurgerImg);
        
        $btnExportQuiz = $quizUtility->getNewBtn(_AM_QUIZMAKER_EXPORT_YML, 'export_quiz', QUIZMAKER_URL_ICONS."/16/download.png",  _AM_QUIZMAKER_EXPORT_QUIZ_YML);
		$GLOBALS['xoopsTpl']->assign('btnExportQuiz', $btnExportQuiz);

        //---------------------------------------------------
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        //---------------------------------------------------
 //echo "<hr>catid = {$catId} - quizId = {$quizId}<hr>";       
    
        //================================================
        // recupe des infos du quiz
        //if (!$quiz) break;
        //---------------------------------------
		
        /* 
        $adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        */
        if ($quizId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('quest_quiz_id',$quizId, "="));
          $questionsCount = $questionsHandler->getCountQuestions($criteria);
          $questionsAll = $questionsHandler->getAllQuestions($criteria, $start, $limit, 'quest_weight ASC, quest_question');
        }else{
          $questionsCount = 0;
          $questionsAll = null;
        }
// exit("quizId = {$quizId} ->nb questions = {$questionsCount}");       
		$GLOBALS['xoopsTpl']->assign('questions_count', $questionsCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
        

   
        
		// Table view questions
		if ($questionsCount > 0) {
			foreach(array_keys($questionsAll) as $i) {
				$Questions = $questionsAll[$i]->getValuesQuestions();
                
                if($Questions['isQuestion']){
                  $inpPoints = new \XoopsFormNumber('', "quest_list[{$Questions['quest_id']}][points]", 4, 4, $Questions['quest_points']);
                  $inpPoints->setMinMax(0, 20);
                  $Questions['inpPoints'] = $inpPoints->render();
                }else{
                  $inpPoints = new \XoopsFormHidden("quest_list[{$Questions['quest_id']}][points]", $Questions['quest_points']);
                  $Questions['inpPoints'] = $inpPoints->render();
                }
                
                $inpTimer = new \XoopsFormNumber(_AM_QUIZMAKER_TIMER, "quest_list[{$Questions['quest_id']}][timer]", 6, 6, $Questions['quest_timer']);
                $inpTimer->setMinMax(0, QUIZMAKER_TIMER_MAX);
                $Questions['inpTimer'] = $inpTimer->render();
                
				$GLOBALS['xoopsTpl']->append('questions_list', $Questions);
				unset($Questions);
			}
			// Display Navigation
			if ($questionsCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($questionsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_QUESTIONS);
		}
       


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
//echoArray('gp',$quizId);

		$templateMain = 'quizmaker_admin_questions.tpl';
  	    $GLOBALS['xoopsTpl']->assign('buttons', '');
  	    $GLOBALS['xoopsTpl']->assign('form', '');
		// Define Stylesheet
//		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
        
        $download = Request::getInt('download', 0);
        if(!isset($download))  $GLOBALS['xoopsTpl']->assign('download', 0);
        //----------------------------------------------
        //recupe du quiz a afficher
        $quiz = $quizHandler->get($quizId);
        $quizValues = $quiz->getValuesQuiz();
        
        $selectors = FQUIZMAKER\getQuestionsSelectorBO($catId, $quizSubject,$quizDifficulty,$quizId);        
  	    $GLOBALS['xoopsTpl']->assign('selectors', $selectors);
       // ----- /Listes de selection pour filtrage -----     
          
//   	    $GLOBALS['xoopsTpl']->assign('cat_id', $catId);
//   	    $GLOBALS['xoopsTpl']->assign('quiz_id', $quizId);
//   	    $GLOBALS['xoopsTpl']->assign('quest_id', $questId);

        //---------------------------------------------        
        //Liste des types de question
        $imgModelesHeight = 80;
        if (!$quest_plugin) $quest_plugin = 'checkboxSimple';
        $inpTypeQuest = new \XoopsFormSelect(_CO_QUIZMAKER_PLUGIN, 'quest_plugin', $quest_plugin);
        $inpTypeQuest->addOptionArray($pluginsHandler->getListByGroup(true));
        $inpTypeQuest->setExtra("onchange='reloadPluginSnapshoots(\"modelesPluginId\",{$imgModelesHeight});'".FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
        $GLOBALS['xoopsTpl']->assign('inpTypeQuest', $inpTypeQuest->render());
\JANUS\include_highslide(null,"quizmaker");     
$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');
        //liste des images du plugin - a faire
        global $quizUtility, $pluginsHandler;
        // recupe de la classe du plugin
        $clPlugin = $pluginsHandler->getPlugin($quest_plugin);
        if($clPlugin){
          $imgModelesHtml = new \XoopsFormLabel('', $clPlugin->getHtmlImgModeles(null,$imgModelesHeight));  
          $GLOBALS['xoopsTpl']->assign('imgModelesHtml', $imgModelesHtml->render());
        }else{
          $GLOBALS['xoopsTpl']->assign('imgModelesHtml', "");
        }

        //---------------------------------------------        
        //Ajout d'une question selon le type de selectPlugin
        $btnNewQuestion = $quizUtility->getNewBtn('<=== ' . _ADD . '===>', 'new', "{$modUrlIcon16}/add.png",  _AM_QUIZMAKER_SELECT_TYPE_BEFORE_ADD);
		$GLOBALS['xoopsTpl']->assign('btnNewQuestion', $btnNewQuestion);
        
        $inpActions = new XoopsFormSelect('Actions', 'actions');
        $inpActions->addOption('no-action', _AM_QUIZMAKER_ACTIONS);
        $inpActions->addOption('edit_quiz', _AM_QUIZMAKER_EDIT_QUIZ);
        $inpActions->addOption('goto_category', _AM_QUIZMAKER_CATEGORY);
        $inpActions->addOption('init_weight', _AM_QUIZMAKER_COMPUTE_WEIGHT);
        $inpActions->addOption('purger_images', _AM_QUIZMAKER_PURGER_IMAGES);
        $inpActions->addOption('disable_pageanswer', _AM_QUIZMAKER_DISABLE_PAGE_ANSWER);
        $inpActions->addOption('enable_pageanswer', _AM_QUIZMAKER_ENABLE_PAGE_ANSWER);
        $inpActions->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
        $inpActions->setExtra('style="display:inline;width:auto;' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUEST,'',false) . '"');
        //exit('style="display:inline;width:auto;' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUEST,'',false) . '"');
 		$GLOBALS['xoopsTpl']->assign('actions', $inpActions->render());
/*
        //---------------------------------------------       
        //edition du quiz 
        $btnEditQuiz = $quizUtility->getNewBtn(_AM_QUIZMAKER_EDIT_QUIZ, 'edit_quiz', "{$modUrlIcon16}/edit.png",  _EDIT);
 		$GLOBALS['xoopsTpl']->assign('btnEditQuiz', $btnEditQuiz);
        
        //retour a la categorie
        $btnCategory = $quizUtility->getNewBtn(_AM_QUIZMAKER_CATEGORY, 'goto_category', "{$modUrlIcon16}/up.png",  _AM_QUIZMAKER_CATEGORY);
		$GLOBALS['xoopsTpl']->assign('btnCategory', $btnCategory);
        
        //---------------------------------------------       
        //update weight 
        $btnInitWeight = $quizUtility->getNewBtn(_AM_QUIZMAKER_COMPUTE_WEIGHT, 'init_weight', "{$modUrlIcon16}/generer-1.png",  _AM_QUIZMAKER_COMPUTE_WEIGHT);
		$GLOBALS['xoopsTpl']->assign('btnInitWeight', $btnInitWeight);
        
        //---------------------------------------------       
        //purger les images
        $btnPurgerImg = $quizUtility->getNewBtn(_AM_QUIZMAKER_PURGER_IMAGES, 'purger_images', "{$modUrlIcon16}/delete.png",  _AM_QUIZMAKER_QUIZ_PURGER_IMAGES);
		$GLOBALS['xoopsTpl']->assign('btnPurgerImg', $btnPurgerImg);
        
*/        
        //---------------------------------------------      
        //export jSon : génération du quiz en Html
        $btnBuildHtml = $quizUtility->getNewBtn(_AM_QUIZMAKER_BUILD_QUIZ, 'build_quiz', "{$modUrlIcon16}/film.png",  _AM_QUIZMAKER_BUILD_QUIZ);
		$GLOBALS['xoopsTpl']->assign('btnBuildHtml', $btnBuildHtml);
        
        //---------------------------------------------        
        //test du quiz : affiche l'icone avec un "?" bleu si le quiz a ete générérer, permet de le tester
        if($quiz && $quizValues["quiz_html"] != ''){
            //lancement dans le frontOffice
            $lib =  _AM_QUIZMAKER_TEST_QUIZ . ' : ' . $quizValues['build'];
            $url = XOOPS_URL . "/modules/quizmaker/" . QUIZMAKER_DISPLAY_QUIZ . "?op=run&quiz_id={$quizValues['id']}&cat_id={$quizValues['cat_id']}&player_id={$playerId}";
            $imgTestHtml2 = new XoopsFormImg($lib, "{$modUrlIcon32}/quiz-1.png", $url);
            $imgTestHtml2->setExtra("target='blank'");
            
            //lancement dans le backOffice
            $lib =  _AM_QUIZMAKER_TEST_QUIZ . ' : ' . $quizValues['build'];
            $url = $quizValues["quiz_html"].'?'.FQUIZMAKER\getParamsForQuiz(1);
            $imgTestHtml1 = new XoopsFormImg($lib, "{$modUrlIcon32}/quiz-2.png", $url);
            $imgTestHtml1->setExtra("target='blank'");
        }else{
              $imgTestHtml1 = new XoopsFormImg($lib, "{$modUrlIcon32}/quiz-0.png");
              $imgTestHtml2 = new XoopsFormImg($lib, "{$modUrlIcon32}/quiz-0.png");
        }        
  		$GLOBALS['xoopsTpl']->assign('imgTestHtml1', $imgTestHtml1->render());
  		$GLOBALS['xoopsTpl']->assign('imgTestHtml2', $imgTestHtml2->render());
        
     
        $btnExportQuiz = $quizUtility->getNewBtn(_AM_QUIZMAKER_EXPORT_YML, 'export_quiz', "{$modUrlIcon16}/download.png",  _AM_QUIZMAKER_EXPORT_QUIZ_YML);
		$GLOBALS['xoopsTpl']->assign('btnExportQuiz', $btnExportQuiz);

        //---------------------------------------------------
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        //---------------------------------------------------
 //echo "<hr>catid = {$catId} - quizId = {$quizId}<hr>";       
    
        //================================================
        // recupe des infos du quiz
        //if (!$quiz) break;
        //---------------------------------------
        /* ===== ajout du fichier exporter le cas échéan =========== */
        // ajout de la liste des quiz esporté si il en a eu
        $tbl = $quizUtility->getQuizExportArr(3);
        if($tbl){
          $GLOBALS['xoopsTpl']->assign('exportCount', $tbl->countElements());
          $GLOBALS['xoopsTpl']->assign('exportList', $tbl->render());
        }
        /* ********************************************************* */
        
        		
        /* 
        $adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUESTIONS, 'questions.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        */
        $limit = 0;
        if ($quizId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('quest_quiz_id',$quizId, "="));
          $questionsCount = $questionsHandler->getCountQuestions($criteria);
          $questionsAll = $questionsHandler->getAllQuestions($criteria, $start, $limit, 'quest_weight ASC, quest_question');
          //exit;
        }else{
          $questionsCount = 0;
          $questionsAll = null;
        }
// exit("quizId = {$quizId} ->nb questions = {$questionsCount}");       
		$GLOBALS['xoopsTpl']->assign('questions_count', $questionsCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		$GLOBALS['xoopsTpl']->assign('update_quiz_id', $quizId);
		$GLOBALS['xoopsTpl']->assign('update_cat_id', $catId);
        
		$GLOBALS['xoopsTpl']->assign('isAdmin', $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()));
   
        
		// Table view questions
        //echo "<hr>nb question = {$questionsCount}<hr>";
		if ($questionsCount > 0) {
			foreach(array_keys($questionsAll) as $i) {
				$Questions = $questionsAll[$i]->getValuesQuestions();
//echoArray($Questions['optionsArr']);
                $Questions['variant'] = (isset($Questions['optionsArr']['variant']) && $Questions['optionsArr']['variant'])  ? $Questions['optionsArr']['variant'] : $Questions['typeForm_lib']; //_AM_QUIZMAKER_QUESTION
        
        //echo "<hr>nb question = {$Questions[]}<hr>";
                
                if($Questions['isQuestion']){
                  $inpPoints = new \XoopsFormNumber('', "quest_list[{$Questions['quest_id']}][points]", 4, 4, $Questions['quest_points']);
                  $inpPoints->setMinMax(0, 20);
                  $Questions['inpPoints'] = $inpPoints->render();
                }else{
                  $inpPoints = new \XoopsFormHidden("quest_list[{$Questions['quest_id']}][points]", $Questions['quest_points']);
                  $Questions['inpPoints'] = $inpPoints->render();
                }
                
                if( $Questions['typeForm'] == QUIZMAKER_TYPE_FORM_ANSWER){
                 $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT, "quest_list[{$Questions['quest_id']}][weight]", 6, 6, $Questions['quest_weight']);
                 $inpWeight->setMinMax(0, 9999);
                 $inpWeight->setExtra("style='visibility:hidden;'");
                 $Questions['inpWeight'] = $inpWeight->render();
               }else{
                 $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT, "quest_list[{$Questions['quest_id']}][weight]", 6, 6, $Questions['quest_weight']);
                 $inpWeight->setMinMax(0, 9999);
                 $Questions['inpWeight'] = $inpWeight->render();
               }
                
                $inpTimer = new \XoopsFormNumber(_AM_QUIZMAKER_TIMER, "quest_list[{$Questions['quest_id']}][timer]", 6, 6, $Questions['quest_timer']);
                $inpTimer->setMinMax(0, QUIZMAKER_TIMER_MAX);
                $Questions['inpTimer'] = $inpTimer->render();
                
                $inpStartTimer = new \XoopsFormCheckbox('', "quest_list[{$Questions['quest_id']}][startTimer]",1);
                $inpStartTimer->addOption($Questions['quest_start_timer'],  ' ');
                $Questions['inpStartTimer'] = $inpStartTimer->render();
                
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
       


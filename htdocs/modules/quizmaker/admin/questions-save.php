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

//   echo "<hr>POST<pre>" . print_r($_POST, true) . "</pre><hr>";
//
  
		// Security Check

// 		if (!$GLOBALS['xoopsSecurity']->check()) {
// 			redirect_header('questions.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
// 		}
        $quizId = Request::getInt('quest_quiz_id', 0);
		if ($questId > 0) {
			$questionsObj = $questionsHandler->get($questId);
            $isNew = false;
		} else {
			$questionsObj = $questionsHandler->create();
    		$questionsObj->setVar('quest_creation', \JANUS\getSqlDate());
		    $questionsObj->setVar('quest_quiz_id', $quizId);
            $isNew = true;
		}
        
        // Set Vars
        $pluginName = Request::getString('quest_plugin', '');
		$questionsObj->setVar('quest_plugin', $pluginName);
		$quest_parent_id = Request::getInt('quest_parent_id', 0);
		$questionsObj->setVar('quest_parent_id', Request::getInt('quest_parent_id', 0));
		$questionsObj->setVar('quest_question', Request::getString('quest_question', ''));
		$questionsObj->setVar('quest_identifiant', Request::getString('quest_identifiant', ''));
        
        $options = Request::getArray(QUIZMAKER_PREFIX_OPTIONS_NAME, null);
		//$questionsObj->setVar('quest_options', implode('|', $options));
        //--------------------------------------------------------
        $quiz = $quizHandler->get($quizId);
        $folderJS = $quiz->getVar('quiz_folderJS');
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";  
        $cls = $pluginsHandler->getClassPlugin($pluginName);
        //********************************************************
        //suppression des images si il y en  de définies dans les options du plugins
        if(isset($options['delete'])){
            foreach($options['delete'] AS $name=>$value){
                $f = "{$path}/{$options[$name]}";
                //echo "{$Name}-{$value}<br>{$f}<br>";
                unlink($f);
                $options[$name] = '';
            }
        }
        
//echoArray($options,'options',true);        
        
        //gesion des images du form "options" spécifique a chaque type de question
        // recherche des imges de prefix quest_options
        $prefix = "quiz-{$questId}-";
        foreach($_FILES as $key=>$img){
            //echo "===>{$key} => {$img['name']} => {$img['tmp_name']}<br>"; 
            if(substr($key,0,strlen(QUIZMAKER_PREFIX_OPTIONS_NAME)) == QUIZMAKER_PREFIX_OPTIONS_NAME){
                //echo "===>{$key} => {$img['name']} => {$img['tmp_name']}<br>"; 
            
                $newImg = $cls->save_img($ans, $key, $path, $folderJS, $prefix);
                if($newImg == ''){
                    //echo "===> {$key} => pas d'image sauvegardée<br>";        
                }else{
                    //echo "===> {$key} => newImg = {$newImg}<br>";        
                    $keyOptions = substr($key,strlen(QUIZMAKER_PREFIX_OPTIONS_NAME)+1);
                    $options[$keyOptions] = $newImg;
                }
            }
        }
        //--------------------------------------------------------
		$questionsObj->setVar('quest_options', json_encode($options));
        //********************************************************
/*
echoArray('gpf','options',true);       
echoArray($_POST,'_POST',false);     
*/        
//echoArray($_FILES,'_FILES',true);     
   
        
		//$questionsObj->setVar('quest_options', Request::getString('quest_options', ''));
		$questionsObj->setVar('quest_comment1', Request::getText('quest_comment1', ''));
		$questionsObj->setVar('quest_posComment1', Request::getText('quest_posComment1', ''));
		$questionsObj->setVar('quest_explanation', Request::getText('quest_explanation', ''));
		$questionsObj->setVar('quest_consigne', Request::getText('quest_consigne', ''));
		$questionsObj->setVar('quest_learn_more', Request::getString('quest_learn_more', ''));
		$questionsObj->setVar('quest_see_also', Request::getString('quest_see_also', ''));
		$questionsObj->setVar('quest_points', Request::getInt('quest_points', 0));
		$questionsObj->setVar('quest_height', Request::getInt('quest_height', 0));
		$questionsObj->setVar('quest_numbering', Request::getInt('quest_numbering', 2));
		$questionsObj->setVar('quest_shuffleAnswers', Request::getInt('quest_shuffleAnswers', 0));
		$questionsObj->setVar('quest_weight', Request::getInt('quest_weight', 0));
		$questionsObj->setVar('quest_timer', Request::getInt('quest_timer', 0));
		$questionsObj->setVar('quest_start_timer', Request::getInt('quest_start_timer', 0));
		$questionsObj->setVar('quest_visible', Request::getInt('quest_visible', 1));
		$questionsObj->setVar('quest_actif', Request::getInt('quest_actif', 1));
		$questionsObj->setVar('quest_update', \JANUS\getSqlDate());

        //suppression de l'image existant si besoin si la case a ete coche 
        //ou si le nom de la nouvelle image est différent de l'ancienne
        $delImage = Request::getInt('del_image', 0);
        if($delImage == 1){ //    || $questImage
            $fullName = $path . '/' . $questionsObj->getVar('quest_image');
            unlink($fullName);
            $questionsObj->setVar('quest_image', '');
        }

        // --- gestion de limage de la question
        $cls = $pluginsHandler->getClassPlugin($pluginName);
        
        //recupe de la nouvelle image si elle a ete selectionnée
        $questImage = $cls->save_img($ans, 'quest_image', $path, $folderJS, 'question', $nameOrg);
        
        //enregistrement de l'image
        if ($questImage) $questionsObj->setVar('quest_image', $questImage);

		// Insert Data
		if ($questionsHandler->insert($questionsObj)) {
            $questId = $questionsObj->getVar('quest_id');

            $cls->saveAnswers(Request::getArray('answers', []), $questId, $quizId);
        
       
//echo "<hr>" .  getParams2list($quizId, $quest_plugin); exit;
         $questionsHandler->incrementeWeight($quizId);
//=============================================================================
          if ($addNew)
			redirect_header('questions.php?op=new&' . getParams2list($quizId, $quest_plugin, "", $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);
          else if ($sender == 'plugin_list')
			redirect_header('plugins.php?op=list&' . getParams2list($quizId, $quest_plugin, $sender, $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);
          else
			redirect_header('questions.php?op=list&' . getParams2list($quizId, $quest_plugin, "", $quest_parent_id), 2, _AM_QUIZMAKER_FORM_OK);

            
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
		$form = $questionsObj->getFormQuestions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

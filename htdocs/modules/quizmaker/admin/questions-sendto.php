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

//--------------------------------------------
// test a virer
/*
$quiz = $quizHandler->get($quizId);
    $imgArr = $quiz->getImages();
    //$imgArr = $questionsObj->getImages();
    echoArray($imgArr);
    exit();
*/
		//$templateMain = 'quizmaker_admin_questions_sendto.tpl';
		$templateMain = 'quizmaker_admin_questions.tpl';
		xoops_load('XoopsFormLoader');    
		//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
//--------------------------------------------
    $quizObj = $quizHandler->get($questionsObj->getVar('quest_quiz_id'));
    $questObj = $questionsHandler->get($questionsObj->getVar('quest_id'));
    
    
         // Title
		$title = _AM_QUIZMAKER_QUESTION_SENDTO;        
		$form = new \XoopsThemeForm($title, 'quizmaker_select_filter', 'questions.php', 'post', true);    
                                             
        $form->addElement(new \XoopsFormHidden('sender', ''));
		$form->addElement(new \XoopsFormHidden('op', 'sendto'));
		$form->addElement(new \XoopsFormHidden('quiz_id', $quiz_Id));
		$form->addElement(new \XoopsFormHidden('quest_id', $questId));
    
        $style="color:red;font-weight:bold;font-size:1.5;";
   	    $form->addElement(new \XoopsFormLabel(_AM_QUIZMAKER_QUESTION, "<span style='{$style}'>[#{$questObj->getVar('quest_id')}] {$questObj->getVar('quest_question')}<span>"));


    
  	    //$form->addElement(new \XoopsFormLabel('zzzzzzzzzzzzzzzzzz', 'ddddddddddddddddddddddddd'));
    
        // ----- Listes de selection pour filtrage -----  
        /*
        */
        //$selectors = FQUIZMAKER\getQuestionsSelectorBO($catId, $quizSubject, $quizDifficulty, $quizId, true, '', true, false);
        $selectors = FQUIZMAKER\getQuestionsSelectorBO($catId, $quizSubject,$quizDifficulty,$quizId, true, '', true, true);
                
  	    $form->addElement($selectors['cat']['select']);
  	    $form->addElement($selectors['subject']['select']);
  	    //$form->addElement($selectors['difficulty']['select']);
  	    $form->addElement($selectors['quiz']['select']);
    
    
    $pathImg = $quizObj->getFolderJS(1, 'images');   
    

define('_AM_QUZMAKER_COPY_QUESTION', "Copier la question");
define('_AM_QUZMAKER_MOVE_QUESTION', "Déplacer la question");
define('_AM_QUZMAKER_ACTION_DESC', "Dans tous les cas si un slide éponse est associé, il sera deplacé ou copié avec le slide principal.");
        $inpAction  = new \XoopsFormRadio(_AM_QUIZMAKER_ACTION, "action", 0);
        $inpAction->addOption(0,_AM_QUZMAKER_COPY_QUESTION);
        $inpAction->addOption(1,_AM_QUZMAKER_MOVE_QUESTION);
  	    $form->addElement($inpAction);

  	    $form->addElement(new \XoopsFormLabel('', _AM_QUZMAKER_ACTION_DESC));
        //--------------------------------------------------
        $btnTray = new \XoopsFormElementTray  ('', '&nbsp;');   
         
        $btnCancel = new \XoopsFormButton('', 'cancel', _CANCEL, 'submit');
        //$btnCancel->setClass('btn btn-cancel');
        $btnTray->addElement($btnCancel);

        $btnSubmit = new \XoopsFormButton('', 'btnSubmitCopy', _SUBMIT, 'submit');
        $btnSubmit->setClass('btn btn-success');
        $btnTray->addElement($btnSubmit);
        
         $form->insertBreak("<center>" . $btnTray->render() . "</center>",'blue'); 
         
/*
         $inpButtons = new \XoopsFormButtonTray('buttons','Valider');
         $form->insertBreak("<center>" . $inpButtons->render() . "</center>",'blue'); 
*/
  	    $form->addElement($btnTest);

  		//$GLOBALS['xoopsTpl']->assign('questions_list', null);   
  		$GLOBALS['xoopsTpl']->assign('form', $form->render());   
        //$form->display();



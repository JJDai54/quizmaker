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

use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Helper;
use XoopsModules\Quizmaker\Constants;

include_once XOOPS_ROOT_PATH . '/modules/quizmaker/include/common.php';
include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");

/**
 * Function show block
 * @param  $options 
 * @return array
 */
function b_quizmaker_quiz_show($options)
{
include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/Quiz.php';
	$myts = MyTextSanitizer::getInstance();
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
	$block       = [];
	$typeBlock   = $options[0];
	$limit       = $options[1];
	$lenghtTitle = $options[2];
	$cats = $options[3];
    $tCats = explode(',', $cats);
    if($tCats[0] == 0){
        $nbCats = 0;
    }else{
        $nbCats = count($tCats);
    }
	$caption = $options[4];
	$desc = $options[5];
    
	array_shift($options);
	array_shift($options);
	array_shift($options);
	array_shift($options);
	array_shift($options);
//-----------------------------------------------    
	$quizmakerHelper      = Helper::getInstance();
	$quizHandler = $quizmakerHelper->getHandler('Quiz');
    $categoriesHandler = $quizmakerHelper->getHandler('Categories');
    $cat = $categoriesHandler->getAll (null,null,false);
    
	$crQuiz = new \CriteriaCompo();
    if ($nbCats>0) 
        $crQuiz->add(new Criteria('quiz_cat_id', "({$cats})", 'IN'));
    

	switch($typeBlock) {
		case 'last':
		default:
			// For the block: quiz last
			$crQuiz->setSort( 'quiz_weight' );
			$crQuiz->setOrder( 'DESC' );
		break;
		case 'new':
			// For the block: quiz new
			$crQuiz->setSort( 'quiz_weight' );
			$crQuiz->setOrder( 'ASC' );
		break;
		case 'hits':
			// For the block: quiz hits
			$crQuiz->setSort( 'quiz_hits' );
			$crQuiz->setOrder( 'DESC' );
		break;
		case 'top':
			// For the block: quiz top
			$crQuiz->add( new \Criteria( 'quiz_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crQuiz->setSort( 'quiz_top' );
			$crQuiz->setOrder( 'ASC' );
		break;
		case 'random':
			// For the block: quiz random
			//$crQuiz->add( new \Criteria( 'quiz_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crQuiz->setSort( 'RAND()' );
		break;
	}



	$crQuiz->setLimit( $limit );
	$quizAll = $quizHandler->getAllowed('view', $crQuiz,'','');
	unset($crQuiz);
    
    $block['options']['title'] = $caption;            
    $block['options']['desc'] = str_replace("\n", "<br>",$desc);            
    $block['options']['theme'] = 'red';   
             
//echo "<hr>===>cat : <pre>". print_r($cat, true) ."</pre><hr>";
	if (count($quizAll) > 0) {
		foreach(array_keys($quizAll) as $i) {
            $catId = $quizAll[$i]->getVar('quiz_cat_id');
			$block['data'][$catId]['cat']['id'] = $catId;            
			$block['data'][$catId]['cat']['name'] = $cat[$catId]['cat_name'];            
			$block['data'][$catId]['cat']['theme'] = $cat[$catId]['cat_theme'];            
            
			$block['data'][$catId]['quiz'][$i]['periodeOK'] = 1; //a revoir
			$block['data'][$catId]['quiz'][$i]['id'] = $quizAll[$i]->getVar('quiz_id');
			$block['data'][$catId]['quiz'][$i]['cat_id'] = $catId;
			$block['data'][$catId]['quiz'][$i]['name'] = $myts->htmlSpecialChars($quizAll[$i]->getVar('quiz_name'));
			$block['data'][$catId]['quiz'][$i]['publishQuiz'] = $quizAll[$i]->getVar('quiz_publishQuiz');
			$block['data'][$catId]['quiz'][$i]['folderJS'] = $quizAll[$i]->getVar('quiz_folderJS');        
		}
	}
//echo "<hr>===>block : <pre>". print_r($block, true) ."</pre><hr>";

\JJD\load_css('', false);	
    return $block;

}

/* **
** */

function b_quizmaker_get_quiz()
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/quiz.php';
	$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();    
	$quizHandler = $quizmakerHelper->getHandler('Quiz');
    
	$crQuiz = new \CriteriaCompo();
	$crQuiz->add( new \Criteria( 'quiz_id', 0, '!=' ) );
	$crQuiz->setSort( 'quiz_id' );
	$crQuiz->setOrder( 'ASC' );
	$quizAll = $quizHandler->getAll($crQuiz);
    return $quizAll;
}

function b_quizmaker_get_categories()
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/quiz.php';
	$quizmakerHelper = Helper::getInstance();
	$quizHandler = $quizmakerHelper->getHandler('Categories');
    
	$crCat = new \CriteriaCompo();
	$crCat->add( new \Criteria( 'cat_id', 0, '!=' ) );
	$crCat->setSort( 'cat_id' );
	$crCat->setOrder( 'ASC' );
	$catAll = $quizHandler->getAll($crCat);
    return $catAll;
}

/**
 * Function edit block
 * @param  $options 
 * @return string
 */
function b_quizmaker_quiz_edit($options)
{
//    echo "<hr><pre>zzz : " . print_r($options, true) . "</pre><hr>";
    
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/quiz.php';
	$quizmakerHelper = Helper::getInstance();
	$quizHandler = $quizmakerHelper->getHandler('Quiz');
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
    
    $form = new \XoopsThemeForm("quizmaker_block", 'form', $action, 'post', true);
	$form->setExtra('enctype="multipart/form-data"');


            
    $filterTray = new \XoopsFormElementTray(_CO_JJD_NB_QUIZ_2_list, '');    
    $index = 0;    //last, random, ... //mettre les formHidden en dernier
    $inpFilter = new \XoopsFormHidden("options[{$index}]", $options[$index]); 
    $filterTray->addElement($inpFilter);
    
    $index++ ; 
    $inpNbItems = new \XoopsFormNumber('', "options[{$index}]", 5, 5, $options[$index]);
    $inpNbItems->setMinMax(3, 25);
    $filterTray->addElement($inpNbItems);
    $form->addElement($filterTray);
    
    $index++;    
    $inpLgItems = new \XoopsFormNumber(_CO_JJD_NAME_LENGTH, "options[{$index}]", 5, 5, $options[$index]);
    $inpLgItems->setMinMax(25, 120);
    $form->addElement($inpLgItems);

    $index++;   
    $tCat = explode(',', $options[$index]); 
	$catAll = b_quizmaker_get_categories();
    $inpCat = new \XoopsFormSelect(_CO_JJD_CATEGORIES, "options[{$index}]", $tCat, $size = 5, true);
    $inpCat->addOption(0, _CO_JJD_ALL_CAT);
	foreach(array_keys($catAll) as $i) {
        $inpCat->addOption($catAll[$i]->getVar('cat_id'), $catAll[$i]->getVar('cat_name'));
	}
    $form->addElement($inpCat);
    
    $index++;    
    $inpCaption = new \XoopsFormText(_CO_JJD_BLOCK_TITLE ,  "options[{$index}]", 120, 120, $options[$index]);
    $form->addElement($inpCaption);
    
    $index++;    
 /*
    $inpDesc = new \XoopsFormText(_MB_QUIZMAKER_BLOCK_DESC ,  "options[{$index}]", 120, 255, $options[$index]);
 */
    $inpDesc = new \XoopsFormTextArea(_CO_JJD_BLOCK_DESC, "options[{$index}]", $options[$index], 5, $cols = 80); 
    $form->addElement($inpDesc);
/*
    $index++ ; //last, random, ... //mettre les formHidden en dernier
    $options[$index] = 'last';
    $inpFilter = new \XoopsFormHidden("options[{$index}]", $options[$index]); 
    $form->addElement($inpFilter);
*/
   
    return $form->render();
    
}

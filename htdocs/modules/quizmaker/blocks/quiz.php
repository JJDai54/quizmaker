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

use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Helper;
use XoopsModules\Quizmaker\Constants;

include_once XOOPS_ROOT_PATH . '/modules/quizmaker/include/common.php';

/**
 * Function show block
 * @param  $options 
 * @return array
 */
function b_quizmaker_quiz_show($options)
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/quiz.php';
	$myts = MyTextSanitizer::getInstance();
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
	$block       = [];
	$typeBlock   = $options[0];
	$limit       = $options[1];
	$lenghtTitle = $options[2];
	$helper      = Helper::getInstance();
	$quizHandler = $helper->getHandler('Quiz');
	$crQuiz = new \CriteriaCompo();
	array_shift($options);
	array_shift($options);
	array_shift($options);

	switch($typeBlock) {
		case 'last':
		default:
			// For the block: quiz last
			$crQuiz->setSort( 'quiz_date' );
			$crQuiz->setOrder( 'DESC' );
		break;
		case 'new':
			// For the block: quiz new
			$crQuiz->add( new \Criteria( 'quiz_date', strtotime(date(_SHORTDATESTRING)), '>=' ) );
			$crQuiz->add( new \Criteria( 'quiz_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crQuiz->setSort( 'quiz_date' );
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
			$crQuiz->add( new \Criteria( 'quiz_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crQuiz->setSort( 'RAND()' );
		break;
	}

	$crQuiz->setLimit( $limit );
	$quizAll = $quizHandler->getAll($crQuiz);
	unset($crQuiz);
	if (count($quizAll) > 0) {
		foreach(array_keys($quizAll) as $i) {
			$block[$i]['cat_id'] = $quizAll[$i]->getVar('quiz_cat_id');
			$block[$i]['name'] = $myts->htmlSpecialChars($quizAll[$i]->getVar('quiz_name'));
		}
	}

	return $block;

}

/**
 * Function edit block
 * @param  $options 
 * @return string
 */
function b_quizmaker_quiz_edit($options)
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/quiz.php';
	$helper = Helper::getInstance();
	$quizHandler = $helper->getHandler('Quiz');
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
	$form = _MB_QUIZMAKER_DISPLAY;
	$form .= "<input type='hidden' name='options[0]' value='".$options[0]."' />";
	$form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' />&nbsp;<br>";
	$form .= _MB_QUIZMAKER_TITLE_LENGTH . " : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' /><br><br>";
	array_shift($options);
	array_shift($options);
	array_shift($options);

	$crQuiz = new \CriteriaCompo();
	$crQuiz->add( new \Criteria( 'quiz_id', 0, '!=' ) );
	$crQuiz->setSort( 'quiz_id' );
	$crQuiz->setOrder( 'ASC' );
	$quizAll = $quizHandler->getAll($crQuiz);
	unset($crQuiz);
	$form .= _MB_QUIZMAKER_QUIZ_TO_DISPLAY . "<br><select name='options[]' multiple='multiple' size='5'>";
	$form .= "<option value='0' " . (in_array(0, $options) == false ? '' : "selected='selected'") . '>' . _MB_QUIZMAKER_ALL_QUIZ . '</option>';
	foreach(array_keys($quizAll) as $i) {
		$quiz_id = $quizAll[$i]->getVar('quiz_id');
		$form .= "<option value='" . $quiz_id . "' " . (in_array($quiz_id, $options) == false ? '' : "selected='selected'") . '>' . $quizAll[$i]->getVar('quiz_cat_id') . '</option>';
	}
	$form .= '</select>';

	return $form;

}

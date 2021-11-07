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
function b_quizmaker_categories_show($options)
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/categories.php';
	$myts = MyTextSanitizer::getInstance();
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
	$block       = [];
	$typeBlock   = $options[0];
	$limit       = $options[1];
	$lenghtTitle = $options[2];
	$helper      = Helper::getInstance();
	$categoriesHandler = $helper->getHandler('Categories');
	$crCategories = new \CriteriaCompo();
	array_shift($options);
	array_shift($options);
	array_shift($options);

	switch($typeBlock) {
		case 'last':
		default:
			// For the block: categories last
			$crCategories->setSort( 'cat_date' );
			$crCategories->setOrder( 'DESC' );
		break;
		case 'new':
			// For the block: categories new
			$crCategories->add( new \Criteria( 'cat_date', strtotime(date(_SHORTDATESTRING)), '>=' ) );
			$crCategories->add( new \Criteria( 'cat_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crCategories->setSort( 'cat_date' );
			$crCategories->setOrder( 'ASC' );
		break;
		case 'hits':
			// For the block: categories hits
			$crCategories->setSort( 'cat_hits' );
			$crCategories->setOrder( 'DESC' );
		break;
		case 'top':
			// For the block: categories top
			$crCategories->add( new \Criteria( 'cat_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crCategories->setSort( 'cat_top' );
			$crCategories->setOrder( 'ASC' );
		break;
		case 'random':
			// For the block: categories random
			$crCategories->add( new \Criteria( 'cat_date', strtotime(date(_SHORTDATESTRING))+86400, '<=' ) );
			$crCategories->setSort( 'RAND()' );
		break;
	}

	$crCategories->setLimit( $limit );
	$categoriesAll = $categoriesHandler->getAll($crCategories);
	unset($crCategories);
	if (count($categoriesAll) > 0) {
		foreach(array_keys($categoriesAll) as $i) {
			$block[$i]['name'] = $myts->htmlSpecialChars($categoriesAll[$i]->getVar('cat_name'));
			$block[$i]['update'] = $categoriesAll[$i]->getVar('cat_update');
		}
	}

	return $block;

}

/**
 * Function edit block
 * @param  $options 
 * @return string
 */
function b_quizmaker_categories_edit($options)
{
	include_once XOOPS_ROOT_PATH . '/modules/quizmaker/class/categories.php';
	$helper = Helper::getInstance();
	$categoriesHandler = $helper->getHandler('Categories');
	$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
	$form = _MB_QUIZMAKER_DISPLAY;
	$form .= "<input type='hidden' name='options[0]' value='".$options[0]."' />";
	$form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' />&nbsp;<br>";
	$form .= _MB_QUIZMAKER_TITLE_LENGTH . " : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' /><br><br>";
	array_shift($options);
	array_shift($options);
	array_shift($options);

	$crCategories = new \CriteriaCompo();
	$crCategories->add( new \Criteria( 'cat_id', 0, '!=' ) );
	$crCategories->setSort( 'cat_id' );
	$crCategories->setOrder( 'ASC' );
	$categoriesAll = $categoriesHandler->getAll($crCategories);
	unset($crCategories);
	$form .= _MB_QUIZMAKER_CATEGORIES_TO_DISPLAY . "<br><select name='options[]' multiple='multiple' size='5'>";
	$form .= "<option value='0' " . (in_array(0, $options) == false ? '' : "selected='selected'") . '>' . _MB_QUIZMAKER_ALL_CATEGORIES . '</option>';
	foreach(array_keys($categoriesAll) as $i) {
		$cat_id = $categoriesAll[$i]->getVar('cat_id');
		$form .= "<option value='" . $cat_id . "' " . (in_array($cat_id, $options) == false ? '' : "selected='selected'") . '>' . $categoriesAll[$i]->getVar('cat_name') . '</option>';
	}
	$form .= '</select>';

	return $form;

}

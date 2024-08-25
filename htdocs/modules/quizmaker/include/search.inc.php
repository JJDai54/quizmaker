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

use XoopsModules\Quizmaker AS FQUIZMAKER;


/**
 * search callback functions 
 *
 * @param $queryarray 
 * @param $andor 
 * @param $limit 
 * @param $offset 
 * @param $userid 
 * @return mixed $itemIds
 */
function quizmaker_search($queryarray, $andor, $limit, $offset, $userid)
{return false;
	$ret = [];
	$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
	// search in table quiz
	// search keywords
	$elementCount = 0;
	$quizHandler = $quizmakerHelper->getHandler('Quiz');
	if (is_array($queryarray)) {
		$elementCount = count($queryarray);
	}
	if ($elementCount > 0) {
		$crKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$crKeyword = new \CriteriaCompo();
			unset($crKeyword);
		}
	}
	// search user(s)
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'quiz_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'quiz_submitter', $userid ), 'OR' );
	}
	$crSearch = new \CriteriaCompo();
	if (isset($crKeywords)) {
		$crSearch->add( $crKeywords, 'AND' );
	}
	if (isset($crUser)) {
		$crSearch->add( $crUser, 'AND' );
	}
	$crSearch->setStart( $offset );
	$crSearch->setLimit( $limit );
	$crSearch->setSort( 'quiz_dateEnd' );
	$crSearch->setOrder( 'DESC' );
	$quizAll = $quizHandler->getAll($crSearch);
	foreach(array_keys($quizAll) as $i) {
		$ret[] = [
			'image'  => 'assets/icons/16/quiz.png',
			'link'   => 'quiz.php?op=show&amp;quiz_id=' . $quizAll[$i]->getVar('quiz_id'),
			'title'  => $quizAll[$i]->getVar('quiz_cat_id'),
			'time'   => $quizAll[$i]->getVar('quiz_dateEnd')
		];
	}
	unset($crKeywords);
	unset($crKeyword);
	unset($crUser);
	unset($crSearch);


	// search in table categories
	// search keywords
	$elementCount = 0;
	$categoriesHandler = $quizmakerHelper->getHandler('Categories');
	if (is_array($queryarray)) {
		$elementCount = count($queryarray);
	}
	if ($elementCount > 0) {
		$crKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$crKeyword = new \CriteriaCompo();
			unset($crKeyword);
		}
	}
	// search user(s)
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'cat_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'cat_submitter', $userid ), 'OR' );
	}
	$crSearch = new \CriteriaCompo();
	if (isset($crKeywords)) {
		$crSearch->add( $crKeywords, 'AND' );
	}
	if (isset($crUser)) {
		$crSearch->add( $crUser, 'AND' );
	}
	$crSearch->setStart( $offset );
	$crSearch->setLimit( $limit );
	$crSearch->setSort( 'cat_update' );
	$crSearch->setOrder( 'DESC' );
	$categoriesAll = $categoriesHandler->getAll($crSearch);
	foreach(array_keys($categoriesAll) as $i) {
		$ret[] = [
			'image'  => 'assets/icons/16/categories.png',
			'link'   => 'categories.php?op=show&amp;cat_id=' . $categoriesAll[$i]->getVar('cat_id'),
			'title'  => $categoriesAll[$i]->getVar('cat_name'),
			'time'   => $categoriesAll[$i]->getVar('cat_update')
		];
	}
	unset($crKeywords);
	unset($crKeyword);
	unset($crUser);
	unset($crSearch);





	return $ret;

}

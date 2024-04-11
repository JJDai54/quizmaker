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

/**
 * comment callback functions
 *
 * @param  $category 
 * @param  $item_id 
 * @return array item|null
 */
function quizmaker_notify_iteminfo($category, $item_id)
{
	global $xoopsDB;

	if (!defined('QUIZMAKER_URL_MODULE')) {
		define('QUIZMAKER_URL_MODULE', XOOPS_URL . '/modules/quizmaker');
	}

	switch($category) {
		case 'global':
			$item['name'] = '';
			$item['url']  = '';
			return $item;
		break;
		case 'quiz':
			$sql          = 'SELECT quiz_cat_id FROM ' . $xoopsDB->prefix('quizmaker_quiz') . ' WHERE quiz_id = '. $item_id;
			$result       = $xoopsDB->query($sql);
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['quiz_cat_id'];
			$item['url']  = QUIZMAKER_URL_MODULE . '/quiz.php?quiz_id=' . $item_id;
			return $item;
		break;
		case 'categories':
			$sql          = 'SELECT cat_name FROM ' . $xoopsDB->prefix('quizmaker_categories') . ' WHERE cat_id = '. $item_id;
			$result       = $xoopsDB->query($sql);
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['cat_name'];
			$item['url']  = QUIZMAKER_URL_MODULE . '/categories.php?cat_id=' . $item_id;
			return $item;
		break;
	}
	return null;
}

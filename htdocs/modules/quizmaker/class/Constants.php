<?php

namespace XoopsModules\Quizmaker;

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

/**
 * Interface  Constants
 */
interface Constants
{
	// Constants for tables
	const TABLE_QUIZ = 0;
	const TABLE_QUESTIONS = 1;
	const TABLE_CATEGORIES = 2;
	const TABLE_PLUGINS = 3;
	const TABLE_ANSWERS = 4;
	const TABLE_RESULTS = 5;
	const TABLE_MESSAGES = 6;

	// Constants for status
	const STATUS_NONE      = 0;
	const STATUS_OFFLINE   = 1;
	const STATUS_SUBMITTED = 2;
	const STATUS_APPROVED  = 3;
	const STATUS_BROKEN    = 4;

	// Constants for permissions
	const PERM_GLOBAL_NONE    = 0;
	const PERM_GLOBAL_VIEW    = 1;
	const PERM_GLOBAL_SUBMIT  = 2;
	const PERM_GLOBAL_APPROVE = 3;

}

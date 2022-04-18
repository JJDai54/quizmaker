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
 * @param mixed      $module
 * @param null|mixed $prev_version
 * @package        Quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        $Id: 1.0 update.php 1 Mon 2018-03-19 10:04:53Z XOOPS Project (www.xoops.org) $
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 */

/**
 * @param      $module
 * @param null $prev_version
 *
 * @return bool|null
 */
 function xoops_module_update_quizmaker(\XoopsModule $xoopsModule, $previousVersion = null)
{
    $newVersion = $xoopsModule->getVar('version') * 100;
    if ($newVersion == $previousVersion) {
        return true;
    }

    //------------------------------------------------------------

    $fld = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/versions/';
    $cls = 'Quizmaker_%1$s';

    $version = [
        '2_80' => 280,
        '2_92' => 292,
    ];

    //    while (list($key, $val) = each($version)) {
    foreach ($version as $key => $val) {
        if ($previousVersion < $val) {
            $name = sprintf($cls, $key);
            $f    = $fld . $name . '.php';
            //ext_echo ("<hr>{$f}<hr>");
            if (is_readable($f)) {
                echo "mise à jour version : {$key} = {$val}<br>";
                require_once $f;
                $cl = new $name($xoopsModule, ['previousVersion' => $previousVersion]);
            }
        }
    }

    return true;
}
///////////////////////////////////////////////////////////////////////////


/**
 * @param $module
 *
 * @return bool
 */
function quizmaker_check_db($module)
{
    $ret = true;
	//insert here code for database check

    /*
    // Example: update table (add new field)
    $table   = $GLOBALS['xoopsDB']->prefix('quizmaker_images');
    $field   = 'img_exif';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` TEXT NULL AFTER `img_state`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // Example: create new table
    $table   = $GLOBALS['xoopsDB']->prefix('quizmaker_categories');
    $check   = $GLOBALS['xoopsDB']->queryF("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        // create new table 'quizmaker_categories'
        $sql = "CREATE TABLE `$table` (
                  `cat_id`        INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `cat_text`      VARCHAR(100)    NOT NULL DEFAULT '',
                  `cat_date`      INT(8)          NOT NULL DEFAULT '0',
                  `cat_submitter` INT(8)          NOT NULL DEFAULT '0',
                  PRIMARY KEY (`cat_id`)
                ) ENGINE=InnoDB;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when creating table '$table'.");
            $ret = false;
        }
    }
    */
    return $ret;
}

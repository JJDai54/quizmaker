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
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 * @package        Quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com> XOOPS Project (www.xoops.org) $
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Common;

/**
 * @param \XoopsModule $module
 * @return bool
 */
function xoops_module_pre_install_quizmaker(\XoopsModule $module)
{

    require dirname(__DIR__) . '/preloads/autoloader.php';
    /** @var FQUIZMAKER\Utility $utility */
    $utility = new FQUIZMAKER\Utility();

    //check for minimum XOOPS version
    $xoopsSuccess = $utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $moduleTables = &$module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

/**
 * @param \XoopsModule $module
 * @return bool|string
 */
function xoops_module_install_quizmaker(\XoopsModule $module)
{
global $xoopsDB;

    require dirname(__DIR__) . '/preloads/autoloader.php';

    /** @var FQUIZMAKER\Helper $quizmakerHelper */ 
    /** @var FQUIZMAKER\Utility $utility */
    /** @var Common\Configurator $configurator */
    $quizmakerHelper       = FQUIZMAKER\Helper::getInstance();
    $utility      = new FQUIZMAKER\Utility();
    $configurator = new Common\Configurator();

    // Load language files
    $quizmakerHelper->loadLanguage('admin');
    $quizmakerHelper->loadLanguage('modinfo');
    $quizmakerHelper->loadLanguage('common');

    //  ---  CREATE FOLDERS ---------------
    if ($configurator->uploadFolders && is_array($configurator->uploadFolders)) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.gif FILES ---------------
    if ($configurator->copyBlankFiles && is_array($configurator->copyBlankFiles)) {
        $file = dirname(__DIR__) . '/assets/images/blank.gif';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.gif';
            $utility::copyFile($file, $dest);
        }
		$file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }
/*
    $file = dirname(__DIR__) . '/sql/datasql.sql';
    
    

        if (false !== ($fp = fopen($file, 'r'))) {
            include_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sql_queries = trim(fread($fp, filesize($file)));
echo $sql_queries;
        }else{
        echo "big probleme";
        }




    
    
    
    echo "<hr>{$file}<br>" . $xoopsDB->Prefix() . "<br>";
    $ret = $xoopsDB->queryFromFile($file);
    echo ($ret) ? 'ok' : 'erreur';
    echo "<hr>";
*/
/**************************************************************
 * 
 * ************************************************************/


    $catId = 1;
    $datetinstal = date('Y-m-d H:i:s');
    $sql = "UPDATE " . $xoopsDB->prefix("quizmaker_categories")
         . " SET cat_creation = '{$datetinstal}', cat_update='{$datetinstal}'"
         . " WHERE cat_id = {$catId}";
    $xoopsDB->query($sql);
    
    //global $grouppermHandler;
	// Ajout des Permissions pour catégorie de test
    $grouppermHandler = xoops_getHandler('groupperm');
    $mid = $module->getVar('mid');
    $groupId = 1;
    $grouppermHandler->addRight('quizmaker_approve_categories', $catId, $groupId, $mid);    
    $grouppermHandler->addRight('quizmaker_view_categories', $catId, $groupId, $mid);    
    $grouppermHandler->addRight('quizmaker_submit_categories', $catId, $groupId, $mid);    
    
    return true;
}

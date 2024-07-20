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

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', '');
$ok = Request::getCmd('ok', 0);
$folder = Request::getCmd('folder', '');
// Request quiz_id

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  

switch ($op){
    case 'truncat':
        $fullPath = XOOPS_ROOT_PATH . "/uploads/quizmaker/" . $folder;
        if (!$ok){
            $msg = sprintf(_AM_QUIZMAKER_TRUNCAT_CONFIRM, $fullPath);
			xoops_confirm(['ok' => 1, 'folder' => $folder, 'op' => 'truncat'], "traitements.php", $msg);
        }else{

          $msg = sprintf(_AM_QUIZMAKER_TRUNCAT_OK, $fullPath);
          //echo "<hr>msg : {$msg}<br>{$fullPath}<hr>";
          $quizUtility->clearFolder($fullPath);
          //sleep(3);
          redirect_header("index.php", 3, $msg);
        }
        break;
        
    case 'autres traitements a venir':
        $msg = "___???___";
        break;
    
}
require __DIR__ . '/footer.php';




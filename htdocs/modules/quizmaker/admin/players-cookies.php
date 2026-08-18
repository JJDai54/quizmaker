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


        if ($userEmail){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('cookie_email', $userEmail, "="));
          //$countEnr = $cookiesHandler->getCountCookies($criteria);
          $allRst = $cookiesHandler->getAllCookiesArr($criteria, $start, $limit, 'cookie_email, cookie_id');
          $countEnr = count($allRst);
//echo "quizId = {$quizId}<br>";    
    		$GLOBALS['xoopsTpl']->assign('countEnr', count($allRst));     
    		$GLOBALS['xoopsTpl']->assign('cookies_list', $allRst);  
        }else{
          $countEnr = 0;
          $cookiesAll = null;
        }
        

// 		if (count($allRst) == 0) {
// 			//$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_RESULTS);
// 		}

//echoArray($cookiesAll, $userEmail);      
//exit;

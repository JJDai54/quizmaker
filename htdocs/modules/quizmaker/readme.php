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
use XoopsModules\Quizmaker\Utlity;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_readme.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
//-----------------------------------------------------------
		$templateMain = 'quizmaker_admin_questions.tpl';
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$criteriaCatAllowed->add(new \criteria('cat_actif',1,'='));

$catId = Request::getInt('cat_id');  
$quizId = Request::getInt('quiz_id');  
$from = Request::getString('from');  
$catObj = $categoriesHandler->get($catId);
$op    = Request::getCmd('op', '');
$playerId = Request::getInt('player_id');  


$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$GLOBALS['xoopsTpl']->assign('sysUtlIcon16', $sysUrlIcon16);
$GLOBALS['xoopsTpl']->assign('sysUrlIcon32', $sysUrlIcon32);
$GLOBALS['xoopsTpl']->assign('modUrlIcon16', $modUrlIcon16);
$GLOBALS['xoopsTpl']->assign('modUrlIcon32', $modUrlIcon32);

//echoArray("GP", $op);
    switch($op){
        case 'isReadmeOk':
        case 'isreadmeok':
          \JANUS\load_css('', false);
          
   
          $urlTo = "readme.php?op=readmeIsOk&cat_id={$catId}&quiz_id={$quizId}&player_id=";              
$xoopsTpl = $GLOBALS['xoopsTpl'];
    
    
          $xoopsTpl->assign('url', $urlTo);        
          $xoopsTpl->assign('catTheme',  $catObj->getVar("cat_theme"));        
          $xoopsTpl->assign('catReadme',  $catObj->getVar("cat_readme_text"));        
          $xoopsTpl->assign('catReadmeLabel',  $catObj->getVar("cat_readme_label"));        
          $xoopsTpl->assign('cat_id',  $catId);        
          $xoopsTpl->assign('quiz_id',  $quizId);        
          $xoopsTpl->assign('from',  $from);        

          
          require __DIR__ . '/footer.php';
        break;
        
        case 'readmeisok':
        case 'readmeIsOk':
        //echoArray("GP");exit;
            $readmeHandler->incremente($catId);
          switch($from){
            case 'quiz':
                $urlTo = "quiz.php?op=list&cat_id={$catId}&player_id=&readmeOk=1";      
                break;
            case 'display':
            default:
                $urlTo = QUIZMAKER_DISPLAY_QUIZ . "?op=run&cat_id={$catId}&quiz_id={$quizId}&player_id={$playerId}&readmeOk=1";      
                break;
          }
          redirect_header($urlTo, 3, _MA_QUIZMAKER_CAT_READ_INFO_OK);        
    //http://127.0.0.16/modules/quizmaker/quiz_display_db.php?op=run&quiz_id=69&cat_id=8&player_id=
        break;
    }
    
    
    

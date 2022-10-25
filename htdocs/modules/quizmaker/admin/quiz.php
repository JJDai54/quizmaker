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


require_once __DIR__ . '/header.php';

use Xmf\Request;
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JJD;

\JJD\loadAllXForms();

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$catId  = Request::getInt('cat_id', 1);
$quizId = Request::getInt('quiz_id');
$sender = Request::getString('');

$utility = new \XoopsModules\Quizmaker\Utility();  
//  $gp = array_merge($_GET, $_POST);
//  echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

//echo "quizId = {$quizId}<br>sender = {$sender}";

switch($op) {
	default:
        $op = 'list';
	case 'list':
	case 'new':
	case 'save':
	case 'edit':
	case 'delete':
        include_once("quiz-{$op}.php");
	   break;
    
	case 'export_json':
        $quizUtility::build_quiz($quizId);
		//redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Export effectue");
	   break;    
        
	case 'export_quiz':
        //exit(_AM_QUIZMAKER_EXPORT_QUIZ);
        $quizUtility::exportQuiz($quizId);
        $op = 'list';
        include_once("quiz-{$op}.php");

        
        //include_once("quiz-{$op}.php");
        //redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Export effectue");
	break;
        
	case 'change_etat':
        $field = Request::getString('field');
        $modulo = Request::getInt('modulo', 2);
        $quizHandler->changeEtat($quizId, $field, $modulo);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Etat de {$field} Changé");
	break;
    
	case 'config_options':
        $config = Request::getInt('config', 0);
        $quizHandler->config_options($quizId, $config);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Options mise à jour");
	break;

    case 'init_weight':
        $quizHandler->incrementeWeight($catId);
        $url = "quiz.php?op=list&cat_id={$catId}";
        //$url = "questions.php?op=list&" . getParams2list($quizId, $quest_type_question)."#question-{$questId}";
        \redirect_header($url, 0, "");
	break;

    case 'weight':
        $action = Request::getString('sens', "down") ;
        $quizHandler->updateWeight($quizId, $action);
        //$quizHandler->incrementeWeight($quizId);
        $url = "quiz.php?op=list&quiz_id={$quizId}";            // ."#question-{$catId}";
        //echo "<hr>{$url}<hr>";exit;
        \redirect_header($url, 0, "");
        break;

    
}
require __DIR__ . '/footer.php';

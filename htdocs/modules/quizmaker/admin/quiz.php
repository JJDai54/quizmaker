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


require_once __DIR__ . '/header.php';

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JANUS;

//\JANUS\loadAllXForms();

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$quizId = Request::getInt('quiz_id');
$sender = Request::getString('');
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$catId  = Request::getInt('cat_id', array_key_first($catArr));

//echoArray($catArr,'',true);
//echoarray($catArr);
//recheche du quiz pour les opération individuelle : edit, save, delete, ...
if($quizId > 0 && $sender != 'cat_id'){
  $quizObj = $quizHandler->get($quizId);
  $quizCat_id = $quizObj->getVar('quiz_cat_id');
  $catId  = $quizCat_id;
  if (!isset($catArr[$catId])) $catId = array_key_first($catArr);    
}
//-----------------------------------------------------------
//echoArray("gp");

$utility = new \XoopsModules\Quizmaker\Utility();  
//   $gp = array_merge($_GET, $_POST);
//   echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

//echo "quizId = {$quizId}<br>sender = {$sender}";
function checkRightEditQuiz($permName, $catId){
global $clPerms;
  if (!$clPerms->getPermissions($permName, $catId))  
      redirect_header("quiz.php?op=list&cat_id={$catId}", 5, _CO_QUIZMAKER_NO_PERM);
}
//--------------------------------------------------------
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
    
	case 'build_quiz':
        checkRightEditQuiz('edit_quiz',$catId);
        $build = $quizUtility::buildQuiz($quizId);
		//redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, sprintf(_AM_QUIZMAKER_QUIZ_BUILD_OK,$build));
	   break;    
        
	case 'export_quiz':
        checkRightEditQuiz('export_quiz',$catId);
        $quizUtility::quiz_export($quizId);
        $op = 'list';
        include_once("quiz-{$op}.php");

        
        //include_once("quiz-{$op}.php");
        //redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Export effectue");
	break;
        
	case 'change_etat':
        checkRightEditQuiz('edit_quiz',$catId);
        $field = Request::getString('field');
        $modulo = Request::getInt('modulo', 2);
        $quizHandler->changeEtat($quizId, $field, $modulo);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Etat de {$field} Changé");
	break;

	case 'set_bit':
        checkRightEditQuiz('edit_quiz',$catId);
        $field = Request::getString('field');
        $bitIndex = Request::getInt('bitIndex');
        $newValue = Request::getInt('newValue', -1);
        $quizHandler->setBitOn($quizId, $field, $bitIndex, $newValue);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Etat de {$field} Changé");
        
// 	case 'set_config':
//         checkRightEditQuiz('edit_quiz',$catId);
//         $newOptionIHM = Request::getInt('newOptionIHM');
//         $newOptionsDev = Request::getInt('newOptionsDev');
//         $quizHandler->setConfigOptions($quizId, $newOptionIHM, $newOptionsDev);
//         redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Etat de {$field} Changé");
// 	break;
    
	case 'set_binoptions':
        checkRightEditQuiz('edit_quiz',$catId);
        $optId = Request::getInt('opt_id');
        $quizHandler->setBinOptions($quizId, $optId);
        $build = $quizUtility::buildQuiz($quizId);
        redirect_header("quiz.php?op=list&cat_id={$catId}", 5, sprintf(_AM_QUIZMAKER_QUIZ_BINOPTIONS_OK,$build));

	break;
    
// 	case 'config_options':
//         $config = Request::getInt('config', 0);
//         $quizHandler->config_options($quizId, $config);
//         redirect_header("quiz.php?op=list&cat_id={$catId}", 5, "Options mise à jour");
// 	break;

    case 'init_weight':
        $quizHandler->incrementeWeight($catId);
        $url = "quiz.php?op=list&cat_id={$catId}";
        //$url = "questions.php?op=list&" . getParams2list($quizId, $quest_plugin)."#question-{$questId}";
        \redirect_header($url, 0, "");
	break;

    case 'weight':
        $action = Request::getString('sens', "down") ;
        $quizHandler->updateWeight($quizId, $action);
        //$quizHandler->incrementeWeight($quizId);
        $url = "quiz.php?op=list&quiz_id={$quizId}";            // ."#question-{$catId}";
        \redirect_header($url, 0, "");
        break;

    
}
require __DIR__ . '/footer.php';

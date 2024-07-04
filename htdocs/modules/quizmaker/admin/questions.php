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
require __DIR__ . '/header.php';
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'edit_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);

$catId  = Request::getInt('cat_id', array_key_first($catArr));

//echoArray($catArr,"===>catId = {$catId}");



//if ($catId == 0 || !isset($catArr[$catId])) $catId = array_key_first($catArr);
//echoArray($catArr);
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quest_id

//utiliser pour rediriger directement sur l'ajout d'une question du même type
$addNew = (Request::getCmd('submit_and_addnew', 'no') == 'no') ? false : true;

//echo "<hr>addNew = " . (($addNew) ? ' ajout ok' : 'pas d ajout') . "-{$addNew}<hr>";

$sender  = Request::getString('sender', '');
if ($sender == 'cat_id') {
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
  $quizId  = Request::getInt('quiz_id', 1);
}
$questId = Request::getInt('quest_id', 0);
$quest_type_question = Request::getString('quest_type_question', '');


//   $gp = array_merge($_GET, $_POST);
//   echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

function getParams2list($quizId, $quest_type_question, $sender = "", $quest_parent_id=0){
global $quizHandler;
    $catId = $quizHandler->getParentId($quizId);
    return $params = "sender={$sender}&cat_id={$catId}&quiz_id={$quizId}&quest_type_question={$quest_type_question}&quest_parent_id={$quest_parent_id}";
}

//////////////////////////////////////////////
switch($op) {
	default:
        $op = 'list';
	case 'list':
	case 'addingroup':
	case 'new':
	case 'edit':
	case 'clone':
	case 'save':
	case 'delete':
        include_once("questions-{$op}.php");
        break;
    
    case 'goto_category':
        $questionsHandler->incrementeWeight($quizId);
        $url = "quiz.php?op=list&quiz_id={$quizId}";
        \redirect_header($url, 0, "");
	break;
    
    case 'init_weight':
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_type_question)."#question-{$questId}";
        \redirect_header($url, 0, "");
	break;
    
    case 'weight':
        $action = Request::getString('sens', "down") ;
        $questionsHandler->updateWeight($questId, $action);
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_type_question)."#question-{$questId}";
        \redirect_header($url, 0, "");
        break;

	case 'build_quiz':
        $quizUtility::buildQuiz($quizId);
        //$utility::export_questions2Jason($quizId);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_type_question), 5, "Export effectue");
		//redirect_header('questions.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
	break;
        
	case 'change_etat':
        $field = Request::getString('field');
        $modulo = Request::getInt('modulo', 2);
        $doItForGroup = ($field == 'quest_actif') ? true : false;
        $questionsHandler->changeEtat($questId, $field, $modulo, $doItForGroup);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}#question-{$questId}", 5, "Etat de {$field} Changé");
	break;
    
	case 'set_value':
        $field = Request::getString('field');
        $value = Request::getString('value', '0');
        $doItForGroup = Request::getInt('doItForGroup', 0);
        $questionsHandler->setValue($questId, $field, $value, $doItForGroup);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}#question-{$questId}", 5, "Etat de {$field} Changé");
	break;
    //------------------------------------------------------
	case 'export_quiz':
        $quizUtility::quiz_export($quizId);
        $op = 'list';
        $download = 1;
        include_once("questions-{$op}.php");
        //redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Export effectué");
	break;
    
	case 'purger_images':
        $nbImg = $quizHandler->purgerImages($quizId);
        $op = 'list';
        $msg = sprintf(_AM_QUIZMAKER_QUIZ_IMAGES_DELETED, $nbImg);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}#question-{$questId}", 5, $msg);

	break;
    
// 	case 'restor_quiz':
//         $quizUtility->quiz_loadData($quizId);
//         redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
// 	break;
    
	case 'quiz_importFromYml':
        $quizUtility->quiz_importFromYml($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
	break;

	case 'edit_quiz':
        redirect_header("quiz.php?op=edit&quiz_id={$quizId}&sender=",0,"");
	break;
    
	case 'update_list':
  $gp = array_merge($_GET, $_POST);
        $list = Request::getArray('quest_list');
        //echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";
        //  echo "<hr>quest_timer<pre>" . print_r($list, true) . "</pre><hr>";
        foreach($list AS $id => $arr){
            $criteria = new Criteria('quest_id', $id, "=");
            $questionsHandler->updateAll('quest_timer', $arr['timer'], $criteria, $force = false);
            $questionsHandler->updateAll('quest_points', $arr['points'], $criteria, $force = false);
        }
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Mise à jour ok");
	break;
    } // fin du switch maitre
    
require __DIR__ . '/footer.php';

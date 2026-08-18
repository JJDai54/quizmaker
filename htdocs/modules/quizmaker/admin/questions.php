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
require __DIR__ . '/header.php';
//------------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'edit_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);

$catId  = Request::getInt('cat_id', 0);
$quizId  = Request::getInt('quiz_id', 0);
$questId  = Request::getInt('quest_id', 0);
$quizSubject = Request::getString('quiz_subject', '');
$quizDifficulty = Request::getInt('quiz_difficulty', 0);
$playerId = Request::getInt('player_id', 0);
//        echo "===>1quizId = {$quizId} - catId  = {$catId}<br>";

if($quizId > 0 && $catId == 0){
    $quiz = $quizHandler->get($quizId);
    $catId = $quiz->getVar('quiz_cat_id');
}

//recherche de catId
if($catId == 0 || !isset($catArr[$catId]))
    $catId = array_key_first($catArr);

//recherche de quizId
$quizArr = $quizHandler->getListKeyName($catId, $quizSubject);

if ($quizId == 0  || !isset($quizArr[$quizId])) 
    $quizId = array_key_first($quizArr);

//$quiz = $quizHandler->get($quizId);
// echoArray('gp');
// echo "<hr>catId : {$catId} - quizId : {$quizId}<hr>";

//echo "2===>quizId = {$quizId} - catId  = {$catId}<br>";




$op = Request::getCmd('op', 'list');
$action = Request::getCmd('actions', ''); 
if($action && $action !='no-action') $op = $action;

//utiliser pour rediriger directement sur l'ajout d'une question du même type
$addNew = (Request::getCmd('submit_and_addnew', 'no') == 'no') ? false : true;
$reload = (Request::getCmd('submit_and_reload', 'no') == 'no') ? false : true;
$cancel = (Request::getCmd('cancel', 'no') == 'no') ? false : true;

//echo "<hr>addNew = " . (($addNew) ? ' ajout ok' : 'pas d ajout') . "-{$addNew}<hr>";


$sender  = Request::getString('sender', '');



$quest_plugin = Request::getString('quest_plugin', '');
//echo "<hr>request_uri = {$_SERVER['REQUEST_URI']}<hr>";

function getParams2list($quizId, $quest_plugin, $sender = "", $quest_parent_id=0, $questId = 0, $subject=""){
global $quizHandler;
    $catId = $quizHandler->getParentId($quizId);
    $params = "sender={$sender}&cat_id={$catId}&quiz_id={$quizId}&quest_plugin={$quest_plugin}&quest_parent_id={$quest_parent_id}&quest_id={$questId}&subject={$subject}";
    return $params;
}
function getParams2list2($questObj, $quizSubject, $sender = ""){
global $quizHandler, $quest_Handler;
    $quizId = $questObj->getVar('quest_quiz_id');    
    $catId = $quizHandler->getParentId($quizId);
    $params = array();
    $params[] = "cat_id={$catId}";
    $params[] = "quiz_id={$quizId}";
    $params[] = "quest_plugin={$questObj->getVar('quest_plugin')}";
    $params[] = "quest_parent_id={$questObj->getVar('quest_parent_id')}";
    $params[] = "quest_id={$questObj->getVar('quest_id')}";
    $params[] = "quiz_subject={$quizSubject}";
    $params[] = "sender={$sender}";
    //echoArray($params,"",true);
    return $params = implode('&', $params);
}
//////////////////////////////////////////////
if(isset($_POST['btnSubmitCopy'])) $op = 'sendto_ok';
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
	case 'addanswer':
	case 'sendto':
        include_once("questions-{$op}.php");
        break;
    
    case 'goto_category':
        $questionsHandler->incrementeWeight($quizId);
        $url = "quiz.php?op=list&quiz_id={$quizId}";
        \redirect_header($url, 0, "");
	    break;
    
    case 'init_weight':
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_plugin)."#question-{$questId}";
        redirect_header($url, 0, "");
	    break;
    
    case 'weight':
        $action = Request::getString('sens', "down") ;
        $questionsHandler->updateWeight($questId, $action);
        $questionsHandler->incrementeWeight($quizId);
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_plugin)."#question-{$questId}";
        redirect_header($url, 0, "");
	    break;


	case 'build_quiz':
        $buildArr = $quizUtility::buildQuiz($quizId);
        $msg = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_OK,$buildArr['name'],$buildArr['id'],$buildArr['build']);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_plugin), 5, $msg);
	    break;
        
	case 'change_etat':
        $field = Request::getString('field');
        $modulo = Request::getInt('modulo', 2);
        $doItForGroup = ($field == 'quest_actif') ? true : false;
        $questionsHandler->changeEtat($questId, $field, $modulo, $doItForGroup);
        redirect_header("questions.php?op=list&questId={$questId}&sender=&cat_id={$catId}&quiz_id={$quizId}#question-{$questId}", 5, "Etat de {$field} Changé");
	    break;
    
	case 'disable_pageanswer':
    case 'enable_pageanswer':
        $etat = ($op == 'enable_pageanswer') ? 1 : 0;
        $field = 'quest_visible';
        $criteria = 'quest_plugin = "pageAnswer"';
        $questionsHandler->setValue2QuestOfQuiz($quizId, $field, $etat, $criteria);
        
        $buildArr = $quizUtility::buildQuiz($quizId);
        $msg = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_OK,$buildArr['name'],$buildArr['id'],$buildArr['build']);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_plugin), 5, $msg);
	    break;

	case 'set_chrono_on':
        $quizHandler->setBitOn($quizId, 'quiz_optionsIhm', QUIZMAKER_BIT_USETIMER, 1);
        $buildArr = $quizUtility::buildQuiz($quizId);
        $msg = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_OK,$buildArr['name'],$buildArr['id'],$buildArr['build']);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_plugin), 5, $msg);
	    break;
    case 'set_chrono_of':
        $quizHandler->setBitOn($quizId, 'quiz_optionsIhm', QUIZMAKER_BIT_USETIMER, 0);
        $buildArr = $quizUtility::buildQuiz($quizId);
        $msg = sprintf(_AM_QUIZMAKER_QUIZ_BUILD_OK,$buildArr['name'],$buildArr['id'],$buildArr['build']);
        redirect_header("questions.php?op=list&" . getParams2list($quizId, $quest_plugin), 5, $msg);
	    break;

    case 'resize_images':
        $msg = sprintf(_AM_QUIZMAKER_RESIZE_IMAGES_CONFIRM, $quizmakerHelper->getConfig('resize_img_width'));
      	xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'resize_images_ok'], $_SERVER['REQUEST_URI'], $msg);
	    break;

    case 'resize_images_ok':
        $quizObj = $quizHandler->get($quizId);
        $nbImages = $quizObj->resizeImages();
        $msg = sprintf(_AM_QUIZMAKER_RESIZE_IMAGES_DONE, $nbImages);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, $msg);
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
        if (!$clPerms->getPermissions('edit_quiz', $catId)){  
            redirect_header("question.php?op=list&cat_id={$catId}", 5, _CO_QUIZMAKER_NO_PERM);
        }

        $uploadArr = $quizUtility::quiz_export($quizId);
        if($uploadArr && $uploadArr['err'] > 0){
            redirect_header("question.php?cat_id={$catId}&quiz_id={$quizId}", 5, $uploadArr['errlib']);
        }
        include_once("questions-list.php");
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
    exit('===>quiz_importFromYml');
        $quizUtility->quiz_importFromYml($quizId);
//         $quizHandler->changeEtat($quizId, $field);
        redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Etat de {$field} Changé");
	    break;

	case 'edit_quiz':
        redirect_header("quiz.php?op=edit&quiz_id={$quizId}&sender=",0,"");
	    break;
    
	case 'update_list':
        //echo "===>quizId = {$quizId} - catId  = {$catId}<br>";

        $list = Request::getArray('quest_list');
        //echoArray("gp"); exit;
        foreach($list AS $id => $arr){
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('quest_id', $id, "="));
            $questionsHandler->updateAll('quest_timer', $arr['timer'], $criteria, $force = false);
            //$startTimer = (isset($arr['startTimer']) ? 1 : 0);
            $startTimer = $arr['startTimer'];
            $questionsHandler->updateAll('quest_start_timer', $startTimer, $criteria, $force = false);
            
            //exclure pageBegin et pageEnd
            $criteria->add(new Criteria('quest_plugin', 'pageBegin', "<>"));
            $criteria->add(new Criteria('quest_plugin', 'pageEnd', "<>"));
            $questionsHandler->updateAll('quest_points', $arr['points'], $criteria, $force = false);
            $questionsHandler->updateAll('quest_weight', $arr['weight'], $criteria, $force = false);

        }
        
        $delArr = array_keys(Request::getArray('delete'));
        if(count($delArr) > 0){
            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE_LIST, $quizId, implode(' / ', $delArr));
          	xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'deletelist'=>implode(',', $delArr), 'op' => 'delete_list'], $_SERVER['REQUEST_URI'], $msg);
// echo "<hr>===>{$msg}<hr>";
// echoArray('gp', "===>quizId = {$quizId}",true);
// require __DIR__ . '/footer.php';

//exit;
        }else{
//        exit("quizId = {$quizId} - catId  = {$catId}");
            redirect_header("questions.php?op=list&questId=$questId&sender=&cat_id={$catId}&quiz_id={$quizId}", 5, "Mise à jour ok");
        }
        
        
	   break;
    
	case 'delete_list':
        $deletelist =  Request::getString('deletelist','');

        $arr = explode(',', $deletelist);
        //echoArray('gp','gp');
        //echoArray($arr,'zzzzzzz',true);
        foreach($arr as $key=>$questIdToDelete){
  	        $questionsHandler->deleteCascade($questIdToDelete);
        }  
  		redirect_header('questions.php?' . getParams2list($quizId, $quest_plugin), 3, _AM_QUIZMAKER_FORM_DELETE_OK);
	   break;
       
       
	case 'sendto_ok':
        $url = "questions.php?op=list&" . getParams2list($quizId, $quest_plugin)."#question-{$questId}";
        redirect_header($url, 5, "Fonctionalité en cours de développement !");
        exit('sendto_ok');

	   break;
       
    } // fin du switch maitre
    
require __DIR__ . '/footer.php';

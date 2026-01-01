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
$GLOBALS['xoopsOption']['template_main'] = 'quizmaker_quiz.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$criteriaCatAllowed->add(new \criteria('cat_actif',1,'='));

$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$catId      = Request::getInt('cat_id', array_key_first($catArr));
$playerId   = Request::getInt('player_id', 2);
$quizSubject    = Request::getString('quiz_subject', '');
$difficulty = Request::getInt('difficulty', 0);

//echoArray($catArr);
// $pg = array_merge($_GET, $_POST);
// echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";

$op    = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$limit = 0;//Request::getInt('limit', $quizmakerHelper->getConfig('userpager'));

// Define Stylesheet
\JANUS\load_css('', false);

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);

$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

$keywords = [];
//----------------------------------------------------
$utility = new \XoopsModules\Quizmaker\Utility();
//echoArray("gp");
//----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('showItem', $catId > 0);
  
      // ----- Listes de selection pour filtrage -----  
         $selectors = $quizHandler->getSelector($catId, $quizSubject);        


          
        if($xoopsUser){
            //$inpPlayer = new \XoopsFormSelect(_CO_QUIZMAKER_PLAYER_STATUS, 'player_id', $playerId);
            $inpPlayer = new \XoopsFormRadio(_CO_QUIZMAKER_PLAYER_STATUS, 'player_id', $playerId);
            $inpPlayer->addOption(0, _CO_QUIZMAKER_PLAYER_ALL);
            $inpPlayer->addOption(1, _CO_QUIZMAKER_PLAYER_YES);
            $inpPlayer->addOption(2, _CO_QUIZMAKER_PLAYER_NONE);
            $inpPlayer->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
      	    //$GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
            
            $criteria =  new \CriteriaCompo(new \Criteria("result_uid", $xoopsUser->uid(), '='));
            $quizPlayer = array_flip($resultsHandler->getList($criteria));
            //echoArray($quizPlayer, "userId : {$xoopsUser->uid()}");
        }else{
            $inpPlayer = new \XoopsFormLabel(_CO_QUIZMAKER_PLAYER_STATUS, _CO_QUIZMAKER_PLAYER_ALL);
        }
        $selectors['select']['inpPlayer'] = $inpPlayer->render();
        

		$quizDifficulty = new \XoopsFormRadio( _CO_QUIZMAKER_DIFFICULT, 'difficulty', $difficulty);
		$quizDifficulty->addOption(0, _CO_QUIZMAKER_DIFFICULT_ALL);
		$quizDifficulty->addOption(1, _CO_QUIZMAKER_DIFFICULT_1);
		$quizDifficulty->addOption(2, _CO_QUIZMAKER_DIFFICULT_2);
		$quizDifficulty->addOption(3, _CO_QUIZMAKER_DIFFICULT_3);
		$quizDifficulty->addOption(4, _CO_QUIZMAKER_DIFFICULT_4);
        $quizDifficulty->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
        $selectors['select']['difficulty'] = $quizDifficulty->render();
        
        
        
        //--------------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('selectors', $selectors);
     // ----- /Listes de selection pour filtrage -----        
        
        $catObj = $categoriesHandler->get($catId);
		$GLOBALS['xoopsTpl']->assign('catTheme', $catObj->getVar('cat_theme'));        
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_CATEGORIES, 'link' => XOOPS_URL . "/modules/quizmaker/categories.php"];        
//$xoBreadcrumbs[] = ['title' => 'zzz' . $catObj->getVar('cat_name'), 'link' => "categories.php?cat_id={$catId}&player_id={$playerId}"];
$xoBreadcrumbs[] = ['title' => _MA_QUIZMAKER_QUIZ . ' : <b>' . $catArr[$catId] . '</b>'];        
        //-------------------------------------
        
//         $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, 'quiz_id', $quizId);
//         $tQuiz = $quizHandler->getListKeyName($catId,null,null,'view');
//         $inpQuiz->addOptionArray($tQuiz);
//         $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
//   	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
//         $selectors['inpQuiz'] = $inpQuiz->render();
  	    $GLOBALS['xoopsTpl']->assign('selector', $selectors);
  	    //$GLOBALS['xoopsTpl']->assign('player_id', $playerId);
  	    $GLOBALS['xoopsTpl']->assign('showQuizSet', ($quizSubject == QUIZMAKER_ALL_ITEMS_KEY));
        // ----- /Listes de selection pour filtrage -----   

  	    //$GLOBALS['xoopsTpl']->assign('isAdmin', $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()));
  	    $GLOBALS['xoopsTpl']->assign('isAdmin', $quizmakerHelper->isUserAdmin());

//-----------------------------------------------------------
    
    //$catObj = $categoriesHandler->get($catId);
    $allQuiz = $quizHandler->getAllQuizAllowed($catId);    
    $quizCount = count($allQuiz);  
//echoArray($allQuiz, "nbQuiz : {$quizCount}");   
	if ($quizCount > 0) {

		// Get All Categories
		foreach(array_keys($allQuiz) as $j) {
            //if (!in_array($j, $quizPerm)) continue;
            $tQuiz = $allQuiz[$j]->getValuesQuiz();

            if($difficulty > 0 && $tQuiz['difficulty'] != $difficulty) continue;
            if($quizSubject!='' && $tQuiz['subject'] != $quizSubject && $quizSubject !=QUIZMAKER_ALL_ITEMS_KEY && count($selectors['arr']['subject'])>1) continue;
            //si $inpPlayer = 1 on ne prenda que si la clé de $quizPlayer 'existe'
            //si $inpPlayer = 2 on ne prenda que si la clé de $quizPlayer ,'existe pas'
            //si $inpPlayer = 0 on prenda tout
           if($playerId == 1){
                if (!isset($quizPlayer[$tQuiz['id']])) continue;
           }else if($playerId == 2){
                if (isset($quizPlayer[$tQuiz['id']])) continue;
           }
            
            
            
            //Ajout des statistiques
            $stat = $quizHandler->getStatistics($j);
            //echoArray($stat);       
            
			//if(	$tQuiz['periodeOK']) $quizArr[$j] = $tQuiz;
			$quizArr[$j] = $tQuiz;
            if (isset($stat[$j]) && $stat[$j]['statOk']){
                $quizArr[$j]['stat'] = $stat[$j];
                $quizArr[$j]['statOk'] = true;
            }else{
                $quizArr[$j]['statOk'] = false;
            }
            
		}
        $i=0;
        //mis dans un tableau pour compatibilite quand toutes les categories puvaient etre affichées.
        //a modifier à l'ocation en assignat le tableau des quiz directement
        $catObj = $categoriesHandler->get($catId);
        $categories[$i] = $catObj->getValuesCategoriesLight();
        $categories[$i]['quiz'] = $quizArr;
    }
    
    //recherche des quiz de la catégorie
    $GLOBALS['xoopsTpl']->assign('paramsForQuiz', FQUIZMAKER\getParamsForQuiz(1));
    
	$GLOBALS['xoopsTpl']->assign('categories', $categories);
    $GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/quizmaker/assets/css/style.css"));        
//echoArray($categories);    
		unset($categories);
////////////////////////////////////////////////////////////

        
require __DIR__ . '/footer.php';

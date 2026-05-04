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

//$resultId = Request::getInt('result_id');


require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_RESULT,'QUIZMAKER_PERMIT_RESULT', "index.php", QUIZMAKER_ADMIN_PERM);

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
$domaine = Request::getCmd('domaine', 'results');
// Request quest_id

$sender   = Request::getString('sender', '');
$catId    = Request::getInt('cat_id', 0);
$resultId = Request::getInt('result_id', 0);
$cookieId = Request::getInt('cookie_id', 0);
if ($sender == 'cat_id') {
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
  $quizId = Request::getInt('result_quiz_id', 0);
  if ($quizId == 0) $quizId  = Request::getInt('quiz_id', 1);
}

$redirectParams = "cat_id={$catId}&quiz_id={$quizId}&domaine={$domaine}";
$redirectURL = "participation.php?{$redirectParams}"; //il faut ajout op=??? dans le traitement spécifique
//  $quizId  = Request::getInt('quiz_id', 1);

// $questId = Request::getInt('quest_id', 0);
// $quest_plugin = Request::getString('quest_plugin', '');

//$gp = array_merge($_GET, $_POST);
//echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

//echoGPF('GP');
    $start = Request::getInt('start', 0);
    $limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
    $templateMain = 'quizmaker_admin_participation.tpl';
        ///-------------------------------------------------------

        //recupe du quiz a afficher
        $quiz = $quizHandler->get($quizId);
        if ($quiz) {
            $quizValues = $quiz->getValuesQuiz();
            $catId = $quiz->getVar('quiz_cat_id');
        }
        
        // ----- Listes de selection pour filtrage -----  
        $catArr = $categoriesHandler->getListKeyName();
        if ($catId == 0) $catId = array_key_first($catArr);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"'.FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
        $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
       
        $quizArr = $quizHandler->getListKeyName($catId);
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizArr);
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
        $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());

        //selection du domaine : cookies ou results
        $inpDomaine = new \XoopsFormSelect(_AM_QUIZMAKER_DOMAINE, 'domaine', $domaine);
        $inpDomaine->addOption('results', _AM_QUIZMAKER_RESULTS);
        $inpDomaine->addOption('cookies', _AM_QUIZMAKER_COOKIES);
        $inpDomaine->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"'.FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
        $GLOBALS['xoopsTpl']->assign('inpDomaine', $inpDomaine->render());
        $GLOBALS['xoopsTpl']->assign('domaine', $domaine);
       // ----- /Listes de selection pour filtrage -----    
        
        
        $btn['razResults'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_RAZ_RESULTS, 'delete_all', "{$modUrlIcon16}/delete.png", '', "exportToCsv();");
        
        

        $btn['exporCSV'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_EXPORT_CSV, 'export_csv', "{$modUrlIcon32}/export.png", '', "exportToCsv();");

        $GLOBALS['xoopsTpl']->assign('btn', $btn);


        //---------------------------------------------------
    $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        ///-------------------------------------------------------
        if($domaine == 'cookies'){
            $permClear = $clPerms->getPermissions('clearcookies_quiz', $catId);
            include_once('participation-cookies.php');
        }else{
            $permClear = $clPerms->getPermissions('clearresults_quiz', $catId);
            include_once('participation-results.php');
        }
        //echo "<hr>permClear : " . (($permClear) ? 'oui' : 'non') . "<hr>";
        $GLOBALS['xoopsTpl']->assign('clear_participations', $quizmakerHelper->getConfig('clear_participations'));
        $GLOBALS['xoopsTpl']->assign('allowed_clear', $permClear);
        $GLOBALS['xoopsTpl']->assign('redirectParams',$redirectParams);
        $GLOBALS['xoopsTpl']->assign('redirectURL',$redirectURL);



require __DIR__ . '/footer.php';

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

//$cookieId = Request::getInt('cookie_id');


require __DIR__ . '/header.php';
//$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_COOKIE,'QUIZMAKER_PERMIT_COOKIE', "index.php", QUIZMAKER_ADMIN_PERM);
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_RESULT,'QUIZMAKER_PERMIT_RESULT', "index.php", QUIZMAKER_ADMIN_PERM);

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quest_id

$sender   = Request::getString('sender', '');
$catId    = Request::getInt('cat_id', 0);
$readmeId = Request::getInt('readme_id', 0);

if ($sender == 'cat_id') {
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
  $quizId = Request::getInt('cookie_quiz_id', 0);
  if ($quizId == 0) $quizId  = Request::getInt('quiz_id', 1);
}


//  $quizId  = Request::getInt('quiz_id', 1);

// $questId = Request::getInt('quest_id', 0);
// $quest_plugin = Request::getString('quest_plugin', '');

//$gp = array_merge($_GET, $_POST);
//echo "<hr>_GET/_POST<pre>" . print_r($gp, true) . "</pre><hr>";

switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		//$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = 0;
		$limit = 0;
		$templateMain = 'quizmaker_admin_readme.tpl';
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
       

       // ----- /Listes de selection pour filtrage -----    
        //$btn['razReadme'] = $quizUtility->getNewBtn(_AM_QUIZMAKER_RAZ_README, 'delete_all', "{$modUrlIcon16}/delete.png",  _AM_QUIZMAKER_RAZ_README);

        //---------------------------------------------------
		//$GLOBALS['xoopsTpl']->assign('btn', $btn);
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
           
        ///-------------------------------------------------------
        if ($catId > 0){
          $criteria = new \CriteriaCompo();
          $criteria->add(new \Criteria('readme_cat_id',$catId, "="));
          $readmeCount = $readmeHandler->getCountReadme($criteria);
          $readmeAll = $readmeHandler->getAllReadme($criteria, $start, $limit, 'readme_cat_id, readme_id');
//echo "quizId = {$quizId}<br>";    
        }else{
          $readmeCount = 0;
          $readmeAll = null;
        }
//exit("count = {$readmeCount}");
        ///-------------------------------------------------------
		//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('readme.php'));
        
		//$readmeCount = $readmeHandler->getCountCookies();
		//$readmeAll = $readmeHandler->getAllCookies($start, $limit);
		$GLOBALS['xoopsTpl']->assign('readme_count', $readmeCount);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
// 		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		// Table view readme
		if ($readmeCount > 0) {
			foreach(array_keys($readmeAll) as $i) {
				$readme = $readmeAll[$i]->getValuesReadme();
				$GLOBALS['xoopsTpl']->append('readme_list', $readme);
				unset($readme);
			}
			$GLOBALS['xoopsTpl']->assign('catArr', $catArr);

			// Display Navigation
// 			if ($readmeCount > $limit && $limit > 0) {
// 				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
// 				$pagenav = new \XoopsPageNav($readmeCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
// 				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
// 			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_README);
		}
	break;

/*


	case 'submit_answers':
	break;


	case 'new':
	break;

    
	break;
*/
	case 'delete':
        $readmeId = Request::getInt('readme_id', 0);
		$readmeObj = $readmeHandler->get($readmeId);
        
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('readme.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($readmeHandler->delete($readmeObj)) {
				redirect_header("readme.php?quiz_id={$quizId}", 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $readmeObj->getHtmlErrors());
			}
		} else {
//             $quiz = $quizHandler->get($quizId);
//             $name = $quiz->getVar('quiz_name');
            $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $readmeObj->getVar('readme_id'), "");
			xoops_confirm(['ok' => 1, 'readme_id' => $readmeId, 'cat_id' => $catId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;

	case 'delete_all':
		//$readmeObj = $readmeHandler->get($cookieId);
		//$quizId = $readmeObj->getVar('quiz_id');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('readme.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
            $criteria = new \CriteriaCompo();            
            $criteria->add(new \Criteria('readme_cat_id',$catId, "="));
            
            $ret = $readmeHandler->deleteAll($criteria);
			redirect_header("readme.php?quiz_id={$quizId}", 3, _AM_QUIZMAKER_DELETE_COOKIES_OK);
		} else {
            $cat = $categoriesHandler->get($catId);
            //$quizValues = $quiz->getValuesQuiz();
            $name = $cat->getVar('cat_name');
        
            $msg = sprintf(_AM_QUIZMAKER_CONFIRM_RAZ_README, $name, $catId); 
            //sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $readmeObj->getVar('cookie_quiz_id'))
			xoops_confirm(['ok' => 1, 'cat_id' => $catId, 'op' => 'delete_all'], $_SERVER['REQUEST_URI'], $msg);
		}
	break;
}
require __DIR__ . '/footer.php';

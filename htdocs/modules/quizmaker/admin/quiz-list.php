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
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JJD;

if($quizId > 0 && $sender != 'cat_id'){
  $quiz = $quizHandler->get($quizId);
  $catId = $quiz->getVar('quiz_cat_id');
}else{
    $quizId = $quizHandler->getFirstIdOfParent($catId);    
}
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_QUIZ, 'quiz.php?op=new', 'add');
        
		$adminObject->addItemButton(_AM_QUIZMAKER_COMPUTE_WEIGHT, "quiz.php?op=init_weight&cat_id={$catId}", 'update');
        
        //update weight 
//         $initWeight = $quizUtility->getNewBtn(_AM_QUIZMAKER_COMPUTE_WEIGHT, 'init_weight', QUIZMAKER_ICONS_URL."/16/generer-1.png",  _AM_QUIZMAKER_COMPUTE_WEIGHT);
// 		$GLOBALS['xoopsTpl']->assign('initWeight', $initWeight);
        
        
        
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$quizCount = $quizHandler->getCountQuiz();
        
        $criteria = new CriteriaCompo();
        if ($catId > 0)
        $criteria->add(new Criteria('quiz_cat_id',$catId));
		//$criteria->setSort('quiz_weight');        
        //$criteria->setOrder('ASC');
		$quizAll = $quizHandler->getAllQuiz($criteria, $start, $limit, 'quiz_weight ASC, quiz_cat_id ASC,quiz_id');
		$GLOBALS['xoopsTpl']->assign('quiz_count', $quizCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);

      // ----- Listes de selection pour filtrage -----  
      //$cat = $categoriesHandler->getListKeyName(null, true, false);
      $cat = $categoriesHandler->getList();
      $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
      $inpCategory->addOptionArray($cat);
      $inpCategory->setExtra('onchange="document.quizmaker_select_filter.submit()"');
	  $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
       
     // ----- /Listes de selection pour filtrage -----        
	  //pour affichage de la categorie dans la liste
      $GLOBALS['xoopsTpl']->assign('cat', $cat);

      $GLOBALS['xoopsTpl']->assign('allCategories', $catId == 0);
//exit('ici - ' . $quizCount) ;       
        // Table view quiz
		if ($quizCount > 0) {
			foreach(array_keys($quizAll) as $i) {
				$Quiz = $quizAll[$i]->getValuesQuiz();
				$GLOBALS['xoopsTpl']->append('quiz_list', $Quiz);
				unset($Quiz);
			}
			// Display Navigation
			if ($quizCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($quizCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_QUIZ);
		}
        

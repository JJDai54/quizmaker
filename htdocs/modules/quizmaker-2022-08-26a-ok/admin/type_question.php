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
 * QuizMaker module for xoops
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
//use JJD;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request type_id
$typeId = Request::getInt('type_id');



$sender  = Request::getString('sender', '');
$catId   = Request::getInt('cat_id', 0);

$catArray = $categoriesHandler->getListKeyName();  
if ($sender == 'cat_id'){
    $quizId = $quizHandler->getFirstIdOfParent($catId);
}else{
    $quizId  = Request::getInt('quiz_id', 0);
}


// if ($quizId > 0) {
//     $quizObj = $quizHandler->get($quizId);
//     $catId = $quizObj->getVar('quiz_ct_id');
// }else{
//     $keys = array_keys ($catArray);
//     $catId = $keys[0];
//     $quizId = $quizHandler->getFirstIdOfParent($catId);
//}




switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_type_question.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('type_question.php'));
        
		$type_questionAll = $type_questionHandler->getAll($start, $limit);
		$type_questionCount = count($type_questionAll);
//echo "<hr>type_questions<pre>" . print_r($type_questionAll, true) . "</pre><hr>";        
		$GLOBALS['xoopsTpl']->assign('type_question_count', $type_questionCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
        
        // ----- Listes de selection pour filtrage -----  
        //if ($catId == 0) $catId = $quiz->getVar('quiz_cat_id');
        //$cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArray);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
        //$inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
        
       // ----- /Listes de selection pour filtrage -----        
    
        $btnGoToQuestion = $quizUtility->getNewBtn(_AM_QUIZMAKER_STRANGER_EXP, 'list', QUIZMAKER_ICONS_URL."/16/question.png",  "");
		$GLOBALS['xoopsTpl']->assign('btnGoToQuestion', $btnGoToQuestion);
        
        
        
        // Table view type_question
		//$GLOBALS['xoopsTpl']->append('type_question_list', $type_questionAll);
		$GLOBALS['xoopsTpl']->assign('type_question_list', $type_questionAll);
                        
\JJD\include_highslide();
	break;

    case 'add_new_question':
    
        // ----- Listes de selection pour filtrage -----  
        if ($catId == 0) $catId = $quiz->getVar('quiz_cat_id');
        $cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($cat);
        $inpCategory->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpCategory', $inpCategory->render());
        
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
        $inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
        
       // ----- /Listes de selection pour filtrage -----        
    
    
    
        $typeQuestion = Request::getString('type_question');
        $templateMain = 'quizmaker_admin_type_new_question.tpl';
        echo "<hr>add_new_question : {$typeQuestion}<hr>";
        $form = $type_questionHandler->getFormType_question($typeQuestion);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
    break;


}
require __DIR__ . '/footer.php';

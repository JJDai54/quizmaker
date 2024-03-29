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

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$catId  = Request::getInt('cat_id', 0);
$quizId = Request::getInt('quiz_id', 0);

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  
$templateMain = 'quizmaker_admin_export.tpl';

////////////////////////////////////////////////////////////////////////
list_on_errors:        
switch($op) {
	case 'export_ok':
        if ($quizId > 0) $quizUtility::exportQuiz($quizId);
        
    case 'export':
    case 'list':
	default:
        if($objError->getErrors())
            $errors = $objError->getHtmlErrors();
        else
            $errors = '';
        
      $GLOBALS['xoopsTpl']->assign('error', $errors);
      $objError = new \XoopsObject();     
      //----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('buttons', '');
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
// 		if (false === $action) {
// 			$action = $_SERVER['REQUEST_URI'];
// 		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		
        
        // Title
		$title = _AM_QUIZMAKER_EXPORT_YML;        
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form_export', 'export.php', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'export_ok'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

        // ----- Listes de selection pour filtrage -----  
        $catArr = $categoriesHandler->getListKeyName(null, false, false, null);
        if ($catId == 0) $catId = array_key_first($catArr);        
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra("onchange=\"document.form_export.op.value='list';document.form_export.sender.value=this.name;document.form_export.submit();\"");
  	    $form->addElement($inpCategory);

        $quizArr = $quizHandler->getListKeyName($catId);        
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
        //$inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
  	    $form->addElement($inpQuiz);
        
        //-----------------------------------------------$caption, $name, $value = '', $type = 'button'
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_EXPORTER, 'submit'));
//echo $form->render()  ;      
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
/////////////////////////////////////////        


    
    break;
    

}
/////////////////////////////////////////   
if($objError->getErrors()){
    $actionArr = array('list'=>array('list'=>'list'));     
    goto list_on_errors;
}

require __DIR__ . '/footer.php';

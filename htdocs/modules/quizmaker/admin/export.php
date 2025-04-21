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
use XoopsModules\Quizmaker\Utility;

require __DIR__ . '/header.php';
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'export_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);


// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$catId  = Request::getInt('cat_id', 0);
$quizId = Request::getInt('quiz_id', 0);
$modeName = Request::getInt('mode_name', 0);
$suffix = Request::getInt('suffix', 0);

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  
$templateMain = 'quizmaker_admin_export.tpl';

////////////////////////////////////////////////////////////////////////
list_on_errors:        
switch($op) {
	case 'export_ok':
        $clPerms->checkAndRedirect('export_quiz', $catId, "{$catId}", "index.php");
        if ($quizId > 0) $quizUtility::quiz_export($quizId, $modeName, $suffix);
        
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
        if ($catId == 0) $catId = array_key_first($catArr);        
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, 'cat_id', $catId);
        $inpCategory->addOptionArray($catArr);
        $inpCategory->setExtra("onchange=\"document.form_export.op.value='list';document.form_export.sender.value=this.name;document.form_export.submit();\"".FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
  	    $form->addElement($inpCategory);
    


        $quizArr = $quizHandler->getListKeyName($catId);        
        if ($quizId == 0 || !$quiz) {
            $quizId = array_key_first($quizArr);
            $quiz = $quizHandler->get($quizId);
        }
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
        //$inpQuiz->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
        $inpQuiz->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));
  	    $form->addElement($inpQuiz);

        $inpModeName = new \XoopsFormRadio(_AM_QUIZMAKER_FILE_NAME, 'mode_name', $modeName, '<br>');
        $inpModeName->addOption(0, _AM_QUIZMAKER_FILE_NAME_QUIZ_NAME);     
        $inpModeName->addOption(1, _AM_QUIZMAKER_FILE_NAME_FOLDERJS_NAME);        
  	    $form->addElement($inpModeName);
        
        $inpSuffix = new \XoopsFormRadio(_AM_QUIZMAKER_FILE_SUFFIX, 'suffix', $suffix, '<br>');
        $inpSuffix->addOption(0, _AM_QUIZMAKER_FILE_NAME_KEEP_NAME);     
        $inpSuffix->addOption(1, _AM_QUIZMAKER_FILE_NAME_ADD_TIMESTAMP);        
        $inpSuffix->addOption(2, _AM_QUIZMAKER_FILE_NAME_ADD_RANDOM);     
  	    $form->addElement($inpSuffix);
           
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

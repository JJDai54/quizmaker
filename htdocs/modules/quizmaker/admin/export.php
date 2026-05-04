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

//echo __FILE__ . '<br>';
//echoArray("GP");
// It recovered the value of argument op in URL$

// Request quiz_id
$catId  = Request::getInt('cat_id', 0);
$quizId = Request::getInt('quiz_id', 0);
$modeName = Request::getInt('mode_name', 0);
$suffix = Request::getInt('suffix', 0);
$emptyfld = Request::getInt('empty_fld', 0);
$quizSubject = Request::getString('quiz_subject', '');
$quizDifficulty = Request::getInt('quiz_difficulty', 0);

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  
$templateMain = 'quizmaker_admin_export.tpl';

$op = Request::getCmd('op', 'list');
if(isset($_POST[_SUBMIT]) && $quizId > 0) 
    $op ='export_all' ;
else if(isset($_POST[_SUBMIT]) && $quizId == 0)
    $op ='export_all' ;
//echoArray($_POST);
$paramsGet= "t&cat_id={$catId}&quiz_subject={$quizSubject}&&quiz_id={$quizId}mode_name={$modeName}&suffix={$suffix}&empty_fld={$emptyfld}";

////////////////////////////////////////////////////////////////////////
list_on_errors:        
switch($op) {
	case 'export_all':
        $msg = $quizUtility::quiz_export_all($catId, $quizSubject, $quizId, $modeName, $suffix, $emptyfld);
        redirect_header("export.php?op=list&{$paramsGet}", 12, $msg);
        break;
        
	case 'export_ok':
exit ('export_ok');
    
        //$clPerms->checkAndRedirect('export_quiz', $catId, "{$catId}", "index.php", QUIZMAKER_ADMIN_PERM);
        if ($quizId > 0) {
            $buildArr = $quizUtility::quiz_export($quizId, $modeName, $suffix, $emptyfld);
           if($uploadArr['err'] > 0){
               redirect_header("export.php?{$paramsGet}", 5, $uploadArr['errlib']);
           }
            $quizUtility::quiz_download_zip($buildArr['href'], $buildArr['name'], 2000);
        }
        
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
		$form = new \XoopsThemeForm($title, 'quizmaker_select_filter', 'export.php', 'post', true);
//		return $form;
//echo "<hr>-------------ICI-------------<hr>";
		// To Save
		//$form->addElement(new \XoopsFormHidden('op', 'export_ok2'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

        // ----- Listes de selection pour filtrage -----  
  	    $form->addElement(new XoopsFormHidden('sender',''));
        $selectors = FQUIZMAKER\getQuestionsSelectorBO($catId, $quizSubject,$quizDifficulty,$quizId, false, '', true, true);

  	    $form->addElement($selectors['cat']['select']);
  	    $form->addElement($selectors['subject']['select']);
  	    //$form->addElement($selectors['difficulty']['select']);
  	    $form->addElement($selectors['quiz']['select']);

        //--------------------------------------------------------
        $inpModeName = new \XoopsFormRadio(_AM_QUIZMAKER_FILE_NAME, 'mode_name', $modeName, '<br>');
        $inpModeName->addOption(0, _AM_QUIZMAKER_FILE_NAME_QUIZ_NAME);     
        $inpModeName->addOption(1, _AM_QUIZMAKER_FILE_NAME_FOLDERJS_NAME);        
  	    $form->addElement($inpModeName);
        
        $inpSuffix = new \XoopsFormRadio(_AM_QUIZMAKER_FILE_SUFFIX, 'suffix', $suffix, '<br>');
        $inpSuffix->addOption(0, _AM_QUIZMAKER_FILE_NAME_KEEP_NAME);     
        $inpSuffix->addOption(1, _AM_QUIZMAKER_FILE_NAME_ADD_TIMESTAMP);        
        $inpSuffix->addOption(2, _AM_QUIZMAKER_FILE_NAME_ADD_RANDOM);     
  	    $form->addElement($inpSuffix);
        
        $inpEmptyFld = new XoopsFormRadioYN(_AM_QUIZMAKER_EXPORT_EMPTY_FLD, 'empty_fld', $emptyfld);
  	    $form->addElement($inpEmptyFld);
   
        //-----------------------------------------------$caption, $name, $value = '', $type = 'button'
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_EXPORTER, 'submit'));
//echo $form->render()  ;      
        //-----------------------------------------------------  
        // ajout de la liste des suiz esporté si il en a eu
        $tbl = $quizUtility->getQuizExportArr(3);
        if($tbl){
          $GLOBALS['xoopsTpl']->assign('exportCount', $tbl->countElements());
    	  $form->addElement($tbl);
        }
        //-----------------------------------------------------  
         
         //on fait le ménage pour la prochaine fois
        $quizUtility->quiz_export_clear_flags(0);
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

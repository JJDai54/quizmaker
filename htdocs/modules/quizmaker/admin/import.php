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
use XoopsModules\Quizmaker\Utility;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request quiz_id
$catId  = Request::getInt('cat_id', -1);
$quizId = Request::getInt('quiz_id');

$utility = new \XoopsModules\Quizmaker\Utility();  
//echo "<hr> = {$catId}<hr>";
// echo "===>{$op}<br>";
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";
// echo "<hr><pre>" . print_r($_POST, true) . "</pre><hr>";
// echo "<hr><pre>" . print_r($_GET, true) . "</pre><hr>";
function getParams2list($quizId, $quest_type_question){
global $quizHandler;
    $catId = $quizHandler->getParentId($quizId);
    return $params = "sender=&cat_id={$catId}&quiz_id={$quizId}&quest_type_question={$quest_type_question}";
}

////////////////////////////////////////////////////////////////////////
switch($op) {
	case 'import_ok':
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";


          include_once XOOPS_ROOT_PATH . '/class/uploader.php';
          $filename       = $_FILES['quizmaker_files']['name'];
          $imgMimetype    = $_FILES['quizmaker_files']['type'];
          //$imgNameDef     = Request::getString('sld_short_name');
          $uploaderErrors = '';
          $uploader = new \XoopsMediaUploader(QUIZMAKER_UPLOAD_FILES_PATH , 
                                                      array('application/x-gzip','application/zip', 'text/plain','application/gzip','application/x-compressed','application/x-zip-compressed'), 
                                                      50000, null, null);
     

 
                                                      
          if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
              $h= strlen($filename) - strrpos($filename, '.');  
              $fileName = "new-quiz"; 
                  $uploader->setPrefix($fileName . "-");
                  $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                  if (!$uploader->upload()) {
                      $uploaderErrors = $uploader->getErrors();
//                      echo "<hr>Errors upload : {$uploaderErrors}<hr>";
                      exit;
                  } else {
                      $savedFilename = $uploader->getSavedFileName();
                      
                      $fullName =  QUIZMAKER_UPLOAD_FILES_PATH . "/". $savedFilename;
                      $destPath = QUIZMAKER_UPLOAD_FILES_PATH . "/files_new_quiz";
                      \JJD\unZipFile($fullName, $destPath);
                      $newQuizId = $quizUtility::loadAsNewData($destPath, $catId);
                  }
                } 


            redirect_header("questions.php?op=list&quiz_id={$newQuizId}&sender=", 5, "Importation Ok dans quiz_id={$newQuizId}");
    break;
    
    case 'import':
    case 'list':
	default:
		$templateMain = 'quizmaker_admin_import.tpl';
		$helper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		
        
        // Title
		$title = _AM_QUIZMAKER_IMPORT;        
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form_import', 'import.php', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'import_ok'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

        
        //$upload_size = $helper->getConfig('maxsize_image'); 
        $upload_size = 50000;
        $uploadTray = new \XoopsFormFile(_AM_QUIZMAKER_FILE_TO_LOAD, 'quizmaker_files', $upload_size);     
        $uploadTray->setDescription(_AM_QUIZMAKER_FILE_DESC . '<br>' . sprintf(_AM_QUIZMAKER_FILE_UPLOADSIZE, $upload_size / 1024), '<br>');
        $form->addElement($uploadTray, true);

        // ----- Listes de selection pour filtrage -----  
        $cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOptionArray($cat);
        $inpCategory->setDescription(_AM_QUIZMAKER_SELECT_CATEGORY_DESC);
  	    $form->addElement($inpCategory);

        //----------------------------------------------- 
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_IMPORTER, 'submit'));
  




//echo $form->render()  ;      
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
   


    
    break;
    

}
require __DIR__ . '/footer.php';

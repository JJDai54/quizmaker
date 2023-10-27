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
//use JJD;
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";
if(count($_GET)>0) echo "<hr>GET : <pre>" . print_r($_GET, true) . "</pre><hr>";
if(count($_POST)>0) echo "<hr>POST : <pre>" . print_r($_POST, true) . "</pre><hr>";
if(count($_FILES)>0) echo "<hr>FILES : <pre>" . print_r($_FILES, true) . "</pre><hr>";

$templateMain = 'quizmaker_admin_import.tpl';

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'zzz');
// Request quiz_id
$catId  = Request::getInt('cat_id', -1);
$quizId = Request::getInt('quiz_id');

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  

$upload_size = $quizmakerHelper->getConfig('maxsize_image'); 
$upload_size = 12000000; //maxsize_image


$newFldImport = "/files_new_quiz" ; //. rand(1,1000);
$pathImport = QUIZMAKER_UPLOAD_IMPORT_PATH . $newFldImport;
if (!is_dir(QUIZMAKER_UPLOAD_IMPORT_PATH)) mkdir(QUIZMAKER_UPLOAD_IMPORT_PATH);
if (!is_dir($pathImport)) mkdir($pathImport);
////////////////////////////////////////////////////////////////////////
list_on_errors:        
switch($op) {
	case 'import_ok':

//exit('case = import_ok');                      

\JJD\FSO\isFolder(QUIZMAKER_UPLOAD_IMPORT_PATH, true);
//\JJD\FSO\isFolder(QUIZMAKER_UPLOAD_IMPORT_PATH . "/files_new_quiz", true);
//\JJD\FSO\isFolder(QUIZMAKER_UPLOAD_IMPORT_PATH . "/files_new_quiz", true);

// echo "<hr>Import : _POST<pre>" . print_r($_POST, true) . "</pre><hr>";
// echo "<hr>Import : _FILES<pre>" . print_r($_FILES, true) . "</pre><hr>";
          include_once XOOPS_ROOT_PATH . '/class/uploader.php';
          $filename       = $_FILES['quizmaker_files']['name'];
          $imgMimetype    = $_FILES['quizmaker_files']['type'];
          //$imgNameDef     = Request::getString('sld_short_name');
          $uploaderErrors = '';
          $uploader = new \XoopsMediaUploader(QUIZMAKER_UPLOAD_IMPORT_PATH , 
                      array('application/x-gzip','application/zip', 'text/plain','application/gzip','application/x-compressed','application/x-zip-compressed'), 
                      $upload_size, null, null);
                      $uploaderErrors = $uploader->getErrors();
                      echo "<hr>Errors upload : {$uploaderErrors}<hr>";
 
                                                      
          if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
              $h= strlen($filename) - strrpos($filename, '.');  
              $fileName = "new-quiz"; 
                  $uploader->setPrefix($fileName . "-");
                  $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                  if (!$uploader->upload()) {
                      $uploaderErrors = $uploader->getErrors();
                      echo "<hr>Errors upload : {$uploaderErrors}<hr>";
                      exit;
                  } else {
                      $savedFilename = $uploader->getSavedFileName();
                      
                      $fullName =  QUIZMAKER_UPLOAD_IMPORT_PATH . "/". $savedFilename;

                      
                      chmod($fullName, 0666);
                      chmod($pathImport, 0777);
                      \JJD\unZipFile($fullName, $pathImport);
                      \JJD\FSO\setChmodRecursif(QUIZMAKER_UPLOAD_IMPORT_PATH, 0777);
                      $newQuizId = $quizUtility::import_quiz($pathImport, $catId);
                  }
                } 

            redirect_header("questions.php?op=list&quiz_id={$newQuizId}&sender=", 5, "Importation Ok dans quiz_id={$newQuizId}");
    break;
    
    case 'import':
    case 'list':
        if($objError->getErrors())
            $errors = $objError->getHtmlErrors();
        else
            $errors = '';
        
      $GLOBALS['xoopsTpl']->assign('error', $errors);
      $objError = new \XoopsObject();     
      //----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('buttons', '');        
    
        //$quizUtility::deleteTree($pathImport);                      
        //$quizUtility::rmAllDir($pathImport);     exit;  
        $quizUtility::deleteDirectory(QUIZMAKER_UPLOAD_IMPORT_PATH . "/files_new_quiz");                      
        $quizUtility::createFolder(QUIZMAKER_UPLOAD_IMPORT_PATH . "/files_new_quiz");                      
        $quizUtility::createFolder(QUIZMAKER_UPLOAD_IMPORT_PATH . "/files_new_quiz/images");                      
        
        $utility = new Quizmaker\Utility();
        //$utility::rrmdir($pathImport . '/images');
        $utility::clearFolder($pathImport . '/images');
        $utility::clearFolder($pathImport );
    
        /** @var Quizmaker\Utility $utility */
    


               
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
		$title = _AM_QUIZMAKER_IMPORT;        
		// Get Theme Form
		//xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form_import', 'import.php', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'import_ok'));
		$form->addElement(new \XoopsFormHidden('sender', ''));

        
        $uploadTray = new \XoopsFormFile(_AM_QUIZMAKER_FILE_TO_LOAD, 'quizmaker_files', $upload_size);     
        $uploadTray->setDescription(_AM_QUIZMAKER_FILE_DESC . '<br>' . sprintf(_AM_QUIZMAKER_FILE_UPLOADSIZE . " ($upload_size)", intval($upload_size / 1024)), '<br>');
        $form->addElement($uploadTray, true);

        // ----- Listes de selection pour filtrage -----  
        $cat = $categoriesHandler->getListKeyName(null, false, false,null);
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'cat_id', $catId);
        $inpCategory->addOption(0, _AM_QUIZMAKER_SELECT_CATEGORY_ORG);
        $inpCategory->addOptionArray($cat);
        $inpCategory->setDescription(_AM_QUIZMAKER_SELECT_CATEGORY_DESC);
  	    $form->addElement($inpCategory);

        //----------------------------------------------- 
		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_IMPORTER, 'submit'));
  




//echo $form->render()  ;      
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
    
    break;
    
	default:
        exit('case = default');
    break;
    

}

/////////////////////////////////////////   
if($objError->getErrors()){
    $actionArr = array('list'=>array('list'=>'list'));     
    goto list_on_errors;
}
require __DIR__ . '/footer.php';

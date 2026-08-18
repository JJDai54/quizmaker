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
//$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_IMPORTG,'QUIZMAKER_PERMIT_IMPORTG', "index.php", QUIZMAKER_ADMIN_PERM);
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'import_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);

//if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
$fromCatId  = Request::getInt('from_cat_id', array_key_first($catArr));
if($fromCatId <= 0) $fromCatId = array_key_first($catArr);

$toCatId  = Request::getInt('to_cat_id', array_key_first($catArr));
if($toCatId <= 0) $toCatId = array_key_first($catArr);

$catId  = Request::getInt('cat_id', array_key_first($catArr));
if($catId <= 0) $catId = array_key_first($catArr);

//use JANUS;
/*
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";
// if(count($_GET)>0) echo "<hr>GET : <pre>" . print_r($_GET, true) . "</pre><hr>";
 if(count($_POST)>0) echo "<hr>POST : <pre>" . print_r($_POST, true) . "</pre><hr>";
 if(count($_FILES)>0) echo "<hr>FILES : <pre>" . print_r($_FILES, true) . "</pre><hr>";
*/
//Variable globale a utiliser pour tous les types d'import
//$eventOnChange = "onchange='document.form_import.type_import.value=\"{$typeImport}\";document.form_import.op.value=\"getForm\";document.form_import.submit()'";

/****   declaration des variables globale utilisée dans tous les type d'import ********* */
$formName = "form_import";
$typeImportName = 'type_import';
$importMainFile = "import.php";
//$importMainFile = "import_debug.php";
$eventOnChange = "onchange='document.{$formName}.op.value=\"getForm\";document.{$formName}.submit()'";
$styleBreakLine = "<div style='background:#FFCCCC;'><center><b>%s</b></center></div>";
//--------------------------------------------------

$templateMain = 'quizmaker_admin_import.tpl';
$op = Request::getCmd('op', 'getform');
$quizId = Request::getInt('quiz_id');
$typeImport = Request::getString($typeImportName,'quiz');
$pluginName = Request::getString('plugin','');
//echoArray($catArr,array_key_first($catArr));
//echo "<hr>fromCatId = {$fromCatId}<hr>";
$fromQuizId = Request::getInt('from_quiz_id');
$toQuizId = Request::getInt('to_quiz_id');
$orderBy = Request::getString('order_by','quest_plugin');
$groupTo = Request::getString('group_to','');

//echoGPF();exit;

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  

$upload_size = $quizmakerHelper->getConfig('maxsize_import'); //$upload_size = 12000000; 
//echo  "<hr>{$upload_size}<hr>"; exit;
 $newFldImport = "/files_new_quiz" ; //. rand(1,1000);
 $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
if (!is_dir(QUIZMAKER_PATH_UPLOAD_IMPORT)) mkdir(QUIZMAKER_PATH_UPLOAD_IMPORT);
if (!is_dir($pathImport)) mkdir($pathImport);

//echoArray('gpf');
$jjd = Request::getString('jjd','');
//if($jjd == 'stop') exit ($jjd);
//exit;
////////////////////////////////////////////////////////////////////////
function loadFileTo($fldImportDest, &$pathImport, &$savedFilename, $clearFldBefore=true, $deleteArchivesImported=true){
global $upload_size, $quizUtility;
//exit('case = import_ok');                      
$bolOk = true;

$pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . '/' . $fldImportDest;
    \JANUS\FSO\isFolder(QUIZMAKER_PATH_UPLOAD_IMPORT, true);

    include_once XOOPS_ROOT_PATH . '/class/uploader.php';
    $filename       = $_FILES['quizmaker_files']['name'];
    $imgMimetype    = $_FILES['quizmaker_files']['type'];
    //$imgNameDef     = Request::getString('sld_short_name');
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader(QUIZMAKER_PATH_UPLOAD_IMPORT , 
                array('application/x-gzip','application/zip', 'text/plain','application/gzip','application/x-compressed','application/x-zip-compressed'), 
                $upload_size, null, null);
     $uploaderErrors = $uploader->getErrors();
     //echo "<hr>01-Errors upload : {$uploaderErrors}<hr>";
          
            
                                                      
      if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
//  exit("ici");
          $h= strlen($filename) - strrpos($filename, '.');  
          $fileName = $fldImportDest; 
              $uploader->setPrefix($fileName . "-");
              $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
// exit ("===> bbbbbbbbbbbbbbbbb");
              if (!$uploader->upload()) {
                  $uploaderErrors = $uploader->getErrors();
                  echo "<hr>02-Errors upload : {$uploaderErrors}<hr>";
                  exit("error");
              } else {
                 $savedFilename = $uploader->getSavedFileName();
                  
                  $fullName =  QUIZMAKER_PATH_UPLOAD_IMPORT . "/". $savedFilename;

                  chmod($fullName, 0666);
                  chmod($pathImport, 0777);
                  if ($clearFldBefore) $quizUtility::clearFolder($pathImport);
                  \JANUS\unZipFile($fullName, $pathImport);
                  \JANUS\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_IMPORT, 0777);
                  if ($deleteArchivesImported) unlink($fullName);
              }
            }else{
                  $bolOk = false;
            } 
 
 //exit ("===> savedFilename : {$savedFilename}<br>pathImport : {$pathImport}");
 // exit("{$msg}<br>{$url}");
        return $bolOk;
}
/* ***********************************************

************************************************** */
function addXformImportType(&$form, $postName, $value){
global $typeImport, $clPerms, $eventOnChange;
    
      $inpTypeImport = new \XoopsFormSelect(_AM_QUIZMAKER_TYPE_IMPORT, $postName, $typeImport);
      //$inpTypeImport->setDescription(_AM_QUIZMAKER_IMPORT_SELECT_TYPE);
      $inpTypeImport->addOption('quiz',  _AM_QUIZMAKER_TYPE_IMPORT_QUIZ);
      $inpTypeImport->addOption('clone', _AM_QUIZMAKER_TYPE_IMPORT_CLONE_QUIZ);
      $inpTypeImport->addOption('batch', _AM_QUIZMAKER_TYPE_IMPORT_BATCH);

      if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_IMPORTG, true)){
        $inpTypeImport->addOption('quest', _AM_QUIZMAKER_TYPE_IMPORT_QUEST);
        $inpTypeImport->addOption('quest_sql', _AM_QUIZMAKER_TYPE_IMPORT_LOCAL);
        $inpTypeImport->addOption('plugin', _AM_QUIZMAKER_TYPE_IMPORT_PLUGIN);
      }

      $inpTypeImport->setExtra($eventOnChange . ' ' . getXformStyle($postName));      

      $form->addElement($inpTypeImport);
      return true;

}
/* ***********************************************

************************************************** */
function getXformStyle($postName){

    $t = explode("_", $postName);
    switch ($t[0]){
      case 'from'   : $bg = QUIZMAKER_BG_LIST_FROM;       break;
      case 'to'     : $bg = QUIZMAKER_BG_LIST_TO;         break;
      case 'type'   : $bg = QUIZMAKER_BG_LIST_TYPEIMPORT; break;
      default       : $bg = QUIZMAKER_BG_LIST_NONE;       break;
    }

    return FQUIZMAKER\getStyle($bg);
}
/* ***********************************************

************************************************** */
function addXformCat(&$form, $postName, $value, $addEvent = false, $description = ''){
global $catArr,$eventOnChange ;
        $inpCategory = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES_NAME, $postName, $value);
        $inpCategory->addOption(0, _AM_QUIZMAKER_SELECT_CATEGORY_ORG);
        $inpCategory->addOptionArray($catArr);
        if ($description) $inpCategory->setDescription($description);
        if ($addEvent)
            $inpCategory->setExtra($eventOnChange . ' ' . getXformStyle($postName));
        else
            $inpCategory->setExtra(getXformStyle($postName));
        
        $form->addElement($inpCategory);
        return true;
}
/* ***********************************************

************************************************** */
function addXformSet(&$form, $postName, $value, $catId, $addEvent = false, $description = ''){
global $catArr, $eventOnChange, $quizHandler;


    $setArr =  $quizHandler->getFieldList('quiz_subject', $catId);
    if(count($setArr) > 1){
        $inpSet = new \XoopsFormSelect(_CO_QUIZMAKER_QUIZ_SUBJECT, $postName, $value);
        if ($description) $inpSet->setDescription($description);
        $inpSet->addOption(QUIZMAKER_ALL_ITEMS_KEY, QUIZMAKER_ALL_ITEMS_LIB);
        $inpSet->addOptionArray($setArr);
        if ($addEvent)
            $inpSet->setExtra($eventOnChange . ' ' . getXformStyle($postName));
        else
            $inpSet->setExtra(getXformStyle($postName));
            
        $form->addElement($inpSet);
    }else{$fromQuizSet = '';}

    return true;
}

/* ***********************************************

************************************************** */
function addXformQuiz(&$form, $postName, $value, $catId, $quizSubject, $addEvent = false, $description = ''){
global $catArr, $eventOnChange, $quizHandler;


    $quizArr = $quizHandler->getListKeyName($catId, $quizSubject);

    $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, $postName, $value);
    if ($description) $inpQuiz->setDescription($description);
    $inpQuiz->addOptionArray($quizArr);
        if ($addEvent)
            $inpQuiz->setExtra($eventOnChange . ' ' . getXformStyle($postName));
        else
            $inpQuiz->setExtra(getXformStyle($postName));

  	$form->addElement($inpQuiz);

    return true;

}
/* ***********************************************

************************************************** */
function addXformQestOrderBy(&$form, $postName, $value, $addEvent = false, $description = ''){
global $eventOnChange;

        //if(!$value) $value = 'quest_plugin';
        $inpOrderBy = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_ORDER_BY, $postName, $value);
        $inpOrderBy->addOptionArray(['quest_question' => _AM_QUIZMAKER_QUIZ_ORDER_BY_QUESTION,
                                     'quest_weight'   => _AM_QUIZMAKER_QUIZ_ORDER_BY_WEIGHT,
                                     'quest_plugin'   => _AM_QUIZMAKER_QUIZ_ORDER_BY_PLUGINS,
                                     'quest_id'       => _AM_QUIZMAKER_QUIZ_ORDER_BY_ID]);

        if ($addEvent)
            $inpOrderBy->setExtra($eventOnChange . ' ' . getXformStyle($postName));
        else
            $inpOrderBy->setExtra(getXformStyle($postName));
            
  	    $form->addElement($inpOrderBy);

    return true;

}
/* ***********************************************

************************************************** */
function addXformBtnSubmit(&$form, $caption = _AM_QUIZMAKER_IMPORTER){
    $form->addElement(new \XoopsFormButton('', _SUBMIT, $caption, 'submit'));
}
/* ***************** actions ********************** */

// It recovered the value of argument op in URL$
list_on_errors:

        // ----- selection du type d'importation -----  

        switch($typeImport){
            case 'error':
                $errors = sprintf(_AM_QUIZMAKER_IMPORT_ERROR_01, $upload_size/1000 . "ko"); 
                break;

            case 'batch':
                include_once "import-batch.php";
                break;
            case 'quest':
                include_once "import-quest.php";
                break;
            case 'quest_sql': 
                include_once "import-quest_sql.php";
                break;
            case 'plugin':
                include_once "import-plugin.php";
                break;
            case 'clone':
                include_once "import-clone_quiz.php";
                break;
            case 'quiz':
            default:
                include_once "import-quiz.php";
                break;
        }
        

/////////////////////////////////////////   
if($objError->getErrors()){
    $actionArr = array('list'=>array('list'=>'list'));     
    goto list_on_errors;
}
require __DIR__ . '/footer.php';

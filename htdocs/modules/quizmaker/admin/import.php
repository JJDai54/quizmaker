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
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_IMPORTG,'QUIZMAKER_PERMIT_IMPORTG', "index.php");
//-----------------------------------------------------------
//recherche des categories autorisées
$clPerms->addPermissions($criteriaCatAllowed, 'export_quiz', 'cat_id');
$catArr = $categoriesHandler->getList($criteriaCatAllowed);
if(!$catArr) redirect_header("index.php", 5, _CO_QUIZMAKER_NO_PERM);
    
//use JJD;
/*
$pg = array_merge($_GET, $_POST);
//echo "<hr>GET/POST : <pre>" . print_r($pg, true) . "</pre><hr>";
// if(count($_GET)>0) echo "<hr>GET : <pre>" . print_r($_GET, true) . "</pre><hr>";
 if(count($_POST)>0) echo "<hr>POST : <pre>" . print_r($_POST, true) . "</pre><hr>";
 if(count($_FILES)>0) echo "<hr>FILES : <pre>" . print_r($_FILES, true) . "</pre><hr>";
*/

$templateMain = 'quizmaker_admin_import.tpl';

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'zzz');
// Request quiz_id
$catId  = Request::getInt('cat_id', -1);
$quizId = Request::getInt('quiz_id');
$typeImport = Request::getString('type_import','file');
$typeQuestion = Request::getString('type_question','');

$fromCatId  = Request::getInt('from_cat_id', -1);
$fromQuizId = Request::getInt('from_quiz_id');
$toCatId  = Request::getInt('to_cat_id', -1);
$toQuizId = Request::getInt('to_quiz_id');
$orderBy = Request::getString('order_by','quest_type_question');
$groupTo = Request::getString('group_to','');

//echoGPF();

$objError = new \XoopsObject();        
$utility = new \XoopsModules\Quizmaker\Utility();  

$upload_size = $quizmakerHelper->getConfig('maxsize_import'); //$upload_size = 12000000; 
//echo  "<hr>{$upload_size}<hr>"; exit;
$newFldImport = "/files_new_quiz" ; //. rand(1,1000);
$pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
if (!is_dir(QUIZMAKER_PATH_UPLOAD_IMPORT)) mkdir(QUIZMAKER_PATH_UPLOAD_IMPORT);
if (!is_dir($pathImport)) mkdir($pathImport);

////////////////////////////////////////////////////////////////////////
function loadFile2Import(){
//exit('case = import_ok');                      
$bolOk = true;
$newFldImport = "/files_new_quiz" ; //. rand(1,1000);
$pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
    \JJD\FSO\isFolder(QUIZMAKER_PATH_UPLOAD_IMPORT, true);

    include_once XOOPS_ROOT_PATH . '/class/uploader.php';
    $filename       = $_FILES['quizmaker_files']['name'];
    $imgMimetype    = $_FILES['quizmaker_files']['type'];
    //$imgNameDef     = Request::getString('sld_short_name');
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader(QUIZMAKER_PATH_UPLOAD_IMPORT , 
                array('application/x-gzip','application/zip', 'text/plain','application/gzip','application/x-compressed','application/x-zip-compressed'), 
                $upload_size, null, null);
     $uploaderErrors = $uploader->getErrors();
     echo "<hr>01-Errors upload : {$uploaderErrors}<hr>";
          
            
                                                      
      if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
//  exit("ici");
          $h= strlen($filename) - strrpos($filename, '.');  
          $fileName = "new-quiz"; 
              $uploader->setPrefix($fileName . "-");
              $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
              if (!$uploader->upload()) {
                  $uploaderErrors = $uploader->getErrors();
                  echo "<hr>02-Errors upload : {$uploaderErrors}<hr>";
                  exit;
              } else {
                 $savedFilename = $uploader->getSavedFileName();
                  
                  $fullName =  QUIZMAKER_PATH_UPLOAD_IMPORT . "/". $savedFilename;

                  
                  chmod($fullName, 0666);
                  chmod($pathImport, 0777);
                  \JJD\unZipFile($fullName, $pathImport);
                  \JJD\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_IMPORT, 0777);
              }
            }else{
                  $bolOk = false;
            } 
//  exit("{$msg}<br>{$url}");
        return $bolOk;
}
////////////////////////////////////////////////////////////////////////
list_on_errors:        
switch($op) {
	case 'import_file':
        if (loadFile2Import()){
            $newQuizId = $quizUtility::quiz_importFromYml($pathImport, $catId);
            $msg = sprintf(_AM_QUIZMAKER_IMPORT_OK,$newQuizId);
            $url = "questions.php?op=list&quiz_id={$newQuizId}&sender=&libErr={$msg}";
        }else{
            //echo "<hr>03-Errors upload : {$uploaderErrors}<hr>";
            $msg = sprintf(_AM_QUIZMAKER_IMPORT_ERROR_01, $upload_size/1000 . "ko");
            $url = "import.php?op=error&numerr=1";
        }
//  exit("{$msg}<br>{$url}");
            redirect_header($url, 5, $msg);
    break;
    

    case 'batch_import':
        $filesOk = $op = Request::getArray('zipFiles');
        $allFiles = $op = Request::getArray('fullName');
        
        foreach($filesOk as $key=>$name){
            echo $allFiles[$name] . "<br>";
            $fullName = $allFiles[$name];
          //dossier provisoir pour decompresser l'archive
          $newFldImport = "/files_new_quiz" ;  
          $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . $newFldImport;
          chmod($fullName, 0666);
          chmod($pathImport, 0777);
          \JJD\unZipFile($fullName, $pathImport);
          \JJD\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_IMPORT, 0777);
          $newQuizId = $quizUtility::quiz_importFromYml($pathImport, $catId);
          //sleep(int $seconds)      
          //exit;
          $quizUtility::buildQuiz($newQuizId);
        }
        
        redirect_header("quiz.php", 3, sprintf(_AM_QUIZMAKER_IMPORT_QUIZ_OK, count($filesOk)));
       
    
    //nom complet de l'archive dans le dossier du plugin
//echo "archive : {$fullName}<br>";
    
/*

*/       
        
    break;

    case 'quest_import':
       //echoGPF("GPF","quest_import",true);
        if (loadFile2Import()){
            $typeQuestionSelected = Request::getArray('types_question_selected',null);

            $newQuizId = $quizUtility::quiz_importOnlyQuestFromYml($pathImport, $quizId, $typeQuestionSelected, array('pageBegin','pageEnd'));                      

            $msg = sprintf(_AM_QUIZMAKER_IMPORT_OK,$newQuizId);
            $url = "questions.php?op=list&quiz_id={$newQuizId}&sender=&libErr={$msg}";
        }else{
            //echo "<hr>03-Errors upload : {$uploaderErrors}<hr>";
            $msg = sprintf(_AM_QUIZMAKER_IMPORT_ERROR_01, $upload_size/1000 . "ko");
            $url = "import.php?op=error&numerr=1";
        }
//  exit("{$msg}<br>{$url}");
            redirect_header($url, 5, $msg);
    break;
    
    case 'quest_sql_import':
//echoGPF();
        //$quest_Ids = explode(",","5182,5183,5184,5185");
        $quest_Ids = Request::getArray('questions_selected');
        $quizIdTo = Request::getInt('select_to_quiz_id');
        $quizIdFrom = Request::getInt('select_from_quiz_id');
        $groupTo = Request::getString('select_group_to');
        //$orderBy = Request::getInt('select_order_by');
        $quizUtility->quiz_import_sql($quest_Ids, $quizIdFrom, $quizIdTo,$groupTo);


            //$url = "import.php?op=list&type_import={$typeImport}";
            $url = "questions.php?op=list&quiz_id={$quizIdTo}";
            $msg = "Importation ok!";
            redirect_header($url, 5, $msg);
    break;
    
    case 'error':
        $errors = sprintf(_AM_QUIZMAKER_IMPORT_ERROR_01, $upload_size/1000 . "ko"); 
    case 'import':
    case 'list':
        // ----- selection du type d'importation -----  
        define('_AM_QUIZMAKER_TYPE_IMPORT',"Type d'importation");
        define('_AM_QUIZMAKER_TYPE_IMPORT_FILE',"Importation d'un quiz unique");
        define('_AM_QUIZMAKER_TYPE_IMPORT_BATCH',"Importation d'un lot de quiz");
        define('_AM_QUIZMAKER_TYPE_IMPORT_QUEST',"Importation des questions uniquement");
        define('_AM_QUIZMAKER_TYPE_IMPORT_MOD',"Importation des questions d'un autre quiz");
        
        
        $inpTypeImport = new \XoopsFormSelect(_AM_QUIZMAKER_TYPE_IMPORT, 'type_import', $typeImport);
        $inpTypeImport->addOption('file',  _AM_QUIZMAKER_TYPE_IMPORT_FILE);
        $inpTypeImport->addOption('batch', _AM_QUIZMAKER_TYPE_IMPORT_BATCH);
        if($clPerms->getPermissions('global_ac', QUIZMAKER_PERMIT_IMPORTG, true)){
          $inpTypeImport->addOption('quest', _AM_QUIZMAKER_TYPE_IMPORT_QUEST);
          $inpTypeImport->addOption('quest_sql', _AM_QUIZMAKER_TYPE_IMPORT_MOD);
        }
        $inpTypeImport->setExtra('onchange="document.quizmaker_select_import.submit()"' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEIMPORT));
              
		$GLOBALS['xoopsTpl']->assign('inpTypeImport', $inpTypeImport->render());
        

//echoGPF();
        switch($typeImport){
            case 'batch':
                include_once "import-batch.php";
                break;
            case 'quest':
                include_once "import-quest.php";
                break;
            case 'quest_sql':
                include_once "import-quest_sql.php";
                break;
            case 'batch':
            default:
                include_once "import-file.php";
                break;
        }
        
        //----------------------------------------------- 
        //affichage des imports par lot
        //----------------------------------------------- 

    
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

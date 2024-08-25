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
use Common\FilesManagement; // Files Management Trait
    
//-----------------------------------------------

require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_MINIFY,'QUIZMAKER_PERMIT_MINIFY', "index.php");

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
if (isset($_POST['submit'])){
    $op = 'minify_ok';
    $select = array_key_first($_POST['submit']);
}else{
    $op = 'list';
    $select = 'none';
} 

// Request quiz_id
$catId  = Request::getInt('cat_id', 0);
$quizId = Request::getInt('quiz_id', 0);

$utility = new \XoopsModules\Quizmaker\Utility();  
$templateMain = 'quizmaker_admin_minify.tpl';
$objError = new \XoopsObject();        
//$errors = '';

////////////////////////////////////////////////////////////////////////


//echo "<hr>{$pathSource}<br>nb files = " . count($filesList) . "<hr>";
include_once("../class/Minifier.php");       


/* ***
*
**** */
function getQuizFolder($root, $folder = ''){
    if($folder == '' || $folder == QUIZMAKER_ALL){
      return $root; 
    }else{
      return $root . '/' . $folder;
    }
}

/* ***
*
**** */
function restaure_folder($folder = ''){
global $objError;
    if($folder == ''){
      $org = QUIZMAKER_PATH_QUIZ_ORG; 
      $min   = QUIZMAKER_PATH_QUIZ_MIN; 
    }else{
      $org = QUIZMAKER_PATH_QUIZ_ORG . '/' . $folder;
      $min   = QUIZMAKER_PATH_QUIZ_MIN . '/' . $folder;
    }
//echo "<hr>restaure_folder : <br>{$org}<br>{$min}<hr>";

    Utility::deleteDirectory($min);
    Utility::recurseCopy($org, $min);  
}
/* ***
*
**** */
function getFilesList($folder = ''){
global $objError;
    if($folder == '' || $folder == QUIZMAKER_ALL){
      $org = QUIZMAKER_PATH_QUIZ_ORG; 
      $min   = QUIZMAKER_PATH_QUIZ_MIN; 
    }else{
      $org = QUIZMAKER_PATH_QUIZ_ORG . '/' . $folder;
      $min   = QUIZMAKER_PATH_QUIZ_MIN . '/' . $folder;
    }
    $ext = array('js','css');
    $filesList =  Utility::getRecurseFiles($org,$ext);
    
    //retire  root du nom du fichier
    $lgRoot = strlen(QUIZMAKER_PATH_QUIZ_ORG);
    foreach($filesList as $k=>$f){
        $filesList[$k] = substr($f, $lgRoot);
    }
    //echoArray($filesList, 'minifie_files', false);
    return $filesList; 
} 
/* ***
*
**** */
function minifie_files($folder = ''){
global $objError;
    if($folder == ''){
      $org = QUIZMAKER_PATH_QUIZ_ORG; 
      $min   = QUIZMAKER_PATH_QUIZ_MIN; 
    }else{
      $org = QUIZMAKER_PATH_QUIZ_ORG . '/' . $folder;
      $min   = QUIZMAKER_PATH_QUIZ_MIN . '/' . $folder;
    }
    $ext = array('js','css');
    $filesList =  Utility::getRecurseFiles($org, $ext);
    
    //retire  root du nom du fichier
    $lgRoot = strlen(QUIZMAKER_PATH_QUIZ_ORG);
    foreach($filesList as $k=>$f){
        $filesList[$k] = substr($f, $lgRoot);
    }
    //echoArray($filesList, 'minifie_files', false);
    minifie_filesList($filesList, '');
    return true; 
} 

function minifie_filesList($filesList, $extra = ''){
global $objError;
    $clMin = new Minifier();
      
    foreach($filesList AS $k=>$f){
        $from = QUIZMAKER_PATH_QUIZ_ORG . $f;
        $to   = QUIZMAKER_PATH_QUIZ_MIN . $f;
        
        $pos = strrpos($f, '.');
        $ext = substr($f, $pos+1);
        
        $content = \file_get_contents($from);
        $newContent = $clMin->minify($content, $ext, $extra);
        //$newContent = $clMin->minifyJS($content);
        
        //echo "minifie_filesList Path : {$ext}<br>{$from}<br>{$to}<br><br>";
        if(\file_put_contents($to, $newContent) === false)
            $objError->setErrors("no minification <br>de ===> {$from}<br>vers ===> {$to}");  ;
    }
}

function folder_is_minified($folder = ''){
$bolOk = true;
    $min = getQuizFolder(QUIZMAKER_PATH_QUIZ_MIN, $folder);
    //$filesList = getFilesList($min);
    $filesList = getFilesList($folder);
    //echo "<hr>folder_is_minified : {$folder} [" . count($filesList) . "] => {$min}<hr>";
    
    foreach($filesList AS $k=>$f){
        $fullName = QUIZMAKER_PATH_QUIZ_MIN . '/' . $f;
        //echo "{$folder} => {$fullName}<br>";
        $content = \file_get_contents($fullName);
        //echo"<br>{$content}<br>";
        $nbLines = count(explode("\n", $content));
        if($nbLines > 1) $bolOk = false;
    }
    return $bolOk;
}


/////////////////////////////////////////////////////////////////////
// echoArray($_GET);
//echoArray($_POST);
$actionArr = Request::getArray('action', array('list'=>array('list'=>'list')));
//echoArray($actionArr);
list_on_errors:        

$domaine = array_key_first($actionArr);  
$action = array_key_first($actionArr[$domaine]);  
$caption = $actionArr[$domaine][$action];   
$op=$domaine;  
$msg = null;

$pathSource = QUIZMAKER_PATH_QUIZ_ORG;
$pathDest   = QUIZMAKER_PATH_QUIZ_MIN;


    switch ($domaine){
        case QUIZMAKER_ALL:
            $folder = '';
            break;
        case 'js':
        case 'css':
        case 'language':
        case 'plugins':
            $folder = $domaine;
            break;
        default:
            $folder = 'plugins/' . $domaine;
            break; 
    }

    switch($action) {
        case "restaure":
            restaure_folder($folder);
            break;
            
        case "minifie":
            $filesList =($folder);
            minifie_files($filesList);
            break;
        }
    //---------------------------------------------
        if($objError->getErrors())
            $errors = $objError->getHtmlErrors();
        else
            $errors = '';
        
      $GLOBALS['xoopsTpl']->assign('error', $errors);
      $objError = new \XoopsObject();     
      //----------------------------------------------------

//echoArray($actionArr);
      $GLOBALS['xoopsTpl']->assign('buttons', '');

       $actions_list = array( 
                    QUIZMAKER_ALL => ['desc' => _AM_QUIZMAKER_TOOLS_FOLDER_ALL_DESC,      'isMinified'=> folder_is_minified(QUIZMAKER_ALL)],
                    'js'          => ['desc' => _AM_QUIZMAKER_TOOLS_FOLDER_APP_DESC,      'isMinified'=> folder_is_minified('js')],
                    'css'         => ['desc' => _AM_QUIZMAKER_TOOLS_FOLDER_CSS_DESC,      'isMinified'=> folder_is_minified('css')],
                    //'html'      => ['desc' => _AM_QUIZMAKER_TOOLS_FOLDER_HTML_DESC,     'isMinified'=> folder_is_minified('html')],
                    'language'    => ['desc' => _AM_QUIZMAKER_TOOLS_MINIFY_LANGUAGE_DESC, 'isMinified'=> folder_is_minified('language')],
                    'plugins'     => ['desc' => _AM_QUIZMAKER_TOOLS_FOLDER_PLUGINS_DESC,  'isMinified'=> folder_is_minified('plugins')]
                    );
                    
        //recupe de tous les plugins pour les traiter un par un
		$pluginAll = $pluginsHandler->getAll();
        //echoArray($pluginAll);
        foreach($pluginAll as $k=>$plugin){
            //$actions_list[$plugin['name']] = $plugin['description'];
            $actions_list[$plugin['type']] = ['desc' => $plugin['name'], 'isMinified'=> folder_is_minified("plugins/" . $plugin['type'])];
        }                    
        $xoopsTpl->assign('actions_list', $actions_list);
    	$xoopsTpl->assign('modPathNotes', $modPathIcon16 . "/notes");





/////////////////////////////////////////   
if($objError->getErrors()){
//    $GLOBALS['xoopsTpl']->assign('error', $errors);
    $actionArr = array('list'=>array('list'=>'list'));     
//    $objError = new \XoopsObject();     
    //exit($errors);
    goto list_on_errors;
}     

if($msg)
    redirect_header('tools.php', 3, $msg);    
else
    require __DIR__ . '/footer.php';exit;
//////////////////////////////////////////////////////


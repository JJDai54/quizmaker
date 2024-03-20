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
//-----------------------------------------------

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
if (isset($_POST['submit'])){
    $op = 'minify_ok';
    $select = array_key_first($_POST['submit']);
}else{
    $op = 'list';
    $select = 'none';
} 

//------------------------------------------------------
//$tr = "<pre>" . print_r($_POST, true) . "</pre>";
//echo "<hr>{$op} - {$select}<pre>" . print_r($_POST, true) . "</pre><hr>";
//exit;
//------------------------------------------------------

// Request quiz_id
$catId  = Request::getInt('cat_id', 0);
$quizId = Request::getInt('quiz_id', 0);

$utility = new \XoopsModules\Quizmaker\Utility();  
$templateMain = 'quizmaker_admin_tools.tpl';
$objError = new \XoopsObject();        
//$errors = '';

////////////////////////////////////////////////////////////////////////


//echo "<hr>{$pathSource}<br>nb files = " . count($filesList) . "<hr>";
include_once("../class/Minifier.php");       

function mkdir_quiz_min_folders(){
global $objError;

    $fldArr = array('/js/plugins', '/js/language', '/css') ;
    foreach($fldArr as $key => $fld){
      if(!is_dir(QUIZMAKER_QUIZ_JS_MIN . $fld)) 
          if(!mkdir(QUIZMAKER_QUIZ_JS_MIN . $fld, 0777, true)) $objError->setErrors("Echec de la creation du dossier \{$fld}\"");
    
    }
}
function getFilesList($path, $subFolder='', $strExtentions=''){
global $objError;
    $ret = array();
    $extensions = explode(',', $strExtentions);
    
    if ($subFolder == '')
    {
        $filesList =  XoopsLists::getFileListByExtension($path, $extensions);   
        foreach($filesList AS $k=>$f){
            $ret[$k] = $path . '/' . $filesList[$k];
        }
     }else{
        $path .= '/' . $subFolder;
        $filesList =  XoopsLists::getFileListByExtension($path,  $extensions, '');   
        foreach($filesList AS $k=>$f){
            $f =  $subFolder . '/' . $filesList[$k];
            $ret[$f] =  $path . '/' . $subFolder . '/' . $filesList[$k];
        }
     }   
//echo "<hr>{$path}<pre>" . print_r($ret, true) . "</pre><hr>";exit;
     return $ret;   
}
function minifie_files($filesList, $extra = ''){
global $objError;
    $clMin = new Minifier();
      
    foreach($filesList AS $k=>$f){
        $from = QUIZMAKER_QUIZ_JS_ORG . '/' .$k;
        $to   = QUIZMAKER_QUIZ_JS_MIN . '/' . $k;
        
        $pos = strrpos($k, '.');
        $ext = substr($k, $pos+1);
        
        $content = \file_get_contents($from);
        $newContent = $clMin->minify($content, $ext, $extra);
        //$newContent = $clMin->minifyJS($content);
        
        //echo "Path : {$ext}<br>{$from}<br>{$to}<br><br>";
        if(\file_put_contents($to, $newContent) === false)
            $objError->setErrors("no minification <br>de ===> {$from}<br>vers ===> {$to}");  ;
    }        
}

function restaure_files($filesList){
global $objError;
     
    foreach($filesList AS $k=>$f){
        $from = QUIZMAKER_QUIZ_JS_ORG . '/' . $k;
        $to   = QUIZMAKER_QUIZ_JS_MIN . '/' . $k;
        
        if(!copy($from, $to)){
            $objError->setErrors("no copie <br>de ===> {$from}<br>vers ===> {$to}");  
        }
    }    
}
function do_it($filesList, $action, $extra = ''){
global $objError;   
$msg = '';             
        switch ($action){
            case 'restaure': 
                restaure_files($filesList);
                $msg .= "<br>" . _AM_QUIZMAKER_TOOLS_RESTAURE_OK;
                break;
            case 'minifie': 
                minifie_files($filesList, $extra);
                $msg .= "<br>" . _AM_QUIZMAKER_TOOLS_MINIFIE_OK;  
                break;
            default:
                $objError->setErrors("Action \"{$action}\" do not exixt");
                $msg = '???';             
                break;
        }
        return $msg;        
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

$pathSource = QUIZMAKER_QUIZ_JS_ORG;
$pathDest   = QUIZMAKER_QUIZ_JS_MIN;
mkdir_quiz_min_folders();

switch($op) {
    case "all":
        $msg =  "{$domaine}===>{$action}===>{$caption}"; 
        switch ($action){
            case 'restaure': 
                //restaure_files($filesList);
                $quizUtility::recurseCopy(QUIZMAKER_QUIZ_JS_ORG, QUIZMAKER_QUIZ_JS_MIN);
                $msg .= "<br>" . _AM_QUIZMAKER_TOOLS_RESTAURE_OK;
                break;
            case 'minifie': 
                $filesListRoot = getFilesList(QUIZMAKER_QUIZ_JS_ORG, 'js',  'js,JS');   
                $filesListTpl = getFilesList(QUIZMAKER_QUIZ_JS_ORG,  'js/plugins', 'js,JS');   
                $filesListCss = getFilesList(QUIZMAKER_QUIZ_JS_ORG, 'css', 'css,CSS');   
                $filesList   = array_merge($filesListRoot, $filesListTpl, $filesListCss);  
                $msg .= do_it($filesList, $action, '');

                $filesList = getFilesList($pathSource, 'js/language', 'js,JS');   
                $msg .= do_it($filesList, $action, 'lang');
                break;
        }
        break;
        
    case "js":
        $msg =  "{$domaine}===>{$action}===>{$caption}";
        $filesList = getFilesList(QUIZMAKER_QUIZ_JS_ORG, 'js',  'js,JS');   
        $msg .= do_it($filesList, $action, '');
        
        break;
        
    case "plugins":
        $msg =  "{$domaine}===>{$action}===>{$caption}";
        $filesList = getFilesList(QUIZMAKER_QUIZ_JS_ORG, 'js/plugins', 'js,JS');
        $msg .= do_it($filesList, $action, '');
        break;
        
    case "css":
        $msg =  "{$domaine}===>{$action}===>{$caption}";
        $filesList = getFilesList($pathSource, 'css', 'css,CSS');        
        $msg .= do_it($filesList, $action, '');
        break;
        
    case "html":
        $msg =  "{$domaine}===>{$action}===>{$caption}";
        $filesList = getFilesList($pathSource, 'js/language', 'js,JS');   
        $msg .= do_it($filesList, $action, '');
        break;
        
    case "lang":
        $msg =  "{$domaine}===>{$action}===>{$caption}";
        $filesList = getFilesList($pathSource, 'js/language', 'js,JS');   
        $msg .= do_it($filesList, $action, 'lang');
        break;
    
    case "list":
    default:
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
                    'all'    => _AM_QUIZMAKER_TOOLS_MINIFY_ALL_DESC,
                    'js'     => _AM_QUIZMAKER_TOOLS_MINIFY_JS_DESC,
                    'plugins'=> _AM_QUIZMAKER_TOOLS_MINIFY_PLUGINS_DESC,
                    'css'    => _AM_QUIZMAKER_TOOLS_MINIFY_CSS_DESC,
                    //'html'   => _AM_QUIZMAKER_TOOLS_MINIFY_HTML_DESC,
                    'lang'   => _AM_QUIZMAKER_TOOLS_MINIFY_LANGUAGE_DESC
        );
        /*
        
        $actions=array( 'minify_all'    => array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_ALL,        'description' => _AM_QUIZMAKER_TOOLS_MINIFY_ALL_DESC),
                        'minify_js'     => array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_JS,         'description' => _AM_QUIZMAKER_TOOLS_MINIFY_JS_DESC),
                        'minify_plugins'=> array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_PLUGINS,    'description' => _AM_QUIZMAKER_TOOLS_MINIFY_PLUGINS_DESC),
                        'minify_css'    => array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_CSS,        'description' => _AM_QUIZMAKER_TOOLS_MINIFY_CSS_DESC),
                        'minify_html'   => array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_HTML,       'description' => _AM_QUIZMAKER_TOOLS_MINIFY_HTML_DESC_DESC),
                        'minify_lang'   => array('caption' => _AM_QUIZMAKER_TOOLS_MINIFY_LANGUAGE,   'description' => _AM_QUIZMAKER_TOOLS_MINIFY_LANGUAGE_DESC)
        )
        */
        $xoopsTpl->assign('actions_list', $actions_list);
        break;
        
    
    }   
    
   
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
    require __DIR__ . '/footer.php';

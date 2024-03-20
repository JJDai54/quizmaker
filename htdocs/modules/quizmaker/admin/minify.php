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

define('_AM_QUIZMAKER_MINIFY',"Minification");

//define('_SUBMIT_ALL',"submit_all");
define('_AM_QUIZMAKER_MINIFY_ALL',"Minifier tous les fichiers sauf lang");
define('_AM_QUIZMAKER_MINIFY_JS',"Minifier le JS");
define('_AM_QUIZMAKER_MINIFY_CSS',"Minifier les CSS");
define('_AM_QUIZMAKER_MINIFY_HTML',"Minifier les HTML");
define('_AM_QUIZMAKER_MINIFY_LANGUAGE',"Minifier les fichiers de langues");

define('_AM_QUIZMAKER_MINIFY_RESTAURE_ORG',"Restaure les originaux");

define('_AM_QUIZMAKER_MINIFY_SELECTION',"Minifier la sélection");
define('_AM_QUIZMAKER_MINIFY_RESTAURE_OK',"Restaure OK");

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
$templateMain = 'quizmaker_admin_minify.tpl';


////////////////////////////////////////////////////////////////////////
$pathSource = QUIZMAKER_QUIZ_JS_ORG;
$pathDest   = QUIZMAKER_QUIZ_JS_MIN;


//echo "<hr>{$pathSource}<br>nb files = " . count($filesList) . "<hr>";
        include_once("../class/Minifier.php");       

function getFilesList($path, $subFolder='', $strExtentions=''){
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

//------------------------------------------------------------
$extra = ''; // pour traiter notamment les fichiers de langues
switch($op) {
    case "minify_ok":
        switch ($select){
        case "retaure_org":
            //deleteDirectory($pathDest);
            $quizUtility::recurseCopy($pathSource, $pathDest);
            //exit('retaure_org');
            redirect_header('minify.php',3,_AM_QUIZMAKER_MINIFY_RESTAURE_OK);
            break;
        default:
        case 'selection':
//echo "<hr><pre>" . print_r($_POST, true) . "</pre><hr>";exit;
        $filesList = Request::getArray('files');    
        break;
        
        case 'all':
          $filesListRoot = getFilesList($pathSource,'js',  'js,JS');   
          $filesListTpl = getFilesList($pathSource, 'js/plugins', 'js,JS');   
          //$filesListLang = getFilesList($pathSource, 'js/language', 'js,JS');   
          $filesListCss = getFilesList($pathSource, 'css', 'css,CSS');   
          $filesList   = array_merge($filesListRoot, $filesListTpl, $filesListCss);    

        break;
        case 'js':
          $filesListRoot = getFilesList($pathSource,'js',  'js,JS');   
          $filesListTpl = getFilesList($pathSource, 'js/plugins', 'js,JS');
          $filesList   = array_merge($filesListRoot,  $filesListTpl);    
        break;
        
        case 'language':
          $filesList = getFilesList($pathSource, 'js/language', 'js,JS');   
          $extra = 'lang';
        break;
        
        case 'css':
          $filesList = getFilesList($pathSource, 'css', 'css,CSS');   
        break;
        case 'html':
            // pas trité pour l'instant
        break;
        }
        //--------------------------------------
        $clMin = new Minifier();
          
        foreach($filesList AS $k=>$f){
            $fullName = $pathSource . '/' .$k;
            $pos = strrpos($k, '.');
            $ext = substr($k, $pos+1);
            
            $content = \file_get_contents($fullName);
            $newContent = $clMin->minify($content, $ext, $extra);
            //$newContent = $clMin->minifyJS($content);
            
            $newPath = $pathDest . '/' . $k;
            //echo "Path : {$ext}<br>{$fullName}<br>{$newPath}<br><br>";
            \file_put_contents($newPath, $newContent);
        }        
        
        //pas de break on continue sur 'list'
        
    case 'list':
	default:
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
		$title = _AM_QUIZMAKER_MINIFY;        
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form_minify', 'minify.php', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		$form->addElement(new \XoopsFormHidden('op', 'minify_ok'));
        //-----------------------------------------------
        $buttonsTray = new XoopsFormElementTray('', " ");
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[retaure_org]', _AM_QUIZMAKER_MINIFY_RESTAURE_ORG, 'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[all]', _AM_QUIZMAKER_MINIFY_ALL, 'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[selection]', _AM_QUIZMAKER_MINIFY_SELECTION, 'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[js]', _AM_QUIZMAKER_MINIFY_JS,    'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[css]', _AM_QUIZMAKER_MINIFY_CSS, 'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[html]', _AM_QUIZMAKER_MINIFY_HTML, 'submit'));
		$buttonsTray->addElement(new \XoopsFormButton('', 'submit[language]', _AM_QUIZMAKER_MINIFY_LANGUAGE,    'submit'));
		$form->addElement($buttonsTray);
        //-----------------------------------------------
        
        $filesListRoot = getFilesList($pathSource, 'js', 'js,JS');   
        $filesListLang = getFilesList($pathSource, 'js/language', 'js,JS');   
        $filesListTpl = getFilesList($pathSource, 'js/plugins', 'js,JS');   
        $filesListCss = getFilesList($pathSource, 'css', 'css,CSS');   
        
         $filesList   = array_merge($filesListRoot, $filesListLang, $filesListTpl,$filesListCss);    
        foreach($filesList AS $k=>$f){
            
            $chqFile = new XoopsFormCheckbox('', "files[{$k}]",1);
            $chqFile->addOption(0, 'ok');
            $libFile = new XoopsFormLabel('',$k);
            
            $fileTray = new XoopsFormElementTray('', " ");
  	        $fileTray->addElement($chqFile);
  	        $fileTray->addElement($libFile);
            
  	        $form->addElement($fileTray);
        }
        
        
        
		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
/////////////////////////////////////////        


    
    break;
    

}
require __DIR__ . '/footer.php';

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




    switch($op){
    case 'getform':
          if(!isset($errors)) {
            if($objError->getErrors())
                $errors = $objError->getHtmlErrors();
            else
                $errors = '';
          }
          
        $GLOBALS['xoopsTpl']->assign('error', $errors);
        $objError = new \XoopsObject();     
        //----------------------------------------------------
          $GLOBALS['xoopsTpl']->assign('buttons', '');        
      
                      
  //  exit(QUIZMAKER_PATH_UPLOAD_IMPORT);       
          $utility = new FQuizmaker\Utility();
          //$utility::rrmdir($pathImport . '/images');
   
  		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
  		// Permissions for uploader
  		$grouppermHandler = xoops_getHandler('groupperm');
  		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
  		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
  		
          
          // Title
  		$title = _AM_QUIZMAKER_TYPE_IMPORT_PLUGIN;        
  		// Get Theme Form
		$form = new \XoopsThemeForm($title, $formName, $importMainFile, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'import'));
        $form->addElement(new \XoopsFormHidden('sender', ''));
        addXformImportType($form, $typeImportName, $typeImport);

        $uploadTray = new \XoopsFormFile(_AM_QUIZMAKER_PLUGIN_TO_INSTALL, 'quizmaker_files', $upload_size);     
        $uploadTray->setDescription(_AM_QUIZMAKER_PLUGIN_TO_INSTALL_DESC . '<br>' . sprintf(_AM_QUIZMAKER_FILE_UPLOADSIZE . " ($upload_size)", intval($upload_size / 1024)), '<br>');
        $form->addElement($uploadTray, true);


          //----------------------------------------------- 
  		$form->addElement(new \XoopsFormButton('', _SUBMIT, _AM_QUIZMAKER_IMPORTER, 'submit'));
  		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        
        break;
        
    case 'import':
		if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            //le plugin existe déjà la confirmation de suppressioins de l'ancien plugin est ok
            //echoArray('gp');
            $pluginName = Request::getString('pluginName','');
            $fullPath = Request::getString('fullPath','');
            $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . '/' . "new_plugin";
            
            $ret = $pluginsHandler->install($pluginName, $pathImport);
            $msg = constant('_AM_QUIZMAKER_IMPORT_PLUGIN_ERR_' . $ret);
            redirect_header("import.php?{$typeImportName}=plugin&op=getform", 8, sprintf($msg, $ret, $pluginName));
            //    exit("===>confirmed import du plugin : {$newPlugin}");
                
		} else {
            loadFileTo("new_plugin", $pathImport, $savedFilename); 
            //echoArray('gpf','',true);
            $allFld =  \XoopsLists::getDirListAsArray($pathImport);
            $pluginName = array_shift($allFld);    
            if($pluginsHandler->exists($pluginName)){
                $msg = sprintf(_AM_QUIZMAKER_IMPORT_PLUGIN_CONFIRM_NEW, $pluginName);
                xoops_confirm(['ok' => 1, 'plugin' => $plugin, 'op' => 'import', $typeImportName => 'plugin' , 'pluginName' => $pluginName,'fullPath'=>$fullPath], $_SERVER['REQUEST_URI'], $msg);
                //exit("===>confirm import du plugin : {$pluginName$pluginName}");
            }else{
                //le plugin ,n'existe pas il est installé directement
                $fullPath = Request::getString('fullPath','');
                $pathImport = QUIZMAKER_PATH_UPLOAD_IMPORT . '/' . "new_plugin";

                $ret = $pluginsHandler->install($pluginName, $pathImport);
            $msg = constant('_AM_QUIZMAKER_IMPORT_PLUGIN_ERR_' . $ret);
            redirect_header("import.php?{$typeImportName}=plugin&op=getform", 8, sprintf($msg, $ret, $pluginName));
            }
		}
                
        break;
    default : break;
    }



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


require_once __DIR__ . '/header.php';

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JANUS;

//\JANUS\loadAllXForms();

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
$addNewImg = Request::getCmd('addNewImg', '');
if($addNewImg) $op = 'add_new_img';

$folder = Request::getCmd('folder', 'coches');
//-----------------------------------------------------------
//recherche des categories autorisées
//$clPerms->addPermissions($criteriaCatAllowed, 'global_ac', QUIZMAKER_PERMIT_CATMAN);
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_CATMAN,'QUIZMAKER_PERMIT_CATMAN', "index.php", QUIZMAKER_ADMIN_PERM);


//echoArray($catArr,'',true);
//echoarray($catArr);
//recheche du quiz pour les opération individuelle : edit, save, delete, ...
//-----------------------------------------------------------
// echoArray("gp");
// echo "sujet = {$quizSubject}<hr>";
$utility = new \XoopsModules\Quizmaker\Utility();  

function checkRightRessources($permName, $catId){
global $clPerms;
  if (!$clPerms->getPermissions($permName, $catId))  
      redirect_header("quiz.php?op=list&cat_id={$catId}", 5, _CO_QUIZMAKER_NO_PERM);
}
$templateMain = 'quizmaker_admin_ressources.tpl';
//--------------------------------------------------------

$imgPath = QUIZMAKER_PATH_QUIZ_ORG . '/images/' . $folder;


switch($op) {
	default:
	case 'list':
        
            // ----- Listes de selection pour filtrage des type de questions par categorie-----  
        //if ($catId == 0) $catId = $quiz->getVar('quiz_cat_id');
        //$cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpFolder = new \XoopsFormSelect(_AM_QUIZMAKER_FOLDER, 'folder', $folder);
        $inpFolder->addOption('coches');
        $inpFolder->addOption('buttons');
        $inpFolder->addOption('emoji');
        $inpFolder->addOption('substitut');
        //$inpFolder->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
        $inpFolder->setExtra('onchange="document.quizmaker_select_filter.submit();"' );
        $inpFolder->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
  	    $GLOBALS['xoopsTpl']->assign('inpFolder', $inpFolder->render());
        


        //$imgCat = QUIZMAKER_URL_UPLOAD . '/categories/' . $this->getVar('cat_image');
        $inpNewImg = new \XoopsFormFile(_AM_QUIZMAKER_IMAGE , 'newImage', $quizmakerHelper->getConfig('maxsize_image'));
  	    $GLOBALS['xoopsTpl']->assign('inpNewImg', $inpNewImg->render());

        $inpAddNewImg =  new \XoopsFormButton('', 'addNewImg', _AM_QUIZMAKER_ADD_NEW_IMAGE, 'submit');
  	    $GLOBALS['xoopsTpl']->assign('inpAddNewImg', $inpAddNewImg->render());

//$url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $imgPath);
        $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
        //echoArray($imgList);
        $imgArr = Array();
 
     foreach($imgList as $k => $img){
         $arr = array();
         $arr['url'] = QUIZMAKER_URL_QUIZ_ORG . "/images/{$folder}/{$img}";
         $arr['name'] = $img;
         
         $imgArr[] = $arr;
         
     }
        //echoArray($imgArr);
		$GLOBALS['xoopsTpl']->assign('imgArr', $imgArr);
		$GLOBALS['xoopsTpl']->assign('imgCount', count($imgArr));        
		$GLOBALS['xoopsTpl']->assign('nbColumns', 8);        
        
        
	   break;
    

    case 'add_new_img':
        $upladArr = Request::getArray('xoops_upload_file');
        $key = $upladArr[0];
        if($_FILES[$key]['name']){
            $optionsArr = array('mimetypes_image'=>$quizmakerHelper->getConfig('mimetypes_image'),
                                'maxsize_image'=>$quizmakerHelper->getConfig('maxsize_image'),
                                'prefix'=>''); 
            $clSaveImage = new \XoopsFormSaveImage();
            $imgName = $clSaveImage->save('newImage', $imgPath, $optionsArr, $nameOrg);

            //echoGPF("GPF","op={$op} - folder={$folder}");
            //exit("add new img : key={$key} <br> nameOrg={$nameOrg} <brn imgPath = {$imgPath}");
            $msg = sprintf(_AM_QUIZMAKER_UPLOAD_FILE_OK, $folder);
            
        }else{
            $msg = _AM_QUIZMAKER_SELECT_FILE;
        }
            $url = "ressources.php?op=list&folder={$folder}";
            redirect_header($url, 5, $msg);

        break;

    
}
require __DIR__ . '/footer.php';

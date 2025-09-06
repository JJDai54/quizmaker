<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Creaaquiz module for xoops
 *
 * @copyright      2021 XOOPS Project (https://xoops.org)
 * @license        GPL 2.0 or later
 * @package        Quizmaker
 * @since          1.0
 * @min_xoops      2.5.10
 * @author         XOOPS Development Team - Email:<jjdelalandre@orange.fr> - Website:<jubile.fr>
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Helper;
use XoopsModules\Quizmaker\Constants;

require_once \XOOPS_ROOT_PATH . '/modules/quizmaker/include/common.php';
include_once (XOOPS_ROOT_PATH . "/Frameworks/janus/load.php");

/**
 * Function show block
 * @param  $options
 * @return array
 */
function b_quizmaker_categories_show($options)
{
//echo "<hr>===>options : <pre>". print_r($options, true) ."</pre><hr>";
	$myts = MyTextSanitizer::getInstance();
    $dirname = "quizmaker";
    $GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', \QUIZMAKER_URL_UPLOAD);
    $GLOBALS['xoopsTpl']->assign('quizmaker_url', \QUIZMAKER_URL_MODULE);
    $block       = [];
    $h=0;
	$limit       = $options[$h++]; //inutilisé
	$lenghtTitle = $options[$h++]; //inutilisé
    
	$catsIds = $options[$h++];
    $tCats = explode(',', $catsIds);
    if($tCats[0] == 0){
        $nbCats = 0;
    }else{
        $nbCats = count($tCats);
    }

    $block['options']['title'] = $options[$h++];
    $block['options']['logo'] = $options[$h++];
    $block['options']['width'] = $options[$h++];
    $block['options']['theme'] = $options[$h++];            
    
    unset($options);
    
//------------------------------------------------------------------
	$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
    $categoriesHandler = $quizmakerHelper->getHandler('Categories');

    if(in_array(0, $tCats) || count($tCats) == 0){
      $cats = $categoriesHandler->getAllowedArr('view', null, 0, 0, $sort='cat_weight,cat_name,cat_id', $order="ASC");   
    }else{
      $crCat = new \CriteriaCompo();
      $crCat->add(new Criteria('cat_id', "({$catsIds})", 'IN'));
      $cats = $categoriesHandler->getAllowedArr('view', $crCat, 0, 0, $sort='cat_weight,cat_name,cat_id', $order="ASC");   
    }
    
	if (count($cats) > 0) {
		foreach(array_keys($cats) as $i) {
            $catId = $cats[$i]['cat_id'];
			$block['data'][$catId]['id'] = $catId;            
			$block['data'][$catId]['name'] = $cats[$catId]['cat_name'];            
			$block['data'][$catId]['theme'] = $cats[$catId]['cat_theme'];            
		}
	}

//echo "<hr>===>block : <pre>". print_r($block, true) ."</pre><hr>";
//    echoArray($block);


\JANUS\load_css('', false);	
    return $block;

}

/**
 * Function edit block
 * @param  $options
 * @return string
 */
function b_quizmaker_categories_edit($options)
{
include_once (XOOPS_ROOT_PATH . "/Frameworks/janus/load.php");
\JANUS\loadAllXForms();   

    //$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();    
	$quizmakerHelper = Helper::getInstance();
    $form = new \XoopsThemeForm("quizmaker_block", 'form', $action, 'post', true);
	$form->setExtra('enctype="multipart/form-data"');
            
    //--------------------------------------------
    $index=0 ; 
    $inpNbItems = new \XoopsFormNumber(_MB_QUIZMAKER_NB_QUIZ_2_list, "options[{$index}]", 5, 5, $options[$index]);
    $inpNbItems->setMinMax(3, 25);
    $form->addElement($inpNbItems);
    //--------------------------------------------
    $index++;    
    $inpLgItems = new \XoopsFormNumber(_MB_QUIZMAKER_NAME_LENGTH, "options[{$index}]", 5, 5, $options[$index]);
    $inpLgItems->setMinMax(25, 120);
    $form->addElement($inpLgItems);

    $index++;   
    $tCat = explode(',', $options[$index]); 
    $categoriesHandler = $quizmakerHelper->getHandler('Categories');
    $catAll = $categoriesHandler->getAllCategories(null);

    $inpCat = new \XoopsFormSelect(_MB_QUIZMAKER_CATEGORIES, "options[{$index}]", $tCat, $size = 5, true);
    $inpCat->addOption(0, _MB_QUIZMAKER_ALL_CAT);
	foreach(array_keys($catAll) as $i) {
        $inpCat->addOption($catAll[$i]->getVar('cat_id'), $catAll[$i]->getVar('cat_name'));
	}
    $form->addElement($inpCat);
    
    $index++;    
    $inpCaption = new \XoopsFormText(_MB_QUIZMAKER_BLOCK_TITLE ,  "options[{$index}]", 120, 120, $options[$index]);
    $form->addElement($inpCaption);

    $index++;   
      $path =  XOOPS_UPLOAD_PATH . "/images";
      $inputLogo = new \XoopsFormIconSelect("qqqqqqqqqq", "options[{$index}]", $options[$index] , $path);
      $inputLogo->setExtension(true);
      $inputLogo->setGridIconNumber(5,5);
      $inputLogo->setIconSize (80, 80);
    $form->addElement($inputLogo);     
    

    $index++;    
    if (!isset($options[$index])) $options[$index] = 250;
    $inpImgWidth = new \XoopsFormNumber(_MB_QUIZMAKER_IMG_WIDTH, "options[{$index}]", 5, 5, $options[$index]);
    $inpImgWidth->setMinMax(25, 350, 'px');
    $form->addElement($inpImgWidth);


    // Form Selection du theme globale du bloc
    $index++;    
    if (!isset($options[$index])) $options[$index] = 'blue';
    $inpTheme = new \XoopsFormSelect(_MB_QUIZMAKER_THEME, "options[{$index}]", $options[$index]);
    $inpTheme->addOptionArray( \JANUS\get_css_color());
    $form->addElement($inpTheme );


    return $form->render();

}



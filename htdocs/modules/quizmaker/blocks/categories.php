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
include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");

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
	$limit       = $options[$h++];
	$lenghtTitle = $options[$h++];
	$catsIds = $options[$h++];
    $tCats = explode(',', $catsIds);
    if($tCats[0] == 0){
        $nbCats = 0;
    }else{
        $nbCats = count($tCats);
    }
	$caption = $options[$h++];
	//$desc = $options[5];
    
	array_shift($options);
	array_shift($options);
	array_shift($options);
	array_shift($options);
	//array_shift($options);
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
    
    $block['options']['title'] = $caption;            
    //$block['options']['desc'] = str_replace("\n", "<br>",$desc);            
    $block['options']['theme'] = 'blue';            

    
	if (count($cats) > 0) {
		foreach(array_keys($cats) as $i) {
            $catId = $cats[$i]['cat_id'];
			$block['data'][$catId]['id'] = $catId;            
			$block['data'][$catId]['name'] = $cats[$catId]['cat_name'];            
			$block['data'][$catId]['theme'] = $cats[$catId]['cat_theme'];            
//             $catId = $cats[$i]->getVar('cat_id');
// 			$block['data'][$catId]['cat']['id'] = $catId;            
// 			$block['data'][$catId]['cat']['name'] = $cats[$catId]['cat_name'];            
// 			$block['data'][$catId]['cat']['theme'] = $cats[$catId]['cat_colors_set'];            
		}
	}

//echo "<hr>===>block : <pre>". print_r($block, true) ."</pre><hr>";

\JJD\load_css('', false);	
    return $block;

}

/**
 * Function edit block
 * @param  $options
 * @return string
 */
function b_quizmaker_categories_edit($options)
{
    $quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();    
    $form = new \XoopsThemeForm("quizmaker_block", 'form', $action, 'post', true);
	$form->setExtra('enctype="multipart/form-data"');


            
    //--------------------------------------------
    $index=0 ; 
    $inpNbItems = new \XoopsFormNumber(_CO_JJD_NB_QUIZ_2_list, "options[{$index}]", 5, 5, $options[$index]);
    $inpNbItems->setMinMax(3, 25);
    $form->addElement($inpNbItems);
    //--------------------------------------------
    $index++;    
    $inpLgItems = new \XoopsFormNumber(_CO_JJD_NAME_LENGTH, "options[{$index}]", 5, 5, $options[$index]);
    $inpLgItems->setMinMax(25, 120);
    $form->addElement($inpLgItems);

    $index++;   
    $tCat = explode(',', $options[$index]); 
    $categoriesHandler = $quizmakerHelper->getHandler('Categories');
    $catAll = $categoriesHandler->getAllCategories();

    $inpCat = new \XoopsFormSelect(_CO_JJD_CATEGORIES, "options[{$index}]", $tCat, $size = 5, true);
    $inpCat->addOption(0, _CO_JJD_ALL_CAT);
	foreach(array_keys($catAll) as $i) {
        $inpCat->addOption($catAll[$i]->getVar('cat_id'), $catAll[$i]->getVar('cat_name'));
	}
    $form->addElement($inpCat);
    
    $index++;    
    $inpCaption = new \XoopsFormText(_CO_JJD_BLOCK_TITLE ,  "options[{$index}]", 120, 120, $options[$index]);
    $form->addElement($inpCaption);

    return $form->render();

}



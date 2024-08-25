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

require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_CATMAN,'QUIZMAKER_PERMIT_CATMAN', "index.php");

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request opt_id
$optId = Request::getInt('opt_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_options.tpl';
        
       $adminObject->displayNavigation('options.php');

        
		$GLOBALS['xoopsTpl']->assign('form', '');
		$GLOBALS['xoopsTpl']->assign('error', '');
        
         
		$optionsCount = $optionsHandler->getCountOptions();
		$optionsAll = $optionsHandler->getAllOptions(null, $start, $limit, 'opt_id');
		$GLOBALS['xoopsTpl']->assign('options_count', $optionsCount);   
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		// Table view options
		if ($optionsCount > 0) {
			foreach(array_keys($optionsAll) as $i) {
				$Options = $optionsAll[$i]->getValuesOptions();
                //echoArray($Options);
				$GLOBALS['xoopsTpl']->append('options_list', $Options);
				unset($Options);
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_CATEGORIES);
		}
	break;
	case 'new':
// 		$templateMain = 'quizmaker_admin_options.tpl';
// 		$GLOBALS['xoopsTpl']->assign('options_list', '');
// 		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('options.php'));
// 		$adminObject->addItemButton(_AM_QUIZMAKER_CATEGORIES_LIST, 'options.php', 'list');
// 		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
// 		// Form Create
// 		$optionsObj = $optionsHandler->create();
// 		$form = $optionsObj->getFormOptions();
// 		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'edit':
		$GLOBALS['xoopsTpl']->assign('options_list', '');
		$templateMain = 'quizmaker_admin_options.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('options.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_CATEGORIES, 'options.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_CATEGORIES_LIST, 'options.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$optionsObj = $optionsHandler->get($optId);
		$form = $optionsObj->getFormOptions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('options.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($optId > 0) {
			$optionsObj = $optionsHandler->get($optId);
		} else {
			$optionsObj = $optionsHandler->create();
    		$optionsObj->setVar('opt_creation', \JANUS\getSqlDate());
		}
		// Set Vars
		$optionsObj->setVar('opt_name', Request::getString('opt_name', ''));
		$optionsObj->setVar('opt_icone', Request::getText('opt_icone', ''));
		$optionsObj->setVar('opt_optionsIhm', Request::getInt('opt_optionsIhm', 0));
		$optionsObj->setVar('opt_optionsDev', Request::getInt('opt_optionsDev', 0));

		// Insert Data
		if ($optionsHandler->insert($optionsObj)) {
            // ----- sauvegarde des permissions
			$newOptId = $optionsObj->getNewInsertedIdOptions();

			redirect_header('options.php?op=list', 2, _AM_QUIZMAKER_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $optionsObj->getHtmlErrors());
		$form = $optionsObj->getFormOptions();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
// 		$optionsObj = $optionsHandler->get($optId);
// 		$catName = $optionsObj->getVar('opt_name');
// 		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
// 			if (!$GLOBALS['xoopsSecurity']->check()) {
//                 $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $optId, $optionsObj->getVar('opt_name'));
//     			redirect_header('options.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()),$msg);
// 			}
// 			if ($optionsHandler->delete($optionsObj)) {
// 				redirect_header('options.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
// 			} else {
// 				$GLOBALS['xoopsTpl']->assign('error', $optionsObj->getHtmlErrors());
// 			}
// 		} else {
//             $msg = sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $optionsObj->getVar('opt_id'), $optionsObj->getVar('opt_name'));
// 			xoops_confirm(['ok' => 1, 'opt_id' => $optId, 'op' => 'delete'], $_SERVER['REQUEST_URI'],$msg);
// 		}
	break;

	case 'set_bit':
        //checkRightEditQuiz('edit_quiz',$optId);
        $field = Request::getString('field');
        $bitIndex = Request::getInt('bitIndex');
        $newValue = Request::getInt('newValue', -1);
        $optionsHandler->setBitOn($optId, $field, $bitIndex, $newValue);
        redirect_header("options.php?op=list", 5, "Etat de {$field} Changé");

    
}
require __DIR__ . '/footer.php';

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

require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_PERMISSIONS,'QUIZMAKER_PERMIT_PERMISSIONS', "index.php",true);

// Template Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('permissions.php'));
//echoArray("gp");

$op = Request::getCmd('op', 'global_ac');

// Get Form
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
xoops_load('XoopsFormLoader');

$permArr = ['global_ac'         => _AM_QUIZMAKER_PERMISSIONS_GLOBAL_AC,
            'view_cats'         =>_AM_QUIZMAKER_PERMISSIONS_VIEW_CATS,
            'create_quiz'       =>_AM_QUIZMAKER_PERMISSIONS_CREATE_QUIZ,
            'edit_quiz'         =>_AM_QUIZMAKER_PERMISSIONS_EDIT_QUIZ,
            'delete_quiz'       =>_AM_QUIZMAKER_PERMISSIONS_DELETE_QUIZ,
            'import_quiz'       =>_AM_QUIZMAKER_PERMISSIONS_IMPORT_QUIZ,
            'importquest_quiz'  =>_AM_QUIZMAKER_PERMISSIONS_IMPORTQUEST_QUIZ,
            'export_quiz'       =>_AM_QUIZMAKER_PERMISSIONS_EXPORT_QUIZ];
            
$permTableForm = new \XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
$formSelect = new \XoopsFormSelect('', 'op', $op);
$formSelect->setExtra('onchange="document.fselperm.submit()"');
$formSelect->addOptionArray($permArr);
$permTableForm->addElement($formSelect);
$permTableForm->display();


$domaines = explode('_', $op . '__');
/*
echo '===>' . $op . '--->' . '_AM_QUIZMAKER_PERMISSIONS_' . strtoupper($domaines[0]). '<br>';
*/
	$formTitle = constant('_AM_QUIZMAKER_PERMISSIONS_' . strtoupper($domaines[0] . '_' . strtoupper($domaines[1]))) ;
	$permName = $op; 
    //$cst = strtoupper('_AM_QUIZMAKER_PERMISSIONS_' . strtoupper($domaines[0]));   // ."_DESC"
	$permDesc = $formTitle;
	$permFound = true;
    
//exit;
switch($domaines[1]) {
	case 'ac':
		$permArr = array(QUIZMAKER_PERMIT_CATMAN    => _AM_QUIZMAKER_PERMIT_CATMAN, 
                         QUIZMAKER_PERMIT_IMPORTG   => _AM_QUIZMAKER_PERMIT_IMPORTG, 
                         QUIZMAKER_PERMIT_IMPORTA   => _AM_QUIZMAKER_PERMIT_IMPORTA,
                         QUIZMAKER_PERMIT_EXPORT    => _AM_QUIZMAKER_PERMIT_EXPORT,
                         QUIZMAKER_PERMIT_RESULT    => _AM_QUIZMAKER_PERMIT_RESULT,
                         QUIZMAKER_PERMIT_MINIFY    => _AM_QUIZMAKER_PERMIT_MINIFY,
                         QUIZMAKER_PERMIT_MESSAGEJS => _AM_QUIZMAKER_PERMIT_MESSAGEJS,
                         QUIZMAKER_PERMIT_CLONE     => _AM_QUIZMAKER_PERMIT_CLONE, 
                         QUIZMAKER_PERMIT_PERMISSIONS => _AM_QUIZMAKER_PERMIT_PERMISSIONS); 
                         
        break;
	case 'cats':
	case 'quiz':
        $permArr = $categoriesHandler->getList();
        break;
	default:
	$permFound = false;
}

//echoArray($permArr, "op= {$op} - domaine={$domaine}");
//echo "formTitle : {$formTitle}<br>permName : {$permName}<br>permDesc : {$permDesc}<hr>";
    $permform = $clPerms->getPermissionsForm($formTitle, $permName, _AM_QUIZMAKER_PERMISSIONS_DESC, $permArr);
    //$permform->addElement(new XoopsFormHidden('op','edit_quiz'));
    echo $permform->render();
    //$GLOBALS['xoopsTpl']->assign('form', $permform->render());


unset($permform);
if (true !== $permFound) {
	redirect_header('permissions.php', 3, _AM_QUIZMAKER_NO_PERMISSIONS_SET);
	exit();
}
require __DIR__ . '/footer.php';

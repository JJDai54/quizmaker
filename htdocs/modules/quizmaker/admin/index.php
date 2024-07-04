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


use XoopsModules\Quizmaker\Common;

include_once dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'quizmaker_admin_index.tpl';

// Count elements
$countCategories = $categoriesHandler->getCount();
$countQuiz = $quizHandler->getCount();
$countQuestions = $questionsHandler->getCount();
$countAnswers = $answersHandler->getCount();
$countType_question = $type_questionHandler->getCount();
$countMessages = $messagesHandler->getCount();
$countResults = $resultsHandler->getCount();

// InfoBox Statistics
$adminObject->addInfoBox(_AM_QUIZMAKER_STATISTICS);
// Info elements
$redirectFile = $_SERVER['SCRIPT_NAME'];
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_CATEGORIES.'</label>', $countCategories));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_QUIZ.'</label>', $countQuiz));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_QUESTIONS.'</label>', $countQuestions));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_ANSWERS.'</label>', $countAnswers));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_TYPE_QUESTION.'</label>', $countType_question));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_MESSAGES.'</label>', $countMessages));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_QUIZMAKER_THEREARE_RESULTS.'</label>', $countResults));


//----------------------------------------------------------
// InfoBox traitements
//----------------------------------------------------------
$nbTraitements = 0;
$adminObject->addInfoBox(_AM_QUIZMAKER_TRAITEMENTS);
// Info elements
$nbFiles = count(XoopsLists::getFileListByExtension(QUIZMAKER_PATH_UPLOAD_EXPORT, array('zip')));
//$nbFiles = count(XoopsLists::getFileListAsArray(QUIZMAKER_PATH_UPLOAD_EXPORT));        
if($nbFiles > 0){
    $nbTraitements++;
    $fld=substr(QUIZMAKER_FLD_EXPORT,1);
    $adminObject->addInfoBoxLine("<a href='traitements.php?op=truncat&folder={$fld}'>".sprintf(_AM_QUIZMAKER_TRUNCAT_FOLDER, QUIZMAKER_FLD_EXPORT,$nbFiles)."</a>");
}

$nbFiles = count(XoopsLists::getFileListByExtension(QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH, array('zip')));
//$nbFiles = count(XoopsLists::getFileListAsArray(QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH));        
if($nbFiles > 0){
    $nbTraitements++;
    $fld=substr(QUIZMAKER_FLD_IMPORT_BATCH,1);
    $adminObject->addInfoBoxLine("<a href='traitements.php?op=truncat&folder={$fld}'>".sprintf(_AM_QUIZMAKER_TRUNCAT_FOLDER, QUIZMAKER_FLD_IMPORT_BATCH,$nbFiles)."</a>");
}

$nbFiles = count(XoopsLists::getFileListByExtension(QUIZMAKER_PATH_UPLOAD_IMPORT, array('zip')));
//$nbFiles = count(XoopsLists::getFileListAsArray(QUIZMAKER_PATH_UPLOAD_IMPORT));        
if($nbFiles > 0){
    $nbTraitements++;
    $fld=substr(QUIZMAKER_FLD_IMPORT,1);
    $adminObject->addInfoBoxLine("<a href='traitements.php?op=truncat&folder={$fld}'>".sprintf(_AM_QUIZMAKER_TRUNCAT_FOLDER, QUIZMAKER_FLD_IMPORT,$nbFiles)."</a>");
}
if($nbTraitements == 0){
    $adminObject->addInfoBoxLine(_AM_QUIZMAKER_NO_TRAITEMENT_TODO);
}

//-------------------------------------------------------------
// Upload Folders
$configurator = new Common\Configurator();
if ($configurator->uploadFolders && is_array($configurator->uploadFolders)) {
	foreach(array_keys($configurator->uploadFolders) as $i) {
		$folder[] = $configurator->uploadFolders[$i];
	}
}
// Uploads Folders Created
foreach(array_keys($folder) as $i) {
	$adminObject->addConfigBoxLine($folder[$i], 'folder');
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($folder[$i], 0777, $redirectFile), '', 	);
}

// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
// Test Data
if ($quizmakerHelper->getConfig('displaySampleButton')) {
	xoops_loadLanguage('admin/modulesadmin', 'system');
	include_once dirname(__DIR__) . '/testdata/index.php';
	$adminObject->addItemButton(constant('_CO_QUIZMAKER_ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
	$adminObject->addItemButton(constant('_CO_QUIZMAKER_SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
//	$adminObject->addItemButton(constant('_CO_QUIZMAKER_EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
	$adminObject->displayButton('left');
}
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());
// End Test Data
require __DIR__ . '/footer.php';

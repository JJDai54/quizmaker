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

// 
$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
// ------------------- Informations ------------------- //


$modversion = [
	'name'                => _MI_QUIZMAKER_NAME,
	'version'             => 4.10,
	'module_status'       => 'Beta 2',
	'release_date'        => '2024/03/21',
	'description'         => _MI_QUIZMAKER_DESC,
	'author'              => 'Jean-Jacques Delalandre',
	'author_mail'         => 'jjdelalandre@orange.fr',
	'author_website_url'  => 'http://xmodules.oritheque.fr',
	'author_website_name' => 'Origami du Monde',
	'credits'             => 'XOOPS Development Team',
	'license'             => 'GPL 2.0 or later',
	'license_url'         => 'http://www.gnu.org/licenses/gpl-3.0.en.html',
	'help'                => 'page=help',
	'release_info'        => 'release_info',
	'release_file'        => XOOPS_URL . '/modules/quizmaker/docs/release_info file',
	'manual'              => 'link to manual file',
	'manual_file'         => XOOPS_URL . '/modules/quizmaker/docs/install.txt',
	'min_php'             => '5.5',
	'min_xoops'           => '2.5.9',
	'min_admin'           => '1.2',
	'min_db'              => array('mysql' => '5.5', 'mysqli' => '5.5'),
	'image'               => 'assets/images/logoModule.png',
	'dirname'             => basename(__DIR__),
	'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
	'sysicons16'          => '../../Frameworks/moduleclasses/icons/16',
	'sysicons32'          => '../../Frameworks/moduleclasses/icons/32',
	'modicons16'          => 'assets/icons/16',
	'modicons32'          => 'assets/icons/32',
	'demo_site_url'       => 'http://xmodules.jubile.fr',
	'demo_site_name'      => 'Xmodule',
	'support_url'         => 'https://www.frxoops.org/modules/newbb/viewforum.php?forum=12',
	'support_name'        => 'Xoops Support Forum',
	'module_website_url'  => 'www.xoops.org',
	'module_website_name' => 'XOOPS Project',
	'release'             => '21-08-2020',
	'system_menu'         => 1,
	'hasAdmin'            => 1,
	'hasMain'             => 1,
	'adminindex'          => 'admin/index.php',
	'adminmenu'           => 'admin/menu.php',
	'onInstall'           => 'include/install.php',
	'onUninstall'         => 'include/uninstall.php',
	'onUpdate'            => 'include/update.php',
];
// ------------------- Templates ------------------- //
$modversion['templates'] = [
	// Admin templates
	['file' => 'quizmaker_admin_about.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_header.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_index.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_quiz.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_questions.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_categories.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_type_question.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_answers.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_results.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_messages.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_permissions.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_quiz_include.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_quiz_inline.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_quiz_outline.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_export.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_import.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'quizmaker_admin_type_new_question.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'quizmaker_admin_clone.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'quizmaker_admin_download.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'quizmaker_admin_minify.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'quizmaker_admin_tools.tpl', 'description' => '', 'type' => 'admin'],

	// User templates
	['file' => 'quizmaker_header.tpl', 'description' => ''],
	['file' => 'quizmaker_index.tpl', 'description' => ''],
	['file' => 'quizmaker_quiz.tpl', 'description' => ''],
	['file' => 'quizmaker_quiz_list.tpl', 'description' => ''],
	['file' => 'quizmaker_quiz_item.tpl', 'description' => ''],
	['file' => 'quizmaker_quiz_display.tpl', 'description' => ''],
	['file' => 'quizmaker_quiz_solutions.tpl', 'description' => ''],
	['file' => 'quizmaker_categories.tpl', 'description' => ''],
	['file' => 'quizmaker_categories_list.tpl', 'description' => ''],
	['file' => 'quizmaker_categories_item.tpl', 'description' => ''],

	['file' => 'quizmaker_results.tpl', 'description' => ''],
	['file' => 'quizmaker_results_list.tpl', 'description' => ''],
	['file' => 'quizmaker_results_item.tpl', 'description' => ''],

	['file' => 'quizmaker_breadcrumbs.tpl', 'description' => ''],
	['file' => 'quizmaker_search.tpl', 'description' => ''],
	['file' => 'quizmaker_footer.tpl', 'description' => ''],
	['file' => 'quizmaker_categories_theme.tpl', 'description' => ''],
];
// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables
$modversion['tables'] = [
	'quizmaker_quiz',
	'quizmaker_questions',
	'quizmaker_categories',
	'quizmaker_type_question',
	'quizmaker_answers',
	'quizmaker_results',
	'quizmaker_messages',
];
// ------------------- Search ------------------- //
$modversion['hasSearch'] = 1;
$modversion['search'] = [
	'file' => 'include/search.inc.php',
	'func' => 'quizmaker_search',
];
// ------------------- Comments ------------------- //
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'quiz.php';
$modversion['comments']['itemName'] = 'quiz_id';
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback'] = [
	'approve' => 'quizmakerCommentsApprove',
	'update'  => 'quizmakerCommentsUpdate',
];
// ------------------- Menu ------------------- //
$currdirname  = isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule']) ? $GLOBALS['xoopsModule']->getVar('dirname') : 'system';
if ($currdirname == $moduleDirName) {
	$modversion['sub'][] = [
		'name' => _MI_QUIZMAKER_SMNAME1,
		'url'  => 'index.php',
	];
	// Sub quiz
	$modversion['sub'][] = [
		'name' => _MI_QUIZMAKER_SMNAME14,
		'url'  => 'results.php',
	];
}
// Entries last
$modversion['blocks'][] = [
    'file'        => 'categories.php',
    'name'        => '//' . \_MI_QUIZMAKER_CATEGORIES_BLOCK,
    'description' => \_MI_QUIZMAKER_CATEGORIES_BLOCK_DESC,
    'show_func'   => 'b_quizmaker_categories_show',
    'edit_func'   => 'b_quizmaker_categories_edit',
    'template'    => 'quizmaker_block_categories.tpl',
    'options'     => '5|80|0|Title', //nbItem|ldTitle|cats(0=all)|title
];
// Quiz last
$modversion['blocks'][] = [
	'file'        => 'quiz.php',
	'name'        => _MI_QUIZMAKER_QUIZ_BLOCK_LAST,
	'description' => _MI_QUIZMAKER_QUIZ_BLOCK_LAST_DESC,
	'show_func'   => 'b_quizmaker_quiz_show',
	'edit_func'   => 'b_quizmaker_quiz_edit',
	'template'    => 'quizmaker_block_quiz.tpl',
	'options'     => 'last|5|25|0',
];
// Quiz random
$modversion['blocks'][] = [
	'file'        => 'quiz.php',
	'name'        => _MI_QUIZMAKER_QUIZ_BLOCK_RANDOM,
	'description' => _MI_QUIZMAKER_QUIZ_BLOCK_RANDOM_DESC,
	'show_func'   => 'b_quizmaker_quiz_show',
	'edit_func'   => 'b_quizmaker_quiz_edit',
	'template'    => 'quizmaker_block_quiz.tpl',
	'options'     => 'random|5|25|0',
];
// Quiz random

// ------------------- Blocks ------------------- //
/*
// Quiz new
$modversion['blocks'][] = [
	'file'        => 'quiz.php',
	'name'        => _MI_QUIZMAKER_QUIZ_BLOCK_NEW,
	'description' => _MI_QUIZMAKER_QUIZ_BLOCK_NEW_DESC,
	'show_func'   => 'b_quizmaker_quiz_show',
	'edit_func'   => 'b_quizmaker_quiz_edit',
	'template'    => 'quizmaker_block_quiz.tpl',
	'options'     => 'new|5|25|0',
];
// Quiz hits
$modversion['blocks'][] = [
	'file'        => 'quiz.php',
	'name'        => _MI_QUIZMAKER_QUIZ_BLOCK_HITS,
	'description' => _MI_QUIZMAKER_QUIZ_BLOCK_HITS_DESC,
	'show_func'   => 'b_quizmaker_quiz_show',
	'edit_func'   => 'b_quizmaker_quiz_edit',
	'template'    => 'quizmaker_block_quiz.tpl',
	'options'     => 'hits|5|25|0',
];
// Quiz top
$modversion['blocks'][] = [
	'file'        => 'quiz.php',
	'name'        => _MI_QUIZMAKER_QUIZ_BLOCK_TOP,
	'description' => _MI_QUIZMAKER_QUIZ_BLOCK_TOP_DESC,
	'show_func'   => 'b_quizmaker_quiz_show',
	'edit_func'   => 'b_quizmaker_quiz_edit',
	'template'    => 'quizmaker_block_quiz.tpl',
	'options'     => 'top|5|25|0',
];



// Results new
$modversion['blocks'][] = [
	'file'        => 'results.php',
	'name'        => _MI_QUIZMAKER_RESULTS_BLOCK_NEW,
	'description' => _MI_QUIZMAKER_RESULTS_BLOCK_NEW_DESC,
	'show_func'   => 'b_quizmaker_results_show',
	'edit_func'   => 'b_quizmaker_results_edit',
	'template'    => 'quizmaker_block_results.tpl',
	'options'     => 'new|5|25|0',
];
// Results hits
$modversion['blocks'][] = [
	'file'        => 'results.php',
	'name'        => _MI_QUIZMAKER_RESULTS_BLOCK_HITS,
	'description' => _MI_QUIZMAKER_RESULTS_BLOCK_HITS_DESC,
	'show_func'   => 'b_quizmaker_results_show',
	'edit_func'   => 'b_quizmaker_results_edit',
	'template'    => 'quizmaker_block_results.tpl',
	'options'     => 'hits|5|25|0',
];
// Results top
$modversion['blocks'][] = [
	'file'        => 'results.php',
	'name'        => _MI_QUIZMAKER_RESULTS_BLOCK_TOP,
	'description' => _MI_QUIZMAKER_RESULTS_BLOCK_TOP_DESC,
	'show_func'   => 'b_quizmaker_results_show',
	'edit_func'   => 'b_quizmaker_results_edit',
	'template'    => 'quizmaker_block_results.tpl',
	'options'     => 'top|5|25|0',
];
// Results random
$modversion['blocks'][] = [
	'file'        => 'results.php',
	'name'        => _MI_QUIZMAKER_RESULTS_BLOCK_RANDOM,
	'description' => _MI_QUIZMAKER_RESULTS_BLOCK_RANDOM_DESC,
	'show_func'   => 'b_quizmaker_results_show',
	'edit_func'   => 'b_quizmaker_results_edit',
	'template'    => 'quizmaker_block_results.tpl',
	'options'     => 'random|5|25|0',
];
// Results last
$modversion['blocks'][] = [
	'file'        => 'results.php',
	'name'        => _MI_QUIZMAKER_RESULTS_BLOCK_LAST,
	'description' => _MI_QUIZMAKER_RESULTS_BLOCK_LAST_DESC,
	'show_func'   => 'b_quizmaker_results_show',
	'edit_func'   => 'b_quizmaker_results_edit',
	'template'    => 'quizmaker_block_results.tpl',
	'options'     => 'last|5|25|0',
];
*/


// ------------------- Config ------------------- //
// Editor Admin
xoops_load('xoopseditorhandler');
$editorHandler = XoopsEditorHandler::getInstance();
$modversion['config'][] = [
	'name'        => 'editor_admin',
	'title'       => '_MI_QUIZMAKER_EDITOR_ADMIN',
	'description' => '_MI_QUIZMAKER_EDITOR_ADMIN_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'TinyMCE', //dhtml
	'options'     => array_flip($editorHandler->getList()),
];
// Editor User
xoops_load('xoopseditorhandler');
$editorHandler = XoopsEditorHandler::getInstance();
$modversion['config'][] = [
	'name'        => 'editor_user',
	'title'       => '_MI_QUIZMAKER_EDITOR_USER',
	'description' => '_MI_QUIZMAKER_EDITOR_USER_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'TinyMCE', //dhtml
	'options'     => array_flip($editorHandler->getList()),
];
// Editor : max characters admin area
$modversion['config'][] = [
	'name'        => 'editor_maxchar',
	'title'       => '_MI_QUIZMAKER_EDITOR_MAXCHAR',
	'description' => '_MI_QUIZMAKER_EDITOR_MAXCHAR_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 50,
];
/*
// Get groups
$memberHandler = xoops_getHandler('member');
$xoopsGroups  = $memberHandler->getGroupList();
$groups = [];
foreach($xoopsGroups as $key => $group) {
	$groups[$group]  = $key;
}
// General access groups
$modversion['config'][] = [
	'name'        => 'groups',
	'title'       => '_MI_QUIZMAKER_GROUPS',
	'description' => '_MI_QUIZMAKER_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $groups,
	'options'     => $groups,
];
*/
// Upload groups
$modversion['config'][] = [
	'name'        => 'upload_groups',
	'title'       => '_MI_QUIZMAKER_UPLOAD_GROUPS',
	'description' => '_MI_QUIZMAKER_UPLOAD_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $groups,
	'options'     => $groups,
];
/*
// Get Admin groups
$crGroups = new \CriteriaCompo();
$crGroups->add( new \Criteria( 'group_type', 'Admin' ) );
$memberHandler = xoops_getHandler('member');
$adminXoopsGroups  = $memberHandler->getGroupList($crGroups);
$adminGroups = [];
foreach($adminXoopsGroups as $key => $adminGroup) {
	$adminGroups[$adminGroup]  = $key;
}
$modversion['config'][] = [
	'name'        => 'admin_groups',
	'title'       => '_MI_QUIZMAKER_ADMIN_GROUPS',
	'description' => '_MI_QUIZMAKER_ADMIN_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $adminGroups,
	'options'     => $adminGroups,
];
*/
unset($crGroups);
// Keywords
$modversion['config'][] = [
	'name'        => 'keywords',
	'title'       => '_MI_QUIZMAKER_KEYWORDS',
	'description' => '_MI_QUIZMAKER_KEYWORDS_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => _MI_QUIZMAKER_KEYWORDS_LIST,
    
];
// Admin pager
$modversion['config'][] = [
	'name'        => 'adminpager',
	'title'       => '_MI_QUIZMAKER_ADMIN_PAGER',
	'description' => '_MI_QUIZMAKER_ADMIN_PAGER_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 100,
];
// User pager
$modversion['config'][] = [
	'name'        => 'userpager',
	'title'       => '_MI_QUIZMAKER_USER_PAGER',
	'description' => '_MI_QUIZMAKER_USER_PAGER_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 25,
];
///////////////////////////////////////////////////////
// create increment steps for file size
include_once __DIR__ . '/include/xoops_version.inc.php';

$iniPostMaxSize       = quizmakerReturnBytes(\ini_get('post_max_size'));
$iniUploadMaxFileSize = quizmakerReturnBytes(\ini_get('upload_max_filesize'));
$maxSize              = min($iniPostMaxSize, $iniUploadMaxFileSize);
//echo quizmakerReturnBytes(\ini_get('post_max_size')) . "---" . quizmakerReturnBytes(\ini_get('upload_max_filesize')) . "---" . $maxSize;

if ($maxSize > 10000 * 1048576) {
    $increment = 500;
}
if ($maxSize <= 10000 * 1048576) {
    $increment = 200;
}
if ($maxSize <= 5000 * 1048576) {
    $increment = 100;
}
if ($maxSize <= 2500 * 1048576) {
    $increment = 50;
}
if ($maxSize <= 1000 * 1048576) {
    $increment = 10;
}
if ($maxSize <= 500 * 1048576) {
    $increment = 5;
}
if ($maxSize <= 100 * 1048576) {
    $increment = 2;
}
if ($maxSize <= 50 * 1048576) {
    $increment = 1;
}
if ($maxSize <= 25 * 1048576) {
    $increment = 0.5;
}
$optionMaxsize = [];
$i = $increment;
while ($i * 1048576 <= $maxSize) {
    $optionMaxsize[$i . ' ' . _MI_QUIZMAKER_SIZE_MB] = $i * 1048576;
    $i += $increment;
}
// Uploads : maxsize of image
$modversion['config'][] = [
    'name'        => 'maxsize_image',
    'title'       => '_MI_QUIZMAKER_MAXSIZE_IMAGE',
    'description' => '_MI_QUIZMAKER_MAXSIZE_IMAGE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 15728640,
    'options'     => $optionMaxsize,
];
// Uploads : mimetypes of image
$modversion['config'][] = [
    'name'        => 'mimetypes_image',
    'title'       => '_MI_QUIZMAKER_MIMETYPES_IMAGE',
    'description' => '_MI_QUIZMAKER_MIMETYPES_IMAGE_DESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'],
    'options'     => ['bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png'],
];
$modversion['config'][] = [
    'name'        => 'maxwidth_image',
    'title'       => '_MI_QUIZMAKER_MAXWIDTH_IMAGE',
    'description' => '_MI_QUIZMAKER_MAXWIDTH_IMAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 300,
];
$modversion['config'][] = [
    'name'        => 'maxheight_image',
    'title'       => '_MI_QUIZMAKER_MAXHEIGHT_IMAGE',
    'description' => '_MI_QUIZMAKER_MAXHEIGHT_IMAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 300,
];
//////////////////////////////////////////////////////
// Admin framework highslide
$modversion['config'][] = [
    'name'        => 'highslide',
    'title'       => '_MI_QUIZMAKER_HIGHSLIDE',
    'description' => '_MI_QUIZMAKER_HIGHSLIDE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'string',
    'default'     => 'highslide-5.0.0',
];

// Use tag
// $modversion['config'][] = [
// 	'name'        => 'usetag',
// 	'title'       => '_MI_QUIZMAKER_USE_TAG',
// 	'description' => '_MI_QUIZMAKER_USE_TAG_DESC',
// 	'formtype'    => 'yesno',
// 	'valuetype'   => 'int',
// 	'default'     => 0,
// ];
/*
// Number column
$modversion['config'][] = [
	'name'        => 'numb_col',
	'title'       => '_MI_QUIZMAKER_NUMB_COL',
	'description' => '_MI_QUIZMAKER_NUMB_COL_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
// Divide by
$modversion['config'][] = [
	'name'        => 'divideby',
	'title'       => '_MI_QUIZMAKER_DIVIDEBY',
	'description' => '_MI_QUIZMAKER_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
*/
// Table type
$modversion['config'][] = [
	'name'        => 'table_type',
	'title'       => '_MI_QUIZMAKER_TABLE_TYPE',
	'description' => '_MI_QUIZMAKER_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 'bordered',
	'options'     => ['bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed'],
];
// Panel by
$modversion['config'][] = [
	'name'        => 'panel_type',
	'title'       => '_MI_QUIZMAKER_PANEL_TYPE',
	'description' => '_MI_QUIZMAKER_PANEL_TYPE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'default',
	'options'     => ['default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger'],
];
// Advertise
$modversion['config'][] = [
	'name'        => 'advertise',
	'title'       => '_MI_QUIZMAKER_ADVERTISE',
	'description' => '_MI_QUIZMAKER_ADVERTISE_DESC',
	'formtype'    => 'textarea',
	'valuetype'   => 'text',
	'default'     => '',
];
// Bookmarks
$modversion['config'][] = [
	'name'        => 'bookmarks',
	'title'       => '_MI_QUIZMAKER_BOOKMARKS',
	'description' => '_MI_QUIZMAKER_BOOKMARKS_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];
// Make Sample button visible?
// $modversion['config'][] = [
// 	'name'        => 'displaySampleButton',
// 	'title'       => '_MI_QUIZMAKER_SHOW_SAMPLE_BUTTON',
// 	'description' => '_MI_QUIZMAKER_SHOW_SAMPLE_BUTTON_DESC',
// 	'formtype'    => 'yesno',
// 	'valuetype'   => 'int',
// 	'default'     => 1,
// ];
// Maintained by
$modversion['config'][] = [
	'name'        => 'maintainedby',
	'title'       => '_MI_QUIZMAKER_MAINTAINEDBY',
	'description' => '_MI_QUIZMAKER_MAINTAINEDBY_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'https://github.com/JJDai54/quizmaker',
];
// Jacascript is minified
$modversion['config'][] = [
	'name'        => 'use_js_minified',
	'title'       => '_MI_QUIZMAKER_USE_JS_MINIFIED',
	'description' => '_MI_QUIZMAKER_USE_JS_MINIFIED_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];

// Use tag
$modversion['config'][] = [
	'name'        => 'perm_by_quiz',
	'title'       => '_MI_QUIZMAKER_APLY_PERM_CAT',
	'description' => '_MI_QUIZMAKER_APLY_PERM_CAT_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];

// help type de question
$modversion['config'][] = [
	'name'        => 'display_slide_help',
	'title'       => '_MI_QUIZMAKER_DISPLAY_SLIDE_HELP',
	'description' => '_MI_QUIZMAKER_DISPLAY_SLIDE_HELP_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 1,
];

$modversion['config'][] = [
    'name' => 'displayTemplateName',
    'title'       => '_MI_QUIZMAKER_SHOW_TPL_NAME',
    'description' => '_MI_QUIZMAKER_SHOW_TPL_NAME_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0];
    
// ------------------- Notifications ------------------- //
$modversion['hasNotification'] = 0;
$modversion['notification'] = [
	'lookup_file' => 'include/notification.inc.php',
	'lookup_func' => 'quizmaker_notify_iteminfo',
];
// Categories of notification
// Global Notify
$modversion['notification']['category'][] = [
	'name'           => 'global',
	'title'          => _MI_QUIZMAKER_NOTIFY_GLOBAL,
	'description'    => '',
	'subscribe_from' => ['index.php', 'categories.php', 'quiz.php'],
];
// Categories Notify
$modversion['notification']['category'][] = [
	'name'           => 'categories',
	'title'          => _MI_QUIZMAKER_NOTIFY_CATEGORIES,
	'description'    => '',
	'subscribe_from' => 'categories.php',
	'item_name'      => 'cat_id',
	'allow_bookmark' => 1,
];
// Quiz Notify
$modversion['notification']['category'][] = [
	'name'           => 'quiz',
	'title'          => _MI_QUIZMAKER_NOTIFY_QUIZ,
	'description'    => '',
	'subscribe_from' => 'quiz.php',
	'item_name'      => 'quiz_id',
	'allow_bookmark' => 1,
];
// Global events notification
// GLOBAL_NEW Notify
$modversion['notification']['event'][] = [
	'name'          => 'global_new',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_GLOBAL_NEW,
	'caption'       => _MI_QUIZMAKER_NOTIFY_GLOBAL_NEW_CAPTION,
	'description'   => '',
	'mail_template' => 'global_new_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_GLOBAL_NEW_SUBJECT,
];
// GLOBAL_MODIFY Notify
$modversion['notification']['event'][] = [
	'name'          => 'global_modify',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_GLOBAL_MODIFY,
	'caption'       => _MI_QUIZMAKER_NOTIFY_GLOBAL_MODIFY_CAPTION,
	'description'   => '',
	'mail_template' => 'global_modify_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_GLOBAL_MODIFY_SUBJECT,
];
// GLOBAL_DELETE Notify
$modversion['notification']['event'][] = [
	'name'          => 'global_delete',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_GLOBAL_DELETE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_GLOBAL_DELETE_CAPTION,
	'description'   => '',
	'mail_template' => 'global_delete_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_GLOBAL_DELETE_SUBJECT,
];
// GLOBAL_APPROVE Notify
$modversion['notification']['event'][] = [
	'name'          => 'global_approve',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_GLOBAL_APPROVE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_GLOBAL_APPROVE_CAPTION,
	'description'   => '',
	'mail_template' => 'global_approve_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_GLOBAL_APPROVE_SUBJECT,
];
// GLOBAL_COMMENT Notify
$modversion['notification']['event'][] = [
	'name'          => 'global_comment',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_GLOBAL_COMMENT,
	'caption'       => _MI_QUIZMAKER_NOTIFY_GLOBAL_COMMENT_CAPTION,
	'description'   => '',
	'mail_template' => 'global_comment_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_GLOBAL_COMMENT_SUBJECT,
];
// Event notifications for items
// CATEGORIES_MODIFY Notify
$modversion['notification']['event'][] = [
	'name'          => 'Categories_modify',
	'category'      => 'categories',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_CATEGORIES_MODIFY,
	'caption'       => _MI_QUIZMAKER_NOTIFY_CATEGORIES_MODIFY_CAPTION,
	'description'   => '',
	'mail_template' => 'Categories_modify_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_CATEGORIES_MODIFY_SUBJECT,
];
// CATEGORIES_DELETE Notify
$modversion['notification']['event'][] = [
	'name'          => 'Categories_delete',
	'category'      => 'categories',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_CATEGORIES_DELETE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_CATEGORIES_DELETE_CAPTION,
	'description'   => '',
	'mail_template' => 'Categories_delete_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_CATEGORIES_DELETE_SUBJECT,
];
// CATEGORIES_APPROVE Notify
$modversion['notification']['event'][] = [
	'name'          => 'Categories_approve',
	'category'      => 'categories',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_CATEGORIES_APPROVE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_CATEGORIES_APPROVE_CAPTION,
	'description'   => '',
	'mail_template' => 'Categories_approve_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_CATEGORIES_APPROVE_SUBJECT,
];
// QUIZ_MODIFY Notify
$modversion['notification']['event'][] = [
	'name'          => 'Quiz_modify',
	'category'      => 'quiz',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_QUIZ_MODIFY,
	'caption'       => _MI_QUIZMAKER_NOTIFY_QUIZ_MODIFY_CAPTION,
	'description'   => '',
	'mail_template' => 'Quiz_modify_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_QUIZ_MODIFY_SUBJECT,
];
// QUIZ_DELETE Notify
$modversion['notification']['event'][] = [
	'name'          => 'Quiz_delete',
	'category'      => 'quiz',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_QUIZ_DELETE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_QUIZ_DELETE_CAPTION,
	'description'   => '',
	'mail_template' => 'Quiz_delete_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_QUIZ_DELETE_SUBJECT,
];
// QUIZ_APPROVE Notify
$modversion['notification']['event'][] = [
	'name'          => 'Quiz_approve',
	'category'      => 'quiz',
	'admin_only'    => 0,
	'title'         => _MI_QUIZMAKER_NOTIFY_QUIZ_APPROVE,
	'caption'       => _MI_QUIZMAKER_NOTIFY_QUIZ_APPROVE_CAPTION,
	'description'   => '',
	'mail_template' => 'Quiz_approve_notify',
	'mail_subject'  => _MI_QUIZMAKER_NOTIFY_QUIZ_APPROVE_SUBJECT,
];

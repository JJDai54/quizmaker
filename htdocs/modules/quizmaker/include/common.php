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
 
$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
if(isset($quizmakerHelper)){
    define ("QUIZMAKER_SHOW_TPL_NAME", $quizmakerHelper->getConfig('displayTemplateName') );
}else{
    define ("QUIZMAKER_SHOW_TPL_NAME", 0);
}
 
if (!defined('XOOPS_ICONS32_PATH')) {
	define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
	define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}


define('QUIZMAKER_DIRNAME', 'quizmaker');
define('QUIZMAKER_PATH', XOOPS_ROOT_PATH.'/modules/'.QUIZMAKER_DIRNAME);
define('QUIZMAKER_URL', XOOPS_URL .      '/modules/'.QUIZMAKER_DIRNAME);

define('QUIZMAKER_PLUGINS_PATH', QUIZMAKER_PATH .'/plugins');
define('QUIZMAKER_PLUGINS_URL',  QUIZMAKER_URL . '/plugins');







define('QUIZMAKER_ICONS_PATH', QUIZMAKER_PATH.'/assets/icons');
define('QUIZMAKER_ICONS_URL', QUIZMAKER_URL.'/assets/icons');
define('QUIZMAKER_IMAGE_PATH', QUIZMAKER_PATH.'/assets/images');
define('QUIZMAKER_IMAGE_URL', QUIZMAKER_URL.'/assets/images');
define('QUIZMAKER_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.QUIZMAKER_DIRNAME);
define('QUIZMAKER_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.QUIZMAKER_DIRNAME);
define('QUIZMAKER_UPLOAD_EXPORT_PATH', QUIZMAKER_UPLOAD_PATH.'/export');
define('QUIZMAKER_UPLOAD_EXPORT_URL', QUIZMAKER_UPLOAD_URL.'/export');
define('QUIZMAKER_UPLOAD_IMPORT_PATH', QUIZMAKER_UPLOAD_PATH.'/import');
define('QUIZMAKER_UPLOAD_IMPORT_URL', QUIZMAKER_UPLOAD_URL.'/import');
define('QUIZMAKER_UPLOAD_IMAGE_PATH', QUIZMAKER_UPLOAD_PATH.'/images');
define('QUIZMAKER_UPLOAD_IMAGE_URL', QUIZMAKER_UPLOAD_URL.'/images');
define('QUIZMAKER_UPLOAD_SHOTS_PATH', QUIZMAKER_UPLOAD_PATH.'/images/shots');
define('QUIZMAKER_UPLOAD_SHOTS_URL', QUIZMAKER_UPLOAD_URL.'/images/shots');
define('QUIZMAKER_ADMIN', QUIZMAKER_URL . '/admin/index.php');


define('QUIZMAKER_QUIZ_JS', '/assets/js');
define('QUIZMAKER_QUIZ_ORG', '/quiz-org');
define('QUIZMAKER_QUIZ_MIN', '/quiz-min');

$useJsMinified = $quizmakerHelper->getConfig('use_js_minified');
define('QUIZMAKER_QUIZ_JS_TO_RUN', QUIZMAKER_QUIZ_JS . (($useJsMinified) ? QUIZMAKER_QUIZ_MIN : QUIZMAKER_QUIZ_ORG)) ;

/*
$useJsMinified = $quizmakerHelper->getConfig('use_js_minified');
define('QUIZMAKER_QUIZ_JS_TO_RUN', '/assets/js/' . (($useJsMinified) ? 'quiz-min' : 'quiz-org')) ;
*/
define('QUIZMAKER_QUIZ_JS_PATH', QUIZMAKER_PATH . QUIZMAKER_QUIZ_JS_TO_RUN);
define('QUIZMAKER_QUIZ_JS_URL',  QUIZMAKER_URL  .  QUIZMAKER_QUIZ_JS_TO_RUN);

define('QUIZMAKER_QUIZ_JS_ORG', QUIZMAKER_PATH . QUIZMAKER_QUIZ_JS . QUIZMAKER_QUIZ_ORG);
define('QUIZMAKER_QUIZ_JS_MIN', QUIZMAKER_PATH . QUIZMAKER_QUIZ_JS . QUIZMAKER_QUIZ_MIN);



define('QUIZMAKER_UPLOAD_QUIZ_JS', '/quiz-js');
define('QUIZMAKER_UPLOAD_QUIZ_PATH', QUIZMAKER_UPLOAD_PATH . QUIZMAKER_UPLOAD_QUIZ_JS);
define('QUIZMAKER_UPLOAD_QUIZ_URL',  QUIZMAKER_UPLOAD_URL  . QUIZMAKER_UPLOAD_QUIZ_JS);

define('QUIZMAKER_ANSWERS_CLASS', QUIZMAKER_PATH . "/class/type_question");
define('QUIZMAKER_MODELES_IMG', QUIZMAKER_URL . "/assets/images/modeles");
define('QUIZMAKER_LANGUAGE', QUIZMAKER_PATH . "/language");
define('QUIZMAKER_MODELES_IMG_PATH', QUIZMAKER_PATH . "/assets/images/modeles");


define('QUIZMAKER_SELECT_ONCHANGE', 'onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
define('QUIZMAKER_ADD_ID', true);

define('QUIZMAKER_POINTS_POSITIF', 'blue');
define('QUIZMAKER_POINTS_NULL', 'black');
define('QUIZMAKER_POINTS_NEGATIF', 'red');

define('QUIZMAKER_FORMAT_DATE_SQL', 'Y-m-d h:i:s');
define('QUIZMAKER_FORMAT_DATE', 'd-m-Y h:i:s');
//define('XOBJ_DTYPE_DATETIME', 99); //XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME

define('QUIZMAKER_TYPE_FORM_QUESTION',   0);
define('QUIZMAKER_TYPE_FORM_BEGIN',  1);
define('QUIZMAKER_TYPE_FORM_GROUP', 2);
define('QUIZMAKER_TYPE_FORM_END', 3);

define('QUIZMAKER_ALL', '__ALL__');
define('QUIZMAKER_TIMER_MAX', 120);
define('QUIZMAKER_SHUFFLE_DEFAULT', 0);

$localLogo = QUIZMAKER_IMAGE_URL . '/jean-jacquesdelalandre_logo.png';
// Module Information
$copyright = "<a href='http://xmodules.jubile.fr' title='Origami' target='_blank'><img src='".$localLogo."' alt='Origami' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once QUIZMAKER_PATH . '/include/functions.php';



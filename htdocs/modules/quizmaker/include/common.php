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
//-------------- folders ------------------------------
define('QUIZMAKER_FLD_MODULE',   '/modules/'. QUIZMAKER_DIRNAME);
define('QUIZMAKER_FLD_UPLOADS',   '/'. QUIZMAKER_DIRNAME);
define('QUIZMAKER_FLD_QUIZ_JS',  '/assets/js');
define('QUIZMAKER_FLD_QUIZ_ORG', '/quiz-org');
define('QUIZMAKER_FLD_QUIZ_MIN', '/quiz-min');
define('QUIZMAKER_FLD_UPLOAD_QUIZ_JS', '/quiz-js');
define('QUIZMAKER_FLD_PLUGINS_JS', '/plugins');
define('QUIZMAKER_FLD_PLUGINS_PHP', '/plugins');
define('QUIZMAKER_FLD_LANGUAGE_JS', '/language');

define('QUIZMAKER_FLD_EXPORT', '/export');
define('QUIZMAKER_FLD_IMPORT', '/import');
define('QUIZMAKER_FLD_IMPORT_BATCH', '/import_batch');

//-------------- path ------------------------------
define('QUIZMAKER_PATH_MODULE', XOOPS_ROOT_PATH . QUIZMAKER_FLD_MODULE);
define('QUIZMAKER_PATH_PLUGINS_PHP', QUIZMAKER_PATH_MODULE . QUIZMAKER_FLD_PLUGINS_PHP);
define('QUIZMAKER_PATH_UPLOAD', XOOPS_UPLOAD_PATH.'/'.QUIZMAKER_DIRNAME);
define('QUIZMAKER_PATH_UPLOAD_EXPORT', QUIZMAKER_PATH_UPLOAD . QUIZMAKER_FLD_EXPORT);
define('QUIZMAKER_PATH_UPLOAD_IMPORT', QUIZMAKER_PATH_UPLOAD. QUIZMAKER_FLD_IMPORT);
define('QUIZMAKER_PATH_UPLOAD_IMPORT_BATCH', QUIZMAKER_PATH_UPLOAD.QUIZMAKER_FLD_IMPORT_BATCH);
define('QUIZMAKER_PATH_UPLOAD_QUIZ', QUIZMAKER_PATH_UPLOAD . QUIZMAKER_FLD_UPLOAD_QUIZ_JS);

//-------------- url ------------------------------
define('QUIZMAKER_URL_MODULE', XOOPS_URL . QUIZMAKER_FLD_MODULE);
define('QUIZMAKER_URL_ADMIN', QUIZMAKER_URL_MODULE . '/admin/index.php');
define('QUIZMAKER_URL_ASSETS', QUIZMAKER_URL_MODULE.'/assets');
define('QUIZMAKER_URL_ICONS', QUIZMAKER_URL_MODULE.'/assets/icons');
define('QUIZMAKER_URL_IMAGE', QUIZMAKER_URL_MODULE.'/assets/images');
define('QUIZMAKER_URL_PLUGINS_PHP',  QUIZMAKER_URL_MODULE . QUIZMAKER_FLD_PLUGINS_PHP);
define('QUIZMAKER_URL_UPLOAD', XOOPS_UPLOAD_URL.'/'.QUIZMAKER_DIRNAME);
define('QUIZMAKER_URL_UPLOAD_EXPORT', QUIZMAKER_URL_UPLOAD.'/export');
define('QUIZMAKER_URL_UPLOAD_QUIZ',  QUIZMAKER_URL_UPLOAD  . QUIZMAKER_FLD_UPLOAD_QUIZ_JS);

//-------------- path et url poour quie_org ou quiz_min ------------------------------
/*
$useJsMinified = $quizmakerHelper->getConfig('use_js_minified');
define('QUIZMAKER_QUIZ_JS_TO_RUN', QUIZMAKER_FLD_QUIZ_JS . (($useJsMinified) ? QUIZMAKER_FLD_QUIZ_MIN : QUIZMAKER_FLD_QUIZ_ORG)) ;
*/
define('QUIZMAKER_PATH_QUIZ_ORG', QUIZMAKER_PATH_MODULE . QUIZMAKER_FLD_QUIZ_JS . QUIZMAKER_FLD_QUIZ_ORG);
define('QUIZMAKER_PATH_QUIZ_MIN', QUIZMAKER_PATH_UPLOAD . QUIZMAKER_FLD_QUIZ_MIN );

if ($quizmakerHelper->getConfig('use_js_minified')){
    define('QUIZMAKER_QUIZ_JS_TO_RUN', QUIZMAKER_FLD_QUIZ_MIN);
    define('QUIZMAKER_PATH_QUIZ_JS', QUIZMAKER_PATH_UPLOAD . QUIZMAKER_QUIZ_JS_TO_RUN);
    define('QUIZMAKER_URL_PLUGINS_JS',   QUIZMAKER_URL_UPLOAD . QUIZMAKER_QUIZ_JS_TO_RUN . QUIZMAKER_FLD_PLUGINS_JS);
    define('QUIZMAKER_URL_QUIZ_JS',  QUIZMAKER_URL_UPLOAD  .  QUIZMAKER_QUIZ_JS_TO_RUN);
}else{
    define('QUIZMAKER_QUIZ_JS_TO_RUN', QUIZMAKER_FLD_QUIZ_JS . QUIZMAKER_FLD_QUIZ_ORG);
    define('QUIZMAKER_PATH_QUIZ_JS', QUIZMAKER_PATH_MODULE . QUIZMAKER_QUIZ_JS_TO_RUN);
    define('QUIZMAKER_URL_PLUGINS_JS',   QUIZMAKER_URL_MODULE . QUIZMAKER_QUIZ_JS_TO_RUN . QUIZMAKER_FLD_PLUGINS_JS);
    define('QUIZMAKER_URL_QUIZ_JS',  QUIZMAKER_URL_MODULE  .  QUIZMAKER_QUIZ_JS_TO_RUN);
}

/***
 * $folder : sous dossier
 * $protocole : P pour path ou U pour url, F pou folder
 * $cible : M pour module, U pour upload
 * ***/
function getQMPath($fld, $protocole='P' , $cible = 'M'){
    switch($fld){
    case 'module'; $fld = '/module/' . QUIZMAKER_DIRNAME; break ;
    case 'upload'; $fld = '/uploads/' . QUIZMAKER_DIRNAME; break ;
    case 'plugins_js'; $fld = '/assets/quiz_org/plugins'; break ;
    }
    
    switch($protocole){
    default:
    case 'P'; $root =  XOOPS_ROOT_PATH . "/module/" . QUIZMAKER_DIRNAME; break ;
    case 'U'; $root =  XOOPS_URL . "/module/" . QUIZMAKER_DIRNAME; break ;
    case 'F'; $root =  "/module/" . QUIZMAKER_DIRNAME; break ;
    }
    
return $root . $fld;
}
///--------------Obsolete----------------------
//define('QUIZMAKER_MODELES_IMG', QUIZMAKER_URL_MODULE . "/assets/images/modeles");
//-------------- autres constantes ------------------------------


define('QUIZMAKER_SELECT_ONCHANGE', 'onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"');
define('QUIZMAKER_ADD_ID', true);
define('QUIZMAKER_NEW', '__NEW__');
define('QUIZMAKER_REQUIS', "<span style='color:red;'>*</span>");

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
define('QUIZMAKER_ALL_SELECTION', '(*) ');
define('QUIZMAKER_TIMER_MAX', 240);
define('QUIZMAKER_SHUFFLE_DEFAULT', 0);

define('QUIZMAKER_PREFIX_OPTIONS_NAME', 'quest_options');
define('QUIZMAKER_PREFIX_CAT', '_QT_QUIZMAKER_CAT_');    
define('QBR', '<br>');    
    
define('QUIZMAKER_BG_LIST_CAT', '#FFCC99');    
define('QUIZMAKER_BG_LIST_QUIZ', '#CCFFFF');    
define('QUIZMAKER_BG_LIST_QUEST', '#E0FFF0');    
define('QUIZMAKER_BG_LIST_TYPEQUEST', '#FFFFCC');    
define('QUIZMAKER_BG_LIST_TYPEIMPORT', '#FFFFCC');    
define('QUIZMAKER_BG_LIST_GROUP', 'lightblue');    
define('QUIZMAKER_BG_LIST_POINTS', '#FFCC99');    
define('QUIZMAKER_BG_LIST_TIMER', '#FFCC99');    

$h = 0;
define('QUIZMAKER_BIT__ALLOWEDSUBMIT', $h++);
define('QUIZMAKER_BIT_SHOW_SCOREMINMAX', $h++);
define('QUIZMAKER_BIT_SHOW_ALLSOLUTIONS', $h++);
define('QUIZMAKER_BIT_ANSWERBEFORENEXT', $h++);
define('QUIZMAKER_BIT_ALLOWEDPREVIOUS', $h++);
define('QUIZMAKER_BIT_USETIMER', $h++);
define('QUIZMAKER_BIT_SHUFFLEQUESTIONS', $h++);
define('QUIZMAKER_BIT_SHOW_RESULTPOPUP', $h++);
//define('QUIZMAKER_BIT_MINUSONSHOWGOODANSWERS', $h++);


$h = 0;
define('QUIZMAKER_BIT_SHOW_PLUGIN', $h++);
define('QUIZMAKER_BIT_SHOW_RELOADANSWERS', $h++);
define('QUIZMAKER_BIT_SHOW_GOTOSLIDE', $h++);
define('QUIZMAKER_BIT_SHOW_GOODANSWERS', $h++);
define('QUIZMAKER_BIT_SHOW_BADANSWERS', $h++);
define('QUIZMAKER_BIT_SHOW_LOG', $h++);
define('QUIZMAKER_BIT_SHOW_RESULTALLWAYS', $h++);
define('QUIZMAKER_BIT_SHOW_REPONSESBOTTOM', $h++);


// Module Information
$localLogo = QUIZMAKER_URL_IMAGE . '/jean-jacques_delalandre_logo.png';
$copyright = "<a href='http://oritheque.fr' title='Origami' target='_blank'><img src='".$localLogo."' alt='Origami' /></a>";


include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once QUIZMAKER_PATH_MODULE . '/include/functions.php';



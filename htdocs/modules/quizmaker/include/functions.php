<?php
namespace XoopsModules\Quizmaker;
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
 use XoopsModules\Quizmaker AS FQUIZMAKER;

 
/**
 * function add selected cats to block
 *
 * @param  $cats 
 * @return string
 */
function quizmaker_utf8_encode($exp)
 {
// utf8_encode is deprecated
//$consigne = utf8_encode(\JANUS\FSO\loadtextFile($this->pathArr['consigne_path']));
    return mb_convert_encoding($exp, 'UTF-8', mb_detect_encoding($exp));//mb_list_encodings
 }
 
 

/**
 * function add selected cats to block
 *
 * @param  $cats 
 * @return string
 */
function getStyle($background='', $color='')
{
    $style = " style='";
    if ($background) $style .= "background:{$background};";
    if ($color) $style .= "color:{$color};";
    $style .= "'";
    return $style;
}

/**
 * function add selected cats to block
 *
 * @param  $cats 
 * @return string
 */
function block_addCatSelect($cats)
{
	$cat_sql = '(';
	if (is_array($cats)) {
		$cat_sql .= current($cats);
		array_shift($cats);
		foreach($cats as $cat) {
			$cat_sql .= ',' . $cat;
		}
	}
	$cat_sql .= ')';
	return $cat_sql;
}

/**
 * Get the permissions ids 
 *
 * @param  $permtype 
 * @param  $dirname 
 * @return mixed $itemIds
 */
function getMyItemIds($permtype, $dirname)
{
	global $xoopsUser;
	static $permissions = [];
	if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
		return $permissions[$permtype];
	}
	$moduleHandler = xoops_getHandler('module');
	$quizmakerModule = $moduleHandler->getByDirname($dirname);
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$grouppermHandler = xoops_getHandler('groupperm');
	$itemIds = $grouppermHandler->getItemIds($permtype, $groups, $quizmakerModule->getVar('mid'));
	return $itemIds;
}

/**
 * Get the number of results from the sub categories of a category or sub topics of or topic
 * @param $mytree
 * @param $results
 * @param $entries
 * @param $cid
 * @return int
 */
function numbersOfEntries($mytree, $results, $entries, $cid)
{
    $count = 0;
    if(in_array($cid, $results)) {
        $child = $mytree->getAllChild($cid);
        foreach (array_keys($entries) as $i) {
            if ($entries[$i]->getVar('result_id') == $cid){
                $count++;
            }
            foreach (array_keys($child) as $j) {
                if ($entries[$i]->getVar('result_id') == $j){
                    $count++;
                }
            }
        }
    }
    return $count;
}

/**
 * Add content as meta tag to template
 * @param $content
 * @return void
 */

function metaKeywords($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = \MyTextSanitizer::getInstance();
    $content= $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if(isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addMeta( 'meta', 'keywords', strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_keywords', strip_tags($content));
    }
}

/**
 * Add content as meta description to template
 * @param $content
 * @return void
 */
 
function metaDescription($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = \MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if(isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addMeta( 'meta', 'description', strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_description', strip_tags($content));
    }
}

/**
 * Rewrite all url
 *
 * @param string  $module  module name
 * @param array   $array   array
 * @param string  $type    type
 * @return null|string $type    string replacement for any blank case
 */
function quizmaker_RewriteUrl($module, $array, $type = 'content')
{
    $comment = '';
    $quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
    $resultsHandler = $quizmakerHelper->getHandler('results');
    $lenght_id = $quizmakerHelper->getConfig('lenght_id');
    $rewrite_url = $quizmakerHelper->getConfig('rewrite_url');

    if ($lenght_id != 0) {
        $id = $array['content_id'];
        while (strlen($id) < $lenght_id) {
            $id = '0' . $id;
        }
    } else {
        $id = $array['content_id'];
    }

    if (isset($array['topic_alias']) && $array['topic_alias']) {
        $topic_name = $array['topic_alias'];
    } else {
        $topic_name = FQUIZMAKER\sanityse_url(xoops_getModuleOption('static_name', $module));
    }

    switch ($rewrite_url) {

        case 'none':
            if($topic_name) {
                 $topic_name = 'topic=' . $topic_name . '&amp;';
            }
            $rewrite_base = '/modules/';
            $page = 'page=' . $array['content_alias'];
            return XOOPS_URL . $rewrite_base . $module . '/' . $type . '.php?' . $topic_name . 'id=' . $id . '&amp;' . $page . $comment;
            break;

        case 'rewrite':
            if($topic_name) {
                $topic_name .= '/';
            }
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext = xoops_getModuleOption('rewrite_ext', $module);
            $module_name = '';
            if(xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['content_alias'];
            $type .= '/';
            $id .= '/';
            if ($type === 'content/') {
                $type = '';
            }
            if ($type === 'comment-edit/' || $type === 'comment-reply/' || $type === 'comment-delete/') {
                return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name  . $id . $page . $rewrite_ext;
            break;

         case 'short':
            if($topic_name) {
                $topic_name .= '/';
            }
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext = xoops_getModuleOption('rewrite_ext', $module);
            $module_name = '';
            if(xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['content_alias'];
            $type .= '/';
            if ($type === 'content/') {
                $type = '';
            }
            if ($type === 'comment-edit/' || $type === 'comment-reply/' || $type === 'comment-delete/') {
                return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $page . $rewrite_ext;
            break;
    }
    return null;
}
/**
 * Replace all escape, character, ... for display a correct url
 *
 * @param string $url      string to transform
 * @param string $type     string replacement for any blank case
 * @return string $url
 */
function sanityse_url($url, $type = '') {

    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    $quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
    $resultsHandler = $quizmakerHelper->getHandler('results');
    $regular_expression = $quizmakerHelper->getConfig('regular_expression');

    $url = strip_tags($url);
    $url .= preg_replace("`\[.*\]`U", '', $url);
    $url .= preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
    $url .= htmlentities($url, ENT_COMPAT, 'utf-8');
    $url .= preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\1", $url);
    $url .= preg_replace(array($regular_expression, "`[-]+`"), '-', $url);
    $url = ($url == '') ? $type : strtolower(trim($url, '-'));
    return $url;
}

/* ***************************************

****************************************** */
function format_caractere($car, $color, $size="11px"){
    $ret = "<span style='font-family: Arial Rounded MT Bold; color: {$color};font-size:{$size}'>{$car}</span></font><br>";
}

/**********************************************************************
 * getParamsForQuiz : renvoi une chaine de parametre pour personaliser le quiz
 * Tout n'est pas utile uname et name sont probablement suffisant, a voir
 **********************************************************************/
function getParamsForQuiz ($asString = false, $resultId=0){
global $xoopsUser;
        xoops_load('XoopsUserUtility');
    if(is_object($xoopsUser)){
        //$currentuid = ($xoopsUser) ? $xoopsUser->uid() : 2;        
        $allParams = array('uid'  => $xoopsUser->uid(),
        'uname' => $xoopsUser->getVar('uname', 'e'),
        'name' => $xoopsUser->getVar('name', 'e'),
        'email' => $xoopsUser->getVar('email', 'e'),
        'ip'   => \XoopsUserUtility::getIP(true));
    }else{
        $currentuid = 2;        
        $allParams = array('uid'  => 2,
        'uname' => 'Anonymous',
        'name' => 'Anonymous',
        'email' => 'anonymous@orange.fr',
        'ip'   => \XoopsUserUtility::getIP(true));
    }     
    $allParams['resultId'] = $resultId;   
    //-------------------------------------------
    if($asString){
        $t = [];
        foreach($allParams AS $key=>$value)
            $t[] = $key . '='  . $value;
        return implode("&", $t);
    }else{
        return $allParams;
    }
}

/**********************************************************************
*
* **********************************************************************/
function getBinOptionsArr ($binName){
    switch(strtolower($binName)){
    case 'ihm':
$arr = array(
QUIZMAKER_BIT_ALLOWEDSUBMIT     => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_ALLOWEDSUBMIT, _AM_QUIZMAKER_QUIZ_SHOW_ALLOWEDSUBMIT_DESC),
QUIZMAKER_BIT_SHOW_SCOREMINMAX  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_SCORE_MIN_MAX, _AM_QUIZMAKER_QUIZ_SHOW_SCORE_MIN_MAX_DESC),
QUIZMAKER_BIT_SHOW_ALLSOLUTIONS => sprintf("%s (%s)", _AM_QUIZMAKER_VIEW_ALL_SOLUTIONS, _AM_QUIZMAKER_SHOW_ALL_SOLUTIONS_DESC),
QUIZMAKER_BIT_ANSWERBEFORENEXT  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_ANSWER_BEFORENEXT, _AM_QUIZMAKER_QUIZ_ANSWERBEFORENEXT_DESC),
QUIZMAKER_BIT_ALLOWEDPREVIOUS   => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_ALLOWED_PREVIOUS, _AM_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS_DESC),
QUIZMAKER_BIT_USETIMER          => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_USE_TIMER, _AM_QUIZMAKER_QUIZ_USE_TIMER_DESC),
QUIZMAKER_BIT_SHUFFLEQUESTIONS  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION, _AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION_DESC),
QUIZMAKER_BIT_SHOW_RESULTPOPUP  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_RESULT_POPUP, _AM_QUIZMAKER_QUIZ_RESULT_POPUP_DESC));
        break;
        
    case 'dev':
$arr = array(
QUIZMAKER_BIT_SHOW_PLUGIN           => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_PLUGIN, _AM_QUIZMAKER_QUIZ_SHOW_PLUGIN_DESC),
QUIZMAKER_BIT_SHOW_RELOADANSWERS    => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_GOTOSLIDE        => sprintf("%s (%s)", _AM_QUIZMAKER_SHOW_BTN_GOTO_SLIDE, _AM_QUIZMAKER_SHOW_BTN_GOTO_PLUGIN_DESC),
QUIZMAKER_BIT_SHOW_GOODANSWERS      => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_BADANSWERS       => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_LOG              => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_LOG, _AM_QUIZMAKER_QUIZ_SHOW_LOG_DESC),
QUIZMAKER_BIT_SHOW_RESULTALLWAYS    => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_RESULT_ALLWAYS, _AM_QUIZMAKER_QUIZ_SHOW_REPONSES),
QUIZMAKER_BIT_SHOW_REPONSESBOTTOM   => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_REPONSES_BOTTOM, _AM_QUIZMAKER_QUIZ_SHOW_REPONSES_BOTTOM_DESC));
        break;
    }
    
    return $arr;
}

/**********************************************************************
*
* **********************************************************************/
function getBinOptionsFlagsArr ($binName, $binOptions){
    $flags = array();

    switch(strtolower($binName)){
    case 'ihm':
        $flags['allowedSubmit']     = quizFlagAscii(isBitOk(QUIZMAKER_BIT_ALLOWEDSUBMIT, $binOptions), "Sb"); 
        $flags['showScoreMinMax']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_SCOREMINMAX, $binOptions), "Smm"); 
        $flags['showAllSolutions']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_ALLSOLUTIONS, $binOptions), "Vas"); 
        $flags['answerBeforeNext']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_ANSWERBEFORENEXT, $binOptions), "Ro");
        $flags['allowedPrevious']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_ALLOWEDPREVIOUS, $binOptions), "Pr"); 
        $flags['useTimer']          = quizFlagAscii(isBitOk(QUIZMAKER_BIT_USETIMER, $binOptions), "T");        
        $flags['shuffleQuestions']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHUFFLEQUESTIONS, $binOptions), "M"); 
        $flags['showResultPopup']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTPOPUP, $binOptions), "PU");
        //$flags['minusOnShowGoodAnswers'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_MINUSONSHOWGOODANSWERS, $binOptions), "Minus");
        break;
        
    case 'dev':
        $flags['showTypeQuestion']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_PLUGIN, $binOptions), "TQ");
        $flags['showReloadAnswers'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RELOADANSWERS, $binOptions), "Rl");
        $flags['showGoToSlide']     = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_GOTOSLIDE, $binOptions), "Go");
        $flags['showGoodAnswers']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_GOODANSWERS, $binOptions), "Ga"); 
        $flags['showBadAnswers']    = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_BADANSWERS, $binOptions), "Ba"); 
        $flags['showLog']           = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_LOG, $binOptions), "Log"); 
        $flags['showResultAllways'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTALLWAYS, $binOptions), "Ra"); 
        $flags['showReponsesBottom']= quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_REPONSESBOTTOM, $binOptions), "Rb"); 
        break;
    }

    return $flags;
}




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
* Returns folder with prefix de categorie si il est defini
* @$root   string : u = url sinon "p" ou defaut path physique
* @$folder string : u = upload  =sinon "m" ou defautl = module
* @return string folder
* 
* FQUIZMAKER\getFolderJS($ret = 'p', $folder = "m" , $subfolder='', $addEndSlash = false)
*/

function getFolder($root = 'p', $folder = "m" , $subfolder='', $addEndSlash = false)
{

    
    if (strtolower($folder) == "u"){
        $ret = "/uploads/" . QUIZMAKER_DIRNAME;
    }else{
        $ret = "/modules/" . QUIZMAKER_DIRNAME;
    }
    
    if($subfolder){
        $ret .= "/" . $subfolder; 
    }
    
    if($addEndSlash){
        $ret .= "/"; 
    }
    
    if (strtolower($root) == "u"){
        $ret = XOOPS_URL .  str_replace('//', '/' , $ret);
    }else{
        $ret = XOOPS_ROOT_PATH .  str_replace('//', '/' , $ret);
    }
    
    return $ret;

}
 
/**
 * function add selected cats to block
 *
 * @param  $cats 
 * @return string
 */
function sanityse_inpValue($exp)
 {
    $exp = trim($exp);
    $exp = str_replace("\t", ' ', $exp);
    $exp = str_replace("\n", ' ', $exp);
    $exp = str_replace("\r", ' ', $exp);
    $exp = str_replace("  ", ' ', $exp);
    return $exp;
 }
 
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
function getStyle($background='', $foreColor='', $addStyleAtt= true)
{
    $style = '';
    if ($background) $style .= "background:{$background};";
    if ($foreColor) $style .= "color:{$foreColor};";
    
    if($addStyleAtt){
        return " style='" . $style . "'";
    }else{
        return $style;  
    }
}
function getMsgStyle($msg, $style, ...$arg)
{
//echoArray($arg);exit;
    $newMsg = sprintf($msg, $arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
    
    switch(strtolower($style)){
        case 'red'   : $ret = "<span style='color : Red;'>{$newMsg}</span>"; break;
        case 'bred'  : $ret = "<b><span style='color : Red;'>{$newMsg}</span></b>"; break;
        case 'blue'  : $ret = "<span style='color : blue;'>{$newMsg}</span>"; break;
        case 'bblue' : $ret = "<b><span style='color : blue;'>{$newMsg}</span></b>"; break;
        case 'green' : $ret = "<span style='color : green;'>{$newMsg}</span>"; break;
        case 'bgreen': $ret = "<b><span style='color : green;'>{$newMsg}</span></b>"; break;
        case 'b'     : $ret = "<b>{$newMsg}"; break;
        default      : $ret = "<b><span style='color : green;'>{$newMsg}</span></b>"; break;
    }
    return $ret;
}

/* ***********

************** */
function getNextMessagesgArr($prefix){
     $lib = array();
      //ajout des libellés prédéfinis pour tous plugins
      $prefix = strtoupper($prefix) . '_';
      $h = 0;
      while (defined($prefix . $h)){
        $lib[] = htmlentities(constant($prefix . $h), ENT_QUOTES);
        $h++;
      }
//echoArray($lib, "getNextMessagesgArr");
    return $lib;
}      
/* ***********

function getNextMessagesgArr_old($prefixPredefinis, $prefixPlugin = ''){
     $lib = array();
      //ajout des libellés prédéfinis pour tous plugins
      if($prefixPredefinis){
          $h = 0;
          while (defined($prefixPredefinis . $h)){
            $lib[] = htmlentities(constant($prefixPredefinis . $h), ENT_QUOTES);
            echo "===>Const prefixPlugin = " . ($prefixPredefinis . $h) . "<br>";
            $h++;
          }
      }
      
      //ajout des libellés prédéfinis spécifiques au plugin
      if($prefixPlugin){
          $h = 0;
          while (defined($prefixPlugin . $h)){
            $lib[] = htmlentities(constant($prefixPlugin . $h), ENT_QUOTES);
            echo "===>Const prefixPlugin = " . ($prefixPlugin . $h) . "<br>";
            $h++;
          }
      }

    return $lib;
}      
************** */

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
function canYouPlayQuiz ($quizId, $MaxAttempt){
global $xoopsUser, $quizHandler, $resultsµHandler;  
$uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
    $ip = \Xmf\IPAddress::fromRequest()->asReadable();
            
    $criteria = new \CriteriaCompo(new \Citeria('result_quiz_id', $quizId, "="));
    $criteria->add(new \Citeria('result_uid', $uid, "="));
    $criteria->add(new \Citeria('result_ip', $ip, "="));

    return true; //provisoir    
}
/**********************************************************************
 * getParamsForQuiz : renvoi une chaine de parametre pour personaliser le quiz
 * Tout n'est pas utile uname et name sont probablement suffisant, a voir
 **********************************************************************/
function getParamsForQuiz ($asString = false, $resultId=0, $playerId = 0)
{
global $xoopsUser;
        xoops_load('XoopsUserUtility');
        $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;  
    if(is_object($xoopsUser)){
        
        $allParams = array('uid'  => $uid,
        'uname' => $xoopsUser->getVar('uname', 'e'),
        'name' => $xoopsUser->getVar('name', 'e'),
        'email' => $xoopsUser->getVar('email', 'e'),
        'ip'   => \XoopsUserUtility::getIP(true));
    }else{
        //$currentuid = 2;      
        $allParams = array('uid' => $uid,
        'uname' => 'Anonymous',
        'name' => 'Anonymous',
        'email' => 'anonymous@orange.fr',
        'ip'   => \XoopsUserUtility::getIP(true));
    }     
    $allParams['resultId'] = $resultId;   
    $allParams['player_id'] = $playerId;   
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
QUIZMAKER_BIT_START_BUTTON      => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_START_BUTTON, _AM_QUIZMAKER_QUIZ_START_BUTTON_DESC),
QUIZMAKER_BIT_SUBMIT_BUTTON     => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SUBMIT_BUTTON, _AM_QUIZMAKER_QUIZ_SUBMIT_BUTTON_DESC),
QUIZMAKER_BIT_SHOW_HORLOGE      => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_HORLOGE, _AM_QUIZMAKER_QUIZ_SHOW_HORLOGE_DESC),
QUIZMAKER_BIT_SHOW_SCOREMINMAX  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_SCORE_MIN_MAX, _AM_QUIZMAKER_QUIZ_SHOW_SCORE_MIN_MAX_DESC),
QUIZMAKER_BIT_SHOW_ALLSOLUTIONS => sprintf("%s (%s)", _AM_QUIZMAKER_VIEW_ALL_SOLUTIONS, _AM_QUIZMAKER_SHOW_ALL_SOLUTIONS_DESC),
QUIZMAKER_BIT_SHOW_SLIDEBAR     => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_SLIDEBAR, _AM_QUIZMAKER_QUIZ_SHOW_SLIDEBAR_DESC),
QUIZMAKER_BIT_ALLOWEDPREVIOUS   => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_ALLOWED_PREVIOUS, _AM_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS_DESC),
QUIZMAKER_BIT_USETIMER          => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_USE_TIMER, _AM_QUIZMAKER_QUIZ_USE_TIMER_DESC),
QUIZMAKER_BIT_SHUFFLEQUESTIONS  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION, _AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION_DESC),
QUIZMAKER_BIT_SHOW_RESULTPOPUP  => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_RESULT_POPUP, _AM_QUIZMAKER_QUIZ_RESULT_POPUP_DESC),
QUIZMAKER_BIT_REPOSITION_WINDOW => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_REPOSITIONE_WINDOWS, _AM_QUIZMAKER_QUIZ_REPOSITIONE_WINDOWS_DESC),
QUIZMAKER_BIT_HIDE_INTERFACE    => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_HIDE_INTERFACE, _AM_QUIZMAKER_QUIZ_HIDE_INTERFACE_DESC),
QUIZMAKER_BIT_FULL_SCREEN       => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_FULL_SCREEN, _AM_QUIZMAKER_QUIZ_FULL_SCREEN_DESC));
        break;

    case 'dev':
$arr = array(
QUIZMAKER_BIT_SHOW_PLUGIN           => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_PLUGIN, _AM_QUIZMAKER_QUIZ_SHOW_PLUGIN_DESC),
QUIZMAKER_BIT_SHOW_RELOADANSWERS    => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_GOTOSLIDE        => sprintf("%s (%s)", _AM_QUIZMAKER_SHOW_BTN_GOTO_SLIDE, _AM_QUIZMAKER_SHOW_BTN_GOTO_PLUGIN_DESC),
QUIZMAKER_BIT_SHOW_GOODANSWERS      => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_BADANSWERS       => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS, _AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS_DESC),
QUIZMAKER_BIT_SHOW_CHEATER          => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_CHEATER, _AM_QUIZMAKER_QUIZ_SHOW_CHEATER_DESC),
QUIZMAKER_BIT_SHOW_MODE_NORMAL      => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_MODE_NORMAL, _AM_QUIZMAKER_QUIZ_SHOW_MODE_NORMAL_DESC),
QUIZMAKER_BIT_SHOW_LOG              => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_LOG, _AM_QUIZMAKER_QUIZ_SHOW_LOG_DESC),
QUIZMAKER_BIT_SHOW_RESULTALLWAYS    => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_RESULT_ALLWAYS, _AM_QUIZMAKER_QUIZ_SHOW_REPONSES),
QUIZMAKER_BIT_SHOW_REPONSESBOTTOM   => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_REPONSES_BOTTOM, _AM_QUIZMAKER_QUIZ_SHOW_REPONSES_BOTTOM_DESC),
QUIZMAKER_BIT_SHOW_RIGHT_CLICK_MENU => sprintf("%s (%s)", _AM_QUIZMAKER_QUIZ_SHOW_SHOW_RIGHT_CLICK_MENU, _AM_QUIZMAKER_QUIZ_SHOW_SHOW_RIGHT_CLICK_MENU_DESC));
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
        $flags['startBtnPosition']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_START_BUTTON, $binOptions), "Run"); 
        $flags['submitBtnPosition'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SUBMIT_BUTTON, $binOptions), "Sub"); 
        $flags['showScoreMinMax']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_SCOREMINMAX, $binOptions), "Smm"); 
        $flags['showAllSolutions']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_ALLSOLUTIONS, $binOptions), "Vas"); 
        $flags['showSlideBar']      = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_SLIDEBAR, $binOptions), "Sb");
        $flags['allowedPrevious']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_ALLOWEDPREVIOUS, $binOptions), "Pr"); 
        $flags['useTimer']          = quizFlagAscii(isBitOk(QUIZMAKER_BIT_USETIMER, $binOptions), "T");        
        $flags['shuffleQuestions']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHUFFLEQUESTIONS, $binOptions), "M"); 
        $flags['showResultPopup']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTPOPUP, $binOptions), "PU");
        $flags['repositionWindow']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTPOPUP, $binOptions), "wt");
        $flags['hideInterface']     = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTPOPUP, $binOptions), "hi");
        $flags['fullScreen']        = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTPOPUP, $binOptions), "fs");
        break;
        
    case 'dev':
        $flags['showPlugin']  = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_PLUGIN, $binOptions), "TQ");
        $flags['showReloadAnswers'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RELOADANSWERS, $binOptions), "Rl");
        $flags['showGoToSlide']     = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_GOTOSLIDE, $binOptions), "Go");
        $flags['showGoodAnswers']   = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_GOODANSWERS, $binOptions), "Ga"); 
        $flags['showBadAnswers']    = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_BADANSWERS, $binOptions), "Ba"); 
        $flags['showLog']           = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_LOG, $binOptions), "Log"); 
        $flags['showResultAllways'] = quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RESULTALLWAYS, $binOptions), "Ra"); 
        $flags['showReponsesBottom']= quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_REPONSESBOTTOM, $binOptions), "Rb"); 
        $flags['showReponsesBottom']= quizFlagAscii(isBitOk(QUIZMAKER_BIT_SHOW_RIGHT_CLICK_MENU, $binOptions), "Rcm"); 
        break;
    }

    return $flags;
}

/**********************************************************************
*
* **********************************************************************/
function getArray($list){
    $list = strtolower($list);
    switch($list){
        case 'typeinput':
            return array(0 => _LG_PLUGIN_MATCHITEMS_LABEL,
                         1 => _LG_PLUGIN_MATCHITEMS_LISTBOX, 
                         2 => _LG_PLUGIN_MATCHITEMS_TEXTBOX,
                         3 => _LG_PLUGIN_MATCHITEMS_CONJONCTION);
            break;
            
        case 'posv':
            return array ('left'   => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_LEFT,
                          'center' => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_CENTER,
                          'right'  => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_DROITE);  
            break;
        case 'posv':
            return array ('Top'    => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_LEFT,
                          'middle' => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_CENTER,
                          'bottom' => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_DROITE);  
            break;
        default:
            break;
            
    }
    


}

/* *************************************************

*************************************************** */
function save_images2($formName, $path, $optionsArr, &$nameOrg=''){
    if(!$_POST['xoops_upload_file']) return false;    
    if(!$_FILES[$formName]['name']) return '';
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    $prefix = (isset($optionsArr['prefix'])) ? $optionsArr['prefix'] : '';
    $renameImage = (isset($optionsArr['renameImage'])) ? $optionsArr['renameImage'] : false;

    $nameOrg = '';
    $keyFile = array_search($formName, $_POST['xoops_upload_file']);    
    $savedFilename = '';
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , $optionsArr['mimetypes_image'], $optionsArr['maxsize_image'], null, null);


    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile])) {

        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile]);
        if (!$uploader->upload()) {
            $uploaderErrors = $uploader->getErrors();
        } else {
            $savedFilename = $uploader->getSavedFileName();

            $nameOrg = $_FILES[$_POST['xoops_upload_file'][$keyFile]]['name'];       
            if($this->renameImage){
                //echo "===>savedFilename : {$savedFilename}<br>";  
                //modification du nom pour les repérer dans le dossier   
                $newName = $prefix . '-' . sanitiseFileName($nameOrg);
                rename($path.'/'. $savedFilename,  $path.'/' . $newName);
                $savedFilename = $newName;
            }
            //retir l'extension et remplace les _ par des espaces
            $h= strrpos($nameOrg,'.');
            $i=0;
            $nameOrg = str_replace('_', ' ', substr($nameOrg, $i, $h));

        }


    } else {
        //if ($filename > '') {
            $uploaderErrors = $uploader->getErrors();
        //}
        // il faut garder l'image existante si il n'y a pas eu de nouvelle selection
        // ou l'image sélectionée dans la liste
        //$slidesObj->setVar('sld_image', Request::getString('sld_image'));
        $savedFilename = '';
    }
    //exit ($savedFilename);
    return $savedFilename;
}
/* ************************************************
*
* ************************************************* */
function sanitiseFileName($str, $replaceBlankBy = '_'){
    $str = utf8_decode($str);
    $str = str_replace(
			array(
				'à', 'â', 'ä', 'á', 'ã', 'å',
				'î', 'ï', 'ì', 'í', 
				'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
				'ù', 'û', 'ü', 'ú', 
				'é', 'è', 'ê', 'ë', 
				'ç', 'ÿ', 'ñ',
				'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
				'Î', 'Ï', 'Ì', 'Í', 
				'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø', 
				'Ù', 'Û', 'Ü', 'Ú', 
				'É', 'È', 'Ê', 'Ë', 
				'Ç', 'Ÿ', 'Ñ'
			),
			array(
				'a', 'a', 'a', 'a', 'a', 'a', 
				'i', 'i', 'i', 'i', 
				'o', 'o', 'o', 'o', 'o', 'o', 
				'u', 'u', 'u', 'u', 
				'e', 'e', 'e', 'e', 
				'c', 'y', 'n', 
				'A', 'A', 'A', 'A', 'A', 'A', 
				'I', 'I', 'I', 'I', 
				'O', 'O', 'O', 'O', 'O', 'O', 
				'U', 'U', 'U', 'U', 
				'E', 'E', 'E', 'E', 
				'C', 'Y', 'N'
			),$str);
  
   if ($replaceBlankBy) $str = strtr($str," ", $replaceBlankBy);

return $str;
}

/* ************************************************
*
* ************************************************* */
function addXoopsFormTray(&$xtray, $caption, $formsArr, $sep = '&nbsp;-&nbsp;'){
    $inpTray = new \XoopsFormElementTray($caption, $sep);
    foreach($formsArr as $key=>$form){
        $inpTray->addElement($form);
    }
    $xtray->addElementOption($inpTray);
    return true;
}

/****************************************************************************
 * getSelector ===> Listes de selection pour filtrage des questions
 * $quizId int : id de la categorie
 * $quizSubject string : subject de quiz
 * $quizId int : id du quiz
 * retour arr renvoie un tableau des liste de sélection pour l'interface:
 ****************************************************************************/
// function getQuizSelector($catId, $quizSubject, $quizDifficulty, 
//                          $asObject=false, $prefixName = '', $addCaption = true, 
//                          $inBackOffice = false){
//                          
//     return getSelector('quiz', $catId, $quizSubject, $quizDifficulty, 0, 
//                        $asObject, $prefixName, $addCaption, $inBackOffice);
// }
// 
// function getQuestionsSelector($catId, $quizSubject, $quizDifficulty, $quizId, 
//                        $asObject=false, $prefixName = '', $addCaption = true, $allQuiz=false, 
//                        $inBackOffice = false)){
//                        
//     return getSelector('question', $catId, $quizSubject, $quizDifficulty, $quizId, 
//                        $asObject, $prefixName, $addCaption, $allQuiz, $inBackOffice);
// } 
///////////////////////////////// 
function getQuizSelectorBO($catId, $quizSubject, $quizDifficulty, 
                         $asObject=false, $prefixName = '', $addCaption = true){
                         
    $inBackOffice = true;
    return getSelector('quiz', $catId, $quizSubject, $quizDifficulty, 0, 
                       $asObject, $prefixName, $addCaption, null,  $inBackOffice);
}
/* ******************************************
*
* ******************************************* */
function getQuestionsSelectorBO($catId, $quizSubject, $quizDifficulty, $quizId, 
                       $asObject=false, $prefixName = '', $addCaption = true, $allQuiz=false){
                       
    $inBackOffice = true;
    return getSelector('question', $catId, $quizSubject, $quizDifficulty, $quizId, 
                       $asObject, $prefixName, $addCaption, $allQuiz, $inBackOffice);
} 

/* ******************************************
*
* ******************************************* */
function getQuizSelectorFO($catId, $quizSubject, $quizDifficulty, 
                         $asObject=false, $prefixName = '', $addCaption = true){

    $inBackOffice = false;
    return getSelector('quiz', $catId, $quizSubject, $quizDifficulty, 0, 
                       $asObject, $prefixName, $addCaption, $inBackOffice);
}

/* ******************************************
*
* ******************************************* */
function getQuestionsSelectorFO($catId, $quizSubject, $quizDifficulty, $quizId, 
                       $asObject=false, $prefixName = '', $addCaption = true, $allQuiz=false){

    $inBackOffice = false;
    return getSelector('question', $catId, $quizSubject, $quizDifficulty, $quizId, 
                       $asObject, $prefixName, $addCaption, $allQuiz, $inBackOffice);
} 
/****************************************************************************
 * getSelector ===> Listes de selection pour filtrage des questions
 * $quizId int : id de la categorie
 * $quizSubject string : subject de quiz
 * $quizId int : id du quiz
 * retour arr renvoie un tableau des liste de sélection pour l'interface:
 ****************************************************************************/

function getSelector($domaine, $catId, $quizSubject, $quizDifficulty, $quizId=0, 
                     $asObject=false, $prefixName = '', $addCaption = true, $allQuiz=false,
                     $inBackOffice = false){
global $categoriesHandler, $quizHandler, $clPerms;
//     if(!isset(formOptions['formName']) formOptions['formName'] = 'quizmaker_select_filter';
//     if(!isset(formOptions['formName']) formOptions['formName'] = 'quizmaker_select_filter';
  $bolUnset = false;
    $selectors = array();
    $sep = ' : ';
    //$event = 'onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"  style="display:inline;width:auto;"';
    //$event = QUIZMAKER_SELECT_ONCHANGE 
    //$event = 'onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"';
    $event = "onchange='document.quizmaker_select_filter.submit();'";
    //   document.getElementById('quizmaker_select_filter')
    //$event = 'onchange="alert(`zzzzzzzzzzz`);"';
    $style = 'style="display:inline;width:auto;"';    

    //------ selection du sujet de la categorie -----
    $name = 'cat';
    $field = 'cat_id';
    $clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
    $selectors[$name]['arr'] = $categoriesHandler->getList($criteriaCatAllowed);
    if ($catId == 0) $catId = array_key_first($selectors[$name]['arr']);
    
    $selectors[$name]['value'] = $catId;
    
//echoArray($selectors[$name]['arr']);
    $inpCategory = new \XoopsFormSelect(_CO_QUIZMAKER_CATEGORIES, $prefixName . $field, $catId);
    $inpCategory->setExtra($event);   
    if($inBackOffice) $inpCategory->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));        
    
    $inpCategory->addOptionArray($selectors[$name]['arr']);
    $inpCategory->setExtra($event);
    $inpCategory->setExtra($style);

    if($asObject) 
        $selectors[$name]['select'] =  $inpCategory;
    else
        $selectors[$name]['select'] = (($addCaption) ? _CO_QUIZMAKER_CATEGORIES . $sep : '') . $inpCategory->render();
    if($bolUnset) unset($selectors[$name]['arr']);  




    //------ selection du sujet de sujet -----
    $name = 'subject';
    $field = 'quiz_subject';
    $selectors[$name]['value'] = $quizSubject;
    $selectors[$name]['arr'] =  $quizHandler->getFieldList($field, $catId);
    if(count($selectors[$name]['arr']) > 1){
        $inpSet = new \XoopsFormSelect(_CO_QUIZMAKER_QUIZ_SUBJECT,  $prefixName . $field, $quizSubject);
        $inpSet->setExtra($event);   
        if($inBackOffice) $inpSet->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));        
        
        $inpSet->addOption(QUIZMAKER_ALL_ITEMS_KEY, QUIZMAKER_ALL_ITEMS_LIB);
        $inpSet->addOptionArray($selectors[$name]['arr']);
        $inpSet->setExtra($event);
        $inpSet->setExtra($style);
        
        if($asObject) 
            $selectors[$name]['select'] = $inpSet;
        else
            $selectors[$name]['select']= (($addCaption) ? _CO_QUIZMAKER_QUIZ_SUBJECT . $sep : '') . $inpSet->render();

    }else{
        $selectors[$name] ['select']= '';
   }
    if($bolUnset) unset($selectors[$name]['arr']);  
          
    //------ selection de la difficulté -----
    $name = 'difficulty';
    $field = 'quiz_difficulty';
    $selectors[$name]['value'] = $quizDifficulty;
    $selectors[$name]['arr'] =  $quizHandler->getFieldList($field, $catId);
    //exit("nb difficultés = " . count($selectors[$name]['arr']));
    if(count($selectors[$name]['arr']) > 1){
        if(QUIZMAKER_SELECTOR_DIFFICUT_MODE == 1){
          $inpDifficulty = new \XoopsFormSelect(_CO_QUIZMAKER_DIFFICULT,  $prefixName . $field, $quizDifficulty);
          $inpDifficulty->setExtra($event);   
          if($inBackOffice) $inpDifficulty->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));        

        }else{
          $inpDifficulty = new \XoopsFormRadio(_CO_QUIZMAKER_DIFFICULT,  $prefixName . $field, $quizDifficulty);
        }
		$inpDifficulty->addOption(0, QUIZMAKER_ALL_ITEMS_LIB); //_CO_QUIZMAKER_DIFFICULT_ALL
		$inpDifficulty->addOption(1, _CO_QUIZMAKER_DIFFICULT_1);
		$inpDifficulty->addOption(2, _CO_QUIZMAKER_DIFFICULT_2);
		$inpDifficulty->addOption(3, _CO_QUIZMAKER_DIFFICULT_3);
		$inpDifficulty->addOption(4, _CO_QUIZMAKER_DIFFICULT_4);
        $inpDifficulty->setExtra($event);
        $inpDifficulty->setExtra($style);
        
        if($asObject) 
            $selectors[$name]['select'] = $inpDifficulty;
        else
            $selectors[$name]['select'] = (($addCaption) ? _CO_QUIZMAKER_DIFFICULT . $sep : '') . $inpDifficulty->render();

    }else{
        $selectors[$name]['select'] = '';
   }
   if($bolUnset) unset($selectors[$name]['arr']);  

    //------ selection du quiz -----
    if($domaine == 'question'){
        $name = 'quiz';
        $field = 'quiz_id';
        $selectors[$name]['value'] = $quizId;
        
        $selectors[$name]['arr'] = $quizHandler->getListKeyName($catId, $quizSubject, $quizDifficulty); 
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ_NAME, $prefixName . $field, $quizId);
        $inpQuiz->setExtra($event);   
        if($inBackOffice) $inpQuiz->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUIZ));        
        
        if($allQuiz){
            $inpQuiz->addOption(0, QUIZMAKER_ALL_ITEMS_LIB); //QUIZMAKER_ALL
        }
        $inpQuiz->addOptionArray($selectors[$name]['arr']);
        $inpQuiz->setExtra($event);
        $inpQuiz->setExtra($style);
        if($asObject) 
            $selectors[$name] ['select']= $inpQuiz;
        else
            $selectors[$name]['select'] = (($addCaption) ? _AM_QUIZMAKER_QUIZ_NAME . $sep : '') . $inpQuiz->render();
    }
    
    
    //-------------------------------------------------------------------
    
    
// echoArray($selectors);
// echo "difficulté = {$quizDifficulty}<hr>";
    return $selectors;
}

/* *************************************************
*
* ************************************************** */
function getNewIdentifiant($prefixe='slide', $min=10000, $max=100000){
    return $prefixe . '_' . rand($min,$max);
}

/* *******************************
* @catObj objet - categorie dans laquel on verifie le champ "cat_readme_status"
* @readmeOk : valeur renvoyée par le GET ou le POST qui permet de savoir si "cat_readme_status" a etét lu ou non
* "cat_readme_status" peut avoir 3 valeur :
* 0 : pas de lecture de "cat_readme_text et on passe directement aui quiz ou à la catégorie
* 1 : l'utilisateur doit lire au moins une fois "cat_readme_text" avant de valider et passer au quiz
* 2 : "cat_readme_text" doit ête lu vant chaque lancement d'un quiz de la catégorie
* cette fonctionalité permet par exemple de valider un règlement de concours
******************************** */
function isReadme($catObj, $readmeOk){
    global $xoopsUser, $readmeHandler;
    $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;    
    $readme = false;    
    $catId = $catObj->getVar('cat_id');
    $readmeStatus = $catObj->getVar('cat_readme_status');
    
    if($readmeStatus > 0){
        $readmeCount =  $readmeHandler->getReadmeCount($catId, $uid);
        if($readmeStatus == 1 && $readmeCount == 0) {
            $readme = true;
        }else if($readmeStatus == 2){
            $readme = true;
            }
    }
    //echo "1-readmeStatus = {$readmeStatus}<br>readmeOk = {$readmeOk}<br>readmeCount = {$readmeCount}<br>readme = " . (($readme) ? 'true': 'false'). "<br>";

    return $readme && $readmeOk == 0;
}

//-----------------------------------------------
/*
function exportUsersCSV($quizId){
SELECT`uid`, `name`, `uname`, `email`, `url`, 
DATE_FORMAT(FROM_UNIXTIME(user_regdate), '%d/%m/%Y') as creation
DATE_FORMAT(FROM_UNIXTIME(last_login), '%d/%m/%Y') as lastUpdate
FROM `x251_users` WHERE 1

unix_timestamp()

// Sélectionner les données de la table
global $resultsHandler;

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
    $userHandler = new XoopsUserHandler($db);
    
    $users = $userHandler->getObjects($criteria, true);
    $ret   = array();
    
    
    $delimiter = ";";   
    $filename = "xoops_users-" . date('Y-m-d') . ".csv";    
    $fullName = QUIZMAKER_PATH_UPLOAD_EXPORT . "/" . $filename;    
    $f = fopen($fullName, 'w');
    // Définir les entêtes du fichier CSV
    $fields = array('uid','uname','name','email','last_login',);

    foreach (array_keys($users) as $i) {
        $lineData = array();
        $lineData[] = $users[$i]->getVar('uid');
        $lineData[] = $users[$i]->getVar('uname');
        $lineData[] = $users[$i]->getVar('name');
        $lineData[] = $users[$i]->getVar('email');
        $lineData[] = $users[$i]->getVar('last_login');
        
        fputcsv($f, $lineData, $delimiter);
    }
    
    fclose($f);
    return $fullName;
}  
*/
  
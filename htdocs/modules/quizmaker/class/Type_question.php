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
 * QuizMaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker;
//echo "<hr>class : Type_question<hr>";
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Type_question   //extends \XoopsObject
 */
class Type_question 
{
var $questId = 0;
var $type = '';
var $name = '';
var $description = '';
var $image_fullName = '';
var $lgTitle = 80;
var $lgProposition = 80;
var $lgProposition2 = 80;
var $lgPoints = 5;
var $lgMot1 = 20;
var $lgMot2 = 50;

var $trayGlobal; 
var $maxPropositions = 12; // valeur par default

	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct($typeQuestion, $parentId = 0)
	{
        $questId = $parentId;
        $prefix = '_CO_QUIZMAKER_TYPE_';
        $this->type = $typeQuestion;
        $this->name = constant($prefix . strToUpper($typeQuestion));
        $this->description = constant($prefix . strToUpper($typeQuestion) . '_DESC');
	}

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function initFormForQuestion()
	{
        $this->trayGlobal = new \XoopsFormElementTray  (_AM_QUIZMAKER_PROPOSITIONS, $delimeter = '<hr>'); 
        $this->trayGlobal->addElement(new \XoopsFormLabel('', $this->name));
        $this->trayGlobal->addElement(new \XoopsFormLabel('', $this->description));
        $this->trayGlobal->addElement($this->getInpHelp());

//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
    
	}

	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesType_question()
	{
		$quizHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
        
        $ret = array();
        $ret['type'] = $this->type;
        $ret['name'] = $this->name;
        $ret['description'] = $this->description;
        $ret['image_fullName'] = QUIZMAKER_MODELES_IMG . "/slide_" . $this->type . '-00.jpg';
        
        return $ret;
	}
    
/* **********************************************************
*
* *********************************************************** */
public function echoAns ($answers, $questId, $bExit = true) {
    
    echo "<hr>Question questId = {$questId}<pre>" . print_r($answers, true) . "</pre><hr>";
    if ($bExit) exit;         
}



/* **********************************************************
*
* *********************************************************** */
	public function getformTextarea($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizHelper;
        return \JJD\getformTextarea($caption, $name, $value, $description, $rows, $cols);
}       
        
/* **********************************************************
*
* *********************************************************** */
	public function getformAdmin($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $quizHelper;
        return \JJD\getAdminEditor($quizHelper, $caption, $name, $value);
}       
        


/* **********************************************************
*
* *********************************************************** */
 	public function getName()
 	{

    $numargs = func_num_args();
    $arg_list = func_get_args();
    
    switch($numargs){
        case 1: 
            return sprintf("answers[%s]",  $arg_list[0]);
            break;
        case 2: 
            return sprintf("answers[%s][%s]",  $arg_list[0], $arg_list[1]);
            break;
        case 3: 
            return sprintf("answers[%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2]);
            break;
        case 4: 
            return sprintf("answers[%s][%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3]);
            break;
        default: 
            return "answers";
            break;
    }
    
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $name, $value = "")
 	{
        return null;
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function isQuestion()
 	{
    //echo ($this->type != "pageInfo") ? "<hr>c'est une question<hr>" : "<hr>Non pas une question<hr>" ;
        return ($this->type != "pageInfo");
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function isParent()
 	{
        return false;
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormType_question($typeQuestion)
 	{
//      global $utility, $answersHandler;
//         global $quizHandler, $utility, $type_questionHandler;
//         //---------------------------------------------- 
// 		$quizHelper = \XoopsModules\Quizmaker\Helper::getInstance();
// 		if (false === $action) {
// 			$action = $_SERVER['REQUEST_URI'];
// 		}else{
//             $h = strpos( $_SERVER['REQUEST_URI'], "?");
// 			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
// 			//$action = "questions.php";
// 			//$action = "modules/quizmaker/admin/questions.php";
//         }
// //         echo "<br>Action : {$action}<br>";
// // 		exit;
//         $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
// 		// Permissions for uploader
// 		$grouppermHandler = xoops_getHandler('groupperm');
// 		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
// 		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
//         //=================================================
//         // recupe de la classe du type de question
//         $clTypeQuestion = $this->getTypeQuestion();
//         
//         //===========================================================        
// 		// Title
// 		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_QUESTIONS_ADD) : sprintf(_AM_QUIZMAKER_QUESTIONS_EDIT);
// 		// Get Theme Form
// 		xoops_load('XoopsFormLoader');
// 		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
// 		$form->setExtra('enctype="multipart/form-data"');
// 		// Questions Handler
//         //----------------------------------------------------------
// 		// Questions Handler
// 		$questionsHandler = $quizHelper->getHandler('Questions');
// 		// Form Select questQuiz_id
// 		$questQuiz_idSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
// 		$questQuiz_idSelect->addOption('Empty');
// 		$questQuiz_idSelect->addOptionArray($quizHandler->getListKeyName());
//         $typeQuestion = $this->getVar('quest_type_question');
//         //----------------------------------------------------------
//         /*
// 		// Form Select quest_parent_id
// 		$inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_PARENT, 'quest_parent_id', $this->getVar('quest_parent_id'));
// 		//$inpParent->addOption('Empty');
// 		$inpParent->addOptionArray($questionsHandler->getParents());
//         $form->addElement($inpParent);
//         */
//         //----------------------------------------------------------
// 		// Form Select questType_question
// 		$questType_questionSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_TYPE_QUESTION, 'quest_type_question', $typeQuestion);
// 		$questType_questionSelect->addOption('Empty');
// 		//$questType_questionSelect->addOptionArray($questionsHandler->getListKeyName());
// 		$questType_questionSelect->addOptionArray($type_questionHandler->getListKeyName());
//         
//         //================================================
// 		// To Save
//         $form->insertBreak("<div style='background:black;color:white;'><center>-----</center></div>");
// 		$form->addElement(new \XoopsFormHidden('op', 'save'));
// 		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
// 		return $form;
//      

	}
/* ********************************************
*
*********************************************** */
 	public function saveAnswers($questId, $answers)
 	{global $utility, $answersHandler, $type_questionHandler;
   
    }
    
/* ********************************************
*
*********************************************** */
 	public function getInpHelp()
 	{global $xoopsConfig, $utility;
        //conteneur pour l'aide et les images
        $trayHelp = new \XoopsFormElementTray  ('', $delimeter = '');          
        //-------------------------------------------
        $fldSlideHelp = "slide_help";
        $language = $xoopsConfig['language'] ;
        $langDir = QUIZMAKER_LANGUAGE ."/{$language}/{$fldSlideHelp}";
        //echo "<hr>getInpHelp<br>{$langDir}<hr>";       
        if (!is_dir($langDir)) 
            $langDir = QUIZMAKER_LANGUAGE . "/english/{$fldSlideHelp}";
        
        $f = $langDir . "/slide_{$this->type}.html";
        //echo "<hr>getInpHelp<br>{$f}<hr>";       
        $help = \JJD\FSO\loadtextFile($f);
        $help = utf8_encode($help);
        $inpHelp = new \XoopsFormLabel  ('', $help);
        //ajout du texte dans le conteneur
        $trayHelp->addElement($inpHelp);
        
        //----------------------------------------------   
/*
        // --- Ajout de la copie d'écran du slide
        $url =  QUIZMAKER_QUIZ_JS_URL . "/quiz-php/images/slide_" . $this->type . '.jpg';
        $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:40px" />
            </a>

        ___IMG___;
        $inpImg = new \XoopsFormLabel  ('', $img);  
        $inpImg->setExtra("class='highslide-gallery'");
//\JJD\include_highslide();       
*/        
        //--------------------------------
        //$inpSnapShoot = new \XoopsFormLabel  ('', 'fgdfhghk');

        $h=0;
        $tHtml = array();
        while (true){
            //$h++;
            $f =  "/modeles/slide_" . $this->type . sprintf("-%02d", $h++) . '.jpg';
            if (!file_exists(QUIZMAKER_IMAGE_PATH . $f)) break;
                $url =  QUIZMAKER_IMAGE_URL . $f;
                $img =  <<<___IMG___
                    <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                        <img src="{$url}" alt="slides" style="max-width:40px" />
                    </a>
        
                ___IMG___;
                $inpImg = new \XoopsFormLabel  ('', $img);  
                $inpImg->setExtra("class='highslide-gallery'");
                $trayHelp->addElement($inpImg);
            
        }
        //----------------------------------------------        
        return $trayHelp;
    }
    
/* ********************************************
*
*********************************************** */
  public function color($exp, $color = null){
    if($color)
        return "<span style='color:{$color};'></span>";
    else
        return $exp;

}     
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId){

    $ret = array();
 
    $ret['answers'] =  _CO_QUIZMAKER_POINTS_UNDER_DEV;
    $ret['scoreMin'] = -99999;
    $ret['scoreMax'] = 99999;
    return $ret;

}     
/* ********************************************
*
*********************************************** */
  public function combineAndSorAnswer($ans, $sep=','){
    return $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
}     
/* ********************************************
*
*********************************************** */
  public function mergeAndSortArrays($exp2sort, $expLib, $sep=','){
    $arr2sort = explode($sep, $exp2sort);
    $arrExp   =  explode($sep, $expLib);
    $ret = array();
    foreach ($arr2sort as $i=>$v){
        $key = 'p=' . str_pad($v, 3, '0', STR_PAD_LEFT);
        $ret[$key]['points'] = $v;
        $ret[$key]['exp'] = $arrExp[$i];
//         $ret[$key] = $arrExp[$i];
    }
    
//    echoArray($ret);
    krsort($ret);
//    echoArray($ret);
    
    return $ret;
}     

} // fin de la classe

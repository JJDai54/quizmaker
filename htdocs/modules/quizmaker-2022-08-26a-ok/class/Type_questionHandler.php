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


/**
 * Class Object Handler Type_question
 */
class Type_questionHandler 
{
    var $dirname = QUIZMAKER_PATH . "/quiz/quiz-php/class";
    var $name = '';
    var $description= '';
    
        
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct()
	{

	}


	/**
	 * Get Count Type_question in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCount()
	{
		return Count($this->getListKeyName());
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
	 * Get Count Quiz in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountQuiz()
	{
        
		return count($this->getListKeyName());
	}

	/**
	 * Get All Type_question in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAll()
	{
//$utility = new \XoopsModules\Quizmaker\Utility();
        $listTQ = $this->getListTypeQuestion();
        $ret = array();
        $utility = new \XoopsModules\Quizmaker\Utility();
        
        foreach ($listTQ as $key=>$v){
        $clsName = "slide_" . $key;        
            $tq = array();
            
            $f = QUIZMAKER_ANSWERS_CLASS . "/slide_" . $key . ".php";
            include_once($f);
            $cls = new $clsName; 
//             $tq['type'] = $key;
//             $tq['name'] = $cls->name;
//             $tq['description'] = $cls->description;
 
            $ret[] = $cls->getValuesType_question();  
//             include_once($f);
//             $obTQ = new $key();
        }
//echo "<hr>type_questions<pre>" . print_r($ret, true) . "</pre><hr>";        
        return $ret;
	}

/* ***********************

************************** */
public function getListTypeQuestion(){
global $utility;
    $dirname = QUIZMAKER_ANSWERS_CLASS; 
    $extensions = array("php");
    $prefix = "slide_";
    return \JJD\FSO\getFilePrefixedBy($dirname, $extensions, $prefix, false, true);     
}
/* ***********************

************************** */
public function getListKeyName(){
        $listTQ = $this->getListTypeQuestion();
        $ret = array();
        $utility = new \XoopsModules\Quizmaker\Utility();
        
        foreach ($listTQ as $key=>$v){
        $clsName = "slide_" . $key;        
            $f = QUIZMAKER_ANSWERS_CLASS . "/slide_" . $key . ".php";
            include_once($f);
            $cls = new $clsName; 
            $ret[$cls->type] = $cls->name;  
        }
//echo "<hr>type_questions<pre>" . print_r($ret, true) . "</pre><hr>";        
        return $ret;

}
/****************************************************************************
 * 
 ****************************************************************************/

public function getClassTypeQuestion($typeQuestion){
    $clsName = "slide_" . $typeQuestion;   
    $f = QUIZMAKER_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
    include_once($f);    
    $cls = new $clsName; 
    return $cls;

}

/* **********************************************************
*
* *********************************************************** */
 	public function getFormType_question($typeQuestion, $quizId = 0)
 	{
     global $utility, $categoriesHandler, $quizHandler, $type_questionHandler, $quizUtility;
        //---------------------------------------------- 
		$helper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}else{
            $h = strpos( $_SERVER['REQUEST_URI'], "?");
			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
			//$action = "questions.php";
			//$action = "modules/quizmaker/admin/questions.php";
        }
//         echo "<br>Action : {$action}<br>";
// 		exit;
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
        //=================================================
        // recupe de la classe du type de question
        $clTypeQuestion = $quizUtility->getTypeQuestion($typeQuestion);
        
        //===========================================================        
		// Title
		$title = sprintf(_AM_QUIZMAKER_QUESTIONS_ADD, $clTypeQuestion->description);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Questions Handler
        //----------------------------------------------------------
		// Questions Handler
// 		$questionsHandler = $helper->getHandler('Questions');
// 		// Form Select questQuiz_id
// 		$questQuiz_idSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
// 		$questQuiz_idSelect->addOption('Empty');
// 		$questQuiz_idSelect->addOptionArray($quizHandler->getListKeyName());
//         $typeQuestion = $this->getVar('quest_type_question');
        //----------------------------------------------------------
        $catArray = $categoriesHandler->getListKeyName();
        //public function (CriteriaElement $criteria = null, $addAll=false, $addNull=false, $short_permtype = 'view')
        
        if ($quizId > 0){
            $quizObj = $quizHandler->get($quizId);
            $catId = $quizObj->getVar('quiz_cat_id');
            //$criteria = new CriteriaCompo(new \Criteria('quiz_cat_id', $catId,""));
            $quizArray = $quizHandler->getListKeyName($catId);
        }else{
            $keys = array_keys ($catArray);
            $catId = $keys[0];
            $quizArray = $quizHandler->getListKeyName($catId);
            $keys = array_keys ($catArray);
            $quizId = $keys[0];
        }

		
        // Form Select quizCat_id
		$inpCat = new \XoopsFormSelect( _AM_QUIZMAKER_CATEGORY, 'cat_id', $catId);
		$inpCat->addOptionArray($catArray);
		$form->addElement($inpCat, true);
        
        $inpQuiz = new \XoopsFormSelect(_AM_QUIZMAKER_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
  	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
		$form->addElement($inpQuiz, true);
        $cls = $type_questionHandler->getClassTypeQuestion($typeQuestion);
        
        /*
		  $inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_PARENT, 'cat_id', $quizId);
		// Form Select quest_parent_id
		//$inpParent->addOption('Empty');
		$inpParent->addOptionArray($questionsHandler->getParents());
        $form->addElement($inpParent);
        */
        //----------------------------------------------------------
		// Form Select questType_question
// 		$questType_questionSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_TYPE_QUESTION, 'quest_type_question', $typeQuestion);
// 		$questType_questionSelect->addOption('Empty');
// 		//$questType_questionSelect->addOptionArray($questionsHandler->getListKeyName());
// 		$questType_questionSelect->addOptionArray($type_questionHandler->getListKeyName());
        
        //================================================
		// To Save
        $form->insertBreak("<div style='background:black;color:white;'><center>-----</center></div>");
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
     

	}
/* ******************************
 *  getTypeQuestion : renvoie la class du type de question
 * @return : classe héritée du type de question
 * *********************** */
public function getTypeQuestion($typeQuestion, $default=null)
  {
      // recupe de la classe du type de question

      if ($typeQuestion == '') return $default;
      
      $clsName = "slide_" . $typeQuestion;   
      $f = QUIZMAKER_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
      if (file_exists($f)){
          include_once($f);    
          $cls = new $clsName; 
        return $cls;
      }
      else{
        return null;
      }
  }
        
    



} //Fin de la class

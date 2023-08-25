<?php
//namespace XoopsModules\Quizmaker;

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

use XoopsModules\Quizmaker;
include_once QUIZMAKER_PATH . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_textareaInput extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textareaInput", 0, "textarea");
        $this->optionsDefaults = ['orientation'=>'V', 'scoreByWord'=>5, 'minReponses'=>0];
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

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           
  
      $name = 'orientation';  
      $inputOrientation = new \XoopsFormRadio(_AM_QUIZMAKER_ORIENTATION, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrientation->addOption("H", _AM_QUIZMAKER_ORIENTATION_H);            
      $inputOrientation->addOption("V", _AM_QUIZMAKER_ORIENTATION_V);            
      $trayOptions->addElement($inputOrientation);     
      
//       $name = 'title';  
//       $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
      
      $name = 'scoreByWord';  
      $inpScore = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_001,  "{$optionName}[{$name}]",$this->lgPoints,  $this->lgPoints, $tValues[$name]);
      $inpScore->setMinMax(1, 20);
      $trayOptions->addElement($inpScore);     
      
      $name = 'minReponses';  
      $inpMinReponses = new XoopsFormNumber(_AM_QUIZMAKER_QUESTIONS_MINREPONSE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMinReponses->setMinMax(0, 12);
      $trayOptions->addElement($inpMinReponses);     

      return $trayOptions;
    }


/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        $trayAns = new XoopsFormElementTray  (_AM_QUIZMAKER_TEXTE, $delimeter = '<br>'); //, $name = '');
        
        $i=0; //texte à retrouver
        if (isset($answers[$i])) {
            $proposition = $answers[$i]->getVar('answer_proposition', 'e');
            $points = $answers[$i]->getVar('answer_points');
        }else{
            $proposition = '';
            $points = 12;
        }
        $inpPropo = $this->getformTextarea('', $this->getName('proposition'), $proposition,'',8);
        $trayAns->addElement($inpPropo);   
/*
        $inpPoints = new XoopsFormText(_AM_QUIZMAKER_SLIDE_001, $this->getName('points'), $this->lgPoints, $this->lgPoints, $points); 
        $trayAns->addElement($inpPoints);     
*/          
        //------------------------------------------------------------        
        
             
        $this->trayGlobal->addElement($trayAns);      
        //----------------------------------------------------------
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
		$ansObj = $answersHandler->create();
  		$ansObj->setVar('answer_quest_id', $questId);
  		$ansObj->setVar('answer_weight', 10);
  		$ansObj->setVar('answer_caption', '');
  		$ansObj->setVar('answer_points', $answers['points']);
  		$ansObj->setVar('answer_proposition', $answers['proposition']);
  		$ansObj->setVar('answer_inputs', 1);

        $ret = $answersHandler->insert($ansObj);
//     exit ("<hr>===>saveAnswers<hr>");
    }
    
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true, &$obQuestion = null){
  global $answersHandler;
    $answersAll = $answersHandler->getListByParent($questId);
//    echoArray($answersAll);
    $ret = array();
    $html = array();
    $html[] = "<table class='quizTbl'>";
    
    $ans = $answersAll[0]->getValuesAnswers();
    
//        echoArray($ans);
    $arr1= array("\n", "{", "}");
    $arr2= array("<br>",  "<b>", "</b>");
    $rep = str_replace($arr1, $arr2, $ans['proposition']);
    $html[] = "<tr><td>{$rep}</td></tr>";

    $html[] = "</table>";
    //-----------------------------------------------------
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = 0;
    $ret['scoreMax'] = $ans['points'];
    return $ret;
     }

} // fin de la classe

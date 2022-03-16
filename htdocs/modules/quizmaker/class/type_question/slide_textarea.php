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
include_once QUIZMAKER_PATH . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_textarea extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textarea");
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
 	public function getFormOptions($caption, $name, $value = "@@@@@", $lg = 12)
 	{    
       $value = trim($value);
       if ($value === '' ) $value = "@@@@@";     
      
      $input = new XoopsFormText($caption, $name, $lg, $lg, $value);;            
      $input->setDescription ('blablabla');      
            
      return $input;
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $this->maxPropositions = 1;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        
       
        //--------------------------------------------------------------------
        for($i = 0; $i < $this->maxPropositions ; $i++){
            $trayAns = new XoopsFormElementTray  ('', $delimeter = '<br>'); //, $name = '');
            if (isset($answers[$i])) {
                $proposition = $answers[$i]->getVar('answer_proposition', 'e');
                $caption = $answers[$i]->getVar('answer_caption');
                $points = $answers[$i]->getVar('answer_points');
            }else{
                $proposition = '';
                $caption = '';
                $points = 0;
            }
    
            $name = $this->getName($i, 'caption');
            $inpCaption = new XoopsFormText(_AM_QUIZMAKER_SLIDE_TITLE, $name, $this->lgProposition, $this->lgProposition, $caption);
            $trayAns->addElement($inpCaption);
            
            $name = $this->getName($i, 'points');
            $inpPoints = new XoopsFormText(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $trayAns->addElement($inpPoints);
            
            $inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, $this->getName($i, 'proposition'), $proposition);
            $trayAns->addElement($inpPropo);     
             
            $this->trayGlobal->addElement($trayAns);      
        }
 
        //----------------------------------------------------------
        
		// To Save
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($questId, $answers)
 	{
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
       foreach ($answers as $keyAns=>$valueAns){
            if($valueAns['proposition'] === '')continue;
            //-------------------------------------- 

//            echo "===>proposition = {$v['proposition']} - points = {$v['points']}<br>";
			$ansObj = $answersHandler->create();
    		$ansObj->setVar('answer_quest_id', $questId);
    		$ansObj->setVar('answer_caption', $valueAns['caption']);
    		$ansObj->setVar('answer_points', $valueAns['points']);
    		$ansObj->setVar('answer_proposition', $valueAns['proposition']);
    		$ansObj->setVar('answer_weight', $key*10);
    		$ansObj->setVar('answer_inputs', 1);

            $ret = $answersHandler->insert($ansObj);
     }
//     exit ("<hr>===>saveAnswers<hr>");
    }
    
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, &$obQuestion = null){
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

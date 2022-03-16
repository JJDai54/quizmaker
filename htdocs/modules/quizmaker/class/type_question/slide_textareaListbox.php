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
class slide_textareaListbox extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textareaListbox");
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
 	public function getFormOptions($caption, $name, $value = "")
 	{    
      
      $input = new XoopsFormRadio($caption, $name, $value, '<br>');
      $input->addOption("H", "Orientation horizontale");            
      $input->addOption("V", "Orientation verticale");            
            
      //$input->setDescription ('Oui');      
            
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
        //-------------------------------------------------
        
        $i=0; //texte à retrouver
        if (isset($answers[$i])) {
            $proposition = $answers[$i]->getVar('answer_proposition', 'e');
            $points = $answers[$i]->getVar('answer_points');
        }else{
            $proposition = '';
            $points = 1;
        }
        $inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_COMMENT2, $this->getName('proposition'), $proposition,'',8);
        $this->trayGlobal->addElement($inpPropo);   
          
         $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_SLIDE_001, $this->getName('points'), $this->lgPoints, $this->lgPoints, $points);
         $inpPoints->setMinMax(-30, 30); 
         $this->trayGlobal->addElement($inpPoints);     
         
        //------------------------------------------------------------        
        $i=1; //liste de propositions fausses
        if (isset($answers[$i])) {
            $tMots = explode(',', $answers[$i]->getVar('answer_proposition'));
            $tPoints = explode(',', $answers[$i]->getVar('answer_points'));
        }else{
            $tMots = array();
            $tPoints = array();
        }
        
        $trayAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        $trayAns->addElement(new xoopsFormLabel('', _AM_QUIZMAKER_STRANGER_EXP));
        for($k = 0; $k < $this->maxPropositions ; $k++){
            $trayMots = new XoopsFormElementTray  ('', $delimeter = ' ');     
            
            $mot = (isset($tMots[$k])) ? $tMots[$k] : ''; 
            $name = $this->getName('mots', $k, 'mot');
            $inpMot = new XoopsFormText($k+1 ."-". _AM_QUIZMAKER_SLIDE_MOT . ' : ', $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayMots->addElement($inpMot);
            
            $points = (isset($tPoints[$k])) ? $tPoints[$k] : 0; 
            $name = $this->getName('mots', $k, 'points');
            $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30); 
            $trayMots->addElement($inpPoints);
            
            $trayAns->addElement($trayMots);
        }        
        
             
        $this->trayGlobal->addElement($trayAns);      
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
		$ansObj = $answersHandler->create();
  		$ansObj->setVar('answer_quest_id', $questId);
  		$ansObj->setVar('answer_weight', 10);
  		$ansObj->setVar('answer_caption', '');
  		$ansObj->setVar('answer_points', $answers['points']);
  		$ansObj->setVar('answer_proposition', $answers['proposition']);
  		$ansObj->setVar('answer_inputs', 1);

        $ret = $answersHandler->insert($ansObj);
       
       
       //-----------------------------------------
       //recupe de la liste des mots indésirables
       $tMots = array();
       $tPoints = array();
       
       foreach ($answers['mots'] as $keyMot => $value){
            if(trim($value['mot']) === '' ) continue;
            $tMots[]   = trim($value['mot']);
            $tPoints[] = (intval(trim($value['points'])));
        }
        
	    $ansObj = $answersHandler->create();
  		$ansObj->setVar('answer_quest_id', $questId);
  		$ansObj->setVar('answer_weight', 20);
  		$ansObj->setVar('answer_caption', '');
  		$ansObj->setVar('answer_proposition', implode(',', $tMots));
  		$ansObj->setVar('answer_points', implode(',', $tPoints));
  		$ansObj->setVar('answer_inputs', 1);
        
        $ret = $answersHandler->insert($ansObj);
         
//     exit ("<hr>===>saveAnswers<hr>");
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, &$obQuestion = null){
  global $answersHandler;
  /*
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['caption']      = $this->getVar('answer_caption');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['points']      = $this->getVar('answer_points');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['inputs']      = $this->getVar('answer_inputs');
  
  */
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

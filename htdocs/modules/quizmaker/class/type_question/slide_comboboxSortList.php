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
class slide_comboboxSortList extends XoopsModules\Quizmaker\Type_question
{
    
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("comboboxSortList", 0, 400);
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
 	public function getFormOptions($caption, $name, $value = "N")
 	{    
      if (!$value) $value = "N";
      $input = new XoopsFormRadio($caption, $name, $value, '<br>');

      $input->addOption("N", _AM_QUIZMAKER_ONLY_ORDER_NAT);            
      $input->addOption("R", _AM_QUIZMAKER_ALLOW_ALL_ORDER);            
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
        $this->maxPropositions = 1;
        $maxMots = 12;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        //element definissat un objet ou un ensemble
        $i=0;
        for ($h = 0; $h < $this->maxPropositions; $h++){
            $trayAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
            if (isset($answers[$h])) {
                $tMots = explode(',', $answers[$h]->getVar('answer_proposition'));
                $points = $answers[$h]->getVar('answer_points');
                $caption = $answers[$h]->getVar('answer_caption'); 
                $weight = $answers[$h]->getVar('answer_weight'); 
            }else{
                $tMots = array();
                $points = 0;
                $caption = ''; 
                $weight = 0; 
            };
            //------------------------------------------------------------
            $trayCaption = new XoopsFormElementTray  ('', $delimeter = '<br>');
            
            $name = $this->getName($h, 'caption');
            $inpCaption = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_CAPTION0, $name, $this->lgProposition, $this->lgProposition, $caption);
            $trayCaption->addElement($inpCaption);
            
            $name = $this->getName($h, 'points');
            $inpPoints = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);            
            $trayCaption->addElement($inpPoints);
            
//             $name = $this->getName($h, 'weight');
//             $inpWeight = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_WEIGHT, $name, $this->lgPoints, $this->lgPoints, $weight);            
//             $trayCaption->addElement($inpWeight);
            
            $this->trayGlobal->addElement($trayCaption);
            //---------------------------------------------------------
            
            $trayMots = new XoopsFormElementTray  ('', $delimeter = '<br>');  
           
            for ($i = 0; $i < $maxMots; $i++){
            $trayItem = new XoopsFormElementTray  ('', $delimeter = ' ');  
            
            
                $inpLab  = new XoopsFormLabel("", $i+1 . " : ");  
                $trayItem->addElement($inpLab);
                
                $mot = (isset($tMots[$i])) ? $tMots[$i] : '';          
                $name = $this->getName($h, 'mots', $i);
                $inpMot = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot2, $mot);
                $trayItem->addElement($inpMot);
                
                $trayMots->addElement($trayItem);
            }            
            $trayAns->addElement($trayMots);
            
            $this->trayGlobal->addElement($trayAns);
        
        }
        //----------------------------------------------------------
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
        $tPropos = array();
        $tPoints = array();
        foreach ($answers as $ansKey=>$ansValue){
            if ($ansValue['caption'] === '' && $ansValue['points'] === '' ) continue;
            $caption = trim($ansValue['caption']);
            $points = intval(trim($ansValue['points']));
    
        	$ansObj = $answersHandler->create();
        	$ansObj->setVar('answer_quest_id', $questId);
        	$ansObj->setVar('answer_caption', $caption);
        	$ansObj->setVar('answer_points', $points);
//        	$ansObj->setVar('answer_weight', intval(trim($ansValue['weight'])));
            
            $tMots = array();
            foreach ($ansValue['mots'] as $keyMot=>$motValue){
                if ($motValue === '' ) continue;
                $tMots[] = $motValue;
            }
        	$ansObj->setVar('answer_proposition', implode(',', $tMots));
            
        	$ret = $answersHandler->insert($ansObj);
        }
//exit;    
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%2\$s;'>%1\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tExp = explode(',', $ans['proposition']);
    $points = intval($ans['points']);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = $points;
    $scoreMin = 0;
    $color = 'blue';
	foreach($tExp as $key=>$exp) {
        $html[] = sprintf($tpl, $exp, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe


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
class slide_radioSimple extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioSimple");
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

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId)
 	{
         global $utility, $answersHandler;
//         if  ($questId > 0){
//             $answers = $answersHandler->getListByParent($questId);
//         }else{
//             $answers = array();
//         }
        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        //element definissant un objet ou un ensemble
        $trayInput = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        $i=0;
        for ($h = 0; $h < $this->maxPropositions; $h++){
            if (isset($answers[$h])) {
                $propos = $answers[$h]->getVar('answer_proposition');
                $points = $answers[$h]->getVar('answer_points');
            }else{
                $propos = '';
                $points = 0;
            };
        
            $trayAns = new XoopsFormElementTray  ('', $delimeter = ' ');  
        
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayAns->addElement($inpLab);
        
            $name = $this->getName($h, 'proposition');
            $inpPropos = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_PROPO, $name, $this->lgProposition, $this->lgProposition, $propos);
            $trayAns->addElement($inpPropos);
        
            $name = $this->getName($h, 'points');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayAns->addElement($inpPoints);
        
        
            $trayInput->addElement($trayAns);
        
        }
        $this->trayGlobal->addElement($trayInput);
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
        foreach ($answers as $key=>$value){
            if ($value['proposition'] === '') continue;
            $propos = trim($value['proposition']);
            $points = intval(trim($value['points']));
    
        	$ansObj = $answersHandler->create();
        	$ansObj->setVar('answer_quest_id', $questId);
        	$ansObj->setVar('answer_proposition', $propos);
        	$ansObj->setVar('answer_points', $points);
        	$ansObj->setVar('answer_weight', $key * 10);
            
        	$ansObj->setVar('answer_caption', '');
        	$ansObj->setVar('answer_inputs', 0);
            
        	$ret = $answersHandler->insert($ansObj);
        }
//exit;
    
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId){
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
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";
        
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');

//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $html = array();
    $html[] = "<table class='solutions'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        if ($points > 0) {
            if ($scoreMax < $points) $scoreMax = $points;
            $color = QUIZMAKER_POINTS_POSITIF;
        }elseif ($points < 0) {
            if ($scoreMin > $points) $scoreMin = $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
        }else{
           $color = QUIZMAKER_POINTS_NULL;
        }

        $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe



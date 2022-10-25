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
class slide_textboxMatchItems extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textboxMatchItems", 0, 420);
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
      if (!$value) $value = "V";      
      $input = new XoopsFormRadio($caption, $name, $value, '<br>');
      $input->addOption("0", _AM_QUIZMAKER_COMPARAISON_0);            
      $input->addOption("1", _AM_QUIZMAKER_COMPARAISON_1);            
      $input->addOption("2", _AM_QUIZMAKER_COMPARAISON_2);            
            
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

// echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        
        $this->initFormForQuestion();
        //-------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');
        for($h = 0; $h < $this->maxPropositions; $h++){
        
            if (isset($answers[$h])) {
            echo $answers[$h]->getVar('answer_proposition') . "-{$h}<br>";
                $tExp = explode('|', $answers[$h]->getVar('answer_proposition'));
                $mot1 = trim($tExp[0]); 
                $mot2 = trim($tExp[1]); 
                $points = intval(trim($answers[$h]->getVar('answer_points')));
                $weight = intval(trim($answers[$h]->getVar('answer_weight')));
            }else{
                $mot1 = ""; 
                $mot2 = ""; 
                $points = 0;
                $weight = $h * 10;
            }
            
            $trayPropo = new XoopsFormElementTray  ('', $delimeter = ' ');  
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayPropo->addElement($inpLab);
            
            $name = $this->getName($h, 'mot1');
            $inpMot1 = new XoopsFormText(_AM_QUIZMAKER_SLIDE_MOT . " 1", $name, $this->lgMot1, $this->lgMot2, $mot1);
            $trayPropo->addElement($inpMot1);
            
            $name = $this->getName($h, 'mot2');
            $inpMot2 = new XoopsFormText(_AM_QUIZMAKER_SLIDE_MOT . " 2", $name, $this->lgMot3, $this->lgMot4, $mot2);
            $trayPropo->addElement($inpMot2);
            
            $name = $this->getName($h, 'points');
            $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayPropo->addElement($inpPoints);
            
            $name = $this->getName($h, 'weight');
            $inpWeight = new XoopsFormNumber(_AM_QUIZMAKER_SLIDE_WEIGHT, $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);
            $trayPropo->addElement($inpWeight);
            
            $trayAllAns->addElement($trayPropo);
        }
        $this->trayGlobal->addElement($trayAllAns);

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
            if ($answers[$key]['mot1'] === '' ||  $answers[$key]['mot2'] === '') continue;
            
          	$ansObj = $answersHandler->create();
          	$ansObj->setVar('answer_quest_id', $questId);
              
            $propo = trim($value['mot1']) . "|" . trim($value['mot2']); 
          	$ansObj->setVar('answer_proposition', $propo);
          	$ansObj->setVar('answer_points', intval($value['points']));
          	$ansObj->setVar('answer_weight', intval($value['weight']));
              
          	$ansObj->setVar('answer_caption', '');
          	$ansObj->setVar('answer_inputs', 0);
      
      	    $ret = $answersHandler->insert($ansObj);
        }
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%1\$s;'>%2\$s</span></td>" 
             . "<td><span style='color:%1\$s;'>%3\$s</span></td>" 
             . "<td><span style='color:%1\$s;'>%4\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%1\$s;'>%5\$s</span></td>"
             . "<td><span style='color:%1\$s;'>%6\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $exp = str_replace(',', ' - ', $ans['proposition']);
        $tExp = explode(',', $ans['proposition']);
        $points = $ans['points'];

        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
        }elseif ($points < 0) {
            $scoreMin += $points;
          $color = QUIZMAKER_POINTS_NEGATIF;
        }else{
           $color = QUIZMAKER_POINTS_NULL;
        }
        $html[] = sprintf($tpl,$color, $tExp[0], $tExp[1], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS);

	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe




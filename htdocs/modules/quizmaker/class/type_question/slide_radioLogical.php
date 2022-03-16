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
class slide_radioLogical extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioLogical");
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

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";

        //element definissat un objet ou un ensemble
        $trayFamily = new XoopsFormElementTray  ('', $delimeter = '<br>');  ;
        $trayMots = new XoopsFormElementTray  ('', $delimeter = ' ');  ;
        
        $i = 0;
        $proposition = (isset($answers[$i])) ? $answers[$i]->getVar('answer_proposition'): '';
        $tPropos = explode(",", $proposition);
        for($h = 0; $h < $this->maxPropositions; $h++){
            $mot = (isset($tPropos[$h])) ? $tPropos[$h] : '';
            
            $name = $this->getName('family',$h);
            $inpSet = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayMots->addElement($inpSet);
        }        
        $trayFamily->addElement(new xoopsFormLabel('', 'Critères'));
        $trayFamily->addElement($trayMots);
        $this->trayGlobal->addElement($trayFamily);

        //---------------------------------------
        //Propositions
        $i=1;
        $proposition = (isset($answers[$i])) ? $answers[$i]->getVar('answer_proposition'): '';
        $tPropos = explode(",", $proposition);
        $points = (isset($answers[$i])) ? $answers[$i]->getVar('answer_points'): '';
        $tPoints = explode(",", $points);
        
        
        
        $trayAllPropos = new XoopsFormElementTray  (_AM_QUIZMAKER_PROPOSITIONS, $delimeter = '<br>');  
        $trayAllPropos->addElement(new XoopsFormLabel  ("", "Propositions"));

        //$trayPropos = new XoopsFormElementTray  (_AM_QUIZMAKER_PROPOSITIONS, $delimeter = ' ');  
  
        $trayItemSet = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        for ($h = 0; $h < $this->maxPropositions; $h++){
            if (isset($tPropos[$h])) {
                $propo = $tPropos[$h];
                $points = $tPoints[$h];
            }else{
                $propo = '';
                $points = 0;
            }
            $trayItem =  new XoopsFormElementTray  ('', $delimeter = ' ');  
            
            $name = $this->getName('inputs',$h,'proposition');
            $inpPropo = new \XoopsFormText($h+1 . ': ' . _AM_QUIZMAKER_SLIDE_LABEL, $name, $this->lgMot1, $this->lgMot2, $propo);
            $trayItem->addElement($inpPropo);  
       
            $name = $this->GetName('inputs',$h,'points');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayItem->addElement($inpPoints);
              
            $trayItemSet->addelement($trayItem);
        }
        $trayAllPropos->addelement($trayItemSet);
        $this->trayGlobal->addElement($trayAllPropos);        
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
        $fam = array();
        foreach ($answers['family'] as $kry=>$value){
            if ($value != ''){
                $fam[] = trim($value);
            }
        }
    
    	$ansObj = $answersHandler->create();
    	$ansObj->setVar('answer_quest_id', $questId);
    	$ansObj->setVar('answer_proposition', implode(',', $fam));
    	$ansObj->setVar('answer_weight', 10);
        
    	$ansObj->setVar('answer_points', 0);
    	$ansObj->setVar('answer_caption', '');
    	$ansObj->setVar('answer_inputs', 0);
        
    	$ret = $answersHandler->insert($ansObj);
        
        //------------------------------------------------------------
        $propos = array();
        $points = array();
        foreach ($answers['inputs'] as $kry=>$value){
            if ($value['proposition'] != ''){
                $propos[] = trim($value['proposition']);
                $points[] = intval(trim($value['points']));
            }
        }
    
    	$ansObj = $answersHandler->create();
    	$ansObj->setVar('answer_quest_id', $questId);
    	$ansObj->setVar('answer_proposition', implode(',', $propos));
    	$ansObj->setVar('answer_points', implode(',', $points));
    	$ansObj->setVar('answer_weight', 20);
        
    	$ansObj->setVar('answer_caption', '');
    	$ansObj->setVar('answer_inputs', 0);
        
    	$ret = $answersHandler->insert($ansObj);
    
//     exit ("<hr>===>saveAnswers<hr>");
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[1]->getValuesAnswers();
    //$tp = $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
    $tp = $this->combineAndSorAnswer($ans);    

    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
//    echoArray($tPoints);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach($tp as $key=>$tItem) {
        $points = intval($tItem['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
        }else{
           $color = QUIZMAKER_POINTS_NULL;
        }
        $html[] = sprintf($tpl, $tItem['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
//    echoArray($ret);
    return $ret;
     }

} // fin de la classe





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
class slide_checkboxLogical extends XoopsModules\Quizmaker\Type_question
{
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("checkboxLogical", 0, 110);
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
 	public function getForm($questId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //----------------------------------------------------------
        $trayPropo = new XoopsFormElementTray  ('', $delimeter = '<br>'); 
        
        //element definissat un objet ou un ensemble
        $i=0;
        $proposition = (isset($answers[$i])) ? $answers[$i]->getVar('answer_proposition'): '';
        $tPropos = explode(",", $proposition);
        
        $trayFamily = new XoopsFormElementTray  ('', $delimeter = ' ');  ;
        for($k = 0; $k < $this->maxPropositions; $k++){
            $mot = isset($tPropos[$k]) ? $tPropos[$k] : '';
            //$chrono = ($k+1) . ' : ' ;
            $chrono = ((($k % 4)==0) ? "<br>" : '')  . ($k + 1) . ' : ' ;
            $name = $this->getName('family',$k);
            $inpMot = new \XoopsFormText($chrono, $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayFamily->addElement($inpMot);

        }    
         
 
        $trayPropo->addElement(new XoopsFormLabel  ("",_AM_QUIZMAKER_SLIDE_002));
        $trayPropo->addElement($trayFamily);   
        $this->trayGlobal->addElement($trayPropo);
        //-------------------------------------------------------
        $trayInput = new XoopsFormElementTray  ('', $delimeter = '<br>'); 
          
        //Propositions
        $i=1;
        $proposition = (isset($answers[$i])) ? $answers[$i]->getVar('answer_proposition'): '';
        $tPropos = explode(",", $proposition);
        $points = (isset($answers[$i])) ? $answers[$i]->getVar('answer_points'): '';
        $tPoints = explode(",", $points);
        $labProposition = new XoopsFormLabel  ("", "<hr>" . _AM_QUIZMAKER_SLIDE_002);        
        $trayPropos = new XoopsFormElementTray  (_AM_QUIZMAKER_PROPOSITIONS, $delimeter = '<br>');  
        
        
        
        $trayItemSet =  new XoopsFormElementTray  ('', $delimeter = '<br>');  
        for($k = 0; $k < $this->maxPropositions; $k++){
            if (isset($tPropos[$k])){
                $mot = $tPropos[$k];
                $points = intval(trim($tPoints[$k]));
            }else{
                $mot = "";
                $points = 0;
            }
            $trayItem =  new XoopsFormElementTray  ('', $delimeter = ' ');  
            
       
            $name = $this->GetName('inputs',$k,'mot');
            $inpMot = new \XoopsFormText(($k+1) . " : " . _AM_QUIZMAKER_SLIDE_MOT, $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayItem->addElement($inpMot);  
            
            $name = $this->GetName('inputs',$k,'points');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayItem->addElement($inpPoints);
            
            $trayItemSet->addelement($trayItem);
        }

        $trayInput->addElement(new XoopsFormLabel  ("",_AM_QUIZMAKER_SLIDE_003));
        $trayInput->addElement($trayItemSet);
        $this->trayGlobal->addElement($trayInput);

        //----------------------------------------------------------
		return $this->trayGlobal;
	}
/* ********************************************
*
*********************************************** */
 	public function saveAnswers($questId, $answers)
 	{global $utility, $answersHandler, $type_questionHandler;
//    echo "<hr>POST<pre>" . print_r($answers, true) . "</pre><hr>";
//exit;    
       //suppression de toutes les proposition du slide
       //c'est plus simple de de modifier et ajouter
       //$answersHandler->deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
       //$criteria = new Criteria('answer_quest_id', $questId, '=');
       if($questId > 0){
           $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id', $questId, '='));
//           echo "===> Criteria delete : " . $criteria->render() . "<br>";
           $answersHandler->deleteAll($criteria);
       }
    //-------------------------------------------------------------------
    $fam = array();
    foreach ($answers['family'] as $key=>$value){
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
    $mots = array();
    $points = array();
    foreach ($answers['inputs'] as $key=>$value){
        if ($value['mot'] != ''){
            $mots[] = trim($value['mot']);
            $points[] = intval(trim($value['points']));
        }
    }

	$ansObj = $answersHandler->create();
	$ansObj->setVar('answer_quest_id', $questId);
	$ansObj->setVar('answer_proposition', implode(',', $mots));
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
  public function getSolutions($questId, $boolAllSolutions = true){
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
            $html[] = sprintf($tpl, $tItem['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $tItem['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $tItem['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
//    echoArray($ret);
    return $ret;
     }

} // fin de la classe



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
class slide_checkboxSimple extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("checkboxSimple", 0, "checkbox");
        $this->optionsDefaults = ['shuffleAnswers' => QUIZMAKER_SHUFFLE_DEFAULT,
                                  'imgHeight'      => '80', 
                                  'familyWords'    => ''];
        $this->hasImageMain = true;
        $this->multiPoints = true;
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
 	public function getFormOptions($caption, $optionName, $jsonValues = null, $folderJS = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           
//echo "<hr><pre>options : " . print_r($tValues, true) . "</pre><hr>";
      
      $name = 'shuffleAnswers';  
      $inputShuffleAnswers = new \XoopsFormRadioYN(_AM_QUIZMAKER_SHUFFLE_ANS, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions ->addElement($inputShuffleAnswers);     
      $trayOptions ->addElement(new XoopsFormLabel('', _AM_QUIZMAKER_SHUFFLE_ANS_DESC));      
      
      $trayOptions ->addElement(new XoopsFormLabel('', '<br>'));   
      
      $name = 'familyWords';  
      $inputFamilyWords = new \XoopsFormText(_AM_QUIZMAKER_FAMILY_WORDS, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot4, $tValues[$name]);
      $trayOptions ->addElement($inputFamilyWords);     
      $trayOptions ->addElement(new XoopsFormLabel('', _AM_QUIZMAKER_FAMILY_WORDS_DESC));      
      
      
   
    
/*
      $name = 'image';  
      $path =  QUIZMAKER_UPLOAD_QUIZ_JS . "/{$folderJS}/images";  
      //$fullName =  QUIZMAKER_UPLOAD_QUIZ_JS . "/{$folderJS}/images/" . $tValues[$name];     
      $inpImage = $this->getXoopsFormImage($tValues[$name], "{$optionName}[{$name}]", $path, 80,'<br>');  
      $trayOptions ->addElement($inpImage);   
*/
      
      //--------------------------------------------------------------------           
      
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
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        
        $i = $this->getFormGroup($trayAllAns, 0, $answers,'', 0, $this->maxPropositions);        


                    
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}

public function getFormGroup(&$trayAllAns, $group, $arr,$titleGroup, $firstItem, $maxItems, $path='')
{ 
        
  reset($arr);
        $tbl = $this->getNewXoopsTableXtray();
        $tbl->addTdStyle(1, "width:500px;");
        $tbl->addTdStyle(2, "width:120px;");
        $tbl->addTdStyle(3, "width:120px;");
        $weight = 0;
        
        for($k = 0; $k < $maxItems; $k++){
            $i = $k + $firstItem;
            if (isset($arr[$k])){
                $proposition = $arr[$k]->getVar('answer_proposition');
                $points = $arr[$k]->getVar('answer_points');
            }else{
                $proposition = '';
                $points = 0;
            }
      
            
            $inpChrono = new \XoopsFormLabel('', $i+1);            
            $inpPropos = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_MOT, $this->getName($i,'proposition'), $this->lgMot2, $this->lgMot2, $proposition);
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_WEIGHT,  $this->getName($i,'weight'), $this->lgWeight, $this->lgWeight, $weight += 10);
            $inpWeight->setMinMax(0, 999);
            $inpGroup = new \XoopsFormHidden($this->getName($i,'group'), $group);

        //----------------------------------------------------------
            $col = 0;
            $tbl->addElement(new \XoopsFormLabel('','' . ($i+1)), $col, $k);
            //$tbl->addElement($inpChrono, $col, $k, '');
            $tbl->addElement($inpPropos, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
            $tbl->addElement($inpGroup, $col, $k, '');
            
            $tbl->addElement($inpGroup, ++$col, $k, '');
        }
        
        $trayAllAns->addElement($tbl);
        return $i+1;  // return le dernier index pour le groupe suivant

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
       foreach ($answers as $key=>$v){
            if($v['proposition'] != ''){
//            echo "===>proposition = {$v['proposition']} - points = {$v['points']}<br>";
			$ansObj = $answersHandler->create();
    		$ansObj->setVar('answer_quest_id', $questId);
            
    		$ansObj->setVar('answer_proposition', $v['proposition']);
    		$ansObj->setVar('answer_points', $v['points']);
    		$ansObj->setVar('answer_weight', $v['weight']);
            
    		$ansObj->setVar('answer_caption', '');
    		$ansObj->setVar('answer_inputs', 1);

		      $ret = $answersHandler->insert($ansObj);
            }
        
     }
     //exit ("<hr>===>saveAnswers<hr>");
    }
    
// /* ********************************************
// *
// *********************************************** */
//   public function getSolutions($questId, $boolAllSolutions = true){
//   global $answersHandler;
//   /*
// 		$ret = $this->getValues($keys, $format, $maxDepth);
// 		$ret['id']          = $this->getVar('answer_id');
// 		$ret['quest_id']    = $this->getVar('answer_quest_id');
// 		$ret['caption']      = $this->getVar('answer_caption');
// 		$ret['proposition'] = $this->getVar('answer_proposition');
// 		$ret['points']      = $this->getVar('answer_points');
// 		$ret['weight']      = $this->getVar('answer_weight');
// 		$ret['inputs']      = $this->getVar('answer_inputs');
//   
//   */
//     $answersAll = $answersHandler->getListByParent($questId);
// //    echoArray($answersAll);
//     $answers = array();
//     $totalPoints = 0;
//     $html = array();
//     $html[] = "<table style='margin:0px 20px 0px 20px;' width='90%'>";
// 	foreach(array_keys($answersAll) as $i) {
// 		$ans = $answersAll[$i]->getValuesAnswers();
// 
//         if ($ans['points'] > 0){
//             $html[] = "<tr><td>- {$ans['proposition']}</td><td>&nbsp;===>&nbsp;</td><td>{$ans['points']}</td></tr>";
//             $totalPoints += intval($ans['points']);
//         }
// 	}
//     
//         $p = sprintf(_CO_QUIZMAKER_POINTS_FOR_ANSWER2, $totalPoints); 
//         $html[] = "<tr><td colspan='2'><hr class='grey1-hr-style-one'></td></tr>";   
//         $html[] = "<tr><td colspan='2'>{$p}</td></tr>";   
//     $html[] = "</table>";
// 
//     return implode("\n", $html);
//      }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
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

//if(!$boolAllSolutions) exit;    
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $html = array();

    $html[] = "<table class='quizTbl'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe

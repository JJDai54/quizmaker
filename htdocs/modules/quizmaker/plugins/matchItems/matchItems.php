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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xoopsfr.kiolo.fr>
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
include_once QUIZMAKER_PATH_MODULE . "/class/Plugins.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class Plugin_matchItems extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("matchItems", 0, "other");
        $this->optionsDefaults = ['disposition'    => 'disposition-01',
                                  'list1_type'    => 0,
                                  'list1_intrus'  => '',
                                  'list2_type'     => '1',
                                  'list2_intrus'   => '',
                                  'conjonction'    => ''];
                                  
        $this->hasImageMain = true;
        //$this->multiPoints = true;

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
      $trayOptions = new XoopsFormElementTray($caption, '<br>');  
      //--------------------------------------------------------------------    
      
      $trayOptions ->addElement(new XoopsFormLabel('<hr>', _LG_PLUGIN_MATCHITEMS_LEFT_LIST . '<br>'));   
      
      $name = 'list1_type';  
      $inpList1 = new \XoopsFormRadioYN(_LG_PLUGIN_MATCHITEMS_LEFT_TYPE, "{$optionName}[{$name}]", $tValues[$name], ' ');   
      $inpList1->addOption(0, _LG_PLUGIN_MATCHITEMS_LABEL);
      $inpList1->addOption(1, _LG_PLUGIN_MATCHITEMS_LISTBOX);
      $inpList1->addOption(2, _LG_PLUGIN_MATCHITEMS_TEXTBOX);
      $trayOptions ->addElement($inpList1);   
      
      $name = 'list1_intrus'; 
      $inputIntrus1 = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_INTRUS1, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot4, $tValues[$name]);
      $inputIntrus1->setDescription(_LG_PLUGIN_MATCHITEMS_INTRUS1_DESC);
      $inputIntrus1->setExtra("style='background:" . self::bgColor1 . ";'");
      $trayOptions->addElement($inputIntrus1);
      $trayOptions->addElement(new \XoopsFormLabel('',_LG_PLUGIN_MATCHITEMS_INTRUS1_DESC . '<br>'));
      
      //------------------------------------------------------------
      $trayOptions ->addElement(new XoopsFormLabel('<hr>',_LG_PLUGIN_MATCHITEMS_RIGHT_LIST  . '<br>'));      

      $name = 'list2_type';  
      $inpList2 = new \XoopsFormRadioYN(_LG_PLUGIN_MATCHITEMS_RIGHT_TYPE, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inpList2->addOption(0, _LG_PLUGIN_MATCHITEMS_LABEL);
      $inpList2->addOption(1, _LG_PLUGIN_MATCHITEMS_LISTBOX);
      $inpList2->addOption(2, _LG_PLUGIN_MATCHITEMS_TEXTBOX);
      $trayOptions ->addElement($inpList2);   
  
      $name = 'list2_intrus'; 
      $inputIntrus2 = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_INTRUS1, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot4, $tValues[$name]);
      $inputIntrus2->setDescription(_LG_PLUGIN_MATCHITEMS_INTRUS1_DESC);
      $inputIntrus2->setExtra("style='background:" . self::bgColor1 . ";'");
      $trayOptions->addElement($inputIntrus2);
      $trayOptions->addElement(new \XoopsFormLabel('',_LG_PLUGIN_MATCHITEMS_INTRUS2_DESC . '<br>'));
      //------------------------------------------------------------
     
             
      //------------------------------------------------------------
      $trayOptions ->addElement(new XoopsFormLabel('<hr>',_LG_PLUGIN_MATCHITEMS_GLOBALS_OPTIONS  . '<br>'));    

      $name = 'conjonction'; 
      $inputConjonction = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_CONJONCTION, "{$optionName}[{$name}]", $this->lgMot1, $this->lgMot1, $tValues[$name]);
      $inputConjonction->setDescription(_LG_PLUGIN_MATCHITEMS_CONJONCTION_DESC);
      $inputConjonction->setExtra("style='background:" . self::bgColor3 . ";'");
      $trayOptions->addElement($inputConjonction);
      $trayOptions->addElement(new \XoopsFormLabel('', _LG_PLUGIN_MATCHITEMS_CONJONCTION_DESC. '<br>'));
        
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
        //element definissat un objet ou un ensemble
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray();
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(1, 'text-align:left;width:50px;');
        
        for($k = 0; $k < $this->maxPropositions; $k++){
        
            if (isset($answers[$k])) {
                $tExp = explode(',', $answers[$k]->getVar('answer_proposition'));
                $mot1 = trim($tExp[0]); 
                $mot2 = trim($tExp[1]); 
                $points = intval(trim($answers[$k]->getVar('answer_points')));
                $weight = intval(trim($answers[$k]->getVar('answer_weight')));
            }else{
                $mot1 = ""; 
                $mot2 = ""; 
                $points = 1;
                $weight = $k * 10;
            }
            
            
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");
            $name = $this->getName($k, 'mot1');
            $inpMot1 = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT . " 1", $name, $this->lgMot1, $this->lgMot2, $mot1);            
            $name = $this->getName($k, 'mot2');
            $inpMot2 = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT . " 2", $name, $this->lgMot1, $this->lgMot2, $mot2);
            
            $name = $this->getName($k, 'points');
            $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(1, 30);
            
            $name = $this->getName($k, 'weight');
            $inpWeight = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_WEIGHT, $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);
            
            $col=0;
            $tbl->addElement($inpLab, $col++, $k);
            $tbl->addElement($inpMot1, $col++, $k);
            $tbl->addElement($inpMot2, $col++, $k);
            $tbl->addElement($inpPoints, $col++, $k);
            $tbl->addElement($inpWeight, $col++, $k);

        }
        $this->trayGlobal->addElement($tbl);

        //----------------------------------------------------------
		return $this->trayGlobal;
	}
/* *************************************************
*
* ************************************************** */
 	public function getForm_old($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');
        for($h = 0; $h < $this->maxPropositions; $h++){
        
            if (isset($answers[$h])) {
                $tExp = explode(',', $answers[$h]->getVar('answer_proposition'));
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
            $inpMot1 = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT . " 1", $name, $this->lgMot1, $this->lgMot2, $mot1);
            $trayPropo->addElement($inpMot1);
            
            $name = $this->getName($h, 'mot2');
            $inpMot2 = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT . " 2", $name, $this->lgMot1, $this->lgMot2, $mot2);
            $trayPropo->addElement($inpMot2);
            
            $name = $this->getName($h, 'points');
            $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayPropo->addElement($inpPoints);
            
            $name = $this->getName($h, 'weight');
            $inpWeight = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_WEIGHT, $name, $this->lgPoints, $this->lgPoints, $weight);
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
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
        foreach ($answers as $key=>$value){
            if ($answers[$key]['mot1'] === '' ||  $answers[$key]['mot2'] === '') continue;
            
          	$ansObj = $answersHandler->create();
          	$ansObj->setVar('answer_quest_id', $questId);
              
              $propo = trim($value['mot1']) . "," . trim($value['mot2']); 
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




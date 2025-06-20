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
class Plugin_selectInputs extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("selectInputs", 0, "basic");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        
        $this->optionsDefaults = ['inputType'        => 0, //'multipleChoice' => 0, unique choice => 1,
                                  'nextSlideDelai'   => 0,
                                  'nextSlideBG'      =>'#FFCC00',
                                  'nextSlideMessage' => ((defined("_AM_QUIZMAKER_NEXT_SLIDE_MSG0")) ? _AM_QUIZMAKER_NEXT_SLIDE_MSG0 : ''),
                                  'familyWords'      => '',
                                  'disposition'      => 'disposition-0'];

        $this->hasImageMain = true;
        $this->multiPoints = true;
        $this->hasShuffleAnswers = true;

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
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------           
    
      $name = 'inputType';  
      $inpType = new \XoopsFormRadio(_LG_PLUGIN_SELECTINPUTS_TYPE, "{$optionName}[{$name}]", $tValues[$name]);
      $inpType->addOptionArray([0 => _LG_PLUGIN_SELECTINPUTS_TYPE_0,
                                1 => _LG_PLUGIN_SELECTINPUTS_TYPE_1]);
      $inpType->setDescription(_LG_PLUGIN_SELECTINPUTS_TYPE_DESC);
      $trayOptions->addElementOption($inpType);     
      
      //--------------------------------   
      
      $arrConst = ['msgNextSlideTxt' => '_LG_PLUGIN_SELECTINPUTS_NEXT_SLIDE'];
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_avertissement.php");
      
      //--------------------------------------------------------------------           
      $name = 'familyWords';  
      $inputFamilyWords = new \XoopsFormText(_AM_QUIZMAKER_FAMILY_WORDS, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
      $inputFamilyWords->setDescription(_AM_QUIZMAKER_FAMILY_WORDS_DESC);
      $trayOptions ->addElementOption($inputFamilyWords);     
      
      // disposition 
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_disposition.php");

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

public function getFormGroup(&$trayAllAns, $group, $answers,$titleGroup, $firstItem, $maxItems, $path='')
{ 
        
  reset($answers);
        $tbl = $this->getNewXoopsTableXtray();
        $tbl->addTdStyle(1, "width:500px;");
        $tbl->addTdStyle(2, "width:120px;");
        $tbl->addTdStyle(3, "width:120px;");
        $weight = 0;
        
        for($k = 0; $k < $maxItems; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            
            $inpPropos = new \XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT, $this->getName($k,'proposition'), $this->lgMot4, $this->lgMot5, $proposition);
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS,  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_WEIGHT,  $this->getName($k,'weight'), $this->lgWeight, $this->lgWeight, $weight += 10);
            $inpWeight->setMinMax(0, 999);
            $inpGroup = new \XoopsFormHidden($this->getName($k,'group'), $group);

        //----------------------------------------------------------

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
        global $utility, $answersHandler, $pluginsHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            $ans['proposition']  = FQUIZMAKER\sanityse_inpValue($ans['proposition']);

            if($ans['proposition'] != ''){
                
        		$ansObj->setVar('answer_proposition', $ans['proposition']);
        		$ansObj->setVar('answer_points', $ans['points']);
        		$ansObj->setVar('answer_weight', $ans['weight']);
                
        		$ansObj->setVar('answer_caption', '');
        		$ansObj->setVar('answer_inputs', 1);

		        $ret = $answersHandler->insert($ansObj);
            }
        
     }
    }
    


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

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

use XoopsModules\Quizmaker AS FQUIZMAKER;
include_once QUIZMAKER_PATH_MODULE . "/class/Plugins.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class Plugin_textboxMultiple extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textboxMultiple", 0, "text");

        $this->hasImageMain = true;
        $this->optionsDefaults = ['disposition' => 'disposition-01'];
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
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           

//       $name = 'minReponses';  
//       $inpMinReponses = new XoopsFormNumber(_AM_QUIZMAKER_QUESTIONS_MINREPONSE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpMinReponses->setMinMax(0, 12);
//       $trayOptions->addElement($inpMinReponses);     

      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $allPluginsName =  \XoopsLists::getDirListAsArray(QUIZMAKER_PATH_PLUGINS_PHP);
      $imgList = XoopsLists::getFileListByExtension($path,  array('jpg','png','gif'), '');
      $inputDisposition = new \XoopsFormIconeSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inputDisposition->setGridIconNumber(count($imgList),1);
      $trayOptions->addElement($inputDisposition);      
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
        $this->maxPropositions = 5;
        $maxMots = 12;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        $lastWeight = 0;        
        for($h = 0; $h < $this->maxPropositions; $h++){
        
            if (isset($answers[$h])) {
                $caption = $answers[$h]->getVar('answer_caption');
                $inputs  = $answers[$h]->getVar('answer_inputs');
                $tMots   = explode(',', $answers[$h]->getVar('answer_proposition'));
                $tPoints = explode(',', trim($answers[$h]->getVar('answer_buffer')));
                $weight  = intval(trim($answers[$h]->getVar('answer_weight')));
                $lastWeight = $weight;

            }else{
                $caption = "";
                $inputs  = 1; 
                $tMots   = array(); 
                $tPoints  = array();
                $weight  = $lastWeight + ($h * 10);
            }
            
            $trayPropo = new XoopsFormElementTray  ('', $delimeter = '<br>'); 
            //--------------------------------------------------------------
            $trayCaption = new XoopsFormElementTray  ('', $delimeter = ' '); 
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayCaption->addElement($inpLab);
            
            $name = $this->getName($h, 'caption');
            $inpCaption = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_TITLE, $name, $this->lgProposition2, $this->lgProposition2, $caption);
            $trayCaption->addElement($inpCaption);
            
            $name = $this->getName($h, 'inputs');
            $inpInputs = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_INPUTS, $name, $this->lgPoints, $this->lgPoints, $inputs);
            $inpInputs->setMinMax(1, 5);
            $trayCaption->addElement($inpInputs);
            
            $name = $this->getName($h, 'weight');
            $inpWeight = new XoopsFormNumber("poids", $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);
            $trayCaption->addElement($inpWeight);
            
            $trayPropo->addElement($trayCaption);
            //--------------------------------------------------------------
            $trayAllMots = new XoopsFormElementTray  ('', $delimeter = '<br>');            
            for($i = 0; $i < $maxMots; $i++){
                $mot    = (isset($tMots[$i])) ? $tMots[$i]: "";
                $points = (isset($tPoints[$i])) ? intval($tPoints[$i]) : 0;
                $trayMot = new XoopsFormElementTray  ('', $delimeter = '');                
                
                $inpLab  = new XoopsFormLabel("",  ($h+1) . ' / ' . ($i+1) . ' : ');
                $trayMot->addElement($inpLab);
           
                $name = $this->getName($h, 'mots', $i, 'mot');
                $inpMot = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT, $name, $this->lgMot1, $this->lgMot2, $mot);
                $trayMot->addElement($inpMot);
                
                $name = $this->getName($h, 'mots', $i, 'points');
                $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
                $inpPoints->setMinMax(-30, 30);
                $trayMot->addElement($inpPoints);
                //-------------------------------------
                $trayAllMots->addelement($trayMot);                
            }    
            
            $trayPropo->addElement($trayAllMots);
            
            
            $this->trayGlobal->addElement($trayPropo);
        }
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
        foreach ($answers as $anskey=>$ansValue){
            if ($ansValue['caption'] === '') continue;
            
          	$ansObj = $answersHandler->create();
          	$ansObj->setVar('answer_quest_id', $questId);
              
          	$ansObj->setVar('answer_caption', trim($ansValue['caption']));
          	$ansObj->setVar('answer_inputs', $ansValue['inputs']);
          	$ansObj->setVar('answer_weight', $ansValue['weight']);
          	//$ansObj->setVar('answer_points', intval($value['points']));
            //----------------------------------------------------------
            $tMots = array();
            $tPoints = array();
            foreach ($ansValue['mots'] as $motKey=>$motValue){
                if ($motValue['mot'] === '' ) continue;
                
                $tMots[]   = trim($motValue['mot']);
                $tPoints[] = $motValue['points'];
            }
          	$ansObj->setVar('answer_proposition', implode(',', $tMots));
          	$ansObj->setVar('answer_buffer', implode(',', $tPoints));
      
      	    $ret = $answersHandler->insert($ansObj);

//    echo "<hr>answers.mots <pre>" . print_r($ansValue['mots'], true) . "</pre><hr>";
    
        }
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl1 = "<tr><td colspan='4'><span style='color:%2\$s;font-weight:normal;'>%1\$s</span></td></tr>";
    
    $tpl2 = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        //$tp = $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
        $tp = $this->combineAndSorAnswer($ans);
            
        $color = "black";
        $html[] = sprintf($tpl1, $ans['caption'], $color);        

        foreach($tp as $key=>$item){
            $points = intval($item['points']);
            if ($points > 0) {
                $scoreMax += $points;
                $color = QUIZMAKER_POINTS_POSITIF;
                $html[] = sprintf($tpl2, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
            }elseif ($points < 0) {
                $scoreMin += $points;
                $color = QUIZMAKER_POINTS_NEGATIF;
                $html[] = sprintf($tpl2, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
            }elseif($boolAllSolutions){
                $color = QUIZMAKER_POINTS_NULL;
                $html[] = sprintf($tpl2, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
            }
        }
        if($i < (count($answersAll)-1))
        $html[] = "<td colspan='4'><hr class='default-hr-style-one'></td>";
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe




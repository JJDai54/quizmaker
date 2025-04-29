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
class Plugin_radioMultiple extends XoopsModules\Quizmaker\Plugins
{
var $nbMaxColumns = 5;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioMultiple", 0, "basic");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['orientation' => 'horitontal', 
                                  'directive'   => '',
                                  'nbColumns'   => $this->nbMaxColumns];
        $this->hasImageMain = true;
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
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);     
 
      //--------------------------------------------------------------------           
      $name = "nbColumns";  
      $nbColumns = $tValues[$name];
      $inpNbColumns = new \XoopsFormNumber(_LG_PLUGIN_RADIOMULTIPLE_NB_COLUMN,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpNbColumns->setExtra("style='background:#FFCC99;'");
      $inpNbColumns->setMinMax(2, $this->nbMaxColumns, _AM_QUIZMAKER_UNIT_OPTIONS);
      $inpNbColumns->setDescription(_LG_PLUGIN_RADIOMULTIPLE_NB_COLUMN_DESC);
      $trayOptions ->addElementOption($inpNbColumns);     
      
      $name = 'directive';  
      if (!$tValues[$name]) $tValues[$name] = _LG_PLUGIN_RADIOMULTIPLE_DIRECTIVE_LIB;
      $inpDirective = new \XoopsFormText(_LG_PLUGIN_RADIOMULTIPLE_DIRECTIVE, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
      $inpDirective->setDescription(_LG_PLUGIN_RADIOMULTIPLE_DIRECTIVE_DESC);
      $trayOptions ->addElementOption($inpDirective);     

      $name = 'orientation'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputOrientation = new \XoopsFormIconSelect("<br>" . _LG_PLUGIN_RADIOMULTIPLE_ORIENTATION. "-" . $tValues[$name], "{$optionName}[{$name}]", $tValues[$name], $path);
      $inputOrientation->setGridIconNumber(2,1);
      $trayOptions->addElementOption($inputOrientation);     
      
      //--------------------------------------------------------------------           
  
      return $trayOptions;
    }


    
/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $xoopsConfig, $questionsHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $this->maxPropositions = 8;

        //---------------------------------------------------------
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        $nbColumns = (isset($options['nbColumns'])) ? $options['nbColumns'] : $this->nbMaxColumns;

        $tbl = $this->getNewXoopsTableXtray();
          $tbl->addTitle('');        
          for($h = 0; $h < $nbColumns; $h++){
              $j = $h+1;
              $tbl->addTitle(sprintf(_AM_QUIZMAKER_PLUGIN_OPTION_NUM, $h+1));        
              //$width = $options["list{$h}_width"];
              //$tbl->addTdStyle($j, "width:{$width}%;");
        //echo "<hr>column {$j} - width : {$width}<hr>";
          }
          $tbl->addTitleArray([_AM_QUIZMAKER_PLUGIN_POINTS, _AM_QUIZMAKER_PLUGIN_WEIGHT]);        
        //----------------------------------------------------------
        
        for($k = 0; $k < $this->maxPropositions; $k++){        
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            if($isNew) $points = 0; //correction du nombre de points pour les nouveaux enregistrement
            
            for($h = 0; $h < $nbColumns; $h++){        
                $mot = (isset($tPropos[$h])) ? $tPropos[$h] : '';
                $name = $this->getName($k, 'mots', $h);
                
                $inpMot = new \XoopsFormText('', $name, $this->lgMot1, $this->lgMot2, $mot);
                $tbl->addElement($inpMot, ++$col,  $k);
            }
            
           $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS . " : ", $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
           $inpPoints->setMinMax(-30,30);
           $tbl->addElement($inpPoints, ++$col, $k); 

           $name = $this->getName($k, 'weight');
           $inpWeight = new XoopsFormNumber('', $name, $this->lgPoints, $this->lgPoints, $weight);
           $inpWeight->setMinMax(0, 1000);
           $tbl->addElement($inpWeight, ++$col, $k); 
           
         }
        
        //----------------------------------------------------------
        $this->trayGlobal->addElement($tbl);
		return $this->trayGlobal;
	}


/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler;

        //--------------------------------------------------------   
        $propos = array();
        $weight = 10;     
   
        foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            $tMots = array(); 
            foreach ($ans['mots'] as $keyMot=>$mot){
                $mot = trim($mot);
                if ($mot != ''){
                    $tMots[] = $mot;
                }
            }
            
            if (count($tMots) > 0){
//echo "<hr>mots : " .  implode(',', $tMots);          
            	$ansObj->setVar('answer_proposition', implode(',', $tMots));
            	$ansObj->setVar('answer_points', $ans['points']);
            	$ansObj->setVar('answer_weight', $weight += 10);
                
            	$ansObj->setVar('answer_caption', '');
            	$ansObj->setVar('answer_inputs', 0);
    	
                $ret = $answersHandler->insert($ansObj);
            }
        }
        //$this->echoAns ($answers, $questId, $bExit = true);    
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

    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $exp = str_replace(',', ' - ', $ans['proposition']);
        $points = $ans['points'];

        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $exp, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $exp, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $exp, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }

	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe


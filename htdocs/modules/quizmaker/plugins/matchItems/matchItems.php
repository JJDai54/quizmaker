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
var $nbMaxList = 5;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("matchItems", 0, "other");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['nbMaxList' => $this->nbMaxList];
        for ($h = 0; $h < $this->nbMaxList; $h++){
          $this->optionsDefaults["list{$h}_type"] = 0;
          $this->optionsDefaults["list{$h}_title"] = '';
          $this->optionsDefaults["list{$h}_intrus"] = '';
          $this->optionsDefaults["list{$h}_width"] = 25;
          $this->optionsDefaults["list{$h}_textalign"] = 'left';
        }
                                  
        $this->hasImageMain = true;
        $this->hasShuffleAnswers = true;
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
      
      $typeArr = array(0 => _LG_PLUGIN_MATCHITEMS_LABEL,
                       1 => _LG_PLUGIN_MATCHITEMS_LISTBOX, 
                       2 => _LG_PLUGIN_MATCHITEMS_TEXTBOX,
                       3 => _LG_PLUGIN_MATCHITEMS_CONJONCTION);

      $textalignArr = array ('left'   => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_LEFT,
                       'center' => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_CENTER,
                       'right'  => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_DROITE);  
                 
      //--------------------------------------------------------------------    
      for ($h = 0; $h < $this->nbMaxList; $h++){
          $j = $h+1;
          $name = "list{$h}_type";  
          $inpTypeList = new \XoopsFormRadio(sprintf(_LG_PLUGIN_MATCHITEMS_TYPE_LIST,$j), "{$optionName}[{$name}]", $tValues[$name], ' ');   
          $inpTypeList->addOptionArray($typeArr);
          $trayOptions ->addElement($inpTypeList);  
          
          $name = "list{$h}_textalign";  
          $inpTextalign = new \XoopsFormRadio(sprintf(_LG_PLUGIN_MATCHITEMS_TEXTALIGN,$j), "{$optionName}[{$name}]", $tValues[$name], ' ');   
          $inpTextalign->addOptionArray($textalignArr);
          $trayOptions ->addElement($inpTextalign);  
          
          $name = "list{$h}_width";  
          $inpWidth = new \XoopsFormNumber(_LG_PLUGIN_MATCHITEMS_TITLE_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
          $inpWidth->setExtra("style='background:#FFCC99;'");
          $inpWidth->setMinMax(5, 50, _AM_QUIZMAKER_UNIT_PERCENT);
          $trayOptions ->addElement($inpWidth);  
          
          $name = "list{$h}_title"; 
          $inpµIntrus = new \XoopsFormText(sprintf(_LG_PLUGIN_MATCHITEMS_TITLE_LIST,$j), "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
          $inpµIntrus->setExtra("style='background:" . self::bgColor1 . ";'");
          $trayOptions->addElement($inpµIntrus);
          
          $name = "list{$h}_intrus"; 
          $inpµIntrus = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_INTRUS, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
          $inpµIntrus->setExtra("style='background:" . self::bgColor2 . ";'");
          $trayOptions->addElement($inpµIntrus);
           
          $trayOptions ->addElement(new XoopsFormLabel('<hr>', ''));   
      }
      
      
      $trayOptions->addElement(new \XoopsFormHidden('nbMaxList', $this->nbMaxList));
      return $trayOptions;
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $questionsHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        
        //recuperation des titres de colonnes
        $quest =  $questionsHandler->get($questId, 'quest_options');
        //echoArray($titlesArr);      
//         
//         $answers = $answersHandler->getListByParent($questId);
//         $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  


        //-------------------------------------------------
        //element definissat un objet ou un ensemble
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray();
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(1, 'text-align:left;width:50px;');
        
        // titre des colonnes et des listes
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        //if(!$options) $options = $this->optionsDefaults;
          $tbl->addTitle('');        
          for($h = 0; $h < $this->nbMaxList; $h++){
              $j = $h+1;
              $title = (isset($options["list{$h}_title"])) ? $options["list{$h}_title"] : sprintf(_LG_PLUGIN_MATCHITEMS_TITLE_DEFAULT,$j);
              $tbl->addTitle($title);        
          }
          $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_POINTS);        
          $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_WEIGHT);        
      
        
        for($k = 0; $k < $this->maxPropositions; $k++){
            $vArr = $this->getAnswerValues($answers[$k], $weight,1);
            foreach($vArr as $key=>$value) $$key = $value;
            if($isNew) $tExp = array('','','','','','');
            else $tExp = explode(',', $proposition);
            //----------------------------------------------------------------------- 
            
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");
            
            $name = $this->getName($k, 'points');
            $inpPoints = new XoopsFormNumber('', $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(1, 30);
            
            $name = $this->getName($k, 'weight');
            $inpWeight = new XoopsFormNumber('', $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);

            $col=0;

            $tbl->addElement($inpLab, $col++, $k);
            
            for($h = 0; $h < $this->nbMaxList; $h++){
              $name = $this->getName($k, "exp", $h); 
              $inpExp = new XoopsFormText('', $name, $this->lgMot1, $this->lgMot2, $tExp[$h]);            
              $tbl->addElement($inpExp, $col++, $k);
            }
            
            
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
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler;
        //$this->echoAns ($answers, $questId, $bExit = true); 
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
        foreach ($answers as $key=>$value){
        
            for($h = 0; $h < $this->nbMaxList; $h++){
                $answers[$key]['exp'][$h] = trim($answers[$key]['exp'][$h]);
            }
            // si la première expression est vide la proposition n'est pas enregistrée
            if ($answers[$key]['exp'][0] === '') continue;
            
          	$ansObj = $answersHandler->create();
          	$ansObj->setVar('answer_quest_id', $questId);

          	$ansObj->setVar('answer_proposition', implode(',', $answers[$key]['exp']));
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
  global $answersHandler, $questionsHandler;
  
  
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        $globalPoints = $quest->getVar('quest_points');
//echoArray($options);
    $scoreMax = 0;
    $scoreMin = 0;

    $tplnumbering   = "<td style='width:3%;text-align:right;'>{numbering}</td>"; 
    $tplBasic = "<td style='width:{width}%;' {alignement}>{itemValue}</td>";
    $tplPoints = "<td style='width:15%;' {alignement}>===>{points} " . _CO_QUIZMAKER_POINTS . "</td>";
    $tplSep = "<td style='width:10%;text-align:center'>-</td>";
     
    $htmlArr = [];
    $htmlArr[] = '<center><table>';



    //////////////////////////////////////
    $answersAll = $answersHandler->getListByParent($questId);
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $scoreMax += $ans['points'];

        $htmlArr[] = '<tr>';
        
        $tExp = explode(',', $ans['proposition']);
//echo $ans['proposition'] . '<br>';        
        for($h = 0; $h < count($tExp); $h++){
            if($tExp[$h]){
              if( $h > 0 ) $htmlArr[] = $tplSep;
              $item = str_replace('{itemValue}', $tExp[$h], $tplBasic);
              $htmlArr[] = $item;
            }
        }
        if($globalPoints == 0){
            $htmlArr[] = str_replace('{points}', $ans['points'], $tplPoints);
        }
        $htmlArr[] = '</tr>';
        
    }
    
    /////////////////////////////////////
    $htmlArr[] = '</table></center>';
   
    //return "en construction";
    $ret['answers'] = implode("\n", $htmlArr);
    $ret['scoreMax'] = ($globalPoints == 0) ? $scoreMax : $globalPoints;
    $ret['scoreMin'] = $scoreMin;
    return $ret;
      
  }
/* ********************************************
*
*********************************************** */
  public function getSolutions_old($questId, $boolAllSolutions = true){
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




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
var $nbMaxColumns = 5;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("matchItems", 0, "other");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['nbColumns' => 2,
                                  'nbMaxColumns' => $this->nbMaxColumns];
        for ($h = 0; $h < $this->nbMaxColumns; $h++){
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
      //$trayOptions = new XoopsFormElementTray($caption, '<br>');  
      $trayOptions = $this->getNewXFTableOptions($caption);
       
        
      $typeArr = array(0 => _LG_PLUGIN_MATCHITEMS_LABEL,
                       1 => _LG_PLUGIN_MATCHITEMS_LISTBOX, 
                       2 => _LG_PLUGIN_MATCHITEMS_TEXTBOX,
                       3 => _LG_PLUGIN_MATCHITEMS_CONJONCTION);

      $textalignArr = array ('left'   => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_LEFT,
                             'center' => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_CENTER,
                             'right'  => _LG_PLUGIN_MATCHITEMS_TEXTALIGN_DROITE);  
      $k=0;           
      //--------------------------------------------------------------------    
      $name = "nbColumns";  
      $nbColumns = $tValues[$name];
      $inpNbColumns = new \XoopsFormNumber(_LG_PLUGIN_MATCHITEMS_NB_COLUMN,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpNbColumns->setExtra("style='background:#FFCC99;'");
      $inpNbColumns->setMinMax(2, $this->nbMaxColumns, _LG_PLUGIN_MATCHITEMS_COLUMNS);
      $inpNbColumns->setDescription(_LG_PLUGIN_MATCHITEMS_NB_COLUMN_DESC);
      //$trayOptions->addXoopsForm($inpNbColumns);  
      
//       $trayOptions->addElement (new \XoopsFormLabel(_LG_PLUGIN_MATCHITEMS_NB_COLUMN),0,$k);
//       $trayOptions->addElement($inpNbColumns,1,$k);
//       
//       //$trayOptions->addElement(_LG_PLUGIN_MATCHITEMS_NB_COLUMN,0,$k);
//       $trayOptions->addElement(new \XoopsFormLabel(_LG_PLUGIN_MATCHITEMS_NB_COLUMN_DESC),1,$k);      
          
      $trayOptions->addElementOption($inpNbColumns);      
    //--------------------------------------------------

      for ($h = 0; $h < $nbColumns; $h++){
          $j = $h+1;
      //$trayOptions->insertBreak("<div style='background:green;'><hr></div>");
      $trayOptions->insertBreak("<hr><div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'>" . sprintf(_LG_PLUGIN_MATCHITEMS_COLUMNS_NUM, $j) . "</div>");
      //  $trayOptions->addElementOption(sprintf(_LG_PLUGIN_MATCHITEMS_COLUMNS_NUM, $j));      
      
          $name = "list{$h}_type";  
          $inpTypeList = new \XoopsFormRadio(_LG_PLUGIN_MATCHITEMS_TYPE_COLLUMN, "{$optionName}[{$name}]", $tValues[$name], ' ');   
          $inpTypeList->addOptionArray($typeArr);
          $trayOptions->addElementOption($inpTypeList);      
  


          
          $name = "list{$h}_textalign";  
          $inpTextalign = new \XoopsFormRadio(_LG_PLUGIN_MATCHITEMS_TEXTALIGN, "{$optionName}[{$name}]", $tValues[$name], ' ');   
          $inpTextalign->addOptionArray($textalignArr);
          $trayOptions->addElementOption($inpTextalign);  
          
          $name = "list{$h}_width";  
          $inpWidth = new \XoopsFormNumber(_LG_PLUGIN_MATCHITEMS_TITLE_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
          $inpWidth->setExtra("style='background:#FFCC99;'");
          $inpWidth->setMinMax(5, 50, _AM_QUIZMAKER_UNIT_PERCENT);
          $trayOptions->addElementOption($inpWidth);  
          
          $name = "list{$h}_title"; 
          $inpµIntrus = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_TITLE_LIST, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
          $inpµIntrus->setExtra("style='background:" . self::bgColor1 . ";'");
          $trayOptions->addElementOption($inpµIntrus);
          
          $name = "list{$h}_intrus"; 
          $inpµIntrus = new \XoopsFormText(_LG_PLUGIN_MATCHITEMS_INTRUS, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
          $inpµIntrus->setExtra("style='background:" . self::bgColor2 . ";'");
          $trayOptions->addElementOption($inpµIntrus);
           
          //$trayOptions ->addElement(new XoopsFormLabel('<hr>', ''));   
      }


    //--------------------------------------------------
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
        //echoArray($titlesArr);      
//         
//         $answers = $answersHandler->getListByParent($questId);
//         $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  


        //-------------------------------------------------
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        $nbColumns = (isset($options['nbColumns'])) ? $options['nbColumns'] : $this->nbMaxColumns;
        //element definissat un objet ou un ensemble
        $weight = 0;
        //$tbl = $this->getNewXoopsTableXtray();
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        //$tbl->addTdStyle(0, 'text-align:left;width:50px;');
        // titre des colonnes et des listes
        
        //if(!$options) $options = $this->optionsDefaults;
          $tbl->addTitle('');        
          //for($h = 0; $h < $this->nbMaxColumns; $h++){
          for($h = 0; $h < $nbColumns; $h++){
              $j = $h+1;
              $title = (isset($options["list{$h}_title"])) ? $options["list{$h}_title"] : sprintf(_LG_PLUGIN_MATCHITEMS_TITLE_DEFAULT,$j);
              $tbl->addTitle("[#{$j}] {$title}" );        
              
              $width = $options["list{$h}_width"];
              $tbl->addTdStyle($j, "width:{$width}%;");
        //echo "<hr>column {$j} - width : {$width}<hr>";
          }
          $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_POINTS);        
          $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_WEIGHT);        
      
        
        for($k = 0; $k < $this->maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            if($isNew) $tPropos = array('','','','','','');

            //----------------------------------------------------------------------- 
            
            $name = $this->getName($k, 'points');
            $inpPoints = new XoopsFormNumber('', $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(1, 30);
            
            $name = $this->getName($k, 'weight');
            $inpWeight = new XoopsFormNumber('', $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);

            //--------------------------------------------------------------
            for($h = 0; $h < $nbColumns; $h++){
              $name = $this->getName($k, "exp", $h); 
              $inpExp = new XoopsFormText('', $name, $this->lgMot1, $this->lgMot2, $tPropos[$h]);       
              
              //peti cacul simpliste pour donner un aperçu de la largeur des colonnes dans le quiz
              //l'environnement étant différent, c'est juste une approche du résultat finel
              $width = $options["list{$h}_width"];
              $inpWidth = ($width < 20) ? 60 : 80;
              $inpExp->setExtra("style='text-align:left;width:{$inpWidth}%;'");

                   
              $tbl->addElement($inpExp, ++$col, $k);
            }
            
            
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);

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
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
        foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
        
            for($h = 0; $h < $this->nbMaxColumns; $h++){
                $answers[$key]['exp'][$h] = trim($answers[$key]['exp'][$h]);
            }
            // si la première expression est vide la proposition n'est pas enregistrée
            if ($answers[$key]['exp'][0] === '') continue;
            
          	$ansObj->setVar('answer_proposition', implode(',', $answers[$key]['exp']));
          	$ansObj->setVar('answer_points', $ans['points']);
          	$ansObj->setVar('answer_weight', $ans['weight']);
              
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




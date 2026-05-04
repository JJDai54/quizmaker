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
class Plugin_puzzle extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("puzzle", 0, "images");
        $this->setVersion('1.0', '2026-02-14', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;
        $this->hasImageMain = true;
        //$this->$maxPropositions = 12;
        
        $this->optionsDefaults = ['variant'     => $this::noClass, 
                                  'allowNext'   => 0,
                                  'gameWidth'   => 300,
                                  'imgRows'     => 5,
                                  'imgCols'     => 5,
                                  'doublons'    => 2,
                                  'preview'     => 5,
                                  'tempo'       => 2,
                                  'gameCols'    => 5,
                                  'mode'        => 0,
                                  'background'  => '#15ff15',
                                  'marge'       => 1,
                                  'radius'      => 0,
                                  'rotation'    => 0,
                                  'level'       => 2,
                                  'nextSlideMessageWinner' => (defined('_AM_QUIZMAKER_NEXT_SLIDE_WINNER_0') ? _AM_QUIZMAKER_NEXT_SLIDE_WINNER_0 : ''),
                                  'nextSlideMessageLooser' => (defined('_AM_QUIZMAKER_NEXT_SLIDE_LOOSER_0') ? _AM_QUIZMAKER_NEXT_SLIDE_LOOSER_0 : ''),
                                  'nextSlideDelai'         => 0,
                                  'nextSlideBG'            =>'#FFCC00',
                                  'maxAttemps'  => 0];


        $this->hasImageMain = true;

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
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------           
     
      $name = 'variant';
      $inpClasse = new \XoopsFormSelect(_LG_PLUGIN_PUZZLE_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
      if (!$tValues[$name] || $tValues[$name] == $this::noClass) $inpClasse->addOption($this::noClass, _LG_PLUGIN_SORTITEMS_VARIANT_SELECT);
      $inpClasse->addOption('puzzle', _LG_PLUGIN_PUZZLE_PUZZLE);
      $inpClasse->addOption('taquin', _LG_PLUGIN_PUZZLE_TAQUIN);
      $inpClasse->addOption('memory', _LG_PLUGIN_PUZZLE_MEMORY);
      $inpClasse->addOption('lucioles', _LG_PLUGIN_PUZZLE_LUCIOLES);

      //$inpClasse->setDescription(_LG_PLUGIN_SORTITEMS_VARIANT_DESC);
      // change la couleur de fond selon que la variante a été selectionnée ou pas
      if($tValues['variant'] == $this::noClass){ 
            $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
      }else{
            $inpClasse->setExtra('style="background:lime;"');
      }
      $trayOptions->addElementOption($inpClasse, true);     

 /*
      $name = 'variant';
      $inpClasse = new \XoopsFormSelect(_LG_PLUGIN_PUZZLE_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
      if (!$tValues[$name] || $tValues[$name] == $this::noClass) $inpClasse->addOption($this::noClass, _LG_PLUGIN_SORTITEMS_VARIANT_SELECT);
      $inpClasse->addOption('puzzle', _LG_PLUGIN_PUZZLE_PUZZLE);
      $inpClasse->addOption('taquin', _LG_PLUGIN_PUZZLE_TAQUIN);
      $trayOptions->addElementOption($inpClasse, true);
 */    
      
      $name = 'gameWidth';  
      $inpInpWidth = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_IMG_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpInpWidth->setMinMax(200, 600, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpInpWidth->setDescription(_LG_PLUGIN_PUZZLE_IMG_WIDTH_DESC);
      $trayOptions->addElementOption($inpInpWidth);     
      
      $name = 'preview';  
      $inpPreview = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_PREVIEW,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpPreview->setMinMax(0, 60, _AM_QUIZMAKER_UNIT_SECONDS, "0.5");
      $inpPreview->setDescription(_LG_PLUGIN_PUZZLE_PREVIEW_DESC);
      $trayOptions->addElementOption($inpPreview);     
      
      //-----------------------------------------
      if ($tValues['variant'] != 'lucioles'){
          $trayImgGrid = new \XoopsFormElementTray(_LG_PLUGIN_PUZZLE_IMG_GRID, '&nbsp;');
          
            $name = 'imgCols';  
            $inpCols = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpCols->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
            $trayImgGrid->addElement($inpCols);    
          
            $name = 'imgRows';  
            $inpRows = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpRows->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_ROWS, "1");
            //$inpRows->setDescription(_LG_PLUGIN_PUZZLE_ROWS_DESC);
            $trayImgGrid->addElement($inpRows);     
           
          $trayOptions->addElementOption($trayImgGrid);     
      }
      //-----------------------------------------

      $name = 'background';  
      $inpBackground = new XoopsFormColorPicker(_LG_PLUGIN_PUZZLE_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
      
      $name = 'marge';  
      $inpMarge = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_MARGE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMarge->setMinMax(0, 4, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpMarge->setDescription(_LG_PLUGIN_PUZZLE_MARGE_DESC);
      $trayOptions->addElementOption($inpMarge);     
      
      $name = 'radius';  
      $inpRadius = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_BORDER_RADIUS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpRadius->setMinMax(0, 12, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpRadius->setDescription(_LG_PLUGIN_PUZZLE_BORDER_RADIUS_DESC);
      $trayOptions->addElementOption($inpRadius);     
      
      $name = 'maxAttemps';  
      $inpMaxAttemps = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpMaxAttemps);     
      
       $name = 'allowNext';  
//       $inpAllowNext = new \XoopsFormRadioYN(_LG_PLUGIN_PUZZLE_ALLOW_NEXT, "{$optionName}[{$name}]", $tValues[$name]);
       $inpAllowNext = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
       $trayOptions->addElementOption($inpAllowNext);   
      
      //--------------------------------   
      //insertion des messages de transition
      $prefixPluginWinner  = '_LG_PLUGIN_SELECTINPUTS_NEXT_SLIDE';
      $prefixPluginLlooser = '_LG_PLUGIN_SELECTINPUTS_NEXT_SLIDE';
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_transition.php");

if ($tValues['variant'] == 'puzzle' || $tValues['variant'] == 'memory'){
$trayOptions->insertBreak("<hr><div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'>" . _LG_PLUGIN_PUZZLE_OPTIONS . "</div>");  
}
      switch($tValues['variant']){ // correspond au nom des images dans "plugins\sortItems\img\classes"
        case 'puzzle' : 
            $name = 'mode';  
            $inpMode = new \XoopsFormRadio(_LG_PLUGIN_PUZZLE_MODE, "{$optionName}[{$name}]", $tValues[$name]);
            $inpMode->addOptionArray([0 => _LG_PLUGIN_PUZZLE_MODE0,
                                      1 => _LG_PLUGIN_PUZZLE_MODE1]);
            $trayOptions->addElementOption($inpMode);     
    
            $name = 'rotation';
            if($tValues[$name] > 1) $tValues[$name] = 1; //pour compatibilité
            $inpRotation = new \XoopsFormRadioYN(_LG_PLUGIN_ROTATION, "{$optionName}[{$name}]", $tValues[$name]);
            $inpRotation->setDescription(_LG_PLUGIN_ROTATION_DESC);
            $trayOptions->addElementOption($inpRotation);     

//             $name = 'rotation';
//             $inpRotation = new \XoopsFormSelect(_LG_PLUGIN_ROTATION, "{$optionName}[{$name}]", $tValues[$name]);
//             $inpRotation->addOption(0, _LG_PLUGIN_ROTATION_NONE);
//             $inpRotation->addOption(1, _LG_PLUGIN_ROTATION_090);
//             $inpRotation->addOption(2, _LG_PLUGIN_ROTATION_180);
//             $inpRotation->setDescription(_LG_PLUGIN_ROTATION_DESC);
//             $trayOptions->addElementOption($inpRotation);     
            
            break;
            
        case 'lucioles' : 
        case 'memory' : 
            $name = 'doublons';  
            $inpDoublons = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_DOUBLONS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpDoublons->setMinMax(2, 5, '', "1");
            $inpDoublons->setDescription(_LG_PLUGIN_PUZZLE_GRID_COLS_DESC);
            $trayOptions->addElementOption($inpDoublons);     

            $name = 'gameCols';  
            $inpCols = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_COLS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpCols->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
            $inpCols->setDescription(_LG_PLUGIN_PUZZLE_GRID_COLS_DESC);
            $trayOptions->addElementOption($inpCols);     

            $name = 'tempo';  
            $inpDoublons = new \XoopsFormNumber(_LG_PLUGIN_PUZZLE_TEMPO,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpDoublons->setMinMax(0, 5, _AM_QUIZMAKER_UNIT_SECONDS, "0.5");
            $inpDoublons->setDescription(_LG_PLUGIN_PUZZLE_TEMPO_DESC);
            $trayOptions->addElementOption($inpDoublons);     
            break;
            /* *********************************************************** */  
     }   
     
     
        
//       if($tValues['variant'] == "puzzle"){
//       }else{
//           $name = 'mode';  
//           $inpMode = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
//       } 
      
          $name = 'level';  
          $inpLevel = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpLevel);
           
      return $trayOptions;
    }
    
/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $options = $this->getOptionsByQuestId($questId);
        //-------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        
       // $i = $this->getFormGroup($trayAllAns, 0, $answers,'', 0, $this->maxPropositions);        

//===============================================
        
//         if($isImage){
//             $tbl->addTitleArray(['',_LG_PLUGIN_SORTITEMS_IMAGE_TO_SORT,_AM_QUIZMAKER_PLUGIN_LABEL,_LG_PLUGIN_SORTITEMS_IMAGE_REFERANTE,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
//         }else{
//             $tbl->addTitleArray(['',_AM_QUIZMAKER_PLUGIN_LABEL,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
//         }
//echoArray($answers);
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
/*
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
       
        $k = 0; //il ny a qu'une seule image a charger
        $ans = (isset($answers[$k])) ? $answers[$k] : null;
        include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");

        $name = $this->getName($k, 'proposition');
        $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot3, $proposition);
        
        $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
        
        if($points < 1) $points = 1;
        $inpPoints = new \XoopsFormNumber('',  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
        $inpPoints->setMinMax(1, 30);
        
        //----------------------------------------------------------
        //$cols=0;
        $tbl->addElement($inpImage1,  ++$col, $k);
        $tbl->addElement($inpProposition, ++$col, $k); 
        if($options['variant'] == 'memory'){
            $inpInfo = new \XoopsFormLabel('', _LG_PLUGIN_PUZZLE_POINTS_BY_IMG);
            $tbl->addElement($inpInfo, $col, $k); 
        }
        $tbl->addElement($inpPoints, ++$col, $k); 
        $this->trayGlobal->addElement($tbl);
*/
        if($options['variant'] == 'lucioles'){
            $this->getFormLucioles($answers, $path, $options);
        }else{
            $this->getFormPuzzles($answers, $path, $options);
        }
//===============================================

                    
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 public function getFormLucioles($answers, $path, $options){
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
        
        for ($k = 0; $k < $this->maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");

            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot3, $proposition);
            
            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
            
            if($points < 1) $points = 1;
            $inpPoints = new \XoopsFormNumber('',  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(1, 30);
            
            //----------------------------------------------------------
            //$cols=0;
            $tbl->addElement($inpImage1,  ++$col, $k);
            $tbl->addElement($inpProposition, ++$col, $k); 
            if($options['variant'] == 'memory'){
                $inpInfo = new \XoopsFormLabel('', _LG_PLUGIN_PUZZLE_POINTS_BY_IMG);
                $tbl->addElement($inpInfo, $col, $k); 
            }
            $tbl->addElement($inpPoints, ++$col, $k); 
        }
 
        $this->trayGlobal->addElement($tbl);
 }
 
 
/* *************************************************
*
* ************************************************** */
 public function getFormPuzzles($answers, $path, $options){
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');

        $k = 0; //il ny a qu'une seule image a charger
        $ans = (isset($answers[$k])) ? $answers[$k] : null;
        include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");

        $name = $this->getName($k, 'proposition');
        $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot3, $proposition);
        
        $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
        
        if($points < 1) $points = 1;
        $inpPoints = new \XoopsFormNumber('',  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
        $inpPoints->setMinMax(1, 30);
        
        //----------------------------------------------------------
        //$cols=0;
        $tbl->addElement($inpImage1,  ++$col, $k);
        $tbl->addElement($inpProposition, ++$col, $k); 
        if($options['variant'] == 'memory'){
            $inpInfo = new \XoopsFormLabel('', _LG_PLUGIN_PUZZLE_POINTS_BY_IMG);
            $tbl->addElement($inpInfo, $col, $k); 
        }
        $tbl->addElement($inpPoints, ++$col, $k); 
        $this->trayGlobal->addElement($tbl);
    }
    
    
/* *************************************************
*
* <img src="images/quiz-1068-16990f8e4c0c64.jpg width='120px'">
*                  quiz-1068-16990f8e4c0c64
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        //echoArray('GPF');// exit;    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------  
        $pathImg = $quizHandler->getFolderJS($quizId, 1, 'images');  
             
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            $ans['proposition']  = FQUIZMAKER\sanityse_inpValue($ans['proposition']);
            //if($ans['proposition'] != '') $ans['proposition'] = "zzz";


            //enregistrement de l'image
            //if($_FILES['answers'][name] != '') 
            //recuperation de l'image pour le champ proposition
            //le chrono ne correspond pas forcément à la clé dans files
            //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
            //un pour le champ "proposition" qui stocke l'image principale
            //et un pour le champ imge qui stocke l'image de substitution
            
            $prefix = "quiz-{$questId}-{$ans['chrono']}";        
            $imgFormName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $imgFormName, $pathImg, $prefix, $nameOrg);
            
            //echo "imgFormName = {$imgFormName}<br>newImage1 = {$newImg}"; exit;
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);       
            }else{
                $ansObj->setVar('answer_image1', $newImg);        
                //if(!$ans['proposition']) $ans['proposition'] = $nameOrg;
            }


             
      		$ansObj->setVar('answer_proposition', $ans['proposition']);
      		$ansObj->setVar('answer_points', $ans['points']);
      		$ansObj->setVar('answer_weight', 0);
              
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

} // fin de la class

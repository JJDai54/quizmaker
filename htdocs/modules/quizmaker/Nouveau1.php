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
class Plugin_memosuite extends XoopsModules\Quizmaker\Plugins
{
var $messagegArr = array();
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("memosuite", 0, "games");
        $this->setVersion('1.0', '2026-05-14', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;
        $this->hasImageMain = true;
        //$this->$maxPropositions = 12;
        
        $this->optionsDefaults = ['variant'        => $this::noClass, 
                                  'allowNext'      => 0,
                                  'imgRows'        => 5,
                                  'imgCols'        => 5,
                                  'sequenceLength' => 5,
                                  'preview'        => 5,
                                  'tempo'          => 2,
                                  'gameCols'       => 5,
                                  'background'     => '#15ff15',
                                  'sequenceH'      => 80,
                                  'sequenceWidth'  => 500,
                                  'gridWidth'      => 300,
                                  'gap'            => 5,
                                  'radius'         => 0,
                                  'minoration'     => 0,
                                  'tempoSequence'  => 1,
                                  'retrySequence'  => 0,
                                  'maxAttempts'    => 0];

        //--------------------------------------------------------------
        $this->addMessages(['newSequence','atYou','getSeqence','bingo','noSequence','attemptsOut', 'lost', 'attemptsNum']); //
        $this->hasImageMain = false;
        $this->hasGlobalPoints = false;        
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
      $variant = $tValues[$name];
      $inpClasse = new \XoopsFormSelect(_AM_QUIZMAKER_VARIATION, "{$optionName}[{$name}]", $variant);
      if (!$variant || $variant == $this::noClass) $inpClasse->addOption($this::noClass, _AP_QUIZMAKER_VARIANT_SELECT);
      $inpClasse->addOption('grille', _AM_QUIZMAKER_IMG_GRID);
      $inpClasse->addOption('liste', _AM_QUIZMAKER_IMG_LIST);

      if($variant == $this::noClass){ 
            $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
            $trayOptions->addElementOption($inpClasse, true);     
            return $trayOptions;
      }else{
            $inpClasse->setExtra('style="background:lime;"');
            $trayOptions->addElementOption($inpClasse, true);     
      }
      //-------------------------------------------
      if ($variant == 'grille'){
           //--------------------------------------------------------------
          $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _LG_PLUGIN_MEMOSUITE_OPTIONS_GRILLE_SOURCE));  
           //--------------------------------------------------------------
          $trayImgGrid = new \XoopsFormElementTray(_AP_QUIZMAKER_IMG_GRID, '&nbsp;');
          
            $name = 'imgCols';  
            $inpCols = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpCols->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
            $trayImgGrid->addElement($inpCols);    
          
            $name = 'imgRows';  
            $inpRows = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpRows->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_ROWS, "1");
            //$inpRows->setDescription(_LG_PLUGIN_MEMOSUITE_ROWS_DESC);
            $trayImgGrid->addElement($inpRows);     
           
          $trayOptions->addElementOption($trayImgGrid);     
      }

       //--------------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _LG_PLUGIN_MEMOSUITE_OPTIONS_GAME));  
       //--------------------------------------------------------------
      $name = 'sequenceLength';  
      $inpSequenceLength = new \XoopsFormNumber(_LG_PLUGIN_MEMOSUITE_NB_IMG,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpSequenceLength->setMinMax(3, 12, _AM_QUIZMAKER_UNIT_IMG, "1");
      $inpSequenceLength->setDescription(_LG_PLUGIN_MEMOSUITE_NB_IMG_DESC);
      $trayOptions->addElementOption($inpSequenceLength); 

      $name = 'retrySequence';
      $inRetrySequence = new \XoopsFormSelect(_LG_PLUGIN_MEMOSUITE_MODE, "{$optionName}[{$name}]", $tValues[$name]);
      $inRetrySequence->setDescription(_LG_PLUGIN_MEMOSUITE_MODE_DESC);
      $inRetrySequence->addOption(0, _LG_PLUGIN_MEMOSUITE_MODE_0);
      $inRetrySequence->addOption(1, _LG_PLUGIN_MEMOSUITE_MODE_1);
      $inRetrySequence->addOption(2, _LG_PLUGIN_MEMOSUITE_MODE_2);
      $trayOptions->addElementOption($inRetrySequence);    
 
      $name = 'tempoSequence';  
      $inpTempoSuite = new \XoopsFormNumber(_LG_PLUGIN_MEMOSUITE_TEMPO_SUITE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpTempoSuite->setMinMax(0.2, 5, _AM_QUIZMAKER_UNIT_SECONDS, "0.1");
      $inpTempoSuite->setDescription(_LG_PLUGIN_MEMOSUITE_TEMPO_SUITE_DESC);
      $trayOptions->addElementOption($inpTempoSuite);     

     
      $name = 'minoration';  
      $inpMinusPoints = new \XoopsFormNumber(_AP_QUIZMAKER_MINORATION,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMinusPoints->setMinMax(0, 5, _AM_QUIZMAKER_UNIT_POINTS, "1");
      $inpMinusPoints->setDescription(_AP_QUIZMAKER_MINORATION_DESC);
      $trayOptions->addElementOption($inpMinusPoints);     
      
       $name = 'allowNext';  
//       $inpAllowNext = new \XoopsFormRadioYN(_LG_PLUGIN_MEMOSUITE_ALLOW_NEXT, "{$optionName}[{$name}]", $tValues[$name]);
       $inpAllowNext = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
       $trayOptions->addElementOption($inpAllowNext);   
      

       //--------------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AP_QUIZMAKER_OPTIONS));  
       //--------------------------------------------------------------




      $name = 'maxAttempts';
      $inpMaxAttemps = new \XoopsFormNumber(_AP_QUIZMAKER__MAXTRY,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name], 'style="background:#FFCC66;"');
      $inpMaxAttemps->setMinMax(-1, 54, _AM_QUIZMAKER_UNIT_ATTEMPTS);
      $inpMaxAttemps->setDescription(_LG_FINDOBJECTS_IMAGES_ATTEMPTS_MAX_DESC);
      $trayOptions ->addElementOption($inpMaxAttemps);  

       //--------------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _LG_PLUGIN_MEMOSUITE_OPTIONS_GRILLE_CIBLE));  
       //--------------------------------------------------------------
define('_AP_QUIZMAKER_SEQUENCE_WIDTH', "Largeur de la séquence");
define('_AP_QUIZMAKER_GRID_WIDTH', "Largeur de la grille d'images");

      $name = 'gridWidth';  
      $inpGridWidth = new \XoopsFormNumber(_AP_QUIZMAKER_GRID_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGridWidth->setMinMax(200, 600, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpGridWidth->setDescription(_AP_QUIZMAKER_GRID_WIDTH_DESC);
      $trayOptions->addElementOption($inpGridWidth);     

      $name = 'gameCols';  
      $inpCols = new \XoopsFormNumber(_AP_QUIZMAKER_COLS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpCols->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
      $inpCols->setDescription(_AP_QUIZMAKER_COLS_DESC);
      $trayOptions->addElementOption($inpCols);    

      $name = 'gap';  
      $inpGap = new \XoopsFormNumber(_AP_QUIZMAKER_MARGEIN,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGap->setMinMax(0, 8, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpGap->setDescription(_AP_QUIZMAKER_MARGEIN_DESC);
      $trayOptions->addElementOption($inpGap);     
      
      $name = 'radius';  
      $inpRadius = new \XoopsFormNumber(_AP_QUIZMAKER_BORDER_RADIUS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpRadius->setMinMax(0, 12, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpRadius->setDescription(_AP_QUIZMAKER_BORDER_RADIUS_DESC);
      $trayOptions->addElementOption($inpRadius);     
/* a voir si on remplace la class du cs par les optionins ci-dessous
*/      
       
       //--------------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _LG_PLUGIN_MEMOSUITE_OPTIONS_SEQUENCE));  
       //--------------------------------------------------------------
      
      $name = 'sequenceWidth';  
      $inpSequenceWidth = new \XoopsFormNumber(_AP_QUIZMAKER_SEQUENCE_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpSequenceWidth->setMinMax(200, 600, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpSequenceWidth->setDescription(_AP_QUIZMAKER_SEQUENCE_WIDTH_DESC);
      $trayOptions->addElementOption($inpSequenceWidth);     
      
      $name = 'sequenceH';  
      $inpSequenceH = new \XoopsFormNumber(_LG_PLUGIN_MEMOSUITE_SEQUENCE_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpSequenceH->setMinMax(50, 200, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpSequenceH->setDescription(_LG_PLUGIN_MEMOSUITE_SEQUENCE_HEIGHT_DESC);
      $trayOptions->addElementOption($inpSequenceH);     
      
      $name = 'background';  
      $inpBackground = new XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
         
      
      //--------------------------------------------------------------
      //insertion des messages de transition
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_messages.php");
//echoArray($tValues)    ;
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
        
        switch($options['variant']){
          case 'grille':
              $maxProposition = 1;
              break;
          
          case 'liste':
              $maxProposition = $this->maxPropositions;
              break;
              
          case $this::noClass:
          default: 
              return null;
              break;
        }
        //===============================================
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        
        
        
//===============================================
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
        
        for ($k = 0; $k < $maxProposition; $k++){
//        echo "proposition n° {$k}<br>";
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
            if($options['variant'] == 'grille'){
                $inpInfo = new \XoopsFormLabel('', _LG_PLUGIN_MEMOSUITE_POINTS_BY_IMG);
                $tbl->addElement($inpInfo, $col, $k); 
            }
            $tbl->addElement($inpPoints, ++$col, $k); 
        }
//===============================================

                    
        //----------------------------------------------------------
        $this->trayGlobal->addElement($tbl);
		return $this->trayGlobal;
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

            //enregistrement de la taille de l'image dans le buffer
            $imgFile = $pathImg . '/' . $ansObj->getVar('answer_image1');
            $imgInfo = getimagesize($imgFile);
            //echoArray($imgInfo);exit;
      		$ansObj->setVar('answer_buffer', "{$imgInfo[0]}_{$imgInfo[1]}");             

             
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


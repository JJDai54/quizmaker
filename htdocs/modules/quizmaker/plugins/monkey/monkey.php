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
class Plugin_monkey extends XoopsModules\Quizmaker\Plugins
{
var $messagegArr = array();
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("monkey", 0, "games");
        $this->setVersion('1.0', '2026-05-14', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;
        $this->hasImageMain = true;
        $this->hasGlobalPoints = true;
        $this->maxPropositions = 3;
        
        $this->optionsDefaults = ['variant'         => $this::noClass, 
                                  'tblRows'         => 5,
                                  'tblCols'         => 8,
                                  'backgroundColor' => '#FFFFCC',
                                  'maskColor'       => '#FFCCFF',
                                  'fontColor'       => 'black',
                                  'disposition'     => 'disposition-00',
                                  'keepSameGrid'    => 0,
                                  'preview'         => 5,
                                  'maxAttempts'     => 0];

        //--------------------------------------------------------------
        //$this->addMessages(['newSequence','atYou','getSeqence','bingo','noSequence','attemptsOut', 'lost', 'attemptsNum']); //
        $this->addMessages(['atYou', 'replay', 'attempts']); //
       
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
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_variant.php");
      if(!$isSelectOk) return $trayOptions;

      // =======================================================

       //--------------------------------------------------------------
      //$trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _LG_PLUGIN_MONKEY_OPTIONS_GRILLE_SOURCE));  
       //--------------------------------------------------------------
      $trayImgGrid = new \XoopsFormElementTray(_AP_QUIZMAKER_IMG_GRID, '&nbsp;');
      
      $name = 'tblCols';  
      $inpCols = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpCols->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
      $trayImgGrid->addElement($inpCols);    

      $name = 'tblRows';  
      $inpRows = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpRows->setMinMax(1, 16, _AM_QUIZMAKER_UNIT_ROWS, "1");
      //$inpRows->setDescription(_LG_PLUGIN_MONKEY_ROWS_DESC);
      $trayImgGrid->addElement($inpRows);     

      $trayOptions->addElementOption($trayImgGrid);     
       //--------------------------------------------------------------
      $name = 'backgroundColor';
      $inpBackground = new \XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);
       
      $name = 'maskColor';
      $inpMask= new \XoopsFormColorPicker(_AP_QUIZMAKER_MASK_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpMask);
      
      $name = 'fontColor';
      $inpColor = new \XoopsFormColorPicker(_AP_QUIZMAKER_FONT_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpColor);
       //--------------------------------------------------------------

      $name = 'maxAttempts';  
      $inpMaxAttemps = new \XoopsFormNumber(_AP_QUIZMAKER__MAXTRY,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name], 'style="background:#FFCC66;"');
      $inpMaxAttemps->setMinMax(-1, 54, _AM_QUIZMAKER_UNIT_ATTEMPTS);
      $inpMaxAttemps->setDescription(_AP_QUIZMAKER__MAXTRY_DESC);
      $trayOptions ->addElementOption($inpMaxAttemps);  

       
      $name = 'preview';  
      $inpPreview = new \XoopsFormNumber(_AP_QUIZMAKER_IMG_PREVIEW,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpPreview->setMinMax(3, 60, _AM_QUIZMAKER_UNIT_SECONDS, "0.5");
      $inpPreview->setDescription(_AP_QUIZMAKER_IMG_PREVIEW_DESC);
      $trayOptions->addElementOption($inpPreview);     

      $name = 'keepSameGrid'; 
      $inpKeepLastItem = new \XoopsFormRadioYN(_LG_PLUGIN_MONKEY_KEEP_SAME_GRID, "{$optionName}[{$name}]", $tValues[$name]); 
      $inpKeepLastItem->setDescription(_LG_PLUGIN_MONKEY_KEEP_SAME_GRID_DESC);
      $trayOptions->addElementOption($inpKeepLastItem);     

//       $name = 'nbItems';  
//       $inpItems = new \XoopsFormNumber(_LG_PLUGIN_MONKEY_NB_IMG,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpItems->setMinMax(3, 12, _AM_QUIZMAKER_UNIT_ITEMS, "1");
//       $inpItems->setDescription(_LG_PLUGIN_MONKEY_NB_ITEMS_DESC);
//       $trayOptions->addElementOption($inpItems); 
       //--------------------------------------------------------------
      //--------------------------------------------------------------
      //insertion des messages de transition
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_disposition.php");

       //--------------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AP_QUIZMAKER_OPTIONS));  
       //--------------------------------------------------------------


      
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
        
        //===============================================
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        
        
        
//===============================================
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
        $tbl->addTitleArray(['#', 'Suite', 'weight']);
        
        for ($k = 0; $k < $this->maxPropositions; $k++){
//        echo "proposition n° {$k}<br>";
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");

            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot3, $proposition);
            
            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
            

            $inpWeight = new \XoopsFormNumber('',  $this->getName($k,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 100);
            
            //----------------------------------------------------------
            //$cols=0;

            $tbl->addElement($inpProposition, ++$col, $k); 
            $tbl->addElement($inpWeight, ++$col, $k); 
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

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
class Plugin_taquin extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("taquin", 0, "games");
        $this->setVersion('1.0', '2026-02-14', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;
        $this->hasImageMain = true;
        //$this->$maxPropositions = 12;
        
        $this->optionsDefaults = ['variant'     => $this::noClass, 
                                  'allowNext'   => 0,
                                  'gameWidth'   => 300,
                                  'imgRows'     => 5,
                                  'imgCols'     => 5,
                                  'preview'     => 5,
                                  'background'  => '#15ff15',
                                  'gap'         => 3,
                                  'radius'      => 0,
                                  'level'       => 2,
                                  'maxAttempts'  => 0];

        $this->addMessages();

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
     
      $name = 'gameWidth';  
      $inpInpWidth = new \XoopsFormNumber(_AP_QUIZMAKER_GAME_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpInpWidth->setMinMax(200, 600, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpInpWidth->setDescription(_LG_PLUGIN_PUZZLE_IMG__AP_QUIZMAKER_WIDTH_DESC);
      $trayOptions->addElementOption($inpInpWidth);     
      
      $name = 'preview';  
      $inpPreview = new \XoopsFormNumber(_AP_QUIZMAKER_IMG_PREVIEW,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpPreview->setMinMax(0, 60, _AM_QUIZMAKER_UNIT_SECONDS, "0.5");
  $inpPreview->setDescription(_AP_QUIZMAKER_IMG_PREVIEW_DESC);
      $trayOptions->addElementOption($inpPreview);     
      
      //-----------------------------------------

      $trayImgGrid = new \XoopsFormElementTray(_AP_QUIZMAKER_IMG_GRID, '&nbsp;');

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
      //-----------------------------------------

      $name = 'background';  
      $inpBackground = new XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
      
      $name = 'gap';  
      $inpMarge = new \XoopsFormNumber(_AP_QUIZMAKER_MARGEIN,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMarge->setMinMax(0, 8, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpMarge->setDescription(_AP_QUIZMAKER_MARGEIN_DESC);
      $trayOptions->addElementOption($inpMarge);     
      
      $name = 'radius';  
      $inpRadius = new \XoopsFormNumber(_AP_QUIZMAKER_BORDER_RADIUS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpRadius->setMinMax(0, 12, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpRadius->setDescription(_LG_PLUGIN_PUZZLE_BORDER_RADIUS_DESC);
      $trayOptions->addElementOption($inpRadius);     
      
      $name = 'maxAttempts';  
      $inpMaxAttemps = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpMaxAttemps);     
      
       $name = 'allowNext';  
//       $inpAllowNext = new \XoopsFormRadioYN(_LG_PLUGIN_PUZZLE_ALLOW_NEXT, "{$optionName}[{$name}]", $tValues[$name]);
       $inpAllowNext = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
       $trayOptions->addElementOption($inpAllowNext);   
      
      //--------------------------------   
      //insertion des messages de transition
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_messages.php");
            /* *********************************************************** */  
   
     
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


        $this->getFormPuzzles($answers, $path, $options);
//===============================================

                    
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
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
    


  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler,$quizHandler,$questionsHandler;
        
    $questObj = $questionsHandler->get($questId);
    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id', $questId, '='));
    $criteria->setsort("answer_weight");
    $criteria->setOrder("ASC");
    $answers = $answersHandler->getObjects($criteria);
        
    $ansObj  = $answers[0];   
    $pathImg = $quizHandler->getFolderJS($questObj->getVar('quest_quiz_id'), 2, 'images');
    $imgFile = $pathImg . '/' . $ansObj->getVar('answer_image1');

    $html = "<div><center><img src='{$imgFile}' ></center></div>";        
    $points = ($questObj->getVar('quest_points') > 0) ? $questObj->getVar('quest_points') : $ansObj->getVar('answer_points');   
       
    $ret['answers'] = $html;
    $ret['scoreMin'] = 0;
    $ret['scoreMax'] = $points;
    //echoArray($ret);
    return $ret;
     }

} // fin de la class

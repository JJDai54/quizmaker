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
class Plugin_selectImages extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("selectImages", 0, "images");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        
        $this->optionsDefaults = ['inputType'         => 0,
                                  'imgHeight1'        => 64,
                                  'cocheImgName'      => 'coche-01.png',
                                  'cocheImgHeight'    => 25,  
                                  'posLibelleV'       => 30,
                                  'fontSize'          => '1.1',
                                  'repartition'       => '321',
                                  'disposition'       => 'disposition-00',
                                  'nextSlideDelai'   => 0,
                                  'nextSlideBG'      =>'#FFCC00',
                                  'nextSlideMessage' => _AM_QUIZMAKER_NEXT_SLIDE_MSG0];
    
        $this->maxPropositions = 9;	
        $this->hasImageMain = true;
        $this->hasShuffleAnswers = true;
        $this->multiPoints = true;
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
      $name = 'inputType';  
      $inpType = new \XoopsFormRadio(_LG_PLUGIN_SELECTIMAGES_TYPE, "{$optionName}[{$name}]", $tValues[$name]);
      $inpType->addOptionArray([0 => _LG_PLUGIN_SELECTIMAGES_TYPE_0,
                                1 => _LG_PLUGIN_SELECTIMAGES_TYPE_1]);
      $inpType->setDescription(_LG_PLUGIN_SELECTIMAGES_TYPE_DESC);                          
      $trayOptions->addElementOption($inpType);     
      
      // avertissement
      $arrConst = ['nextSlideMessage' => '_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE'];
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_avertissement.php");
      
      //--------------------------------------------------------------------           

      $name = 'imgHeight1';  
      $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_IMG_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 128, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElementOption($inpHeight1);     

      $name = 'cocheImgName'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/coches';
      $inpCocheImg = new \XoopsFormIconSelect("<br>" . _LG_PLUGIN_SELECTIMAGES_COCHE, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpCocheImg->setSelectedIconSize(64, 64);
      $inpCocheImg->setIconSize(64, 64);
      $inpCocheImg->setGridIconNumber(4);
      $trayOptions->addElementOption($inpCocheImg);     

      $name = 'cocheImgHeight';  
      $inpCocheHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_COCHE_IMG_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpCocheHeight1->setMinMax(12, 48, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElementOption($inpCocheHeight1);     
      
      $name = 'posLibelleV';  
      $inpTopLib = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_IMG_TOP,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpTopLib->setDescription(_LG_PLUGIN_SELECTIMAGES_IMG_TOP_DESC);
      $inpTopLib->setMinMax(-150, 150, _AM_QUIZMAKER_UNIT_PERCENT);
      $trayOptions->addElementOption($inpTopLib);     

      $name = 'fontSize';  
      $inpFontSize = new \XoopsFormText(_LG_PLUGIN_SELECTIMAGES_FONT_SIZE, "{$optionName}[{$name}]",5,5, $tValues[$name]);
      $trayOptions->addElementOption($inpFontSize);     
      //$trayOptions->addElement(new \XoopsFormLabel('', _LG_PLUGIN_SELECTIMAGES_FONT_SIZE_DESC));      
      
      $name = 'repartition';  
      $inpRepartition = new \XoopsFormTextPlus(_LG_PLUGIN_SELECTIMAGES_DISPOSITION, "{$optionName}[{$name}]",20,20, $tValues[$name]);
      $inpRepartition->addBtnClear("X");
      $inpRepartition->addBtn("1", '123');
      $inpRepartition->addBtn("2", '232');
      $inpRepartition->addBtn("3", '323');
      $inpRepartition->setHelp(_LG_PLUGIN_SELECTIMAGES_DISPOSITION_DESC . QBR);
      $trayOptions->addElementOption($inpRepartition);     
      
      // disposition 
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_disposition.php");

      return $trayOptions;

    }


/* *************************************************
* le champ inputs sert à différencier la suite logique des mauvaises réponses
*  - 0 : suite logique + weight
*  - 1 : mauvaises reponses
*  Le nom de l'image est stocké dans proposition
*  L'image de substitution est stocké dans le champ image
*  Les items a trouvé sont notés avec des points positifs et seront rempacés par l'image de substitution
*  Il peut y avoir plusieurs items a trouver dans la suite (conseillé : 2 ou 3 max )
*  Le nombre d'image de la séquence est de 8 maximum (voir 10 à tester)
*  Le nombre de fausse images est limité aussi à 8 (voir 10 à tester)
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler;
        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
//echo "<hr>{$path}<hr>";

        //lecture de toutes les proposition et répartition en 
        // - sequence logique
        // - image de remplacement
        // - mauvaise réponses
        $answers = $answersHandler->getListByParent($questId);
        
        //-------------------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path);
        
     
        
        //----------------------------------------------------------------
        $this->initFormForQuestion();

        //-------------------------------------------------
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}
/* *************************************************
* meme procedure pour chaque groupe:
* - image de substitution
* - sequence logique
* - mauvaises reponses
* ************************************************** */
public function getFormGroup(&$trayAllAns, $inputs, $answers,$titleGroup, $firstItem, $maxItems, $path)
{ 
        //suppression des enregistrement en trop
        if(count($answers) > $maxItems) $this->deleteToMuchItems($answers, $maxItems);
        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;

        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            $color = "#000000";
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            $inpPropos = new \XoopsFormText('', $this->getName($k,'proposition'), $this->lgMot2, $this->lgMot2, $proposition);
            //$inpPropos->setExtra('required');
            
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS,  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_WEIGHT,  $this->getName($k,'weight'), $this->lgWeight, $this->lgWeight, $weight += 10);
            $inpWeight->setMinMax(0, 99999);


            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$k}", $path, 60, '<br>',$this->getName($k,'delete_image1'));
            $inpColor = new XoopsFormColorPicker('Text', $this->getName($k,'color'), $color);


            $btnPath = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/buttons';
            $name =  $this->getName($k,'image2');
            $inpImage2 = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_IMAGE, $name, $image2, $btnPath);
            //$zzz->setSelectedIconWidth(120);
            $inpImage2->setSelectedIconSize(48, 48);
            $inpImage2->setIconSize(64, 64);
            $inpImage2->setGridIconNumber(4);
            
            $labImg1OrImg2 =  new \XoopsFormLabel("", _LG_PLUGIN_SELECTIMAGES_IMG1_OR_IMG2);
//-------------------------------------------------------------------
            //$inpChrono = new \XoopsFormHidden($this->getName($k,'chrono'), $k+1);


            //----------------------------------------------------
            $tbl->addElement($inpPropos, ++$col, $k);
                         
            $tbl->addElement($inpImage2, ++$col, $k);
            $tbl->addElement($labImg1OrImg2, ++$col, $k);
            $tbl->addElement($inpImage1, ++$col, $k);
             
            //$tbl->addElement($inpCaption, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
           
            $tbl->addElement($inpColor, $col, $k);

            
        }
        $trayAllAns->addElement($tbl);
        return $k+1;  // return le dernier index pour le groupe suivant

}


/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler;
        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
        //--------------------------------------------------------       
//  echoArray($_FILES);       

       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            
            //Suppression de l'image
            if(isset($ans['delete_image1']) &&  $ans['delete_image1'] == 1) {
               $ans['image1'] = '';  
               $ansObj->setVar('answer_image1',  '');          
            }
            if (!isset( $ans['image1']))  $ans['image1'] = '';     
            if ($ans['image2'] == QUIZMAKER_NO_ICON) $ans['image2'] = null;
      
            //todo : a virer quand le menage sera fait
            if ($ans['image2'] == 'Button_Icon_Black') $ans['image2'] = null;
            
            $ans['proposition']  = FQUIZMAKER\sanityse_inpValue($ans['proposition']);  
            if(!$ans['proposition'] && !$ans['image1'] &&  !$ans['image2']){
              if($ans['id']>0) $this->delete_answer_by_image($ans,$path);
              continue;
            }
            
            
            
            
//echoArray($ans);  
//echo "===>|{$ans['image2']}|<br>";                
      		$ansObj->setVar('answer_proposition', $ans['proposition']);
      		$ansObj->setVar('answer_points',  $ans['points']);
      		$ansObj->setVar('answer_weight',  $ans['weight']);
            $ansObj->setVar('answer_color',   $ans['color']);          
            $ansObj->setVar('answer_image2',  $ans['image2']);          
            
            $formName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $prefix = "quiz-{$questId}-{$ans['chrono']}";

            $newImg = $this->save_img($ans, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);        
            }else{
                $ansObj->setVar('answer_image1', $newImg);        
            }

            $answersHandler->insert($ansObj);
     }
//exit;
    }
    


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $questionsHandler, $quizHandler;
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
  $question = $questionsHandler->get($questId);
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
    
    if($question->getVar('quest_image')){
        $quiz = $quizHandler->get($question->getVar('quest_quiz_id'));
        $folderJS = $quiz->getVar('quiz_folderJS');
        $sourcePath = QUIZMAKER_URL_UPLOAD_QUIZ . "/{$folderJS}/images";
        $html[] = "<center><img src='{$sourcePath}/{$question->getVar('quest_image')}' height='90px' title='' ></center>";
    }
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

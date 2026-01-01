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
        parent::__construct("selectImages", 0, "classique");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        
        $this->optionsDefaults = ['variant'           => $this::noClass, 
                                  'inputType'         => 'checkbox',
                                  'imgHeight1'        => 64,
                                  'cocheImgName'      => 'coche-01.png',
                                  'cocheImgHeight'    => 25,  
                                  'cocheOpacity'      => 25,  
                                  'posLibelleV'       => 30,
                                  'fontSize'          => '1.1',
                                  'intervalVertical'  => '0',
                                  'trHeight'          => '0',
                                  'repartition'       => '321',
                                  'disposition'       => 'disposition-00',
                                  'nextSlideMessageWinner'      => (defined('_AM_QUIZMAKER_NEXT_SLIDE_WINNER_0') ? _AM_QUIZMAKER_NEXT_SLIDE_WINNER_0 : ''),
                                  'nextSlideMessageLooser'      => (defined('_AM_QUIZMAKER_NEXT_SLIDE_LOOSER_0') ? _AM_QUIZMAKER_NEXT_SLIDE_LOOSER_0 : ''),
                                  'nextSlideDelai'              => 0,
                                  'nextSlideBG'                 =>'#FFCC00'];
                                   
    
        $this->maxPropositions = 12;	
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
      $name = 'variant';  
      //$inpClasse = new \XoopsFormRadio(_LG_PLUGIN_SELECTIMAGES_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
      $inpClasse = new \XoopsFormSelect(_LG_PLUGIN_SELECTIMAGES_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
      if (!$tValues[$name] || $tValues[$name] == $this::noClass) $inpClasse->addOption($this::noClass, _LG_PLUGIN_SELECTIMAGES_VARIANT_SELECT);
      $inpClasse->addOptionArray(['01-image' => _LG_PLUGIN_SELECTIMAGES_VARIANT_IMAGE,
                                  '02-texte' => _LG_PLUGIN_SELECTIMAGES_VARIANT_TEXTE]);
                                
      // change la couleur de fond selon que la variante a été selectionnée ou pas
      if($tValues['variant'] == $this::noClass){ 
            $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
            $inpClasse->setDescription(_LG_PLUGIN_SELECTIMAGES_VARIANT_DESC1);                          
      }else{
            $inpClasse->setExtra('style="background:lime;"');
            $inpClasse->setDescription(_LG_PLUGIN_SELECTIMAGES_VARIANT_DESC2);                          
      }
      $trayOptions->addElementOption($inpClasse);     
      // =======================================================
//       switch($tValues['variant']){ // correspond au nom des images dans "plugins\sortItems\img\classes"
//         case '01-listbox' : 
//             break; 
//         case '01-listbox' :
//             break; 
//     }

      if($tValues['variant'] != $this::noClass){ 
          $name = 'inputType';  
          $inpType = new \XoopsFormRadio(_LG_PLUGIN_SELECTIMAGES_TYPE, "{$optionName}[{$name}]", $tValues[$name]);
          $inpType->addOptionArray(['checkbox' => _LG_PLUGIN_SELECTIMAGES_TYPE_CHECKBOX,
                                    'radio'    => _LG_PLUGIN_SELECTIMAGES_TYPE_RADIO]);
          $inpType->setDescription(_LG_PLUGIN_SELECTIMAGES_TYPE_DESC);                          
          $trayOptions->addElementOption($inpType);     
          
          // ===== messages de transition =====
          //insertion des messages de transition
          $prefixPluginWinner = '';
          $prefixPluginLlooser = '';
          include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_transition.php");
          
          //--------------------------------------------------------------------           
        if($tValues['variant'] == '01-image'){ 
            $name = 'imgHeight1';  
            $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_IMG_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpHeight1->setMinMax(32, 256, _AM_QUIZMAKER_UNIT_PIXELS);
            $trayOptions->addElementOption($inpHeight1);     
            
            $name = 'posLibelleV';  
            $inpTopLib = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_IMG_TOP,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpTopLib->setDescription(_LG_PLUGIN_SELECTIMAGES_IMG_TOP_DESC);
            $inpTopLib->setMinMax(-150, 150, _AM_QUIZMAKER_UNIT_PERCENT);
            $trayOptions->addElementOption($inpTopLib);     
            
            $name = 'repartition';  
            $inpRepartition = new \XoopsEditList(_LG_PLUGIN_SELECTIMAGES_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], 12) ; 
            $inpRepartition->setBackground('#E0FFE0');
            $inpRepartition->setWidth(12);
            $inpRepartition->addBtnClear("X");
            $inpRepartition->addBtnArray(['321','323','222','232']);
            $inpRepartition->setHelp(_LG_PLUGIN_SELECTIMAGES_DISPOSITION_DESC . QBR);
            $trayOptions->addElementOption($inpRepartition);     

            //$inpNextSlideMsgWin->addOptionArray($allSet);
            
 

            
        }
        
         $trayOptions->insertBreak("<div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'><center><b>" . _AM_QUIZMAKER_PARAMS_COCHES . "</b></center></div>",-1,false);

          $name = 'cocheImgName'; 
          $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/coches';
          $inpCocheImg = new \XoopsFormIconSelect("<br>" . _LG_PLUGIN_SELECTIMAGES_COCHE, "{$optionName}[{$name}]", $tValues[$name], $path);
          $inpCocheImg->setSelectedIconSize(64, 64);
          $inpCocheImg->setIconSize(64, 64);
          $inpCocheImg->setGridIconNumber(4);
          //$trayOptions->addElementOption($inpCocheImg);     

          $name = 'cocheImgHeight';  
          $inpCocheHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_COCHE_IMG_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
          $inpCocheHeight1->setMinMax(12, 48, _AM_QUIZMAKER_UNIT_PIXELS);
          //$trayOptions->addElementOption($inpCocheHeight1);     

          $name = 'cocheOpacity';  
          $inpCocheOpacity = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_OPACITY,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
          $inpCocheOpacity->setDescription(_LG_PLUGIN_SELECTIMAGES_OPACITY_DESC);
          $inpCocheOpacity->setMinMax(0, 100, _AM_QUIZMAKER_UNIT_PERCENT, "1");
          //$trayOptions->addElementOption($inpCocheOpacity);     

FQUIZMAKER\addXoopsFormTray($trayOptions, 'CCoche', [$inpCocheImg,$inpCocheHeight1,$inpCocheOpacity], '<br>');          
         $trayOptions->insertBreak("<div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'><center><b>" . _AM_QUIZMAKER_PARAMS_OTHERS . "</b></center></div>",-1,false);

          $name = 'fontSize';  
          $inpFontSize = new \XoopsFormNumber(_AM_QUIZMAGER_PLUGIN_FONT_SIZE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
          $inpFontSize->setMinMax(0.5, 4, _AM_QUIZMAKER_UNIT_EM, "0.1");
          $trayOptions->addElementOption($inpFontSize);     


        if($tValues['variant'] == '01-image'){ 
            $name = 'intervalVertical';  
            $inpIntervalVertical = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpIntervalVertical->setMinMax(-40, 40, _AM_QUIZMAKER_UNIT_PIXELS, "1");
            $inpIntervalVertical->setDescription(_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL_DESC);
            $trayOptions->addElementOption($inpIntervalVertical);     
        }else{
            $name = 'trHeight';  
            $inpTrHeight = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_TR_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpTrHeight->setMinMax(0, 80, _AM_QUIZMAKER_UNIT_PIXELS, "1");
            $inpTrHeight->setDescription(_LG_PLUGIN_SELECTIMAGES_TR_HEIGHT_DESC);
            $trayOptions->addElementOption($inpTrHeight);     
        }

          // disposition 
          include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_disposition.php");

      }

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
        global $utility, $answersHandler, $quizHandler, $questionsHandler;
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
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        
        if (!$options['variant'] && $options['classe']) $options['variant'] = $options['classe'];
        if (!$options['variant'] || $options['variant'] == $this::noClass) return null;
        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, $options, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path);
        
     
        
        //----------------------------------------------------------------
        $this->initFormForQuestion();

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
public function getFormGroup(&$trayAllAns, &$options, &$answers, $titleGroup, $firstItem, $maxItems, $path)
{ 
        $isImage = ($options['variant'] == '01-image');
            
        //suppression des enregistrement en trop
        if(count($answers) > $maxItems) $this->deleteToMuchItems($answers, $maxItems);
        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;

        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        //$imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();

        $tbl->addTdStyle(2, "width:80px;");
        
        $tbl->addTitle('');        
        $tbl->addTitle(_AM_QUIZMAKER_PROPOSITIONS);    
        if($isImage){
            $tbl->addTitle(_AM_QUIZMAKER_ICONE);        
            $tbl->addTitle('');        
            $tbl->addTitle(_AM_QUIZMAKER_IMAGE);        
        }    
        $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_FORECOLOR);        
        $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_WEIGHT);        
        $tbl->addTitle(_AM_QUIZMAKER_PLUGIN_POINTS);        
        

        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            $color = "#000000";
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");
            //-------------------------------------------------
            $inpPropos = new \XoopsFormText('', $this->getName($k,'proposition'), $this->lgMot2, $this->lgMot3, $proposition);
            //$inpPropos->setExtra('required');
            
            $inpPoints = new \XoopsFormNumber('',  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $inpWeight = new \XoopsFormNumber('',  $this->getName($k,'weight'), $this->lgWeight, $this->lgWeight, $weight += 10);
            $inpWeight->setMinMax(0, 99999);


            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$k}", $path, 60, '<br>',$this->getName($k,'delete_image1'));
            $inpImage1Hidden = new \XoopsFormHidden($this->getName($k,'image1'), $image1);
            $inpColor = new XoopsFormColorPicker('', $this->getName($k,'color'), $color);


            $btnPath = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/buttons';
            $name =  $this->getName($k,'image2');
            $inpImage2 = new \XoopsFormIconSelect('', $name, $image2, $btnPath);
            //$zzz->setSelectedIconWidth(120);
            $inpImage2->setSelectedIconSize(48, 48);
            $inpImage2->setIconSize(64, 64);
            $inpImage2->setGridIconNumber(4);
            
            $labImg1OrImg2 =  new \XoopsFormLabel("", _LG_PLUGIN_SELECTIMAGES_IMG1_OR_IMG2);
//-------------------------------------------------------------------
            //$inpChrono = new \XoopsFormHidden($this->getName($k,'chrono'), $k+1);


            //----------------------------------------------------
            $tbl->addElement($inpPropos, ++$col, $k);
                         
            if($isImage){
                $tbl->addElement($inpImage2, ++$col, $k);
                $tbl->addElement($labImg1OrImg2, ++$col, $k);
                $tbl->addElement($inpImage1, ++$col, $k);
                $tbl->addElement($inpImage1Hidden, $col, $k);
            }

            $tbl->addElement($inpColor, ++$col, $k);
             
            //$tbl->addElement($inpCaption, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
           

            
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
        
        $pathImg = $quizHandler->getFolderJS($quizId, 1, 'images');  
        //--------------------------------------------------------       
//  echoArray($_FILES, '', true);       
//echoArray($answers);  

       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            
            //Suppression de l'image
            if(isset($ans['delete_image1']) &&  $ans['delete_image1'] == 1) {
               $ans['image1'] = '';  
               $ansObj->setVar('answer_image1',  '');          
            }
            
            //chargement de la nouvelle image si une image a ete selectionée
            $formName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $prefix = "quiz-{$questId}-{$ans['chrono']}";
            $newImg = $this->save_img($ans, $formName, $pathImg, $prefix);
            
            if($newImg){
                $ans['image1'] = $newImg;  
            }
            
            if($ans['image1']){
                $ans['image2'] = '';
            }
            
            if (!isset( $ans['image1']))  $ans['image1'] = '';     
            if ($ans['image2'] == QUIZMAKER_NO_ICON) $ans['image2'] = '';
      
            //todo : a virer quand le menage sera fait
            if ($ans['image2'] == 'Button_Icon_Black') $ans['image2'] = null;
            
            $ans['proposition']  = FQUIZMAKER\sanityse_inpValue($ans['proposition']);  
            if(!$ans['proposition'] && !$ans['image1'] &&  !$ans['image2']){
              if($ans['id']>0) $this->delete_answer_by_image($ans,$pathImg);
             // exit ("zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz");
              continue;
            }
            
            
            
            
//echoArray($ans);  
//echo "===>|{$ans['image2']}|<br>";                
      		$ansObj->setVar('answer_proposition', $ans['proposition']);
      		$ansObj->setVar('answer_points',  $ans['points']);
      		$ansObj->setVar('answer_weight',  $ans['weight']);
            $ansObj->setVar('answer_color',   $ans['color']);     
            
            $ansObj->setVar('answer_image1',  $ans['image1']);     
            $ansObj->setVar('answer_image2',  $ans['image2']);          

            $answersHandler->insert($ansObj);
//if($key == 1){
//                 exit (echoArray($ans));   
// }            
     }

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
    $quizId = $question->getVar('quest_quiz_id');
    //$quiz = $quizHandler->get();
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
        $urlImg = $quizHandler->getFolderJS($quizId, 2, 'images');
        $html[] = "<center><img src='{$urlImg}/{$question->getVar('quest_image')}' height='90px' title='' ></center>";
    }
    $html[] = "<table class='quizTbl'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $proposition = str_replace('_', ' ', $ans['proposition'],);
        $points = intval($ans['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $proposition, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $proposition, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $proposition, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
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

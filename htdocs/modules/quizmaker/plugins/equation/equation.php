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
class Plugin_equation extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("equation", 0, "games");
        $this->setVersion('1.2', '2026-006-20', 'JJDai (jjd@orange.fr)');
        
        $this->hasZoom = false;
        $this->hasImageMain = true;
        $this->multiPoints = false;                
        $this->maxPropositions = 5;	 
        
        $this->optionsDefaults = ['variant'         => $this::noClass, 
                                  'gameWidth'       => 600,
                                  'skin'            => 'bois-01',
                                  'tokenSize'       => '50',                                  
                                  'tokenColor'      => 'black',                                  
                                  'slotColor'       => 'red',                                  
                                  'movedColor'      => 'green',                                  
                                  'padding'         => 20,
                                  'backgroundColor' => '#F0F0F0',
                                  'radius'          => 12,
                                  'allowNext'       => 0,                                  
                                  'maxMouvements'   => 0]; //nombre maximum de mouvements autorisé

        
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
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AP_QUIZMAKER_OPTIONS),-1,false);

      $name = 'skin'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/skins';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(250, 50);
      $inpKin->setIconSize(250, 50);
      $inpKin->setGridIconNumber(1, 4);
      $trayOptions->addElementOption($inpKin);     
  
      $name = 'tokenSize';  
      $inpTokenSize = new \XoopsFormNumber(_LG_PLUGIN_EQUATION_TOKEN_SIZE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpTokenSize->setMinMax(10, 80, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpTokenSize->setDescription(_LG_PLUGIN_EQUATION_TOKEN_SIZE_DESC);
      $trayOptions->addElementOption($inpTokenSize);     

      $name = 'tokenColor';  
      $inpColor = new XoopsFormColorPicker(_LG_PLUGIN_EQUATION_TOKEN_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpColor);     
      
      $name = 'slotColor';  
      $inpColor = new XoopsFormColorPicker(_LG_PLUGIN_EQUATION_SLOT_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpColor);     
      
      $name = 'movedColor';  
      $inpColor = new XoopsFormColorPicker(_LG_PLUGIN_EQUATION_MOVED_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpColor);     
/*
      $name = 'fontSize';  
      $inpFontSize = new \XoopsFormNumber(_AM_QUIZMAGER_PLUGIN_FONT_SIZE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpFontSize->setMinMax(0.5, 4, _AM_QUIZMAKER_UNIT_EM, "0.1");
      $trayOptions->addElementOption($inpFontSize);     
*/  

/* essais d'imgages individualisees pour les boites et les jetons
      $name = 'imgEquation'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/equations';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(250, 50);
      $inpKin->setIconSize(250, 50);
      $inpKin->setGridIconNumber(1, 4);
      $trayOptions->addElementOption($inpKin);     

      $name = 'imgStock'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/stocks';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(250, 100);
      $inpKin->setIconSize(250, 100);
      $inpKin->setGridIconNumber(1, 4);
      $trayOptions->addElementOption($inpKin);     

      $name = 'imgOperande'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/tokens';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(64, 64);
      $inpKin->setIconSize(64, 64);
      $inpKin->setGridIconNumber(4, 4);
      $trayOptions->addElementOption($inpKin);     

      $name = 'imgOperateur'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/tokens';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(64, 64);
      $inpKin->setIconSize(64, 64);
      $inpKin->setGridIconNumber(4, 4);
      $trayOptions->addElementOption($inpKin);     

      $name = 'imgToken'; 
      $path = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/tokens';
      $inpKin = new \XoopsFormIconSelect(_AP_QUIZMAKER_SKINS, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inpKin->setSelectedIconSize(64, 64);
      $inpKin->setIconSize(64, 64);
      $inpKin->setGridIconNumber(4, 4);
      $trayOptions->addElementOption($inpKin);     
*/




      
      $name = 'gameWidth';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    

/* calculé dans le js ) partir du nombre de facteurs et d'opérateurs
      $name = 'gridColumns';  
    $inpGridColumns = new \XoopsFormNumber(_LG_PLUGIN_EQUATION_GRID_COLUMNS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGridColumns->setMinMax(5, 12, _AM_QUIZMAKER_UNIT_COLUMNS, "1");
      $inpGridColumns->setDescription(_LG_PLUGIN_EQUATION_GRID_COLUMNS_DESC);
      $inpGridColumns->setStep(1);
      $trayOptions->addElementOption($inpGridColumns);     
*/
      
      $name = 'padding';  
    $inpPadding = new \XoopsFormNumber(_LG_PLUGIN_EQUATION_PADDING,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpPadding->setMinMax(5, 50, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpPadding->setDescription(_LG_PLUGIN_EQUATION_PADDINGDESC);
      $inpPadding->setStep(1);
      $trayOptions->addElementOption($inpPadding);     
      
      $name = 'radius';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    

      $name = 'maxMouvements';  
      $inpMaxMouvements = new \XoopsFormNumber(_LG_PLUGIN_EQUATION_MAX_MOVEMENTS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMaxMouvements->setMinMax(0, 20);
      $inpMaxMouvements->setDescription(_LG_PLUGIN_EQUATION_MAX_MOVEMENTS_DESC);
      //$inpMaxMouvements->setStep(1);
      $trayOptions->addElementOption($inpMaxMouvements);   

/*
      $name = 'backgroundColor';  
      $inpBackground = new XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
      
      $name = 'allowNext';  
      $inpBackground = new XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
*/      
      

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
        
//         if (!$options['variant'] && $options['classe']) $options['variant'] = $options['classe'];
//         if (!$options['variant'] || $options['variant'] == $this::noClass) return null;
        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, $options, $answers, _AM_QUIZMAKER_PROPOSITIONS, 0, $this->maxPropositions, $path);
        
     
        
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

        $tbl = $this->getNewXoopsTableXtray();
        //$tbl->addTdStyle(2, "width:80px;");
        
        $tbl->addTitle('');        
        $tbl->addTitle(_LG_PLUGIN_PROPOSITION_EQUATION . "<br>" . _LG_PLUGIN_PROPOSITION_EQUATION_DESC);    
        $tbl->addTitle(_LG_PLUGIN_PROPOSITION_VALUES  . "<br>" . _LG_PLUGIN_PROPOSITION_VALUES_DESC);    

        $styleInput = 'style="font-size:1.5em"';
        
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl            
            $color = "#000000";
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");
            //-------------------------------------------------
            
            $inpPropos = new \XoopsFormText('', $this->getName($k,'proposition'), $this->lgMot2, $this->lgMot2, $proposition);
            $inpPropos->setExtra($styleInput);
            
            $inpBuffer = new \XoopsFormText('', $this->getName($k,'buffer'), $this->lgMot2, $this->lgMot2, $buffer);
            $inpBuffer->setExtra($styleInput);
            
//             $libEquation =  new \XoopsFormLabel('', _LG_PLUGIN_PROPOSITION_EQUATION_DESC);
//             $libValues  =  new \XoopsFormLabel('', _LG_PLUGIN_PROPOSITION_VALUES_DESC);
            
            $inpPoints = new \XoopsFormHidden($this->getName($k,'points'), 1);
            $inpWeight = new \XoopsFormHidden($this->getName($k,'weight'), $weight += 10);
            
            
            

            $labImg1OrImg2 =  new \XoopsFormLabel("", _LG_PLUGIN_PENDU_IMG1_OR_IMG2);
//-------------------------------------------------------------------
            //$inpChrono = new \XoopsFormHidden($this->getName($k,'chrono'), $k+1);


            //----------------------------------------------------
            //$tbl->addElement($libEquation, ++$col,   $k);
            $tbl->addElement($inpPropos, ++$col, $k, '<br>');
            
            //$tbl->addElement($libValues, ++$col,   $k);
            $tbl->addElement($inpBuffer, ++$col,   $k, '<br>');
                         
            //$tbl->addElement($inpColor, ++$col, $k);
             
            //$tbl->addElement($inpCaption, ++$col, $k);
            $tbl->addElementHidden($inpWeight, ++$col, $k);
            $tbl->addElementHidden($inpPoints, ++$col, $k);
           

            
        }
        $trayAllAns->addElement($tbl);
        return $k+1;  // return le dernier index pour le groupe suivant

}


    
   
/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler, $xoopsDB;
//echoArray($answers,'saveAnswers');exit;           
//echoArray('gp','saveAnswers');exit;        
        $pathImg = $quizHandler->getFolderJS($quizId, 1, 'images');  

        //$this->echoAns ($answers, $questId, $bExit = false);    
        //--------------------------------------------------------       
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            if($ans['proposition'] == '[]') $ans['proposition'] = '';
            if(!$ans['proposition']) continue;

            $ansObj->setVar('answer_proposition', $ans['proposition']);
            $ansObj->setVar('answer_buffer', $ans['buffer']);
            //$ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', 0); 
//             $ansObj->setVar('answer_quest_id', $questId); 
//             $ansObj->setVar('answer_group', $ans['group']); 
            
            $answersHandler->insert($ansObj);
     }

    }

 

/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $quizHandler, $questionsHandler;
  /*
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['caption']      = $this->getVar('answer_caption');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['touches']      = $this->getVar('answer_touches');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['group']      = $this->getVar('answer_group');
  
  */
    // = "<TR style='color:%5\$s;'><TD>%1\$s</TD><TD>%2\$s</TD><TD>%3\$s</TD><TD>%4\$s</TD></TR>";
    $html = array();
 
    //-------------------------------------------
    // commenÃ§ons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_weight,answer_id');
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <HR><PRE>" . print_r($quizId,true) . "</PRE><HR>");
    //recherche du dossier upload du quiz
    $urlImg = $quizHandler->getFolderJS($quizId, 2, 'images');
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_touches DESC,answer_weight,answer_id');
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $tpl = "<TR><TD><SPAN style='color:%5\$s;'>%1\$s</SPAN></TD>" 
             . "<TD><SPAN style='color:%5\$s;'>%6\$s</SPAN></TD>" 
             . "<TD><SPAN style='color:%5\$s;'>%2\$s</SPAN></TD>" 
             . "<TD style='text-align:right;padding-right:5px;'><SPAN style='color:%5\$s;'>%3\$s</SPAN></TD>"
             . "<TD><SPAN style='color:%5\$s;'>%4\$s</SPAN></TD></TR>";

    $html[] = "<TABLE class='quizTbl'>";
    
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $touches = intval($ans['touches']);
        $tokenImg = sprintf(QUIZMAKER_TPL_IMG2, $urlImg, $ans['image1'], $ans['image1'], $ans['color']);
        if ($touches > 0) {
            $scoreMax += $touches;
            $color = QUIZMAKER_TOUCHES_POSITIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif ($touches < 0) {
            $scoreMin += $touches;
            $color = QUIZMAKER_TOUCHES_NEGATIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_TOUCHES_NULL;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }
	}
    $html[] = "</TABLE>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la class

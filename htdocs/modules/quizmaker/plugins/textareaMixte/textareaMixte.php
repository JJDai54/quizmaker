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
class Plugin_textareaMixte extends XoopsModules\Quizmaker\Plugins
{
const maxBadWords = 8;
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textareaMixte", 0, "text");
        $this->optionsDefaults = ['presentation'    => 'listbox',
                                  'comparaison'     => 0,
                                  'strToReplace'    => '@@@@@',
                                  'scoreByGoodWord' => 1,
                                  'disposition'     => 'disposition-01',
                                  'tokenColor'      => '#FF0000'];

 
                       
                              
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
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           
      $labPresentation = new XoopsFormLabel('', _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION);
      $trayOptions->addElement($labPresentation);
           
      $name = 'presentation';  
      $inputComparaison = new XoopsFormRadio('', "{$optionName}[{$name}]", $tValues[$name], '<br>');
      $inputComparaison->addOption("listbox",  _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_LISTBOX);            
      $inputComparaison->addOption("textbox",  _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTBOX);            
      $inputComparaison->addOption("textarea", _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTAREA);            
      $trayOptions->addElement($inputComparaison);   
        
      $labComparaison = new XoopsFormLabel('', _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON);
      $trayOptions->addElement($labComparaison);     
      
      $name = 'comparaison';  
      $inputComparaison = new XoopsFormRadio('', "{$optionName}[{$name}]", $tValues[$name], '<br>');
      $inputComparaison->addOption("0", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_0);            
      $inputComparaison->addOption("1", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_1);            
      $inputComparaison->addOption("2", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_2);            
      $trayOptions->addElement($inputComparaison);     
       
      $name = 'strToReplace';
      $inputStrToReplace = new XoopsFormText(_AM_QUIZMAKER_CARS_TO_REPLACE,"{$optionName}[{$name}]", $this->lgMot1, $this->lgMot1, $tValues[$name]);            
      //$inputStrToReplace->setDescription ('blablabla');      
      $trayOptions->addElement($inputStrToReplace);
      
      $name = 'tokenColor';  
      $inpTokenColor = new XoopsFormColorPicker('Couleur des balises', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inpTokenColor);     

      $name = 'scoreByGoodWord';  
      $inpScoreByGoodWord = new \XoopsFormNumber(_LG_PLUGIN_TEXTAREAMIXTE_SCORE_BY_WORD,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpScoreByGoodWord->setMinMax(1, 10, _AM_QUIZMAKER_UNIT_POINTS);
      $inpScoreByGoodWord->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TIMER));
      $trayOptions->addElement($inpScoreByGoodWord);     

      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      //$inputDisposition->setHorizontalIconNumber(9);
      $trayOptions->addElement($inputDisposition);     
      //$trayOptions->addElement(new XoopsFormLabel('',_AM_QUIZMAKER_DISPOSITION_DESC));     

      return $trayOptions;
    }
      

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $this->maxPropositions = 1;
        $this->maxBadWords = 8;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        $i = 0; // il ny a qu'une seule proposition, pas utile de faire une boucle
        if (isset($answers[$i])) {
            $ansId = $answers[$i]->getVar('answer_id');
            $proposition = $answers[$i]->getVar('answer_proposition', 'e');
            $caption = $answers[$i]->getVar('answer_caption');
            $points = $answers[$i]->getVar('answer_points');
            $buffer = $answers[$i]->getVar('answer_buffer');
        }else{
            $ansId = 0;
            $proposition = '';
            $caption = '';
            $points = 0;
            $buffer = '';
        }
        
        $name = $this->getName($i, 'ansId');
        $this->trayGlobal->addElement(new XoopsFormHidden($name, $ansId));
 
        $name = $this->getName($i, 'caption');
        $inpCaption = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_TITLE, $name, $this->lgProposition, $this->lgProposition, $caption);
        $this->trayGlobal->addElement($inpCaption);
 
        
        $name = $this->getName($i, 'proposition');
        $inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, $name, $proposition);
        $this->trayGlobal->addElement($inpPropo);     
        //------------------------------------------------------------       
        $trayBuffer = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        $words = explode(',', $buffer);
        
        for ($j = 0; $j < $this->maxBadWords; $j++){
            if(isset($words[$j])){
                $mot = $words[$j];
            }else{
                $mot = "";
            }
            
            $trayMot = new XoopsFormElementTray  ('', $delimeter = ' ');  
            $name = $this->getName($i,'mots', $j);
            $inpMot = new XoopsFormText($j+1 ."-". _AM_QUIZMAKER_PLUGIN_MOT . ' : ', $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayMot->addElement($inpMot);
            
            $trayBuffer->addElement($trayMot);
        } 
        $this->trayGlobal->addElement($trayBuffer);      


		// To Save
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
//echoArray('p');exit;
        global $utility, $answersHandler, $pluginsHandler;
        
        $i = 0; //il n'y a qu'une seule proposition, pas utile de parcourir le tableau
        $valueAns = $answers[0];
        if($valueAns['ansId'] == 0){
            $ansObj = $answersHandler->create();
  		    $ansObj->setVar('answer_quest_id', $questId);
  		    $ansObj->setVar('answer_weight', 0);
  		    $ansObj->setVar('answer_inputs', 1);
        }else{
            $ansObj = $answersHandler->get($valueAns['ansId']);
        }
        

  		$ansObj->setVar('answer_caption', $valueAns['caption']);
  		//$ansObj->setVar('answer_points', $valueAns['points']);
  		$ansObj->setVar('answer_proposition', $valueAns['proposition']);
  		$ansObj->setVar('answer_buffer', implode(',', $valueAns['mots']));

        $ret = $answersHandler->insert($ansObj);

    }
    
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true, &$obQuestion = null){
  global $answersHandler;
    $answersAll = $answersHandler->getListByParent($questId);
//    echoArray($answersAll);
    $ret = array();
    $html = array();
    $html[] = "<table class='quizTbl'>";
    
    $ans = $answersAll[0]->getValuesAnswers();
    
//        echoArray($ans);
    $arr1= array("\n", "{", "}");
    $arr2= array("<br>",  "<b>", "</b>");
    $rep = str_replace($arr1, $arr2, $ans['proposition']);
    $html[] = "<tr><td>{$rep}</td></tr>";

    $html[] = "</table>";
    //-----------------------------------------------------
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = 0;
    $ret['scoreMax'] = $ans['points'];
    return $ret;
     }

} // fin de la classe

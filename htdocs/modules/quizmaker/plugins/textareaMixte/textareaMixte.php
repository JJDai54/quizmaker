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
const maxBadWords = 12;
const maxPropositions = 1;
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textareaMixte", 0, "text");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;

        $this->optionsDefaults = ['presentation'    => 'listbox',
                                  'comparaison'     => 0,
                                  'strToReplace'    => '@@@@@',
                                  'scoreByGoodWord' => 1,
                                  'disposition'     => 'disposition-01',
                                  'tokenColor'      => '#FF0000'];

 
                       
                              
        $this->hasImageMain = true;
        $this->numbering = 1; // force la umerotation avec des nombres
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
      $name = 'presentation';  
      $inputComparaison = new XoopsFormRadio(_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION, "{$optionName}[{$name}]", $tValues[$name], '<br>');
      $inputComparaison->addOption("listbox",  _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_LISTBOX);            
      $inputComparaison->addOption("textbox",  _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTBOX);            
      $inputComparaison->addOption("textarea", _LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTAREA);            
      $trayOptions->addElementOption($inputComparaison);   
        
      $name = 'comparaison';  
      $inputComparaison = new XoopsFormRadio(_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON, "{$optionName}[{$name}]", $tValues[$name], '<br>');
      $inputComparaison->addOption("0", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_0);            
      $inputComparaison->addOption("1", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_1);            
      $inputComparaison->addOption("2", _LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_2);            
      $trayOptions->addElementOption($inputComparaison);     
       
      $name = 'strToReplace';
      $inputStrToReplace = new XoopsFormText(_AM_QUIZMAKER_CARS_TO_REPLACE,"{$optionName}[{$name}]", $this->lgMot1, $this->lgMot1, $tValues[$name]);            
      $trayOptions->addElementOption($inputStrToReplace);
      
      $name = 'tokenColor';  
      $inpTokenColor = new XoopsFormColorPicker('Couleur des balises', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpTokenColor);     

      $name = 'scoreByGoodWord';  
      $inpScoreByGoodWord = new \XoopsFormNumber(_LG_PLUGIN_TEXTAREAMIXTE_SCORE_BY_WORD,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpScoreByGoodWord->setMinMax(1, 10, _AM_QUIZMAKER_UNIT_POINTS);
      $inpScoreByGoodWord->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TIMER));
      $trayOptions->addElementOption($inpScoreByGoodWord);     

      // disposition 
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_disposition.php");

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
        //$this->maxBadWords = 8;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        $k = 0;
        $ans = (isset($answers[$k])) ? $answers[$k] : null;
        //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
        include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
        //-------------------------------------------------

       
        $name = $this->getName($k, 'id');
        $this->trayGlobal->addElement(new XoopsFormHidden($name, $answerId));
 
        $name = $this->getName($k, 'caption');
        $inpCaption = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_TITLE, $name, $this->lgProposition, $this->lgProposition, $caption);
        $this->trayGlobal->addElement($inpCaption);
 
        
        $name = $this->getName($k, 'proposition');
        $racine = $this->getName($k);
        $inpPropo = new XoopsFormTextArea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, $name, $proposition);
        //    public function __construct($caption, $name, $value = '', $rows = 5, $cols = 50)
        //$inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, $name, $proposition);
        $onFocus = "onfocus='textareaMixte_updateButtons(\"{$racine}\")' ";
        $onBlur = "onblur='textareaMixte_verif(\"{$racine}\",\"" ._LG_PLUGIN_TEXTAREAMIXTE_ACCOLADES_ERR. "\")'";
        $onSelectionChange = "onfocus='textareaMixte_updateButtons(\"{$racine}\")' ";
        $style = "style='background:#ECECEC;'";
        $inpPropo->setExtra("{$style} required onselectionchange='textareaMixte_updateButtons(\"{$racine}\")' {$onFocus} {$onBlur} {$onSelectionChange}");

        $this->trayGlobal->addElement($inpPropo);     
        $btnLib = array('{+}','{-}','X');
        // ajout des boutons des gestion des accolages pour les mot à selectionner, ou pas
        $trayBtnAccollades = new XoopsFormElementTray  ('Action', ' ');
        
        $inpAddAccollades = new XoopsFormButton('', $this->getName($k, 'addAccollades'), $btnLib[0]);
        $inpAddAccollades->setExtra("onclick='textareaMixte_addAccolades(\"{$racine}\")'");
        $trayBtnAccollades->addElement($inpAddAccollades);
        
        $inpNewText = new XoopsFormButton('',  $this->getName($k, 'removeAccollades'), $btnLib[1]);
        $inpNewText->setExtra("onclick='textareaMixte_removeAccolades(\"{$racine}\")'");
        $trayBtnAccollades->addElement($inpNewText);
        
        $inpNewText = new XoopsFormButton('',  $this->getName($k, 'clearAccollades'), $btnLib[2]);
        $inpNewText->setExtra("onclick='textareaMixte_ClearAccolades(\"{$racine}\",\"" ._LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ALERT. "\")'");
        $trayBtnAccollades->addElement($inpNewText);
        
        $inpNewText = new XoopsFormButton('', 'button', "exemple 1");
        $inpNewText->setExtra("onclick='textareaMixte_addTextDefault(\"{$racine}\",1)'");
        $trayBtnAccollades->addElement($inpNewText);
        
        $inpNewText = new XoopsFormButton('', 'button', "exemple 2");
        $inpNewText->setExtra("onclick='textareaMixte_addTextDefault(\"{$racine}\",2)'");
        $trayBtnAccollades->addElement($inpNewText);
        
        $desc = "<br>\"<b>{$btnLib[0]}</b>\" : "  .  _LG_PLUGIN_TEXTAREAMIXTE_ADD_ACCOLADES
              . "<br>\"<b>{$btnLib[1]}</b>\" : " .  _LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ACCOLADES
              . "<br>\"<b>{$btnLib[2]}</b>\" : " .  _LG_PLUGIN_TEXTAREAMIXTE_CLEAR_ALL_ACCOLADES;
        $trayBtnAccollades->addElement(new XoopsFormLabel('',$desc));      
        $this->trayGlobal->addElement($trayBtnAccollades);      
        
        
        
        
        
        //------------------------------------------------------------ 
        $this->trayGlobal->addElement(new XoopsFormLabel('',_LG_PLUGIN_TEXTAREAMIXTE_ADD_BAD_EXP));      
              
        $trayBuffer = new XoopsFormElementTray  ('', $delimeter = ' ');  
        $words = explode(',', $buffer);
        
        for ($j = 0; $j < $this::maxBadWords; $j++){
            if(isset($words[$j])){
                $mot = $words[$j];
            }else{
                $mot = "";
            }
            
            $trayMot = new XoopsFormElementTray  ('', $delimeter = ' ');  
            $name = $this->getName($k,'mots', $j);
            //$inpMot = new XoopsFormText($j+1 ."-". _AM_QUIZMAKER_PLUGIN_MOT . ' : ', $name, $this->lgMot1, $this->lgMot2, $mot);
            $inpMot = new XoopsFormText('', $name, $this->lgMot1, $this->lgMot2, $mot);
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
        
        $k = 0; //il n'y a qu'une seule proposition, pas utile de parcourir le tableau
        $ans = (isset($answers[$k])) ? $answers[$k] : null;

        //chargement des operations communes à tous les plugins
        include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
        //if (is_null($ansObj)) continue;
        //---------------------------------------------------           
        
  		$ansObj->setVar('answer_caption', $ans['caption']);
  		$ansObj->setVar('answer_proposition', $ans['proposition']);
  		$ansObj->setVar('answer_buffer', implode(',', $ans['mots']));

        $ret = $answersHandler->insert($ansObj);
//exit;
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

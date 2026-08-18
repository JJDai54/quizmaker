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
class Plugin_textMixte extends XoopsModules\Quizmaker\Plugins
{
const maxBadWords = 10;
const maxPropositions = 12;
const maxIntrus = 12;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textMixte", 0, "text");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;

        $this->optionsDefaults = ['presentation'     => 'listbox1',
                                  'variant'           => $this::noClass,
                                  'comparaison'      => 0,
                                  'initText'         => 0,
                                  'strToReplace'     => '@@@@@',
                                  'scoreByGoodWord'  => 1,
                                  'disposition'      => 'disposition-01',
                                  'fontsize'         => 3,
                                  'intervalVertical' => 3,
                                  'prose'            => '',
                                  'textWidth'        => 50,
                                  'tokenColor'       => '#FF0000',
                                  'wordColor'        => '#008000'];

 
        for ($h = 0; $h < $this::maxIntrus; $h++){
          $this->optionsDefaults["intrus_{$h}"] = '';
        }
                       
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
      $trayOptions->addElementOption(new \XoopsFormLabel('presentation = ', $tValues['presentation']));    
      //--------------------------------------------------------------------           
      $variantsArr = ['listbox1' => _LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX1,
                      'listbox2' => _LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX2,
                      'textbox'  => _LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTEBOX,
                      'textarea' => _LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTAREA];
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_variant.php");
      if(!$isSelectOk) return $trayOptions;

      // =======================================================

      $name = 'variant';  
      if(!$tValues[$name]) $tValues[$name] = $tValues['presentation'];
      $inpClasse = new \XoopsFormSelect(_LG_PLUGIN_TEXTMIXTE_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
      if (!$tValues[$name] || $tValues[$name] == $this::noClass) $inpClasse->addOption($this::noClass, _LG_PLUGIN_TEXTMIXTE_VARIANT_SELECT);
      $inpClasse->addOptionArray();

      // change la couleur de fond selon que la variante a été selectionnée ou pas
      if($tValues['variant'] == $this::noClass){ 
            $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
            $inpClasse->setDescription(_LG_PLUGIN_TEXTMIXTE_VARIANT_DESC1);                          
      }else{
            $inpClasse->setExtra('style="background:lime;"');
            $inpClasse->setDescription(_LG_PLUGIN_TEXTMIXTE_VARIANT_DESC2);                          
      }
      $trayOptions->addElementOption($inpClasse);    
      if($tValues['variant'] == $this::noClass) return $trayOptions; 
    //------------------------------------------------------------
      $name = 'comparaison';  
      if($tValues['variant'] == 'textarea' || $tValues['variant'] == 'textbox' ) {
          $inputComparaison = new XoopsFormRadio(_LG_PLUGIN_TEXTMIXTE_COMPARAISON, "{$optionName}[{$name}]", $tValues[$name], '<br>');
          $inputComparaison->addOption("0", _LG_PLUGIN_TEXTMIXTE_COMPARAISON_0);            
          $inputComparaison->addOption("1", _LG_PLUGIN_TEXTMIXTE_COMPARAISON_1);            
          $inputComparaison->addOption("2", _LG_PLUGIN_TEXTMIXTE_COMPARAISON_2);            
          $trayOptions->addElementOption($inputComparaison);     
      }else{
          $trayOptions->addElementOption(new XoopsFormHidden($name, $tValues[$name]));
      }
      
       
      $name = 'strToReplace';
      if($tValues['variant'] == 'textarea') {
          $inputStrToReplace = new XoopsFormText(_AM_QUIZMAKER_CARS_TO_REPLACE,"{$optionName}[{$name}]", $this->lgMot1, $this->lgMot1, $tValues[$name]);            
          $trayOptions->addElementOption($inputStrToReplace);
      }else{
          $trayOptions->addElementOption(new XoopsFormHidden($name, $tValues[$name]));
      }

             
      $name = 'tokenColor';  
      $inpTokenColor = new XoopsFormColorPicker(_LG_PLUGIN_TEXTMIXTE_TOKEN__AP_QUIZMAKER_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpTokenColor);     
      
      $name = 'wordColor';  
      $inpWordColor = new XoopsFormColorPicker(_LG_PLUGIN_TEXTMIXTE_WORD__AP_QUIZMAKER_COLOR, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpWordColor);   
        
      $name = 'fontsize';  
      $inpFontSize = new XoopsFormSelect(_AM_QUIZMAGER_PLUGIN_FONT_SIZE, "{$optionName}[{$name}]", $tValues[$name]);
      for($h=0; $h<15;$h++){
        $inpFontSize->addOption($h, ($h+10)*0.1);
      }
      $trayOptions->addElementOption($inpFontSize);     
      
//       $name = 'lineheight';  
//       $inpLineHeight = new XoopsFormSelect(_AP_QUIZMAKER_TEXT_LINE_HEIGHT, "{$optionName}[{$name}]", $tValues[$name]);
//       for($h=0; $h<15;$h++){
//         $inpLineHeight->addOption($h, ($h+10)*0.1);
//       }
//       $trayOptions->addElementOption($inpLineHeight);     

      $name = 'intervalVertical';  
      $inpIntervalVertical = new \XoopsFormNumber(_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpIntervalVertical->setMinMax(-40, 400, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      $inpIntervalVertical->setDescription(_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL_DESC);
      $trayOptions->addElementOption($inpIntervalVertical);     


      $name = 'textWidth';  
      $inpTextWidth = new \XoopsFormNumber(_AP_QUIZMAKER_TEXT_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpTextWidth->setMinMax(30, 70, _AM_QUIZMAKER_UNIT_PERCENT);
      $inpTextWidth->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TIMER));
      $trayOptions->addElementOption($inpTextWidth);     

      $name = 'scoreByGoodWord';  
      $inpScoreByGoodWord = new \XoopsFormNumber(_LG_PLUGIN_TEXTMIXTE_SCORE_BY_WORD,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpScoreByGoodWord->setMinMax(1, 10, _AM_QUIZMAKER_UNIT_POINTS);
      $inpScoreByGoodWord->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TIMER));
      $trayOptions->addElementOption($inpScoreByGoodWord);     


      $name = 'initText';  
      if($tValues['variant'] == 'listbox1' || $tValues['variant'] == 'listbox2' ) {
          $inpInitText = new XoopsFormRadio(_LG_PLUGIN_TEXTMIXTE_INIT_TEXT, "{$optionName}[{$name}]", $tValues[$name], '<br>');
          $inpInitText->setDescription(_LG_PLUGIN_TEXTMIXTE_INIT_TEXT_DESC);
          $inpInitText->addOption("0", _LG_PLUGIN_TEXTMIXTE_INIT_NONE);            
          $inpInitText->addOption("1", _LG_PLUGIN_TEXTMIXTE_INIT_FIRST);            
          $inpInitText->addOption("2", _LG_PLUGIN_TEXTMIXTE_INIT_RND);            
          $trayOptions->addElementOption($inpInitText);     
      }else{
          $trayOptions->addElementOption(new XoopsFormHidden($name, $tValues[$name]));
      }


      // disposition 
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_disposition.php");

// ========================================================
        $proseTray = new \XoopsFormElementTray  ('', '<br>');
            
        $name = 'prose';
        $fullName = "{$optionName}[{$name}]";
        $inpPropo = new XoopsFormTextArea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, "{$optionName}[{$name}]", $tValues[$name],16);
        $inpPropo->setExtra("style='background:#FFF0F0;line-height:1em'");
        //    public function __construct($caption, $fullName, $value = '', $rows = 5, $cols = 50)
        //$inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT, $fullName, $proposition);
        $onFocus = "onfocus='textMixte_updateButtons(\"{$fullName}\")' ";
        $onBlur = "onblur='textMixte_verif(\"{$fullName}\",\"" ._LG_PLUGIN_TEXTMIXTE_ACCOLADES_ERR. "\")'";
        $onSelectionChange = "onfocus='textMixte_updateButtons(\"{$fullName}\")' ";
        $style = "style='background:#ECECEC;'";
        $inpPropo->setExtra("{$style} required onselectionchange='textMixte_updateButtons(\"{$fullName}\")' {$onFocus} {$onBlur} {$onSelectionChange}");
        $proseTray->addElement($inpPropo);   // todo  mettre ", true" des que possible quand les ancienne versions seront virées  

/* *********************************** */     
         $btnLib = array('{+}','{-}','X');
         // ajout des boutons de gestion des accolades pour les mots à selectionner, ou pas
         $trayBtnAccollades = new XoopsFormElementTray  ('Action', ' ');
        
        $inpBtn = new XoopsFormButton('', $fullName . '[addAccollades]', $btnLib[0]);
        $inpBtn->setExtra("onclick='textMixte_addAccolades(\"{$fullName}\")'");
        $trayBtnAccollades->addElement($inpBtn);
        
        $inpBtn = new XoopsFormButton('', $fullName . '[removeAccollades]', $btnLib[1]);
        $inpBtn->setExtra("onclick='textMixte_removeAccolades(\"{$fullName}\")'");
        $trayBtnAccollades->addElement($inpBtn);
        
        $inpBtn = new XoopsFormButton('', $fullName . '[clearAccollades]', $btnLib[2]);
        $inpBtn->setExtra("onclick='textMixte_ClearAccolades(\"{$fullName}\",\"" ._LG_PLUGIN_TEXTMIXTE_REMOVE_ALERT. "\")'");
        $trayBtnAccollades->addElement($inpBtn);
        
        $inpBtn = new XoopsFormButton('', 'button', "exemple 1");
        $inpBtn->setExtra("onclick='textMixte_addTextDefault(\"{$fullName}\",1)'");
        $trayBtnAccollades->addElement($inpBtn);
        
        $inpBtn = new XoopsFormButton('', 'button', "exemple 2");
        $inpBtn->setExtra("onclick='textMixte_addTextDefault(\"{$fullName}\",2)'");
        $trayBtnAccollades->addElement($inpBtn);
        
        $desc = "<br>\"<b>{$btnLib[0]}</b>\" : "  .  _LG_PLUGIN_TEXTMIXTE_ADD_ACCOLADES
              . "<br>\"<b>{$btnLib[1]}</b>\" : " .  _LG_PLUGIN_TEXTMIXTE_REMOVE_ACCOLADES
              . "<br>\"<b>{$btnLib[2]}</b>\" : " .  _LG_PLUGIN_TEXTMIXTE_CLEAR_ALL_ACCOLADES;
        $trayBtnAccollades->addElement(new XoopsFormLabel('',$desc));      
        $proseTray->addElement($trayBtnAccollades);    
        
        $trayOptions->addElementOption($proseTray);     
        //-----------------------------------------------
        if($tValues['variant'] == 'listbox1') {
        //if($tValues['variant'] == 'listbox1' || $tValues['variant'] == 'listbox2' ) {
            $name = "intrus"; 
            $trayIntrus = new XoopsFormElementTray  (_AM_QUIZMAKER_PLUGIN_INTRUS, '<br>');
            for ($h = 0; $h < $this::maxIntrus; $h++){
              $j = $h+1;
              $name = "intrus_{$h}";  
              $inpIntrus = new \XoopsFormText("{$j}", "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
              $inpIntrus->setExtra("style='background:" . self::bgColor2 . ";'");
                $trayIntrus->addElement($inpIntrus);     
            }  
            $trayOptions->addElementOption($trayIntrus);     
        }

// ========================================================

      return $trayOptions;
    }
      
/* *************************************************
*
* ************************************************** */
 	public function getWordsArrBetweenAccolades($exp, &$wordsArr, $idxRep = 2){
/* testextra    ction des mots entre accolades*/
        $pattern = '/(\{)(.*?)(\})/';
        $nbWords = preg_match_all ($pattern, $exp, $arrFound, PREG_PATTERN_ORDER);
        $wordsArr = $arrFound[$idxRep];
        //echoArray($arrFound, 'test regex pour extraire les mots entre accolafes');
        return $nbWords;
    
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId){
        global $utility, $answersHandler, $questionsHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();

//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if(!$options) $options = $this->optionsDefaults;
        
        switch($options['variant']){
            case 'listbox1':
                //obsolette , transferé dans les options de la question
//                 $trayAllAns = new XoopsFormElementTray  (_LG_PLUGIN_TEXTMIXTE_INTRUS, $delimeter = '<br>');              
//                 $this->getFormGroup($trayAllAns, $answers, 1, $this::maxBadWords, null);
//                 $this->trayGlobal->addElement($trayAllAns);
                break;
                
            case 'listbox2':
                $nbWords = $this->getWordsArrBetweenAccolades($options['prose'], $wordsArray);
//             echo $options['prose']; 
//             echoArray($wordsArray);
//             exit;
                $trayAllAns = new XoopsFormElementTray  (_LG_PLUGIN_TEXTMIXTE_INTRUS, $delimeter = '<br>');              
                $this->getFormGroup($trayAllAns, $answers, $nbWords, $this::maxBadWords, $wordsArray);
                $this->trayGlobal->addElement($trayAllAns);
                break;
        }
        
        //-------------------------------------------------
        

        $this->trayGlobal->addElement(new XoopsFormHidden('newVersion', '1'));
        
		return $this->trayGlobal;
	}
     
/* *************************************************
*
* ************************************************** */
public function getFormGroup(&$trayAllAns, $answers, $maxPropositions, $maxBadWords, $wordsArray)
{ 
        //suppression des enregistrement en trop
        if(count($answers) > $maxPropositions) $this->deleteToMuchItems($answers, $maxPropositions);
        $weight = 0;
      
        $tbl = $this->getNewXoopsTableXtray();
            if ($maxPropositions > 1) {
              $tbl->addTdStyle(3, "width:250px;");
        //echo "<hr>column {$j} - width : {$width}<hr>";

//           $tbl->addTitle('a');        
//           $tbl->addTitle('b');        
//           $tbl->addTitle('c');        
 
            
            }
        //----------------------------------------------------------
        for ($k = 0; $k < $maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");
                        
//             if($ans){
//                 $proposition = $ans->getVar('proposition');
//                 $buffer = $ans->getVar('buffer');
//             if ($ans->getVar('buffer')) $proposition = $ans->getVar('buffer');
// 
//             }else{
//                 $proposition = '';
// 
//             }
// echo "===>{$k} $proposition : {$proposition}<br>";        
// echo "===>{$k} $buffer : {$buffer}<br>";      
//echo "maxPropositions = {$maxPropositions}<hr>";  
            if ($maxPropositions > 1) {
                $inpWordInAccolade = new XoopsFormLabel('', $wordsArray[$k]);
                $tbl->addElement($inpWordInAccolade, ++$col, $k);    
                $inpWordInAccolade = new XoopsFormHidden( $this->getName($k,'proposition'), $wordsArray[$k]);
                $tbl->addElement($inpWordInAccolade, $col, $k);    
            }
            $intrus = ($proposition) ? explode(QUIZMAKER_SEP_EXP, $proposition): array();
            
            // de la liste d'intrus
            $trayBuffer = new XoopsFormElementTray  ('', $delimeter = ' ');  
            
            //ne pas prendre le premier élément du tableau qui fait partie des mots entre accolades
            for ($j = 1; $j < $maxBadWords; $j++){
                if(isset($intrus[$j])){
                    $mot = $intrus[$j];
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
            $tbl->addElement($trayBuffer, ++$col, $k);;      
        }
        
        $trayAllAns->addElement($tbl, $k);
        return true; 
  }          


/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
    {
    global $xoopsDB;
//echoArray($answers);exit;
        global $utility, $answersHandler, $pluginsHandler;


       foreach ($answers as $k=>$ans){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            if(!$ans) continue;
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if(!$ansObj) continue;
            $tWords = array();
            $tWords[] = $ans['proposition'];
            foreach ($ans['mots'] as $kWord=>$exp){
                if($exp && $exp != $ans['proposition']) $tWords[] = $exp;
             }
             
                $intrus = implode(QUIZMAKER_SEP_EXP, $tWords);
                $ansObj->setVar('answer_proposition', $intrus);
                
                //le temps du transfert d'un champ à l'autre pour compatibilité
                $ansObj->setVar('answer_buffer', $intrus);
                
                $ret = $answersHandler->insert($ansObj);

             if (count($tWords) > 0){
             }
       }

        
    $sql = "DELETE FROM " . $xoopsDB->prefix('quizmaker_answers')
         . " WHERE answer_quest_id={$questId}"
         . " AND (answer_buffer = '' and answer_proposition IS NULL) ";
    //$xoopsDB->Query($sql);    
    //echo "<hr>{$sql}<hr>"; exit;
//exit;
    }
    
    
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true, &$obQuestion = null){
  global $answersHandler, $quizHandler, $questionsHandler;

    //-------------------------------------------
    $questObj = $questionsHandler->get($questId);
    $quizId = $questObj->getVar("quest_quiz_id");
    $options = json_decode(html_entity_decode($questObj->getVar('quest_options')),true); 
    //echoArray($options);   exit;
    //-------------------------------------------
    $scoreMax = substr_count($options['prose'], '{') * $options['scoreByGoodWord'];
    
    $prose=str_replace("\r",'<br>', $options['prose']);
    $prose=str_replace("{",'<b>', $prose);
    $prose=str_replace("}",'</b>', $prose);
    $html = "<div>{$prose}</div>";
//     $answersAll = $answersHandler->getListByParent($questId);
// //    echoArray($answersAll);
//     $ret = array();
//     $html = array();
//     $html[] = "<table class='quizTbl'>";
//     
//     $ans = $answersAll[0]->getValuesAnswers();
//     
// //        echoArray($ans);
//     $arr1= array("\n", "{", "}");
//     $arr2= array("<br>",  "<b>", "</b>");
//     $rep = str_replace($arr1, $arr2, $ans['proposition']);
//     $html[] = "<tr><td>{$rep}</td></tr>";
// 
//     $html[] = "</table>";
    //-----------------------------------------------------
    $ret['answers'] = $html;
    $ret['scoreMin'] = 0;
    $ret['scoreMax'] = $scoreMax;
    return $ret;
     }

} // fin de la class

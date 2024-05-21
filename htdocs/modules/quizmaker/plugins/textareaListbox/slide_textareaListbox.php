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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker;
include_once QUIZMAKER_PATH_MODULE . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_textareaListbox extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("textareaListbox", 0, "text");
        $this->optionsDefaults = ['orientation'=>'V', 
                                  'scoreByGoodWord'=>2, 
                                  'scoreByBadWord'=>0, 
                                  'tokenColor'=>'#FF0000',
                                  'text'=>''];
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
       
      $name = 'orientation';  
      $inputOrientation = new \XoopsFormRadio(_AM_QUIZMAKER_ORIENTATION . _AM_QUIZMAKER_PP, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrientation->addOption("H", _AM_QUIZMAKER_ORIENTATION_H);            
      $inputOrientation->addOption("V", _AM_QUIZMAKER_ORIENTATION_V);            
      $trayOptions->addElement($inputOrientation);     
      
//       $name = 'title';  
//       $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);


      $name = 'scoreByGoodWord';  
      $inpScoreByGoodWord = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_001 . _AM_QUIZMAKER_PP,  "{$optionName}[{$name}]",$this->lgPoints,  $this->lgPoints, $tValues[$name]);
      $inpScoreByGoodWord->setMinMax(1, 20);
      $trayOptions->addElement($inpScoreByGoodWord);     
      
      $name = 'scoreByBadWord';  
      $inpScoreByBadWord = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_004 . _AM_QUIZMAKER_PP,  "{$optionName}[{$name}]",$this->lgPoints,  $this->lgPoints, $tValues[$name]);
      $inpScoreByBadWord->setMinMax(-10, 0);
      $trayOptions->addElement($inpScoreByBadWord);     
      
//       $name = 'minReponses';  
//       $inpMinReponses = new XoopsFormNumber(_AM_QUIZMAKER_QUESTIONS_MINREPONSE . _AM_QUIZMAKER_PP,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpMinReponses->setMinMax(0, 12);
//       $trayOptions->addElement($inpMinReponses);     


      $name = 'tokenColor';  
      $inpTokenColor = new XoopsFormColorPicker('Couleur des balises', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inpTokenColor);     

      $name = 'text';  
      $inpPropo = $this->getformTextarea("Texte à corriger" ,  "{$optionName}[{$name}]", $tValues[$name],'',8);
      $trayOptions->addElement($inpPropo);   

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
        //-------------------------------------------------
        
/*
        $i=0; //texte à retrouver
        if (isset($answers[$i])) {
            $proposition = $answers[$i]->getVar('answer_proposition', 'e');
            $points = $answers[$i]->getVar('answer_points');
        }else{
            $proposition = '';
            $points = 1;
        }
        $inpPropo = $this->getformTextarea(_AM_QUIZMAKER_QUESTIONS_COMMENT2, $this->getName('proposition'), $proposition,'',8);
        $this->trayGlobal->addElement($inpPropo);   
*/        
/*
         $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_SLIDE_001, $this->getName('points'), $this->lgPoints, $this->lgPoints, $points);
         $inpPoints->setMinMax(-30, 30); 
         $this->trayGlobal->addElement($inpPoints);     
*/          
         
        //------------------------------------------------------------       
        $trayAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        for ($i = 0; $i < $this->maxPropositions; $i++){
            if(isset($answers[$i])){
                $mot = $answers[$i]->getVar('answer_proposition');
            }else{
                $mot = "";
            }
            
            $trayMots = new XoopsFormElementTray  ('', $delimeter = ' ');  
            $name = $this->getName('mots', $i, 'mot');
            $inpMot = new XoopsFormText($i+1 ."-". _AM_QUIZMAKER_SLIDE_MOT . ' : ', $name, $this->lgMot1, $this->lgMot2, $mot);
            $trayMots->addElement($inpMot);
            
            $trayAns->addElement($trayMots);
        } 
        
            
        
        
             
        $this->trayGlobal->addElement($trayAns);      
        //----------------------------------------------------------
        
		// To Save
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
// 		$ansObj = $answersHandler->create();
//   		$ansObj->setVar('answer_quest_id', $questId);
//   		$ansObj->setVar('answer_weight', 10);
//   		$ansObj->setVar('answer_caption', '');
//   		//$ansObj->setVar('answer_points', $answers['points']);
//   		//$ansObj->setVar('answer_proposition', $answers['proposition']);
//   		$ansObj->setVar('answer_inputs', 1);
// 
//         $ret = $answersHandler->insert($ansObj);
//        
       
       //-----------------------------------------
       //recupe de la liste des mots indésirables
       $tMots = array();
       $tPoints = array();
       
       foreach ($answers['mots'] as $keyMot => $value){
            $mots = trim($value['mot']);
            if($mots === '' ) continue;
  	        
            $ansObj = $answersHandler->create();
    		$ansObj->setVar('answer_quest_id', $questId);
    		$ansObj->setVar('answer_weight', 20);
    		$ansObj->setVar('answer_caption', '');
    		$ansObj->setVar('answer_proposition', $mots );
    		$ansObj->setVar('answer_points', 0);
    		$ansObj->setVar('answer_inputs', 1);
            $ret = $answersHandler->insert($ansObj);
        }
        
        
         
    //exit ("<hr>===>saveAnswers<hr>");
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true, &$obQuestion = null){
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

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
class slide_ulDaDSortList extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("ulDaDSortList", 0, "dragAndDrop");
        $this->optionsDefaults = ['ordre'=>'N', 'title'=>'', 
                                  'liBgDefault' =>'#f5f5f5', 
                                  'liBgActive'  =>'#ffe7e7', 
                                  'liBgHover'   =>'#00FF00'];
        $this->maxPropositions = 8; 
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
*  toto : ghanger le mode de gestion par une proposition par item
* *********************************************************** */

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{      
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           

      $name = 'orderStrict';  
      $inputOrder = new \XoopsFormRadio(_AM_QUIZMAKER_ORDER_ALLOWED . ' : ', "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrder->addOption("N", _AM_QUIZMAKER_ONLY_ORDER_NAT);            
      $inputOrder->addOption("R", _AM_QUIZMAKER_ALLOW_ALL_ORDER);            
      $trayOptions->addElement($inputOrder);     
      
      $name = 'title';  
      $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
      $trayOptions->addElement($inpTitle);     

      $name = 'liBgDefault';   /* background des items par defaut f5f5f5*/
      $inpLiBgDefault = new XoopsFormColorPicker('Couleur par defaut', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inpLiBgDefault);     

      $name = 'liBgActive';  /* background de l'item survole pendant drag on drop ffe7e7*/
      $inpLiBgActive = new XoopsFormColorPicker('Couleur de survol pandant le déplacement', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inpLiBgActive);     

      $name = 'liBgHover';   /* background de l'item survole avant drag on drop 00FF00*/
      $inpLiBgHover = new XoopsFormColorPicker('Couleur de survol avant selection', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inpLiBgHover);     



      return $trayOptions;
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        //echoArray($answers,'getForm',true);
        $this->initFormForQuestion();

        //-------------------------------------------------
        //element definissat un objet ou un ensemble
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray();
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(1, 'text-align:left;width:50px;');
                
        foreach($answers as $k=>$item){
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");  
                
            $proposition = $item->getVar('answer_proposition');          
            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot2, $proposition);
                
            $name = $this->getName($k, 'weight');
            $inpWeight = new \XoopsFormText(_AM_QUIZMAKER_WEIGHT, $name, $this->lgWeight, $this->lgWeight, $weight += 10);
                
            $col=0;
            $tbl->addElement($inpLab, $col++, $k);
            $tbl->addElement($inpProposition, $col++, $k);
            $tbl->addElement($inpWeight, $col++, $k);
        } 
        //completion jusqu' à maxItem si besoin
        for($k = count($answers); $k < $this->maxPropositions; $k++){
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");  
            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot2, '');
            $name = $this->getName($k, 'weight');
            $inpWeight = new \XoopsFormText(_AM_QUIZMAKER_WEIGHT, $name, $this->lgWeight, $this->lgWeight, $weight += 10);
            
            $col=0;
            $tbl->addElement($inpLab, $col++, $k);
            $tbl->addElement($inpProposition, $col++, $k);
            $tbl->addElement($inpWeight, $col++, $k);
        }
        
        
        
        $this->trayGlobal->addElement($tbl);
        //----------------------------------------------------------
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
    //echoArray($answers,'saveAnswers',true);
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
        $tPropos = array();
        $tPoints = array();
        foreach ($answers as $ansKey=>$ansValue){
            $proposition = trim($ansValue['proposition']);
            if (!$proposition) continue;
            
            $caption = trim($ansValue['caption']);
            $weight = intval(trim($ansValue['weight']));
    
        	$ansObj = $answersHandler->create();
        	$ansObj->setVar('answer_proposition', $proposition);
        	$ansObj->setVar('answer_quest_id', $questId);
        	//$ansObj->setVar('answer_caption', $caption);
        	$ansObj->setVar('answer_weight', intval(trim($ansValue['weight'])));
        	$ansObj->setVar('answer_points', 0);
        	$ret = $answersHandler->insert($ansObj);
        }
//exit;    
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%2\$s;'>%1\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tExp = explode(',', $ans['proposition']);
    $points = intval($ans['points']);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = $points;
    $scoreMin = 0;
    $color = 'blue';
	foreach($tExp as $key=>$exp) {
        $html[] = sprintf($tpl, $exp, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe


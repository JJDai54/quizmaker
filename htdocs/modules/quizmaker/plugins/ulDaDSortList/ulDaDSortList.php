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
class Plugin_ulDaDSortList extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("ulDaDSortList", 0, "dragAndDrop");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['orderStrict' => 'N', 
                                  'title'       => '', 
                                  'liBgDefault' => '#f5f5f5', 
                                  'liBgActive'  => '#ffe7e7', 
                                  'liBgHover'   => '#00FF00'];
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
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------           

      $name = 'orderStrict';  
      $inputOrder = new \XoopsFormRadio(_AM_QUIZMAKER_ORDER_ALLOWED . ' : ', "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrder->addOption("N", _AM_QUIZMAKER_ONLY_ORDER_NAT);            
      $inputOrder->addOption("R", _AM_QUIZMAKER_ALLOW_ALL_ORDER);            
      $trayOptions->addElementOption($inputOrder);     
      
      $name = 'title';  
      $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_PLUGIN_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
      $trayOptions->addElementOption($inpTitle);     

      $name = 'liBgDefault';   /* background des items par defaut f5f5f5*/
      $inpLiBgDefault = new XoopsFormColorPicker('Couleur par defaut', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpLiBgDefault);     

      $name = 'liBgActive';  /* background de l'item survole pendant drag on drop ffe7e7*/
      $inpLiBgActive = new XoopsFormColorPicker('Couleur de survol pandant le déplacement', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpLiBgActive);     

      $name = 'liBgHover';   /* background de l'item survole avant drag on drop 00FF00*/
      $inpLiBgHover = new XoopsFormColorPicker('Couleur de survol avant selection', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpLiBgHover);     



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
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
        $tbl->addTitleArray(['',_AM_QUIZMAKER_PLUGIN_LABEL,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
                
        for($k = 0; $k < $this->maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
        
            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot2, $proposition);
                
            $name = $this->getName($k, 'weight');
            $inpWeight = new \XoopsFormNumber('',  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 99999);

                
            $tbl->addElement($inpProposition, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
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
        global $utility, $answersHandler, $pluginsHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        //--------------------------------------------------------        
        foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            $ans['proposition'] = FQUIZMAKER\sanityse_inpValue($ans['proposition']);
            if (!$ans['proposition']) continue;
            
        	$ansObj->setVar('answer_proposition', $ans['proposition']);
        	//$ansObj->setVar('answer_caption', $caption);
        	$ansObj->setVar('answer_weight', $ans['weight']);
        	$ansObj->setVar('answer_points', 0);
        	$ret = $answersHandler->insert($ansObj);
        }
   
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


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
class Plugin_sortItems extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("sortItems", 0, "other");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['orderStrict' => 'N', 
                                  'title'       => '', 
                                  'btnColor'    => 'blue', 
                                  'btnHeight'   => 28, 
                                  'mouseClick'  => 1,
                                  'disposition' => 'disposition-001'];

        $this->hasImageMain = true;
        $this->multiPoints = false;
        
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

      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      //$inputDisposition->setHorizontalIconNumber(9);
      $trayOptions->addElementOption($inputDisposition);     
      
      //options dans le cas d'une liste déroullante
      //$trayOptions->addElementOption(new XoopsFormLabel('',QBR . _LG_PLUGIN_SORTITEMS_OPTIONS_LISTBOX));   

      $name = 'btnColor';  
      $btnColors = XoopsLists::getDirListAsArray(QUIZMAKER_PATH_QUIZ_ORG . '/plugins/sortItems/img/buttons', '');
      $impBtnColors = new XoopsFormSelect(_AM_QUIZMAKER_BUTTONS_COLOR, "{$optionName}[{$name}]",$tValues[$name]) ;
      $impBtnColors->addOptionArray($btnColors);
      $trayOptions->addElementOption($impBtnColors);   
        
      $name = 'btnHeight';  
      $inpHeight1 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(22, 96, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElementOption($inpHeight1);     

      $name = 'mouseClick';  
      $inputMouseClick = new XoopsFormRadio(_AM_QUIZMAKER_QUIZ_ONCLICK, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputMouseClick->addOption(0, _AM_QUIZMAKER_CLICK_DOUBLE);   
      $inputMouseClick->addOption(1, _AM_QUIZMAKER_CLICK_SIMPLE);            
      $trayOptions->addElementOption($inputMouseClick);     

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
            $inpWeight = new \XoopsFormNumber('',  $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900);

                
            //----------------------------------------------------------
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
        $tPropos = array();
        $tPoints = array();
        foreach ($answers as $ansKey=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            if (!$ans['proposition']) continue;
            
        	$ansObj->setVar('answer_proposition', $ans['proposition']);
        	$ansObj->setVar('answer_weight', $ans['weight']);
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


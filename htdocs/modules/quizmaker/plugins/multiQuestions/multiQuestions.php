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
class Plugin_multiQuestions extends XoopsModules\Quizmaker\Plugins
{
var $inpMaxlength = 0;    
var $inpSize = 0;    
var $maxMots = 0;    
    
 
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("multiQuestions", 0, "basic");
        $this->setVersion('1.1', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->hasImageMain = true;
        $this->maxPropositions = 6;
        $this->maxMots = 12;
        $this->inpSize = $this->lgMot1;
        $this->inpMaxlength = $this->lgMot2;

        $this->optionsDefaults = ['disposition'  => 'disposition-01',
                                  'inpSize'      => $this->inpSize,
                                  'inpMaxlength' => $this->inpMaxlength];

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
//       //--------------------------------------------------------------------           
//       $name = 'inpSize';  
//       $inpSize = new \XoopsFormNumber(_LG_PLUGIN_MULTIQUESTIONS_INP_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpSize->setMinMax(5, $this->inpMaxCar, _AM_QUIZMAKER_UNIT_CARACTERES);
//       $trayOptions ->addElement($inpSize);     
// 
//       $name = 'inpMaxlength';  
//       $inpMaxlength = new \XoopsFormNumber(_LG_PLUGIN_MULTIQUESTIONS_INP_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpMaxlength->setMinMax(5, $this->inpWidth, _AM_QUIZMAKER_UNIT_CARACTERES);
//       $trayOptions ->addElement($inpMaxlength);     
//       
// 
//       $name = 'disposition'; 
//       $path = $this->pathArr['img'] . "/dispositions"; 
//       $allPluginsName =  \XoopsLists::getDirListAsArray(QUIZMAKER_PATH_PLUGINS_PHP);
//       $imgList = XoopsLists::getFileListByExtension($path,  array('jpg','png','gif'), '');
//       $inputDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
//       $inputDisposition->setGridIconNumber(count($imgList),1);
//       $trayOptions->addElement($inputDisposition);      
      //--------------------------------------------------------------------           

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

//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        $weight = 0;
        $lastWeight = 0;    
        $typeArr = array(0 => _LG_PLUGIN_MULTIQUESTIONS_TYPE_LISTBOX,
                         1 => _LG_PLUGIN_MULTIQUESTIONS_TYPE_TEXTBOX,
                         2 => _LG_PLUGIN_MULTIQUESTIONS_TYPE_CHECKBOX,
                         3 => _LG_PLUGIN_MULTIQUESTIONS_TYPE_RADIO);
//         $typeArr = array('listbox' => _LG_PLUGIN_MULTIQUESTIONS_TYPE_LISTBOX,
//                          'textbox' => _LG_PLUGIN_MULTIQUESTIONS_TYPE_TEXTBOX,
//                          'checkbox' => _LG_PLUGIN_MULTIQUESTIONS_TYPE_CHECKBOX,
//                          'radio' => _LG_PLUGIN_MULTIQUESTIONS_TYPE_RADIO);

 
        $tbl = $this->getNewXoopsTableXtray();
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;');
        $tbl->addTdStyle(0, 'text-align:left;width:30px;');
        $tbl->addTdStyle(1, 'text-align:left;width:700px;');
        $tbl->addTdStyle(2, 'text-align:left;width:150px;');

$lgProposition1 = $this->lgMot3;
$lgProposition2 = $this->lgMot3;
           
        for($k = 0; $k < $this->maxPropositions; $k++){
            $vArr = $this->getAnswerValues($answers[$k], $weight,1);
            foreach($vArr as $key=>$value) $$key = $value;
            //if($isNew) $tExp = array('','','','','','');
            //else $tExp = explode(',', $proposition);
            //$trayCaption = new XoopsFormElementTray  ('', $delimeter = '<br>'); 
            $tplLib80r = "<div style='width:80px;float:left;text-align:right;'>{libelle}</div>";
            $tplLib60r = "<div style='width:60px;float:left;text-align:right;'>{libelle}</div>";
            $tplLib50r = "<div style='width:50px;float:left;text-align:right;'>{libelle}</div>";
            //--------------------------------------------------------- 
            $inpId = new \XoopsFormHidden($this->getName($k,'id'), $id);
            $inpChrono = new \XoopsFormLabel("", $k+1 . " : ");
            if(!$isNew){
              $delProposition = new \XoopsFormCheckBox('', $this->getName($k,'delete'));                        
              $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);
            }else{
              $delProposition = new \XoopsFormLabel('', _CO_QUIZMAKER_NEW);
            }
            
            $name = $this->getName($k, 'caption'); //contient la question
            $lib = str_replace('{libelle}', _LG_PLUGIN_MULTIQUESTIONS_QUESTION, $tplLib80r);
            $inpQuestion = new XoopsFormText($lib, $name, $lgProposition1, $lgProposition2, $caption);

            $name = $this->getName($k, 'proposition');
            $lib = str_replace('{libelle}', _LG_PLUGIN_MULTIQUESTIONS_ANSWERS, $tplLib80r);
            $inpProposition = new XoopsFormText($lib, $name, $lgProposition1, $lgProposition2, $proposition);
            
            $name = $this->getName($k, 'buffer');
            $lib = str_replace('{libelle}', _LG_PLUGIN_MULTIQUESTIONS_INTRUS, $tplLib80r);
            $inpIntrus = new XoopsFormText($lib, $name, $lgProposition1, $lgProposition2, $buffer);
            
            $name = $this->getName($k, 'group');
            $lib = str_replace('{libelle}', _LG_PLUGIN_MULTIQUESTIONS_TYPE_INPUT, $tplLib50r);
            $inpType = new \XoopsFormSelect($lib, $name, $group);
            $inpType->addOptionArray($typeArr);
            
            $name = $this->getName($k, 'inputs');
            $lib = str_replace('{libelle}', _AM_QUIZMAKER_PLUGIN_INPUTS, $tplLib50r);
            if($inputs == 0) $inputs = 1; 
            $inpInputs = new XoopsFormNumber($lib, $name, $this->lgPoints, $this->lgPoints, $inputs);
            $inpInputs->setMinMax(1, 5);

            
            $name = $this->getName($k, 'points');
            $lib = str_replace('{libelle}', _AM_QUIZMAKER_UNIT_POINTS, $tplLib50r);
            $inpPoints = new XoopsFormNumber($lib, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(1, 12);
            
            $name = $this->getName($k, 'weight');
            $lib = str_replace('{libelle}', _AM_QUIZMAKER_WEIGHT, $tplLib50r);
            $inpWeight = new XoopsFormNumber($lib, $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);

            //--------------------------------------------------
            $col = 0;
            $tbl->addElement($inpId, $col, $k);
            $tbl->addElement($inpChrono, $col, $k);
            $tbl->addElement($delProposition, $col++, $k);
            $tbl->addElement($inpQuestion, $col, $k);
            $tbl->addElement($inpProposition, $col, $k);
            $tbl->addElement($inpIntrus, $col++, $k);
            
            $tbl->addElement($inpType, $col, $k);
            $tbl->addElement($inpInputs, $col, $k);
            $tbl->addElement($inpPoints, $col, $k);
            $tbl->addElement($inpWeight, $col, $k);

/*
            //--------------------------------------------------------------
            $trayCaption = new XoopsFormElementTray  ('', $delimeter = ' '); 
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayCaption->addElement($inpLab);
            
            $name = $this->getName($h, 'caption');
            $inpCaption = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_TITLE, $name, $this->lgProposition2, $this->lgProposition2, $caption);
            $trayCaption->addElement($inpCaption);
            
            $name = $this->getName($h, 'inputs');
            $inpInputs = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_INPUTS, $name, $this->lgPoints, $this->lgPoints, $inputs);
            $inpInputs->setMinMax(1, $this->maxInputs);
            $trayCaption->addElement($inpInputs);
            
            $name = $this->getName($h, 'weight');
            $inpWeight = new XoopsFormNumber("poids", $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 1000);
            $trayCaption->addElement($inpWeight);
            
            $trayPropo->addElement($trayCaption);
            //--------------------------------------------------------------
            $trayAllMots = new XoopsFormElementTray  ('', $delimeter = '<br>');            
            for($i = 0; $i < $this->maxMots; $i++){
                $mot    = (isset($tMots[$i])) ? $tMots[$i]: "";
                $points = (isset($tPoints[$i])) ? intval($tPoints[$i]) : 0;
                $trayMot = new XoopsFormElementTray  ('', $delimeter = '');                
                
                $inpLab  = new XoopsFormLabel("",  ($h+1) . ' / ' . ($i+1) . ' : ');
                $trayMot->addElement($inpLab);

                $name = $this->getName($h, 'mots', $i, 'mot');
                $inpMot = new XoopsFormText(_AM_QUIZMAKER_PLUGIN_MOT, $name, $this->inpSize, $this->inpMaxlength, $mot);
                $trayMot->addElement($inpMot);
                
                $name = $this->getName($h, 'mots', $i, 'points');
                $inpPoints = new XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
                $inpPoints->setMinMax(-30, 30);
                $trayMot->addElement($inpPoints);
                //-------------------------------------
                $trayAllMots->addelement($trayMot);                
            }    
            
            $trayPropo->addElement($trayAllMots);
*/           
            
            

        }
        //----------------------------------------------------------
        $this->trayGlobal->addElement($tbl);
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler;
        $this->echoAns ($answers, $questId, $bExit = false);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
        foreach ($answers as $anskey=>$ansValue){
        echo ("===>id = {$ansValue['id']}<br>");
            if(isset($ansValue['delete']) || $ansValue['caption']==''){
                if($ansValue['id'] > 0)
                    $answersHandler->deleteId($ansValue['id']);
            }else{
                if($ansValue['id'] == 0){
          	        $ansObj = $answersHandler->create();
                    $ansObj->setVar('answer_quest_id', $questId);
                }else{
          	        $ansObj = $answersHandler->get($ansValue['id']);
                }
            	$ansValue['caption'] = trim($ansValue['caption']);
            	$ansValue['proposition'] = trim($ansValue['proposition']);
            	$ansValue['buffer'] = trim($ansValue['buffer']);
                
                //correction eventuelle du nombre d'input
                switch($ansValue['group']){
                    case 2: //checkbox et bouton radio on ne fait qu'un seul groupe d'input
                    case 3;
                        $ansValue['inputs'] = 1;
                        break;
                    default: //listbox et textbox, le nombre d'input ne peut âs etre superieur au nomre de bonne réponses
                    $nbGoodAns = count(explode(",", $ansValue['proposition']));
                    if ($ansValue['inputs'] > $nbGoodAns) $ansValue['inputs'] = $nbGoodAns;
                }
                
                //------------------------------------------------------------------
            	$ansObj->setVar('answer_proposition', $ansValue['proposition']);
            	$ansObj->setVar('answer_caption', $ansValue['caption']);
            	$ansObj->setVar('answer_buffer', $ansValue['buffer']);
              
            	$ansObj->setVar('answer_group',  $ansValue['group']);
            	$ansObj->setVar('answer_inputs', $ansValue['inputs']);
            	$ansObj->setVar('answer_points', $ansValue['points']);
            	$ansObj->setVar('answer_weight', $ansValue['weight']);
                
              
        	    $ret = $answersHandler->insert($ansObj);

            
            }
            
//    echo "<hr>answers.mots <pre>" . print_r($ansValue['mots'], true) . "</pre><hr>";
    
        }
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl1 = "<tr><td ><span style='color:%3\$s;font-weight:normal;'>%4\$s %5\$s : %1\$s</span><br>%6\$s : %2\$s</td>"
          . "<td>===>&nbsp;%7\$s&nbsp;%8\$s</td></tr>";
    
//     $tpl2 = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
//              . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
//              . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
//              . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";


    $answersAll = $answersHandler->getListByParent($questId);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        //echoArray($ans);
        //$tp = $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
        //$tp = $this->combineAndSorAnswer($ans);
            
        $color = "black";
        $html[] = sprintf($tpl1, $ans['caption'], 
                                 $ans['proposition'], 
                                 $color,
                                 _LG_PLUGIN_MULTIQUESTIONS_QUESTION_NUM,
                                 $i+1,
                                 _LG_PLUGIN_MULTIQUESTIONS_ANSWERS, 
                                 $ans['points'],
                                 _CO_QUIZMAKER_POINTS);        
        $scoreMax += intval($ans['points']);

        if($i < (count($answersAll)-1))
        $html[] = "<td colspan='2'><hr class='default-hr-style-one'></td>";
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe




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
class Plugin_listboxClassItems extends XoopsModules\Quizmaker\Plugins
{
var $maxGroups = 3;
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("listboxClassItems", 0, "other");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['group0'=>'', 'group1'=>'', 'group2'=>'', 
                                  'groupDefault'   => '-1', 
                                  'mouseClick'     => 1];
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
 	{global $myts;
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);  

      //--------------------------------------------------------------------           
      
      $name = 'mouseClick';  
      $inputMouseClick = new XoopsFormRadio(_AM_QUIZMAKER_QUIZ_ONCLICK, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputMouseClick->addOption(0, _AM_QUIZMAKER_CLICK_DOUBLE);   
      $inputMouseClick->addOption(1, _AM_QUIZMAKER_CLICK_SIMPLE);            
      $trayOptions->addElementOption($inputMouseClick);     

      for($h = 0; $h < $this->maxGroups; $h++){
        $name = 'group' . $h;
        $requis = ($h < 2);
        
        $inpGoup = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . $h . (($requis)?QUIZMAKER_REQUIS:''),  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
        if($requis){
          $inpGoup->setExtra("required placeholder='" . _AM_QUIZMAKER_REQUIRED . "'");
        }else{
          $inpGoup->setExtra("placeholder='" . _AM_QUIZMAKER_OPTIONAL . "'");
        }
        $trayOptions->addElementOption($inpGoup);     
      }
      
      
      $name = 'groupDefault';  
      $inputGroupDefault = new XoopsFormRadio(_AM_QUIZMAKER_REPARTITION, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputGroupDefault->addOption(-1, _AM_QUIZMAKER_REPARTITION_ALL_GROUPS);  
      $inputGroupDefault->addOption(-2, _AM_QUIZMAKER_REPARTITION_ONLY_GROUP0);  
      for($h=0; $h<3; $h++){
        $inputGroupDefault->addOption($h, (($tValues["group{$h}"]) ? $tValues["group{$h}"] : _AM_QUIZMAKER_GROUP . " {$h}"));            
      } 
      $inputGroupDefault->setDescription(_AM_QUIZMAKER_REPARTITION_DESC);  
      $inputGroupDefault->setDescription(_AM_QUIZMAKER_REPARTITION_DESC);
      $trayOptions->addElementOption($inputGroupDefault);     
           
      return $trayOptions;
    }

      
            

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler, $questionsHandler;

        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if(!$options) $options = $this->optionsDefaults;
//echoArray($options, 'getForm');
        $answers = $answersHandler->getListByParent($questId);
//echoArray($answers, 'getForm', true);
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la liste
        $i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, '', $options);
        
        
        //----------------------------------------------------------
        //----------------------------------------------------------------
        $this->initFormForQuestion();
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;

	}
/* *************************************************
* meme procedure pour chaque groupe:
* - image de substitution
* - sequence logique
* - mauvaises reponses
* ************************************************** */
public function getFormGroup(&$trayAllAns, $group, $answers,$titleGroup, $firstItem, $maxItems, $path, $options)
{ 
//echoArray($answers, 'getForm', true);
        //suppression des enregistrement en trop
        if(count($answers) > $maxItems) $this->deleteToMuchItems($answers, $maxItems);
//        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;
        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
//        $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            //recupe des libellés de groupe si ils ont déjà été definis
            //$libGroup0 = _AM_QUIZMAKER_GROUP_ALL;
            $libGroup0 = ($options['group0']) ? $options['group0'] : _AM_QUIZMAKER_GROUP . ' 0';
            $libGroup1 = ($options['group1']) ? $options['group1'] : _AM_QUIZMAKER_GROUP . ' 1';
            $libGroup2 = ($options['group2']) ? $options['group2'] : _AM_QUIZMAKER_GROUP . ' 2';
//            $libGroup3 = ($options['group3']) ? $options['group3'] : _AM_QUIZMAKER_GROUP . ' 3';
            
            //-------------------------------------------------
            $inpProposition = new \XoopsFormText('',  $this->getName($i,'proposition'), $this->lgProposition, $this->lgProposition, $proposition);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900, 'pixels');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_UNIT_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
            $inpPoints->setMinMax(1, 30);
            $inpgroup = new \xoopsFormSelect(_AM_QUIZMAKER_GROUP,  $this->getName($i,'group'), $group); //n° du groupe
            $inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2]);
            
            //------------------------------------------------   
            $tbl->addElement($inpProposition, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpgroup, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
           
        }
        
        $trayAllAns->addElement($tbl, $k);
        return $i+1;  // return le dernier index pour le groupe suivant


}
    
/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler;
        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        //$path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";

/*
echoArray ($_POST,'saveAnswers', true);    
*/
                
        //$this->echoAns ($answers, $questId, $bExit = false);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------       
        
        /*
        */ 
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            

            $ansObj->setVar('answer_proposition', $ans['proposition']);
            //$ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', $ans['points']); 
            $ansObj->setVar('answer_quest_id', $questId); 
            $ansObj->setVar('answer_group', $ans['group']); 
            
            $answersHandler->insert($ansObj);
     }
    }

/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tp = $this->combineAndSorAnswer($ans);    
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach($tp as $key=>$item) {
        $points = intval($item['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }

	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe





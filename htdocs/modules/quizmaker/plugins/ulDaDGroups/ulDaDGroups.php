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
class Plugin_ulDaDGroups extends XoopsModules\Quizmaker\Plugins
{
var $maxGroups = 4;     

	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("ulDaDGroups", 0, "groups");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;

        $this->maxPropositions = 20;	
        $this->optionsDefaults = ['group0'=>'','group1'=>'','group2'=>'','group3'=>'',
                                  'bgGroup0'=>'#dfdfdf','bgGroup1'=>'#dfdfdf','bgGroup2'=>'#dfdfdf','bgGroup3'=>'#dfdfdf',
                                  'ulWidth'=>'28',
                                  'groupDefault'=>'-1', 
                                  'disposition'=>'disposition-02'];
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
    
      $name = 'ulWidth';  
      $inpUlWidth = new \XoopsFormNumber(_LG_PLUGIN_ULDADGROUPS_UL_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpUlWidth->setMinMax(20, 100, _AM_QUIZMAKER_UNIT_PERCENT);
      $trayOptions ->addElementOption($inpUlWidth);     
      
      //--------------------------------------
      // groupes
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_groups.php");

      // disposition
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_disposition.php");

      
      return $trayOptions;
    }

/* *************************************************
* le champ group sert à différencier la suite logique des mauvaises réponses
*  - 0 : suite logique + weight
*  - 1 : mauvaises reponses
*  Le nom de l'image est stocké dans proposition
*  L'image de substitution est stocké dans le champ image
*  Les items a trouvé sont notés avec des points positifs et seront rempacés par l'image de substitution
*  Il peut y avoir plusieurs items a trouver dans la suite (conseillé : 2 ou 3 max )
*  Le nombre d'image de la séquence est de 8 maximum (voir 10 à tester)
*  Le nombre de fausse images est limité aussi à 8 (voir 10 à tester)
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler, $questionsHandler;

        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if(!$options) $options = $this->optionsDefaults;
//echo "<hr><pre>options : " . print_r($options, true) . "</pre><hr>";
        $answers = $answersHandler->getListByParent($questId);
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, '', $options);
        

        
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
        //suppression des enregistrement en trop
        if(count($answers) > $maxItems) $this->deleteToMuchItems($answers, $maxItems);
//        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;
//        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
//        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        //$imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        $tbl->addTitleArray(['',_AM_QUIZMAKER_PLUGIN_LABEL,_AM_QUIZMAKER_PLUGIN_BACKGROUND,_AM_QUIZMAKER_PLUGIN_GROUP,_AM_QUIZMAKER_PLUGIN_POINTS,_AM_QUIZMAKER_PLUGIN_WEIGHT]);

        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            $points = 1;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");
            //-------------------------------------------------
            $isBackground = ($background) ? 1 : 0;
            
            //recupe des libellés de groupe si ils ont déjà été definis
            //$libGroup0 = _AM_QUIZMAKER_GROUP_ALL;
            $libGroup0 = ($options['group0']) ? $options['group0'] : _AM_QUIZMAKER_GROUP . ' 0';
            $libGroup1 = ($options['group1']) ? $options['group1'] : _AM_QUIZMAKER_GROUP . ' 1';
            $libGroup2 = ($options['group2']) ? $options['group2'] : _AM_QUIZMAKER_GROUP . ' 2';
            $libGroup3 = ($options['group3']) ? $options['group3'] : _AM_QUIZMAKER_GROUP . ' 3';
            

            //recupe des libellés de groupe si ils ont déjà été defini
            //$this->echoAns ($options,'options', false);   
            
             
            //if(!$imgName) $imgName     = 'blank-org.jpg';
            //-------------------------------------------------
            $inpProposition = new \XoopsFormText('',  $this->getName($i,'proposition'), $this->lgProposition, $this->lgProposition, $proposition);
            $inpWeight = new \XoopsFormNumber('',  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 99999);
            $inpPoints = new \XoopsFormNumber('',  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
            $inpPoints->setMinMax(1, 30);
            $inpgroup = new \xoopsFormSelect('',  $this->getName($i,'group'), $group); //n° du groupe
            $inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2, '3'=>$libGroup3]);

            $idChkIsBackground = $this->getName($i,'isBackground') ;
            $inpBackground = new XoopsFormColorPicker('', $this->getName($i,'background'), $background);
            $inpBackground->setExtra("onChange=\"document.getElementById('{$idChkIsBackground}1').checked=1;\"");
            //$inpBackground->setExtra("onChange=\"alert('{$idChkIsBackground}');document.getElementById('{$idChkIsBackground}1').checked=1;alert('zzzzzzz');\"");
            $inpIsBackround = new \XoopsFormCheckBox('', $idChkIsBackground, array($isBackground));                        
            $inpIsBackround->addOption(1, ' ');
            //$tbl->addStyle('background:yellow');
               
            //--------------------------------------------------               
            $tbl->addElement($inpProposition, ++$col, $k);
            
            $tbl->addElement($inpIsBackround, ++$col, $k, ' ');
            $tbl->addElement($inpBackground, $col, $k);
            
            
            $tbl->addElement($inpgroup, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpWeight, ++$col, $k);
/*
*/             
           
        }
        
        $trayAllAns->addElement($tbl);
        return $i+1;  // return le dernier index pour le groupe suivant


}

     
/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler;
        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        //--------------------------------------------------------       
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           

            $ans['proposition']  = FQUIZMAKER\sanityse_inpValue($ans['proposition']);
            if(!$ans['proposition']) continue;
            
            if ($ans['points'] == 0) $ans['points'] = 1;

            $ansObj->setVar('answer_proposition', $ans['proposition']);
            //$ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', $ans['points']); 
            $ansObj->setVar('answer_quest_id', $questId); 
            $ansObj->setVar('answer_group', $ans['group']); 
            $ansObj->setVar('answer_background', ($ans['isBackground'] == 1) ? $ans['background'] : ''); 
            
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
		$ret['points']      = $this->getVar('answer_points');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['group']      = $this->getVar('answer_group');
  
  */
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $html = array();
 
    //-------------------------------------------
    $questObj = $questionsHandler->get($questId);
    $quizId = $questObj->getVar("quest_quiz_id");
    $options = json_decode(html_entity_decode($questObj->getVar('quest_options')),true); 
//    echoArray($options);   exit;
    //-------------------------------------------
    
    // commençons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_group, answer_points DESC,answer_weight,answer_id');

//if(!$boolAllSolutions) exit;    
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

    $tplRow = "<tr><td>%1\$s</td>" 
            . "<td>&nbsp;===>&nbsp;</td>" 
            . "<td>%2\$s</td>"
            . "</tr>";
            
    $tplGroup = "<tr><td colspan='4'><center><b>%1\$s</b></center></td></tr>";
            
    $html[] = "<table class='quizTbl'>";
    $currentGroup = '';
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        $group = $options["group" . $ans['group']];
        $proposition = $ans['proposition'];
        $scoreMax += $ans['points'];
        
        if($currentGroup != $group){
	       $html[] = sprintf($tplGroup, $group);
           $currentGroup = $group;
        }else{
        }
	    $html[] = sprintf($tplRow, $proposition, $points);
    }
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la class

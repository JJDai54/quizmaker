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
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  

      //--------------------------------------------------------------------           
      
      $name = 'mouseClick';  
      $inputMouseClick = new XoopsFormRadio(_AM_QUIZMAKER_QUIZ_ONCLICK, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputMouseClick->addOption(0, _AM_QUIZMAKER_CLICK_DOUBLE);   
      $inputMouseClick->addOption(1, _AM_QUIZMAKER_CLICK_SIMPLE);            
      $trayOptions->addElement($inputMouseClick);     

      for($h = 0; $h < $this->maxGroups; $h++){
        $name = 'group' . $h;
        $requis = ($h < 2);
        
        $inpGoup = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . $h . (($requis)?QUIZMAKER_REQUIS:''),  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
        if($requis){
          $inpGoup->setExtra("required placeholder='" . _AM_QUIZMAKER_REQUIRED . "'");
        }else{
          $inpGoup->setExtra("placeholder='" . _AM_QUIZMAKER_OPTIONAL . "'");
        }
        $trayOptions->addElement($inpGoup);     
      }
      
      
      
      
//       $name = 'group0';
//       $inpGoup0 = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . ' 0',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
//       $inpGoup0->setExtra("required placeholder='" . _AM_QUIZMAKER_REQUIRED . "'");
//       $trayOptions->addElement($inpGoup0);     
//       
//       $name = 'group1';
//       $inpGoup1 = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . ' 1',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2,$tValues[$name]);
//       $inpGoup1->setExtra("required placeholder='" . _AM_QUIZMAKER_REQUIRED . "'");
//       $trayOptions->addElement($inpGoup1);     
//       
//       $name = 'group2';
//       $inpGoup2 = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . ' 2',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
//       $inpGoup2->setExtra("placeholder='" . _AM_QUIZMAKER_OPTIONAL . "'");
//       $trayOptions->addElement($inpGoup2);  
//          
      $name = 'groupDefault';  
      $inputGroupDefault = new XoopsFormRadio(_AM_QUIZMAKER_REPARTITION, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputGroupDefault->addOption(-1, _AM_QUIZMAKER_REPARTITION_ALL_GROUPS);  
      $inputGroupDefault->addOption(-2, _AM_QUIZMAKER_REPARTITION_ONLY_GROUP0);  
      for($h=0; $h<3; $h++){
        $inputGroupDefault->addOption($h, (($tValues["group{$h}"]) ? $tValues["group{$h}"] : _AM_QUIZMAKER_GROUP . " {$h}"));            
      } 
      $inputGroupDefault->setDescription(_AM_QUIZMAKER_REPARTITION_DESC);  
      $trayOptions->addElement($inputGroupDefault);     
      $trayOptions->addElement(new \XoopsFormLabel('',_AM_QUIZMAKER_REPARTITION_DESC));     
           
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
public function getFormGroup(&$trayAllAns, $group, $arr,$titleGroup, $firstItem, $maxItems, $path, $options)
{ 
//echoArray($arr, 'getForm', true);
        //suppression des enregistrement en trop
        if(count($arr) > $maxItems) $this->deleteToMuchItems($arr, $maxItems);
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
            $i = $k + $firstItem;
            $weight += 10;
            if (isset($arr[$k])){
                $answerId    = $arr[$k]->getVar('answer_id');
                $proposition = $arr[$k]->getVar('answer_proposition');
                $points      = $arr[$k]->getVar('answer_points');
                $weight      = $weight; // $arr[$i]->getVar('answer_weight');
//                $caption     = $arr[$k]->getVar('answer_caption');
                $group      = $arr[$k]->getVar('answer_group'); // on y met le n° du groupe
                $delete = true;
/*
*/        //choix d'une image existante:
            }else{
                $answerId = 0;
                $proposition = "";
//                $imgName     = '';
                $points      = 1;
                $weight      = $weight;
//                $caption     = '';
                $group       = '0';
                $delete = false;
            }
            
            //recupe des libellés de groupe si ils ont déjà été definis
            //$libGroup0 = _AM_QUIZMAKER_GROUP_ALL;
            $libGroup0 = ($options['group0']) ? $options['group0'] : _AM_QUIZMAKER_GROUP . ' 0';
            $libGroup1 = ($options['group1']) ? $options['group1'] : _AM_QUIZMAKER_GROUP . ' 1';
            $libGroup2 = ($options['group2']) ? $options['group2'] : _AM_QUIZMAKER_GROUP . ' 2';
//            $libGroup3 = ($options['group3']) ? $options['group3'] : _AM_QUIZMAKER_GROUP . ' 3';
            

            //recupe des libellés de groupe si ils ont déjà été defini
            //$this->echoAns ($options,'options', false);   
            
             
            //if(!$imgName) $imgName     = 'blank-org.jpg';
            //-------------------------------------------------
            if($delete){
              $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
              $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);
            }

            $inpAnswerId = new \XoopsFormHidden($this->getName($i,'id'), $answerId);            
            //$inpInput = new \XoopsFormHidden($this->getName($i,'group'), $group);            
            $libChrono = new \XoopsFormLabel('', $i+1); // . "[{$answerId}]"
            $inpChrono = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);    
            $inpProposition = new \XoopsFormText('',  $this->getName($i,'proposition'), $this->lgProposition, $this->lgProposition, $proposition);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900, 'pixels');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_UNIT_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
            $inpPoints->setMinMax(1, 30);
            $inpgroup = new \xoopsFormSelect(_AM_QUIZMAKER_GROUP,  $this->getName($i,'group'), $group); //n° du groupe
            $inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2]);
            
            //$tbl->addStyle('background:yellow');
               
               
            $col = 0;
            $tbl->addElement(new \XoopsFormLabel('','Proposition : ' . ($i+1)), $col, $k);
            $tbl->addElement($inpChrono, -1, $k, '');
            if ($delete) $tbl->addElement($delProposition, $col, $k);
             
            $tbl->addElement($inpAnswerId, ++$col, $k, '');
            //$tbl->addElement($libChrono, $col, $k);
            $tbl->addElement($inpProposition, $col, $k);
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
       foreach ($answers as $key=>$v){
            $answerId = $v['id'];
            if($answerId > 0){
                if($v['proposition'] && !isset($v['delete_Proposition'])) {
                    $ansObj = $answersHandler->get($answerId);
                }else{
                    $answersHandler->deleteId($v['id']);
                    continue;  
                }
                //if(!isset($v['delete_Proposition'])) $v['delete_Proposition'] = 0;
            }else{
                if($v['proposition']) {
                    $ansObj = $answersHandler->create();
                }else{
                    continue;
                }
                //$v['delete_Proposition'] = 0;
            }
        //$this->echoAns ($v, $questId, $bExit = false);    
                $v['quest_id'] = $questId;
            
        //Suppression de la proposition et de l'image
        //if(isset($v['delete_Proposition'])) $this->delete_answer_by_image($v,$path);  
        
        
        //enregistrement de l'image
        //if($_FILES['answers'][name] != '') 
        //recuperation de l'image pour le champ proposition
        //le chrono ne correspond pad forcément à la clé dans files
        //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
        //un pour le champ "proposition" qui stocke l'image principale
        //et un pour le champ imge qui stocke l'image de substitution
        //SSi le champ 'points' est plus petit que zéro on le force à 1
        //if (intval($v['points']) == 0) $v['points'] = 1;

        //if(isset($v['proposition'])) $ansObj->setVar('answer_proposition', $v['proposition']);        
       // if ($fileImg =! '') $ansObj->setVar('answer_proposition', $fileImg);
        

        $ansObj->setVar('answer_proposition', $v['proposition']);
        //$ansObj->setVar('answer_caption', $v['caption']);
        $ansObj->setVar('answer_weight', $v['weight']);
        $ansObj->setVar('answer_points', $v['points']); 
        $ansObj->setVar('answer_quest_id', $questId); 
        $ansObj->setVar('answer_group', $v['group']); 
        
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





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
        parent::__construct("ulDaDGroups", 0, "dragAndDrop");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

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
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           
    
      $name = 'ulWidth';  
      $inpUlWidth = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpUlWidth->setMinMax(20, 100, 'pixels');
      $trayUlWidth = new XoopsFormElementTray(_LG_PLUGIN_ULDADGROUPS_UL_WIDTH, $delimeter = ' ');  
      $trayUlWidth->addElement($inpUlWidth);
      $trayUlWidth->addElement(new \XoopsFormLabel(' ', '%'));
      $trayOptions ->addElement($trayUlWidth);     
      
      //--------------------------------------
      // groupes
      
      for($h = 0; $h < $this->maxGroups; $h++){
          $trayGroup = new XoopsFormElementTray('', $delimeter = ' ');  
          
          $name = 'group' . $h;
          $inpGoup = new \XoopsFormText(_AM_QUIZMAKER_GROUP_LIB . $h,  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
          $trayGroup->addElement($inpGoup);     

          $name = 'bgGroup' . $h;  
          $inpBgGroup = new XoopsFormColorPicker('', "{$optionName}[{$name}]", $tValues[$name]);
          $trayGroup->addElement($inpBgGroup);     
          
          $trayOptions->addElement($trayGroup);     
      }
      //--------------------------------------
      $name = 'groupDefault';  
      $inputGroupDefault = new \XoopsFormRadio(_AM_QUIZMAKER_GROUP_DEFAULT, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputGroupDefault->addOption(-1, _AM_QUIZMAKER_GROUP_ALL);            
      for($h = 0; $h < $this->maxGroups; $h++){ 
        $groupeName = ($tValues['group' . $h]) ? $tValues['group' . $h] : 'group' . $h;
        $inputGroupDefault->addOption($h, $groupeName);            
      }
      $trayOptions ->addElement($inputGroupDefault);     

      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      //$inputDisposition->setHorizontalIconNumber(9);
      $trayOptions->addElement($inputDisposition);     
      
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
public function getFormGroup(&$trayAllAns, $group, $arr,$titleGroup, $firstItem, $maxItems, $path, $options)
{ 
        //suppression des enregistrement en trop
        if(count($arr) > $maxItems) $this->deleteToMuchItems($arr, $maxItems);
//        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;
//        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
//        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        //$imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
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
                $background = $arr[$k]->getVar('answer_background');


/*
*/        
//choix d'une image existante:
            }else{
                $answerId = 0;
                $proposition = "";
//                $imgName     = '';
                $points      = 1;
                $weight      = $weight;
//                $background     = '';
                $group       = '0';
                $delete = false;
                $background = '';
            }
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
            $inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2, '3'=>$libGroup3]);

            $idChkIsBackground = $this->getName($i,'isBackground') ;
            $inpBackground = new XoopsFormColorPicker('Couleur', $this->getName($i,'background'), $background);
            $inpBackground->setExtra("onChange=\"document.getElementById('{$idChkIsBackground}1').checked=1;\"");
            //$inpBackground->setExtra("onChange=\"alert('{$idChkIsBackground}');document.getElementById('{$idChkIsBackground}1').checked=1;alert('zzzzzzz');\"");
            $inpIsBackround = new \XoopsFormCheckBox('', $idChkIsBackground, array($isBackground));                        
            $inpIsBackround->addOption(1, ' ');
            //$tbl->addStyle('background:yellow');
               
               
            $col = 0;
            $tbl->addElement(new \XoopsFormLabel('','Proposition : ' . ($i+1)), $col, $k);
            $tbl->addElement($inpChrono, -1, $k, '');
            if ($delete) $tbl->addElement($delProposition, $col, $k);
             
            $tbl->addElement($inpAnswerId, ++$col, $k, '');
            //$tbl->addElement($libChrono, $col, $k);
            $tbl->addElement($inpProposition, $col, $k);
             
            $tbl->addElement($inpIsBackround, ++$col, $k);
            $tbl->addElement($inpBackground, $col, $k);
            
            $tbl->addElement($inpWeight, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            
            $tbl->addElement($inpgroup, ++$col, $k);
           
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

                
        //$this->echoAns ($answers, $questId, $bExit = false);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------       
        
        /*
        */ 
       foreach ($answers as $key=>$v){
            $answerId = $v['id'];
            $v['proposition']  = FQUIZMAKER\sanityse_inpValue($v['proposition']);
            
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
        if (intval($v['points']) == 0) $v['points'] = 1;

        //if(isset($v['proposition'])) $ansObj->setVar('answer_proposition', $v['proposition']);        
       // if ($fileImg =! '') $ansObj->setVar('answer_proposition', $fileImg);
        

        $ansObj->setVar('answer_proposition', $v['proposition']);
        //$ansObj->setVar('answer_caption', $v['caption']);
        $ansObj->setVar('answer_weight', $v['weight']);
        $ansObj->setVar('answer_points', $v['points']); 
        $ansObj->setVar('answer_quest_id', $questId); 
        $ansObj->setVar('answer_group', $v['group']); 
        $ansObj->setVar('answer_background', ($v['isBackground'] == 1) ? $v['background'] : ''); 
        
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
    // commençons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_weight,answer_id');
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <hr><pre>" . print_r($quizId,true) . "</pre><hr>");
    //recherche du dossier upload du quiz
    $quiz = $quizHandler->get($quizId,"quiz_folderJS");
    $path =  QUIZMAKER_URL_UPLOAD_QUIZ . "/" . $quiz->getVar('quiz_folderJS') . "/images";
    $tplImg = "<img src='{$path}/%s' alt='' title='%s' height='64px'>";
    
    $tImg = array();
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        if ($ans['group'] == 0) {
            $tImg[] = sprintf($tplImg, $ans['proposition'], $ans['proposition']);
        }
	}
    $html[] = implode("\n", $tImg);
    $html[] = "<hr>";
    
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');
//if(!$boolAllSolutions) exit;    
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%6\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $html[] = "<table class='quizTbl'>";
    
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        $imgUrl = sprintf($tplImg, $ans['proposition'], $ans['proposition']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe

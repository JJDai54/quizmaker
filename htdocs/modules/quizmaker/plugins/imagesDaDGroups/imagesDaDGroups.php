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
class Plugin_imagesDaDGroups extends XoopsModules\Quizmaker\Plugins
{
var $maxGroups = 4;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("imagesDaDGroups", 0, "dragAndDrop");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');
        $this->hasZoom = true;

        $this->maxPropositions = 16;	
        $this->optionsDefaults = ['imgHeight1'=>64,'imgHeight2'=>64,
                                  'group0'=>'Panier','group1'=>'','group2'=>'','group3'=>'',
                                  'bgGroup0'=>'#dfdfdf','bgGroup1'=>'#dfdfdf','bgGroup2'=>'#dfdfdf','bgGroup3'=>'#dfdfdf',
                                  'showCaptions'=>'B','groupDefault'=>'-1', 'disposition'=>''];
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
//echoArray($tValues);    
      //Taille des images à regrouper
      $name = 'imgHeight1';
      $inpHeight0 = new \XoopsFormNumber(_AM_QUIZMAKER_SIZE0,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight0->setMinMax(32, 128, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions ->addElementOption($inpHeight0);     

       
      $name = 'showCaptions';  
      $inputShowCaption = new \XoopsFormRadio(_AM_QUIZMAKER_SHOW_CAPTIONS, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputShowCaption->addOption("N", _AM_QUIZMAKER_SHOW_CAPTIONS_NONE);            
      $inputShowCaption->addOption("T", _AM_QUIZMAKER_SHOW_CAPTIONS_TOP);            
      $inputShowCaption->addOption("B", _AM_QUIZMAKER_SHOW_CAPTIONS_BOTTOM);            
      $trayOptions ->addElementOption($inputShowCaption);     
      
      //groups     
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_groups.php");

      // disposition 
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_disposition.php");
   
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
        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  QUIZMAKER_FLD_UPLOAD_QUIZ_JS . "/" . $quiz->getVar('quiz_folderJS') . "/images";
        
//echo "<hr>{$path}<hr>";
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if(!$options) $options = $this->optionsDefaults;
        $answers = $answersHandler->getListByParent($questId);
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path, $options);
        

        
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
public function getLstGroups($caption, $name, $group, &$options){
    //recupe des libellés de groupe si ils ont déjà été definis
    $libGroup0 = ($options['group0']) ? $options['group0'] : _AM_QUIZMAKER_GROUP;
    $libGroup1 = ($options['group1']) ? $options['group1'] : _AM_QUIZMAKER_GROUP . ' 1';
    $libGroup2 = ($options['group2']) ? $options['group2'] : _AM_QUIZMAKER_GROUP . ' 2';
    $libGroup3 = ($options['group3']) ? $options['group3'] : _AM_QUIZMAKER_GROUP . ' 3';

    $inpGroup = new \xoopsFormSelect($caption,  $name, $group); 
    $inpGroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2, '3'=>$libGroup3]);
    
    return $inpGroup;
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
        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        //$imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            
            //-------------------------------------------------

            
            //$inpInput = new \XoopsFormHidden($this->getName($i,'group'), $group);            
            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path, 80, '<br>');
            $inpImageName1 = new \XoopsFormHidden($this->getName($i,'image1'), $image1);            
            $inpCaption = new \XoopsFormText(_AM_QUIZMAKER_CAPTION,  $this->getName($i,'caption'), $this->lgMot1, $this->lgMot1, $caption);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900);
//             $inpWeight->setExtra('style="margin:15px;"');
//             $inpWeight->setClass('quizmaker');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_UNIT_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
            $inpPoints->setMinMax(1, 30);
            //$inpgroup = new \xoopsFormSelect(_AM_QUIZMAKER_GROUP,  $this->getName($i,'group'), $group); //n° du groupe
            //$inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2, '3'=>$libGroup3]);
            $inpgroup  = $this->getLstGroups(_AM_QUIZMAKER_GROUP, $this->getName($i,'group'), $group, $options); 
            
            
            //$tbl->addStyle('background:yellow');
            //------------------------------------------------
            $tbl->addElement($inpImage1, ++$col, $k);
            $tbl->addElement($inpImageName1, $col, $k);
             
            $tbl->addElement($inpCaption, ++$col, $k);
            $tbl->addElement($inpWeight, $col, $k);
            $tbl->addElement($inpPoints, $col, $k);
            
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
        
        $pathImg = $quizHandler->getFolderJS($quizId, 1, 'images');  

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
        
        
            //enregistrement de l'image
            //if($_FILES['answers'][name] != '') 
            //recuperation de l'image pour le champ proposition
            //le chrono ne correspond pad forcément à la clé dans files
            //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
            //un pour le champ "proposition" qui stocke l'image principale
            //et un pour le champ image1 qui stocke l'image de substitution
            $prefix = "quiz-{$questId}-{$ans['chrono']}";
            $formName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $formName, $pathImg, $prefix, $nameOrg);
            if($newImg == ''){
                //$ansObj->setVar('answer_image1', $ans['image1']);        
            }else{
                $ansObj->setVar('answer_image1', $newImg);        
                if(!$ans['caption']) $ans['caption'] = $nameOrg;
            }
            
            $ansObj->setVar('answer_proposition', QUIZMAKER_PROPOSITION_VIDE);
            $ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', $ans['points']); 
            $ansObj->setVar('answer_quest_id', $questId); 
            $ansObj->setVar('answer_group', $ans['group']); 
              
            $answersHandler->insert($ansObj);
     }
     //suppression des propositions qui n'ont pas d'image de definie
     $criteria = new CriteriaCompo(new Criteria('answer_quest_id', $questId, '='));
     $criteria->add(new Criteria('', 0, '=',null,'length(answer_image1)'),"AND");
     $answersHandler->deleteAll($criteria);
     //exit ("<hr>===>saveAnswers<hr>" . $criteria->renderWhere() ."<hr>");
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
    $urlImg = $quizHandler->getFolderJS($quizId, 2, 'images');
    
    $tImg = array();
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        if ($ans['group'] == 0) {
            $tImg[] = sprintf(QUIZMAKER_TPL_IMG1, $urlImg, $ans['image1'], $ans['image1']);
        }
	}
    $html[] = implode("\n", $tImg);
    $html[] = "<hr>";
    
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');
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
        $tokenImg = sprintf(QUIZMAKER_TPL_IMG1, $urlImg, $ans['image1'], $ans['image1']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color, $ans['caption']);
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

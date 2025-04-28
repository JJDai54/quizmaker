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
class Plugin_imagesDaDSortItems extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("imagesDaDSortItems", 0, "dragAndDrop");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->maxPropositions = 14;	
        $this->optionsDefaults = ['imgHeight1'  => 64, 
                                  'imgHeight2'  => 64, 
                                  'moveMode'    => 0, 
                                  'showCaptions'=> 'B',
                                  'directive'   => _CO_QUIZMAKER_NEW];

        $this->hasImageMain = true;
        $this->multiPoints = true;

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

// 	public function getJasonError($err){
// 
//      switch($err){
//         case JSON_ERROR_NONE:  return " - Aucune erreur";        break;
//         case JSON_ERROR_STATE_MISMATCH:  return " - Inadéquation des modes ou underflow";   break;
//         case JSON_ERROR_CTRL_CHAR: return " - Erreur lors du contrôle des caractères"; break;
//         case JSON_ERROR_SYNTAX: return " - Erreur de syntaxe ; JSON malformé"; break;
//         case JSON_ERROR_UTF8: return " - Caractères UTF-8 malformés, probablement une erreur d\'encodage";  break;
//         default: return " - Erreur inconnue";  break;
//     }  
// }

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------           

      $name = 'imgHeight1';  
      $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_IMAGESDADSORTITEMS_IMG1_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 300, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions ->addElementOption($inpHeight1);     
      
      $name = 'imgHeight2';  
      $inpHeight2 = new \XoopsFormNumber(_LG_PLUGIN_IMAGESDADSORTITEMS_IMG2_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight2->setMinMax(32, 300, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions ->addElementOption($inpHeight2);     
      
      $name = 'showCaptions';  
      $inputShowCaption = new \XoopsFormRadio(_AM_QUIZMAKER_SHOW_CAPTIONS, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputShowCaption->addOption("N", _AM_QUIZMAKER_SHOW_CAPTIONS_NONE);            
      $inputShowCaption->addOption("T", _AM_QUIZMAKER_SHOW_CAPTIONS_TOP);            
      $inputShowCaption->addOption("B", _AM_QUIZMAKER_SHOW_CAPTIONS_BOTTOM);            
      $trayOptions ->addElementOption($inputShowCaption);     
      
      
      $name = 'moveMode';  
      $inpMoveMode = new \xoopsFormRadio(_AM_QUIZMAKER_MOVE_MODE, "{$optionName}[{$name}]" ,$tValues[$name] , ' ');
      $inpMoveMode->addOptionArray(['0'=>_LG_PLUGIN_IMAGESDADSORTITEMS_FLIP, "1"=>_LG_PLUGIN_IMAGESDADSORTITEMS_INSERT, "2"=>_LG_PLUGIN_IMAGESDADSORTITEMS_CARRET]);
      $trayOptions ->addElementOption($inpMoveMode);     

      $name = 'directive';  
      if ($tValues[$name] == _CO_QUIZMAKER_NEW) $tValues[$name] = _LG_PLUGIN_IMAGESDADSORTITEMS_DIRECTIVE_LIB;
      $inpDirective = new \XoopsFormText(_LG_PLUGIN_IMAGESDADSORTITEMS_DIRECTIVE, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
      $inpDirective->setDescription(_LG_PLUGIN_IMAGESDADSORTITEMS_DIRECTIVE_DESC);
      $trayOptions ->addElementOption($inpDirective);     
      
     
      //--------------------------------------------------------------------           
     
      return $trayOptions;
    }


/* *************************************************
* le champ inputs sert à différencier la suite logique des mauvaises réponses
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
        global $utility, $answersHandler, $quizHandler;
        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
//echo "<hr>{$path}<hr>";

        //lecture de toutes les proposition et répartition en 
        // - sequence logique
        // - image de remplacement
        // - mauvaise réponses
        $answers = $answersHandler->getListByParent($questId);
                

//$this->echoAns ($sequence, "getForm : count = " . count($answers) . " | " . count($substitutImg) . " | " . count($sequence) . " | " . count($badly), false);
//echo "getForm : count = " . count($answers) . " | " . count($substitutImg) . " | " . count($sequence) . " | " . count($badly) . '<br>';
        
        //-------------------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path);
        
        //----------------------------------------------------------------
        $this->initFormForQuestion();

        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}
/* *************************************************
* meme procedure pour chaque groupe:
* - image de substitution
* - sequence logique
* - mauvaises reponses
* ************************************************** */
public function getFormGroup(&$trayAllAns, $answers,$titleGroup, $firstItem, $maxItems, $path)
{ 
   
        //suppression des enregistrement en trop
        if(count($answers) > $maxItems) $this->deleteToMuchItems($answers, $maxItems);
//         $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//         $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;

        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            
            $inpInputs = new \XoopsFormHidden($this->getName($i,'inputs'), $inputs);            
            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
            $inpImage2 = $this->getXoopsFormImage($image2, $this->getName()."_image2_{$i}", $path);
            //$inpImage1Path = new \XoopsFormHidden($this->getName($i,'image1'), $image1);            
            //$libProposition = new \XoopsFormLabel('',  $image1; // pour le dev

              $inpProposition = new \XoopsFormText(_AM_QUIZMAKER_CAPTION,  $this->getName($i,'proposition'), $this->lgMot1, $this->lgMot2, $proposition);
              $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
              $inpWeight->setMinMax(0, 1000);
            
            
              $inpPoints = new \XoopsFormHidden($this->getName($i,'points'), '');   
              $inpImage = new \XoopsFormHidden($this->getName($i,'image'), '');   
            
            //----------------------------------------------------
             $tbl->addElement($inpInputs,  $col, $k);
             
             $tbl->addElement($inpImage1,  ++$col, $k);
             $tbl->addElement($inpImage2,  ++$col, $k);

             $tbl->addElement($inpProposition,  ++$col, $k);
             $tbl->addElement($inpWeight,  ++$col, $k);
            
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
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
                
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
            
            //Suppression de la proposition et de l'image
            if( $ans['delete'] == 1) $this->delete_answer_by_image($ans,$path);  
            
            //affectation d'une valeur par défaut pour ce type de question
            //Il n'y a pas de points défini pour chaque proposition
            if ($ans['points'] == 0) $ans['points'] = 5;
            
            //enregistrement de l'image
            //if($_FILES['answers'][name] != '') 
            //recuperation de l'image pour le champ proposition
            //le chrono ne correspond pad forcément à la clé dans files
            //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
            //un pour le champ "proposition" qui stocke l'image principale
            //et un pour le champ imge qui stocke l'image de substitution
            
            $prefix = "quiz-{$questId}-{$ans['chrono']}";        
            $imgFormName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $imgFormName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);        
            }else{
                $ansObj->setVar('answer_image1', $newImg);        
            }
            
            $imgFormName = $this->getName()."_image2_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $imgFormName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);        
            }else{
                $ansObj->setVar('answer_image2', $newImg);        
            }
            
            $ansObj->setVar('answer_proposition', $ans['proposition']);
            $ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', $ans['points']); 
            //$ansObj->setVar('answer_image1', $ans['image']); 
            $ansObj->setVar('answer_quest_id', $questId); 
            $ansObj->setVar('answer_inputs', $ans['inputs']); 
            
              
            $answersHandler->insert($ansObj);
            //exit;
     }
     //suppression des propositions qui n'ont pas d'image de definie
     $criteria = new CriteriaCompo(new Criteria('answer_quest_id', $questId, '='));
     $criteria->add(new Criteria('', 0, '=',null,'length(answer_image1)'),"AND");
     $answersHandler->deleteAll($criteria);
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
		$ret['inputs']      = $this->getVar('answer_inputs');
  
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
        if ($ans['inputs'] == 0) {
            $tImg[] = sprintf($tplImg, $ans['proposition'], $ans['proposition']);
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

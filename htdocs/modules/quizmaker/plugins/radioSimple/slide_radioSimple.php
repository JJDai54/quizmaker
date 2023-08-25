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
include_once QUIZMAKER_PATH . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_radioSimple extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioSimple", 0, "radio");
        $this->optionsDefaults = ['shuffleAnswers'=>1, 'imgHeight'=> 80];
        $this->hasImageMain = true;
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
      $name = 'imgHeight';  
      $inpHeight1 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 300);
      $trayHeight1 = new \XoopsFormElementTray(_AM_QUIZMAKER_IMG_HEIGHT1, $delimeter = ' ');  
      $trayHeight1->addElement($inpHeight1);
      $trayHeight1->addElement(new \XoopsFormLabel(' ', _AM_QUIZMAKER_PIXELS));
      $trayOptions->addElement($trayHeight1);     

      $name = 'shuffleAnswers';  
      $inputShowCaption = new \XoopsFormRadioYN(_AM_QUIZMAKER_SHUFFLE_ANS . ' : ', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions ->addElement($inputShowCaption);     
      
      $trayOptions ->addElement(new XoopsFormLabel('', _AM_QUIZMAKER_SHUFFLE_ANS_DESC));     
      
      //--------------------------------------------------------------------           
      
      return $trayOptions;
    }


/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        //element definissant un objet ou un ensemble
  
//        $tbl = $this->getNewXoopsTableXtray();
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");        
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
        //-------------------------------------------------------------
        // affichage des proposition 
$i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path);
        
        //----------------------------------------------------------------
/*
        for ($k = 0; $k < $this->maxPropositions; $k++){
            if (isset($answers[$k])) {
                $propos = $answers[$k]->getVar('answer_proposition');
                $points = $answers[$k]->getVar('answer_points');
            }else{
                $propos = '';
                $points = 0;
            };

            $col = 0;
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");
            $tbl->addElement($inpLab, $col++, $k, '');
        
            $name = $this->getName($k, 'proposition');
            $inpPropos = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_PROPO, $name, $this->lgProposition, $this->lgProposition, $propos);
            $tbl->addElement($inpPropos, $col++, $k, '');
        
            $name = $this->getName($k, 'points');
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $tbl->addElement($inpPoints, $col++, $k, '');
        
        
       
        }
        $this->trayGlobal->addElement($tbl);
        //----------------------------------------------------------
		return $this->trayGlobal;
*/
        $this->initFormForQuestion();

        //-------------------------------------------------
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
public function getFormGroup(&$trayAllAns, $inputs, $arr,$titleGroup, $firstItem, $maxItems, $path)
{ 
    
        //suppression des enregistrement en trop
        if(count($arr) > $maxItems) $this->deleteToMuchItems($arr, $maxItems);
//         $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//         $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;

        $tbl = $this->getNewXoopsTableXtray();
        $tbl->addTdStyle(0, 'width:120px');
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $ans = $this->getAnswerValues($arr[$k], $weight);
            $i = $k + $firstItem;
            $tInp = $this->getAnswerInp($ans, $i, $inputs, $path);
            foreach($tInp as $key=>$val) $$key = $val; 
            //if(!$imgName) $imgName     = 'blank-org.jpg';
            //-------------------------------------------------
//define('', "");            
         
            //----------------------------------------------------

            $col=0;
            $tbl->addElement(new \XoopsFormLabel('', ($i+1)), $col, $k);
            $tbl->addElement($inpChrono, -1, $k, '');
            $tbl->addElement($inpAnswerId,  -1, $k, '');
            $tbl->addElement($delProposition,  $col, $k);
            $tbl->addElement($inpInputs,  $col, $k);
            
            $tbl->addElement($inpProposition,  ++$col, $k);
            $tbl->addElement($inpWeight,  ++$col, $k);
            $tbl->addElement($inpPoints,  ++$col, $k);
            $tbl->addElement($inpImage1,  ++$col, $k, '<br>');
            $tbl->addElement($inpImage1Lib,  $col, $k);
/*
            
            $tbl->addElement($inpCaption,  ++$col, $k);
            
            $tbl->addElement($inpImage,  ++$col, $k);
            $tbl->addElement($inpImgSubstitut,  $col, $k);
            //$tbl->addElement($delSubstitut,  $col, $k);
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
        global $utility, $answersHandler, $quizHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    

        $quiz = $quizHandler->get($quizId);    //  ,"quiz_folderJS"
        $path = QUIZMAKER_UPLOAD_PATH . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
        //--------------------------------------------------------    
$this->echoAns ($answers,'answers', false);    
/*
$this->echoAns ($_POST,'POST', false);    
$this->echoAns ($_FILES,'Fichiers', false);    
echo '<hr>saveAnswers - POST : ';
echo '<hr>saveAnswers - FILES : ';
echo '<hr>';
exit;
*/
            
        //--------------------------------------------------------    
        foreach ($answers as $key=>$tAns){
            $answerId = $tAns['id'];
            $propos = trim($tAns['proposition']);
            $img1FormName = $this->getName()."_image1_" . ($tAns['chrono']-1);
            //$keyFile = array_search($img1FormName, $_POST['xoops_upload_file']);            
            if($propos == '' &&  array_search($img1FormName, $_POST['xoops_upload_file']) === false) {
                //ça ça ne devrait pas arriver, mais il faut gérer la compatibiité ascendante
                if($answerId != 0 ) $this->delete_answer_by_image($tAns, $path);  ;      
                continue;
            };
            //-----------------------------------
                
            
            
            if($answerId > 0){
                if( isset($tAns['delete_Proposition'])) {
                    $this->delete_answer_by_image($tAns, $path);  
                    continue;
                }
                $ansObj = $answersHandler->get($answerId);
            }else{
                $ansObj = $answersHandler->create();
        	    $ansObj->setVar('answer_quest_id', $questId);
            }
            //----------------------------------------------------------------------- 
            
        
            if(isset($tAns['delete_image1'])) {
                //todo : supprimer le fichier
                $this->delete_image($ansObj->getVar('answer_image1'), $path);
                $tAns['image1'] = '';  
                if($propos == '')  $this->delete_answer_by_image($tAns, $path);  ;                      
            }
            if(isset($tAns['delete_image2'])) {
                //todo : supprimer le fichier
                $this->delete_image($tAns['image2'], $path);
                $tAns['image2'] = '';  
            }
            //------------------------------------------------------

            $points = intval(trim($tAns['points']));
            //------------------------------------------------------
    

        	$ansObj->setVar('answer_proposition', $propos);
        	$ansObj->setVar('answer_points', $points);
        	$ansObj->setVar('answer_weight', $key * 10);
            
        	$ansObj->setVar('answer_caption', '');
        	$ansObj->setVar('answer_inputs', 0);
            //--------------------------------------------------
            $prefix = "quiz-{$questId}-{$tAns['chrono']}";
            //$nameOrg = '';
            $newImg = $this->save_img($tAns, $img1FormName, $path, null, $prefix, $nameOrg);
            if($newImg != '') $tAns['image1'] = $ansObj->setVar('answer_image1', $newImg);



        	$ret = $answersHandler->insert($ansObj);
        }
//exit;
    
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;
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
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";
        
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');

//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $html = array();
    $html[] = "<table class='quizTbl'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        if ($points > 0) {
            if ($scoreMax < $points) $scoreMax = $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            if ($scoreMin > $points) $scoreMin = $points;
            $color = QUIZMAKER_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_POINTS_NULL;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
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



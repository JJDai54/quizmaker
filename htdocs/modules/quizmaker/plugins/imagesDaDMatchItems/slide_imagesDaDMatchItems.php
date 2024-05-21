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
include_once QUIZMAKER_PATH_MODULE . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_imagesDaDMatchItems extends XoopsModules\Quizmaker\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("imagesDaDMatchItems", 0, "dragAndDrop");
        $this->maxPropositions = 12;	
        $this->optionsDefaults = ['imgHeight1'  => 64,
                                  'imgHeight2'  => 48,
                                  'directive'   => '', 
                                  'disposition' => 'dispositions-10'];
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

      $name = 'imgHeight1';  
      $inpHeight1 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 128);
      $trayHeight1 = new \XoopsFormElementTray(_AM_QUIZMAKER_SEQUENCE0, $delimeter = ' ');  
      $trayHeight1->addElement($inpHeight1);
      $trayHeight1->addElement(new \XoopsFormLabel(' ', _AM_QUIZMAKER_PIXELS));
      $trayOptions->addElement($trayHeight1);     


      $name = 'imgHeight2';  
      $inpHeight2 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight2->setMinMax(32, 128);
      $trayHeight2 = new \XoopsFormElementTray(_AM_QUIZMAKER_SEQUENCE1, $delimeter = ' ');  
      $trayHeight2->addElement($inpHeight2);
      $trayHeight2->addElement(new \XoopsFormLabel(' ', _AM_QUIZMAKER_PIXELS));
      $trayOptions->addElement($trayHeight2);     
 
       $name = 'directive';  
      $inpDirective = new \XoopsFormText(_AM_QUIZMAKER_DIRECTIVE, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot4, $tValues[$name]);
      $trayOptions ->addElement($inpDirective);     
      $trayOptions ->addElement(new XoopsFormLabel('', _AM_QUIZMAKER_DIRECTIVE_DESC));      
     
      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputDisposition = new \XoopsFormIconeSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      //$inputDisposition->setHorizontalIconNumber(9);
      $trayOptions->addElement($inputDisposition);     

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
        $answers = $answersHandler->getListByParent($questId);
        
        //-------------------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage des proposition 
$i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, 0, $this->maxPropositions, $path);
        
        //----------------------------------------------------------------
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
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $i = $k + $firstItem;
            $weight += 10;
            if (isset($arr[$k])){
                $answerId    = $arr[$k]->getVar('answer_id');
                $proposition = $arr[$k]->getVar('answer_proposition');
                $image       = $arr[$k]->getVar('answer_image');
                $points      = $arr[$k]->getVar('answer_points');
                $weight      = $weight; // $arr[$i]->getVar('answer_weight');
                $caption     = $arr[$k]->getVar('answer_caption');
                $addNew = false;
/*
*/        //choix d'une image existante:
            }else{
                $answerId = 0;
                $proposition = "";
                $image     = '';
                $points      = 0;
                $weight      = $weight;
                $caption     = '';
                $addNew = true;
            }
            //if(!$imgName) $imgName     = 'blank-org.jpg';
            //-------------------------------------------------
//define('', "");            
            $inpAnswerId = new \XoopsFormHidden($this->getName($i,'id'), $answerId);            
            $inpInputs = new \XoopsFormHidden($this->getName($i,'inputs'), $inputs);            
            $libChrono = new \XoopsFormLabel('', $i+1); // . "[{$answerId}]"
            $inpChrono = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);    
            $inpPropositionImg = $this->getXoopsFormImage($proposition, $this->getName()."_proposition_{$i}", $path,80,'<br>');
            $inpImage= new \XoopsFormHidden($this->getName($i,'image'), $image);    
            
              $libProposition = new \XoopsFormLabel('', $proposition);                        
              $libImage = new \XoopsFormLabel('', $image);                        
            
            if($addNew){
              $delProposition = new \XoopsFormLabel('', QUIZMAKER_NEW);                        
              //$delSubstitut = new \XoopsFormLabel('', QUIZMAKER_NEW);                        
            }else{
              $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
              $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);
            
              //$delSubstitut = new \XoopsFormCheckBox('', $this->getName($i,'delete_image_Substitution'));
              //$delSubstitut->addOption(1, _AM_QUIZMAKER_DELETE);
            }
            
            $inpProposition = new \XoopsFormHidden($this->getName($i,'proposition'), $proposition);            
            //$libProposition = new \XoopsFormLabel('',  $proposition; // pour le dev

            
            // a remplacer par le chargement d'un image
            //$inpPropos = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_IMG,       $this->getName($i,'proposition'), $this->lgMot1, $this->lgMot1, $proposition);

                //c'est une séquence logique
              //$inpProposition = new \XoopsFormHidden($this->getName($i,'proposition'), $this->getName($i,'proposition'));            
              $inpCaption = new \XoopsFormText(_AM_QUIZMAKER_CAPTION,  $this->getName($i,'caption'), $this->lgMot1, $this->lgMot1, $caption);
              $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
              $inpWeight->setMinMax(0, 900);
              $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
              $inpPoints->setMinMax(0, 30);
              
//         $inpImgSubstitut= new \XoopsFormSelect(_AM_QUIZMAKER_IMG_SUBSTITUT, $this->getName($i,'image'),$image);   
//         $inpImgSubstitut->addOptionArray($listImg);
//$inpImgSubstitut = $this->getFormSelectImage(_AM_QUIZMAKER_IMG_SUBSTITUT, $image, $this->getName($i,'image'), $imgUrl, $imgList, $maxWidth=80);    
            $inpImgSubstitut = $this->getXoopsFormImage($image, $this->getName()."_substitut_{$i}", $path, 80, '<br>', $this->getName($i,'delete_image_Substitution'));
         
            //----------------------------------------------------

            $col=0;
            $tbl->addElement(new \XoopsFormLabel('','Proposition : ' . ($i+1)), $col, $k);
            $tbl->addElement($inpChrono, -1, $k, '');
            $tbl->addElement($inpAnswerId,  -1, $k, '');
            $tbl->addElement($delProposition,  $col, $k);
            $tbl->addElement($inpInputs,  $col, $k);
            
            $tbl->addElement($inpPropositionImg,  ++$col, $k);
            $tbl->addElement($inpProposition,  $col, $k, '');
            $tbl->addElement($libProposition,  $col, $k);
            
            $tbl->addElement($inpCaption,  ++$col, $k);
            $tbl->addElement($inpWeight,  $col, $k);
            $tbl->addElement($inpPoints,  $col, $k);
            
            $tbl->addElement($inpImage,  ++$col, $k);
            $tbl->addElement($inpImgSubstitut,  $col, $k);
            //$tbl->addElement($delSubstitut,  $col, $k);
            $tbl->addElement($libImage,  $col, $k);
            
        }

        $trayAllAns->addElement($tbl);
        return $i+1;  // return le dernier index pour le groupe suivant
}


/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $type_questionHandler, $quizHandler;
        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
echo '<hr>saveAnswers - POST : ';
$this->echoAns ($_POST,'POST', false);    
/*
echo '<hr>saveAnswers - FILES : ';
$this->echoAns ($_FILES,'Fichiers', false);    
echo '<hr>';
exit;
*/
                
        //$this->echoAns ($answers, $questId, $bExit = false);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------       
        
        /*
        */ 
       foreach ($answers as $key=>$v){
            $answerId = $v['id'];
            if($answerId > 0){
                $ansObj = $answersHandler->get($answerId);
                if(!isset($v['delete'])) $v['delete'] = 0;
            }else{
                $ansObj = $answersHandler->create();
                //$v['delete_Proposition'] = 0;
                //$v['delete_image_Substitution'] = 0;
            }
        //$this->echoAns ($v, $questId, $bExit = false);    
        $v['quest_id'] = $questId;
            
        //Suppression de la proposition et de l'image
        if( isset($v['delete_Proposition'])) {
            $this->delete_answer_by_image($v,$path);  
            continue;
        }
        
        if( isset($v['delete_image_Substitution'])) {
            //todo : supprimer le fichier
            $v['image'] = '';  
        }





        
        //enregistrement de l'image
        //if($_FILES['answers'][name] != '') 
        //recuperation de l'image pour le champ proposition
        //le chrono ne correspond pad forcément à la clé dans files
        //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
        //un pour le champ "proposition" qui stocke l'image principale
        //et un pour le champ imge qui stocke l'image de substitution
        $formName = $this->getName()."_proposition_" . ($v['chrono']-1);
        $prefix = "quiz-{$questId}-{$v['chrono']}";
        //$nameOrg = '';
        $newImg = $this->save_img($v, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix, $nameOrg);
        if($newImg != ''){
            $v['proposition'] = $newImg;        
            $v['caption'] =  $nameOrg;
        }

        //idem pour le champ image qui sctocke celle de substitution
        $formName = $this->getName()."_substitut_" . ($v['chrono']-1);
        $newImg = $this->save_img($v, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
        if($newImg != ''){
            $v['image'] = $newImg;    
            //Si il y a une image de substitution on force le nombre de points aux ou il auraut été oublué                
            if ($v['points'] == 0) $v['points']  = 1;        
        }

        //pad d'image de substitution donc mauvaise reponse on foree les points a 0
        if($v['image'] == '') $v['points'] = 0;        
        
        //Si le champ 'points' est plus petit ou égal à zéro , suppression de l'image de substitution
//         if (intval($v['points']) == 0){
//             $ansObj->setVar('answer_image', '');        
//         }else{
//         } 

        //if(isset($v['proposition'])) $ansObj->setVar('answer_proposition', $v['proposition']);        
       // if ($fileImg =! '') $ansObj->setVar('answer_proposition', $fileImg);
        
        $ansObj->setVar('answer_proposition', $v['proposition']);
        $ansObj->setVar('answer_caption', $v['caption']);
        $ansObj->setVar('answer_image', $v['image']);
        $ansObj->setVar('answer_weight', $v['weight']);
        $ansObj->setVar('answer_points', $v['points']); 
        $ansObj->setVar('answer_quest_id', $questId); 
        $ansObj->setVar('answer_inputs', $v['inputs']); 
        
        if ($v['points'] == 0) $v['image'] = '';
        //else if($v['image'] == '') $v['image'] = 'interrogation-05-bleu.png';
        
        //$ansObj->setVar('answer_image', $v['image']); 
        //$ansObj->setVar('answer_group', $v['group']); 
          
        $answersHandler->insert($ansObj);
     }
//        exit;
     //suppression des propositions qui n'ont pas d'image de definie
     $criteria = new CriteriaCompo(new Criteria('answer_quest_id', $questId, '='));
     $criteria->add(new Criteria('', 0, '=',null,'length(answer_proposition)'),"AND");
     $answersHandler->deleteAll($criteria);
     //exit ("<hr>===>saveAnswers<hr>" . $criteria->renderWhere() ."<hr>");
//     exit('fin de saveAnswer');
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

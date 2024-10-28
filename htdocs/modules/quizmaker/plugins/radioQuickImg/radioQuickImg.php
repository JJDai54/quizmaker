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
class Plugin_radioQuickImg extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioQuickImg", 0, "images");
        $this->maxPropositions = 9;	
        $this->hasImageMain = true;
        $this->optionsDefaults = ['imgHeight1'      => 64,
                                  'posLibelleV'     => 30,
                                  'fontSize'        => '1.1',
                                  'shuffleAnswers'  => 0,
                                  'gotoNextOnClick' => 0,
                                  'repartition'     => '321',
                                  'disposition'     => 'disposition-00'];
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
      $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_RADIOQUICKIMG_IMG_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 128, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElement($inpHeight1);     

      $name = 'posLibelleV';  
      $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_RADIOQUICKIMG_IMG_TOP,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(-150, 150, _AM_QUIZMAKER_UNIT_PERCENT);
      $trayOptions->addElement($inpHeight1);     

      $name = 'fontSize';  
      $inpDisposition = new \XoopsFormText(_LG_PLUGIN_RADIOQUICKIMG_FONT_SIZE, "{$optionName}[{$name}]",5,5, $tValues[$name]);
      $trayOptions->addElement($inpDisposition);     
      //$trayOptions->addElement(new \XoopsFormLabel('', _LG_PLUGIN_RADIOQUICKIMG_FONT_SIZE_DESC));      
      
      $trayOptions->addElement(new \XoopsFormLabel('', ''));      
      
//       $name = 'posLibelle';  
// 	  $inpPosLibelle = new \XoopsFormRadio(_LG_PLUGIN_RADIOQUICKIMG_POSITION_LIBELLE, "{$optionName}[{$name}]", $tValues[$name]);
//       $inpPosLibelle->addOptionArray(['0'=>_LG_PLUGIN_RADIOQUICKIMG_POSITION_0, '1'=>_LG_PLUGIN_RADIOQUICKIMG_POSITION_1 , '2'=>_LG_PLUGIN_RADIOQUICKIMG_POSITION_2]);
//       $inpPosLibelle->setDescription(_AM_QUIZMAKER_POS_COMMENT_DESC);
//       $trayOptions->addElement($inpPosLibelle);

      $name = 'shuffleAnswers';  
      $inputShuffleAnswers = new \XoopsFormRadioYN(_AM_QUIZMAKER_SHUFFLE_ANS, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inputShuffleAnswers);     
      $trayOptions->addElement(new \XoopsFormLabel('', _AM_QUIZMAKER_SHUFFLE_ANS_DESC . QBR));      

      $name = 'gotoNextOnClick';  
      $inputShuffleAnswers = new \XoopsFormRadioYN(_LG_PLUGIN_RADIOQUICKIMG_GOTONEXT, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElement($inputShuffleAnswers);     
      $trayOptions->addElement(new \XoopsFormLabel('', _LG_PLUGIN_RADIOQUICKIMG_GOTONEXT_DESC . QBR));      
      
      $name = 'repartition';  
      $inpDisposition = new \XoopsFormText(_LG_PLUGIN_RADIOQUICKIMG_DISPOSITION, "{$optionName}[{$name}]",20,20, $tValues[$name]);
      // $inpDisposition->setDescription(_LG_PLUGIN_RADIOQUICKIMG_DISPOSITION_DESC);      
      $trayOptions->addElement($inpDisposition);     
      $trayOptions->addElement(new \XoopsFormLabel('', _LG_PLUGIN_RADIOQUICKIMG_DISPOSITION_DESC . QBR));      
      
      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inputDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inputDisposition->setSelectedIconSize(64, 64);
      $inputDisposition->setIconSize(64, 64);
      $trayOptions->addElement($inputDisposition);     

//      $path = $this->pathArr['img'] . "/buttons"; 
// 
//       $name = 'zzz';
//       $zzz = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
//       //$zzz->setSelectedIconWidth(120);
//       $zzz->setSelectedIconSize(120, 120);
//       $zzz->setIconSize(120, 120);
//       $zzz->setGridIconNumber(5, 3);
//       $trayOptions->addElement($zzz);   
//         
//       $name = 'yyy';
//       $yyy = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
//       //$zzz->setSelectedIconWidth(120);
//       $yyy->setSelectedIconSize(64, 64);
//       $yyy->setIconSize(120, 120);
//       $yyy->setGridIconNumber(3);
//       $trayOptions->addElement($yyy);   
      
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
        
        //-------------------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
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
        $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
        $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;




        $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
        $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';
        $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $i = $k + $firstItem;
            $weight += 10;
            
            if (isset($arr[$k])){
                $answerId    = $arr[$k]->getVar('answer_id');
                $proposition = $arr[$k]->getVar('answer_proposition');
                $image1      = $arr[$k]->getVar('answer_image1');
                $image2      = $arr[$k]->getVar('answer_image2');
                $points      = $arr[$k]->getVar('answer_points');
                $weight      = $weight; 
                $caption     = $arr[$k]->getVar('answer_caption');
                $color  = $arr[$k]->getVar('answer_color');
/*
*/        //choix d'une image existante:
            }else{
                $answerId = 0;
                $proposition = "";
                $image1     = '';
                $image2     = '';
                $points      = 0;
                $weight      = $weight;
                $caption     = '';
                $color       = '';
            }
            //if(!$image1) $image1     = 'blank-org.jpg';
/*
*/            
            //-------------------------------------------------
//echoArray($arr);            
            $inpAnswerId = new \XoopsFormHidden($this->getName($i,'id'), $answerId);
            $libChrono = new \XoopsFormLabel('', $i+1);          
            //$inpChrono = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);            
            $inpChrono = new \XoopsFormLabel('', $i+1);            
            
            
            $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete'),);                        
            $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);
            
            $inpPropos = new \XoopsFormText(_AM_QUIZMAKER_ANSWER . " {$i} ", $this->getName($i,'proposition'), $this->lgMot1, $this->lgMot1, $proposition);
            $inpPoints = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $inpWeight = new \XoopsFormNumber(_AM_QUIZMAKER_PLUGIN_WEIGHT,  $this->getName($i,'weight'), $this->lgWeight, $this->lgWeight, $weight += 10);
            $inpWeight->setMinMax(0, 999);


            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path, 60, '<br>',$this->getName($i,'delete_image1'));
            $inpColor = new XoopsFormColorPicker('Text', $this->getName($i,'color'), $color);


            $btnPath = QUIZMAKER_PATH_QUIZ_ORG . '/plugins/' . $this->pluginName .  '/img/buttons';            $name =  $this->getName($i,'image2');
            $inpImage2 = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, $name, $image2, $btnPath);
            //$zzz->setSelectedIconWidth(120);
            $inpImage2->setSelectedIconSize(48, 48);
            $inpImage2->setIconSize(64, 64);
            $inpImage2->setGridIconNumber(4);
            
            $labImg1OrImg2 =  new \XoopsFormLabel("",_LG_PLUGIN_RADIOQUICKIMG_IMG1_OR_IMG2);
//-------------------------------------------------------------------
            $inpChrono = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);


            //----------------------------------------------------

            $tbl->addElement($inpChrono, -1);
               
            $col = 0;
            //$tbl->addElement($libChrono, $col, $k);
            $tbl->addElement($delProposition, $col, $k);
            //$tbl->addElement($inpInputs, $col, $k);
            $tbl->addElement($inpPropos, ++$col, $k);
                         
            $tbl->addElement($inpAnswerId, $col, $k);
            //$tbl->addElement($libChrono, $col, $k);
            $tbl->addElement($inpImage2, ++$col, $k);
            $tbl->addElement($labImg1OrImg2, ++$col, $k);
            $tbl->addElement($inpImage1, ++$col, $k);
             
            //$tbl->addElement($inpCaption, ++$col, $k);
            $tbl->addElement($inpPoints, ++$col, $k);
            $tbl->addElement($inpWeight, $col, $k);
           
            $tbl->addElement($inpColor, $col, $k);

            
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
// echoArray($answers); exit;
// echoArray($_FILES);       
        /*
        */ 
       foreach ($answers as $key=>$v){
            $answerId = $v['id'];
            if($answerId > 0){
                $ansObj = $answersHandler->get($answerId);
                if(!isset($v['delete'])) $v['delete'] = 0;
            }else{
                $ansObj = $answersHandler->create();
    		    $ansObj->setVar('answer_quest_id', $questId);        
                $v['delete'] = 0;
            }
        //$this->echoAns ($v, $questId, $bExit = false);    
            
        //Suppression de la proposition et de l'image
        if( $v['delete'] == 1) {
            $this->delete_answer_by_image($v,$path);  
            continue;
        }
        //if( $v['answer_proposition'] == '') continue;
        //Suppression de l'image
        if( $v['delete_image1'] == 1) {
           $v['image1'] = '';  
           $ansObj->setVar('answer_image1',  '');          
        }
        
        
  		$ansObj->setVar('answer_proposition', $v['proposition']);
  		$ansObj->setVar('answer_points',  $v['points']);
  		$ansObj->setVar('answer_weight',  $v['weight']);
        $ansObj->setVar('answer_color',   $v['color']);          
        $ansObj->setVar('answer_image2',  $v['image2']);          
        
        $formName = $this->getName()."_image1_" . ($v['chrono']-1);
        $prefix = "quiz-{$questId}-{$v['chrono']}";

        $newImg = $this->save_img($v, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
        if($newImg == ''){
            //$ansObj->setVar('answer_proposition', $v['proposition']);        
        }else{
            $ansObj->setVar('answer_image1', $newImg);        
        }
        
        
        
        
        
        
        

        $answersHandler->insert($ansObj);
     }

     //suppression des propositions qui n'ont pas d'image de definie
     /*
       $criteria = new CriteriaCompo(new Criteria('answer_quest_id', $questId, '='));
       $criteria->add(new Criteria('', 0, '=',null,'length(answer_proposition)'),"AND");
       $answersHandler->deleteAll($criteria);
     */
    }
    


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $questionsHandler, $quizHandler;
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
  $question = $questionsHandler->get($questId);
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
    
    if($question->getVar('quest_image')){
        $quiz = $quizHandler->get($question->getVar('quest_quiz_id'));
        $folderJS = $quiz->getVar('quiz_folderJS');
        $sourcePath = QUIZMAKER_URL_UPLOAD_QUIZ . "/{$folderJS}/images";
        $html[] = "<center><img src='{$sourcePath}/{$question->getVar('quest_image')}' height='90px' title='' ></center>";
    }
    $html[] = "<table class='quizTbl'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = QUIZMAKER_POINTS_POSITIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_QUIZMAKER_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += $points;
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

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
class Plugin_sortItems extends XoopsModules\Quizmaker\Plugins
{
var $noClass = "00-none";
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("sortItems", 0, "other");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = ['classe'      => $this->noClass, 
                                  'orderStrict' => 'N', 
                                  'title'       => '', 
                                  'btnColor'    => 'blue', 
                                  'btnHeight'   => 28, 
                                  'liBgDefault' => '#f5f5f5', 
                                  'liBgActive'  => '#ffe7e7', 
                                  'liBgHover'   => '#00FF00',
                                  'imgHeight1'  => 64, 
                                  'imgHeight2'  => 64, 
                                  'moveMode'    => 1, 
                                  'showCaptions'=> 'B',
                                  'directive'   => _CO_QUIZMAKER_NEW];
                                  
        $this->hasImageMain = true;
        $this->multiPoints = false;
        
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
     
      $name = 'classe';
      $inpClasse = new \XoopsFormSelect(_LG_PLUGIN_SORTITEMS_CLASSE, "{$optionName}[{$name}]", $tValues[$name]);
      if (!$tValues[$name] || $tValues[$name] == $this->noClass) $inpClasse->addOption($this->noClass, _LG_PLUGIN_SORTITEMS_CLASSE_SELECT);
      $inpClasse->addOption('01-listbox', _LG_PLUGIN_SORTITEMS_CLASSE_LISTBOX);
      $inpClasse->addOption('02-combobox', _LG_PLUGIN_SORTITEMS_CLASSE_COMBOBOX);
      $inpClasse->addOption('03-listeapuces', _LG_PLUGIN_SORTITEMS_CLASSE_LISTUL);
      $inpClasse->addOption('04-imagesdad', _LG_PLUGIN_SORTITEMS_CLASSE_IMAGEDAD);
      $inpClasse->setDescription(_LG_PLUGIN_SORTITEMS_CLASSE_DESC);
      // change la couleur de fond selon que la classe a été selectionnée ou pas
      if($tValues['classe'] == $this->noClass){ 
            $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
      }else{
            $inpClasse->setExtra('style="background:lime;"');
      }
      $trayOptions->addElementOption($inpClasse, true);     

      switch($tValues['classe']){ // correspond au nom des images dans "plugins\sortItems\img\classes"
        case '01-listbox' : 
            /* *********************************************************** */  
            $trayOptions->insertBreak("<hr><div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'>" . _LG_PLUGIN_SORTITEMS_OPTIONS_LISTBOX . "</div>");  
       
            $name = 'btnColor'; 
            $path = $this->pathArr['img'] . "/buttons"; 
            $btnColors = new \XoopsFormIconSelect(_AM_QUIZMAKER_BUTTONS_COLOR, "{$optionName}[{$name}]", $tValues[$name], $path);
            $btnColors->setHorizontalIconNumber(3);
            $trayOptions->addElementOption($btnColors);     
       
              
            $name = 'btnHeight';  
            $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SORTITEMS_BTN_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpHeight1->setMinMax(22, 96, _AM_QUIZMAKER_UNIT_PIXELS);
            $trayOptions->addElementOption($inpHeight1);     

        case '02-combobox' : 
            break;
        case '03-listeapuces' : 
            /* *********************************************************** */  
            $trayOptions->insertBreak("<hr><div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'>" . _LG_PLUGIN_SORTITEMS_OPTIONS_LISTUL . "</div>");  
            
            $name = 'liBgDefault';   /* background des items par defaut f5f5f5*/
            $inpLiBgDefault = new XoopsFormColorPicker('Couleur par defaut', "{$optionName}[{$name}]", $tValues[$name]);
            $trayOptions->addElementOption($inpLiBgDefault);     

            $name = 'liBgActive';  /* background de l'item survole pendant drag on drop ffe7e7*/
            $inpLiBgActive = new XoopsFormColorPicker('Couleur de survol pandant le déplacement', "{$optionName}[{$name}]", $tValues[$name]);
            $trayOptions->addElementOption($inpLiBgActive);     

            $name = 'liBgHover';   /* background de l'item survole avant drag on drop 00FF00*/
            $inpLiBgHover = new XoopsFormColorPicker('Couleur de survol avant selection', "{$optionName}[{$name}]", $tValues[$name]);
            $trayOptions->addElementOption($inpLiBgHover);     
            break;
            
        case '04-imagesdad' : 
            /* *********************************************************** */  
            $trayOptions->insertBreak("<hr><div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'>" . _LG_PLUGIN_SORTITEMS_OPTIONS_DADIMAGE . "</div>");  

            $name = 'imgHeight1';  
            $inpHeight1 = new \XoopsFormNumber(_LG_PLUGIN_SORTITEMS_IMG1_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpHeight1->setMinMax(32, 300, _AM_QUIZMAKER_UNIT_PIXELS);
            $trayOptions ->addElementOption($inpHeight1);     
            
            $name = 'imgHeight2';  
            $inpHeight2 = new \XoopsFormNumber(_LG_PLUGIN_SORTITEMS_IMG2_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
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
            $inpMoveMode->addOptionArray(['0'=>_LG_PLUGIN_SORTITEMS_FLIP, "1"=>_LG_PLUGIN_SORTITEMS_INSERT, "2"=>_LG_PLUGIN_SORTITEMS_CARRET]);
            $trayOptions ->addElementOption($inpMoveMode);     

            $name = 'directive';  
            if ($tValues[$name] == _CO_QUIZMAKER_NEW) $tValues[$name] = _LG_PLUGIN_SORTITEMS_DIRECTIVE_LIB;
            $inpDirective = new \XoopsFormText(_LG_PLUGIN_SORTITEMS_DIRECTIVE, "{$optionName}[{$name}]", $this->lgMot3, $this->lgMot5, $tValues[$name]);
            $inpDirective->setDescription(_LG_PLUGIN_SORTITEMS_DIRECTIVE_DESC);
            $trayOptions ->addElementOption($inpDirective);     
            break;
      

     }
    if($tValues['classe'] != $this->noClass){ // si la classe n'a pas été selectionner pas d'affichage des opttions commune
        $name = 'orderStrict';  
        $inputOrder = new \XoopsFormRadio(_AM_QUIZMAKER_ORDER_ALLOWED . ' : ', "{$optionName}[{$name}]", $tValues[$name], ' ');
        $inputOrder->addOption("N", _AM_QUIZMAKER_ONLY_ORDER_NAT);            
        $inputOrder->addOption("R", _AM_QUIZMAKER_ALLOW_ALL_ORDER);            
        $trayOptions->addElementOption($inputOrder);     

        $name = 'title';  
        $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_PLUGIN_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
        $trayOptions->addElementOption($inpTitle);     
    }


      /* *********************************************************** */  
      // Petite bidouille pour conserver les options non utiliséees selon la classe au cas ou il y aurait plusieurs fois un changement de classe par erreur  
      $hiddenArr = array();
      foreach($this->optionsDefaults as $name=>$v){
          $hiddenArr[$name] = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      }
/*
      $trayOptions->addElementHidden($hiddenArr['classe']);     
      $trayOptions->addElementHidden($hiddenArr['orderStrict']);     
      $trayOptions->addElementHidden($hiddenArr['title']);     
      $trayOptions->addElementHidden($hiddenArr['btnColor']);     
      $trayOptions->addElementHidden($hiddenArr['btnHeight']);     
      $trayOptions->addElementHidden($hiddenArr['liBgDefault']);     
      $trayOptions->addElementHidden($hiddenArr['liBgActive']);     
      $trayOptions->addElementHidden($hiddenArr['liBgHover']);     
      $trayOptions->addElementHidden($hiddenArr['imgHeight1']);     
      $trayOptions->addElementHidden($hiddenArr['imgHeight2']);     
      $trayOptions->addElementHidden($hiddenArr['moveMode']);     
      $trayOptions->addElementHidden($hiddenArr['showCaptions']);     
      $trayOptions->addElementHidden($hiddenArr['directive']);     
*/

     // on ajoute les xoopsFormHidden pour completer et conserver les valeurs suite à un changement de classe par erreur
      switch($tValues['classe']){ // correspond au nom des images dans "plugins\sortItems\img\classes"
        case '01-listbox' : 
            /* *********************************************************** */  
            $trayOptions->addElementHidden($hiddenArr['liBgDefault']);     
            $trayOptions->addElementHidden($hiddenArr['liBgActive']);     
            $trayOptions->addElementHidden($hiddenArr['liBgHover']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight1']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight2']);     
            $trayOptions->addElementHidden($hiddenArr['moveMode']);     
            $trayOptions->addElementHidden($hiddenArr['showCaptions']);     
            $trayOptions->addElementHidden($hiddenArr['directive']);     
            break;

        case '02-combobox' : 
            $trayOptions->addElementHidden($hiddenArr['btnColor']);     
            $trayOptions->addElementHidden($hiddenArr['btnHeight']);     
            $trayOptions->addElementHidden($hiddenArr['liBgDefault']);     
            $trayOptions->addElementHidden($hiddenArr['liBgActive']);     
            $trayOptions->addElementHidden($hiddenArr['liBgHover']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight1']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight2']);     
            $trayOptions->addElementHidden($hiddenArr['moveMode']);     
            $trayOptions->addElementHidden($hiddenArr['showCaptions']);     
            $trayOptions->addElementHidden($hiddenArr['directive']);     
            break;
            
        case '03-listeapuces' : 
            /* *********************************************************** */  
            $trayOptions->addElementHidden($hiddenArr['btnColor']);     
            $trayOptions->addElementHidden($hiddenArr['btnHeight']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight1']);     
            $trayOptions->addElementHidden($hiddenArr['imgHeight2']);     
            $trayOptions->addElementHidden($hiddenArr['moveMode']);     
            $trayOptions->addElementHidden($hiddenArr['showCaptions']);     
            $trayOptions->addElementHidden($hiddenArr['directive']);     
            break;
            
        case '04-imagesdad' : 
            /* *********************************************************** */  
            $trayOptions->addElementHidden($hiddenArr['btnColor']);     
            $trayOptions->addElementHidden($hiddenArr['btnHeight']);     
            $trayOptions->addElementHidden($hiddenArr['liBgDefault']);     
            $trayOptions->addElementHidden($hiddenArr['liBgActive']);     
            $trayOptions->addElementHidden($hiddenArr['liBgHover']);     
            break;
      
      }
      
      /* *********************************************************** */  
      return $trayOptions;
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler, $questionsHandler;

        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";

        $answers = $answersHandler->getListByParent($questId);
        //echoArray($answers,'getForm',true);
        $this->initFormForQuestion();

        //-------------------------------------------------
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if (!$options['classe'] || $options['classe'] == $this->noClass) return null;
        //echo "===> " . $options['classe'] . "<hr>";exit;
        $isImage = ($options['classe'] == '04-imagesdad');
        

        //-------------------------------------------------
        //element definissat un objet ou un ensemble
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        $tbl->addTdStyle(2, 'text-align:left;width:50px;');
        if($isImage){
            $tbl->addTitleArray(['',_LG_PLUGIN_SORTITEMS_IMAGE_TO_SORT,_AM_QUIZMAKER_PLUGIN_LABEL,_LG_PLUGIN_SORTITEMS_IMAGE_REFERANTE,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
        }else{
            $tbl->addTitleArray(['',_AM_QUIZMAKER_PLUGIN_LABEL,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
        }

        for($k = 0; $k < $this->maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormText("", $name, $this->lgMot2, $this->lgMot2, $proposition);
            $inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
            $inpImage2 = $this->getXoopsFormImage($image2, $this->getName()."_image2_{$i}", $path);
                        
                            
            $name = $this->getName($k, 'weight');
            $inpWeight = new \XoopsFormNumber('',  $name, $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900);

                
            //----------------------------------------------------------
            if($isImage){
                $tbl->addElement($inpImage1,  ++$col, $k);            
            }
            $tbl->addElement($inpProposition, ++$col, $k);
            if($isImage){
                $tbl->addElement($inpImage2,  ++$col, $k);            
            }
            $tbl->addElement($inpWeight, ++$col, $k);
        }
        
        $this->trayGlobal->addElement($tbl);
        //----------------------------------------------------------
		return $this->trayGlobal;
	}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
    //echoArray($answers,'saveAnswers',true);
        global $utility, $answersHandler, $pluginsHandler, $quizHandler;
//         echoArray("PGF");
//         $this->echoAns ($answers, $questId, $bExit = true);    
        //--------------------------------------------------------     
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";   
        $tPropos = array();
        $tPoints = array();
        foreach ($answers as $ansKey=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            


            //enregistrement de l'image
            //if($_FILES['answers'][name] != '') 
            //recuperation de l'image pour le champ proposition
            //le chrono ne correspond pas forcément à la clé dans files
            //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
            //un pour le champ "proposition" qui stocke l'image principale
            //et un pour le champ imge qui stocke l'image de substitution
            
            $prefix = "quiz-{$questId}-{$ans['chrono']}";        
            $imgFormName = $this->getName()."_image1_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $imgFormName, $path, $quiz->getVar('quiz_folderJS'), $prefix, $nameOrg);
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);        
            }else{
                $ansObj->setVar('answer_image1', $newImg);        
                if(!$ans['proposition']) $ans['proposition'] = $nameOrg;
            }

            $imgFormName = $this->getName()."_image2_" . ($ans['chrono']-1);
            $newImg = $this->save_img($ans, $imgFormName, $path, $quiz->getVar('quiz_folderJS'), $prefix);
            if($newImg == ''){
                //$ansObj->setVar('answer_proposition', $ans['proposition']);        
            }else{
                $ansObj->setVar('answer_image2', $newImg);        
            }

            if (!$ans['proposition']) continue;
        	$ansObj->setVar('answer_proposition', $ans['proposition']);
        	$ansObj->setVar('answer_weight', $ans['weight']);
            
        	$ret = $answersHandler->insert($ansObj);
        }
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%2\$s;'>%1\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tExp = explode(',', $ans['proposition']);
    $points = intval($ans['points']);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = $points;
    $scoreMin = 0;
    $color = 'blue';
	foreach($tExp as $key=>$exp) {
        $html[] = sprintf($tpl, $exp, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe


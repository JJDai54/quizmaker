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
class Plugin_allumettes extends XoopsModules\Quizmaker\Plugins
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("allumettes", 0, "games");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->hasZoom = false;
        $this->hasImageMain = false;
        $this->multiPoints = false;                
        $this->maxPropositions = 5;	
        
        $this->optionsDefaults = ['variant'         => $this::noClass, 
                                  'gameWidth'       => 600,
                                  'gameHeight'      => 300,
                                  'gridSize'        => 20,
                                  'rotation'        => 24, //15°
                                  'allowRotation'   => 1,
                                  'maxMouvements'   => 0, //nombre maximum d'allumette à deplacer°
                                  'maxAttempts'      => 0,
                                  'addAllumettes'   => 0,
                                  'allumetteWidth'  => 10,
                                  'allumetteHeight' => 70,
                                  'maxPropositions' => $this->maxPropositions, 
                                  'backgroundColor' => '#F0F0F0',
                                  'allowNext'       => 0,
                                  'marge'           => 1,
                                  'radius'          => 0];

        $this->addMessages(['replay', 'remaining','addallumettes', 'delallumettes']);

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
	public function loadJS()
	{
//         $jsArr =  array('Touches','findObject_events','messages-fr','colorPicker');
//         $jsPath = QUIZMAKER_PATH_PLUGINS_PHP . "/{$this->pluginName}/js/";
//         $jsUrl = QUIZMAKER_URL_PLUGINS_PHP   . "/{$this->pluginName}/js/";  
//         
//         // chargement du fichier "Touches.js" qui se trouve dans quiz-org
//         // pour eviter un doublon a maintenir
//         $url = QUIZMAKER_URL_ASSETS . "/js/quiz-org/plugins/allumettes/Touche.js";
//         $GLOBALS['xoTheme']->addScript($url);
//         
//         $url = QUIZMAKER_URL_ASSETS . "/js/quiz-org/plugins/allumettes/Touches.js";
//         $GLOBALS['xoTheme']->addScript($url);
//         
//         //chargement des autres JS utilisé uniquement dans l'admin du module
//         foreach($jsArr as $k=>$js){
//             $f = $jsPath . $js . ".js";  
//              // echo "javascript du plugin : {$f}<hr>";
//             if (file_exists($f)){
//               $url = $jsUrl . $js . ".js";  
//               //echo "javascript du plugin <br>{$jsPath}<br>{$url}<hr>";
//               $GLOBALS['xoTheme']->addScript($url);
//             }
//         }

    }


/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null, $folderJS = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------      
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AP_QUIZMAKER_OPTIONS));  
      
      $name = 'gameWidth';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      $name = 'gameHeight';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      $name = 'gridSize';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      $name = 'rotation';
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      $name = 'allumetteWidth';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      $name = 'allumetteHeight';  
      $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));    
      
/* ************************************************* 
les options sont gérée dans le javascript et sont répercuter dans les xoopsFormHidden
je garde les input nuber au cas ou, mais seront a virer in fine

      $name = 'gameWidth';  
      $inpGameWidth = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_GAME_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGameWidth->setMinMax(200, 600, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpGameWidth->setDescription();
      $inpGameWidth->setStep(10);
      $trayOptions->addElementOption($inpGameWidth);     
      

      $name = 'gameHeight';  
      $inpGameHeight = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_GAME_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGameHeight->setMinMax(100, 400, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpGameHeight->setDescription();
      $inpGameHeight->setStep(10);
      $trayOptions->addElementOption($inpGameHeight);     
      

      $name = 'gridSize';  
      $inpGridSize = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_GRID_SIZE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpGridSize->setMinMax(5, 30, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpGridSize->setDescription();
      $inpGridSize->setStep(5);
      $trayOptions->addElementOption($inpGridSize);     
      
      $name = 'rotation';
      $inpRotation= new \XoopsFormSelect(_LG_PLUGIN_ALLUMETTES_ROTATION, "{$optionName}[{$name}]", $tValues[$name]);   
      $inpRotation->setDescription(_AM_QUIZMAKER_ROTATION_DESC);
      $inpRotation->addOption( 0,  "0"   . _AM_QUIZMAKER_UNIT_DEGRES);
      $inpRotation->addOption(24, "15"   . _AM_QUIZMAKER_UNIT_DEGRES);
      $inpRotation->addOption(16, "22.5" . _AM_QUIZMAKER_UNIT_DEGRES);
      $inpRotation->addOption(12, "30"   . _AM_QUIZMAKER_UNIT_DEGRES);
      $inpRotation->addOption( 8, "45"   . _AM_QUIZMAKER_UNIT_DEGRES);
      $inpRotation->addOption( 4, "90"   . _AM_QUIZMAKER_UNIT_DEGRES);
      $trayOptions->addElementOption($inpRotation);     
      //-----------------------------------------
        
      $name = 'allumetteWidth';  
      $inpAllumetteWidth = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_AL_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpAllumetteWidth->setMinMax(5, 30, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpAllumetteWidth->setDescription();
      $inpAllumetteWidth->setStep(1);
      $trayOptions->addElementOption($inpAllumetteWidth);   
      

      $name = 'allumetteHeight';  
      $inpAllumetteHeight = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_AL_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpAllumetteHeight->setMinMax(10, 90, _AM_QUIZMAKER_UNIT_PIXELS, "1");
      //$inpAllumetteHeight->setDescription();
      $inpAllumetteHeight->setStep(1);
      $trayOptions->addElementOption($inpAllumetteHeight);   

      $trayImgGrid = new \XoopsFormElementTray(_AP_QUIZMAKER_IMG_GRID, '&nbsp;');
*/           


      //-----------------------------------------
      $name = 'maxMouvements';  
      $inpMaxMouvements = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_MAX_ALTO_MOVE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMaxMouvements->setMinMax(0, 20);
      $inpMaxMouvements->setDescription(_LG_PLUGIN_ALLUMETTES_MAX_ALTO_MOVE_DESC);
      //$inpMaxMouvements->setStep(1);
      $trayOptions->addElementOption($inpMaxMouvements);   

      $name = 'allowRotation';  
      $inpAllowRotation = new \XoopsFormRadioYN(_AP_QUIZMAKER_ALLOW_ROTATION, "{$optionName}[{$name}]", $tValues[$name]);
      $inpAllowRotation->setDescription(_AP_QUIZMAKER_ALLOW_ROTATION_DESC);
      $trayOptions->addElementOption($inpAllowRotation);   

      $name = 'maxAttempts';
      $inpMaxAttemps = new \XoopsFormNumber(_AP_QUIZMAKER__MAXTRY,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name], 'style="background:#FFCC66;"');
      $inpMaxAttemps->setMinMax(0, 12, _AM_QUIZMAKER_UNIT_ATTEMPTS);
      //$inpMaxAttemps->setDescription(_AM_QUIZMAKER_UNIT_ATTEMPTS_DESC);
      $trayOptions ->addElementOption($inpMaxAttemps);  
      
      $name = 'addAllumettes';
      $inpAddAllumettes = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_ALLUMETTES_TO_ADD,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name], 'style="background:#FFCC66;"');
      $inpAddAllumettes->setMinMax(0, 25, _LG_PLUGIN_ALLUMETTES);
      $inpAddAllumettes->setDescription(_LG_PLUGIN_ALLUMETTES_ALLUMETTES_TO_ADD_DESC);
      $trayOptions ->addElementOption($inpAddAllumettes);  
      
      $name = 'backgroundColor';  
      $inpBackground = new XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpBackground);   
       
      $name = 'maxPropositions';  
      $inpMaxSolutions = new \XoopsFormNumber(_LG_PLUGIN_ALLUMETTES_MAXPROPOSITIONS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMaxSolutions->setMinMax(5, 12, '', "1");
      $inpMaxSolutions->setDescription(_LG_PLUGIN_ALLUMETTES_MAXPROPOSITIONS_DESC);
      $trayOptions->addElementOption($inpMaxSolutions);     
      
       $name = 'allowNext';  
//       $inpAllowNext = new \XoopsFormRadioYN(_AP_QUIZMAKER_ALLOW_NEXT, "{$optionName}[{$name}]", $tValues[$name]);
       $inpAllowNext = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
       $trayOptions->addElementOption($inpAllowNext);   
      
      //--------------------------------   
      //insertion des messages de transition
      include (QUIZMAKER_PATH_PLUGINS_INCLUDE . "/options_messages.php");


      /* *********************************************************** */  
      return $trayOptions;
    }

/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler,$xoTheme;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $options = $this->getOptionsByQuestId($questId);
        //-------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        

//===============================================
        
//         if($isImage){
//             $tbl->addTitleArray(['',_LG_PLUGIN_SORTITEMS_IMAGE_TO_SORT,_AM_QUIZMAKER_PLUGIN_LABEL,_LG_PLUGIN_SORTITEMS_IMAGE_REFERANTE,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
//         }else{
//             $tbl->addTitleArray(['',_AM_QUIZMAKER_PLUGIN_LABEL,_AM_QUIZMAKER_PLUGIN_WEIGHT]);
//         }
//echoArray($answers);
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
/*
*/
\JANUS\include_highslide(null,"quizmaker");     
$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');

//$isOpen=true;
$zzz = new \XoopsFormLabel('ssssss','ppppp');
$exp="cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>cmbv,cvmb,c,vb,cvmb,mcv,bmcv,b<br>";
        //function __construct($caption, $name , $value, $isOpen = false)
        $trayHeader = new \XoopsFormShowHide(_AM_QUIZMAKER_PLUGIN_HELP, 'togodo',$this->getFormGroup($answers, $path, $options), false); 
        $trayHeader->setLibelle('caption', _AM_QUIZMAKER_PLUGIN_HELP_LIBELLE);
        $trayHeader->setLibelle('show', _AM_QUIZMAKER_PLUGIN_HELP_SHOW);
        $trayHeader->setLibelle('hide', _AM_QUIZMAKER_PLUGIN_HELP_HIDE);
        $trayHeader->setLibelle('close', _AM_QUIZMAKER_PLUGIN_HELP_CLOSE);
        $trayHeader->setIsOpen($isOpen);
        $trayHeader->setBackcolor('rgb(241,227,209)');
        
        $this->trayGlobal->addElement($trayHeader);
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";



       // $i = $this->getFormGroup($trayAllAns, 0, $answers,'', 0, $this->maxPropositions);        
        
//===============================================


        $this->trayGlobal->addElement($this->getFormPlateau($options));
                                
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}

 /* *************************************************
*
* ************************************************** */
 public function getFormGroup($answers, $path, $options){
        $weight = 0;
        $tbl = $this->getNewXoopsTableXtray('', 'padding:5px 0px 0px 5px;', "style='width:60%;'");
        //$tbl->addTdStyle(2, 'text-align:left;width:50px;');

        //il ya 2 proposition lelo1 et memo2
        for ($k = 0; $k < $options['maxPropositions']; $k++){
        //for ($k = 0; $k < $this->maxPropositions; $k++){
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/getFormGroup.php");

            $name = $this->getName($k, 'proposition-log');
            $inpPropositionLog = new \XoopsFormText("", $name, $this->lgMot4, $this->lgMot5, $proposition);
            $inpPropositionLog->setExtra("disabled='disabled'");
            
            
            
            $name = $this->getName($k, 'proposition');
            $inpProposition = new \XoopsFormHidden($name, $proposition);
            //----------------------------------------------
            $name = $this->getName($k, 'weight-log');
            $inpWeightLog = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot1, $weight);
            $inpWeightLog->setExtra("disabled='disabled'");
            
            $name = $this->getName($k, 'weight');
            $inpWeight = new \XoopsFormHidden($name, $weight);
            //$inpImage1 = $this->getXoopsFormImage($image1, $this->getName()."_image1_{$i}", $path);
        
            
            
            //if($points < 1) $points = 1;
            //$inpPoints = new \XoopsFormNumber('',  $this->getName($k,'points'), $this->lgPoints, $this->lgPoints, $points);
            //$inpPoints->setMinMax(1, 30);
        
        //----------------------------------------------------------
        //$cols=0;
        //$tbl->addElement($inpImage1,  ++$col, $k);
        $tbl->addElement($inpPropositionLog, ++$col, $k); 
        $tbl->addElement($inpProposition, $col, $k); 
        
        $tbl->addElement($inpWeightLog, ++$col, $k); 
        $tbl->addElementHidden($inpWeight);
//         if($options['variant'] == 'memory'){
//             $inpInfo = new \XoopsFormLabel('', _AP_QUIZMAKER_POINTS_BY_IMG);
//             $tbl->addElement($inpInfo, $col, $k); 
         }
//         $tbl->addElement($inpPoints, ++$col, $k); 
        //$this->trayGlobal->addElement($tbl);
        return $tbl;
    }
    
   
/* *************************************************
* Interfaface java script a parametrer avec les optionss du plugins
* et a charger après le jeu lui même
* ************************************************** */

public function getFormPlateau($options)
{ 
    $optArr = [];
    $optArr[] = ($options['rotation'] ==  0) ? 'selected=""' : '';
    $optArr[] = ($options['rotation'] == 24) ? 'selected=""' : '';
    $optArr[] = ($options['rotation'] == 16) ? 'selected=""' : '';
    $optArr[] = ($options['rotation'] == 12) ? 'selected=""' : '';
    $optArr[] = ($options['rotation'] ==  8) ? 'selected=""' : '';
    $optArr[] = ($options['rotation'] ==  4) ? 'selected=""' : '';

   
$lib_save_defi          = _LG_PLUGIN_ALLUMETTES_SAVE_DEFI;
$lib_save_solution      = _LG_PLUGIN_ALLUMETTES_SAVE_SOLUTION;
$lib_restaure_defi      = _LG_PLUGIN_ALLUMETTES_RESTAURE_DEFI;
$lib_restaure_solution  = _LG_PLUGIN_ALLUMETTES_RESTAURE_SOLUTION;
$lib_add_al_mobile      = _LG_PLUGIN_ALLUMETTES_ADD_MOBILE;
$lib_add_al_fixe        = _LG_PLUGIN_ALLUMETTES_ADD_FIXE;
$lib_remove_off         = _LG_PLUGIN_ALLUMETTES_DELETE_OFF;
$lib_remove_on          = _LG_PLUGIN_ALLUMETTES_DELETE_ON;
$lib_reset_rotation     = _LG_PLUGIN_ALLUMETTES_RESET_ROTATION;

$lib_save_defi_ok       = _LG_PLUGIN_ALLUMETTES_SAVE_DEFI_OK;
$lib_save_solution_ok   = _LG_PLUGIN_ALLUMETTES_SAVE_SOLUTION_OK;
$lib_apply_change       = _LG_PLUGIN_ALLUMETTES_APPLY_CHANGE;
$lib_rotation           = _LG_PLUGIN_ALLUMETTES_ROTATION2;
$lib_memory             = _LG_PLUGIN_ALLUMETTES_MEMORY;

$lib_move               = _LG_PLUGIN_ALLUMETTES_MOVE;
$lib_left               = _LG_PLUGIN_ALLUMETTES_LEFT;
$lib_right              = _LG_PLUGIN_ALLUMETTES_RIGHT;
$lib_top                = _LG_PLUGIN_ALLUMETTES_TOP;
$lib_bottom             = _LG_PLUGIN_ALLUMETTES_BOTTOM;
$lib_solution           = _LG_PLUGIN_ALLUMETTES_SOLUTION;
$lib_solutions          = _LG_PLUGIN_ALLUMETTES_SOLUTIONS;
$lib_save               = _LG_PLUGIN_ALLUMETTES_SAVE;
$lib_restaure           = _LG_PLUGIN_ALLUMETTES_RESTAURE;
$lib_defi               = _LG_PLUGIN_ALLUMETTES_DEFI;
$lib_test_solutions     = _LG_PLUGIN_ALLUMETTES_TEST_SOLUTIONS;
$lib_Count_allumettes   = _LG_PLUGIN_ALLUMETTES_COUNT;
$lib_delete_solution    = _LG_PLUGIN_ALLUMETTES_DELETE;
$lib_hidde_al_fixe      = _LG_PLUGIN_ALLUMETTES_HIDDE_FIXE;
$lib_recaler            = _LG_PLUGIN_ALLUMETTES_RECALER_SUR_LA_GRILLE;

$title_plateau_width    = _LG_PLUGIN_ALLUMETTES_TITLE_PLATEAU_WIDTH; 
$title_plateau_height   = _LG_PLUGIN_ALLUMETTES_TITLE_PLATEAU_HEIGHT; 
$title_grid_size        = _LG_PLUGIN_ALLUMETTES_TITLE_GRID_SIZE; 
$title_allumettes_width = _LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_WIDTH; 
$title_allumettes_height= _LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_HEIGHT; 
$title_allumettes_rot   = _LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_ROT; 

/////////////////////////////////////////
$lib_mem1_undifined     = _LG_PLUGIN_ALLUMETTES_MEMORY1_UNDFINED;
$lib_defi               = _LG_PLUGIN_ALLUMETTES_DEFI;
$lib_solution           = _LG_PLUGIN_ALLUMETTES_SOLUTION;
$lib_recalage_ok        = _LG_PLUGIN_ALLUMETTES_RECALAGE_OK;
$lib_recalage_done      = _LG_PLUGIN_ALLUMETTES_RECALAGE_DONE;
$lib_confirm_delete     = _LG_PLUGIN_ALLUMETTES_CONFIRM_DELETE;
$lib_good_solution      = _LG_PLUGIN_ALLUMETTES_GOOD;
$lib_bad_solution       = _LG_PLUGIN_ALLUMETTES_BAD;


/* je vais plustot opter pour des bouton pour chaque memeoire en dur dans le hetm ci-dessous
$inpSolution = new \XoopsFormSelect(_LG_PLUGIN_ALLUMETTES_SOLUTION, 'inpSolution', 1);
for ($h = 0; $h < $options['maxPropositions']; $h++){
    $inpSolution->addOption($h, _LG_PLUGIN_ALLUMETTES_SOLUTION . ' ' . $h);
}
*/

//generation des boutons de solutions
$btnSolutions = '';
    for($h=1; $h <= $options['maxPropositions']; $h++){
        $btnSolutions .= 
$btn = <<<__btnSolutions__
        <div class="allumettes_button_group">
            {$h} : 
            <button type='button' class='btn_memoriser' onclick="memoriser($h)">{$lib_save}</button>
            <button type='button' class='btn_rappel'    onclick="restaurer($h)">{$lib_restaure}</button>
            <button type='button' class='btn_delete'    onclick="delete_solution($h)">{$lib_delete_solution}</button>
        </div>
__btnSolutions__;
    }
        
    
    
    
$tpl = <<<__inpDivImg__
<div class="allumettes_controls">
    Grille: <input type="number" id="g" value="30" style="width: 50px;" min='5' max='50' step='5' onchange='update_options(event, "gridSize");' title='{$title_grid_size} '>
    L: <input type="number" id="w" value="600" style="width: 50px;" min='200' max='600' step='10' onchange='update_options(event, "gameWidth");' title='{$title_plateau_width}'>
    H: <input type="number" id="h" value="300" style="width: 50px;" min='100' max='400' step='10' onchange='update_options(event, "gameHeight");' title='{$title_plateau_height}'>
    L.Allu: <input type="number" id="mw" value="20" style="width: 50px;" min='5' max='30' step='1' onchange='update_options(event, "allumetteWidth");' title='{$title_allumettes_width}'>
    H.Allu: <input type="number" id="mh" value="90" style="width: 50px;" min='20' max='120' step='1' onchange='update_options(event, "allumetteHeight");' title='{$title_allumettes_height}'>
    
    <!-- angle: <input type="number" id="rot" value="22.5" step="0.5" style="width: 50px;" min='0' max='600' step='10'>° -->
    
    
    {$lib_rotation} : <select id="rot" size="1" name="rot" id="quest_options[rotation]" title="{$title_allumettes_rot}" onchange='update_options(event, "rotation");'>
        for(var h=1; h<  ; h++)
        <option value="0"  {$optArr[0]}>0</option>
        <option value="24" {$optArr[1]}>15</option>
        <option value="16" {$optArr[2]}>22.5</option>
        <option value="12" {$optArr[3]}>30</option>
        <option value="8"  {$optArr[4]}>45</option>
        <option value="6"  {$optArr[4]}>60</option>
        <option value="4"  {$optArr[5]}>90</option>
    </select>
    <button type='button' onclick="appliquerConfig()">{$lib_apply_change}</button>
</div>

<div id="allumettes_main_container">
    <div><div id="allumettes_plateau" oncontextmenu="return false;" ></div><div  id='allumette_info'>x-y-r</div></div>
        <div class="allumettes_button_group"> 
    <div id="allumettes_side_panel">
        <strong>{$lib_Count_allumettes} : <span id="libMemoire">Memoire</span> : <span id="numMemoire">Memoire</span><br><span id="nbAlRouge">Allumettes rouges</span><br><span id="nbAlNoire">Allumettes noires</span>
        <div class="allumettes_button_group"> 
        <button type='button' id='btn-add-mobile' onclick="ajouterAllumette(10,10,0,1);" style="color:red;">{$lib_add_al_mobile}</button>
        <button type='button' id='btn-add-fixed' onclick="ajouterAllumette(10,10,0,0);">{$lib_add_al_fixe}</button>
        </div>
        <div class="allumettes_button_group"> 
        <button type='button'  id="btnDeleteMode" onclick="toggleDeleteMode('{$lib_remove_on}','{$lib_remove_off}')">{$lib_remove_off}</button>
        <button type='button'  onclick="resetRotations()">{$lib_reset_rotation}</button>
        </div>
 
        <div class="allumettes_button_group"> 
        <strong>{$lib_move} :
          <button type='button' onclick="move_all_allumettes('l');">{$lib_left}</button>
          <button type='button' onclick="move_all_allumettes('r');">{$lib_right}</button>
          <button type='button' onclick="move_all_allumettes('t');">{$lib_top}</button>
          <button type='button' onclick="move_all_allumettes('b');">{$lib_bottom}</button>
        </div>
       
        <hr style="width:100%">
        <strong>{$lib_defi} : <span id="allumettes_statusMessage" classe='memorisation'></span></strong>

        <div class="allumettes_button_group">
            0 : 
            <button type='button' class='btn_memoriser'  onclick="memoriser(0)">{$lib_save}</button>
            <button type='button' class='btn_rappel' onclick="restaurer(0)">{$lib_restaure}</button>
            <button type='button' class='btn_delete'     onclick="delete_solution(0)">{$lib_delete_solution}</button>
        </div>
        
        
        <!-- =================================== -->
        {$lib_solutions} :  
        <div class="allumettes_button_group">
          <label>
              <input type="checkbox" id="hiddeAlFixes" onchange="hidde_allumettes_fixed(event)"> 
                  {$lib_hidde_al_fixe}
          </label>
         </div>
        
        {$btnSolutions}
        <hr style="width:100%">
        
        <div class="allumettes_button_group"> 
        <button type='button'  class='btn_test_solution' onclick="test_solutions()">{$lib_test_solutions}</button>
        </div>
        <div class="allumettes_button_group">
          <button type='button' onclick="recaler_sur_la_grille();">{$lib_recaler}</button>
        </div>
        <!-- =================================== 
        <div class="allumettes_button_group">
            <div id=mouchard>mouchard</div>        
        </div>
        -->
        <!-- =================================== -->
<div style='display: none;'>
        <strong>Rapport (vs Mém. 1) :</strong>
        <div id="rapportContainer">Mém. 1 non définie</div>
        
        <hr style="width:100%">
        <strong>Import / Export :</strong>
        <div class="allumettes_button_group">
            <button type='button' onclick="exporterJSON()">Exporter</button>
            <button type='button' onclick="collerPressePapier()">Importer</button>
        </div>
        <textarea id="importArea" placeholder="JSON ici..." style="width: 100%; height: 50px;"></textarea>
    </div>
</div>
</div>
<script>
const lib_save_defi_ok      = '{$lib_save_defi_ok}';
const lib_save_solution_ok  = '{$lib_save_solution_ok}';
const lib_mem1_undifined    = '{$lib_mem1_undifined}';
const lib_defi              = '{$lib_defi}';
const lib_solution          = '{$lib_solution}';
const lib_recalage_done     = '{$lib_recalage_done}';
const lib_recalage_ok       = '{$lib_recalage_ok}';
const lib_confirm_delete    = '{$lib_confirm_delete}';
const lib_good_solution     = '{$lib_good_solution}';
const lib_bad_solution      = '{$lib_bad_solution}';



// ajouterAllumette( 0,  0,   0);
// ajouterAllumette(10, 10,   0);
// ajouterAllumette(20, 10,   0);
// ajouterAllumette(30, 20,  90);
// ajouterAllumette(40, 20, 270);
/*
    document.documentElement.style.setProperty('--plateau-w', document.getElementById('quest_options[gameWidth]').value + 'px');
    document.documentElement.style.setProperty('--plateau-h', document.getElementById('quest_options[gameHeight]').value + 'px');
    document.documentElement.style.setProperty('--grid-size', document.getElementById('quest_options[gridSize]').value + 'px'));
    rotationAngle = parseFloat(document.getElementById('quest_options[Rotation]').value.value);    
    document.documentElement.style.setProperty('--match-h', document.getElementById('quest_options[allumetteWidth]').value + 'px');
    document.documentElement.style.setProperty('--match-w', document.getElementById('quest_options[allumetteHeight]').value + 'px');
    
*/

    var maxMemory = {$options['maxPropositions']};
    
    document.getElementById('w').value   = {$options['gameWidth']};
    document.getElementById('h').value   = {$options['gameHeight']}; 
    document.getElementById('g').value   = {$options['gridSize']};
    document.getElementById('mw').value  = {$options['allumetteWidth']};
    document.getElementById('mh').value  = {$options['allumetteHeight']}; 
    document.getElementById('rot').value = {$options['rotation']};
appliquerConfig();    



/*
    document.getElementById('w').value = document.getElementById('quest_options[gameWidth]').value;
    document.getElementById('h').value = document.getElementById('quest_options[gameHeight]').value;
    document.getElementById('g').value = document.getElementById('quest_options[gridSize]').value;
    document.getElementById('mw').value = document.getElementById('quest_options[allumetteWidth]').value;
    document.getElementById('mh').value = document.getElementById('quest_options[allumetteHeight]').value;
    document.getElementById('rot').value = 360 / document.getElementById('quest_options[rotation]').value.replace('°','');
*/

restaurerFromPlugin();



</script>
__inpDivImg__;



    $inp = new XoopsFormLabel('', $tpl);
    return $inp;
 

        
        //<div id='resultat' class='resultat'></div>
}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler, $xoopsDB;
//echoArray($answers,'saveAnswers');exit;           
//echoArray('gp','saveAnswers');exit;        
        $pathImg = $quizHandler->getFolderJS($quizId, 1, 'images');  

        //$this->echoAns ($answers, $questId, $bExit = false);    
        //--------------------------------------------------------       
       foreach ($answers as $key=>$ans){
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_PLUGINS_INCLUDE . "/saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
            if($ans['proposition'] == '[]') $ans['proposition'] = '';
            if(!$ans['proposition']) continue;

            $ansObj->setVar('answer_proposition', $ans['proposition']);
            //$ansObj->setVar('answer_caption', $ans['caption']);
            $ansObj->setVar('answer_weight', $ans['weight']);
            $ansObj->setVar('answer_points', 0); 
//             $ansObj->setVar('answer_quest_id', $questId); 
//             $ansObj->setVar('answer_group', $ans['group']); 
            
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
		$ret['touches']      = $this->getVar('answer_touches');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['group']      = $this->getVar('answer_group');
  
  */
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $html = array();
 
    //-------------------------------------------
    // commenÃ§ons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_weight,answer_id');
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <hr><pre>" . print_r($quizId,true) . "</pre><hr>");
    //recherche du dossier upload du quiz
    $urlImg = $quizHandler->getFolderJS($quizId, 2, 'images');
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_touches DESC,answer_weight,answer_id');
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
        $touches = intval($ans['touches']);
        $tokenImg = sprintf(QUIZMAKER_TPL_IMG2, $urlImg, $ans['image1'], $ans['image1'], $ans['color']);
        if ($touches > 0) {
            $scoreMax += $touches;
            $color = QUIZMAKER_TOUCHES_POSITIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif ($touches < 0) {
            $scoreMin += $touches;
            $color = QUIZMAKER_TOUCHES_NEGATIF;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_TOUCHES_NULL;
            $html[] = sprintf($tpl, $tokenImg, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la class

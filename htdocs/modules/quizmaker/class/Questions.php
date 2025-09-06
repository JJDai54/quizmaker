<?php

namespace XoopsModules\Quizmaker;

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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Questions
 */
class Questions extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('quest_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_parent_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_flag', XOBJ_DTYPE_INT);
		$this->initVar('quest_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_plugin', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_question', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_identifiant', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_options', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_comment1', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_explanation', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_consigne', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_learn_more', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_see_also', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_posComment1', XOBJ_DTYPE_INT);
		$this->initVar('quest_image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_zoom', XOBJ_DTYPE_INT);
		$this->initVar('quest_background', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_height', XOBJ_DTYPE_INT);
		$this->initVar('quest_points', XOBJ_DTYPE_INT);
		$this->initVar('quest_numbering', XOBJ_DTYPE_INT);
		$this->initVar('quest_shuffleAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quest_weight', XOBJ_DTYPE_INT);
		$this->initVar('quest_timer', XOBJ_DTYPE_INT);
		$this->initVar('quest_start_timer', XOBJ_DTYPE_INT);
		$this->initVar('quest_isQuestion', XOBJ_DTYPE_INT);
		$this->initVar('quest_visible', XOBJ_DTYPE_INT);		
        $this->initVar('quest_actif', XOBJ_DTYPE_INT);
		$this->initVar('quest_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quest_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
	}

	/**
	 * @static function &getInstance
	 *
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
	}

	/**
	 * The new inserted $Id
	 * @return inserted id
	 */
	public function getNewInsertedIdQuestions()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

    /**
     * create a clone(copy) of the current object
     *
     * @access public
     * @return object clone
     */
    public function cloneQuestion()
    {
        global $questionsHandler;
        
        $class = get_class($this);
        $clone = null;
        $clone = new $class();
        foreach ($this->vars as $k => $v) {
            $clone->setVar($k, $v['value']);
        }
        $clone->setVar('quest_id', 0);
        $clone->setVar('quest_question', $clone->getVar('quest_question') . " - (clone [#{$this->getVar('quest_id')}])");
        $clone->setVar('quest_weight', $clone->getVar('quest_weight')+2);
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();

        return $clone;
    }




/* *************************************
insert un lineBreak avec des liens sur les différenes partie du formulaire
**************************************** */
function insertShorcuts(&$form, $caption, $color, $backcolor){
$shortcut = [_AM_QUIZMAKER_HEADER,
             _AM_QUIZMAKER_PARAMETRES,
             _AM_QUIZMAKER_PLUGIN_CONSIGNE,
             _AM_QUIZMAKER_PLUGIN_OPTIONS,
             _AM_QUIZMAKER_PLUGIN_OPTIONS_PLUGIN,
             _AM_QUIZMAKER_PROPOSITIONS_ANSWERS,
             _AM_QUIZMAKER_SUBMIT];
  
    $tpl = "<a href='#%s' onclick='quizmaker_scrollWin(-80);'  style='color:{$color};'>[%s]</a>";
    $html[] = "<div >";
  
   
    $htmlShortcut = [];
    for($h = 0; $h < count($shortcut); $h++){
        //ça marche aussiavec les espace, mais c'est plus propre de le enever
        $name = str_replace(" ", "-", $shortcut[$h]);
        //$name =  $shortcut[$h];
        
        
        if($caption == $shortcut[$h]){
            $html[] = "<b>{$caption}</b> ===> ";
            $html[] = "<a href='' name='{$name}'></a>";
        }else{
            $htmlShortcut[] = sprintf($tpl, $name, $shortcut[$h]);
        }
    }
 
    
    $html[] = implode(' - ', $htmlShortcut);

    $html[] = "</div>";
    $innerHtml =  implode("", $html);  

   $form->insertBreak($innerHtml, 'quizmaker_linebreak_' . $backcolor);
      
}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
 	public function getFormQuestions($action = false, $sender="")
 	{
        global $quizHandler, $utility, $quizUtility, $pluginsHandler, $xoTheme;
        
		// Permissions for uploader
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		xoops_load('XoopsFormLoader');

        //===========================================================        
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
        // recupe de la classe du type de question
        $clPlugin = $this->getPlugin($pluginName);
		$questionsHandler = $quizmakerHelper->getHandler('Questions'); // Questions Handler
        //=================================================
        
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}else{
            $h = strpos( $_SERVER['REQUEST_URI'], "?");
			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
        }
        //---------------------------------------------- 
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_QUESTIONS_ADD) : sprintf(_AM_QUIZMAKER_QUESTIONS_EDIT);
		// Get Theme Form
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        
        $form->addElement(new \XoopsFormHidden('sender', $sender));
\JANUS\include_highslide(null,"quizmaker");     
$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');

       //----------------------------------------------------------
        $this->insertShorcuts($form, _AM_QUIZMAKER_HEADER, 'blue', 'yellow');        
		// Form Select questQuiz_id
		$inpQuizId = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
		$inpQuizId->addOption('Empty');
		$inpQuizId->addOptionArray($quizHandler->getListKeyName());
        $saisissable = false;
        if (!$saisissable){ //autorise la selection de quiz_id
            $inpQuizId->setExtra("disabled");
            $form->addElement(new \XoopsFormHidden('quest_quiz_id', $this->getVar('quest_quiz_id')));
        }        
		$form->addElement($inpQuizId);

       //----------------------------------------------------------
        $trayPlugin = new \XoopsFormElementTray  ("", $delimeter = '<br>');  //_AM_QUIZMAKER_QUESTIONS_PLUGIN

        
        if ($clPlugin->isQuestion){
            // Form Select questPlugin
            $inpPlugin = new \XoopsFormSelect( '', 'quest_plugin', $pluginName);
            $inpPlugin->addOption('Empty');
            $inpPlugin->addOptionArray($pluginsHandler->getListKeyName(null, true));
            $inpPlugin->setExtra("onchange='reloadPluginSnapshoots(\"modelesPluginId\");'");
            $trayPlugin->addElement($inpPlugin);
            $trayPlugin->addElement(new \XoopsFormLabel('', _CO_QUIZMAKER_PLUGIN_DESC));
          
        }else{
            $form->addElement(new \XoopsFormHidden('quest_plugin', $pluginName));
            $inpPlugin = new \XoopsFormLabel('', $pluginName);
            $trayPlugin->addElement($inpPlugin);
        }		
        
        //----------------------------------------------------------
        $imgModelesHtml = new \XoopsFormLabel('', $clPlugin->getHtmlImgModeles());  
        //$imgModelesHtml->setExtra("class='highslide-gallery'");
        $trayPlugin->addElement($imgModelesHtml);
		$form->addElement($trayPlugin);
        //----------------------------------------------------------
		// Form Select quest_parent_id         
        if($clPlugin->isQuestion()){         
            $tParent = $questionsHandler->getParents($this->getVar('quest_quiz_id'), true);         
            $parentId = ($this->getVar('quest_parent_id') == 0) ? array_keys($tParent)[0] : $this->getVar('quest_parent_id');
            $inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_PARENT, 'quest_parent_id', $parentId);
            $inpParent->addOptionArray($tParent);
            $inpWeight = new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));

        }elseif($clPlugin->pluginName == 'pageGroup'){
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));
        }
        else{
            //c'est la page de debut ou de fin on affiche pas le poids et pas de parent;
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormHidden('quest_weight', $this->getVar('quest_weight'));        
        }   
        $inpParent->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
        $form->addElement($inpParent);

        $questId = $this->getVar('quest_id');
        //----------------------------------------------------------
        $this->insertShorcuts($form, _AM_QUIZMAKER_PARAMETRES, 'yellow', 'black');        
        //-------------------------------------------
        //--- ajout de racourcis pour acceder directement aux element importants
        /*
        $tpl = "<a href='#%s' onclick='quizmaker_scrollWin(-80);'><input type='button' value='%s'></a>";
        $raccourcis = new \XoopsFormElementTray('Acces rapide', "&nbsp; ");
        
        $raccourcis->addElement(new \XoopsFormLabel('', sprintf($tpl, 'options-specifique', 'Option spécéfique')));        
        $raccourcis->addElement(new \XoopsFormLabel('', sprintf($tpl, 'options-slide', 'Options de présentation')));        
        $raccourcis->addElement(new \XoopsFormLabel('', sprintf($tpl, 'propositions', 'Proposirions')));        
        $raccourcis->addElement(new \XoopsFormLabel('', sprintf($tpl, 'submit-form', 'Boutons de validation')));        
        $form->addElement($raccourcis);        
        */
        
        

		// Form Text questQuestion
        $inpQuestion = new \XoopsFormText(_AM_QUIZMAKER_QUESTIONS_QUESTION . " [#{$questId}]", 'quest_question', 120, 255, $this->getVar('quest_question') );
		$inpQuestion->setDescription(_AM_QUIZMAKER_QUESTIONS_QUESTION_DESC);
        $inpQuestion->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUEST));
        $form->addElement($inpQuestion, true);
        
		
		// Form Editor DhtmlTextArea questComment1
        $inpComment1  = $quizUtility->getEditor2(_AM_QUIZMAKER_QUESTIONS_COMMENT1, 'quest_comment1', $this->getVar('quest_comment1', 'e'), _AM_QUIZMAKER_QUESTIONS_COMMENT1_DESC  , null, $quizmakerHelper);        
		$form->addElement($inpComment1);
        
		// Form Text quest_identifiant
        //$this->setVar('quest_identifiant', $this->getVar('quest_question') ); 
        if (!$this->getVar('quest_identifiant')) $this->setVar('quest_identifiant', 'slide_' . rand(10000,99999));        
        $inpIdentifiant = new \XoopsFormText(_AM_QUIZMAKER_QUESTIONS_IDENTIFIANT, 'quest_identifiant', 120, 255, $this->getVar('quest_identifiant') );
        $inpIdentifiant->setDescription(_AM_QUIZMAKER_QUESTIONS_IDENTIFIANT_DESC);
		$form->addElement($inpIdentifiant, false);
		
        // Form Text questWeight
		$form->addElement($inpWeight);

		// Form Editor DhtmlTextArea quest_explanation
        $inpExplanation  = $quizUtility->getEditor2(_AM_QUIZMAKER_EXPLANATION, 'quest_explanation', $this->getVar('quest_explanation', 'e'), _AM_QUIZMAKER_EXPLANATION_DESC, null, $quizmakerHelper);        
		$form->addElement($inpExplanation);
        
		// Form Text learn_more
		$inpLearnMore = new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_LEARN_MORE, 'quest_learn_more', 120, 255, $this->getVar('quest_learn_more') );
        $inpLearnMore->setDescription(_AM_QUIZMAKER_QUESTIONS_LEARN_MORE_DESC);
		$form->addElement($inpLearnMore);
		// Form Text see_also
		$inpSeeAlso = new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_SEE_ALSO, 'quest_see_also', 120, 255, $this->getVar('quest_see_also') );
        $inpSeeAlso->setDescription(_AM_QUIZMAKER_QUESTIONS_SEE_ALSO_DESC);
		$form->addElement($inpSeeAlso);
        
        /* ***** Options uniquement pour les questions ***** */
        // Form quest_posComment1
/* a vooir dans une prochaine si cette options est déporté de quiz vers question pour affiner la présentation individuellement
*/		
        $inpPosComment = new \XoopsFormRadio(_AM_QUIZMAKER_POS_COMMENT, 'quest_posComment1', $this->getVar('quest_posComment1'));
        $inpPosComment->addOptionArray(['0'=>_AM_QUIZMAKER_POS_COMMENT_0, '1'=>_AM_QUIZMAKER_POS_COMMENT_1 , '2'=>_AM_QUIZMAKER_POS_COMMENT_2, '3'=>_AM_QUIZMAKER_POS_COMMENT_3]);
        $inpPosComment->setDescription(_AM_QUIZMAKER_POS_COMMENT_DESC);
        $form->addElement($inpPosComment);
        
        if($clPlugin->isQuestion() && $clPlugin->numbering == -1){
            // Form Text questNumbering
            $tOptNumbering = array(_CO_QUIZMAKER_NUM_NONE, _AM_QUIZMAKER_NUM_NUMERIQUE, _AM_QUIZMAKER_NUM_UPPERCASE, _AM_QUIZMAKER_NUM_LOWERCASE, _AM_QUIZMAKER_NUM_NUM_ACCOLADES);
            $inpNumbering = new \XoopsFormSelect(_AM_QUIZMAKER_NUMBERING , 'quest_numbering', $this->getVar('quest_numbering'));
            $inpNumbering->addOptionArray($tOptNumbering);
        }else{
            $inpNumbering = new \XoopsFormHidden('quest_numbering', $clPlugin->numbering);        
        }
        $form->addElement($inpNumbering);
        
        //-------------------------------------------------------
        $trayTimer = new \XoopsFormElementTray(_AM_QUIZMAKER_TIMER, '<br>');
        
        // Form Text Select questTimer
        $inpTimer = new \XoopsFormNumber('', 'quest_timer', 8, 8, $this->getVar('quest_timer'));
        $inpTimer->setMinMax(0, QUIZMAKER_TIMER_MAX, _AM_QUIZMAKER_UNIT_SECONDS);
        $inpTimer->setExtra(getStyle(QUIZMAKER_BG_LIST_TIMER));
		$trayTimer->addElement($inpTimer);

        $trayTimer->addElement(new \XoopsFormLabel('',_AM_QUIZMAKER_TIMER_DESC));
        
        // Form quest_start_timer
		$inpStartTimer = new \XoopsFormRadioYN(QBR . _AM_QUIZMAKER_START_TIMER, 'quest_start_timer', $this->getVar('quest_start_timer'));
        $inpStartTimer->setDescription(_AM_QUIZMAKER_START_TIMER_DESC);
        $trayTimer->addElement($inpStartTimer);
        
        $trayTimer->addElement(new \XoopsFormLabel('',_AM_QUIZMAKER_START_TIMER_DESC));
        
		$form->addElement($trayTimer);
        //-------------------------------------------------------
        

		// Form Editor DhtmlTextArea quest_consigne
        $inpConsigne  = $quizUtility->getEditor2(_AM_QUIZMAKER_QUESTIONS_CONSIGNE, 'quest_consigne', $this->getVar('quest_consigne', 'e'), _AM_QUIZMAKER_QUESTIONS_CONSIGNE_DESC, null, $quizmakerHelper);        
		$form->addElement($inpConsigne);
		//$form->addElement($fileNameTray);
        
        // Form quest_visible

		$inpVisible = new \XoopsFormRadioYN(_AM_QUIZMAKER_VISIBLE, 'quest_visible', $this->getVar('quest_visible'));
        $inpVisible->setDescription(_AM_QUIZMAKER_VISIBLE_DESC);
        $form->addElement($inpVisible);
        
        // Form quest_actif
		$inpActif = new \XoopsFormRadioYN(_AM_QUIZMAKER_ACTIF, 'quest_actif', $this->getVar('quest_actif'));
        $inpActif->setDescription(_AM_QUIZMAKER_ACTIF_DESC);
        $form->addElement($inpActif);
        
        // ===================================================================
        // cette partie insert l'aide, les options et les poropositions propres au type de question, mais pas que (image)
        // ===================================================================
        if ($quizmakerHelper->getConfig('display_plugin_help')){
        }
          //ajout de l'aide pour ce slide
        $this->insertShorcuts($form, _AM_QUIZMAKER_PLUGIN_CONSIGNE, 'white', 'magenta');        
          
          $form->addElement($clPlugin->getSlideHelper($quizmakerHelper->getConfig('display_plugin_help')));


        //====================================================================
        
        //options globales pour les propositions (height, btnColor, ...)
        $quiz = $quizHandler->get($this->getVar('quest_quiz_id'));
        $folderJS = $quiz->getVar('quiz_folderJS');
        //$idQuiz = $this->getVar('quest_quiz_id');
        //echo "<hr>dossier du quiz : {$idQuiz}-{$folderJS}<hr>";        
        //--------------------------------------------------------------  
        $this->insertShorcuts($form, _AM_QUIZMAKER_PLUGIN_OPTIONS, 'white', 'blue');        
        
        //--------------------------------------------------------------  
        $form->addElement(new \XoopsformLabel("<a href='' name='options-slide'><a>"));
        if($clPlugin->isQuestion()){
            // Form Text quest_points
            // ce champ fait partie de la table question mais il est plus ergonomique de le metre ici
            $inpPoints =   new \XoopsFormNumber('', 'quest_points', 8, 8, $this->getVar('quest_points'));
            $inpPoints->setMinMax(0, 50, _AM_QUIZMAKER_UNIT_POINTS);
            //$inpPoints->setExtra(getStyle(QUIZMAKER_BG_LIST_TIMER));
            $inpPoints->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_POINTS));            
            
            if ($clPlugin->multiPoints){
              $form->addElement($this->TrayMergeFormWithDesc(_AM_QUIZMAKER_QUESTIONS_POINTS, $inpPoints, _AM_QUIZMAKER_QUESTIONS_POINTS_DESC));
            }else{
              $form->addElement($this->TrayMergeFormWithDesc(_AM_QUIZMAKER_QUESTIONS_POINTS, $inpPoints));
            }
        }else{
            //c'est un slide sans question (pageGroup, pageInfo, ...)
            $form->addElement(new \XoopsFormHidden('quest_points', 0));
        }
        
        // Form quest_shuffleAnswers
        $name = 'quest_shuffleAnswers';
        if($clPlugin->hasShuffleAnswers){
            $inpShuffleAnswers = new \XoopsFormRadioYN(_AM_QUIZMAKER_SHUFFLE_ANSWERS, $name, $this->getVar($name));
            $inpShuffleAnswers->setDescription(_AM_QUIZMAKER_SHUFFLE_ANSWERS_DESC);
        }else{
            $inpShuffleAnswers = new \XoopsFormHidden(_AM_QUIZMAKER_SHUFFLE_ANSWERS, 0);
        }
        $form->addElement($inpShuffleAnswers);
          //--------------------------------------------------------------  
        if ($clPlugin){
            $options =  html_entity_decode($this->getVar('quest_options'));
            
            //if($inpOptions || $clPlugin->hasImageMain) 
            //--------------------------------------------
            //zoom
            if($clPlugin->hasZoom){
                $name = 'quest_zoom';  
                $inputZoom = new \XoopsFormRadio(_AM_QUIZMAKER_ZOOM, $name, $this->getVar($name), ' ');
                $inputZoom->setDescription(_AM_QUIZMAKER_ZOOM_DESC);            
                $inputZoom->addOption(0, _AM_QUIZMAKER_ZOOM_NONE);            
                $inputZoom->addOption(1, _AM_QUIZMAKER_ZOOM_MANUEL);            
                $inputZoom->addOption(2, _AM_QUIZMAKER_ZOOM_AUTO);            
                $form->addElement($inputZoom);     
            }
            //--------------------------------------------
            // Image
            if($clPlugin->hasImageMain){
              $image = $this->getVar('quest_image');
              $inpImage = $clPlugin->getFormImage(_AM_QUIZMAKER_IMAGE_MAIN, 'quest_image', $image, $folderJS);
                  $inpImage->setCaption('');
                  //$form->addElement($inpImage, false);

                  $name = 'imgHeight';  
                  $height = ( $this->getVar('quest_height')) ?  $this->getVar('quest_height') : 32;
                  $inpHeight1 = new \XoopsFormNumber('',  "quest_height", 5, 3, $height);
                  $inpHeight1->setMinMax(32, 500, _AM_QUIZMAKER_UNIT_PIXELS);
                  //$form->addElement($inpHeight1);     

                  $inpTrayImg = new \XoopsFormElementTray(_AM_QUIZMAKER_IMAGE_MAIN, "-> " . _AM_QUIZMAKER_IMG_HEIGHT1 . " : ");
                  $inpTrayImg->addElement($inpImage);
                  $inpTrayImg->addElement($inpHeight1);
                  $form->addElement($inpTrayImg);     

              }
            
            //--------------------------------------------
            $background = $this->getVar('quest_background');
            $inpBakground = $clPlugin->getFormImage(_AM_QUIZMAKER_BACKGROUND_MAIN, 'quest_background', $background, $folderJS);
            $inpBakground->setCaption(_AM_QUIZMAKER_BACKGROUND_MAIN);
            $form->addElement($inpBakground);     

            // --------- ajout des options propres au plugin -------------------
            $form->addElement(new \XoopsformLabel("<a href='' name='options-specifique'><a>"));
            $this->insertShorcuts($form, _AM_QUIZMAKER_PLUGIN_OPTIONS_PLUGIN, 'white', 'red');        
            
            $inpOptions = $clPlugin->getFormOptions(_AM_QUIZMAKER_SPECIFIC_OPTIONS, QUIZMAKER_PREFIX_OPTIONS_NAME,  $options, $folderJS);
            if($inpOptions){
                $form->addElement($inpOptions, false);
            }
            
        } 
       
        //================================================
        //ajout des propositions de réponses
        //$titleOptions = new \XoopsFormLabel(null,'Liste des options');
        $form->addElement(new \XoopsformLabel("<a href='' name='propositions'><a>"));
        $this->insertShorcuts($form, _AM_QUIZMAKER_PROPOSITIONS_ANSWERS, 'yellow', 'green');        
       
        if ($clPlugin->integration == 1){
            $inpProposition = $clPlugin->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id'));
            $form->insertBreak($inpProposition->render());

        }else{
            $form->addElement($clPlugin->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id')));
        }
        
        //================================================
		// To Save
        $this->insertShorcuts($form, _AM_QUIZMAKER_SUBMIT, 'black', 'cyan');        
        
		$form->addElement(new \XoopsFormHidden('op', 'save'));
        
        $btnTray = new \XoopsFormElementTray  ('', '&nbsp;');
        
        //remplacé par le bouton annuler qui appel la page php et non un retour arrière
        //$btnTray->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        
        
        $btnSubmit = new \XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $btnSubmit->setClass('btn btn-success');
        $btnTray->addElement($btnSubmit);
        
        $btnReload = new \XoopsFormButton('', 'submit_and_reload', _AM_QUIZMAKER_SUBMIT_AND_RELOAD, 'submit');
        $btnReload->setClass('btn btn-success');
        $btnTray->addElement($btnReload);
        
        $btnAddNew = new \XoopsFormButton('', 'submit_and_addnew', _AM_QUIZMAKER_SUBMIT_AND_ADDNEW,'submit');
        $btnAddNew->setClass('btn btn-success');
        $btnTray->addElement($btnAddNew);
        
        //$link="<a href='questions.php?op=list&" . getParams2list($quizId, $quest_plugin, "", $quest_parent_id) . "'>" . _CANCEL .  "</a>;";
        $btnSubmit = new \XoopsFormButton('', 'reset', 'Réinitialiser', 'reset');
        $btnSubmit->setClass('btn btn-success');
        $btnTray->addElement($btnSubmit);
        
        $btnSubmit = new \XoopsFormButton('', 'cancel', _CANCEL, 'submit');
        //$btnSubmit->setClass('btn btn-cancel');
        $btnTray->addElement($btnSubmit);
        
        $form->insertBreak("<center>" . $btnTray->render() . "</center>",'blue');        
		//$form->addElement($btnTray);
		return $form;
	}

     
	/**
	 * TrayMergeFormWithDesc : assemble un form avec une description pour l'avoir dessous et non dans la colonne de titre
	 * @return form
	 */
function TrayMergeFormWithDesc($caption, $form, $desc='', $sep="<br>"){
    $tray = new \XoopsFormElementTray($caption, $sep);
    $tray->addelement($form);
    if($desc) $tray->addelement(new \XoopsFormLabel("",$desc));
    return $tray;
    
}
     
	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesQuestions($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility;
        $clPlugin = $this->getPlugin($pluginName);
        
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']             = $this->getVar('quest_id');
		$ret['parent_id']      = $this->getVar('quest_parent_id');
		$ret['quiz_id']        = $this->getVar('quest_quiz_id');
		$ret['plugin']  = $pluginName; //$this->getVar('quest_plugin');
		$ret['question']       = $this->getVar('quest_question');
		$ret['identifiant']    = $this->getVar('quest_identifiant');
		$editorMaxchar = $quizmakerHelper->getConfig('editor_maxchar');
        
        //getVar genere une transformation facheuse 
		$ret['options']        = html_entity_decode($this->getVar('quest_options')) ;
        //pour palier aux transfert des options spécifiques sur des quiz plus anciens,
        //on recupère les options par éfauts en attenaant de modifier et valider de nouveau la question
        if(!$ret['options']) $ret['options'] = json_encode($clPlugin->optionsDefaults);
      
		$ret['comment1']       = $this->getVar('quest_comment1', 'e');
		$ret['pos_comment1']   = $this->getVar('quest_posComment1');
		$ret['comment1_short'] = $utility::truncateHtml($ret['comment1'], $editorMaxchar);
 		$ret['explanation']    = $this->getVar('quest_explanation', 'e');
 		$ret['explanation_short'] = $utility::truncateHtml($ret['explanation'], $editorMaxchar);
 		$ret['consigne']       = $this->getVar('quest_consigne', 'e');
 		$ret['learn_more']     = $this->getVar('quest_learn_more', 'e');
 		$ret['see_also']       = $this->getVar('quest_see_also', 'e');
 		$ret['image']          = $this->getVar('quest_image', 'e');
 		$ret['zoom']           = $this->getVar('quest_zoom');
 		$ret['background']     = $this->getVar('quest_background', 'e');
 		$ret['height']         = $this->getVar('quest_height');
		$ret['points']         = $this->getVar('quest_points');
		$ret['numbering']      = $this->getVar('quest_numbering');
		$ret['shuffleAnswers'] = $this->getVar('quest_shuffleAnswers');
		$ret['creation']       = \JANUS\getDateSql2Str($this->getVar('quest_creation'));
		$ret['update']         = \JANUS\getDateSql2Str($this->getVar('quest_update'));
        
		$ret['weight']         = $this->getVar('quest_weight');
		$ret['timer']          = $this->getVar('quest_timer');
		$ret['start_timer']    = $this->getVar('quest_start_timer');
		$ret['visible']        = $this->getVar('quest_visible');
		$ret['actif']          = $this->getVar('quest_actif');
		$ret['flags']          = $this->getFlags($ret);
        
		$ret['isQuestion']        = $clPlugin->isQuestion;
        if($clPlugin){
    		$ret['isParent']       = $clPlugin->isParent;
    		$ret['isQuestion']     = $clPlugin->isQuestion;
    		$ret['canDelete']      = $clPlugin->canDelete;
    		$ret['typeForm']       = $clPlugin->typeForm;
		    $ret['typeForm_lib']  = $clPlugin->typeForm_lib;
        }else{
    		$ret['isParent']       = false;
    		$ret['isQuestion']     = false;
    		$ret['canDelete']      = false;
    		$ret['typeForm']       = false;
		    $ret['typeForm_lib']  = '???';
        }
        
        
		return $ret;

	}

    public function getFlags(&$ret){
        $flags = array();
        $flags['actif'] = quizFlagAscii($ret['actif'], "A");
        //$flags['visible'] = quizFlagAscii($ret['visible'], "V");
        $flags['shuffleAnswers'] = quizFlagAscii($ret['shuffleAnswers'], "M");
        
        $flags['numbering'] = quizFlagAlpha($ret['numbering'], _CO_QUIZMAKER_NUM_NONE . "|123|ABC|abc|{123}","red|green|blue|blue");
                                           
        return $flags;
                                      
    }

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayQuestions()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
/* ******************************
 * Change l'etat du champ passer en parametre
 * @$quizId : id du quiz
 * @$field : nom du champ à changer
 * *********************** */
    public function changeEtat($questId)
    {
        $sql = "UPDATE " . $this->table . " SET {$field} = not {$field} WHERE quest_id={$questId};";
        $ret = $this->db->queryf($sql);
        return $ret;
    }

/* ******************************
 *  getPlugin : renvoie la class du type de question
 * @return : classe héritée du type de question
 * *********************** */
    public function getPlugin(&$pluginName = null)
    {
    //echo "<hr>{$default}<hr>";
        global $quizUtility, $pluginsHandler;
        // recupe de la classe du type de question
        $pluginName = $this->getVar('quest_plugin');
        //if ($pluginName == '') $pluginName = $default;
        return $pluginsHandler->getPlugin($pluginName);
    }
        
/* ********************************************
*
*********************************************** */
  public function getSolutions($boolAllSolutions = true){
  //global $answersHandler;
    $tclTpeQuestion = $this->getPlugin($pluginName);
    if (is_null($tclTpeQuestion)) return "Problemo";

    //return $tclTpeQuestion->getSolutions($this->getVar('quest_id'), $this);
    return $tclTpeQuestion->getSolutions($this->getVar('quest_id'), $boolAllSolutions, $this);

     }
    
/* ********************************************
* todo
*********************************************** */
  public function moveTo($newQuizId){
  global $quizHandler;
    $quizFrom = $quizHandler->get($this->getVar('quest_quiz_id'));
    $fldFrom = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $quizFrom->getVar('quiz_folderJS');
    
    $quizTo = $quizHandler->get($newQuizId);
    $fldTo = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' .  $quizTo->getVar('quiz_folderJS');
    
    echo "<hr>newQuizId : {$newQuizId}<br>From : <br>{$fldFrom}<br>to : <br>{$fldTo}<hr>";
exit('move');
   }
 
 
}//------------------- FIN DE LA CLASSE ---------------------------------




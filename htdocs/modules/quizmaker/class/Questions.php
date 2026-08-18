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
		$this->initVar('quest_reference_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_flag', XOBJ_DTYPE_INT);
		$this->initVar('quest_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_plugin', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_question', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_question_style', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_identifiant1', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_identifiant2', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_options', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_comment1', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_explanation', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_consigne', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_learn_more', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_see_also', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_posComment1', XOBJ_DTYPE_INT);
		$this->initVar('quest_image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_height', XOBJ_DTYPE_INT);
		$this->initVar('quest_shadow', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_image_style', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_zoom', XOBJ_DTYPE_INT);
		$this->initVar('quest_background', XOBJ_DTYPE_TXTBOX);
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
        $clone->setVar('quest_identifiant1', FQUIZMAKER\getNewIdentifiant());
        $clone->setVar('quest_weight', $clone->getVar('quest_weight')+2);
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();

        return $clone;
    }

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
 	public function getFormQuestions($action = false, $sender="")
 	{
        global $quizmakerHelper, $quizHandler, $utility, $quizUtility, $pluginsHandler, $xoTheme;
		// Permissions for uploader
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		//$grouppermHandler = xoops_getHandler('groupperm');
		//$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		//$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		xoops_load('XoopsFormLoader');

        $questId = $this->getVar('quest_id');
        $quizId =  $this->getVar('quest_quiz_id');
        
        //===========================================================        
		//$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
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
		$form = new \XoopsFormJanus($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        
        $form->addHidden('sender', $sender);
\JANUS\include_highslide(null,"quizmaker");     
$xoTheme->addScript(QUIZMAKER_URL_MODULE . '/assets/js/admin.js');

       //----------------------------------------------------------
        $shortcut = [_AM_QUIZMAKER_HEADER,
                     _AM_QUIZMAKER_PARAMETRES,
                     _AM_QUIZMAKER_PLUGIN_CONSIGNE,
                     _AM_QUIZMAKER_PLUGIN_OPTIONS,
                     _AM_QUIZMAKER_PLUGIN_OPTIONS_PLUGIN,
                     _AM_QUIZMAKER_PROPOSITIONS_ANSWERS,
                     _AM_QUIZMAKER_SUBMIT];
       
        $form->insertShorcuts(_AM_QUIZMAKER_HEADER, $shortcut, 'yellow');        
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
        $trayPlugin = new \XoopsFormElementTray  (_AM_QUIZMAKER_PLUGIN, $delimeter = '<br>');  //_AM_QUIZMAKER_QUESTIONS_PLUGIN

        
        if ($clPlugin->isQuestion || $clPlugin->typeForm == QUIZMAKER_TYPE_FORM_INFO){
        //exit("pluginName-> = {$clPlugin->pluginName} - ->isQuestion = {$clPlugin->isQuestion} - ->typeForm = {$clPlugin->typeForm} - ->typeForm_lib = {$clPlugin->typeForm_lib}");
            // Form Select questPlugin
            $inpPlugin = new \XoopsFormSelect( '', 'quest_plugin', $pluginName);
            //$inpPlugin->addOption('Empty');
            //echoArray($pluginsHandler->getListKeyName(null, true));exit;
            $inpPlugin->addOptionArray($pluginsHandler->getListKeyName(null, true));
            $inpPlugin->setExtra("onchange='reloadPluginSnapshoots(\"modelesPluginId\");' " . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_PLUGIN));
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
        if($clPlugin->isQuestion || $clPlugin->pluginName == 'pageInfo'){         
            $tParent = $questionsHandler->getParents($this->getVar('quest_quiz_id'), 'pageGroup', true);         
            $parentId = ($this->getVar('quest_parent_id') == 0) ? array_keys($tParent)[0] : $this->getVar('quest_parent_id');
            $inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_GROUP, 'quest_parent_id', $parentId);
            $inpParent->addOptionArray($tParent);
            $inpWeight = new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));

        }elseif($clPlugin->pluginName == 'pageAnswer'){         
            $criteria = new \CriteriaCompo(new \Criteria('quest_isQuestion', 1));
            $tParent = $questionsHandler->getListKeyName($quizId, $criteria, 'quest_identifiant1', 'quest_question', false);  
                 
            $inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTION, 'quest_identifiant2', $this->getVar('quest_identifiant2'));
            $inpParent->addOptionArray($tParent);
            $inpWeight = new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));
            
        }elseif($clPlugin->pluginName == 'pageGroup'){
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));
        }else{
            //c'est la page de debut ou de fin on affiche pas le poids et pas de parent;
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormHidden('quest_weight', $this->getVar('quest_weight'));        
        }   
        $inpParent->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
        $form->addElement($inpParent);

//test coches
/*
        $inpCoche = new \XoopsFormCheckBoxImage('togodo', 'togodo', [0],'<br>');    
        $inpCoche->switchImage(false);                    
        $inpCoche->showCaption(true);                    
        $inpCoche->addOption(0, _AM_QUIZMAKER_DELETE);
        $inpCoche->addOption(1, _AM_QUIZMAKER_DELETE);
        $inpCoche->setImgArr(2,1);
        $this->_imgArr = array($valueFalse, $valueTrue);
		$form->addElement($inpCoche);
*/



        // Form Text questWeight
		$form->addElement($inpWeight);
        
		// Form Text quest_identifiant1
        if (!$this->getVar('quest_identifiant1')) $this->setVar('quest_identifiant1', 'slide_' . rand(10000,99999));
        $inpIdentifiant = new \XoopsEditList(_AM_QUIZMAKER_QUESTIONS_IDENTIFIANT, 'quest_identifiant1', $this->getVar('quest_identifiant1'), 20) ; 
        $inpIdentifiant->setDescription(_AM_QUIZMAKER_QUESTIONS_IDENTIFIANT_DESC);      
		$form->addXtrayElement($inpIdentifiant, false);
        //----------------------------------------------------------
        $form->insertShorcuts(_AM_QUIZMAKER_PARAMETRES, null, 'black');        
        //-------------------------------------------

		// Form Text questQuestion
        $inpQuestion = new \XoopsFormText(_AM_QUIZMAKER_QUESTIONS_QUESTION . " [#{$questId}]", 'quest_question', 120, 255, $this->getVar('quest_question') );
		$inpQuestion->setDescription(_AM_QUIZMAKER_QUESTIONS_QUESTION_DESC);
        $inpQuestion->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_QUEST));
        $form->addXtrayElement($inpQuestion, true);
        
//////////////////////////////////////////

        $name = 'quest_question_style';
        $style = $this->getVar($name);
         $inpTitleStyle = new \XoopsFormJson(_AM_QUIZMAKER_QUESTIONS_STYLE, $name, $style, false);                  
          //$inpTitleStyle->setTextBoxVisible(true);        
          //$inpTitleStyle->setPreviewVisible(true);        
          $inpTitleStyle->addOption('size', 1, 'number', ['caption' => 'Taille', 'min'=>0.8,'max'=>2.2, 'size'=>5, 'unit'=>'em']);
          //$inpTitleStyle->addOption('line-height', 1, 'number', ['caption' => 'Interligne', 'min'=>0.8,'max'=>2.1, 'size'=>5, 'unit'=>'em']);
          $inpTitleStyle->addOption('color', '#000000', 'palette', ['caption' => 'Couleur', 'palette' => 'classic']);                  
          

          $form->addElement($inpTitleStyle);                  

/////////////////////////////////////////

		
		// Form Editor DhtmlTextArea questComment1
        $newOptions = array('height'=>'50px', 'width' => '600px');
        $inpComment1  = $quizUtility->getEditor2(_AM_QUIZMAKER_QUESTIONS_COMMENT1, 
                                                 'quest_comment1', 
                                                 $this->getVar('quest_comment1', 'e'), 
                                                 _AM_QUIZMAKER_QUESTIONS_COMMENT1_DESC, 
                                                 $newOptions, 
                                                 $quizmakerHelper);        
		$form->addXtrayElement($inpComment1);
  
        $inpPosComment = new \XoopsFormRadio(_AM_QUIZMAKER_POS_COMMENT, 'quest_posComment1', $this->getVar('quest_posComment1'));
        $inpPosComment->addOptionArray(['0'=>_AM_QUIZMAKER_POS_COMMENT_0, '1'=>_AM_QUIZMAKER_POS_COMMENT_1 , '2'=>_AM_QUIZMAKER_POS_COMMENT_2, '3'=>_AM_QUIZMAKER_POS_COMMENT_3]);
        $inpPosComment->setDescription(_AM_QUIZMAKER_POS_COMMENT_DESC);
        $form->addElement($inpPosComment);
        
		// Form Editor DhtmlTextArea quest_explanation
        $newOptions = array('height'=>'250px', 'width' => '600px');
        $inpExplanation  = $quizUtility->getEditor2(_AM_QUIZMAKER_EXPLANATION, 
                                                    'quest_explanation', 
                                                    $this->getVar('quest_explanation', 'e'), 
                                                    _AM_QUIZMAKER_EXPLANATION_DESC, 
                                                    $newOptions, 
                                                    $quizmakerHelper);        
		$form->addXtrayElement($inpExplanation);
        
		// Form Text learn_more
		$inpLearnMore = new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_LEARN_MORE, 'quest_learn_more', 120, 255, $this->getVar('quest_learn_more') );
        $inpLearnMore->setDescription(_AM_QUIZMAKER_QUESTIONS_LEARN_MORE_DESC);
		$form->addXtrayElement($inpLearnMore);
		// Form Text see_also
		$inpSeeAlso = new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_SEE_ALSO, 'quest_see_also', 120, 255, $this->getVar('quest_see_also') );
        $inpSeeAlso->setDescription(_AM_QUIZMAKER_QUESTIONS_SEE_ALSO_DESC);
		$form->addXtrayElement($inpSeeAlso);


        /* ***** Options uniquement pour les questions ***** */
        // Form quest_posComment1
/* a voir dans une prochaine si cette options est déportée de quiz vers question pour affiner la présentation individuellement
*/		
        if($clPlugin->isQuestion && $clPlugin->numbering == -1){
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
        $inpTimer->setDescription(_AM_QUIZMAKER_TIMER_DESC);
        $form->addXTrayElement($inpTimer);
        
        // Form quest_start_timer
        /*
		$inpStartTimer = new \XoopsFormRadioYN(QBR . _AM_QUIZMAKER_START_TIMER, 'quest_start_timer', $this->getVar('quest_start_timer'));
        $inpStartTimer->setDescription(_AM_QUIZMAKER_START_TIMER_DESC);
        $form->addXTrayElement($inpStartTimer);
        */
        
        
        $inpTimer = new \XoopsFormSelect(_AM_QUIZMAKER_START_TIMER , 'quest_start_timer', $this->getVar('quest_start_timer'));
		$inpTimer->addOption(0, _AM_QUIZMAKER_START_TIMER_0);
		$inpTimer->addOption(1, _AM_QUIZMAKER_START_TIMER_1);
		$inpTimer->addOption(2, _AM_QUIZMAKER_START_TIMER_2);
        $form->addXTrayElement($inpTimer);


        
        //-------------------------------------------------------
        

		// Form Editor DhtmlTextArea quest_consigne
        $inpConsigne  = $quizUtility->getEditor2(_AM_QUIZMAKER_QUESTIONS_CONSIGNE, 
                                                 'quest_consigne', 
                                                 $this->getVar('quest_consigne', 'e'), 
                                                 _AM_QUIZMAKER_QUESTIONS_CONSIGNE_DESC, 
                                                 $newOptions, 
                                                 $quizmakerHelper);        
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
        $form->insertShorcuts(_AM_QUIZMAKER_PLUGIN_CONSIGNE, null, 'magenta');        
          
          $form->addElement($clPlugin->getSlideHelper($quizmakerHelper->getConfig('display_plugin_help')));


        //====================================================================
        
        //options globales pour les propositions (height, btnColor, ...)
        $quiz = $quizHandler->get($this->getVar('quest_quiz_id'));
        $folderJS = $quiz->getVar('quiz_folderJS');
        //$idQuiz = $this->getVar('quest_quiz_id');
        //echo "<hr>dossier du quiz : {$idQuiz}-{$folderJS}<hr>";        
        //--------------------------------------------------------------  
        $form->insertShorcuts(_AM_QUIZMAKER_PLUGIN_OPTIONS, null, 'blue');        
        
        //--------------------------------------------------------------  
        $form->addElement(new \XoopsformLabel("<a href='' name='options-slide'><a>"));
        if($clPlugin->isQuestion && $clPlugin->hasGlobalPoints){
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
                  
                  $name = 'quest_image_style';  
                  $style = $this->getVar($name);  
                  //$style = '';  
/*
                  $inpJson = new \XoopsFormJson('', $name, $style);  
                  $inpJson->setStyle('background', '#FFCCFF');                
                  $inpJson->setTextBoxVisible(true);        
                  $inpJson->setPreviewVisible(true);        
                  if ($clPlugin->isQuestion){
                    $inpJson->addOption('lettrine', 0, 'hidden');
                  }else{
                    $inpJson->addOption('lettrine', 0, 'radio', ['_caption_'=> 'Lettrine', 'options'=>'non=0,Oui=1']);
                  }
                  $inpJson->addOption('height', 125, 'number', ['_caption_' => 'Hauteur', 'min'=>25,'max'=>400, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('borderRound', 12, 'number', ['_caption_'=> 'Arrondi', 'min'=>0,'max'=>150, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('shadow', '#000000', 'palette', ['_caption_' => 'Ombre', 'palette' => 'classic']);
                  $inpJson->addOption('shadow_offset', 8, 'number', ['_caption_' => "Décalage de l'ombre", 'min'=>0,'max'=>16, 'size'=>5, 'unit'=>'px']);
                  //si c'est une question on ne donne pas la possibilite de mettre l'image principale en lettrine
                  //cett option n'est dispponible que pour pageBegin, pageEnd, pageInfo, ...
                  $inpJson->updateOptions('height', ['max'=>400]);
                  $inpJson->setOrder("lettrine,height,borderRound,shadow,shadow_offset");
*/   
                  $inpJson = new \XoopsFormJson('', $name, $style);  
                  $inpJson->setStyle('background', '#FFCCFF');                
//                   $inpJson->setTextBoxVisible(true);        
//                   $inpJson->setPreviewVisible(true);        
                  if ($clPlugin->isQuestion){
                    $inpJson->addOption('lettrine', 0, 'hidden');
                  }else{
                    $inpJson->addOption('lettrine', 0, 'radio', ['caption'=> 'Lettrine', 'options'=>'non=0,Oui=1']);
                  }
                  $inpJson->addOption('height', 125, 'number', ['caption' => 'Hauteur', 'min'=>25,'max'=>400, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('borderRound', 12, 'number', ['caption'=> 'Arrondi', 'min'=>0,'max'=>150, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('shadow', '#000000', 'palette', ['caption' => 'Ombre', 'palette' => 'classic']);
                  $inpJson->addOption('shadow_offset', 8, 'number', ['caption' => "Décalage de l'ombre", 'min'=>0,'max'=>16, 'size'=>5, 'unit'=>'px']);
                  //si c'est une question on ne donne pas la possibilite de mettre l'image principale en lettrine
                  //cett option n'est dispponible que pour pageBegin, pageEnd, pageInfo, ...
                  $inpJson->updateOptions('height', ['max'=>400]);
                  //$inpJson->setOrder("lettrine,height,borderRound,shadow,shadow_offset");
//                   if($inpJson->isNew){
//                         $inpJson->updateOptions('height', ['value'=>$this->getVar('quest_height')]);
//                         $inpJson->updateOptions('shadow', ['value'=>$this->getVar('quest_shadow')]);
//                   }       
                    
                  $inpTrayImg = new \XoopsFormElementTray(_AM_QUIZMAKER_IMAGE_MAIN, "");
                  $inpTrayImg->addElement($inpImage);
                  

/*
                  $style = $this->getVar($name);  
                  //echo "<hr>{$style}<hr>"; 
                  //$style = '';   hidden
                  $inpJson = new \XoopsFormJson('', $name, $style);                  
                  $inpJson->setCaptions('Editer le style', 'Soumettre le style', 'Annuler');        
                  $inpJson->setTextBoxVisible(true);        
                  $inpJson->setPreviewVisible(true);        
                  $inpJson->addOption('height', 125, 'number', ['_caption_' => 'Hauteur', 'min'=>25,'max'=>300, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('shadow', '#000000', 'palette', ['_caption_' => 'Ombre', 'palette' => 'classic']);
                  $inpJson->addOption('shadow2', '#000000', 'palette', ['_caption_' => 'Ombre']);
                  $inpJson->addOption('borderRound', 12, 'number', ['_caption_'=> 'Arrondi', 'min'=>0,'max'=>150, 'size'=>5, 'unit'=>'px']);
                  $inpJson->addOption('borderColor', '#000000', 'color', ['_caption_' => 'Bordure']);
                  $inpJson->addOption('alignement', 'left', 'list', ['_caption_'=> 'Alignement', 'options'=>'left,center,right']);                 
                  $inpJson->addOption('aaaa', 'left', 'radio', ['_caption_'=> 'aaaaa', 'options'=>'left,center,right']);                 
                  $inpJson->addOption('bbbb', 'right', 'radio', ['_caption_'=> 'bbbb', 'options'=>'left,center,right']);                 
                  $inpJson->addOption('cccc', 'top,right,bottom', 'checkbox', ['_caption_'=> 'case a cocher', 'options'=>'top,right,bottom,left']);                 
                  $inpJson->addOption('test_red', 'red', 'hidden');
                  $inpJson->addOption('test_zzz', 'red', 'textbox');
                  //$inpJson->removeOption('borderColor');         
                  if($inpJson->isNew){
                        $inpJson->updateOptions('height', ['value'=>$this->getVar('quest_height')]);
                        $inpJson->updateOptions('shadow', ['value'=>$this->getVar('quest_shadow')]);
                  }       
                    
                  $inpTrayImg = new \XoopsFormElementTray(_AM_QUIZMAKER_IMAGE_MAIN, "");
                  $inpTrayImg->addElement($inpImage);
                  
                  
                  $inpPalette = new \XoopsFormPalette('test palette n°1', 'quest_palette1', 'lime');                 
                  $inpTrayImg->addElement($inpPalette);
                  $inpPalette = new \XoopsFormPalette('test palette n°2', 'quest_palette2', 'yellow', '16c');                 
                  $inpTrayImg->addElement($inpPalette);
                  $inpPalette = new \XoopsFormPalette('test palette n°3', 'quest_palette3', 'cyan');
                  $inpPalette->setUserPalette("black,red,lime,blue,white", 5,32);                 
                  $inpTrayImg->addElement($inpPalette);
*/                  
                  
                  
/*
                  $name = 'quest_height';  
                  $height = ( $this->getVar($name)) ?  $this->getVar($name) : 32;
                  $inpHeight1 = new \XoopsFormNumber(_AM_QUIZMAKER_IMG_HEIGHT1 . " : ",  $name, 5, 3, $height);
                  $inpHeight1->setMinMax(32, 500, _AM_QUIZMAKER_UNIT_PIXELS);
                  //$form->addElement($inpHeight1);     
 
                  $name = 'quest_shadow';  
                  $shadow = ( $this->getVar($name)) ?  $this->getVar($name) : '#000000';
                  $inpShadow = new \XoopsFormPalette(_AM_QUIZMAKER_SHADOW__AP_QUIZMAKER_COLOR . " : ", $name, $shadow);                 
                  //$inpShadow = new \XoopsFormColorPicker(_AM_QUIZMAKER_SHADOW__AP_QUIZMAKER_COLOR . " : ", $name, $shadow);
                  
                  $inpTrayImg->addElement($inpHeight1);
                  $inpTrayImg->addElement($inpShadow);
*/


                  $inpShadowDesc = new \XoopsFormLabel('', _AM_QUIZMAKER_SHADOW__AP_QUIZMAKER_COLOR_DESC);
                  
                  //$inpTrayImg->addElement($inpShadowDesc);

$inpTrayImg->addElement($inpJson);
                  $form->addElement($inpTrayImg);     

              }
           
            //--------------------------------------------
            $background = $this->getVar('quest_background');
            $inpBakground = $clPlugin->getFormImage(_AP_QUIZMAKER_BACKGROUND_MAIN, 'quest_background', $background, $folderJS);
            $inpBakground->setCaption(_AP_QUIZMAKER_BACKGROUND_MAIN);
            $form->addElement($inpBakground);     

            // --------- ajout des options propres au plugin -------------------
            $form->insertShorcuts(_AM_QUIZMAKER_PLUGIN_OPTIONS_PLUGIN, $shortcut, 'red');        
            
            $inpOptions = $clPlugin->getFormOptions(_AM_QUIZMAKER_SPECIFIC_OPTIONS, QUIZMAKER_PREFIX_OPTIONS_NAME,  $options, $folderJS);
            if($inpOptions){
                //$form->addElement($inpOptions, false);
                $form->integrate($inpOptions, $clPlugin->integration);        
            }
            
        } 
       
        //================================================
        //ajout des propositions de réponses<br />
        $form->insertShorcuts(_AM_QUIZMAKER_PROPOSITIONS_ANSWERS, null, 'yellow');        
       
        //insertion de optionsForm propre à chaque plugin
//         if ($clPlugin->integration == 1){
//             $inpProposition = $clPlugin->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id'));
//             $form->insertBreak($inpProposition->render());
// 
//         }else{
//             $form->addElement($clPlugin->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id')));
//         }
        $form->integrate($clPlugin->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id')), $clPlugin->integration);        
        //================================================
		// To Save
        $form->insertShorcuts(_AM_QUIZMAKER_SUBMIT, $shortcut, 'cyan');        
        
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormHidden('quest_id', $questId));
        
        $btnTray = new \XoopsFormElementTray  ('', '&nbsp;');
        
        //remplacé par le bouton annuler qui appel la page php et non un retour arrière
        //$btnTray->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        
        
        //================================================
		// vouttons d'    action
        //================================================
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
        
        $form->insertBreakJanus($btnTray->render(), '#CCFFFF');

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
		$ret['reference_id']   = $this->getVar('quest_reference_id');
		$ret['quiz_id']        = $this->getVar('quest_quiz_id');
		$ret['plugin']         = $pluginName; //$this->getVar('quest_plugin');
		$ret['question']       = $this->getVar('quest_question');
 		$ret['question_style'] = $this->getVar('quest_question_style');
		$ret['identifiant1']   = $this->getVar('quest_identifiant1');
		$ret['identifiant2']   = $this->getVar('quest_identifiant2');
		$editorMaxchar = $quizmakerHelper->getConfig('editor_maxchar');
        
        //getVar genere une transformation facheuse 
		$ret['options']        = html_entity_decode($this->getVar('quest_options')) ;
        //pour palier aux transfert des options spécifiques sur des quiz plus anciens,
        //on recupère les options par éfauts en attenaant de modifier et valider de nouveau la question
        if(!$ret['options']) $ret['options'] = json_encode($clPlugin->optionsDefaults);
		$ret['optionsArr']     = json_decode(html_entity_decode($ret['options']),true);
      
		$ret['comment1']       = $this->getVar('quest_comment1', 'e');
		$ret['pos_comment1']   = $this->getVar('quest_posComment1');
		$ret['comment1_short'] = $utility::truncateHtml($ret['comment1'], $editorMaxchar);
 		$ret['explanation']    = $this->getVar('quest_explanation', 'e');
 		$ret['explanation_short'] = $utility::truncateHtml($ret['explanation'], $editorMaxchar);
 		$ret['consigne']       = $this->getVar('quest_consigne', 'e');
 		$ret['learn_more']     = $this->getVar('quest_learn_more', 'e');
 		$ret['see_also']       = $this->getVar('quest_see_also', 'e');
 		$ret['image']          = $this->getVar('quest_image', 'e');
 		$ret['height']         = $this->getVar('quest_height');
 		$ret['shadow']         = $this->getVar('quest_shadow');
 		$ret['image_style']    = $this->getVar('quest_image_style');
 		$ret['zoom']           = $this->getVar('quest_zoom');
 		$ret['background']     = $this->getVar('quest_background', 'e');
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
        
		//$ret['isQuestion']        = $clPlugin->isQuestion;
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
        $flags['shuffle'] = quizFlagAlpha($ret['shuffleAnswers'], "M|M","red|green|blue|blue");
                                           
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
 *  getPlugin : renvoie la class du plugin
 * @return : classe héritée du plugin
 * *********************** */
    public function getPlugin(&$pluginName = null)
    {
    //echo "<hr>{$default}<hr>";
        global $quizUtility, $pluginsHandler;
        // recupe de la classe du plugin
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
  public function sendo($newQuizId, $action){
  global $quizHandler;
  
    
    //remplacer quiz_id courant par quiz_id de destination dans question
    
    //deplacer image et background dans le dossier du quiz de desstination
    
    //déplacer image1 et image 2 de answers dans le dossier du quiz de desstination
        

    $quizFrom = $quizHandler->get($this->getVar('quest_quiz_id'));
    $fldFrom = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $quizFrom->getVar('quiz_folderJS');
    
    $quizTo = $quizHandler->get($newQuizId);
    $fldTo = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' .  $quizTo->getVar('quiz_folderJS');
    
    //echo "<hr>newQuizId : {$newQuizId}<br>From : <br>{$fldFrom}<br>to : <br>{$fldTo}<hr>";
exit('move');
   }

/**************************************************************
 * get_quest_images : renvoie un tableau des images de la question pass en paramètre
 * utilisé pour deplacer ou compié une question dans un autre quiz.
 * @$questIdFrom : Id de la question
 * ************************************************************/
public function getImages(){
global $questionsHandler, $answersHandler;
    $allImg = array();
    $questId = $this->getVar('quest_id');
    
    $question = $this->getValuesQuestions();    
    if($question['image'])
        $allImg[] = $question['image'];

    if($question['background'])
        $allImg[] = $question['background'];
    
    //recharche des images des proposition de la question : image1 et image2
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('answer_quest_id', $questId, '='));
//     $criteria->add(new \Criteria('answer_image1', "%jpg%", 'LIKE'));
//     $criteria->add(new \Criteria('answer_image2', "%jpg%", 'LIKE'), "OR");
    $rst = $answersHandler->getAll($criteria);
    if(count($rst) > 0){
      foreach(array_keys($rst) as $i) {
        $answer =  $rst[$i]->getValuesAnswers();
        if( $answer['image1'])
            $allImg[] = $answer['image1'];
            
        if( $answer['image2'])
            $allImg[] = $answer['image2'];
      }
    }
    
    $tExtImg = array('jpg', 'jpeg','png','gif');
    //echo("===>" . $this->getVar['quest_options']);
    //echo("===>" . $question['options']);
    //$tOptions = json_decode($this->getVar['quest_options'],true);    
    if (is_array($question['options'])){
        foreach($question['options'] as $key=>$v){
            $i = strrpos($v, '.');
            if($i !== false){
              $ext = substr($v, $i+1);
              //echo "ext = $ext";
              if(in_array($ext, $tExtImg)) $allImg[] = $v;
            }
        } 
       
    }
     //----------------------------------------------------------------
    return $allImg;
}
 
/**************************************************************
 * get_quest_images : renvoie un tableau des images de la question pass en paramètre
 * utilisé pour deplacer ou compié une question dans un autre quiz.
 * @$questIdFrom : Id de la question
 * ************************************************************/
public function getPageReponse(){
global $questionsHandler;
    $identifiant1 = $this->getVar('quest_identifiant1');
    //recherche si il y a une page d'info page info liée à cette question
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $this->getVar('quest_quiz_id'), '='));
    $criteria->add(new \Criteria('quest_plugin', 'pageAnswer', '='));
    $criteria->add(new \Criteria('quest_identifiant2', $identifiant1, '='));
    
    $rst = $questionsHandler->getAll($criteria);
    //echoArray($rst, "identifiant = {$identifiant1}");exit;
    if(count($rst) > 0) {
        return array_shift($rst);
    }else{
        return null;
    }

} 
}//------------------- FIN DE LA CLASSE ---------------------------------




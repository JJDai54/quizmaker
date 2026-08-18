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
use JANUS;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Quiz
 */
class Quiz extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quiz_flag', XOBJ_DTYPE_INT);
		$this->initVar('quiz_flag_text', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_cat_id', XOBJ_DTYPE_INT);
		$this->initVar('quiz_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_subject', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_author', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_difficulty', XOBJ_DTYPE_INT);
		$this->initVar('quiz_folderJS', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_description', XOBJ_DTYPE_OTHER);
		$this->initVar('quiz_weight', XOBJ_DTYPE_INT);
		$this->initVar('quiz_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_dateBegin', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_dateEnd', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_publishQuiz', XOBJ_DTYPE_INT);
		$this->initVar('quiz_publishResults', XOBJ_DTYPE_INT);
		$this->initVar('quiz_publishAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_theme', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_background', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_libBegin', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_libEnd', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_questPosComment1', XOBJ_DTYPE_INT);
		$this->initVar('quiz_legend', XOBJ_DTYPE_OTHER);
		$this->initVar('quiz_build', XOBJ_DTYPE_INT);
		$this->initVar('quiz_optionsIhm', XOBJ_DTYPE_INT);
		$this->initVar('quiz_optionsDev', XOBJ_DTYPE_INT);
		$this->initVar('quiz_actif', XOBJ_DTYPE_INT);
		$this->initVar('quiz_delai_cookie', XOBJ_DTYPE_INT);
		$this->initVar('quiz_max_attempts', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showConsigne', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showTimer', XOBJ_DTYPE_INT);
		$this->initVar('quiz_timerSize', XOBJ_DTYPE_INT);
		$this->initVar('quiz_dateBeginOk', XOBJ_DTYPE_INT);
		$this->initVar('quiz_dateEndOk', XOBJ_DTYPE_INT);
        
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

	/**
	 * The new inserted $Id
	 * @return inserted id
	 */
	public function getNewInsertedIdQuiz()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormQuiz($action = false)
	{global $utility, $categoriesHandler, $quizUtility, $pluginsHandler;
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        $quiId = $this->getVar('quiz_id');
        
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_QUIZ_ADD) : sprintf(_AM_QUIZMAKER_QUIZ_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		//$form = new \XoopsThemeForm($title . " (#{$quiId})", 'form', $action, 'post', true);
		$form = new \XoopsFormJanus($title . " (#{$quiId})", 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');

		// Quiz Handler
		$quizHandler = $quizmakerHelper->getHandler('Quiz');
        $form->addXtrayElement(new \XoopsFormHidden('quiz_id', $quiId));
		
        // Form Select quizCat_id
		$quizCat_idSelect = new \XoopsFormSelect( _AM_QUIZMAKER_CATEGORY, 'quiz_cat_id', $this->getVar('quiz_cat_id'));
		$quizCat_idSelect->addOptionArray($categoriesHandler->getListKeyName());
		$form->addXtrayElement($quizCat_idSelect, true);
        
        // Form Text quizName
		$form->addXtrayElement(new \XoopsFormText( _AM_QUIZMAKER_NAME, 'quiz_name', 50, 255, $this->getVar('quiz_name') ), true);
       
        // Form Text quiz_subject
        $fldName = 'quiz_subject';
        $catId = $this->getVar('quiz_cat_id');
        if (!$this->getVar($fldName)) $this->setVar($fldName, _AM_QUIZMAGER_MISCELLANEOUS);
        $allSet =  $quizHandler->getFieldList($fldName, $catId);
        if (count($allSet) > 0) {
            //echoArray($allSet);
            $inpSet = new \XoopsEditList(_CO_QUIZMAKER_QUIZ_SUBJECT, $fldName, $this->getVar($fldName), 50) ; 
            $inpSet->addOptionArray($allSet);
            $inpSet->setBackground('#E0FFE0');
            $inpSet->setWidth(250);
            
            //$inpSet->setHeight(80);
        }else{
            $inpSet = new \XoopsFormText( _CO_QUIZMAKER_QUIZ_SUBJECT, $fldName, 50, 255, $this->getVar($fldName));
            $inpSet->setDescription(_CO_QUIZMAKER_QUIZ_SUBJECT_DESC);
        }  
        $form->addXtrayElement($inpSet, false);
/* **************


  $k = "colediteur";
  $searchEditeur = ($t['idEditeur']) ?  "AND idEditeur={$t['idEditeur']}" : '';
  $tCollection = $this->handler->getDistinct('colediteur', "colediteur <> '' {$searchEditeur}", "ASC", false);
 
  $tCollection = array_values($tCollection);
  $xf[$k] = new XoopsEditList($k, sprintf($xName,$k), $t[$k], 100) ; 
  $xf[$k]->addOptionArray($tCollection);
  $xf[$k]->setBackground('#C4DDF3');

  $xf[$k]->setHeight(80);
  $form->addXtrayElement($xf[$k], false);     
  
  $name = sprintf($xName,'old_'.$k);
  $xf[$name] = new XoopsFormHidden($name,$t[$k]);
  $form->addXtrayElement($xf[$name], false);     


***************** */        
        // Form Select quiz_difficulty
		$quizDifficulty = new \XoopsFormSelect( _CO_QUIZMAKER_DIFFICULT, 'quiz_difficulty', $this->getVar('quiz_difficulty'));
		$quizDifficulty->addOption(0, _CO_QUIZMAKER_DIFFICULT_0);
		$quizDifficulty->addOption(1, _CO_QUIZMAKER_DIFFICULT_1);
		$quizDifficulty->addOption(2, _CO_QUIZMAKER_DIFFICULT_2);
		$quizDifficulty->addOption(3, _CO_QUIZMAKER_DIFFICULT_3);
		$quizDifficulty->addOption(4, _CO_QUIZMAKER_DIFFICULT_4);
		$form->addXtrayElement($quizDifficulty, true);
		
        // Form Text quiz_author
		$form->addXtrayElement(new \XoopsFormText( _AM_QUIZMAKER_QUIZ_AUTHOR, 'quiz_author', 50, 255, $this->getVar('quiz_author') ), false);

        //----------------------------------------------------------
        $fileNameTray = new \XoopsFormElementTray(_AM_QUIZMAKER_FILE_NAME_JS, ' ');        
		// Form Text quiz_folderJS
        $folderJS = $this->getVar('quiz_folderJS');
        $inpFileName = new \XoopsFormText('' , 'quiz_folderJS', 50, 255, $folderJS);
        $inpFileName->setDescription(_AM_QUIZMAKER_FILE_NAME_JS_DESC);
		$fileNameTray->addElement($inpFileName, false);
        
		// Form number quiz_build
		$build = $this->isNew() ? 0 : $this->getVar('quiz_build');
        $inpBuild = new \XoopsFormNumber(_AM_QUIZMAKER_QUIZ_BUILD, 'quiz_build', 5, 5, $build);
        $inpBuild->setMinMax(0, 500);
		$fileNameTray->addElement($inpBuild);
        
		$form->addXtrayElement($fileNameTray);
        
        // Form Text quiz_weight
		$form->addXtrayElement(new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quiz_weight', 50, 255, $this->getVar('quiz_weight') ), false);
        //----------------------------------------------------------
		// Form Editor DhtmlTextArea quizDescription
        /* champ a supprimer fait double emploi avec les champs du premier slide "page_info/intro"
        $editDescription = $quizUtility->getEditor2(_AM_QUIZMAKER_DESCRIPTION, 'quiz_description', $this->getVar('quiz_description', 'e'),  _AM_QUIZMAKER_DESCRIPTION_DESC, null, $quizmakerHelper);
		$form->addXtrayElement($editDescription, true);
        */
            

        //-------------------------------------------------------
        // quiz_max_attempts : Maximum de tentives pour une meme cession
        $name = 'quiz_max_attempts';
        $inpMaxAttempts = new \XoopsFormNumber(_AM_QUIZMAKER_QUIZ_MAX_ATTEMPTS,  $name, 3, 1, $this->getVar($name));
        $inpMaxAttempts->setMinMax(0, QUIZMAKER_MAX_ATTEMPTS, _AM_QUIZMAKER_UNIT_ATTEMPTS);
        $inpMaxAttempts->setExtra(FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_CAT));
        $inpMaxAttempts->setDescription(_AM_QUIZMAKER_QUIZ_MAX_ATTEMPTS_DESC);
        $form->addXtrayElement($inpMaxAttempts);    
         
        // quiz_delai_cookie
        $name = 'quiz_delai_cookie';
		$inpDuration = new \XoopsFormDuration( _AM_QUIZMAKER_COOKIE_DURATION, $name, $this->getVar($name));
        $inpDuration->setDescription(_AM_QUIZMAKER_COOKIE_DURATION_DESC);
        $inpDuration->setCompteurs("dhms");
		$form->addXtrayElement($inpDuration);
        
        //-------------------------------------------------------

		// Form Check Box quizDateBegin
        $quizDateBegin = \JANUS\xoopsformDateOkTray(_AM_QUIZMAKER_DATEBEGIN, 'quiz_dateBeginOk', $this->getVar('quiz_dateBeginOk'), 'quiz_dateBegin', $this->getVar('quiz_dateBegin'));
		$form->addXtrayElement($quizDateBegin);
        
		// Form Check Box quizDateEnd
        $quizDateEnd = \JANUS\xoopsformDateOkTray(_AM_QUIZMAKER_DATEEND, 'quiz_dateEndOk', $this->getVar('quiz_dateEndOk'), 'quiz_dateEnd', $this->getVar('quiz_dateEnd'));
		$form->addXtrayElement($quizDateEnd);
        
		// Form Check Box quiz_actif
		$quizActif = $this->isNew() ? 1 : $this->getVar('quiz_actif');
		$inpActif = new \XoopsFormRadioYN( _AM_QUIZMAKER_ACTIF, 'quiz_actif', $quizActif);
		$form->addXtrayElement($inpActif);
        
		// Form Check Box quiz_publishQuiz
		$quizExecution = $this->isNew() ? 0 : $this->getVar('quiz_publishQuiz');
		$inpExecution = new \XoopsFormRadio( _CO_QUIZMAKER_PUBLISH_QUIZ, 'quiz_publishQuiz', $quizExecution);
        $inpExecution->setDescription(_AM_QUIZMAKER_PUBLISH_QUIZ_DESC);
		$inpExecution->addOption(0, _CO_QUIZMAKER_PUBLISH_NONE);
		$inpExecution->addOption(1, _CO_QUIZMAKER_PUBLISH_INLINE);
		$inpExecution->addOption(2, _CO_QUIZMAKER_PUBLISH_OUTLINE);
		$form->addXtrayElement($inpExecution);

        $name = 'quiz_publishResults';
        $publishArr = array(1=>_YES, 0=>_NO, 2=>_AM_QUIZMAKER_AUTO);
        $inpPublishResults = new \XoopsFormRadio(_AM_QUIZMAKER_PUBLISH_RESULTS , $name, $this->getVar($name));
        $inpPublishResults->addOptionArray($publishArr);
        $inpPublishResults->setDescription(_AM_QUIZMAKER_PUBLISH_AUTO_DESC);
		$form->addXtrayElement($inpPublishResults);
        
        $name = 'quiz_publishAnswers';
        $inpPublishAnswers = new \XoopsFormRadio(_AM_QUIZMAKER_PUBLISH_ANSWERS , $name, $this->getVar($name));
        $inpPublishAnswers->addOptionArray($publishArr);
        $inpPublishAnswers->setDescription(_AM_QUIZMAKER_PUBLISH_AUTO_DESC);
		$form->addXtrayElement($inpPublishAnswers);

        /* JJDai - Pas vraiment utile, mais je garde des fois que ça puisse servir a autre chose
        oui : ce bouton est activer sur le dernier slide
        non :  ce bouton esst désactiver sur le dernier slide (utilisation en dehors du site a verifier)
        */
        
        //========================================================
        $form->insertBreakJanus(_AM_QUIZMAKER_OPTIONS_FOR_QUIZ, 'black');

        // Form Text quiz_theme
        $inpTheme = new \XoopsFormSelect(_AM_QUIZMAKER_THEME, 'quiz_theme', $this->getVar('quiz_theme'));
		$inpTheme->setDescription(_AM_QUIZMAKER_THEME_DESC);
        //$inpTheme->addOptionArray($quizUtility::get_css_color(true));
        $inpTheme->addOptionArray( \JANUS\get_css_color());
		$form->addXtrayElement($inpTheme, false);

        //--------------------------------------------
        $urlImg =  QUIZMAKER_URL_UPLOAD_QUIZ . "/{$folderJS}/images";
        $maxSize = $quizmakerHelper->getConfig('maxsize_image');
        
        $image = $this->getVar('quiz_image');
        $inpImage = new \XoopsFormImage(_AM_QUIZMAKER_IMAGE , 'quiz_image', $maxSize, $image,  $urlImg);
		$form->addXtrayElement($inpImage);
        
        //--------------------------------------------
        // Form Text quiz_background
        $background = $this->getVar('quiz_background');
        $inpBakground = new \XoopsFormImage(_AP_QUIZMAKER_BACKGROUND_MAIN , 'quiz_background', $maxSize, $background,  $urlImg);
		$form->addXtrayElement($inpBakground);
        
        // Form Text quiz_libBegin
        $libBegin = ($this->getVar('quiz_libBegin')) ? $this->getVar('quiz_libBegin') :  _CO_QUIZMAKER_LIB_BEGIN_DEFAULT;
        $inpLibBegin = new \XoopsFormText(_CO_QUIZMAKER_LIB_BEGIN , 'quiz_libBegin', 120, 120, $libBegin);  
        $inpLibBegin->setDescription(_CO_QUIZMAKER_LIB_BEGIN_DESC);  
		$form->addXtrayElement($inpLibBegin, false);
        
        // Form Text quiz_libEnd
        $libEnd = ($this->getVar('quiz_libEnd')) ? $this->getVar('quiz_libEnd') :  _CO_QUIZMAKER_LIB_END_DEFAULT;
        $inpLibEnd = new \XoopsFormText(_CO_QUIZMAKER_LIB_END , 'quiz_libEnd', 120, 120, $libEnd);  
        $inpLibEnd->setDescription(_CO_QUIZMAKER_LIB_END_DESC);  
		$form->addXtrayElement($inpLibEnd, false);
        
		// Form Check Box quiz_questPosComment1
		$inpPosComment = new \XoopsFormRadio(_AM_QUIZMAKER_POS_COMMENT, 'quiz_questPosComment1', $this->getVar('quiz_questPosComment1'));
        $inpPosComment->addOptionArray(['1'=>_AM_QUIZMAKER_POS_COMMENT_1, '2'=>_AM_QUIZMAKER_POS_COMMENT_2 , '3'=>_AM_QUIZMAKER_POS_COMMENT_3]);
        $inpPosComment->setDescription(_AM_QUIZMAKER_POS_COMMENT_DESC);
        $form->addXtrayElement($inpPosComment);

		// Form Check Box quiz_showConsigne
		$quizShowConsigne = $this->isNew() ? 0 : $this->getVar('quiz_showConsigne');
		$inpShowConsigne = new \XoopsFormSelect( _AM_QUIZMAKER_QUIZ_SHOW_CONSIGNE, 'quiz_showConsigne', $quizShowConsigne);
		$inpShowConsigne->setDescription(_AM_QUIZMAKER_QUIZ_SHOW_CONSIGNE_DESC);
        $inpShowConsigne->addOption(0, _AM_QUIZMAKER_POSITION_NONE);
        $inpShowConsigne->addOption(1, _AM_QUIZMAKER_POSITION_TL);
        $inpShowConsigne->addOption(2, _AM_QUIZMAKER_POSITION_TR);
        $inpShowConsigne->addOption(3, _AM_QUIZMAKER_POSITION_BR);
        $inpShowConsigne->addOption(4, _AM_QUIZMAKER_POSITION_BL);
		//$form->addXtrayElement($inpShowConsigne);
		$form->addXtrayElement($inpShowConsigne);
        
		// Form Check Box quiz_showTimer
		$quizShowTimer = $this->isNew() ? 1 : $this->getVar('quiz_showTimer');
		$quizShowTimer = new \XoopsFormSelect( _AM_QUIZMAKER_QUIZ_SHOW_TIMER, 'quiz_showTimer', $quizShowTimer);
		$quizShowTimer->setDescription(_AM_QUIZMAKER_QUIZ_SHOW_TIMER_DESC);
        //$quizShowTimer->addOption(0, _AM_QUIZMAKER_POSITION_NONE);
        $quizShowTimer->addOption(1, _AM_QUIZMAKER_POSITION_TL);
        $quizShowTimer->addOption(2, _AM_QUIZMAKER_POSITION_TR);
        $quizShowTimer->addOption(3, _AM_QUIZMAKER_POSITION_BR);
        $quizShowTimer->addOption(4, _AM_QUIZMAKER_POSITION_BL);
		$form->addXtrayElement($quizShowTimer);
        
        
		// Form Check Box quiz_timerSize
        $name = 'quiz_timerSize';
        $minSize = 48;
        $timerSieze = ($this->getVar($name) < $minSize) ? $minSize : $this->getVar($name);
        $inpTimerSize = new \XoopsFormNumber(_AM_QUIZMAKER_TIMER_SIZE,  $name, 3, 1, $timerSieze);
        $inpTimerSize->setMinMax($minSize, 200, _AM_QUIZMAKER_UNIT_PIXELS);
        $inpTimerSize->setDescription(_AM_QUIZMAKER_TIMER_SIZE_DESC);
        $form->addXtrayElement($inpTimerSize);    

//         $inpTimerJson = new \XoopsFormJson(_AM_QUIZMAKER_TIMER_SIZE, $name, $style);                  
//         //$inpTimerJson->setTextBoxVisible(true);        
//         //$inpTimerJson->setPreviewVisible(true);        
//         $inpTimerJson->addOption('height', 48, 'number', ['caption' => 'Hauteur', 'min'=>48,'max'=>250, 'size'=>48, 'unit'=>'px']);
//         $inpTimerJson->addOption('font_size', 14, 'number', ['caption' => 'Font_size', 'min'=>12,'max'=>250,'size' => 48, 'unit' => 'px']);
// 
// //         if($inpTimerJson->isNew){
// //               $inpTimerJson->updateOptions('height', ['value'=>$this->getVar('quest_height')]);
// //               $inpTimerJson->updateOptions('font_size', ['value'=>$this->getVar('quest_fontSize')]);
// //         }       
//         $form->addXtrayElement($inpTimerJson);    

/*
        // Form Editor DhtmlTextArea quizLegend
        $editLegend = \JANUS\getformTextarea(_AM_QUIZMAKER_LEGEND, 'quiz_legend', $this->getVar('quiz_legend', 'e'), _AM_QUIZMAKER_LEGEND_DESC);
		$form->addXtrayElement($editLegend, false);
*/		
        
        //========================================================
		// Form CheckBoxBin quiz_optionsIhm
        //========================================================
        $form->insertBreakJanus(_AM_QUIZMAKER_OPTIONS_FOR_QUIZ, 'blue');
        
        $inpOptionsIhm = new \xoopsFormCheckboxBin(_AM_QUIZMAKER_QUIZ_OPTIONS_IHM . "[{$this->getVar('quiz_optionsIhm')}]", 'quiz_optionsIhm', $this->getVar('quiz_optionsIhm'),1,true);
        $inpOptionsIhm->setDescription(_AM_QUIZMAKER_QUIZ_OPTIONS_IHM_DESC);
        $inpOptionsIhm->addOptionArray(getBinOptionsArr('ihm'));
		$form->addXtrayElement($inpOptionsIhm);
        
        //========================================================
		// Form CheckBoxBin quiz_optionsDev
        //========================================================
        $form->insertBreakJanus(_AM_QUIZMAKER_OPTIONS_FOR_DEV, 'red');

		// Form CheckBoxBin quiz_optionsDev
        $inpOptionsDev = new \xoopsFormCheckboxBin(_AM_QUIZMAKER_QUIZ_OPTIONS_DEV . "[{$this->getVar('quiz_optionsDev')}]", 'quiz_optionsDev', $this->getVar('quiz_optionsDev'),1,true);
        $inpOptionsDev->setDescription(_AM_QUIZMAKER_QUIZ_OPTIONS_DEV_DESC);
        $inpOptionsDev->addOptionArray(getBinOptionsArr('dev'));
		$form->addXtrayElement($inpOptionsDev);


        //========================================================
        //$form->insertBreakJanus(_AM_QUIZMAKER_PERMISSIONS, 'green');
        
		// To Save
		$form->addXtrayElement(new \XoopsFormHidden('op', 'save'));
		$form->addXtrayElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
	}

	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesQuiz($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $categoriesHandler;
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
        
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']                = $this->getVar('quiz_id');
		$ret['cat_id']            = $this->getVar('quiz_cat_id');
		$ret['name']              = $this->getVar('quiz_name');
		$ret['flagInt']           = $this->getVar('quiz_flag');
		$ret['flagTxt']           = $this->getVar('quiz_flag_text');
		$ret['subject']           = $this->getVar('quiz_subject');
		$ret['author']            = $this->getVar('quiz_author');
		$ret['difficulty']        = $this->getVar('quiz_difficulty');
        $difArr = [_CO_QUIZMAKER_DIFFICULT_0,_CO_QUIZMAKER_DIFFICULT_1,_CO_QUIZMAKER_DIFFICULT_2,_CO_QUIZMAKER_DIFFICULT_3,_CO_QUIZMAKER_DIFFICULT_4];
		$ret['difficulty_lib']    = $difArr[$ret['difficulty']];
		$ret['difficulty_icon']   = 'difficulty-0' . $ret['difficulty'] . '.png';
		$ret['folderJS']          = $this->getVar('quiz_folderJS');
		$ret['description']       = $this->getVar('quiz_description', 'e');
		$ret['weight']            = $this->getVar('quiz_weight');
		$editorMaxchar = $quizmakerHelper->getConfig('editor_maxchar');
		$ret['description_short'] = $utility::truncateHtml($ret['description'], $editorMaxchar);
		$ret['creation']          = \JANUS\getDateSql2Str($this->getVar('quiz_creation'));
		$ret['update']            = \JANUS\getDateSql2Str($this->getVar('quiz_update'));

// 		$ret['dateBegin']         = formatTimeStamp($this->getVar('quiz_dateBegin'), 'm');
// 		$ret['dateEnd']           = formatTimeStamp($this->getVar('quiz_dateEnd'), 'm');
        
		$ret['dateBegin']          = \JANUS\getDateSql2Str($this->getVar('quiz_dateBegin'));
		$ret['dateEnd']            = \JANUS\getDateSql2Str($this->getVar('quiz_dateEnd'));
		$ret['periodeOK']          = \JANUS\isDateBetween($this->getVar('quiz_dateBegin'), $this->getVar('quiz_dateEnd'), $this->getVar('quiz_dateBeginOk'), $this->getVar('quiz_dateEndOk'));
         
		$ret['publishQuiz']         = $this->getVar('quiz_publishQuiz');
		$ret['publishQuiz_lib']     = Array(_CO_QUIZMAKER_PUBLISH_NONE,_CO_QUIZMAKER_PUBLISH_INLINE,_CO_QUIZMAKER_PUBLISH_OUTLINE)[$ret['publishQuiz']];
		
        
        $ret['publishResults']      = $this->getVar('quiz_publishResults');
        $ret['publishResultsOk']    = (($ret['periodeOK']==0 && $ret['publishQuiz']>0 && $ret['publishResults']==2) || $ret['publishResults']==1) ? 1 : 0;

		$ret['publishAnswers']      = $this->getVar('quiz_publishAnswers');
        $ret['publishAnswersOk']    = (($ret['periodeOK']==0 && $ret['publishQuiz']>0 && $ret['publishAnswers']==2) || $ret['publishAnswers']==1) ? 1 : 0;

        
		$ret['theme']             = $this->getVar('quiz_theme');
		$ret['image']             = $this->getVar('quiz_image');
		$ret['background']        = $this->getVar('quiz_background');
		$ret['libBegin']          = $this->getVar('quiz_libBegin');
		$ret['libEnd']            = $this->getVar('quiz_libEnd');
        $ret['theme_ok'] = ($ret['theme'] == '') ? $categoriesHandler->getValue($ret['cat_id'],'cat_theme','default') : $ret['theme'];
		$ret['questPosComment1']  = $this->getVar('quiz_questPosComment1');
		$ret['legend']            = $this->getVar('quiz_legend', 'e');
		$ret['legend_short']      = $utility::truncateHtml($ret['legend'], $editorMaxchar);
		$ret['dateBeginOk']       = $this->getVar('quiz_dateBeginOk');
		$ret['dateEndOk']         = $this->getVar('quiz_dateEndOk');
		$ret['build']             = $this->getVar('quiz_build');
		$ret['optionsIhm']        = $this->getVar('quiz_optionsIhm');
		$ret['optionsDev']        = $this->getVar('quiz_optionsDev');
		$ret['actif']             = $this->getVar('quiz_actif');
		$ret['delai_cookie']      = $this->getVar('quiz_delai_cookie');
		$ret['max_attempts']      = $this->getVar('quiz_max_attempts');
		$ret['showConsigne']      = $this->getVar('quiz_showConsigne');
		$ret['showTimer']         = $this->getVar('quiz_showTimer');
		$ret['timerSize']         = $this->getVar('quiz_timerSize');

        //verifie que le quiz a été généré
        $quiz_html = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$ret['folderJS']}/index.html"; 
        $ret['quiz_html'] = (file_exists($quiz_html)) ?  QUIZMAKER_URL_UPLOAD_QUIZ . "/{$ret['folderJS']}/index.html" : '';
        $ret['quiz_html_path'] = (file_exists($quiz_html)) ?  $quiz_html : '';
        
        $quiz_tpl = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$ret['folderJS']}/index.tpl"; 
        $ret['quiz_tpl'] = (file_exists($quiz_tpl)) ?  QUIZMAKER_URL_UPLOAD_QUIZ . "/{$ret['folderJS']}/index.tpl" : '';
        $ret['quiz_tpl_path'] = (file_exists($quiz_tpl)) ?  $quiz_tpl : '';
        $ret['flags'] = $this->getFlags($ret);




        
        $ret['countQuestions'] = $this->countQuestions();
        
		return $ret;
	}
	
    public function getFlags(&$ret){
        $flags = array();
        $flags['actif']             = quizFlagAscii($ret['actif'], "A");
        $flags['showConsigne']      = quizFlagAscii($ret['showConsigne'], "?");
        $flags['publishQuiz']       = quizFlagAscii($ret['publishQuiz'], "P");
        $flags['publishResults']    = quizFlagAscii($ret['publishResults'], "R");
        $flags['publishAnswers']    = quizFlagAscii($ret['publishAnswers'], "S");

        $flags = array_merge($flags,
                 getBinOptionsFlagsArr('ihm', $ret['optionsIhm']),
                 getBinOptionsFlagsArr('dev', $ret['optionsDev']));

        return $flags;
}                                      

	/**
	 * Returns folder with prefix de categorie si il est defini
	 * $parram $ret : 0 : return folder
	 *                1 : return full path
	 *                2 : return full URL
	 * @param $subfolder string
	 * @return folder
	 */
	public function getFolderJS($ret = 0, $subfolder='')
	{
        $fldJS = $this->getVar('quiz_folderJS');
        if ($subfolder) $fldJS .= '/' . $subfolder;
        $fldJS = str_replace('//', '/' , $fldJS);
        
        switch($ret){
            case 1: return QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $fldJS; break;
            case 2: return QUIZMAKER_URL_UPLOAD_QUIZ  . '/' . $fldJS; break;
            default: return $fldJS; break;
        }
        
        return false;
    }
	public function getFolderImages($ret = 0, $subfolder='')
	{
        return getFolderJS($ret, QUIZMAKER_FLD_PLUGIN_IMAGES);
    }
	public function getFolderSounds($ret = 0, $subfolder='')
	{
        return getFolderJS($ret, QUIZMAKER_FLD_PLUGIN_SOUNDS);
    }
        

     
	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayQuiz()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function getParentId($quizId)

    {
        $ob = $this->get('quest_id', $questId);
        return $ob->GetVar('quest_quiz_id');
    }
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function countQuestions()

    {
    global $questionsHandler;
    
    $criteria = new \CriteriaCompo();
    $criteria->add( new \Criteria("quest_quiz_id",  $this->getVar('quiz_id'), "="));
//     $criteria->add( new \Criteria("quest_plugin",  'pageBegin', "<>"));
//     $criteria->add( new \Criteria("quest_plugin",  'pageEnd', "<>"));
//     $criteria->add( new \Criteria("quest_plugin",  'pageGroup', "<>"));
//     $criteria->add( new \Criteria("quest_plugin",  'pageInfo', "<>"));
//     $criteria->add( new \Criteria("quest_plugin",  'pageReponse', "<>"));
    $criteria->add( new \Criteria("quest_isQuestion",  1, "="));
    $count = $questionsHandler->getCount($criteria);
    return $count;
    }
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function countGroups()

    {
    global $questionsHandler;
    
    $criteria = new \CriteriaCompo();
    $criteria->add( new \Criteria("quest_quiz_id",  $this->getVar('quiz_id'), "="));
    $criteria->add( new \Criteria("quest_plugin",  'pageGroup', "="));
    $count = $questionsHandler->getCount($criteria);
    return $count;
    }
 
 /* ******************************
 *  purgerImages
 * *********************** */
 public function purgerImages(){
 global $questionsHandler, $answersHandler, $xoopsDB;
 //return false;
 $nbImgDeleted = 0;
    $quiz_id = $this->getVar('quiz_id');
    // Liste des fichier dans le dossiers des images du quiz
// echo "<hr><pre>quiz : " . print_r($quiz, true) . "</pre><hr>";    

    $tExtImg = array('jpg', 'jpeg','png','gif');
    $folder = $this->getVar('quiz_folderJS');
    $imgPath = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $folder . '/images';
    $imgList = \XoopsLists::getFileListByExtension($imgPath,  $tExtImg);    
    $imgList = array_values($imgList);
    
    //--------------------------------------------------
    $quizTblImg = $this->getImages();

    $imgToDelete = array_diff($imgList, $quizTblImg);
// echoArray($imgList,'');    
// echoArray($quizTblImg,'');    
// echoArray($imgToDelete,'');    
     //----------------------------------------------------------------
    //echo "delete from {$imgPath}<br>";
    //suppression des fichiers physique si le nom n'est pas dans une des table
    foreach($imgToDelete as $key=>$file){
            $fullName = $imgPath . '/' . $file;  
            //echo "delete===>{$fullName}<br>";
            unlink($fullName);
            $nbImgDeleted++;
    }
// exit;   
// echoArray($imgList,'');    
// echoArray($quizTblImg,'');    
// exit;     
    
    return $nbImgDeleted;
 }

 /* ******************************
 *  resizeImages
 * *********************** */
 public function resizeImages(){
 global $questionsHandler, $answersHandler, $xoopsDB, $quizmakerHelper;
 $nbImgResized = 0;
 
    $tExtImg = array('jpg', 'jpeg','png','gif');
    $folder = $this->getVar('quiz_folderJS');
    $imgPath = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $folder . '/images';
    $imgList = \XoopsLists::getFileListByExtension($imgPath,  $tExtImg);    
    //$imgList = array_values($imgList);

    foreach($imgList as $key=>$file){
        $fullName = $imgPath . '/' . $file;  
        //echo "{$fullName}<br>";
        chmod($fullName, 0777);
        \ImageBuilder::redimensionnerEtRemplacer($fullName, $quizmakerHelper->getConfig('resize_img_width')); 
        $nbImgResized++;
    }
    
    return $nbImgResized;
 }
/**************************************************************
 * get_quest_images : renvoie un tableau des images de la question pass en paramètre
 * utilisé pour deplacer ou compié une question dans un autre quiz.
 * @$questIdFrom : Id de la question
 * ************************************************************/
public function getImages(){
global $questionsHandler, $answersHandler;
    $allImg = array();
    $quizId = $this->getVar('quiz_id');
    $quiz = $this->getValuesQuiz();
//     //recherche des images du quiz
    if( $quiz['image'])
        $allImg[] = $quiz['image'];
        
    if( $quiz['background'])
        $allImg[] = $quiz['background'];
        
        
    $criteria = new \Criteria('quest_quiz_id', $quizId, '=');        
    $questIds = $questionsHandler->getIds($criteria);
    //echoArray ($questIds, "zzzzz");  
    
   
    foreach($questIds as $key=>$questID){
        $questObj = $questionsHandler->get($questID);
        $imgArr = $questObj->getImages();
        $allImg = array_merge($allImg, $imgArr);
    }    

    return $allImg;
}

} //================FIN DE LA CLASSE =======================

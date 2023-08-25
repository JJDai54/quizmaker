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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class Answers extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('answer_id', XOBJ_DTYPE_INT);
		$this->initVar('answer_flag', XOBJ_DTYPE_INT);
		$this->initVar('answer_quest_id', XOBJ_DTYPE_INT);
		$this->initVar('answer_proposition', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_caption', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_color', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_background', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_points', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_weight', XOBJ_DTYPE_INT);
		$this->initVar('answer_inputs', XOBJ_DTYPE_INT);
		$this->initVar('answer_image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_image1', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_image2', XOBJ_DTYPE_TXTBOX);
		$this->initVar('answer_group', XOBJ_DTYPE_INT);
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
	public function getNewInsertedIdAnswers()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormAnswers($action = false, $questId = 0)
	{global $questionsHandler;
        //if ($questId > 0) $this->setVar('answer_quest_id', $questId);
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_ANSWERS_ADD) : sprintf(_AM_QUIZMAKER_ANSWERS_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Answers Handler
		$answersHandler = $quizmakerHelper->getHandler('Answers');
        
        $form->addElement(new \XoopsFormHidden('answer_id', $this->getVar('answer_id')));        
		// Form Select answerQuestion_id
		$answerQuestion_idSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTION, 'answer_quest_id', $this->getVar('answer_quest_id'));
        $answerQuestion_idSelect->setExtra('disabled');
        $form->addElement(new \XoopsFormHidden('answer_quest_id', $this->getVar('answer_quest_id')));		
        
        //$answerQuestion_idSelect->addOption('Empty');
		$answerQuestion_idSelect->addOptionArray($questionsHandler->getListKeyName());
		$form->addElement($answerQuestion_idSelect, true);
		// Answers Handler
		$answersHandler = $quizmakerHelper->getHandler('Answers');

        
        
        //--------------------------------------------------------------
		// Form Text answerProposition
		//$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_ANSWERS_PROPOSITION, 'answer_proposition', 50, 255, $this->getVar('answer_proposition') ), true);
        //--------------------------------------------------------------
		// Form Editor DhtmlTextArea answer_proposition
		$editorConfigs = [];
		if ($isAdmin) {
			$editor = $quizmakerHelper->getConfig('editor_admin');
		} else {
			$editor = $quizmakerHelper->getConfig('editor_user');
		}
		$editorConfigs['name'] = 'answer_proposition';
		$editorConfigs['value'] = $this->getVar('answer_proposition', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '100%';
		$editorConfigs['height'] = '400px';
		$editorConfigs['editor'] = $editor;
		$form->addElement(new \XoopsFormEditor( _AM_QUIZMAKER_QUESTIONS_COMMENT1, 'answer_proposition', $editorConfigs) );
        //--------------------------------------------------------------
        
		// Form Text answerCaption
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_CAPTION, 'answer_caption', 80, 255, $this->getVar('answer_caption') ), false);
        
		// Form Text answer_color
        $idChkIsColor = 'answer_isColor';   
        $isColor = ($this->getVar('answer_color')) ? 1 : 0 ;
        $inpColor = new \XoopsFormColorPicker('', 'answer_color', $this->getVar('answer_color') );
        $inpColor->setExtra("onChange=\"document.getElementById('{$idChkIsColor}1').checked=1;\"");
        //$inpColor->setExtra("onChange=\"alert('{$idChkIsColor}');document.getElementById('{$idChkIsColor}1').checked=1;alert('zzzzzzz');\"");
        $inpIsBackround = new \XoopsFormCheckBox('', $idChkIsColor, array($isColor));                        
        $inpIsBackround->addOption(1, ' ');
        $trayColor = new \XoopsFormElementTray(_AM_QUIZMAKER_BACKGROUND, ' ');
		$trayColor->addElement($inpIsBackround, false);
		$trayColor->addElement($inpColor, false);
		$form->addElement($trayColor, false);

		
		// Form Text answer_background
        $idChkIsBackground = 'answer_isBackground';   
        $isBackground = ($this->getVar('answer_background')) ? 1 : 0 ;
        $inpBackground = new \XoopsFormColorPicker('', 'answer_background', $this->getVar('answer_background') );
        $inpBackground->setExtra("onChange=\"document.getElementById('{$idChkIsBackground}1').checked=1;\"");
        //$inpBackground->setExtra("onChange=\"alert('{$idChkIsBackground}');document.getElementById('{$idChkIsBackground}1').checked=1;alert('zzzzzzz');\"");
        $inpIsBackround = new \XoopsFormCheckBox('', $idChkIsBackground, array($isBackground));                        
        $inpIsBackround->addOption(1, ' ');
        $trayBackground = new \XoopsFormElementTray(_AM_QUIZMAKER_BACKGROUND, ' ');
		$trayBackground->addElement($inpIsBackround, false);
		$trayBackground->addElement($inpBackground, false);
		$form->addElement($trayBackground, false);
        
        
       
        // Form Text answerGroup
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_ANSWERS_GROUP, 'answer_group', 50, 255, $this->getVar('answer_group') ), false);
		// Form Text answerPoints
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_ANSWERS_POINTS, 'answer_points', 50, 255, $this->getVar('answer_points') ), false);
		// Form Text answerWeight
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'answer_weight', 50, 255, $this->getVar('answer_weight') ), false);
		// Form Text answer_inputs
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_INPUTS, 'answer_inputs', 50, 255, $this->getVar('answer_inputs') ), false);
		// Form Text answer_image
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_IMAGE, 'answer_image', 50, 255, $this->getVar('answer_image') ), false);
		// Form Text answer_image1
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_IMAGE, 'answer_image1', 50, 255, $this->getVar('answer_image1') ), false);
		// Form Text answer_image2
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_IMAGE, 'answer_image2', 50, 255, $this->getVar('answer_image2') ), false);
		
        
        // To Save
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
	}

	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesAnswers($keys = null, $format = null, $maxDepth = null)
	{
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['caption']     = $this->getVar('answer_caption');
		$ret['color']       = $this->getVar('answer_color');
		$ret['background']  = $this->getVar('answer_background');
		$ret['points']      = $this->getVar('answer_points');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['inputs']      = $this->getVar('answer_inputs');
		$ret['image']       = $this->getVar('answer_image');
		$ret['image1']      = $this->getVar('answer_image1');
		$ret['image2']      = $this->getVar('answer_image2');
		$ret['group']     = $this->getVar('answer_group');
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayAnswers()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}

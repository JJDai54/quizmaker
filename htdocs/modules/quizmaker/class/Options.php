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
 * Class Object Options
 */
class Options extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('opt_id', XOBJ_DTYPE_INT);
		$this->initVar('opt_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('opt_actif', XOBJ_DTYPE_INT);
		$this->initVar('opt_icone', XOBJ_DTYPE_TXTBOX);
		$this->initVar('opt_optionsIhm', XOBJ_DTYPE_INT);
		$this->initVar('opt_optionsDev', XOBJ_DTYPE_INT);
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
	public function getNewInsertedIdOptions()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormOptions($action = false)
	{
    
        global $quizUtility;
        
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_OPTIONS_ADD) : sprintf(_AM_QUIZMAKER_OPTIONS_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        //------------------------------------------------------------------------
		// Form Text opt_name
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_NAME, 'opt_name', 50, 50, $this->getVar('opt_name') ), true);
        
		// Form Text opt_actif
        $form->addElement(new \XoopsFormRadioYN(_AM_QUIZMAKER_ACTIF,'opt_actif', $this->getVar('opt_actif')));
        
		// Form Text opt_icone
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_ICONE, 'opt_icone', 50, 50, $this->getVar('opt_icone') ), true);
        
		// Form CheckBoxBin opt_optionsIhm
        $inpOptionsIhm = new \xoopsFormCheckboxBin(_AM_QUIZMAKER_QUIZ_OPTIONS_IHM . "[{$this->getVar('opt_optionsIhm')}]", 'opt_optionsIhm', $this->getVar('opt_optionsIhm'),1,true);
        $inpOptionsIhm->setDescription(_AM_QUIZMAKER_QUIZ_OPTIONS_IHM_DESC);
        $inpOptionsIhm->addOptionArray(getBinOptionsArr('ihm'));
		$form->addElement($inpOptionsIhm);
        
		// Form CheckBoxBin opt_optionsDev
        $inpOptionsDev = new \xoopsFormCheckboxBin(_AM_QUIZMAKER_QUIZ_OPTIONS_DEV . "[{$this->getVar('opt_optionsDev')}]", 'opt_optionsDev', $this->getVar('opt_optionsDev'),1,true);
        $inpOptionsDev->setDescription(_AM_QUIZMAKER_QUIZ_OPTIONS_DEV_DESC);
        $inpOptionsDev->addOptionArray(getBinOptionsArr('dev'));
		$form->addElement($inpOptionsDev);

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
	public function getValuesOptions($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $optionsHandler;
        
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
        
		$ret['id']          = $this->getVar('opt_id');
		$ret['name']        = $this->getVar('opt_name');
		$ret['actif']       = $this->getVar('opt_actif');
		$ret['icone']       = $this->getVar('opt_icone');
		$ret['optionsIhm']  = $this->getVar('opt_optionsIhm');
		$ret['optionsDev']  = $this->getVar('opt_optionsDev');

        $ret['flags'] = array_merge(getBinOptionsFlagsArr('ihm', $ret['optionsIhm']),
                                    getBinOptionsFlagsArr('dev', $ret['optionsDev']));
//echoArray($ret);
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayOptions()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
    
}

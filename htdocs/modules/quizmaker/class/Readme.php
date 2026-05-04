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
 * Class Object Readme
 */
class Readme extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('readme_id', XOBJ_DTYPE_INT);
		$this->initVar('readme_cat_id', XOBJ_DTYPE_INT);
		$this->initVar('readme_uid', XOBJ_DTYPE_INT);
		$this->initVar('readme_email', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('readme_count', XOBJ_DTYPE_INT);
		$this->initVar('readme_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('readme_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
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
	public function getNewInsertedIdReadme()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormReadme($action = false)
	{
        global $quizUtility;
        
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_README_ADD) : sprintf(_AM_QUIZMAKER_README_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		//$form->setExtra('enctype="multipart/form-data"');
        //------------------------------------------------------------------------
        return $form;

	}


	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesReadme($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $quizHandler;
        
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
        
		$ret = $this->getValues($keys, $format, $maxDepth);
        
		$ret['id']        = $this->getVar('readme_id');
		$ret['cat_id']    = $this->getVar('readme_cat_id');
		$ret['uid']       = $this->getVar('readme_uid');
		$ret['email']     = $this->getVar('readme_email');
		$ret['count']     = $this->getVar('readme_count');
		$ret['creation']  = \JANUS\getDateSql2Str($this->getVar('readme_creation'));
		$ret['update']    = \JANUS\getDateSql2Str($this->getVar('readme_update'));
        
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayReadme()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
}

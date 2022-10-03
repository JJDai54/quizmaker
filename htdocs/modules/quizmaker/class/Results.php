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
 * QuizMaker module for xoops
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
 * Class Object Results
 */
class Results extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('result_id', XOBJ_DTYPE_INT);
		$this->initVar('result_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('result_uid', XOBJ_DTYPE_INT);
		$this->initVar('result_uname', XOBJ_DTYPE_TXTBOX);
		$this->initVar('result_ip', XOBJ_DTYPE_TXTBOX);
		$this->initVar('result_score_achieved', XOBJ_DTYPE_INT);
		$this->initVar('result_score_max', XOBJ_DTYPE_INT);
		$this->initVar('result_score_min', XOBJ_DTYPE_INT);
		$this->initVar('result_answers_total', XOBJ_DTYPE_INT);
		$this->initVar('result_answers_achieved', XOBJ_DTYPE_INT);
		$this->initVar('result_duration', XOBJ_DTYPE_INT);
		$this->initVar('result_note', XOBJ_DTYPE_FLOAT);
		$this->initVar('result_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('result_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
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
	public function getNewInsertedIdResults()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormResults($action = false)
	{
		$quizHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_RESULTS_ADD) : sprintf(_AM_QUIZMAKER_RESULTS_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
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
	public function getValuesResults($keys = null, $format = null, $maxDepth = null)
	{
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']               = $this->getVar('result_id');
		$ret['quiz_id']          = $this->getVar('result_quiz_id');
		$ret['uname']            = $this->getVar('result_uname');
		$ret['uid']              = $this->getVar('result_uid');
		$ret['ip']               = $this->getVar('result_ip');
		$ret['score_achieved']   = $this->getVar('result_score_achieved');
		$ret['score_max']        = $this->getVar('result_score_max');
		$ret['score_min']        = $this->getVar('result_score_min');
		$ret['answers_achieved'] = $this->getVar('result_answers_achieved');
		$ret['answers_total']    = $this->getVar('result_answers_total');
		//$ret['duration']         = $this->getVar('result_duration'); date()
		$ret['duration']         = $this->format_duration($this->getVar('result_duration'));     
		$ret['duration2']        = $this->format_duration($this->getVar('result_duration'),_MA_QUIZMAKER_HOUR,_MA_QUIZMAKER_MINUTES,_MA_QUIZMAKER_SECONDS, ' ');     

		$ret['note']             = $this->getVar('result_note');
		$ret['color']            = str_pad(round($ret['score_achieved'] / $ret['score_max'] * 5 , 0), 3, '0', STR_PAD_LEFT) . '.png';
		$ret['creation']         = \JJD\getDateSql2Str($this->getVar('result_creation'));

		$ret['update']           = $this->getVar('result_update');
		return $ret;
	}
    
    function format_duration($time, $hourlib='h', $minuteLib="m", $secondLib="s", $sep=''){
        $secondes   = floor ( ( ( $time % 86400 ) % 3600 ) % 60 ) ;
        $minutes    = floor ( ( ( $time % 86400 ) % 3600 ) / 60 ) ;
        $hours      = floor ( ( $time % 86400 ) / 3600 ) ;
        $days       = floor ( $time / 86400 ) ;
        if($hours > 0) $ret = "{$hours}{$sep}{$hourlib} {$minutes}{$sep}{$minuteLib} {$secondes}{$sep}{$secondLib}";
        elseif($minutes > 0) $ret = "{$minutes}{$sep}{$minuteLib} {$secondes}{$sep}{$secondLib}";
        else $ret = "{$secondes}{$sep}{$secondLib}";
    
        return $ret;
    }
/*
<!-- fonction de conversion secondes => Jour Heure Minute Seconde - Par MonkeyIsBack le 13 novembre 2009 -->
// Fonction convertir secondes en heures
function Convert_Sec_JHms($Seconde)
{
	// Transformation Secondes en Jour Heure minute seconde
	while ($Seconde >= 86400)
	{$Jour = $Jour + 1; $Seconde = $Seconde - 86400;}
	while ($Seconde >= 3600)
	{$Heure = $Heure + 1; $Seconde = $Seconde - 3600;}
	while ($Seconde >= 60)
	{$Minute = $Minute + 1; $Seconde = $Seconde - 60;}
	
	// Ajout des zéros au cas où l'affichage soit en dessous de 10
	if ($Heure < 10)
	{$Heure = '0'.$Heure;}
	if ($Minute < 10 AND $Minute > 0)
	{$Minute = '0'.$Minute;}
	if ($Minute == 0)
	{$Minute = '00';}
	if ($Seconde < 10)
	{$Seconde = '0'.$Seconde;}
	
	// Retourne une variable la plus petite possible
	if ($Jour > 0)
	{$Convert = $Jour.'j '.$Heure.':'.$Minute.':'.$Seconde; return $Convert;}
	elseif ($Heure > 0)
	{$Convert = $Heure.':'.$Minute.':'.$Seconde; return $Convert;}
	elseif ($Minute > 0)
	{$Convert = $Minute.':'.$Seconde; return $Convert;}
	else
	{$Convert = '00:'.$Seconde; return $Convert;}
}
*/
	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayResults()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}

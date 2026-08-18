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
 * Class Object Cookies
 */
class Cookies extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('cookie_id', XOBJ_DTYPE_INT);
		$this->initVar('cookie_cat_id', XOBJ_DTYPE_INT);
		$this->initVar('cookie_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('cookie_uid', XOBJ_DTYPE_INT);
		$this->initVar('cookie_uname', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cookie_readme', XOBJ_DTYPE_INT);
		$this->initVar('cookie_email', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cookie_ip', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cookie_attempts', XOBJ_DTYPE_INT);
		$this->initVar('cookie_dead_line', XOBJ_DTYPE_TXTBOX);
// 		$this->initVar('cookie_score_achieved', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_score_max', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_score_min', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_answers_total', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_answers_achieved', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_duration', XOBJ_DTYPE_INT);
// 		$this->initVar('cookie_note', XOBJ_DTYPE_FLOAT);
		$this->initVar('cookie_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('cookie_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
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
	public function getNewInsertedIdCookies()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormCookies($action = false)
	{global $redirectURL;
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
//			$action = $_SERVER['REQUEST_URI'];
        }
		  $action = "{$redirectURL}&op=save";
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		// Title
        
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_COOKIES_ADD) : sprintf(_AM_QUIZMAKER_COOKIES_EDIT);

		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        //-----------------------------------------------------------------
        $form->addElement(new \XoopsFormHidden('cookie_id', $this->getVar('cookie_id')));
        $form->addElement(new \XoopsFormHidden('cookie_cat_id', $this->getVar('cookie_cat_id')));
        $form->addElement(new \XoopsFormHidden('cookie_quiz_id', $this->getVar('cookie_quiz_id')));

         if($isAdmin){
    		// Form Text cookie_uid
            $name = 'cookie_uid';
    		$form->addElement(new \XoopsFormText(_AM_QUIZMAKER_UID,$name,12,12, $this->getVar($name)));

    		// Form Text cookie_uname
            $name = 'cookie_uname';
    		$form->addElement(new \XoopsFormText(_AM_QUIZMAKER_NAME,$name,50,50, $this->getVar($name)));

    		// Form Text cookie_email
            $name = 'cookie_email';
    		$form->addElement(new \XoopsFormText(_AM_QUIZMAKER_EMAIL,$name,80,80, $this->getVar($name)));
         }else{
    		// Form Text cookie_uid
            $name = 'cookie_uid';
    		$form->addElement(new \XoopsFormLabel(_AM_QUIZMAKER_UID , $this->getVar($name) ));
    		$form->addElement(new \XoopsFormHidden($name, $this->getVar($name)));       
            
    		// Form Text cookie_uname
            $name = 'cookie_uname';
    		$form->addElement(new \XoopsFormLabel( _AM_QUIZMAKER_NAME, $this->getVar($name) ));
    		$form->addElement(new \XoopsFormHidden($name, $this->getVar($name)));    
                    
    		// Form Text cookie_email
            $name = 'cookie_email';
    		$form->addElement(new \XoopsFormLabel( _AM_QUIZMAKER_EMAIL, $this->getVar($name) ));
    		$form->addElement(new \XoopsFormHidden($name, $this->getVar($name)));            
         }           
        
		$form->addElement(new \XoopsFormLabel( _AM_QUIZMAKER_IP, $this->getVar('cookie_ip') ));
        
		// Form cookie_readme
        //$inpReadme= new \XoopsFormText(_AM_QUIZMAKER_README_TEXT, 'cookie_readme', 50, 50, $this->getVar('cookie_readme'));
        $inpReadme = new \XoopsFormRadioYN(_AM_QUIZMAKER_README_TEXT, 'cookie_readme', $this->getVar('cookie_readme'));        
		$form->addElement($inpReadme);
       
        // Form cookie_attempts
        $inpAttempts = new \XoopsFormNumber(_AM_QUIZMAKER_ATTEMPTS, 'cookie_attempts', 8, 8, $this->getVar('cookie_attempts'));
        $inpAttempts->setMinMax(0, 500);
		$form->addElement($inpAttempts);
        
        // Form Text  cookie_dead_line        
//      $inpDeadLine= new \XoopsFormText(_AM_QUIZMAKER_DEAD_LINE, 'cookie_dead_line', 50, 50, $this->getVar('cookie_dead_line'));        
// 		$form->addElement($inpDeadLine);
        
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
	public function getValuesCookies($keys = null, $format = null, $maxDepth = null)
	{
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']               = $this->getVar('cookie_id');
		$ret['cat_id']           = $this->getVar('cookie_cat_id');
		$ret['quiz_id']          = $this->getVar('cookie_quiz_id');
		$ret['uname']            = $this->getVar('cookie_uname');
		$ret['uid']              = $this->getVar('cookie_uid');
		$ret['uname']            = $this->getVar('cookie_uname');
		$ret['readme']           = $this->getVar('cookie_readme');
		$ret['email']            = $this->getVar('cookie_email');
		$ret['ip']               = $this->getVar('cookie_ip');
		$ret['attempts']         = $this->getVar('cookie_attempts');
		$ret['dead_line']        = $this->getVar('cookie_dead_line');
		$ret['dead_linef']       = date('Y-m-d H:i:s', $ret['dead_line'] );
// 		$ret['score_achieved']   = $this->getVar('cookie_score_achieved');
// 		$ret['score_max']        = $this->getVar('cookie_score_max');
// 		$ret['score_min']        = $this->getVar('cookie_score_min');
// 		$ret['answers_achieved'] = $this->getVar('cookie_answers_achieved');
// 		$ret['answers_total']    = $this->getVar('cookie_answers_total');
// 		//$ret['duration']         = $this->getVar('cookie_duration'); date()
// 		$ret['duration']         = $this->format_duration($this->getVar('cookie_duration'));     
// 		$ret['duration2']        = $this->format_duration($this->getVar('cookie_duration'),_CO_QUIZMAKER_HOUR,_CO_QUIZMAKER_MINUTES,_CO_QUIZMAKER_SECONDS, ' ');     

		$ret['note']             = $this->getVar('cookie_note');
        
        //-------------------------------------------------------
        //si $ret['score_max'] == 0 revoir les points attribués aux propositions des questions
        //il n'est probablement renseigné, laissé à o pour toutes les propositions de toutes les questions
        if($ret['score_max'] == 0) $ret['score_max'] = 1; 
        //-------------------------------------------------------
        
        $colorNote = round($ret['score_achieved'] / $ret['score_max'] * 5 , 0);
//echo "<hr>{$ret['score_achieved'] } - {$ret['score_max']} - {$colorNote}<hr>";
        if ($colorNote < 0)  $colorNote = 0;
        //if ($colorNote > 4)  $colorNote = 4;
		$ret['color']            = str_pad($colorNote, 3, '0', STR_PAD_LEFT) . '.png';
		$ret['creation']         = \JANUS\getDateSql2Str($this->getVar('cookie_creation'));
		$ret['update']           = \JANUS\getDateSql2Str($this->getVar('cookie_update'));
        
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
	public function toArrayCookies()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}

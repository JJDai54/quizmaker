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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */
use XoopsModules\Quizmaker;
include_once QUIZMAKER_PATH . "/class/Type_question.php";
include_once QUIZMAKER_PATH . "/plugins/listboxSortItems/slide_listboxSortItems.php";
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_comboboxSortItems extends  \slide_listboxSortItems
{
                    
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{   
        $this->__init("comboboxSortItems", 0, "combobox");
        $this->optionsDefaults = ['ordre'=>'N', 'title'=>''];
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
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           

      $name = 'ordre';  
      $inputOrder = new \XoopsFormRadio($caption, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrder->addOption("N", _AM_QUIZMAKER_ONLY_ORDER_NAT);            
      $inputOrder->addOption("R", _AM_QUIZMAKER_ALLOW_ALL_ORDER);            
      $trayOptions->addElement($inputOrder);     
      
      $name = 'title';  
      $inpTitle = new \XoopsFormText(_AM_QUIZMAKER_SLIDE_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
      $trayOptions->addElement($inpTitle);     

      return $trayOptions;
    }



} // fin de la classe


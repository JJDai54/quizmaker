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
include_once QUIZMAKER_PATH_MODULE . "/plugins/pageInfo/pageInfo.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');


/**
 * Class Object Answers
 */
class Plugin_pageAnswer extends  Plugin_pageInfo 
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct();
        $pluginName = "pageAnswer";
        $this->pluginName = $pluginName;
        $this->type = $pluginName;
        $this->typeForm = QUIZMAKER_TYPE_FORM_ANSWER;
        $this->typeForm_lib = _CO_QUIZMAKER_FORM_ANSWER;
                
        $this->isParent = false; 
        $this->isQuestion = 0; 
        $this->canDelete = true;  

        $this->pathArr = $this->getPluginPath();
//echo "<hr><pre>" . print_r($this->pathArr,true) . "</pre><hr>";
        $this->name        = constant($this->prefix . strToUpper($pluginName));
        $this->description = constant($this->prefix . strToUpper($pluginName) . '_DESC');
        $this->consigne    = constant($this->prefix . strToUpper($pluginName) . '_CONSIGNE');
 
        $this->category = "page"; //first_last
        $this->categoryLib = constant(QUIZMAKER_PREFIX_CAT . strToUpper($this->category));
        //$this->categoryWeight = constant('QUIZMAKER_PLUGIN_CAT_' . strToUpper($cat));
    }

} // ----------------- FIN DE LA classe ------------------

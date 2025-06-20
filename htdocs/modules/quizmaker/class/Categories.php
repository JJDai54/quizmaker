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
 * Class Object Categories
 */
class Categories extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('cat_id', XOBJ_DTYPE_INT);
		$this->initVar('cat_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cat_actif', XOBJ_DTYPE_INT);
		$this->initVar('cat_description', XOBJ_DTYPE_OTHER);
		$this->initVar('cat_theme', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cat_weight', XOBJ_DTYPE_INT);
		$this->initVar('cat_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('cat_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
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
	public function getNewInsertedIdCategories()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormCategories($action = false)
	{
        global $quizUtility;
        
		$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_CATEGORIES_ADD) : sprintf(_AM_QUIZMAKER_CATEGORIES_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        //------------------------------------------------------------------------
		// Form Text catName
        $name = $this->getVar('cat_name');
        $inpName = new \XoopsFormText( _AM_QUIZMAKER_NAME, 'cat_name', 50, 255, $name);
        if($name == QUIZMAKER_CAT_NAME_FOR_EXEMPLE){
          $inpName->setExtra("disabled");
		  $form->addElement($inpName, false);
          $form->addElement(new \XoopsFormHidden('cat_name', $name));
        }else{
		  $form->addElement($inpName, true);
        }
        
        //cat_actif
		$form->addElement(new \XoopsFormRadioYN(_AM_QUIZMAKER_ACTIF, 'cat_actif', $this->getVar('cat_actif')));
        
		// Form Editor DhtmlTextArea catDescription
		$editorConfigs = [];
		$editor = $quizmakerHelper->getConfig('quizmaker_editor');
		$editorConfigs['name'] = 'cat_description';
		$editorConfigs['value'] = $this->getVar('cat_description', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '100%';
		$editorConfigs['height'] = '400px';
		$editorConfigs['editor'] = $editor;
		$form->addElement(new \XoopsFormEditor( _AM_QUIZMAKER_DESCRIPTION, 'cat_description', $editorConfigs) );
		
        // Categories Handler
		$categoriesHandler = $quizmakerHelper->getHandler('Categories');
		
        /* todo - champ à virer, pas utile de le garder
        */
        // Form Select catTheme
		$inpTheme = new \XoopsFormSelect( _AM_QUIZMAKER_THEME, 'cat_theme', $this->getVar('cat_theme'));
        $inpTheme->setdescription(_AM_QUIZMAKER_THEME_DEFAULT_CAT);
        //$inpTheme->addOptionArray($quizUtility::get_css_color());
        $inpTheme->addOptionArray( \JANUS\get_css_color());
		$form->addElement($inpTheme );

		// Form Text cat_weight
        //$cat_weight = $this->isNew() ? '0' : $this->getVar('cat_weight');
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'cat_weight', 20, 50,  $this->getVar('cat_weight')) );
		
/*
        // Form Text Select questTimer
        $inpTimer = new \XoopsFormText( _AM_QUIZMAKER_TIMER, 'quest_timer', 20, 50,  $this->getVar('quest_timer'));
        $inpTimer->setDescription(_AM_QUIZMAKER_TIMER_DESC);
		$form->addElement($inpTimer);

        // Form Text Date Select catCreation
		$catCreation = $this->isNew() ? 0 : $this->getVar('cat_creation');
		$form->addElement(new \XoopsFormDateTime( _AM_QUIZMAKER_CREATION, 'cat_creation', '', $catCreation ) );
		
        // Form Text Date Select catUpdate
		$catUpdate = $this->isNew() ? 0 : $this->getVar('cat_update');
		$form->addElement(new \XoopsFormDateTime( _AM_QUIZMAKER_UPDATE, 'cat_update', '', $catUpdate ) );
*/		
		
        // Permissions
		$memberHandler = xoops_getHandler('member');
		$groupList = $memberHandler->getGroupList();
		$fullList[] = array_keys($groupList);
        
		$grouppermHandler = xoops_getHandler('groupperm');
//$groupsIdsEdit = getCheckboxByGroup($label, $name, $itemId, $fullList, $permName, $isNew)
//echoArray($fullList);
//echoArray($groupList);

        $catId = $this->getVar('cat_id');
        $clPerms = new \JanusPermissions();

        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_VIEW_CATS,        'view_cats',        $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_EDIT_QUIZ,        'edit_quiz',        $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_CREATE_QUIZ,      'create_quiz',      $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_DELETE_QUIZ,      'delete_quiz',      $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_IMPORT_QUIZ,      'import_quiz',      $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_IMPORTQUEST_QUIZ, 'importquest_quiz', $catId, $this->isNew()));
        $form->addElement($clPerms->getCheckboxByGroup2(_AM_QUIZMAKER_PERMISSIONS_EXPORT_QUIZ,      'export_quiz',      $catId, $this->isNew()));


/*
		// To Approve
		$groupsCanApproveCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanApproveCheckbox);
		// To Submit
		$groupsCanSubmitCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanSubmitCheckbox);
		// To View
		$groupsCanViewCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanViewCheckbox);
*/        
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
	public function getValuesCategories($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $quizHandler;
        $ret = $this->getValuesCategoriesLight($keys, $format, $maxDepth);
        if(!$quizHandler){
    		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
            $quizHandler = $quizmakerHelper->getHandler('Quiz');
        } 
            
        $criteria = new \Criteria("quiz_cat_id", $ret['id'], '=');
        $ret['nbQuiz'] = $quizHandler->getCount($criteria);
		return $ret;
	}
	public function getValuesCategoriesLight($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $quizHandler;
        
		$quizmakerHelper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
        
		$ret['id']                = $this->getVar('cat_id');
		$ret['name']              = $this->getVar('cat_name');
		$ret['actif']             = $this->getVar('cat_actif');
		$ret['description']       = $this->getVar('cat_description', 'e');
		$editorMaxchar = $quizmakerHelper->getConfig('editor_maxchar');
		$ret['description_short'] = $utility::truncateHtml($ret['description'], $editorMaxchar);
		$ret['theme']             = $this->getVar('cat_theme');
		$ret['weight']            = $this->getVar('cat_weight');
		$ret['creation']          = \JANUS\getDateSql2Str($this->getVar('cat_creation'));
		$ret['update']            = \JANUS\getDateSql2Str($this->getVar('cat_update'));
        
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayCategories()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
/* ******************************
 *  
 * *********************** */
    public function getChildren($tQuizIdsAllowed = null)
    {
    global $xoopsDB, $quizHandler;

        $criteria = new \CriteriaCompo(new \Criteria('quiz_cat_id', $this->getVar('cat_id', '=')));
        if(!is_null($tQuizIdsAllowed)){
            $criteria = new \CriteriaCompo(new \Criteria('quiz_id', "(" . implode(',', $tQuizIdsAllowed) . ")", "IN"), 'AND');
        }
        
// 		$criteria->setStart( $start );
// 		$criteria->setLimit( $limit );
// 		$criteria->setSort( 'quiz_weight,quiz_name' );
		$criteria->setSort( 'quiz_name' );
		$criteria->setOrder( 'ASC' );
        
        $children = $quizHandler->getAll($criteria);
        
        $ret = array();
        foreach ($children AS $child){
            $ret[] = $child->getValuesQuiz();
        }

       return $ret;         

    }
   
}

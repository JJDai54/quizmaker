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
		$this->initVar('quest_flag', XOBJ_DTYPE_INT);
		$this->initVar('quest_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_type_question', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_type_form', XOBJ_DTYPE_INT);
		$this->initVar('quest_question', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_options', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_comment1', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_explanation', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_minReponse', XOBJ_DTYPE_INT);
		$this->initVar('quest_numbering', XOBJ_DTYPE_INT);
		$this->initVar('quest_shuffleAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quest_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quest_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quest_weight', XOBJ_DTYPE_INT);
		$this->initVar('quest_timer', XOBJ_DTYPE_INT);
//		$this->initVar('quest_isQuestion', XOBJ_DTYPE_INT);
		$this->initVar('quest_visible', XOBJ_DTYPE_INT);
		$this->initVar('quest_actif', XOBJ_DTYPE_INT);
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
	public function getNewInsertedIdQuestions()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
 	public function getFormQuestions($action = false, $sender="")
 	{
        global $quizHandler, $utility, $type_questionHandler;
        //---------------------------------------------- 
		$helper = \XoopsModules\Quizmaker\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}else{
            $h = strpos( $_SERVER['REQUEST_URI'], "?");
			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
			//$action = "questions.php";
			//$action = "modules/quizmaker/admin/questions.php";
        }
//         echo "<br>Action : {$action}<br>";
// 		exit;
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
        //=================================================
        // recupe de la classe du type de question
        $clTypeQuestion = $this->getTypeQuestion();
        
        //===========================================================        
		// Title
		$title = $this->isNew() ? sprintf(_AM_QUIZMAKER_QUESTIONS_ADD) : sprintf(_AM_QUIZMAKER_QUESTIONS_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Questions Handler
        //----------------------------------------------------------
		// Questions Handler
		$questionsHandler = $helper->getHandler('Questions');
		// Form Select questQuiz_id
		$questQuiz_idSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
		$questQuiz_idSelect->addOption('Empty');
		$questQuiz_idSelect->addOptionArray($quizHandler->getListKeyName());
        $typeQuestion = $this->getVar('quest_type_question');
        //----------------------------------------------------------
        /*
		// Form Select quest_parent_id
		$inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_PARENT, 'quest_parent_id', $this->getVar('quest_parent_id'));
		//$inpParent->addOption('Empty');
		$inpParent->addOptionArray($questionsHandler->getParents());
        $form->addElement($inpParent);
        */
        //----------------------------------------------------------
		// Form Select questType_question
		$questType_questionSelect = new \XoopsFormSelect( _AM_QUIZMAKER_QUESTIONS_TYPE_QUESTION, 'quest_type_question', $typeQuestion);
		$questType_questionSelect->addOption('Empty');
		//$questType_questionSelect->addOptionArray($questionsHandler->getListKeyName());
		$questType_questionSelect->addOptionArray($type_questionHandler->getListKeyName());
        $form->addElement(new \XoopsFormHidden('sender', $sender));
        
        //----------------------------------------------------------
        $saisissable = true;
        if (!$saisissable) //autorise la selection de quiz_id et type_question
        {
        $questQuiz_idSelect->setExtra("disabled");
        $form->addElement(new \XoopsFormHidden('quest_quiz_id', $this->getVar('quest_quiz_id')));
        
          $questType_questionSelect->setExtra("disabled");
          $form->addElement(new \XoopsFormHidden('quest_type_question', $typeQuestion));
        }        
// 		$form->addElement($questQuiz_idSelect, true);
// 		$form->addElement($questType_questionSelect );

        $trayParent = new \XoopsFormElementTray  ('', $delimeter = '===>');  
        $trayParent->addElement($questQuiz_idSelect);
        $trayParent->addElement($questType_questionSelect);
        
        // --- Ajout de la copie d'écran du slide
        $url =  QUIZMAKER_MODELES_IMG . "/slide_" . $typeQuestion . '-00.jpg';
        $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:40px" />
            </a>

        ___IMG___;
        $inpImg = new \XoopsFormLabel  ('', $img);  
        $inpImg->setExtra("class='highslide-gallery'");
\JJD\include_highslide();       
        $trayParent->addElement($inpImg);
        //--------------------------------
		$form->addElement($trayParent);
        //----------------------------------------------------------
        /*
        */

		// Form Select questType_form
        
		// Form Select quest_parent_id
        if($clTypeQuestion->isQuestion()){
            $tParent = $questionsHandler->getParents($this->getVar('quest_quiz_id'), false);
            $parentId = ($this->getVar('quest_parent_id') == 0) ? array_keys($tParent)[0] : $this->getVar('quest_parent_id');
            $inpParent = new \XoopsFormSelect( _AM_QUIZMAKER_PARENT, 'quest_parent_id', $parentId);
            //$inpParent->addOption('Empty');
            $inpParent->addOptionArray($tParent);
            $form->addElement($inpParent);
        }else{
            $typeForm = $this->getVar('quest_type_form');
            if ($typeForm == 0) $typeForm = 1;
    		$tForms = array(1 => _CO_QUIZMAKER_FORM_INTRO,
                            2 => _CO_QUIZMAKER_FORM_ENCART,
                            3 => _CO_QUIZMAKER_FORM_RESULT);
            $inpTypeForm = new \XoopsFormSelect(_AM_QUIZMAKER_FORM_TYPE , 'quest_type_form', $typeForm);
    		$inpTypeForm->setDescription(_AM_QUIZMAKER_FORM_TYPE_DESC);
    		$inpTypeForm->addOptionArray($tForms);
            $form->addElement($inpTypeForm);
        }
        //----------------------------------------------------------
        // Form  quest_isQuestion
        /*
		$inpIsQuestion = new \XoopsFormRadioYN(_AM_QUIZMAKER_ISQUESTION, 'quest_isQuestion', $this->getVar('quest_isQuestion'));
        $inpIsQuestion->setDescription(_AM_QUIZMAKER_ISQUESTION_DESC);
        */
        $form->addElement(new \XoopsFormHidden('quest_isQuestion', $this->getVar('quest_isQuestion')));
		$inpIsQuestion = new \XoopsFormRadioYN(_AM_QUIZMAKER_ISQUESTION, 'quest_isQuestion', $clTypeQuestion->isQuestion());
        $inpIsQuestion->setDescription(_AM_QUIZMAKER_ISQUESTION_DESC);
        $inpIsQuestion->setExtra('disabled');
        $form->addElement($inpIsQuestion);
        //$form->addElement(new \XoopsFormHidden('quest_isQuestion', $isQuestion));
        
        
		// Form Text questQuestion
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_QUESTION, 'quest_question', 120, 255, $this->getVar('quest_question') ), true);
		
        //$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_OPTIONS, 'quest_options', 50, 255, $this->getVar('quest_options') ), false);
        if ($clTypeQuestion) $inpOptions = $clTypeQuestion->getformOptions(_AM_QUIZMAKER_OPTIONS,'quest_options',$this->getVar('quest_options'));
        $form->addElement($inpOptions, false);
        //--------------------------------------------------------------
		// Form Editor DhtmlTextArea questComment1
		$editorConfigs = [];
		if ($isAdmin) {
			$editor = $helper->getConfig('editor_admin');
		} else {
			$editor = $helper->getConfig('editor_user');
		}
		$editorConfigs['name'] = 'quest_comment1';
		$editorConfigs['value'] = $this->getVar('quest_comment1', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '100%';
		$editorConfigs['height'] = '400px';
		$editorConfigs['editor'] = $editor;
		$inpComment1 = new \XoopsFormEditor( _AM_QUIZMAKER_QUESTIONS_COMMENT1, 'quest_comment1', $editorConfigs);
        $inpComment1->setDescription(_AM_QUIZMAKER_QUESTIONS_COMMENT1_DESC);
		$form->addElement($inpComment1);
        //--------------------------------------------------------------
		// Form Editor DhtmlTextArea quest_explanation
		$editorConfigs = [];
		if ($isAdmin) {
			$editor = $helper->getConfig('editor_admin');
		} else {
			$editor = $helper->getConfig('editor_user');
		}
		$editorConfigs['name'] = 'quest_explanation';
		$editorConfigs['value'] = $this->getVar('quest_explanation', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '70%';
		$editorConfigs['height'] = '200px';
		$editorConfigs['editor'] = $editor;
        $inpExplanation = new \XoopsFormEditor( _AM_QUIZMAKER_EXPLANATION, 'quest_explanation', $editorConfigs) ;
        $inpExplanation->setDescription(_AM_QUIZMAKER_EXPLANATION_DESC);
		$form->addElement($inpExplanation);
        
        /* ***** Option uniquement pour les questions ***** */
        if($clTypeQuestion->isQuestion()){
		// Form Text questMinReponse
		$questMinReponse = $this->isNew() ? '0' : $this->getVar('quest_minReponse');
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_QUESTIONS_MINREPONSE, 'quest_minReponse', 20, 150, $questMinReponse ) );
        
		// Form Text questNumbering
        //----------------------------------------------------------
        $tOptNumbering = array(_AM_QUIZMAKER_NUMERIQUE,_AM_QUIZMAKER_UPPERCASE,_AM_QUIZMAKER_LOWERCASE);
		$inpNumbering = new \XoopsFormSelect(_AM_QUIZMAKER_NUMBERING , 'quest_numbering', $this->getVar('quest_numbering'));
		$inpNumbering->addOptionArray($tOptNumbering);
		$form->addElement($inpNumbering);

        
        //----------------------------------------------------------
        $inpShuffleAns = new \XoopsFormRadioYN(_AM_QUIZMAKER_SHUFFLE_ANS , 'quest_shuffleAnswers', $this->getVar('quest_shuffleAnswers'));        
		$inpShuffleAns->setDescription(_AM_QUIZMAKER_SHUFFLE_ANS_DESC);
		$form->addElement($inpShuffleAns);


        }else{
        }
        
	
		// Form Text questWeight
        //$questWeight = $this->isNew() ? '0' : $this->getVar('quest_weight');
		$form->addElement(new \XoopsFormText( _AM_QUIZMAKER_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight')) );
		
        // Form Text Select questTimer
        $inpTimer = new \XoopsFormText( _AM_QUIZMAKER_TIMER, 'quest_timer', 20, 50,  $this->getVar('quest_timer'));
        $inpTimer->setDescription(_AM_QUIZMAKER_TIMER_DESC);
		$form->addElement($inpTimer);
		
        
        
                
        // Form quest_visible
		$inpVisible = new \XoopsFormRadioYN(_AM_QUIZMAKER_VISIBLE, 'quest_visible', $this->getVar('quest_visible'));
        $inpVisible->setDescription(_AM_QUIZMAKER_VISIBLE_DESC);
        $form->addElement($inpVisible);
        
        // Form quest_actif
		$inpActif = new \XoopsFormRadioYN(_AM_QUIZMAKER_ACTIF, 'quest_visible', $this->getVar('quest_actif'));
        $inpActif->setDescription(_AM_QUIZMAKER_ACTIF_DESC);
        $form->addElement($inpActif);
        
		// Form Text Date Select questCreation
// 		$questCreation = $this->isNew() ? 0 : $this->getVar('quest_creation');
// 		$form->addElement(new \XoopsFormDateTime( _AM_QUIZMAKER_QUESTIONS_CREATION, 'quest_creation', '', $questCreation ) );
//echo "<hr>===> getForm -> quest_id ===> " . $this->getVar('quest_id') . "<hr>";        
        //================================================
        //ajout des options de réponnses
        //$titleOptions = new \XoopsFormLabel(null,'Liste des options');
        $form->insertBreak("<div style='background:black;color:white;'><center>" . _AM_QUIZMAKER_PROPOSITIONS_ANSWERS . "</center></div>");
        if ($clTypeQuestion)  $form->addElement($clTypeQuestion->getForm($this->getVar('quest_id')));
        //================================================
		// To Save
        $form->insertBreak("<div style='background:black;color:white;'><center>-----</center></div>");
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
	public function getValuesQuestions($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility;
        $clTypeQuestion = $this->getTypeQuestion();
        
		$helper  = \XoopsModules\Quizmaker\Helper::getInstance();
		$utility = new \XoopsModules\Quizmaker\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']             = $this->getVar('quest_id');
		$ret['parent_id']      = $this->getVar('quest_parent_id');
		$ret['quiz_id']        = $this->getVar('quest_quiz_id');
		$ret['type_question']  = $this->getVar('quest_type_question');
		$ret['type_form']  = $this->getVar('quest_type_form');
		$ret['type_form_lib']  = array(_CO_QUIZMAKER_FORM_QUESTION,_CO_QUIZMAKER_FORM_INTRO,_CO_QUIZMAKER_FORM_ENCART,_CO_QUIZMAKER_FORM_RESULT)[$this->getVar('quest_type_form')];
		$ret['question']       = $this->getVar('quest_question');
		$editorMaxchar = $helper->getConfig('editor_maxchar');
		$ret['options']        = $this->getVar('quest_options');
		$ret['comment1']       = $this->getVar('quest_comment1', 'e');
		$ret['comment1_short'] = $utility::truncateHtml($ret['comment1'], $editorMaxchar);
 		$ret['explanation']    = $this->getVar('quest_explanation', 'e');
 		$ret['explanation_short'] = $utility::truncateHtml($ret['explanation'], $editorMaxchar);
		$ret['minReponse']     = $this->getVar('quest_minReponse');
		$ret['numbering']      = $this->getVar('quest_numbering');
		$ret['shuffleAnswers'] = $this->getVar('quest_shuffleAnswers');
		$ret['creation']       = \JJD\getDateSql2Str($this->getVar('quest_creation'));
		$ret['update']         = \JJD\getDateSql2Str($this->getVar('quest_update'));
        
		$ret['weight']         = $this->getVar('quest_weight');
		$ret['timer']          = $this->getVar('quest_timer');
		//$ret['isQuestion']     = $this->getVar('quest_isQuestion');
		$ret['isQuestion']     = $clTypeQuestion->isQuestion();
		$ret['visible']        = $this->getVar('quest_visible');
		$ret['actif']        = $this->getVar('quest_actif');
		return $ret;
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
 *  getTypeQuestion : renvoie la class du type de question
 * @return : classe héritée du type de question
 * *********************** */
    public function getTypeQuestion($default='checkbox')
    {
        global $quizUtility, $type_questionHandler;
        // recupe de la classe du type de question
        $typeQuestion = $this->getVar('quest_type_question');
        if ($typeQuestion == '') $typeQuestion = $default;
        return $type_questionHandler->getTypeQuestion($typeQuestion);
/*
        $clsName = "slide_" . $typeQuestion;   
        $f = QUIZMAKER_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
        if (file_exists($f)){
            include_once($f);    
            $cls = new $clsName; 
        }else{$cls = null;}
        return $cls;
*/        
    }
        
/* ********************************************
*
*********************************************** */
  public function getSolutions(){
  //global $answersHandler;
    $typeQuestion = $this->getTypeQuestion(null);
    if (is_null($typeQuestion)) return "Problemo";

    return $typeQuestion->getSolutions($this->getVar('quest_id'), $this);

     }
    
}

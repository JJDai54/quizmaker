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
include_once QUIZMAKER_PATH . "/class/type_question/slide_pageBegin.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_pageGroup extends XoopsModules\Quizmaker\Type_question //slide_pageBegin
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("pageGroup", 0, 10);
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
 	public function isQuestion()
 	{
        return false;
    }

/* ************************************************
*
* *************************************************/
 	public function getForm($questId)
 	{
        global $utility, $quizUtility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        $this->maxPropositions = 2;
        //-------------------------------------------------
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";

        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>'); //, $name = '');
        
        for($i = 0; $i < $this->maxPropositions ; $i++){
            
            $name = $this->getName($i,'proposition');
            $proposition = (isset($answers[$i])) ? $answers[$i]->getVar('answer_proposition', 'e') : '';
            //$inpPropo = $this->getformTextarea(_AM_QUIZMAKER_SLIDE_TEXTES, $name, $proposition);        
            //$inpPropo = $this->getformAdmin(_AM_QUIZMAKER_SLIDE_TEXTES, $name, $proposition);      
            $inpPropo  = $quizUtility->getEditor2(_AM_QUIZMAKER_SLIDE_TEXTES, $name, $proposition,  null , null, null);
              
            $trayAllAns->addElement($inpPropo);      
        }

        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}

/* ************************************************
*
* *************************************************/
 	public function saveAnswers($questId, $answers)
 	{
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
       //echo "===>nb request : " . count($answers) . "<br>";
       foreach ($answers as $key=>$v){
            //if($v['proposition'] === '')continue;
            
            //echo "===>proposition = {$v['proposition']} - points = {$v['points']}<br>";
			$ansObj = $answersHandler->create();
    		$ansObj->setVar('answer_quest_id', $questId);
            
    		$ansObj->setVar('answer_proposition', $v['proposition']);
    		$ansObj->setVar('answer_points', 0);
    		$ansObj->setVar('answer_weight', $key*10);
            
    		$ansObj->setVar('answer_caption', '');
    		$ansObj->setVar('answer_inputs', 1);

            $ret = $answersHandler->insert($ansObj);
      }
//     exit ("<hr>===>saveAnswers<hr>");
    }

}

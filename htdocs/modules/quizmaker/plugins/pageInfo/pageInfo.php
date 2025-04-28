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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class Plugin_pageInfo extends XoopsModules\Quizmaker\Plugins 
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("pageInfo", 0, "page");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');

        $this->optionsDefaults = [];
        $this->hasImageMain = true;

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
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);     
  
      //--------------------------------------------------------------------   

      $trayOptions ->addElementOption(new XoopsFormLabel('', _AM_QUIZMAKER_NO_OPTIONS_));     
     
      //--------------------------------------------------------------------           
      
      return $trayOptions;
    }

/* ************************************************
*
* *************************************************/
 	public function getForm($questId, $quizId)
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
            //$inpPropo = $this->getformTextarea(_AM_QUIZMAKER_PLUGIN_TEXTES, $name, $proposition);        
            //$inpPropo = $this->getformAdmin(_AM_QUIZMAKER_PLUGIN_TEXTES, $name, $proposition);      
            $inpPropo  = $quizUtility->getEditor2(_AM_QUIZMAKER_PLUGIN_TEXTES, $name, $proposition,  null , null, null);
              
            $trayAllAns->addElement($inpPropo);      
        }

        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}

/* ************************************************
*
* *************************************************/
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler;
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
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $quizHandler, $questionsHandler;
 
    $question = $questionsHandler->get($questId);
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <hr><pre>" . print_r($quizId,true) . "</pre><hr>");
    //recherche du dossier upload du quiz
    $quiz = $quizHandler->get($quizId,"quiz_folderJS");
    $path =  QUIZMAKER_URL_UPLOAD_QUIZ . "/" . $quiz->getVar('quiz_folderJS') . "/images";
    $img = $path . '/' . $question->getVar('quest_image');
    $tplImg = "<img src='{$img}' alt='' title='' style='height:250px;>";
    //$ret['answers'] = implode("\n", $html);
    
    $ret['answers'] = $tplImg;
    $ret['scoreMin'] = 0;
    $ret['scoreMax'] = 0;
    //echoArray($ret);
    return $ret;
}

} // ----------------- FIN DE LA CLASSE ------------------

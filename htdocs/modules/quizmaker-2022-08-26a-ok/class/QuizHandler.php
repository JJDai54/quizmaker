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


/**
 * Class Object Handler Quiz
 */
class QuizHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_quiz', Quiz::class, 'quiz_id', 'quiz_cat_id');
	}

	/**
	 * @param bool $isNew
	 *
	 * @return object
	 */
	public function create($isNew = true)
	{
		return parent::create($isNew);
	}

	/**
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function get($i = null, $fields = null)
	{
		return parent::get($i, $fields);
	}

	/**
	 * get inserted id
	 *
	 * @param null
	 * @return integer reference to the {@link Get} object
	 */
	public function getInsertId()
	{
		return $this->db->getInsertId();
	}

	/**
	 * Get Count Quiz in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountQuiz($criteria=null, $start = 0, $limit = 0, $sort = 'quiz_id ASC, quiz_cat_id', $order = 'ASC')
	{
        $crCountQuiz  = ($criteria) ? $criteria: new \CriteriaCompo();
		$crCountQuiz = $this->getQuizCriteria($crCountQuiz, $start, $limit, $sort, $order);
		return parent::getCount($crCountQuiz);
	}

	/**
	 * Get All Quiz in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllQuiz($criteria=null, $start = 0, $limit = 0, $sort = 'quiz_id ASC, quiz_cat_id', $order = 'ASC')
	{
        $crAllQuiz  = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllQuiz = $this->getQuizCriteria($crAllQuiz, $start, $limit, $sort, $order);
		return parent::getAll($crAllQuiz);
	}

	/**
	 * Get Criteria Quiz
	 * @param        $crQuiz
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getQuizCriteria($crQuiz, $start, $limit, $sort, $order)
	{
		$crQuiz->setStart( $start );
		$crQuiz->setLimit( $limit );
		$crQuiz->setSort( $sort );
		$crQuiz->setOrder( $order );
		return $crQuiz;
	}

/* *************************************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */

    public function getListKeyName($quiz_cat_id = 0, $addAll=false, $addNull=false, $short_permtype='')

    {
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";
//         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);

        $criteria = new \CriteriaCompo();
        if($quiz_cat_id > 0){
            $criteria = new \CriteriaCompo(new \Criteria('quiz_cat_id' , $quiz_cat_id, '='));
        }
        
        if($short_permtype != '')
            $allAllowed = $this->getAllowed($short_permtype, $criteria);
        else
            $allAllowed = $this->getObjects($criteria, true);

                
        foreach (array_keys($allAllowed) as $i) {
            $key = $allAllowed[$i]->getVar('quiz_id');
            $ret[$key] = $allAllowed[$i]->getVar('quiz_name') . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");;
        
        }

        return $ret;
    }


/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function getParentId($quizId)

    {
        $ob = $this->get($quizId, 'quiz_cat_id');
        return ($ob) ? $ob->GetVar('quiz_cat_id') : -1;
    }
    
/* ******************************
 * renvoie le premier id enfant du parent 
 * *********************** */
    public function getFirstIdOfParent($catId)

    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \criteria('quiz_cat_id', $catId, '=' ));
        $obs = $this->getIds($criteria);
        //echo "<hr>getFirstIdOfParent : <pre>" . print_r($obs, true) . "</pre><hr>";
        if ($obs){
            return($obs[0]);
        }else{
            return null;
        }
    }
    
/* ******************************
 * renvoie la valeur maxmum d'un champ pour un idParent 
 * *********************** */
    public function getMax($field = "quiz_weight", $cat_id = 0)

    {
        $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}";
        if($cat_id > 0) $sql .= " WHERE quiz_cat_id = {$cat_id}";
        
        $rst = $this->db->query($sql);
        $arr = $this->db->fetchArray($rst);
//        echo print_r($arr,true);
        return $arr['valueMax'];
    }
    
/* ******************************
 * renvoie la valeur d'un champ en particulier 
 * *********************** */
    public function getValue($quizId, $field, $default = null)

    {
        $quizObj = $this->get($quizId);
        $v  = $quizObj->getVar($field);
        if(!$v) $v = $default;
        
//         $sql = "SELECT {$field} AS fldValue FROM {$this->table} WHERE cat_id = {$quizId}";   
//         $rst = $this->db->query($sql);
//         $arr = $this->db->fetchArray($rst);
//         $v = ($arr['fldValue']) ? $arr['fldValue'] : $default;
//echo "<hr>getValue : {$field} = {$v}<hr>";

        return $v;        
    }
/* ******************************
 *  
 * *********************** */
    public function getChildrenIds($quizId)
    {
    global $xoopsDB;

	   $sql = "SELECT GROUP_CONCAT(`quest_id`) as questIdList FROM " . $xoopsDB->prefix('quizmaker_questions')
            . " WHERE quest_quiz_id = {$quizId};"; 
	   $rst = $xoopsDB->query($sql);
	   list($questIdList) = $xoopsDB->fetchRow($rst);
//echo "<hr>{$questIdList}";
        return $questIdList;
    }

    /**
     * delete an object from the database
     *
     * @param  XoopsObject $object {@link XoopsObject} reference to the object to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete($object, $force = false)
    {
        global $answersHandler, $questionsHandler, $resultsHandler;
        
        $quizId = $object->getVar("quiz_id");
        //-----------------------------------------------------
        //suppression des resultats
        $criteria = new \Criteria("result_quiz_id", $quizId, '=');
        $ret = $resultsHandler->deleteAll($criteria);
        
        //-----------------------------------------------------
        $ids = $this->getChildrenIds($quizId);
        $criteria = new \CriteriaCompo(new \Criteria("answer_quest_id", "($ids)", 'IN'));
        $ret = $answersHandler->deleteAll($criteria);
        //-----------------------------------------------------
        $criteria = new \CriteriaCompo(new \Criteria("quest_quiz_id", $quizId, '='));
        $ret = $questionsHandler->deleteAll($criteria);
        
        $ret = parent::delete($object, $force);
        
        return $ret;
    }
    
/* **********************************************************
*   deleteQuestions
*   @questId integer : identifiant du quiz
*   
*   suppression de toutes les questions du quiz
* *********************************************************** */
public function deleteQuiz ($quizId) {
global $questionsHandler, $resultsHandler;    
    if ($quizId == 0) return false; //sinon ça efface tout
    
    //suppression des resultats
    $criteria = new \Criteria("result_quiz_id", $quizId, '=');
    $ret = $resultsHandler->deleteAll($criteria);
    
    //suppression des questions et des propositions(réponses)
    $questionsHandler->deleteQuestionsByQuizId ($quizId);
    
     $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
//     echo "===> Criteria delete : " . $criteria->render() . "<br>";
     $ret = $questionsHandler->deleteAll($criteria);
     
     $criteria = new \CriteriaCompo(new \Criteria('quiz_id', $quizId, '='));
     $ret = $this->deleteAll($criteria);
}
    
   
/* ******************************
 * Change l'etat du champ passer en parametre
 * @$quizId : id du quiz
 * @$field : nom du champ à changer
 * *********************** */
    public function changeEtat($quizId, $field, $modulo = 2)
    {
        //$sql = "UPDATE " . $this->table . " SET {$field} = not {$field} WHERE quiz_id={$quizId};";
        $sql = "UPDATE " . $this->table . " SET {$field} = mod({$field}+1,{$modulo}) WHERE quiz_id={$quizId};";
        $ret = $this->db->queryf($sql);
        return $ret;
    }

/* ******************************
 * renvoi un jeu de valeurs utilisé dans la liste de l'admin
 * permet une modification rapide des options
 * *********************** */
    public function config_options($quizId, $config = 0)
    {
        switch ($config){
        case 1:
        $tField = array('quiz_onClickSimple        = 1',
                        'quiz_answerBeforeNext     = 1',
                        'quiz_allowedPrevious      = 1',
                        'quiz_allowedSubmit        = 0',
                        'quiz_shuffleQuestions     = 0',
                        'quiz_showGoodAnswers      = 1',
                        'quiz_showBadAnswers       = 1',
                        'quiz_showReloadAnswers    = 1',
                        'quiz_useTimer             = 0',
                        'quiz_showResultAllways    = 1',
                        'quiz_showReponsesBottom   = 1',
                        'quiz_showResultPopup      = 0',
                        'quiz_showTypeQuestion     = 1',
                        'quiz_showLog              = 1',
                        'quiz_actif                = 0');
            break;
            
        default :
        $tField = array('quiz_onClickSimple        = 1',
                        'quiz_answerBeforeNext     = 1',
                        'quiz_allowedPrevious      = 0',
                        'quiz_allowedSubmit        = 0',
                        'quiz_shuffleQuestions     = 0',
                        'quiz_showGoodAnswers      = 0',
                        'quiz_showBadAnswers       = 0',
                        'quiz_showReloadAnswers    = 0',
                        'quiz_useTimer             = 0',
                        'quiz_showResultAllways    = 0',
                        'quiz_showReponsesBottom   = 0',
                        'quiz_showResultPopup      = 1',
                        'quiz_showTypeQuestion     = 0',
                        'quiz_showLog              = 0',
                        'quiz_actif                = 1');
            break;
        }
        
    $sql = "UPDATE " . $this->table . " SET " . implode(', ', $tField)   
         . " WHERE quiz_id = {$quizId}; ";     
        
    $ret = $this->db->queryf($sql);
    return $ret;
    }


	/**
     * Fonction qui liste les quiz qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
	public function getPermissions($short_permtype = 'view')
    {
        $permtype = sprintf("quizmaker_%s_quiz", $short_permtype);
        
        global $xoopsUser;
        $tPerm = array();
        $helper = Helper::getHelper('quizmaker');
        $moduleHandler = $helper->getModule();
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');
        $tPerm = $gpermHandler->getItemIds($permtype, $groups, $moduleHandler->getVar('mid'));

        return $tPerm;
    }


	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
	public function getAllowed($short_permtype = 'view', $criteria = null, $sorted='quiz_name,quiz_id', $order="ASC")
    {
        global $categoriesHandler;
        
        $tPerm = $this->getPermissions($short_permtype);
        $idsQuiz = join(',', $tPerm);
        //---------------------------------------------------------------
        $idsCat = join(',', $categoriesHandler->getPermissions($short_permtype));
        //echo "<hr>===>getAllowed quiz :<br>idsQuiz : {$idsQuiz}<br>idsCat : {$idsCat}<hr>";
        //------------------------------------------------
        if (is_null($criteria)) $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('quiz_id',"({$idsQuiz})",'IN'));
        $criteria->add(new \Criteria('quiz_cat_id',"({$idsCat})",'IN'), 'AND');
        if ($sorted != '') $criteria->setSort($sorted);
        if ($order  != '') $criteria->setOrder($order);
       
        $allEnrAllowed = parent::getAll($criteria);
        return $allEnrAllowed;
    }
    
public function getStatistics($QuizId = 0){
global $questionsHandler, $resultsHandler;
    
    //nombre de questions par quiz
    $statQuestions = $questionsHandler->getStatistics();
    //Stat des resultat par quiz
    $statResults   = $resultsHandler->getStatistics();
    
//     echoArray($statQuestions);    
//     echoArray($statResults);    

    $stat = array ();
     foreach($statQuestions as $key=>$arr){
         if (isset($statResults[$key])){
            //ajout des clé du tablau $result dans le tableau $question
            foreach($statResults[$key] as $j=>$v){
             $statQuestions[$key][$j] = $v;
            }
         }
         $statQuestions[$key]['statOk'] = (isset($statResults[$key])) ? 1 : 0;
         
     }
//     echoArray($statQuestions);   
    
    return $statQuestions;
}

/* ******************************
 * Incremente weight
 * *********************** */
 public function incrementeWeight($masterId, $orderBy = 'ASC', $firstWeight = 10, $step = 10){
$fldWeight = 'quiz_weight';
$fldMasterId =  "quiz_cat_id";

    $sql = "SET @rank=-{$step};";
    $result = $this->db->queryf($sql);

    $sql = "update {$this->table} SET {$fldWeight} = {$step}+(@rank:=@rank+{$step}) WHERE {$fldMasterId}='{$masterId}' ORDER BY {$fldWeight} {$orderBy};";    
    $result = $this->db->queryf($sql);
   
}

 /* ******************************
 * Update weight
 * *********************** */
 public function updateWeight2($quiz_id, $action){
 $step = 10;
 $fldWeight = 'quiz_weight';
 $fldId = 'quiz_id';
 
    $currentEnr = $this->get($quiz_id); 
    $quiz_cat_id  = $currentEnr->getVar('quiz_cat_id');
    $this->incrementeWeight($quiz_cat_id, $step);
 
    switch ($action){
      case 'up'; 
        $newWeight = "{$fldWeight} = {$fldWeight} - {$step} - 5";
        break;
    
      case 'down'; 
        $newWeight = "{$fldWeight} = {$fldWeight} + {$step} + 5";
      break;
    
      case 'first'; 
        $newWeight = "{$fldWeight}=-99999";
      break;
    
      case 'last'; 
        $newWeight = "{$fldWeight}=99999";
      break;
      
    }
    $sql = "update {$this->table} SET {$newWeight} WHERE {$fldId} = {$quiz_id};";    
    $result = $this->db->queryf($sql);
 
    $this->incrementeWeight($quiz_cat_id, $step);
    return true;
 }

} // Fin de la classe

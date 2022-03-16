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
 * Class Object Handler Questions
 */
class QuestionsHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_questions', Questions::class, 'quest_id', 'quest_quiz_id');
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
	 * Get Count Questions in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountQuestions($criteria=null, $start = 0, $limit = 0, $sort = 'quest_id ASC, quest_quiz_id', $order = 'ASC')
	{
		$crCountQuestions = ($criteria) ? $criteria: new \CriteriaCompo();
		$crCountQuestions = $this->getQuestionsCriteria($crCountQuestions, $start, $limit, $sort, $order);
		return parent::getCount($crCountQuestions);
	}

	/**
	 * Get All Questions in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllQuestions($criteria=null, $start = 0, $limit = 0, $sort = 'quest_id ASC, quest_quiz_id', $order = 'ASC')
	{
		$crAllQuestions = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllQuestions = $this->getQuestionsCriteria($crAllQuestions, $start, $limit, $sort, $order);
		return parent::getAll($crAllQuestions);
	}

	/**
	 * Get Criteria Questions
	 * @param        $crQuestions
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getQuestionsCriteria($crQuestions, $start, $limit, $sort, $order)
	{
		$crQuestions->setStart( $start );
		$crQuestions->setLimit( $limit );
		$crQuestions->setSort( $sort );
		$crQuestions->setOrder( $order );
		return $crQuestions;
	}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName($quiz_id = 0, $fieldsName = 'quest_question', $addAll=false, $addNull=false)

    {
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";
//         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);

        if ($quiz_id > 0){
            $criteria = new \Criteria('quest_quiz_id', $quiz_id, '=');
        }else{
            $criteria = null;
        }
        
        $obs = $this->getObjects($criteria, true);
        foreach (array_keys($obs) as $i) {
            $key = $obs[$i]->getVar('quest_id');
            $ret[$key] = $obs[$i]->getVar($fieldsName) . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");
        
        }

        return $ret;
    }

 /* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function getParentId($questId)
    {
        $ob = $this->get($questId, 'quest_quiz_id');
        return ($ob) ? $ob->GetVar('quest_quiz_id') : -1;
    }
    
/* ******************************
 * renvoie le premier id enfant du parent 
 * *********************** */
    public function getFirstIdOfParent($quizId)

    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \criteria('quest_quiz_id', $quizId, '=' ));
        $obs = $this->getIds($criteria);
        //echo "<hr>getFirstIdOfParent : <pre>" . print_r($obs, true) . "</pre><hr>";
        if ($obs){
            return($obs[0]);
        }else{
            return -1;
        }
    }

/* ******************************
 * renvoie la valeu maxmum d'un champ pour un idParent 
 * *********************** */
    public function getMax($field = "quest_weight", $quiz_id = 0)

    {
        $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}";
        if($quiz_id > 0) $sql .= " WHERE quest_quiz_id = {$quiz_id}";
        
        $rst = $this->db->query($sql);
        
        $arr = $this->db->fetchArray($rst);
//        echo print_r($arr,true);
        return $arr['valueMax'];
    }
  
/* ******************************
 * Update weight
 * *********************** */
 public function incrementeWeight($quiz_id, $orderBy = 'ASC', $firstWeight = 10, $step = 10){

//calcul d'un poids provisoire en tenant compte du regroupement par parent
$sql = <<<__SQL__
update {$this->table}  , {$this->table} tp
right join {$this->table} tq ON tq.quest_parent_id = tp.quest_id 
SET tq.quest_flag = if(tp.quest_id is null, tq.quest_weight*1000, (tp.quest_weight*1000)+tq.quest_weight)
WHERE tq.quest_quiz_id = {$quiz_id};
__SQL__;
	
    $result = $this->db->queryf($sql);
    //--------------------------------
    $firstWeight -= $step;
    $sql = "SET @rank={$firstWeight};";
    $result = $this->db->queryf($sql);
    
    //affection du poids à partir du poids provisoir calculé précédemment
    //$sql = "update {$this->table} SET quest_flag = (@rank:=@rank+{$step}) WHERE quest_quiz_id='{$quiz_id}' ORDER BY quest_flag {$orderBy};";
    $sql = "update {$this->table} SET quest_weight = (@rank:=@rank+{$step}) WHERE quest_quiz_id='{$quiz_id}' ORDER BY quest_flag {$orderBy};";    
    $result = $this->db->queryf($sql);
    return $result;
 }
 
/* ******************************
 * Update weight
 * *********************** */
 public function updateWeight($quest_id, $action){
          $currentEnr = $this->get($quest_id); 
          $quiz_id = $currentEnr->getVar('quest_quiz_id');
          $quest_weight = $currentEnr->getVar('quest_weight');
          $quest_parent_id  = $currentEnr->getVar('quest_parent_id');
          
//exit ("===>quest_id = {$quest_id}<br>Action = {$action}");          
         switch ($action){
            case 'up'; 
              $sens =  '<';
              $ordre = "DESC";
              break;

            case 'down'; 
              $sens =  '>';
              $ordre = "ASC";
            break;

            case 'first'; 
              $sens =  '<=';
              $ordre = "DESC";
            break;

            case 'last'; 
              $sens =  '>=';
              $ordre = "ASC";
            break;
            
         }
         
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('quest_quiz_id', $quiz_id));
        $criteria->add(new \Criteria('quest_weight', $quest_weight, $sens));
        
        // selection du parent ou du groupe des enfants
        $selectParent = ($quest_parent_id == 0) ? '=' : '>';
        $criteria->add(new \Criteria('quest_parent_id', 0, $selectParent));
        
        $criteria->setSort("quest_weight");
		$criteria->setOrder( $ordre );
        $limit = 0;
        $start = 0;
        //$allObjects = $this->getAllQuestions($criteria, $start, $limit, "quest_weight {$ordre}, quest_question {$ordre}, quest_id");
        $allObjects = $this->getObjects($criteria, true);
        if(count($allObjects) == 0  ){
            return true;
        }
        

         switch ($action){
            case 'up'; 
            case 'down'; 
              $key = array_key_first($allObjects);
      //            echo "===> count = " . count($allObjects) . "<br>key={$key}"; 
              $enr2 = $allObjects[$key]->getValuesQuestions();
              $quest_id2 = $enr2['id'];
              $quest_weight2 = $enr2['weight'];
        
              $tbl = $this->table;
              $sql = "UPDATE {$tbl} SET quest_weight={$quest_weight2} WHERE quest_id={$quest_id}";
              $this->db->queryf($sql);
              
              $sql = "UPDATE {$tbl} SET quest_weight={$quest_weight} WHERE quest_id={$quest_id2}";
              $this->db->queryf($sql);
            break;

            case 'first'; 
            case 'last'; 
              
                $keys = array_keys($allObjects);
              
//echo "<hr>quest_id = {$quest_id}<br>quest_weight = {$quest_weight}<br>quiz_id = {$quiz_id}<br><pre>" . print_r($keys, true) . "</pre><hr>";              
              
              for ($h = 0; $h < count($keys); $h++){
                if($h == 0){
                    $key = array_key_last($allObjects);
                    $newWeight = $allObjects[$key]->getVar('quest_weight');
                    $key2update = $keys[$h];
                }else{
                    $key = $keys[$h-1];
                    $newWeight = $allObjects[$key]->getVar('quest_weight');
                    $key2update = $keys[$h];
                }
                $sql = "UPDATE {$this->table} SET quest_weight = {$newWeight} WHERE quest_id = {$key2update}" ;               
                $this->db->queryf($sql);
              }
              
            break;
            
         }
         return true;
 }

    
/* ******************************
 * 
 * *********************** */
    public function delete( $object, $force = false)
    {
        global $answersHandler;
        
        $questId = $object->getVar("quest_id");
        //-----------------------------------------------------
        $criteria = new \CriteriaCompo(new \Criteria("answer_quest_id", $questId, '='));
        $ret = $answersHandler->deleteAll($criteria);
        
        $ret = parent::delete($object, $force);
        
        return $ret;
    }
// /* **********************************************************
// *   deleteQuestions
// *   @questId integer : identifiant du quiz
// *   
// *   suppression de toutes les questions du quiz
// * *********************************************************** */
// public function deleteQuestionsByQuizId ($quizId) {
// global $answersHandler;    
//     if ($quizId == 0) return false; //sinon ça efface tout
//     
//      $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
// //     echo "===> Criteria delete : " . $criteria->render() . "<br>";
//      return $this->deleteAll($criteria);
//}
    
/* ******************************
 * renvoie la valeur maxmum d'un champ pour un idParent 
 * *********************** */
    public function changeEtat($questId, $field, $doItForGroup = false)
    {
        $sql = "UPDATE " . $this->table . " SET {$field} = not {$field} WHERE quest_id={$questId};";
        $ret = $this->db->queryf($sql);
        
        if($doItForGroup){
            $questObj = $this->get($questId);
            if($questObj->getVar('quest_type_form') == 1){
              // si c'est le slide d'introduction, change l'état de toutes les questions du quiz
              $etat = $questObj->getVar($field);
              $quizId = $questObj->getVar('quest_quiz_id');
              $sql = "UPDATE " . $this->table . " SET {$field} = {$etat} WHERE quest_quiz_id={$quizId};";            
              $ret = $this->db->queryf($sql);
            }else{
              // si c'est un slide "encart" , change l'état de toutes les questions du groupe
              $etat = $questObj->getVar($field);
              $sql = "UPDATE " . $this->table . " SET {$field} = {$etat} WHERE quest_parent_id={$questId};";            
              $ret = $this->db->queryf($sql);
            }
        }
        
        return $ret;
    }
/* ********************************
*
* ******************************* */
public function getParents($quizId, $addNone = true){
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
    $criteria->add(new \Criteria('quest_parent_id', 0, '='));
    $criteria->add(new \Criteria('quest_type_question', '("Encart","intro","pageInfo")', 'IN'));
    $criteria->setOrder('ASC');
    $criteria->setSort('quest_weight,quest_question');
    $rst = $this->getAll($criteria);
    
    $tParent = array();
    if ($addNone) $tParent[0] = _AM_QUIZMAKER_NONE .  " (0)";
    foreach($rst AS $i=>$question){
        $tParent[$question->getVar('quest_id')] = $question->getVar('quest_question') .  " (" . $question->getVar('quest_id') . ")";
    }
    return $tParent;
}

public function getStatistics($QuizId = 0){
/*
*/
    $sql = "SELECT quest_quiz_id AS quizId, count(quest_quiz_id) as countQuestions"
         . " FROM ". $this->table . " GROUP BY quest_quiz_id";
    if ($QuizId > 0)
        $sql .= " WHERE quest_quiz_id = {$quizId}";
    $rst = $this->db->query($sql);
    $stat = array ();
    while (false !== ($row = $this->db->fetchArray($rst))) {
        $stat[$row['quizId']] = $row;
    }
//    echoArray($stat);
    return $stat;
}

} //Fin de la classe

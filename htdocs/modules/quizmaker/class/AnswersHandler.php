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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;


/**
 * Class Object Handler Answers
 */
class AnswersHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_answers', Answers::class, 'answer_id', 'answer_quest_id');
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
	 * Get Count Answers in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountAnswers($critria = null, $start = 0, $limit = 0, $sort = 'answer_id ASC, answer_quest_id', $order = 'ASC')
	{
		$crCountAnswers = ($critria) ? $critria : new \CriteriaCompo();
		$crCountAnswers = $this->getAnswersCriteria($crCountAnswers, $start, $limit, $sort, $order);
		return parent::getCount($crCountAnswers);
	}

	/**
	 * Get All Answers in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllAnswers($critria = null, $start = 0, $limit = 0, $sort = 'answer_id ASC, answer_quest_id', $order = 'ASC')
	{
		$crAllAnswers = ($critria) ? $critria : $crAllAnswers = new \CriteriaCompo();
		$crAllAnswers = $this->getAnswersCriteria($crAllAnswers, $start, $limit, $sort, $order);
		return parent::getAll($crAllAnswers);
	}

	/**
	 * Get Criteria Answers
	 * @param        $crAnswers
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getAnswersCriteria($crAnswers, $start, $limit, $sort, $order)
	{
		$crAnswers->setStart( $start );
		$crAnswers->setLimit( $limit );
		$crAnswers->setSort( $sort );
		$crAnswers->setOrder( $order );
		return $crAnswers;
	}

/* *************************************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */

    public function getListKeyName($answers_quest_id = 0, $addAll=false, $addNull=false)
    {
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";
//         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);

        if($quiz_cat_id > 0){
            $criteria = new \Criteria('answers_quest_id' , $answers_quest_id, '=');
        }else{
            $criteria = null;
        }

        $obs = $this->getObjects($criteria, true);
        foreach (array_keys($obs) as $i) {
            $key = $obs[$i]->getVar('quest_id');
            $ret[$key] = $obs[$i]->getVar('quest_name') . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");;
        
        }

        return $ret;
    }
/* *************************************************
 * renvoie une liste des réponses pour un idparent trié
 * *********************** */
    public function getListByParent($quest_id = 0, $sort = 'answer_weight, answer_quest_id, answer_id', $order = 'ASC')
    {
        if  ($quest_id == 0) return  $answers = array();
        //------------------------------------------------------
        $criteria = new \Criteria('answer_quest_id' , $quest_id, '=');
        $criteria->setOrder($order);
        $criteria->setSort($sort);
		$criteria->setStart( 0 );
		$criteria->setLimit( 0 );

        $obs = $this->getObjects($criteria);
//         foreach (array_keys($obs) as $i) {
//             $key = $obs[$i]->getVar('quest_id');
//             $ret[$key] = $obs[$i]->getVar('quest_name') . ((QUIZMAKER_ADD_ID) ? " ({$key})" : "");;
//         
//         }
//         foreach ($obs as $key=>$ans){
//             $ret[] = $ans;
//         }

        return $obs;
    }

 /* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function getParentId($answerId)

    {
        $ob = $this->get($answerId, 'answer_quest_id');
        return $ob->GetVar('answer_quest_id');
    }
    
/* ******************************
 * renvoie le premier id enfant du parent 
 * *********************** */
    public function getFirstIdOfParent($questId)

    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \criteria('answer_quest_id', $questId, '=' ));
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
    public function getMax($field = "answer_weight", $quest_id = 0)
    {
        $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}";
        if ($quest_id > 0) $sql .= " WHERE answer_quest_id = {$quest_id}";
        
        $rst = $this->db->query($sql);
        $arr = $this->db->fetchArray($rst);
//        echo print_r($arr,true);
        return $arr['valueMax'];
    }

/* ******************************
 * Update weight
 * *********************** */
 public function incrementeWeight($quest_id, $orderBy = "ASC", $firstWeight= 10, $step = 10)
 {
 
    $firstWeight -= $step;
    $sql = "SET @rank={$firstWeight};";
    $result = $this->db->queryf($sql);
    
    $sql = "update {$this->table} SET quest_weight = (@rank:=@rank+{$step}) WHERE quest_quiz_id='{$quiz_id}' ORDER BY quest_weight {$orderBy};";
    $result = $this->db->queryf($sql);
    return $result;
 }


/* **********************************************************
*   deleteAnswers
*   @questId integer : identifiant de la question
*   
*   suppression de toutes les propositions du slide
*   c'est plus simple de de modifier et ajouter
* *********************************************************** */
public function deleteAnswersByQuestId ($questId) {
global $answersHandler;    
    if ($questId == 0) return false; //sinon ça efface tout
    
     $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id', $questId, '='));
//     echo "===> Criteria delete : " . $criteria->render() . "<br>";
     return $this->deleteAll($criteria);
}

public function deleteId ($id) {
//global $answersHandler;    
    $obj = $this->get($id);
    $this->delete($obj);
}

/* ******************************
 * Update weight
 * *********************** */
 public function updateWeight($answer_id, $action)
 {
//         if($quiz_id == 0){
//         }
          $currentEnr = $this->get($answer_id); 
          $quiz_id = $currentEnr->getVar('answer_quest_id');
          $answer_weight = $currentEnr->getVar('answer_weight');
          
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
        $criteria->add(new \Criteria('answer_quest_id', $quiz_id));
        $criteria->add(new \Criteria('answer_weight', $answer_weight, $sens));
        $criteria->setSort("answer_weight");
		      $criteria->setOrder( $ordre );
        $limit = 0;
        $start = 0;
        //$allObjects = $this->getAllQuestions($criteria, $start, $limit, "answer_weight {$ordre}, answer_question {$ordre}, answer_id");
        $allObjects = $this->getObjects($criteria, true);
        if(count($allObjects) == 0  ){
            return true;
        }
        

         switch ($action){
            case 'up'; 
            case 'down'; 
              $key = array_key_first($allObjects);
      //            echo "===> count = " . count($allObjects) . "<br>key={$key}"; 
              $enr2 = $allObjects[$key]->getValuesAnswers();
              $answer_id2 = $enr2['id'];
              $answer_weight2 = $enr2['weight'];
        
              $tbl = $this->table;
              $sql = "UPDATE {$tbl} SET answer_weight={$answer_weight2} WHERE answer_id={$answer_id}";
              $this->db->queryf($sql);
              
              $sql = "UPDATE {$tbl} SET answer_weight={$answer_weight} WHERE answer_id={$answer_id2}";
              $this->db->queryf($sql);
            break;

            case 'first'; 
            case 'last'; 
              
            $keys = array_keys($allObjects);
              
//echo "<hr>answer_id = {$answer_id}<br>answer_weight = {$answer_weight}<br>quiz_id = {$quiz_id}<br><pre>" . print_r($keys, true) . "</pre><hr>";              
              
              for ($h = 0; $h < count($keys); $h++){
                if($h == 0){
                    $key = array_key_last($allObjects);
                    $newWeight = $allObjects[$key]->getVar('answer_weight');
                    $key2update = $keys[$h];
                }else{
                    $key = $keys[$h-1];
                    $newWeight = $allObjects[$key]->getVar('answer_weight');
                    $key2update = $keys[$h];
                }
                $sql = "UPDATE {$this->table} SET answer_weight = {$newWeight} WHERE answer_id = {$key2update}" ;               
                $this->db->queryf($sql);
              }
              
            break;
            
         }
         return true;
         
 }   
    /**
     * delete all objects matching the conditions
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement} with conditions to meet
     * @param  bool            $force    force to delete
     * @param  bool            $asObject delete in object way: instantiate all objects and delete one by one
     * @return bool
     */
//     public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
//     {
//         $handler = $this->loadHandler('write');
// 
//         return $handler->deleteAll($criteria, $force, $asObject);
//     }
} // Fin de la classe
   


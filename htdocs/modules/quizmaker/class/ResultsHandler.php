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


/**
 * Class Object Handler Results
 */
class ResultsHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_results', Results::class, 'result_id', 'result_quiz_id');
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
	 * Get Count Results in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
// 	public function getCountResults($start = 0, $limit = 0, $sort = 'result_id ASC, result_quiz_id', $order = 'ASC')
// 	{
// 		$crCountResults = new \CriteriaCompo();
// 		$crCountResults = $this->getResultsCriteria($crCountResults, $start, $limit, $sort, $order);
// 		return parent::getCount($crCountResults);
// 	}
	public function getCountResults($criteria=null, $start = 0, $limit = 0, $sort = 'result_id', $order = 'ASC')
	{
		if(!$criteria) $criteria = new \CriteriaCompo();
		$crCountResults = $this->getResultsCriteria($criteria, $start, $limit, $sort, $order);
		return parent::getCount($crCountResults);
	}

	/**
	 * Get All Results in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
// 	public function getAllResults($start = 0, $limit = 0, $sort = 'result_id ASC, result_quiz_id', $order = 'ASC')
// 	{
// 		$crAllResults = new \CriteriaCompo();
// 		$crAllResults = $this->getResultsCriteria($crAllResults, $start, $limit, $sort, $order);
// 		return parent::getAll($crAllResults);
// 	}
	public function getAllResults($criteria=null, $start = 0, $limit = 0, $sort = 'result_id', $order = 'ASC')
	{
		$newCriteria = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllResults = $this->getResultsCriteria($newCriteria, $start, $limit, $sort, $order);
		return parent::getAll($crAllResults);
	}


	/**
	 * Get Criteria Results
	 * @param        $crResults
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getResultsCriteria($crResults, $start, $limit, $sort, $order)
	{
		$crResults->setStart( $start );
		$crResults->setLimit( $limit );
		$crResults->setSort( $sort );
		$crResults->setOrder( $order );
		return $crResults;
	}

public function getStatistics($QuizId = 0){
/*
  `result_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `result_quiz_id` int(8) NOT NULL DEFAULT '0',
  `result_uid` int(8) NOT NULL DEFAULT '0',
  `result_uname` varchar(50) NOT NULL,
  `result_ip` varchar(50) NOT NULL,
  `result_score_achieved` int(8) NOT NULL DEFAULT '0',
  `result_score_max` int(8) NOT NULL DEFAULT '0',
  `result_score_min` int(8) NOT NULL DEFAULT '0',
  `result_answers_achieved` int(8) NOT NULL DEFAULT '0',
  `result_answers_total` int(8) NOT NULL DEFAULT '0',
  `result_duration` int(8) NOT NULL DEFAULT '0',
  `result_note` float NOT NULL DEFAULT '0',
  `result_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `result_update` datetime(6) NOT NULL,
  `result_attempts` int(8) NOT NULL DEFAULT '0',
*/
    $sql = "SELECT result_quiz_id AS quizId, count(result_quiz_id) as countResults,"
         . " max(result_score_achieved) as bestScore, MAX(result_score_max) AS scoreMax,"
         . " round(avg(result_score_achieved),2) as avgScore"
         . " FROM ". $this->table . " GROUP BY result_quiz_id";
    if ($QuizId > 0)
        $sql .= " WHERE result_quiz_id = {$quizId}";
    $rst = $this->db->query($sql);
    $stat = array ();
    while (false !== ($row = $this->db->fetchArray($rst))) {
        $stat[$row['quizId']] = $row;
    }
//    echoArray($stat);
    return $stat;
}

/* ******************************
 * renvoie le score max pour un uid et un quiz
 * *********************** */
    public function getScoreMax($quest_id , $uid)
    {
        $field = "result_score_achieved"; 
        
        $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}"
             . " WHERE result_quiz_id = {$quest_id} AND result_uid = {$uid}";
        
        $rst = $this->db->query($sql);
        $arr = $this->db->fetchArray($rst);
//        echo print_r($arr,true);
        return $arr['valueMax'];
    }

    
}

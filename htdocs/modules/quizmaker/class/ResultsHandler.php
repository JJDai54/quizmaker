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
    
	public function getAllResultsArr($criteria=null, $start = 0, $limit = 0, $sort = 'result_id', $order = 'ASC')
	{
		$newCriteria = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllResults = $this->getResultsCriteria($newCriteria, $start, $limit, $sort, $order);
        
        $allRst = $this->getAllResults($criteria, $start, $limit, $sort, $order);
            
        $ret = array();
        if (count($allRst) > 0) {
          foreach(array_keys($allRst) as $i) {
          	$ret[] = $allRst[$i]->getValuesResults();
          }
        }
        
        return $ret;
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
  `result_email` varchar(60) NOT NULL,
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

//-----------------------------------------------
public function exportResultsToCSV($quizId){
// Sélectionner les données de la table
global $resultsHandler;

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
    $result = $resultsHandler->getAllResultsArr($criteria);    
    $resultsCount = count($result);
    if ($quizId == 0){
        $resultsCount = 0;
        return '';
    }

    $delimiter = ";";
    $filename = "results_quiz_{$quizId}-" . date('Y-m-d') . ".csv";
    $fullName = QUIZMAKER_PATH_UPLOAD_EXPORT . "/" . $filename;
    // Créer un fichier CSV
    $f = fopen($fullName, 'w');
    
    // Définir les entêtes du fichier CSV
    $fields = array('result_id','Quiz_id','uid','Nom','Courriel','IP','Score',
                    'max','min','nbreponses','nbRepondu','Duree','Note',
                    'date_creation','date_update');

    //echoArray($result);
    fputcsv($f, $fields, $delimiter);
    
    // Boucler à travers les enregistrements et les écrire dans le fichier CSV
    foreach($result AS $key=>$arr){
        $lineData = array();
        $lineData[] = $arr['result_id'];
        $lineData[] = $arr['result_quiz_id'];
        $lineData[] = $arr['result_uid'];
        $lineData[] = $arr['result_uname'];
        $lineData[] = $arr['result_email'];
        $lineData[] = $arr['result_ip'];
        $lineData[] = $arr['result_score_achieved'];
        $lineData[] = $arr['result_score_max'];
        $lineData[] = $arr['result_score_min'];
        $lineData[] = $arr['result_answers_total'];
        $lineData[] = $arr['result_answers_achieved'];
        $lineData[] = $arr['result_duration'];
        $lineData[] = $arr['result_note'];
        $lineData[] = $arr['result_creation'];
        $lineData[] = $arr['result_update'];
        //$lineData[] = $arr[''];
        //$lineData[] = str_replace('.',',',$arr['result_note']);
            
        fputcsv($f, $lineData, $delimiter);
    }
    
    fclose($f);
    return $fullName;
}    

/* ***
*/
public function updateEmptyFields($quizId){
    $usershandler = xoops_getHandler('user');
        
    $criteria = new \CriteriaCompo();            
    $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
    $criteria->add(new \Criteria('result_uid', 0, ">"));
    //$criteria->add(new \Criteria('result_uid', 3, "<>"));
    //$criteria->add(new \Criteria('length(result_uname)',0,'='));
    
    $criteria2 = new \CriteriaCompo();
    $criteria2->add(new \Criteria('', 0, '=',null,'length(result_uname)'));
    $criteria2->add(new \Criteria('', 0, '=',null,'length(result_email)'),"OR");
    $criteria->add($criteria2);
        
    $result = $this->getAllResultsArr($criteria); 
//echoArray($result);exit;
    if(count($result) ==  0) return 0;
    foreach($result AS $key=>$arr){
    //echoArray($arr);
        $uid = $arr['uid'];
        $user = $usershandler->get($uid);
        $resultObj = $this->get($arr['id']);
        if($user){
          $resultObj->setVar('result_uname', $user->getVar('uname'));
          $resultObj->setVar('result_email', $user->getVar('email'));
          $this->insert($resultObj);
        }else{
          //le user n'existe plus
          $this->delete($resultObj);
        }        

    }
 
    return count($result);
}

/* ***
*/
public function deleteEmptyFields($quizId){

    //on ne supprime pas les anonymes dont l'email est renseigné
    $criteria = new \CriteriaCompo();            
    $criteria->add(new \Criteria('result_quiz_id',$quizId, "="));
    $criteria->add(new \Criteria('', 0, '=',null,'length(result_uname)'));
    $criteria->add(new \Criteria('', 0, '=',null,'length(result_email)'),"AND");
    $this->deleteAll($criteria);

    return true;
}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName($criteria = null, $keyField=null, $nameField = null, $addAll=false, $addNull=false)
    {
        if(!$keyField) $nameField = 'result_id';
        if(!$nameField) $nameField = 'result_email';
        
        $obs = $this->getObjects($criteria, true);
        $ret = array();
        if ($addAll) $ret[0] = "(*)";
        
        foreach (array_keys($obs) as $i) {
            $key = $obs[$i]->getVar($keyField);
            //echo "i = {$i} - key = {$key}<br>";
            if (($key) == $obs[$i]->getVar($nameField)){
                $ret[$key] = $obs[$i]->getVar($nameField) ;
            }else{
                $ret[$key] = ((QUIZMAKER_ADD_ID) ? " (#{$key}) - " : "") . $obs[$i]->getVar($nameField) ;
            }
        }
        //echoArray($ret);exit;
        return $ret;
    }

} // -------------------fin de la classe ---------------

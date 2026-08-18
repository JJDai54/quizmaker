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
 * Class Object Handler Cookies
 */
class CookiesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_cookies', Cookies::class, 'cookie_id', 'cookie_quiz_id');
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
	 * Get Count Cookies in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
// 	public function getCountCookies($start = 0, $limit = 0, $sort = 'cookie_id ASC, cookie_quiz_id', $order = 'ASC')
// 	{
// 		$crCountCookies = new \CriteriaCompo();
// 		$crCountCookies = $this->getCookiesCriteria($crCountCookies, $start, $limit, $sort, $order);
// 		return parent::getCount($crCountCookies);
// 	}
	public function getCountCookies($criteria=null, $start = 0, $limit = 0, $sort = 'cookie_id', $order = 'ASC')
	{
		if(!$criteria) $criteria = new \CriteriaCompo();
		$crCountCookies = $this->getCookiesCriteria($criteria, $start, $limit, $sort, $order);
		return parent::getCount($crCountCookies);
	}

	/**
	 * Get All Cookies in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
// 	public function getAllCookies($start = 0, $limit = 0, $sort = 'cookie_id ASC, cookie_quiz_id', $order = 'ASC')
// 	{
// 		$crAllCookies = new \CriteriaCompo();
// 		$crAllCookies = $this->getCookiesCriteria($crAllCookies, $start, $limit, $sort, $order);
// 		return parent::getAll($crAllCookies);
// 	}
	public function getAllCookies($criteria=null, $start = 0, $limit = 0, $sort = 'cookie_id', $order = 'ASC')
	{
		$newCriteria = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllCookies = $this->getCookiesCriteria($newCriteria, $start, $limit, $sort, $order);
		return parent::getAll($crAllCookies);
	}

	public function getAllCookiesArr($criteria=null, $start = 0, $limit = 0, $sort = 'cookie_id', $order = 'ASC')
	{
		$newCriteria = ($criteria) ? $criteria: new \CriteriaCompo();
		$crAllResults = $this->getCookiesCriteria($newCriteria, $start, $limit, $sort, $order);
        
        $allRst = $this->getAllCookies($criteria, $start, $limit, $sort, $order);
            
        $ret = array();
        if (count($allRst) > 0) {
          foreach(array_keys($allRst) as $i) {
          	$ret[] = $allRst[$i]->getValuesCookies();
          }
        }
        
        return $ret;
	}

	/**
	 * Get Criteria Cookies
	 * @param        $crCookies
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getCookiesCriteria($crCookies, $start, $limit, $sort, $order)
	{
		$crCookies->setStart( $start );
		$crCookies->setLimit( $limit );
		$crCookies->setSort( $sort );
		$crCookies->setOrder( $order );
		return $crCookies;
	}

//public function getStatistics($QuizId = 0){
/*
  `cookie_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cookie_quiz_id` int(8) NOT NULL DEFAULT '0',
  `cookie_uid` int(8) NOT NULL DEFAULT '0',
  `cookie_uname` varchar(50) NOT NULL,
  `cookie_ip` varchar(50) NOT NULL,
  `cookie_score_achieved` int(8) NOT NULL DEFAULT '0',
  `cookie_score_max` int(8) NOT NULL DEFAULT '0',
  `cookie_score_min` int(8) NOT NULL DEFAULT '0',
  `cookie_answers_achieved` int(8) NOT NULL DEFAULT '0',
  `cookie_answers_total` int(8) NOT NULL DEFAULT '0',
  `cookie_duration` int(8) NOT NULL DEFAULT '0',
  `cookie_note` float NOT NULL DEFAULT '0',
  `cookie_creation` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `cookie_update` datetime(6) NOT NULL,
  `cookie_attempts` int(8) NOT NULL DEFAULT '0',
*/
//     $sql = "SELECT cookie_quiz_id AS quizId, count(cookie_quiz_id) as countCookies,"
//          . " max(cookie_score_achieved) as bestScore, MAX(cookie_score_max) AS scoreMax,"
//          . " round(avg(cookie_score_achieved),2) as avgScore"
//          . " FROM ". $this->table . " GROUP BY cookie_quiz_id";
//     if ($QuizId > 0)
//         $sql .= " WHERE cookie_quiz_id = {$quizId}";
//     $rst = $this->db->query($sql);
//     $stat = array ();
//     while (false !== ($row = $this->db->fetchArray($rst))) {
//         $stat[$row['quizId']] = $row;
//     }
// //    echoArray($stat);
//     return $stat;
// }

/* ******************************
 * renvoie le score max pour un uid et un quiz
 * *********************** */
//     public function getScoreMax($quest_id , $uid)
//     {
//         $field = "cookie_score_achieved"; 
//         
//         $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}"
//              . " WHERE cookie_quiz_id = {$quest_id} AND cookie_uid = {$uid}";
//         
//         $rst = $this->db->query($sql);
//         $arr = $this->db->fetchArray($rst);
// //        echo print_r($arr,true);
//         return $arr['valueMax'];
//     }

public function exportCookiesToCSV($quizId){
// Sélectionner les données de la table

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('cookie_quiz_id',$quizId, "="));
    $result = $this->getAllCookiesArr($criteria);    
    $resultsCount = count($result);
    if ($quizId == 0){
        $resultsCount = 0;
        return null;
    }
//echoArray($result);exit;
    $delimiter = ";";
    $filename = "cookies_quiz_{$quizId}-" . date('Y-m-d') . ".csv";
    $fullName = QUIZMAKER_PATH_UPLOAD_EXPORT . "/" . $filename;
    // Créer un fichier CSV
    $f = fopen($fullName, 'w');
    
    // Définir les entêtes du fichier CSV
    $fields = array('id','cat_id','quiz_id','uid','uname','readme','email','ip',
                    'attempts','dead_line','creation','update');

    //echoArray($result);
    fputcsv($f, $fields, $delimiter);
    
    // Boucler à travers les enregistrements et les écrire dans le fichier CSV
    foreach($result AS $key=>$arr){
        $lineData = array();
        $lineData[] = $arr['cookie_id'];
        $lineData[] = $arr['cookie_cat_id'];
        $lineData[] = $arr['cookie_quiz_id'];
        $lineData[] = $arr['cookie_uid'];
        $lineData[] = $arr['cookie_uname'];
        $lineData[] = $arr['cookie_readme'];
        $lineData[] = $arr['cookie_email'];
        $lineData[] = $arr['cookie_ip'];
        $lineData[] = $arr['cookie_attempts'];
        $lineData[] = $arr['cookie_dead_line'];
        $lineData[] = $arr['cookie_creation'];
        $lineData[] = $arr['cookie_update'];
        //$lineData[] = $arr[''];
            
        fputcsv($f, $lineData, $delimiter);
    }
 
    fclose($f);
    return $fullName;


}

public function updateEmptyFields($quizId){
    $usershandler = xoops_getHandler('user');
        
    $criteria = new \CriteriaCompo();            
    $criteria->add(new \Criteria('cookie_quiz_id',$quizId, "="));
    $criteria->add(new \Criteria('cookie_uid', 0, ">"));
//    $criteria->add(new \Criteria('cookie_uid', 3, "<>"));

    $criteria2 = new \CriteriaCompo();
    $criteria2->add(new \Criteria('', 0, '=',null,'length(cookie_uname)'));
    $criteria2->add(new \Criteria('', 0, '=',null,'length(cookie_email)'),"OR");
    $criteria->add($criteria2);

    
    $result = $this->getAllCookiesArr($criteria); 
    if(count($result) ==  0) return 0;
    foreach($result AS $key=>$arr){
    //echoArray($arr);
        $uid = $arr['uid'];
        $user = $usershandler->get($uid);
        $cookieObj = $this->get($arr['id']);
        if($user){
          $cookieObj->setVar('cookie_uname', $user->getVar('uname'));
          $cookieObj->setVar('cookie_email', $user->getVar('email'));
          $this->insert($cookieObj);
        }else{
          //le user n'existe plus
          $this->delete($cookieObj);
        }        
    }
 
    return count($result);
}
public function deleteEmptyFields($quizId){

    //on ne supprime pas les anonymes dont l'email est renseigné
    $criteria = new \CriteriaCompo();            
    $criteria->add(new \Criteria('cookie_quiz_id',$quizId, "="));
    $criteria->add(new \Criteria('', 0, '=',null,'length(cookie_uname)'));
    $criteria->add(new \Criteria('', 0, '=',null,'length(cookie_email)'),"OR");
    $this->deleteAll($criteria);

    return true;
}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName($criteria = null, $keyField=null, $nameField = null, $addAll=false, $addNull=false)
    {
        if(!$keyField) $nameField = 'cookie_id';
        if(!$nameField) $nameField = 'cookie_email';
        
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
            //$ret[$key] = $obs[$i]->getVar($nameField) . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");
        
        }
        //echoArray($ret);exit;
        return $ret;
    }
     
} // ------------ FIN DE LA CLASSE ---------------

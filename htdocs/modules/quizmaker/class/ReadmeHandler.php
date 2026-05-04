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
 * Class Object Handler Readme
 */
class ReadmeHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_readme', Readme::class, 'readme_id', 'readme_cat_id');
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
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function exists($i)
	{
		return (parent::get($i, $fields)) ? true : false;
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
	 * Get Count Readme in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountReadme($crAllReadme, $start = 0, $limit = 0, $sort = 'readme_id ASC, readme_cat_id, readme_uid', $order = 'ASC')
	{
		if(!$crAllReadme) $crAllReadme = new \CriteriaCompo();
		$crCountReadme = $this->getReadmeCriteria($crAllReadme, $start, $limit, $sort, $order);
		return parent::getCount($crCountReadme);
	}

	/**
	 * Get All Readme in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllReadme($crAllReadme, $start = 0, $limit = 0, $sort = 'readme_id ASC, readme_cat_id, readme_uid', $order = 'ASC')
	{
		if(!$crAllReadme) $crAllReadme = new \CriteriaCompo();
		$crAllReadme = $this->getReadmeCriteria($crAllReadme, $start, $limit, $sort, $order);
		return parent::getAll($crAllReadme);
	}

	/**
	 * Get Criteria Readme
	 * @param        $crReadme
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getReadmeCriteria($crReadme, $start, $limit, $sort, $order)
	{
		$crReadme->setStart( $start );
		$crReadme->setLimit( $limit );
		$crReadme->setSort( $sort );
		$crReadme->setOrder( $order );
		return $crReadme;
	}
    
   
/* ******************************
 * renvoie la valeur d'un champ en particulier 
 * *********************** */
    public function getReadmeCount($catId, $uid, $field = 'readme_count', $default = 0)

    {
        $sql = "SELECT max(readme_count) AS readmeCount FROM {$this->table}"
             . " WHERE readme_cat_id={$catId} and readme_uid={$uid}";
        
        $rst = $this->db->query($sql);
        //echoArray($rst);
        $arr = $this->db->fetchArray($rst);
        if($arr['readmeCount']){
        //echoArray($arr);
            return $arr['readmeCount'];
        }else{
            return 0;
        }
    }

/* ******************************
 *  todo : verifier la pertinence de cette function : voir getId
 * *********************** */
public function getNewReadme($catId, $uid){
    $readmeObj = $this->create();
	$readmeObj->setVar('readme_creation', \JANUS\getSqlDate());
    $readmeObj->setVar('readme_update', \JANUS\getSqlDate());
		
	$readmeObj->setVar('readme_cat_id', $catId);
    $readmeObj->setVar('readme_uid', $uid);        
    $readmeObj->setVar('readme_count', 0);        
        
	// Insert Data
	if ($readmeHandler->insert($readmeObj)) {
		$newReadmeId = $readmeObj->getNewInsertedIdReadme();
	}else{
		$newReadmeId = 0;
    }
        
	return $newReadmeId;
}
/* ******************************
 *  
 * *********************** */
// public function getId($catName, $create = false, $setPermsToGroups = false){
// global $xoopsDB;
//     //recherche la catégorie par son nom
//     $criteria = new \Criteria("cat_name", $xoopsDB->escape($catName), 'LIKE');
//     $rst = $this->getAll($criteria);
// 
//     if (count($rst) > 0) {
//         $catArr = array_shift($rst);
//         $catId = $catArr->getVar('cat_id');
//         if($setPermsToGroups) $this->setPermsToCurrentGroups($catId);
//     }else{
//         $catId  = 0;        
//     }  
//     //la categorie a été trouvée , retour de l'id
//     if($catId > 0 || $create == false) return $catId;
//     //--------------------------------------------------
//   
// return $catId;    
// }

/* ******************************
 * Incremente weight
 * *********************** */
 public function incremente($catId, $increment = 1){
$field = 'readme_count';
global $xoopsUser;
    
    if($xoopsUser){
      $uid = $xoopsUser->uid();
      $email = $xoopsUser->getVar('email');
    }else{
      $uid = 0;
      $email = '???';
    }
    //$sql = "SET @rank=-{$step};";
    //$result = $this->db->queryf($sql);
    
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('readme_cat_id', $catId));
    $criteria->add(new \Criteria('readme_uid', $uid));
    $countRst = parent::getCount($criteria);

    
    $newDate = \JANUS\getSqlDate();
    if($countRst == 0){
      $sql = "INSERT INTO {$this->table}  (readme_cat_id,readme_uid,readme_email,readme_count,readme_creation,readme_update)"
           . " VALUES ({$catId},{$uid}, '{$email}', 1,'{$newDate}','{$newDate}')";
    }else{
      $sql = "UPDATE {$this->table} SET {$field} = {$field}+{$increment}, readme_update='{$newDate}'"
           . " WHERE readme_cat_id={$catId} and readme_uid={$uid}";    
    }
      
    $result = $this->db->queryf($sql);
}



} // fin de la class

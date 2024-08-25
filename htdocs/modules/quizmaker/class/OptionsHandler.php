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
 * Class Object Handler Options
 */
class OptionsHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_options', Options::class, 'opt_id', 'opt_name');
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
	 * Get Count Options in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountOptions($crAllOptions = null, $start = 0, $limit = 0, $sort = 'opt_id ASC, opt_name', $order = 'ASC')
	{
		if(!$crAllOptions) $crAllOptions = new \CriteriaCompo();
		$crCountOptions = $this->getOptionsCriteria($crAllOptions, $start, $limit, $sort, $order);
		return parent::getCount($crCountOptions);
	}

	/**
	 * Get All Options in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllOptions($crAllOptions = null, $start = 0, $limit = 0, $sort = 'opt_id ASC, opt_name', $order = 'ASC')
	{
		if(!$crAllOptions) $crAllOptions = new \CriteriaCompo();
		$crAllOptions = $this->getOptionsCriteria($crAllOptions, $start, $limit, $sort, $order);
		return parent::getAll($crAllOptions);
	}
/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * $optionsHandler->getListKeyName
 * *********************** */
    public function getAllOptionsArr(&$binMerged = null)
    {$binMerged = array();
    
        $optionsAll = $this->getAllOptions();
        $optionArr = array();
    	foreach(array_keys($optionsAll) as $i) {
            $values = $optionsAll[$i]->getValuesOptions();
    		$optionArr[] = $values;
            $binMerged[$values['id']] = $values['optionsIhm'] | $values['optionsDev']; 
    	}
    //echoArray($binMerged);
    return $optionArr;
    }

	/**
	 * Get Criteria Options
	 * @param        $crOptions
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getOptionsCriteria($crOptions, $start, $limit, $sort, $order)
	{
		$crOptions->setStart( $start );
		$crOptions->setLimit( $limit );
		$crOptions->setSort( $sort );
		$crOptions->setOrder( $order );
		return $crOptions;
	}
    
        //////////////////////////////
    
/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * $optionsHandler->getListKeyName
 * *********************** */
//     public function getListKeyName($short_permtype = 'view_cats', $addAll=false)
//     {global $clPerms;
//         if (!$short_permtype) return $this->getList();
//         //-------------------------------------------------------------
//         $clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'opt_id');
//         $cat = $this->getList($criteriaCatAllowed);
//         if ($addAll) $cat[0] = "(*)";
//         return $cat;
//     }
    
/* ******************************
 * renvoie la valeur d'un champ en particulier 
 * *********************** */
//     public function getValue($catId, $field, $default = null)
// 
//     {
//         $catObj = $this->get($catId);
//         $v  = $catObj->getVar($field);
//         if(!$v) $v = $default;
//         
//         return $v;        
//     }

	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
// 	public static function getPermissionsOld($short_permtype = 'view')
//     {
//         global $xoopsUser;
// 
//         $permtype = sprintf("quizmaker_%s_options", $short_permtype);
//         
//         $tPerm = array();
//         $quizmakerHelper = Helper::getHelper('quizmaker');
//         $moduleHandler = $quizmakerHelper->getModule();
//         $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
//         $gpermHandler = xoops_getHandler('groupperm');
//         $tPerm = $gpermHandler->getItemIds($permtype, $groups, $moduleHandler->getVar('mid'));
// 
//         return $tPerm;
//     }

	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
// 	public function getAllowed($short_permtype = 'view_cats', $criteria = null, $sorted='opt_weight,opt_name,opt_id', $order="ASC")
//     {global $clPerms;
//         $clPerms->addPermissions($criteria, 'view_cats', 'opt_id');
//         
//         if (is_null($criteria)) $criteria = new \CriteriaCompo();
//         $criteria->add(new \Criteria('opt_id',"({$ids})",'IN'));
//         if ($sorted != '') $criteria->setSort($sorted);
//         if ($order  != '') $criteria->setOrder($order);
//         
//         
//         //------------------------------------------------
// 
//         $allEnrAllowed = parent::getAll($criteria);
//         return $allEnrAllowed;
//     }
//     
// 	public function getAllowedArr($short_permtype = 'view', $criteria = null, $sorted='opt_weight,opt_name,opt_id', $order="ASC")
//     {
//         $optionsAll = $this->getAllowed($short_permtype,$criteria,$sorted,$order);
//         $catArr = array();
//         foreach (\array_keys($optionsAll) as $i) {
//             $catArr[$optionsAll[$i]->getVar('opt_id')] = $optionsAll[$i]->getValuesOptions();
//         }
// 
//         return $catArr;
//         
//     }

    //////////////////////////////////

/* ******************************
 * Update weight
 * *********************** */
//  public function updateWeight($opt_id, $action){
//           $currentEnr = $this->get($opt_id); 
//           $opt_weight = $currentEnr->getVar('opt_weight');
//           $opt_parent_id  = $currentEnr->getVar('opt_parent_id');
//           
//          switch ($action){
//             case 'up'; 
//               $sens =  '<';
//               $ordre = "DESC";
//               break;
// 
//             case 'down'; 
//               $sens =  '>';
//               $ordre = "ASC";
//             break;
// 
//             case 'first'; 
//               $sens =  '<=';
//               $ordre = "DESC";
//             break;
// 
//             case 'last'; 
//               $sens =  '>=';
//               $ordre = "ASC";
//             break;
//             
//          }
//          
//         $criteria = new \CriteriaCompo();
//         $criteria->add(new \Criteria('opt_weight', $opt_weight, $sens));
//         
//         // selection du parent ou du groupe des enfants
//         $selectParent = ($opt_parent_id == 0) ? '=' : '>';
//         $criteria->add(new \Criteria('opt_parent_id', 0, $selectParent));
//         
//         $criteria->setSort("opt_weight");
// 		$criteria->setOrder( $ordre );
//         $limit = 0;
//         $start = 0;
//         //$allObjects = $this->getAllQuestions($criteria, $start, $limit, "opt_weight {$ordre}, quest_question {$ordre}, opt_id");
//         $allObjects = $this->getObjects($criteria, true);
//         if(count($allObjects) == 0  ){
//             return true;
//         }
//         
// 
//          switch ($action){
//             case 'up'; 
//             case 'down'; 
//               $key = array_key_first($allObjects);
// //              echo "===> count = " . count($allObjects) . "<br>key={$key}"; 
//               $enr2 = $allObjects[$key]->getValuesOptions();
//               $opt_id2 = $enr2['id'];
//               $opt_weight2 = $enr2['weight'];
//         
//               $tbl = $this->table;
//               $sql = "UPDATE {$tbl} SET opt_weight={$opt_weight2} WHERE opt_id={$opt_id}";
//               $this->db->queryf($sql);
//               
//               $sql = "UPDATE {$tbl} SET opt_weight={$opt_weight} WHERE opt_id={$opt_id2}";
//               $this->db->queryf($sql);
//             break;
// 
//             case 'first'; 
//             case 'last'; 
//               
//                 $keys = array_keys($allObjects);
//               
// //echo "<hr>opt_id = {$opt_id}<br>opt_weight = {$opt_weight}<br>quiz_id = {$quiz_id}<br><pre>" . print_r($keys, true) . "</pre><hr>";              
//               
//               for ($h = 0; $h < count($keys); $h++){
//                 if($h == 0){
//                     $key = array_key_last($allObjects);
//                     $newWeight = $allObjects[$key]->getVar('opt_weight');
//                     $key2update = $keys[$h];
//                 }else{
//                     $key = $keys[$h-1];
//                     $newWeight = $allObjects[$key]->getVar('opt_weight');
//                     $key2update = $keys[$h];
//                 }
//                 $sql = "UPDATE {$this->table} SET opt_weight = {$newWeight} WHERE opt_id = {$key2update}" ;               
//                 $this->db->queryf($sql);
//               }
//               
//             break;
//             
//          }
// 
//          return true;
//  }

/* ******************************
 * renvoie la valeur maximum d'un champ 
 * *********************** */
    public function getMax($field = "opt_id")

    {
        $sql = "SELECT max({$field}) AS valueMax FROM {$this->table}";
        
        $rst = $this->db->query($sql);
        $arr = $this->db->fetchArray($rst);
//        echo print_r($arr,true);
        return $arr['valueMax'];
    }


// function getFirst(){
//     $criteria = \newCriteriacompo();
//     $criteria->setOrder('ASC');
//     $criteria->setSord('name')
//     $criteria->setLimit = 1;
//     $criteria->setStart = 0;
//     $
// }
/* ******************************
 *  
 * *********************** */
// public function getNewCat($name){
//     $optionsObj = $this->create();
// 	$optionsObj->setVar('opt_creation', \JANUS\getSqlDate());
// 		
// 	$optionsObj->setVar('opt_name', $name);
//     $optionsObj->setVar('opt_description', '');        
//     $optionsObj->setVar('opt_weight', 0);
//     $optionsObj->setVar('opt_theme', 'default');
//     $optionsObj->setVar('opt_update', \JANUS\getSqlDate());
// 
//         
// 	// Insert Data
// 	if ($optionsHandler->insert($optionsObj)) {
// 		$newCatId = $optionsObj->getNewInsertedIdOptions();
// 	}else{
// 		$newCatId = 0;
//     }
//         
// 	return $newCatId;
// }
/* ******************************
 *  
 * *********************** */
    public function getId($name, $create = false){
    global $xoopsDB;
    
        $criteria = new \Criteria("opt_name", $xoopsDB->escape($name), 'LIKE');
        $rst = $this->getAll($criteria);
    
        if (count($rst) > 0) {
            $cat = array_shift($rst);
            $catId = $cat->getVar('opt_id');
        }else if($create){
            $cat = $this->getNewCat($name);    
        }else{
            $catId  = 0;        
        }   
    
    return $catId;    
    }


/* ******************************
 *  
 * *********************** */
public function setBitOn($optId, $field, $bitIndex, $newValue = -1)
{
        $binValue = pow(2, $bitIndex);
        if ($newValue == 1){
            $sql = "UPDATE {$this->table} SET {$field} = {$field} | {$binValue}";
        }elseif ($newValue == 0){
            $sql = "UPDATE {$this->table} SET {$field} = {$field} & ~{$binValue}";
        }else{
            $sql = "UPDATE {$this->table} SET {$field} = {$field} ^ {$binValue}";
        }
    
    
    $sql .= " WHERE opt_id={$optId};";
    $ret = $this->db->queryf($sql);
    return $ret;
}

} // fin de la classe

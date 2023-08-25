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

use XoopsModules\Quizmaker;


/**
 * Class Object Handler Categories
 */
class CategoriesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_categories', Categories::class, 'cat_id', 'cat_name');
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
	 * Get Count Categories in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountCategories($start = 0, $limit = 0, $sort = 'cat_id ASC, cat_name', $order = 'ASC')
	{
		$crCountCategories = new \CriteriaCompo();
		$crCountCategories = $this->getCategoriesCriteria($crCountCategories, $start, $limit, $sort, $order);
		return parent::getCount($crCountCategories);
	}

	/**
	 * Get All Categories in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllCategories($start = 0, $limit = 0, $sort = 'cat_id ASC, cat_name', $order = 'ASC')
	{
		$crAllCategories = new \CriteriaCompo();
		$crAllCategories = $this->getCategoriesCriteria($crAllCategories, $start, $limit, $sort, $order);
		return parent::getAll($crAllCategories);
	}

	/**
	 * Get Criteria Categories
	 * @param        $crCategories
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getCategoriesCriteria($crCategories, $start, $limit, $sort, $order)
	{
		$crCategories->setStart( $start );
		$crCategories->setLimit( $limit );
		$crCategories->setSort( $sort );
		$crCategories->setOrder( $order );
		return $crCategories;
	}
    
        //////////////////////////////
    
//     public function getSelectForm($addAll=false, $addNull=false){
// global $xoopsDB;
//     $tList = array();
// 
//          if ($addAll) $tList[0] = "(*)";
// //         if ($addAll) $inpList->addOption(Constants::ALL, _AM_CARTOUCHES_SELECT_ALL);
// //         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);
// 
//     
// 		$categoriesAll = $this->getAllCategories();
// 		foreach(array_keys($categoriesAll) as $i) {
// 
//             $tList[$categoriesAll[$i]->getVar('cat_id')] = $categoriesAll[$i]->getVar('cat_name');
// 		}
// 
//         return $tList;
//}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName(CriteriaElement $criteria = null, $addAll=false, $addNull=false, $short_permtype = 'view')
    {
        if (!$short_permtype) return $this->getList($criteria);
        //-------------------------------------------------------------
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";

         $obs = $this->getAllowed($short_permtype, $criteria = null, $sorted='cat_name,cat_id', $order="ASC");
        
        foreach (array_keys($obs) as $i) {
            $key = $obs[$i]->getVar('cat_id');
            $ret[$key] = $obs[$i]->getVar('cat_name') . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");;
        
        }

        return $ret;
    }
    
/* ******************************
 * renvoie la valeur d'un champ en particulier 
 * *********************** */
    public function getValue($catId, $field, $default = null)

    {
        $catObj = $this->get($catId);
        $v  = $catObj->getVar($field);
        if(!$v) $v = $default;
        
//         $sql = "SELECT {$field} AS fldValue FROM {$this->table} WHERE cat_id = {$catId}";   
//         $rst = $this->db->query($sql);
//         $arr = $this->db->fetchArray($rst);
//         $v = ($arr['fldValue']) ? $arr['fldValue'] : $default;
//echo "<hr>getValue : {$field} = {$v}<hr>";

        return $v;        
    }

	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
	public static function getPermissions($short_permtype = 'view')
    {
        global $xoopsUser;

        $permtype = sprintf("quizmaker_%s_categories", $short_permtype);
        
        $tPerm = array();
        $quizmakerHelper = Helper::getHelper('quizmaker');
        $moduleHandler = $quizmakerHelper->getModule();
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
	public function getAllowed($short_permtype = 'view', $criteria = null, $sorted='cat_weight,cat_name,cat_id', $order="ASC")
    {
        $tPerm = $this->getPermissions($short_permtype);
        $ids = join(',', $tPerm);
        //echo "<hr>===>getAllowed cat :<br>idsCat : {$ids}<hr>";
        //------------------------------------------------
        if (is_null($criteria)) $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('cat_id',"({$ids})",'IN'));
        if ($sorted != '') $criteria->setSort($sorted);
        if ($order  != '') $criteria->setOrder($order);

        $allEnrAllowed = parent::getAll($criteria);
        return $allEnrAllowed;
    }
    
	public function getAllowedArr($short_permtype = 'view', $criteria = null, $sorted='cat_weight,cat_name,cat_id', $order="ASC")
    {
        $categoriesAll = $this->getAllowed($short_permtype,$criteria,$sorted,$order);
        $catArr = array();
        foreach (\array_keys($categoriesAll) as $i) {
            $catArr[$categoriesAll[$i]->getVar('cat_id')] = $categoriesAll[$i]->getValuesCategories();
        }

        return $catArr;
        
    }

    //////////////////////////////////

/* ******************************
 * Update weight
 * *********************** */
 public function updateWeight($cat_id, $action){
          $currentEnr = $this->get($cat_id); 
          $cat_weight = $currentEnr->getVar('cat_weight');
          $cat_parent_id  = $currentEnr->getVar('cat_parent_id');
          
//exit ("===>cat_id = {$cat_id}<br>Action = {$action}");          
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
        $criteria->add(new \Criteria('cat_weight', $cat_weight, $sens));
        
        // selection du parent ou du groupe des enfants
        $selectParent = ($cat_parent_id == 0) ? '=' : '>';
        $criteria->add(new \Criteria('cat_parent_id', 0, $selectParent));
        
        $criteria->setSort("cat_weight");
		$criteria->setOrder( $ordre );
        $limit = 0;
        $start = 0;
        //$allObjects = $this->getAllQuestions($criteria, $start, $limit, "cat_weight {$ordre}, quest_question {$ordre}, cat_id");
        $allObjects = $this->getObjects($criteria, true);
        if(count($allObjects) == 0  ){
            return true;
        }
        

         switch ($action){
            case 'up'; 
            case 'down'; 
              $key = array_key_first($allObjects);
//              echo "===> count = " . count($allObjects) . "<br>key={$key}"; 
              $enr2 = $allObjects[$key]->getValuesCategories();
              $cat_id2 = $enr2['id'];
              $cat_weight2 = $enr2['weight'];
        
              $tbl = $this->table;
              $sql = "UPDATE {$tbl} SET cat_weight={$cat_weight2} WHERE cat_id={$cat_id}";
              $this->db->queryf($sql);
              
              $sql = "UPDATE {$tbl} SET cat_weight={$cat_weight} WHERE cat_id={$cat_id2}";
              $this->db->queryf($sql);
            break;

            case 'first'; 
            case 'last'; 
              
                $keys = array_keys($allObjects);
              
//echo "<hr>cat_id = {$cat_id}<br>cat_weight = {$cat_weight}<br>quiz_id = {$quiz_id}<br><pre>" . print_r($keys, true) . "</pre><hr>";              
              
              for ($h = 0; $h < count($keys); $h++){
                if($h == 0){
                    $key = array_key_last($allObjects);
                    $newWeight = $allObjects[$key]->getVar('cat_weight');
                    $key2update = $keys[$h];
                }else{
                    $key = $keys[$h-1];
                    $newWeight = $allObjects[$key]->getVar('cat_weight');
                    $key2update = $keys[$h];
                }
                $sql = "UPDATE {$this->table} SET cat_weight = {$newWeight} WHERE cat_id = {$key2update}" ;               
                $this->db->queryf($sql);
              }
              
            break;
            
         }

         return true;
 }

/* ******************************
 * renvoie la valeur maximum d'un champ 
 * *********************** */
    public function getMax($field = "cat_id")

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


} // fin de la classe

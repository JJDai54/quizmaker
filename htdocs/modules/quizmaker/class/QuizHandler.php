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
		parent::__construct($db, 'quizmaker_quiz', Quiz::class, 'quiz_id', 'quiz_name');
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
	public function getCountQuiz($crCountQuiz=null, $start = 0, $limit = 0, $sort = 'quiz_id ASC, quiz_cat_id', $order = 'ASC')
	{
        if(!$crCountQuiz)  $crCountQuiz = new \CriteriaCompo();
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
        $ret = parent::getAll($crAllQuiz);
		return $ret;
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
        $criteria->setSort('quiz_name');
        $criteria->setOrder('ASC');
        
        if($short_permtype != '')
            $allAllowed = $this->getAllowed($short_permtype, $criteria);
        else
            $allAllowed = $this->getObjects($criteria, true);

                
        foreach (array_keys($allAllowed) as $i) {
            $key = $allAllowed[$i]->getVar('quiz_id');
            $ret[$key] = $allAllowed[$i]->getVar('quiz_name') . ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "");
            //$ret[$key] =  ((QUIZMAKER_ADD_ID) ? " (#{$key})" : "") . $allAllowed[$i]->getVar('quiz_name');            
        
        }
        
        $ret=array_flip($ret);
        ksort($ret);
        $ret=array_flip($ret);
        
        return $ret;
    }

/* *************************************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */

    public function getKeysByCat($quiz_cat_id, $keyField)

    {
        $ret     = array();

        $criteria = new \CriteriaCompo();
        if($quiz_cat_id > 0){
            $criteria = new \CriteriaCompo(new \Criteria('quiz_cat_id' , $quiz_cat_id, '='));
        }

        $allAllowed = $this->getObjects($criteria, true);

                
        foreach (array_keys($allAllowed) as $i) {
            $key = $allAllowed[$i]->getVar($keyField);
            if(!isset($ret[$key])){
                $ret[$key] = $allAllowed[$i]->getVar('quiz_id');
            }else{
                // le nom existe déjà
            }
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
        
        if (is_int($object)){
           $object = $this->get($object);
        }
        
        
        $quizId = $object->getVar("quiz_id");
        //-----------------------------------------------------
        //suppression des resultats
        $criteria = new \Criteria("result_quiz_id", $quizId, '=');
        $ret = $resultsHandler->deleteAll($criteria);
        
        //-----------------------------------------------------
        $ids = $this->getChildrenIds($quizId);
        if($ids){
          $criteria = new \CriteriaCompo(new \Criteria("answer_quest_id", "({$ids})", 'IN'));
          $ret = $answersHandler->deleteAll($criteria);
        }
        //-----------------------------------------------------
        $criteria = new \CriteriaCompo(new \Criteria("quest_quiz_id", $quizId, '='));
        $ret = $questionsHandler->deleteAll($criteria);
        
        $fld = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $object->getVar('quiz_folderJS');
        Utility::deleteDirectory($fld);
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
 * Change l'etat du champ passer en parametre
 * @$quizId : id du quiz
 * @$field : nom du champ à changer
 * *********************** */
/*
public function setBitOn($quizId, $field, $bitIndex, $newValue = -1)
{
    if (($newValue) < 0){
        $binValue = pow(2, $bitIndex);
        $sql = "UPDATE {$this->table} SET {$field} = {$field} ^ {$binValue}";
    }
    
    $sql .= " WHERE quiz_id={$quizId};";
    $ret = $this->db->queryf($sql);
    return $ret;
}
*/

public function setBitOn($quizId, $field, $bitIndex, $newValue = -1)
{
    if($bitIndex == -1){
        //si bitIndex = -1, change toutes les bit selon $newvalue
        
        if ($newValue == 1){                    //mets tous les bits à 1
            $binValue = pow(2, 16)-1;
            $sql = "UPDATE {$this->table} SET {$field} = {$binValue}";
        }elseif ($newValue == 0){               //mets tous les bits à 0
            $sql = "UPDATE {$this->table} SET {$field} = 0";
        }else{                                  //config definie dans les constante
            //$binValue = pow(2, 16)-1;
            $sql = "UPDATE {$this->table} SET {$field} = {$newValue}";
            //exit("setBitOn => {$newValue}");
        }
    
        
    }else{
        $binValue = pow(2, $bitIndex);
        if ($newValue == 1){
            $sql = "UPDATE {$this->table} SET {$field} = {$field} | {$binValue}";
        }elseif ($newValue == 0){
            $sql = "UPDATE {$this->table} SET {$field} = {$field} & ~{$binValue}";
        }else{
            $sql = "UPDATE {$this->table} SET {$field} = {$field} ^ {$binValue}";
        }
    
        
    }
    
    
    $sql .= " WHERE quiz_id={$quizId};";
    $ret = $this->db->queryf($sql);
    return $ret;
}

/* ******************************
 *  setConfigOptions : defini la configuration IHM et DEV du quiz
 *  
 * *********************** */
public function setConfigOptions($quizId, $newOptionIHM, $newOptionsDev)
{
    $sql = "UPDATE {$this->table} SET quiz_optionsIhm = {$newOptionIHM}, quiz_optionsDev = {$newOptionsDev}";
    $sql .= " WHERE quiz_id={$quizId};";
    $ret = $this->db->queryf($sql);
    //exit($sql);
    return $ret;
}
/* ******************************
 * setBinOptions  : defini la configuration IHM et DEV du quiz
 *  
 * *********************** */
public function setBinOptions($quizId, $optId)
{   
    global $optionsHandler;
    //echo "<hr>setBinOptions : {$quizId}-{$optId}<hr>";
    $optionObj = $optionsHandler->get($optId);
    $quizObj=$this->get($quizId);
    $quizObj->setVar('quiz_optionsIhm', $optionObj->getVar('opt_optionsIhm'));
    $quizObj->setVar('quiz_optionsDev', $optionObj->getVar('opt_optionsDev'));
    $this->insert($quizObj);

    return true;
}

/* ******************************
 * renvoi un jeu de valeurs utilisé dans la liste de l'admin
 * permet une modification rapide des options
 * *********************** */
    public function config_options($quizId, $config = 0)
    {
        switch ($config){
        case 1:
        $tField = array('quiz_showConsigne'         => '1',
                        'quiz_showTimer'            => '0',
                        'quiz_optionsIhm'           => '0',
                        'quiz_optionsDev'           => '0',
                        'quiz_actif'                => '0');
            break;
            
        default :
        $tField = array('quiz_showConsigne'         => '1',
                        'quiz_showTimer'            => '0',
                        'quiz_optionsIhm'           => '0',
                        'quiz_optionsDev'           => '0',
                        'quiz_actif'                => '1');
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
	public function getPermissionsOld($short_permtype = 'view')
    {
        global $xoopsUser, $quizmakerHelper;
        
        $permtype = sprintf("quizmaker_%s_quiz", $short_permtype);
        
        $tPerm = array();
        $quizmakerHelper = Helper::getHelper('quizmaker');
        $moduleHandler = $quizmakerHelper->getModule();
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');
        $tPerm = $gpermHandler->getItemIds($permtype, $groups, $moduleHandler->getVar('mid'));

        return $tPerm;
    }


	/**
     * Fonction getAllQuizAllowed liste les quiz de la categorie en fonction de la date et de actif
     * @param int   $catId	id de la categorie
     * @param string  $sorted 	champs de tri
     * @param string  $order 	ordre de tri
     * @param string  $permName nom de la permission : gpermName)
    * @return array   Liste des quiz
     */
	public function getAllQuizAllowed($catId, $asKeyNameArr=false, $sorted='quiz_weight,quiz_name,quiz_id', $order="ASC", $permName = 'view_cats'){
        global $categoriesHandler, $quizmakerHelper, $clPerms;
        if(!$categoriesHandler) $categoriesHandler = $quizmakerHelper->getHandler('Categories');
        
        $clPerms->addPermissions($criteria, $permName, 'quiz_cat_id');        
        $criteria->add(new \Criteria('quiz_cat_id', $catId, "="));
        //---------------------------------------------------------------
       $now = \JANUS\getSqlDate();
       $crtDatBegin = new \CriteriaCompo();   
       $crtDatBegin->add(new \Criteria('quiz_dateBegin', $now, "<="));
       $crtDatBegin->add(new \Criteria('quiz_dateBeginOk', 0, "="),'OR');
       $criteria->add($crtDatBegin,'AND');   
       
       $crtDatEnd = new \CriteriaCompo();   
       $crtDatEnd->add(new \Criteria('quiz_dateEnd', $now, ">="));
       $crtDatEnd->add(new \Criteria('quiz_dateEndOk', 0, "="),'OR');
       $criteria->add($crtDatEnd,'AND');   
   
        if ($sorted != '') $criteria->setSort($sorted);
        if ($order  != '') $criteria->setOrder($order);
        
        if($asKeyNameArr){
            $allEnrAllowed = parent::getList($criteria);
        }else{
            $allEnrAllowed = parent::getAll($criteria);
        }
        
        return $allEnrAllowed;
    }
    
	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param string   $permtype	Type de permission
     * @return array   $cat		    Liste des catégorie qui correspondent à la permission
     */
	public function getAllowed($short_permtype = 'view_cats', $criteria = null, $sorted='quiz_weight,quiz_name,quiz_id', $order="ASC")
    {
        global $categoriesHandler, $quizmakerHelper, $clPerms;
        if(!$categoriesHandler) $categoriesHandler = $quizmakerHelper->getHandler('Categories');

        $clPerms->addPermissions($criteria, 'view_cats', 'quiz_cat_id');
        
        if (is_null($criteria)) $criteria = new \CriteriaCompo();
        
        $tPerm = $this->getPermissionsOld($short_permtype);
     
        
        //---------------------------------------------------------------

        $idsCat = join(',', $categoriesHandler->getPermissionsOld($short_permtype));
        //echo "<hr>===>getAllowed quiz :<br>idsQuiz : {$idsQuiz}<br>idsCat : {$idsCat}<hr>";
        //------------------------------------------------
        $criteria->add(new \Criteria('quiz_cat_id',"({$idsCat})",'IN'), 'AND');
        
            
       $now = \JANUS\getSqlDate();
       $crtDatBegin = new \CriteriaCompo();   
       $crtDatBegin->add(new \Criteria('quiz_dateBegin', $now, "<="));
       $crtDatBegin->add(new \Criteria('quiz_dateBeginOk', 0, "="),'OR');
       $criteria->add($crtDatBegin,'AND');   
       
       $crtDatEnd = new \CriteriaCompo();   
       $crtDatEnd->add(new \Criteria('quiz_dateEnd', $now, ">="));
       $crtDatEnd->add(new \Criteria('quiz_dateEndOk', 0, "="),'OR');
       $criteria->add($crtDatEnd,'AND');   
   
        if ($sorted != '') $criteria->setSort($sorted);
        if ($order  != '') $criteria->setOrder($order);
        $allEnrAllowed = parent::getAll($criteria);

        return $allEnrAllowed;
    }
    
	/**
     * Fonction qui liste les catégories qui respectent la permission demandée
     * @param int $QuizId  id du quiz
     * @return array   
     */
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
 public function updateWeight($quiz_id, $action){
 $step = 10;
 $fldWeight = 'quiz_weight';
 $fldId = 'quiz_id';
 
    $currentEnr = $this->get($quiz_id); 
    $quiz_cat_id  = $currentEnr->getVar('quiz_cat_id');
    $this->incrementeWeight($quiz_cat_id, 'ASC', $step);
 
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
 
    $this->incrementeWeight($quiz_cat_id, 'ASC', $step);

    return true;
 }

 /* ******************************
 *  purgerImages
 * *********************** */
 public function purgerImages($quiz_id){
 global $questionsHandler, $answersHandler, $xoopsDB;
 $nbImgDeleted = 0;
    
    return $this->get($quiz_id)->purgerImages();
 }

/* ******************************
 *  
 * *********************** */
    public function getId($name, $catId = 0){
    
    
        $criteria = new \CriteriaCompo(new \Criteria("quiz_name", $name, 'LIKE'));
        if ($catId > 0) $criteria->add(new \Criteria("quiz_cat_id", $catId, '='));
        $rst = $this->getAll($criteria);
    
        if (count($rst) > 0) {
            $quiz = array_shift($rst);
            $quizId = $quiz->getVar('quiz_id');
        }else{
            $quizId  = 0;        
        }   
    
    return $quizId;    
    }
    
/****************************************************************************
 * isValid : verifie la validité d'un quiz
 * retour err bool:
 ****************************************************************************/
public function isValid($fullPath){
global $quizUtility;
    
    $filesArr = ['categories.yml','quiz.yml','questions.yml','answers.yml'];
    for($h = 0; $h < count($filesArr); $h++){
        $f1 = "{$fullPath}/{$filesArr[$h]}"; 
        echo "<br>isValid ===> {$f1}<br>";
        //$ok =  file_exists($f1) ;
        //if(!$ok) exit("isValid file not exixts :<br> {$f1}");
        if (!file_exists($f1)) return false;
    }
    echo "<br>isValid ===> ok<br>";

    return true;
   }
   
/****************************************************************************
 * isFolderJSValid : verifie la validité du dossier des JS du quiz
 * retour err bool:
 ****************************************************************************/
public function isFolderJSValid($folderJS){
//echo "<hr>isFolderJSValid : {$folderJS}<br>";    
    $criteria = new \Criteria('quiz_folderJS', $folderJS, '=');
    $count = $this->getCountQuiz($criteria);
//exit ("isFolderJSValid count : {$count}<br>");
    if ($count > 0) return false;
    
    $path =  QUIZMAKER_PATH_UPLOAD . "/quiz-js/{$folderJS}";
    if (is_dir($path)) return false;
//echo "isFolderJSValid : {$path}<hr>";    
    return true;
   }
 
/****************************************************************************
 * getFolderJSValid : renvoie un nom de dossier valid pour les JS du quiz
 * retour err bool:
 ****************************************************************************/
public function getFolderJSValid($folderJS){

    $newFolder = $folderJS;
    while (!$this->isFolderJSValid($newFolder)){
        $newFolder = $folderJS . '-' . rand(10000,99999);
    }
    return $newFolder;
   }
} // Fin de la classe

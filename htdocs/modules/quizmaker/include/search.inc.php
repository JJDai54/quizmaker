<?php
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

//surtout ne pas inclure l'entete qui réinitialise les données
//require  XOOPS_ROOT_PATH . '/header.php';

include_once (XOOPS_ROOT_PATH . "/Frameworks/janus/class/Permissions.php");
xoops_loadLanguage('common', 'quizmaker');
xoops_loadLanguage('main', 'quizmaker');

/**************************************************************
 * 
 * ************************************************************/
function dateIsBetween($dateBegin, $dateEnd, $dateBeginOk = true, $dateEndOk = true, $currentTime = null)
{
    if (is_null($currentTime)) $currentTime = time();
    if (is_string($dateBegin))  $dateBegin = strtotime($dateBegin);
    if (is_string($dateEnd))    $dateEnd   = strtotime($dateEnd);
    
    if ($dateBeginOk && $dateEndOk){
        $ret =  (($currentTime >= $dateBegin) && ($currentTime <= $dateEnd));
    }elseif ($dateBeginOk){
        $ret =  ($currentTime >= $dateBegin);
    }elseif($dateEndOk){
        $ret =  ($currentTime <= $dateEnd);
    }else{
        $ret = true;
    }
    
    
    return ($ret) ? 1 : 0 ;
}
 
/**
 * search callback functions 
 *
 * @param $queryarray 
 * @param $andor 
 * @param $limit 
 * @param $offset 
 * @param $userid 
 * @return mixed $itemIds
 */
function quizmaker_search($queryarray, $andor, $limit, $offset, $userid)
{
$quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
$categoriesHandler = $quizmakerHelper->getHandler('Categories');
$quizHandler = $quizmakerHelper->getHandler('Quiz');
$clPerms = new JanusPermissions('quizmaker');
$clPerms->addPermissions($criteriaCatAllowed, 'view_cats', 'cat_id');
$catAllowed = $categoriesHandler->getList($criteriaCatAllowed);
//echo "<hr>catAllowed<pre>".  print_r($catAllowed, true) .  "</pre><hr>";
$isAdmin = $clPerms->isUserAdmin;

	$ret = array();
	$elementCount = 0;
	if (is_array($queryarray)) {
		$elementCount = count($queryarray);
	}
    if($elementCount == 0) return $ret;

    /* ***************************************************** */
    /*     recherche dans la table quiz                      */
    /* ***************************************************** */
	$crSearch = new \CriteriaCompo();	
    // search in table quiz
	// search keywords
    //echo "<hr><pre>".  print_r($queryarray, true) .  "</pre><hr>";
	if ($elementCount > 0) {
		$crKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$crKeywords->add(new \Criteria('quiz_name', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
			$crKeywords->add(new \Criteria('quiz_author', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
			$crKeywords->add(new \Criteria('quiz_description', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
			$crKeywords->add(new \Criteria('quiz_subject', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
		}
		$crSearch->add( $crKeywords, $andor);
	}
	// search user(s)
    /*
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'quiz_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'quiz_submitter', $userid ), 'OR' );
	}
	if (isset($crUser)) {
		$crSearch->add( $crUser, 'AND' );
	}
    */

	$crSearch->setStart( $offset );
	$crSearch->setLimit( $limit );
	$crSearch->setSort( 'quiz_dateEnd' );
	$crSearch->setOrder( 'DESC' );
	$quizAll = $quizHandler->getAll($crSearch);
    //exit;
	foreach(array_keys($quizAll) as $i) {
        $quizObj = $quizAll[$i];
        $periodeOK = dateIsBetween($quizObj->getVar('quiz_dateBegin'), $quizObj->getVar('quiz_dateEnd'), $quizObj->getVar('quiz_dateBeginOk'), $quizObj->getVar('quiz_dateEndOk'));
        
        //$quizValues = $quizObj->getValuesQuiz();
        if(!$periodeOK) continue;
//         $quizValues['id'];
//         $quizValues['name'];

        $quizId =  $quizObj->getVar('quiz_id');
        $quizName = $quizObj->getVar('quiz_name');
        $catId = $quizObj->getVar('quiz_cat_id');
        $quizDate = strtotime ($quizObj->getVar('quiz_update'));
        $quizSubject = $quizObj->getVar('quiz_subject');
        //if($quizSubject) $quizSubject .= ' => '; 
        if($quizSubject) $quizSubject = _MA_QUIZMAKER_QUIZ_SUBJECT . "  : {$quizSubject} =>"; 
               
        if(!array_key_exists($catId,$catAllowed)) continue;
        if($isAdmin){
			$title = _MA_QUIZMAKER_CATEGORIE . " {$catAllowed[$catId]} [#{$catId}] => {$quizSubject}{$quizName} [#{$quizId}]";
        }else{
			$title = _MA_QUIZMAKER_CATEGORIE . " {$catAllowed[$catId]} => {$quizSubject}{$quizName}";
        }
         
		$ret[] = [
			'image'  => 'assets/icons/16/quiz-1.png',
			'link'   => 'quiz_display.php?op=run&quiz_id=' . $quizObj->getVar('quiz_id'),
			'title'  => $title, 
            'time'   => $quizDate,
			'uid'    => 0
		];
	}
	unset($crKeywords);
	unset($crUser);
	unset($crSearch);

    /* ***************************************************** */
    /*     recherche dans la table categories                      */
    /* ***************************************************** */
	// search in table categories
	// search keywords
    /*
    */
	$crSearch = new \CriteriaCompo();	
	if ($elementCount > 0) {
		$crKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$crKeywords->add(new \Criteria('cat_name', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
			$crKeywords->add(new \Criteria('cat_description', "%{$queryarray[$i]}%" , 'LIKE'), 'OR');
		}
		$crSearch->add( $crKeywords, 'AND' );

        
	}
	// search user(s)
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'cat_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$crUser = new \CriteriaCompo();
		$crUser->add( new \Criteria( 'cat_submitter', $userid ), 'OR' );
	}


	$crSearch->add(new \Criteria('cat_actif', 1, 'AND' )); //todo : remonter l'erreur à Goffy
	if (isset($crKeywords)) {
		$crSearch->add( $crKeywords, 'AND' );
	}
	if (isset($crUser)) {
		$crSearch->add( $crUser, $andor );
	}
	$crSearch->setStart( $offset );
	$crSearch->setLimit( $limit );
	$crSearch->setSort( 'cat_update' );
	$crSearch->setOrder( 'DESC' );
	$categoriesAll = $categoriesHandler->getAll($crSearch);
    
	foreach(array_keys($categoriesAll) as $i) {
        $catObj = $categoriesAll[$i];
        $catId = $catObj->getVar('cat_id');
        $catName = $catObj->getVar('cat_name');
        $catDate = strtotime ($catObj->getVar('cat_update'));
        
        if($isAdmin){
			$title = _MA_QUIZMAKER_CATEGORIE . " : {$catName} [#{$catId}]";
        }else{
			$title = _MA_QUIZMAKER_CATEGORIE . " : {$catName}";
        }
    
		$ret[] = [
			'image'  => 'assets/icons/16/categories.png',
			'link'   => 'quiz.php?cat_id==' . $categoriesAll[$i]->getVar('cat_id'),
			'title'  => $title,
			'time'   => $catDate,
            'uid'   => 0

		];
	}
	unset($crKeywords);
	unset($crUser);
	unset($crSearch);
    /* ******************************************************* */;



    //echo "<hr>===>module : Quizmaker<pre>".  print_r($ret, true) .  "</pre><hr>";
	return $ret;

}

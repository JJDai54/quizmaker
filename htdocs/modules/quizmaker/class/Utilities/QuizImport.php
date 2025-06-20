<?php

namespace XoopsModules\Quizmaker\Utilities;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  quizmaker
 *
 * @package      \module\quizmaker\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use Xmf\Request;
use JANUS;
//include_once XOOPS_ROOT_PATH . "/modules/quizmaker/class/Utility.php";
                            
//$utility = new \XoopsModules\Quizmaker\Utility();

/**
 * Class Utility
 */
trait QuizImport
{
// =========================================================
// ============ Fonctions d'importation des quiz ===========
// =========================================================
/* ***********************

************************** */
public static function quiz_import_sql($questIdsArr, $quizIdFrom, $quizIdTo, $groupTo = ''){
global $quizHandler, $questionsHandler, $answersHandler, $xoopsDB;

$idGroup = 0;
$tblQuiz  =  $xoopsDB->prefix('quizmaker_quuiz');
$tblQuest =  $xoopsDB->prefix('quizmaker_questions');
$tblAns   =  $xoopsDB->prefix('quizmaker_answers');
    
    if($groupTo){
		$questionsObj = $questionsHandler->create();
        $questionsObj->setVar('quest_quiz_id', $quizIdTo);
        $questionsObj->setVar('quest_plugin', 'pageGroup');
        $questionsObj->setVar('quest_question', $groupTo);
        $questionsObj->setVar('quest_weight', $questionsHandler->getMax("quest_weight", $quizIdTo)+10);
        
        
        
		if ($questionsHandler->insert($questionsObj)) {
            $idGroup = $questionsObj->getVar('quest_id');

        }else{
            $idGroup = 0;
        }
        
    }
    
    //remise a 0 du champ flag utilise pour stocker les ancien id
    $sqlQuest = "update {$tblQuest} SET quest_flag=0;";
    $xoopsDB->query($sqlQuest);
    $ids = implode(',', $questIdsArr);
    /*
        //remise a 0 du champ flag utilise pour stocker les ancien id
    $sql = "update {$tblQuest} SET quest_flag = quest_id WHERE quest_id={$quizIdFrom};";
    $xoopsDB->query($sql);
    $sql = "update {$tblAns} SET answer_flag = answer_quest_id WHERE answer_quest_id={$quizIdFrom};";
    $xoopsDB->query($sqlQuest);
    
    */
    //==============================================    
    //critere de selection des questions a dupliquer
    $columns = getColoumnsFromTable('quizmaker_questions', 'quest_id,quest_quiz_id,quest_flag,quest_parent_id', false);
    //dupliquer les quesion dans le quiz de destination
    $sql = "INSERT INTO {$tblQuest} (quest_quiz_id,quest_flag,quest_parent_id,{$columns})" 
         . " SELECT {$quizIdTo},tblFrom.quest_id,{$idGroup},{$columns} FROM {$tblQuest} tblFrom"
         . " WHERE quest_id IN ($ids)";

    echo "<hr>quiz_import_sql - columns: {$columns}<br>{$sql}<hr>";
    $xoopsDB->query($sql);


    //remise a 0 du champ flag utilise pour stocker les ancien id
    $sqlAns = "update {$tblAns} SET answer_flag=0;";
    $xoopsDB->query($sqlAns);        
    
    //dupplication des enr de la table answer avec copie dans flag de l'ancien quest_id
    $columns = getColoumnsFromTable('quizmaker_answers', 'answer_id,answer_quest_id,answer_flag', false);    
    $sql = "INSERT INTO {$tblAns} (answer_quest_id,answer_flag,{$columns})" 
         . " SELECT 999999,answer_quest_id,{$columns} FROM {$tblAns} tblFrom"
         . " WHERE answer_quest_id IN ($ids)";
    echo "<hr>quiz_import_sql - columns: {$columns}<br>{$sql}<hr>";
    $xoopsDB->query($sql);
    //mise a jour du champ ans_ques_id avec le nouvel id de questions   
    $sql = "UPDATE " . $tblAns  . " ta"
         . " LEFT JOIN {$tblQuest}  tq"
         . " ON ta.answer_flag = tq.quest_flag"
         . " SET ta.answer_quest_id = tq.quest_id"
         . " WHERE ta.answer_flag > 0;";
    $ret = $xoopsDB->query($sql);
    
   
    
    //dossier source et destination pour les images
    $quizFrom = $quizHandler->get($quizIdFrom);
    $pathFrom = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $quizFrom->getVar('quiz_folderJS');
    echo "===>quizIdFrom : {$quizIdFrom}<br>===>{$pathFrom}<br>";
    self::quiz_copy_images($pathFrom, $quizIdTo);    
    
}




// =========================================================
// ============ Fonctions d'importation des quiz ===========
// =========================================================
/**************************************************************
 * 
 * ************************************************************/
public static function quiz_import_category($pathSource, $catId = 0)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;
//    exit;
 //echo "===>quiz_import_category : catId = {$catId}<br>"  ; 
    //--------------------------------------------------------
    //Recherche de la catégorie par son nom ou création si $catId == 0
    //--------------------------------------------------------
    if($catId == 0){
        $shortName = "categories";
        $table     = 'quizmaker_' . $shortName;
        //Chargement de du fichier yml
        $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $shortName . '.yml'); 
        $catId = $categoriesHandler->getId($tabledata[0]['cat_name'],false,false);
//echoArray($tabledata,"===>tabledata");
//echo "<hr>found<pre>" . print_r($catFound, true) . "</pre><hr>";
        if ($catId == 0) {
            unset($tabledata['cat_id']);
            \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
            //$catId = $categoriesObj->getNewInsertedIdCategories();
            $catId = $categoriesHandler->getId($tabledata[0]['cat_name'],false,true);
        }   
//echo "<hr>name = {$catName}<br>catId = {$catId}<hr>";
    }
//    exit("quiz_import_category : catId = {$catId} - name = {$tabledata[0]['cat_name']}");
    return $catId;
    
}

/**************************************************************
 * 
 * ************************************************************/
public static function delFiledsObsolettes (&$dataArr)
{
    $numargs = func_num_args();
    //echo "Nombre d'arguments : $numargs \n";
//     if ($numargs >= 2) {
//         echo "Le second argument est : " . func_get_arg(1) . "\n";
//     }
    $arg_list = func_get_args();
    for ($i = 1; $i < $numargs; $i++) {
        $fldName = $arg_list[$i];
        if (isset($dataArr[$fldName])) unset($dataArr[$fldName]);
        //echo "L'argument $i est : " . $arg_list[$i] . "\n";
    }
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_import_quiz($pathSource, $catId, $folderJS=null)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;
    
    // --- Nouvel id pour ce quiz
    // --- ce n'est pas la bonne méthode il faudrait utiliser la méthode de xoopsObjectHandler
    $newQuizId  = $quizHandler->getMax('quiz_id')+1;

echo "<hr>newQuizId : {$newQuizId}<hr>";

    
    // --- Dossier de destination
//     if (!is_dir($pathSource))
//         mkdir($pathSource, 0777, true);
//echo "1===>quizId = {$newQuizId}<br>";
    //--------------------------------------------------------
    // chargement de la table quiz et affectation du nouvel ID
    //--------------------------------------------------------
    $shortName = "quiz";
    $table     = 'quizmaker_' . $shortName;
    $quizHandler->updateAll('quiz_flag',0,null,true);
    
    //lecture du fichier et chargement dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $shortName . '.yml');
//     echo "path import : <hr>{$pathSource}<hr>";
//     echoArray($tabledata);

    //Mise à jour des champs avant importation
    //il ny a normalement qu'un seul quiz, inutile de boucler sur tableData
    $row = $tabledata[0];
    $oldQuizId = $row['quiz_id'];
    $row['quiz_id'] = $newQuizId;
    $row['quiz_cat_id'] = $catId;    
    
    //champs obsolettes , pour import d'ancienne version
    self::delFiledsObsolettes($row,'quiz_binOptions','quiz_onClickSimple',
           'quiz_allowedSubmit','quiz_answerBeforeNext','quiz_allowedPrevious',
           'quiz_useTimer','quiz_showResultAllways','quiz_showReponsesBottom',
           'quiz_showLog','quiz_shuffleQuestions','quiz_showGoodAnswers',
           'quiz_showBadAnswers','quiz_showReloadAnswers','quiz_minusOnShowGoodAnswers',
           'quiz_showResultPopup','quiz_showPlugin','quiz_showAllSolutions',
           'quiz_showScoreMinMax','quiz_showGoToSlide');

    //affectation du nouvel ID
    // stockage de l'ancien ID dans le champs flag pour permettre la mise à jour des enfants   
    $row['quiz_flag'] = $row['quiz_id']; 
       
    //modification du nom du fichier et dossier du quiz pour ne pas surcharger l'original si il existe
    //cette modification consiste juste à ajouter un nombre aléatoir a la fin du nom original
    //il pourra être modifier une fois l'importation terminé
    if(!$folderJS){
        $folderJS = $row['quiz_folderJS'] . "-" . rand(1000, 9999);        
    }
    $row['quiz_folderJS'] = $folderJS;
    //affectation de la nouvelle catégorie pour ce quiz    
    //unset($row['quiz_id']);    
    
    $tabledata[0] = $row;
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
    //echoArray($row, 'quiz_import_quiz',true);


    //correction de la valeur des champs `quiz_optionsIhm` et `quiz_optionsDev`
    //xoops considere que ce sont des chaines alors que ce sont des valeurs binaires
    //du coup par exemple la valeur binaire 95 devient 14645, pas bon du tout
    $quiz = $quizHandler->get($newQuizId);
    $quiz->setVar('quiz_optionsIhm', $row['quiz_optionsIhm']);
    $quiz->setVar('quiz_optionsDev', $row['quiz_optionsDev']);
    $quizHandler->insert($quiz);
    

    return $newQuizId;
}    

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_import_quest($pathSource, $newQuizId, $pluginNameYes= null, $pluginNameNo = null)
{global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;

    if (is_string($pluginNameYes) && $pluginNameYes == 'null') $pluginNameYes = null;  
    if (is_string($pluginNameYes)) $pluginNameYes = array($pluginNameYes);  
    if (is_string($pluginNameNo)) $pluginNameNo = array($pluginNameNo);  
// echoArray($pluginNameYes,'ok');
// echoArray($pluginNameNo, 'exclus');
   
    $questShortName = 'questions';
    $tblQuest     = 'quizmaker_' . $questShortName;

    //Mise a zero du flag pour tous les enregistrement de la table
    $questionsHandler->updateAll('quest_flag', 0, null, true);
    
//     $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
//     $questionsHandler->deleteAll($criteria);

    //lecture du fichier et chargement dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $questShortName . '.yml');
    //modificaion des champs
//$pluginNameYes = array('listboxIntruders1','listboxSortItems','textboxMultiple');    
//$pluginNameNo = array('listboxIntruders1','listboxSortItems','textboxMultiple','pageGroup');    
//echoArray($pluginNameYes,"pluginNameFilter");
    //balayage de toutes les questions
    $newTblData = array(); //table filtrée
    foreach ($tabledata as $index => $row) {
        //$bolok = ( in_array($row['quest_plugin'], $pluginNameYes)) ? 'ok' : 'no';
        //echo "{$bolok}===> {$pluginNameYes} === {$row['quest_plugin']}<br>";    
        if ((is_null($pluginNameYes) ||  in_array($row['quest_plugin'], $pluginNameYes))
        &&  (is_null($pluginNameNo)  || !in_array($row['quest_plugin'], $pluginNameNo))){

            $row['quest_quiz_id'] = $newQuizId;    
 
            
            //champs obsolettes
            if(isset($row['quest_type_question'])){
                if($row['quest_type_question']!='')
                    $row['quest_plugin'] = $row['quest_type_question']    ;
                //dans tous les cas ce champ est obsolette
                self::delFiledsObsolettes($row,'quest_type_question');           
            }
            self::delFiledsObsolettes($row,'quest_minReponse');           
            self::delFiledsObsolettes($row,'quest_type_form');           
            
            //recupe de l'ancien ID dans la champ FLAG
            //il sera utile pour les enfants de la table answers
            //et pour reconstituer les groupe des pagesinfo si ils existent    
            $row['quest_flag'] = $row['quest_id'];    
            unset($row['quest_id']);    
            $newTblData[] = $row;
        }
    }
    //Chargement de la table questions
    \Xmf\Database\TableLoad::loadTableFromArray($tblQuest, $newTblData);
    //affectation du nouvelle quiz_id
    //sleep(2);
    //$criteria = new \criteriaCompo(new \Criteria('quest_flag', 0 , '<>'));
    //$questionsHandler->updateAll('quest_quiz_id', $newQuizId, $criteria, true);
//echo "2b===>quizId = {$newQuizId}<br>";
   
    //--------------------------------------------------------------
    //mise à jour du champ parent_id pour recreer les groupes (plugin = pageGroup)
    //--------------------------------------------------------------
    $criteria = new \criteriaCompo(new \Criteria('quest_quiz_id',  $newQuizId, '='));
    $criteria->add(new \Criteria('quest_flag',  0, '>'));
    $criteria->add(new \Criteria('quest_parent_id',  0, '='));
    $questionsAll = $questionsHandler->getAll($criteria);
//echo "3===>quizId = {$newQuizId}<br>";
    
	foreach(array_keys($questionsAll) as $i) {
	   $newQuestId = $questionsAll[$i]->getVar('quest_id');
	   $oldQuestId = $questionsAll[$i]->getVar('quest_flag');
       
        $criteria = new \criteriaCompo(new \Criteria('quest_parent_id', $oldQuestId , '='));       
        $criteria->add(new \Criteria('quest_quiz_id', $newQuizId , '='));       
        $questionsHandler->updateAll('quest_parent_id', $newQuestId, $criteria, true);       
    }
// echo "quizId = {$newQuizId}<br>{$pathSource}<br>";
// echoArray($tabledata, 'tabledata');    
// echoArray($newTblData, 'newTblData',true);    
    
    
    return $newQuizId;
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_import_answers($pathSource, $newQuizId)
{global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;

    $ansShortName = "answers";
    $table     = 'quizmaker_' . $ansShortName;
    //Mise a zero du flag pour tous les enregistrement de la table
    $answersHandler->updateAll('answer_flag',0, null, true);
    
//     $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
//     $answersHandler->deleteAll($criteria);
    //lecture du fichier dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $ansShortName . '.yml');
    foreach ($tabledata as $index => $row) {
        //champs obsolettes
        //if (isset($tabledata[$index]['answer_bouquet'])) unset($tabledata[$index]['answer_bouquet']);
        self::delFiledsObsolettes($tabledata[$index],'answer_bouquet');
    
        $tabledata[$index]['answer_flag'] = $tabledata[$index]['answer_quest_id'];    
        unset($tabledata[$index]['answer_id']);  //suppression de l'id d'origine pour eviter les doublons  
    }
    //chargement du tableau dans la table
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
    
    //mise à jour du cham answer_quest_id pour recreer le lien avec la table question
    $tblAns = $xoopsDB->prefix($table);  
    $sql = "UPDATE " . $tblAns  . " ta"
         . " LEFT JOIN " . $xoopsDB->prefix('quizmaker_questions') . " tq"
         . " ON ta.answer_flag = tq.quest_flag"
         . " SET ta.answer_quest_id = tq.quest_id"
         . " WHERE ta.answer_flag > 0;";
    $ret = $xoopsDB->queryf($sql);
    echo "<hr>quiz_import_answers : {$sql}<hr>";
    return $ret;
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_copy_images($pathSource, $newQuizId)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;
    
    //--------------------------------------------------------
    // Creation de l'arborescence du quiz et copie du dossier images
    //--------------------------------------------------------
    $quiz = $quizHandler->get($newQuizId);
    $pathDest = QUIZMAKER_PATH_UPLOAD_QUIZ . '/' . $quiz->getVar('quiz_folderJS');    
//echo "<hr>pathSource : {$pathSource}<br>";
//echo "<hr>pathDest   : {$pathDest}<hr>";
    self::create_quiz_arborescense($pathDest);
    if(is_dir($pathSource . '/images'))    
        self::copyFolder ($pathSource . '/images', $pathDest . '/images');     
    \JANUS\FSO\setChmodRecursif($pathDest . '/images', 0777);
    
    //pour finir on supprime les images non référencées dans les réponses 
    // au cas ou la purge n'aurait pas été faite à l'export
    $quizHandler->purgerImages($newQuizId);

    return true;
   
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_importFromYml($pathSource, &$catId = null, &$newQuizId = null, $folderJS=null)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;

echo "<hr>===>pathSource = {$pathSource}<hr>";    
    
    if(!$quizHandler->isValid($pathSource)) return 2;

    //--------------------------------------------------------
    //Recherche de la catégorie par son nom ou création si $catId == 0
    //--------------------------------------------------------
    $catId = self::quiz_import_category($pathSource, $catId );
    
    //--------------------------------------------------------
    //Recherche de la catégorie par son nom ou création si $catId == 0
    //--------------------------------------------------------
    $newQuizId = self::quiz_import_quiz($pathSource, $catId, $folderJS );
    
    //--------------------------------------------------------
    // chargement de la table questions
    //--------------------------------------------------------
    self::quiz_import_quest($pathSource, $newQuizId);

    //--------------------------------------------------------
    // chargement de la table answers
    //--------------------------------------------------------
    self::quiz_import_answers($pathSource, $newQuizId);
    
    //--------------------------------------------------------
    // Creation de l'arborescence du quiz et copie du dossier images
    //--------------------------------------------------------
    self::quiz_copy_images($pathSource, $newQuizId);
//echo "<hr>===>catId = {$catId}<br>newQuizId = {$newQuizId}<hr>";    
    return 0; //return l'erreur si besoin
   
}
/**************************************************************
 * 
 * ************************************************************/
 
public static function quiz_importOnlyQuestFromYml($pathSource, $newQuizId, $pluginNameYes= null, $pluginNameNo = null)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;
    
    //--------------------------------------------------------
    // chargement de la table questions
    //--------------------------------------------------------
    self::quiz_import_quest($pathSource, $newQuizId, $pluginNameYes, $pluginNameNo);

    //--------------------------------------------------------
    // chargement de la table answers
    //--------------------------------------------------------
    self::quiz_import_answers($pathSource, $newQuizId);
    
    //--------------------------------------------------------
    // Creation de l'arborescence du quiz et copie du dossier images
    //--------------------------------------------------------
    self::quiz_copy_images($pathSource, $newQuizId);

    return $newQuizId;
}
/**************************************************************
 * 
 * ************************************************************/
public static function quiz_loadData($quizId)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $xoopsDB;
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
    $name = $quiz->getVar('quiz_folderJS');    
    $path = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}/export/";
    if (!is_dir($path))
        mkdir($path, 0777, true);
    //--------------------------------------------------------
    $questIdList = $quizHandler->getChildrenIds($quizId);    
    //--------------------------------------------------------
       
    $criteria = new \CriteriaCompo(new \Criteria('quiz_id',$quizId,'='));
    $quizHandler->deleteAll($criteria);
    $table     = 'quizmaker_quiz';
    $tabledata = \Xmf\Yaml::readWrapped($path . $table . '.yml');
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
    //--------------------------------------------------------
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
    $questionsHandler->deleteAll($criteria);
    $table     = 'quizmaker_questions';
    $tabledata = \Xmf\Yaml::readWrapped($path . $table . '.yml');
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
//echo "<hr>question<pre>" . print_r($tabledata, true) . "</pre><hr>";
    //--------------------------------------------------------
    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
    $answersHandler->deleteAll($criteria);
    $table     = 'quizmaker_answers';
    $tabledata = \Xmf\Yaml::readWrapped($path . $table . '.yml');
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
   
}

/**************************************************************
 * 
 * ************************************************************/
public static function quiz_getPluginFromYML($pathSource)
{global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;

    $questShortName = 'questions';
    $pluginName = array();

    //lecture du fichier et chargement dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $questShortName . '.yml');

    foreach ($tabledata as $index => $row) {
        if(!isset($pluginName[$row['quest_plugin']]))
            $pluginName[$row['quest_plugin']] = $row['quest_plugin'];
    }

    return $pluginName;
}

}  //fin de la classe

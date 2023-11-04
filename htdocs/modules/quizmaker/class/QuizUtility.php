<?php

namespace XoopsModules\Quizmaker;

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

use XoopsModules\Quizmaker;
use Xmf\Request;
use JJD;
//include_once XOOPS_ROOT_PATH . "/modules/quizmaker/class/Utility.php";
                            
//$utility = new \XoopsModules\Quizmaker\Utility();

/**
 * Class Utility
 */
class QuizUtility extends Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits
    use Common\ServerStats; // getServerStats Trait
    use Common\FilesManagement; // Files Management Trait


/* ************************************************
*
* ************************************************* */
public static function create_quiz_arborescense($path){
   // $path = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$folderName}";
//echo "<hr>{$path}<hr>";  
\JJD\FSO\isFolder($path, true);  
\JJD\FSO\isFolder($path . '/js', true);
\JJD\FSO\isFolder($path . '/images', true);  
  
//     if (!is_dir($path))             mkdir($path, 0777, true);
//     if (!is_dir($path . '/js'))     mkdir($path . '/js', 0777, true);
//     if (!is_dir($path . '/images')) mkdir($path . '/images', 0777, true);
}

/* ************************************************
*
* ************************************************* */
public static function build_quiz($quizId){
global $quizHandler, $questionsHandler, $answersHandler;
///quiz-questions.js
    
    //Au cas ou cela aurait été oublié
    $questionsHandler->incrementeWeight($quizId);
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
    $name = $quiz->getVar('quiz_folderJS');   
    $path = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}";
    self::create_quiz_arborescense($path);     
    
    
    // --- copies du dossier des images si il n'existe pas---
    //copie des images utilisées notamment pour pour les liste a trier
    self::copie_ressources_images(QUIZMAKER_UPLOAD_QUIZ_PATH . '/images');
    \JJD\FSO\setChmodRecursif(QUIZMAKER_UPLOAD_QUIZ_PATH . '/images', 0777);   
     
    // --- Génération du fichier d'option ---
    self::export_options2Jason($quiz, $path);
    
    self::export_consignes($path);    
        
    // --- Génération du fichier de questions ---
    self::export_questions2Jason($quizId, $path, $typesQuestions);
//    self::export_classLoader($typesQuestions, $path);
    
    
    // --- Génération du fichier d'HTML ---
    self::build_quizinline($quiz, QUIZMAKER_UPLOAD_QUIZ_PATH, $name);
    

    // incrementer la version => quiz_build   
    $quiz->setVar('quiz_build', $quiz->getVar('quiz_build') + 1);
    $quizHandler->insert($quiz);
}


/* ************************************************
*
* ************************************************* */
static function copie_ressources_images($pathDest){
    //if (is_dir($pathDest . "images")) return true;
    $pathSource = QUIZMAKER_QUIZ_JS_PATH . "/images";
//             xoops_load('XoopsFile');
//         $folderHandler   = XoopsFile::getHandler('folder');
//             
//     $folderHandler->copy($pathSource,$pathDest);
self:: CopieRep2 ($pathSource,$pathDest) ;
//    exit('copie_ressources_images');
    return true;
}

/****************************************************************************
// copie le contenu du repertoire $orig vers le repertoire $dest en le créant 
// copie tous les sous-reps de manière récursive 
// sous-entend qu'on a les droits d'écriture, bien sûr! 
 ****************************************************************************/
//function CopyRep ($orig,$dest) { return CopieRep ($orig,$dest);}
// public static function CopieRep ($orig, $dest) { 
// //echo "CopieRep<hr>{$orig}<br>{$dest}<hr>";
//   \JJD\FSO\isFolder($orig, true, 0777);   
//   
//   //if (!is_dir($dest)) mkdir ($dest,0777); // à modifier si le rep cible existe déjà
//   $dir = dir($orig); 
// 
//   while ($entry = $dir->read()) { 
//     $pathOrig = "$orig/$entry"; 
//     $pathDest = "$dest/$entry"; 
//     // repertoire ->copie récursive
//     if (is_dir($pathOrig) and (substr($entry,0,1)<>'.')) self::CopieRep ($pathOrig,$pathDest);     
//    // fichier -> copie simple
//    if (is_file($pathOrig) and ($pathDest<>'') and ($fp=fopen($pathOrig,'rb'))) { 
//       $buf = fread($fp,filesize($pathOrig)); 
//       $cop = fopen($pathDest,'ab+'); 
//       fputs ($cop,$buf); 
//       fclose ($cop); 
//       fclose ($fp); 
//     } 
//   } 
//   $dir->close(); 
// } 
public static function CopieRep2 ($source, $dest) { 

  if (!is_dir($dest)) mkdir($dest, 0755);
  foreach (
    $iterator = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
    \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
    
    $dir = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
    if ($item->isDir()) {
        if(!is_dir($dir)) mkdir($dir);
    } else if(!is_dir($dir)){
        copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
    }
  }
}

public static function deleteFolder($dir){
    self::deleteTree($dir);
    rmdir($dir); // Et on le supprime
}
public static function deleteTree($dir){
    foreach(glob($dir . "/*") as $element){
        if(is_dir($element)){
            self::deleteTree($element); // On rappel la fonction deleteTree           
            rmdir($element); // Une fois le dossier courant vidé, on le supprime
        } else { // Sinon c'est un fichier, on le supprime
            unlink($element);
        }
        // On passe à l'élément suivant
    }
}

public static function rmAllDir($strDirectory){
    $handle = opendir($strDirectory);
    while(false !== ($entry = readdir($handle))){
        if($entry != '.' && $entry != '..'){
            if(is_dir($strDirectory.'/'.$entry)){
                self::rmAllDir($strDirectory.'/'.$entry);
            }
            elseif(is_file($strDirectory.'/'.$entry)){
                unlink($strDirectory.'/'.$entry);
            }
        }
    }
    rmdir($strDirectory.'/'.$entry);
    closedir($handle);
}


/* ************************************************
*
* ************************************************* */
public static function build_quizinline($quiz, $path, $name){
global $utility, $xoopsConfig;    

    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $tpl = new \xoopsTpl();
    $rootApp = QUIZMAKER_QUIZ_JS_PATH ;
    $urlApp  = QUIZMAKER_QUIZ_JS_URL  ;
    //----------------------------------------------
    //insertion des CSS
    $tCss = \JJD\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//echo "<hr><pre>CSS : " . print_r($tCss, true) . "</pre><hr>";
    $urlCss = QUIZMAKER_QUIZ_JS_URL. "/css";
    $tpl->assign('urlCss', $urlCss);
    $tpl->assign('allCss', $tCss);
    
    //----------------------------------------------
    //insertion du prototype des tpl
    $urlApp = QUIZMAKER_QUIZ_JS_URL. "";    
    $tpl->assign('prototype', 'slide__prototype.js');
    
    
    //----------------------------------------------
    //insertion des tpl js
    $tTpljs = \JJD\FSO\getFilePrefixedBy($rootApp.'/js/tpl', array('js'), '', false, false,false);
//echo "<hr><pre>TPL-JS <br>: {$rootApp}/js/tpl" . print_r($tTpljs, true) . "</pre><hr>";
    $tpl->assign('allTpljs', $tTpljs);

    //----------------------------------------------
    //insertion du fichier de langue
    $language = $xoopsConfig['language'];
    $langFile = $rootApp . "/js/language/quiz-" . $language . ".js";
    if (!file_exists($langFile)) { //JJDai : peut-etre forcer overwrite
        //self::buildJsLanguage($langFile);
        //$language = english;
        $messagesHandler->buildJsLanguage($language);
    }
    $tpl->assign('urlApp', $urlApp);
    $tpl->assign('language', $language);
    

    //----------------------------------------------
    $quizUrl = QUIZMAKER_UPLOAD_QUIZ_URL . "/{$name}";
    $tpl->assign('quizUrl', $quizUrl);
    $tpl->assign('questions', 'quiz-questions');
    $tpl->assign('options', 'quiz-options');
 

    $tpl->assign('quiz_functions', 'quiz-functions');
    $tpl->assign('quiz_events', 'quiz-events');
    $tpl->assign('quiz_main', 'quiz-main');

    //-------------------------------------------------
    $tpl->assign('outline', true);
    $tpl->assign('quiz_execution', 2);
    $content = $tpl->fetch('db:quizmaker_admin_quiz_inline.tpl' );    
    $fileName =  "{$path}/{$name}/index.html";
    \JJD\FSO\saveTexte2File($fileName, $content);   
    
    //-------------------------------------------------
    $tpl->assign('outline', false);
    $tpl->assign('quiz_execution', 1);
    $content = $tpl->fetch('db:quizmaker_admin_quiz_inline.tpl' );    
    $fileName =  "{$path}/{$name}/index.tpl";
    \JJD\FSO\saveTexte2File($fileName, $content);   
     
//exit;        
}

/* ************************************************
*
* ************************************************* */
public static function export_options2Jason($quiz, $path){
global $categoriesHandler, $quizHandler, $questionsHandler, $answersHandler, $utility;
    $quizValues = $quiz->getValuesQuiz();
    
    if($quizValues['theme'] == ''){
        $theme = $categoriesHandler->getValue($quizValues['cat_id'], 'cat_theme', 'default');
    }else{
        $theme = $quizValues['theme'];
    }
    
    $optionsArr = array();

    $optionsArr['quizId']                   = $quizValues['id'];
    $optionsArr['name']                     = $quizValues['name']; 
    $optionsArr['description']              = $quizValues['description']; 
    $optionsArr['legend']                   = $quizValues['legend'];//"{allType}"
    $optionsArr['theme']                    = $theme;
    $optionsArr['onClickSimple']            = $quizValues['onClickSimple'];
    $optionsArr['answerBeforeNext']         = $quizValues['answerBeforeNext'];
    $optionsArr['allowedPrevious']          = $quizValues['allowedPrevious'];
    $optionsArr['shuffleQuestions']         = $quizValues['shuffleQuestions'];
    $optionsArr['showConsigne']             = $quizValues['showConsigne'];
    $optionsArr['useTimer']                 = $quizValues['quiz_useTimer'];
    $optionsArr['showGoodAnswers']          = $quizValues['showGoodAnswers'];
    $optionsArr['showBadAnswers']           = $quizValues['showBadAnswers'];
    $optionsArr['minusOnShowGoodAnswers']   = $quizValues['minusOnShowGoodAnswers'];
    $optionsArr['showReloadAnswers']        = $quizValues['showReloadAnswers'];
    $optionsArr['showGoToSlide']            = $quizValues['showGoToSlide'];
    $optionsArr['allowedSubmit']            = $quizValues['allowedSubmit'];
    $optionsArr['showReponsesBottom']       = $quizValues['showReponsesBottom'];
    $optionsArr['showScoreMinMax']          = $quizValues['showScoreMinMax'];
    $optionsArr['showResultPopup']          = $quizValues['showResultPopup'];  
    $optionsArr['showResultAllways']        = $quizValues['showResultAllways'];
    $optionsArr['showLog']                  = $quizValues['quiz_showLog'];
    $optionsArr['build']                    = $quizValues['quiz_build'];
    $optionsArr['folderJS']                 = $quizValues['folderJS'];
    $optionsArr['url']                      = QUIZMAKER_UPLOAD_QUIZ_URL;
    $optionsArr['urlMain']                  = QUIZMAKER_QUIZ_JS_URL;
    $optionsArr['execution']                = $quizValues['actif'];
    $optionsArr['showResultAllways']        = $quizValues['showResultAllways'];
          
    //Utiliser pour le dev pas utile de mettre ces infos en base
    $optionsArr['showTypeQuestion']         = $quizValues['showTypeQuestion'];
    $optionsArr['devOptions']               = $quizValues['devOptions'];
    $optionsArr['mode']                     = 0;

    //---------------------------------------------------------
//echo "<hr><pre>optionsArr : " . print_r($optionsArr, true) . "</pre><hr>";
//echo json_encode($optionsArr);
 
    $exp ="var quiz = JSON.parse(`" .  json_encode($optionsArr) . "`);";
    $exp = str_replace('"', '\"', $exp);
//     $exp = str_replace("\n", '<br>', $exp);
    
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
    $exp = utf8_encode($exp);
    
    $exp = utf8_encode($exp);
    $fileName = $path . "/js/quiz-options.js";
    
//    echo "Export ===>{$path}<br>";
//    echo "{$exp}";
    \JJD\FSO\saveTexte2File($fileName, $exp);

//exit;

}
/* ************************************************
*
* ************************************************* */
public static function export_questions2Jason($quizId, $path, &$typesQuestions){
global $quizHandler, $questionsHandler, $answersHandler, $utility,$type_questionHandler;
    
    $questionsArr = array();
    $typesQuestions = array();
    
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
    $criteria->add(new \Criteria('quest_visible', 1, '='));
    $criteria->add(new \Criteria('quest_actif', 1, '='));
    $criteria->setsort("quest_weight");
    $criteria->setOrder("ASC");
    $questions = $questionsHandler->getObjects($criteria);
    foreach (array_keys($questions) as $i) {
        $values=$questions[$i]->getValuesQuestions();
        $clTypeQuestion = $type_questionHandler->getTypeQuestion($values['type_question']);
        $typesQuestions[] = $values['type_question'];  
              
        $tQuest = array();
        $tQuest['quizId']         = $quizId;
        $tQuest['questId']        = $values['quest_id'];
        $tQuest['type']           = $values['type_question'];
        $tQuest['typeQuestion']   = $values['type_question'];
        $tQuest['typeForm']       = $values['typeForm'];
        $tQuest['question']       = self::sanitise($values['quest_question']);
        $tQuest['identifiant']    = self::sanitise($values['identifiant']);
 
        //$tQuest['options']        = json_decode($values['quest_options'],true);    
        $tQuest['options']        = json_decode($values['options'],true);    
        $tQuest['optionsDefault'] =  $clTypeQuestion->optionsDefaults;    
/*
if($values['options'] != $values['quest_options']) {
echo "<hr><pre>{$values['quest_id']} - options : {$values['options']}<br>quest_options : {$values['quest_options']}</pre><hr>";
for($h=0; $h<strlen($values['options']); $h++){
    echo $values['options'][$h] . '-' . ord($values['options'][$h]) . '-' . $values['quest_options'][$h] . '-' . ord($values['quest_options'][$h]) . '<br>';
}
exit;        
}
*/
        $tQuest['comment1']       = self::sanitise($values['quest_comment1']);
        $tQuest['explanation']    = self::sanitise($values['quest_explanation']);
        $tQuest['consigne']       = self::sanitise($values['quest_consigne'],0,'UTF-8');
        //echo "<hr><pre>{$tQuest['consigne']}</pre><hr>" ;
        $tQuest['learn_more']     = self::sanitise($values['quest_learn_more']);
        $tQuest['see_also']       = self::sanitise($values['quest_see_also']);
        $tQuest['image']          = self::sanitise($values['quest_image']);
        $tQuest['points']         = $values['points'];
        $tQuest['numbering']      = $values['numbering'];
//        $tQuest['shuffleAnswers'] = $values['shuffleAnswers'];
        $tQuest['isQuestion']     = $values['isQuestion'];
        $tQuest['timer']          = $values['timer'];
        $tQuest['timestamp']      = date("H:i:s");
    
        $tQuest['answers']        = self::exportAnswers2Jason($values['id']);
        $questionsArr[] = $tQuest;
//echo "<hr>tQuest<pre>" . print_r($values, true) . "</pre><hr>";
   
    }
//exit;
   //echo "<hr>tQuest<pre>" . print_r($questionsArr, true) . "</pre><hr>";exit;
   //echo "<hr>tQuest<pre>" . json_encode($questionsArr)  . "</pre><hr>";exit;
    $exp ="var myQuestions = JSON.parse(`" .  json_encode($questionsArr) . "`);";
     $exp = str_replace('"', '\"', $exp);
//     $exp = str_replace("\n", '<br>', $exp);
    
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
    
    $exp = utf8_encode($exp);
    //$exp ="var myQuestions = JSON.parse('" .  json_encode($questionsArr) . "');";
    //$path = QUIZMAKER_UPLOAD_PATH . "/quiz-questions-01.js";
    //$path = QUIZMAKER_QUIZ_JS_PATH . "/quiz-js/data/quiz-questions-01.js";
//    $path = QUIZMAKER_UPLOAD_PATH . "/quiz-js/togodo/quiz-questions.js";
    $fileName = $path . "/js/quiz-questions.js";
    
//    echo "Export ===>{$path}<br>";
//    echo "{$exp}";
    \JJD\FSO\saveTexte2File($fileName, $exp);
//   exit;
//json_encode
}

/* ************************************************
*
* ************************************************* */
public static function exportAnswers2Jason($questId){
global $quizHandler, $questionsHandler, $answersHandler;

    $answersArr = array();
    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id', $questId, '='));
    //$criteria->add(new \Criteria('answer_proposition', '', '<>'));
    
    $criteria->setsort("answer_weight");
    $criteria->setOrder("ASC");
    $answers = $answersHandler->getObjects($criteria);
    foreach (array_keys($answers) as $i) {
        $values = $answers[$i]->getValuesAnswers();
        $tVals = array();
        if(!$values['proposition']) continue;
        $tVals['answerId']      = $values['answer_id'];
        $tVals['proposition']   = self::sanitise($values['proposition']);
        //$tVals['reponse']       = 0;//$values[''];
        $tVals['inputs']        = $values['inputs'];
        $tVals['points']        = $values['points'];
        $tVals['caption']       = self::sanitise(($values['caption']) ? $values['caption'] : "");
        $tVals['color']         = $values['color'];
        $tVals['background']    = $values['background'];
        $tVals['image']         = ($values['image']) ? $values['image'] : "";
        $tVals['group']         = $values['group'];
        
        $answersArr[] = $tVals;
   
    }
    return $answersArr;    
}

/* ************************************************
*
* ************************************************* */
public static function export_consignes($path){
global $type_questionHandler;
    
    $allPlugins =  $type_questionHandler->getAllPlugins();
    
    $consignes = array();
    foreach($allPlugins as $key=>$plugin){
        $consignes[$plugin['type']] = $plugin['consigne'];
    }
    echoArray($consignes);
    
    $exp ="var quiz_consignes = JSON.parse(`" .  json_encode($consignes) . "`);";  
    $exp = str_replace('"', '\"', $exp);
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
    $exp = utf8_encode($exp);
      
    $exp = utf8_encode($exp);
    $fileName = $path . "/js/quiz-consignes.js";
    \JJD\FSO\saveTexte2File($fileName, $exp);
   
//exit ('export_consignes : ' . $fileName);    

}


/* ***********************

************************** */
public static function getNewBtn($caption, $op, $img,  $title ){
/*
<div class="xo-buttons">
<a class="ui-corner-all tooltip" href="questions.php?op=new" title="Add New Questions"><img src="http://xmodules.jubile.fr/Frameworks/moduleclasses/icons/32/add.png" title="" alt="">Add New Questions</a>
&nbsp;</div>
*/

$html = <<<__HTML__
<a class="ui-corner-all tooltip" title="{$title}" onclick="document.quizmaker_select_filter.op.value='{$op}';document.quizmaker_select_filter.submit();">
<img src="{$img}" title="" alt="">{$caption}</a>
&nbsp;
__HTML__;

    return $html;

}




public static function exportQuiz($quizId){
global $quizHandler;
        //suppression des images non référencées dans les réponses
        $quizHandler->purgerImages($quiz_id);
        
        $quiz = $quizHandler->get($quizId);
        $folder = $quiz->getVar('quiz_folderJS');    
        $name = $folder . '_' . date("Y-m-d_H-m-s");    
        self::saveDataKeepId($quizId);
        
\JJD\FSO\isFolder(QUIZMAKER_UPLOAD_EXPORT_PATH, true);        
        $sourcePath = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$folder}/export/";
        $outZipPath = QUIZMAKER_UPLOAD_EXPORT_PATH . "/{$name}.zip";
        $outZipUrl = QUIZMAKER_UPLOAD_EXPORT_URL . "/{$name}.zip";
        
        //\JJD\zipSimpleDir($sourcePath, $outZipPath);   
        \JJD\ZipReccurssiveDir($sourcePath, $outZipPath);   
chmod ($outZipPath , 0666);

		$GLOBALS['xoopsTpl']->assign('download', 1);        
		$GLOBALS['xoopsTpl']->assign('href', $outZipUrl);        
		$GLOBALS['xoopsTpl']->assign('delai', 2000);        
		$GLOBALS['xoopsTpl']->assign('name', $name);        
//exit;
}

/**************************************************************
 * 
 * ************************************************************/
public static function saveDataKeepId($quizId)
{
    global $xoopsConfig, $quizHandler, $xoopsDB;
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
//exit;    
    $name = $quiz->getVar('quiz_folderJS');    
    $path = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/export/";
    if (!is_dir($path))
        mkdir($path, 0777, true);
    //----------------------------------------------------
    $criteria = new \CriteriaCompo(new \Criteria('quiz_id',$quizId,'='));
    $shortName = 'quiz';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //-----------------------------------------------------    
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
    $shortName = 'questions';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //--------------------------------------------
    $questIdList = $quizHandler->getChildrenIds($quizId);
//echo "<hr>{$questIdList}";
    $shortName = 'answers';
    $tbl = 'quizmaker_' . $shortName;

    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //categorie
    $criteria = new \CriteriaCompo(new \Criteria('cat_id',$quiz->getVar('quiz_cat_id'),'='));
    $shortName = 'categories';
    $tbl = 'quizmaker_' . $shortName;
    \Xmf\Database\TableLoad::saveTableToYamlFile($tbl, $path . $shortName . '.yml', $criteria);
    
    //----------------------------------------------------
    //copie du dossier des images
    //\JJD\FSO\isFolder(QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/images", true2);
    $pathSource = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/images/";
    //\JJD\FSO\setChmodRecursif(QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/images", 0777);
    \JJD\FSO\setChmodRecursif($path, 0777);
    self:: CopieRep2 ($pathSource,$path . '/images/') ;
    //exit;
}
/**************************************************************
 * 
 * ************************************************************/
public static function import_quiz($pathSource, $catId = 1)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $categoriesHandler, $xoopsDB;
//exit;    
    //--------------------------------------------------------
    //Recherche de la catégorie par son nom ou création si $catId == 0
    //--------------------------------------------------------
    if($catId == 0){
        $shortName = "categories";
        $table     = 'quizmaker_' . $shortName;
        $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $shortName . '.yml');      
//echo "<hr>categories<pre>" . print_r($tabledata, true) . "</pre><hr>";
        $catName = $tabledata[0]['cat_name'];
      
        $criteria = new \Criteria("cat_name", $catName, '=');
        $catFound = $categoriesHandler->getAll($criteria);
//echo "<hr>found<pre>" . print_r($catFound, true) . "</pre><hr>";
        
        if (count($catFound) > 0) {
            $cat = array_shift($catFound);
            $catId = $cat->getVar('cat_id');
        }else{
            $catId  = $categoriesHandler->getMax()+1;        
            $tabledata[0]['cat_id'] = $catId;
            \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
        }   
//        echo "<hr>name = {$catName}<br>catId = {$catId}<hr>";

    }
    
    
    
 
    
    // --- Nouvel id pour ce quiz
    // --- ce n'est pas la bonne méthode il faudrait utiliser la méthode de xoopsObjectHandler
    $newQuizId  = $quizHandler->getMax('quiz_id')+1;
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
//exit;    
//    $name = $quiz->getVar('quiz_folderJS');    
//    $pathSource = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/export/";



    
    // --- Dossier de destination
//     if (!is_dir($pathSource))
//         mkdir($pathSource, 0777, true);
    //--------------------------------------------------------
    // chargement de la table quiz et affectation du nouvel ID
    //--------------------------------------------------------
    $shortName = "quiz";
    $table     = 'quizmaker_' . $shortName;
    $quizHandler->updateAll('quiz_flag',0);
    
    //lecture du fichier et chargement dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $shortName . '.yml');
//     echo "path import : <hr>{$pathSource}<hr>";
//     echoArray($tabledata);

    //Mise à jour des champs avant importation
    foreach ($tabledata as $index => $row) {

        //champs obsolettes
        if (isset($tabledata[$index]['quiz_binOptions'])) unset($tabledata[$index]['quiz_binOptions']);
        if (isset($tabledata[$index]['quiz_onClickSimple'])) unset($tabledata[$index]['quiz_onClickSimple']);

        //affectation du nouvel ID
        $tabledata[$index]['quiz_id'] = $newQuizId;
        // stockage de l'ancien ID dans le champs flag pour permettre la mise à jour des enfants   
        $tabledata[$index]['quiz_flag'] = $tabledata[$index]['quiz_id'];    
        //modification du nom du fichier et dossier du quiz pour ne pas surcharger l'original si il existe
        //cette modification consiste juste à ajouter un nombre aléatoir a la fin du nom original
        //il pourra être modifier une fois l'importation terminé
        $quizFileName = $tabledata[$index]['quiz_folderJS'] . "-" . rand(1000, 9999);        
        $tabledata[$index]['quiz_folderJS'] = $quizFileName;
        //affectation de la nouvelle catégorie pour ce quiz    
        $tabledata[$index]['quiz_cat_id'] = $catId;    
        //unset($tabledata[$index]['quiz_id']);    
    }
    \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
 //exit;  
//     $criteria = new \criteriaCompo(new \Criteria('quiz_flag', $quizId, "="));
//     $ids = $quizHandler->getIds($criteria);
//     $newQuizId = $ids[0];    
//echo "<hr>" . implode('-', $ids) . "<br>New newQuizId = {$newQuizId}<hr>";    

    //--------------------------------------------------------
    // chargement de la table questions
    //--------------------------------------------------------
    $questShortName = 'questions';
    $tblQuest     = 'quizmaker_' . $questShortName;

    //Mise a zero du flag pour tous les enregistrement de la table
    $questionsHandler->updateAll('quest_flag',0);
    
//     $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id',$quizId,'='));
//     $questionsHandler->deleteAll($criteria);

    //lecture du fichier et chargement dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $questShortName . '.yml');
    //modificaion des champs
    foreach ($tabledata as $index => $row) {
        //champs obsolettes
        if (isset($tabledata[$index]['quest_minReponse'])) unset($tabledata[$index]['quest_minReponse']);
            
        //recupe de l'ancien ID dans la champ FLAG
        //il sera utile pour les enfaant de la table answers
        //et pour reconstituer les groupe des pagesinfo si ils existent    
        $tabledata[$index]['quest_flag'] = $tabledata[$index]['quest_id'];    
        unset($tabledata[$index]['quest_id']);    
    }
    //Chargement de la table questions
    \Xmf\Database\TableLoad::loadTableFromArray($tblQuest, $tabledata);
    //affectation du nouvelle quiz_id
    $criteria = new \criteriaCompo(new \Criteria('quest_flag', 0 , '<>'));
    $questionsHandler->updateAll('quest_quiz_id', $newQuizId, $criteria);
    
    //--------------------------------------------------------------
    //mise à jour du champ parent_id pour recreer les groupes (pageinfo)
    //--------------------------------------------------------------
    $criteria = new \criteriaCompo(new \Criteria('quest_quiz_id',  $newQuizId, '='));
    $criteria->add(new \Criteria('quest_parent_id',  0, '='));
    $questionsAll = $questionsHandler->getAll($criteria);
    
	foreach(array_keys($questionsAll) as $i) {
	   $newQuestId = $questionsAll[$i]->getVar('quest_id');
	   $oldQuestId = $questionsAll[$i]->getVar('quest_flag');
       
        $criteria = new \criteriaCompo(new \Criteria('quest_parent_id', $oldQuestId , '='));       
        $criteria->add(new \Criteria('quest_quiz_id', $newQuizId , '='));       
        $questionsHandler->updateAll('quest_parent_id', $newQuestId, $criteria);       
    }


    //--------------------------------------------------------
    // chargement de la table answers
    //--------------------------------------------------------
    $ansShortName = "answers";
    $table     = 'quizmaker_' . $ansShortName;
    //Mise a zero du flag pour tous les enregistrement de la table
    $answersHandler->updateAll('answer_flag',0);
    
//     $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id',"({$questIdList})",'in'));
//     $answersHandler->deleteAll($criteria);
    //lecture du fichier dans un tableau
    $tabledata = \Xmf\Yaml::readWrapped($pathSource . "/". $ansShortName . '.yml');
    foreach ($tabledata as $index => $row) {
        //champs obsolettes
        if (isset($tabledata[$index]['answer_bouquet'])) unset($tabledata[$index]['answer_bouquet']);

        $tabledata[$index]['answer_flag'] = $tabledata[$index]['answer_quest_id'];    
        unset($tabledata[$index]['answer_id']);    
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
    $ret = $xoopsDB->query($sql);
    
    //--------------------------------------------------------
    // Creation de l'arborescence qu quiz et copie du dossier images
    //--------------------------------------------------------
    $pathDest = QUIZMAKER_UPLOAD_QUIZ_PATH . '/' . $quizFileName;    
    echo "<hr>pathDest : {$pathDest}<hr>";
    self::create_quiz_arborescense($pathDest);
    if(is_dir($pathSource . '/images'))    
        self::CopieRep2 ($pathSource . '/images', $pathDest . '/images');     
    \JJD\FSO\setChmodRecursif($pathDest . '/images', 0777);
    
    
    
    //exit;
    //pour finir on supprime les images non référencées dans les réponses 
    // au cas ou la purge n'aurait pas été faite à l'export
    $quizHandler->purgerImages($quiz_id);
//exit;
    return $newQuizId;
/*
*/    
   
}
/**************************************************************
 * 
 * ************************************************************/
public static function loadData($quizId)
{
    global $xoopsConfig, $quizHandler, $questionsHandler, $answersHandler, $xoopsDB;
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
//exit;    
    $name = $quiz->getVar('quiz_folderJS');    
    $path = QUIZMAKER_UPLOAD_QUIZ_PATH . "/{$name}/export/";
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
public static function submitQuizVerif($quizId, $uid, $uname)
{
        $ip = \Xmf\IPAddress::fromRequest()->asReadable();
        $criteria = new \CriteriaCompo(new \criteria('result_ip', $ip, "="));
        $criteria->add(new \criteria('result_quiz_id', $quizId, "="));
        $resultsCount = $resultsHandler->getCount($criteria);
        $attempt_max = 3;
        if ($resultsCount >= $attempt_max){
			redirect_header("categories.php?op=list&quiz_id={$quizId}&sender=", 3, _MA_QUIZMAKER_STILL_ANSWER);
        }        
		

}

    /**
     * @param \Xmf\Module\Helper $quizmakerHelper
     * @param array|null         $options
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
//      Avertissement: Declaration of XoopsModules/Quizmaker/QuizUtility::
//      getEditor($caption, $name, $value, $description = '', $newOptions = NULL, $quizmakerHelper = NULL) 
//      should be compatible with XoopsModules/Quizmaker/Utility::getEditor($quizmakerHelper = NULL, $options = NULL)
//       dans le fichier /modules/quizmaker/class/QuizUtility.php ligne 
    public static function getEditor2($caption, $name, $value, $description = "", $newOptions = null, $quizmakerHelper = null)
    {
        if ($quizmakerHelper === null) $quizmakerHelper = \XoopsModules\Quizmaker\Helper::getInstance();
        $options           = [];
        $options['name']   = $name;
        $options['value']  = $value;
        $options['rows']   = 10;
        $options['cols']   = '100%';
        $options['width']  = '100%';
        $options['height'] = '300px';
        $options['editor'] = $quizmakerHelper->getConfig('editor_admin');
        
        if($newOptions !== null){
          $keys = array('rows','cols','width','height');
          for ($h=0; $h < count($keys); $h++){
                $key = $keys[$h];
                if (isset($newOptions[$key]) )  $options[$key] = $newOptions[$key];
          }
        }

        $isAdmin = $quizmakerHelper->isUserAdmin();

        if (class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor($caption, $name, $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor($caption, $name, $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea($caption, $name, $options['value'], '100%', '100%');
        }

        //        $form->addElement($descEditor);
        if($description) $descEditor->setDescription($description);
        return $descEditor;
    }
    
/**********************************************************************
 * 
 **********************************************************************/
    public static function loadTextFile ($fullName){


  if (!is_readable($fullName)){return '';}
  
  $fp = fopen($fullName,'rb');
  $taille = filesize($fullName);
  $content = fread($fp, $taille);
  fclose($fp);
  
  return $content;

}



////////////////////////////////////////////////////////////////
/* ************************************************
*
* ************************************************* */
public static function sanitise($exp){
    //$exp = str_replace("'","_",$exp);
    return html_entity_decode($exp,ENT_QUOTES);
}
/* ************************************************
*
* ************************************************* */
public static function sanitiseFileName($str, $replaceBlankBy = '_'){
//echo "nom du fichier avant : {$str}<br>";
   $str = self::minusculesSansAccents($str);

//echo "nom du fichier après : {$str}<br>";

   return $str;
}

/////////////////////////////////////
public static function minusculesSansAccents($str, $replaceBlankBy = '_'){
    //$str = mb_strtolower($str, 'UTF-8');
    $str = utf8_decode($str);
    $str = str_replace(
			array(
				'à', 'â', 'ä', 'á', 'ã', 'å',
				'î', 'ï', 'ì', 'í', 
				'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
				'ù', 'û', 'ü', 'ú', 
				'é', 'è', 'ê', 'ë', 
				'ç', 'ÿ', 'ñ',
				'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
				'Î', 'Ï', 'Ì', 'Í', 
				'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø', 
				'Ù', 'Û', 'Ü', 'Ú', 
				'É', 'È', 'Ê', 'Ë', 
				'Ç', 'Ÿ', 'Ñ'
			),
			array(
				'a', 'a', 'a', 'a', 'a', 'a', 
				'i', 'i', 'i', 'i', 
				'o', 'o', 'o', 'o', 'o', 'o', 
				'u', 'u', 'u', 'u', 
				'e', 'e', 'e', 'e', 
				'c', 'y', 'n', 
				'A', 'A', 'A', 'A', 'A', 'A', 
				'I', 'I', 'I', 'I', 
				'O', 'O', 'O', 'O', 'O', 'O', 
				'U', 'U', 'U', 'U', 
				'E', 'E', 'E', 'E', 
				'C', 'Y', 'N'
			),$str);
  
   if ($replaceBlankBy) $str = strtr($str," ", $replaceBlankBy);

return $str;
}


 

}  //fin de la classe
